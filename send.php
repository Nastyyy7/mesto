<?php
$conn = mysqli_connect('localhost', 'root', '', 'interior_design_studio');// Подключение к базе данных

// Проверка соединения
if ($conn->connect_error) {
    die("Соединение прервано: " . $conn->connect_error);
}

// Обработка данных из формы
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["username"];
    $number = $_POST["number"];
    $email = $_POST["email"];

    // Подготовка SQL-запроса
    $sql = "INSERT INTO applications (name, number, email)
            VALUES ('$name', '$number', '$email')";
    
    if ($conn->query($sql) === TRUE) {
        
        header("Location: ./index.html");        
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Закрытие подключения к базе данных
$conn->close();

?>

