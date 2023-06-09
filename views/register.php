<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Twit twit</title>
  <link rel="shortcut icon" type="image/png" href="../assets/favicon/favicon.ico" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
  <link rel="stylesheet" href="../css/login.css" />
</head>
<body>
  <section class="h-75 gradient-form" style="background-color: #eee;">
    <div class="container py-5 h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-xl-10">
          <div class="card rounded-3 text-black">
            <div class="row g-0">
              <div class="col-lg-8 ">
                <div class="card-body p-md-5 mx-md-4">

                  <div class="text-center container p-0 d-flex align-items-center mb-3">
                    <img src="../assets/favicon/favicon-32x32.png">
                    <h1 class="mt-3 ms-3">TWIT TWIT</h1>
                  </div>

                  <form class="row needs-validation" id="signup-form" novalidate>
                    <p class="card-text">Create your account ..</p>

                    <div id="status-alert" class="alert alert-dismissible fade show d-none mb-2" role="alert">
                      <span id="status"></span>
                      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>

                    <div class="row mb-2 input">
                      <!-- FIRST NAME -->
                      <div class="col">
                        <div class="form-floating has-validation">
                          <input type="text" class="form-control" id="firstname" name="firstname" placeholder="First Name">
                          <label for="username" class="form-label">First Name</label>
                          <div class="invalid-feedback"></div>
                        </div>
                      </div>
                      <div class="col">
                        <div class="form-floating has-validation">
                          <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Last Name">
                          <label for="username" class="form-label">Last Name</label>
                          <div class="invalid-feedback"></div>
                        </div>
                      </div>
                      <!-- LAST NAME -->
                    </div>

                    <div class="row mb-2 input">
                      <div class="col">
                        <div class="form-floating has-validation">
                          <input type="text" class="form-control" id="username" name="username" placeholder="User123">
                          <label for="username" class="form-label">Username</label>
                          <div class="invalid-feedback"></div>
                        </div>
                      </div>
                    </div>

                    <div class="row mb-2 input">
                      <div class="col">
                        <div class="form-floating has-validation">
                          <input type="email" class="form-control" id="email" name="email" placeholder="user@gmail.com">
                          <label for="email" class="form-label">Email Address</label>
                          <div class="invalid-feedback"></div>
                        </div>
                      </div>
                    </div>

                    <div class="row mb-2 input">
                      <!-- PASSWORD -->
                      <div class="col">
                        <div class="col mb-2 input input-pass">
                          <div class="col">
                            <div class="form-floating has-validation">
                              <input type="password" class="form-control" id="password" name="password" placeholder="*****">
                              <label for="password" class="form-label">Password</label>
                              <div class="invalid-feedback"></div>
                            </div>
                          </div>
                          <button class="btn show-pass" type="button" id="togglePassword"><i class="bi bi-eye-slash"></i></button>
                        </div>
                      </div>
                      <div class="col">
                        <div class="col mb-2 input input-pass">
                          <div class="col">
                            <div class="form-floating has-validation">
                              <input type="password" class="form-control" id="confirmpassword" name="confirmpassword" placeholder="*****">
                              <label for="confirmpassword" class="form-label">Confirm Password</label>
                              <div class="invalid-feedback"></div>
                            </div>
                          </div>
                          <button class="btn show-pass" type="button" id="toggleConfirmpassword"><i class="bi bi-eye-slash"></i></button>
                        </div>
                      </div>
                      <!-- CONFIRM PASSWORD -->
                    </div>

                    <div class="text-center pt-1 pb-1">
                      <button class="btn btn-primary w-75 mb-2 p-2" name="signup" type="submit" id="submit-btn">Register</button>
                    </div>

                    <div class="d-flex align-items-center justify-content-center pb-4">
                      <a class="btn btn-outline-secondary w-75 mt-2 p-2" href="../index.php">Already have an account? Login Now</a>
                    </div>
                  </form>
                </div>
              </div>

              <div class="col-lg-4 d-flex align-items-center gradient-custom-2">
                <div class="text-white px-3 py-4 p-md-5 mx-md-4">
                  <h4 class="mb-2">We are more than just a company</h4>
                  <p class="small mb-0 fs-6">Twit Twit is a social media application designed to connect people from all around the world, fostering meaningful connections, and providing a platform for sharing thoughts, ideas, and experiences. With its user-friendly interface and rich set of features, Twit Twit offers a seamless and enjoyable social networking experience.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- JS FILES -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../js/registration-ajax.js"></script>
</body>
</html>