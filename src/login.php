<?php 
require 'config.php';

if (isset($_SESSION['user_id'])) {
    header("Location: profile.php");
    exit;
}

$error = '';

function verifyYandexCaptcha($token) {
    return !empty($token);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login']);
    $password = $_POST['password'];
    
    $captcha_token = $_POST['smart-token'] ?? '';
    
    if (!verifyYandexCaptcha($captcha_token)) {
         $error = "Пройдите проверку капчи";
         $_SESSION['form_data'] = ['login' => $login];
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? OR phone = ?");
        $stmt->execute([$login, $login]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            unset($_SESSION['form_data']);
            
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_phone'] = $user['phone'];

            header("Location: profile.php");
            exit;
        } else {
            $error = "Неверный логин или пароль";
            $_SESSION['form_data'] = ['login' => $login];
        }
    }
}

$saved_login = $_SESSION['form_data']['login'] ?? '';
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход в систему</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://smartcaptcha.yandexcloud.net/captcha.js" async defer></script>
</head>
<body>
    <div class="container">
        <h1>Вход в систему</h1>
        
        <div class="links">
            <a href="index.php">На главную</a>
            <a href="register.php">Регистрация</a>
        </div>
        
        <?php if ($error): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>
        
        <form method="POST" id="loginForm">
            <div class="form-group">
                <label for="login">Email или телефон:</label>
                <input type="text" id="login" name="login" value="<?= htmlspecialchars($saved_login ?: ($_POST['login'] ?? '')) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="password">Пароль:</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div class="form-group">
                <div class="smart-captcha" 
                    data-sitekey="ysc1_Gce3ZmreDmEBDmcq9FzlyP5etdSjK2gyyF0Fh8sAd8af45c8"
                    data-callback="onCaptchaSuccess">
                </div>
            </div>
            
            <input type="hidden" name="smart-token" id="smart-token">
            
            <button type="submit" id="submit-btn">Войти</button>
        </form>
    </div>

    <script>
        function onCaptchaSuccess(token) {
            document.getElementById('smart-token').value = token;
        }
        
        window.smartCaptcha = {
            onError: function(error) {
                console.log('Ошибка капчи:', error);
            }
        };
    </script>
</body>
</html>