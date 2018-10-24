<?php

namespace App\Services;

use Abraham\TwitterOAuth\TwitterOAuth;
use Illuminate\Http\Request;

class TwitterService
{

  public function getTimeline($search_word, $lang) {
    $access_token = session('oauth_token');
    $access_token_secret = session('oauth_token_secret');

    if (!isset($access_token) || !isset($access_token_secret)) {
      return redirect('/login');
    }

    $twitter = new TwitterOAuth(
      config('TWITTER_CONSUMER_KEY'),
      config('TWITTER_CONSUMER_SECRET'));
      // $access_token,
      // $access_token_secret);

    $oObj = $twitter->get("search/tweets", ["q" => $search_word,"lang" => $lang,"result_type"=>"resent","count"=>"100"]);
    \Debugbar::info($oObj);
    return $this->convertViewData($oObj);
  }

 private function convertViewData($twitterResult) {
   $iCount = $twitterResult->{'search_metadata'}->{'count'};
   $responseArray = $twitterResult->{'statuses'};
   $tweets = [];
   for($iTweet = 0; $iTweet<$iCount; $iTweet++){
       $iTweetId =                 $responseArray[$iTweet]->{'id'};
       if ($iTweetId == '') {
         continue;
       }
       $sIdStr =                   (string)$responseArray[$iTweet]->{'id_str'};
       $sText=                     $responseArray[$iTweet]->{'text'};
       $sName=                     $responseArray[$iTweet]->{'user'}->{'name'};
       $sScreenName=               $responseArray[$iTweet]->{'user'}->{'screen_name'};
       $sProfileImageUrl =         $responseArray[$iTweet]->{'user'}->{'profile_image_url'};
       $sCreatedAt =               $responseArray[$iTweet]->{'created_at'};
       $sStrtotime=                strtotime($sCreatedAt);
       $sCreatedAt =               date('Y-m-d H:i', $sStrtotime);
       $sCreatedYMD =              date('Y-m-d', $sStrtotime);
       $sCreatedHI =               date('H:i', $sStrtotime);
       $tweetLink =                $responseArray[$iTweet]->{'user'}->{'url'};
       $tweetURL =            'https://twitter.com/'.$sScreenName.'/status/'.$sIdStr;    //$responseArray[$iTweet]->{'entities'}->{'urls'}->{'url'};
       $isFavorited =  $responseArray[$iTweet]->{'favorited'};
       $heartIcon = 'https://coder-100days.herokuapp.com/images/heart_off.png';
       $favClass = 'fav-icon';
       if ($isFavorited) {
         $heartIcon = 'https://coder-100days.herokuapp.com/images/heart_on.png';
         $favClass .= ' favorited';
       }

       $tweets[] = (object)['iTweetId'=>$iTweetId,'sIdStr'=>$sIdStr,'sText'=>$sText,'sName'=>$sName,'sScreenName'=>$sScreenName,
       'sProfileImageUrl'=>$sProfileImageUrl,'sCreatedAt'=>$sCreatedAt,'sCreatedYMD'=>$sCreatedYMD,'sCreatedHI'=>$sCreatedHI,
       'tweetLink'=>$tweetLink,'tweetURL'=>$tweetURL,'isFavorited'=>$isFavorited,'heartIcon'=>$heartIcon,'favClass'=>$favClass
       ];
     }
     \Debugbar::info($tweets);
     return $tweets;
 }

  /**
   * @return $url {String}
   */
  public function authenticate() {
     $twitter = new TwitterOAuth(env("TWITTER_CONSUMER_KEY"), env("TWITTER_CONSUMER_SECRET"));
     $request_token = $twitter->oauth('oauth/request_token', array('oauth_callback'=>env("TWITTER_OAUTH_CALLBACK")));

     session(array(
       'requestToken'=>$request_token['oauth_token'],
       'requestTokenSecret'=>$request_token['oauth_token_secret']
     ));

     $url = $twitter->url('oauth/authenticate', array(
                 'oauth_token' => $request_token['oauth_token']
             ));
     return $url;
  }

  public function verify(Request $request) {
    $oauth_token = session('requestToken');
    $oauth_token_secret = session('requestTokenSecret');

    # request_tokenが不正な値だった場合エラー
    if ($request->has('oauth_token') && $oauth_token !== $request->oauth_token) {
        return Redirect::to('/login');
    }
    $twitter = new TwitterOAuth($oauth_token,$oauth_token_secret);

    $token = $twitter->oauth('oauth/access_token', array(
        'oauth_verifier' => $request->oauth_verifier,
        'oauth_token' => $request->oauth_token,
    ));

    session(array(
      'requestToken'=>$token['oauth_token'],
      'requestTokenSecret'=>$token['oauth_token_secret']
    ));

    return true;
  }
}
