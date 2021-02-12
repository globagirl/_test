<?php
/**
 * Created by PhpStorm.
 * User: khaoula
 * Date: 2/11/2021
 * Time: 6:24 PM
 */

namespace AppBundle\Service;

class EmailGenerator
{
    private $mailer;
    private $templating;

    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $templating)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    public function generateEmail($data,$to,$subject,$body,$attachement){

        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom('khawla@starzelectronics.com')//configured email
            ->setTo($to)
            ->setCharset('utf-8')
            ->setBody(
                $this->templating->render(
                    'emails/'.$body,
                    ['data' => $data]
                ),
                'text/html'
            );

            if($attachement){
                $message->attach($attachement);//join PDF
            }

        $this->mailer->send($message);

    }
}