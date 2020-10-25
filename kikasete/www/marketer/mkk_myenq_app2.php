<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_enquete.php");
include("$inc/mail.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

// 事務局・エージェント宛て通知メール送信
function mail_send1(&$myenq, $apply) {
	$comment = "\n" .
		"◇アンケートご担当者　　：" . $_POST['tantou_name'] . "\n" .
		"◇ご連絡先電話番号　　　：" . $_POST['renraku_tel'] . "\n" .
		"◇ご連絡先メールアドレス：" . $_POST['renraku_mail'] . "\n" .
		"◇その他、ご連絡可能な時間帯、ご質問など：\n" . $_POST['sonota'];

	$owner_id = $_SESSION['ss_owner_id'];
	$enquete = &$myenq->enquete;

	$sql = "SELECT mr_mail_addr,mr_name1,mr_name2,mr_agent_id,mr_kinmu_name FROM t_marketer WHERE mr_marketer_id=$owner_id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$mail_addr = $fetch->mr_mail_addr;
		$name1 = $fetch->mr_name1;
		$name2 = $fetch->mr_name2;
		$agent_id = $fetch->mr_agent_id;
		$kinmu_name = $fetch->mr_kinmu_name;
	}

	get_mail_template('mye_ag', $subject, $from, $cc, $bcc, $repty_to, $body);

	$fetch = get_system_info('sy_url_agant');
	$url_agent = $fetch->sy_url_agant;

	$body = str_replace('%APP_DATE%', date('Y/m/d H:i'), $body);
	$body = str_replace('%MARKETER_NAME%', "$name1 $name2", $body);
	$body = str_replace('%MAIL_ADDR%', $mail_addr, $body);
	$body = str_replace('%COMPANY_NAME%', $kinmu_name, $body);
	$body = str_replace('%TITLE%', $enquete->title, $body);
	$body = str_replace('%START_DATE%', "$enquete->start_date_y/$enquete->start_date_m/$enquete->start_date_d", $body);
	$body = str_replace('%END_DATE%', "$enquete->end_date_y/$enquete->end_date_m/$enquete->end_date_d", $body);
	$body = str_replace('%ENQ_TOTAL%', $enquete->get_question_num(), $body);
	$body = str_replace('%REAPPLY_FLG%', $apply, $body);
	$body = str_replace('%COMMENT%', $comment, $body);
	$body = str_replace('%MAIL_PREVIEW%', "{$url_agent}preview/mail.php?id=$myenq->marketer_id&no=$myenq->my_enq_no", $body);
	$body = str_replace('%WEB_PREVIEW%', "{$url_agent}preview/web.php?id=$myenq->marketer_id&no=$myenq->my_enq_no", $body);
	$body = str_replace('%SEND_NUM%', $myenq->send_num, $body);
	$body = str_replace('%CELL_FLAG%', $myenq->cell->cell_option == 1 ? 'なし' : 'あり', $body);
	$body = str_replace('%CELL_NUM%', $myenq->cell->cell_num, $body);

	// 事務局宛て送信
	$fetch = get_system_info('sy_myenq_to');
	send_mail($subject, $fetch->sy_myenq_to, $from, $body, $cc, $bcc, $reply_to);

	// エージェント宛て送信
	if ($agent_id) {
		$sql = "SELECT ag_mail_addr,ag_bcc_addr FROM t_agent WHERE ag_agent_id=$agent_id";
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);
			send_mail($subject, $fetch->ag_mail_addr, $from, $body, $cc, $fetch->ag_bcc_addr, $reply_to);
		}
	}
}

// マーケター宛て通知メール送信
function mail_send2(&$myenq, $apply) {
	$marketer_id = $_SESSION['ss_marketer_id'];
	$owner_id = $_SESSION['ss_owner_id'];
	$enquete = &$myenq->enquete;

	$sql = "SELECT mr_mail_addr,mr_name1,mr_name2,mr_kinmu_name FROM t_marketer WHERE mr_marketer_id=$owner_id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$mail_addr = $fetch->mr_mail_addr;
		$name1 = $fetch->mr_name1;
		$name2 = $fetch->mr_name2;
		$kinmu_name = $fetch->mr_kinmu_name;
	}

	get_mail_template('mye_mk', $subject, $from, $cc, $bcc, $repty_to, $body);

	$body = str_replace('%APP_DATE%', date('Y/m/d H:i'), $body);
	$body = str_replace('%MARKETER_NAME%', "$name1 $name2", $body);
	$body = str_replace('%MAIL_ADDR%', $mail_addr, $body);
	$body = str_replace('%COMPANY_NAME%', $kinmu_name, $body);
	$body = str_replace('%TITLE%', $enquete->title, $body);
	$body = str_replace('%START_DATE%', "$enquete->start_date_y/$enquete->start_date_m/$enquete->start_date_d", $body);
	$body = str_replace('%END_DATE%', "$enquete->end_date_y/$enquete->end_date_m/$enquete->end_date_d", $body);
	$body = str_replace('%ENQ_TOTAL%', $enquete->get_question_num(), $body);
	$body = str_replace('%REAPPLY_FLG%', $apply, $body);
	$body = str_replace('%SEND_NUM%', $myenq->send_num, $body);
	$body = str_replace('%CELL_FLAG%', $myenq->cell->cell_option == 1 ? 'なし' : 'あり', $body);
	$body = str_replace('%CELL_NUM%', $myenq->cell->cell_num, $body);

	$myenq->get_notify_mail_addr($to, $cc);
	send_mail($subject, $to, $from, $body, $cc, $bcc, $reply_to);
}

if (!isset($_SESSION['ss_my_enq']))
	redirect('mkk_myenq.php');
$myenq = &$_SESSION['ss_my_enq'];
$enquete = &$myenq->enquete;

$status = $enquete->status;

// ポイント設定
$enquete->point = $enquete->get_enquete_point();

// ステータスを申請中に設定
$enquete->status = 2;
$myenq->save_enquete = true;
$myenq->write_db();


// 申請種別
$apply = ($status == 1) ? '申請' : '再申請';

// 通知メール送信
mail_send1($myenq, $apply);
mail_send2($myenq, $apply);

// セッション変数削除
unset($_SESSION['ss_my_enq']);
session_unregister('ss_my_enq');
?>
<? marketer_header('Ｍｙアンケート', PG_NULL) ?>

<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="10" border="0"></td>
	</tr>
</table>

<table border="0" cellspacing="0" cellpadding="0" bgcolor="ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		<td width="778" align="center" valign="top">
			<table border="0" cellspacing="0" cellpadding="0" width="778">
				<tr>
					<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
					<td width="768" valign="top"><img src="images/t_mk_myenq2.gif" width="538" height="20"></td>
				</tr>
			</table>
			<form method="" action="mkk_myenq_app2.php">
			<br>
			<center>
			<table width="500" border="0" cellspacing="0" cellpadding="5" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0">
				<tr>
					<td width="100%" valign="top" bgcolor="#ffffff" align="center">

						アンケートの申請を完了しました

					</td>
				</tr>
			</table>
			<br>

<a href="mkk_myenq.php"><img src="images/mkk_bt/back.gif" alt="戻る" width="108" height="31" border="0"></a>

			</center>
			</form>
		</td>
	</tr>
</table>

<? marketer_footer() ?>
