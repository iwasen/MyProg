<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:広告クリックカウンタ設定
'******************************************************/

$title_text = $TT_click;
$title_color = $TC_OTHER;

switch ($next_action) {
case 'new':
	$sql = sprintf("insert into t_click_counter (cc_click_name,cc_client_name,cc_start_date,cc_all_num,cc_jump_url) values (%s,%s,%s,%s,%s)",
			sql_char(trim($cc_click_name)),
			sql_char(trim($cc_client_name)),
			sql_char(trim($cc_start_date)),
			sql_number($cc_all_num),
			sql_char(trim($cc_jump_url)));
			db_exec($sql);
	$msg = "登録しました。";
	break;
case 'update':
	//リセット処理
	if ($counter_set == "reset") {
		$cc_click_num=0;
		$sql = "delete from l_click_counter where lc_ct_id=".sql_number($ct_id);
		db_exec($sql);
	}
	$sql = sprintf("update t_click_counter set cc_click_name=%s,cc_client_name=%s,cc_start_date=%s,cc_all_num=%s,cc_jump_url=%s,cc_click_num=%s where cc_ct_id=".sql_number($ct_id),
			sql_char(trim($cc_click_name)),
			sql_char(trim($cc_client_name)),
			sql_char(trim($cc_start_date)),
			sql_number($cc_all_num),
			sql_char(trim($cc_jump_url)),
			sql_number($cc_click_num));
			db_exec($sql);
	$msg = "更新しました。";
	break;
case 'delete':
	$sql = "delete from t_click_counter where cc_ct_id=".sql_number($ct_id);
	db_exec($sql);
	$sql = "delete from l_click_counter where lc_ct_id=".sql_number($ct_id);
	db_exec($sql);
	$back = "location.href='$back_url'";
	$msg = "削除しました。";
	break;
default:
	redirect('index.php');
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

<center>
<form method="post" name="form1">

<?= $msg ?><br><br>
<input type="button" value=" 戻る " onclick="location.href='index.php'">

</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
