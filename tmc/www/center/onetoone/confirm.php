<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:OneToOneメール・返信確認
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");
include("$inc/mail.php");
include("$inc/greeting.php");
include("$inc/encrypt.php");

// 問い合わせ内容取得
$sql = "SELECT cmc_body,ups_name_kanji,ups_mail_addr,umg_kounyu_kibou_car,tts_tantousha_name,tts_from_addr,sch_sales_channel_name,dlr_dealer_name,ulg_login_id,ulg_password,clu_lineup"
		. " FROM t_comm_log"
		. " JOIN t_comm_contents ON cmc_contents_id=cml_contents_id"
		. " JOIN t_user_login ON ulg_user_id=cml_user_id"
		. " JOIN t_user_personal ON ups_user_id=cml_user_id"
		. " JOIN t_user_manage ON umg_user_id=cml_user_id"
		. " JOIN t_car_model ON crm_car_model_cd=umg_car_model_cd"
		. " JOIN t_dealer ON dlr_dealer_cd=umg_dealer_cd"
		. " JOIN t_tantousha ON tts_tantousha_id=dlr_tantousha_id"
		. " JOIN t_sales_channel ON sch_sales_channel_cd=dlr_sales_channel_cd"
		. " LEFT JOIN t_car_lineup ON clu_sales_channel_cd=dlr_sales_channel_cd AND clu_car_category_cd=crm_car_category_cd"
		. " WHERE cml_comm_id={$_POST['comm_id']}";
$result = db_exec($sql);
if (pg_num_rows($result) == 0)
	redirect('list1.php');
$fetch = pg_fetch_object($result, 0);

// ワンクリックログインURL取得
$login_url = get_system_info('url_user_login');

// メール購読解除URL取得
$stop_url = get_system_info('url_mail_stop');

// 時節の挨拶文取得
$season_greeting = get_season_greeting();

// メール識別ID取得
$new_comm_id = $_POST['new_comm_id'];
$mail_id = '-C' . encrypt_id($new_comm_id);

$body = $_POST['body'] . "\n" . $_POST['footer'];

// 差込変数処理
$body = str_replace('%TANTOUSHA%', $fetch->tts_tantousha_name, $body);
$body = str_replace('%TT_EMAIL%', str_replace('@', $mail_id . '@', $fetch->tts_from_addr), $body);
$body = str_replace('%US_NAME%', $fetch->ups_name_kanji, $body);
$body = str_replace('%CHANNEL%', $fetch->sch_sales_channel_name, $body);
$body = str_replace('%MAIL_ADDR%', $fetch->ups_mail_addr, $body);
$body = str_replace('%CAR_NAME%', $fetch->umg_kounyu_kibou_car, $body);
$body = str_replace('%DEALER%', trim(str_replace('株式会社', '', $fetch->dlr_dealer_name)), $body);
$body = str_replace('%TIMES%', $season_greeting, $body);
$body = str_replace('%LINEUP%', $fetch->clu_lineup, $body);
$body = str_replace('%MYPASS%', "$login_url?t=$fetch->ulg_login_id", $body);
$body = str_replace('%CANCEL%', "$stop_url?t=$fetch->ulg_login_id", $body);
$body = str_replace('%PASSWORD%', "$fetch->ulg_password", $body);
$body = str_replace('%QUOTE%', make_quote($fetch->cmc_body), $body);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
<script type="text/javascript">
<!--
function onSubmit_form1(f) {
	return confirm("メールを送信します。よろしいですか？");
}
//-->
</script>
</head>
<body>

<? center_header('OneToOneメール｜返信メール確認') ?>

<div align="center">
<form method="post" name="form1" action="send.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>■返信メール</td>
	</tr>
	<tr>
		<td class="m1" width="20%">件名(Subject)</td>
		<td class="n1">
			<?=htmlspecialchars($_POST['subject'])?>
		</td>
	</tr>
	<tr>
		<td class="m1">宛先(To)</td>
		<td class="n1">
			<?=htmlspecialchars($fetch->ups_name_kanji)?> &lt;<?=htmlspecialchars($fetch->ups_mail_addr)?>&gt;
		</td>
	</tr>
	<tr>
		<td class="m1">本文・フッタ</td>
		<td class="n1">
			<table border=1 cellspacing=0 cellpadding=3>
				<tr>
					<td><pre><?=htmlspecialchars($body)?></pre></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="m1">添付ファイル</td>
		<td class="n1">
			<table border=0 cellspacing=0 cellpadding=2>
<?
$sql = "SELECT mat_file_no,mat_file_size,mat_file_name FROM t_mail_attachment WHERE mat_comm_id=$new_comm_id";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
$total_size = 0;
for ($i = 0; $i < $nrow; $i++) {
	$fetch2 = pg_fetch_object($result, $i);
	$total_size += $fetch2->mat_file_size;
?>
				<tr>
					<td><?=htmlspecialchars($fetch2->mat_file_name)?></td>
					<td align="right"><?=number_format(round($fetch2->mat_file_size / 1024)) . 'KB'?></td>
				</tr>
<?
}
$total_size = round($total_size / 1024);
?>
				<tr>
					<td>合計</td>
					<td align="right"><?=$total_size > 30 ? '<font color="red">' . number_format($total_size) . 'KB</font>' : number_format($total_size) . 'KB'?></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<br>
<input type="submit" value="　送信　">
<input type="button" value="キャンセル" onclick="history.back()">
<input type="hidden" name="subject" <?=value($_POST['subject'])?>>
<input type="hidden" name="body" <?=value($body)?>>
<input type="hidden" name="comm_id" <?=value($_POST['comm_id'])?>>
<input type="hidden" name="user_id" <?=value($_POST['user_id'])?>>
<input type="hidden" name="to_addr" <?=value($_POST['to_addr'])?>>
<input type="hidden" name="url" <?=value($_POST['url'])?>>
<input type="hidden" name="message_id" <?=value($_POST['message_id'])?>>
<input type="hidden" name="references" <?=value($_POST['references'])?>>
<input type="hidden" name="new_comm_id" <?=value($_POST['new_comm_id'])?>>
<input type="hidden" name="end_flag" <?=value($_POST['end_flag'])?>>
</form>
</div>

<? center_footer() ?>

</body>
</html>
