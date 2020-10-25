<?
/******************************************************
' System :きかせて・netエージェント用ページ
' Content:告知メールテンプレート一覧
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
set_global2('project', 'プロジェクト管理', '告知メールテンプレート一覧', BACK_TOP);

$order_by = order_by(1, 1, 'pm_seq_no', 'pm_title');
$sql = "SELECT pm_seq_no,pm_title,pm_body FROM m_pjt_mail";
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
function OnClick_mail(pm_no) {
  var win;
  win = window.open("tmp_mail2.php?pm_no=" + pm_no, "tmp_mail2");
}
function OnClick_copy(seq_no) {
	if (confirm("テンプレートをコピーします。よろしいですか？")) {
		opener.document.form1.en_description.value += document.form1("pm_body[" + seq_no + "]").value;
		window.close();
	}
}

//-->
</SCRIPT>
</head>
<body>
<form method="post" name="form1">
	<center>
		<table width=95%>
			<tr>
				<td class="m0" colspan=2 align="left">■コピー元テンプレートを指定してください。</td>
				<td align=right valign=bottom norap><small><font color=blue>■</font><A href='JavaScript:window.close()'>閉じる</A></small></td>
			</tr>
		</table>
		<input type="hidden" name="sort_col" <?=value($sort_col)?>>
		<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
		<table <?=LIST_TABLE?> class="small" width=95%>
			<tr class="tch">
<?
				sort_header(1, 'seq');
				sort_header(2, 'タイトル');
?>
			</tr>
<?
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
?>
			<tr class="tc<?=$i % 2?>">
				<input type='hidden' name='pm_body[<?=$fetch->pm_seq_no?>]' value="<?=$fetch->pm_body?>">
				<td align="center" name="job_name1"><a href="javascript:OnClick_copy(<?=$fetch->pm_seq_no?>)"><?=$fetch->pm_seq_no?></a></td>
				<td align="left" name="job_name1"><a href="javascript:OnClick_mail(<?=$fetch->pm_seq_no?>)"><?=$fetch->pm_title?></a></td>
			</tr>
		<?
		}
		?>
		</table>
	</center>
</form>
</body>
</html>