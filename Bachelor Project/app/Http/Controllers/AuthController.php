<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Notifications\VerifyEmail;
use DB;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function refresh(Request $request)
    {
        $user=User::where('username',$request->refresh_token)->first();

        $t=DB::table('oauth_access_tokens')
                ->select('id')
                ->where('user_id','=', $user->id)
                ->first();
                
        $ref=DB::table('oauth_refresh_tokens')
                 ->select('id')
                 ->where('access_token_id','=', $t->id)
                 ->first();

        $http = new \GuzzleHttp\Client;
        $response = $http->post(config('services.passport.login_endpoint'), [
            'form_params' => [
                'grant_type' => 'refresh_token',
                'refresh_token' => 'def50200da3ff140b51fd2c467c9fabbde2a6145772e831b88357725273beb13bcf32e55e7bba86f1fbec4d639a45831d1307c2b11cb88ce94005d55dd310e35714c6a5e31d8769543ba8732e24c6436c8597315d74a3a90248143ddea6254ddc42c9204e9e78c0b0b843dcc1e01c9f6c5e03088a0b3662db4f8364a53a1efd29d060496eb36df9489142e88013269aaad2699d4afa39a6a2d1304c20afe18ef977f825f9884b2fd0e2686df7fce7dcdfa3a4def9fdde0c59bb9d40944095a06818430b31fdd3ac45d2d92686b9621867e71b0af2535164c0d7909fc0628bbab55f30cfbd41a0b2541f842d0520044915170283cd27f575254c691c12a2036bc9d1be5a27c31721c85f388bb01f02c29ac171a3c074b88d455d8d179867a3ec837f8b4ec3f1a6870ea24a8ba41ce152c329e48b3e1c8b4214b671ea949b2c7de0a9e7bbe1d6a95565c05629166dd989710ab8463ccaf5099989fb3ea435ac6e56f',
                'client_id' => config('services.passport.client_id'),
                'client_secret' => config('services.passport.client_secret')
            ],
        ]);

        return json_decode((string) $response->getBody(), true);
    }

    public function login(Request $request)
    {
        $http = new \GuzzleHttp\Client;

        $response = $http->post(
            'https://www.google.com/recaptcha/api/siteverify',
            ['form_params'=>
                [
                    'secret'=>'6Ld7M5AUAAAAAFwVShOI7cRvRnhUdvgvrItEZTpg',
                    'response'=>$request->token
                 ]
            ]
        );
    
        $body = json_decode((string)$response->getBody());

        if($body->score>'0.5')
        {
            try {
                $response = $http->post(config('services.passport.login_endpoint'), [
                    'form_params' => [
                        'grant_type' => 'password',
                        'client_id' => config('services.passport.client_id'),
                        'client_secret' => config('services.passport.client_secret'),
                        'username' => $request->username,
                        'password' => $request->password,
                    ]
                ]);
                
                return json_decode((string) $response->getBody(), true);
            } catch (\GuzzleHttp\Exception\BadResponseException $e) {
                if ($e->getCode() === 400) {
                    return response()->json('Please enter a username or a password.', $e->getCode());
                } else if ($e->getCode() === 401) {
                    return response()->json('Your credentials are incorrect. Please try again.', $e->getCode());
                }
                return response()->json('Something went wrong on the server.', $e->getCode());
            }
        }
        return response()->json('Looks like you are a robot.');
    }

    public function register(Request $request)
    {
        $http = new \GuzzleHttp\Client;

        $response = $http->post(
            'https://www.google.com/recaptcha/api/siteverify',
            ['form_params'=>
                [
                    'secret'=>'6Ld7M5AUAAAAAFwVShOI7cRvRnhUdvgvrItEZTpg',
                    'response'=>$request->token
                 ]
            ]
        );
    
        $body = json_decode((string)$response->getBody());

        if($body->score>'0.5')
        {
            $request->validate([
                'username' => 'required|string|max:255|unique:users',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
                'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:1999'
            ]);

            if(isset($request->avatar)){
                $filenameWithExt = $request->avatar->getClientOriginalName();;
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = $request->avatar->getClientOriginalExtension();
                $fileNameToStore= $filename.'_'.time().'.'.$extension;
                $path = $request->avatar->storeAs('public/profile_images', $fileNameToStore);
            } else {
                $fileNameToStore = 'noimage.jpg';
            }

            $remove = array('.','/');
            $activationToken = str_replace ($remove, '', Hash::make(Carbon::now().$request->username));
            
            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'avatar' => $fileNameToStore,
                'activation_token' => $activationToken,
                'active' => true
            ]);
            $user->notify(new VerifyEmail($user));
            return json_encode("Registration completed");
        }
        return json_encode("Looks like you are a robot.");
    }

    public function logout()
    {
        auth()->user()->tokens->each(function ($token, $key) {
            $token->delete();
        });
        return response()->json('Logged out successfully', 200);
    }

    public function activate($token){
        $user = User::where('activation_token', $token)->first();

        if (!$user)
            return redirect('/')->with('status', 'Profile updated!');
        else if($user->active=='1')
            return redirect('/')->with('status', 'Profile updated!');

        $user->active = true;
        $user->save();

        return redirect('/')->with('status', 'Profile updated!');;
        //return json_encode("Account has been sucesfully activated.");
    }
}
