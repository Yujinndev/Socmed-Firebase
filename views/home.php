<?php
  require_once '../utils/firebase.php';
  include_once './components/queries.php';

  if (isset($_SESSION['uid'])) {
    $uid = $_SESSION['uid'];

    $user = $firebase->collection('users')->document($uid)->snapshot();
  } else {
    header('Location: ../controller/process.php?logout=true');
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Twit twit</title>
  <link rel="shortcut icon" type="image/png" href="../assets/favicon/favicon.ico" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
  <link rel="stylesheet" href="../css/styles.css" />
</head>
<body>
  <!-- LEFT SIDEBAR STRUCTURE -->
  <?php require_once 'components/left-sidebar.php'; ?>
  <!-- LEFT SIDEBAR STRUCTURE END -->

    <!-- FEED STRUCTURE -->
    <div class="feed">
      <div class="feed-header">
        <h2>Explore</h2>
      </div>
      <div class="tweetBox">
        <form method="POST" action="../controller/process.php">
          <div class="tweetbox-input">
            <img src=" <?php echo $user['avatar']; ?> "/>
            <input type="hidden" name="userid" value="<?php echo $uid; ?>">
            <textarea type="text" name="body" placeholder="What's new, <?php echo $user['fullname']; ?>?"></textarea>
          </div>
          <button class="tweetBox-tweetButton" name="tweet">Tweet</button>
        </form>
      </div>

      <!-- POSTS -->
<?php 
  if ($postsCount > 0) :
    foreach ($posts as $post) :  
      $timeAgo = getTimeAgo($post['date'], 'Asia/Manila');
      $userPosted = $userRef->document($post['userid'])->snapshot();
?>    
      <div class="post">
        <div class="post-user">
          <img src=" <?php echo $userPosted['avatar']; ?> "/>
          <div class="post-userdetail">
            <a data-bs-toggle="tooltip" data-bs-placement="right" title="View profile" href="profile.php?id=<?php echo $userPosted->id(); ?>"><h3> <?php echo $userPosted['fullname']; ?> </h3></a>
            <span class="post-special"><?php echo $userPosted['username']; ?> </span>
          </div>
        </div>

        <div class="post-body">
          <p> <?php echo $post['body']; ?> </p>
          <?php if (isset($post['photo'])): ?>
            <div class="image-container">
              <img src=" <?php echo $post['photo']; ?> "/>
            </div>
          <?php endif; ?>
        </div>
          
        <div class="post-actions">
          <div class="mt-1">
            <?php $hasLiked = checkIfUserLikedPost($firebase, $post->id(), $uid); ?> <!-- FROM COMPONENTS --> 
            <a class="fw-semibold mb-0 fs-6 icons" href="../controller/process.php?like=<?php echo $post->id(); ?>">
              <i class=" <?php echo $hasLiked ? 'bi bi-heart-fill' : 'bi bi-heart'; ?> "></i>
              <span class="like-count"> <?php echo $post['reactions']; ?> </span>
            </a>

            <a class="fw-semibold mb-0 fs-6 icons" href="post.php?id=<?php echo $post->id(); ?>">
              <i class="bi bi-chat"></i>
              <span class="comment-count"> <?php echo $post['comments']; ?> </span>
            </a>

            <a class="fw-semibold mb-0 fs-6 icons">
              <i class="bi bi-send"></i>
              <span class="share-count"> <?php echo $post['shares']; ?></span>
            </a>
          </div>
          <div class="time-ago text-secondary">
            <p> <?php echo $timeAgo; ?> </p>
          </div>
        </div>
      </div>
  <?php 
      endforeach;
    else: echo '<div class="d-flex justify-content-center">No tweets today ..</div>';
    endif;
  ?>
    </div>

    <?php 
if (isset($_POST['search'])) {
  $toSearch = strtolower($_POST['search']);
  $userSearched = array();

  foreach ($users as $user) {
    $userName = strtolower($user['fullname']);

    if (strpos($userName, $toSearch) !== false) {
      $userSearched[] = $user['fullname'];
    }
  }
  
  // Convert the $userSearched array to JSON for sending it to JavaScript
  echo json_encode($userSearched);
  exit; // Terminate the script execution after sending the JSON response
} else {
  $users = array(); // Empty array if no search query
}

?>

<!-- <div class="widgets">
  <div class="widgets-input">
    <form class="search" method="post" id="search-user">
      <button type="submit" class="btn"><span class="material-icons widgets-searchIcon">search</span></button>
      <input type="text" class="mb-2" placeholder="Search username" name="search" id="searchInput">
    </form>
  </div>
  
  <div id="searchResults"></div> 
</div> -->

  <!-- JS FILES -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../js/ui.js"></script>
</body>
</html>