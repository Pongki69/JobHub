<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\CSRFToken;





                    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Hub</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Custom styles -->
    <style>
        body {
            padding-top: 70px; /* Adjust based on your navbar height */
            background-color: #f0f0f0;
        }
        .job-card {
            margin-bottom: 20px;
        }
.card-text {
    overflow: hidden;
    
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 7; /* Show only 7 lines initially */
    max-height: 10.5em; /* Match this value to 7 lines */
    margin: 0; /* Remove any margin */
    padding: 0; 
    text-overflow: clip;
}

.full-description {
    display: none; /* Initially hide full content */
    margin: 0; /* Ensure no margin adds space */
    padding: 0; /* Ensure no padding adds space */
}

.job-description {
    position: relative; /* Ensure relative positioning for absolute elements */
}


.see-more-container {
    display: flex; /* Use flexbox for alignment */
    justify-content: flex-end; /* Align to the right */
    margin-top: 5px; /* Optional spacing */
}

.see-more, .hide-content {
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
            margin: auto; /* Adjust as needed */
            max-width: 100%;
            min-height: 600px;
            object-fit: cover; 
            display: block;
        }
        .post-header {
            font-size: 1rem;
            color: #555;
        }
        .container {
            max-width: 1200px;
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
                <li class="nav-item dropdown" id="profileDropdown">
                    <span class="nav-link dropdown-toggle" href="#" role="button" aria-expanded="false" style="font-size: 1.25rem; color: #ffffff;">
                        Profile
                    </span>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                           <a class="dropdown-item" href="{{ url('profile/' . $user->id) }}" style="font-size: 1.1rem;">Your Profile</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#" style="font-size: 1.1rem;">Settings</a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item" href="#" style="font-size: 1.1rem;" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" style="font-size: 1.25rem; color: #ffffff;">Post a Job</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" style="font-size: 1.25rem; color: #ffffff;">Browse Jobs</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Hidden logout form -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<div class="container mt-4">
<section id="post-job">
    <div class="card">
        <form id="postForm" action="{{ route('postjob.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <h2 class="card-title">Post a Job</h2>

                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                {{ $error }}
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="form-group mb-3">
                    <input type="hidden" class="form-control" id="uploader_name" name="uploader_name" value="{{ session('user_name') }}" required>
                    <input type="hidden" name="user_id" value="{{ session('id') }}" required>
                </div>
                <div class="form-group mb-3">
                    <label for="job-title">Job Title:</label>
                    <input type="text" class="form-control" id="job-title" name="job_title" required>
                </div>
                <div class="form-group mb-3">
                    <label for="company">Company:</label>
                    <input type="text" class="form-control" id="company" name="company_name" required>
                </div>
                <div class="form-group mb-3">
                    <label for="job-description">Job Description:</label>
                    <textarea class="form-control" id="job-description" name="job_description" rows="4" style="resize: none;" required></textarea>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="job-type">Job Type:</label>
                            <select class="form-select" id="job-type" name="job_type" required>
                                <option value="Full-time">Full-time</option>
                                <option value="Part-time">Part-time</option>
                                <option value="Contract">Contract</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="job-location">Location:</label>
                            <input type="text" class="form-control" id="job-location" name="job_location" required>
                        </div>
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label for="job-deadline">Application Deadline:</label>
                    <input type="datetime-local" class="form-control" id="job-deadline" name="job_deadline" min="{{ now()->format('Y-m-d\TH:i') }}" required>
                </div>
                <div class="form-group mb-3">
                    <label for="postImage">Upload Image:</label>
                    <input type="file" class="form-control" id="postImage" name="postImage" accept="image/*" onchange="previewImage(event)">
                    <div class="image-container mt-2">
                        <img id="imagePreview" src="#" alt="Image Preview" class="img-fluid">
                    </div>
                </div>
                <div class="form-group text-end">
                    <button type="submit" class="btn btn-primary">Post Job</button>
                </div>
            </div>
        </form>
    </div>
</section>
</div>
<div class="container mt-4">
  
<?php 
date_default_timezone_set('UTC'); // Set the default timezone to UTC
$currentDateTime = new DateTime(); // Create a new DateTime object
$currentDateTime->setTimezone(new DateTimeZone('Asia/Singapore')); // Adjust to UTC+8
$currentDateTime->format('Y-m-d H:i:s'); 
$userId = Auth::id();
foreach ($jobPostings as $jobPost): ?>
    <div class="card job-card" id="post-<?php echo $jobPost->id; ?>">
    <div class="card-body">
        <div class="post-header">
            <p><strong>
            <a href="{{ url('profile/' . $jobPost->user->id) }}" style="text-decoration: none; color: inherit;">
            <?php echo htmlspecialchars($jobPost->user->name); ?>
            </a>
            </strong> - <span class="time-ago" data-time="<?php echo htmlspecialchars($jobPost->created_at); ?>"></span></p>
            <?php if ($userId == $jobPost->user_id): ?>
                <div class="dropdown">
                    <button class="btn btn-link dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-cog"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><a href="#" class="dropdown-item" onclick="deleteJobPost(<?php echo $jobPost->id; ?>)">Delete Post</a></li>
                        <li><a href="#" class="dropdown-item" onclick="hidePost(<?php echo $jobPost->id; ?>)">Hide Post</a></li>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
        <h5 class="card-title"><?php echo htmlspecialchars($jobPost->job_title); ?></h5>
        <p class="company_name"><strong>Company:</strong> <?php echo htmlspecialchars($jobPost->company_name); ?></p>
        <p class="job-type"><strong>Job Type:</strong> <?php echo htmlspecialchars($jobPost->job_type); ?></p>
        <p class="job-location"><strong>Location:</strong> <?php echo htmlspecialchars($jobPost->job_location); ?></p>
        <p class="job-deadline"><strong>Deadline:</strong> <?php echo htmlspecialchars($jobPost->job_deadline); ?></p>





<div class="job-description">
    <div class="card-text" id="job-description-text">
        <?php echo nl2br(trim(htmlspecialchars($jobPost->job_description))); ?>
    </div>

    <?php if (strlen($jobPost->job_description) > 50): ?>
        <div class="see-more-container" id="see-more-container">
            <span class="see-more" onclick="toggleDescription()">See More...</span>
        </div>
    <?php endif; ?>

    <div class="full-description" id="full-description" style="display:none;">
        <?php echo nl2br(trim(htmlspecialchars($jobPost->job_description))); ?>
    </div>

    <div class="see-more-container" id="hide-content" style="display:none;">
        <div class="hide-content" onclick="toggleDescription()">Hide</div>
    </div>
</div>







        <?php if (!empty($jobPost->image_path)): ?>
            <div class="image-container">
                <img src="{{ asset('storage/' . $jobPost->image_path) }}" class="centered-image">
            </div>
        <?php endif; ?>
        </div>
    </div>
<?php endforeach; ?>



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


</div>
<!-- Bootstrap JS and dependencies -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const seeMoreButtons = document.querySelectorAll('.see-more');

    seeMoreButtons.forEach(button => {
        button.addEventListener('click', function() {
            const jobCard = this.closest('.job-card');
            const fullDescription = jobCard.querySelector('.full-description');
            const hideContent = jobCard.querySelector('.hide-content');

            fullDescription.style.display = 'block';
            hideContent.style.display = 'block';
            this.style.display = 'none'; // Hide 'See More' button
        });
    });

    const hideContents = document.querySelectorAll('.hide-content');

    hideContents.forEach(content => {
        content.addEventListener('click', function() {
            const jobCard = this.closest('.job-card');
            const fullDescription = jobCard.querySelector('.full-description');
            const seeMoreButton = jobCard.querySelector('.see-more');

            fullDescription.style.display = 'none';
            this.style.display = 'none'; // Hide 'Hide' text
            seeMoreButton.style.display = 'block'; // Show 'See More' button
        });
    });
});

