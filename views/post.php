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
      <div class="feed-header d-flex">
        <a href="home.php"><i class="bi bi-arrow-left me-4"></i></a>
        <h2>Tweet</h2>
      </div>

      <!-- POSTS -->
  <?php 
    if(isset($_GET['id'])) :
      $postid = $_GET['id'];
      $post = $firebase->collection('posts')->document($postid)->snapshot();
      $timeAgo = getTimeAgo($post['date'], 'Asia/Manila');
      $userPosted = $userRef->document($post['userid'])->snapshot();

      // GET ALL COMMENTS
      $comments = $firebase->collection('post_comment')->orderBy('date', 'desc')->where('postid', '=', $postid)->documents();
      $commentsCount = $comments->size();
  ?>    
      <div class="post" style="border: none">
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
          
        <?php $likedUsers = getUsersWhoLikedPost($firebase, $postid);
            if (!empty($likedUsers)) {
        ?>    <div class="mt-1 mb-n1">
                <div class="liked-users mt-1 ms-1"> Liked by:
        <?php 
                foreach ($likedUsers as $index => $likedUser) { ?>
                  <span class="text-danger"> <?php echo $likedUser['username'] ?> </span> <?= $index !== count($likedUsers) - 1 ? ', ' : '';
                } echo '
                </div>
              </div>';
            } 
        ?>

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
            <p> <?php echo $timeAgo; ?> </p>
          </div>
        </div>

        <div class="post-comments">
          <?= $commentsCount > 0 ? '<span class="card-title my-3">All Comments <i class="bi bi-arrow-down-short"></i>' : '' ?></span>
          <?php
foreach ($comments as $comment) :
    $userCommented = $userRef->document($comment['userid'])->snapshot();
    $timeAgo = getTimeAgo($comment['date'], 'Asia/Manila');

    $canDelete = ($comment['userid'] === $uid); // Check if the comment belongs to the logged-in user
?>

    <div class="post-user">
        <img src="<?php echo $userCommented['avatar']; ?>" />
        <div class="post-userdetail">
            <a data-bs-toggle="tooltip" data-bs-placement="right" title="View profile" href="profile.php?id=<?php echo $userCommented->id(); ?>">
                <h3><?php echo $userCommented['fullname']; ?></h3>
            </a>
            <span class="post-special"><?php echo $userCommented['username']; ?></span>
        </div>
        <div class="text-secondary ms-2 mt-1 d-flex" style="font-size: 14px; margin-top: 1px">
            <i class="bi bi-check2-all me-1"></i>
            <p><?php echo $timeAgo; ?></p>
        </div>

    </div>

    <div class="post-body border-bottom">
        <p><?php echo $comment['comment']; ?></p>
        <?php if ($canDelete) : ?>
            <!-- Display delete button or option here -->
            <a href="../controller/process.php?delete-comment=<?php echo $comment->id() ?>&post=<?=$postid?>" class="btn btn-danger">Delete</a>
        <?php endif; ?>
    </div>
<?php endforeach; ?>


          <div class="container mt-2">
            <form action="../controller/process.php" method="post">
              <div class="mb-3 d-flex">
                <input type="hidden" name="userid" value="<?= $uid ?>">
                <input type="hidden" name="postid" value="<?= $postid ?>">
                <input type="text" class="form-control border border-bottom" name="content" placeholder="Enter comment ...">
                <button type="submit" name="comment" class="btn"><i class="bi bi-send-fill me-2"></i></button>
              </div>
            </form>
          </div>
        </div>
      </div>
  <?php endif; ?>
    </div>

  <!-- JS FILES -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../js/ui.js"></script>
</body>
</html>