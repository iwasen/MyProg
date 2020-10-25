<?
/******************************************************
' System :ICPプロジェクトページ
' Content:プロジェクトインポート完了ページ
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/auth.php");

$inc = "$top/inc";
include("$inc/pjt_common.php");
include("$inc/login_check.php");
include("$inc/header.php");

// 入力パラメータ
$pjt_id = $_SESSION[SCD]['pjt_id'];
$import_data = unserialize($_POST['import_data']);

db_begin_trans();

foreach ($import_data as $data) {
	// プロジェクトメンバー追加更新
	$rec['pm_status'] = sql_number(1);
	$rec['pm_pjt_id'] = sql_number($_SESSION[SCD]['pjt_id']);
	$rec['pm_staff_auth'] = sql_char($data['staff_auth']);
	$rec['pm_mail_addr'] = sql_char($data['mail_addr']);
	$rec['pm_password'] = sql_char($data['password']);
	$rec['pm_org_id'] = sql_number($data['org_id']);
	$rec['pm_org_member_id'] = sql_char($data['org_member_id']);
	$rec['pm_nickname'] = sql_char($data['nickname']);
	$rec['pm_name1'] = sql_char($data['name1']);
	$rec['pm_name2'] = sql_char($data['name2']);
	$rec['pm_name1_kana'] = sql_char($data['name1_kana']);
	$rec['pm_name2_kana'] = sql_char($data['name2_kana']);
	$rec['pm_sex_cd'] = sql_char($data['sex_cd']);
	$rec['pm_birthday'] = sql_date($data['birthday']);
	$rec['pm_mikikon_cd'] = sql_char($data['mikikon_cd']);
	$rec['pm_shokugyou'] = sql_char($data['shokugyou']);
	$rec['pm_gyoushu'] = sql_char($data['gyoushu']);
	$rec['pm_shokushu'] = sql_char($data['shokushu']);
	$rec['pm_zip_cd'] = sql_char($data['zip_cd']);
	$rec['pm_address'] = sql_char($data['address']);
	$rec['pm_tel_no'] = sql_char($data['tel_no']);
	$member_id = $data['member_id'];
	if (!$member_id)
		db_insert('t_pjt_member', $rec);
	else
		db_update('t_pjt_member', $rec, "pm_member_id=" . sql_char($member_id));
}

db_commit_trans();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">

<head>
	<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
	<meta http-equiv="Pragma" content="no-cache">
	<title><?=htmlspecialchars($_SESSION[SCD]['pjt_name'])?>　icp.net</title>
	<link rel="stylesheet" type="text/css" href="<?=$top?>/default.css">
</head>

<body>
<div align="center">

<? pjt_header() ?>

<!-- pankuzu -->
<table border="0" cellspacing="0" cellpadding="3" bgcolor="#fdc92b" width="800" class="pankuzu">
<tr>
	<td>ホーム
		&nbsp;&nbsp;＞&nbsp;&nbsp;
		プロジェクト情報
		&nbsp;&nbsp;＞&nbsp;&nbsp;
		メンバーリスト
		&nbsp;&nbsp;＞&nbsp;&nbsp;
		インポート完了
		</td>
</tr>
</table>
<!-- pankuzu -->

<!-- 新規ジョブ -->
<table border="0" cellspacing="0" cellpadding="0" width="800" class="regist">
<tr>
	<td width="10"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
	<td width="790" align="right"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
</tr>
</table>
<!-- 新規ジョブ -->

<!-- main -->
<table border="0" cellspacing="0" cellpadding="0" width="800" class="main">
<tr>
	<td width="20"><img src="../img/spacer.gif" width="20" height="1" border="0"></td>
	<td width="760">
		<table border="0" cellspacing="0" cellpadding="0" width="760" class="contents">
		<tr>
			<td>プロジェクトメンバーのインポートを完了しました。</td>
		</tr>
		</table>

	</td>
	<td width="20"><img src="../img/spacer.gif" width="20" height="1" border="0"></td>
</tr>
</table>
<!-- main -->

<!-- 戻る -->
<table border="0" cellspacing="0" cellpadding="0" width="800" class="regist">
<tr>
	<td width="10"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
	<td width="780" align="center"><input type="button" value="このウィンドウを閉じる" class="form" onclick="window.close();opener.location.reload()"></td>
	<td width="10"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
</tr>
</table>
<!-- 戻る -->

<? pjt_footer() ?>

</div>
</body>

</html>
