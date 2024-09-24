<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\CSRFToken;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Custom styles -->
    <style>
        body {
            padding-top: 70px;
            background-color: #f0f0f0;
        }
        .profile-header {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
        }
        .cover-photo {
            width: 1200px;
            height: 400px;
            background-color: white;
            background-size: cover;
            background-position: center;
            position: relative;
            border-radius: 5px;
            border: 1px solid #e0e0e0;
        }
        .cover-photo-overlay {
            display: none;
            position: absolute;
            bottom: 10px;
            right: 10px;
            background: rgba(0, 0, 0, 0.5);
            color: #fff;
            border-radius: 50%;
            padding: 5px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            z-index: 1;
        }
        .profile-pic {
            width: 250px;
            height: 250px;
            border-radius: 50%;
            border: 2px solid #e0e0e0;
            background: white;
            margin-top: -180px;
            background-size: cover;
            background-position: center;
            position: relative;
            z-index: 2;
        }
        .profile-pic-overlay {
             display: none;
            position: absolute;
            bottom: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.5);
            color: #fff;
            border-radius: 50%;
            padding: 5px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            z-index: 3;
        }
        .profile-info {
            text-align: center;
            margin-top: 10px;
        }
        .nav-tabs .nav-link {
            font-size: 1.25rem;
        }
        .nav-tabs .nav-link.active {
            color: #007bff;
            font-weight: bold;
        }
        .container {
            max-width: 1200px;
        }
        .main-content {
            display: flex;
            flex-direction: column;
            padding: 20px;
        }
        .profile-section, .job-posting-section {
            flex: 1;
        }
        .post-card, .profile-data {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .profile-data {
            margin-bottom: 20px;
        }
        .img-preview {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #ddd;
            margin-top: 10px;
        }
        .job-posting-section {
            display: flex;
            flex-direction: column;
        }

         



    /* Profile Picture Modal Styles */
    .profile-pic-modal .modal-dialog {
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 0;
        max-width: 100%;
        max-height: 100%;
    }
    .profile-pic-modal .modal-content {
        border: none;
        border-radius: 0;
        background: transparent;
        box-shadow: none;
        max-width: 80%;
        max-height: 80%;
    }
    .profile-pic-modal .modal-body {
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        
    }
    .profile-pic-modal .modal-body img {
       
        max-height: 80vh;
        object-fit: contain;
        display: block;
        margin: auto;
    }

    /* Cover Photo Modal Styles */
    .cover-photo-modal .modal-dialog {
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 0;
        max-width: 100%;
        max-height: 100%;
    }
    .cover-photo-modal .modal-content {
        border: none;
        border-radius: 0;
        background: transparent;
        box-shadow: none;
        max-width: 80%;
        max-height: 80%;
        
    }
    .cover-photo-modal .modal-body {
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
     
    }
    .cover-photo-modal .modal-body img {
        max-height: 80vh;
        object-fit: contain;
        display: block;
        margin: auto;
    }

    .job-card {
            margin-bottom: 20px;
        }
        .card-text {
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 7; /* Show only 7 lines initially */
            max-height: 10.5em; /* Adjust this value to match 7 lines */
            white-space: pre-wrap; /* Maintain formatting */
            position: relative; /* Position relative for the "See More" link */
        }
        .job-description {
            position: relative; /* Ensure relative positioning for absolute elements */
        }
        .full-description {
            display: none; /* Initially hide full content */
            white-space: pre-wrap; /* Maintain formatting */
        }
        .see-more {
            display: none; /* Initially hide "See More" */
            cursor: pointer;
            color: #007bff; /* Adjust color as needed */
        }
        .hide-content {
            display: none; /* Initially hide "Hide" */
            cursor: pointer;
            color: #007bff; /* Adjust color as needed */
        }
        .image-container {
            text-align: center; /* Center-align the contents */
            margin: 0 auto; /* Center the container itself */
        }
        #imagePreview {
            display: none; /* Hide the preview initially */
            max-width: 100%;
            max-height: 600px;
            margin-top: 10px;
            margin-left: auto;
            margin-right: auto;
            left: 0;
            right: 0;
            top: 50%;
        }
        .centered-image {
            margin-top: 10px; /* Adjust as needed */
            max-width: 100%;
            max-height: 600px;
        }
        .post-header {
            font-size: 1rem;
            color: #555;
        }


        /* Style the post header */
