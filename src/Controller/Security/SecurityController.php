<?php

namespace App\Controller\Security;

use App\Form\Security\LoginType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class SecurityController.
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     *
     * @Template("security/login.html.twig")
     *
     * @return mixed
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils)
    {
        $form = $this->createForm(LoginType::class, null, [
            'attr' => ['id' => 'login_form'],
            'action' => $this->generateUrl($request->get('_route')),
            'method' => 'POST',
        ])->handleRequest($request);

        return [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error'         => $authenticationUtils->getLastAuthenticationError(),
            'form'          => $form->createView(),
        ];
    }

    /**
     * @Route("/logout", name="logout")
     *
     * @throws \Exception
     */
    public function logout(): void
    {
        throw new \Exception('Will be intercepted before getting here');
    }
}