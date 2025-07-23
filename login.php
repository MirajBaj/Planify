<?php
session_start();

$email = $password = "";
$emailErr = $passwordErr = $loginErr = "";

// Dummy user data (replace with database in real project)
$dummyEmail = "user@example.com";
$dummyHashedPassword = password_hash("mypassword@123", PASSWORD_DEFAULT);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Email validation
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format";
    } else {
        $email = trim(htmlspecialchars($_POST["email"]));
    }

    // Password validation
    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
    } else {
        $password = $_POST["password"];
        if (strlen($password) < 8) {
            $passwordErr = "Password must be at least 8 characters";
        } elseif (strlen($password) > 64) {
            $passwordErr = "Password too long";
        } elseif (!preg_match('/[A-Z]/', $password)) {
            $passwordErr = "Password must contain at least one uppercase letter";
        } elseif (!preg_match('/[a-z]/', $password)) {
            $passwordErr = "Password must contain at least one lowercase letter";
        } elseif (!preg_match('/[^a-zA-Z0-9]/', $password)) {
            $passwordErr = "Password must contain at least one special character";
        }
    }

    // Only check credentials if no validation errors
    if (empty($emailErr) && empty($passwordErr)) {
        if ($email === $dummyEmail && password_verify($password, $dummyHashedPassword)) {
            $_SESSION["loggedin"] = true;
            $_SESSION["email"] = $email;
            // Redirect to dashboard or show success
            header("Location: dashboard.php");
            exit;
        } else {
            $loginErr = "Invalid email or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="CSS/login.css">
</head>
<body>
  <div class="login-container">
    <div class="login-title">Welcome Back</div>
    <form class="login-form" method="post" action="">
      <?php if($loginErr) echo '<div class="error">'.$loginErr.'</div>'; ?>
      <div>
        <label class="login-label" for="email">Email</label>
        <input class="login-input" type="email" id="email" name="email" placeholder="example@gmail.com" value="<?php echo htmlspecialchars($email); ?>" required>
        <?php if($emailErr) echo '<div class="error">'.$emailErr.'</div>'; ?>
      </div>
      <div>
        <label class="login-label" for="password">Password</label>
        <input class="login-input" type="password" id="password" name="password" placeholder="**************" required>
        <?php if($passwordErr) echo '<div class="error">'.$passwordErr.'</div>'; ?>
        <div class="password-requirements" id="passwordRequirements">
          <div class="requirements-title">Password Requirements:</div>
          <div class="requirement" id="lengthReq">
            <span class="requirement-icon"></span>
            <span>At least 8 characters, max 64</span>
          </div>
          <div class="requirement" id="uppercaseReq">
            <span class="requirement-icon"></span>
            <span>At least one uppercase letter (A-Z)</span>
          </div>
          <div class="requirement" id="lowercaseReq">
            <span class="requirement-icon"></span>
            <span>At least one lowercase letter (a-z)</span>
          </div>
          <div class="requirement" id="specialReq">
            <span class="requirement-icon"></span>
            <span>At least one special character (!@#$%^&*)</span>
          </div>
        </div>
        <div id="allMatchedMsg" style="display:none;color:#27ae60;font-weight:600;margin-top:0.5rem;text-align:center;">All conditions matched</div>
      </div>
      <div class="login-footer">
        <span>Don’t have an account? <a class="signup-link" href="#">SignUp</a></span>
      </div>
      <button class="login-btn" type="submit">Login</button>
    </form>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const passwordInput = document.getElementById('password');
      const passwordRequirements = document.getElementById('passwordRequirements');
      const lengthReq = document.getElementById('lengthReq');
      const uppercaseReq = document.getElementById('uppercaseReq');
      const lowercaseReq = document.getElementById('lowercaseReq');
      const specialReq = document.getElementById('specialReq');
      const allMatchedMsg = document.getElementById('allMatchedMsg');

      // Show requirements when user focuses on password field
      passwordInput.addEventListener('focus', function() {
        if (this.value || !areAllRequirementsMet(this.value)) {
          passwordRequirements.classList.add('show');
        }
      });

      // Real-time validation as user types
      passwordInput.addEventListener('input', function() {
        const password = this.value;
        // Show requirements if there's text and not all requirements are met
        if (password && !areAllRequirementsMet(password)) {
          passwordRequirements.classList.add('show');
        }
        // Validate each requirement
        validateRequirement(lengthReq, password.length >= 8 && password.length <= 64);
        validateRequirement(uppercaseReq, /[A-Z]/.test(password));
        validateRequirement(lowercaseReq, /[a-z]/.test(password));
        validateRequirement(specialReq, /[^a-zA-Z0-9]/.test(password));
        // Show message if all requirements met
        if (areAllRequirementsMet(password)) {
          allMatchedMsg.style.display = 'block';
          setTimeout(() => {
            passwordRequirements.classList.remove('show');
          }, 1200);
        } else {
          allMatchedMsg.style.display = 'none';
        }
        // Hide requirements if field is empty
        if (!password) {
          passwordRequirements.classList.remove('show');
          allMatchedMsg.style.display = 'none';
        }
      });

      // Hide requirements when user clicks away from empty field
      passwordInput.addEventListener('blur', function() {
        if (!this.value) {
          setTimeout(() => {
            passwordRequirements.classList.remove('show');
            allMatchedMsg.style.display = 'none';
          }, 200);
        }
      });

      // Function to validate individual requirements
      function validateRequirement(element, isValid) {
        if (isValid) {
          element.classList.add('valid');
        } else {
          element.classList.remove('valid');
        }
      }

      // Function to check if all requirements are met
      function areAllRequirementsMet(password) {
        return password.length >= 8 && 
               password.length <= 64 &&
               /[A-Z]/.test(password) &&
               /[a-z]/.test(password) &&
               /[^a-zA-Z0-9]/.test(password);
      }
    });
  </script>
</body>
</html>
