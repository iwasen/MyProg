<?
/******************************************************
' System :Eco-footprint 管理ページ
' Content:Welcomeメッセージ登録
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/select.php");
include("$inc/decode.php");

function decode_impress($code) {
	if ($code) {
		switch ($code) {
		case 1:
			return "よく出来ている";
			break;
		case 2:
			return "普通";
			break;
		case 3:
			return "たいした事はない";
			break;
		}
	}
}
set_global('member', '会員情報管理', '退会者リスト', BACK_TOP);

$sql = "SELECT mb_seq_no,mb_id,mb_name1,mb_name2,tk_mb_seq_no,tk_opinion_cd,tk_reason_cd,tk_reason_text,tk_regist_date"
		. " FROM t_taikai LEFT JOIN t_member ON tk_mb_seq_no=mb_seq_no WHERE tk_seq_no=$seq_no";
$result = db_exec($sql);
$nrow = pg_numrows($result);
$fetch = pg_fetch_object($result, 0);
$reason_cd = explode(',', $fetch->tk_reason_cd);
$i = 0;
while ($reason_cd[$i]) {
	$reason[] = decode_taikai($reason_cd[$i]);
	$i++;
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>■退会理由詳細</td>
	</tr>
	<tr>
		<td class="m1" width=200>氏名</td>
		<td class="n1"><?=htmlspecialchars("$fetch->mb_name1 $fetch->mb_name2")?></td>
	</tr>
	<tr>
		<td class="m1">感想（評価）</td>
		<td class="n1"><?=decode_impress($fetch->tk_opinion_cd)?></td>
	</tr>
	<tr>
		<td class="m1">退会理由</td>
		<td class="n1"><?=disp_msg($reason, '・', '<br>')?></td>
	</tr>
	<tr>
		<td class="m1">コメント</td>
		<td class="n1"><?=htmlspecialchars($fetch->tk_reason_text != '' ? $fetch->tk_reason_text : 'なし')?></td>
	</tr>
</table>
<br>
<input type="button" value=" 戻る " onclick="history.back()">
</form>
</div>

<? page_footer() ?>
</body>
</html>
