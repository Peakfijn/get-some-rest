<?php namespace Peakfijn\GetSomeRest\Contracts;

use League\OAuth2\Server\AuthorizationServer;

interface GrantImplementationInterface {

    /**
     * @param \League\OAuth2\Server\AuthorizationServer $auth
     */
    public function __construct(AuthorizationServer $auth);

    /**
     * Implement the grant.
     */
    public function set();
}