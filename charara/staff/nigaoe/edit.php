<?
/******************************************************
' System :「きゃららFactory」作画スタッフ用ページ
' Content:似顔絵申込み写真表示
'******************************************************/

$top = '..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");
include("$inc/format.php");
include("$inc/status.php");
include("inc.php");

// 送受信メール表示
function disp_mail($nigaoe_id) {
	$sql = "SELECT lm_send_recv,lm_date,lm_from_addr,lm_to_addr,lm_subject,lm_body FROM l_mail WHERE lm_nigaoe_id=$nigaoe_id ORDER BY lm_mail_log_id";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		if ($i != 0)
			echo "<hr>";

		echo $fetch->lm_send_recv == 'R' ? "<font color='blue'>【受信】</font>\n" : "<font color='red'>【送信】</font>\n";
		echo 'Subject: ', htmlspecialchars($fetch->lm_subject), "\n";
		echo 'From: ', htmlspecialchars($fetch->lm_from_addr), "\n";
		echo 'To: ', htmlspecialchars($fetch->lm_to_addr), "\n";
		echo 'Date: ', format_datetime($fetch->lm_date), "\n";
		echo "\n";
		echo htmlspecialchars($fetch->lm_body), "\n";
	}
}

set_global('charara', '', 'きゃららFactory 似顔絵申込み写真参照', BACK_TOP);

// 写真画像URL取得
$url_photo = get_system_info('url_photo');

// 似顔絵画像URL取得
$url_nigaoe = get_system_info('url_nigaoe');

$sql = "SELECT nd_nigaoe_id,nd_random_id,nd_status,nd_completion_date,nd_nouki_date,nd_sex_code,nd_age,nd_photo_file,nd_nigaoe_file"
		. " FROM t_nigaoe_data"
		. " WHERE nd_nigaoe_id=$nigaoe_id";
$result = db_exec($sql);
if (pg_num_rows($result))
	$fetch = pg_fetch_object($result, 0);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function onsubmit_form1(f) {
	switch (f.next_action.value) {
	case "make":
		return confirm("この写真の似顔絵を作成しますか？");
	case "upload":
		if (f.nigaoe_file.value == "") {
			alert("似顔絵ファイルを入力してください。");
			f.nigaoe_file.focus();
			return false;
		}
		return confirm("似顔絵ファイルをアップロードします。よろしいですか？");
	}
	return false;
}
//-->
</script>
</head>
<body>

<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" enctype="multipart/form-data" onsubmit="return onsubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>■似顔絵作成情報</td>
	</tr>
	<tr>
		<td class="m1" width="20%">顔写真</td>
		<td class="n1">
			<a href="<?=$url_photo?><?=$fetch->nd_photo_file?>" target="_blank"><img class="photo" src="<?=$url_photo?><?=$fetch->nd_photo_file?>" alt="顔写真"></a>
		</td>
	</tr>
<?
if ($fetch->nd_nigaoe_file) {
?>
	<tr>
		<td class="m1" width="20%">似顔絵画像</td>
		<td class="n1">
			<img class="nigaoe" src="<?=$url_nigaoe?><?=$fetch->nd_nigaoe_file?>" alt="似顔絵画像">
		</td>
	</tr>
<?
}
?>
	<tr>
		<td class="m1">性別</td>
		<td class="n1"><?=disp_sex($fetch->nd_sex_code)?></td>
	</tr>
	<tr>
		<td class="m1">年齢</td>
		<td class="n1"><?=disp_age($fetch->nd_age)?></td>
	</tr>
	<tr>
		<td class="m1">ステータス</td>
		<td class="n1"><?=disp_status($fetch->nd_status)?></td>
	</tr>
	<tr>
		<td class="m1">送受信メール</td>
		<td class="n1"><pre><? disp_mail($nigaoe_id)?></pre></td>
	</tr>
	<tr>
		<td class="m1">似顔絵作成納期</td>
		<td class="n1"><?=format_datetime($fetch->nd_nouki_date)?></td>
	</tr>
<?
if ($fetch->nd_completion_date) {
?>
	<tr>
		<td class="m1">似顔絵完成日時</td>
		<td class="n1"><?=format_datetime($fetch->nd_completion_date)?></td>
	</tr>
<?
}
?>
<?
if ($fetch->nd_status == STATUS_MAKING) {
?>
	<tr>
		<td class="m1">似顔絵アップロードファイル</td>
		<td class="n1">
			<input type="file" name="nigaoe_file" size=70>
			<br><span class="note">（作成した似顔絵画像ファイル名を入力してください）</span>
		</td>
	</tr>
<?
}
?>
</table>
<br>
<?
switch ($fetch->nd_status) {
case STATUS_PHOTO_RECEIVED:
case STATUS_STAFF_ASSIGNED:
?>
<input type="button" value="メールを返信する" onclick="location.href='mail.php?nigaoe_id=<?=$nigaoe_id?>'">
<input type="submit" value="この写真の似顔絵を作成する" onclick="document.form1.next_action.value='make'">
<?
	break;
case STATUS_MAKING:
?>
<input type="submit" value="似顔絵をアップロードする" onclick="document.form1.next_action.value='upload'">
<?
	break;
}
?>
<input type="button" value="　戻る　" onclick="location.href='list.php'">
<input type="hidden" name="next_action">
<input type="hidden" name="nigaoe_id" <?=value($nigaoe_id)?>>

</div>
</form>

<? page_footer() ?>

</body>
</html>
