<?php
session_start();

// Проверяем, является ли пользователь админом
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Подключаемся к базе данных
$conn = mysqli_connect('localhost', 'root', '', 'interior_design_studio');
if ($conn->connect_error) {
    die("Ошибка подключения к базе данных: " . $conn->connect_error);
}

// Получаем данные пользователя для редактирования
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $sql = "SELECT * FROM users WHERE id_user = $user_id";
    $result = $conn->query($sql);
    $user = $result->fetch_assoc();
} else {
    header("Location: users.php");
    exit;
}

// Обработка формы редактирования пользователя
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $number = $_POST['number'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $sql = "UPDATE users SET username = '$username', number = '$number', email = '$email', password = '$password', role = '$role' WHERE id_user = $user_id";
    if ($conn->query($sql) === TRUE) {
        header("Location: users.php");
        exit;
    } else {
        echo "Ошибка: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактирование пользователя</title>
    <link rel="stylesheet" href="../public/styles/adminpanel.css">
</head>
<body>
<header>
        <div class="logo">
          <img class="logo_img" src="../public/images/mestoLogo.svg" alt="">
          <a class="logo_name" href="./adminpanel.php">mesto</a>
        </div>
        <nav>
            <ul>
                <li><a href="./users.php">Пользователи</a></li>
                <li><a href="./orders.php">Заказы</a></li>
                <li><a href="./project.php">Проекты</a></li>
                <li><a href="./designers.php">Дизайнеры</a></li>
                <li><a href="./applications.php">Заявки</a></li>
                <li><a href="./request1.php">Запросы1</a></li>
                <li><a href="./request2.php">Запросы2</a></li>
            </ul>
        </nav>
        <a href="../index.html">Выйти</a>
    </header>

    <main>
        <form method="post" action="">
            <label for="username">Имя:</label>
            <input type="text" id="username" name="username" value="<?php echo $user['username']; ?>" required>

            <label for="number">Номер:</label>
            <input type="text" id="number" name="number" value="<?php echo $user['number']; ?>" required>

            <label for="email">Электронная почта:</label>
            <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required>

            <label for="password">Пароль:</label>
            <input type="password" id="password" name="password" value="<?php echo $user['password']; ?>" required>

            <label for="role">Роль:</label>
            <select id="role" name="role" required>
                <option value="user" <?php if ($user['role'] === 'user') echo 'selected'; ?>>Пользователь</option>
                <option value="admin" <?php if ($user['role'] === 'admin') echo 'selected'; ?>>Администратор</option>
            </select>

            <button type="submit">Сохранить изменения</button>
        </form>
    </main>
</body>
</html>
