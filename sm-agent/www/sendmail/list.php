<?
/******************************************************
' System :メール配信サービス
' Content:配信メール一覧表示
'******************************************************/

$top = '..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/header.php");
include("$inc/database.php");
include("$inc/list.php");
include("$inc/format.php");
include("$inc/select.php");

// 送信状態
function decode_send_status($code) {
	switch ($code) {
	case 0:
		return '未送信';
	case 1:
		return '送信待ち';
	case 2:
		return '送信中';
	case 3:
	case 4:
		return '送信終了';
	}
	return '不明';
}

// 状態別カウント表示
function disp_count($send_mail_id, $count, $status) {
	if ($count)
		echo "<a href='mailaddr.php?send_mail_id=$send_mail_id&status=$status'>", number_format($count), "</a>\n";
	else
		echo number_format($count), "\n";
}

// エラーカウント表示
function disp_err_count($send_mail_id, $count, $status) {
	if ($count != 0 && $status == 0)
		echo "<a href='erraddr.php?send_mail_id=$send_mail_id'>", number_format($count), "</a>\n";
	else
		disp_count($send_mail_id, $count, 1);
}

//メイン処理
set_global('配信メールリスト', $_SESSION['sma_system'] == 'tmc' ? BACK_NONE : BACK_LOGOFF);

// セッション登録
get_session_vars($pset, 'sendmail', 'displine', 'sort_col', 'sort_dir', 'page');

// ソート条件
$order_by = order_by(1, 1, 'md_seq_no', 'sd_subject', 'md_send_date', 'sd_end_date', 'sd_status', 'mc_count0', 'mc_count1', 'mc_count2', 'mc_count3', 'mc_count4');

// 表示行数条件
$limit = disp_limit();

$sql = "SELECT md_send_mail_id,md_seq_no,md_send_date,mc_count0,mc_count1,mc_count2,mc_count3,mc_count4,sd_subject,sd_status,sd_end_date"
		. " FROM t_mail_data"
		. " JOIN t_mail_count ON mc_send_mail_id=md_send_mail_id"
		. " JOIN t_send_mail_data ON sd_send_mail_id=md_send_mail_id"
		. " WHERE md_user_id=$user_login_id"
		. " $order_by $limit";
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
		<td class="lt">■配信メール一覧</td>
		<td class="lb">
			<input type="button" value="新規登録" onclick="location.href='new.php'">
		</td>
	</tr>
	<tr>
		<td colspan=2 class="lc">
			<nobr>表示行数<select name="displine" onchange="submit()"><?select_displine($displine)?></select><input type="button" value="前頁" onclick="page.value=<?=$page - 1?>;submit()"<?=disabled($page == 0)?>><input type="button" value="次頁"onclick="page.value=<?=$page + 1?>;submit()"<?=disabled($displine == 0 || $nrow < $displine)?>></nobr>
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="page" value=0>
<input type="hidden" name="pset" value=1>
</form>

<table <?=LIST_TABLE?> width="100%" class="small">
	<tr class="tch">
<?
		sort_header(1, 'ＩＤ', 2);
		sort_header(2, '件名', 2);
		sort_header(3, '送信開始日時', 2);
		sort_header(4, '送信終了日時', 2);
		sort_header(5, '状態', 2);
		sort_header(0, '状態別件数', 1, 5);
?>
	</tr>
	<tr class="tch">
<?
		sort_header(6, '未送信');
		sort_header(7, '不正');
		sort_header(8, '有効');
		sort_header(9, '不達');
		sort_header(10, '解除');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$send_mail_id = $fetch->md_send_mail_id;
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><?=$fetch->md_seq_no?></td>
		<td><a href="edit.php?send_mail_id=<?=$send_mail_id?>" title="メール内容を表示・変更します"><?=htmlspecialchars($fetch->sd_subject)?></a></td>
		<td align="center"><?=format_datetime($fetch->md_send_date, '指定なし')?></td>
		<td align="center"><?=format_datetime($fetch->sd_end_date, '-')?></td>
		<td align="center"><?=decode_send_status($fetch->sd_status)?></td>
		<td align="right"><? disp_count($send_mail_id, $fetch->mc_count0, 0)?></td>
		<td align="right"><? disp_err_count($send_mail_id, $fetch->mc_count1, $fetch->sd_status)?></td>
		<td align="right"><? disp_count($send_mail_id, $fetch->mc_count2, 2)?></td>
		<td align="right"><? disp_count($send_mail_id, $fetch->mc_count3, 3)?></td>
		<td align="right"><? disp_count($send_mail_id, $fetch->mc_count4, 4)?></td>
	</tr>
<?
}
?>
</table>
</div>

<? page_footer() ?>
</body>
</html>
