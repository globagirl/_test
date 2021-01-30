<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Service
 *
 * @ORM\Table(name="service")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ServiceRepository")
 */
class Service
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
     * @ORM\Column(name="nom_service", type="string", length=100, unique=true)
     */
    private $nomService;

    /**
     * @var OptionService
     *
     * @ORM\ManyToMany(targetEntity=OptionService::class, mappedBy="service")
     */
    protected $optionService;

    /**
     * @var RdvService
     *
     * @ORM\OneToOne(targetEntity=RdvService::class, mappedBy="service")
     */
    protected $rdvService;

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
     * Set nomService
     *
     * @param string $nomService
     *
     * @return Service
     */
    public function setNomService($nomService)
    {
        $this->nomService = $nomService;

        return $this;
    }

    /**
     * Get nomService
     *
     * @return string
     */
    public function getNomService()
    {
        return $this->nomService;
    }

    /**
     * @return OptionService
     */
    public function getOptionService()
    {
        return $this->optionService;
    }

    /**
     * @param OptionService $optionService
     */
    public function setOptionService($optionService)
    {
        $this->optionService = $optionService;
    }

    /**
     * @return RdvService
     */
    public function getRdvService()
    {
        return $this->rdvService;
    }

    /**
     * @param RdvService $rdvService
     */
    public function setRdvService($rdvService)
    {
        $this->rdvService = $rdvService;
    }


}

