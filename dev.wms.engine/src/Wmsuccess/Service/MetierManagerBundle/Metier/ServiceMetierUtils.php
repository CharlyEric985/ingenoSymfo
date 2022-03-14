<?php

namespace App\Wmsuccess\Service\MetierManagerBundle\Metier;

use App\Wmsuccess\Service\MetierManagerBundle\Entity\WmsTransaction;
use App\Wmsuccess\Service\MetierManagerBundle\Utils\DateTimeFrench;
use App\Wmsuccess\Service\MetierManagerBundle\Utils\EntityName;
use App\Wmsuccess\Service\MetierManagerBundle\Utils\PathName;
use App\Wmsuccess\Service\MetierManagerBundle\Utils\Util;
use Doctrine\ORM\EntityManagerInterface;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use HTTP_Request2;
use HTTP_Request2_Exception;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig\Environment;

/**
 * Class ServiceMetierEquipe
 * @package App\Gs\Service\MetierManagerBundle\Metier\Utils
 */
class ServiceMetierUtils
{
    private $_entity_manager;
    private $_container;
    private $_token_storage;
    private $_web_root;
    private $_twig;

    /**
     * ServiceMetierEquipe constructor.
     * @param EntityManagerInterface $_entity_manager
     * @param ContainerInterface $_container
     * @param TokenStorageInterface $_token_storage
     * @param ParameterBagInterface $_root_dir
     * @param Environment $_twig
     */
    public function __construct(EntityManagerInterface $_entity_manager, ContainerInterface $_container,
                                TokenStorageInterface $_token_storage, ParameterBagInterface $_root_dir,
                                Environment $_twig)
    {
        $this->_entity_manager = $_entity_manager;
        $this->_container      = $_container;
        $this->_token_storage  = $_token_storage;
        $this->_web_root       = realpath($_root_dir->get('kernel.project_dir') . '/public');
        $this->_twig           = $_twig;
    }

    /**
     * @param $_type
     * @param $_message
     * @return mixed
     * @throws \Exception
     */
    public function setFlash($_type, $_message)
    {
        return $this->_container->get('session')->getFlashBag()->add($_type, $_message);
    }

    /**
     * Get repository
     * @param string $_entity_name
     * @return array
     */
    public function getRepository($_entity_name)
    {
        return $this->_entity_manager->getRepository($_entity_name);
    }

    /**
     * Get all entities
     * @param string $_entity_name
     * @return array
     */
    public function getAllEntities($_entity_name)
    {
        return $this->getRepository($_entity_name)->findBy(array(), array('id' => 'DESC'));
    }

    /**
     * Get all entities by filter
     * @param string $_entity_name
     * @param array $_filter
     * @param array $_order
     * @return array
     */
    public function getAllEntitiesByOrder($_entity_name, $_filter, $_order)
    {
        return $this->getRepository($_entity_name)->findBy($_filter, $_order);
    }

    /**
     * Get entity by filter
     * @param string $_entity_name
     * @param array $_filter
     * @return array
     */
    public function getEntityByFilter($_entity_name, $_filter)
    {
        return $this->getRepository($_entity_name)->findBy($_filter);
    }

    /**
     * Get entity by filter with limit
     * @param string $_entity_name
     * @param array $_filter
     * @param array $_options
     * @return array
     */
    public function getEntityByFilterAndLimit($_entity_name, $_filter, $_options)
    {
        return $this->getRepository($_entity_name)->findBy($_filter, array('id' => 'desc'), $_options['limit'],
            $_options['offset']);
    }

    /**
     * Get one entity by filter
     * @param $_entity_name
     * @param $_filter
     * @param array $_order
     * @return mixed
     */
    public function findOneEntityByFilter($_entity_name, $_filter, $_order = [])
    {
        return $this->getRepository($_entity_name)->findOneBy($_filter, $_order);
    }

    /**
     * Get entity by id
     * @param string $_entity_name
     * @param integer $_id
     * @return array
     */
    public function getEntityById($_entity_name, $_id)
    {
        return $this->getRepository($_entity_name)->find($_id);
    }

