<?
/******************************************************
' System :ICPオペレータページ
' Content:送信メールテンプレート一覧リスト表示
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/list.php");

$inc = "$top/inc";
include("$inc/ope_common.php");
include("$inc/login_check.php");
include("$inc/header.php");

//メイン処理
$order_by = order_by(1, 0, 'mt_guide', 'mt_subject', 'mt_from');
$sql = "SELECT mt_template_id,mt_guide,mt_subject,mt_from FROM m_mail_template $order_by";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>icp.net オペレーターページ</title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/default.css">
<? list_script() ?>
</head>
<body>

<div align="center">

<? ope_header() ?>

<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr>

<? ope_menu() ?>

	<!-- 右コンテンツ -->
	<td valign="top">
		<table border="0" cellspacing="0" cellpadding="10" width="100%">
		<tr>
			<td>

<div align="center">
<form name="form1">
<table border="0" cellspacing="0" cellpadding="3" width="100%" class="pagetitle">
	<tr>
		<td>■送信メールテンプレート一覧</td>
		<td align="right">
			<input type="button" value=" 戻る " onclick="location.href='../menu.php'">
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
</form>

<table border="1" cellspacing="0" cellpadding="2" width="100%" class="list">
	<tr>
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
	<tr class="line<?=$i % 2?>">
		<td><a href="edit.php?template_id=<?=$fetch->mt_template_id?>" title="送信メールテンプレートを表示・変更します"><?=htmlspecialchars($fetch->mt_guide)?></a></td>
		<td><?=htmlspecialchars($fetch->mt_subject)?></td>
		<td><?=htmlspecialchars($fetch->mt_from)?></td>
	</tr>
<?
}
?>
</table>
</div>
			</td>
		</tr>
		</table>

	</td>
	<!-- 右コンテンツ -->
</tr>
</table>

<? ope_footer() ?>

</div>
</body>
</html>
