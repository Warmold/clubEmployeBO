<?php

declare(strict_types=1);

namespace App\Form\Invitation;

use App\Form\Type\Select2Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class CustomerChoiceType
 */
class GuestChoiceType extends AbstractType
{
    private $router;

    /**
     * ProductChoiceType constructor.
     *
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->resetViewTransformers();
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'autocomplete_url' => $this->router->generate('user_autocomplete'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): string
    {
        return Select2Type::class;
    }
}
