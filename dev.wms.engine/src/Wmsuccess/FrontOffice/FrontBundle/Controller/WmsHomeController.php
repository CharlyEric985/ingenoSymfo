<?php

namespace App\Wmsuccess\FrontOffice\FrontBundle\Controller;

use App\Wmsuccess\Service\MetierManagerBundle\Entity\WmsVille;
use App\Wmsuccess\Service\MetierManagerBundle\Entity\WmsCode;
use App\Wmsuccess\Service\MetierManagerBundle\Entity\WmsDirigeant;
use App\Wmsuccess\Service\MetierManagerBundle\Entity\WmsSociete;
//use App\Wmsuccess\Service\MetierManagerBundle\Form\WmsJobType;
use App\Wmsuccess\Service\MetierManagerBundle\Metier\ServiceMetierUtils;
use App\Wmsuccess\Service\MetierManagerBundle\Utils\EntityName;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class WmsHomeController
 */
class WmsHomeController extends AbstractController
{

	private $_utils_manager;

    /**
     * UserController constructor.
     * @param ServiceMetierUtils $_utils_manager
     */
    public function __construct(ServiceMetierUtils $_utils_manager){
        $this->_utils_manager = $_utils_manager;
    }

    /**
     * Display a page home
     * @return string
     */
    public function index()
    {
    	$_order = ['id' => 'desc'];

    	$_codes = $this->_utils_manager->getAllEntitiesByOrder(EntityName::WMS_CODE, [], $_order);
    	$tableau_code = [];
        foreach ($_codes as $code) {
            array_push($tableau_code, [
                'id'  => $code->getId(),
                'code' => $code->getCode()
            ]);
        }

    	$_villes = $this->_utils_manager->getAllEntitiesByOrder(EntityName::WMS_VILLE, [], $_order);

        return $this->render('@Front/WmsHome/index.html.twig',[
        	'codes' => $tableau_code,
        	'villes'=> $_villes
        ]);
    }

    public function getVilleId(WmsCode $_code){

    	$villes = $this->_utils_manager->getEntityByFilter(EntityName::WMS_VILLE, ['wmsCode'=>$_code]);

    	$tableau_ville = [];
        foreach ($villes as $ville) {
            array_push($tableau_ville, [
                'id'  => $ville->getId(),
                'ville' => $ville->getNomVille()
            ]);
        }

    	return new JsonResponse ($tableau_ville);

    }

    public function insertionDirigeant(Request $request){

    	$dirigeant = new WmsDirigeant();
		$dirigeant->setDirigeantLastName($request->request->get('nom'));
		$dirigeant->setDirigeantFirstName($request->request->get('prenom')); 
		$dirigeant->setEmail($request->request->get('email')); 
		$dirigeant->setSexe($request->request->get('sexe'));   
		$this->_utils_manager->saveEntity($dirigeant,'new');
    	
    	return $this->redirectToRoute('home_index');
    }

    public function insertionSociete(Request $request){

		$societe = new WmsSociete();
		$societe->setSocieteName($request->request->get('societe'));
		$societe->setDescription($request->request->get('desc'));
		$societe->setCodePostal($request->request->get('code'));
		$societe->setVille($request->request->get('ville'));

		$checks = '';
        for($i=0;$i<8;$i++){
            if(!($request->request->get('check_'.$i))) continue;
            $checks = ( $i > 1) ? $checks.'/'.$request->request->get('check_'.$i) : $checks.$request->request->get('check_'.$i) ;
		}
		$societe->setTypes($checks);

		$this->_utils_manager->saveEntity($societe,'new');
    	
    	return $this->redirectToRoute('home_index');
    }

}