    /**
     * @param $_entity
     * @param $_action
     * @return mixed
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function saveEntity($_entity, $_action)
    {
        if ($_action == 'new') {
            $this->_entity_manager->persist($_entity);
        }
        $this->_entity_manager->flush();

        return $_entity;
    }

    /**
     * @param $_entity
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function deleteEntity($_entity)
    {
        $this->_entity_manager->remove($_entity);
        $this->_entity_manager->flush();

        return true;
    }

    /**
     * @param $_entity_name
     * @param $_ids
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function deleteGroupEntity($_entity_name, $_ids)
    {
        if (count($_ids)) {
            foreach ($_ids as $_id) {
                $_entity = $this->getEntityById($_entity_name, $_id);
                $this->deleteEntity($_entity);
            }
        }

        return true;
    }

    /**
     * Send mail
     * @param $_recipient
     * @param $_subjet
     * @param $_template
     * @param $_data_param
     * @param null $_cc
     * @return bool
     * @throws \Twig\Error\Error
     */
    public function sendMail($_recipient, $_subjet, $_template, $_data_param, $_cc = null)
    {
        $_email_body         = $this->_twig->render($_template, $_data_param);
        $_from_email_address = $this->_container->getParameter('from_email_address');
        $_from_firstname     = $this->_container->getParameter('from_firstname');

        $_email_body = implode("\n", array_slice(explode("\n", $_email_body), 4));
        $_message    = (new \Swift_Message($_subjet))
            ->setFrom(array($_from_email_address => $_from_firstname))
            ->setTo($_recipient)
            ->setBody($_email_body);

        if ($_cc != null) {
            $_message = (new \Swift_Message($_subjet))
                ->setFrom(array($_from_email_address => $_from_firstname))
                ->setTo($_recipient)
                ->setCc($_cc)
                ->setBody($_email_body);
        }

        // Pièce jointe
        $_jointe_paths = [];
        if (isset($_data_param['attachs']))
            foreach ($_data_param['attachs'] as $_attach)
                $_jointe_paths[] = $this->addAttachMail($_attach);
        if ($_jointe_paths)
            foreach ($_jointe_paths as $_jointe_path)
                $_message->attach(\Swift_Attachment::fromPath($_jointe_path));

        $_message->setContentType("text/html");
        $_result = $this->_container->get('mailer')->send($_message);

        $_headers = $_message->getHeaders();
        $_headers->addIdHeader('Message-ID', uniqid() . "@domain.com");
        $_headers->addTextHeader('MIME-Version', '1.0');
        $_headers->addTextHeader('X-Mailer', 'PHP v' . phpversion());
        $_headers->addParameterizedHeader('Content-type', 'text/html', ['charset' => 'utf-8']);

        if ($_result)
            return true;

        return false;
    }

    /**
     * Upload pièce jointe
     * @param array $_attach
     * @return string
     */
    public function addAttachMail($_attach)
    {
        // Récupérer le répertoire image spécifique
        $_directory_image = PathName::UPLOAD_ATTACH;

        try {
            $_original_name = $_attach->getClientOriginalName();
            $_path_part     = pathinfo($_original_name);
            $_extension     = $_path_part['extension'];
            $_filename      = Util::slug($_path_part['filename']);

            // Upload jointe
            $_file_name_image = $_filename . '.' . $_extension;
            $_dir             = $this->_web_root . $_directory_image;
            $_attach->move(
                $_dir,
                $_file_name_image
            );

            return $_dir . $_file_name_image;
        } catch (\Exception $_exc) {
            throw new NotFoundHttpException("Erreur survenue lors de l'upload fichier " . $_exc->getMessage());
        }
    }

    /**
     * Slugify
     * @param string $_string
     * @param string $_delimiter
     * @return mixed|string
     */
    function slugify($_string, $_delimiter = '.')
    {
        $_old_locale = setlocale(LC_ALL, '0');
        setlocale(LC_ALL, 'en_US.UTF-8');
        $_clean = iconv('UTF-8', 'ASCII//TRANSLIT', $_string);
        $_clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $_clean);
        $_clean = strtolower($_clean);
        $_clean = preg_replace("/[\/_|+ -]+/", $_delimiter, $_clean);
        $_clean = trim($_clean, $_delimiter);
        setlocale(LC_ALL, $_old_locale);

