<?
/******************************************************
' System :メール配信サービス
' Content:ユーザ一覧表示
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/common.php");
include("$inc/header.php");
include("$inc/database.php");
include("$inc/list.php");
include("$inc/format.php");
include("$inc/select.php");

//メイン処理
set_global('登録ユーザ一覧', BACK_NONE);

// セッション登録
get_session_vars($pset, 'user', 'sort_col', 'sort_dir');

// ソート条件
$order_by = order_by(1, 0, 'us_mail_addr', 'us_name1||us_name2', 'us_regist_date');

$sql = "SELECT us_user_id,us_mail_addr,us_password,us_name1,us_name2,us_regist_date"
		. " FROM m_user"
		. " $order_by";
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
<form method="post" name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">■登録ユーザ一覧</td>
		<td class="lb">
			<input type="button" value="新規登録" onclick="location.href='new.php'">
			<input type="button" value="　戻る　" onclick="location.href='../menu.php'">
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="pset" value=1>
</form>

<table <?=LIST_TABLE?> width="100%" class="small">
	<tr class="tch">
<?
		sort_header(1, 'メールアドレス');
		sort_header(2, '名前');
		sort_header(3, '登録日時');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td><a href="edit.php?user_id=<?=$fetch->us_user_id?>" title="ユーザ情報を表示・変更します"><?=htmlspecialchars($fetch->us_mail_addr)?></a></td>
		<td align="center"><?=htmlspecialchars("$fetch->us_name1 $fetch->us_name2")?></td>
		<td align="center"><?=format_date($fetch->us_regist_date)?></td>
	</tr>
<?
}
?>
</table>
</div>

<? page_footer() ?>
</body>
</html>
