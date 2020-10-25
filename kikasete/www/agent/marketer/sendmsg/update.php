<?
/******************************************************
' System :きかせて・netエージェント用ページ
' Content:メッセージ送信・履歴の閲覧・削除
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/mail.php");
include("$inc/check.php");

//エージェント情報取得
function get_agent_info($agent_id, &$fetch) {
	$sql = "SELECT ag_mail_addr, ag_bcc_addr FROM t_agent WHERE ag_agent_id=$agent_id";
	$result = db_exec($sql);
	$fetch = pg_fetch_object($result, 0);
}

//本文差し替え処理
function replace_body(&$body, $marketer_name, $kigyou_name, $busho_name) {
	$body = str_replace("%MARKETER_NAME%", $marketer_name, $body);
	$body = str_replace("%KIGYOU_NAME%", $kigyou_name, $body);
	$body = str_replace("%BUSHO_NAME%", $busho_name, $body);
}

//bccアドレスチェック
function bcc_check($ag_bcc_addr,&$bcc) {
	$ag_bcc_addr = str_replace("\r\n",',',$ag_bcc_addr);
	$ag_bcc = strtok($ag_bcc_addr, ",");
	while($ag_bcc) {
		if (check_mail_addr($ag_bcc)) {
			$bcc .= "$ag_bcc,";
		}
		$ag_bcc = strtok(",");
	}
	return $bcc;
}

//t_communication登録
function insert_t_communication($marketer_id, $agent_id, $subject, $body) {
	$sql = sprintf("INSERT INTO t_communication (co_regist_date, co_direction, co_marketer_id, co_agent_id, co_title, co_text)"
		. "VALUES ('now',2,%s,%s,%s,%s)",
		sql_number($marketer_id),
		sql_number($agent_id),
		sql_char($subject),
		sql_char($body));
	db_exec($sql);
}

//署名セット
function set_signature(&$body) {
	global $agent_id;

	$sql = "SELECT ag_signature FROM t_agent WHERE ag_agent_id=$agent_id";
	$signature = db_fetch1($sql);
	if ($signature != '')
		$body .= "\n\n\n$signature";
}

//メイン処理
set_global('マーケターへのメッセージ送信/更新', BACK_TOP);

switch ($next_action) {
case 'test':
	$sql = "SELECT ag_name1,ag_name2,ag_kinmu_name,ag_kinmu_unit FROM t_agent WHERE ag_agent_id=$agent_id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$body = $body_org;
		replace_body($body, $fetch->ag_name1 . " " . $fetch->ag_name2, $fetch->ag_kinmu_name, $fetch->ag_kinmu_unit);
	}
	set_signature($body);
	get_agent_info($agent_id, $fetch);
	send_mail("【テスト送信】$subject", $fetch->ag_mail_addr, $fetch->ag_mail_addr, $body, 0, 0, 0, 0);
	$msg = "メッセージをテスト送信しました。<br><br><font color='black' size=-1>（「戻る」ボタンを押して、送信処理を実行してください。）</font>";
	$back = "location.href='javascript:history.back()'";
	break;
case 'send':
	$count = 0;
	bcc_check($ag_bcc_addr, $bcc);
	if ($to_chk == "choice") {
		for ($i = 0; $i < count($to_ary); $i++) {
			$sql = "SELECT mr_name1,mr_name2,mr_kinmu_name,mr_kinmu_unit,mr_mail_addr FROM t_marketer WHERE mr_marketer_id=$to_ary[$i]";
			$result = db_exec($sql);
			if (pg_numrows($result)) {
				$fetch = pg_fetch_object($result, 0);
				$to = $fetch->mr_mail_addr;
				$body = $body_org;
				$marketer_id = $to_ary[$i];
				replace_body($body, $fetch->mr_name1 . " " . $fetch->mr_name2, $fetch->mr_kinmu_name, $fetch->mr_kinmu_unit);
			}
			$body2 = $body;
			set_signature($body);
			get_agent_info($agent_id, $fetch);
			if (send_mail($subject, $to, $fetch->ag_mail_addr, $body, 0, $bcc, 0, 0)) {
				insert_t_communication($marketer_id, $agent_id, $subject, $body2);
				$count++;
			}
		}
	} elseif ($to_chk == "all") {
		$sql = "SELECT mr_marketer_id,mr_name1,mr_name2,mr_kinmu_name,mr_kinmu_unit,mr_mail_addr FROM t_marketer WHERE mr_agent_id=$agent_id AND mr_status=0";
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
			$to = $fetch->mr_mail_addr;
			$body = $body_org;
			$marketer_id = $fetch->mr_marketer_id;
			replace_body($body, $fetch->mr_name1 . " " . $fetch->mr_name2, $fetch->mr_kinmu_name, $fetch->mr_kinmu_unit);
			$body2 = $body;
			set_signature($body);
			get_agent_info($agent_id, $fetch);
			if (send_mail($subject, $to, $fetch->ag_mail_addr, $body, 0, $bcc, 0, 0)) {
				insert_t_communication($marketer_id, $agent_id, $subject, $body2);
				$count++;
			}
		}
	}
	$msg = "メッセージを " . $count . "通 送信しました。";
	$back = "location.href='list.php'";
	break;
case 'update':
	$sql = "UPDATE t_communication SET" .
		" co_status=" . sql_number($co_status) .
		" WHERE co_seq_no=$co_no";
	db_exec($sql);
	$msg = 'メッセージの状態を更新しました。';
	$back = "location.href='list.php'";
	break;
default:
	redirect('list.php');
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<? menu_script() ?>
</head>
<body onLoad="document.all.ok.focus()">

<? page_header() ?>
<? menu_start() ?>

<table border=0 cellspacing=0 cellpadding=1 width="95%">
	<tr>
		<td>
			<table border=0 cellspacing=2 cellpadding=3 width=700>
				<tr>
					<td class="m4" align="center"><?=$g_title?></td>
				</tr>
				<tr>
					<td align="center"><br><br>
						<p class="msg"><?=$msg?></p>
						<p><input type="button" id="ok" value="　戻る　" onclick="<?=$back?>"></p>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
