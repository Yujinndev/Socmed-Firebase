<?php 
  function getTimeAgo($dateTimeString, $timezone) {
    date_default_timezone_set($timezone);

    $dt = new DateTime($dateTimeString, new DateTimeZone('UTC'));
    $dt->setTimezone(new DateTimeZone($timezone));
    $formattedDate = $dt->format('m/d h:i A');
    $currentDateTime = new DateTime();
    $currentDateTime->setTimezone(new DateTimeZone($timezone));
    $previousDateTime = DateTime::createFromFormat('m/d h:i A', $formattedDate);

    $timeDifference = $currentDateTime->diff($previousDateTime);
    $timeAgo = '';

    if ($timeDifference->y > 0) {
      $timeAgo .= $timeDifference->y . 'y, ';
    }

    if ($timeDifference->m > 0) {
      $timeAgo .= $timeDifference->m . 'm, ';
    }

    if ($timeDifference->d > 0) {
      $timeAgo .= $timeDifference->d . 'd, ';
    }

    if ($timeDifference->h > 0) {
      $timeAgo .= $timeDifference->h . 'h, ';
    }

    if ($timeDifference->i > 0) {
      $timeAgo .= $timeDifference->i . 'min ago';
    } elseif ($timeDifference->s > 0) {
      $timeAgo .= 'Just Now';
    }

    $timeAgo = trim($timeAgo, ', ');

    return $timeAgo;
  }

  /* QUERY TO CHECK IF THE LOGGED USER HASLIKED */
  function checkIfUserLikedPost($firebase, $postId, $userId) {
    $likeQuery = $firebase->collection('post_likes')
      ->where('postid', '=', $postId)
      ->where('userid', '=', $userId)
      ->documents();
  
    $hasLiked = false;
  
    foreach ($likeQuery as $likeDoc) {
      $hasLiked = true;
      break;
    }
  
    return $hasLiked;
  }
  
  function getUsersWhoLikedPost($firebase, $postId) {
    $likedUsers = [];
    $postLikes = $firebase->collection('post_likes')
      ->where('postid', '=', $postId)
      ->documents();
  
    foreach ($postLikes as $like) {
      $userId = $like['userid'];
      $user = $firebase->collection('users')->document($userId)->snapshot();
      $likedUsers[] = $user;
    }
  
    return $likedUsers;
  }