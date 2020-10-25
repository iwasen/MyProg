<?
/******************************************************
' System :きかせて・netエージェント用ページ
' Content:対象条件テンプレート一覧
'******************************************************/

$top = '../../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");
include("$inc/list.php");

//メイン処理
set_global2('project', 'プロジェクト管理', '対象条件テンプレート一覧', BACK_TOP);

$order_by = order_by(1, 1, 'ps_seq_no', 'ps_title');
$sql = "SELECT ps_seq_no,ps_title,ps_search_id FROM m_pjt_search";
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
function OnClick_copy(search_id) {
	if (confirm("対象条件をコピーします。よろしいですか？")) {
		opener.CopySearch(search_id ,<?=$job_id?>);
		close();
	}
}
function OnClick_search(search_id) {
  var win;
  win = window.open("search_result.php?ps_search_id=" + search_id, "search_result");
  win.focus();
}
//-->
</SCRIPT>
</head>
<body>
<form method="post" name="form1">
	<center>
		<table width=95%>
			<tr>
				<td class="m0" colspan=2 align="left">■コピー元対象条件を指定してください。</td>
				<td align=right valign=bottom norap><small><font color=blue>■</font><A href='JavaScript:window.close()'>閉じる</A></small></td>
			</tr>
		</table>
		<input type="hidden" name="sort_col" <?=value($sort_col)?>>
		<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
		<table <?=LIST_TABLE?> class="small" width=95%>
			<tr class="tch">
<?
				sort_header(1, 'seq');
				sort_header(2, '対象条件名');
?>
			</tr>
<?
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
?>
			<tr class="tc<?=$i % 2?>">
				<td align="center" name="job_name1"><a href="javascript:OnClick_copy(<?=$fetch->ps_search_id?>)"><?=$fetch->ps_seq_no?></a></td>
				<td align="left" name="job_name1"><a href="javascript:OnClick_search(<?=$fetch->ps_search_id?>)"><?=$fetch->ps_title?></a></td>
			</tr>
		<?
		}
		?>
		</table>
	</center>
</form>
</body>
</html>