.post-header {
    display: flex;
    align-items: center;
    justify-content: space-between; /* Ensures space between the content and the gear icon */
    position: relative;
}

/* Ensure the dropdown menu and button are correctly positioned */
.dropdown {
    position: relative; /* So that dropdown menu is positioned relative to this */
}

.dropdown-toggle {
    background: none;
    border: none;
    cursor: pointer;
    font-size: 20px;
    margin-left: 10px; /* Adjust margin as needed */
}

/* Style the dropdown menu */
.dropdown-menu {
    display: none;
    position: absolute;
    right: 0; /* Aligns the dropdown menu to the right side of the button */
    top: 100%; /* Positions it just below the button */
    background-color: #fff;
    border: 1px solid #ddd;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
}

.dropdown-menu .dropdown-item {
    padding: 8px 16px;
    text-decoration: none;
    display: block;
    color: #000;
}

.dropdown-menu .dropdown-item:hover {
    background-color: #f1f1f1;
}

/* Show the dropdown menu */
.show {
    display: block;
}
</style>

    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#" style="font-size: 1.5rem; font-weight: bold; color: #ffffff;">Job Hub</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('profile/' . $user->id) }}" style="font-size: 1.25rem; color: #ffffff;">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('postjob') }}" style="font-size: 1.25rem; color: #ffffff;">Post a Job</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" style="font-size: 1.25rem; color: #ffffff;">Browse Jobs</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

<div class="profile-header">


    <form id="uploadCoverPhotoForm" method="post" enctype="multipart/form-data" action="<?php echo url('upload-cover-photo'); ?>">
    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

    <div class="cover-photo" id="coverPhoto" 
         style="background-image: url('<?php echo $user->cover_photo ? asset($user->cover_photo) : asset('job_images/default/white.jpg'); ?>');"
         data-toggle="modal" data-target="#coverPhotoModal" 
         onclick="setCoverPhotoModal();">
         
        <div class="cover-photo-overlay" id="coverCameraIcon">
            <i class="fas fa-camera"></i>
        </div>
    </div>

    <input type="file" id="coverPhotoInput" name="coverPhoto" style="display: none;" accept="image/*" onchange="document.getElementById('uploadCoverPhotoForm').submit();">
</form>




 <form id="uploadProfilePicForm" method="post" enctype="multipart/form-data" action="<?php echo url('upload-profile-pic'); ?>">
    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
   <div class="profile-pic" id="profilePic" style="background-image: url('<?php echo $user->profile_pic ? asset($user->profile_pic) : asset('job_images/default/white.jpg'); ?>');" data-toggle="modal" data-target="#profilePicModal" onclick="setProfilePicModal();">
    <div class="profile-pic-overlay" id="profileCameraIcon">
        <i class="fas fa-camera"></i>
    </div>
</div>
    <input type="file" id="profilePicInput" name="profilePic" style="display: none;" accept="image/*" onchange="document.getElementById('uploadProfilePicForm').submit();">
</form>


  <div class="profile-info">
   <?php if (isset($user)): ?>
    <h2 id="profileName"><?php echo htmlspecialchars($user->name); ?></h2>
    <p id="profileEmail"><?php echo htmlspecialchars($user->email); ?></p>
<?php endif; ?>
</div>
</div>



    <div class="container">
        <div class="main-content">
            <div class="profile-section">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" id="my-posts-tab" href="#my-posts">My Job Postings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="my-profile-tab" href="#my-profile">My Profile</a>
                    </li>
                </ul>

                <div id="my-posts" class="mt-3">

  <?php 
