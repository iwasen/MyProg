<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_partner.php");
include("$inc/check.php");
include("$inc/mail.php");
include("$inc/get_form.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
include("$inc/mk_error.php");

// エージェント宛て登録通知メール送信
function send_notify_mail(&$myp) {
	$owner_id = $_SESSION['ss_owner_id'];
	$enquete = &$myp->enquete;

	$sql = "SELECT mr_mail_addr,mr_name1,mr_name2,mr_agent_id FROM t_marketer WHERE mr_marketer_id=$owner_id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$mail_addr = $fetch->mr_mail_addr;
		$name1 = $fetch->mr_name1;
		$name2 = $fetch->mr_name2;
		$agent_id = $fetch->mr_agent_id;
	}

	get_mail_template('myp_pjt', $subject, $from, $cc, $bcc, $repty_to, $body);

	$body = str_replace('%REG_DATE%', date('Y/m/d H:i'), $body);
	$body = str_replace('%MARKETER_NAME%', "$name1 $name2", $body);
	$body = str_replace('%MAIL_ADDR%', $mail_addr, $body);
	$body = str_replace('%PJT_ID%', $myp->pjt_id, $body);
	$body = str_replace('%PJT_NAME%', $myp->room_name, $body);
	$body = str_replace('%START_DATE%', "$myp->recruit_start_y/$myp->recruit_start_m/$myp->recruit_start_d", $body);
	$body = str_replace('%END_DATE%', "$myp->room_end_y/$myp->room_end_m/$myp->room_end_d", $body);

	// エージェント宛て送信
	if ($agent_id) {
		$sql = "SELECT ag_mail_addr,ag_bcc_addr FROM t_agent WHERE ag_agent_id=$agent_id";
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);

			if ($fetch->ag_bcc_addr != '') {
				if ($bcc != '')
					$bcc .= ',';
				$bcc .= $fetch->ag_bcc_addr;
			}

			send_mail($subject, $fetch->ag_mail_addr, $from, $body, $cc, $bcc, $reply_to);
		}
	}
}

// Myパートナークラス取得
if (!isset($_SESSION['s_my_partner']))
	redirect('mp_pjt_list.php');
$myp = &$_SESSION['s_my_partner'];

// フォームからクラスへ代入
$myp->room_name = $room_name;
$myp->purpose = $purpose;
$myp->policy = $policy;
$myp->member_num = get_number($member_num);
$myp->recruit_start_y = get_number($recruit_start_y);
$myp->recruit_start_m = get_number($recruit_start_m);
$myp->recruit_start_d = get_number($recruit_start_d);
$myp->recruit_start_h = get_number($recruit_start_h);
$myp->recruit_end_y = get_number($recruit_end_y);
$myp->recruit_end_m = get_number($recruit_end_m);
$myp->recruit_end_d = get_number($recruit_end_d);
$myp->recruit_end_h = get_number($recruit_end_h);
$myp->decision_date_y = get_number($decision_date_y);
$myp->decision_date_m = get_number($decision_date_m);
$myp->decision_date_d = get_number($decision_date_d);
$myp->room_start_y = get_number($room_start_y);
$myp->room_start_m = get_number($room_start_m);
$myp->room_start_d = get_number($room_start_d);
$myp->room_end_y = get_number($room_end_y);
$myp->room_end_m = get_number($room_end_m);
$myp->room_end_d = get_number($room_end_d);

// 参加条件の取得
$seq_no = 1;
$myp->sanka_jouken = array();
for ($i = 1; $i <= $myp->sanka_jouken_num; $i++) {
	if ($_POST["sanka_jouken$i"] != '') {
		$myp->sanka_jouken[$seq_no] = $_POST["sanka_jouken$i"];
		$seq_no++;
	}
}

// リクルート条件の取得
$seq_no = 1;
$myp->recruit_jouken = array();
for ($i = 1; $i <= $myp->recruit_jouken_num; $i++) {
	if ($_POST["recruit_jouken$i"] != '') {
		$myp->recruit_jouken[$seq_no] = $_POST["recruit_jouken$i"];
		$seq_no++;
	}
}

// 参加条件追加処理
if ($next_action == 'add_sanka_jouken') {
	$myp->sanka_jouken_num++;
	redirect('mp_pjt_new2.php?edit=1');
}

// リクルート条件追加処理
if ($next_action == 'add_recruit_jouken') {
	$myp->recruit_jouken_num++;
	redirect('mp_pjt_new2.php?edit=1');
}

