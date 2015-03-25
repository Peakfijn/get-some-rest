<?php namespace Peakfijn\GetSomeRest\Auth\Storage;

use Illuminate\Support\Facades\DB;
use League\OAuth2\Server\Entity\ScopeEntity;
use League\OAuth2\Server\Storage\AbstractStorage;
use League\OAuth2\Server\Storage\ScopeInterface;

class ScopeStorage extends AbstractStorage implements ScopeInterface
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
        $result = DB::table('oauth_scopes')
            ->where('id', $scope)
            ->first();

        if (!$result) {
            return;
        }

        return (new ScopeEntity($this->server))->hydrate([
            'id'            =>  $result->id,
            'description'   =>  $result->description,
        ]);
    }
}