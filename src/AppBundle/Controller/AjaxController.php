<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class AjaxController extends Controller
{
    //return each service options
    /**
     * @Route ("/request_options")
     */
    public function optionAction(Request $request)
    {
        $serId=$request->request->get('service');

        // get service for filtering
        $service = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:Service')
            ->findOneById($serId);

        $value[]="";//initialisation
        //get service's options
        $options = $service->getOptionService();
        if ($options != null){
            for ($i = 0; $i < count($options); $i++) {
                $id=$options[$i]->getId();
                $nom=$options[$i]->getNom();
                $value[] = ["id"=>$id, "nom"=>$nom];
            }
        }

        return new JsonResponse($value);

    }
}
