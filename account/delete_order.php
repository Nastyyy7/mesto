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

// Получаем ID заказа для удаления
if (isset($_GET['id'])) {
    $order_id = $_GET['id'];

    // Удаляем заказ
    $sql = "DELETE FROM orders WHERE id_orders = $order_id";
    if ($conn->query($sql) === TRUE) {
        header("Location: orders.php");
        exit;
    } else {
        echo "Ошибка при удалении заказа: " . $conn->error;
    }
} else {
    header("Location: orders.php");
    exit;
}
?>
