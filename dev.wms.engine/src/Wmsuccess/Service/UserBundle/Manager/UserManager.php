<?php

namespace App\Wmsuccess\Service\UserBundle\Manager;

use App\Wmsuccess\Service\MetierManagerBundle\Metier\ServiceMetierWmsFile;
use App\Wmsuccess\Service\MetierManagerBundle\Utils\EntityName;
use App\Wmsuccess\Service\MetierManagerBundle\Utils\FileTypeName;
use App\Wmsuccess\Service\MetierManagerBundle\Utils\PathName;
use App\Wmsuccess\Service\MetierManagerBundle\Utils\RoleName;
use App\Wmsuccess\Service\UserBundle\Entity\WmsUser;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserManager
 * @package App\Csn\Service\UserBundle\Manager
 */
class UserManager
{
    private $_entity_manager;
    private $_container;
    private $_pass_encoder;
    private $_file_manager;

    /**
     * UserManager constructor.
     * @param EntityManagerInterface $_entity_manager
     * @param ContainerInterface $_container
     * @param ServiceMetierWmsFile $_file_manager
     * @param UserPasswordEncoderInterface $_pass_encoder
     */
    public function __construct(
        EntityManagerInterface $_entity_manager,
        ContainerInterface $_container,
        ServiceMetierWmsFile $_file_manager,
        UserPasswordEncoderInterface $_pass_encoder
    )
    {
        $this->_entity_manager = $_entity_manager;
        $this->_container      = $_container;
        $this->_file_manager   = $_file_manager;
        $this->_pass_encoder   = $_pass_encoder;
    }

    /**
     * Récuperer le repository utilisateur
     * @return array
     */
    public function getRepository()
    {
        return $this->_entity_manager->getRepository(EntityName::USER);
    }

    /**
     * Recuperer tous les utilisateurs
     * @return array
     */
    public function getListUsers($_page, $_nb_max_page, $_search, $_order_by)
    {
        $_order_by       = $_order_by ? $_order_by : "usr.id DESC";
        $_user           = EntityName::WMS_USER;
        $_super_admin_id = RoleName::ID_ROLE_SUPERADMIN;

        $_dql = "SELECT fl.flUrl,
                        rl.rlName,
                        CONCAT(usr.usrFirstname, ' ', usr.usrLastname) AS usrFullname,
                        usr.email,
                        DATE_FORMAT(usr.usrDateCreate, '%d-%m-%Y') AS usrDateCreate,
                        usr.id,
                        IF(rl.id = $_super_admin_id, 1, 0)
                 FROM $_user usr
                 LEFT JOIN usr.wmsFile fl
                 LEFT JOIN usr.wmsRole rl
                 WHERE (usr.usrFirstname LIKE :search
                     OR usr.usrLastname LIKE :search
                     OR usr.email LIKE :search
                     OR rl.rlName LIKE :search
                     OR CONCAT(usr.usrFirstname, ' ', usr.usrLastname) LIKE :search
                     OR DATE_FORMAT(usr.usrDateCreate, '%d-%m-%Y') LIKE :search)
                 ORDER BY $_order_by";

        $_query = $this->_container->get('doctrine.orm.entity_manager')->createQuery($_dql);
        $_query->setParameter('search', "%$_search%")
            ->setFirstResult($_page)
            ->setMaxResults($_nb_max_page);

        return [$_query->getResult(), $this->countUsers($_search)];
    }

    /**
     * @param $_search
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    private function countUsers($_search)
    {
        $_user = EntityName::WMS_USER;

        $_dql = "SELECT COUNT(usr.id) AS nbTotal
                 FROM $_user usr
                 WHERE (usr.usrFirstname LIKE :search
                     OR usr.usrLastname LIKE :search  
                     OR usr.email LIKE :search  
                     OR DATE_FORMAT(usr.usrDateCreate, '%d-%m-%Y') LIKE :search)";

        $_query = $this->_container->get('doctrine.orm.entity_manager')->createQuery($_dql);
        $_query->setParameter('search', "%$_search%");

        return $_query->getOneOrNullResult()['nbTotal'];
    }

    /**
     * Ajouter un utilisateur
     * @param WmsUser $_user
     * @param Object $_form
     * @param $_user_photo
     * @return WmsUser
     */
    public function addUser(WmsUser $_user, $_form, $_user_photo)
    {
        // Encode the new users password
        $_user->setPassword($this->_pass_encoder->encodePassword($_user, $_user->getPassword()));
        // Activer par defaut
        $_user->setEnabled(1);
        // Traitement du photo
        $_img_photo = $_user_photo;
        if ($_img_photo) {
            $_user_file = $this->_file_manager->saveFile($_img_photo, FileTypeName::FL_TP_PROFILE_USER, PathName::UPLOAD_IMAGE_USER);
            if ($_user_file) $_user->setWmsFile($_user_file);
        }
        // Traitement rôle utilisateur
        $_type      = $_form['wmsRole']->getData();
        $_user_role = RoleName::$ROLE_TYPE[$_type->getRlName()];
        $_user->setRoles(array($_user_role));

        return $this->saveUser($_user, 'new');
    }

    /**
     * Modifier un utilisateur
     * @param WmsUser $_user
     * @param Object $_form
     * @param $_user_photo
     * @return WmsUser
     */
    public function updateUser(WmsUser $_user, $_form, $_user_photo)
    {
        $_user->setUsrDateUpdate(new \DateTime());
        // Traitement photo
        $_img_photo = $_user_photo;
        // S il y a un nouveau fichier ajoute, on supprime l ancien fichier puis on enregistre ce nouveau
        if ($_img_photo) {
            $_user_file = $this->_file_manager->saveFile($_img_photo, FileTypeName::FL_TP_PROFILE_USER, PathName::UPLOAD_IMAGE_USER, $_user->getWmsFile());
            if ($_user_file) $_user->setWmsFile($_user_file);
        }

        // Mise a jour mot de passe
        if ($_form['password']->getData()) {
            $_user->setPassword($this->_pass_encoder->encodePassword($_user, $_user->getPassword()));
        }

        return $this->saveUser($_user, 'update');
    }

    /**
     * Enregistrer un utilisateur
     * @param User $_user
     * @param string $_action
     * @return User
     */
    public function saveUser($_user, $_action)
    {
        if ($_action == 'new') {
            $this->_entity_manager->persist($_user);
        }
        $this->_entity_manager->flush();

        return $_user;
    }
}