        return $_clean;
    }

    /**
     * Recupere l utilisateur connecte
     * @return null|object|string
     */
    public function getUserConnected()
    {
        $_user_connected = null;
        $_user           = $this->_token_storage->getToken() ? $this->_token_storage->getToken()->getUser() : null;
        if (is_object($_user)) {
            $_user_connected = $this->_token_storage->getToken()->getUser();
        }

        return $_user_connected;
    }

    /**
     * Recupere Role utilisateur connecte
     * @return null
     */
    public function getUserRoleConnected()
    {
        $_user_role_connected = null;
        $_user                = $this->_token_storage->getToken() ? $this->_token_storage->getToken()->getUser() : null;
        if (is_object($_user)) {
            $_user_connected      = $this->_token_storage->getToken()->getUser();
            $_user_role_connected = $_user_connected->getWmsRole();
        }

        return $_user_role_connected;
    }

    /**
     * get user up
     */
    public function getIpUserConnected()
    {
        if (isset($_SERVER['HTTP_CLIENT_IP']) && array_key_exists('HTTP_CLIENT_IP', $_SERVER)) {
            $_ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
            $_ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $_ips = array_map('trim', $_ips);
            $_ip  = $_ips[0];
        } else {
            $_ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        }

        $_ip = filter_var($_ip, FILTER_VALIDATE_IP);
        $_ip = ($_ip === false) ? '0.0.0.0' : $_ip;
        return $_ip;
    }

    /**
     * Recuperer le date debut et fin de la semaine
     * @param $_month
     * @param $_year
     * @return array
     */
    public function getBeginAndLastDayInWeek($_month, $_year)
    {
        $_month       = intval($_month);
        $_end         = date('t', mktime(0, 0, 0, $_month, 1, $_year));        //last date day of month: 28 - 31
        $_start       = date('w', mktime(0, 0, 0, $_month, 1, $_year));    //1st day of month: 0 - 6 (Sun - Sat)
        $_last        = 7 - $_start;
        $_count_weeks = ceil((($_end - ($_last + 1)) / 7) + 1);
        $_output      = [];                        //initialize string
        $_month_label = str_pad($_month, 2, '0', STR_PAD_LEFT);

        for ($_i = 1; $_i < $_count_weeks + 1; $_i++) {
            if ($_i == 1) {
                $_start_date = "$_year-$_month_label-01";
                $_day        = $_last - 6;
            } else {
                $_day        = $_last + 1 + (($_i - 2) * 7);
                $_day        = str_pad($_day, 2, '0', STR_PAD_LEFT);
                $_start_date = "$_year-$_month_label-$_day";
            }
            if ($_i == $_count_weeks) {
                $_end_date = "$_year-$_month_label-$_end";
            } else {
                $_day_end  = $_day + 6;
                $_day_end  = str_pad($_day_end, 2, '0', STR_PAD_LEFT);
                $_end_date = "$_year-$_month_label-$_day_end";
            }

            if ($_i == 1) {
                $_temp_date  = new \DateTime($_end_date);
                $_start_date = $_temp_date->modify(" - 6 days")->format("Y-m-d");
            }
            if ($_i == $_count_weeks) {
                $_temp_date = new \DateTime($_start_date);
                $_end_date  = $_temp_date->modify(" + 6 days")->format("Y-m-d");
            }

            array_push($_output, [
                "start_date" => new \DateTime($_start_date),
                "end_date"   => new \DateTime($_end_date)
            ]);
        }

        return $_output;
    }

    /**
     * @param $_result
     * @param $_all_result
     * @return array
     */
    public function datatableResponse($_result, $_all_result)
    {
        return [
            'data'            => array_map(function ($_value) {
                return array_values($_value);
            }, $_result),
            'recordsTotal'    => count($_result),
            'recordsFiltered' => count($_all_result)
        ];
    }

    /**
     * Return le label de la semaine donne
     * @param $_week
     * @return string
     */
    public function getWeekLabel($_week)
    {
        $_start_date_fr = new DateTimeFrench($_week['start_date']->format('Y-m-d'));
        $_end_date_fr   = new DateTimeFrench($_week['end_date']->format('Y-m-d'));

        $_current_start_month_text = $_start_date_fr->format('M');
        $_current_end_month_text   = $_end_date_fr->format('M');
        $_first_day                = $_week['start_date']->format('d');
        $_last_day                 = $_week['end_date']->format('d');

        $_date_label = $_first_day . '' . $_current_start_month_text . '-' . $_last_day . '' . $_current_end_month_text;

        return $_date_label;
    }

    /**
     * get cookie authenticate
     * @return JsonResponse
     * @throws \HTTP_Request2_LogicException
     */
    public function getCookieAuthenticate()
    {
        $_erp_by_company = $this->getErpByCompany();

        if ($_erp_by_company) {
            $_erp_api_url        = $_erp_by_company['erpApiUrl'];
            $_erp_api_database   = $_erp_by_company['erpApiDatabase'];
            $_erp_api_user_email = $_erp_by_company['erpApiUserEmail'];
            $_erp_api_user_pwd   = $_erp_by_company['erpApiUserPwd'];

            $_request2 = new HTTP_Request2();

            $_request2->setUrl($_erp_api_url . "session/authenticate");
            $_request2->setMethod(HTTP_Request2::METHOD_POST);
            $_request2->setConfig([
                'follow_redirects' => TRUE
            ]);
            $_request2->setHeader([
                'Content-Type' => 'application/json',
            ]);

            $_request2->setbody('{"jsonrpc":"2.0","params":{"db":"' . $_erp_api_database . '","login":"' . $_erp_api_user_email . '","password":"' . $_erp_api_user_pwd . '"}}');
            try {
                $_response = $_request2->send();

                if ($_response->getStatus() == 200) {
                    $_cookie = $_response->getCookies()[0];
                    return new JsonResponse(['status' => true, 'message' => 'success', 'cookie' => $_cookie['value']]);
                } else {
                    return new JsonResponse(['status' => false, 'message' => $_response->getReasonPhrase()]);
                }
            } catch (HTTP_Request2_Exception $e) {
                return new JsonResponse(['status' => false, 'message' => 'warning']);
            }
        }
        return new JsonResponse(['status' => false, 'message' => 'warning']);
    }

    /**
     * @param $_url
     * @param $_param
     * @return bool|mixed
     * @throws \HTTP_Request2_LogicException
     */
    public function getApiOdoo($_url, $_param)
    {
        $_erp_by_company = $this->getErpByCompany();

        if ($_erp_by_company) {

            $_erp_api_url = $_erp_by_company['erpApiUrl'];
            $_data        = json_decode($this->getCookieAuthenticate()->getContent());

            if ($_data && $_data->status) {
                $_cookie   = $_data->cookie;
                $_request2 = new HTTP_Request2();
                $_request2->setUrl($_erp_api_url . $_url);

                $_request2->setMethod(HTTP_Request2::METHOD_POST);
                $_request2->setConfig(array(
                    'follow_redirects' => TRUE
                ));
                $_request2->setHeader(array(
                    'Content-Type' => 'application/json',
                    'X-Openerp'    => $_cookie,
                    'Cookie'       => 'session_id=' . $_cookie
                ));
                $_request2->setBody($_param);
                try {
                    $_response = $_request2->send();
                    if ($_response->getStatus() == 200) {
                        $_data = json_decode($_response->getBody())->result;
                        return $_data;
                    } else {
                        return null;
                    }
                } catch (HTTP_Request2_Exception $e) {
                    return null;
                }
            }
        }
        return null;
    }

    /**
     * get erp by company
     * @return |null
     */
    public function getErpByCompany()
    {
        //user id connected
        $_user_connected_id = $this->getUserConnected()->getId();

        $_wms_company = EntityName::ZRP_COMPANY;
        $_where       = "WHERE user.id = {$_user_connected_id}";

        $_dql = "SELECT  wmsErp.erpApiUrl AS erpApiUrl, wmsErp.erpApiDatabase AS erpApiDatabase,
                 wmsErp.erpApiUserEmail AS erpApiUserEmail, wmsErp.erpApiUserPwd AS erpApiUserPwd
                 FROM $_wms_company company 
                 LEFT JOIN company.wmsUser user
                 LEFT JOIN company.wmsCompanyErp wmsCompanyErp
                 LEFT JOIN wmsCompanyErp.wmsErp wmsErp ";

        $_result = $this->_entity_manager->createQuery($_dql . ' ' . $_where)->getResult();

        if ($_result) {
            return $_result[0];
        }
        return null;
    }


    /**
     * get company connected
     * @return mixed
     */
    public function getCompanyConnected()
    {
        //user id connected
        $_user_connected_id = $this->getUserConnected()->getId();

        $_wms_company = EntityName::ZRP_COMPANY;
        $_where       = "WHERE user.id = {$_user_connected_id}";

        $_dql = "SELECT  company FROM $_wms_company company JOIN company.wmsUser user";

        $_result = $this->_entity_manager->createQuery($_dql . ' ' . $_where)->getResult();

        return $_result ? $_result[0] : [];
    }

    /**
     * generate qrCode transaction
     * @param WmsTransaction $_transaction
     * @return string
     */
    public function generateQrCodeByTransaction(WmsTransaction $_transaction)
    {

        $_transaction_content = $_transaction->getTrxAddressBiller()
            . $_transaction->getTrxAmount() . ' ' . $_transaction->getTrxIssueDate()->format('d-m-Y') . ' ' .
            $_transaction->getTrxPostalCodeBiller();

        $_qr_code = new QrCode($_transaction_content);
        $_qr_code->setSize(300);
        $_qr_code->setMargin(10);

        // Set advanced options
        $_qr_code->setWriterByName('png');
        $_qr_code->setEncoding('UTF-8');
        $_qr_code->setErrorCorrectionLevel(ErrorCorrectionLevel::HIGH());
        $_qr_code->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0]);
        $_qr_code->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0]);
        //$_qr_code->setLabel('Scan the code', 16, '../public/backoffice/fonts/cryptocoins/cryptocoins.woff', LabelAlignment::CENTER());
        // $_qr_code->setLogoPath('../public/backoffice/img/logok.png');
        // $_qr_code->setLogoSize(150, 200);
        $_qr_code->setValidateResult(false);

        $_qr_code->setRoundBlockSize(true, QrCode::ROUND_BLOCK_SIZE_MODE_MARGIN); // The size of the qr code is shrinked, if necessary, but the size of the final image remains unchanged due to additional margin being added (default)
        $_qr_code->setRoundBlockSize(true, QrCode::ROUND_BLOCK_SIZE_MODE_ENLARGE); // The size of the qr code and the final image is enlarged, if necessary
        $_qr_code->setRoundBlockSize(true, QrCode::ROUND_BLOCK_SIZE_MODE_SHRINK); // The size of the qr code and the final image is shrinked, if necessary

        // Set additional writer options (SvgWriter example)
        $_qr_code->setWriterOptions(['exclude_xml_declaration' => true]);

        $_directory_image = PathName::UPLOAD_IMAGE_QR_CODE;

        $_file_name          = 'transaction_' . $_transaction->getId() . '.png';
        $_filename_extension = $_file_name;
        $_uri_file           = $_directory_image . $_filename_extension;

        // Save it to a file
        $_qr_code->writeFile('../public/upload/qr_code/' . $_file_name);

        $_transaction->setTrxQrCode($_uri_file);
        $this->saveEntity($_transaction, 'update');

        // Generate a data URI to include image data inline (i.e. inside an <img> tag)
        //$dataUri = $_qr_code->writeDataUri();
        return $_file_name;
    }
}