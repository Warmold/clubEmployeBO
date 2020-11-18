<?php

namespace App\Manager;

use App\Connector\ApiConnector;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class AbstractManager.
 */
class AbstractManager
{
    /**
     * @var ApiConnector
     */
    protected ApiConnector $api;

    /**
     * @var PaginatorInterface
     */
    protected PaginatorInterface $paginator;

    /**
     * @var string
     */
    protected string $endpoint = '/';

    /**
     * AbstractManager constructor.
     *
     * @param ApiConnector       $api
     * @param PaginatorInterface $paginator
     */
    public function __construct(ApiConnector $api, PaginatorInterface $paginator)
    {
        $this->api       = $api;
        $this->paginator = $paginator;
    }

    /**
     * @return int|null
     */
    public function getReturnCode(): ?int
    {
        return $this->api->getReturnCode();
    }

    /**
     * @param bool $catchError
     *
     * @return self
     */
    public function catchError(bool $catchError = false): self
    {
        $this->api->setCatchError($catchError);

        return $this;
    }

    /**
     * @param array|null $context
     *
     * @return self
     */
    public function setEndpointContext(?array $context = []): self
    {
        foreach ($context as $param => $value) {
            $this->endpoint = str_replace('%' . $param . '%', $value, $this->endpoint);
        }

        return $this;
    }

    /**
     * @return array
     *
     * @throws \Exception
     */
    public function findAll(): array
    {
        return $this->api->get($this->endpoint);
    }

    /**
     * @param int $page
     * @param string|null $filters
     *
     * @param int $limit
     * @return PaginationInterface
     */
    public function findAllPaginated(int $page = 1, ?string $filters = null, int $limit = 50): PaginationInterface
    {
        $target = $this->endpoint;

        if (null !== $filters) {
            $target .= '?' . $filters;
        }

        return $this->paginator->paginate(
            $target,
            $page,
            $limit
        );
    }

    /**
     * @param string $uuid
     *
     * @return array
     *
     * @throws \Exception
     */
    public function find(string $uuid): array
    {
        return $this->api->get($this->endpoint . '/' . $uuid);
    }

    /**
     * @param array $data
     *
     * @return array
     *
     * @throws \Exception
     */
    public function add(array $data = []): array
    {
        return $this->api->post($this->endpoint, $data);
    }

    /**
     * @param array $data
     *
     * @return array
     *
     * @throws \Exception
     */
    public function edit(array $data = []): array
    {
        return $this->api->put($this->endpoint . '/' . $data['uuid'], $data);
    }

    /**
     * @param string $uuid
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function delete(string $uuid)
    {
        return $this->api->delete($this->endpoint . '/' . $uuid);
    }
}
