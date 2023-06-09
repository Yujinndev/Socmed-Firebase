const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

$(document).ready(function() {
  /* Get the current page URL */
  let currentUrl = window.location.href;

  /* Remove the active class from all sidebar links */
  $('.sidebar-items').removeClass('active');

  /* Add the active class to the corresponding sidebar link */
  $('.sidebar-items').each(function() {
    let linkUrl = $(this).attr('href');
    if (currentUrl.indexOf(linkUrl) !== -1) {
      $(this).addClass('active');
    }
  });

  let feed = document.querySelector('.feed');
  let feedHeader = document.querySelector('.feed-header');

  feed.addEventListener('scroll', function() {
    let scrollPosition = feed.scrollTop;

    if (scrollPosition > 10) {
      feedHeader.classList.add('blur');
    } else {
      feedHeader.classList.remove('blur');
    }
  });

  // script.js
$('.pencil-icon').click(function() {
  $('.pencil-icon').hide();
  $('.save-icon').show();

  // Make the elements editable
  $('#name').attr('contenteditable', 'true');
  $('#username').attr('contenteditable', 'true');
  $('#bio').attr('contenteditable', 'true');
});

$('.save-icon').click(function() {
  var newFullname = $('#name').text();
  var newUsername = $('#username').text();
  var newBio = $('#bio').text();

  var data = {
    updateuser: true,
    fullname: newFullname,
    username: newUsername,
    bio: newBio
  };

  $.ajax({
    url: '../controller/process.php',
    type: 'POST',
    data: data,
    dataType: 'json',
    success: function(response) {
      console.log('Changes saved successfully.');
      if (response.status === 'success') {
        $('#alertMessage').removeClass('d-none');
        $('#alertMessage').removeClass('alert-danger').addClass('alert-success');
        $('#alertMessage strong').html('Success!');
        $('#alertMessage').html(response.message);

        // Reload the page after 3 seconds
        setTimeout(function() {
            location.reload();
        }, 1500);
    } else {
        $('#alertMessage').removeClass('d-none');
        $('#alertMessage').removeClass('alert-success').addClass('alert-danger');
        $('#alertMessage strong').html('Error!');
        $('#alertMessage').html(response.message);
    }
    },
    error: function(xhr, status, error) {
      console.error(error);
      // Handle the error, if necessary
    }
  });

  // Make the elements non-editable
  $('#name').attr('contenteditable', 'false');
  $('#username').attr('contenteditable', 'false');
  $('#bio').attr('contenteditable', 'false');

  $('.pencil-icon').show();
  $('.save-icon').hide();
});

  
// Event handler for input event on the search input field
// $('#searchInput').on('input', function() {
//   $.ajax({
//     url: '../views/home.php', // Replace with the actual path to your search PHP file
//     type: 'POST',
//     data: { search: $('#searchInput').val() },
//     dataType: 'json', // Expecting JSON response
//     success: function(response) {
//       // Clear previous search results
//       $('#searchResults').empty();

//       // Append names to search results container
//       response.forEach(function(name) {
//         $('#searchResults').append('<div>' + name + '</div>');
//       });
//     }
//   });
// });



});



// // Initialize Firebase
// const firebaseConfig = {
//   // Add your Firebase configuration here
// };

// firebase.initializeApp(firebaseConfig);

// // Get a reference to the Firebase database
// const database = firebase.database();

// // Function to perform the search
// function searchUsers() {
//   const searchInput = document.getElementById('search-input');
//   const searchTerm = searchInput.value.trim().toLowerCase();
//   const searchResultsDiv = document.getElementById('search-results');

//   // Clear previous search results
//   searchResultsDiv.innerHTML = '';

//   // Perform the search query in Firebase
//   const usersRef = database.ref('users');
//   usersRef.orderByChild('name')
//     .startAt(searchTerm)
//     .endAt(searchTerm + '\uf8ff')
//     .once('value', (snapshot) => {
//       snapshot.forEach((childSnapshot) => {
//         const user = childSnapshot.val();
//         const userName = user.name;
//         const userHandle = user.handle;

//         // Create a div to display each search result
//         const searchResultDiv = document.createElement('div');
//         searchResultDiv.classList.add('search-result');
//         searchResultDiv.innerHTML = `<p>Name: ${userName}</p><p>Handle: ${userHandle}</p>`;

//         // Append the search result div to the search results div
//         searchResultsDiv.appendChild(searchResultDiv);
//       });
//     });
// }

// // Add an event listener to the search form
// const searchForm = document.querySelector('.search');
// searchForm.addEventListener('submit', (e) => {
//   e.preventDefault();
//   searchUsers();
// });