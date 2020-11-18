<?php

namespace App\Security;

use App\Manager\UserManager;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Class ApiUserProvider
 */
class ApiUserProvider implements UserProviderInterface
{
    /**
     * @var UserManager
     */
    private UserManager $userManager;

    /**
     * ApiUserProvider constructor.
     *
     * @param UserManager $userManager
     */
    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * @param string $username
     *
     * @return User|UserInterface|null
     */
    public function loadUserByUsername($username): ?UserInterface
    {
        return $this->fetchUser($username);
    }

    /**
     * @param UserInterface $user
     *
     * @return User|UserInterface
     *
     * @throws \Exception
     */
    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        return $user;
    }

    /**
     * @param $username
     *
     * @return User|UserInterface|null
     */
    private function fetchUser($username): ?UserInterface
    {
        try {
            $userData = $this->userManager->findByToken($username);

            if ($userData) {
                $user = new User($userData['email'], $userData['roles']);
                $user->setUsername($username);
                $user->setToken($username);

                return $user;
            }
        } catch (\Exception $e) {
            return null;
        }

        throw new UsernameNotFoundException(
            sprintf('Username "%s" does not exist.', $username)
        );
    }

    /**
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class): bool
    {
        return User::class === $class;
    }
}
