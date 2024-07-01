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

// Получаем ID заявки для удаления
if (isset($_GET['id'])) {
    $application_id = $_GET['id'];

    // Удаляем заявку
    $sql = "DELETE FROM applications WHERE id_applications = $application_id";
    if ($conn->query($sql) === TRUE) {
        header("Location: applications.php");
        exit;
    } else {
        echo "Ошибка при удалении заявки: " . $conn->error;
    }
} else {
    header("Location: applications.php");
    exit;
}
?>