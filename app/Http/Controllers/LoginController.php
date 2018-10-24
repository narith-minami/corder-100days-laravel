<?php

namespace App\Http\Controllers;

use App\Services\TwitterService as TwitterService;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    private $twitterService;

    public function __construct(TwitterService $twitterService) {
        $this->twitterService = $twitterService;
    }

    public function twitter() {
       $url = $this->twitterService->authenticate();
       return redirect($url);
    }

    public function twitterCallback(Request $request) {
      if ($this->twitterService->verify($request)) {
        // return redirect('/list');
        $tw = $this->twitterService->getTimeline("#100DaysOfCode exclude:retweets", "ja");
        $tweets = json_encode($tw);
        return view('list', ['tweets'=>$tweets]);
      } else {
        return redirect('/');
      }
    }
}
