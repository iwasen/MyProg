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
include("$inc/mypage.php");
include("$inc/header2.php");

// 事務局宛て通知メール送信
function mail_send1(&$enquete, $apply, $staff_id, $my_enq_no) {
	global $comment;

	$staff_id = $_SESSION['ss_staff_id'];

	$sql = "SELECT st_mail_addr,st_name1,st_name2,st_kinmu_name FROM t_staff WHERE st_staff_id=$staff_id";
	$result = db_exec($sql);
	if (pg_num_rows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$mail_addr = $fetch->st_mail_addr;
		$name1 = $fetch->st_name1;
		$name2 = $fetch->st_name2;
		$kinmu_name = $fetch->st_kinmu_name;
	}

	get_mail_template('mye_ag', $subject, $from, $cc, $bcc, $repty_to, $body);

	$body = str_replace('%APP_DATE%', date('Y/m/d H:i'), $body);
	$body = str_replace('%STAFF_NAME%', "$name1 $name2", $body);
	$body = str_replace('%MAIL_ADDR%', $mail_addr, $body);
	$body = str_replace('%COMPANY_NAME%', $kinmu_name, $body);
	$body = str_replace('%TITLE%', $enquete->title, $body);
	$body = str_replace('%START_DATE%', "$enquete->start_date_y/$enquete->start_date_m/$enquete->start_date_d", $body);
	$body = str_replace('%END_DATE%', "$enquete->end_date_y/$enquete->end_date_m/$enquete->end_date_d", $body);
	$body = str_replace('%ENQ_TOTAL%', $enquete->get_question_num(), $body);
	$body = str_replace('%REAPPLY_FLG%', $apply, $body);
	$body = str_replace('%COMMENT%', $comment, $body);

	// 事務局宛て送信
	$fetch = get_system_info('sy_myenq_to');
	send_mail($subject, $fetch->sy_myenq_to, $from, $body, $cc, $bcc, $reply_to);
}

// スタッフ宛て通知メール送信
function mail_send2(&$enquete, $apply) {
	$staff_id = $_SESSION['ss_staff_id'];

	$sql = "SELECT st_mail_addr,st_name1,st_name2 FROM t_staff WHERE st_staff_id=$staff_id";
	$result = db_exec($sql);
	if (pg_num_rows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$mail_addr = $fetch->st_mail_addr;
		$name1 = $fetch->st_name1;
		$name2 = $fetch->st_name2;
	}

	get_mail_template('mye_mk', $subject, $from, $cc, $bcc, $repty_to, $body);

	$body = str_replace('%APP_DATE%', date('Y/m/d H:i'), $body);
	$body = str_replace('%STAFF_NAME%', "$name1 $name2", $body);
	$body = str_replace('%MAIL_ADDR%', $mail_addr, $body);
	$body = str_replace('%TITLE%', $enquete->title, $body);
	$body = str_replace('%START_DATE%', "$enquete->start_date_y/$enquete->start_date_m/$enquete->start_date_d", $body);
	$body = str_replace('%END_DATE%', "$enquete->end_date_y/$enquete->end_date_m/$enquete->end_date_d", $body);
	$body = str_replace('%ENQ_TOTAL%', $enquete->get_question_num(), $body);
	$body = str_replace('%REAPPLY_FLG%', $apply, $body);

	if ($staff_id != $staff_id) {
		$sql = "SELECT st_mail_addr FROM t_staff WHERE st_staff_id=$staff_id";
		$result = db_exec($sql);
		if (pg_num_rows($result)) {
			$fetch = pg_fetch_object($result, 0);
			$to = $fetch->st_mail_addr;
			$cc = $mail_addr;
		}
	} else
		$to = $mail_addr;

	send_mail($subject, $to, $from, $body, $cc, $bcc, $reply_to);
}

// アンケートポイント取得
function get_enquete_point(&$enquete) {
	$point = 0;
	foreach ($enquete->question as $question) {
		if ($question->question_type == 3)
			$point += 20;
		else
			$point += 10;
	}
	return $point;
}

if (!isset($_SESSION['ss_my_enq']))
	redirect('myenq.php');
$myenq = &$_SESSION['ss_my_enq'];
$enquete = &$myenq->enquete;

$status = $enquete->status;

// ポイント設定
$enquete->point = get_enquete_point($enquete);

// ステータスを申請中に設定
$enquete->status = 2;
$myenq->save_enquete = true;
$myenq->write_db();


// 申請種別
$apply = ($status == 1) ? '申請' : '再申請';

// 通知メール送信
mail_send1($enquete, $apply, $myenq->staff_id, $myenq->my_enq_no);
mail_send2($enquete, $apply);

// セッション変数削除
unset($_SESSION['ss_my_enq']);
session_unregister('ss_my_enq');
?>
<? staff_header('アンケート登録') ?>

<table width="720" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><img src="images/shim.gif" width="1" height="10" border="0"></td>
	</tr>
</table>

			<form method="" action="myenq_app2.php">
			<br>
			<center>
			<table width="500" border="1" cellspacing="0" cellpadding="5" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0">
				<tr>
					<td width="100%" valign="top" bgcolor="#ffffff" align="center">
						<font size="2" class="honbun2">
						アンケートの申請を完了しました
						</font>
					</td>
				</tr>
			</table>
			<br>
			<input type="button" value="　戻る　" onclick="location.href='myenq.php'">
			</center>
			</form>

<? staff_footer() ?>
