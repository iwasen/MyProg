<?
/******************************************************
' System :お酒と買物のアンケートモニター事務局用ページ
' Content:クライアント一覧リスト表示
'******************************************************/

$top = "../../..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/format.php");
include("$inc/list.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

//メイン処理
set_global('etc', 'その他管理｜マスタメンテナンス', 'クライアントマスタ', BACK_TOP);

if ($del != '1')
	$where = "WHERE cl_status=0";

$order_by = order_by(1, 0, 'cl_mail_addr', 'cl_name1', 'cl_regist_date', 'cl_status');
$sql = "SELECT cl_client_id,cl_status,cl_regist_date,cl_name1,cl_name2,cl_mail_addr FROM m_client $where $order_by";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
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
		<td class="lt">■クライアント一覧</td>
		<td class="lb">
			<input type="button" value="新規登録" onclick="location.href='new.php'">
			<input type="button" value="　戻る　" onclick="location.href='../menu.php'">
		</td>
	</tr>
	<tr>
		<td class="lc" colspan=2>
			<nobr><input type="checkbox" name="del" <?=value_checked('1', $del)?> onclick="submit()">削除済も表示</nobr>
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
</form>

<table <?=LIST_TABLE?> width="100%">
	<tr class="tch">
<?
		sort_header(1, 'メールアドレス');
		sort_header(2, '名前');
		sort_header(3, '登録日付');
		sort_header(4, '状態');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td><a href="edit.php?client_id=<?=$fetch->cl_client_id?>" title="クライアント情報を表示・変更します"><?=htmlspecialchars($fetch->cl_mail_addr)?></a></td>
		<td><?=htmlspecialchars("$fetch->cl_name1 $fetch->cl_name2")?></td>
		<td align="center"><?=format_date($fetch->cl_regist_date)?></td>
		<td align="center"><?=decode_admin_status($fetch->cl_status)?></td>
	</tr>
<?
}
?>
</table>
</div>

<? page_footer() ?>
</body>
</html>
