<?php

namespace App\Wmsuccess\Service\MetierManagerBundle\Twig\Extension;

/**
 * Class FileExistsExtension
 * @package App\Wmsuccess\Service\MetierManagerBundle\Twig\Extension
 */
class FileExistsExtension extends \Twig_Extension
{
    /**
     * Return the functions registered as twig extensions
     *
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('file_exists', 'file_exists'),
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'twig';
    }
}
