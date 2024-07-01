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

// Обработка формы добавления заказа
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dataStart = $_POST['dataStart'];
    $dataEnd = $_POST['dataEnd'];
    $status = $_POST['status'];
    $stars = isset($_POST['stars']) ? $_POST['stars'] : null;
    $comment = isset($_POST['comment']) ? $_POST['comment'] : null;
    $id_project = $_POST['id_project'];
    $id_user = $_POST['id_user'];
    $id_designer = $_POST['id_designer'];

    $sql = "INSERT INTO orders (dataStart, dataEnd, status, stars, comment, id_project, id_user, id_designer) VALUES ('$dataStart', '$dataEnd', '$status', ?, ?, '$id_project', '$id_user', '$id_designer')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $stars, $comment);
    if ($stmt->execute()) {
        header("Location: orders.php");
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
    <title>Добавление заказа</title>
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
            <label for="dataStart">Дата начала:</label>
            <input type="date" id="dataStart" name="dataStart" required>

            <label for="dataEnd">Дата завершения:</label>
            <input type="date" id="dataEnd" name="dataEnd" required>

            <label for="status">Статус:</label>
            <input type="text" id="status" name="status" required>

            <label for="stars">Оценка:</label>
            <input type="number" id="stars" name="stars" min="0" max="5" >

            <label for="comment">Комментарий:</label>
            <textarea id="comment" name="comment" ></textarea>

            <label for="id_project">ID проекта:</label>
            <input type="number" id="id_project" name="id_project" required>

            <label for="id_user">ID пользователя:</label>
            <input type="number" id="id_user" name="id_user" required>

            <label for="id_designer">ID дизайнера:</label>
            <input type="number" id="id_designer" name="id_designer" required>

            <button type="submit">Добавить заказ</button>
        </form>
    </main>

</body>
</html>
