<?php
session_start();

if (!isset($_SESSION['id_user']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

include '../conn.php';
// 1
// Получение ID клиента из формы
$client_id = isset($_POST['client_id']) ? $_POST['client_id'] : null;

if ($client_id) {
    // Получить список всех заказов определенного клиента
    $sql = "SELECT * FROM orders WHERE id_user = $client_id";
    $result = $conn->query($sql);
} else {
    $result = null;
}

// 2
// Получение ID дизайнера из формы
$designer_id = isset($_POST['designer_id']) ? $_POST['designer_id'] : null;

if ($designer_id) {
    // Получить список всех проектов, над которыми работает определенный дизайнер
    $sql_designer = "SELECT p.* 
                     FROM project p
                     JOIN orders o ON p.id_project = o.id_project
                     WHERE o.id_designer = $designer_id
                     GROUP BY p.id_project";
    $result_designer = $conn->query($sql_designer);
} else {
    $result_designer = null;
}


// 3
// Получить список всех статусов заявок
$sql_statuses = "SELECT DISTINCT status FROM applications";
$result_statuses = $conn->query($sql_statuses);
// Получить список заявок, фильтруя по выбранному статусу
$selected_status = isset($_POST['status']) ? $_POST['status'] : null;
if ($selected_status) {
    $sql_unprocessed_applications = "SELECT * FROM applications WHERE status = '$selected_status'";
} else {
    $sql_unprocessed_applications = "SELECT * FROM applications";
}
$result_unprocessed_applications = $conn->query($sql_unprocessed_applications);

// 4
// Получить список отзывов, находящихся в таблице заказов
$sql_reviews = "SELECT * FROM orders WHERE NOT stars = '0' OR NOT comment = '0';";
$result_reviews = $conn->query($sql_reviews);

// 5
// Получить список всех статусов заказов
$sql_order_statuses = "SELECT DISTINCT status FROM orders";
$result_order_statuses = $conn->query($sql_order_statuses);

// Получить список проектов, фильтруя по выбранному статусу заказа
$selected_order_status = isset($_POST['order_status']) ? $_POST['order_status'] : null;
$sql_projects = "
    SELECT DISTINCT p.*
    FROM project p
    JOIN orders o ON p.id_project = o.id_project
";
if ($selected_order_status) {
    $sql_projects .= " WHERE o.status = '$selected_order_status'";
}
$result_projects = $conn->query($sql_projects);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Запросы к базе данных</title>
    <link rel="stylesheet" href="../public/styles/adminpanel.css">
</head>
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

        <h2>1. Список заказов определенного клиента</h2>

            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <label for="client_id">ID клиента:</label>
                <input type="text" id="client_id" name="client_id" required>
                <input type="submit" value="Показать заказы">
            </form>

            <?php if ($result) { ?>
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
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . $row['id_orders'] . "</td>
                                    <td>" . $row['dataStart'] . "</td>
                                    <td>" . $row['dataEnd'] . "</td>
                                    <td>" . $row['status'] . "</td>
                                    <td>" . $row['stars'] . "</td>
                                    <td>" . $row['comment'] . "</td>
                                    <td>" . $row['id_project'] . "</td>
                                    <td>" . $row['id_user'] . "</td>
                                    <td>" . $row['id_designer'] . "</td>
                                </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            <?php } elseif (isset($_POST['client_id'])) { ?>
                <p>Пожалуйста, введите действительный ID клиента.</p>
            <?php } else { ?>
                <p>Пожалуйста, введите ID клиента в форме.</p>
            <?php } ?>
    


        <h2>2. Список проектов, над которыми работает определенный дизайнер</h2>

            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <label for="designer_id">ID дизайнера:</label>
                <input type="text" id="designer_id" name="designer_id">
                <input type="submit" value="Показать проекты">
            </form>

            <?php if ($result_designer) { ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Название</th>
                            <th>Адрес</th>
                            <th>Услуги</th>
                            <th>Площадь</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = $result_designer->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . $row['id_project'] . "</td>
                                    <td>" . $row['address'] . "</td>
                                    <td>" . $row['services'] . "</td>
                                    <td>" . $row['square'] . "</td>
                                </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            <?php } elseif (isset($_POST['designer_id'])) { ?>
                <p>Пожалуйста, введите действительный ID дизайнера.</p>
            <?php } else { ?>
                <p>Пожалуйста, введите ID дизайнера в форме.</p>
            <?php } ?>



        <h2>3. Список всех заявок</h2>

            <form method="post">
                <label for="status">Выберите статус:</label>
                <select name="status" id="status">
                    <option value="">Все статусы</option>
                    <?php
                    while ($row = $result_statuses->fetch_assoc()) {
                        $status = $row['status'];
                        $selected = ($status == $selected_status) ? 'selected' : '';
                        echo "<option value='$status' $selected>$status</option>";
                    }
                    ?>
                </select>
                <button type="submit">Фильтровать</button>
            </form>

            <?php if ($result_unprocessed_applications) { ?>
                <table>
                    <thead>
                        <tr>
                        <th>ID</th>
                        <th>Имя</th>
                        <th>Номер</th>
                        <th>Email</th>
                        <th>Статус</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = $result_unprocessed_applications->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . $row['id_applications'] . "</td>
                                    <td>" . $row['name'] . "</td>
                                    <td>" . $row['number'] . "</td>
                                    <td>" . $row['email'] . "</td>
                                    <td>" . $row['status'] . "</td>
                                </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <p>Нет заявок с указанным статусом.</p>
            <?php } ?>

        <h2>4. Список отзывов</h2>

            <?php if ($result_reviews) { ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Дата начала</th>
                            <th>Дата завершения</th>
                            <th>Статус</th>
                            <th>ID проекта</th>
                            <th>ID пользователя</th>
                            <th>ID дизайнера</th>
                            <th>Оценка</th>
                            <th>Комментарий</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = $result_reviews->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . $row['id_orders'] . "</td>
                                    <td>" . $row['dataStart'] . "</td>
                                    <td>" . $row['dataEnd'] . "</td>
                                    <td>" . $row['status'] . "</td>
                                    <td>" . $row['id_project'] . "</td>
                                    <td>" . $row['id_user'] . "</td>
                                    <td>" . $row['id_designer'] . "</td>
                                    <td>" . $row['stars'] . "</td>
                                    <td>" . $row['comment'] . "</td>
                                </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    <?php } else { ?>
                        <p>Нет отзывов.</p>
                    <?php } ?>


        <h2>5. Список проектов по статусу заказа</h2>
            <form method="post">
                <label for="order_status">Выберите статус заказа:</label>
                <select name="order_status" id="order_status">
                    <option value="">Все статусы</option>
                    <?php
                    while ($row = $result_order_statuses->fetch_assoc()) {
                        $status = $row['status'];
                        $selected = ($status == $selected_order_status) ? 'selected' : '';
                        echo "<option value='$status' $selected>$status</option>";
                    }
                    ?>
                </select>
                <button type="submit">Фильтровать</button>
            </form>

            <?php if ($result_projects) { ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Название</th>
                            <th>Адрес</th>
                            <th>Услуги</th>
                            <th>Площадь</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = $result_projects->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . $row['id_project'] . "</td>
                                    <td>" . $row['image'] . "</td>
                                    <td>" . $row['address'] . "</td>
                                    <td>" . $row['services'] . "</td>
                                    <td>" . $row['square'] . "</td>
                                </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <p>Нет проектов, связанных с заказами указанного статуса.</p>
            <?php } ?>

    </main>
</html>