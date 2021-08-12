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
     * @ORM\Column(name="membership", type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="入力してください")
     * @Eccube\FormAppend(
     *     auto_render=true,
     *     type="\Symfony\Component\Form\Extension\Core\Type\ChoiceType",
     *     options={
     *          "required": true,
     *          "choices": {"有料":"有料","無料":"無料"},
     *          "expanded": true,
     *          "label": "有料/無料"     
     *     })
     */
    public $membership;
}