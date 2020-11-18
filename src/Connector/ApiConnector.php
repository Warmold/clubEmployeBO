<?php

declare(strict_types=1);

namespace App\Connector;

use App\Security\User;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Security;

/**
 * Class ApiConnector.
 */
class ApiConnector implements ApiConnectorInterface
{
    /**
     * @var array
     */
    private array $config;

    /**
     * @var Security
     */
    private Security $security;

    /**
     * @var RequestStack
     */
    private RequestStack $request;

    /**
     * @var int|null
     */
    private ?int $httpCode;

    /**
     * @var bool
     */
    private bool $catchError = true;

    /**
     * ApiConnector constructor.
     *
     * @param array         $config
     * @param Security      $security
     * @param RequestStack  $request
     */
    public function __construct(array $config, Security $security, RequestStack $request)
    {
        $this->config   = $config;
        $this->security = $security;
        $this->request  = $request;
    }

    /**
     * @return int|null
     */
    public function getReturnCode(): ?int
    {
        return $this->httpCode;
    }

    /**
     * @return bool
     */
    public function getCatchError(): bool
    {
        return $this->catchError;
    }

    /**
     * @param bool $catchError
     *
     * @return self
     */
    public function setCatchError(bool $catchError): self
    {
        $this->catchError = $catchError;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function get(string $path, array $data = [], array $headers = [])
    {
        return $this->request('GET', $path, $data, $headers);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function post(string $path, array $data = [], array $headers = [])
    {
        return $this->request('POST', $path, $data, $headers);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function put(string $path, array $data = [], array $headers = [])
    {
        return $this->request('PUT', $path, $data, $headers);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function delete(string $path)
    {
        return $this->request('DELETE', $path);
    }

    /**
     * @param $method
     * @param $path
     * @param array $data
     * @param array $headers
     *
     * @return array|string|null
     *
     * @throws \Exception
     */
    private function request(string $method, string $path, array $data = [], array $headers = [])
    {
        $fullPath = $this->config['host'] . $path;
        $request  = curl_init();

        curl_setopt($request, CURLOPT_URL, $fullPath);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);

        $this->httpCode = null;
        $body           = json_encode($data, JSON_THROW_ON_ERROR);

        $defaultHeaders = [
            'Content-type' => 'application/json',
            'Accept'       => 'application/json',
        ];

        /** @var User $user */
        $user = $this->security->getUser();
        if (null !== $user && $user->getToken()) {
            $headers['Authorization'] = 'Bearer ' . $user->getToken();
        }

        $headers = \array_merge($defaultHeaders, $headers);

        curl_setopt($request, CURLOPT_HTTPHEADER, \array_map(static function ($key, $value) {
            return $key . ': ' . $value;
        }, \array_keys($headers), $headers));

        if ('application/json' === $headers['Content-type']) {
            $data = json_encode($data, JSON_THROW_ON_ERROR);
        }

        if ('GET' === $method) {
            curl_setopt($request, CURLOPT_CUSTOMREQUEST, 'GET');
        } elseif ('POST' === $method) {
            curl_setopt($request, CURLOPT_POST, true);
            curl_setopt($request, CURLOPT_POSTFIELDS, $data);
        } elseif ('DELETE' === $method) {
            curl_setopt($request, CURLOPT_CUSTOMREQUEST, 'DELETE');
            curl_setopt($request, CURLOPT_POSTFIELDS, []);
        } elseif ('PUT' === $method) {
            curl_setopt($request, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($request, CURLOPT_POSTFIELDS, $data);
        }

        $response       = curl_exec($request);
        $this->httpCode = (int) curl_getinfo($request, CURLINFO_HTTP_CODE);
        $contentType    = curl_getinfo($request, CURLINFO_CONTENT_TYPE);
        curl_close($request);

        if ('application/json' === $contentType) {
            $final = json_decode($response, true, 512, JSON_THROW_ON_ERROR);
        } else {
            $final = $response;
        }

        if (($this->httpCode < 200 || $this->httpCode >= 500) && $this->getCatchError()) {
            throw new HttpException($this->httpCode, sprintf(
                'Request error, HTTP %s received: %s',
                $this->httpCode,
                $response
            ));
        }

        if ($this->httpCode === 403) {
            throw new AccessDeniedHttpException(sprintf(
                'Request error, HTTP %s received: %s',
                $this->httpCode,
                $response
            ));
        }

        return $final;
    }
}