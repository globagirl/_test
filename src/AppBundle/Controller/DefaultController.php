<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Client;
use AppBundle\Entity\OptionService;
use AppBundle\Entity\RdvService;
use AppBundle\Entity\RdvServOption;
use AppBundle\Entity\Rendezvous;
use AppBundle\Entity\Service;

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
        //render home page
        return $this->render('index.html.twig');

    }

    /**
     * @Route("/liste_RDV", name="liste_RDV")
     */
    public function ConsultRDV(Request $request)
    {
        $rdv=$this->get('doctrine.orm.entity_manager')
            ->getRepository(Rendezvous::class)
            ->findAll();

        return $this->render('admin/liste_rdv.html.twig',['rdvs'=>$rdv]);
    }

    /**
     * @Route("/valider_RDV", name="valider_RDV")
     */
    public function validerRDV(Request $request)
    {
        $id=$request -> get('id');//get path variable
        $rdv=$this->get('doctrine.orm.entity_manager')
            ->getRepository(Rendezvous::class)
            ->findOneById($id);

        //Generate PDF---------------------------
        $html = $this->renderView("PdfTemplate/bon_commande.html.twig", ['rdv'=>$rdv]);
        $filename = 'bonNum'.$rdv->getId().'.pdf';
        $pdf = $this->get("knp_snappy.pdf")->getOutputFromHtml($html);
        $attachement = \Swift_Attachment::newInstance($pdf, $filename, 'application/pdf' );

        // Envoi Email---------------------------
        $message = \Swift_Message::newInstance()
            ->setSubject('Centre de beauté **')
            ->setFrom('khawla@starzelectronics.com')//configured email
            ->setTo($rdv->getClient()->getEmail())
            ->setCharset('utf-8')
            ->setBody(
                $this->renderView(
                    'emails/validationRDV.html.twig',
                    ['rdv' => $rdv]
                ),
                'text/html'
            )
            ->attach($attachement)//join PDF
        ;
        $this->get('mailer')->send($message);
        // Fin Envoi Email-----------------------

        //update RDV statut----------------------
        $rdv->setStatut('valider');
        $em = $this->get('doctrine.orm.entity_manager');
        $em->merge($rdv );
        $em->flush();
        //-----------------
        if($em) {
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
    public function ajouterRdvAction(Request $request)
    {
        //generate customer form
        $client = new Client();
        $form = $this->createForm('AppBundle\Form\ClientType', $client);
        $form->handleRequest($request);

        //select db all services
        $allServices=$this->get('doctrine.orm.entity_manager')
            ->getRepository(Service::class)
            ->findAll();

        if ($form->isSubmitted()) {

            //check email
            $exist=$this->get('doctrine.orm.entity_manager')
                ->getRepository(Client::class)
                ->findOneBy(['email'=>$client->getEmail()]);
            if ($exist){
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
                //service du rendez-vous  //TODO: multiple services
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

                // Create the message---------------------------------------
                //TODO: déplacer à un service
                $message = \Swift_Message::newInstance()
                    ->setSubject('Notif: Nouveau Rendez-vous')
                    ->setFrom('khawla@starzelectronics.com')//configured email
                    ->setTo('kaoulatouati@gmail.com')//email admin
                    ->setCharset('utf-8')
                    ->setBody(
                        $this->renderView(
                            'emails/rendezvous.html.twig',
                            ['name' => $client->getNom()]
                        ),
                        'text/html'
                    )
                ;
                $this->get('mailer')->send($message);

                if($em) {
                    $this->addFlash(
                        'notice_success',
                        'votre demande a été prise en compte et sera traitée dès que possible'
                    );
                }
                else{
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