// Dropdown menu sa bawat post
document.addEventListener('DOMContentLoaded', function () {
    var dropdownButton = document.querySelector('.dropdown-toggle');
    var dropdownMenu = document.querySelector('.dropdown-menu');
    if (dropdownButton && dropdownMenu) {
        dropdownButton.addEventListener('click', function () {
            dropdownMenu.classList.toggle('show');
        });
        document.addEventListener('click', function (event) {
            if (!dropdownButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
                dropdownMenu.classList.remove('show');
            }
        });
    }
});

//Java Script dropdown function sa profile <- navigation bar
    document.addEventListener('DOMContentLoaded', function() {
        const profileDropdown = document.getElementById('profileDropdown');

        profileDropdown.addEventListener('mouseenter', function() {
            this.querySelector('.dropdown-menu').classList.add('show');
        });

        profileDropdown.addEventListener('mouseleave', function() {
            this.querySelector('.dropdown-menu').classList.remove('show');
        });
    });

document.addEventListener('DOMContentLoaded', function() {
    function previewImage(event) {
        const imagePreview = document.getElementById('imagePreview');
        const file = event.target.files[0];
        const reader = new FileReader();

        reader.onload = function(e) {
            imagePreview.src = e.target.result;
            imagePreview.style.display = 'block'; // Show the image preview
        }

        if (file) {
            reader.readAsDataURL(file);
        } else {
            imagePreview.src = ''; // Reset the image if no file is selected
            imagePreview.style.display = 'none'; // Hide the preview if no file is selected
        }
    }

    // Assign the function to the global scope
    window.previewImage = previewImage;
});

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


