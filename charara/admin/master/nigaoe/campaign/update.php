<?
/******************************************************
' System :「きゃららFactory」管理者用ページル
' Content:マスタメンテナンス｜似顔絵関連マスタ｜キャンペーンマスタ
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

set_global('master_st', 'マスタ管理｜似顔絵関連マスタ', 'キャンペーンマスタ', BACK_TOP);

switch ($next_action) {
case 'new':
	$sql = "SELECT cp_campaign_cd FROM m_campaign WHERE cp_campaign_cd='$cp_campaign_cd'";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sql = sprintf("INSERT INTO m_campaign (cp_campaign_cd, cp_server_id, cp_name, cp_explain, cp_from_date, cp_to_date, cp_nouki, cp_brushwork)"
			. " VALUES (%s,%s,%s,%s,%s,%s,%s,%s)",
					sql_char($cp_campaign_cd),
					sql_char($cp_server_id),
					sql_char($cp_name),
					sql_char($cp_explain),
					sql_date("$cp_from_date_y/$cp_from_date_m/$cp_from_date_d"),
					sql_date("$cp_to_date_y/$cp_to_date_m/$cp_to_date_d"),
					sql_number($cp_nouki),
					sql_number($cp_brushwork));
		db_exec($sql);
		$msg = 'キャンペーン情報を登録しました。';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '入力したキャンペーンコードは既に登録されています。';
		$ret = 'history.back()';
	}
	break;
case 'update':
	$sql = "SELECT cp_campaign_cd FROM m_campaign WHERE cp_campaign_cd<>'$cp_cd' AND cp_name='$cp_name'";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sql = "UPDATE m_campaign SET" .
					" cp_server_id=" . sql_char($cp_server_id) .
					", cp_name=" . sql_char($cp_name) .
					", cp_explain=" . sql_char($cp_explain) .
					", cp_from_date=" . sql_date("$cp_from_date_y/$cp_from_date_m/$cp_from_date_d") .
					", cp_to_date=" . sql_date("$cp_to_date_y/$cp_to_date_m/$cp_to_date_d") .
					", cp_nouki=" . sql_number($cp_nouki) .
					", cp_brushwork=" . sql_number($cp_brushwork) .
					" WHERE cp_campaign_cd='$cp_cd'";
		db_exec($sql);
		$msg = 'キャンペーン情報を更新しました。';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = 'キャンペーンコードは既に登録されています。';
		$ret = 'history.back()';
	}
	break;
case 'delete':
	$sql = "SELECT nd_nigaoe_id FROM t_nigaoe_data WHERE nd_campaign_cd='$cp_cd'";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sql = "DELETE FROM m_campaign WHERE cp_campaign_cd='$cp_cd'";
		db_exec($sql);
		$msg = 'キャンペーン情報を削除しました。';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = 'キャンペーンコードは既に使用されている為、削除できません。';
		$ret = 'history.back()';
	}
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