if ($next_action != 'calc_date') {
	// 会議室名チェック
	if ($myp->room_name == '')
		$msg[] = '会議室名が入力されていないようです。';

	// 目的チェック
	if ($myp->purpose == '')
		$msg[] = '目的が入力されていないようです。';

//	// 参加条件入力チェック
//	if (count($myp->sanka_jouken) == 0)
//		$msg[] = '参加条件を１つ以上入力してください。';

	// リクルート条件入力チェック
	if (count($myp->recruit_jouken) == 0)
		$msg[] = 'リクルート条件を１つ以上入力してください。';

	// 会議室運営ポリシー入力チェック
	if ($myp->policy == '')
		$msg[] = '会議室運営ポリシーが選択されていないようです。';

	// メンバー数入力チェック
	if ($myp->member_num == '')
		$msg[] = 'メンバー数が入力されていないようです。';
	elseif (!check_num($myp->member_num, 1))
		$msg[] = 'メンバー数が正しく入力されていないようです。';
}

// リクルート期間の開始年チェック
$recruit_start_ok = true;
if ($myp->recruit_start_y == '') {
	$msg[] = 'リクルート期間の開始年が入力されていないようです。';
	$recruit_start_ok = false;
} elseif (!check_num($myp->recruit_start_y, 2004, 2100)) {
	$msg[] = 'リクルート期間の開始年が正しく入力されていないようです。';
	$recruit_start_ok = false;
}

// リクルート期間の開始月チェック
if ($myp->recruit_start_m == '') {
	$msg[] = 'リクルート期間の開始月が入力されていないようです。';
	$recruit_start_ok = false;
} elseif (!check_num($myp->recruit_start_m, 1, 12)) {
	$msg[] = 'リクルート期間の開始月が正しく入力されていないようです。';
	$recruit_start_ok = false;
}

// リクルート期間の開始日チェック
if ($myp->recruit_start_d == '') {
	$msg[] = 'リクルート期間の開始日が入力されていないようです。';
	$recruit_start_ok = false;
} elseif (!check_num($myp->recruit_start_d, 1, 31)) {
	$msg[] = 'リクルート期間の開始日が正しく入力されていないようです。';
	$recruit_start_ok = false;
}

// リクルート期間の開始時間チェック
if ($myp->recruit_start_h == '') {
	$msg[] = 'リクルート期間の開始時間が入力されていないようです。';
	$recruit_start_ok = false;
} elseif (!check_num($myp->recruit_start_h, 0, 23)) {
	$msg[] = 'リクルート期間の開始時間が正しく入力されていないようです。';
	$recruit_start_ok = false;
}

// リクルート期間の開始年月日正当性チェック
if ($recruit_start_ok) {
	if (!checkdate($myp->recruit_start_m, $myp->recruit_start_d, $myp->recruit_start_y)) {
		$msg[] = 'リクルート期間の開始年月日が正しく入力されていないようです。';
		$recruit_start_ok = false;
	} else {
		$recruit_start = mktime($myp->recruit_start_h, 0, 0, $myp->recruit_start_m, $myp->recruit_start_d, $myp->recruit_start_y);

		// 過去の日付チェック
		if ($recruit_start <= time())
			$msg[] = 'リクルート期間の開始日に過去の日付が入力されているようです。';
	}
}

