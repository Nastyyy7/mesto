
<?php
session_start();
include 'conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $number = $_POST['number'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    $errors = array();

    // Проверка имени пользователя
    if (empty($username)) {
        $errors[] = "Имя пользователя обязательно для заполнения.";
    } elseif (!preg_match('/^[a-zA-Zа-яА-Я0-9_]+$/', $username)) {
        $errors[] = "Имя пользователя может содержать только буквы, цифры и символ подчеркивания.";
    }

    // Проверка email
   

    // Проверка номера телефона
    if (empty($number)) {
        $errors[] = "Номер телефона обязателен для заполнения.";
    } elseif (!preg_match('/^[0-9]{10,15}$/', $number)) {
        $errors[] = "Некорректный формат номера телефона.";
    }

    // Проверка пароля
    if (empty($password)) {
        $errors[] = "Пароль обязателен для заполнения.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Пароль должен быть не менее 6 символов.";
    } elseif ($password !== $confirm_password) {
        $errors[] = "Пароли не совпадают.";
    }

    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (username, email, number, password) VALUES ('$username', '$email', '$number', '$hashed_password')";

        if ($conn->query($sql) === TRUE) {
            $_SESSION['username'] = $username;
            $_SESSION['number'] = $number;

            echo "Регистрация прошла успешно!";
        } else {
            echo "Ошибка: " . $sql . "<br>" . $conn->error;
        }
    } else {
        foreach ($errors as $error) {
            echo $error . "<br>";
        }
    }
}