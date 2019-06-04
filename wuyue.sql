/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50553
Source Host           : 127.0.0.1:3306
Source Database       : wuyue

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2019-03-11 09:18:34
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for ec_admin
-- ----------------------------
DROP TABLE IF EXISTS `ec_admin`;
CREATE TABLE `ec_admin` (
  `admin_id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `admin_name` varchar(32) NOT NULL,
  `email` varchar(32) NOT NULL,
  `pwd` varchar(32) NOT NULL,
  `ip` char(15) NOT NULL,
  `addtime` int(10) NOT NULL,
  `lasttime` int(10) NOT NULL,
  `salt` varchar(20) NOT NULL,
  PRIMARY KEY (`admin_id`),
  UNIQUE KEY `admin_name` (`admin_name`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ec_admin
-- ----------------------------
INSERT INTO `ec_admin` VALUES ('1', 'admin1', 'admin@qq.com', '03740518aab51340bfcda78397c0011a', '127.0.0.1', '1550749573', '1550749573', 'rMka7g');
INSERT INTO `ec_admin` VALUES ('2', 'admin2', 'admin@qq.com', '5f50964d5840f49db172dd4308f69c62', '127.0.0.1', '1550751730', '1550751730', 'h4Q$Lb');
INSERT INTO `ec_admin` VALUES ('3', 'admin3', '280654805@qq.com', '1bf0c95c3b37ff6c1f2886fa366fccd5', '127.0.0.1', '1550815552', '1550815552', '^LVQUF');
INSERT INTO `ec_admin` VALUES ('4', 'admin4', 'admin@qq.com', 'c04aeb6fa16c0562dcd93f7b1196c79a', '127.0.0.1', '1550815698', '1550815698', 'qzN6X9');
INSERT INTO `ec_admin` VALUES ('14', '杨绍峰1', '280654805@qq.com', '997ca7768017abceaeae8952a1ce95f4', '127.0.0.1', '1551275491', '1551275491', 's&OzlE');
INSERT INTO `ec_admin` VALUES ('15', 'admin', '280654805@qq.com', '50545b883a55d70c9f6a1337ae79c42b', '127.0.0.1', '1551313777', '1551313777', 'nVQ2GW');

-- ----------------------------
-- Table structure for ec_brand
-- ----------------------------
DROP TABLE IF EXISTS `ec_brand`;
CREATE TABLE `ec_brand` (
  `brand_id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `brand_name` varchar(50) NOT NULL,
  `brand_url` varchar(100) NOT NULL,
  `brand_logo` varchar(100) DEFAULT NULL,
  `brand_desc` varchar(200) DEFAULT NULL,
  `is_show` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 是 0 否',
  PRIMARY KEY (`brand_id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ec_brand
-- ----------------------------
INSERT INTO `ec_brand` VALUES ('3', '苹果', 'http://www.pingguo.com', '20190226\\10b75af4eb3b3f92e9517e472feb2bb5.jpg', '明天星期四', '1');
INSERT INTO `ec_brand` VALUES ('2', '小米', 'http://www.xiaomi.com', '20190226\\c4bf27376e66be4b240999470d26ae38.jpg', '明天星期三', '0');
INSERT INTO `ec_brand` VALUES ('1', 'oppo', 'http://www.oppo.com', '20190226\\34f41edaff8b5ac7a461d81f2ecc4811.jpg', '星期二', '1');
INSERT INTO `ec_brand` VALUES ('8', '华为', 'http://www.baidu.com', '20190226\\34f41edaff8b5ac7a461d81f2ecc4811.jpg', '信心形影', '1');
INSERT INTO `ec_brand` VALUES ('9', 'oppo', 'http://www.oppo.com', '20190226\\34f41edaff8b5ac7a461d81f2ecc4811.jpg', '星期二', '1');
INSERT INTO `ec_brand` VALUES ('7', '苹果', 'http://www.pingguo.com', '20190226\\10b75af4eb3b3f92e9517e472feb2bb5.jpg', '明天星期四', '1');
INSERT INTO `ec_brand` VALUES ('6', '小米', 'http://www.xiaomi.com', '20190226\\c4bf27376e66be4b240999470d26ae38.jpg', '明天星期三', '0');
INSERT INTO `ec_brand` VALUES ('5', 'oppo', 'http://www.oppo.com', '20190226\\34f41edaff8b5ac7a461d81f2ecc4811.jpg', '星期二', '1');
INSERT INTO `ec_brand` VALUES ('4', '华为', 'http://www.baidu.com', '20190226\\34f41edaff8b5ac7a461d81f2ecc4811.jpg', '信心形影', '1');

-- ----------------------------
-- Table structure for ec_category
-- ----------------------------
DROP TABLE IF EXISTS `ec_category`;
CREATE TABLE `ec_category` (
  `cate_id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `cate_name` varchar(60) NOT NULL,
  `parent_id` int(8) NOT NULL,
  `is_show` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 是 0 否',
  `is_nev_show` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 是 0 否',
  `keywords` varchar(50) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `addtime` int(10) NOT NULL,
  PRIMARY KEY (`cate_id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ec_category
-- ----------------------------
INSERT INTO `ec_category` VALUES ('1', '数码、家电', '0', '1', '1', '家电', '家用电器', '1551335734');
INSERT INTO `ec_category` VALUES ('13', '音响', '1', '0', '0', '音响', '音响', '1551351323');
INSERT INTO `ec_category` VALUES ('2', ' 进口食品、生鲜 ', '0', '1', '1', ' 进口食品、生鲜 ', ' 进口食品、生鲜 ', '1551351134');
INSERT INTO `ec_category` VALUES ('3', ' 食品、饮料、酒 ', '0', '1', '1', ' 食品、饮料、酒 ', ' 食品、饮料、酒 ', '1551351197');
INSERT INTO `ec_category` VALUES ('11', '手机', '1', '0', '0', '手机', '品牌手机', '1551351323');
INSERT INTO `ec_category` VALUES ('21', 'OPPO', '11', '1', '0', '智能手机', '全新拍照手机', '1551355724');
INSERT INTO `ec_category` VALUES ('12', '相机', '1', '0', '0', '照相机', '单反相机', '1551355800');
INSERT INTO `ec_category` VALUES ('22', 'VIVO', '11', '1', '0', '手机', '美颜手机', '1551355876');
INSERT INTO `ec_category` VALUES ('23', 'mimi', '11', '1', '0', '智能手机', '智能语音手机', '1551360204');

-- ----------------------------
-- Table structure for ec_friend
-- ----------------------------
DROP TABLE IF EXISTS `ec_friend`;
CREATE TABLE `ec_friend` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `url` varchar(100) NOT NULL,
  `logo` varchar(100) NOT NULL,
  `add_time` int(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ec_friend
-- ----------------------------
INSERT INTO `ec_friend` VALUES ('15', 'php', 'www.php.com', '20190307\\665f91d07d4adb267de9b24104b780e5.jpg', '1551956633');
INSERT INTO `ec_friend` VALUES ('12', '百度小爱2', 'www.baidu.com', '20190307\\9ee23257069961480001aebb7a6876a6.jpg', '1551950377');
INSERT INTO `ec_friend` VALUES ('16', '小说网', 'http://www.baidu.com.', '20190307\\84c483d8cc3fc543375c7b100fad2bcd.jpg', '1551957700');
INSERT INTO `ec_friend` VALUES ('11', '百度小爱1', 'www.1234.com', '20190307\\3e08f454213d0122a2de7ccef213e7d5.jpg', '1551950329');
INSERT INTO `ec_friend` VALUES ('10', 'dfghj', ' afghjjj', '20190307\\5000a519469daa7cc2b2ec0d3ad1fa4a.jpg', '1551950147');
INSERT INTO `ec_friend` VALUES ('17', '百度消毒', 'http://www.baidu.com', '20190307\\2d495f17fac72a5a59ea952e22235676.jpg', '1551959363');
INSERT INTO `ec_friend` VALUES ('18', '百度去吧', 'http://www.xiaoai.com', '20190307\\2936cea9c4aa99dfd668e9ae200b5cb3.jpg', '1551959534');
INSERT INTO `ec_friend` VALUES ('19', 'xiaobai', 'http://www.3456', '20190307\\510fcadccb6d939e2186f9934a8651dc.jpg', '1551959575');

-- ----------------------------
-- Table structure for ec_goods
-- ----------------------------
DROP TABLE IF EXISTS `ec_goods`;
CREATE TABLE `ec_goods` (
  `goods_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `goods_name` varchar(120) NOT NULL COMMENT '商品名',
  `cate_id` int(8) NOT NULL COMMENT '商品分类',
  `brand_id` int(8) NOT NULL COMMENT '商品品牌',
  `goods_sn` varchar(100) NOT NULL COMMENT '货号',
  `shop_price` decimal(10,2) NOT NULL COMMENT '报价',
  `market_price` decimal(10,2) NOT NULL COMMENT '市场价',
  `goods_img` varchar(120) DEFAULT NULL COMMENT '商品图片',
  `goods_thumb` varchar(120) DEFAULT NULL COMMENT '商品缩略图',
  `goods_number` int(10) DEFAULT NULL COMMENT '商品库存',
  `is_new` tinyint(1) DEFAULT NULL COMMENT '是否新品 1 是 0 否',
  `is_best` tinyint(1) DEFAULT NULL COMMENT '是否精品 1 是 0 否',
  `is_hot` tinyint(1) DEFAULT NULL COMMENT '是否热销 1 是 0 否',
  `is_on_sale` tinyint(1) DEFAULT NULL COMMENT '是否上架 1 是 0 否',
  `keywords` varchar(120) DEFAULT NULL COMMENT '关键字',
  `desciption` varchar(200) DEFAULT NULL COMMENT '描述',
  `content` text COMMENT '正文',
  PRIMARY KEY (`goods_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ec_goods
-- ----------------------------
INSERT INTO `ec_goods` VALUES ('1', 'oppoR9s', '1', '1', '123', '2462.00', '2750.00', null, null, '100', '1', '1', '1', '1', '智能手机', '手机', '双闪双摄');
INSERT INTO `ec_goods` VALUES ('2', 'vivo', '1', '2', '2345', '345.00', '4534.00', null, null, '212', '0', '0', '0', '0', '手机', '手机', '美颜');
INSERT INTO `ec_goods` VALUES ('3', '电话', '11', '7', '123456', '4326.00', '243636.00', '', '', '2334', '1', '1', '1', '0', '3246', 'shioujij', 'sdfadf');
INSERT INTO `ec_goods` VALUES ('4', '手表', '1', '3', '21344', '213456.00', '12354.00', '20190304\\5c4900c769a8a31e9dd11fe2d76e8347.jpg', '20190304\\5c4900c769a8a31e9dd11fe2d76e8347_thumb.jpg', '234', '1', '0', '0', '1', '234', null, null);
INSERT INTO `ec_goods` VALUES ('5', '435', '1', '3', '2345', '324.00', '324.00', '20190304\\1c964a77413e7820f0283e46b79277f7.jpg', '20190304\\1c964a77413e7820f0283e46b79277f7_thumb.jpg', '34', '1', '0', '1', '0', '342', null, '<p><img src=\"/ueditor/php/upload/image/20190304/1551698131.jpg\" title=\"1551698131.jpg\"/></p><p><img src=\"/ueditor/php/upload/image/20190304/1551698131.jpg\" title=\"1551698131.jpg\"/></p><p><img src=\"/ueditor/php/upload/image/20190304/1551698131.jpg\" title=\"1551698131.jpg\"/></p><p><img src=\"/ueditor/php/upload/image/20190304/1551698131.gif\" title=\"1551698131.gif\"/></p><p><br/></p>');
INSERT INTO `ec_goods` VALUES ('6', '435', '1', '3', '2345', '324.00', '324.00', '20190304\\1c964a77413e7820f0283e46b79277f7.jpg', '20190304\\1c964a77413e7820f0283e46b79277f7_thumb.jpg', '34', '0', '1', '0', '1', '342', null, '<p><img src=\"/ueditor/php/upload/image/20190304/1551698131.jpg\" title=\"1551698131.jpg\"/></p><p><img src=\"/ueditor/php/upload/image/20190304/1551698131.jpg\" title=\"1551698131.jpg\"/></p><p><img src=\"/ueditor/php/upload/image/20190304/1551698131.jpg\" title=\"1551698131.jpg\"/></p><p><img src=\"/ueditor/php/upload/image/20190304/1551698131.gif\" title=\"1551698131.gif\"/></p><p><br/></p>');
INSERT INTO `ec_goods` VALUES ('7', '435', '1', '3', '2345', '324.00', '324.00', '20190304\\1c964a77413e7820f0283e46b79277f7.jpg', '20190304\\1c964a77413e7820f0283e46b79277f7_thumb.jpg', '34', '1', '0', '1', '0', '342', null, '<p><img src=\"/ueditor/php/upload/image/20190304/1551698131.jpg\" title=\"1551698131.jpg\"/></p><p><img src=\"/ueditor/php/upload/image/20190304/1551698131.jpg\" title=\"1551698131.jpg\"/></p><p><img src=\"/ueditor/php/upload/image/20190304/1551698131.jpg\" title=\"1551698131.jpg\"/></p><p><img src=\"/ueditor/php/upload/image/20190304/1551698131.gif\" title=\"1551698131.gif\"/></p><p><br/></p>');
INSERT INTO `ec_goods` VALUES ('8', '435', '1', '3', '2345', '324.00', '324.00', '20190304\\1c964a77413e7820f0283e46b79277f7.jpg', '20190304\\1c964a77413e7820f0283e46b79277f7_thumb.jpg', '34', '0', '1', '0', '1', '342', null, '<p><img src=\"/ueditor/php/upload/image/20190304/1551698131.jpg\" title=\"1551698131.jpg\"/></p><p><img src=\"/ueditor/php/upload/image/20190304/1551698131.jpg\" title=\"1551698131.jpg\"/></p><p><img src=\"/ueditor/php/upload/image/20190304/1551698131.jpg\" title=\"1551698131.jpg\"/></p><p><img src=\"/ueditor/php/upload/image/20190304/1551698131.gif\" title=\"1551698131.gif\"/></p><p><br/></p>');
INSERT INTO `ec_goods` VALUES ('9', '435', '1', '3', '2345', '324.00', '324.00', '20190304\\1c964a77413e7820f0283e46b79277f7.jpg', '20190304\\1c964a77413e7820f0283e46b79277f7_thumb.jpg', '34', '1', '0', '1', '0', '342', null, '<p><img src=\"/ueditor/php/upload/image/20190304/1551698131.jpg\" title=\"1551698131.jpg\"/></p><p><img src=\"/ueditor/php/upload/image/20190304/1551698131.jpg\" title=\"1551698131.jpg\"/></p><p><img src=\"/ueditor/php/upload/image/20190304/1551698131.jpg\" title=\"1551698131.jpg\"/></p><p><img src=\"/ueditor/php/upload/image/20190304/1551698131.gif\" title=\"1551698131.gif\"/></p><p><br/></p>');
INSERT INTO `ec_goods` VALUES ('10', '435', '1', '3', '2345', '324.00', '324.00', '20190304\\1c964a77413e7820f0283e46b79277f7.jpg', '20190304\\1c964a77413e7820f0283e46b79277f7_thumb.jpg', '34', '0', '1', '0', '1', '342', null, '<p><img src=\"/ueditor/php/upload/image/20190304/1551698131.jpg\" title=\"1551698131.jpg\"/></p><p><img src=\"/ueditor/php/upload/image/20190304/1551698131.jpg\" title=\"1551698131.jpg\"/></p><p><img src=\"/ueditor/php/upload/image/20190304/1551698131.jpg\" title=\"1551698131.jpg\"/></p><p><img src=\"/ueditor/php/upload/image/20190304/1551698131.gif\" title=\"1551698131.gif\"/></p><p><br/></p>');
INSERT INTO `ec_goods` VALUES ('11', '435', '1', '3', '2345', '324.00', '324.00', '20190304\\1c964a77413e7820f0283e46b79277f7.jpg', '20190304\\1c964a77413e7820f0283e46b79277f7_thumb.jpg', '34', '1', '0', '1', '0', '342', null, '<p><img src=\"/ueditor/php/upload/image/20190304/1551698131.jpg\" title=\"1551698131.jpg\"/></p><p><img src=\"/ueditor/php/upload/image/20190304/1551698131.jpg\" title=\"1551698131.jpg\"/></p><p><img src=\"/ueditor/php/upload/image/20190304/1551698131.jpg\" title=\"1551698131.jpg\"/></p><p><img src=\"/ueditor/php/upload/image/20190304/1551698131.gif\" title=\"1551698131.gif\"/></p><p><br/></p>');
INSERT INTO `ec_goods` VALUES ('12', '435', '1', '3', '2345', '324.00', '324.00', '20190304\\1c964a77413e7820f0283e46b79277f7.jpg', '20190304\\1c964a77413e7820f0283e46b79277f7_thumb.jpg', '34', '0', '1', '0', '0', '342', null, '<p><img src=\"/ueditor/php/upload/image/20190304/1551698131.jpg\" title=\"1551698131.jpg\"/></p><p><img src=\"/ueditor/php/upload/image/20190304/1551698131.jpg\" title=\"1551698131.jpg\"/></p><p><img src=\"/ueditor/php/upload/image/20190304/1551698131.jpg\" title=\"1551698131.jpg\"/></p><p><img src=\"/ueditor/php/upload/image/20190304/1551698131.gif\" title=\"1551698131.gif\"/></p><p><br/></p>');
INSERT INTO `ec_goods` VALUES ('16', '435', '1', '3', '', '324.00', '324.00', '20190304\\1c964a77413e7820f0283e46b79277f7.jpg', '20190304\\1c964a77413e7820f0283e46b79277f7_thumb.jpg', '34', '1', '0', '1', '0', '342', null, '<p><img src=\"/ueditor/php/upload/image/20190304/1551698131.jpg\" title=\"1551698131.jpg\"/></p><p><img src=\"/ueditor/php/upload/image/20190304/1551698131.jpg\" title=\"1551698131.jpg\"/></p><p><img src=\"/ueditor/php/upload/image/20190304/1551698131.jpg\" title=\"1551698131.jpg\"/></p><p><img src=\"/ueditor/php/upload/image/20190304/1551698131.gif\" title=\"1551698131.gif\"/></p><p><br/></p>');
INSERT INTO `ec_goods` VALUES ('17', '435', '1', '3', '', '324.00', '324.00', '20190304\\1c964a77413e7820f0283e46b79277f7.jpg', '20190304\\1c964a77413e7820f0283e46b79277f7_thumb.jpg', '34', '0', '1', '0', '0', '342', null, '<p><img src=\"/ueditor/php/upload/image/20190304/1551698131.jpg\" title=\"1551698131.jpg\"/></p><p><img src=\"/ueditor/php/upload/image/20190304/1551698131.jpg\" title=\"1551698131.jpg\"/></p><p><img src=\"/ueditor/php/upload/image/20190304/1551698131.jpg\" title=\"1551698131.jpg\"/></p><p><img src=\"/ueditor/php/upload/image/20190304/1551698131.gif\" title=\"1551698131.gif\"/></p><p><br/></p>');
INSERT INTO `ec_goods` VALUES ('18', '3456', '1', '5', '', '3141.00', '3245.00', '20190305\\59b2908d68a61f63e708622a529fa1cb.jpg', '20190305\\59b2908d68a61f63e708622a529fa1cb_thumb.jpg', '3543', '1', '0', '1', '0', '34', null, '<p>1354444</p>');

-- ----------------------------
-- Table structure for ec_goods_photo
-- ----------------------------
DROP TABLE IF EXISTS `ec_goods_photo`;
CREATE TABLE `ec_goods_photo` (
  `photo_id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` int(8) DEFAULT NULL,
  `url` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`photo_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ec_goods_photo
-- ----------------------------
INSERT INTO `ec_goods_photo` VALUES ('1', '10', '20190304\\e8429ecb2bfbe54846ff6d8a92d57ab5.gif');
INSERT INTO `ec_goods_photo` VALUES ('2', '10', '20190304\\96b030d8832948717698c4a97133378f.jpg');
INSERT INTO `ec_goods_photo` VALUES ('3', '10', '20190304\\4b8bbcb83a9fe5c667599f42072d3b47.jpg');
INSERT INTO `ec_goods_photo` VALUES ('4', '10', '20190304\\ca8d42775ce4b20a7d9b64064f835334.jpeg');
INSERT INTO `ec_goods_photo` VALUES ('5', '16', '20190304\\e8429ecb2bfbe54846ff6d8a92d57ab5.gif');
INSERT INTO `ec_goods_photo` VALUES ('6', '16', '20190304\\96b030d8832948717698c4a97133378f.jpg');
INSERT INTO `ec_goods_photo` VALUES ('7', '16', '20190304\\4b8bbcb83a9fe5c667599f42072d3b47.jpg');
INSERT INTO `ec_goods_photo` VALUES ('8', '16', '20190304\\ca8d42775ce4b20a7d9b64064f835334.jpeg');
INSERT INTO `ec_goods_photo` VALUES ('9', '17', '20190304\\e8429ecb2bfbe54846ff6d8a92d57ab5.gif');
INSERT INTO `ec_goods_photo` VALUES ('10', '17', '20190304\\96b030d8832948717698c4a97133378f.jpg');
INSERT INTO `ec_goods_photo` VALUES ('11', '17', '20190304\\4b8bbcb83a9fe5c667599f42072d3b47.jpg');
INSERT INTO `ec_goods_photo` VALUES ('12', '17', '20190304\\ca8d42775ce4b20a7d9b64064f835334.jpeg');
INSERT INTO `ec_goods_photo` VALUES ('13', '18', '20190305\\5dddcbe5b0f26526b2719ad699cbbc06.jpg');
INSERT INTO `ec_goods_photo` VALUES ('14', '18', '20190305\\ba23b1dd8765a969da14acd22deeac22.jpg');
INSERT INTO `ec_goods_photo` VALUES ('15', '18', '20190305\\bb4409b2ae045aba94a5aa8a049f6b6e.jpeg');
INSERT INTO `ec_goods_photo` VALUES ('16', '18', '20190305\\fc839b5a6e51eac1bf509f226582c15d.jpg');
INSERT INTO `ec_goods_photo` VALUES ('17', '18', '20190305\\0b423b3cf0a783b25c73458c6fdf8269.png');

-- ----------------------------
-- Table structure for ec_member
-- ----------------------------
DROP TABLE IF EXISTS `ec_member`;
CREATE TABLE `ec_member` (
  `mem_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mem_name` varchar(30) NOT NULL,
  `mem_email` varchar(100) DEFAULT NULL,
  `mem_mobile` bigint(11) DEFAULT NULL,
  `pwd` char(32) NOT NULL,
  `sex` tinyint(1) DEFAULT '1' COMMENT '1 男 0 女',
  `age` tinyint(3) DEFAULT NULL,
  `addtime` int(10) DEFAULT NULL,
  PRIMARY KEY (`mem_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ec_member
-- ----------------------------
INSERT INTO `ec_member` VALUES ('1', '', '280654805@qq.com', null, '7d4692e0a38788952ecdeaea05fdc12b', '1', null, '1552148930');

-- ----------------------------
-- Table structure for ec_menu
-- ----------------------------
DROP TABLE IF EXISTS `ec_menu`;
CREATE TABLE `ec_menu` (
  `menu_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `menu_name` varchar(30) NOT NULL COMMENT '菜单名称',
  `parent_id` int(10) NOT NULL COMMENT '父级菜单',
  `module` varchar(255) NOT NULL COMMENT '模块名',
  `con_name` varchar(255) NOT NULL COMMENT '控制器名',
  `me_name` varchar(255) NOT NULL COMMENT '方法名',
  PRIMARY KEY (`menu_id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ec_menu
-- ----------------------------
INSERT INTO `ec_menu` VALUES ('1', '商品管理', '0', '列表', 'Goods.php', 'index');
INSERT INTO `ec_menu` VALUES ('2', '商品分类', '0', '添加', 'Category', 'add');
INSERT INTO `ec_menu` VALUES ('6', '添加', '4', '添加', 'Goods', 'add');
INSERT INTO `ec_menu` VALUES ('4', '商品品牌', '1', '删除', 'Category', 'delete');
INSERT INTO `ec_menu` VALUES ('5', '添加', '3', '添加', 'Category', 'add');
INSERT INTO `ec_menu` VALUES ('7', '权限管理', '0', '管理员--管理', 'Menu', 'index');
INSERT INTO `ec_menu` VALUES ('8', '管理员管理', '7', '管理员管理', 'menu', 'menu');
INSERT INTO `ec_menu` VALUES ('3', '商品管理', '0', '列表', 'Goods.php', 'index');
INSERT INTO `ec_menu` VALUES ('9', '商品分类', '0', '添加', 'Category', 'add');
INSERT INTO `ec_menu` VALUES ('10', '添加', '4', '添加', 'Goods', 'add');
INSERT INTO `ec_menu` VALUES ('11', '商品品牌', '1', '删除', 'Category', 'delete');
INSERT INTO `ec_menu` VALUES ('12', '添加', '3', '添加', 'Category', 'add');
INSERT INTO `ec_menu` VALUES ('13', '权限管理', '0', '管理员--管理', 'Menu', 'index');
INSERT INTO `ec_menu` VALUES ('14', '管理员管理', '7', '管理员管理', 'menu', 'menu');

-- ----------------------------
-- Table structure for ec_role
-- ----------------------------
DROP TABLE IF EXISTS `ec_role`;
CREATE TABLE `ec_role` (
  `role_id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(30) NOT NULL,
  `role_ribe` text NOT NULL,
  PRIMARY KEY (`role_id`),
  UNIQUE KEY `role_name` (`role_name`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ec_role
-- ----------------------------
INSERT INTO `ec_role` VALUES ('8', 'sdfghj', 'sdfghjkhgfdfguyfdzbcxghg');
INSERT INTO `ec_role` VALUES ('2', 'admin2', '明天星期一');
INSERT INTO `ec_role` VALUES ('3', 'admin3', '明天星期二');
INSERT INTO `ec_role` VALUES ('4', 'admin4', '明天星期三');
INSERT INTO `ec_role` VALUES ('5', 'admin5', '明天星期四');
INSERT INTO `ec_role` VALUES ('6', 'admin7', '星期日3sdfgh');

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `uid` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `sex` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 男 1 女',
  `age` tinyint(3) NOT NULL,
  `class` varchar(50) NOT NULL DEFAULT '0' COMMENT '0 1810a 1 1810b 2 1810c',
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('1', 'a', '0', '24', '0');
INSERT INTO `user` VALUES ('2', 'b', '1', '22', '1');
INSERT INTO `user` VALUES ('3', 'c', '1', '21', '2');
INSERT INTO `user` VALUES ('4', 'd', '0', '22', '1');
INSERT INTO `user` VALUES ('5', 'e', '0', '32', '1');
INSERT INTO `user` VALUES ('6', 'f', '1', '31', '0');
INSERT INTO `user` VALUES ('7', 'g', '1', '21', '2');
