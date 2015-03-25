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
        $result = DB::table('oauth_sessions')
            ->select([
                'oauth_sessions.id',
                'oauth_sessions.owner_type',
                'oauth_sessions.owner_id',
                'oauth_sessions.client_id',
                'oauth_sessions.client_redirect_uri'
            ])
            ->join('oauth_access_tokens', 'oauth_access_tokens.session_id', '=', 'oauth_sessions.id')
            ->where('oauth_access_tokens.access_token', $accessToken->getId())
            ->first();
        if ($result) {
            $session = new SessionEntity($this->server);
            $session->setId($result->id);
            $session->setOwner($result->owner_type, $result->owner_id);

            return $session;
        }

        return;
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
        $result = Capsule::table('oauth_sessions')
            ->select([
                'oauth_sessions.id',
                'oauth_sessions.owner_type',
                'oauth_sessions.owner_id',
                'oauth_sessions.client_id',
                'oauth_sessions.client_redirect_uri'
            ])
            ->join('oauth_auth_codes', 'oauth_auth_codes.session_id', '=', 'oauth_sessions.id')
            ->where('oauth_auth_codes.auth_code', $authCode->getId())
            ->first();
        if ($result) {
            $session = new SessionEntity($this->server);
            $session->setId($result->id);
            $session->setOwner($result->owner_type, $result->owner_id);

            return $session;
        }

        return;
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
        $result = DB::table('oauth_sessions')
            ->select('oauth_scopes.*')
            ->join('oauth_session_scopes', 'oauth_sessions.id', '=', 'oauth_session_scopes.session_id')
            ->join('oauth_scopes', 'oauth_scopes.id', '=', 'oauth_session_scopes.scope')
            ->where('oauth_sessions.id', $session->getId())
            ->get();
        $scopes = [];
        foreach ($result as $scope) {
            $scopes[] = (new ScopeEntity($this->server))->hydrate([
                'id'          => $scope['id'],
                'description' => $scope['description'],
            ]);
        }

        return $scopes;
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
                'owner_type' => $ownerType,
                'owner_id'   => $ownerId,
                'client_id'  => $clientId,
            ]);

        return $id;
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
        DB::table('oauth_session_scopes')
            ->insert([
                'session_id' => $session->getId(),
                'scope'      => $scope->getId(),
            ]);
    }
}