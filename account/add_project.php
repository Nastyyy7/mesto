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

// Обработка формы добавления проекта
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $image = $_FILES['image']['name'];
    $tmp_name = $_FILES['image']['tmp_name'];
    $address = $_POST['address'];
    $services = $_POST['services'];
    $square = $_POST['square'];

    // Сохраняем изображение в папку ../public/project_img/
    $target_dir = "../public/project_img/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    if (move_uploaded_file($tmp_name, $target_file)) {
        $sql = "INSERT INTO project (image, address, services, square) VALUES ('$image', '$address', '$services', '$square')";
        if ($conn->query($sql) === TRUE) {
            header("Location: project.php");
            exit;
        } else {
            echo "Ошибка: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Ошибка при загрузке изображения.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавление проекта</title>
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
        <form method="post" action="" enctype="multipart/form-data">
            <label for="image">Изображение:</label>
            <input type="file" id="image" name="image" required>

            <label for="address">Адрес:</label>
            <input type="text" id="address" name="address" required>

            <label for="services">Услуги:</label>
            <textarea id="services" name="services" required></textarea>

            <label for="square">Площадь:</label>
            <input type="text" id="square" name="square" required>

            <button type="submit">Добавить проект</button>
        </form>
    </main>

</body>
</html>