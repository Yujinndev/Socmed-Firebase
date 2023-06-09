$(document).ready(function() {
  let submitted = false; /* FLAG TO TRACK FIRST FORM SUBMIT */

  /* EVERY INPUT EVENT RE-VALIDATE FORM (REAL-TIME VALIDATION) */
  $('#username, #firstname, #lastname, #email, #password, #confirmpassword').on('input', function() {
    if (submitted) {
      validateForm();
    }
  }); 

  /* FUNCTION FOR CUSTOM VALIDATION */
  function validateForm() {
    let valid = true; 

    let username = $('#username').val();
    let firstname = $('#firstname').val();
    let lastname = $('#lastname').val();
    let email = $('#email').val();
    let password = $('#password').val();
    let confirmpassword = $('#confirmpassword').val();

    /* USERNAME CHECKER */
    if (username === '') {
      valid = false;
      $('#username').addClass('is-invalid');
      $('#username').closest('.form-floating').find('.invalid-feedback').text('Username is required');
    } else if (username.length < 6) {
      valid = false;
      $('#username').removeClass('is-valid').addClass('is-invalid');
      $('#username').closest('.form-floating').find('.invalid-feedback').text('Username should have at least 6 characters');
    } else {
      $('#username').removeClass('is-invalid').addClass('is-valid');
      $('#username').closest('.form-floating').find('.invalid-feedback').text('');
    }

    /* FIRST NAME CHECKER */
    if (firstname === '') {
      valid = false;
      $('#firstname').addClass('is-invalid');
      $('#firstname').closest('.form-floating').find('.invalid-feedback').text('First Name is required');
    } else {
      $('#firstname').removeClass('is-invalid').addClass('is-valid');
      $('#firstname').closest('.form-floating').find('.invalid-feedback').text('');
    }

    /* LAST NAME CHECKER */
    if (lastname === '') {
      valid = false;
      $('#lastname').addClass('is-invalid');
      $('#lastname').closest('.form-floating').find('.invalid-feedback').text('Last Name is required');
    } else {
      $('#lastname').removeClass('is-invalid').addClass('is-valid');
      $('#lastname').closest('.form-floating').find('.invalid-feedback').text('');
    }

    /* EMAIL CHECKER */
    if (email === '') {
      valid = false;
      $('#email').addClass('is-invalid');
      $('#email').closest('.form-floating').find('.invalid-feedback').text('Email is Required');
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
      valid = false;
      $('#email').addClass('is-invalid');
      $('#email').closest('.form-floating').find('.invalid-feedback').text('Invalid email');
    } else {
      $('#email').removeClass('is-invalid').addClass('is-valid');
      $('#email').closest('.form-floating').find('.invalid-feedback').text('');
    }

    /* PASSWORDS CHECKER */
    if (password === '') {
      valid = false;
      $('#password').addClass('is-invalid');
      $('#password').closest('.form-floating').find('.invalid-feedback').text('Password is required');
    } else if (password.length < 8) {
      valid = false;
      $('#password').addClass('is-invalid');
      $('#password').closest('.form-floating').find('.invalid-feedback').text('Password must be at least 8 characters');
    } else {
      $('#password').removeClass('is-invalid').addClass('is-valid');
      $('#password').closest('.form-floating').find('.invalid-feedback').text('');
    }

    if (confirmpassword === '') {
      valid = false;
      $('#confirmpassword').addClass('is-invalid');
      $('#confirmpassword').closest('.form-floating').find('.invalid-feedback').text('Confirm Password is required');
    } else if (password !== confirmpassword) {
      valid = false;
      $('#confirmpassword').addClass('is-invalid');
      $('#confirmpassword').closest('.form-floating').find('.invalid-feedback').text('Passwords do not match');
    } else {
      $('#confirmpassword').removeClass('is-invalid').addClass('is-valid');
      $('#confirmpassword').closest('.form-floating').find('.invalid-feedback').text('');
    }

    return valid; /* RETURN VALID SHOULD BE TRUE */
  }

  /* WHEN FORM IS SUBMITTED */
  $('#signup-form').submit(function(event) {
    event.preventDefault();
    submitted = true; /* MAKE THE TRUE SO IT WILL TELL THAT THIS WAS SUBMITTED */

    /* IF FUNCTION VALIDATE FORM RETURNS TRUE OR NO ERRORS OCCURED IN THE INPUTS */
    if (validateForm()) { 
      /* GET DATA FORM THE FORM */
      let formData = {
        signup: true,
        username: $('#username').val(),
        firstname: $('#firstname').val(),
        lastname: $('#lastname').val(),
        email: $('#email').val(),
        password: $('#password').val(),
      };
      
      /* MAKE THE FORM BUTTON DISABLED AND TEXT CHANGE */
      let submitButton = $('#signup-form button[type="submit"]');
      submitButton.prop('disabled', true);
      submitButton.text('Registering ...');

      /* AJAX REQUEST PROCESS */
      $.ajax({
        type: 'POST',
        url: '../controller/process.php',
        data: formData,
        dataType: 'json',
        success: function(response) {

          /* FORM BUTTON BACK TO DEFAULT */
          submitButton.prop('disabled', false);
          submitButton.text('Register');

          if (response.exists) {
            /* EMAIL ALREADY EXISTS */
            $('#email').addClass('is-invalid');
            $('#email').closest('.form-floating').find('.invalid-feedback').text('Email already taken, try another one');
          } else if (response.success) {
            
            /* DISPLAY SUCCESS MESSAGE WITH COUNTDOWN TIMER */
            let countdown = 3;
            $('#status').text(response.message + ' Redirecting to login in ' + countdown + ' seconds');
            $('#status-alert').removeClass('d-none').addClass('alert-success');

            /* CLEAR FORM */
            $('#signup-form')[0].reset();

            /* START COUNTDOWN TIMER */
            let timer = setInterval(function() {
              countdown--;
              $('#status').text(response.message + ' Redirecting to login in ' + countdown + ' seconds');
              if (countdown === 0) {
                clearInterval(timer);
                window.location.href = '../index.php';
              }
            }, 1000);
          } else {
            $('#status').text(response.message);
            $('#status-alert').removeClass('d-none').addClass('alert-warning');
          }
        },
        error: function(xhr, status, error) {
          /* HANDLE AJAX ERROR */
          alert('Error: ' + error);
        }
      });
    }
  });

  $('#togglePassword').click(function() {
    togglePasswordVisibility('#password', $(this));
  });
  
  $('#toggleConfirmpassword').click(function() {
    togglePasswordVisibility('#confirmpassword', $(this));
  });
  
  function togglePasswordVisibility(inputField, icon) {
    const passwordInput = $(inputField);
  
    if (passwordInput.attr('type') === 'password') {
      passwordInput.attr('type', 'text');
      icon.find('i').removeClass('bi-eye-slash').addClass('bi-eye');
    } else {
      passwordInput.attr('type', 'password');
      icon.find('i').removeClass('bi-eye').addClass('bi-eye-slash');
    }
  }  
});