<?php namespace Peakfijn\GetSomeRest\Auth;

use League\OAuth2\Server\ResourceServer;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Grant\PasswordGrant;
use League\OAuth2\Server\Storage\AccessTokenInterface;
use League\OAuth2\Server\Storage\AuthCodeInterface;
use League\OAuth2\Server\Storage\ClientInterface;
use League\OAuth2\Server\Storage\RefreshTokenInterface;
use League\OAuth2\Server\Storage\ScopeInterface;
use League\OAuth2\Server\Storage\SessionInterface;
use Peakfijn\GetSomeRest\Contracts\GrantImplementationInterface;

class Shield
{
    /**
     * @var \League\OAuth2\Server\ResourceServer
     */
    public $resource;

    /**
     * @var \League\OAuth2\Server\AuthorizationServer
     */
    public $auth;
    /**
     * @var \League\OAuth2\Server\Storage\SessionInterface
     */
    private $sessionStorage;
    /**
     * @var \League\OAuth2\Server\Storage\AccessTokenInterface
     */
    private $accessTokenStorage;
    /**
     * @var \League\OAuth2\Server\Storage\RefreshTokenInterface
     */
    private $refreshTokenStorage;
    /**
     * @var \League\OAuth2\Server\Storage\ClientInterface
     */
    private $clientStorage;
    /**
     * @var \League\OAuth2\Server\Storage\ScopeInterface
     */
    private $scopeStorage;
    /**
     * @var \League\OAuth2\Server\Storage\AuthCodeInterface
     */
    private $authCodeStorage;

    protected $implementedGrants = [
        'password'
    ];

    /**
     * @param \League\OAuth2\Server\Storage\SessionInterface      $sessionStorage
     * @param \League\OAuth2\Server\Storage\AccessTokenInterface  $accessTokenStorage
     * @param \League\OAuth2\Server\Storage\RefreshTokenInterface $refreshTokenStorage
     * @param \League\OAuth2\Server\Storage\ClientInterface       $clientStorage
     * @param \League\OAuth2\Server\Storage\ScopeInterface        $scopeStorage
     * @param \League\OAuth2\Server\Storage\AuthCodeInterface     $authCodeStorage
     */
    public function __construct(
        SessionInterface $sessionStorage,
        AccessTokenInterface $accessTokenStorage,
        RefreshTokenInterface $refreshTokenStorage,
        ClientInterface $clientStorage,
        ScopeInterface $scopeStorage,
        AuthCodeInterface $authCodeStorage
    )
    {
        $this->sessionStorage = $sessionStorage;
        $this->accessTokenStorage = $accessTokenStorage;
        $this->refreshTokenStorage = $refreshTokenStorage;
        $this->clientStorage = $clientStorage;
        $this->scopeStorage = $scopeStorage;
        $this->authCodeStorage = $authCodeStorage;

        $this->setResourceServer();
        $this->setAuthorizationServer();
        $this->setGrants();
    }

    /**
     *
     */
    public function setResourceServer()
    {
        $this->resource = new ResourceServer(
            $this->sessionStorage,
            $this->accessTokenStorage,
            $this->clientStorage,
            $this->scopeStorage
        );
    }

    /**
     *
     */
    public function setAuthorizationServer()
    {
        $this->auth = (new AuthorizationServer())
            ->setSessionStorage($this->sessionStorage)
            ->setAccessTokenStorage($this->accessTokenStorage)
            ->setRefreshTokenStorage($this->refreshTokenStorage)
            ->setClientStorage($this->clientStorage)
            ->setScopeStorage($this->scopeStorage)
            ->setAuthCodeStorage($this->authCodeStorage);
    }

    /**
     *
     */
    public function setGrants()
    {
        $grants = config('get-some-rest.grants');
        $enabledGrants = array_intersect(config('get-some-rest.enabledGrants'), $this->implementedGrants);

        foreach($enabledGrants as $grant) {
            $grantClass = $grants[$grant];
            $grant = new $grantClass($this->auth);
            $this->setGrant($grant);
        }
    }

    /**
     * @param \Peakfijn\GetSomeRest\Contracts\GrantImplementationInterface $grant
     */
    public function setGrant(GrantImplementationInterface $grant)
    {
        $grant->set();
    }
}
