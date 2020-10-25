<?
/******************************************************
' System :きかせて・netエージェント用ページ
' Content:質問文テンプレート一覧
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

//質問項目数カウント
function enq_count($enquete_id) {
	$sql = "SELECT COUNT(*) FROM t_enq_question WHERE eq_enquete_id=$enquete_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	return $nrow;
}

//メイン処理
set_global2('project', 'プロジェクト管理', '質問文テンプレート一覧', BACK_TOP);

$order_by = order_by(1, 1, 'pq_seq_no', 'pq_title');
$sql = "SELECT pq_seq_no,pq_title,pq_enquete_id FROM m_pjt_question";
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

function OnClick_copy(enquete_id) {
	if (confirm("質問文をコピーします。よろしいですか？")) {
		opener.CopyQuestion(enquete_id);
		close();
	}
}
function OnClick_question(pq_no) {
  var win;
  win = window.open("tmp_question2.php?pq_no=" + pq_no, "tmp_question2");
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
		<input type="hidden" name="test" value="!!test!!">
		<input type="hidden" name="sort_col" <?=value($sort_col)?>>
		<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
		<table <?=LIST_TABLE?> class="small" width=95%>
			<tr class="tch">
<?
				sort_header(1, 'seq');
				sort_header(2, '質問文名');
?>
			</tr>
<?
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
?>
			<tr class="tc<?=$i % 2?>">
				<input type="hidden" name="en_enquete_id" <?=value($fetch->pq_enquete_id)?>>
				<input type='hidden' name='en_count' value="<?=enq_count($fetch->pq_enquete_id)?>">
				<td align="center" name="pq_no"><a href="javascript:OnClick_copy(<?=$fetch->pq_enquete_id?>)"><?=$fetch->pq_seq_no?></a></td>
				<td align="left" name="pq_title"><a href="javascript:OnClick_question(<?=$fetch->pq_seq_no?>)"><?=$fetch->pq_title?></a></td>
			</tr>
		<?
		}
		?>
		</table>
	</center>
</form>
</body>
</html>