//  Timer when the post created 
document.addEventListener('DOMContentLoaded', function() {
    function updateTimeAgo() {
        const timeAgoElements = document.querySelectorAll('.time-ago');
        
        timeAgoElements.forEach(function(element) {
            const postTime = new Date(element.getAttribute('data-time'));
            const currentTime = new Date();
            const timeDifference = Math.floor((currentTime - postTime) / 1000); // Time difference in seconds

            let timeAgoText;

            if (timeDifference < 60) {
                timeAgoText = 'Just now';
            } else if (timeDifference < 3600) { // Less than 1 hour
                const minutes = Math.floor(timeDifference / 60);
                timeAgoText = minutes + (minutes === 1 ? ' minute ago' : ' minutes ago');
            } else if (timeDifference < 86400) { // Less than 1 day
                const hours = Math.floor(timeDifference / 3600);
                timeAgoText = hours + (hours === 1 ? ' hour ago' : ' hours ago');
            } else {
                const days = Math.floor(timeDifference / 86400);
                timeAgoText = days + (days === 1 ? ' day ago' : ' days ago');
            }

            element.textContent = timeAgoText;
        });
    }

    // Initial time update
    updateTimeAgo();

    // Update time every minute (60000 milliseconds)
    setInterval(updateTimeAgo, 60000);
});

function toggleDescription() {
    const fullDescription = document.getElementById('full-description');
    const seeMoreContainer = document.getElementById('see-more-container');
    const hideContent = document.getElementById('hide-content');
    const cardText = document.getElementById('job-description-text');

    if (fullDescription.style.display === 'none' || fullDescription.style.display === '') {
        fullDescription.style.display = 'block';
        seeMoreContainer.style.display = 'none';
        hideContent.style.display = 'flex'; // Use 'flex' for alignment
        cardText.style.display = 'none'; // Hide the truncated text
    } else {
        fullDescription.style.display = 'none';
        seeMoreContainer.style.display = 'flex';
        hideContent.style.display = 'none';
        cardText.style.display = 'block'; // Show the truncated text
    }
}

// Initial setup to ensure the full description is hidden
document.getElementById('full-description').style.display = 'none';

</script>
</body>
</html>





