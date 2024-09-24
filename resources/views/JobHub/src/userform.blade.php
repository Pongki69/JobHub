<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\CSRFToken;

?>
<div class="form-container">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Form</title>
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            padding-top: 70px; /* Space for fixed navbar */
        }
        .form-container {
            background-color: #fff; /* White background for the form */
            border-radius: 8px; /* Rounded corners */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow effect */
            padding: 20px; /* Padding inside the form */
            margin-top: 20px; /* Space above the form */
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
    <div class="container">
        <a class="navbar-brand" href="#" style="font-size: 1.5rem; font-weight: bold; color: #ffffff;">Job Hub</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto"> <!-- Added 'ms-auto' to align to the right -->
                <li class="nav-item dropdown" id="profileDropdown">
                    <span class="nav-link dropdown-toggle" href="#" role="button" aria-expanded="false" style="font-size: 1.25rem; color: #ffffff;">
                        Profile
                    </span>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="#" style="font-size: 1.1rem;" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    
    <!-- Logout Form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</nav>

<!-- Main Container -->
<div class="container mt-4">
    <div class="form-container"> <!-- Added a div for styling -->
        <form method="POST" action="{{ route('submit.user.form') }}" class="needs-validation" novalidate>
            @csrf

            <!-- Hidden Input for User ID -->
            <input type="hidden" name="id" value="{{ $user->google_id ?? '' }}">
            <!-- Name Field with Checkbox -->
            <div class="mb-3 row align-items-center">
                <div class="col">
                    <label for="name" class="form-label">Name:</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name ?? '') }}" readonly required>
                    <div class="invalid-feedback">Please provide your name.</div>
                </div>
                <div class="col-auto">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="edit_name_checkbox" onchange="toggleField('name')">
                        <label class="form-check-label" for="edit_name_checkbox">Edit Name</label>
                    </div>
                </div>
            </div>

            <!-- Email Field with Checkbox -->
            <div class="mb-3 row align-items-center">
                <div class="col">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email ?? '') }}" readonly required>
                    <div class="invalid-feedback">Please provide a valid email address.</div>
                </div>
                <div class="col-auto">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="edit_email_checkbox" onchange="toggleField('email')">
                        <label class="form-check-label" for="edit_email_checkbox">Edit Email</label>
                    </div>
                </div>
            </div>

            <!-- Contact Number -->
            <div class="mb-3">
            <label for="contact_num" class="form-label">Contact Number:</label>
            <div class="input-group">
            <span class="input-group-text">+63</span>
            <input type="text" class="form-control" id="contact_num" name="contact_num" value="{{ old('contact_num', $user->contact_num ?? '') }}" placeholder="Ex: 9876543210" required>
            </div>
            <div class="invalid-feedback">Please provide your contact number.</div>
            </div>

            <!-- Birthdate -->
            <div class="mb-3">
                <label for="birthdate" class="form-label">Birthdate:</label>
                <input type="date" class="form-control" id="birthdate" name="birthdate" value="{{ old('birthdate', $user->birthdate ?? '') }}" required>
                <div class="invalid-feedback">Please provide your birthdate.</div>
            </div>

            <!-- User Role -->
           <div class="mb-3">
           <label for="user_role" class="form-label">User Role:</label>
           <select class="form-select" id="user_role" name="user_role" onchange="showAdditionalFields()" required>
                <option value="" disabled selected hidden>Select Role</option> <!-- Placeholder option -->
                <option value="jobseeker">Jobseeker</option>
                 <option value="unskilled_worker">Unskilled Worker</option>
                 <option value="employer">Employer</option>
          </select>
          <div class="invalid-feedback">Please select a user role.</div>
          </div>     

            <!-- Job Experience and Description Fields, hidden by default -->
            <div id="job_experience_fields" class="mb-3" style="display:none;">
                <div class="mb-3">
                    <label for="job_experience" class="form-label">Job Experience:</label>
                    <input type="text" class="form-control" id="job_experience" name="job_experience" value="{{ old('job_experience') }}">
                </div>

                <div class="mb-3">
                    <label for="job_description" class="form-label">Job Description:</label>
                    <textarea class="form-control" id="job_description" name="job_description">{{ old('job_description') }}</textarea>
                </div>
            </div>

            <!-- Company Name Field for Employers, hidden by default -->
            <div id="company_name_field" class="mb-3" style="display:none;">
                <div class="mb-3">
                    <label for="company_name" class="form-label">Company Name:</label>
                    <input type="text" class="form-control" id="company_name" name="company_name" value="{{ old('company_name') }}">
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div> <!-- End of form-container -->
</div>

<!-- Bootstrap JS and Popper.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>

<script>
    // Toggle field to be editable or not
    function toggleField(fieldId) {
        var field = document.getElementById(fieldId);
        field.readOnly = !field.readOnly;
    }

    // Show/Hide additional fields based on role
    function showAdditionalFields() {
        var userRole = document.getElementById('user_role').value;
        var jobExperienceFields = document.getElementById('job_experience_fields');
        var companyNameField = document.getElementById('company_name_field');

        if (userRole === 'jobseeker') {
            jobExperienceFields.style.display = 'block';
            companyNameField.style.display = 'none'; // Hide company field for jobseeker
        } else if (userRole === 'unskilled_worker') {
            jobExperienceFields.style.display = 'none'; // Hide job experience fields for unskilled workers
            companyNameField.style.display = 'none'; // Hide company field for unskilled workers
        } else if (userRole === 'employer') {
            companyNameField.style.display = 'block';
            jobExperienceFields.style.display = 'none'; // Hide job experience fields for employers
        } else {
            jobExperienceFields.style.display = 'none';
            companyNameField.style.display = 'none'; // Hide both for other roles
        }
    }
     
    // Function to set the maximum date for the birthdate input   
    function setMaxBirthdate() {
    const today = new Date();
    const dd = String(today.getDate()).padStart(2, '0');
    const mm = String(today.getMonth() + 1).padStart(2, '0'); // January is 0!
    const yyyy = today.getFullYear();

    // Format date to YYYY-MM-DD
    const maxDate = `${yyyy}-${mm}-${dd}`;

    // Set the maximum date attribute
    document.getElementById('birthdate').setAttribute('max', maxDate);
   }

   // Call the function when the document is loaded
  document.addEventListener('DOMContentLoaded', function() {
  setMaxBirthdate();
    
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
</script>

</body>
</html>
