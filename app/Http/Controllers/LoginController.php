<?php

namespace App\Http\Controllers;

use App\Services;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    private $twitterService = new TwitterService();

    public function twitter() {
       $url = $this->$twitterService->authenticate();
       return redirect($url);
    }

    public function twitterCallback(Request $request) {
      if ($this->$twitterService->verify($request)) {
        return redirect('/list');
      } else {
        return redirect('/');
      }
    }
}
