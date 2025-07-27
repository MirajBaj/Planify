<?php
require 'db.php'; // Include database connection

$email = $password = "";
$emailErr = $passwordErr = $loginErr = "";
$signupSuccessMsg = "";

// Display signup success message if it exists
if (isset($_SESSION['signup_success'])) {
    $signupSuccessMsg = $_SESSION['signup_success'];
    unset($_SESSION['signup_success']); // Remove it so it only shows once
}

// Check if user is already logged in, but allow signup redirect to show login form
if (empty($signupSuccessMsg) && isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("Location: dashboard.php");
    exit;
}

// Clear variables if this is a fresh page load (not a POST request)
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    $email = $password = "";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Rate limiting check (basic implementation)
    $max_attempts = 5;
    $lockout_time = 300; // 5 minutes
    $ip_address = $_SERVER['REMOTE_ADDR'];
    
    // Check if IP is currently locked out (you might want to implement this with database/cache)
    // For now, we'll use session-based tracking
    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = [];
    }
    
    $current_time = time();
    $recent_attempts = array_filter($_SESSION['login_attempts'], function($timestamp) use ($current_time, $lockout_time) {
        return ($current_time - $timestamp) < $lockout_time;
    });
    
    if (count($recent_attempts) >= $max_attempts) {
        $loginErr = "Too many failed login attempts. Please try again in 5 minutes.";
    } else {
        // Email sanitization and validation
        if (empty($_POST["email"])) {
            $emailErr = "Email is required.";
        } else {
            $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emailErr = "Please enter a valid email address.";
            } elseif (strlen($email) > 255) {
                $emailErr = "Email address is too long.";
            }
        }

        // Password sanitization and validation
        if (empty($_POST["password"])) {
            $passwordErr = "Password is required.";
        } else {
            $password = trim($_POST["password"]); // Don't over-sanitize passwords
            
            // Basic password validation (not as strict as signup since we're just checking existing)
            if (strlen($password) < 1) {
                $passwordErr = "Password cannot be empty.";
            } elseif (strlen($password) > 255) {
                $passwordErr = "Password is too long.";
            }
        }

        // Only check credentials if no validation errors
        if (empty($emailErr) && empty($passwordErr)) {
            try {
                // Query database for user
                $stmt = $pdo->prepare("SELECT user_id, username, email, password, role, created_at FROM users WHERE email = ?");
                $stmt->execute([$email]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($user && password_verify($password, $user['password'])) {
                    // Authentication successful
                    $_SESSION["loggedin"] = true;
                    $_SESSION["user_id"] = $user['user_id'];
                    $_SESSION["username"] = $user['username'];
                    $_SESSION["email"] = $user['email'];
                    $_SESSION["role"] = $user['role'] ?? 'user'; // Default role if not set
                    $_SESSION["login_time"] = time();
                    
                    // Clear failed login attempts
                    unset($_SESSION['login_attempts']);
                    
                    // Log successful login (optional)
                    error_log("Successful login for user: " . $user['email'] . " from IP: " . $ip_address);
                    
                    // Redirect to dashboard or intended page
                    $redirect_url = isset($_SESSION['redirect_after_login']) ? $_SESSION['redirect_after_login'] : 'dashboard.php';
                    unset($_SESSION['redirect_after_login']);
                    
                    header("Location: " . $redirect_url);
                    exit;
                } else {
                    // Authentication failed - record attempt
                    $_SESSION['login_attempts'][] = $current_time;
                    $loginErr = "Invalid email or password.";
                    
                    // Log failed login attempt
                    error_log("Failed login attempt for email: " . $email . " from IP: " . $ip_address);
                }
            } catch (PDOException $e) {
                $loginErr = "Login system temporarily unavailable. Please try again later.";
                error_log("Database error during login: " . $e->getMessage());
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Planify</title>
  <link rel="stylesheet" href="/Planify/CSS/login.css">
  <!-- Prevent caching of login page -->
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="0">
</head>
<body>
  <div class="login-container">
    <div class="login-title">Welcome Back</div>
    
    <!-- Display success message from signup -->
    <?php if($signupSuccessMsg): ?>
      <div class="success" style="background-color: #d4edda; color: #155724; padding: 0.75rem 1rem; margin-bottom: 1rem; border: 1px solid #c3e6cb; border-radius: 4px; text-align: center;">
        <?php echo $signupSuccessMsg; ?>
      </div>
    <?php endif; ?>
    
    <form class="login-form" method="post" action="" autocomplete="off">
      <?php if($loginErr): ?>
        <div class="error" style="background-color: #f8d7da; color: #721c24; padding: 0.75rem 1rem; margin-bottom: 1rem; border: 1px solid #f5c6cb; border-radius: 4px; text-align: center;">
          <?php echo htmlspecialchars($loginErr); ?>
        </div>
      <?php endif; ?>
      
      <div>
        <label class="login-label" for="email">Email</label>
        <input class="login-input" type="email" id="email" name="email" placeholder="example@gmail.com" value="<?php echo htmlspecialchars($email); ?>" required maxlength="255" autocomplete="off">
        <?php if($emailErr): ?>
          <div class="error" style="color: #dc3545; font-size: 0.875rem; margin-top: 0.25rem;">
            <?php echo htmlspecialchars($emailErr); ?>
          </div>
        <?php endif; ?>
      </div>
      
      <div>
        <label class="login-label" for="password">Password</label>
        <div class="password-wrapper" style="position:relative;">
          <input class="login-input" type="password" id="password" name="password" placeholder="**************" required autocomplete="current-password">
          <span id="togglePassword" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);cursor:pointer;">
            <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24"><path stroke="#888" stroke-width="2" d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12Z"/><circle cx="12" cy="12" r="3" stroke="#888" stroke-width="2"/></svg>
            <svg id="eyeClosed" xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" style="display:none;"><path stroke="#888" stroke-width="2" d="M3 3l18 18M10.7 10.7A3 3 0 0 0 12 15a3 3 0 0 0 2.12-.88M6.53 6.53C4.06 8.06 2 12 2 12s4 7 10 7c2.03 0 3.87-.5 5.47-1.47M17.47 17.47C19.94 15.94 22 12 22 22 12s-4-7-10-7c-1.13 0-2.22.13-3.24.37"/></svg>
          </span>
        </div>
        <?php if($passwordErr): ?>
          <div class="error" style="color: #dc3545; font-size: 0.875rem; margin-top: 0.25rem;">
            <?php echo htmlspecialchars($passwordErr); ?>
          </div>
        <?php endif; ?>
      </div>
      
      <div class="login-footer">
        <span>Don't have an account?<a class="signup-link" href="sign.php">Sign Up</a></span>
      </div>
      <button class="login-btn" type="submit">Login</button>
    </form>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Clear form fields on page refresh to prevent browser autocomplete issues
      if (window.performance && window.performance.navigation.type === 1) {
        document.getElementById('email').value = '';
        document.getElementById('password').value = '';
      }

      const passwordInput = document.getElementById('password');
      const togglePassword = document.getElementById('togglePassword');
      const eyeOpen = document.getElementById('eyeOpen');
      const eyeClosed = document.getElementById('eyeClosed');

      // Password show/hide toggle
      togglePassword.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const isPassword = passwordInput.type === 'password';
        passwordInput.type = isPassword ? 'text' : 'password';
        eyeOpen.style.display = isPassword ? 'none' : 'inline';
        eyeClosed.style.display = isPassword ? 'inline' : 'none';
        passwordInput.focus();
      });

      // Auto-hide success message after 5 seconds
      const successMsg = document.querySelector('.success');
      if (successMsg) {
        setTimeout(function() {
          successMsg.style.transition = 'opacity 0.5s';
          successMsg.style.opacity = '0';
          setTimeout(function() {
            successMsg.style.display = 'none';
          }, 500);
        }, 5000);
      }
    });
  </script>
</body>
</html>