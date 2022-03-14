<?php

namespace App\Wmsuccess\Service\UserBundle\Controller;

use App\Wmsuccess\Service\MetierManagerBundle\Metier\ServiceMetierWmsFile;
use App\Wmsuccess\Service\MetierManagerBundle\Metier\ServiceMetierUtils;
use App\Wmsuccess\Service\UserBundle\Entity\WmsUser;
use App\Wmsuccess\Service\UserBundle\Form\WmsUserType;
use App\Wmsuccess\Service\UserBundle\Manager\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class UserController
 */
class UserController extends AbstractController
{
    private $_utils_manager;
    private $_user_manager;
    private $_translator;

    public function __construct(ServiceMetierUtils $_utils_manager, UserManager $_user_manager, TranslatorInterface $_translator)
    {
        $this->_utils_manager = $_utils_manager;
        $this->_user_manager  = $_user_manager;
        $this->_translator    = $_translator;
    }

    /**
     * Ajax list user
     * @param \Symfony\Component\HttpFoundation\Request $_request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAjax(Request $_request)
    {
        $_page        = $_request->query->get('start');
        $_nb_max_page = $_request->query->get('length');
        $_search      = $_request->query->get('search')['value'];
        $_order_by    = $_request->query->get('order_by');
        $_users       = $this->_user_manager->getListUsers($_page, $_nb_max_page, $_search, $_order_by);

        return new JsonResponse([
            'recordsTotal'    => $_users[1],
            'recordsFiltered' => $_users[1],
            'data'            => array_map(function ($val) {
                return array_values($val);
            }, $_users[0])
        ]);
    }

    /**
     * Afficher tout les utilisateurs
     * @return Render page
     */
    public function index()
    {
        return $this->render('@User/User/index.html.twig');
    }

    /**
     * Modification utilisateur
     *
     * @param WmsUser $_user
     * @param Request $_request
     *
     * @return Render page
     */
    public function edit(WmsUser $_user, Request $_request)
    {
        $_edit_form = $this->createFormByMethod($_user, 'edit');
        $_edit_form->handleRequest($_request);

        if ($_edit_form->isSubmitted() && $_edit_form->isValid()) {
            // Enregistrement utilisateur
            $this->_user_manager->updateUser($_user, $_edit_form, $_request->files->get('usr_photo'));

            $_flash_message = $this->_translator->trans('bo.confirmation.update');
            $this->_utils_manager->setFlash('success', $_flash_message);

            return $this->redirectToRoute('user_index');
        }

        return $this->render('@User/User/edit.html.twig', array(
            'user'      => $_user,
            'edit_form' => $_edit_form->createView()
        ));
    }

    /**
     * Creation utilisateur
     * @param Request $_request requête
     * @return Render page
     */
    public function new(Request $_request)
    {
        $_user = new WmsUser();
        $_form = $this->createFormByMethod($_user, 'add');
        $_form->handleRequest($_request);

        if ($_form->isSubmitted() && $_form->isValid()) {
            // Enregistrement utilisateur
            $this->_user_manager->addUser($_user, $_form, $_request->files->get('usr_photo'));

            $_flash_message = $this->_translator->trans('bo.confirmation.add');
            $this->_utils_manager->setFlash('success', $_flash_message);

            return $this->redirectToRoute('user_index');
        }

        return $this->render('@User/User/new.html.twig', array(
            'user' => $_user,
            'form' => $_form->createView()
        ));
    }

    /**
     * Suppression utilisateur
     * @param Request $_request requête
     * @param WmsUser $_user
     * @param ServiceMetierWmsFile $_file_manager
     * @return Redirect redirection
     */
    public function delete(Request $_request, WmsUser $_user, ServiceMetierWmsFile $_file_manager)
    {
        $_form = $this->createDeleteForm($_user);
        $_form->handleRequest($_request);

        if ($_request->isMethod('GET') || ($_form->isSubmitted() && $_form->isValid())) {
            // Suppression utilisateur
            $_file_manager->deleteFile($_user->getWmsFile());
            $this->_utils_manager->deleteEntity($_user);

            $_flash_message = $this->_translator->trans('bo.confirmation.delete');
            $this->_utils_manager->setFlash('success', $_flash_message);
        }

        return $this->redirectToRoute('user_index');
    }

    /**
     * Ajax suppression fichier image utilisateur
     * @param Request $_request
     * @return JsonResponse
     */
    public function deleteImageAjax(Request $_request, ServiceMetierWmsFile $_file_manager)
    {
        // Récupérer manager
        $_response = false;
        $_user = $this->_utils_manager->getEntityById(WmsUser::class, $_request->request->get('user_id'));
        if($_user) {
            $_user_photo = $_user->getWmsFile();
            if($_user_photo) {
                $_file_manager->deleteFile($_user_photo);
                $_response = true;
            }
        }

        return new JsonResponse($_response);
    }

    /**
     * mon profil
     * @return Response
     */
    public function profile(Request $_request)
    {
        // Recuperer l utilisateur connecte
        $_user         = $this->getUser();
        $_profile_form = $this->createProfileForm($_user);
        $_profile_form->handleRequest($_request);

        if ($_profile_form->isSubmitted() && $_profile_form->isValid()) {
            // Enregistrement utilisateur
            $this->_user_manager->updateUser($_user, $_profile_form, $_request->files->get('usr_photo'));

            $_flash_message = $this->_translator->trans('bo.confirmation.update');
            $this->_utils_manager->setFlash('success', $_flash_message);

            return $this->redirectToRoute('user_profile');
        }

        return $this->render('@User/User/profile.html.twig', array(
            'user'         => $_user,
            'profile_form' => $_profile_form->createView()
        ));
    }

    /**
     * Creation formulaire utilisateur
     * @param WmsUser $_user
     * @param $_type
     * @return \Symfony\Component\Form\Form The form
     */
    private function createFormByMethod(WmsUser $_user, $_type)
    {
        $_form = $this->createForm(WmsUserType::class, $_user, [
            'action'     => $_type == 'add'
                ? $this->generateUrl('user_new')
                : $this->generateUrl('user_edit', ['id' => $_user->getId()]),
            'method'     => $_type == 'add' ? 'POST' : 'PUT',
            'is_edit'    => $_type == 'add' ? false : true,
            'is_profile' => false
        ]);

        return $_form;
    }

    /**
     * Creation formulaire de suppression utilisateur
     * @param WmsUser $_user The user entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(WmsUser $_user)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('user_delete', array('id' => $_user->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * Creation profile form user
     * @param WmsUser $_user The entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createProfileForm(WmsUser $_user)
    {
        $_form = $this->createForm(WmsUserType::class, $_user, array(
            'action'     => $this->generateUrl('user_profile'),
            'method'     => 'POST',
            'is_edit'    => true,
            'is_profile' => true
        ));

        return $_form;
    }
}
