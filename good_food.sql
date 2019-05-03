-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Май 03 2019 г., 18:48
-- Версия сервера: 10.1.31-MariaDB
-- Версия PHP: 7.2.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `good_food`
--

-- --------------------------------------------------------

--
-- Структура таблицы `eating`
--

CREATE TABLE `eating` (
  `id_eating` int(11) UNSIGNED NOT NULL,
  `id_user` int(11) UNSIGNED NOT NULL,
  `date_time` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Очистить таблицу перед добавлением данных `eating`
--

TRUNCATE TABLE `eating`;
-- --------------------------------------------------------

--
-- Структура таблицы `food_in_eating`
--

CREATE TABLE `food_in_eating` (
  `id` int(11) UNSIGNED NOT NULL,
  `id_eating` int(11) UNSIGNED NOT NULL,
  `id_product` int(11) UNSIGNED NOT NULL,
  `amount` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Очистить таблицу перед добавлением данных `food_in_eating`
--

TRUNCATE TABLE `food_in_eating`;
-- --------------------------------------------------------

--
-- Структура таблицы `food_picture`
--

CREATE TABLE `food_picture` (
  `id_picture` int(11) UNSIGNED NOT NULL,
  `address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Очистить таблицу перед добавлением данных `food_picture`
--

TRUNCATE TABLE `food_picture`;
--
-- Дамп данных таблицы `food_picture`
--

INSERT INTO `food_picture` (`id_picture`, `address`) VALUES
(1, '../pict/Penguins.jpg'),
(4, '../pict/Jellyfish.jpg'),
(5, '../pict/Tulips.jpg'),
(9, '../pict/гречка.jpg'),
(10, '../pict/говядина.jpg'),
(11, '../pict/груша.jpg'),
(12, '../pict/капуста.jpg'),
(13, '../pict/кешью.jpg'),
(14, '../pict/курица.jpg'),
(15, '../pict/миндаль.jpg'),
(16, '../pict/огурец.jpg'),
(17, '../pict/пастила.jpg'),
(18, '../pict/рис.jpg'),
(19, '../pict/салат-айсберг.jpg'),
(20, '../pict/салат-латук.jpg'),
(21, '../pict/семга.jpg'),
(22, '../pict/сыр.jpg'),
(23, '../pict/творог.jpg'),
(24, '../pict/томат.jpg'),
(25, '../pict/чернослив.jpg'),
(26, '../pict/шампиньон.jpg'),
(27, '../pict/шоколад.jpg'),
(28, '../pict/яблоко.jpg'),
(29, '../pict/яйцо.jpg');

-- --------------------------------------------------------

--
-- Структура таблицы `glycemic_index`
--

CREATE TABLE `glycemic_index` (
  `id_gi_type` int(11) UNSIGNED NOT NULL,
  `name` varchar(20) NOT NULL,
  `min_gi` int(11) UNSIGNED NOT NULL,
  `max_gi` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Очистить таблицу перед добавлением данных `glycemic_index`
--

TRUNCATE TABLE `glycemic_index`;
--
-- Дамп данных таблицы `glycemic_index`
--

INSERT INTO `glycemic_index` (`id_gi_type`, `name`, `min_gi`, `max_gi`) VALUES
(1, 'высокий', 71, 100),
(2, 'средний', 51, 70),
(3, 'низкий', 0, 50);

-- --------------------------------------------------------

--
-- Структура таблицы `normal`
--

CREATE TABLE `normal` (
  `id_normal` int(11) UNSIGNED NOT NULL,
  `height` int(11) UNSIGNED NOT NULL,
  `gender` varchar(1) NOT NULL,
  `min_weight` int(11) UNSIGNED NOT NULL,
  `max_weight` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Очистить таблицу перед добавлением данных `normal`
--

TRUNCATE TABLE `normal`;
--
-- Дамп данных таблицы `normal`
--

INSERT INTO `normal` (`id_normal`, `height`, `gender`, `min_weight`, `max_weight`) VALUES
(1, 150, 'm', 50, 54),
(2, 151, 'm', 51, 55),
(3, 152, 'm', 52, 56),
(4, 153, 'm', 53, 57),
(5, 154, 'm', 54, 58),
(6, 155, 'm', 55, 59),
(7, 156, 'm', 56, 60),
(8, 157, 'm', 57, 61),
(9, 158, 'm', 58, 62),
(10, 159, 'm', 59, 63),
(11, 160, 'm', 60, 64),
(12, 161, 'm', 61, 65),
(13, 162, 'm', 62, 66),
(14, 163, 'm', 63, 67),
(15, 164, 'm', 64, 68),
(16, 165, 'm', 65, 69),
(17, 166, 'm', 66, 70),
(18, 167, 'm', 67, 71),
(19, 168, 'm', 68, 72),
(20, 169, 'm', 69, 73),
(21, 170, 'm', 70, 74),
(22, 171, 'm', 71, 75),
(23, 172, 'm', 72, 76),
(24, 173, 'm', 73, 77),
(25, 174, 'm', 74, 78),
(26, 175, 'm', 75, 79),
(27, 176, 'm', 76, 80),
(28, 177, 'm', 77, 81),
(29, 178, 'm', 78, 82),
(30, 179, 'm', 79, 83),
(31, 180, 'm', 80, 84),
(32, 181, 'm', 81, 85),
(33, 182, 'm', 82, 86),
(34, 183, 'm', 83, 87),
(35, 184, 'm', 84, 88),
(36, 185, 'm', 85, 89),
(37, 186, 'm', 86, 90),
(38, 187, 'm', 87, 91),
(39, 188, 'm', 88, 92),
(40, 189, 'm', 89, 93),
(41, 190, 'm', 90, 94),
(42, 150, 'f', 45, 50),
(43, 151, 'f', 46, 51),
(44, 152, 'f', 47, 52),
(45, 153, 'f', 48, 53),
(46, 154, 'f', 49, 54),
(47, 155, 'f', 50, 55),
(48, 156, 'f', 51, 56),
(49, 157, 'f', 52, 57),
(50, 158, 'f', 53, 58),
(51, 159, 'f', 54, 59),
(52, 160, 'f', 55, 60),
(53, 161, 'f', 56, 61),
(54, 162, 'f', 57, 62),
(55, 163, 'f', 58, 63),
(56, 164, 'f', 59, 64),
(57, 165, 'f', 60, 65),
(58, 166, 'f', 61, 66),
(59, 167, 'f', 62, 67),
(60, 168, 'f', 63, 68),
(61, 169, 'f', 64, 69),
(62, 170, 'f', 65, 70),
(63, 171, 'f', 66, 71),
(64, 172, 'f', 67, 72),
(65, 173, 'f', 68, 73),
(66, 174, 'f', 69, 74),
(67, 175, 'f', 70, 75),
(68, 176, 'f', 71, 76),
(69, 177, 'f', 72, 77),
(70, 178, 'f', 73, 78),
(71, 179, 'f', 74, 79),
(72, 180, 'f', 75, 80),
(73, 181, 'f', 76, 81),
(74, 182, 'f', 77, 82),
(75, 183, 'f', 78, 83),
(76, 184, 'f', 79, 84),
(77, 185, 'f', 80, 85),
(78, 186, 'f', 81, 86),
(79, 187, 'f', 82, 87),
(80, 188, 'f', 83, 88),
(81, 189, 'f', 84, 89),
(82, 190, 'f', 85, 90);

-- --------------------------------------------------------

--
-- Структура таблицы `product_list`
--

CREATE TABLE `product_list` (
  `id_product` int(11) UNSIGNED NOT NULL,
  `prod_name` varchar(20) NOT NULL,
  `id_group` int(11) UNSIGNED NOT NULL,
  `proteins` float UNSIGNED NOT NULL,
  `fats` float UNSIGNED NOT NULL,
  `carbohydrates` float NOT NULL,
  `ccal` int(11) UNSIGNED NOT NULL,
  `gi` int(11) DEFAULT NULL,
  `id_gi_type` int(11) UNSIGNED NOT NULL,
  `id_picture` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Очистить таблицу перед добавлением данных `product_list`
--

TRUNCATE TABLE `product_list`;
--
-- Дамп данных таблицы `product_list`
--

INSERT INTO `product_list` (`id_product`, `prod_name`, `id_group`, `proteins`, `fats`, `carbohydrates`, `ccal`, `gi`, `id_gi_type`, `id_picture`) VALUES
(1, 'гречка', 8, 10, 0, 70, 320, 50, 3, 9),
(2, 'говядина отварная', 4, 35, 10, 2, 238, 1, 3, 10),
(3, 'груша', 1, 2, 0, 45, 188, 55, 2, 11),
(4, 'капуста', 2, 2, 0, 5, 28, 10, 3, 12),
(5, 'кешью', 11, 25, 50, 15, 610, 30, 3, 13),
(6, 'грудка куриная отвар', 4, 25, 10, 0, 190, 0, 3, 14),
(7, 'миндаль', 11, 25, 60, 10, 680, 20, 3, 15),
(8, 'огурец', 2, 0, 0, 3, 12, 2, 3, 16),
(9, 'пастила', 12, 10, 15, 70, 455, 70, 2, 17),
(10, 'рис басмати', 8, 15, 2, 60, 308, 45, 3, 18),
(11, 'салат айсберг', 10, 0, 0, 3, 12, 0, 3, 19),
(12, 'салат латук', 10, 0, 0, 2, 8, 0, 3, 20),
(13, 'семга', 5, 28, 8, 4, 200, 0, 3, 21),
(14, 'сыр', 7, 15, 45, 3, 477, 10, 3, 22),
(15, 'творог', 7, 20, 5, 5, 148, 10, 3, 23),
(16, 'томат', 2, 2, 0, 3, 20, 10, 3, 24),
(18, 'шампиньоны', 9, 12, 3, 20, 155, 15, 3, 26),
(19, 'шоколад', 12, 2, 30, 60, 518, 90, 1, 27),
(20, 'яблоко', 1, 0, 0, 45, 180, 50, 3, 28),
(21, 'яйцо вареное', 6, 15, 10, 0, 150, 0, 3, 29),
(22, 'вода', 14, 0, 0, 0, 0, 0, 3, NULL),
(24, 'чернослив', 3, 2, 20, 70, 468, 89, 1, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `product_type`
--

CREATE TABLE `product_type` (
  `id_product_type` int(11) UNSIGNED NOT NULL,
  `name` varchar(50) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Очистить таблицу перед добавлением данных `product_type`
--

TRUNCATE TABLE `product_type`;
--
-- Дамп данных таблицы `product_type`
--

INSERT INTO `product_type` (`id_product_type`, `name`) VALUES
(1, 'фрукт'),
(2, 'овощ'),
(3, 'сухофрукт'),
(4, 'мясо'),
(5, 'морепродукты'),
(6, 'яйца'),
(7, 'молочные'),
(8, 'крупа'),
(9, 'грибы'),
(10, 'зелень'),
(11, 'орехи'),
(12, 'сладости'),
(13, 'фаст фуд'),
(14, 'напитки');

-- --------------------------------------------------------

--
-- Структура таблицы `result`
--

CREATE TABLE `result` (
  `id_result` int(11) UNSIGNED NOT NULL,
  `id_user` int(11) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `current_weight` int(11) UNSIGNED NOT NULL,
  `normal_ccal` int(11) UNSIGNED NOT NULL,
  `normal_water` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Очистить таблицу перед добавлением данных `result`
--

TRUNCATE TABLE `result`;
-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `id_user` int(11) UNSIGNED NOT NULL,
  `gender` varchar(1) NOT NULL,
  `birth` int(11) NOT NULL,
  `id_normal` int(11) UNSIGNED NOT NULL,
  `height` int(11) NOT NULL,
  `lifestyle_code` int(11) NOT NULL DEFAULT '1',
  `purpose` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Очистить таблицу перед добавлением данных `user`
--

TRUNCATE TABLE `user`;
--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `eating`
--
ALTER TABLE `eating`
  ADD PRIMARY KEY (`id_eating`),
  ADD KEY `FK_eating_id_user` (`id_user`);

--
-- Индексы таблицы `food_in_eating`
--
ALTER TABLE `food_in_eating`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_food_in_eating_id_eating` (`id_eating`),
  ADD KEY `FK_food_in_eating_id_product` (`id_product`);

--
-- Индексы таблицы `food_picture`
--
ALTER TABLE `food_picture`
  ADD PRIMARY KEY (`id_picture`),
  ADD UNIQUE KEY `id_picture` (`id_picture`);

--
-- Индексы таблицы `glycemic_index`
--
ALTER TABLE `glycemic_index`
  ADD PRIMARY KEY (`id_gi_type`);

--
-- Индексы таблицы `normal`
--
ALTER TABLE `normal`
  ADD PRIMARY KEY (`id_normal`);

--
-- Индексы таблицы `product_list`
--
ALTER TABLE `product_list`
  ADD PRIMARY KEY (`id_product`),
  ADD KEY `FK_product_list_id_group` (`id_group`),
  ADD KEY `FK_product_list_id_gi_type` (`id_gi_type`),
  ADD KEY `FK_product_list_id_picture` (`id_picture`);

--
-- Индексы таблицы `product_type`
--
ALTER TABLE `product_type`
  ADD PRIMARY KEY (`id_product_type`);

--
-- Индексы таблицы `result`
--
ALTER TABLE `result`
  ADD PRIMARY KEY (`id_result`),
  ADD KEY `FK_result_id_user` (`id_user`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD KEY `FK_user_id_normal` (`id_normal`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `eating`
--
ALTER TABLE `eating`
  MODIFY `id_eating` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=153;

--
-- AUTO_INCREMENT для таблицы `food_in_eating`
--
ALTER TABLE `food_in_eating`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT для таблицы `food_picture`
--
ALTER TABLE `food_picture`
  MODIFY `id_picture` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT для таблицы `glycemic_index`
--
ALTER TABLE `glycemic_index`
  MODIFY `id_gi_type` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `normal`
--
ALTER TABLE `normal`
  MODIFY `id_normal` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT для таблицы `product_list`
--
ALTER TABLE `product_list`
  MODIFY `id_product` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT для таблицы `product_type`
--
ALTER TABLE `product_type`
  MODIFY `id_product_type` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT для таблицы `result`
--
ALTER TABLE `result`
  MODIFY `id_result` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=148;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `eating`
--
ALTER TABLE `eating`
  ADD CONSTRAINT `FK_eating_id_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Ограничения внешнего ключа таблицы `food_in_eating`
--
ALTER TABLE `food_in_eating`
  ADD CONSTRAINT `FK_food_in_eating_id_eating` FOREIGN KEY (`id_eating`) REFERENCES `eating` (`id_eating`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_food_in_eating_id_product` FOREIGN KEY (`id_product`) REFERENCES `product_list` (`id_product`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Ограничения внешнего ключа таблицы `product_list`
--
ALTER TABLE `product_list`
  ADD CONSTRAINT `FK_product_list_id_gi_type2` FOREIGN KEY (`id_gi_type`) REFERENCES `glycemic_index` (`id_gi_type`) ON DELETE NO ACTION,
  ADD CONSTRAINT `FK_product_list_id_group2` FOREIGN KEY (`id_group`) REFERENCES `product_type` (`id_product_type`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_product_list_id_picture` FOREIGN KEY (`id_picture`) REFERENCES `food_picture` (`id_picture`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `result`
--
ALTER TABLE `result`
  ADD CONSTRAINT `FK_result_id_user2` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Ограничения внешнего ключа таблицы `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `FK_user_id_normal2` FOREIGN KEY (`id_normal`) REFERENCES `normal` (`id_normal`) ON DELETE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
