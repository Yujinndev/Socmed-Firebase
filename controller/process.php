<?php
  require_once '../vendor/autoload.php';
  require_once '../utils/firebase.php';
  
  use Google\Cloud\Firestore\FieldValue;
  use Kreait\Firebase\Exception\Auth\EmailExists;

  if (isset($_POST['tweet'])) {
    $userid = $_POST['userid']; 
    $body = $_POST['body'];

    $firebase->collection('posts')->add([
      'userid' => $userid,
      'body' => $body,
      'date' => FieldValue::serverTimestamp(),
      'reactions' => 0,
      'comments' => 0,
      'shares' => 0
    ]);

    header('Location: ../views/home.php');
    exit();
  }

  if (isset($_GET['like'])) {
    $postId = $_GET['like'];
    $uid = $_SESSION['uid'];
  
    // Check if the user has already liked the post
    $likeQuery = $firebase->collection('post_likes')
      ->where('postid', '==', $postId)
      ->where('userid', '==', $uid)
      ->documents();

    $likeExists = false;

    foreach ($likeQuery as $likeDoc) {
      $likeExists = true;
      $likeDoc->reference()->delete();
      break;
    }
  
    if (!$likeExists) {
      $firebase->collection('post_likes')->add([
        'postid' => $postId,
        'userid' => $uid,
        'date' => FieldValue::serverTimestamp()
      ]);
    }
  
    $lastUrl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
    header('Location: ' . $lastUrl);
    exit();
  }

  if (isset($_POST['comment'])) {
    $comment = $_POST['content'];
    $userid = $_POST['userid'];
    $postid = $_POST['postid'];

    $firebase->collection('post_comment')->add([
      'date' => FieldValue::serverTimestamp(),
      'userid' => $userid,
      'postid' => $postid,
      'comment' => $comment
    ]);

    header('Location: ../views/post.php?id=' . $postid);
    exit();
  }

  if(isset($_GET['delete-comment'])) {
    $commentId = $_GET['delete-comment'];
    $postid = $_GET['post'];

    $commentRef = $firebase->collection('post_comment')->document($commentId)->delete();

    header('Location: ../views/post.php?id=' . $postid);
    exit();
  }

  if (isset($_POST['signup'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];

    $personFields = [
      'email' => $email,
      'password' => $password,
    ];

    $auth = $factory->createAuth();

    try {
      $user = $auth->createUser($personFields);
      $firestore = $factory->createFirestore();

      $firestore->database()->collection('users')->document($user->uid)->set([
        'email' => $email,
        'password' => $password,
        'username' => '@' . $username,
        'fullname' => $firstname . ' ' . $lastname,
        'bio' => 'Tap to edit',
        'avatar' => 'https://www.pngkey.com/png/full/73-730477_first-name-profile-image-placeholder-png.png',
      ]);

      // Return success response
      $response = [
        'success' => true,
        'message' => 'Registration Successful!'
      ];
    } catch(EmailExists $err) {
      $response = [ 'exists' => $err ];
    } catch(Exception $err) {
      $response = [
        'success' => false,
        'message' => 'Error: ' . $err->getMessage()
      ];
    }
    echo json_encode($response);
    exit();
  }

  if (isset($_POST['updateuser'])) {
    $newUsername = $_POST['username'];
    $newFullname = $_POST['fullname'];
    $newBio = $_POST['bio'];
    $uid = $_SESSION['uid'];

    try {
      $userRef->document($uid)->set([
        'username' => $newUsername,
        'fullname' => $newFullname,
        'bio' => $newBio,
      ], ['merge' => true]);

      $status = 'success';
      $message = 'User data updated successfully.';
    } catch (\Exception $e) {
      $status = 'error';
      $message = 'Failed to update user data: ' . $e->getMessage();
    }

    // Prepare the response
    $response = [
        'status' => $status,
        'message' => $message,
    ];

    // Send the JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
  }

  if (isset($_POST['signin'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $auth = $factory->createAuth();

    try {
      $signInResult = $auth->signInWithEmailAndPassword($email, $password);
      $_SESSION['uid'] = $signInResult->firebaseUserId();

      // Return success response 
      $response = ['success' => true];
    } catch(\Throwable $err) {
      $response = ['failed' => true];
    }
    echo json_encode($response);
    exit();
  }

  if (isset($_GET['logout'])) {
    session_start(); 
    $_SESSION = array();
    session_destroy();
    
    header('Location: ../index.php');
    exit();
  }

  if (isset($_POST['search'])) {
    $searchQuery = $_POST['search'];
  
    $query = $firebase->collection('users')
      ->where('username', '>=', $searchQuery)
      ->where('username', '<', $searchQuery . "\uf8ff")
      ->documents();
  
    $searchResults = [];
    foreach ($query as $document) {
      $searchResults[] = $document->data();
    }
  
    if (!empty($searchResults)) {
      foreach ($searchResults as $result) {
        echo '<p>' . $result['fullname'] . '</p>';
      }
    } else {
      echo '<p>No results found.</p>';
    }
  }

?>