if ($next_action != 'calc_date') {
	// リクルート期間の終了年チェック
	$recruit_end_ok = true;
	if ($myp->recruit_end_y == '') {
		$msg[] = 'リクルート期間の終了年が入力されていないようです。';
		$recruit_end_ok = false;
	} elseif (!check_num($myp->recruit_end_y, 2004, 2100)) {
		$msg[] = 'リクルート期間の終了年が正しく入力されていないようです。';
		$recruit_end_ok = false;
	}

	// リクルート期間の終了月チェック
	if ($myp->recruit_end_m == '') {
		$msg[] = 'リクルート期間の終了月が入力されていないようです。';
		$recruit_end_ok = false;
	} elseif (!check_num($myp->recruit_end_m, 1, 12)) {
		$msg[] = 'リクルート期間の終了月が正しく入力されていないようです。';
		$recruit_end_ok = false;
	}

	// リクルート期間の終了日チェック
	if ($myp->recruit_end_d == '') {
		$msg[] = 'リクルート期間の終了日が入力されていないようです。';
		$recruit_end_ok = false;
	} elseif (!check_num($myp->recruit_end_d, 1, 31)) {
		$msg[] = 'リクルート期間の終了日が正しく入力されていないようです。';
		$recruit_end_ok = false;
	}

	// リクルート期間の終了時間チェック
	if ($myp->recruit_end_h == '') {
		$msg[] = 'リクルート期間の終了時間が入力されていないようです。';
		$recruit_end_ok = false;
	} elseif (!check_num($myp->recruit_end_h, 0, 23)) {
		$msg[] = 'リクルート期間の終了時間が正しく入力されていないようです。';
		$recruit_end_ok = false;
	}

	// リクルート期間の終了年月日正当性チェック
	if ($recruit_end_ok) {
		if (!checkdate($myp->recruit_end_m, $myp->recruit_end_d, $myp->recruit_end_y)) {
			$msg[] = 'リクルート期間の終了年月日が正しく入力されていないようです。';
			$recruit_end_ok = false;
		} else
			$recruit_end = mktime($myp->recruit_end_h, 0, 0, $myp->recruit_end_m, $myp->recruit_end_d, $myp->recruit_end_y);
	}

	// リクルート期間の開始と終了年月日の関係チェック
	if ($recruit_start && $recruit_end) {
		if ($recruit_start >= $recruit_end)
			$msg[] = 'リクルート期間の終了日は開始日より後の日付を入力してください。';
	}

	// 決定日の年チェック
	$decision_date_ok = true;
	if ($myp->decision_date_y == '') {
		$msg[] = '決定日の年が入力されていないようです。';
		$decision_date_ok = false;
	} elseif (!check_num($myp->decision_date_y, 2004, 2100)) {
		$msg[] = '決定日の年が正しく入力されていないようです。';
		$decision_date_ok = false;
	}

	// 決定日の月チェック
	if ($myp->decision_date_m == '') {
		$msg[] = '決定日の月が入力されていないようです。';
		$decision_date_ok = false;
	} elseif (!check_num($myp->decision_date_m, 1, 12)) {
		$msg[] = '決定日の月が正しく入力されていないようです。';
		$decision_date_ok = false;
	}

	// 決定日の日チェック
	if ($myp->decision_date_d == '') {
		$msg[] = '決定日の日が入力されていないようです。';
		$decision_date_ok = false;
	} elseif (!check_num($myp->decision_date_d, 1, 31)) {
		$msg[] = '決定日の日が正しく入力されていないようです。';
		$decision_date_ok = false;
	}

	// 決定日の年チェック
	if ($decision_date_ok) {
		if (!checkdate($myp->decision_date_m, $myp->decision_date_d, $myp->decision_date_y)) {
			$msg[] = '決定日の年月日が正しく入力されていないようです。';
			$decision_date_ok = false;
		} else {
			$decision_date = mktime(0, 0, 0, $myp->decision_date_m, $myp->decision_date_d, $myp->decision_date_y);

			// 過去の日付チェック
			if ($decision_date <= time())
				$msg[] = '決定日に過去の日付が入力されているようです。';
		}
	}

	// リクルート終了と決定日の関係チェック
	if ($recruit_end && $decision_date) {
		if ($recruit_end > ($decision_date + 24 * 60 * 60))
			$msg[] = '決定日はリクルート期間の終了日より後の日付を入力してください。';
	}

	// 会議室実施期間の開始年チェック
	$room_start_ok = true;
	if ($myp->room_start_y == '') {
		$msg[] = '会議室実施期間の開始年が入力されていないようです。';
		$room_start_ok = false;
	} elseif (!check_num($myp->room_start_y, 2004, 2100)) {
		$msg[] = '会議室実施期間の開始年が正しく入力されていないようです。';
		$room_start_ok = false;
	}

	// 会議室実施期間の開始月チェック
	if ($myp->room_start_m == '') {
		$msg[] = '会議室実施期間の開始月が入力されていないようです。';
		$room_start_ok = false;
	} elseif (!check_num($myp->room_start_m, 1, 12)) {
		$msg[] = '会議室実施期間の開始月が正しく入力されていないようです。';
		$room_start_ok = false;
	}

	// 会議室実施期間の開始日チェック
	if ($myp->room_start_d == '') {
		$msg[] = '会議室実施期間の開始日が入力されていないようです。';
		$room_start_ok = false;
	} elseif (!check_num($myp->room_start_d, 1, 31)) {
		$msg[] = '会議室実施期間の開始日が正しく入力されていないようです。';
		$room_start_ok = false;
	}

	// 会議室実施期間の開始年月日正当性チェック
	if ($room_start_ok) {
		if (!checkdate($myp->room_start_m, $myp->room_start_d, $myp->room_start_y)) {
			$msg[] = '会議室実施期間の開始年月日が正しく入力されていないようです。';
			$room_start_ok = false;
		} else {
			$room_start = mktime(0, 0, 0, $myp->room_start_m, $myp->room_start_d, $myp->room_start_y);

			// 過去の日付チェック
			if ($room_start <= time())
				$msg[] = '会議室実施期間の開始日に過去の日付が入力されているようです。';
		}
	}

	// 会議室実施期間の終了年チェック
	$room_end_ok = true;
	if ($myp->room_end_y == '') {
		$msg[] = '会議室実施期間の終了年が入力されていないようです。';
		$room_end_ok = false;
	} elseif (!check_num($myp->room_end_y, 2004, 2100)) {
		$msg[] = '会議室実施期間の終了年が正しく入力されていないようです。';
		$room_end_ok = false;
	}

	// 会議室実施期間の終了月チェック
	if ($myp->room_end_m == '') {
		$msg[] = '会議室実施期間の終了月が入力されていないようです。';
		$room_end_ok = false;
	} elseif (!check_num($myp->room_end_m, 1, 12)) {
		$msg[] = '会議室実施期間の終了月が正しく入力されていないようです。';
		$room_end_ok = false;
	}

	// 会議室実施期間の終了日チェック
	if ($myp->room_end_d == '') {
		$msg[] = '会議室実施期間の終了日が入力されていないようです。';
		$room_end_ok = false;
	} elseif (!check_num($myp->room_end_d, 1, 31)) {
		$msg[] = '会議室実施期間の終了日が正しく入力されていないようです。';
		$room_end_ok = false;
	}

	// 会議室実施期間の終了年月日正当性チェック
	if ($room_end_ok) {
		if (!checkdate($myp->room_end_m, $myp->room_end_d, $myp->room_end_y)) {
			$msg[] = '会議室実施期間の年月日が正しく入力されていないようです。';
			$room_end_ok = false;
		} else
			$room_end = mktime(0, 0, 0, $myp->room_end_m, $myp->room_end_d, $myp->room_end_y);
	}

	// 会議室実施期間の開始と終了年月日の関係チェック
	if ($room_start && $room_end) {
		if ($room_start >= $room_end)
			$msg[] = '会議室実施期間の終了日は開始日より後の日付を入力してください。';
	}

	// 決定日と会議室実施開始の関係チェック
	if ($decision_date && $room_start) {
		if ($decision_date > $room_start)
			$msg[] = '会議室実施期間の開始日は決定日より後の日付を入力してください。';
	}
}

