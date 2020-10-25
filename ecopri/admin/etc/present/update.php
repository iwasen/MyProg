<?
/******************************************************
' System :Eco-footprint 管理ページ
' Content:プレゼント管理
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

//メイン処理
set_global('etc', 'その他管理', 'プレゼント管理', BACK_TOP);

switch ($next_action) {
case 'new':
	$sql = "insert into t_present (ps_type,ps_name,ps_explain,ps_prize,ps_num,ps_start_date,ps_end_date,ps_lottery_date,"
		. "ps_contact_addr1,ps_contact_addr2,ps_contact_addr3,ps_status,ps_regist_date) values ("
		. sql_number($type) . ","
		. sql_char($name) . ","
		. sql_char($explain) . ","
		. sql_char($prize) . ","
		. sql_number($num) . ","
		. sql_date2($start_date_y,$start_date_m,$start_date_d,$start_date_h) . ","
		. sql_date2($end_date_y,$end_date_m,$end_date_d,$end_date_h) . ","
		. sql_date2($lottery_date_y,$lottery_date_m,$lottery_date_d) . ","
		. sql_char($contact_addr1) . ","
		. sql_char($contact_addr2) . ","
		. sql_char($contact_addr3) . ","
		. sql_number($status)
		. ",'now')";
	db_exec($sql);
	$msg = 'プレゼント情報を登録しました。';
	$back = "location.href='list.php'";
	break;
case 'update':
	$sql = "update t_present set"
		. " ps_type=" . sql_number($type)
		. ",ps_name=" . sql_char($name)
		. ",ps_explain=" . sql_char($explain)
		. ",ps_prize=" . sql_char($prize)
		. ",ps_num=" . sql_number($num)
		. ",ps_start_date=" . sql_date2($start_date_y,$start_date_m,$start_date_d,$start_date_h)
		. ",ps_end_date=" . sql_date2($end_date_y,$end_date_m,$end_date_d,$end_date_h)
		. ",ps_lottery_date=" . sql_date2($lottery_date_y,$lottery_date_m,$lottery_date_d)
		. ",ps_contact_addr1=" . sql_char($contact_addr1)
		. ",ps_contact_addr2=" . sql_char($contact_addr2)
		. ",ps_contact_addr3=" . sql_char($contact_addr3)
		. ",ps_status=" . sql_number($status)
		. " where ps_present_no=" . sql_number($ps_no);
	db_exec($sql);
	$msg = 'プレゼント情報を更新しました。';
	$back = "location.href='list.php'";
	break;
case 'delete':
	$msg = 'プレゼント情報をを削除しました。';
	$back = "location.href='list.php'";
	break;
default:
	redirect('list.php');
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body onLoad="document.all.ok.focus()">
<? page_header() ?>

<div align="center">
<p class="msg"><?=$msg?></p>
<p><input type="button" id="ok" value="　戻る　" onclick="<?=$back?>"></p>
</div>

<? page_footer() ?>
</body>
</html>
