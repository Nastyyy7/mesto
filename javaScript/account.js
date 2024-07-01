

const $burgerIcon = document.querySelector('.head_end_burger');//кнопка
const $navLinks = document.querySelector('.head_center_list');//список меню

const $button = document.querySelector('.head_end_btn');
const $form = document.querySelector('.footer');

const $openModal = document.querySelector('.head_end_link');//кнопка открывающая модальные окна

const $overlay = document.querySelector('.overlay');//фон

const $regModal = document.querySelector('.register');
const $loginModal = document.querySelector('.login');//модальные окна

const $RegToLogin = document.querySelector('.register_content_form_link');
const $LoginToReg = document.querySelector('.login_content_form_link');//ссылки между модалками

const $closeLoginModal = document.querySelector('.login_content_head_close');
const $closeRegModal = document.querySelector('.register_content_head_close');//кнопки скрывающие модальные окна

$openModal.addEventListener('click', () => {
    $regModal.style.display = 'block';
    $overlay.style.display = 'block';
});

$closeLoginModal.addEventListener('click', () => {
    $loginModal.style.display = 'none';
    $overlay.style.display = 'none';
});

$closeRegModal.addEventListener('click', () => {
    $regModal.style.display = 'none';
    $overlay.style.display = 'none';

});

$RegToLogin.addEventListener('click', () => {
    $loginModal.style.display = 'block';
    $regModal.style.display = 'none';
});

$LoginToReg.addEventListener('click', () => {
    $regModal.style.display = 'block';
    $loginModal.style.display = 'none';
});


$button.addEventListener('click', e => {
    // Прокрутим страницу к форме 
    $form.scrollIntoView({ 
      block: 'nearest', // к ближайшей границе экрана
      behavior: 'smooth', // и плавно 
    });
  });

  
$burgerIcon.addEventListener('click', () => {//по клику 
    document.querySelector('.head_center_list').classList.toggle('active');//активировать бургер меню
});





const $openFeedback = document.querySelector('.person_history_yes_block_feedback');
const $Feedback = document.querySelector('.feedback');
const $closeFeedModal = document.querySelector('.feedback_content_head_close');

if ($openFeedback && $Feedback && $closeFeedModal && $overlay) {
    $openFeedback.addEventListener('click', () => {
    $Feedback.style.display = 'block';
    $overlay.style.display = 'block';
    });
    
    $closeFeedModal.addEventListener('click', () => {
    $Feedback.style.display = 'none';
    $overlay.style.display = 'none';
    });
    } else {
        console.log('нет');
    }


// $openFeedback.addEventListener('click', () => {
//     $Feedback.style.display = 'block';
//     $overlay.style.display = 'block';
// });

// $closeFeedModal.addEventListener('click', () => {
//     $Feedback.style.display = 'none';
//     $overlay.style.display = 'none';
// });



