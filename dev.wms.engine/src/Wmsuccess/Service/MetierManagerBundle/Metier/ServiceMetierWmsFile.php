<?php

namespace App\Wmsuccess\Service\MetierManagerBundle\Metier;

use App\Wmsuccess\Service\MetierManagerBundle\Entity\WmsFile;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class ServiceMetierWmsFile
 * @package App\Wmsuccess\Service\MetierManagerBundle\Metier
 */
class ServiceMetierWmsFile
{
    private $_entity_manager;
    private $_web_root;

    /**
     * ServiceMetierWmsFile constructor.
     * @param EntityManagerInterface $_entity_manager
     * @param ParameterBagInterface $_root_dir
     */
    public function __construct(EntityManagerInterface $_entity_manager, ParameterBagInterface $_root_dir)
    {
        $this->_entity_manager = $_entity_manager;
        $this->_web_root       = realpath($_root_dir->get('kernel.project_dir') . '/public');
    }

    /**
     * Save file
     * @param $_file
     * @param $_nature_file
     * @param $_directory
     * @param $_last_file
     * @return WmsFile|bool
     */
    public function saveFile($_file, $_nature_file, $_directory, $_last_file = null)
    {
        $_user_file = new WmsFile();
        if (is_object($_last_file)) {
            $_user_file = $_last_file;
            $_path      = $this->_web_root . $_last_file->getFlUrl();
            @unlink($_path);
        }
        // Retrieve the specific directory
        try {
            $_original_name = $_file->getClientOriginalName();
            $_path_part     = pathinfo($_original_name);
            $_extension     = $_path_part['extension'];
            $_filename      = md5(uniqid());

            // Upload file
            $_filename_extension = $_filename . '.' . $_extension;
            $_uri_file           = $_directory . $_filename_extension;
            $_dir                = $this->_web_root . $_directory;
            $_file->move($_dir, $_filename_extension);
            $_user_file->setFlExtension($_extension);
            $_user_file->setFlName($_original_name);
            $_user_file->setFlUrl($_uri_file);
            $_user_file->setFlNature($_nature_file);

            $this->_entity_manager->persist($_user_file);
            $this->_entity_manager->flush();

            $_response = $_user_file;
        } catch (\Exception $_exc) {
            $_response = false;
        }

        return $_response;
    }

    /**
     * Delete file
     * @param WmsFile $_file
     * @return bool
     */
    public function deleteFile($_file)
    {
        if(is_object($_file)) {
            // delete file in the directory
            $_path = $this->_web_root . $_file->getFlUrl();
            @unlink($_path);
            // delete file in database
            $this->_entity_manager->remove($_file);
            $this->_entity_manager->flush();
        }

        return true;
    }
}
