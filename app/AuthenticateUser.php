<?php

namespace App;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Contracts\Factory as Socialite;

/**
 * Class AuthenticateUser
 * @package app
 */
class AuthenticateUser
{
    /**
     * @var UserRepository
     */
    private $users;
    /**
     * @var Socialite
     */
    private $socialite;
    /**
     * @var SocialiteProvider
     */
    private $socialiteProvider;

    /**
     * AuthenticateUser constructor.
     * @param UserRepository $users
     * @param Socialite $socialite
     * @param SocialiteProvider $socialiteProvider
     */
    public function __construct(
        UserRepository $users,
        Socialite $socialite,
        SocialiteProvider $socialiteProvider)
    {
        $this->users = $users;
        $this->socialite = $socialite;
        $this->socialiteProvider = $socialiteProvider;
    }

    public function execute(string $hasCode, AuthenticateUserListener $listener)
    {
        if ( ! $hasCode) return $this->getAuthorizationFirst();

        $socialUser = $this->getSocialUser(); // show social data
//        dd($socialUser);

        $user = $this->users->findByUsernameOrCreate($socialUser);

        Auth::login($user, true);

        return $listener->userHasLoggedIn($user);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    private function getAuthorizationFirst()
    {
        return $this->socialite->driver($this->socialiteProvider::getSocialiteProvider())->redirect();
    }

    /**
     * @return \Laravel\Socialite\Contracts\User
     */
    private function getSocialUser()
    {
        return $this->socialite->driver($this->socialiteProvider::getSocialiteProvider())->user();
    }

}