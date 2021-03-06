<?php

namespace App\Services;

use Abraham\TwitterOAuth\TwitterOAuth;
use Illuminate\Http\Request;

date_default_timezone_set('Asia/Tokyo');

class TwitterService
{

  public function postFavorite($tweet_id, $is_create) {
    $twitter = $this->getTwitterInstance();
    if ($is_create) {
      $oObj = $twitter->post("favorites/create", ["id" => $tweet_id]);
    } else {
      $oObj = $twitter->post("favorites/destroy", ["id" => $tweet_id]);
    }
  }

  public function getTimelineHTML($search_word, $lang, $isUserMode) {
    return $this->convertHTML($this->getTweets($search_word, $lang), $isUserMode);
  }

  public function getTimeline($search_word, $lang) {
    return $this->convertViewData($this->getTweets($search_word, $lang));
  }

 private function getTwitterInstance() {
   $access_token = session('oauth_token');
   $access_token_secret = session('oauth_token_secret');

   if (!isset($access_token) || !isset($access_token_secret)) {
     return redirect('/login');
   }

   $twitter = new TwitterOAuth(
     env('TWITTER_CONSUMER_KEY'),
     env('TWITTER_CONSUMER_SECRET'),
     $access_token,
     $access_token_secret);
     return $twitter;
 }

 private function getTweets($search_word, $lang) {
   $twitter = $this->getTwitterInstance();
   $oObj = $twitter->get("search/tweets", ["q"=>$search_word,"lang" =>$lang,"result_type"=>"resent","count"=>"100"]);
   // \Debugbar::info($oObj);
   return $oObj;
 }

 private function convertHTML($twitterResult ,$isUserMode) {
   $responseArray = $twitterResult->{'statuses'};
   $iCount = count($responseArray);
   \Debugbar::info($responseArray);
   $result = '<ul id="user-timeline" class="cbp_tmtimeline">';
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
       $tweetURL =            'https://twitter.com/'.$sScreenName.'/status/'.$sIdStr;
       $isFavorited = (boolean)$responseArray[$iTweet]->{'favorited'};
       $heartIcon = 'https://coder-100days.herokuapp.com/images/heart_off.png';
       $favClass = 'fav-icon';
       if ($isFavorited) {
         $heartIcon = 'https://coder-100days.herokuapp.com/images/heart_on.png';
         $favClass .= ' favorited';
       }

       $row_html = '<li>';
       $row_html .= '<time class="cbp_tmtime" datetime="'.$sCreatedAt.'"><span>'.$sCreatedYMD.'</span> <span>'.$sCreatedHI.'</span></time>';
       $row_html .= '<div class="cbp_tmicon"><img class="avator" src="'.$sProfileImageUrl.'"/></div>';
       $row_html .= '<div class="cbp_tmlabel" data-username="'.$sScreenName.'" data-tweet-url="'.$tweetURL.'">';
       if (!$isUserMode) {
         $row_html .= '<h2>'.$sName.'</h2>';
       }
       $row_html .= '<p>'.$sText.'</p>';
       $row_html .= '<div><img class="'.$favClass.'" data-tweet-id="'.$sIdStr.'" src="'.$heartIcon.'">';
       $row_html .= '<span class="show_on_tweet" data-username="'.$sScreenName.'" data-tweet-url="'.$tweetURL.'">twitterで見る</span></div>';
       $row_html .= '</div></li>';

       $result .=$row_html;
   }

   $result .= '</ul>';
   return $result;
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
     // \Debugbar::info($tweets);
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
        return false;
    }
    $twitter = new TwitterOAuth($oauth_token,$oauth_token_secret);

    $token = $twitter->oauth('oauth/access_token', array(
        'oauth_verifier' => $request->oauth_verifier,
        'oauth_token' => $request->oauth_token,
    ));

    session(array(
      'oauth_token'=>$token['oauth_token'],
      'oauth_token_secret'=>$token['oauth_token_secret']
    ));

    return true;
  }
}
