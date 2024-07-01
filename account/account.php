<?php
session_start();

include '../conn.php';

if (!isset($_SESSION['id_user']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("Location: ../index.html");
    exit;
}
$id_user = $_SESSION['id_user'];
$number = $_SESSION['number'];
$username = $_SESSION['username'];


// Получение истории заказов пользователя
$sql_history = "SELECT o.*, p.* 
       FROM orders o
       JOIN project p ON o.id_project = p.id_project
       WHERE o.id_user = $id_user
       AND o.status = 'Завершён'
       AND o.dataEnd < CURDATE()
       ORDER BY o.dataStart DESC";
$result_history = $conn->query($sql_history);

// Получение статуса заказов пользователя
$sql_status = "SELECT o.*, p.* 
       FROM orders o
       JOIN project p ON o.id_project = p.id_project
       WHERE o.id_user = $id_user
       AND o.status <> 'Завершён'
       AND o.dataStart <= CURDATE() 
       AND o.dataEnd >= CURDATE()
       ORDER BY o.dataStart DESC";
$result_status = $conn->query($sql_status);


?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <link rel="icon" type="image/svg+xml" href="../public/images/mestoLogo.svg" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../public/styles/account.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <title>Account Vite App</title>
  </head>
  <body>
    <header>
      <div class="header-fix">
          <div class="conteiner">
            <div class="head">
              <div class="head_logo">
                <img class="head_logo_img" src="../public/images/mestoLogo.svg" alt="">
                <a class="head_logo_name" href="../index.html">mesto</a>
              </div>
              <nav class="head_center">
                <ul class="head_center_list">
                  <li class="head_center_list_item"><a class="head_center_list_item_link" href="../project/project.html">Проекты</a></li>
                  <li class="head_center_list_item"><a class="head_center_list_item_link" href="../service/service.html">Услуги</a></li>
                  <li class="head_center_list_item"><a class="head_center_list_item_link" href="../company/company.html">О нас</a></li>
                </ul>
              </nav>
              <div class="head_end">
                <button class="head_end_btn">Отправить заявку</button>
                <img class="head_end_link" src="../public/images/acc.svg" alt="">
                <img class="head_end_burger" src="../public/images/menu.svg" alt="">
              </div>
            </div>
          </div>
      </div>
    </header>
    <section style="background-color: #e8e8e8;">
        <div class="conteiner">
            <div class="account">
                <div class="account_way">
                    <a class="account_way_link" href="../index.html">Главная</a>
                    <img class="account_way_point" src="../public/images/mestoLogo(dark).svg" alt="">
                    <a class="account_way_link" href="./index.html">Личный кабинет</a>
                </div>
                <div class="account_person">
                    <div class="account_person_choose">
                        <img class="account_person_choose_img" src="../public/images/avatars/avatar girl with hair.png" alt="">
                        <a class="account_person_choose_link" href="">изменить фото</a>
                    </div>
                    <div class="account_person_back">
                        <p class="account_person_back_name"><?php echo $username; ?></p>
                        <p class="account_person_back_name"><?php echo $number; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section style="background-color: #e8e8e8;">
      <div class="conteiner">
        <div class="person">
          <div class="person_status">
            <p class="person_status_head">Статус заказа:</p>
            <div class="person_status_yes">
            <?php if ($result_status->num_rows > 0) {
                  while($row = $result_status->fetch_assoc()) { ?>
              <div class="person_status_yes_info">
                <img class="person_status_yes_info_img" src="../public/images/<?php echo $row['image']; ?>" alt="">
                <p class="person_status_yes_info_address"><?php echo $row['address']; ?></p>
                <p class="person_status_yes_info_status"><?php echo $row['status']; ?></p>
              </div>
              <button class="person_status_yes_btn">Позвонить специалисту</button>
              <?php }
                  } else { ?>
              <p class="person_status_yes_null">У вас пока нет действущих заказов</p>
              <?php } ?>
            </div>
          </div>
          <div class="person_history">
            <p class="person_history_head">История заказов:</p>
            <div class="person_history_yes">
            <?php if ($result_history->num_rows > 0) {
                  while($row = $result_history->fetch_assoc()) { ?>
              <div class="person_history_yes_block">
                <img class="person_history_yes_block_img" src="../public/images/<?php echo $row['image']; ?>" alt="">
                <p class="person_history_yes_block_addres"><?php echo $row['address']; ?></p>
                <p class="person_history_yes_block_type"><?php echo $row['services']; ?></p>
                <p class="person_history_yes_block_square"><?php echo $row['square']; ?>м2</p>
                <p class="person_history_yes_block_feedback"  >Оставить отзыв</p>
              </div>
            </div>
            <?php }
                  } else { ?>
            <p class="person_history_null">У вас пока нет завершённых заказов</p>
                <?php } ?>
          
        </div>
      </div>
    </section>
    <section style="background-color: #3B444B">
      <div class="conteiner">
        <div class="form">
          <div class="form_text">
            <h3 class="form_text_head">Оставить заявку</h3>
            <p class="form_text_paragraf">Свяжитесь с нами, чтобы обсудить проект, или закажите обратный звонок</p>
          </div>
          <div class="form_block">
            <form class="form_block_send" method="post" action="../send.php">
              <input class="form_block_send_input" type="text" name="username" placeholder="имя" required><br>
              <input class="form_block_send_input" type="number" name="number" placeholder="номер телефона" required><br>
              <input class="form_block_send_input" type="email" name="email" placeholder="email" required><br>
              <p class="form_block_send_text">Нажимая эту кнопку, вы соглашаетесьс политикой обработки персональных данных</p>
              <input class="form_block_send_button" type="submit" name="send" value="Заказать звонок">
            </form>
          </div>
        </div>
      </div>
    </section>
    <footer style="background-color: #3B444B">
      <div class="conteiner">
        <div class="footer">
          <div class="footer_logo">
            <a href="../index.html"><img class="footer_logo_img" src="../public/images/mestoLogo.svg" alt=""></a>
            <a class="footer_logo_text" href="../index.html">mesto</a>
          </div>
          <div class="footer_right">
            <p class="footer_right_text">© mesto, Все права защищены, 2024</p>
            <p class="footer_right_text">Политика конфиденциальности</p>
          </div>
        </div>
      </div>
    </footer>




      <div class="overlay"></div>

      <div class="conteiner">
        <div class="login">
          <div class="login_content">
            <div class="login_content_head">
              <h3 class="login_content_head_name">Вход</h3>
              <img class="login_content_head_close" src="../public/images/close.svg" alt="">
            </div>
            <form class="login_content_form" method="post" action="../login.php">
              <input class="login_content_form_input" type="text" name="number" placeholder="номер телефона" required><br>
              <input class="login_content_form_input" type="password" name="password" placeholder="пароль" required><br>
              <div class="g-recaptcha" name="g-recaptcha" data-sitekey="6Lc37AAqAAAAAGQm6Wn0vAeJD59vKuumqAFhq5ME"></div><br>
              <input class="login_content_form_link" type="button" name="LoginToReg" value="Нет аккаунта?">
              <input class="login_content_form_button" type="submit" name="submit" value="Войти">
          </form>
          </div>
        </div>
      </div>
  
  
      <div class="conteiner">
        <div class="register">
          <div class="register_content">
            <div class="register_content_head">
              <h3 class="register_content_head_name">Регистрация</h3>
              <img class="register_content_head_close" src="../public/images/close.svg" alt="">
            </div>
            <form class="register_content_form" method="post" action="../reg.php">
              <input class="register_content_form_input" type="text" name="username" placeholder="имя" required><br>
              <input class="register_content_form_input" type="text" name="number" placeholder="номер телефона" required><br>
              <input class="register_content_form_input" type="text" name="email" placeholder="email" required><br>
              <input class="register_content_form_input" type="password" name="password" placeholder="пароль" required><br>
              <input class="register_content_form_input" type="password" name="confirm_password" placeholder="повторите пароль" required><br>
              <input class="register_content_form_link" type="button" name="RegToLogin" value="Есть аккаунт?">
              <input class="register_content_form_button" type="submit" name="registration" value="Зарегистрироваться">
          </form>
          </div>
        </div>
      </div>

      <div class="conteiner">
    <div class="feedback">
        <div class="feedback_content">
            <div class="feedback_content_head">
                <h3 class="feedback_content_head_name">Оставить отзыв</h3>
                <img class="feedback_content_head_close" src="../public/images/close.svg" alt="">
            </div>
                <form id="feedback_content_form" method="POST" action="./">
                    <div class="feedback_content_form_rating">
                        <input class="feedback_content_form_rating" type="radio" id="star5" name="stars" value="5">
                        <label for="star5">&#9733;</label>
                        <input class="feedback_content_form_rating" type="radio" id="star4" name="stars" value="4">
                        <label for="star4">&#9733;</label>
                        <input class="feedback_content_form_rating" type="radio" id="star3" name="stars" value="3">
                        <label for="star3">&#9733;</label>
                        <input class="feedback_content_form_rating" type="radio" id="star2" name="stars" value="2">
                        <label for="star2">&#9733;</label>
                        <input class="feedback_content_form_rating" type="radio" id="star1" name="stars" value="1">
                        <label for="star1">&#9733;</label>
                    </div>
                    <div class="feedback_content_form_comment">
                        <textarea id="comment" name="comment"></textarea>
                    </div>
                    <button type="submit" class="feedback_content_form_btn">Отправить</button>
                </form>
        </div>
    </div>
</div>



    <script type="module" src="../javaScript/account.js"></script>
          
  </body>
</html>

