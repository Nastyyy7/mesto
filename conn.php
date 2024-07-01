<?php


$conn = mysqli_connect('localhost', 'root', '', 'interior_design_studio');// Подключение к базе данных

// Проверка соединения
if ($conn->connect_error) {
    die("Соединение прервано: " . $conn->connect_error);
}