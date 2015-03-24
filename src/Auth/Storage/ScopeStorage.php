<?php namespace Peakfijn\GetSomeRest\Auth\Storage;

use League\OAuth2\Server\AbstractServer;
use League\OAuth2\Server\Storage\ScopeInterface;

class ScopeStorage implements ScopeInterface
{

    /**
     * Return information about a scope
     *
     * @param string $scope     The scope
     * @param string $grantType The grant type used in the request (default = "null")
     * @param string $clientId  The client sending the request (default = "null")
     *
     * @return \League\OAuth2\Server\Entity\ScopeEntity | null
     */
    public function get($scope, $grantType = null, $clientId = null)
    {
        // TODO: Implement get() method.
    }

    /**
     * Set the server
     *
     * @param \League\OAuth2\Server\AbstractServer $server
     */
    public function setServer(AbstractServer $server)
    {
        // TODO: Implement setServer() method.
    }
}