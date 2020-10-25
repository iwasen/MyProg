<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/pro_enquete.php");
include("$inc/pro_enq_target.php");
include("$inc/mail.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

// 事務局・エージェント宛て通知メール送信
function mail_send1(&$pro_enq, $apply) {
	$comment = "\n" .
		"◇アンケートご担当者　　：" . $_POST['tantou_name'] . "\n" .
		"◇ご連絡先電話番号　　　：" . $_POST['renraku_tel'] . "\n" .
		"◇ご連絡先メールアドレス：" . $_POST['renraku_mail'] . "\n" .
		"◇その他、ご連絡可能な時間帯、ご質問など：\n" . $_POST['sonota'];

	$owner_id = $_SESSION['ss_owner_id'];
	$enquete = &$pro_enq->enquete;
	$enquete2 = &$pro_enq->enquete2;

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

	get_mail_template('pe_ag', $subject, $from, $cc, $bcc, $repty_to, $body);

	$fetch = get_system_info('sy_url_agant,sy_url_marketer');
	$url_agent = $fetch->sy_url_agant;
	$url_marketer = $fetch->sy_url_marketer;

	$body = str_replace('%APP_DATE%', date('Y/m/d H:i'), $body);
	$body = str_replace('%MARKETER_NAME%', "$name1 $name2", $body);
	$body = str_replace('%MAIL_ADDR%', $mail_addr, $body);
	$body = str_replace('%COMPANY_NAME%', $kinmu_name, $body);
	$body = str_replace('%TITLE%', $enquete->title, $body);
	$body = str_replace('%START_DATE%', "$enquete->start_date_y/$enquete->start_date_m/$enquete->start_date_d", $body);
	$body = str_replace('%END_DATE%', "$enquete->end_date_y/$enquete->end_date_m/$enquete->end_date_d", $body);
	$body = str_replace('%ENQ_TOTAL%', $enquete2->get_question_num(), $body);
	$body = str_replace('%REAPPLY_FLG%', $apply, $body);
	$body = str_replace('%COMMENT%', $comment, $body);
	$body = str_replace('%MAIL_PREVIEW%', "{$url_agent}preview/mail.php?id=$pro_enq->marketer_id&pno=$pro_enq->pro_enq_no", $body);
	$body = str_replace('%WEB_PREVIEW%', "{$url_agent}preview/web.php?id=$pro_enq->marketer_id&pno=$pro_enq->pro_enq_no", $body);
	$body = str_replace('%SEND_NUM%', $pro_enq->send_num, $body);
	$body = str_replace('%CELL_FLAG%', $pro_enq->cell->cell_option == 1 ? 'なし' : 'あり', $body);
	$body = str_replace('%CELL_NUM%', $pro_enq->cell->cell_num, $body);
	$body = str_replace('%FINDING_NUM%', $pro_enq->finding_flag == DBTRUE ? number_format($pro_enq->appearance_ratio ? $pro_enq->sample_num * 100 / $pro_enq->appearance_ratio: 0) : '-', $body);
	$body = str_replace('%APPEARANCE%', $pro_enq->finding_flag == DBTRUE ? "$pro_enq->appearance_ratio%" : '-', $body);
	$body = str_replace('%SAMPLE_NUM%', number_format($pro_enq->sample_num), $body);
	$body = str_replace('%ENQTEST_URL%', "{$url_marketer}pe_remlenq3a.php?m=1&enquete_id=$pro_enq->enquete_id&pro_enq_no=$pro_enq->pro_enq_no", $body);
	$body = str_replace('%ESTIMATION%', number_format($pro_enq->get_total_price()), $body);
    // 利用ネットワークを取得し、パラメータにセット BTI
    $sql = "SELECT pet_target_flg FROM t_pro_enq_target WHERE pet_enquete_id=$pro_enq->enquete_id";
    $result = db_exec($sql);
    if (pg_numrows($result)) {
        $fetch = pg_fetch_object($result, 0);
        $body = str_replace('%USED_NETWORK%', $fetch->pet_target_flg == 'g' ? 'GMO(infoQ)': 'きかせて.net', $body);
    }

	$body = str_replace('%USED_NETWORK%', $target_flg == 'g' ? 'GMO(infoQ)': 'きかせて.net', $body);

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
function mail_send2(&$pro_enq, $apply) {
	$marketer_id = $_SESSION['ss_marketer_id'];
	$owner_id = $_SESSION['ss_owner_id'];
	$enquete = &$pro_enq->enquete;
	$enquete2 = &$pro_enq->enquete2;

	$sql = "SELECT mr_mail_addr,mr_name1,mr_name2,mr_kinmu_name FROM t_marketer WHERE mr_marketer_id=$owner_id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$mail_addr = $fetch->mr_mail_addr;
		$name1 = $fetch->mr_name1;
		$name2 = $fetch->mr_name2;
		$kinmu_name = $fetch->mr_kinmu_name;
	}

	get_mail_template('pe_mk', $subject, $from, $cc, $bcc, $repty_to, $body);

	$fetch = get_system_info('sy_url_marketer');
	$url_marketer = $fetch->sy_url_marketer;

	$body = str_replace('%APP_DATE%', date('Y/m/d H:i'), $body);
	$body = str_replace('%MARKETER_NAME%', "$name1 $name2", $body);
	$body = str_replace('%MAIL_ADDR%', $mail_addr, $body);
	$body = str_replace('%COMPANY_NAME%', $kinmu_name, $body);
	$body = str_replace('%TITLE%', $enquete->title, $body);
	$body = str_replace('%START_DATE%', "$enquete->start_date_y/$enquete->start_date_m/$enquete->start_date_d", $body);
	$body = str_replace('%END_DATE%', "$enquete->end_date_y/$enquete->end_date_m/$enquete->end_date_d", $body);
	$body = str_replace('%ENQ_TOTAL%', $enquete2->get_question_num(), $body);
	$body = str_replace('%REAPPLY_FLG%', $apply, $body);
	$body = str_replace('%SEND_NUM%', $pro_enq->send_num, $body);
	$body = str_replace('%CELL_FLAG%', $pro_enq->cell->cell_option == 1 ? 'なし' : 'あり', $body);
	$body = str_replace('%CELL_NUM%', $pro_enq->cell->cell_num, $body);
	$body = str_replace('%FINDING_NUM%', $pro_enq->finding_flag == DBTRUE ? number_format($pro_enq->appearance_ratio ? $pro_enq->sample_num * 100 / $pro_enq->appearance_ratio: 0) : '-', $body);
	$body = str_replace('%APPEARANCE%', $pro_enq->finding_flag == DBTRUE ? "$pro_enq->appearance_ratio%" : '-', $body);
	$body = str_replace('%SAMPLE_NUM%', number_format($pro_enq->sample_num), $body);
	$body = str_replace('%ENQTEST_URL%', "{$url_marketer}pe_remlenq3a.php?m=1&enquete_id=$pro_enq->enquete_id&pro_enq_no=$pro_enq->pro_enq_no", $body);
	$body = str_replace('%ESTIMATION%', number_format($pro_enq->get_total_price()), $body);

	$pro_enq->get_notify_mail_addr($to, $cc);
	send_mail($subject, $to, $from, $body, $cc, $bcc, $reply_to);
}

if (!isset($_SESSION['ss_pro_enq']))
	redirect('pe_index.php');
$pro_enq = &$_SESSION['ss_pro_enq'];
$enquete = &$pro_enq->enquete;
$enquete2 = &$pro_enq->enquete2;

$status = $enquete->status;

// ポイント設定
$enquete->point = $enquete->get_finding_point();
$enquete2->point = $enquete2->get_enquete_point();

// 本アンケートにタイトル設定
$enquete2->title = $enquete->title;

// ステータスを申請中に設定
$enquete->status = 2;
$pro_enq->save_enquete = true;
$pro_enq->save_enquete2 = true;
$pro_enq->write_db();

// 申請種別
$apply = ($status == 1) ? '申請' : '再申請';

// 通知メール送信
mail_send1($pro_enq, $apply);
mail_send2($pro_enq, $apply);

// セッション変数削除
unset($_SESSION['ss_pro_enq']);
session_unregister('ss_pro_enq');
?>
<? marketer_header('Ｐｒｏアンケート', PG_NULL) ?>
<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="10" border="0"></td>
	</tr>
</table>

<table  border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		<td width="778" align="center" valign="top">
			<table border="0" cellspacing="0" cellpadding="0" width="778">
				<tr>
					<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
					<td width="768" valign="top"><img src="images/mkk_pe/title_01.gif"></td>
				</tr>
			</table>
			<form>
			<br>
			<center>
			<table width="500" border="0" cellspacing="0" cellpadding="5">
				<tr>
					<td width="100%" valign="top" bgcolor="#ffffff" align="center">

						アンケートの申請を完了しました

					</td>
				</tr>
			</table>
			<br>
			<a href="pe_index.php"><img src="images/mkk_bt/back.gif" alt="戻る" name="image2" width="108" height="31" border="0"></a>
			</center>
			</form>
		</td>
	</tr>
</table>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer() ?>
