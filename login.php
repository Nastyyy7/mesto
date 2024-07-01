<?php
session_start();

include 'conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $number = $_POST['number'];
    $password = $_POST['password'];
    $submit = $_POST['submit'];

    $errors = array();

    $site_key = '6Lc37AAqAAAAAGQm6Wn0vAeJD59vKuumqAFhq5ME'; // Ключ сайта reCAPTCHA

    if (empty($submit)) { // Проверка капчи
        // Проверка номера телефона
        if (empty($number)) {
            $errors[] = "Номер телефона обязателен для заполнения.";
        } elseif (!preg_match('/^[0-9]{10,15}$/', $number)) {
            $errors[] = "Некорректный формат номера телефона.";
        }

        // Проверка пароля
        if (empty($password)) {
            $errors[] = "Пароль обязателен для заполнения.";
        }
    }

    if (empty($_POST['g-recaptcha-response'])) { // Капча
        $recaptcha = $_POST['g-recaptcha-response'];
        if (!$recaptcha) {
            $errors[] = "Пожалуйста, подтвердите, что вы не робот";
        } else {
            $secret_key = '6Lc37AAqAAAAAEOACiBHVcALNu2Ik4hA7H0DqCFm'; // Секретный ключ reCAPTCHA
            $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret_key . '&response=' . $recaptcha;
            $response = file_get_contents($url);
            $response_key = json_decode($response, true);

            if (!isset($response_key['success']) || !$response_key['success']) {
                $errors[] = "Проверка reCAPTCHA не пройдена. Попробуйте еще раз.";
            }
        }
    }

    if (empty($errors)) {
        $sql = "SELECT * FROM users WHERE number = '$number'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['id_user'] = $row['id_user'];
                $_SESSION['role'] = $row['role'];
                $_SESSION['number'] = $row['number'];
                $_SESSION['username'] = $row['username'];

                if ($row['role'] === 'admin') {
                    header("Location: ./account/adminpanel.php");
                    exit();
                } else {
                    header("Location: ./account/account.php");
                    exit();
                }
            } else {
                $errors[] = "Неверный номер телефона или пароль.";
            }
        } else {
            $errors[] = "Неверный номер телефона или пароль.";
        }
    }

    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo $error . "<br>";
        }
    }
}
?>