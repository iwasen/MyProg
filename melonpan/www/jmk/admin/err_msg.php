<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:
'******************************************************/

//'=== タイトル選択 ===
$title = substr($id, 0, 2);

if ($title <= 20 ) {
	$title_color = $TC_HAKKOUSHA;
	if ($title == 1) {
		$title_text = $TT_kari_touroku;
	}
	if ($title == 2) {
		$title_text = $TT_hon_touroku;
	}
	if ($title == 4) {
		$title_text = $TT_teishi;
	}
	if ($title == 6) {
		$title_text = $TT_hinshitsu;
	}
	if ($title == 9) {
		$title_text = $TT_hakkousha_mainte;
	}
	if ($title == 10) {
		$title_text = $TT_riba_regist;
	}
} elseif ($title > 20 && $title <= 40) {
	$title_color = $TC_DOKUSHA;
	if ($title == 21) {
		$title_text = $TT_dokusha_mainte;
	}
	if ($title == 23) {
		$title_text = $TT_addr_search;
	}
} elseif ($title > 40 && $title <= 60) {
	$title_color = $TC_MASTER;
	if ($title == 53) {
		$title_text = $TT_add_point;
	}
} elseif ($title > 60) {
	$title_color = $TC_OTHER;
	if ($title == 61) {
		$title_text = $TT_present;
	}
}
//'=== エラーメッセージ選択 ===
function err_msg($id) {
	$err_code = substr($id, -2);
	if ($err_code == "01") {
		print "メールアドレスが見つかりません。";
	}
	if ($err_code == "02") {
		print "メールアドレス及びパスワードは既に登録されています。";
	}
	if ($err_code == "03") {
		print "メルマガIDが見つかりません。";
	}
	if ($err_code == "04") {
		print "データの内容が既に変更されています。ご確認下さい。";
	}
	if ($err_code == "05") {
		print "既に登録されています。ご確認下さい。";
	}
	if ($err_code == "06") {
		print "該当するメルマガが見つかりませんでした。";
	}
	if ($err_code == "07") {
		print "変更後のメールアドレスは既に登録されています。ご確認ください。";
	}
	if ($err_code == "08") {
		print "該当するデータが見つかりませんでした。";
	}
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><?= $title_text ?></title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>

<form method="post" name="form1">
<center>

<? err_msg($id) ?>

<br><br>
<input type="button" value=" 戻る " onclick="history.back()">

</center>
</form>

<? footer_jmk(0); ?>

</body>
</html>
