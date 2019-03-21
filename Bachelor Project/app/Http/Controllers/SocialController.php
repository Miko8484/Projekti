<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Socialite;
use App\User;

class SocialController extends Controller
{
    public function redirectToProvider()
    {
        return Socialite::driver('facebook')->stateless()->redirect()->getTargetUrl();
    }
    public function handleProviderCallback(Request $request)
    {
        try {
            $user = Socialite::driver('facebook')->stateless()->user();
            return json_encode($user);
            return redirect('/#');
        } catch (Exception $e) {
            return Redirect::to('/');
        }
        /*$authUser = $this->findOrCreateUser($user);
        auth()->login($authUser, true);*/

        return ("aaaaaa");
    }
}
