<?php

namespace App\Http\Controllers;

use App\Services\TwitterService as TwitterService;
use Illuminate\Http\Request;

class TwitterApiController extends Controller
{
  private $twitterService;

  public function __construct(TwitterService $twitterService) {
      $this->twitterService = $twitterService;
  }

  public function getTimelineElements(Request $request) {
    $isUserMode = false;
    $data = $request->all();
    \Debugbar::info($data);
    if ($data['query'] && $data['query'] !== '') {
      $search_param = "#100DaysOfCode ".$data['query']." exclude:retweets";
    } else if ($data['username'] && $data['username'] !== '') {
      $search_param = "#100DaysOfCode exclude:retweets from:".$data['username'];
      $isUserMode = true;
    }
    $tweetsElements = $this->twitterService->getTimelineHTML($search_param, "ja", $isUserMode);
    return $tweetsElements;
  }
}
