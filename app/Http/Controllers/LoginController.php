<?php

namespace App\Http\Controllers;

use Abraham\TwitterOAuth\TwitterOAuth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function twitter() {
       $twitterAuth = new TwitterOAuth(env("TWITTER_CONSUMER_KEY"), env("TWITTER_CONSUMER_SECRET"));
       $request_token = $twitterAuth->oauth('oauth/request_token', array('o_auth'=>env("TWITTER_OAUTH_CALLBACK")));

       session(array(
         'requestToken'=>$request_token['oauth_token'],
         'requestTokenSecret'=>$request_token['oauth_token_secret']
       ));

       $url = $twitter->url('oauth/authenticate', array(
                   'oauth_token' => $request_token['oauth_token']
               ));

       return redirect($sUrl);
    }

    public function twitterCallback() {
      $oauth_token = session['requestToken'];
      $oauth_token_secret = session['requestTokenSecret'];

      # request_tokenが不正な値だった場合エラー
      if ($request->has('oauth_token') && $oauth_token !== $request->oauth_token) {
          return Redirect::to('/login');
      }
      $twitter = new TwitterOAuth($oauth_token,$oauth_token_secret);

      $token = $twitter->oauth('oauth/access_token', array(
          'oauth_verifier' => $request->oauth_verifier,
          'oauth_token' => $request->oauth_token,
      ));

      # access_tokenを用いればユーザー情報へアクセスできるため、それを用いてTwitterOAuthをinstance化
      $twitter_user = new TwitterOAuth(
          config('TWITTER_CONSUMER_KEY'),
          config('TWITTER_CONSUMER_SECRET'),
          $token['oauth_token'],
          $token['oauth_token_secret']
      );

      return redirect('/list');
    }
}
