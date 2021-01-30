<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rendezvous
 *
 * @ORM\Table(name="rendezvous")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RendezvousRepository")
 */
class Rendezvous
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Client
     *
     * @ORM\OneToOne(targetEntity=Client::class, cascade={"persist"}, inversedBy="RDV")
     */
    protected $client;

    /**
     * @var RdvService
     *
     * @ORM\OneToMany(targetEntity=RdvService::class, mappedBy="rdv")
     */
    protected $rdvServices;

    /**
     * @var string
     *
     * @ORM\Column(name="statut", type="string", length=10)
     */
    private $statut;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param Client $client
     */
    public function setClient($client)
    {
        $this->client = $client;
    }

    /**
     * @return RdvService
     */
    public function getRdvServices()
    {
        return $this->rdvServices;
    }

    /**
     * @param RdvService $rdvServices
     */
    public function setRdvServices($rdvServices)
    {
        $this->rdvServices = $rdvServices;
    }

    /**
     * @return string
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * @param string $statut
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;
    }


}

