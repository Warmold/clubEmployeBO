<?php


namespace App\Manager;


class InvitationManager extends AbstractManager
{
    protected string $endpoint = '/invitations';

    /**
     * @param string $uuid
     *
     * @return array|string|null
     *
     * @throws \Exception
     */
    public function confirm(string $uuid)
    {
        return $this->api->post($this->endpoint . '/' . $uuid . '/confirm');
    }

    /**
     * @param string $uuid
     *
     * @return array|string|null
     *
     * @throws \Exception
     */
    public function refuse(string $uuid)
    {
        return $this->api->post($this->endpoint . '/' . $uuid . '/refuse');
    }

    /**
     * @param string $uuid
     *
     * @return array|string|null
     *
     * @throws \Exception
     */
    public function cancel(string $uuid)
    {
        return $this->api->post($this->endpoint . '/' . $uuid . '/cancel');
    }
}