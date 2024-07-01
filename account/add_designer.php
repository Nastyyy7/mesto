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

// Обработка формы добавления дизайнера
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fio = $_POST['fio'];
    $number = $_POST['number'];
    $email = $_POST['email'];
    $datajob = $_POST['datajob'];
    $specialization = $_POST['specialization'];
    $sql = "INSERT INTO designers (fio, number, email, datajob, specialization) VALUES ('$fio', '$number', '$email', '$datajob', '$specialization')";
    if ($conn->query($sql) === TRUE) {
        header("Location: designers.php");
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
    <title>Добавление дизайнера</title>
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
            <label for="fio">ФИО:</label>
            <input type="text" id="fio" name="fio" required>

            <label for="number">Номер:</label>
            <input type="text" id="number" name="number" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="datajob">Дата приема на работу:</label>
            <input type="date" id="datajob" name="datajob" required>

            <label for="specialization">Специализация:</label>
            <input type="text" id="specialization" name="specialization" required>

            <button type="submit">Добавить дизайнера</button>
        </form>
    </main>

</body>
</html>