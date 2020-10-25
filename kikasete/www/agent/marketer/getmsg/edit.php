<?
/******************************************************
' System :きかせて・netエージェント用ページ
' Content:メッセージ表示・履歴の閲覧・削除
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");
include("$inc/format.php");
include("$inc/select.php");

// メッセージ状態選択肢
function select_co_status($default, $sel) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	echo "<option ", value_selected('0', $sel), ">有効</option>";
	echo "<option ", value_selected('9', $sel), ">無効</option>";
}

//メイン処理

set_global('マーケターからのメッセージ表示／状態変更', BACK_TOP);

$sql = "SELECT co_status,co_regist_date,co_title,co_text,mr_name1, mr_name2,mr_mail_addr"
	. " FROM t_communication LEFT JOIN t_marketer ON co_marketer_id=mr_marketer_id WHERE co_seq_no=$co_no AND co_agent_id=$agent_id";
$result = db_exec($sql);
if (pg_numrows($result))
	$fetch = pg_fetch_object($result, 0);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function onSubmit_form1(f) {
	return confirm("メッセージの状態を更新します。よろしいですか？");
}
//-->
</script>
<? menu_script() ?>
</head>
<body>

<? page_header() ?>
<? menu_start() ?>

<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=0 cellpadding=0 width=95%>
	<tr>
		<td>
			<table border=0 cellspacing=2 cellpadding=3 width=700>
				<tr>
					<td class="m4" align="center"><?=$g_title?></td>
				</tr>
			</table><br>
			<table border=0 cellspacing=2 cellpadding=3 width=700>
				<tr>
					<td class="m1">送信先</td>
					<td class="n1">
						<?=htmlspecialchars("$fetch->mr_name1 $fetch->mr_name2")?>（<?=htmlspecialchars($fetch->mr_mail_addr)?>）
					</td>
				</tr>
				<tr>
					<td class="m1" width=140>メッセージタイトル</td>
					<td class="n1"><?=htmlspecialchars($fetch->co_title)?></td>
				</tr>
				<tr>
					<td class="m1">本文</td>
					<td class="n1">
						<textarea class="kanji" name="self_introduction" cols=70 rows=30 readonly><?=htmlspecialchars($fetch->co_text)?></textarea>
					</td>
				</tr>
				<tr>
					<td class="m1">送信日時</td>
					<td class="n1"><?=format_datetime($fetch->co_regist_date)?></td>
				</tr>
				<tr>
					<td class="m1">状態</td>
					<td class="n1">
						<select name="co_status"><? select_co_status('',$fetch->co_status)?></select>
					</td>
				</tr>
			</table>
			<table width=700>
				<div align="center">
				<input type="hidden" name="co_no" <?=value($co_no)?>>
				<input type="hidden" name="next_action">
				<input type="submit" value="　更新　" onclick="document.form1.next_action.value='update'">
				<input type="button" value="　戻る　" onclick="history.back()">
				</div>
			</table>
		</td>
	</tr>
</table>
</form>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
