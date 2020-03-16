<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Program;
use App\Entity\Exercise;
use App\Entity\MasteryLevel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class UserEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => [
                    new Email(),
                    new NotBlank(),
                ],
            ])
            ->add('roles', ChoiceType::class, [
                    'choices' => [
                        'ADMIN' => 'ROLE_ADMIN',
                        'MODERATOR' => 'ROLE_MODERATOR',
                        'USER' => 'ROLE_USER'
                    ],
                    'multiple' => true,
                ]
            )
            ->add('account_name', TextType::class)
            ->add('img_path', FileType::class, [
                'mapped' => false,
                'label' => 'Upload Image Exercise',
                'constraints' => [
                    new File([
                        'maxSize' => '2048k',
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'Please upload a valid Image : JPEG or PNG, less than 2MB',
                    ])
                ],
            ])
            ->add('mastery_level', EntityType::class, [
                'class' => MasteryLevel::class,
                'choice_label' => 'title',
                'expanded' => true,
                'multiple' => false,
            ]
            )
            ->add('available_time', IntegerType::class, [
                'label' => 'Available Time per Week'])
            ->add('score', IntegerType::class, [
            'label' => 'score']) 
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'attr' => [
                'novalidate' => 'novalidate',
            ]
        ]);
    }
}
