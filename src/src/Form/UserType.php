<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('fileName', FileType::class, [
                'mapped' => false,
                'required' => false,
                'attr' => ['placeholder' => 'Select an article image'],
                'constraints' => [
                    new Image([
                        'maxSize' => '60k'
                    ]),
                    /*new NotNull([
                        'message' => 'Please upload an image',
                    ])*/
                ]
            ])
            ->add('email')
            ->add('firstName')
            ->add('lastName')
            ->add('optin')
            /*->add('roles')
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                $form = $event->getForm();
                $form->add('roles', ChoiceType::class,
                    [
                        'multiple' => false,
                        'placeholder' => 'Roles',
                        'choices' => User::ROLES,
                        'data' => $event->getData()['roles'][0] ?? User::ROLE_USER
                    ]
                );
            });*/
        ;
        /*$builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                    // transform the array to a string
                    return count($rolesArray)? $rolesArray[0]: null;
                },
                function ($rolesString) {
                    // transform the string back to an array
                    return [$rolesString];
                }
            ));*/

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
