<?php

declare(strict_types=1);

namespace App\Connector;

interface ApiConnectorInterface
{
    /**
     * @param string $path
     * @param array  $data
     *
     * @return array|string|null
     */
    public function get(string $path, array $data = []);

    /**
     * @param string $path
     * @param array  $data
     *
     * @return array|string|null
     */
    public function post(string $path, array $data = []);

    /**
     * @param string $path
     * @param array  $data
     *
     * @return array|string|null
     */
    public function put(string $path, array $data = []);

    /**
     * @param string $path
     *
     * @return array|string|null
     */
    public function delete(string $path);
}
