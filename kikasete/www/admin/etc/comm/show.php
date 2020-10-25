<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:メッセージ送受信内容表示
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/format.php");

//メイン処理
set_global('etc', 'その他管理', 'メッセージ送受信履歴', BACK_TOP);

$sql = "SELECT co_seq_no,co_status,co_regist_date,co_direction,co_marketer_id,co_agent_id,co_title,co_text,mr_mail_addr,mr_name1,mr_name2,ag_name1,ag_name2,ag_mail_addr"
		. " FROM t_communication JOIN t_marketer ON co_marketer_id=mr_marketer_id JOIN t_agent ON co_agent_id=ag_agent_id"
		. " WHERE co_seq_no=$seq_no";
$result = db_exec($sql);
if (pg_numrows($result) == 0)
	redirect('list.php');
$fetch = pg_fetch_object($result, 0);
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
<form method="post" name="form1" action="update.php" onsubmit="return onsubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>■メッセージ送受信内容</td>
	</tr>
	<tr>
		<td class="m1" width="20%">マーケター</td>
		<td class="n1" width="80%"><?=htmlspecialchars("$fetch->mr_name1 $fetch->mr_name2 <$fetch->mr_mail_addr>")?></td>
	</tr>
	<tr>
		<td class="m1">エージェント</td>
		<td class="n1"><?=htmlspecialchars("$fetch->ag_name1 $fetch->ag_name2 <$fetch->ag_mail_addr>")?></td>
	</tr>
	<tr>
		<td class="m1">送信日時</td>
		<td class="n1"><?=format_datetime($fetch->co_regist_date)?></td>
	</tr>
	<tr>
		<td class="m1">タイトル</td>
		<td class="n1"><?=htmlspecialchars($fetch->co_title)?></td>
	</tr>
	<tr>
		<td class="m1">メッセージ内容</td>
		<td class="n1"><textarea cols=70 rows=20 readonly><?=htmlspecialchars($fetch->co_text)?></textarea></td>
	</tr>
</table>
<br>
<input type="button" value="　戻る　" onclick="history.back()">
</form>
</div>

<? page_footer() ?>
</body>
</html>
