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

  public function show(Request $request) {
    $tweets = $this->twitterService->getTimeline("#100DaysOfCode exclude:retweets", "ja");
    return view('list', ['tweets'=>$tweets]);
  }
}
