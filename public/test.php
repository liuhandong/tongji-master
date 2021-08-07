<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019\3\19 0019
 * Time: 9:29
 */
$dbh = new PDO('mysql:host=127.0.0.1;dbname=code', 'root', '123456');
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh->exec('set names utf8');
$sqlcount = sprintf('SELECT * FROM zc_admin WHERE id IN (SELECT uid FROM zc_auth_group_access WHERE group_id=\'1\')');
$countrs = $dbh->prepare($sqlcount);
$countrs->execute();
$countarr = $countrs->fetchAll(PDO::FETCH_ASSOC);
echo '子查询：查询admin表，用auth_group_access的group_idc查询';
echo '<br>';
var_dump($countarr);
echo '<br>';

$sqlcount1 = sprintf('SELECT za.* FROM zc_admin za LEFT JOIN zc_auth_group_access zaga ON zaga.uid = za.id WHERE zaga.uid = \'1\'');
$countrs1 = $dbh->prepare($sqlcount1);
$countrs1->execute();
$countarr1 = $countrs1->fetchAll(PDO::FETCH_ASSOC);
echo '左联查询：查询admin表，用auth_group_access的group_idc查询';
echo '<br>';
var_dump($countarr1);
echo '<br>';

$sqlcount2 = sprintf('SELECT * FROM zc_admin ORDER BY id DESC ');
$countrs2 = $dbh->prepare($sqlcount2);
$countrs2->execute();
$countarr2 = $countrs2->fetchAll(PDO::FETCH_ASSOC);
echo '降序：查询admin表，用 ORDER BY 实现降序';
echo '<br>';
var_dump($countarr2);
echo '<br>';


$sqlcount3 = sprintf('SELECT * FROM zc_admin ORDER BY id ASC ');
$countrs3 = $dbh->prepare($sqlcount3);
$countrs3->execute();
$countarr3 = $countrs3->fetchAll(PDO::FETCH_ASSOC);
echo '升序：查询admin表，用 ORDER BY 实现升序';
echo '<br>';
var_dump($countarr3);
echo '<br>';

$sqlcount4 = sprintf('SELECT * FROM zc_city GROUP BY city_code');
$countrs4 = $dbh->prepare($sqlcount4);
$countrs4->execute();
$countarr4 = $countrs4->fetchAll(PDO::FETCH_ASSOC);
echo '分组查询：查询zc_city表，用 GROUP BY 实现分组查询';
echo '<br>';
var_dump($countarr4);
echo '<br>';

$sqlcount5 = sprintf('SELECT * FROM zc_city WHERE id = 11');
$countrs5 = $dbh->prepare($sqlcount5);
$countrs5->execute();
$countarr5 = $countrs5->fetchAll(PDO::FETCH_ASSOC);
echo 'where = 查询：查询zc_city表';
echo '<br>';
var_dump($countarr5);
echo '<br>';

$sqlcount6 = sprintf('SELECT * FROM zc_city WHERE id > 11');
$countrs6 = $dbh->prepare($sqlcount6);
$countrs6->execute();
$countarr6 = $countrs6->fetchAll(PDO::FETCH_ASSOC);
echo 'where > 查询：查询zc_city表';
echo '<br>';
var_dump($countarr6);
echo '<br>';

$sqlcount7 = sprintf('SELECT * FROM zc_city WHERE id < 11');
$countrs7 = $dbh->prepare($sqlcount7);
$countrs7->execute();
$countarr7 = $countrs7->fetchAll(PDO::FETCH_ASSOC);
echo 'where < 查询：查询zc_city表';
echo '<br>';
var_dump($countarr7);
echo '<br>';

$sqlcount8 = sprintf('SELECT * FROM zc_city WHERE id IN (10,11,12)');
$countrs8 = $dbh->prepare($sqlcount8);
$countrs8->execute();
$countarr8 = $countrs8->fetchAll(PDO::FETCH_ASSOC);
echo 'where IN 查询：查询zc_city表';
echo '<br>';
var_dump($countarr8);
echo '<br>';

$sqlcount9 = sprintf('SELECT * FROM zc_city WHERE id BETWEEN 10 AND 13');
$countrs9 = $dbh->prepare($sqlcount9);
$countrs9->execute();
$countarr9 = $countrs9->fetchAll(PDO::FETCH_ASSOC);
echo 'where BETWEEN 查询：查询zc_city表';
echo '<br>';
var_dump($countarr9);
echo '<br>';

$sqlcount10 = sprintf('SELECT * FROM zc_city WHERE city_name LIKE \'%%大%%\'');
$countrs10 = $dbh->prepare($sqlcount10);
$countrs10->execute();
$countarr10 = $countrs10->fetchAll(PDO::FETCH_ASSOC);
echo 'where like 查询：查询zc_city表';
echo '<br>';
var_dump($countarr10);
echo '<br>';

$sqlcount11 = sprintf('select * from zc_test where  FIND_IN_SET(\'12\',category_ids)');
$countrs11 = $dbh->prepare($sqlcount11);
$countrs11->execute();
$countarr11 = $countrs11->fetchAll(PDO::FETCH_ASSOC);
echo 'where FIND_IN_SET 查询：查询zc_city表';
echo '<br>';
var_dump($countarr11);
echo '<br>';


$sqlcount11 = sprintf('SELECT * FROM zc_city WHERE city_name LIKE\'%%大%%\'');
echo $sqlcount11;exit;
$countrs11 = $dbh->prepare($sqlcount11);
$countrs11->execute();
$countarr11 = $countrs11->fetchAll(PDO::FETCH_ASSOC);
echo 'where like 查询：查询zc_city表';
echo '<br>';
var_dump($countarr11);
echo '<br>';



