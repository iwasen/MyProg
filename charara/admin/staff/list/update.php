<?
/******************************************************
' System :「きゃららFactory」管理者用ページル
' Content:スタッフ登録リスト 
'******************************************************/

$top = "../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

set_global('staff', 'スタッフ情報管理', 'スタッフ登録リスト', BACK_TOP);

switch ($next_action) {
case 'new':
	$sql = "SELECT st_staff_id FROM m_staff WHERE st_mail_addr=" . sql_char($st_mail_addr);
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sub = "SELECT (coalesce(MAX(st_staff_id), 0) + 1) FROM m_staff";
		$sql = sprintf("INSERT INTO m_staff (st_staff_id,st_password,st_status,st_name1,st_name2,st_name1_kana,st_name2_kana,st_mail_addr,st_zip_code,"
						. "st_address,st_tel_no,st_priority_rank,st_level_rank,st_organization,st_from_date,st_to_date)"
						. "VALUES (($sub),%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
							sql_char($st_password),
							sql_number($st_status),
							sql_char($st_name1),
							sql_char($st_name2),
							sql_char(mb_convert_kana($st_name1_kana, 'KCV')),
							sql_char(mb_convert_kana($st_name2_kana, 'KCV')),
							sql_char($st_mail_addr),
							sql_char($st_zip1 . $st_zip2),
							sql_char($st_address),
							sql_char($st_tel_no),
							sql_number($st_priority_rank),
							sql_number($st_level_rank),
							sql_number($st_organization),
							sql_date("$st_from_date_y/$st_from_date_m/$st_from_date_d"),
							sql_date("$st_to_date_y/$st_to_date_m/$st_to_date_d"));
		db_exec($sql);
		$msg = 'スタッフ情報を登録しました。';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '入力したメールアドレスは既に登録されています。';
		$ret = 'history.back()';
	}
	break;
case 'update':
	$sql = "SELECT st_staff_id FROM m_staff WHERE st_staff_id<>$st_id AND st_mail_addr=" . sql_char($st_mail_addr);
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sql = "UPDATE m_staff SET st_password=" . sql_char($st_password)
						. ",st_status=" . sql_number($st_status)
						. ",st_name1=" . sql_char($st_name1)
						. ",st_name2=" . sql_char($st_name2)
						. ",st_name1_kana=" . sql_char(mb_convert_kana($st_name1_kana, 'KCV'))
						. ",st_name2_kana=" . sql_char(mb_convert_kana($st_name2_kana, 'KCV'))
						. ",st_mail_addr=" . sql_char($st_mail_addr)
						. ",st_zip_code=" . sql_char($st_zip1 . $st_zip2)
						. ",st_address=" . sql_char($st_address)
						. ",st_tel_no=" . sql_char($st_tel_no)
						. ",st_priority_rank=" . sql_number($st_priority_rank)
						. ",st_level_rank=" . sql_number($st_level_rank)
						. ",st_organization=" . sql_number($st_organization)
						. ",st_from_date=" . sql_date("$st_from_date_y/$st_from_date_m/$st_from_date_d")
						. ",st_to_date=" . sql_date("$st_to_date_y/$st_to_date_m/$st_to_date_d")
						. "WHERE st_staff_id=$st_id";
		db_exec($sql);
		$msg = 'スタッフ情報を更新しました。';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '入力したメールアドレスは既に登録されています。';
		$ret = 'history.back()';
	}
	break;
case 'delete';
	$sql ="DELETE FROM m_staff WHERE st_staff_id=$st_id";
	db_exec($sql);
	$msg = 'スタッフ情報を削除しました。';
	$ret = 'location.href=\'list.php\'';
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
<p><input type="button" id="ok" value="　戻る　" onclick="<?=$ret?>"></p>
</div>

<? page_footer() ?>
</body>
</html>
