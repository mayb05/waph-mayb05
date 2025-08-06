link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.3/dist/quartz/bootstrap.min.css">

<!-- form.php -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Login page - WAPH Project 2</title>
  <!-- Minty Bootswatch Theme -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.3/dist/minty/bootstrap.min.css">
  <style>
    body {
      background: linear-gradient(to right, #a8edea, #fed6e3);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
    }
    .form-container {
      background-color: rgba(255, 255, 255, 0.85);
      padding: 2rem;
      border-radius: 0.5rem;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>
  <div class="form-container">
    <h1 class="mb-4">A Simple login form, SecAD</h1>

    <?php
      //some code here
      echo "Current time: " . date("Y-m-d h:i:sa");
    ?>

    <form action="index.php" method="POST" class="form login">
      <div class="mb-3">
        <label for="username" class="form-label">Username (Email):</label>
        <input type="text" class="form-control" id="username" name="username" required
          pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$"
          title="Please enter a valid email address" />
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Password:</label>
        <input type="password" class="form-control" id="password" name="password" required />
      </div>

      <button class="btn btn-primary" type="submit">Login</button>
    </form>

    <div class="mt-3">
      <a href="registrationform.php">Don't have an account? Register Here.</a>
    </div>
  </div>
</body>
</html>