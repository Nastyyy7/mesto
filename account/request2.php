
<?php
session_start();

if (!isset($_SESSION['id_user']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

include '../conn.php';



// 6
// Получение минимального количества выполненных проектов из формы
$min_completed_projects = isset($_POST['min_completed_projects']) ? $_POST['min_completed_projects'] : 1;

// Получение списка дизайнеров с количеством выполненных проектов, превышающим заданное значение
$sql_designers = "
    SELECT d.id_designer, d.fio, COUNT(o.id_project) AS completed_projects
    FROM designers d
    LEFT JOIN orders o ON d.id_designer = o.id_designer
    WHERE o.status = 'Завершен'
    GROUP BY d.id_designer
    HAVING COUNT(o.id_project) >= ?
";
$stmt = $conn->prepare($sql_designers);
$stmt->bind_param("i", $min_completed_projects);
$result = $stmt->execute();

if (!$result) {
    echo "Ошибка выполнения запроса: " . $stmt->error;
    exit;
}

$result_designers = $stmt->get_result();


// 7
// Получить список всех статусов заказов
$sql_order_statuses = "SELECT DISTINCT status FROM orders";
$result_order_statuses = $conn->query($sql_order_statuses);

// Получить список клиентов, фильтруя по выбранному статусу заказа
$selected_order_status = isset($_POST['order_status']) ? $_POST['order_status'] : null;
$sql_clients = "
    SELECT DISTINCT u.id_user, u.username, u.number, u.email
    FROM users u
    JOIN orders o ON u.id_user = o.id_user
";
if ($selected_order_status) {
    $sql_clients .= " WHERE o.status = '$selected_order_status'";
}
$result_clients = $conn->query($sql_clients);

// 8
// Получить список всех статусов заявок
$sql_specializations = "SELECT DISTINCT specialization FROM designers";
$result_specializations = $conn->query($sql_specializations);

// Получить список заявок, фильтруя по выбранному статусу
$selected_specialization = isset($_POST['specialization']) ? $_POST['specialization'] : null;
if ($selected_specialization) {
    $sql_unprocessed_designers = "SELECT * FROM designers WHERE specialization = '$selected_specialization'";
} else {
    $sql_unprocessed_designers = "SELECT * FROM designers";
}
$result_unprocessed_designers = $conn->query($sql_unprocessed_designers);


// 9
// Получить список всех услуг
$sql_services = "SELECT DISTINCT services FROM project";
$result_services = $conn->query($sql_services);
$services = [];
while ($row = $result_services->fetch_assoc()) {
    $services[] = $row['services'];
}

// Получить значение введенной услуги из формы
$searchService = isset($_POST['searchService']) ? $_POST['searchService'] : '';

// Получить список всех заказов, у которых услуга соответствует введенному значению
$sql_requests = "SELECT r.id_orders, r.dataStart, r.dataEnd, r.status, r.id_project, r.id_user , r.id_designer , p.services
                 FROM orders r
                 JOIN project p ON r.id_project = p.id_project
                 WHERE p.services LIKE ?";
$stmt_requests = $conn->prepare($sql_requests);
$searchValue = "%$searchService%";
$stmt_requests->bind_param("s", $searchValue);
$stmt_requests->execute();
$result_requests = $stmt_requests->get_result();
$requests = $result_requests->fetch_all(MYSQLI_ASSOC);



// 10
// Получить список всех дизайнеров
$sql_designer = "SELECT id_designer, fio FROM designers";
$result_designer = $conn->query($sql_designer);
$designers = $result_designer->fetch_all(MYSQLI_ASSOC);

// Получить значение минимальной площади из формы
$minArea = isset($_POST['minArea']) ? $_POST['minArea'] : '';

// Получить значение дизайнера из формы
$selectedDesigner = isset($_POST['designer']) ? $_POST['designer'] : '';

// Получить список проектов, соответствующих условиям
$sql_projects = "
    SELECT p.id_project, p.address, p.square, d.fio
    FROM project p
    JOIN orders o ON p.id_project = o.id_project
    JOIN designers d ON o.id_designer = d.id_designer
    WHERE p.square >= ?
";

if ($selectedDesigner) {
    $sql_projects .= " AND o.id_designer = ?";
    $stmt_projects = $conn->prepare($sql_projects);
    $stmt_projects->bind_param("ii", $minArea, $selectedDesigner);
} else {
    $stmt_projects = $conn->prepare($sql_projects);
    $stmt_projects->bind_param("i", $minArea);
}

$stmt_projects->execute();
$result_projects = $stmt_projects->get_result();
$projects = $result_projects->fetch_all(MYSQLI_ASSOC);
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

    

    <h2>6. Список дизайнеров, у которых количество выполненных проектов превышает заданное значение</h2>

            <form method="post">
                <label for="min_completed_projects">Минимальное количество выполненных проектов:</label>
                <input type="number" name="min_completed_projects" id="min_completed_projects" value="<?php echo $min_completed_projects; ?>" required>
                <button type="submit">Применить</button>
            </form>

            <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Имя</th>
                    <th>Количество выполненных проектов</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result_designers && $result_designers->num_rows > 0) {
                    while ($row = $result_designers->fetch_assoc()) {
                        echo "<tr>
                                <td>" . $row['id_designer'] . "</td>
                                <td>" . $row['fio'] . "</td>
                                <td>" . $row['completed_projects'] . "</td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='1'>Нет дизайнеров, удовлетворяющих заданным условиям.</td></tr>";
                }
                ?>
            </tbody>
        </table>


<h2>7. Список клиентов по статусу заказа</h2>
                <form method="post">
                <label for="order_status">Выберите статус заказа:</label>
            <select name="order_status" id="order_status">
                <option value="">Все статусы</option>
                <?php
                $result_order_statuses->data_seek(0); // Вернуть курсор в начало результата
                while ($row = $result_order_statuses->fetch_assoc()) {
                    $status = $row['status'];
                    $selected = ($status == $selected_order_status) ? 'selected' : '';
                    echo "<option value='$status' $selected>$status</option>";
                }
                ?>
            </select>
            <button type="submit">Фильтровать</button>
        </form>

        <?php if ($result_clients) { ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Имя пользователя</th>
                        <th>Номер телефона</th>
                        <th>Почта</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = $result_clients->fetch_assoc()) {
                        echo "<tr>
                                <td>" . $row['id_user'] . "</td>
                                <td>" . $row['username'] . "</td>
                                <td>" . $row['number'] . "</td>
                                <td>" . $row['email'] . "</td>
                            </tr>";
                    }
                    ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>Нет клиентов, связанных с заказами указанного статуса.</p>
        <?php } ?>

        
    <h2>8. Список всех дизайнеров</h2>

            <form method="post">
                <label for="specialization">Выберите специальность:</label>
                <select name="specialization" id="specialization">
                    <option value="">Все специализации</option>
                    <?php
                    while ($row = $result_specializations->fetch_assoc()) {
                        $specialization = $row['specialization'];
                        $selected = ($specialization == $selected_specialization) ? 'selected' : '';
                        echo "<option value='$specialization' $selected>$specialization</option>";
                    }
                    ?>
                </select>
                <button type="submit">Фильтровать</button>
            </form>
                
            <?php if ($result_unprocessed_designers) { ?>
                <table>
                    <thead>
                        <tr>
                        <th>ID</th>
                        <th>ФИО</th>
                        <th>Номер</th>
                        <th>Email</th>
                        <th>Дата трудоустройства</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = $result_unprocessed_designers->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . $row['id_designer'] . "</td>
                                    <td>" . $row['fio'] . "</td>
                                    <td>" . $row['number'] . "</td>
                                    <td>" . $row['email'] . "</td>
                                    <td>" . $row['datajob'] . "</td>
                                </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <p>Нет заявок с указанной специализацией.</p>
            <?php } ?>



            <h2>9.Список заказов по услуге</h2>
        <form method="post">
            <label for="searchService">Введите услугу:</label>
            <input type="text" name="searchService" id="searchService" value="<?php echo $searchService; ?>">
            <button type="submit">Найти</button>
        </form>

        <?php if (!empty($requests)) { ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Дата начала</th>
                        <th>Дата окончания</th>
                        <th>статус</th>
                        <th>ID проекта</th>
                        <th>ID клиента</th>
                        <th>ID дизайнера</th>
                        <th>услуга</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($requests as $request) {
                        echo "<tr>
                                <td>" . $request['id_orders'] . "</td>
                                <td>" . $request['dataStart'] . "</td>
                                <td>" . $request['dataEnd'] . "</td>
                                <td>" . $request['status'] . "</td>
                                <td>" . $request['id_project'] . "</td>
                                <td>" . $request['id_user'] . "</td>
                                <td>" . $request['id_designer'] . "</td>
                                <td>" . $request['services'] . "</td>

                            </tr>";
                    }
                    ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>Нет заказов, соответствующих введенной услуге.</p>
        <?php } ?>


        <h2>10. Список проектов по площади и дизайнеру</h2>
        <form method="post">
            <label for="minArea">Минимальная площадь:</label>
            <input type="number" name="minArea" id="minArea" value="<?php echo $minArea; ?>">
            <label for="designer">Выберите дизайнера:</label>
            <select name="designer" id="designer">
                <option value="">Все дизайнеры</option>
                <?php
                foreach ($designers as $designer) {
                    $selected = ($designer['id_designer'] == $selectedDesigner) ? 'selected' : '';
                    echo "<option value='" . $designer['id_designer'] . "' $selected>" . $designer['fio'] . "</option>";
                }
                ?>
            </select>
            <button type="submit">Найти</button>
        </form>

        <?php if (!empty($projects)) { ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Адрес</th>
                        <th>Площадь</th>
                        <th>Дизайнер</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($projects as $project) {
                        echo "<tr>
                                <td>" . $project['id_project'] . "</td>
                                <td>" . $project['address'] . "</td>
                                <td>" . $project['square'] . "</td>
                                <td>" . $project['fio'] . "</td>
                            </tr>";
                    }
                    ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>Нет проектов, соответствующих заданным условиям.</p>
        <?php } ?>
        </main>
</body>
</html>
