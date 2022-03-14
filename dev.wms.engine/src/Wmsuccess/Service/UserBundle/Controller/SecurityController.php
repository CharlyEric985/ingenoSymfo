<?php

namespace App\Wmsuccess\Service\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $_auth_checker = $this->container->get('security.authorization_checker');

        if ($_auth_checker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            if($_auth_checker->isGranted('ROLE_SUPERADMIN')) $_route_name = 'role_index';
            else if($_auth_checker->isGranted('ROLE_USER')) $_route_name = 'home_index';

            return $this->redirectToRoute($_route_name);
        }
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('@User/Security/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error
        ]);
    }
}