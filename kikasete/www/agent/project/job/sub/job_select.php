<?
/******************************************************
' System :きかせて・netエージェント用ページ
' Content:ジョブ一覧表示
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

//window_size 600:300

//メイン処理
set_global2('project', 'プロジェクト管理', 'コピー元ジョブ選択', BACK_TOP);

$order_by = order_by(1, 1, 'pj_project_id,jb_job_id', 'pj_project_name,jb_job_id', 'jb_job_id', 'jb_job_name');
$sql = "SELECT pj_project_id,pj_project_name,jb_job_id,jb_job_name FROM t_job LEFT JOIN t_project ON pj_project_id=jb_project_id $order_by";
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
function OnClick_job_id(job_id) {
	opener.CopyJob(job_id);
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
				<td class="m0" colspan=2 align="left">■コピー元ジョブを指定してください。</td>
				<td align=right valign=bottom norap><small><font color=blue>■</font><A href='JavaScript:window.close()'>閉じる</A></small></td>
			</tr>
		</table>
		<input type="hidden" name="sort_col" <?=value($sort_col)?>>
		<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
		<table <?=LIST_TABLE?> class="small" width=95%>
			<tr class="tch">
<?
				sort_header(1, 'ﾌﾟﾛｼﾞｪｸﾄID');
				sort_header(2, 'プロジェクト名');
				sort_header(3, 'ジョブID');
				sort_header(4, 'ジョブ名');
?>
			</tr>
<?
		$same_project = new join_same_item;
		$same_project->prepare($result, 'pj_project_id');

		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
			$row_count = $same_project->get_row_count();
?>
			<tr>
<?
			if ($row_count > 0) {
?>
				<td align="center" rowspan=<?=$row_count?>><?=$fetch->pj_project_id?></td>
				<td align="left" rowspan=<?=$row_count?>><?=$fetch->pj_project_name?></td>
<?
			}
?>
				<td align="center" class="tc<?=$i % 2?>" title="選択したジョブ情報をコピーします"><a href='JavaScript:OnClick_job_id(<?=$fetch->jb_job_id?>)'><?=$fetch->jb_job_id?></a></td>
				<td align="left" class="tc<?=$i % 2?>"><?=$fetch->jb_job_name?></td>
			</tr>
		<?
		}
		?>
		</table>
	</center>
</form>
</body>
</html>