foreach ($jobPostings as $jobPost): ?>
    <div class='card job-card'>
        <div class='card-body'>
            <div class='post-header'>
                <p><strong><a href="{{ url('profile/' . $jobPost->user->id) }}" style="text-decoration: none; color: inherit;">
            <?php echo htmlspecialchars($jobPost->user->name); ?>
            </a></strong> - <span class="time-ago" data-time="<?php echo htmlspecialchars($jobPost->created_at); ?>"></span></p></strong></span></p>
                <div class='dropdown'>
                    <button class='dropdown-toggle' onclick='toggleDropdown(this)'><i class='fas fa-cog'></i></button>
                    <div class='dropdown-menu'>
                       <li><a href="#" class="dropdown-item" onclick="deleteJobPost(<?php echo $jobPost->id; ?>)">Delete Post</a></li>
                       <li><a href="#" class="dropdown-item" onclick="hidePost(<?php echo $jobPost->id; ?>)">Hide Post</a></li>
                    </div>
                </div>
            </div>
            <h5 class='card-title'><?php echo htmlspecialchars($jobPost->job_title); ?></h5>
            <p class="company_name"><strong>Company:</strong> <?php echo htmlspecialchars($jobPost->company_name); ?></p>
            <p class='job-type'><strong>Job Type:</strong> <?php echo htmlspecialchars($jobPost->job_type); ?></p>
            <p class='job-location'><strong>Location:</strong> <?php echo htmlspecialchars($jobPost->job_location); ?></p>
            <p class='job-deadline'><strong>Deadline:</strong> <?php echo htmlspecialchars($jobPost->job_deadline); ?></p>
            <div class='job-description'>
                <p class='card-text'><?php echo htmlspecialchars($jobPost->job_description); ?></p>
                <div class='see-more'>See More</div>
                <div class='full-description'><?php echo htmlspecialchars($jobPost->job_description); ?></div>
                <div class='hide-content'>Hide</div>
            </div>
            <?php if ($jobPost->image_path): ?>
            <div class="image-container">
            <img src="{{ asset('storage/' . $jobPost->image_path) }}" class="centered-image">
            </div>
            <?php endif; ?>

        </div>
    </div>
<?php endforeach; ?>

</div>

<div id="my-profile" class="mt-3" style="display: none;">
    <div class="profile-data">
        <h4>Profile Details</h4>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($user->name); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user->email); ?></p>
        <p><strong>Contact Number:</strong> <?php echo htmlspecialchars($user->contact_num); ?></p>
        <p><strong>Birthdate:</strong> <?php echo htmlspecialchars($user->birthdate); ?></p>
        <button class="btn btn-primary" data-toggle="modal" data-target="#editProfileModal">Edit Profile</button>
    </div>
</div>

<div class="job-posting-section">
    <!-- Additional content or job posting details can go here -->
</div>


    <!-- Edit Profile Modal -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" role="dialog" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php foreach ($jobPostings as $jobPost): ?>
                    <p><strong>Name:</strong> <?php echo $jobPost->user->name; ?></p>
                    <p><strong>Email:</strong> <?php echo $jobPost->user->email; ?></p>
                    <p><strong>Contact Number:</strong> <?php echo $jobPost->user->contact_num; ?></p>
                    <p><strong>Birthdate:</strong> <?php echo $jobPost->user->birthdate; ?></p>
                <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Profile Picture -->
    <div class="modal fade profile-pic-modal" id="profilePicModal" tabindex="-1" role="dialog" aria-labelledby="profilePicModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <img id="modalProfilePic" src="" alt="Profile Picture">
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Cover Photo -->
    <div class="modal fade cover-photo-modal" id="coverPhotoModal" tabindex="-1" role="dialog" aria-labelledby="coverPhotoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content">
            <div class="modal-body">
                <img id="modalCoverPhoto" src="" alt="Cover Photo">
            </div>
        </div>
    </div>
</div>


<!-- delete confirmation modal -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this job post?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button id="confirmDeleteBtn" type="button" class="btn btn-danger">Delete</button>
            </div>
        </div>
    </div>
</div>




