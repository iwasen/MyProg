<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:OneToOneメール・返信入力
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

// Re: を削除
function delete_re($s) {
	while (true) {
		$s = trim($s);
		if (strcasecmp ("Re: ", substr($s, 0, 4)) == 0)
			$s = substr($s, 4);
		else
			break;
	}
	return $s;
}

// 差込変数処理
function sashikomi(&$body, &$fetch, $mail_id, $season_greeting, $login_url, $stop_url) {
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
}

// 入力パラメータ
$comm_id = $_REQUEST['comm_id'];

// 問い合わせ内容取得
$sql = "SELECT cml_user_id,cml_message_id,cml_references,cmc_subject,cmc_body,ups_name_kanji,ups_mail_addr,umg_kounyu_kibou_car,tts_tantousha_name,tts_from_addr,sch_sales_channel_name,dlr_dealer_name,ulg_login_id,ulg_password,clu_lineup"
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
		. " WHERE cml_comm_id=$comm_id";
$result = db_exec($sql);
if (pg_num_rows($result) == 0)
	redirect('list1.php');
$fetch = pg_fetch_object($result, 0);

if ($_POST['new_comm_id']) {
	$new_comm_id = $_POST['new_comm_id'];
	$subject = $_POST['subject'];
	$body = $_POST['body'];
	$footer = $_POST['footer'];
	$end_flag = $_POST['end_flag'];
	$url = $_POST['url'];

	if (is_array($_POST['delete_attach'])) {
		foreach ($_POST['delete_attach'] as $key => $value) {
			db_delete('t_mail_attachment', "mat_comm_id=$new_comm_id AND mat_file_no=$key");
		}
	}
} else {
	// メール一時保存済みチェック
	$sql = "SELECT * FROM t_mail_save WHERE msv_comm_id=$comm_id";
	$result2 = db_exec($sql);
	if (pg_num_rows($result2) == 0) {
		// 送信メールテンプレート取得
		get_mail_template('onetoone', $subject, $from, $cc, $bcc, $repty_to, $body, $footer);

		// 件名取得
		$subject = str_replace('%SUBJECT%', delete_re($fetch->cmc_subject), $subject);

		// ワンクリックログインURL取得
		$login_url = get_system_info('url_user_login');

		// メール購読解除URL取得
		$stop_url = get_system_info('url_mail_stop');

		// 時節の挨拶文取得
		$season_greeting = get_season_greeting();

		// メール識別ID取得
		$new_comm_id = get_next_seq('t_comm_log', 'cml_comm_id');
		$mail_id = '-C' . encrypt_id($new_comm_id);

		// 差込変数処理
		sashikomi($body, $fetch, $mail_id, $season_greeting, $login_url, $stop_url);
		sashikomi($footer, $fetch, $mail_id, $season_greeting, $login_url, $stop_url);
	} else {
		$fetch2 = pg_fetch_object($result2, 0);
		$new_comm_id = $fetch2->msv_new_comm_id;
		$subject = $fetch2->msv_subject;
		$body = $fetch2->msv_body;
		$footer = $fetch2->msv_footer;
		$end_flag = $fetch2->msv_end_flag;
	}
	$url = $_GET['url'];
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
<script type="text/javascript">
<!--
function onSubmit_form1(f) {
	if (f.action == "confirm.php") {
		if (f.subject.value == "") {
			alert("件名を入力してください。");
			f.subject.focus();
			return false;
		}
		if (f.body.value == "") {
			alert("本文を入力してください。");
			f.body.focus();
			return false;
		}
	}
	return true;
}
function onclick_attadd() {
  var gWindowWidth = 550;
  var gWindowHeight = 350;
  var st = 'width=' + gWindowWidth;
  st += ',';
  st += 'height=' + gWindowHeight;
  st += ',';
  var cw = (screen.width / 2) - (gWindowWidth / 2);
  var ch = (screen.height / 2) - (gWindowHeight / 2);
  if (navigator.userAgent.indexOf("MSIE") != -1) {
    st += 'left=' + cw;
  } else {
    st += 'screenX=' + cw;
  }
  st += ',';
  if (navigator.userAgent.indexOf("MSIE") != -1) {
    st += 'top=' + ch;
  } else {
    st += 'screenY=' + ch;
  }
  st += ',';
  st += 'scrollbars=1';
  window.open("attachment.php?comm_id=<?=$new_comm_id?>", "attachment", st);
}
//-->
</script>
</head>
<body onload="document.form1.subject.focus()">

<? center_header('OneToOneメール｜返信入力') ?>

<div align="center">
<form method="post" name="form1" action="confirm.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>■返信メールを入力してください。</td>
	</tr>
	<tr>
		<td class="m1" width="20%">件名(Subject)<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="subject" size=70 maxlength=100 <?=value($subject)?>>
			<span class="note">（全角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">宛先(To)</td>
		<td class="n1">
			<?=htmlspecialchars($fetch->ups_name_kanji)?> &lt;<?=htmlspecialchars($fetch->ups_mail_addr)?>&gt;
		</td>
	</tr>
	<tr>
		<td class="m1">本文<?=MUST_ITEM?></td>
		<td class="n1">
			<textarea class="kanji" name="body" cols=78 rows=30><?=$body?></textarea>
		</td>
	</tr>
	<tr>
		<td class="m1">フッタ</td>
		<td class="n1">
			<textarea class="kanji" name="footer" cols=78 rows=10 readonly><?=$footer?></textarea>
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
					<td><input type="submit" value="削除" name="delete_attach[<?=$fetch2->mat_file_no?>]" onclick="document.form1.action='answer.php'"></td>
					<td><a href="<?=$top?>/common/show_attach.php?comm_id=<?=$new_comm_id?>&file_no=<?=urlencode($fetch2->mat_file_no)?>" target="_blank"><?=htmlspecialchars($fetch2->mat_file_name)?></a></td>
					<td align="right"><?=number_format(round($fetch2->mat_file_size / 1024)) . 'KB'?></td>
				</tr>
<?
}
$total_size = round($total_size / 1024);
?>
				<tr>
					<td><input type="button" value="追加" onclick="onclick_attadd()"></td>
					<td>合計</td>
					<td align="right"><?=$total_size > 30 ? '<font color="red">' . number_format($total_size) . 'KB</font>' : number_format($total_size) . 'KB'?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="m1">終了フラグ</td>
		<td class="n1">
			<input type="checkbox" name="end_flag" value="1" <?=checked($end_flag == DBTRUE)?>>終了
			<span class="note">（このスレッドを終了させ、対応履歴に残します）</span>
		</td>
	</tr>
</table>
<br>
<input type="submit" value="一時保存" onclick="document.form1.action='save.php'">
<input type="submit" value="　送信　" onclick="document.form1.action='confirm.php'">
<input type="button" value="キャンセル" onclick="location.href='<?=$url?>'">
<input type="hidden" name="comm_id" value="<?=$comm_id?>">
<input type="hidden" name="user_id" <?=value($fetch->cml_user_id)?>>
<input type="hidden" name="to_addr" <?=value($fetch->ups_mail_addr)?>>
<input type="hidden" name="url" <?=value($url)?>>
<input type="hidden" name="message_id" <?=value($fetch->cml_message_id)?>>
<input type="hidden" name="references" <?=value($fetch->cml_references)?>>
<input type="hidden" name="new_comm_id" <?=value($new_comm_id)?>>
</form>
</div>

<? center_footer() ?>

</body>
</html>
