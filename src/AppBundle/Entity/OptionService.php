<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * OptionService
 *
 * @ORM\Table(name="option_service")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OptionServiceRepository")
 */
class OptionService
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
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=100, unique=true)
     */
    private $nom;

    /**
     * @var Service
     *
     * @ORM\ManyToMany(targetEntity=Service::class, inversedBy="optionService")
     * @ORM\JoinTable(name="opt_de_serv",
     *   joinColumns={@ORM\JoinColumn(name="option_id", referencedColumnName="id")},
     *   inverseJoinColumns={@ORM\JoinColumn(name="service_id", referencedColumnName="id")})
     */
    protected $service;

    /**
     * @var RdvServOption
     *
     * @ORM\OneToOne(targetEntity=RdvServOption::class, mappedBy="option")
     */
    protected $rdvOption;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return OptionService
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->service=new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param ArrayCollection $service
     */
    public function setService($service)
    {
        $this->service = $service;
    }

    /**
     * @return RdvServOption
     */
    public function getRdvOption()
    {
        return $this->rdvOption;
    }

    /**
     * @param RdvServOption $rdvOption
     */
    public function setRdvOption($rdvOption)
    {
        $this->rdvOption = $rdvOption;
    }

}

