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

// Получаем данные заявки для редактирования
if (isset($_GET['id'])) {
    $application_id = $_GET['id'];
    $sql = "SELECT * FROM applications WHERE id_applications = $application_id";
    $result = $conn->query($sql);
    $application = $result->fetch_assoc();
} else {
    header("Location: applications.php");
    exit;
}

// Обработка формы редактирования заявки
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $number = $_POST['number'];
    $email = $_POST['email'];
    $status = $_POST['status'];

    $sql = "UPDATE applications SET name = '$name', number = '$number', email = '$email', status = '$status' WHERE id_applications = $application_id";
    if ($conn->query($sql) === TRUE) {
        header("Location: applications.php");
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
    <title>Редактирование заявки</title>
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
            <label for="name">Имя:</label>           
            <input type="text" id="name" name="name" value="<?php echo $application['name']; ?>" required>

            <label for="number">Номер:</label>
            <input type="text" id="number" name="number" value="<?php echo $application['number']; ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $application['email']; ?>" required>

            <label for="status">Статус:</label>
            <select id="status" name="status">
                <option value="не обработан" <?php if ($application['status'] === 'не обработан') echo 'selected'; ?>>Не обработан</option>
                <option value="обработан" <?php if ($application['status'] === 'обработан') echo 'selected'; ?>>Обработан</option>
            </select>

            <button type="submit">Сохранить изменения</button>
        </form>
    </main>

</body>
</html>
