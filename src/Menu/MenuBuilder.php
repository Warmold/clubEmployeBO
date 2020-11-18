<?php

namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class MenuBuilder
 */
class MenuBuilder
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var RequestStack
     */
    private RequestStack $requestStack;


    /**
     * MenuBuilder constructor.
     *
     * @param FactoryInterface     $factory
     * @param RequestStack         $requestStack
     * @param TranslatorInterface  $translator
     */
    public function __construct(FactoryInterface $factory, RequestStack $requestStack)
    {
        $this->factory           = $factory;
        $this->requestStack      = $requestStack;
    }

    /**
     * @return ItemInterface
     *
     * @throws \Exception
     */
    public function createMainMenu(): ItemInterface
    {
        $menu = $this->factory->createItem('root');

        $menu->addChild('invitation', [
            'route'  => 'invitation_list',
            'extras' => [
                'icon' => 'fas fa-calendar-alt',
            ],
            'label' => 'Invitation',
        ]);

        return $menu;
    }

}