<?php

namespace App\Form\Security;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class LoginType.
 */
class LoginType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('email', TextType::class, [
            'required'   => false,
            'label'      => false,
            'empty_data' => null,
            'attr'       => [
                'class'        => 'form-control',
                'autocomplete' => 'off',
                'placeholder'  => 'app.login.email',
            ],
            'constraints' => new NotBlank(),
        ])

            ->add('password', PasswordType::class, [
                'required'   => false,
                'label'      => false,
                'empty_data' => null,
                'attr'       => [
                    'class'        => 'form-control',
                    'autocomplete' => 'off',
                    'placeholder'  => 'app.login.password',
                ],
                'constraints' => new NotBlank(),
            ])

            ->add('_remember_me', CheckboxType::class, [
                'required' => false,
                'label'    => false,
            ])

            ->add('_target_path', HiddenType::class);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class'         => null,
            'locked'             => false,
            'csrf_protection'    => true,
            'csrf_token_id'      => 'authenticate',
            'csrf_field_name'    => '_csrf_token',
            'translation_domain' => 'messages',
        ])->setAllowedTypes('locked', 'boolean');
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return '';
    }
}
