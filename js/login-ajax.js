$(document).ready(function() {
  let submitted = false; /* FLAG TO TRACK FIRST FORM SUBMIT */

  /* EVERY INPUT EVENT RE-VALIDATE FORM (REAL-TIME VALIDATION) */
  $('#email, #password').on('input', function() {
    if (submitted) {
      validateForm();
    }
  }); 

  /* FUNCTION FOR CUSTOM VALIDATION */
  function validateForm() {
    let valid = true; 
    let email = $('#email').val();
    let password = $('#password').val();

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
    return valid; /* RETURN VALID SHOULD BE TRUE */
  }

  /* WHEN FORM IS SUBMITTED */
  $('#signin-form').submit(function(event) {
    event.preventDefault();
    submitted = true; /* MAKE THE TRUE SO IT WILL TELL THAT THIS WAS SUBMITTED */

    /* IF FUNCTION VALIDATE FORM RETURNS TRUE OR NO ERRORS OCCURED IN THE INPUTS */
    if (validateForm()) { 
      /* GET DATA FORM THE FORM */
      let formData = {
        signin: true,
        email: $('#email').val(),
        password: $('#password').val()
      };
      
      /* MAKE THE FORM BUTTON DISABLED AND TEXT CHANGE */
      let submitButton = $('#signin-form button[type="submit"]');
      submitButton.prop('disabled', true);
      submitButton.text('Logging in ...');

      /* AJAX REQUEST PROCESS */
      $.ajax({
        type: 'POST',
        url: '../controller/process.php',
        data: formData,
        dataType: 'json',
        success: function(response) {

          /* FORM BUTTON BACK TO DEFAULT */
          submitButton.prop('disabled', false);
          submitButton.text('Login');

          if (response.failed) {
            /*  */
            $('#email').addClass('is-invalid');
            $('#password').addClass('is-invalid');
            $('#password').closest('.form-floating').find('.invalid-feedback').text('Credendials you\'ve entered is incorrect');

          } else if (response.success) {
            window.location.href = '../views/home.php';
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
    const passwordInput = $('#password');
    const icon = $(this).find('i');
  
    if (passwordInput.attr('type') === 'password') {
      passwordInput.attr('type', 'text');
      passwordInput.text('Hide')
      icon.removeClass('bi-eye-slash').addClass('bi-eye');
    } else {
      passwordInput.attr('type', 'password');
      icon.removeClass('bi-eye').addClass('bi-eye-slash');
    }
  });
  
});