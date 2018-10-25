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
    if ($_POST['query'] && $_POST['query'] !== '') {
      $search_param = "#100DaysOfCode ".$_POST['query']." exclude:retweets";
    } else if ($_POST['username'] && $_POST['username'] !== '') {
      $search_param = "#100DaysOfCode exclude:retweets from:".$_POST['username'];
      $isUserMode = true;
    }
    $tweetsElements = $this->twitterService->getTimelineHTML($search_param, "ja", $isUserMode);
    return $tweetsElements;
  }
}
