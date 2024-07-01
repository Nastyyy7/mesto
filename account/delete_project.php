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

// Получаем ID проекта для удаления
if (isset($_GET['id'])) {
    $project_id = $_GET['id'];

    // Удаляем проект
    $sql = "DELETE FROM project WHERE id_project = $project_id";
    if ($conn->query($sql) === TRUE) {
        header("Location: project.php");
        exit;
    } else {
        echo "Ошибка при удалении проекта: " . $conn->error;
    }
} else {
    header("Location: project.php");
    exit;
}
?>
