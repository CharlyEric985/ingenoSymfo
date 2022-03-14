<?php

namespace App\Wmsuccess\Service\MetierManagerBundle\Twig\Extension;

use App\Wmsuccess\Service\MetierManagerBundle\Utils\ServiceName;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class SlugifyExtension
 * @package App\Wmsuccess\Service\MetierManagerBundle\Twig\Extension
 */
class SlugifyExtension extends \Twig_Extension
{
    private $_container;

    /**
     * SlugifyExtension constructor.
     * @param Container $_container
     */
    public function __construct(ContainerInterface $_container) {
        $this->_container = $_container;
    }

    /**
     * Slugify
     * @param string $_string
     * @return string
     */
    public function slugify($_string)
    {
        // Get manager
        $_utils_manager = $this->_container->get(ServiceName::SRV_METIER_UTILS);

        return $_utils_manager->slugify($_string);
    }

    /**
     * Return the functions registered as twig extensions
     *
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('slugify', array($this, 'slugify'))
        );
    }

    /**
     * Récupérer le préfixe twig
     * @return string
     */
    public function getName()
    {
        return 'gs_twig';
    }
}
