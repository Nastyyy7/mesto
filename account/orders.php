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

// Получаем список заказов из базы данных
$sql = "SELECT * FROM orders";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление заказами</title>
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
        <h2>Заказы</h2>
        <a href="add_order.php">Добавить заказ</a>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Дата начала</th>
                    <th>Дата завершения</th>
                    <th>Статус</th>
                    <th>Оценка</th>
                    <th>Комментарий</th>
                    <th>ID проекта</th>
                    <th>ID пользователя</th>
                    <th>ID дизайнера</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['id_orders']; ?></td>
                        <td><?php echo $row['dataStart']; ?></td>
                        <td><?php echo $row['dataEnd']; ?></td>
                        <td><?php echo $row['status']; ?></td>
                        <td><?php echo $row['stars']; ?></td>
                        <td><?php echo $row['comment']; ?></td>
                        <td><?php echo $row['id_project']; ?></td>
                        <td><?php echo $row['id_user']; ?></td>
                        <td><?php echo $row['id_designer']; ?></td>
                        <td>
                            <a href="edit_order.php?id=<?php echo $row['id_orders']; ?>">Редактировать</a>
                            <a href="delete_order.php?id=<?php echo $row['id_orders']; ?>">Удалить</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </main>

</body>
</html>
