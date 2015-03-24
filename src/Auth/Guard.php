<?php namespace Peakfijn\GetSomeRest\Auth;

use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\ResourceServer;

class Guard
{
    public function __construct()
    {
        $sessionStorage = new Storage\SessionStorage();
        $accessTokenStorage = new Storage\AccessTokenStorage();
        $refreshTokenStorage = new Storage\RefreshTokenStorage();
        $clientStorage = new Storage\ClientStorage();
        $scopeStorage = new Storage\ScopeStorage();
        $authCodeStorage = new Storage\AuthCodeStorage();

        $this->resource = new ResourceServer(
            $sessionStorage,
            $accessTokenStorage,
            $clientStorage,
            $scopeStorage
        );

        $this->auth = (new AuthorizationServer())
            ->setSessionStorage($sessionStorage)
            ->setAccessTokenStorage($accessTokenStorage)
            ->setRefreshTokenStorage($refreshTokenStorage)
            ->setClientStorage($clientStorage)
            ->setScopeStorage($scopeStorage)
            ->setAuthCodeStorage($authCodeStorage);

        $passwordGrant = new \League\OAuth2\Server\Grant\PasswordGrant();
        $passwordGrant->setVerifyCredentialsCallback(function ($username, $password) {
            $user = \App\User::whereEmail($username)->first();
            if($user && \Hash::check($password, $user->password)) {
                return $user->id;
            }
            return false;
        });
        $this->auth->addGrantType($passwordGrant);
    }
}
