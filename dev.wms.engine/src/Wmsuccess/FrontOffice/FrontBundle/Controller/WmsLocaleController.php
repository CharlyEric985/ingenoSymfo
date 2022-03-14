<?php

namespace App\Wmsuccess\FrontOffice\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class WmsLocaleController
 */
class WmsLocaleController extends AbstractController
{
    /**
     * Change the current locale of the user
     * @param Request $_request
     * @param string $_locale
     * @return RedirectResponse
     */
    public function setLocaleAction(Request $_request, $_locale = null)
    {
        if ($_locale != null) {
            // We save the language in session
            $this->get('session')->set('_locale', $_locale);
        }
        $_request->setLocale($_locale);

        $_url_referer = $_request->headers->get('referer');

        return new RedirectResponse($_url_referer);
    }
}