<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>

    <script>
       // Check if there are any errors passed from the controller
    @if($errors->any())
        const errors = @json($errors->all());
        console.log('Validation Errors:');
        errors.forEach(function(error) {
            console.error(error);
        });
    @endif


    document.addEventListener('DOMContentLoaded', function () {
    const userId = {{ Auth::id() }}; // Get the authenticated user's ID


    // Fetch profile and job postings
    fetch(`/profile/${userId}`) // Include the user ID in the fetch URL
        .then(response => {
            // Check if the response is ok (status in the range 200-299)
            if (!response.ok) {
                console.error('Network response was not ok', response); // Log the full response for debugging
                throw new Error('Network response was not ok: ' + response.status + ' ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            // Populate user profile data
            document.getElementById('profileName').innerText = data.user.name;
            document.getElementById('profileEmail').innerText = 'Email: ' + data.user.email;
            document.getElementById('userName').innerText = data.user.name;
            document.getElementById('userEmail').innerText = data.user.email;
            document.getElementById('userContact').innerText = data.user.contact_num;
            document.getElementById('userBirthdate').innerText = data.user.birthdate;

            // Populate job postings
            let jobPostsContainer = document.getElementById('my-posts');
            jobPostsContainer.innerHTML = '';
            data.jobs.forEach(job => {
                let jobCard = document.createElement('div');
                jobCard.className = 'post-card mb-3';
                jobCard.innerHTML = `
                    <h5>${job.job_title}</h5>
                    <p><strong>Type:</strong> ${job.job_type}</p>
                    <p><strong>Deadline:</strong> ${job.job_deadline}</p>
                    <p>${job.job_description}</p>
                    ${job.image_path ? `<img src="${job.image_path}" class="img-preview" alt="${job.job_title}">` : ''}
                `;
                jobPostsContainer.appendChild(jobCard);
            });
        })
        .catch(error => console.error('Error fetching profile data:', error));

    // Toggle between profile and job postings
    document.getElementById('my-posts-tab').addEventListener('click', function () {
        document.getElementById('my-posts').style.display = 'block';
        document.getElementById('my-profile').style.display = 'none';
    });

    document.getElementById('my-profile-tab').addEventListener('click', function () {
        document.getElementById('my-posts').style.display = 'none';
        document.getElementById('my-profile').style.display = 'block';
    });
});




        function previewCoverPhoto(event) {
            const coverPhoto = document.getElementById('coverPhoto');
            coverPhoto.style.backgroundImage = `url(${URL.createObjectURL(event.target.files[0])})`;
        }

        function previewProfilePic(event) {
            const profilePic = document.getElementById('profilePic');
            profilePic.style.backgroundImage = `url(${URL.createObjectURL(event.target.files[0])})`;
        }


   function setProfilePicModal() {
        const profilePicUrl = document.getElementById('profilePic').style.backgroundImage.slice(5, -2);
        document.getElementById('modalProfilePic').src = profilePicUrl;
        $('#profilePicModal').modal('show');
    }

    // Set URL for Cover Photo Modal and show it
    function setCoverPhotoModal() {
        const coverPhotoUrl = document.getElementById('coverPhoto').style.backgroundImage.slice(5, -2);
        document.getElementById('modalCoverPhoto').src = coverPhotoUrl;
        $('#coverPhotoModal').modal('show');
    }


document.addEventListener('DOMContentLoaded', function() {
            function updateTimeAgo() {
                const timeAgoElements = document.querySelectorAll('.time-ago');
                timeAgoElements.forEach(element => {
                    const time = new Date(element.getAttribute('data-time'));
                    element.textContent = timeAgo(time);
                });
            }

            function timeAgo(date) {
                const now = new Date();
                const seconds = Math.floor((now - date) / 1000);
                let interval = Math.floor(seconds / 31536000);
                if (interval > 1) return interval + " years ago";
                interval = Math.floor(seconds / 2592000);
                if (interval > 1) return interval + " months ago";
                interval = Math.floor(seconds / 86400);
                if (interval > 1) return interval + " days ago";
                interval = Math.floor(seconds / 3600);
                if (interval > 1) return interval + " hours ago";
                interval = Math.floor(seconds / 60);
                if (interval > 1) return interval + " minutes ago";
                return Math.floor(seconds) + " seconds ago";
            }

            // Initial call to set the time ago display
            updateTimeAgo();

            // Update time ago display every minute
            setInterval(updateTimeAgo, 60000);

            // Toggle description visibility
            document.querySelectorAll('.see-more').forEach(button => {
                button.addEventListener('click', function() {
                    const card = this.closest('.job-card');
                    card.querySelector('.card-text').style.display = 'none';
                    card.querySelector('.see-more').style.display = 'none';
                    card.querySelector('.full-description').style.display = 'block';
                    card.querySelector('.hide-content').style.display = 'block';
                });
            });

            document.querySelectorAll('.hide-content').forEach(button => {
                button.addEventListener('click', function() {
                    const card = this.closest('.job-card');
                    card.querySelector('.card-text').style.display = 'block';
                    card.querySelector('.see-more').style.display = 'block';
                    card.querySelector('.full-description').style.display = 'none';
                    card.querySelector('.hide-content').style.display = 'none';
                });
            });
        });

        function previewImage(event) {
            const input = event.target;
            const file = input.files[0];
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const img = document.getElementById('imagePreview');
                img.src = e.target.result;
                img.style.display = 'block';
            };
            
            reader.readAsDataURL(file);
        }

         // Prevent modal from opening when clicking on the cover photo camera icon
    document.getElementById('coverCameraIcon').addEventListener('click', function(event) {
        event.stopPropagation(); // Prevents the modal from opening
        document.getElementById('coverPhotoInput').click(); // Triggers the file input
    });

    // Prevent modal from opening when clicking on the profile pic camera icon
    document.getElementById('profileCameraIcon').addEventListener('click', function(event) {
        event.stopPropagation(); // Prevents the modal from opening
        document.getElementById('profilePicInput').click(); // Triggers the file input
    });

  $(document).ready(function() {
    // Only target nav-link elements within the .nav-tabs class
    $('.nav-tabs .nav-link').click(function(e) {
        e.preventDefault();
        $('.nav-tabs .nav-link').removeClass('active'); // Remove 'active' class from all tabs
        $(this).addClass('active'); // Add 'active' class to the clicked tab

        // Show the corresponding tab content
        $('.tab-pane').removeClass('show active'); // Hide all tab content
        $($(this).attr('href')).addClass('show active'); // Show the clicked tab content
    });
});

  function toggleDropdown(button) {
    var menu = button.nextElementSibling;
    menu.classList.toggle('show');
}

// Example functions for delete and hide actions
function deletePost(postId) {
    // Implement the AJAX request to delete the post
    alert('Delete post with ID: ' + postId);
}

function hidePost(postId) {
    // Implement the AJAX request to hide the post
    alert('Hide post with ID: ' + postId);
}






// Delete Post job
let jobIdToDelete; // Declare in the outer scope
let modalElement; // Declare in the outer scope

function deleteJobPost(id) {
    jobIdToDelete = id; // Store the job ID for deletion
    modalElement = document.getElementById('deleteConfirmationModal'); // Get the modal element
    if (modalElement) {
        const deleteConfirmationModal = new bootstrap.Modal(modalElement);
        deleteConfirmationModal.show();
    } else {
        console.error('Modal element not found');
    }
}

document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        console.log('Job ID to delete:', jobIdToDelete); // Log the job ID

        if (jobIdToDelete) {
            fetch(`/job-posts/${jobIdToDelete}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
            })
            .then(response => {
                console.log('Response status:', response.status); // Log the status
                if (!response.ok) {
                    return response.json().then(errData => {
                        throw new Error(errData.error || 'Network response was not ok');
                    });
                }
                return response.json();
            })
            .then(data => {
                alert(data.message); // Show success message
                const postElement = document.getElementById(`post-${jobIdToDelete}`);
                if (postElement) {
                    postElement.remove(); // Remove the post from UI
                } else {
                    console.error('Post element not found:', `post-${jobIdToDelete}`);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to delete the job post: ' + error.message);
            })
            .finally(() => {
                jobIdToDelete = null; // Reset job ID after deletion
                if (modalElement) {
                    bootstrap.Modal.getInstance(modalElement).hide(); // Hide the modal
                }
            });
        } else {
            console.error('No job ID set for deletion');
        }
    });
});





document.addEventListener('DOMContentLoaded', function () {
    // Get modal and confirm button elements
    const modalElement = document.getElementById('deleteConfirmationModal'); // Update to match your modal ID
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    let jobIdToDelete = null; // Variable to hold job ID for deletion

    // Function to open the modal and set the job ID
    function openDeleteModal(id) {
        jobIdToDelete = id; // Set job ID for deletion
        const modalInstance = new bootstrap.Modal(modalElement);
        modalInstance.show(); // Show the modal
    }

    // Attach event listener for when the confirm button is clicked
    confirmDeleteBtn.addEventListener('click', function() {
        console.log('Confirm delete clicked'); // Debug log

        if (jobIdToDelete) {
            // Perform fetch request to delete the job post
            fetch(`/job-posts/${jobIdToDelete}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
            })
            .then(response => {
                console.log('Response status:', response.status); // Log the response status
                if (!response.ok) {
                    return response.json().then(errData => {
                        throw new Error(errData.error || 'Network response was not ok');
                    });
                }
                return response.json();
            })
            .then(data => {
                alert(data.message); // Show success message
                const postElement = document.getElementById(`post-${jobIdToDelete}`);
                if (postElement) {
                    postElement.remove(); // Remove the post from UI
                } else {
                    console.error('Post element not found:', `post-${jobIdToDelete}`);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to delete the job post: ' + error.message);
            })
            .finally(() => {
                jobIdToDelete = null; // Reset job ID after deletion
                bootstrap.Modal.getInstance(modalElement).hide(); // Hide the modal
            });
        } else {
            console.error('No job ID set for deletion');
        }
    });

    // Attach this function to the delete button for each job post
    document.querySelectorAll('.deleteJobBtn').forEach(button => {
        button.addEventListener('click', function() {
            const jobId = this.dataset.jobId; // Assuming you have data-job-id attribute
            openDeleteModal(jobId); // Open modal with the job ID
        });
    });
});




