<?
/******************************************************
' System :きかせて・netエージェント用ページ
' Content:マーケター一覧選択
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");
include("$inc/list.php");

//window_size 600:300

//メイン処理
set_global2('project', 'プロジェクト管理｜マスタ登録', 'マーケター一覧', BACK_TOP);

$order_by = order_by(1, 0, 'mr_marketer_id', 'mr_name1_kana||mr_name2_kana', 'mr_mail_addr');
$sql = "SELECT mr_marketer_id,mr_name1,mr_name2,mr_mail_addr FROM t_marketer $order_by";
$result = db_exec($sql);
$nrow = pg_numrows($result);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<SCRIPT LANGUAGE=javascript>
<!--
function sort_list(sort, dir) {
	var f = document.form1;
	f.sort_col.value = sort;
	f.sort_dir.value = dir;
	f.submit();
}
function OnClick_marketer_id(marketer_id) {
	opener.copy_marketer(marketer_id);
	close();
}
//-->
</SCRIPT>
</head>
<body>
<form method="post" name="form1">
	<center>
		<table width=95%>
			<tr>
				<td class="m0" colspan=2 align="left">■マーケターを選択してください。</td>
				<td align=right valign=bottom norap><small><font color=blue>■</font><A href='JavaScript:window.close()'>閉じる</A></small></td>
			</tr>
		</table>
		<input type="hidden" name="sort_col" <?=value($sort_col)?>>
		<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
		<table <?=LIST_TABLE?> class="small" width=95%>
			<tr class="tch">
<?
sort_header(1, 'マーケターID');
sort_header(2, 'マーケター名');
sort_header(3, 'メールアドレス');
?>
			</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
			<tr class="tc<?=$i % 2?>">
				<td align="center" name="marketer_id" title="選択したマーケターを登録します"><a href='JavaScript:OnClick_marketer_id(<?=$fetch->mr_marketer_id?>)'><?=$fetch->mr_marketer_id?></a></td>
				<td><?="$fetch->mr_name1 $fetch->mr_name2"?></td>
				<td><?=$fetch->mr_mail_addr?></td>
			</tr>
		<?
}
		?>
		</table>
	</center>
</form>
</body>
</html>