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

  /**
   * @return {string} html
   */
  public function searchTimeline(Request $request) {
    $data = $request->all();
    \Debugbar::info($data);
    $query = $data['query'];
    if (empty($query)) {
       return null;
    }
    $search_param = "#100DaysOfCode ".$query." exclude:retweets";
    $tweetsElements = $this->twitterService->getTimelineHTML($search_param, "ja", false);
    return $tweetsElements;
  }

  /**
   * @return {string} html
   */
  public function getTimelineElements(Request $request) {
    $data = $request->all();
    \Debugbar::info($data);
    $username = $data['username'];
    if (empty($username)) {
      return null;
    }
    $search_param = "#100DaysOfCode exclude:retweets from:".$username;
    $tweetsElements = $this->twitterService->getTimelineHTML($search_param, "ja", true);
    return $tweetsElements;
  }
}
