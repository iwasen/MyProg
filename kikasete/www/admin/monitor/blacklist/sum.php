<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:ブラックリスト管理
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/select.php");
include("$inc/format.php");
include("$inc/list.php");

//メイン処理
set_global('monitor', 'モニター管理', 'ブラックリスト管理', BACK_TOP);

$sub1 = "SELECT mn_blacklist_flg,COUNT(*) AS cnt"
			. " FROM t_monitor WHERE mn_blacklist_flg>0 AND mn_status<>9 GROUP BY mn_blacklist_flg";
$sql = "SELECT bl_blacklist_flg,bl_name,cnt"
		. " FROM m_blacklist LEFT JOIN ($sub1) AS sub1 ON mn_blacklist_flg=bl_blacklist_flg"
		. " ORDER BY bl_blacklist_flg";
$result = db_exec($sql);
$nrow = pg_numrows($result);
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
<table border=0 cellspacing=0 cellpadding=1 width="80%">
	<tr>
		<td class="lt">■フラグ別ブラックリスト登録者数</td>
		<td class="lb">
			<input type="button" value="　戻る　" onclick="history.back()">
		</td>
	</tr>
</table>
</form>

<table <?=LIST_TABLE?> width="80%">
	<tr class="tch">
<?
		sort_header(0, 'フラグ');
		sort_header(0, 'ブラックリスト名');
		sort_header(0, '登録者数');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><?=$fetch->bl_blacklist_flg?></td>
		<td align="center"><?=htmlspecialchars($fetch->bl_name)?></td>
		<td align="center"><?=number_format($fetch->cnt)?></td>
	</tr>
<?
}
?>
</table>
</div>

<? page_footer() ?>
</body>
</html>
