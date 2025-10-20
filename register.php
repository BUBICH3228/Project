<?php 
require 'config.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    if ($password !== $password_confirm) {
        $errors[] = "Пароли не совпадают";
    }

    $stmt = $pdo->prepare(query: "SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $errors[] = "Email уже используется";
    }

    $stmt = $pdo->prepare(query: "SELECT id FROM users WHERE phone = ?");
    $stmt->execute([$phone]);
    if ($stmt->fetch()) {
        $errors[] = "Телефон уже используется";
    }

    if (empty($errors)) {
        $hashed_password = password_hash(password: $password, algo: PASSWORD_DEFAULT);
        
        $stmt = $pdo->prepare("INSERT INTO users (name, phone, email, password) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $phone, $email, $hashed_password]);
        
        $_SESSION['success'] = "Регистрация успешна! Теперь войдите в систему.";
        header("Location: login.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Регистрация</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Регистрация</h1>
        <div class="links">
            <a href="index.php">На главную</a>
        </div>
        
        <?php if (!empty($errors)): ?>
            <div class="error">
                <?php foreach ($errors as $error): ?>
                    <p><?= $error ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>Имя:</label>
                <input type="text" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
            </div>
            
            <div class="form-group">
                <label>Телефон:</label>
                <input type="tel" name="phone" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>" required>
            </div>
            
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
            </div>
            
            <div class="form-group">
                <label>Пароль:</label>
                <input type="password" name="password" required>
            </div>
            
            <div class="form-group">
                <label>Повторите пароль:</label>
                <input type="password" name="password_confirm" required>
            </div>
            
            <button type="submit">Зарегистрироваться</button>
        </form>
        
        <p>Уже есть аккаунт? <a href="login.php">Войдите</a></p>
    </div>
</body>
</html>