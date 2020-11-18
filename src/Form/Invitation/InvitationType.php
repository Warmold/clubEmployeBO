<?php

namespace App\Form\Invitation;

use App\Form\DataTransformer\StringToDateTimeTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class InvitationType
 */
class InvitationType extends AbstractType
{

    private $transformer;

    public function __construct(StringToDateTimeTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label'    => 'Titre',
                'required' => true,
            ])

            ->add('content', TextType::class, [
                'label'    => 'Contenu',
                'required' => false,
            ])

            ->add('guest', GuestChoiceType::class, [
                'label'    => 'Choisir un invité',
                'required' => true,
            ])

            ->add('invited_at', DateTimeType::class, [
                'label'    => 'Date',
                'required' => true,
            ]);

        $builder->get('invited_at')->addModelTransformer($this->transformer);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'allow_extra_fields' => true,
        ]);
    }
}
