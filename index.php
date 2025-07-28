<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Planify</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;500;400&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Montserrat', sans-serif;
            background: #c9d6cb;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .landing-main {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100vw;
            min-height: 100vh;
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
            box-sizing: border-box;
        }
        .landing-left {
            flex: 1 1 0;
            max-width: 540px;
        }
        .landing-logo {
            width: 100px;
            margin-bottom: 32px;
        }
        .landing-headline {
            font-size: 2.8rem;
            font-weight: 800;
            color: #256029;
            margin-bottom: 24px;
            line-height: 1.1;
        }
        .landing-subheading {
            font-size: 1.1rem;
            color: #4b5e4a;
            margin-bottom: 36px;
            font-weight: 500;
            max-width: 420px;
        }
        .cta-btn {
            display: inline-block;
            background: linear-gradient(90deg, #2563eb 0%, #2b7a4b 100%);
            color: #fff;
            font-size: 1.1rem;
            font-weight: 700;
            padding: 12px 36px;
            border: none;
            border-radius: 24px;
            text-decoration: none;
            transition: background 0.2s, box-shadow 0.2s;
            box-shadow: 0 2px 8px rgba(44, 62, 80, 0.10);
        }
        .cta-btn:hover {
            background: linear-gradient(90deg, #2b7a4b 0%, #2563eb 100%);
            box-shadow: 0 4px 16px rgba(44, 62, 80, 0.18);
        }
        .landing-right {
            flex: 1 1 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 320px;
            min-height: 400px;
        }
        .image-placeholder {
            width: 340px;
            height: 280px;
            background: #f3f5f2;
            border-radius: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 32px rgba(44, 62, 80, 0.10);
            padding: 0;
        }
        @media (max-width: 900px) {
            .landing-main {
                flex-direction: column;
                padding: 30px 5px;
            }
            .landing-right {
                margin-top: 32px;
            }
        }
        @media (max-width: 600px) {
            .landing-headline {
                font-size: 1.5rem;
            }
            .image-placeholder {
                width: 220px;
                height: 180px;
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="landing-main">
        <div class="landing-left">
            <img src="planify.png" alt="Planify Logo" class="landing-logo">
            <div class="landing-headline">Never worry<br>about forgetting<br>things again</div>
            <div class="landing-subheading">
                Keep your work on track and your team involved with a simple & visual task management tool. This is the combo to-do list app/task manager that you may be searching for. Planify labels items as today, tomorrow, and upcoming.
            </div>
            <a href="login.php" class="cta-btn">Get Started</a>
        </div>
        <div class="landing-right">
            <div class="image-placeholder">
                <!-- Free SVG illustration of a laptop with notes -->
                <svg width="220" height="180" viewBox="0 0 220 180" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <rect x="30" y="40" width="160" height="90" rx="10" fill="#c9d6cb" stroke="#bfc8b2" stroke-width="3"/>
                  <rect x="50" y="60" width="120" height="50" rx="6" fill="#fff" stroke="#bfc8b2" stroke-width="2"/>
                  <rect x="60" y="70" width="100" height="10" rx="3" fill="#e0e7de"/>
                  <rect x="60" y="85" width="60" height="8" rx="2" fill="#b7d2c2"/>
                  <rect x="60" y="98" width="80" height="7" rx="2" fill="#dedede"/>
                  <rect x="80" y="120" width="60" height="8" rx="2" fill="#bfa46f"/>
                  <rect x="100" y="135" width="20" height="6" rx="2" fill="#e74c3c"/>
                  <rect x="30" y="130" width="160" height="18" rx="6" fill="#bfc8b2"/>
                  <rect x="60" y="135" width="100" height="8" rx="3" fill="#f3f5f2"/>
                  <rect x="90" y="150" width="40" height="10" rx="3" fill="#2563eb"/>
                  <rect x="80" y="160" width="60" height="8" rx="3" fill="#2b7a4b"/>
                </svg>
            </div>
        </div>
    </div>
</body>
</html> 