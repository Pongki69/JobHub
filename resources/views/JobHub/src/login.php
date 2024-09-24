<?php



if(isset($_SESSION['alert_message'])) {
    echo "<div class='alert-container'><div class='alert alert-danger fade show' role='alert'>"
        . $_SESSION['alert_message'] .
        "</div></div>";
    
    // Clear the session variable after displaying
    unset($_SESSION['alert_message']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- BOOTSTRAP-->
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unpkg.com/bs-brain@2.0.3/components/logins/login-9/assets/css/login-9.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <title>Job Hub</title>
</head>
 <style>

  .alert-container {
            position: fixed;
            top: 20px;
            right: 20px;
            max-width: 300px;
            z-index: 1050;
    }

  .password-container {
    position: relative;
  }

  .password-container .toggle-password {
    position: absolute;
    top: 50%;
    right: 10px;
    transform: translateY(-50%);
    cursor: pointer;
  }
</style>
<body>
  

  <section class="bg-primary py-3 py-md-5 py-xl-8">
    <div class="container">
      <div class="row gy-4 align-items-center">
        <div class="col-12 col-md-6 col-xl-7">
          
          <div class="d-flex justify-content-center text-bg-primary">
            <div class="col-12 col-xl-9">
              <h2 class="h1 mb-4" style="color:white;">Job Hub</h1>
              <hr class="border-primary-subtle mb-4">
              <h2 class="h1 mb-4">Explore a Wealth of Exciting Career Paths Waiting for You to Discover.</h2>
              <p class="lead mb-5">We enable smooth communication between employers and job seekers, enhancing connectivity for optimal engagement.</p>
              <div class="text-endx">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-grip-horizontal" viewBox="0 0 16 16">
                  <path d="M2 8a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                </svg>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-6 col-xl-5">
          <div class="card border-0 rounded-4">
            <div class="card-body p-3 p-md-4 p-xl-5">
              <div class="row">
                <div class="col-12">
                  <div class="mb-4">
                    <h3>Sign in</h3>
                    <p>Don't have an account? <a href="register.php">Sign up</a></p>
                  </div>
                </div>

              </div>
              <form action="" method="POST">
                <div class="row gy-3 overflow-hidden">
                  <div class="col-12">
                    <div class="form-floating mb-3">
                      <input type="text" class="form-control" name="email" id="email" placeholder="name@example.com" required>
                      <label for="email" class="form-label">Email</label>
                    </div>
                  </div>

                  <div class="col-12">
                 <div class="form-floating password-container">
                   <input type="password" class="form-control" name="password" id="password" required>
                                  <label for="password" class="form-label">Password</label>
                   <span class="error">
                     <?php echo isset($password_error) ? $password_error : ''; ?>
                   </span>
                   <i class="bi bi-eye-slash toggle-password" id="togglePassword"></i>
                 </div>
               </div>

                  <div class="col-12">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" value="" name="remember_me" id="remember_me">
                      <label class="form-check-label text-secondary" for="remember_me">
                        Keep me logged in
                      </label>
                    </div>
                  </div>
                  
                    <div class="col-12">
                   <?php echo isset($login_error) ? $login_error : ''; ?>
                 </div>

                  <div class="col-12">
                    <div class="d-grid">
                      <button class="btn btn-primary btn-lg" name ="login" type="submit">Log in now</button>
                    </div>
                  </div>
                </div>
              </form>
              
               <div class="row mt-4">
                 <div class="col-12 d-flex justify-content-between align-items-center">
                 <a href="<?php echo route('auth.google'); ?>" class="me-4">
                 <img src="https://developers.google.com/identity/images/btn_google_signin_dark_normal_web.png" alt="Sign in with Google">
                </a>
              <a href="#!">Forgot password</a>
            </div>
          </div>


              
            
          
        
     
  
  </section>

  


  
</body>
<footer class="bg-body-tertiary text-center text-lg-start">
  <!-- Grid container -->
  <div class="container p-4">
    <!--Grid row-->
    <div class="row">
      <!--Grid column-->
      <div class="col-lg-6 col-md-12 mb-4 mb-md-0">
        <h5 class="text-uppercase">Footer text</h5>

        <p>
          Lorem ipsum dolor sit amet consectetur, adipisicing elit. Iste atque ea quis
          molestias. Fugiat pariatur maxime quis culpa corporis vitae repudiandae aliquam
          voluptatem veniam, est atque cumque eum delectus sint!
        </p>
      </div>
      <!--Grid column-->

      <!--Grid column-->
      <div class="col-lg-6 col-md-12 mb-4 mb-md-0">
        <h5 class="text-uppercase">Footer text</h5>

        <p>
          Lorem ipsum dolor sit amet consectetur, adipisicing elit. Iste atque ea quis
          molestias. Fugiat pariatur maxime quis culpa corporis vitae repudiandae aliquam
          voluptatem veniam, est atque cumque eum delectus sint!
        </p>
      </div>
      <!--Grid column-->
    </div>
    <!--Grid row-->
  </div>
  <!-- Grid container -->

  <!-- Copyright -->
  <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
    © 2024 Develop by:
    <a class="text-body" href="https://www.facebook.com/code232" style="text-decoration:none" target=”_blank”>Nathaniel Replan</a> &
    <a class="text-body" href="https://www.facebook.com/Pongki17/" style="text-decoration:none" target=”_blank”>Alfonso Panisales</a>
  </div>
  <!-- Copyright -->
</footer>
</html>
<script>
  const togglePassword = document.getElementById('togglePassword');
  const password = document.getElementById('password');

  togglePassword.addEventListener('click', function () {
    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
    password.setAttribute('type', type);
    this.classList.toggle('bi-eye');
    this.classList.toggle('bi-eye-slash');
  });
</script>