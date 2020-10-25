#!/usr/local/bin/php4
<?
/******************************************************
' System :flette�桼���ڡ���
' Content:ͽ�󳫻Ͻ���
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/reserve.php");
include("$inc/decode.php");

// �ѥ�᡼���������ξ��ϥȥåץڡ�����
if (!is_numeric($shop_id) || !is_numeric($course_id))
	redirect('/index.html');

// Ź��̾�ȥ�����̾�����
$shop_name = decode_shop($shop_id);
$course_name = decode_course($course_id);

// ̾�Τ�̵����Хȥåץڡ�����
if ($shop_name == '' || $course_name == '')
	redirect('/index.html');

// ���å�����ͽ��������¸
session_register('reserve');

// ͽ����󥯥饹��Ź�ޤȥ���������¸
$reserve = new reserve_class;
$reserve->shop_id = $shop_id;
$reserve->course_id = $course_id;
$reserve->shop = $shop_name;
$reserve->course = $course_name;

// ���ɽ��ǯ��򥻥å�
$date = getdate(time() + DAY_SEC * 2);
$reserve->year = $date['year'];
$reserve->month = $date['mon'];

// ͽ�����������̤�����
redirect('choice.php');
?>