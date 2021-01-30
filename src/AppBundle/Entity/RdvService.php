<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RdvService
 *
 * @ORM\Table(name="rdv_service")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RdvServiceRepository")
 */
class RdvService
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
     * @var Rendezvous
     *
     * @ORM\ManyToOne(targetEntity=Rendezvous::class, cascade={"persist"}, inversedBy="rdvServices")
     */
    protected $rdv;

    /**
     * @var Service
     *
     * @ORM\ManyToOne(targetEntity=Service::class, cascade={"persist"}, inversedBy="rdvService")
     */
    protected $service;

    /**
     * @var RdvServOption
     *
     * @ORM\OneToMany(targetEntity=RdvServOption::class, mappedBy="rdvService")
     */
    protected $option;

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
     * @return Rendezvous
     */
    public function getRdv()
    {
        return $this->rdv;
    }

    /**
     * @param Rendezvous $rdv
     */
    public function setRdv($rdv)
    {
        $this->rdv = $rdv;
    }

    /**
     * @return Service
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param Service $service
     */
    public function setService($service)
    {
        $this->service = $service;
    }

    /**
     * @return RdvServOption
     */
    public function getOption()
    {
        return $this->option;
    }

    /**
     * @param RdvServOption $option
     */
    public function setOption($option)
    {
        $this->option = $option;
    }

}

