<?php
/**
 * Created by PhpStorm.
 * User: khaoula
 * Date: 2/12/2021
 * Time: 11:52 AM
 */

namespace AppBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class PdfGenerator
{
    private $templating;
    private $request;

    public function __construct( \Twig_Environment $templating, ContainerInterface $request)
    {
        $this->templating = $templating;
        $this->request = $request;
    }

    public function generatePdf($data,$body){

        $html = $this->templating->render("PdfTemplate/".$body, ['data'=>$data]);
        $filename = 'attachement'.$data->getId().'.pdf';
        $pdf = $this->request->get("knp_snappy.pdf")->getOutputFromHtml($html);
        $attachement = \Swift_Attachment::newInstance($pdf, $filename, 'application/pdf' );

        return $attachement;
    }
}