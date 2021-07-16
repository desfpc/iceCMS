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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;


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
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of material_types
-- ----------------------------
INSERT INTO `material_types` VALUES (1, 'Корень', NULL, 'null', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `material_types` VALUES (2, 'Главная', 0, 'main', 1, 1, 1, NULL, 5, NULL, NULL, 0, NULL, NULL, 'Home page', 'Hauptseite');
INSERT INTO `material_types` VALUES (3, 'Технический раздел', 0, 'technical', 100, 0, NULL, NULL, NULL, 0, 0, 0, 0, 0, 'Technical section', 'Technischer Abschnitt');
INSERT INTO `material_types` VALUES (4, 'Документация', 3, 'documentation', 1, NULL, NULL, NULL, NULL, NULL, NULL, 20, NULL, NULL, 'Documentation', 'Dokumentation');
INSERT INTO `material_types` VALUES (5, 'Изображения', 3, 'images', 1, NULL, NULL, NULL, NULL, NULL, NULL, 10, NULL, NULL, 'Images', 'Bilder');
INSERT INTO `material_types` VALUES (6, 'Для разработки', 3, 'for-development', 100, NULL, NULL, NULL, 5, NULL, NULL, 10, 1, NULL, 'For development', 'Zu entwickeln');
INSERT INTO `material_types` VALUES (7, 'Каталог', 0, 'catalog', 2, 1, 6, 7, 5, 1, 1, 20, NULL, NULL, 'Catalog', 'Katalog');
INSERT INTO `material_types` VALUES (9, 'Корзина', 0, 'cart', 3, NULL, 11, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Cart', 'Korb');
INSERT INTO `material_types` VALUES (10, 'Настойки интернет магазина', 3, 'online-store-settings', 3, NULL, NULL, NULL, NULL, NULL, NULL, 100, NULL, NULL, 'Online store settings', 'Online-Shop-Einstellungen');

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
  `date_add` datetime(0) NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_edit` datetime(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of materials
-- ----------------------------
INSERT INTO `materials` VALUES (1, 'E-mail уведомлений', 'E-mail-uvedomlenij', 11, 1, 'store@ice.cms', NULL, NULL, '2021-06-02 19:34:06', '2021-06-02 19:34:06', '2021-06-02 19:34:06', '2021-06-02 19:34:06', 10, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `materials` VALUES (2, 'Telegram уведомлений', 'Telegram-uvedomlenij', 11, 1, '+79991112233', NULL, NULL, '2021-06-02 19:37:25', '2021-06-02 19:37:25', '2021-06-02 19:37:25', '2021-06-02 19:37:25', 10, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `materials` VALUES (3, 'Способы оплаты', 'Sposoby-oplaty', 11, 1, 'on_delivery:при получении;', NULL, NULL, '2021-06-02 19:39:13', '2021-06-02 19:39:13', '2021-06-02 19:39:13', '2021-06-02 19:39:13', 10, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `materials` VALUES (4, 'Способы доставки', 'Sposoby-dostavki', 11, 1, 'from_stock:самовывоз;', NULL, NULL, '2021-06-02 19:43:30', '2021-06-02 19:43:30', '2021-06-02 19:43:30', '2021-06-02 19:43:30', 10, 1, NULL, NULL, NULL, NULL, NULL, NULL);

-- ----------------------------
-- Table structure for modules
-- ----------------------------
DROP TABLE IF EXISTS `modules`;
CREATE TABLE `modules`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content` varchar(1025) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `secure` tinyint(1) NULL DEFAULT NULL,
  `special_rights` json NULL,
  `admin_module` int(11) NULL DEFAULT NULL,
  `subitem_query` varchar(512) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `parent_id` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `modules_name_idx`(`name`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 20 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

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
INSERT INTO `modules` VALUES (20, 'print_forms', 'Печатные формы', 1, NULL, NULL, NULL, NULL);

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
INSERT INTO `templates` VALUES (9, 'cart', 'Корзина', 2, 'Отображение товаров, добавленных в корзину; оформление заказа');

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
-- Table structure for user_files
-- ----------------------------
DROP TABLE IF EXISTS `user_files`;
CREATE TABLE `user_files`  (
  `file_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ordernum` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`file_id`, `user_id`) USING BTREE,
  INDEX `usermat_fil_idx`(`file_id`) USING BTREE,
  INDEX `usermat_user_idx`(`user_id`) USING BTREE,
  CONSTRAINT `user_files_ibfk_1` FOREIGN KEY (`file_id`) REFERENCES `files` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'admin@admin.local', '01', 'admin', 'Site administrator', NULL, 1, '$2y$10$/8iSwC.kqQfag6IXv0TCWuCCcnfuR4q3FitKw.JtQ8Z4OCZyWvl.a', '2021-01-28 08:30:02', NULL, NULL, 2, NULL);

-- ----------------------------
-- Table structure for store_requests
-- ----------------------------
DROP TABLE IF EXISTS `store_requests`;
CREATE TABLE `store_requests` (
  `id` INT(10) NOT NULL AUTO_INCREMENT,
  `user_id` INT(10) NOT NULL DEFAULT '0',
  `date_add` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `date_edit` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` ENUM('created','in_work','ready','completed','cancelled') NOT NULL DEFAULT 'created' CHARACTER SET utf8 COLLATE utf8_general_ci,
  `payment_method` ENUM('on_delivery') NOT NULL DEFAULT 'on_delivery' CHARACTER SET utf8 COLLATE utf8_general_ci,
  `delivery` ENUM('from_stock') NOT NULL DEFAULT 'from_stock' CHARACTER SET utf8 COLLATE utf8_general_ci,
  `price` DECIMAL(11,2) NULL DEFAULT NULL,
  `comment` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `store_requests_user_idx` (`user_id`) USING BTREE,
  INDEX `store_requests_status_idx` (`status`) USING BTREE,
  CONSTRAINT `store_requests_user_idx` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for store_request_goods
-- ----------------------------
DROP TABLE IF EXISTS `store_request_goods`;
CREATE TABLE `store_request_goods` (
  `request_id` INT(10) NOT NULL,
  `good_id` INT(10) NOT NULL,
  `price` DECIMAL(10,2) NOT NULL DEFAULT '0.00',
  `count` INT(10) NOT NULL,
  PRIMARY KEY (`request_id`, `good_id`) USING BTREE,
  INDEX `FK_store_request_goods_materials` (`good_id`) USING BTREE,
  CONSTRAINT `FK_store_request_goods_materials` FOREIGN KEY (`good_id`) REFERENCES `ice`.`materials` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `FK_store_request_goods_store_requests` FOREIGN KEY (`request_id`) REFERENCES `ice`.`store_requests` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
