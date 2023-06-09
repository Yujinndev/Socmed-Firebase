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

  <?php 
    if(isset($_GET['id'])) :
      $userid = $_GET['id'];
      $user = $userRef->document($userid)->snapshot();
      $posts = $firebase->collection('posts')->orderBy('date', 'desc')->where('userid', '=', $userid)->documents();

      $tweetsCounts = $posts->size();
  ?>   
    <!-- FEED STRUCTURE -->
    <div class="feed">
      <div class="feed-header d-flex justify-content-between">
        <div class="d-flex">
          <a href="home.php" class="me-4 mt-1"><i class="bi bi-arrow-left"></i></a>
          <div class="d-flex flex-column">
            <h2 style="margin-bottom: -3px">Profile</h2>
            <small><?php echo $tweetsCounts . ' tweets' ?></small>
          </div>
        </div>
        <div class="mt-2">
    <?php if ($userid === $uid) : ?>
      <i class="bi bi-pencil-square pencil-icon"></i>
      <i class="bi bi-check-circle save-icon" style="display: none;"></i>
    <?php endif; ?>
    </div>
</div>

<div class="alert alert-warning alert-dismissible fade show d-none" role="alert" id="alertMessage">
  <strong>Holy guacamole!</strong> You should check in on some of those fields below.
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>


<!-- ABOUT USER PROFILE -->
<div class="container">
  <div class="post-user border-bottom border-top py-3">
    <img src="<?php echo $user['avatar']; ?>" style="width: 10rem; height: 10rem; aspect-ratio: 3/2" />
    <div class="post-userdetail mt-3 ms-1 d-flex flex-column w-100">
      <h2 class="input-user" id="name" contenteditable="false"><?php echo $user['fullname']; ?></h2>
      <span class="post-special" id="username" contenteditable="false"><?php echo $user['username']; ?></span>
      <p class="bio" id="bio" contenteditable="false"><i class="bi bi-chat-right-quote-fill m-2"></i> <?php echo $user['bio']; ?> </p>
    </div>
  </div>
</div>
        <!-- USER ALL POSTS -->
<?php if ($tweetsCounts > 0) : 
    foreach ($posts as $post) :  
      $timeAgo = getTimeAgo($post['date'], 'Asia/Manila');
      $userPosted = $userRef->document($post['userid'])->snapshot(); ?>
      <div class="post">
        <div class="post-user">
          <img src=" <?php echo $userPosted['avatar']; ?> "/>
          <div class="post-userdetail">
            <h3> <?php echo $userPosted['fullname']; ?> </h3>
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
          <div class="actions mt-1">
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
            <p> <?php echo $timeAgo; ?></p>
          </div>
        </div>
      </div>
  <?php endforeach; 
      else: echo '<div class="d-flex justify-content-center">No tweets ..</div>';
      endif; 
    endif; ?>
    </div>

  <!-- JS FILES -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../js/ui.js"></script>
</body>
</html>