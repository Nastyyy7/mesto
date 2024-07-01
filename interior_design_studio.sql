-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Июл 01 2024 г., 15:15
-- Версия сервера: 8.0.30
-- Версия PHP: 8.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `interior_design_studio`
--

-- --------------------------------------------------------

--
-- Структура таблицы `applications`
--

CREATE TABLE `applications` (
  `id_applications` int NOT NULL,
  `name` varchar(250) NOT NULL,
  `number` varchar(16) NOT NULL,
  `email` varchar(250) NOT NULL,
  `status` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'не обработан'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `applications`
--

INSERT INTO `applications` (`id_applications`, `name`, `number`, `email`, `status`) VALUES
(1, 'настя', '8 111 222 33 44 ', '1@yandex.ru', 'обработан'),
(2, '111', '111', '111@yandex.ru', 'не обработан'),
(10, '11', '11', '11@gmail.ru', 'не обработан'),
(11, '11', '123', 'hsgajhgdshjsadg@hhhs.s', 'не обработан'),
(15, '000', '000', '11@yandex.ru', 'не обработан'),
(17, '55', '55h', '55@gmail.ru', 'не обработан');

-- --------------------------------------------------------

--
-- Структура таблицы `designers`
--

CREATE TABLE `designers` (
  `id_designer` int NOT NULL,
  `fio` varchar(250) NOT NULL,
  `number` varchar(16) NOT NULL,
  `email` varchar(250) NOT NULL,
  `datajob` date NOT NULL,
  `specialization` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `designers`
--

INSERT INTO `designers` (`id_designer`, `fio`, `number`, `email`, `datajob`, `specialization`) VALUES
(1, 'Дмитриева Анастасия Даниловна', '89686218888', 'aaa@yandex.ru', '2023-12-01', 'Дизайнер'),
(2, 'Ульянова Аглая Адамовна', '8 955 333 11 22', 'qqq@yandex.ru', '2024-04-05', '3d дизайнер'),
(4, 'Лена', '12345678910', 'hsgajhgdshjsadg@hhhs.s', '2024-06-04', 'Главный дизайнер');

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id_orders` int NOT NULL,
  `dataStart` date NOT NULL,
  `dataEnd` date NOT NULL,
  `status` varchar(200) NOT NULL,
  `stars` int DEFAULT '0',
  `comment` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '0',
  `id_project` int NOT NULL,
  `id_user` int NOT NULL,
  `id_designer` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id_orders`, `dataStart`, `dataEnd`, `status`, `stars`, `comment`, `id_project`, `id_user`, `id_designer`) VALUES
(1, '2024-05-08', '2024-06-08', 'Завершён', 5, 'очень крутая работа, спасибо', 1, 6, 1),
(2, '2024-06-19', '2024-06-25', 'Завершён', 0, '0', 2, 4, 2),
(5, '2024-06-26', '2024-07-26', 'разработка', 0, '0', 3, 7, 2),
(7, '2024-06-13', '2024-06-25', 'Завершён', 0, '0', 4, 7, 2),
(11, '2024-06-24', '2024-06-29', 'разработка', 0, '0', 2, 4, 1),
(12, '2024-06-11', '2024-07-10', 'создание дизайна', 0, '0', 5, 4, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `project`
--

CREATE TABLE `project` (
  `id_project` int NOT NULL,
  `image` longblob NOT NULL,
  `address` varchar(250) NOT NULL,
  `services` varchar(250) NOT NULL,
  `square` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `project`
--

INSERT INTO `project` (`id_project`, `image`, `address`, `services`, `square`) VALUES
(1, 0x696d6167652033352e706e67, 'Квартира в Воронеже', 'Дизайн, перепланировка и ремонт', 30),
(2, 0x696d6167652034312e706e67, 'Квартира в Москве', 'перепланировка и ремонт', 30),
(3, 0x696d6167652034372e706e67, 'Квартира в Санкт-Питербурге', 'ремонт', 100),
(4, 0x696d6167652035332e706e67, 'Дом в Подмосковье', 'Дизайн, перепланировка и ремонт', 150),
(5, 0x696d6167652035392e706e67, 'Спальня и гостиная', 'Дизайн, перепланировка и ремонт', 20),
(6, 0x696d6167652036302e706e67, 'Кофейня в отеле', 'перепланировка', 15);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id_user` int NOT NULL,
  `username` varchar(200) NOT NULL,
  `number` varchar(16) NOT NULL,
  `email` varchar(250) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id_user`, `username`, `number`, `email`, `password`, `role`) VALUES
(4, '88888888888', '88888888888', '888@y.ru', '$2y$10$awD3EJFWl1/fOI83y6XoQu6HH8.BfUvBQqhTdEPlfYrY.d4urf9DG', 'user'),
(5, '12345678910', '12345678910', '12345678910@y.a', '$2y$10$upRlT6F5TsGXCVlIUsWtKuBP6MmwfPdahXGyoA.3d/mwlwUzXXwhu', 'admin'),
(6, '9999999999', '9999999999', '9999999999@main.ru', '$2y$10$1LZLUDg7orMDQw2cq/bZ/.y7ZDlf9AGjyo22SWtHbclrUzo0dneM2', 'user'),
(7, 'admin1', '1111111111', 'admin1@mail.ru', '$2y$10$3s4YLdFrqJ6t.cFIZ0XtnuHAppFm1T2IuqucVhOm3qcaf.z3/RDxS', 'user'),
(9, 'bbbb', '9999999999', '111@yandex.ru', '$2y$10$yiw7AsWNPhzNk3.h4V4oQ./9Hr5ywN5zxnbyOGZ5CJDgtODx1G2MO', 'user'),
(10, 'bbbb', '1111122222', '12@yandex.ru', '$2y$10$jb5nPlMSJMuTZIVTUT10.ez7NsKl0PplmSjEEnC1HZO9ivB0a/ei6', 'user'),
(11, 'bbbb', '1111122222', '12@yandex.ru', '$2y$10$lrLyxlkHBIDH1UtQtmD3T.yAuNjDiI4hMuAxfbylzeHLh50Gos3ra', 'user');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id_applications`);

--
-- Индексы таблицы `designers`
--
ALTER TABLE `designers`
  ADD PRIMARY KEY (`id_designer`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id_orders`),
  ADD KEY `OrdersDesigners` (`id_designer`),
  ADD KEY `OrdersProjects` (`id_project`),
  ADD KEY `OrdersUsers` (`id_user`);

--
-- Индексы таблицы `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`id_project`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `applications`
--
ALTER TABLE `applications`
  MODIFY `id_applications` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT для таблицы `designers`
--
ALTER TABLE `designers`
  MODIFY `id_designer` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id_orders` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT для таблицы `project`
--
ALTER TABLE `project`
  MODIFY `id_project` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `OrdersDesigners` FOREIGN KEY (`id_designer`) REFERENCES `designers` (`id_designer`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `OrdersProjects` FOREIGN KEY (`id_project`) REFERENCES `project` (`id_project`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `OrdersUsers` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
