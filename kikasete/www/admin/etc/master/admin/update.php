<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:管理者情報更新処理
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

set_global('etc', 'その他管理｜マスタメンテナンス', '管理者マスタ', BACK_TOP);

switch ($next_action) {
case 'new':
	$sql = "SELECT ad_admin_id FROM m_admin WHERE ad_mail_addr='$mail_addr'";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sql = "INSERT INTO m_admin (ad_name1,ad_name2,ad_name1_kana,ad_name2_kana,ad_birthday,ad_sex,ad_mail_addr,ad_password,ad_kinmu_name,ad_kinmu_unit,ad_kinmu_post) VALUES (" .
						sql_char($name1) . "," .
						sql_char($name2) . "," .
						sql_char($name1_kana) . "," .
						sql_char($name2_kana) . "," .
						sql_datetime($birthday_y, $birthday_y, $birthday_m ,$birthday_d) . "," .
						sql_char($sex) . "," .
						sql_char($mail_addr) . "," .
						sql_char($password) . "," .
						sql_char($kinmu_name) . "," .
						sql_char($kinmu_unit) . "," .
						sql_char($kinmu_post) . ")";
		db_exec($sql);
		$msg = '管理者を登録しました。';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '入力したメールアドレスは既に登録されています。';
		$ret = 'history.back()';
	}
	break;
case 'update':
	$sql = "SELECT ad_admin_id FROM m_admin WHERE ad_mail_addr='$mail_addr' AND ad_admin_id<>$admin_id AND ad_status<>9";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sql = "UPDATE m_admin SET" .
					" ad_status=" . sql_char($status) .
					",ad_name1=" . sql_char($name1) .
					",ad_name2=" . sql_char($name2) .
					",ad_name1_kana=" . sql_char($name1_kana) .
					",ad_name2_kana=" . sql_char($name2_kana) .
					",ad_birthday=" . sql_date("$birthday_y/$birthday_m/$birthday_d") .
					",ad_sex=" . sql_char($sex) .
					",ad_mail_addr=" . sql_char($mail_addr) .
					",ad_password=" . sql_char($password) .
					",ad_kinmu_name=" . sql_char($kinmu_name) .
					",ad_kinmu_unit=" . sql_char($kinmu_unit) .
					",ad_kinmu_post=" . sql_char($kinmu_post) .
					",ad_update_date=current_timestamp" .
					" WHERE ad_admin_id=$admin_id";
		db_exec($sql);
		$msg = '管理者情報を更新しました。';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '入力したメールアドレスは既に登録されています。';
		$ret = 'history.back()';
	}
	break;
case 'delete':
	$sql = "UPDATE m_admin SET ad_status=9 WHERE ad_admin_id=$admin_id";
	db_exec($sql);
	$msg = '管理者を削除しました。';
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
