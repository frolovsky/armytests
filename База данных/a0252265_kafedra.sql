-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Хост: 10.0.0.40
-- Время создания: Май 25 2019 г., 16:28
-- Версия сервера: 5.7.22-22
-- Версия PHP: 7.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `a0252265_kafedra`
--

-- --------------------------------------------------------

--
-- Структура таблицы `admins`
--

CREATE TABLE `admins` (
  `id` int(3) NOT NULL,
  `login` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `email` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `admins`
--

INSERT INTO `admins` (`id`, `login`, `password`, `email`) VALUES
(2, 'lol', 'lol', 'lol'),
(5, 'admin', 'admin', 'admin@voenka.ru');

-- --------------------------------------------------------

--
-- Структура таблицы `profile`
--

CREATE TABLE `profile` (
  `profile_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `profile_name` text NOT NULL,
  `profile_surname` text NOT NULL,
  `profile_vzvod` varchar(20) NOT NULL,
  `profile_group` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `profile`
--

INSERT INTO `profile` (`profile_id`, `user_id`, `profile_name`, `profile_surname`, `profile_vzvod`, `profile_group`) VALUES
(1, 2, 'Alexey', 'Frolovsy', '123', 'TestGROUP'),
(3, 3, 'Александр', 'Головин', '358', 'ММ-14'),
(7, 24, '', '', '', ''),
(8, 20, '', '', '', ''),
(9, 7, '', '', '', '');

-- --------------------------------------------------------

--
-- Структура таблицы `test_results`
--

CREATE TABLE `test_results` (
  `id` int(12) NOT NULL,
  `test_name` varchar(32) NOT NULL,
  `test_time` varchar(32) NOT NULL,
  `test_maxtime` varchar(32) NOT NULL,
  `test_score` varchar(10) NOT NULL,
  `test_minscore` varchar(10) NOT NULL,
  `test_percent` varchar(10) NOT NULL,
  `test_user` varchar(32) NOT NULL,
  `test_userid` int(10) NOT NULL,
  `test_date` date NOT NULL,
  `test_path` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `test_results`
--

INSERT INTO `test_results` (`id`, `test_name`, `test_time`, `test_maxtime`, `test_score`, `test_minscore`, `test_percent`, `test_user`, `test_userid`, `test_date`, `test_path`) VALUES
(2, 'Тест', '31', '600', '100', '80', '80', 'Орлов И.А', 24, '2019-05-14', 'test2'),
(3, 'Тест', '295', '600', '70', '80', '80', 'Ковальцун И.А', 20, '2019-05-14', 'test2');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `login` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `vzvod` varchar(10) NOT NULL,
  `test` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `email`, `vzvod`, `test`) VALUES
(7, 'Горбачёв Н.А', '00870961', 'Не указан', '358', ''),
(8, 'Чернышев А.В', '20020843', 'Не указан', '358', ''),
(9, 'Михлин А.А', '14940398', 'Не указан', '358', ''),
(10, 'Уткин Д.Д', '27262179', 'Не указан', '358', ''),
(11, 'Каташин В.А', '77505734', 'Не указан', '358', ''),
(12, 'Решетко С.А', '75811829', 'Не указан', '358', ''),
(13, 'Пирязев А.С', '67653356', 'Не указан', '358', ''),
(14, 'Васенев А.А', '56622301', 'Не указан', '358', ''),
(15, 'Сухарев П.П', '16168487', 'Не указан', '358', ''),
(16, 'Тункин В.А', '18289977', 'Не указан', '358', ''),
(17, 'Кравчук А.Ю', '82959907', 'Не указан', '358', ''),
(18, 'Аничкин И.И', '89162605', 'Не указан', '358', ''),
(19, 'Момотов Е.О', '69921628', 'Не указан', '358', ''),
(20, 'Ковальцун И.А', '22696175', 'Не указан', '358', ''),
(21, 'Сиротенко М.Р', '86612697', 'Не указан', '358', ''),
(22, 'Борщевский Н.В', '77389321', 'Не указан', '358', ''),
(23, 'Любавин А.Д', '50798636', 'Не указан', '358', ''),
(24, 'Орлов И.А', '75534466', 'Не указан', '358', ''),
(25, 'Смирнов М.А', '31330111', 'Не указан', '358', '');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `profile`
--
ALTER TABLE `profile`
  ADD PRIMARY KEY (`profile_id`);

--
-- Индексы таблицы `test_results`
--
ALTER TABLE `test_results`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `profile`
--
ALTER TABLE `profile`
  MODIFY `profile_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `test_results`
--
ALTER TABLE `test_results`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
