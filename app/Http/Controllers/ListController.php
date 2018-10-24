<?php

namespace App\Http\Controllers;

use App\Services\TwitterService as TwitterService;
use Illuminate\Http\Request;

class ListController extends Controller
{
  private $twitterService;

  public function __construct(TwitterService $twitterService) {
      $this->twitterService = $twitterService;
  }

  public function show() {
    $tweets = $this->twitterService->getTimeline("#100DaysOfCode exclude:retweets", "ja");
    \Debugbar::info($tweets);
    return view('list', ['tweets'=>$tweets]);
  }
}
