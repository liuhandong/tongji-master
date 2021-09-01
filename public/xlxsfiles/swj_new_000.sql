INSERT INTO `zc_auth_rule` (`id`, `type`, `pid`, `name`, `title`, `icon`, `condition`, `remark`, `ismenu`, `createtime`, `updatetime`, `weigh`, `status`) VALUES
(135, 'file', 131, 'modular/yearassessmodular', '年度考核模板', 'fa fa-align-justify', '', '', 1, 1551850689, 1566051579, 0, 'normal'),
(275, 'file', 135, 'modular/yearassessmodular/index', '查看', 'fa fa-align-justify', '', '', 0, 1566146434, 1566146434, 0, 'normal'),
(276, 'file', 135, 'modular/yearassessmodular/add', '添加', 'fa fa-align-justify', '', '', 0, 1566146467, 1566146467, 0, 'normal'),
(277, 'file', 135, 'modular/yearassessmodular/edit', '编辑', 'fa fa-align-justify', '', '', 0, 1566146488, 1566146488, 0, 'normal');

CREATE TABLE `zc_year_assess_report` (
  `id` bigint(20) NOT NULL COMMENT '主键id',
  `rf_id` varchar(255) NOT NULL COMMENT '关联报表字段id',
  `item_val` varchar(100) DEFAULT NULL COMMENT '录入项的值',
  `chk_item_val` varchar(100) DEFAULT NULL COMMENT '校验录入项的值',
  `add_time` int(11) DEFAULT '0' COMMENT '创建日期',
  `fa_id` int(11) DEFAULT NULL COMMENT '报表ID',
  `state` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '0：添加项  1：申报  2：校对  3：审批  ',
  `declare_time` int(11) DEFAULT '0' COMMENT '申报时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
 
  
ALTER TABLE `zc_year_assess_report`
  ADD PRIMARY KEY (`id`) USING BTREE;

ALTER TABLE `zc_year_assess_report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
  
INSERT INTO `zc_auth_rule` (`id`, `type`, `pid`, `name`, `title`, `icon`, `condition`, `remark`, `ismenu`, `createtime`, `updatetime`, `weigh`, `status`) VALUES
(136, 'file', 130, 'declared/declaredsyr', '年度考核填报', 'fa fa-align-justify', '', '', 1, 1551850689, 1566051579, 0, 'normal'),
(278, 'file', 136, 'declared/declaredsyr/index', '查看', 'fa fa-align-justify', '', '', 0, 1566146434, 1566146434, 0, 'normal'),
(279, 'file', 136, 'declared/declaredsyr/add', '添加', 'fa fa-align-justify', '', '', 0, 1566146467, 1566146467, 0, 'normal'),
(290, 'file', 136, 'declared/declaredsyr/edit', '编辑', 'fa fa-align-justify', '', '', 0, 1566146488, 1566146488, 0, 'normal'),
(291, 'file', 136, 'declared/declaredsyr/del', '删除', 'fa fa-align-justify', '', '', 0, 1566150388, 1566150388, 0, 'normal'),
(292, 'file', 136, 'declared/declaredsyr/tibao', '提报', 'fa fa-align-justify', '', '', 0, 1566150415, 1566150415, 0, 'normal');

CREATE TABLE `zc_chk_report_form` (
  `id` int(11) NOT NULL COMMENT '主键',
  `pid` int(11) COMMENT '',
  `name` varchar(100) NOT NULL COMMENT '显示名称',
  `seqn` int(10) UNSIGNED NULL COMMENT '序号',
  `unit_id` int(11) DEFAULT NULL COMMENT '计量单位表关联id',
  `topic` varchar(50) NULL DEFAULT '' COMMENT '考核主体',
  `rf_class` char(1) DEFAULT NULL COMMENT '年报月报季报m：月，q:季，y：年',
  `rf_year` varchar(4) DEFAULT NULL COMMENT '当前报表规则属于哪一年的',
  `order_no` int(11) DEFAULT 0 COMMENT '排序',
  `num_flag` varchar(100) DEFAULT NULL DEFAULT '' COMMENT '是否为数据项',
  `their_garden` varchar(100) DEFAULT NULL DEFAULT '' COMMENT '所属园区',
  `list1` varchar(50) DEFAULT NULL DEFAULT '0' COMMENT '关联报表项目1',
  `list2` varchar(50) DEFAULT NULL DEFAULT '0' COMMENT '关联报表项目2',
  `rf_note` text NULL COMMENT '项目说明'  
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;


ALTER TABLE `zc_chk_report_form`
  ADD PRIMARY KEY (`id`) USING BTREE;

ALTER TABLE `zc_chk_report_form`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
  
INSERT INTO `zc_chk_report_form` ( `id`, `pid`,`name`,`seqn`,`unit_id`,`topic`,`rf_class`,`rf_year`,`order_no`,`num_flag`,`their_garden`,`list1`,`list2`,`rf_note`) VALUES 
(1,NULL,'有限期至',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(2,NULL,'单位负责人',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(3,NULL,'填报人',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(4,NULL,'报出日期',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(5,NULL,'说明',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);



INSERT INTO `zc_auth_rule` (`id`, `type`, `pid`, `name`, `title`, `icon`, `condition`, `remark`, `ismenu`, `createtime`, `updatetime`, `weigh`, `status`) VALUES
(135, 'file', 131, 'modular/yearassessmodular', '年度考核模板', 'fa fa-align-justify', '', '', 1, 1551850689, 1566051579, 0, 'normal'),
(275, 'file', 135, 'modular/yearassessmodular/index', '查看', 'fa fa-align-justify', '', '', 0, 1566146434, 1566146434, 0, 'normal'),
(276, 'file', 135, 'modular/yearassessmodular/add', '添加', 'fa fa-align-justify', '', '', 0, 1566146467, 1566146467, 0, 'normal'),
(277, 'file', 135, 'modular/yearassessmodular/edit', '编辑', 'fa fa-align-justify', '', '', 0, 1566146488, 1566146488, 0, 'normal');


INSERT INTO `zc_auth_rule` (`id`, `type`, `pid`, `name`, `title`, `icon`, `condition`, `remark`, `ismenu`, `createtime`, `updatetime`, `weigh`, `status`) VALUES
(500, 'file', 131, 'modular/scoremodular', '评分细则', 'fa fa-align-justify', '', '', 1, 1551850689, 1566051579, 0, 'normal'),
(501, 'file', 500, 'modular/scoremodular/index', '查看', 'fa fa-align-justify', '', '', 0, 1566146434, 1566146434, 0, 'normal'),
(502, 'file', 500, 'modular/scoremodular/add', '添加', 'fa fa-align-justify', '', '', 0, 1566146467, 1566146467, 0, 'normal'),
(503, 'file', 500, 'modular/scoremodular/edit', '编辑', 'fa fa-align-justify', '', '', 0, 1566146488, 1566146488, 0, 'normal');


CREATE TABLE `zc_year_assess_report` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rf_id` varchar(255) NOT NULL COMMENT '关联报表字段id',
  `item_val` varchar(100) DEFAULT NULL COMMENT '录入项的值',
  `chk_item_val` varchar(100) DEFAULT NULL COMMENT '校验录入项的值',
  `add_time` int(11) DEFAULT 0 COMMENT '创建日期',
  `fa_id` int(11) DEFAULT NULL COMMENT '报表ID',
  `state` tinyint(1) unsigned NOT NULL DEFAULT 0 COMMENT '0：添加项  1：申报  2：校对  3：审批  ',
  `declare_time` int(11) DEFAULT 0 COMMENT '申报时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8

CREATE TABLE `zc_swj_assess_upload_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `file_path` varchar(200) DEFAULT NULL COMMENT '路径',
  `fa_id` int(11) DEFAULT NULL COMMENT '报表id',
  `rf_class` char(1) DEFAULT NULL COMMENT '年报月报季报m：月，q:季，y：年',
  `add_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8

INSERT INTO code.zc_chk_report_form (pid,name,seqn,unit_id,topic,rf_class,rf_year,order_no,num_flag,their_garden,list1,list2,rf_note) VALUES 
(NULL,'有限期至',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL)
,(NULL,'单位负责人',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL)
,(NULL,'填报人',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL)
,(NULL,'报出日期',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL)
,(NULL,'说明',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL)
,(0,'一)土地开发',0,4,'','r','2021',1,'0','8','','','')
,(121,'本年末批准规划面积',1,4,'市发改委','r','2021',2,'1','8','1','8','本年末批准规划面积：指截至本年末，经国家或省级自然资源部门批准的可由园区使用的所有土地面积总和，包括已开发和待开发的土地面积。')
,(121,'    其中：上级批准的四至范围核心区）面积',2,4,'市发改委','r','2021',3,'2','8','1','9','')
,(121,'10规划工业产业）用地面积',3,4,'市财政局','r','2021',4,'2','8','1','6','规划工业用地面积：指截至本年末，园区批准规划面积中规划的工矿仓储用地。工矿仓储用地指用于工业生产、物资存放场所的土地面积。')
,(0,'经济发展指标(85%)',NULL,4,'','r',NULL,5,'0','8','','0',NULL)
;
INSERT INTO code.zc_chk_report_form (pid,name,seqn,unit_id,topic,rf_class,rf_year,order_no,num_flag,their_garden,list1,list2,rf_note) VALUES 
(121,'      地区生产总值',4,4,'','r','',6,'0','8','0','0',NULL)
,(121,'        地区生产总值增速',5,4,'市发改委','r','2021',7,'0','8','0','0',NULL)