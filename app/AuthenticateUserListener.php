<?php
/**
 * Created by PhpStorm.
 * User: rjones
 * Date: 5/18/17
 * Time: 1:31 PM
 */

namespace App;

interface AuthenticateUserListener
{
    public function userHasLoggedIn($user);
}