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
                <input class="input medium-input" type="text" placeholder="username">
            </div>
            <div class="line">
                <input class="input small-input" type="text" placeholder="email">
                <input class="input small-input" type="text" placeholder="phone">
            </div>
            <div class="line">
                <input class="input medium-input" type="text" placeholder="Invitation code">
            </div>
            <div class="line">
                <textarea class="input big-input" placeholder="Public Key"></textarea>
            </div>
            <div class="line">
                <button class="confirm">Send</button>
            </div>
        </div>
    </main>
    <?php include_once "./../parts/footer.html" ?>
    <script src="/js/copyright.js"></script>
    <script src="/assets/bootstrap.js"></script>
    <script src="/js/register.js"></script>
</body>
</html>