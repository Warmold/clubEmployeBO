<?php

namespace App\Security;

use App\Manager\UserManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Exception\LockedException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

/**
 * Class LoginFormAuthenticator.
 */
class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
    /**
     * @var UserManager
     */
    private UserManager $userManager;

    /**
     * @var RouterInterface
     */
    private RouterInterface $router;

    /**
     * @var SessionInterface
     */
    private SessionInterface $session;

    /**
     * @var CsrfTokenManagerInterface
     */
    private CsrfTokenManagerInterface $csrfTokenManager;

    /**
     * LoginFormAuthenticator constructor.
     *
     * @param UserManager               $userManager
     * @param RouterInterface           $router
     * @param SessionInterface          $session
     * @param CsrfTokenManagerInterface $csrfTokenManager
     */
    public function __construct(UserManager $userManager,
                                RouterInterface $router,
                                SessionInterface $session,
                                CsrfTokenManagerInterface $csrfTokenManager)
    {
        $this->userManager      = $userManager;
        $this->router           = $router;
        $this->session          = $session;
        $this->csrfTokenManager = $csrfTokenManager;
    }

    /**
     * @return SessionInterface
     */
    public function getSession(): SessionInterface
    {
        return $this->session;
    }

    /**
     * @param Request $request
     *
     * @return bool
     */
    public function supports(Request $request): bool
    {
        return 'login' === $request->attributes->get('_route')
            && $request->isMethod('POST')
            && !$request->isXmlHttpRequest();
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function getCredentials(Request $request): array
    {
        $credentials = [
            'email'      => $request->request->get('email'),
            'password'   => $request->request->get('password'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];

        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['email']
        );

        return $credentials;
    }

    /**
     * @param array                 $credentials
     * @param UserProviderInterface $userProvider
     *
     * @return User|null
     *
     * @throws InvalidCsrfTokenException
     */
    public function getUser($credentials, UserProviderInterface $userProvider): ?User
    {
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);

        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException('Invalid token');
        }

        return new User($credentials['email']);
    }

    /**
     * @param array         $credentials
     * @param UserInterface $user
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function checkCredentials($credentials, UserInterface $user): bool
    {
        if (($date = $this->getSession()->get('AUTH-LOCKED')) && new \DateTime() < $date) {
            $ex = new LockedException('User account is locked.');
            $ex->setUser($user);

            throw $ex;
        }

        $data = $this->userManager->login($credentials['email'], $credentials['password']);

        if (isset($data['token'])) {
            /* @var User $user */
            $user->setUuid($data['uuid']);
            $user->setUsername($data['token']);
            $user->setToken($data['token']);
            $user->setRoles($data['roles']);
            $user->setEmail($data['email']);

            $this->getSession()->set('AUTH-LOCKED', false);

            return true;
        }

        return false;
    }

    /**
     * @param Request        $request
     * @param TokenInterface $token
     * @param string         $providerKey
     *
     * @return RedirectResponse|null
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): ?RedirectResponse
    {
        return new RedirectResponse($this->router->generate('invitation_list'));
    }

    /**
     * @return string
     */
    protected function getLoginUrl(): string
    {
        return $this->router->generate('login');
    }
}
