<?php

namespace App\Manager;

use App\Manager\AbstractManager;

/**
 * Class UserManager
 */
class UserManager extends AbstractManager
{
    /**
     * @var string
     */
    protected string $endpoint = '/users';

    /**
     * @param string $email
     * @param string $password
     *
     * @return array
     *
     * @throws \Exception
     */
    public function login(string $email, string $password): array
    {
        return $this->api->post('/login_check', [
            'username' => $email,
            'password' => $password,
        ]);
    }
}
