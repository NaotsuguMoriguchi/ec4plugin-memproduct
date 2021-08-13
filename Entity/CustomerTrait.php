<?php

namespace Plugin\membership\Entity;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Annotation as Eccube;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Eccube\EntityExtension("Eccube\Entity\Customer")
 */
trait CustomerTrait
{
    
    /**
     * @var \Plugin\membership\Entity\MbOption
     *
     * @ORM\ManyToOne(targetEntity="Plugin\membership\Entity\MbOption")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="membership_id", referencedColumnName="id")
     * })
     * @Eccube\FormAppend(
     *   auto_render=true,
     *   type="\Plugin\membership\Form\Type\MbOptionType",
     *   options={
     *     "required": false,
     *     "label": "有料/無料",
     *   })
     */

    private $membership;

    /**
     * Get membership.
     *
     * @return \Plugin\Membership\Entity\MbOption|null
     */
    public function getMembership()
    {
        return $this->membership;
    }
    
}