<?php

namespace App\Wmsuccess\Service\UserBundle\Security;

use App\Wmsuccess\Service\UserBundle\Entity\WmsUser as AppUser;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class UserChecker
 * @package App\Wmsuccess\Service\UserBundle\Checker
 */
class UserChecker implements UserCheckerInterface
{
    private $_translator;

    /**
     * UserChecker constructor.
     * @param TranslatorInterface $_translator
     */
    public function __construct(TranslatorInterface $_translator)
    {
        $this->_translator = $_translator;
    }

    /**
     * checkPreAuth
     * @param UserInterface $_user
     */
    public function checkPreAuth(UserInterface $_user)
    {
        if (!$_user instanceof AppUser) {
            return;
        }

        if ($_user->getEnabled() == 0) {
            // the message passed to this exception is meant to be displayed to the user
            throw new CustomUserMessageAccountStatusException($this->_translator->trans('exception.user.disabled'));
        }
    }

    /**
     * checkPostAuth
     * @param UserInterface $_user
     */
    public function checkPostAuth(UserInterface $_user)
    {
        if (!$_user instanceof AppUser) {
            return;
        }

        if ($_user->getEnabled() == 0) {
            // the message passed to this exception is meant to be displayed to the user
            throw new CustomUserMessageAccountStatusException($this->_translator->trans('exception.user.disabled'));
        }
    }
}