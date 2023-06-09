<?php
  session_start();

  putenv("FIREBASE_AUTH_EMULATOR_HOST=localhost:9099");
  putenv("FIRESTORE_EMULATOR_HOST=localhost:8080");

  require_once '../vendor/autoload.php';
  use Google\Cloud\Firestore\FirestoreClient;
  use Kreait\Firebase\Factory;

  $firebase = new FirestoreClient([
    // 'keyFilePath' => '..\utils\infoman2-g2-cc907-firebase-adminsdk-l4tpo-d9db058134.json',
    'projectId' => 'infoman2-g2-cc907'
  ]);

  $factory = (new Factory)->withServiceAccount('..\utils\infoman2-g2-cc907-firebase-adminsdk-l4tpo-d9db058134.json');

  $postRef = $firebase->collection('posts')->orderBy('date', 'desc');
  $userRef = $firebase->collection('users');
  
  $users = $userRef->documents();
  $posts = $postRef->documents();

  $postsCount = $posts->size();
?>