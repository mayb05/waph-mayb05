<!-- registrationform.php -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Sign Up - WAPH Project 2</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.3/dist/minty/bootstrap.min.css">
  <style>
    body {
      background: linear-gradient(to right, #a8edea, #fed6e3);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem;
    }
    .form-container {
      background-color: rgba(255, 255, 255, 0.95);
      padding: 2rem;
      border-radius: 1rem;
      box-shadow: 0 0 12px rgba(0,0,0,0.1);
      max-width: 500px;
      width: 100%;
    }
  </style>
</head>
<body>
  <div class="form-container">
    <h1 class="mb-4 text-center">Sign Up for a New Account</h1>

    <?php
      echo "<p class='text-muted text-center'>Current time: " . date("Y-m-d h:i:sa") . "</p>";
    ?>

    <form action="addnewuser.php" method="POST">
      <div class="mb-3">
        <label for="firstname" class="form-label">Full Name</label>
        <input type="text" class="form-control" name="firstname" required placeholder="First name" />
      </div>

      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control" name="username" required pattern="\w+"
          placeholder="Enter your username" onchange="this.setCustomValidity(this.validity.patternMismatch ? this.title: ' ');"

        />
                  <!-- onchange="this.setCustomValidity(this.validity.patternMismatch ? this.title : '');" -->
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" name="password" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}"
          
          title="Password must have at least 8 characters with 1 special symbol (@#$%^&+=!), 1 number, 1 lowercase, and 1 uppercase"
          placeholder="Create a strong password" onchange="form.repassword.pattern = this.value;"

        />
      </div>

      <div class="mb-3">
        <label for="repassword" class="form-label">Retype Password</label>
        <input type="password" class="form-control" name="repassword" placeholder="Confirm your password" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}" name="repassword"
        />
      </div>

      <button type="submit" class="btn btn-success w-100">Register</button>
    </form>
  </div>
</body>
</html>


