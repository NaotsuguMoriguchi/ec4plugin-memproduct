<?php

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) EC-CUBE CO.,LTD. All Rights Reserved.
 *
 * http://www.ec-cube.co.jp/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\membership\Form\Type;

use Eccube\Form\Type\MasterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MbOptionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options['membership_options']['required'] = $options['required'];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => 'Plugin\membership\Entity\MbOption',
            'placeholder' => false,
        ]);
    }

    public function getParent()
    {
        return MasterType::class;
    }

    public function getBlockPrefix()
    {
        return 'membership';
    }
}
