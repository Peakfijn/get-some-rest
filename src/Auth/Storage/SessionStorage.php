<?php namespace Peakfijn\GetSomeRest\Auth\Storage;

use Illuminate\Support\Facades\DB;
use League\OAuth2\Server\AbstractServer;
use League\OAuth2\Server\Entity\AccessTokenEntity;
use League\OAuth2\Server\Entity\AuthCodeEntity;
use League\OAuth2\Server\Entity\ScopeEntity;
use League\OAuth2\Server\Entity\SessionEntity;
use League\OAuth2\Server\Storage\AbstractStorage;
use League\OAuth2\Server\Storage\SessionInterface;

class SessionStorage extends AbstractStorage implements SessionInterface
{

    /**
     * Get a session from an access token
     *
     * @param \League\OAuth2\Server\Entity\AccessTokenEntity $accessToken The access token
     *
     * @return \League\OAuth2\Server\Entity\SessionEntity | null
     */
    public function getByAccessToken(AccessTokenEntity $accessToken)
    {
        dd('SessionStorage.getByAccessToken');
        // TODO: Implement getByAccessToken() method.
    }

    /**
     * Get a session from an auth code
     *
     * @param \League\OAuth2\Server\Entity\AuthCodeEntity $authCode The auth code
     *
     * @return \League\OAuth2\Server\Entity\SessionEntity | null
     */
    public function getByAuthCode(AuthCodeEntity $authCode)
    {
        dd('SessionStorage.getByAuthCode');
        // TODO: Implement getByAuthCode() method.
    }

    /**
     * Get a session's scopes
     *
     * @param  \League\OAuth2\Server\Entity\SessionEntity
     *
     * @return \League\OAuth2\Server\Entity\ScopeEntity[] Array of \League\OAuth2\Server\Entity\ScopeEntity
     */
    public function getScopes(SessionEntity $session)
    {
        return false;
        dd('SessionStorage.getScopes');
        // TODO: Implement getScopes() method.
    }

    /**
     * Create a new session
     *
     * @param string $ownerType         Session owner's type (user, client)
     * @param string $ownerId           Session owner's ID
     * @param string $clientId          Client ID
     * @param string $clientRedirectUri Client redirect URI (default = null)
     *
     * @return integer The session's ID
     */
    public function create($ownerType, $ownerId, $clientId, $clientRedirectUri = null)
    {
        $id = DB::table('oauth_sessions')
            ->insertGetId([
                'owner_type'  =>    $ownerType,
                'owner_id'    =>    $ownerId,
                'client_id'   =>    $clientId,
            ]);
        return $id;
        // TODO: Implement create() method.
    }

    /**
     * Associate a scope with a session
     *
     * @param \League\OAuth2\Server\Entity\SessionEntity $session The session
     * @param \League\OAuth2\Server\Entity\ScopeEntity   $scope   The scope
     *
     * @return void
     */
    public function associateScope(SessionEntity $session, ScopeEntity $scope)
    {
        dd('SessionStorage.associateScope');
        // TODO: Implement associateScope() method.
    }
}