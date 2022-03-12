/*
SQLyog Ultimate v11.25 (64 bit)
MySQL - 5.7.34-log : Database - jinxiaocun_kuguo
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `tp51_actual_salemode` */

DROP TABLE IF EXISTS `tp51_actual_salemode`;

CREATE TABLE `tp51_actual_salemode` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `region_name` varchar(30) DEFAULT NULL COMMENT '地区名称',
  `sales_model` varchar(30) DEFAULT NULL COMMENT '销售模式',
  `d_manager` varchar(30) DEFAULT NULL COMMENT '部门经理',
  `salesman` varchar(30) DEFAULT NULL COMMENT '业务员',
  `hospital_name` varchar(30) DEFAULT NULL COMMENT '医院名称',
  `supplier` varchar(30) DEFAULT NULL COMMENT '供货单位',
  `product_name` varchar(30) DEFAULT NULL COMMENT '品名',
  `specs` varchar(30) DEFAULT NULL COMMENT '规格',
  `last_month` varchar(30) DEFAULT NULL COMMENT '上月余数',
  `to_replenish` varchar(30) DEFAULT NULL COMMENT '本月进货',
  `month_70` varchar(30) DEFAULT NULL COMMENT '本月销售70%',
  `remainder_of` varchar(30) DEFAULT NULL COMMENT '本月余数',
  `ab_after` varchar(30) DEFAULT NULL COMMENT 'AB标准（税后）',
  `ab_money` varchar(30) DEFAULT NULL COMMENT 'AB金额',
  `paper_fee` varchar(30) DEFAULT NULL COMMENT '论文费',
  `money` varchar(30) DEFAULT NULL COMMENT '金额',
  `m_commission` varchar(30) DEFAULT NULL COMMENT '经理奖金提成单价',
  `manager_money` varchar(30) DEFAULT NULL COMMENT '经理奖金',
  `behalf_commission` varchar(30) DEFAULT NULL COMMENT '代表奖金提成价格',
  `behalf_money` varchar(20) DEFAULT NULL COMMENT '代表奖金',
  `margin_50` varchar(20) DEFAULT NULL COMMENT '保证金50%',
  `real_pay` varchar(20) DEFAULT NULL COMMENT '实付金额',
  `supply_price` varchar(30) DEFAULT NULL COMMENT '商业供货价',
  `complete_money` varchar(30) DEFAULT NULL COMMENT '完成金额',
  `task_yuan` varchar(30) DEFAULT NULL COMMENT '任务（元）',
  `completion` varchar(30) DEFAULT NULL COMMENT '完成率',
  `bad_amount` varchar(30) DEFAULT NULL COMMENT '超差金额（+/-）',
  `r_dan_p` varchar(30) DEFAULT NULL COMMENT '奖罚(+/-)1%',
  `real_amount` varchar(20) DEFAULT NULL COMMENT '实支金额',
  `note` varchar(500) DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `tp51_actual_salemode` */

insert  into `tp51_actual_salemode`(`id`,`region_name`,`sales_model`,`d_manager`,`salesman`,`hospital_name`,`supplier`,`product_name`,`specs`,`last_month`,`to_replenish`,`month_70`,`remainder_of`,`ab_after`,`ab_money`,`paper_fee`,`money`,`m_commission`,`manager_money`,`behalf_commission`,`behalf_money`,`margin_50`,`real_pay`,`supply_price`,`complete_money`,`task_yuan`,`completion`,`bad_amount`,`r_dan_p`,`real_amount`,`note`) values (1,'石家庄','70%','部门经理A','业务员A','省二院','供货单位A','小葵花','6 * 2','922','542','x * 70%','130','税后AB标准','AB金额','论文费','70','322','165','540','350','z * 50%','456','654','654','8000','60%','95%','-5%','7650','暂无备注');

/*Table structure for table `tp51_admin` */

DROP TABLE IF EXISTS `tp51_admin`;

CREATE TABLE `tp51_admin` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `username` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `password` char(36) COLLATE utf8_unicode_ci NOT NULL,
  `level` tinyint(5) NOT NULL,
  `xingming` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bumen` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `level` (`level`)
) ENGINE=MyISAM AUTO_INCREMENT=118 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='22223222';

/*Data for the table `tp51_admin` */

insert  into `tp51_admin`(`id`,`username`,`password`,`level`,`xingming`,`bumen`) values (1,'admin','e10adc3949ba59abbe56e057f20f883e',1,'水蓝月','CEO'),(98,'zhangsan','01d7f40760960e7bd9443513f22ab9af',2,'zhangsan','法务部'),(112,'ceshi','d0970714757783e6cf17b26fb8e2298f',53,'测试管理员','测试'),(111,'测试-修改','e10adc3949ba59abbe56e057f20f883e',53,'测试','1'),(114,'123456','e10adc3949ba59abbe56e057f20f883e',50,'一二三四五六','财务');

/*Table structure for table `tp51_admins` */

DROP TABLE IF EXISTS `tp51_admins`;

CREATE TABLE `tp51_admins` (
  `uid` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `user_name` varchar(20) NOT NULL COMMENT '用户名',
  `user_password` varchar(12) DEFAULT NULL COMMENT '密码',
  `user_phone` int(11) DEFAULT NULL COMMENT '手机号',
  `status` enum('0','1','-1') DEFAULT NULL,
  `rid` int(3) NOT NULL COMMENT '权限ID',
  `create_time` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`uid`),
  KEY `rid` (`rid`),
  CONSTRAINT `tp51_admins_ibfk_1` FOREIGN KEY (`rid`) REFERENCES `tp51_role` (`rid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `tp51_admins` */

insert  into `tp51_admins`(`uid`,`user_name`,`user_password`,`user_phone`,`status`,`rid`,`create_time`) values (1,'admins','123456',NULL,'1',1,NULL),(2,'user1','123123',NULL,'1',2,NULL);

/*Table structure for table `tp51_api_permission` */

DROP TABLE IF EXISTS `tp51_api_permission`;

