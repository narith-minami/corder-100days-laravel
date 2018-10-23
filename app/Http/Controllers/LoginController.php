<?php

namespace App\Http\Controllers;

use Abraham\TwitterOAuth\TwitterOAuth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function twitter() {
       $twitterAuth = new TwitterOAuth(env("TWITTER_CONSUMER_KEY"), env("TWITTER_CONSUMER_SECRET"));
       $request_token = $twitterAuth->getRequestToken(env("TWITTER_OAUTH_CALLBACK"));

       session(array(
         'requestToken'=>$request_token['oauth_token'],
         'requestTokenSecret'=>$request_token['oauth_token_secret']
       ));

       if(isset($_GET['authorizeBoolean']) && $_GET['authorizeBoolean'] != '')
       $bAuthorizeBoolean = false;
       else
       $bAuthorizeBoolean = true;

       //Authorize url を取得
       $sUrl = $twitterAuth->getAuthorizeURL($sToken, $bAuthorizeBoolean);

       return redirect($sUrl);
    }

    public function twitterCallback() {
      $request_token = [];
      $request_token['oauth_token'] = session['requestToken'];
      $request_token['oauth_token_secret'] = session['requestTokenSecret'];

      if(isset($_GET['oauth_verifier']) && $_GET['oauth_verifier'] != ''){
      	$sVerifier = $_GET['oauth_verifier'];
      }else{
      	echo 'oauth_verifier error!';
      	exit;
      }

      //OAuth トークンも用いて TwitterOAuth をインスタンス化
      $connection = new TwitterOAuth(env("TWITTER_CONSUMER_KEY"), env("TWITTER_CONSUMER_SECRET"), $request_token['oauth_token'], $request_token['oauth_token_secret']);

      //oauth_verifierを使ってAccess tokenを取得
      $oAccessToken = $connection->getAccessToken($sVerifier);

      //取得した値をSESSIONに格納
      session['oauth_token'] = 			$oAccessToken['oauth_token'];
      session['oauth_token_secret'] = 	$oAccessToken['oauth_token_secret'];
      return redirect('/list');
    }
}
