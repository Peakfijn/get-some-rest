<?php namespace Peakfijn\GetSomeRest\Auth\Storage;

use Illuminate\Support\Facades\DB;
use League\OAuth2\Server\AbstractServer;
use League\OAuth2\Server\Storage\AbstractStorage;
use League\OAuth2\Server\Entity\AccessTokenEntity;
use League\OAuth2\Server\Entity\ScopeEntity;
use League\OAuth2\Server\Storage\AccessTokenInterface;

class AccessTokenStorage extends AbstractStorage implements AccessTokenInterface
{

    /**
     * Get an instance of Entity\AccessTokenEntity
     *
     * @param string $token The access token
     *
     * @return \League\OAuth2\Server\Entity\AccessTokenEntity | null
     */
    public function get($token)
    {
        $result = DB::table('oauth_access_tokens')
            ->where('access_token', $token)
            ->first();
        if ($result) {
            $token = (new AccessTokenEntity($this->server))
                ->setId($result->access_token)
                ->setExpireTime($result->expire_time);

            return $token;
        }

        return;
    }

    /**
     * Get the scopes for an access token
     *
     * @param \League\OAuth2\Server\Entity\AccessTokenEntity $token The access token
     *
     * @return \League\OAuth2\Server\Entity\ScopeEntity[] Array of \League\OAuth2\Server\Entity\ScopeEntity
     */
    public function getScopes(AccessTokenEntity $token)
    {
        $result = DB::table('oauth_access_token_scopes')
            ->select(['oauth_scopes.id', 'oauth_scopes.description'])
            ->join('oauth_scopes', 'oauth_access_token_scopes.scope', '=', 'oauth_scopes.id')
            ->where('access_token', $token->getId())
            ->get();
        $response = [];
        if (count($result) > 0) {
            foreach ($result as $row) {
                $scope = (new ScopeEntity($this->server))->hydrate([
                    'id'          => $row['id'],
                    'description' => $row['description'],
                ]);
                $response[] = $scope;
            }
        }

        return $response;
    }

    /**
     * Creates a new access token
     *
     * @param string         $token      The access token
     * @param integer        $expireTime The expire time expressed as a unix timestamp
     * @param string|integer $sessionId  The session ID
     *
     * @return void
     */
    public function create($token, $expireTime, $sessionId)
    {
        DB::table('oauth_access_tokens')
            ->insert([
                'access_token' => $token,
                'session_id'   => $sessionId,
                'expire_time'  => $expireTime,
            ]);
    }

    /**
     * Associate a scope with an access token
     *
     * @param \League\OAuth2\Server\Entity\AccessTokenEntity $token The access token
     * @param \League\OAuth2\Server\Entity\ScopeEntity       $scope The scope
     *
     * @return void
     */
    public function associateScope(AccessTokenEntity $token, ScopeEntity $scope)
    {
        DB::table('oauth_access_token_scopes')
            ->insert([
                'access_token' => $token->getId(),
                'scope'        => $scope->getId(),
            ]);
    }

    /**
     * Delete an access token
     *
     * @param \League\OAuth2\Server\Entity\AccessTokenEntity $token The access token to delete
     *
     * @return void
     */
    public function delete(AccessTokenEntity $token)
    {
        DB::table('oauth_access_tokens')
            ->where('access_token', $token->getId())
            ->delete();
    }
}