CREATE TABLE `tp51_api_permission` (
  `ap_id` int(11) NOT NULL AUTO_INCREMENT,
  `ap_name` varchar(255) DEFAULT NULL,
  `ap_route` varchar(255) DEFAULT NULL,
  `ap_description` varchar(255) DEFAULT NULL,
  `ap_menuname` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ap_id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

/*Data for the table `tp51_api_permission` */

insert  into `tp51_api_permission`(`ap_id`,`ap_name`,`ap_route`,`ap_description`,`ap_menuname`) values (1,'hospitaladd',NULL,NULL,NULL),(9,NULL,NULL,NULL,NULL),(10,NULL,NULL,NULL,NULL),(11,NULL,NULL,NULL,NULL),(12,NULL,NULL,NULL,NULL),(13,NULL,NULL,NULL,NULL),(14,NULL,NULL,NULL,NULL),(18,NULL,NULL,NULL,NULL),(19,NULL,NULL,NULL,NULL),(20,NULL,NULL,NULL,NULL),(21,NULL,NULL,NULL,NULL),(22,NULL,NULL,NULL,NULL),(23,NULL,NULL,NULL,NULL),(24,NULL,NULL,NULL,NULL),(25,NULL,NULL,NULL,NULL);

/*Table structure for table `tp51_caozuojilu` */

DROP TABLE IF EXISTS `tp51_caozuojilu`;

CREATE TABLE `tp51_caozuojilu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data` varchar(255) DEFAULT NULL,
  `time` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=276 DEFAULT CHARSET=utf8;

/*Data for the table `tp51_caozuojilu` */

insert  into `tp51_caozuojilu`(`id`,`data`,`time`) values (102,'用户删除了业务员','2021-12-01 13:23:22'),(103,'用户删除了业务员','2021-12-01 13:23:41'),(104,'用户删除了业务员','2021-12-01 13:23:47'),(105,'用户导入了文件','2021-12-01 13:23:57'),(106,'用户删除了业务员','2021-12-01 13:24:04'),(107,'用户删除了业务员','2021-12-01 13:24:08'),(108,'用户导入了文件','2021-12-01 13:24:16'),(109,'用户导入了文件','2021-12-01 13:24:23'),(110,'用户删除了业务员','2021-12-01 13:24:32'),(111,'用户删除了业务员','2021-12-01 13:24:38'),(112,'用户删除了业务员','2021-12-01 13:24:43'),(113,'用户导入了文件','2021-12-01 13:24:49'),(114,'用户测试管理员登陆了系统','2021-12-01 13:42:30'),(115,'用户水蓝月登陆了系统','2021-12-01 13:45:45'),(116,'用户水蓝月详细查看了角色ID为2的数据','2021-12-01 13:53:58'),(117,'用户水蓝月详细查看了角色ID为2的数据','2021-12-01 13:54:02'),(118,'用户水蓝月修改了角色权限','2021-12-01 13:54:06'),(119,'用户zhangsan登陆了系统','2021-12-01 13:54:23'),(120,'用户zhangsan添加了角色','2021-12-01 13:55:06'),(121,'用户zhangsan修改了角色权限','2021-12-01 13:55:22'),(122,'用户zhangsan修改了管理员','2021-12-01 13:55:32'),(123,'用户zhangsan修改了管理员','2021-12-01 13:55:42'),(124,'用户测试管理员登陆了系统','2021-12-01 13:55:57'),(125,'用户水蓝月登陆了系统','2021-12-01 14:11:43'),(126,'用户zhangsan删除了直营终端','2021-12-01 14:37:13'),(127,'用户zhangsan删除了直营终端','2021-12-01 14:37:21'),(128,'用户zhangsan删除了直营终端','2021-12-01 14:37:22'),(129,'用户zhangsan删除了直营终端','2021-12-01 14:37:24'),(130,'用户zhangsan删除了直营终端','2021-12-01 14:37:25'),(131,'用户zhangsan删除了直营终端','2021-12-01 14:37:27'),(132,'用户zhangsan删除了直营终端','2021-12-01 14:37:28'),(133,'用户zhangsan删除了直营终端','2021-12-01 14:37:30'),(134,'用户zhangsan删除了直营终端','2021-12-01 14:37:33'),(135,'用户zhangsan删除了直营终端','2021-12-01 14:37:34'),(136,'用户zhangsan删除了直营终端','2021-12-01 14:37:36'),(137,'用户zhangsan删除了直营终端','2021-12-01 14:37:38'),(138,'用户zhangsan删除了直营终端','2021-12-01 14:37:40'),(139,'用户zhangsan删除了直营终端','2021-12-01 14:37:41'),(140,'用户zhangsan删除了直营终端','2021-12-01 14:37:43'),(141,'用户zhangsan删除了直营终端','2021-12-01 14:37:45'),(142,'用户zhangsan删除了直营终端','2021-12-01 14:37:47'),(143,'用户zhangsan删除了直营终端','2021-12-01 14:37:48'),(144,'用户zhangsan删除了直营终端','2021-12-01 14:37:50'),(145,'用户zhangsan删除了直营终端','2021-12-01 14:37:52'),(146,'用户zhangsan删除了直营终端','2021-12-01 14:37:53'),(147,'用户zhangsan删除了直营终端','2021-12-01 14:37:56'),(148,'用户删除了业务员','2021-12-01 14:49:31'),(149,'用户删除了业务员','2021-12-01 14:49:36'),(150,'用户导入了文件','2021-12-01 15:25:12'),(151,'用户测试管理员登陆了系统','2021-12-02 08:34:06'),(152,'用户查看了基础信息备案','2021-12-02 08:35:05'),(153,'用户测试管理员登陆了系统','2021-12-02 08:44:54'),(154,'用户水蓝月登陆了系统','2021-12-02 09:12:43'),(155,'用户删除了角色','2021-12-02 14:13:10'),(156,'用户zhangsan添加了角色','2021-12-02 14:13:30'),(157,'用户zhangsan添加了角色','2021-12-02 14:13:32'),(158,'用户zhangsan添加了角色','2021-12-02 14:13:36'),(159,'用户zhangsan添加了角色','2021-12-02 14:13:42'),(160,'用户zhangsan删除了角色','2021-12-02 14:13:49'),(161,'用户zhangsan删除了角色','2021-12-02 14:17:34'),(162,'用户zhangsan添加了角色','2021-12-02 14:32:27'),(163,'用户zhangsan添加了角色','2021-12-02 14:32:29'),(164,'用户zhangsan添加了角色','2021-12-02 14:32:33'),(165,'用户zhangsan添加了角色','2021-12-02 14:32:36'),(166,'用户zhangsan添加了角色','2021-12-02 14:32:38'),(167,'用户zhangsan添加了角色','2021-12-02 14:32:42'),(168,'用户zhangsan添加了角色','2021-12-02 14:32:44'),(169,'用户zhangsan添加了角色','2021-12-02 14:32:47'),(170,'用户zhangsan添加了角色','2021-12-02 14:32:49'),(171,'用户zhangsan添加了角色','2021-12-02 14:32:53'),(172,'用户zhangsan添加了角色','2021-12-02 14:32:56'),(173,'用户zhangsan删除了角色','2021-12-02 14:33:03'),(174,'用户zhangsan添加了角色','2021-12-02 14:33:12'),(175,'用户zhangsan添加了角色','2021-12-02 14:33:17'),(176,'用户zhangsan添加了角色','2021-12-02 14:33:21'),(177,'用户zhangsan添加了角色','2021-12-02 14:33:23'),(178,'用户zhangsan删除了角色','2021-12-02 14:34:18'),(179,'用户zhangsan删除了角色','2021-12-02 14:34:37'),(180,'用户zhangsan删除了角色','2021-12-02 14:35:48'),(181,'用户zhangsan添加了管理员','2021-12-02 14:41:45'),(182,'用户zhangsan删除了角色','2021-12-02 14:43:27'),(183,'用户zhangsan删除了账号','2021-12-02 14:48:40'),(184,'用户zhangsan添加了管理员','2021-12-02 14:49:10'),(185,'用户zhangsan添加了管理员','2021-12-02 14:49:54'),(186,'用户zhangsan删除了账号','2021-12-02 14:50:03'),(187,'用户删除了产品','2021-12-02 14:53:26'),(188,'用户删除了产品','2021-12-02 14:55:03'),(189,'用户删除了业务员','2021-12-02 14:55:11'),(190,'用户导入了文件','2021-12-02 14:55:48'),(191,'用户删除了产品','2021-12-02 14:56:20'),(192,'用户zhangsan添加了产品','2021-12-02 14:57:05'),(193,'用户删除了产品','2021-12-02 14:57:42'),(194,'用户zhangsan添加了产品','2021-12-02 14:57:44'),(195,'用户删除了产品','2021-12-02 14:58:47'),(196,'用户zhangsan添加了产品','2021-12-02 14:58:51'),(197,'用户删除了产品','2021-12-02 14:59:04'),(198,'用户zhangsan添加了产品','2021-12-02 14:59:06'),(199,'用户删除了产品','2021-12-02 14:59:42'),(200,'用户zhangsan添加了产品','2021-12-02 14:59:45'),(201,'用户zhangsan登陆了系统','2021-12-02 15:00:59'),(202,'用户zhangsan添加了产品','2021-12-02 15:01:23'),(203,'用户删除了产品','2021-12-02 15:01:27'),(204,'用户zhangsan添加了产品','2021-12-02 15:01:30'),(205,'用户删除了产品','2021-12-02 15:01:42'),(206,'用户zhangsan添加了产品','2021-12-02 15:01:45'),(207,'用户zhangsan添加了产品','2021-12-02 15:03:28'),(208,'用户zhangsan添加了产品','2021-12-02 15:03:30'),(209,'用户zhangsan添加了产品','2021-12-02 15:03:32'),(210,'用户zhangsan添加了产品','2021-12-02 15:03:33'),(211,'用户删除了产品','2021-12-02 15:03:37'),(212,'用户zhangsan添加了产品','2021-12-02 15:03:39'),(213,'用户删除了产品','2021-12-02 15:05:44'),(214,'用户zhangsan添加了产品','2021-12-02 15:08:05'),(215,'用户删除了产品','2021-12-02 15:08:16'),(216,'用户zhangsan添加了产品','2021-12-02 15:08:22'),(217,'用户删除了产品','2021-12-02 15:08:29'),(218,'用户zhangsan添加了产品','2021-12-02 15:08:32'),(219,'用户删除了产品','2021-12-02 15:09:11'),(220,'用户zhangsan添加了产品','2021-12-02 15:09:14'),(221,'用户删除了产品','2021-12-02 15:09:35'),(222,'用户zhangsan添加了产品','2021-12-02 15:09:38'),(223,'用户删除了产品','2021-12-02 15:11:44'),(224,'用户zhangsan添加了产品','2021-12-02 15:11:53'),(225,'用户删除了产品','2021-12-02 15:11:59'),(226,'用户zhangsan添加了产品','2021-12-02 15:12:00'),(227,'用户删除了产品','2021-12-02 15:18:56'),(228,'用户zhangsan添加了产品','2021-12-02 15:19:48'),(229,'用户删除了产品','2021-12-02 15:19:52'),(230,'用户zhangsan添加了产品','2021-12-02 15:19:53'),(231,'用户删除了产品','2021-12-02 15:22:36'),(232,'用户zhangsan添加了产品','2021-12-02 15:22:39'),(233,'用户删除了产品','2021-12-02 15:22:47'),(234,'用户zhangsan添加了产品','2021-12-02 15:23:02'),(235,'用户删除了产品','2021-12-02 15:23:56'),(236,'用户zhangsan添加了产品','2021-12-02 15:24:00'),(237,'用户删除了产品','2021-12-02 15:27:55'),(238,'用户zhangsan添加了产品','2021-12-02 15:28:08'),(239,'用户zhangsan添加了产品','2021-12-02 15:28:24'),(240,'用户删除了产品','2021-12-02 15:28:26'),(241,'用户删除了产品','2021-12-02 15:28:29'),(242,'用户zhangsan添加了产品','2021-12-02 15:30:24'),(243,'用户删除了产品','2021-12-02 15:30:26'),(244,'用户zhangsan添加了产品','2021-12-02 15:30:28'),(245,'用户zhangsan添加了产品','2021-12-02 15:30:37'),(246,'用户zhangsan添加了产品','2021-12-02 15:30:38'),(247,'用户zhangsan添加了产品','2021-12-02 15:30:40'),(248,'用户删除了产品','2021-12-02 15:30:48'),(249,'用户zhangsan添加了产品','2021-12-02 15:30:50'),(250,'用户删除了产品','2021-12-02 15:32:02'),(251,'用户zhangsan添加了产品','2021-12-02 15:32:08'),(252,'用户删除了产品','2021-12-02 15:33:01'),(253,'用户zhangsan添加了产品','2021-12-02 15:33:05'),(254,'用户删除了产品','2021-12-02 15:33:33'),(255,'用户zhangsan添加了产品','2021-12-02 15:33:35'),(256,'用户删除了产品','2021-12-02 15:34:28'),(257,'用户zhangsan添加了产品','2021-12-02 15:34:30'),(258,'用户删除了产品','2021-12-02 15:34:33'),(259,'用户zhangsan添加了产品','2021-12-02 15:34:35'),(260,'用户删除了产品','2021-12-02 15:36:31'),(261,'用户zhangsan添加了产品','2021-12-02 15:36:35'),(262,'用户zhangsan添加了产品','2021-12-02 15:36:38'),(263,'用户删除了产品','2021-12-02 15:36:41'),(264,'用户删除了产品','2021-12-02 15:36:42'),(265,'用户水蓝月登陆了系统','2021-12-02 15:37:45'),(266,'用户zhangsan登陆了系统','2021-12-02 15:40:07'),(267,'用户zhangsan删除了业务员','2021-12-02 15:52:39'),(268,'用户导入了文件','2021-12-02 15:52:49'),(269,'用户导入了文件','2021-12-02 16:02:39'),(270,'用户导入了文件','2021-12-02 16:02:52'),(271,'用户zhangsan详细查看了角色ID为3的数据','2021-12-02 16:25:15'),(272,'用户zhangsan详细查看了角色ID为53的数据','2021-12-02 16:25:27'),(273,'用户删除了产品','2021-12-02 16:27:14'),(274,'用户修改了产品','2021-12-02 16:28:50'),(275,'用户zhangsan登陆了系统','2021-12-02 16:31:24');

/*Table structure for table `tp51_chanpin` */

DROP TABLE IF EXISTS `tp51_chanpin`;

CREATE TABLE `tp51_chanpin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `chanpin` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=111 DEFAULT CHARSET=utf8;

/*Data for the table `tp51_chanpin` */

insert  into `tp51_chanpin`(`id`,`chanpin`) values (100,'斑蝥酸钠维生素B6注射液'),(102,'斑蝥酸钠维生素B8注射液'),(103,'复方红豆杉胶囊'),(104,'儿童七珍丸'),(105,'枫蓼肠胃康合剂'),(107,'斑蝥酸钠维生素B16注射液'),(108,'消炎止咳片'),(109,'开喉健喷雾剂'),(110,'骨康胶囊');

/*Table structure for table `tp51_chanpinjinxiaocun` */

DROP TABLE IF EXISTS `tp51_chanpinjinxiaocun`;

CREATE TABLE `tp51_chanpinjinxiaocun` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `yuefen` varchar(255) DEFAULT NULL,
  `chanpin` varchar(255) DEFAULT NULL,
  `guige` varchar(255) DEFAULT NULL,
  `benyuefahuo` varchar(255) DEFAULT NULL,
  `shangyedanwei` varchar(255) DEFAULT NULL,
  `fahuoshuliang` varchar(255) DEFAULT NULL,
  `fenxiaoshang` varchar(255) DEFAULT NULL,
  `fenxiaofahuo` varchar(255) DEFAULT NULL,
  `beizhu` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `tp51_chanpinjinxiaocun` */

insert  into `tp51_chanpinjinxiaocun`(`id`,`yuefen`,`chanpin`,`guige`,`benyuefahuo`,`shangyedanwei`,`fahuoshuliang`,`fenxiaoshang`,`fenxiaofahuo`,`beizhu`) values (1,'8','开喉间','50ml','300','北京六智','300','代理商','200','暂无');

/*Table structure for table `tp51_class` */

DROP TABLE IF EXISTS `tp51_class`;

CREATE TABLE `tp51_class` (
  `cid` int(11) NOT NULL,
  `classid` int(11) NOT NULL AUTO_INCREMENT,
  `class` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`classid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

/*Data for the table `tp51_class` */

insert  into `tp51_class`(`cid`,`classid`,`class`) values (2,3,'心血管类'),(2,4,'肠胃类');

/*Table structure for table `tp51_delivery` */

DROP TABLE IF EXISTS `tp51_delivery`;

CREATE TABLE `tp51_delivery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `factory_name` varchar(100) DEFAULT NULL COMMENT '厂家名称',
  `fac_name` varchar(100) DEFAULT NULL COMMENT '药品名称',
  `fac_specs` varchar(100) DEFAULT NULL COMMENT '药品规格',
  `fac_num` int(11) DEFAULT NULL COMMENT '发货数量',
  `fac_price` decimal(10,2) DEFAULT NULL COMMENT '产品单价',
  `total_price` decimal(10,2) DEFAULT NULL COMMENT '总金额',
  `batch_num` varchar(100) DEFAULT NULL COMMENT '批号',
  `business_unit` varchar(100) DEFAULT NULL COMMENT '商业单位(一、二级名称)',
  `month_time` date DEFAULT NULL COMMENT '具体日期',
  `create_time` varchar(20) DEFAULT NULL,
  `update_time` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

/*Data for the table `tp51_delivery` */

insert  into `tp51_delivery`(`id`,`factory_name`,`fac_name`,`fac_specs`,`fac_num`,`fac_price`,`total_price`,`batch_num`,`business_unit`,`month_time`,`create_time`,`update_time`) values (6,'贵阳德昌祥药业有限公司','儿童七珍丸','80丸',4000,'18.22','72880.00','20210905','华润河北医药有限公司','2021-11-29','1638149057','1638235233'),(7,'海南万州绿色制药有限公司','枫蓼肠胃康合剂','18ml*2支',10,'20.11','201.10','20210803','河北龙海新药经营有限公司','2021-11-29','1638149057','1638233608'),(8,'贵州三力制药股份有限公司','开喉健喷雾剂','儿童30ml',250,'10.00','2500.00','20210611','必康润祥医药河北有限公司','2021-11-29','1638149057','1638151934'),(9,'贵州柏强制药有限公司','斑蝥酸钠维生素B6注射液','10ml',3,'3.00','9.00','3','河北金天燕霄医药有限公司','2021-10-21','1638179071','1638238775'),(10,'贵州汉方药业有限公司','儿童回春颗粒','6袋',10,'10.00','100.00','21212122','必康润祥医药河北有限公司','2021-11-29','1638232722','1638233600'),(13,'重庆赛诺生物药业股份有限公司','复方红豆杉胶囊','18s',11,'22.00','242.00','12','河北谛康医药有限公司','2021-11-29','1638233279','1638234832'),(14,'贵州柏强制药有限公司','斑蝥酸钠维生素B6注射液','10ml',121,'123.00','14883.00','121','华润河北医药有限公司','2021-11-29','1638233320','1638235107'),(150,'贵州柏强制药有限公司','斑蝥酸钠维生素B6注射液','5ml',400,'32.00','12800.00','20210608','国药乐仁堂医药有限公司','2021-11-29','1638149057','1638349134');

/*Table structure for table `tp51_diqu` */

DROP TABLE IF EXISTS `tp51_diqu`;

CREATE TABLE `tp51_diqu` (
  `diqu` int(11) NOT NULL AUTO_INCREMENT,
  `diquname` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`diqu`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=268 DEFAULT CHARSET=utf8;

/*Data for the table `tp51_diqu` */

insert  into `tp51_diqu`(`diqu`,`diquname`) values (147,'河北省-石家庄市-井陉县'),(149,'河北省-石家庄市-井陉县'),(151,'河北省-保定市-莲池区'),(153,'河北省-保定市-涞源县'),(154,'河北省-保定市-易县'),(236,'北京市-西城区'),(239,'内蒙古自治区-呼和浩特市'),(240,'内蒙古自治区-呼和浩特市-回民区'),(243,'江苏省-徐州市-铜山区'),(244,'江苏省-徐州市-铜山区'),(245,'江苏省-徐州市-铜山区'),(246,'河北省-邯郸市-峰峰矿区'),(257,'内蒙古自治区-乌海市-海南区'),(258,'内蒙古自治区-乌海市-海南区'),(259,'内蒙古自治区-乌海市-海南区'),(261,'河北省-唐山市-路北区'),(262,'河北省-邢台市-柏乡县'),(263,'河北省-秦皇岛市-抚宁区'),(264,'北京市-朝阳区'),(265,'山西省-晋城市-陵川县'),(266,'河北省-石家庄市'),(267,'河北省-石家庄市');

/*Table structure for table `tp51_division` */

DROP TABLE IF EXISTS `tp51_division`;

CREATE TABLE `tp51_division` (
  `bid` int(11) NOT NULL AUTO_INCREMENT,
  `prescription` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`bid`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8;

/*Data for the table `tp51_division` */

insert  into `tp51_division`(`bid`,`prescription`) values (1,'处方药'),(2,'非处方药');

/*Table structure for table `tp51_dqid` */

DROP TABLE IF EXISTS `tp51_dqid`;

CREATE TABLE `tp51_dqid` (
  `dqid` int(11) DEFAULT NULL,
  `diqu` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tp51_dqid` */

insert  into `tp51_dqid`(`dqid`,`diqu`) values (1,233),(1,234),(4,1),(72,3),(72,5),(74,1),(75,2),(76,3),(77,4),(78,5),(79,2),(82,118),(83,118),(84,118),(85,118),(86,177),(87,180),(88,120),(89,147),(90,147),(93,227),(94,234),(123,123),(123,123),(123,123),(123,123),(123,123),(123,123),(96,123),(96,235),(98,237),(99,123),(99,236),(100,239),(100,240),(101,239),(102,240),(103,241),(104,240),(105,237),(106,241),(107,236),(108,153),(109,123),(110,237),(97,237),(97,236),(111,241),(111,123),(112,239),(112,240),(113,257);

/*Table structure for table `tp51_excexport` */

DROP TABLE IF EXISTS `tp51_excexport`;

CREATE TABLE `tp51_excexport` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `ttitle` varchar(20) DEFAULT NULL COMMENT '测试标题/主题',
  `tcontent` varchar(100) DEFAULT NULL COMMENT '测试信息内容',
  `tnum` varchar(20) DEFAULT NULL COMMENT '测试数量',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=619 DEFAULT CHARSET=utf8;

/*Data for the table `tp51_excexport` */

insert  into `tp51_excexport`(`id`,`ttitle`,`tcontent`,`tnum`) values (1,'测试','一条测试信息','1'),(61,'1','2',NULL),(62,'3','4',NULL),(63,'5','6',NULL),(64,'7','8',NULL),(65,'123','456',NULL),(66,'798','12',NULL),(67,'1','2',NULL),(68,'3','4',NULL),(69,'5','6',NULL),(70,'7','8',NULL),(71,'123','456',NULL),(72,'798','12',NULL),(88,'1','2',NULL),(89,'3','4',NULL),(90,'5','6',NULL),(91,'7','8',NULL),(92,'123','456',NULL),(93,'798','12',NULL),(597,'1','2',NULL),(598,'3','4',NULL),(599,'5','6',NULL),(600,'7','8',NULL),(601,'123','456',NULL),(602,'798','12',NULL),(603,'1','2',NULL),(604,'3','4',NULL),(605,'5','6',NULL),(606,'7','8',NULL),(607,'123','456',NULL),(608,'798','12',NULL),(609,'1','2',NULL),(610,'3','4',NULL),(611,'5','6',NULL),(612,'7','8',NULL),(613,'123','456',NULL),(614,'798','12',NULL),(615,'批号','编号','名称'),(616,'1','1001','测试'),(617,'批号','编号','名称'),(618,'1','1001','测试');

/*Table structure for table `tp51_fenxiaozhongduan` */

DROP TABLE IF EXISTS `tp51_fenxiaozhongduan`;

CREATE TABLE `tp51_fenxiaozhongduan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `yuefen` varchar(255) DEFAULT NULL,
  `xiaoshoumoshi` varchar(255) DEFAULT NULL,
  `yewuyuan` varchar(255) DEFAULT NULL,
  `diqu` varchar(255) DEFAULT NULL,
  `yiyuanmingcheng` varchar(255) DEFAULT NULL,
  `zhongduanjibie` varchar(255) DEFAULT NULL,
  `gonghuodanwei` varchar(255) DEFAULT NULL,
  `pinming` varchar(255) DEFAULT NULL,
  `guige` varchar(255) DEFAULT NULL,
  `jinhuo` varchar(255) DEFAULT NULL,
  `xiaoshou` varchar(255) DEFAULT NULL,
  `kucun` varchar(255) DEFAULT NULL,
  `abbiaozhunshuihou` varchar(255) DEFAULT NULL,
  `abjine` varchar(255) DEFAULT NULL,
  `beizhu` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8;

/*Data for the table `tp51_fenxiaozhongduan` */

insert  into `tp51_fenxiaozhongduan`(`id`,`yuefen`,`xiaoshoumoshi`,`yewuyuan`,`diqu`,`yiyuanmingcheng`,`zhongduanjibie`,`gonghuodanwei`,`pinming`,`guige`,`jinhuo`,`xiaoshou`,`kucun`,`abbiaozhunshuihou`,`abjine`,`beizhu`) values (24,'2021-11','线上','张三','内蒙古自治区-呼和浩特市','北京医院','直营终端','某某','某某','？','？','？','？','？','？','1'),(25,'2021-11','线上','张三','内蒙古自治区-呼和浩特市','北京医院','1','1','111','111','11','11111','111','111','11','11'),(26,'2021-11','线上','张三','内蒙古自治区-呼和浩特市','北京医院','1','1','111','111','11','11111','111','111','11','11'),(27,'2021-11','线上','张三','内蒙古自治区-呼和浩特市','北京医院','1','1','白云山','423','3','43','5454','111','11','11'),(28,'2021-11','线上','张三','内蒙古自治区-呼和浩特市','北京医院','1','1','白云山','423','3','43','5454','111','11','11'),(29,'2021-11','线上','张三','内蒙古自治区-呼和浩特市','北京医院','1','1','白云山','423','3','43','5454','111','11','11'),(30,'2021-11','线上','张三','内蒙古自治区-呼和浩特市','北京医院','1','1','白云山','423','3','43','5454','111','11','11'),(31,'2021-11','线上','张三','内蒙古自治区-呼和浩特市','北京医院','1','1','白云山','423','3','43','5454','111','11','11'),(32,'2021-11','线上','张三','内蒙古自治区-呼和浩特市','北京医院','1','1','白云山','423','3','43','5454','111','11','11'),(33,'2021-11','线上','张三','内蒙古自治区-呼和浩特市','北京医院','1','1','白云山','423','3','43','5454','111','11','11'),(34,'2021-11','线上','张三','内蒙古自治区-呼和浩特市','北京医院','1','1','白云山','423','3','43','5454','111','11','11'),(35,'2021-11','线上','张三','内蒙古自治区-呼和浩特市','北京医院','1','1','白云山','423','3','43','5454','111','11','11'),(36,'2021-11','线上','张三','内蒙古自治区-呼和浩特市-回民区','北京医院','1','1','白云山','423','3','43','5454','111','11','11'),(37,'2021-11','线上','张三','内蒙古自治区-呼和浩特市-回民区','北京医院','1','1','白云山','423','3','43','5454','111','11','11'),(38,'2021-11','线上','张三','内蒙古自治区-呼和浩特市-回民区','北京医院','1','一级','白云山','43','3','4342','332','111','11','11'),(39,'2021-11','线上','张三','内蒙古自治区-呼和浩特市-回民区','北京医院','1','一级','白云山','43','3','4342','332','111','11','11'),(40,'2021-11','线上','张三','内蒙古自治区-呼和浩特市-回民区','北京医院','1','一级','白云山','43','3','4342','332','111','11','11');

/*Table structure for table `tp51_flowofmed` */

DROP TABLE IF EXISTS `tp51_flowofmed`;

CREATE TABLE `tp51_flowofmed` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `one_id` varchar(11) DEFAULT NULL COMMENT '一级ID',
  `de_id` varchar(11) DEFAULT NULL COMMENT '商业发货ID',
  `innums` int(30) DEFAULT NULL COMMENT '入库数量',
  `facname` varchar(50) DEFAULT NULL COMMENT '商业公司名称',
  `in_time` date DEFAULT NULL COMMENT '一级销售日期',
  `med_name` varchar(30) DEFAULT NULL COMMENT '药品名称',
  `med_specs` varchar(30) DEFAULT NULL COMMENT '规格',
  `med_unit` varchar(20) DEFAULT NULL COMMENT '药品计量单位',
  `med_salenum` int(20) DEFAULT NULL COMMENT '销售数量',
  `med_batchnum` int(20) DEFAULT NULL COMMENT '批号',
  `med_price` decimal(10,2) DEFAULT NULL COMMENT '药品单价',
  `customer_name` varchar(50) DEFAULT NULL COMMENT '客户名称A',
  `customer_nameb` varchar(50) DEFAULT NULL COMMENT '客户名称B',
  `buss_name` varchar(50) DEFAULT NULL COMMENT '供应商名称',
  `buss_origin` varchar(50) DEFAULT NULL COMMENT '产地',
  `create_time` varchar(15) DEFAULT NULL,
  `update_time` varchar(15) DEFAULT NULL,
  `ssid` enum('1','2') DEFAULT NULL COMMENT '标识符:1=1级;2=2级',
  `stock_num` int(11) DEFAULT NULL COMMENT '库存数量',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=246 DEFAULT CHARSET=utf8;

/*Data for the table `tp51_flowofmed` */

insert  into `tp51_flowofmed`(`id`,`one_id`,`de_id`,`innums`,`facname`,`in_time`,`med_name`,`med_specs`,`med_unit`,`med_salenum`,`med_batchnum`,`med_price`,`customer_name`,`customer_nameb`,`buss_name`,`buss_origin`,`create_time`,`update_time`,`ssid`,`stock_num`) values (236,NULL,'150',4001,'国药乐仁堂医药有限公司','2021-11-15','斑蝥酸钠维生素B6注射液','5ml','支',500,20210608,'32.00','国药乐仁堂衡水医药有限公司','','贵州柏强制药有限公司','贵州柏强制药有限公司','1638349087','1638406225','1',NULL),(237,'236',NULL,5012,'国药乐仁堂衡水医药有限公司','2021-11-25','斑蝥酸钠维生素B6注射液','5ml','支',600,20210608,'33.00','药房1','','国药乐仁堂医药有限公司','贵州柏强制药有限公司','1638349174','1638412107','2',NULL),(238,NULL,NULL,4001,'国药乐仁堂医药有限公司','2021-11-15','斑蝥酸钠维生素B6注射液','5ml','支',440,20210608,'32.00','阿三是','啥哭给你看','贵州柏强制药有限公司','贵州柏强制药有限公司','1638407665','1638407665','1',NULL),(239,NULL,NULL,4001,'国药乐仁堂医药有限公司','2021-11-15','斑蝥酸钠维生素B6注射液','5ml','支',500,20210608,'32.00','国药乐仁堂衡水医药有限公司','','贵州柏强制药有限公司','贵州柏强制药有限公司',NULL,NULL,'1',NULL),(240,NULL,NULL,5011,'国药乐仁堂衡水医药有限公司','2021-11-25','斑蝥酸钠维生素B6注射液','5ml','支',600,20210608,'33.00','dfsa','','国药乐仁堂医药有限公司','贵州柏强制药有限公司',NULL,'1638412101','2',NULL),(241,NULL,NULL,4001,'国药乐仁堂医药有限公司','2021-11-15','斑蝥酸钠维生素B6注射液','5ml','支',500,20210608,'32.00','dsa asd as',NULL,NULL,'贵州柏强制药有限公司',NULL,NULL,'1',NULL),(242,NULL,NULL,500,'国药乐仁堂','2021-12-23','三九胃泰666','66ml','个',100,20210603,'32.00','22','22','阿萨德','大萨达','1638429618','1638429618','1',NULL),(243,NULL,NULL,500,'国药乐仁堂','2021-12-23','三九胃泰666','66ml','个',100,20210603,'23.00','阿萨德','发','阿萨德','大萨达','1638430128','1638430128','1',NULL),(244,NULL,NULL,100,'1','2021-12-10','11','11','11',10,11,'11.00','11','','11','11','1638432026','1638432052','1',NULL),(245,NULL,NULL,5000,'中华保健','2021-12-11','片仔癀','20ml','支',1000,20211202,'33.00','阿达','发啥','供应商','打开来感觉','1638432073','1638432073','2',NULL);

/*Table structure for table `tp51_goods` */

DROP TABLE IF EXISTS `tp51_goods`;

CREATE TABLE `tp51_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lotnumber` varchar(11) DEFAULT NULL COMMENT '批号',
  `number` varchar(11) DEFAULT NULL COMMENT '编号',
  `name` varchar(255) DEFAULT NULL COMMENT '药品名称',
  `brand` varchar(255) DEFAULT NULL COMMENT '药品品牌',
  `specs` varchar(255) DEFAULT NULL COMMENT '药品规格',
  `prescription` varchar(10) DEFAULT NULL COMMENT '处方药/非处方药',
  `class` varchar(255) DEFAULT NULL COMMENT '具体分类',
  `composition` varchar(255) DEFAULT NULL COMMENT '成分',
  `color` varchar(20) DEFAULT NULL COMMENT '颜色',
  `library_number` varchar(255) DEFAULT NULL COMMENT '库存数量',
  `sales` varchar(11) DEFAULT NULL COMMENT '销量',
  `create_time` varchar(11) DEFAULT NULL COMMENT '时间',
  `update_time` varchar(11) DEFAULT NULL,
  `unit` varchar(11) DEFAULT NULL COMMENT '计量单位',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1590 DEFAULT CHARSET=utf8;

/*Data for the table `tp51_goods` */

insert  into `tp51_goods`(`id`,`lotnumber`,`number`,`name`,`brand`,`specs`,`prescription`,`class`,`composition`,`color`,`library_number`,`sales`,`create_time`,`update_time`,`unit`) values (889,'1','20211124','感冒灵颗粒','999','24*1','1','4',NULL,NULL,'31',NULL,NULL,'1637715156',NULL),(890,'1','20211124','健胃消食片','哈药集团','8*2','2','3',NULL,NULL,'111',NULL,NULL,'1637715093',NULL),(891,'1','20211124','西瓜霜','乐仁堂','3*2','1','4',NULL,NULL,'73',NULL,NULL,'1637725462',NULL),(892,'1','20211124','藿香正气水','乐仁堂','12','1','心血管类',NULL,NULL,NULL,NULL,'2642-04-13 ',NULL,NULL),(893,'1','20211124','三九胃泰','国药集团','6*2','1','3',NULL,NULL,'77',NULL,NULL,'1637714838',NULL),(895,'1','20211124','三九胃泰','华药集团','6*3','2','3',NULL,NULL,'42',NULL,NULL,'1637714806',NULL),(896,'1','20211124','三九胃泰','哈药集团','6*2','2','4',NULL,NULL,'73',NULL,NULL,'1637714768',NULL),(946,'1','1000113','阿莫西林','阿莫西林','12 * 2','1','4',NULL,NULL,NULL,NULL,NULL,'1637725593',NULL),(1494,NULL,NULL,'导入测试4','5958958','15 * 2 ',NULL,NULL,NULL,NULL,'',NULL,'1637811374','1638330861',NULL),(1495,NULL,NULL,'导入测试2',NULL,'13 * 2 ',NULL,NULL,NULL,NULL,NULL,NULL,'1637811374',NULL,NULL),(1496,NULL,NULL,'导入测试3',NULL,'14 * 2 ',NULL,NULL,NULL,NULL,NULL,NULL,'1637811374',NULL,NULL),(1497,NULL,NULL,'导入测试4','aafff','15 * 2 ',NULL,NULL,NULL,NULL,'',NULL,'1637811374','1638330523',NULL),(1498,NULL,NULL,'导入测试5',NULL,'16 * 2 ',NULL,NULL,NULL,NULL,NULL,NULL,'1637811374',NULL,NULL),(1500,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1637812406',NULL,NULL),(1501,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1637812406',NULL,NULL),(1502,NULL,NULL,'产品',NULL,'规格',NULL,NULL,NULL,NULL,NULL,NULL,'1637812406',NULL,NULL),(1503,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1637812406',NULL,NULL),(1504,NULL,NULL,'三九胃泰',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1637812406',NULL,NULL),(1505,NULL,NULL,'三九胃泰',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1637812406',NULL,NULL),(1506,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1637812406',NULL,NULL),(1507,NULL,NULL,'一级配送商业',NULL,'产品',NULL,NULL,NULL,NULL,NULL,NULL,'1637812406',NULL,NULL),(1508,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1637812406',NULL,NULL),(1509,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1637812406',NULL,NULL),(1510,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1637812406',NULL,NULL),(1511,NULL,NULL,'二级配送商业（需注明一级商业名称）',NULL,'产品',NULL,NULL,NULL,NULL,NULL,NULL,'1637812406',NULL,NULL),(1512,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1637812406',NULL,NULL),(1513,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1637812406',NULL,NULL),(1514,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1637812406',NULL,NULL),(1515,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1637812406',NULL,NULL),(1516,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1637812406',NULL,NULL),(1517,NULL,NULL,'第一个是从厂家的全部的发货',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1637812406',NULL,NULL),(1518,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1637812406',NULL,NULL),(1519,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1637812406',NULL,NULL),(1520,NULL,NULL,'第二个是一级分销商的发货',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1637812406',NULL,NULL),(1521,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1637812406',NULL,NULL),(1522,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1637812406',NULL,NULL),(1523,NULL,NULL,'第三个是二级商的发货',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1637812406',NULL,NULL);

/*Table structure for table `tp51_hospital` */

DROP TABLE IF EXISTS `tp51_hospital`;

CREATE TABLE `tp51_hospital` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hospitalid` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `anothername` varchar(255) DEFAULT NULL,
  `place` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=118 DEFAULT CHARSET=utf8;

/*Data for the table `tp51_hospital` */

insert  into `tp51_hospital`(`id`,`hospitalid`,`name`,`anothername`,`place`) values (67,67,'北京医院','北京','北京'),(68,68,'石家庄医院','石家庄','石家庄'),(96,96,'石家庄第五医院','五月医院','五月天之地'),(97,97,'石家庄第6医院','6月医院','6月天之地'),(98,98,'石家庄第7医院','7月医院','7月天之地'),(115,115,'沧州市中心医院(西)','沧州','沧州'),(116,116,'唐山医院','唐山','唐山'),(117,117,'河北省红十字基金会石家庄中西医结合医院','河北省红十字基金会石家庄中西医结合医院','河北省石家庄市');

/*Table structure for table `tp51_hospitalid` */

DROP TABLE IF EXISTS `tp51_hospitalid`;

CREATE TABLE `tp51_hospitalid` (
  `hid` int(11) NOT NULL AUTO_INCREMENT,
  `managerid` int(11) DEFAULT NULL,
  `hospitalid` int(11) DEFAULT NULL,
  `move` varchar(255) DEFAULT NULL COMMENT '移动业务',
  PRIMARY KEY (`hid`)
) ENGINE=InnoDB AUTO_INCREMENT=162 DEFAULT CHARSET=utf8;

/*Data for the table `tp51_hospitalid` */

insert  into `tp51_hospitalid`(`hid`,`managerid`,`hospitalid`,`move`) values (134,297,3,NULL),(135,297,4,NULL),(136,298,2,NULL),(137,298,3,NULL),(138,298,4,NULL),(139,299,1,NULL),(140,299,2,NULL),(141,300,1,NULL),(142,301,3,NULL),(143,301,4,NULL),(144,302,2,NULL),(145,302,1,NULL),(146,302,3,NULL),(147,302,4,NULL),(149,304,2,NULL),(151,305,2,NULL),(154,306,1,NULL),(155,306,2,NULL),(156,307,1,NULL),(157,307,2,NULL),(158,307,3,NULL),(159,308,1,NULL),(160,308,2,NULL),(161,309,4,NULL);

/*Table structure for table `tp51_juese` */

DROP TABLE IF EXISTS `tp51_juese`;

CREATE TABLE `tp51_juese` (
  `jueseid` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户id',
  `name` varchar(255) NOT NULL,
  `miaoshu` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`jueseid`)
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8;

/*Data for the table `tp51_juese` */

insert  into `tp51_juese`(`jueseid`,`name`,`miaoshu`) values (1,'超级管理员','所有权限'),(2,'管理员','张三'),(3,'业务员管理','业务员模块'),(53,'超级管理员1','空');

/*Table structure for table `tp51_juesechanpin` */

DROP TABLE IF EXISTS `tp51_juesechanpin`;

CREATE TABLE `tp51_juesechanpin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jueseid` int(11) DEFAULT NULL,
  `chanpinid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8;

/*Data for the table `tp51_juesechanpin` */

insert  into `tp51_juesechanpin`(`id`,`jueseid`,`chanpinid`) values (1,1,1),(2,1,2),(14,2,7),(15,2,8),(16,2,10),(25,51,6),(27,52,8),(35,1,8),(36,1,11),(37,3,6),(38,2,7),(39,2,8),(40,2,10),(41,2,54),(42,3,6),(43,3,55),(47,53,52),(48,53,52),(49,53,109),(50,3,6),(51,3,6),(52,3,55),(53,3,102),(54,3,6),(55,3,6),(56,3,55),(57,3,6),(58,3,6),(59,3,55),(60,3,102),(61,3,109),(62,3,6),(63,3,6),(64,3,55),(65,3,6),(66,3,6),(67,3,55),(68,3,102),(69,3,6),(70,3,6),(71,3,55),(72,3,6),(73,3,6),(74,3,55),(75,3,102),(76,3,109),(77,3,107);

/*Table structure for table `tp51_juesequan` */

DROP TABLE IF EXISTS `tp51_juesequan`;

CREATE TABLE `tp51_juesequan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jueseid` int(11) DEFAULT NULL,
  `mid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1230 DEFAULT CHARSET=utf8;

/*Data for the table `tp51_juesequan` */

insert  into `tp51_juesequan`(`id`,`jueseid`,`mid`) values (122,3,1),(123,3,2),(124,3,3),(125,3,4),(126,3,5),(127,3,6),(128,3,7),(129,3,8),(130,3,9),(131,3,10),(132,3,11),(133,3,12),(135,3,14),(136,4,2),(137,4,3),(138,4,4),(139,4,5),(140,4,6),(141,4,7),(142,4,8),(143,4,9),(144,4,10),(207,3,15),(208,3,16),(209,3,17),(210,3,18),(211,3,19),(212,3,20),(213,3,1),(214,3,2),(215,3,3),(216,3,4),(217,3,5),(218,34,6),(219,34,7),(220,34,8),(221,34,9),(222,34,10),(223,34,11),(224,34,12),(226,34,14),(227,34,15),(228,34,16),(229,34,17),(230,34,18),(231,34,19),(232,34,20),(233,34,21),(234,34,22),(235,34,23),(236,34,24),(237,34,25),(238,34,26),(239,34,27),(297,35,15),(298,35,16),(299,35,17),(667,1,2),(668,1,3),(669,1,4),(670,1,5),(671,1,6),(672,1,7),(673,1,8),(674,1,9),(675,1,10),(676,1,28),(677,1,11),(678,1,12),(680,1,14),(681,1,15),(682,1,16),(683,1,17),(684,1,18),(685,1,19),(686,1,20),(687,1,21),(688,1,22),(689,1,23),(690,1,24),(691,1,25),(692,1,26),(693,1,27),(706,1,33),(922,47,5),(958,1,35),(959,1,36),(960,1,37),(961,1,38),(962,1,39),(966,1,34),(967,1,29),(968,1,30),(969,1,31),(970,1,32),(971,1,40),(972,1,41),(1011,1,42),(1013,47,42),(1014,1,43),(1025,46,9),(1028,462,33),(1034,46,38),(1036,46,42),(1037,462,18),(1038,462,19),(1039,46,20),(1040,46,21),(1041,46,22),(1042,46,23),(1043,46,24),(1044,46,25),(1045,46,26),(1046,46,34),(1047,46,35),(1048,46,36),(1049,46,40),(1050,46,41),(1051,1,44),(1052,46,44),(1053,47,44),(1179,2,1),(1180,2,2),(1181,2,3),(1182,2,4),(1183,2,8),(1184,2,9),(1185,2,10),(1186,2,33),(1187,2,43),(1188,2,45),(1189,2,15),(1190,2,16),(1191,2,17),(1192,2,37),(1193,2,38),(1194,2,39),(1195,2,42),(1196,2,44),(1197,2,46),(1198,2,34),(1199,2,35),(1200,2,36),(1201,2,40),(1202,2,41),(1203,53,1),(1204,53,2),(1205,53,3),(1206,53,4),(1207,53,8),(1208,53,9),(1209,53,10),(1210,53,33),(1211,53,43),(1212,53,45),(1213,53,15),(1214,53,16),(1215,53,17),(1216,53,37),(1217,53,38),(1218,53,39),(1219,53,42),(1220,53,44),(1221,53,46),(1222,53,34),(1223,53,35),(1224,53,36),(1225,53,40),(1226,53,41),(1227,1,44),(1228,1,45),(1229,1,46);

/*Table structure for table `tp51_jxs` */

DROP TABLE IF EXISTS `tp51_jxs`;

CREATE TABLE `tp51_jxs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jxsid` int(11) NOT NULL,
  `jxs` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='呜呜呜呜';

/*Data for the table `tp51_jxs` */

insert  into `tp51_jxs`(`id`,`jxsid`,`jxs`) values (1,1,'中视众影'),(2,2,'集梦传媒'),(3,3,'第三帝国'),(4,4,'第四记录'),(5,5,'第五人格'),(6,6,'六月晚风');

/*Table structure for table `tp51_mainmenu` */

DROP TABLE IF EXISTS `tp51_mainmenu`;

CREATE TABLE `tp51_mainmenu` (
  `mid` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(3) NOT NULL COMMENT '父ID',
  `title` varchar(15) NOT NULL COMMENT '名称',
  `micon` varchar(50) DEFAULT NULL COMMENT '对应icon',
  `name` varchar(20) DEFAULT NULL COMMENT '名称2',
  `path` varchar(100) DEFAULT NULL COMMENT '路径',
  `status` enum('0','1') DEFAULT '1' COMMENT '状态',
  `create_time` varchar(11) DEFAULT NULL,
  `update_time` varchar(11) DEFAULT NULL,
  `iconsize` int(3) DEFAULT NULL COMMENT 'icon尺寸',
  `label` varchar(255) DEFAULT NULL COMMENT 'label名称',
  PRIMARY KEY (`mid`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8 COMMENT='111111';

/*Data for the table `tp51_mainmenu` */

insert  into `tp51_mainmenu`(`mid`,`pid`,`title`,`micon`,`name`,`path`,`status`,`create_time`,`update_time`,`iconsize`,`label`) values (1,0,'首页','md-home','home','','1','',NULL,18,'首页'),(2,0,'基础信息','ios-briefcase','',NULL,'1',NULL,NULL,18,'基础信息'),(3,2,'管理员中心','ios-book','admin',NULL,'1',NULL,NULL,18,'管理员中心'),(4,2,'角色管理','ios-book','role_management',NULL,'1',NULL,NULL,18,'角色管理'),(8,2,'关系管理','ios-book','relation_management',NULL,'1',NULL,NULL,18,'关系管理'),(9,2,'奖金设定','ios-book','bonus',NULL,'1',NULL,NULL,18,'奖金设定'),(10,2,'提成设置','ios-book','commission',NULL,'1',NULL,NULL,18,'提成设置'),(15,0,'库存管理','ios-filing','',NULL,'1',NULL,NULL,18,'库存管理'),(16,15,'商品库存','ios-book','t1',NULL,'1',NULL,NULL,18,'商品库存'),(17,15,'库存流水','ios-book','t2',NULL,'1',NULL,NULL,18,'库存流水'),(18,0,'数据管理','md-mail','',NULL,'1',NULL,NULL,18,'数据管理'),(19,18,'数据汇总','ios-book','home',NULL,'1',NULL,NULL,18,'数据汇总'),(20,18,'数据导出','ios-book','export_excel',NULL,'1',NULL,NULL,18,'数据导出'),(21,0,'之前杂项','ios-water',NULL,NULL,'1',NULL,NULL,18,'之前杂项'),(22,21,'通知消息','ios-book','msg',NULL,'1',NULL,NULL,18,'通知消息'),(23,21,'修改密码','ios-book','password',NULL,'1',NULL,NULL,18,'修改密码'),(24,21,'用户信息','ios-book','userinfo',NULL,'1',NULL,NULL,18,'用户信息'),(25,21,'导出已选择项','ios-book','merge_header',NULL,'1',NULL,NULL,18,'导出已选择项'),(26,21,'导出多级表头','ios-book','select_excel',NULL,'1',NULL,NULL,18,'导出多级表头'),(33,2,'操作记录','ios-book','operation_record',NULL,'1',NULL,NULL,18,'操作记录'),(34,0,'支付管理','logo-usd',NULL,NULL,'1',NULL,NULL,18,'支付管理'),(35,34,'直营终端','ios-book','direct_sell',NULL,'1',NULL,NULL,18,'直营终端'),(36,34,'预算部','ios-book','budget',NULL,'1',NULL,NULL,18,'预算部'),(37,15,'厂家发货','ios-book','t3',NULL,'1',NULL,NULL,18,'厂家发货'),(38,15,'一级分销商','ios-book','t4',NULL,'1',NULL,NULL,18,'一级分销商'),(39,15,'二级分销商','ios-book','t5',NULL,'1',NULL,NULL,18,'二级分销商'),(40,34,'实销模式','ios-book','pay_3',NULL,'1',NULL,NULL,18,'实销模式'),(41,34,'分销终端','ios-book','distribution',NULL,'1',NULL,NULL,18,'分销终端'),(42,15,'商业发货','ios-book','t6',NULL,'1',NULL,NULL,18,'商业发货'),(43,2,'信息备案','ios-book','information_filing',NULL,'1',NULL,NULL,18,'信息备案'),(44,15,'流水流向','ios-book','t7',NULL,'1',NULL,NULL,18,'流水流向'),(45,2,'信息管理','ios-book','information_manage',NULL,'1',NULL,NULL,18,'基础信息'),(46,15,'商品库存(改)','ios-book','t8',NULL,'1',NULL,NULL,18,'商品库存(改)');

/*Table structure for table `tp51_managerid` */

DROP TABLE IF EXISTS `tp51_managerid`;

CREATE TABLE `tp51_managerid` (
  `jid` int(11) NOT NULL AUTO_INCREMENT,
  `managerid` int(11) DEFAULT NULL,
  `jxsid` int(11) DEFAULT NULL,
  PRIMARY KEY (`jid`)
) ENGINE=MyISAM AUTO_INCREMENT=236 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `tp51_managerid` */

insert  into `tp51_managerid`(`jid`,`managerid`,`jxsid`) values (231,307,5),(230,307,3),(229,307,1),(228,307,2),(200,296,4),(227,306,2),(202,296,6),(223,303,3),(226,306,1),(205,297,4),(206,297,5),(207,297,6),(208,298,1),(209,298,2),(210,298,3),(211,299,1),(212,299,2),(213,300,1),(214,301,5),(215,301,6),(216,302,1),(217,302,2),(218,302,3),(219,302,4),(220,302,5),(221,302,6),(232,307,4),(233,308,1),(234,308,2),(235,309,6);

/*Table structure for table `tp51_med_reserve` */

DROP TABLE IF EXISTS `tp51_med_reserve`;

CREATE TABLE `tp51_med_reserve` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fl_id` int(11) DEFAULT NULL COMMENT '流水表ID',
  `company_name` varchar(50) DEFAULT NULL COMMENT '商业名称',
  `names` varchar(50) DEFAULT NULL COMMENT '产品名称',
  `fac_specs` varchar(50) DEFAULT NULL COMMENT '规格',
  `measuring_unit` varchar(20) DEFAULT NULL COMMENT '计量单位',
  `origin` varchar(50) DEFAULT NULL COMMENT '产地',
  `stock_num` int(11) DEFAULT NULL COMMENT '库存数量',
  `batch_num` varchar(30) DEFAULT NULL COMMENT '批号',
  `create_time` varchar(12) DEFAULT NULL,
  `update_time` varchar(12) DEFAULT NULL,
  `operation_time` datetime DEFAULT NULL COMMENT '操作时间',
  `ssid` enum('0','1') DEFAULT NULL COMMENT '等级',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `tp51_med_reserve` */

insert  into `tp51_med_reserve`(`id`,`fl_id`,`company_name`,`names`,`fac_specs`,`measuring_unit`,`origin`,`stock_num`,`batch_num`,`create_time`,`update_time`,`operation_time`,`ssid`) values (1,126,'国药乐仁堂医药有限公司','斑蝥酸钠维生素B6注射液','5ml:0.05mg','支','贵州柏强制药有限公司',NULL,'20210608','','',NULL,NULL),(2,127,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(3,128,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(4,129,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);

/*Table structure for table `tp51_option` */

DROP TABLE IF EXISTS `tp51_option`;

CREATE TABLE `tp51_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `business_unit_1` varchar(50) DEFAULT NULL COMMENT '商业单位',
  `fac_name_1` varchar(50) DEFAULT NULL COMMENT '产品',
  `fac_specs_1` varchar(30) DEFAULT NULL COMMENT '规格',
  `factory_name_1` varchar(50) DEFAULT NULL COMMENT '厂家名称',
  `mark` enum('0','1') DEFAULT NULL COMMENT '标识符',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8;

/*Data for the table `tp51_option` */

insert  into `tp51_option`(`id`,`business_unit_1`,`fac_name_1`,`fac_specs_1`,`factory_name_1`,`mark`) values (1,'河北金天燕霄医药有限公司',NULL,NULL,NULL,'0'),(2,'必康润祥医药河北有限公司',NULL,NULL,NULL,'0'),(3,'河北龙海新药经营有限公司',NULL,NULL,NULL,'0'),(4,'国药乐仁堂医药有限公司',NULL,NULL,NULL,'0'),(5,'华润河北医药有限公司',NULL,NULL,NULL,'0'),(6,'石家庄瑞盟医药经营有限公司',NULL,NULL,NULL,'0'),(7,'石药集团河北中诚医药有限公司',NULL,NULL,NULL,'0'),(8,'河北科威药业有限公司',NULL,NULL,NULL,'0'),(9,'河北谛康医药有限公司',NULL,NULL,NULL,'0'),(10,NULL,'百草妇炎清栓','3粒','贵州双升制药有限公司','1'),(11,NULL,'斑蝥酸钠维生素B6注射液','5ml','贵州柏强制药有限公司','1'),(12,NULL,'斑蝥酸钠维生素B6注射液','10ml','贵州柏强制药有限公司','1'),(13,NULL,'儿童回春颗粒','6袋','贵州汉方药业有限公司','1'),(14,NULL,'儿童七珍丸','80丸','贵阳德昌祥药业有限公司','1'),(15,NULL,'枫蓼肠胃康合剂','18ml*2支','海南万州绿色制药有限公司','1'),(16,NULL,'妇科再造胶囊','40s','贵州汉方药业有限公司','1'),(17,NULL,'妇科再造丸','60w','贵阳德昌祥药业有限公司','1'),(18,NULL,'妇科再造丸','80w','贵阳德昌祥药业有限公司','1'),(19,NULL,'骨康胶囊','48s','贵州维康子帆药业股份有限公司','1'),(20,NULL,'骨康胶囊','60s','贵州维康子帆药业股份有限公司','1'),(21,NULL,'骨康胶囊','36s','贵州维康子帆药业股份有限公司','1'),(22,NULL,'健脑胶囊','30s','贵州远程制药有限责任公司','1'),(23,NULL,'筋骨伤喷雾剂','25ml','贵州远程制药有限责任公司','1'),(24,NULL,'筋骨伤喷雾剂','50ml','贵州远程制药有限责任公司','1'),(25,NULL,'筋骨伤喷雾剂','100ml','贵州远程制药有限责任公司','1'),(26,NULL,'开喉健喷雾剂','儿童15ml','贵州三力制药股份有限公司','1'),(27,NULL,'开喉健喷雾剂','儿童30ml','贵州三力制药股份有限公司','1'),(28,NULL,'开喉健喷雾剂','成人30ml','贵州三力制药股份有限公司','1'),(29,NULL,'开喉健喷雾剂','儿童20ml','贵州三力制药股份有限公司','1'),(30,NULL,'开喉健喷雾剂','成人20ml','贵州三力制药股份有限公司','1'),(31,NULL,'抗妇炎胶囊','26s','贵州远程制药有限责任公司','1'),(32,NULL,'抗妇炎胶囊','36s','贵州远程制药有限责任公司','1'),(33,NULL,'抗妇炎胶囊','48s','贵州远程制药有限责任公司','1'),(34,NULL,'癃清胶囊','24s','贵州远程制药有限责任公司','1'),(35,NULL,'癃清胶囊','48s','贵州远程制药有限责任公司','1'),(36,NULL,'癃清胶囊','36s','贵州远程制药有限责任公司','1'),(37,NULL,'芪胶升白胶囊','36s','贵州汉方药业有限公司','1'),(38,NULL,'强力天麻杜仲胶囊','48s','贵州三力制药股份有限公司','1'),(39,NULL,'舒眠胶囊','36s','贵州大隆药业有限责任公司','1'),(40,NULL,'舒眠胶囊','24s','贵州大隆药业有限责任公司','1'),(41,NULL,'舒眠胶囊','48s','贵州大隆药业有限责任公司','1'),(42,NULL,'醒脑再造胶囊','72s','贵州远程制药有限责任公司','1'),(43,NULL,'盐酸颁布特罗口服溶液','18ml*4支装','岳阳新华达制药有限公司','1'),(44,NULL,'盐酸颁布特罗口服溶液','18ml*2支装','岳阳新华达制药有限公司','1'),(45,NULL,'银丹心泰滴丸','100w','贵州君之堂制药有限公司','1'),(46,NULL,'银丹心泰滴丸','200w','贵州君之堂制药有限公司','1'),(47,NULL,'复方红豆杉胶囊','18s','重庆赛诺生物药业股份有限公司','1');

/*Table structure for table `tp51_out` */

DROP TABLE IF EXISTS `tp51_out`;

CREATE TABLE `tp51_out` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zhongduanmingcheng` varchar(255) DEFAULT NULL,
  `zhongduanmingcheng2` varchar(255) DEFAULT NULL,
  `yiyuanjibie` varchar(255) DEFAULT NULL,
  `yewuyuan` varchar(255) DEFAULT NULL,
  `zhuguan` varchar(255) DEFAULT NULL,
  `xiaoshouzhengce` varchar(255) DEFAULT NULL,
  `bumen` varchar(255) DEFAULT NULL,
  `pinming` varchar(255) DEFAULT NULL,
  `guige` varchar(255) DEFAULT NULL,
  `chandi` varchar(255) DEFAULT NULL,
  `renwu` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2035 DEFAULT CHARSET=utf8;

/*Data for the table `tp51_out` */

insert  into `tp51_out`(`id`,`zhongduanmingcheng`,`zhongduanmingcheng2`,`yiyuanjibie`,`yewuyuan`,`zhuguan`,`xiaoshouzhengce`,`bumen`,`pinming`,`guige`,`chandi`,`renwu`,`status`) values (1998,'卓达太阳城','暂无','一级','刘彩虹','宋会军','待定','市区 预算部（社区）','斑蝥酸钠维生素B6注射液','5ml','贵州柏强制药有限公司','待定','正常在职'),(1999,'国药乐仁堂医药有限公司','暂无','一级','李白','李XX','待定','市区 预算部（社区）','','6ml','贵州柏强制药有限公司','待定','正常在职'),(2000,'国药乐仁堂沧州医药有限公司','暂无',NULL,'杜甫','杨YY','待定','市区 预算部（社区）','斑蝥酸钠维生素B8注射液','7ml','贵州柏强制药有限公司','待定','正常在职'),(2001,'国药乐仁堂衡水医药有限公司','暂无',NULL,'杨万里','宋会军','待定','市区 预算部（社区）','复方红豆杉胶囊','8ml','贵州柏强制药有限公司','待定','正常在职'),(2002,'国药乐仁堂石家庄医药有限公司','暂无',NULL,'苏轼','杨YY','待定','市区 预算部（社区）','复方红豆杉胶囊','9ml','贵州柏强制药有限公司','待定','正常在职'),(2003,'国药乐仁堂德州医药有限公司','暂无',NULL,'阮籍','宋会军','待定','市区 预算部（社区）','斑蝥酸钠维生素B6注射液','10ml','贵州柏强制药有限公司','待定','正常在职'),(2004,'国药乐仁堂医药有限公司','暂无','二级','孔子','李XX','待定','市区 预算部（社区）','儿童七珍丸','11ml','贵州柏强制药有限公司','待定','正常在职'),(2005,'卓达太阳城','暂无','二级','张三','杨YY','待定','市区 预算部（社区）','枫蓼肠胃康合剂','12ml','贵州柏强制药有限公司','待定','正常在职'),(2006,'卓达太阳城','暂无','三级','李四','宋会军','待定','市区 预算部（社区）','儿童回春颗粒','13ml','贵州柏强制药有限公司','待定','正常在职'),(2007,'国药乐仁堂医药有限公司','暂无','三级','王五','杨YY','待定','市区 预算部（社区）','复方红豆杉胶囊','14ml','贵州柏强制药有限公司','待定','正常在职'),(2008,'卓达太阳城','暂无','三级','李六','李XX','待定','市区 预算部（社区）','斑蝥酸钠维生素B16注射液','15ml','贵州柏强制药有限公司','待定','正常在职'),(2009,'国药乐仁堂医药有限公司','暂无','三级','七上','杨YY','待定','市区 预算部（社区）','消炎止咳片','16ml','贵州柏强制药有限公司','待定','正常在职'),(2010,'卓达太阳城','暂无','三级','张三','宋会军','待定','市区 预算部（社区）','斑蝥酸钠维生素B6注射液','17ml','贵州柏强制药有限公司','待定','正常在职'),(2011,'卓达太阳城','暂无','a','刘彩虹','宋会军','待定','市区 预算部（社区）','开喉健喷雾剂','儿童20ml','贵州三力','待定','正常在职'),(2012,'乐仁堂总店','暂无','a','王恒','宋会军','待定','市区 预算部（otc）','开喉健喷雾剂','儿童20ml','贵州三力','待定','正常在职'),(2013,'省人民','暂无','a','孙云','刘芳','待定','市区 预算部 （医院）','骨康 胶囊','48s','贵州维康','待定','正常在职');

/*Table structure for table `tp51_psi` */

DROP TABLE IF EXISTS `tp51_psi`;

CREATE TABLE `tp51_psi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) DEFAULT NULL COMMENT '商品id',
  `name` varchar(33) DEFAULT NULL COMMENT '防止删除无数据的goodsname',
  `specs` varchar(30) DEFAULT NULL COMMENT '防止删除无数据的specs',
  `psi_oneid` int(11) DEFAULT NULL COMMENT '一级id',
  `psi_twoid` int(11) DEFAULT NULL COMMENT '二级id',
  `company` varchar(30) DEFAULT NULL COMMENT '商业单位',
  `goods_num` int(11) DEFAULT NULL COMMENT '配送商发货数量',
  `agent` varchar(20) DEFAULT NULL COMMENT '代理商',
  `goods_num2` int(11) DEFAULT NULL COMMENT '代理商发货数量',
  `remarks` varchar(500) DEFAULT NULL COMMENT '备注',
  `create_time` varchar(30) DEFAULT NULL,
  `thismonth` int(11) DEFAULT NULL COMMENT '本月发货',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=364 DEFAULT CHARSET=utf8;

/*Data for the table `tp51_psi` */

insert  into `tp51_psi`(`id`,`goods_id`,`name`,`specs`,`psi_oneid`,`psi_twoid`,`company`,`goods_num`,`agent`,`goods_num2`,`remarks`,`create_time`,`thismonth`) values (1,889,'感冒灵颗粒','24*1',1,NULL,'一级商业单位a',500,'暂无数据',0,'暂无备注',NULL,1000),(2,890,'健胃消食片','8*2',NULL,2,'暂无数据',0,'健胃消食代理商',200,'暂无备注',NULL,NULL),(359,1494,'导入测试1','12 * 2 ',NULL,NULL,'商业单位1',1900,'代理商1',463,'暂无备注','1637811374',2363),(360,1495,'导入测试2','13 * 2 ',NULL,NULL,'商业单位2',1901,'代理商2',464,'暂无备注','1637811374',2364),(361,1496,'导入测试3','14 * 2 ',NULL,NULL,'商业单位3',1902,'代理商3',465,'暂无备注','1637811374',2365),(362,1497,'导入测试4','15 * 2 ',NULL,NULL,'商业单位4',1903,'代理商4',466,'暂无备注','1637811374',2366),(363,1498,'导入测试5','16 * 2 ',NULL,NULL,'商业单位5',1904,'代理商5',467,'暂无备注','1637811374',2367);

/*Table structure for table `tp51_psi_one` */

DROP TABLE IF EXISTS `tp51_psi_one`;

CREATE TABLE `tp51_psi_one` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL COMMENT '防止级联删除无数据的name',
  `specs` varchar(30) DEFAULT NULL COMMENT '防止级联删除无数据的specs',
  `psi_id` int(11) DEFAULT NULL COMMENT 'psi表id',
  `psi_twoid` int(11) DEFAULT NULL COMMENT '二级id',
  `class_one` varchar(30) DEFAULT NULL COMMENT '一级配送商',
  `upper_cun` varchar(20) DEFAULT NULL COMMENT '上月库存',
  `month_cun` varchar(20) DEFAULT NULL COMMENT '本月库存',
  `terminal_name` varchar(30) DEFAULT NULL COMMENT '终端名称',
  `sales_num` varchar(20) DEFAULT NULL COMMENT '销售数量',
  `person` varchar(20) DEFAULT NULL COMMENT '负责人',
  `company` varchar(50) DEFAULT NULL COMMENT '商业单位',
  `transfer` varchar(30) DEFAULT NULL COMMENT '调货数量',
  `total` varchar(30) DEFAULT NULL COMMENT '本月销合',
  `on_stock` varchar(30) DEFAULT NULL COMMENT '本月库存',
  `remarks` varchar(500) DEFAULT NULL COMMENT '备注',
  `create_time` varchar(30) DEFAULT NULL COMMENT '创建时间',
  `month_time` varchar(30) DEFAULT NULL COMMENT '每月时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8;

/*Data for the table `tp51_psi_one` */

insert  into `tp51_psi_one`(`id`,`goods_id`,`name`,`specs`,`psi_id`,`psi_twoid`,`class_one`,`upper_cun`,`month_cun`,`terminal_name`,`sales_num`,`person`,`company`,`transfer`,`total`,`on_stock`,`remarks`,`create_time`,`month_time`) values (1,889,'感冒灵颗粒','24*1',1,1,'一级商业单位a','0','500','暂无数据','0','无','二级A','300','300','200',NULL,NULL,NULL),(47,1523,'第三个是二级商的发货','null',NULL,NULL,'打扫','打扫','打扫','','','','','','','','','1637892391',NULL),(48,1523,'第三个是二级商的发货','null',NULL,NULL,'','','','','','','','','','','','1637892398',NULL),(49,1523,'第三个是二级商的发货','null',NULL,NULL,'','','','','','','','','','','','1637892401',NULL),(50,1523,'第三个是二级商的发货','null',NULL,NULL,'','','','','','','','','','','','1637892404',NULL),(51,1523,'第三个是二级商的发货','null',NULL,NULL,'','','','','','','','','','','','1637892408',NULL),(52,1523,'第三个是二级商的发货','null',NULL,NULL,'','','','','','','','','','','','1637892411',NULL),(53,1523,'第三个是二级商的发货','null',NULL,NULL,'','','','','','','','','','','','1637892415',NULL),(54,1523,'第三个是二级商的发货','null',NULL,NULL,'','','','','','','','','','','','1637892419',NULL),(55,1523,'第三个是二级商的发货','null',NULL,NULL,'','','','','','','','','','','','1637892424',NULL),(56,1523,'第三个是二级商的发货','null',NULL,NULL,'大时代','大时代','打扫','大时代','的阿斯顿',' 俺打算d','打扫',' 打扫',' 打扫','阿斯顿',' 打扫','1637892429',NULL);

/*Table structure for table `tp51_psi_two` */

DROP TABLE IF EXISTS `tp51_psi_two`;

CREATE TABLE `tp51_psi_two` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `distrib_two` varchar(50) DEFAULT NULL COMMENT '二级配送商业',
  `upper_level` varchar(30) DEFAULT NULL COMMENT '货品上级来源',
  `goods_id` int(11) DEFAULT NULL,
  `name` varchar(30) DEFAULT NULL COMMENT '防止级联删除无数据的goodsname',
  `specs` varchar(30) DEFAULT NULL COMMENT '防止级联删除无数据的specs',
  `last_stock` varchar(30) DEFAULT NULL COMMENT '上月库存',
  `this_purchase` varchar(30) DEFAULT NULL COMMENT '本月进货',
  `terminal_name` varchar(50) DEFAULT NULL COMMENT '终端名称',
  `sale_num` varchar(30) DEFAULT NULL COMMENT '销售数量',
  `responsibler` varchar(20) DEFAULT NULL COMMENT '负责人',
  `sale_total` varchar(20) DEFAULT NULL COMMENT '本月销售合计',
  `this_stock` varchar(30) DEFAULT NULL COMMENT '本月库存',
  `remark` varchar(500) DEFAULT NULL COMMENT '备注',
  `create_time` varchar(30) DEFAULT NULL,
  `psi_oneid` int(11) DEFAULT NULL COMMENT '一级Id',
  `psi_id` int(11) DEFAULT NULL COMMENT '厂家发货ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;

/*Data for the table `tp51_psi_two` */

insert  into `tp51_psi_two`(`id`,`distrib_two`,`upper_level`,`goods_id`,`name`,`specs`,`last_stock`,`this_purchase`,`terminal_name`,`sale_num`,`responsibler`,`sale_total`,`this_stock`,`remark`,`create_time`,`psi_oneid`,`psi_id`) values (1,'二级A','一级商业单位a',889,'感冒灵颗粒','24*1','0','300','省二院','200','小明','200','100','暂无备注',NULL,NULL,NULL),(2,'健胃消食代理商','总分销商',890,'健胃消食片','8*2','0','200','暂无数据','恶趣味','去','恶趣味','恶趣味','恶趣味',NULL,NULL,NULL);

/*Table structure for table `tp51_renwu` */

DROP TABLE IF EXISTS `tp51_renwu`;

CREATE TABLE `tp51_renwu` (
  `djid` int(11) NOT NULL AUTO_INCREMENT,
  `dengjiname` varchar(255) NOT NULL,
  `renwu` int(33) NOT NULL,
  PRIMARY KEY (`djid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8;

/*Data for the table `tp51_renwu` */

insert  into `tp51_renwu`(`djid`,`dengjiname`,`renwu`) values (1,'初级业务员',1000),(4,'中级业务员',3500),(7,'高级业务员',10000);

/*Table structure for table `tp51_role` */

DROP TABLE IF EXISTS `tp51_role`;

CREATE TABLE `tp51_role` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '角色ID',
  `role_name` varchar(20) NOT NULL COMMENT '角色名称',
  `status` enum('0','1') DEFAULT NULL COMMENT '状态',
  PRIMARY KEY (`rid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `tp51_role` */

insert  into `tp51_role`(`rid`,`role_name`,`status`) values (1,'超级管理员','1'),(2,'一级管理员','1'),(3,'二级管理员','1');

/*Table structure for table `tp51_role_api` */

DROP TABLE IF EXISTS `tp51_role_api`;

CREATE TABLE `tp51_role_api` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL,
  `ap_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `tp51_role_api` */

insert  into `tp51_role_api`(`id`,`rid`,`ap_id`) values (1,1,1),(2,1,9),(3,1,10);

/*Table structure for table `tp51_role_menu` */

DROP TABLE IF EXISTS `tp51_role_menu`;

CREATE TABLE `tp51_role_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL,
  `mid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

/*Data for the table `tp51_role_menu` */

insert  into `tp51_role_menu`(`id`,`rid`,`mid`) values (1,1,9),(2,1,10),(3,1,11),(4,1,12),(5,1,13),(6,1,14),(7,1,18),(8,1,19),(9,1,20),(10,1,21),(11,1,22),(12,1,23),(13,1,24),(14,1,25),(15,2,11),(16,2,12),(17,2,13),(18,2,14),(19,3,18),(20,3,19),(21,3,20);

/*Table structure for table `tp51_shixiaoyiyuan` */

DROP TABLE IF EXISTS `tp51_shixiaoyiyuan`;

CREATE TABLE `tp51_shixiaoyiyuan` (
  `id` int(11) NOT NULL,
  `yuefen` varchar(255) DEFAULT NULL,
  `diqumingcheng` varchar(255) DEFAULT NULL,
  `xiaoshoumoshi` varchar(255) DEFAULT NULL,
  `bumenjingli` varchar(255) DEFAULT NULL,
  `yewuyuan` varchar(255) DEFAULT NULL,
  `yiyuanmingcheng` varchar(255) DEFAULT NULL,
  `gonghuodanwei` varchar(255) DEFAULT NULL,
  `pinming` varchar(255) DEFAULT NULL,
  `guige` varchar(255) DEFAULT NULL,
  `shangyueyushu` varchar(255) DEFAULT NULL,
  `benyuejinhuo` varchar(255) DEFAULT NULL,
  `benyueyushu` varchar(255) DEFAULT NULL,
  `benyuexiaoshou` varchar(255) DEFAULT NULL,
  `abbiaozhunshuihou` varchar(255) DEFAULT NULL,
  `abjine` varchar(255) DEFAULT NULL,
  `lunwenfei` varchar(255) DEFAULT NULL,
  `jine` varchar(255) DEFAULT NULL,
  `jinglijiangjindanjia` varchar(255) DEFAULT NULL,
  `jinglijiangjin` varchar(255) DEFAULT NULL,
  `daibiaojiangjinticheng` varchar(255) DEFAULT NULL,
  `daibiaojiangjin` varchar(255) DEFAULT NULL,
  `baozhengjin` varchar(255) DEFAULT NULL,
  `shifujine` varchar(255) DEFAULT NULL,
  `shangyegonghuojia` varchar(255) DEFAULT NULL,
  `wanchengjine` varchar(255) DEFAULT NULL,
  `renwu` varchar(255) DEFAULT NULL,
  `wanchenglv` varchar(255) DEFAULT NULL,
  `chaochajine` varchar(255) DEFAULT NULL,
  `jiangfa` varchar(255) DEFAULT NULL,
  `shizhijine` varchar(255) DEFAULT NULL,
  `beizhu` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tp51_shixiaoyiyuan` */

insert  into `tp51_shixiaoyiyuan`(`id`,`yuefen`,`diqumingcheng`,`xiaoshoumoshi`,`bumenjingli`,`yewuyuan`,`yiyuanmingcheng`,`gonghuodanwei`,`pinming`,`guige`,`shangyueyushu`,`benyuejinhuo`,`benyueyushu`,`benyuexiaoshou`,`abbiaozhunshuihou`,`abjine`,`lunwenfei`,`jine`,`jinglijiangjindanjia`,`jinglijiangjin`,`daibiaojiangjinticheng`,`daibiaojiangjin`,`baozhengjin`,`shifujine`,`shangyegonghuojia`,`wanchengjine`,`renwu`,`wanchenglv`,`chaochajine`,`jiangfa`,`shizhijine`,`beizhu`) values (1,'8','石家庄','线下','吕欢杰','吕欢杰','石家庄第二医院','北京六合','喷雾剂','50ml','100','200','300','300','5','3','20','0','100','100','2','200','50','100','100','300','300','100','50','10','300','1');

/*Table structure for table `tp51_stock_direction` */

DROP TABLE IF EXISTS `tp51_stock_direction`;

CREATE TABLE `tp51_stock_direction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `com_company` varchar(30) DEFAULT NULL COMMENT '商业公司',
  `goods_id` varchar(30) DEFAULT NULL COMMENT '商品id',
  `name` varchar(30) DEFAULT NULL COMMENT '防止删除无数据的name',
  `specs` varchar(20) DEFAULT NULL COMMENT '防止删除无数据的specs',
  `in_num` varchar(20) DEFAULT NULL COMMENT '数量',
  `goods_price` varchar(20) DEFAULT NULL COMMENT '单价',
  `customera` varchar(20) DEFAULT NULL COMMENT '客户名称A',
  `customerb` varchar(20) DEFAULT NULL COMMENT '客户名称B',
  `supplier` varchar(20) DEFAULT NULL COMMENT '供应商',
  `local_company` varchar(30) DEFAULT NULL COMMENT '地市公司名称',
  `origin_place` varchar(30) DEFAULT NULL COMMENT '产地',
  `create_time` varchar(12) DEFAULT NULL COMMENT '操作日期',
  `unit` varchar(20) DEFAULT NULL COMMENT '计量单位',
  `lotnumber` varchar(30) DEFAULT NULL COMMENT '批号',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5131 DEFAULT CHARSET=utf8;

/*Data for the table `tp51_stock_direction` */

insert  into `tp51_stock_direction`(`id`,`com_company`,`goods_id`,`name`,`specs`,`in_num`,`goods_price`,`customera`,`customerb`,`supplier`,`local_company`,`origin_place`,`create_time`,`unit`,`lotnumber`) values (5129,'商业公司名称',NULL,'药品名称','药品规格','数量','单价','用户名称a','用户名称b','供应商','地市公司名称','产地','1637812696','计量单位','批号'),(5130,'商业公司名称',NULL,'药品名称','药品规格','数量','单价','用户名称a','用户名称b','供应商','地市公司名称','产地','1637812822','计量单位','批号');

/*Table structure for table `tp51_unit` */

DROP TABLE IF EXISTS `tp51_unit`;

CREATE TABLE `tp51_unit` (
  `gid` int(11) NOT NULL AUTO_INCREMENT,
  `unit` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`gid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `tp51_unit` */

insert  into `tp51_unit`(`gid`,`unit`) values (1,'瓶'),(2,'盒'),(3,'盒');

/*Table structure for table `tp51_yusuanbu` */

DROP TABLE IF EXISTS `tp51_yusuanbu`;

CREATE TABLE `tp51_yusuanbu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `yuefen` varchar(255) DEFAULT NULL,
  `diqumingcheng` varchar(255) DEFAULT NULL,
  `bumenjingli` varchar(255) DEFAULT NULL,
  `yewuyuan` varchar(255) DEFAULT NULL,
  `yiyuanmingcheng` varchar(255) DEFAULT NULL,
  `zhongduanjibie` varchar(255) DEFAULT NULL,
  `gonghuodanwei` varchar(255) DEFAULT NULL,
  `pinming` varchar(255) DEFAULT NULL,
  `guige` varchar(255) DEFAULT NULL,
  `shangyueyushu` varchar(255) DEFAULT NULL,
  `benyuejinhuo` varchar(255) DEFAULT NULL,
  `benyuexiaoshou` varchar(255) DEFAULT NULL,
  `benyueyushu` varchar(255) DEFAULT NULL,
  `lunwenfei` varchar(255) DEFAULT NULL,
  `jine` varchar(255) DEFAULT NULL,
  `shangyegonghuojia` varchar(255) DEFAULT NULL,
  `wanchengjine` varchar(255) DEFAULT NULL,
  `renwu` varchar(255) DEFAULT NULL,
  `wanchenglv` varchar(255) DEFAULT NULL,
  `jinglijiangjindanjia` varchar(255) DEFAULT NULL,
  `jinglijiangjin` varchar(255) DEFAULT NULL,
  `daibiaojiangjinticheng` varchar(255) DEFAULT NULL,
  `daibiaojiangjin` varchar(255) DEFAULT NULL,
  `beizhu` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `tp51_yusuanbu` */

insert  into `tp51_yusuanbu`(`id`,`yuefen`,`diqumingcheng`,`bumenjingli`,`yewuyuan`,`yiyuanmingcheng`,`zhongduanjibie`,`gonghuodanwei`,`pinming`,`guige`,`shangyueyushu`,`benyuejinhuo`,`benyuexiaoshou`,`benyueyushu`,`lunwenfei`,`jine`,`shangyegonghuojia`,`wanchengjine`,`renwu`,`wanchenglv`,`jinglijiangjindanjia`,`jinglijiangjin`,`daibiaojiangjinticheng`,`daibiaojiangjin`,`beizhu`) values (1,NULL,'北京市-东城区','吕欢杰','测试地区显不显示','吕欢杰直属医院','一级','南京','喷雾剂','100ml','200','1000','800','200','50','100','8','7000','200','100','8','800','6','600','');

/*Table structure for table `tp51_zhiyingerji` */

DROP TABLE IF EXISTS `tp51_zhiyingerji`;

CREATE TABLE `tp51_zhiyingerji` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `yuefen` varchar(255) DEFAULT NULL,
  `erjishangye` varchar(255) DEFAULT NULL,
  `chanpin` varchar(255) DEFAULT NULL,
  `guige` varchar(255) DEFAULT NULL,
  `shangyuekucun` varchar(255) DEFAULT NULL,
  `benyuejinhuo` varchar(255) DEFAULT NULL,
  `zhongduanmingcheng` varchar(255) DEFAULT NULL,
  `xiaoshoushuliang` varchar(255) DEFAULT NULL,
  `fuzeren` varchar(255) DEFAULT NULL,
  `benyuexiaoshouheji` varchar(255) DEFAULT NULL,
  `benyuekucun` varchar(255) DEFAULT NULL,
  `beizhu` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tp51_zhiyingerji` */

/*Table structure for table `tp51_zhiyingyiji` */

DROP TABLE IF EXISTS `tp51_zhiyingyiji`;

CREATE TABLE `tp51_zhiyingyiji` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `yuefen` varchar(255) DEFAULT NULL,
  `yijishangye` varchar(255) DEFAULT NULL,
  `chanpin` varchar(255) DEFAULT NULL,
  `guige` varchar(255) DEFAULT NULL,
  `shangyuekucun` varchar(255) DEFAULT NULL,
  `benyuejinhuo` varchar(255) DEFAULT NULL,
  `zhongduanmingcheng` varchar(255) DEFAULT NULL,
  `xiaoshoushuliang` varchar(255) DEFAULT NULL,
  `fuzeren` varchar(255) DEFAULT NULL,
  `shangyedanwei` varchar(255) DEFAULT NULL,
  `diaohuoshuliang` varchar(255) DEFAULT NULL,
  `benyuexiaoshou` varchar(255) DEFAULT NULL,
  `benyuekucun` varchar(255) DEFAULT NULL,
  `beizhu` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tp51_zhiyingyiji` */

/*Table structure for table `tp51_zhiyingzhongduan` */

DROP TABLE IF EXISTS `tp51_zhiyingzhongduan`;

CREATE TABLE `tp51_zhiyingzhongduan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `yuefen` varchar(255) DEFAULT NULL,
  `diquming` varchar(255) DEFAULT NULL,
  `xiaoshoumoshi` varchar(255) DEFAULT NULL,
  `yewuyuan` varchar(255) DEFAULT NULL,
  `yiyuanmingcheng` varchar(255) DEFAULT NULL,
  `zhongduanjibie` varchar(255) DEFAULT NULL,
  `gonghuodanwei` varchar(255) DEFAULT NULL,
  `pinming` varchar(255) DEFAULT NULL,
  `guige` varchar(255) DEFAULT NULL,
  `shangyueyushu` varchar(255) DEFAULT NULL,
  `benyuejinhuo` varchar(255) DEFAULT NULL,
  `benyuexiaoshou` varchar(255) DEFAULT NULL,
  `benyueyushu` varchar(255) DEFAULT NULL,
  `abbiaozhunshuihou` varchar(255) DEFAULT NULL,
  `abjine` varchar(255) DEFAULT NULL,
  `baozhengjin` varchar(255) DEFAULT NULL,
  `shifujine` varchar(255) DEFAULT NULL,
  `shangyegonghuojia` varchar(255) DEFAULT NULL,
  `wanchengjine` varchar(255) DEFAULT NULL,
  `renwu` varchar(255) DEFAULT NULL,
  `wanchenglv` varchar(255) DEFAULT NULL,
  `chaochajine` varchar(255) DEFAULT NULL,
  `jiangfa` varchar(255) DEFAULT NULL,
  `shizhijine` varchar(255) DEFAULT NULL,
  `beizhu` varchar(255) DEFAULT NULL,
  `beizhu1` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=107 DEFAULT CHARSET=utf8;

/*Data for the table `tp51_zhiyingzhongduan` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
