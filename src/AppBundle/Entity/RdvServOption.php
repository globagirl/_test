<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RdvServOption
 *
 * @ORM\Table(name="rdv_serv_option")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RdvServOptionRepository")
 */
class RdvServOption
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
     * @var RdvService
     *
     * @ORM\ManyToOne(targetEntity=RdvService::class, cascade={"persist"}, inversedBy="option")
     */
    protected $rdvService;

    /**
     * @var OptionService
     *
     * @ORM\ManyToOne(targetEntity=OptionService::class, inversedBy="rdvOption")
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

    /**
     * @return OptionService
     */
    public function getOption()
    {
        return $this->option;
    }

    /**
     * @param OptionService $option
     */
    public function setOption($option)
    {
        $this->option = $option;
    }

}

