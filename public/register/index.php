<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/bootstrap.css">
    <link rel="stylesheet" href="/css/navbar.css">
    <link rel="stylesheet" href="/css/footer.css">
    <link rel="stylesheet" href="/css/button.css">
    <link rel="stylesheet" href="/css/register.css">
    <title>Key Manager Service</title>
</head>
<body>
    <?php include_once "./../parts/navbar.html" ?>
    <main>
        <div class="form-register">
            <div class="line">
                <input class="input medium-input" type="text" id="register-username" placeholder="username">
            </div>
            <div class="line">
                <input class="input small-input" type="text" id="register-email" placeholder="email">
                <input class="input small-input" type="text" id="register-phone" placeholder="phone">
            </div>
            <div class="line">
                <input class="input medium-input" type="text" id="register-code" placeholder="Invitation code">
            </div>
            <div class="line">
                <textarea class="input big-input" id="register-key" placeholder="Public Key"></textarea>
            </div>
            <div class="line">
                <button class="confirm" id="confirm-button">
                    <span id="confirm-text">Send</span>
                    <span class="confirm-icon" id="confirm-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                        </svg>
                    </span>
                </button>
            </div>
        </div>
    </main>
    <?php include_once "./../parts/footer.html" ?>
    <script src="/js/copyright.js"></script>
    <script src="/assets/bootstrap.js"></script>
    <script src="/js/register.js"></script>
</body>
</html>