<?php

namespace App\Security;

use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class User
 */
class User implements UserInterface, EquatableInterface
{
    /**
     * @var string|null
     */
    private ?string $uuid = null;

    /**
     * @var string|null
     */
    private ?string $email = null;

    /**
     * @var string|null
     */
    private ?string $username = null;

    /**
     * @var string|null
     */
    private ?string $password = null;

    /**
     * @var string|null
     */
    private ?string $salt = null;

    /**
     * @var array|null
     */
    private ?array $roles = null;

    /**
     * @var string|null
     */
    private ?string $token = null;

    /**
     * User constructor.
     *
     * @param string $email
     * @param array $roles
     */
    public function __construct($email, array $roles = [])
    {
        $this->email = $email;
        $this->roles = $roles;
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @param $uuid
     *
     * @return self
     */
    public function setUuid($uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @param string $salt
     */
    public function setSalt($salt): void
    {
        $this->salt = $salt;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     *
     * @return self
     */
    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @param UserInterface $user
     *
     * @return bool
     */
    public function isEqualTo(UserInterface $user): bool
    {
        if (!$user instanceof self) {
            return false;
        }

        if ($this->password !== $user->getPassword()) {
            return false;
        }

        if ($this->salt !== $user->getSalt()) {
            return false;
        }

        if ($this->username !== $user->getUsername()) {
            return false;
        }

        return true;
    }

    public function eraseCredentials()
    {
    }
}