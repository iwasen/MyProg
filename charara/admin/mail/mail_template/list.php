<?
/******************************************************
' System :「きゃららFactory」管理者用ページル
' Content:送信メールテンプレート一覧リスト表示
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/list.php");

//メイン処理
set_global('etc', 'メール配信管理', '送信メールテンプレート', BACK_TOP);

$order_by = order_by(1, 0, 'mt_template_id', 'mt_subject', 'mt_from');
$sql = "SELECT mt_template_id,mt_guide,mt_subject,mt_from FROM m_mail_template $order_by";
$result = db_exec($sql);
$nrow = pg_numrows($result);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function sort_list(sort, dir) {
	var f = document.form1;
	f.sort_col.value = sort;
	f.sort_dir.value = dir;
	f.submit();
}
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">■送信メールテンプレート一覧</td>
		<td class="lb">
			<input type="button" value="　戻る　" onclick="location.href='../../menu.php'">
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
</form>

<table <?=LIST_TABLE?> width="100%">
	<tr class="tch">
<?
		sort_header(1, 'テンプレート名');
		sort_header(2, '件名');
		sort_header(3, '差出人アドレス');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td><a href="edit.php?template_id=<?=$fetch->mt_template_id?>" title="メールテンプレートを表示・変更します"><?=htmlspecialchars($fetch->mt_guide)?></a></td>
		<td><?=htmlspecialchars($fetch->mt_subject)?></td>
		<td><?=htmlspecialchars($fetch->mt_from)?></td>
	</tr>
<?
}
?>
</table>
</div>

<? page_footer() ?>
</body>
</html>
