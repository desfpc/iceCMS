/*
 Navicat Premium Data Transfer

 Source Server         : local_serv
 Source Server Type    : MySQL
 Source Server Version : 80018
 Source Host           : localhost:3306
 Source Schema         : ice

 Target Server Type    : MySQL
 Target Server Version : 80018
 File Encoding         : 65001

 Date: 28/01/2021 09:12:49
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for files
-- ----------------------------
DROP TABLE IF EXISTS `files`;
CREATE TABLE `files`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `filename` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `anons` varchar(2048) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `date_add` datetime(0) NOT NULL,
  `date_edit` datetime(0) NULL DEFAULT NULL,
  `user_id` int(11) NULL DEFAULT NULL,
  `status_id` tinyint(1) NULL DEFAULT NULL,
  `filetype` enum('image','file') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `extension` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `size` int(11) NULL DEFAULT NULL,
  `image_width` smallint(6) NULL DEFAULT NULL,
  `image_height` smallint(6) NULL DEFAULT NULL,
  `private` tinyint(1) NULL DEFAULT NULL,
  `date_event` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fil_user_idx`(`user_id`) USING BTREE,
  INDEX `fil_status_idx`(`status_id`) USING BTREE,
  INDEX `fil_type_idx`(`filetype`) USING BTREE,
  INDEX `fil_private_idx`(`private`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 35 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of files
-- ----------------------------
INSERT INTO `files` VALUES (21, 'thrustmaster-t300rs-gt1.jpg', 'thrustmaster-t300rs-gt1.jpg', '', '2020-05-27 06:14:11', '2020-05-27 06:14:11', 9, 2, 'image', 'jpg', '/files/202005/', 156492, 1100, 1000, 2, NULL);
INSERT INTO `files` VALUES (22, 'thrustmaster-t300rs-gt2.jpg', 'thrustmaster-t300rs-gt2.jpg', '', '2020-05-27 06:14:16', '2020-05-27 06:14:16', 9, 2, 'image', 'jpg', '/files/202005/', 265261, 1100, 1000, 2, NULL);
INSERT INTO `files` VALUES (23, 'thrustmaster-t300rs-gt3.jpg', 'thrustmaster-t300rs-gt3.jpg', '', '2020-05-27 06:14:21', '2020-05-27 06:14:21', 9, 2, 'image', 'jpg', '/files/202005/', 253507, 1100, 1000, 2, NULL);
INSERT INTO `files` VALUES (24, 'thrustmaster-t300rs-gt4.jpg', 'thrustmaster-t300rs-gt4.jpg', '', '2020-05-27 06:14:26', '2020-05-27 06:14:26', 9, 2, 'image', 'jpg', '/files/202005/', 139243, 1100, 1000, 2, NULL);
INSERT INTO `files` VALUES (25, 'thrustmaster-t300rs-gt5.jpg', 'thrustmaster-t300rs-gt5.jpg', '', '2020-05-27 06:14:31', '2020-05-27 06:14:31', 9, 2, 'image', 'jpg', '/files/202005/', 131027, 1100, 1000, 2, NULL);
INSERT INTO `files` VALUES (26, 'thrustmaster-t300rs-gt6.jpg', 'thrustmaster-t300rs-gt6.jpg', '', '2020-05-27 06:14:38', '2020-05-27 06:14:38', 9, 2, 'image', 'jpg', '/files/202005/', 235202, 1100, 782, 2, NULL);
INSERT INTO `files` VALUES (27, 'thrustmaster-t300rs-gt8.jpg', 'thrustmaster-t300rs-gt8.jpg', '', '2020-05-27 06:14:44', '2020-05-27 06:14:44', 9, 2, 'image', 'jpg', '/files/202005/', 247480, 1100, 1000, 2, NULL);
INSERT INTO `files` VALUES (28, 'T300RS-2.jpg', 'T300RS-2.jpg', '', '2020-05-27 06:20:59', '2020-05-27 06:20:59', 9, 2, 'image', 'jpg', '/files/202005/', 2375336, 2480, 2783, 2, NULL);
INSERT INTO `files` VALUES (29, 'tspcracer_f488challenge_1_(1).jpg', 'tspcracer_f488challenge_1_(1).jpg', '', '2020-05-27 06:29:32', '2020-05-27 06:29:32', 9, 2, 'image', 'jpg', '/files/202005/', 949916, 1795, 1613, 2, NULL);
INSERT INTO `files` VALUES (30, 'tspcracer_f488challenge_3.jpg', 'tspcracer_f488challenge_3.jpg', '', '2020-05-27 06:29:38', '2020-05-27 06:29:38', 9, 2, 'image', 'jpg', '/files/202005/', 1468274, 1772, 1726, 2, NULL);
INSERT INTO `files` VALUES (31, 'tspcracer_f488challenge_4.jpg', 'tspcracer_f488challenge_4.jpg', '', '2020-05-27 06:29:44', '2020-05-27 06:29:44', 9, 2, 'image', 'jpg', '/files/202005/', 1875120, 2362, 2082, 2, NULL);
INSERT INTO `files` VALUES (32, 'tspcracer_f488challenge_wheelfront.jpg', 'tspcracer_f488challenge_wheelfront.jpg', '', '2020-05-27 06:29:50', '2020-05-27 06:29:50', 9, 2, 'image', 'jpg', '/files/202005/', 1541746, 2362, 2022, 2, NULL);
INSERT INTO `files` VALUES (33, 'front_tspcracer_f488challenge.jpg', 'front_tspcracer_f488challenge.jpg', '', '2020-05-27 06:29:57', '2020-05-27 06:29:57', 9, 2, 'image', 'jpg', '/files/202005/', 2007864, 2362, 1439, 2, NULL);
INSERT INTO `files` VALUES (34, '317012_10150316893950946_189614985945_8529710_1767473142_n.jpg', '317012_10150316893950946_189614985945_8529710_1767473142_n.jpg', '', '2020-05-27 08:37:39', '2020-05-27 08:37:39', 9, 2, 'image', 'jpg', '/files/202005/', 110233, 960, 640, 2, NULL);

-- ----------------------------
-- Table structure for image_caches
-- ----------------------------
DROP TABLE IF EXISTS `image_caches`;
CREATE TABLE `image_caches`  (
  `width` smallint(5) NOT NULL,
  `height` smallint(5) NOT NULL,
  `watermark` int(11) NOT NULL,
  `w_x` int(11) NULL DEFAULT NULL,
  `w_y` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`width`, `height`, `watermark`) USING BTREE,
  INDEX `img_cache_watermark_fk`(`watermark`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of image_caches
-- ----------------------------
INSERT INTO `image_caches` VALUES (0, 800, 0, NULL, NULL);
INSERT INTO `image_caches` VALUES (48, 48, 0, NULL, NULL);
INSERT INTO `image_caches` VALUES (100, 100, 0, NULL, NULL);
INSERT INTO `image_caches` VALUES (150, 150, 0, NULL, NULL);
INSERT INTO `image_caches` VALUES (200, 200, 0, NULL, NULL);
INSERT INTO `image_caches` VALUES (800, 0, 0, NULL, NULL);

-- ----------------------------
-- Table structure for languages
-- ----------------------------
DROP TABLE IF EXISTS `languages`;
CREATE TABLE `languages`  (
  `id` int(11) NOT NULL,
  `id_char` varchar(4) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of languages
-- ----------------------------
INSERT INTO `languages` VALUES (1, 'ru', 'Русский');
INSERT INTO `languages` VALUES (2, 'en', 'English');
INSERT INTO `languages` VALUES (3, 'de', 'Deutsche');

-- ----------------------------
-- Table structure for material_extra_params
-- ----------------------------
DROP TABLE IF EXISTS `material_extra_params`;
CREATE TABLE `material_extra_params`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `mtype_id` int(11) NOT NULL,
  `value_type` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `value_mtype` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `mep_mtype_idx`(`mtype_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of material_extra_params
-- ----------------------------
INSERT INTO `material_extra_params` VALUES (1, 'Производитель', 12, 'value_mat', 17);
INSERT INTO `material_extra_params` VALUES (2, 'Производитель', 10, 'value_mat', 17);
INSERT INTO `material_extra_params` VALUES (3, 'Производитель', 11, 'value_mat', 17);
INSERT INTO `material_extra_params` VALUES (4, 'Производитель', 15, 'value_mat', 17);

-- ----------------------------
-- Table structure for material_extra_values
-- ----------------------------
DROP TABLE IF EXISTS `material_extra_values`;
CREATE TABLE `material_extra_values`  (
  `material_id` int(11) NOT NULL,
  `param_id` int(11) NOT NULL,
  `value_int` int(11) NULL DEFAULT NULL,
  `value_char` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `value_mat` int(11) NULL DEFAULT NULL,
  `value_text` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `value_flag` tinyint(1) NULL DEFAULT NULL,
  PRIMARY KEY (`material_id`, `param_id`) USING BTREE,
  INDEX `mev_par_fk`(`param_id`) USING BTREE,
  INDEX `mev_int_idx`(`value_int`) USING BTREE,
  INDEX `mev_mat_idx`(`value_mat`) USING BTREE,
  INDEX `mev_flag_idx`(`value_flag`) USING BTREE,
  CONSTRAINT `mev_par_fk` FOREIGN KEY (`param_id`) REFERENCES `material_extra_params` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of material_extra_values
-- ----------------------------
INSERT INTO `material_extra_values` VALUES (5, 1, NULL, NULL, 8, NULL, NULL);

-- ----------------------------
-- Table structure for material_files
-- ----------------------------
DROP TABLE IF EXISTS `material_files`;
CREATE TABLE `material_files`  (
  `file_id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL,
  `ordernum` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`file_id`, `material_id`) USING BTREE,
  INDEX `filmat_fil_idx`(`file_id`) USING BTREE,
  INDEX `filmat_mat_idx`(`material_id`) USING BTREE,
  CONSTRAINT `filmat_fil_fk` FOREIGN KEY (`file_id`) REFERENCES `files` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of material_files
-- ----------------------------
INSERT INTO `material_files` VALUES (21, 4, NULL);
INSERT INTO `material_files` VALUES (22, 4, NULL);
INSERT INTO `material_files` VALUES (23, 4, NULL);
INSERT INTO `material_files` VALUES (24, 4, NULL);
INSERT INTO `material_files` VALUES (25, 4, NULL);
INSERT INTO `material_files` VALUES (26, 4, NULL);
INSERT INTO `material_files` VALUES (27, 4, NULL);
INSERT INTO `material_files` VALUES (28, 4, NULL);
INSERT INTO `material_files` VALUES (29, 5, NULL);
INSERT INTO `material_files` VALUES (30, 5, NULL);
INSERT INTO `material_files` VALUES (31, 5, NULL);
INSERT INTO `material_files` VALUES (32, 5, NULL);
INSERT INTO `material_files` VALUES (33, 5, NULL);
INSERT INTO `material_files` VALUES (34, 6, NULL);

-- ----------------------------
-- Table structure for material_materials
-- ----------------------------
DROP TABLE IF EXISTS `material_materials`;
CREATE TABLE `material_materials`  (
  `parent_id` int(11) NOT NULL,
  `child_id` int(11) NOT NULL,
  `count` int(11) NULL DEFAULT NULL,
  `price` decimal(10, 2) NULL DEFAULT NULL,
  `ordernum` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`parent_id`, `child_id`) USING BTREE,
  INDEX `matmat_parent_idx`(`parent_id`) USING BTREE,
  INDEX `matmat_child_idx`(`child_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for material_types
-- ----------------------------
DROP TABLE IF EXISTS `material_types`;
CREATE TABLE `material_types`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(512) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `parent_id` int(11) NULL DEFAULT NULL,
  `id_char` varchar(512) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `ordernum` int(11) NULL DEFAULT NULL,
  `sitemenu` smallint(6) NULL DEFAULT NULL,
  `template_list` int(11) NULL DEFAULT NULL,
  `template_item` int(11) NULL DEFAULT NULL,
  `template_admin` int(11) NULL DEFAULT NULL,
  `prepare_list` smallint(6) NULL DEFAULT NULL,
  `prepare_item` smallint(6) NULL DEFAULT NULL,
  `list_items` int(11) NULL DEFAULT NULL,
  `shop_ifgood` smallint(6) NULL DEFAULT NULL,
  `shop_ifstore` smallint(6) NULL DEFAULT NULL,
  `name_en` varchar(512) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `name_de` varchar(512) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `mt_idchar_idx`(`id_char`(255)) USING BTREE,
  INDEX `mt_parent_fk`(`parent_id`) USING BTREE,
  INDEX `mt_sitemenu_idx`(`sitemenu`) USING BTREE,
  INDEX `mt_temp_item_fk`(`template_item`) USING BTREE,
  INDEX `mt_temp_list_fk`(`template_list`) USING BTREE,
  INDEX `mt_temp_adm_fk`(`template_admin`) USING BTREE,
  CONSTRAINT `mt_temp_admin_fk` FOREIGN KEY (`template_admin`) REFERENCES `templates` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `mt_temp_item_fk` FOREIGN KEY (`template_item`) REFERENCES `templates` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `mt_temp_list_fk` FOREIGN KEY (`template_list`) REFERENCES `templates` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 20 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of material_types
-- ----------------------------
INSERT INTO `material_types` VALUES (1, 'Корень', NULL, 'null', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `material_types` VALUES (2, 'Главная', 0, 'main', 1, 1, 1, NULL, 5, NULL, NULL, 0, NULL, NULL, 'Home page', 'Hauptseite');
INSERT INTO `material_types` VALUES (3, 'Технический раздел', 0, 'technical', 100, 0, NULL, NULL, NULL, 0, 0, 0, 0, 0, 'Technical section', 'Technischer Abschnitt');
INSERT INTO `material_types` VALUES (4, 'Документация', 3, 'documentation', 1, NULL, NULL, NULL, NULL, NULL, NULL, 20, NULL, NULL, 'Documentation', 'Dokumentation');
INSERT INTO `material_types` VALUES (5, 'Изображения', 3, 'images', 1, NULL, NULL, NULL, NULL, NULL, NULL, 10, NULL, NULL, 'Images', 'Bilder');
INSERT INTO `material_types` VALUES (6, 'Для разработки', 3, 'for-development', 100, NULL, NULL, NULL, 5, NULL, NULL, 10, 1, NULL, 'For development', 'Zu entwickeln');
INSERT INTO `material_types` VALUES (9, 'Каталог', 0, 'catalog', 2, 1, 6, 7, 5, 1, 1, 20, NULL, NULL, 'Catalog', 'Katalog');
INSERT INTO `material_types` VALUES (10, 'Стойки для игрового руля', 9, 'steering-racks', 1, 1, 6, 7, 5, 1, 1, 10, NULL, NULL, 'Steering racks', 'Lenkgetriebe');
INSERT INTO `material_types` VALUES (11, 'Кресла для автосимуляторов', 9, 'chairs-for-car-simulators', 2, 1, 6, 7, 5, NULL, NULL, 10, NULL, NULL, 'Chairs for car simulators', 'Stühle für Autosimulatoren');
INSERT INTO `material_types` VALUES (12, 'Рули для автосимуляторов', 9, 'steering-wheels-for-car-simulators', NULL, 1, 6, 7, 5, 1, 1, 15, NULL, NULL, 'Steering wheels for car simulators', 'Lenkräder für Autosimulatoren');
INSERT INTO `material_types` VALUES (13, 'Оплата и доставка', 0, 'payment-and-delivery', 3, 1, 8, NULL, NULL, NULL, NULL, 10, NULL, NULL, 'Payment and delivery', 'Zahlungs-und Lieferbedingungen');
INSERT INTO `material_types` VALUES (14, 'Контакты', 0, 'contacts', 4, 1, 8, NULL, NULL, NULL, NULL, 10, NULL, NULL, 'Contacts', 'Kontakte');
INSERT INTO `material_types` VALUES (15, 'Кресла для авиасимуляторов', 9, 'chairs-for-flight-simulators', 4, 1, 6, 7, 5, NULL, NULL, 10, NULL, NULL, 'Chairs for flight simulators', 'Stühle für Flugsimulatoren');
INSERT INTO `material_types` VALUES (16, 'Новости', 0, 'news', 5, 1, 2, 3, 4, 1, 1, 10, NULL, NULL, 'News', 'Nachrichten');
INSERT INTO `material_types` VALUES (17, 'Производители', 3, 'manufacturers', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 10, NULL, NULL, 'Manufacturers', 'Hersteller');

-- ----------------------------
-- Table structure for materials
-- ----------------------------
DROP TABLE IF EXISTS `materials`;
CREATE TABLE `materials`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(1024) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `id_char` varchar(1024) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `material_type_id` int(11) NOT NULL,
  `language` int(11) NOT NULL,
  `anons` varchar(2048) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `parent_id` int(11) NULL DEFAULT NULL,
  `date_add` datetime(0) NOT NULL,
  `date_edit` datetime(0) NULL DEFAULT NULL,
  `date_event` datetime(0) NULL DEFAULT NULL,
  `date_end` datetime(0) NULL DEFAULT NULL,
  `user_id` int(11) NULL DEFAULT NULL,
  `status_id` tinyint(1) NOT NULL,
  `material_count` int(11) NULL DEFAULT NULL,
  `price` decimal(10, 2) NULL DEFAULT NULL,
  `goodcode` varchar(23) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `important` tinyint(1) NULL DEFAULT NULL,
  `ordernum` int(11) NULL DEFAULT NULL,
  `tags` varchar(1024) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `mat_goodcode_uq`(`goodcode`) USING BTREE,
  INDEX `mat_charid_idx`(`id_char`(255)) USING BTREE,
  INDEX `mat_mattype_fk`(`material_type_id`) USING BTREE,
  INDEX `mat_lang_fk`(`language`) USING BTREE,
  INDEX `mat_status_idx`(`status_id`) USING BTREE,
  INDEX `mat_important_idx`(`important`) USING BTREE,
  INDEX `mat_parent_fk`(`parent_id`) USING BTREE,
  CONSTRAINT `mat_lang_fk` FOREIGN KEY (`language`) REFERENCES `languages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mat_type_fk` FOREIGN KEY (`material_type_id`) REFERENCES `material_types` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of materials
-- ----------------------------
INSERT INTO `materials` VALUES (4, 'Thrustmaster T300RS GT', 'Thrustmaster-T300RS-GT', 12, 1, 'Съемный гоночный руль в стиле GT', '&lt;h2&gt;СЪЕМНЫЙ ГОНОЧНЫЙ РУЛЬ В СТИЛЕ GT&lt;/h2&gt;\r\n&lt;p&gt;Съемный реалистичный руль диаметром 28 см с усиленным текстурированным прорезиненным покрытием по всей поверхности. Официальный логотип GT в центре перемычки&lt;br /&gt;Разнообразные гоночные регуляторы: 13 функциональных кнопок (в том числе 2 на базе ) + 1 многопозиционная кнопка.&lt;br /&gt;Официальные кнопки PlayStation&amp;reg;4 (PS, SHARE, OPTIONS) &amp;mdash; доступ к новым функциям общения моментальным переключением между игрой и системой в любой момент времени.&lt;/p&gt;\r\n&lt;p&gt;&amp;nbsp;&lt;/p&gt;\r\n&lt;h3&gt;2 больших секвенционных переключателя-лепестка на руле&lt;/h3&gt;\r\n&lt;p&gt;Секвенционное переключение передач обеспечивается двумя большими (величиной 13 см) полностью металлическими лепестковыми переключателями, закрепленными на руле, и выококлассной тактовой кнопкой (жизненный цикл более 10 миллионов включений).&lt;/p&gt;\r\n&lt;p&gt;&amp;nbsp;&lt;/p&gt;\r\n&lt;h3&gt;T3PA &amp;mdash; ПЕДАЛЬНЫЙ БЛОК GT EDITION&lt;/h3&gt;\r\n&lt;p&gt;Для еще большего повышения реалистичности игры, этот специальный выпуск комплекта GT EDITION предлагает педальный блок T3PA в стиле GT с 3 педалями и полностью металлической конструкцией. Регулируемая высота и расстояние между педалями газа и сцепления. Педаль тормоза поставляется вместе с модулем Conical Rubber Brake Mod и обеспечивает прогрессивное сопротивление.&lt;/p&gt;\r\n&lt;p&gt;&amp;nbsp;&lt;/p&gt;\r\n&lt;h3&gt;Бесщеточный сервомотор промышленного класса&lt;/h3&gt;\r\n&lt;p&gt;Под кожухом этой системы &amp;mdash; фирменный бесщеточный сервомотор промышленного класса с силовой обратной связью (без эффекта трения), который обеспечивает суперплавный ход и эффективную обратную связь. Тихая работа мотора позволяет геймерам полностью сосредоточиться на главном &amp;mdash; превосходных гоночных показателях.&lt;br /&gt;Суперчувствительные и реалистичные силовые эффекты без задержки.&lt;/p&gt;\r\n&lt;p&gt;&amp;nbsp;&lt;/p&gt;\r\n&lt;h3&gt;Новая двухременная система&lt;/h3&gt;\r\n&lt;p&gt;Новая двухременная система с углом 1080&amp;deg; обеспечивает суперплавный ход и тихую обратную связь исключительного отклика, а также реалистичные силовые эффекты, так что геймер чувствует дорогу, а не механизм рулевой системы.&lt;/p&gt;\r\n&lt;p&gt;&amp;nbsp;&lt;/p&gt;\r\n&lt;h3&gt;H.E.A.R.T. HallEffect AccuRate Technology&amp;reg;&lt;/h3&gt;\r\n&lt;p&gt;Разработчики из Thrustmaster дополнили внушительные характеристики мотора повышенной точностью работы благодаря внедрению технологии H.E.A.R.T HallEffect AccuRate Technology&amp;reg; с бесконтактным магнитным датчиком, обеспечивающим 16-битное разрешение и 65 536 значений на поворот.&lt;/p&gt;\r\n&lt;p&gt;&amp;nbsp;&lt;/p&gt;\r\n&lt;h3&gt;СОВМЕСТИМОСТЬ С ДРУГИМИ ПРОДУКТАМИ СЕРИИ&lt;/h3&gt;\r\n&lt;p&gt;Совместимость на системах&amp;nbsp; PS4&amp;trade;, PS3&amp;trade; и ПК со съемными рулями Thrustmaster** (599XX EVO 30 Wheel Add-On Alcantara Edition, TM Leather 28 GT Wheel Add-On, Ferrari F1 Wheel Add-On, Ferrari GTE Wheel Add-On и пр.)&lt;br /&gt;Совместимость на системах PS4&amp;trade;, PS3&amp;trade; и ПК с рулями Thrustmaster и 3-педальным блоком T3PA-PRO**.&amp;nbsp;&lt;br /&gt;Совместимость на системах PS4&amp;trade;, PS3&amp;trade; и ПК с коробкой передач TH8A** (TH8A &amp;mdash; Thrustmaster TH8 Add-on)&lt;br /&gt;** Приобретается отдельно.&lt;/p&gt;\r\n&lt;p&gt;&amp;nbsp;&lt;/p&gt;\r\n&lt;h3&gt;Система быстрого крепления Thrustmaster Quick Release&lt;/h3&gt;\r\n&lt;p&gt;Система быстрого крепления Thrustmaster Quick Release позволяет без проблем менять один руль на другой за считанные секунды. Эта инновационная концепция позволяет владельцам базы отдельно приобретать съемные рули Thrustmaster и использовать их с имеющейся базой. Оптимальная реалистичность на всех типах гонок!&lt;/p&gt;', NULL, '2020-05-27 06:11:20', '2020-05-27 06:25:26', '2020-05-27 06:11:20', '2020-05-27 06:11:20', 9, 1, NULL, 43990.00, NULL, NULL, NULL, NULL);
INSERT INTO `materials` VALUES (5, 'Thrustmaster TS-PC RACER FERRARI 488 Challenge ,PC', 'Thrustmaster-TS-PC-RACER-FERRARI-488-Challenge-PC', 12, 1, 'Рулевая система TS-PC RACER Ferrari 488 Challenge Edition', '&lt;h2&gt;Рулевая система TS-PC RACER Ferrari 488 Challenge Edition&lt;/h2&gt;\r\n&lt;p&gt;В честь 70-й годовщины самого знаменитого бренда спортивных автомобилей в мире, Ferrari, компания Thrustmaster с гордостью представляет рулевую систему TS-PC RACER Ferrari 488 Challenge Edition. Наслаждайтесь невероятно реалистичными гоночными ощущениями благодаря системе, в которой передовые технологии Thrustmaster сервобазы TS-PC RACER, призванные повысить игровые показатели, сочетаются с репликой руля подлинного автомобиля Ferrari 488 Challenge (с официальной лицензией Ferrari).&lt;/p&gt;\r\n&lt;p&gt;Содержимое упаковки: гоночный руль, блок питания Turbo Power, система крепления, руководство пользователя и сведения о потребительской гарантии&lt;/p&gt;\r\n&lt;p&gt;&amp;nbsp;&lt;/p&gt;\r\n&lt;h3&gt;Кулак мощных технологий&lt;/h3&gt;\r\n&lt;p&gt;TS-PC RACER &amp;mdash; превосходный гоночный симулятор Thrustmaster для ПК. Он правдоподобно воспроизводит отклик автомобиля и условия трассы.&lt;/p&gt;\r\n&lt;p&gt;Система TS-PC RACER объединяет в себе целый спектр мощных технологий, призванных повысить игровые показатели и приблизить игровые впечатления к реальным для обеспечения полного погружения в игру.&lt;/p&gt;\r\n&lt;h3&gt;&lt;br /&gt;Сервобаза Thrustmaster TS-PC&lt;/h3&gt;\r\n&lt;p&gt;Металлическая верхняя панель.&lt;br /&gt;Угол поворота 270&amp;deg;&amp;mdash;1080&amp;deg;.&lt;br /&gt;Бесщеточный мотор промышленного класса 2-го поколения.&lt;br /&gt;Мотор системы TS-PC RACER предлагает бесщеточную силовую обратную связь 40 Вт.&lt;br /&gt;Встроенная система охлаждения Motor Cooling Embedded&lt;br /&gt;Алгоритм Field Oriented Control&lt;br /&gt;Внешний блок питания Turbo Power&lt;/p&gt;\r\n&lt;p&gt;&amp;nbsp;&lt;/p&gt;\r\n&lt;h3&gt;Реалистичный дизайн руля для соревнований&lt;/h3&gt;\r\n&lt;p&gt;Руль представляет собой реплику 9:10 (32 см в диаметре) руля подлинного автомобиля Ferrari 488 Challenge. Он имеет официальную лицензию Ferrari и предлагает в общей сложности 25 программируемых функций.&lt;br /&gt;Центральная пластина и 2 лепестковых секвенционных переключателя из черного анодированного металла с шероховатой поверхностью.&lt;br /&gt;15 встроенных* индикаторов для функции тахометра (об./мин.).&lt;br /&gt;2 многопозиционных переключателя (с функцией нажимной кнопки).&lt;br /&gt;2 поворотных переключателя с нажимными кнопками и 8 функциональных кнопок.&lt;br /&gt;Дизайн с простроченным вручную покрытием из того же итальянского материала Alcantara, что и покрытие на оригинальных рулях Ferrari.&lt;/p&gt;\r\n&lt;p&gt;&amp;nbsp;&lt;/p&gt;\r\n&lt;h3&gt;Бесщеточная технология сервомотора (40 Вт)&lt;/h3&gt;\r\n&lt;p&gt;Компания Thrustmaster разработала сервобазу TS-PC, чтобы предложить взыскательным ПК-геймерам высокий уровень производительности, точности и комфорта.&lt;br /&gt;Бесщеточный мотор промышленного класса 2-го поколения обеспечивает 50% повышение динамичности отклика и 4-кратное увеличение момента при заторможенном моторе по сравнению с предыдущим поколением моторов. Крутящий момент в 1,6 раза выше по сравнению с показателями баз рулевых систем TX и T300 RS.&lt;/p&gt;\r\n&lt;p&gt;&amp;nbsp;&lt;/p&gt;\r\n&lt;h3&gt;Динамический крутящий момент&lt;/h3&gt;\r\n&lt;p&gt;40-Вт мотор с высоким уровнем отклика обеспечивает мощные динамические эффекты и оптимизированную обратную связь. Мотор системы TS-PC RACER обеспечивает мощную бесщеточную силовую обратную связь 40 Вт и потрясающий вектор скорости (динамический крутящий момент) как на длинных виражах при заторможенном двигателе (режим STALL), так и в суперточных зигзагах (режим DYNAMIC).&lt;/p&gt;\r\n&lt;p&gt;&amp;nbsp;&lt;/p&gt;\r\n&lt;h3&gt;Встроенная система охлаждения мотора Motor Cooling Embedded (подана заявка на патент)&lt;/h3&gt;\r\n&lt;p&gt;Обеспечивает динамику нового мотора, защищая его от перегрева, и не создает лишнего шума. Дает 50% повышение динамичности отклика и 4-кратное увеличение момента при заторможенном моторе по сравнению с базами систем TX и T300 RS плюс теплоотвод путем монофазного охлаждения.&lt;/p&gt;\r\n&lt;p&gt;&amp;nbsp;&lt;/p&gt;\r\n&lt;h3&gt;H.E.A.R.T (HallEffect AccuRate Technology) + F.O.C.&lt;/h3&gt;\r\n&lt;p&gt;Алгоритм Field Oriented Control: технология H.E.A.R.T (HallEffect AccuRate Technology) предлагает 16-битное разрешение (65 536 значений), а новый алгоритм F.O.C. динамически оптимизирует уровень отклика при высоких требованиях к крутящему моменту. В ответ на повышение требований к крутящему моменту мотор реагирует динамически, компенсируя потери мощности.&lt;/p&gt;\r\n&lt;p&gt;&amp;nbsp;&lt;/p&gt;\r\n&lt;h3&gt;Внешний блок питания Turbo Power&lt;/h3&gt;\r\n&lt;p&gt;Внешний блок питания Turbo Power обеспечивает постоянное питание и высокую пиковую мощность, что гарантирует моментальный отклик на самые быстрые команды от игры. Тороидальная конструкция дает, благодаря отсутствию ребер, оптимизированную энергоэффективность. Пиковая мощность: 400 Вт!&lt;/p&gt;\r\n&lt;p&gt;&amp;nbsp;&lt;/p&gt;\r\n&lt;h3&gt;Полная совместимость с экосистемой Thrustmaster&lt;/h3&gt;\r\n&lt;p&gt;Совместимость на ПК с дополнительными гоночными рулями Thrustmaster* (Ferrari F1 Wheel Add-On, Ferrari GTE Wheel Add-On, 599XX EVO 30 Wheel Add-On Alcantara Edition, TM Leather 28 GT Wheel Add-On, TM Rally Wheel Add-On Sparco 383 Mod) и экосистемой гоночных продуктов Thrustmaster: педальными блоками с 3 педалями Thrustmaster T3PA* и T3PA-PRO* и переключателем передач TH8A*.&lt;br /&gt;* Приобретается отдельно&lt;/p&gt;', NULL, '2020-05-27 06:28:34', '2020-06-02 00:00:22', '2020-05-27 06:28:34', '2020-05-27 06:28:34', 9, 1, NULL, 65990.00, NULL, NULL, NULL, NULL);
INSERT INTO `materials` VALUES (6, 'Открытие сайта', 'Otkrytie-sajta', 16, 1, 'Сегодня запустили сайт simracingseat.ru!', '&lt;p&gt;Мы предоставляем любителям гоночных симуляторов только высококачественные продукты для улучшения водительского мастерства и удобства вождения виртуальных болидов. Наши продукты &amp;nbsp;созданы из лучших материалов и их можно использовать как дома, так и в любых коммерческих помещениях. Красивый дизайн отлично будет смотрится в любом интерьере, а&amp;nbsp;продуманная до мелочей эргономика позволит управлять виртуальным болидом без усталости.&lt;br /&gt;&lt;br /&gt;Также наши игровые кресла идеально подходят для проведения турниров по автосимуляторам, а также для различных презентаций и выставок.&lt;br /&gt;&lt;br /&gt;Наши продукты используют не только дома, но и на разнообразных мероприятиях, связанных с игровым миром автосимуляторов.&amp;nbsp;&lt;br /&gt;&lt;br /&gt;Мы подходим индивидуально к каждому клиенту и поможем сделать правильный выбор не только в наших продуктах, а также в продукции сторонних производителей. Мы посоветуем только лучшее, так как мы лучшие специалисты своего дела.&lt;br /&gt;&amp;nbsp;&amp;nbsp;&lt;br /&gt;Мы предлагаем только лучшие подставки, игровые кресла и рули для автосимуляторов. Никаких компромиссов.&lt;/p&gt;', NULL, '2020-05-27 08:34:31', '2020-05-27 08:37:48', '2020-05-27 08:34:31', '2020-05-27 08:34:31', 9, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `materials` VALUES (7, 'Производитель 1', 'Proizvoditel-1', 17, 1, NULL, NULL, NULL, '2020-06-01 22:53:18', '2020-06-01 22:53:23', '2020-06-01 22:53:18', '2020-06-01 22:53:18', 9, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `materials` VALUES (8, 'Производитель 2', 'Proizvoditel-2', 17, 1, NULL, NULL, NULL, '2020-06-01 22:53:41', '2020-06-01 22:53:47', '2020-06-01 22:53:41', '2020-06-01 22:53:41', 9, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `materials` VALUES (9, 'Производитель 3', 'Proizvoditel-3', 17, 1, NULL, NULL, NULL, '2020-06-01 22:53:58', '2020-06-02 09:34:21', '2020-06-01 22:53:58', '2020-06-01 22:53:58', 9, 1, NULL, NULL, NULL, NULL, NULL, NULL);

-- ----------------------------
-- Table structure for modules
-- ----------------------------
DROP TABLE IF EXISTS `modules`;
CREATE TABLE `modules`  (
  `id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content` varchar(1025) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `secure` tinyint(1) NULL DEFAULT NULL,
  `special_rights` json NULL,
  `admin_module` int(11) NULL DEFAULT NULL,
  `subitem_query` varchar(512) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `parent_id` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `modules_name_idx`(`name`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of modules
-- ----------------------------
INSERT INTO `modules` VALUES (1, 'material_types_admin', 'Материалы - структура', 1, NULL, NULL, NULL, 3);
INSERT INTO `modules` VALUES (2, 'materials', 'Материалы', NULL, NULL, 3, NULL, NULL);
INSERT INTO `modules` VALUES (3, 'materials_admin', 'Материалы', 1, NULL, NULL, NULL, NULL);
INSERT INTO `modules` VALUES (4, 'users_admin', 'Пользователи', 1, NULL, NULL, NULL, NULL);
INSERT INTO `modules` VALUES (5, 'files_admin', 'Файлы', 1, NULL, NULL, NULL, NULL);
INSERT INTO `modules` VALUES (6, 'file_get', 'Файлы - выдача на скачку', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `modules` VALUES (7, 'image_caches_admin', 'Кэши изображений', 1, NULL, NULL, NULL, 5);
INSERT INTO `modules` VALUES (8, 'authorize', 'Авторизация пользователя', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `modules` VALUES (9, '404', 'Ошибка 404', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `modules` VALUES (10, '500', 'Ошибка 500', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `modules` VALUES (11, 'registration', 'Регистрация пользователя', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `modules` VALUES (12, 'exit', 'Выход', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `modules` VALUES (13, 'iceFW', 'Информация о фреймворке', 1, NULL, NULL, NULL, NULL);
INSERT INTO `modules` VALUES (14, 'shop', 'Интернет магазин', 1, NULL, NULL, NULL, NULL);
INSERT INTO `modules` VALUES (15, 'shop_report_sales', 'Отчет по продажам', 1, NULL, NULL, NULL, 14);
INSERT INTO `modules` VALUES (16, 'shop_report_customer', 'Отчет по клиентам', 1, NULL, NULL, NULL, 14);
INSERT INTO `modules` VALUES (17, 'shop_settings', 'Настройки магазина', 1, NULL, NULL, NULL, 14);
INSERT INTO `modules` VALUES (18, 'ajax', 'Вывод данных для ajax запросов', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `modules` VALUES (19, 'templates', 'Шаблоны материалов', 1, NULL, NULL, NULL, 3);

-- ----------------------------
-- Table structure for mtype_files
-- ----------------------------
DROP TABLE IF EXISTS `mtype_files`;
CREATE TABLE `mtype_files`  (
  `mtype_id` int(11) NOT NULL,
  `file_id` int(11) NOT NULL,
  `ordernum` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`mtype_id`, `file_id`) USING BTREE,
  INDEX `mtfil_mt_idx`(`mtype_id`) USING BTREE,
  INDEX `mtfil_fil_idx`(`file_id`) USING BTREE,
  CONSTRAINT `mtfil_file_fk` FOREIGN KEY (`file_id`) REFERENCES `files` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mtfil_type_fk` FOREIGN KEY (`mtype_id`) REFERENCES `material_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for templates
-- ----------------------------
DROP TABLE IF EXISTS `templates`;
CREATE TABLE `templates`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(127) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `type` int(11) NOT NULL,
  `content` varchar(1024) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of templates
-- ----------------------------
INSERT INTO `templates` VALUES (1, 'main', 'Главная страница', 2, 'Главная страница сайта');
INSERT INTO `templates` VALUES (2, 'news', 'Новости', 2, 'Новостная лента');
INSERT INTO `templates` VALUES (3, 'news_item', 'Новости детализация', 1, 'Детализация новости');
INSERT INTO `templates` VALUES (4, 'news_admin', 'Новости редактирование', 3, 'Новости - форма редактирования');
INSERT INTO `templates` VALUES (5, 'all_admin', 'Полный', 3, 'Шаблон со всеми возможными полями (для разработки)');
INSERT INTO `templates` VALUES (6, 'catalog', 'Каталог список', 2, 'Список товаров, разделы каталога');
INSERT INTO `templates` VALUES (7, 'catalog_item', 'Каталог - товар', 1, 'Детализация товара');
INSERT INTO `templates` VALUES (8, 'text', 'Текстовый раздел', 2, 'В списке показывается детализация последнего активного материала');

-- ----------------------------
-- Table structure for translates
-- ----------------------------
DROP TABLE IF EXISTS `translates`;
CREATE TABLE `translates`  (
  `language_id` int(11) NOT NULL,
  `rus_str` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `lang_str` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`language_id`, `rus_str`) USING BTREE,
  INDEX `trs_str_idx`(`rus_str`) USING BTREE,
  INDEX `trs_lang_idx`(`language_id`) USING BTREE,
  CONSTRAINT `trs_lang_fk` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for user_roles
-- ----------------------------
DROP TABLE IF EXISTS `user_roles`;
CREATE TABLE `user_roles`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `secure` tinyint(1) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of user_roles
-- ----------------------------
INSERT INTO `user_roles` VALUES (1, 'пользователь', NULL);
INSERT INTO `user_roles` VALUES (2, 'администратор', 1);
INSERT INTO `user_roles` VALUES (3, 'модератор', 2);

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login_email` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `login_phone` varchar(11) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `nik_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `full_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `passcode` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `status_id` int(11) NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `date_add` datetime(0) NULL DEFAULT NULL,
  `contacts` json NULL,
  `user_state` int(11) NULL DEFAULT NULL,
  `user_role` int(255) NULL DEFAULT NULL,
  `sex` varchar(1) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `us_email_idx`(`login_email`) USING BTREE,
  UNIQUE INDEX `us_phote_idx`(`login_phone`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (10, 'admin@admin.local', '01', 'admin', 'Site administrator', NULL, 1, '$2y$10$/8iSwC.kqQfag6IXv0TCWuCCcnfuR4q3FitKw.JtQ8Z4OCZyWvl.a', '2021-01-28 08:30:02', NULL, NULL, 2, NULL);

SET FOREIGN_KEY_CHECKS = 1;
