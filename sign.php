<?php
require 'db.php'; 

// Initialize variables - ensure they're empty on fresh page load
$username = $email = $password = $confirmPassword = "";
$errors = [];

// Clear variables if this is a fresh page load (not a POST request)
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    $username = $email = $password = $confirmPassword = "";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $username = htmlspecialchars(trim($_POST['username']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']); // Don't sanitize password too much, just trim
    $confirmPassword = trim($_POST['confirmPassword']);

    // Validation
    // Check if all fields are filled
    if (empty($username)) {
        $errors[] = "Username is required.";
    } elseif (strlen($username) < 2) {
        $errors[] = "Username must be at least 2 characters long.";
    } elseif (strlen($username) > 50) {
        $errors[] = "Username must not exceed 50 characters.";
    } elseif (!preg_match('/^[a-zA-Z0-9_\s]+$/', $username)) {
        $errors[] = "Username can only contain letters, numbers, underscores, and spaces.";
    }

    // Email validation
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Enter a valid email address.";
    } elseif (strlen($email) > 255) {
        $errors[] = "Email address is too long.";
    }

    // Password validation
    if (empty($password)) {
        $errors[] = "Password is required.";
    } else {
        // Comprehensive password validation
        if (strlen($password) < 8) {
            $errors[] = "Password must be at least 8 characters long.";
        } elseif (strlen($password) > 64) {
            $errors[] = "Password must not exceed 64 characters.";
        } elseif (!preg_match('/[A-Z]/', $password)) {
            $errors[] = "Password must contain at least one uppercase letter (A-Z).";
        } elseif (!preg_match('/[a-z]/', $password)) {
            $errors[] = "Password must contain at least one lowercase letter (a-z).";
        } elseif (!preg_match('/[0-9]/', $password)) {
            $errors[] = "Password must contain at least one number (0-9).";
        } elseif (!preg_match('/[^a-zA-Z0-9]/', $password)) {
            $errors[] = "Password must contain at least one special character (!@#$%^&*).";
        }
    }

    // Confirm password validation
    if (empty($confirmPassword)) {
        $errors[] = "Please confirm your password.";
    } elseif ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match.";
    }

    // Check for duplicate email if no errors so far
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $errors[] = "An account with this email already exists.";
            }
        } catch (PDOException $e) {
            $errors[] = "Database error occurred. Please try again.";
            error_log("Database error: " . $e->getMessage());
        }
    }

    // Check for duplicate username if no errors so far
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("SELECT user_id FROM users WHERE username = ?");
            $stmt->execute([$username]);
            if ($stmt->fetch()) {
                $errors[] = "This username is already taken.";
            }
        } catch (PDOException $e) {
            $errors[] = "Database error occurred. Please try again.";
            error_log("Database error: " . $e->getMessage());
        }
    }

    // If no errors, hash password and insert
    if (empty($errors)) {
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, created_at) VALUES (?, ?, ?, NOW())");
            if ($stmt->execute([$username, $email, $hashedPassword])) {
                // Set success message in session and redirect to login page
                session_start();
                $_SESSION['signup_success'] = "Registration successful! Please login with your new account.";
                header("Location: login.php");
                exit;
            } else {
                $errors[] = "Registration failed. Please try again.";
            }
        } catch (PDOException $e) {
            $errors[] = "Registration failed. Please try again.";
            error_log("Database error: " . $e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up</title>
  <link rel="stylesheet" href="/planify/CSS/sign.css">
</head>
<body>
  <div class="signup-container">
    <img src="planify.png" alt="Planify Logo" class="signup-logo">
    <div class="signup-title">Create Your Account</div>
    <?php
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<div class='error'>" . htmlspecialchars($error) . "</div>";
        }
    }
    ?>
    <form class="signup-form" method="post" action="" autocomplete="off">
      <div>
        <label class="signup-label" for="name">Name</label>
        <input class="signup-input" type="text" id="name" name="username" placeholder="Your Name" value="<?php echo htmlspecialchars($username); ?>" required maxlength="50" autocomplete="off">
      </div>
      <div>
        <label class="signup-label" for="email">Email</label>
        <input class="signup-input" type="email" id="email" name="email" placeholder="example@gmail.com" value="<?php echo htmlspecialchars($email); ?>" required maxlength="255" autocomplete="off">
      </div>
      <div>
        <label class="signup-label" for="password">Password</label>
        <div class="password-wrapper">
          <input class="signup-input" type="password" id="password" name="password" placeholder="**************" required minlength="8" maxlength="64" autocomplete="new-password">
          <span class="toggle-password" onclick="togglePassword('password', 'togglePasswordOpen', 'togglePasswordClosed')">
            <svg id="togglePasswordOpen" xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24"><path stroke="#888" stroke-width="2" d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12Z"/><circle cx="12" cy="12" r="3" stroke="#888" stroke-width="2"/></svg>
            <svg id="togglePasswordClosed" style="display:none;" xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24"><path stroke="#888" stroke-width="2" d="M3 3l18 18M10.7 10.7A3 3 0 0 0 12 15a3 3 0 0 0 2.12-.88M6.53 6.53C4.06 8.06 2 12 2 12s4 7 10 7c2.03 0 3.87-.5 5.47-1.47M17.47 17.47C19.94 15.94 22 12 22 12s-4-7-10-7c-1.13 0-2.22.13-3.24.37"/><circle cx="12" cy="12" r="3" stroke="#888" stroke-width="2"/></svg>
          </span>
        </div>
        <div class="password-requirements" id="passwordRequirements" style="display: none; margin-top: 0.5rem; padding: 0.75rem; background-color: #f8f9fa; border-radius: 4px; border-left: 3px solid #007bff;">
          <div class="requirements-title" style="font-weight: 600; margin-bottom: 0.5rem; color: #333;">Password Requirements:</div>
          <div class="requirement" id="lengthReq" style="display: flex; align-items: center; margin: 0.25rem 0; font-size: 0.85rem;">
            <span class="requirement-icon" style="width: 16px; height: 16px; border-radius: 50%; margin-right: 0.5rem; display: inline-block; background-color: #dc3545;">✗</span>
            <span>8-64 characters</span>
          </div>
          <div class="requirement" id="uppercaseReq" style="display: flex; align-items: center; margin: 0.25rem 0; font-size: 0.85rem;">
            <span class="requirement-icon" style="width: 16px; height: 16px; border-radius: 50%; margin-right: 0.5rem; display: inline-block; background-color: #dc3545;">✗</span>
            <span>At least one uppercase letter (A-Z)</span>
          </div>
          <div class="requirement" id="lowercaseReq" style="display: flex; align-items: center; margin: 0.25rem 0; font-size: 0.85rem;">
            <span class="requirement-icon" style="width: 16px; height: 16px; border-radius: 50%; margin-right: 0.5rem; display: inline-block; background-color: #dc3545;">✗</span>
            <span>At least one lowercase letter (a-z)</span>
          </div>
          <div class="requirement" id="numberReq" style="display: flex; align-items: center; margin: 0.25rem 0; font-size: 0.85rem;">
            <span class="requirement-icon" style="width: 16px; height: 16px; border-radius: 50%; margin-right: 0.5rem; display: inline-block; background-color: #dc3545;">✗</span>
            <span>At least one number (0-9)</span>
          </div>
          <div class="requirement" id="specialReq" style="display: flex; align-items: center; margin: 0.25rem 0; font-size: 0.85rem;">
            <span class="requirement-icon" style="width: 16px; height: 16px; border-radius: 50%; margin-right: 0.5rem; display: inline-block; background-color: #dc3545;">✗</span>
            <span>At least one special character (!@#$%^&*)</span>
          </div>
        </div>
        <div id="allMatchedMsg" style="display:none; color:#27ae60; font-weight:600; margin-top:0.5rem; text-align:center; padding: 0.5rem; background-color: #d4edda; border-radius: 4px;">✓ All requirements satisfied!</div>
      </div>
      <div>
        <label class="signup-label" for="confirmPassword">Confirm Password</label>
        <div class="password-wrapper">
          <input class="signup-input" type="password" id="confirmPassword" name="confirmPassword" placeholder="**************" required minlength="8" maxlength="64" autocomplete="new-password">
          <span class="toggle-password" onclick="togglePassword('confirmPassword', 'toggleConfirmPasswordOpen', 'toggleConfirmPasswordClosed')">
            <svg id="toggleConfirmPasswordOpen" xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24"><path stroke="#888" stroke-width="2" d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12Z"/><circle cx="12" cy="12" r="3" stroke="#888" stroke-width="2"/></svg>
            <svg id="toggleConfirmPasswordClosed" style="display:none;" xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24"><path stroke="#888" stroke-width="2" d="M3 3l18 18M10.7 10.7A3 3 0 0 0 12 15a3 3 0 0 0 2.12-.88M6.53 6.53C4.06 8.06 2 12 2 12s4 7 10 7c2.03 0 3.87-.5 5.47-1.47M17.47 17.47C19.94 15.94 22 12 22 12s-4-7-10-7c-1.13 0-2.22.13-3.24.37"/><circle cx="12" cy="12" r="3" stroke="#888" stroke-width="2"/></svg>
          </span>
        </div>
      </div>
      <div class="signup-footer">
        <span>Already have an account?
        <a class="login-link" href="login.php">Login</a></span>
      </div>
      <button class="signup-btn" type="submit">Sign Up</button>
    </form>
  </div>
  <script>
    function togglePassword(inputId, openId, closedId) {
      const input = document.getElementById(inputId);
      const openIcon = document.getElementById(openId);
      const closedIcon = document.getElementById(closedId);
      if (input.type === 'password') {
        input.type = 'text';
        openIcon.style.display = 'none';
        closedIcon.style.display = 'inline';
      } else {
        input.type = 'password';
        openIcon.style.display = 'inline';
        closedIcon.style.display = 'none';
      }
    }

    // Enhanced client-side validation with real-time password requirements
    document.addEventListener('DOMContentLoaded', function() {
      const form = document.querySelector('.signup-form');
      const passwordInput = document.getElementById('password');
      const confirmPasswordInput = document.getElementById('confirmPassword');
      const passwordRequirements = document.getElementById('passwordRequirements');
      const lengthReq = document.getElementById('lengthReq');
      const uppercaseReq = document.getElementById('uppercaseReq');
      const lowercaseReq = document.getElementById('lowercaseReq');
      const numberReq = document.getElementById('numberReq');
      const specialReq = document.getElementById('specialReq');
      const allMatchedMsg = document.getElementById('allMatchedMsg');

      // Show requirements when user focuses on password field
      passwordInput.addEventListener('focus', function() {
        passwordRequirements.style.display = 'block';
        if (this.value) {
          validatePasswordRequirements(this.value);
        }
      });

      // Real-time validation as user types
      passwordInput.addEventListener('input', function() {
        const password = this.value;
        
        if (password) {
          passwordRequirements.style.display = 'block';
          validatePasswordRequirements(password);
          
          // Show success message if all requirements are met
          if (areAllRequirementsMet(password)) {
            allMatchedMsg.style.display = 'block';
            setTimeout(() => {
              passwordRequirements.style.display = 'none';
            }, 2000);
          } else {
            allMatchedMsg.style.display = 'none';
          }
        } else {
          // Reset all requirements when field is empty
          passwordRequirements.style.display = 'none';
          allMatchedMsg.style.display = 'none';
          resetAllRequirements();
        }
      });

      // Hide requirements when user clicks away from empty field
      passwordInput.addEventListener('blur', function() {
        if (!this.value) {
          setTimeout(() => {
            passwordRequirements.style.display = 'none';
            allMatchedMsg.style.display = 'none';
            resetAllRequirements();
          }, 200);
        }
      });

      // Real-time password confirmation check
      confirmPasswordInput.addEventListener('input', function() {
        if (passwordInput.value && this.value) {
          if (passwordInput.value !== this.value) {
            this.setCustomValidity('Passwords do not match');
          } else {
            this.setCustomValidity('');
          }
        }
      });

      passwordInput.addEventListener('input', function() {
        if (confirmPasswordInput.value) {
          if (this.value !== confirmPasswordInput.value) {
            confirmPasswordInput.setCustomValidity('Passwords do not match');
          } else {
            confirmPasswordInput.setCustomValidity('');
          }
        }
      });

      // Function to validate individual requirements
      function validateRequirement(element, isValid) {
        const icon = element.querySelector('.requirement-icon');
        if (isValid) {
          icon.style.backgroundColor = '#28a745';
          icon.textContent = '✓';
          icon.style.color = 'white';
          element.style.color = '#28a745';
        } else {
          icon.style.backgroundColor = '#dc3545';
          icon.textContent = '✗';
          icon.style.color = 'white';
          element.style.color = '#6c757d';
        }
      }

      // Function to validate all password requirements
      function validatePasswordRequirements(password) {
        validateRequirement(lengthReq, password.length >= 8 && password.length <= 64);
        validateRequirement(uppercaseReq, /[A-Z]/.test(password));
        validateRequirement(lowercaseReq, /[a-z]/.test(password));
        validateRequirement(numberReq, /[0-9]/.test(password));
        validateRequirement(specialReq, /[^a-zA-Z0-9]/.test(password));
      }

      // Function to check if all requirements are met
      function areAllRequirementsMet(password) {
        return password.length >= 8 && 
               password.length <= 64 &&
               /[A-Z]/.test(password) &&
               /[a-z]/.test(password) &&
               /[0-9]/.test(password) &&
               /[^a-zA-Z0-9]/.test(password);
      }

      // Function to reset all requirements to default state
      function resetAllRequirements() {
        const requirements = [lengthReq, uppercaseReq, lowercaseReq, numberReq, specialReq];
        requirements.forEach(req => {
          const icon = req.querySelector('.requirement-icon');
          icon.style.backgroundColor = '#dc3545';
          icon.textContent = '✗';
          icon.style.color = 'white';
          req.style.color = '#6c757d';
        });
      }
    });
  </script>
</body>
</html>