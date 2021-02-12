<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Client;
use AppBundle\Entity\OptionService;
use AppBundle\Entity\RdvService;
use AppBundle\Entity\RdvServOption;
use AppBundle\Entity\Rendezvous;
use AppBundle\Entity\Service;

use AppBundle\Service\EmailGenerator;
use AppBundle\Service\PdfGenerator;
use AppBundle\Service\RdvServices;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        return $this->render('index.html.twig');//render home page
    }

    /**
     * @Route("/liste_RDV", name="liste_RDV")
     */
    public function ConsultRDV(RdvServices $rdvServices)
    {
        $rdvs= $rdvServices->getAllRdv();
        return $this->render('admin/liste_rdv.html.twig',['rdvs'=>$rdvs]);
    }

    /**
     * @Route("/valider_RDV", name="valider_RDV")
     */
    public function validerRDV(Request $request,RdvServices $rdvServices, PdfGenerator $pdfGenerator, EmailGenerator $emailGenerator)
    {
        $id=$request -> get('id');//get path variable

        $rdv=$rdvServices->getRdvById($id);
        $rdv->setStatut('valider');
        $rdvServices->updateRdv($rdv);

        //Generate PDF
       $attachement= $pdfGenerator->generatePdf($rdv, 'bon_commande.html.twig');
        // Envoi Email
        $emailGenerator->generateEmail($rdv,$rdv->getClient()->getEmail(),
            'Centre de beauté: Confirmation rendez-vous','validationRDV.html.twig',$attachement);

        //update RDV
        if($emailGenerator) {
            $this->addFlash(
                'notice_success',
                'Rendez-vous valider avec succée'
            );
        }
        else{
            $this->addFlash(
                'error_synth',
                'Erreur technique: veuillez réessayer !'
            );
        }
        return $this->redirect($this->generateUrl('liste_RDV'));
    }

    /**
     * @Route("/ajouter_RDV", name="ajouter_RDV")
     */
    public function ajouterRdvAction(Request $request,RdvServices $rdvServices, EmailGenerator $emailGenerator)
    {
        //generate customer form
        $client = new Client();
        $form = $this->createForm('AppBundle\Form\ClientType', $client);
        $form->handleRequest($request);

        //select db all services
        $allServices=$rdvServices->getAllServices();

        if ($form->isSubmitted()) {
            //check email
            if ($rdvServices->getClientByEmail($client->getEmail())){
                $this->addFlash(
                    'error_synth',
                    'Email existe déja !'
                );
            }
            else{
                $em = $this->getDoctrine()->getManager();
                //create rendez-vous
                $rdv= new Rendezvous();
                $rdv->setClient($client);
                $rdv->setStatut('en attente');

                $services= $request->get("service");//get input

                $k=1;//used to get options of each service
                for ($j=0; $j<count($services); $j++){
                    $service=$this->get('doctrine.orm.entity_manager')
                        ->getRepository(Service::class)
                        ->findOneBy(['id'=>$services[$j]]);

                    //ajout service to DB
                    $service_rdv= new RdvService();
                    $service_rdv->setRdv($rdv);
                    $service_rdv->setService($service);

                    $em->persist($service_rdv);
                    $em->flush();

                    $options= $request->get("options".$k);//get input
                    $k++;
                    //ajout options de chaque service
                    for ($i = 0; $i < count($options); $i++) {
                        $option=$this->get('doctrine.orm.entity_manager')
                            ->getRepository(OptionService::class)
                            ->findOneBy(['nom'=>$options[$i]]);

                        $option_rdv= new RdvServOption();
                        $option_rdv->setRdvService($service_rdv);
                        $option_rdv->setOption($option);

                        $em->persist($option_rdv);
                        $em->flush();
                    }

                }
                $em->persist($client);
                $em->flush();

                // Envoi Email
                $emailGenerator->generateEmail($client->getNom(),'kaoulatouati@gmail.com',
                    'Notif: Nouveau Rendez-vous','rendezvous.html.twig','');

                if($em) {
                    $this->addFlash(
                        'notice_success',
                        'votre demande a été prise en compte et sera traitée dès que possible'
                    );
                }else{
                    $this->addFlash(
                        'error_synth',
                        'Une erreur technique est survenue, veuillez réessayer ultérieurement!'
                    );
                }
            }
        }
        return $this->render('client/ajouter_rdv.html.twig',[
            'form'=>$form->createView(), 'services'=>$allServices
        ]);
    }

}
