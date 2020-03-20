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

class ExerciseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Exercise Title'])
            ->add('time', IntegerType::class, [
            'label' => 'Time (in minutes)'])
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
            ->add('description')
            ->add('score', IntegerType::class)
            ->add('hints', EntityType::class, [
                'class' => Hint::class,
                'choice_label' => 'text',
                'expanded' => true,
                'multiple' => true,
            ]
            )
            ->add('prerequisites', EntityType::class, [
                'class' => Prerequisite::class,
                'choice_label' => 'description',
                'expanded' => true,
                'multiple' => true,
            ]
            )
            ->add('programs', EntityType::class, [
                'class' => Program::class,
                'choice_label' => 'title',
                'expanded' => true,
                'multiple' => true,
            ]
            )
            ->add('mastery_level', EntityType::class, [
                'class' => MasteryLevel::class,
                'choice_label' => 'title',
            ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Exercise::class,
            'attr' => [
                'novalidate' => 'novalidate',
            ]
        ]);
    }
}
