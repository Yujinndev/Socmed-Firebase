<aside class="sidebar">
  <img src=".././assets/favicon/favicon-32x32.png" class="img-fluid m-3"> Twit Twit 

  <a class="sidebar-items active" href="home.php">
    <span class="material-icons"> home </span>
    <h2 class="mt-2">Home</h2>
  </a>

  <!-- <a class="sidebar-items" href="">
    <span class="material-icons"> search </span>
    <h2 class="mt-2">Explore</h2>
  </a> -->

  <a class="sidebar-items" href="profile.php?id=<?= $user->id(); ?>">
    <span class="material-icons"> perm_identity </span>
    <h2 class="mt-2">Profile</h2>
  </a>

  <a class="sidebar-items">
    <span class="material-icons"> more_horiz </span>
    <h2 class="mt-2">More</h2>
  </a>
  <a class="sidebar-tweet btn btn-outline-danger" href=".././controller/process.php?logout">Logout</a>
</aside>