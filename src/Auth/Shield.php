<?php namespace Peakfijn\GetSomeRest\Auth;

use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Grant\PasswordGrant;
use League\OAuth2\Server\ResourceServer;
use League\OAuth2\Server\Storage\AccessTokenInterface;
use League\OAuth2\Server\Storage\AuthCodeInterface;
use League\OAuth2\Server\Storage\ClientInterface;
use League\OAuth2\Server\Storage\RefreshTokenInterface;
use League\OAuth2\Server\Storage\ScopeInterface;
use League\OAuth2\Server\Storage\SessionInterface;

class Shield
{
    public function __construct(
        SessionInterface $sessionStorage,
        AccessTokenInterface $accessTokenStorage,
        RefreshTokenInterface $refreshTokenStorage,
        ClientInterface $clientStorage,
        ScopeInterface $scopeStorage,
        AuthCodeInterface $authCodeStorage
    )
    {
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

        $passwordGrant = new PasswordGrant();
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
