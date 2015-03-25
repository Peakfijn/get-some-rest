<?php namespace Peakfijn\GetSomeRest\Auth\Grants;

use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Grant\PasswordGrant;
use Peakfijn\GetSomeRest\Contracts\GrantImplementationInterface;

class PasswordGrantImplementation implements GrantImplementationInterface
{
    /**
     * @param \League\OAuth2\Server\AuthorizationServer $auth
     */
    public function __construct(AuthorizationServer $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Implement the grant.
     */
    public function set()
    {
        $passwordGrant = new PasswordGrant();
        $passwordGrant->setVerifyCredentialsCallback(function ($username, $password) {
            $users = \App::make('\\' . config('get-some-rest.namespace') . '\User');
            $user = $users->whereEmail($username)->first();

            if ($user && \Hash::check($password, $user->password)) {
                return $user->id;
            }

            return false;
        });
        $this->auth->addGrantType($passwordGrant);
    }
}