if (!$msg) {
	// 日付の自動計算処理
	if ($next_action == 'calc_date') {
		calc_date($recruit_start, &$recruit_end, &$decision_date, &$room_start);
		$myp->recruit_end_y = date('Y', $recruit_end);
		$myp->recruit_end_m = date('n', $recruit_end);
		$myp->recruit_end_d = date('j', $recruit_end);
		$myp->recruit_end_h = date('G', $recruit_end);
		$myp->decision_date_y = date('Y', $decision_date);
		$myp->decision_date_m = date('n', $decision_date);
		$myp->decision_date_d = date('j', $decision_date);
		$myp->room_start_y = date('Y', $room_start);
		$myp->room_start_m = date('n', $room_start);
		$myp->room_start_d = date('j', $room_start);
		$myp->room_end_y = date('Y', $room_start);

		redirect('mp_pjt_new2.php?edit=1');
	}

	$pjt_id = $myp->pjt_id;

	// DBに保存
	$myp->recruit_flag |= 0x01;
	$myp->save_sanka_jouken = true;
	$myp->save_recruit_jouken = true;
	$myp->write_db();

	// エージェントに登録通知メール送信
	if (!$pjt_id)
		send_notify_mail($myp);

	// セッション変数削除
	unset($_SESSION['s_my_partner']);
}
?>
<? marketer_header('Myパートナー', PG_NULL) ?>
<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="10" border="0"></td>
	</tr>
</table>
<?
if (!$msg) {
?>
<table border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		<td width="778" valign="top">
			<table border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
				<tr>
					
					<td><img src="images/t_mk_mymonitor2.gif" width="538" height="20"></td>
				</tr>
			</table>
			<!-- myパートナー -->
			<div align="center">
			<form>
			<table border=0 cellspacing=2 cellpadding=3 width="95%">
				<tr>
					<td>Myパートナーの基本項目の設定が完了しました。<br>
							<br>
							１）　この後の設定をご自身で行われる方は、「戻る」ボタンで戻って「詳細設定」を押し、<br>
							　　　参加者リクルート用アンケートの設定を行ってください。<br>
									<br><br>
							２）　この後の設定をエージェントに任せる場合は、下記「エージェントへ連絡」画面から、<br>
							　　　その旨をご連絡ください。<br>
							　　　（ただし、30,000円のオプション料金が発生しますことをご了承ください。）<br>
							<br>
							　　⇒　<a href="mkk_contact.php" target="_blank">エージェントへ連絡</a>
							</td>
				</tr>
			</table>
			<br>
			<a href="mp_pjt_list.php"><img src="images/mkk_bt/back.gif" alt="戻る" name="image2" width="108" height="31" border="0"></a>
			</form>
			</div>
		</td>
	</tr>
</table>
<? } else{?>
<table width="788" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><? error_msg($msg);?></td>
  </tr>
</table>
<? }?>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>

<? marketer_footer() ?>
