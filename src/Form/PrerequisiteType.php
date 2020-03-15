<?php

namespace App\Form;

use App\Entity\Prerequisite;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PrerequisiteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description')
            ->add('created_at')
            ->add('updated_at')
            ->add('exercises')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Prerequisite::class,
            'attr' => [
                'novalidate' => 'novalidate',
            ]
        ]);
    }
}