// Hide Change profile and cover photo 
document.addEventListener('DOMContentLoaded', function() {
        // Replace these values with your actual session user ID and current user ID
        const sessionUserId = @json(session('user_id')); // Get session ID
        const currentUserId = @json(Auth::user()->id); // Get the current user ID

        const profileCameraIcon = document.getElementById('profileCameraIcon');
        const coverCameraIcon = document.getElementById('coverCameraIcon');

        // Function to show/hide icons based on user ID match
        function toggleIcons() {
            if (sessionUserId === currentUserId) {
                // Show icons if IDs match
                profileCameraIcon.style.display = 'block'; // or use '' to inherit from CSS
                coverCameraIcon.style.display = 'block'; // or use '' to inherit from CSS
            } else {
                // Hide icons if IDs do not match
                profileCameraIcon.style.display = 'none';
                coverCameraIcon.style.display = 'none';
            }
        }

        // Call the function to set the visibility
        toggleIcons();
    });


document.addEventListener('DOMContentLoaded', function() {
        // Get the user ID stored in the session and the ID of the currently authenticated user.
        const sessionUserId = @json(session('user_id')); // Replace with actual session user ID from Laravel.
        const currentUserId = @json(Auth::user()->id); // Replace with the current user ID from Laravel.

        // Select the DOM elements for the profile and cover camera icons.
        const profileCameraIcon = document.getElementById('profileCameraIcon');
        const coverCameraIcon = document.getElementById('coverCameraIcon');

        // Function to show or hide the camera icons based on user ID match.
        function toggleIcons() {
            // Check if the session user ID matches the current user ID.
            if (sessionUserId === currentUserId) {
                // If they match, show the icons by setting their display style to 'block'.
                profileCameraIcon.style.display = 'block'; // Show profile camera icon.
                coverCameraIcon.style.display = 'block'; // Show cover photo camera icon.
            } else {
                // If they do not match, hide the icons by setting their display style to 'none'.
                profileCameraIcon.style.display = 'none'; // Hide profile camera icon.
                coverCameraIcon.style.display = 'none'; // Hide cover photo camera icon.
            }
        }

        // Call the function to set the visibility of the icons based on the user ID check.
        toggleIcons();
    });
   


    </script>
</body>
</html>
