$(function() {

  $userView = $('#user-view');
  $listItems = $('#list-items');
  $showAllBtn = $('#show-all');
  $showMore = $('#show-more-button');
	$footer = $('#footer');
  $dispName = $('#user-display-name');
	$search = $('#search');
	$searchQuery = $("#search-query");
  var searchedUsername = '';

  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });

	$('.fav-icon').click(function(event) {
      _favoreteClickAction($(this));
	});

	$('.cbp_tmlabel').dblclick(function(event) {
      _favoreteClickAction($($($(this).children()[2]).children()[0]));
	});

	$('.user-name').click(function(event) {
     var username = $(this).attr('data-username');
		 if (!username || username === '') {
			 return;
		 }
		 window.open('https://twitter.com/'+username, '_blank');
	 });

  $('.show_on_tweet').click(function(event) {
		var tweetURL = $(this).attr('data-tweet-url');
		if (!tweetURL || tweetURL === '') {
			return;
		}
		window.open(tweetURL, '_blank');
	});

	$("#search-submit").click(function(e){
		$userView.empty();
		var query = $("#search-query").val();
		_showSearchByUserTweets('twitter/search/tweets', {query:query}, '検索ワード：'+query);
	});

	$searchQuery.keypress(function(e){
	  if(e.which === 13){
	    $("#search-submit").click();
	  }
	});

  $('.avator').hover(
		function() {
		 var username = $(this).attr('data-username');
 		 if (!username || username === '') {
 			 return;
 		 }
 		 var userDispName = $(this).attr('data-display-name');
		 if (!userDispName || userDispName === '') {
 			 return;
 		 }
		 searchedUsername = username;
 		 $userView.empty();
		 $footer.hide();
 		 $.ajax({
         url : "twitter/get/tweets",
         type : "GET",
         data : {username:username}
     }).done(function(response, textStatus, xhr) {
 	      $dispName.text(userDispName);
        $userView.append(response);
				$footer.show();
 		    _bindClickEvent();
     }).fail(function(xhr, textStatus, errorThrown) {
         console.log("ajax通信に失敗しました");
 		    $listItems.show();
     });
		},
		function() {

		}
	);

	$('.avator').click(function(event) {
     var username = $(this).attr('data-username');
		 if (!username || username === '') {
			 return;
		 }
		 var userDispName = $(this).attr('data-display-name');
		 if (searchedUsername === username) { // 取得済
			 $dispName.show();
			 $userView.show();
			 $listItems.hide();
			 $showAllBtn.show();
			 $showMore.hide();
			 $search.hide();
			 return;
		 }

		 $userView.empty();
		 $footer.hide();
		 _showSearchByUserTweets('twitter/get/tweets', {username:userDispName} ,userDispName);
	});

	$showMore.click(function(event) {
			var query = '?f=tweets&vertical=default&q=%23100DaysOfCode%20exclude%3Aretweets';
			window.open('https://twitter.com/search'+query, '_blank');
	});

	$showAllBtn.click(function(event) {
		 $dispName.hide();
		 $userView.hide();
		 $showAllBtn.hide();
		 $listItems.show();
		 $showMore.show();
		 $search.show();
		 $searchQuery.val('');
	});

	function _postFavoritesAction(targetId, doCreate) {
		$.ajax({
			 url : "twitter/favorite",
			 type : "POST",
			 data : {targetId:targetId,doCreate:doCreate}
	 }).done(function(response, textStatus, xhr) {

	 }).fail(function(xhr, textStatus, errorThrown) {
			 console.log("ajax通信に失敗しました");
	 });
  }

  function _showSearchByUserTweets(url, data, dispName) {
		$.ajax({
			 url : url,
			 type : "GET",
			 data : data
	 }).done(function(response, textStatus, xhr) {
			 $dispName.text(dispName);
			 $dispName.show();
			 $userView.append(response);
			 $userView.show();
			 $listItems.hide();
			 $showAllBtn.show();
			 $showMore.hide();
			 $search.hide();
			 $footer.show();
			 _bindClickEvent();
	 }).fail(function(xhr, textStatus, errorThrown) {
			 console.log("ajax通信に失敗しました");
			 $listItems.show();
	 });
  }

  function _bindClickEvent() {
		$('#user-timeline .show_on_tweet').click(function(event) {
			var tweetURL = $(this).attr('data-tweet-url');
			if (!tweetURL || tweetURL === '') {
				return;
			}
			window.open(tweetURL, '_blank');
		});

		$('#user-timeline .fav-icon').click(function(event) {
				_favoreteClickAction($(this));
		});
	}

	function _favoreteClickAction($target) {
		var targetId = $target.attr('data-tweet-id');
		var doCreate = !$target.hasClass('favorited');
		if (!targetId || targetId === '') {
			return;
		}
		_postFavoritesAction(targetId, doCreate);
		if (doCreate) {
			$target.addClass('favorited');
			$target.attr('src', 'https://coder-100days.herokuapp.com/images/heart_on.png');
		} else {
			$target.removeClass('favorited');
			$target.attr('src', 'https://coder-100days.herokuapp.com/images/heart_off.png');
		}
	}

});
