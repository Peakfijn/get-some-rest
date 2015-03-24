<?php namespace Peakfijn\GetSomeRest\Auth\Storage;

use League\OAuth2\Server\AbstractServer;
use League\OAuth2\Server\Entity\AuthCodeEntity;
use League\OAuth2\Server\Entity\ScopeEntity;
use League\OAuth2\Server\Storage\AuthCodeInterface;

class AuthCodeStorage implements AuthCodeInterface
{

    /**
     * Get the auth code
     *
     * @param string $code
     *
     * @return \League\OAuth2\Server\Entity\AuthCodeEntity | null
     */
    public function get($code)
    {
        // TODO: Implement get() method.
    }

    /**
     * Create an auth code.
     *
     * @param string  $token       The token ID
     * @param integer $expireTime  Token expire time
     * @param integer $sessionId   Session identifier
     * @param string  $redirectUri Client redirect uri
     *
     * @return void
     */
    public function create($token, $expireTime, $sessionId, $redirectUri)
    {
        // TODO: Implement create() method.
    }

    /**
     * Get the scopes for an access token
     *
     * @param \League\OAuth2\Server\Entity\AuthCodeEntity $token The auth code
     *
     * @return \League\OAuth2\Server\Entity\ScopeEntity[] Array of \League\OAuth2\Server\Entity\ScopeEntity
     */
    public function getScopes(AuthCodeEntity $token)
    {
        // TODO: Implement getScopes() method.
    }

    /**
     * Associate a scope with an acess token
     *
     * @param \League\OAuth2\Server\Entity\AuthCodeEntity $token The auth code
     * @param \League\OAuth2\Server\Entity\ScopeEntity    $scope The scope
     *
     * @return void
     */
    public function associateScope(AuthCodeEntity $token, ScopeEntity $scope)
    {
        // TODO: Implement associateScope() method.
    }

    /**
     * Delete an access token
     *
     * @param \League\OAuth2\Server\Entity\AuthCodeEntity $token The access token to delete
     *
     * @return void
     */
    public function delete(AuthCodeEntity $token)
    {
        // TODO: Implement delete() method.
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