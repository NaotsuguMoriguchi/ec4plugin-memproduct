<?php

namespace Plugin\membership\Entity;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\AbstractEntity;

/**
 * MbOption
 *
 * @ORM\Table(name="plg_membership_membership_option")
 * @ORM\Entity(repositoryClass="Plugin\membership\Repository\MbOptionRepository")
 */
class MbOption extends AbstractEntity
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", options={"unsigned":true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

      /**
     * @var int
     *
     * @ORM\Column(name="sort_no", type="integer", options={"unsigned":true})
     */
    private $sort_no;

    /**
     * @var string
     *
     * @ORM\Column(name="discriminator_type", type="text", nullable=true)
     */    
    private $discriminator_type;


    
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this;
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

      /**
     * Set name.
     *
     * @param string $sort_no
     *
     * @return CustomMembership
     */
    public function setSortNo($sort_no)
    {
        $this->sort_no = $sort_no;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getSortNo()
    {
        return $this->sort_no;
    }
    /**
     * Set discriminator_type.
     *
     * @param string $discriminator_type
     *
     * @return CustomMembership
     */
    public function setDiscriminatorType($discriminator_type)
    {
        $this->discriminator_type = $discriminator_type;

        return $this;
    }

    /**
     * Get discriminator_type.
     *
     * @return string
     */
    public function getDiscriminatorType()
    {
        return $this->discriminator_type;
    }

    public function __toString()
    {
        return $this->name;
    }
}
