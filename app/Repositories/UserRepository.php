<?php

namespace App\Repositories;

use App\User;
use function GuzzleHttp\Psr7\str;
use Illuminate\Support\Str;

class UserRepository
{

    /**
     * @param $userData
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findByUsernameOrCreate($userData)
    {
        $user = new User();

        if (is_null($userData->nickname)) { // google does not have a nickname and github does..
            $userData->nickname = ucfirst(str::camel($userData->name));
        }

        $user = $user->newQuery()->firstOrCreate([
            'name'  => $userData->nickname,
            'email' => $userData->email
        ]);

        return $user;

    }
}