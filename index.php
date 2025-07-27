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
            background: linear-gradient(120deg, #ede7f6 0%, #d1c4e9 100%);
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
            color: #4b2996;
            margin-bottom: 24px;
            line-height: 1.1;
        }
        .landing-subheading {
            font-size: 1.1rem;
            color: #5e548e;
            margin-bottom: 36px;
            font-weight: 500;
            max-width: 420px;
        }
        .cta-btn {
            display: inline-block;
            background: linear-gradient(90deg, #6a82fb 0%, #fc5c7d 100%);
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
            background: linear-gradient(90deg, #fc5c7d 0%, #6a82fb 100%);
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
            height: 480px;
            background: #e0d7f3;
            border-radius: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #b39ddb;
            font-size: 1.2rem;
            font-weight: 600;
            box-shadow: 0 8px 32px rgba(44, 62, 80, 0.10);
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
            <div class="image-placeholder">[Your image here soon]</div>
        </div>
    </div>
</body>
</html> 