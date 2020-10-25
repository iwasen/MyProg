<?
/******************************************************
' System :メール配信サービス
' Content:メールアドレス表示
'******************************************************/

$top = '..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/header.php");
include("$inc/database.php");

function decode_status($status) {
	switch ($status) {
	case 0:
		return '未送信';
	case 1:
		return '不正';
	case 2:
		return '送信済み';
	case 3:
		return '不達';
	case 4:
		return '解除';
	}
}

function get_subject($send_mail_id) {
	$sql = "SELECT sd_subject FROM t_send_mail_data WHERE sd_send_mail_id=$send_mail_id";
	return db_fetch1($sql);
}

//メイン処理
set_global('メールアドレス表示', BACK_TOP);

$sql = "SELECT ml_mail_addr"
		. " FROM t_mail_list"
		. " WHERE ml_send_mail_id=$send_mail_id AND ml_status=$status"
		. " ORDER BY ml_mail_addr";
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
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">■<?=decode_status($status)?>メールアドレス一覧　<span class="note">（件名：<?=get_subject($send_mail_id)?>）</span></td>
		<td class="lb">
			<input type="button" value="ダウンロード" onclick="location.href='download.php?send_mail_id=<?=$send_mail_id?>&status=<?=$status?>'">
			<input type="button" value="　戻る　" onclick="location.href='list.php'">
		</td>
	</tr>
</table>
</form>
</div>
<hr class="header">
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	echo $fetch->ml_mail_addr, "<br>\n";
}
?>

<? page_footer() ?>
</body>
</html>
