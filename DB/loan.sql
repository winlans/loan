DROP TABLE IF EXISTS `products`;CREATE TABLE `products` (  `id` int(11) NOT NULL AUTO_INCREMENT,  `name` varchar(255) NOT NULL COMMENT '产品名称',  `logo` varchar(255) NULL COMMENT 'logo path',  `min_loans` int(11) NOT NULL DEFAULT 0 COMMENT '最低贷款', `max_loans` int(11) NOT NULL DEFAULT 0 COMMENT '最多贷款',  `rate` varchar(255) NOT NULL DEFAULT 0 COMMENT '利率',  `fee` int(11) NOT NULL DEFAULT 0 COMMENT '服务费',  `loan_time_min` int(11) NOT NULL DEFAULT 0 COMMENT '最快放款时间 分钟',  `loan_time_max` int(11) NOT NULL DEFAULT 0 COMMENT '最迟放款时间 分钟',  `loan_period_min` int(11) NOT NULL DEFAULT 0 COMMENT '放款期限最低 天',  `loan_period_max` int(11) NOT NULL DEFAULT 0 COMMENT '放款期限最高 天',  `applications` bigint NOT NULL DEFAULT 0 COMMENT '申请人数',  `sale` tinyint(4) DEFAULT 0 COMMENT '是否上架 0 否 1上架',  `is_new` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1 new  0 old',  `apply_path` varchar(255) NOT NULL DEFAULT '' COMMENT '申请地址 url',  `amount_limit_show` varchar(255) NULL COMMENT '展示贷款额度',  `loan_time_show` INT NOT NULL DEFAULT 0 COMMENT '展示最快放款时间',  `rate_show` INT NOT NULL DEFAULT 0 COMMENT '展示利息',  `apply_cond` VARCHAR(128) NOT NULL DEFAULT '' COMMENT '申请条件',  `auerbach` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '认证材料',  `descr` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '产品描述',  `not_show` VARCHAR(128) NULL COMMENT '不展示的应用商店',  `sort` INT NOT NULL DEFAULT 0 COMMENT '排序',  `status` TINYINT(4) NOT NULL DEFAULT 1 ,  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),  `updated` TIMESTAMP DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP,  PRIMARY KEY (`id`));DROP TABLE IF EXISTS `users`;CREATE TABLE `users` (  `id` INT(11) AUTO_INCREMENT,  `nickname` VARCHAR(32) NOT NULL COMMENT '用户昵称',  `mobile` CHAR(14) NOT NULL COMMENT '手机号',  `password` VARCHAR(255) NOT NULL COMMENT '密码',  `channel` VARCHAR(32) NOT NULL DEFAULT '' COMMENT '注册渠道',  `package_name` VARCHAR(32) NOT NULL DEFAULT '' COMMENT '包名',  `log_ip` varchar(23) DEFAULT NULL COMMENT '用户ip',  `status` TINYINT(4) NOT NULL DEFAULT 1 ,  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),  `updated` TIMESTAMP DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP,  PRIMARY KEY (`id`));INSERT INTO `users`(`nickname`, `mobile`, `password`) VALUES ('andy', '13512351235', '$2y$13$cdd81f89d6d9a3c8a81c5OWs5oCGHankhZPlZIQE8g3QTytjVka2C');INSERT INTO `users`(`nickname`, `mobile`, `password`) VALUES ('sunlight', '13512351236', '$2y$13$cdd81f89d6d9a3c8a81c5OWs5oCGHankhZPlZIQE8g3QTytjVka2C');DROP TABLE IF EXISTS `access_record`;CREATE TABLE `access_record`(  `id` BIGINT AUTO_INCREMENT,  `product_id` INT NOT NULL COMMENT '产品id',  `product_name` VARCHAR(64) NULL COMMENT '产品名称',  `user_id` INT NOT NULL COMMENT 'user_Id',  `d_count` INT NOT NULL DEFAULT 0 COMMENT '详情页访问次数',  `a_count` INT NOT NULL DEFAULT 0 COMMENT '申请页访问次数',  `created` INT NOT NULL COMMENT '时间戳',  PRIMARY KEY (`id`));DROP TABLE IF EXISTS `advertising`;CREATE TABLE `advertising`(  `id` BIGINT AUTO_INCREMENT,  `title` VARCHAR(64) NOT NULL COMMENT '标题',  `start` TIMESTAMP NULL COMMENT '开始时间',  `end` TIMESTAMP NULL COMMENT '结束时间',  `is_show` TINYINT(4) NOT NULL DEFAULT 1 COMMENT '是否展示',  `sort` INT NOT NULL DEFAULT 0 COMMENT '排序',  `logo_path` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '图片',  `skip_path` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '跳转路径',  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0-无效 1-有效',  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),  `updated` TIMESTAMP DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP,  PRIMARY KEY (`id`));DROP TABLE IF EXISTS `admin`;CREATE TABLE `admin`(  `id` INT AUTO_INCREMENT,  `name` VARCHAR(32) NOT NULL COMMENT '管理员名称',  `mobile` CHAR(14) NOT NULL COMMENT '手机号',  `password` VARCHAR(64) NOT NULL COMMENT '密码',  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0-无效 1-有效',  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),  `updated` TIMESTAMP DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP,  PRIMARY KEY (`id`));INSERT INTO `admin`(`name`, `mobile`, `password`) VALUES ('admin', '18903709210', '$2y$13$cdd81f89d6d9a3c8a81c5OWs5oCGHankhZPlZIQE8g3QTytjVka2C');DROP TABLE IF EXISTS `auth_code`;CREATE TABLE `auth_code` (  `id` int(11) NOT NULL AUTO_INCREMENT,  `code` char(6) NOT NULL COMMENT '验证码',  `user_id` int(11) NOT NULL COMMENT '用户表主键id',  `mobile` varchar(45) NOT NULL COMMENT '渠道，手机号',  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建（申请）时间',  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0-无效 1-有效',  PRIMARY KEY (`id`)) ENGINE=InnoDB AUTO_INCREMENT=13 COMMENT='手机生成的验证码';INSERT INTO statistics(`product_id`, `product_name`, `user_id`, `deepth`) VALUES (1, '1', 1, 1);INSERT INTO statistics(`product_id`, `product_name`, `user_id`, `deepth`) VALUES (1, '1', 1, 1);