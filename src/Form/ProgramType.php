<?php

namespace App\Form;

use App\Entity\Hint;
use App\Entity\Program;
use App\Entity\Exercise;
use App\Entity\MasteryLevel;
use App\Entity\Prerequisite;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ProgramType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Program Title'])
            ->add('description')
            ->add('time', IntegerType::class, [
            'label' => 'Time (in sessions)'])
            ->add('img_path', FileType::class, [
                'mapped' => false,
                'label' => 'Upload Image',
                'constraints' => [
                    new File([
                        'maxSize' => '2048k',
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'Please upload a valid Image : JPEG or PNG, less than 2MB',
                    ])
                ],
            ])
            ->add('set_image_default',CheckboxType::class, [
                'label' => 'Set Default image',
                'mapped' => false,
            ])
            ->add('exercises', EntityType::class, [
                'class' => Exercise::class,
                'choice_label' => 'title',
                'expanded' => true,
                'multiple' => true,
            ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Program::class,
            'attr' => [
                'novalidate' => 'novalidate',
            ]
        ]);
    }
}
