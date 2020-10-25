<?
/******************************************************
' System :きかせて・net事務局・エージェント用ページ
' Content:Myパートナー・リクルートジョブ申請
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_partner.php");
include("$inc/mail.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

//メイン処理
set_global2('mypartner', 'Myパートナー管理', 'Myパートナーリクルートジョブ申請', BACK_TOP);

// 事務局・エージェント宛て通知メール送信
function mail_send1(&$myp, $apply) {
	$enquete = &$myp->enquete;

	$sql = "SELECT mr_mail_addr,mr_name1,mr_name2,mr_agent_id FROM t_marketer WHERE mr_marketer_id=$myp->marketer_id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$mail_addr = $fetch->mr_mail_addr;
		$name1 = $fetch->mr_name1;
		$name2 = $fetch->mr_name2;
		$agent_id = $fetch->mr_agent_id;
	}

	get_mail_template('myp_ag', $subject, $from, $cc, $bcc, $repty_to, $body);

	$body = str_replace('%APP_DATE%', date('Y/m/d H:i'), $body);
	$body = str_replace('%MARKETER_NAME%', "$name1 $name2", $body);
	$body = str_replace('%MAIL_ADDR%', $mail_addr, $body);
	$body = str_replace('%PJT_ID%', $myp->pjt_id, $body);
	$body = str_replace('%PJT_NAME%', $myp->room_name, $body);
	$body = str_replace('%START_DATE%', "$myp->recruit_start_y/$myp->recruit_start_m/$myp->recruit_start_d", $body);
	$body = str_replace('%END_DATE%', "$myp->room_end_y/$myp->room_end_m/$myp->room_end_d", $body);
	$body = str_replace('%ENQ_TOTAL%', $enquete->get_question_num(), $body);
	$body = str_replace('%REAPPLY_FLG%', $apply, $body);

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
function mail_send2(&$myp, $apply) {
	$marketer_id = $_SESSION['ss_marketer_id'];
	$enquete = &$myp->enquete;

	$sql = "SELECT mr_mail_addr,mr_name1,mr_name2 FROM t_marketer WHERE mr_marketer_id=$myp->marketer_id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$mail_addr = $fetch->mr_mail_addr;
		$name1 = $fetch->mr_name1;
		$name2 = $fetch->mr_name2;
	}

	get_mail_template('myp_mk', $subject, $from, $cc, $bcc, $repty_to, $body);

	$body = str_replace('%APP_DATE%', date('Y/m/d H:i'), $body);
	$body = str_replace('%MARKETER_NAME%', "$name1 $name2", $body);
	$body = str_replace('%MAIL_ADDR%', $mail_addr, $body);
	$body = str_replace('%PJT_ID%', $myp->pjt_id, $body);
	$body = str_replace('%PJT_NAME%', $myp->room_name, $body);
	$body = str_replace('%START_DATE%', "$myp->recruit_start_y/$myp->recruit_start_m/$myp->recruit_start_d", $body);
	$body = str_replace('%END_DATE%', "$myp->room_end_y/$myp->room_end_m/$myp->room_end_d", $body);
	$body = str_replace('%ENQ_TOTAL%', $enquete->get_question_num(), $body);
	$body = str_replace('%REAPPLY_FLG%', $apply, $body);

	send_mail($subject, $mail_addr, $from, $body, $cc, $bcc, $reply_to);
}

// アンケートポイント設定
function set_enquete_point(&$myp) {
	$point = $myp->enquete->get_enquete_point();

	$rec['en_point'] = sql_number($point);
	db_update('t_enquete', $rec, "en_enquete_id=$myp->enquete_id");
}

$myp = new my_partner_class;
$myp->read_db($pjt_id);

db_begin_trans();

switch ($myp->status) {
case MPS_REC_KANRYOU:
	$myp->status = MPS_REC_SHINSEI;
	$msg = 'リクルートジョブを申請しました。';

	// ポイント設定
	set_enquete_point($myp);

	// 申請種別
	$apply = '申請';

	// 通知メール送信
	mail_send1($myp, $apply);
	mail_send2($myp, $apply);
	break;
}
$myp->write_db();

db_commit_trans();
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<? menu_script() ?>
</head>
<body>
<? page_header() ?>
<? menu_start() ?>

<table border=0 cellspacing=2 cellpadding=3 width=700>
	<tr>
		<td align="center" class="m4"><?=$g_title?></td>
	</tr>
	<tr>
		<td align="center"><br>
			<p class="msg">リクルートジョブを申請しました。</p>
			<p><input type="button" value="　戻る　" onclick="location.href='r_job.php?pjt_id=<?=$pjt_id?>'"></p>
		</td>
	</tr>
</table>

<? menu_end() ?>
<? page_footer() ?>
</body>
</html>
