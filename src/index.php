<?php require 'config.php'; 
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Главная</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Добро пожаловать!</h1>
        <div class="links">
            <a href="register.php">Регистрация</a> | 
            <a href="login.php">Вход</a>
        </div>
        
        <?php if (isset($_SESSION['user_id'])): ?>
            <div class="success">
                Вы авторизованы как <?= htmlspecialchars($_SESSION['user_name']) ?> 
                <div class="links">
                    <a href="profile.php">Профиль</a> | 
                     <a href="logout.php">Выйти</a>
                 </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>