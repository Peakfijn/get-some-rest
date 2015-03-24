<?php namespace Peakfijn\GetSomeRest\Auth\Storage;

use League\OAuth2\Server\AbstractServer;
use League\OAuth2\Server\Entity\ClientEntity;
use League\OAuth2\Server\Entity\SessionEntity;
use League\OAuth2\Server\Storage\AbstractStorage;
use League\OAuth2\Server\Storage\ClientInterface;

class ClientStorage extends AbstractStorage implements ClientInterface
{

    /**
     * Validate a client
     *
     * @param string $clientId     The client's ID
     * @param string $clientSecret The client's secret (default = "null")
     * @param string $redirectUri  The client's redirect URI (default = "null")
     * @param string $grantType    The grant type used (default = "null")
     *
     * @return \League\OAuth2\Server\Entity\ClientEntity | null
     */
    public function get($clientId, $clientSecret = null, $redirectUri = null, $grantType = null)
    {
        $query = \DB::table('oauth_clients')
            ->select('oauth_clients.*')
            ->where('oauth_clients.id', $clientId);

        if ($clientSecret !== null) {
            $query->where('oauth_clients.secret', $clientSecret);
        }
        if ($redirectUri) {
            $query->join('oauth_client_redirect_uris', 'oauth_clients.id', '=', 'oauth_client_redirect_uris.client_id')
                ->select(['oauth_clients.*', 'oauth_client_redirect_uris.*'])
                ->where('oauth_client_redirect_uris.redirect_uri', $redirectUri);
        }
        $result = $query->first();

        if ($result) {
            $client = new ClientEntity($this->server);
            $client->hydrate([
                'id'   => $result->id,
                'name' => $result->name
            ]);

            return $client;
        }

        return;
    }

    /**
     * Get the client associated with a session
     *
     * @param \League\OAuth2\Server\Entity\SessionEntity $session The session
     *
     * @return \League\OAuth2\Server\Entity\ClientEntity | null
     */
    public function getBySession(SessionEntity $session)
    {
        $result = DB::table('oauth_clients')
            ->select(['oauth_clients.id', 'oauth_clients.name'])
            ->join('oauth_sessions', 'oauth_clients.id', '=', 'oauth_sessions.client_id')
            ->where('oauth_sessions.id', $session->getId())
            ->get();
        if (count($result) === 1) {
            $client = new ClientEntity($this->server);
            $client->hydrate([
                'id'   => $result[0]['id'],
                'name' => $result[0]['name'],
            ]);

            return $client;
        }

        return;
    }
}