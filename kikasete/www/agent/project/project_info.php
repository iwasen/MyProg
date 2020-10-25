<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:プロジェクト管理メニュー画面
'******************************************************/

$top = '..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

//メイン処理
set_global2('project', 'プロジェクト管理', 'プロジェクト情報', BACK_TOP);

$sql = "SELECT pj_project_id,pj_project_name,pj_summary,pj_tantousha_id,ts_name1,ts_name2,cl_client_id,cl_name"
	. " FROM t_project"
	. " LEFT JOIN m_tantousha ON pj_tantousha_id=ts_tantousha_id"
	. " LEFT JOIN m_client ON ts_client_id=cl_client_id"
	. " WHERE pj_project_id=$project_id";
$result = db_exec($sql);
if (pg_numrows($result) == 0)
	system_error('project_idが不正', __FILE__);
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

<div align="center">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>■プロジェクト情報</td>
	</tr>
	<tr>
		<td class="m1" width=140>プロジェクトID</td>
		<td class="n1"><?=$fetch->pj_project_id?></td>
	</tr>
	<tr>
		<td class="m1" width=140>プロジェクト名</td>
		<td class="n1"><?=htmlspecialchars($fetch->pj_project_name)?></td>
	</tr>
	<tr>
		<td class="m1">プロジェクト概要</td>
		<td class="n1">
			<textarea class="kanji" name="summary" cols=50 rows=5 readonly><?=htmlspecialchars($fetch->pj_summary)?></textarea>
		</td>
	</tr>
	<tr>
		<td class="m0" colspan=2>■企業情報</td>
	</tr>
	<tr>
		<td class="m1" width=140>企業ID</td>
		<td class="n1"><?=$fetch->cl_client_id?></td>
	</tr>
	<tr>
		<td class="m1" width=140>企業名</td>
		<td class="n1"><?=htmlspecialchars($fetch->cl_name)?></td>
	</tr>
	<tr>
		<td class="m1" width=140>企業担当者ID</td>
		<td class="n1"><?=$fetch->pj_tantousha_id?></td>
	</tr>
	<tr>
		<td class="m1" width=140>企業担当者名</td>
		<td class="n1"><?=htmlspecialchars("$fetch->ts_name1 $fetch->ts_name2")?></td>
	</tr>
</table>
<br>
<input type="button" value="閉じる" onclick='window.close()'>
</div>

</body>
</html>
