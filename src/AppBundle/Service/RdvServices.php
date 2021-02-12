<?php
/**
 * Created by PhpStorm.
 * User: khaoula
 * Date: 2/11/2021
 * Time: 6:28 PM
 */

namespace AppBundle\Service;

use AppBundle\Entity\Client;
use AppBundle\Entity\OptionService;
use AppBundle\Entity\RdvService;
use AppBundle\Entity\RdvServOption;
use AppBundle\Entity\Rendezvous;
use AppBundle\Entity\Service;

use AppBundle\Repository\OptionServiceRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class RdvServices
{
    protected $em;
    protected $container;

    public function __construct(EntityManagerInterface $entityManagerInterface, ContainerInterface $container)
    {
        $this->em = $entityManagerInterface;
        $this->container = $container;
    }

    public function getAllServices() : array {
        $services=$this->em->getRepository(Service::class)->findAll();
        return $services;
    }

    public function getClientByEmail($email) : Client {
        $client = $this->em->getRepository(Client::class)->findOneBy(['email'=>$email]);
        return $client;
    }

    public function getRdvById($id) : Rendezvous {
        $rdv=$this->em->getRepository(Rendezvous::class)->findOneById($id);
        return $rdv;
    }

    public function getAllRdv() : array {
        $rdv=$this->em->getRepository(Rendezvous::class)->findAll();
        return $rdv;
    }
    //:void - prob version php
    public function updateRdv(Rendezvous $rdv) {
        $this->em->persist($rdv);
        $this->em->flush();
    }

    public function createRdvOptions(OptionService $optionService) {
        $this->em->persist($optionService);
        $this->em->flush();
    }
}