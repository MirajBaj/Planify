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
            position: relative;
        }
        
        .signup-btn {
            position: fixed;
            top: 2vh;
            right: 3vh;
            background: #6d9e60;
            color: #fff;
            font-size: 1.8vh;
            font-weight: 600;
            padding: 1.5vh 3vh;
            border: none;
            border-radius: 2.5vh;
            text-decoration: none;
            transition: background 0.2s, transform 0.2s;
            box-shadow: 0 0.3vh 1vh rgba(0,0,0,0.15);
            z-index: 1000;
        }
        
        .signup-btn:hover {
            background: #8ebf84;
            transform: translateY(-0.1vh);
        }
        
        .signup-btn:active {
            transform: translateY(0);
        }
        
        .top-logo {
            position: fixed;
            top: 2vh;
            left: 1vh;
            z-index: 1000;
        }
        
        .top-logo img {
            width: 12vh;
            height: auto;
        }
        
        .landing-main {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100vw;
            min-height: 100vh;
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px 60px;
            box-sizing: border-box;
        }
        .landing-left {
            flex: 0 0 auto;
            max-width: 450px;
            margin-right: 80px;
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
            font-size: 2vh;
            color: #4b5e4a;
            margin-bottom: 4.5vh;
            font-weight: 500;
            max-width: 420px;
            line-height: 1.7;
            text-align: justify;
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
                padding: 30px 20px;
                justify-content: center;
                max-width: 100%;
            }
            .landing-left {
                margin-right: 0;
                max-width: 100%;
                margin-bottom: 40px;
            }
            .landing-right {
                margin-top: 32px;
            }
            .landing-subheading {
                text-align: left;
                font-size: 1.8vh;
                margin-bottom: 4vh;
                max-width: 100%;
            }
            .signup-btn {
                font-size: 2vh;
                padding: 1.8vh 3.5vh;
                top: 1.5vh;
                right: 2vh;
                border-radius: 3vh;
            }
            .top-logo {
                top: 1.5vh;
                left: 0.8vh;
            }
            .top-logo img {
                width: 10vh;
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
            .landing-subheading {
                font-size: 1.6vh;
                max-width: 100%;
                margin-bottom: 3.5vh;
            }
            .signup-btn {
                font-size: 2.2vh;
                padding: 2vh 4vh;
                top: 1vh;
                right: 1.5vh;
                border-radius: 3.5vh;
            }
            .top-logo {
                top: 1vh;
                left: 0.5vh;
            }
            .top-logo img {
                width: 8vh;
            }
        }
    </style>
</head>
<body>
    <div class="top-logo">
        <img src="planify.png" alt="Planify Logo">
    </div>
    <a href="sign.php" class="signup-btn">Sign Up</a>
    <div class="landing-main">
        <div class="landing-left">
            <div class="landing-headline">Never worry<br>about forgetting<br>things again</div>
            <div class="landing-subheading">
            Welcome to Planify— your simple yet powerful digital companion for managing life's daily chaos. Whether you're a student juggling assignments, a professional handling deadlines, or someone striving for balance, Planify empowers you to stay on top of your tasks with clarity and confidence. With intuitive design, category-based organization, and anywhere-access, Planify turns your goals into actionable steps. Say goodbye to stress, forgetfulness, and clutter. Say hello to a more productive, focused, and fulfilled you.
            </div>
            <a href="login.php" class="cta-btn">Get Started</a>
        </div>
        <div class="landing-right">
            <div class="image-placeholder">
                <img src="done.jpg" alt="Planify Illustration" style="width: 100%; height: 100%; object-fit: cover; border-radius: 32px;">
            </div>
        </div>
    </div>
</body>
</html>