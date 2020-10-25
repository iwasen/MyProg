<?
/******************************************************
' System :�������ơ�net������������ѥڡ���
' Content:�������������Ͽ��ǧ
'******************************************************/

$top = '.';
$inc = "$top/inc";
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");
include("$inc/encrypt.php");
include("$inc/mail.php");
include("$inc/decode.php");
include("$inc/format.php");

set_global('����������ȿ�����Ͽ��λ', BACK_NONE);

// ��Ͽ��λ�᡼������
function mail_send($mail_addr, $password) {
	get_mail_template('reg_ag2', $subject, $from, $cc, $bcc, $repty_to, $body);
	$body = str_replace('%MAIL_ADDR%', $mail_addr, $body);
	$body = str_replace('%PASSWORD%', $password, $body);

	send_mail($subject, $mail_addr, $from, $body, $cc, $bcc, $reply_to);
}

// ����������ȼ���
function decode_agent_disc($code, $def='����') {
	switch ($code) {
	case 1:
		return '�������������Ұ�';
	case 2:
		return '�Х�����';
	case 3:
		return '���ϲ�ҼҰ�';
	}
	return $def;
}

// �ᥤ�����

$seq_no = decrypt_number($key);
if (!$seq_no) {
	$msg = '��Ͽ�˼��Ԥ��ޤ�����';
} else {
	$sql = "SELECT * FROM t_agent WHERE ag_agent_id=$seq_no";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		if ($fetch->ag_status != 1)
			$msg = "���Ǥ���Ͽ�ѤߤǤ���";
		else {
			$sql = "UPDATE t_agent SET ag_status=0 WHERE ag_agent_id=$seq_no";
			db_exec($sql);

			// ����������Ȱ��ƥ᡼��
			mail_send($fetch->ag_mail_addr, $fetch->ag_password);

			// ��̳�ɰ��ƥ᡼��
			get_mail_template('reg_ag3', $subject, $from, $cc, $bcc, $repty_to, $body);
			$body = str_replace('%REGIST_DATE%', format_datetime($fetch->ag_regist_date), $body);
			$body = str_replace('%NAME1%', $fetch->ag_name1, $body);
			$body = str_replace('%NAME2%', $fetch->ag_name2, $body);
			$body = str_replace('%NAME1_KANA%', $fetch->ag_name1_kana, $body);
			$body = str_replace('%NAME2_KANA%', $fetch->ag_name2_kana, $body);
			$body = str_replace('%BIRTHDAY%', format_date($fetch->ag_birthday), $body);
			$body = str_replace('%SEX%', decode_sex($fetch->ag_sex), $body);
			$body = str_replace('%MAIL_ADDR%', $fetch->ag_mail_addr, $body);
			$body = str_replace('%AGENT_DISC%', decode_agent_disc($fetch->ag_agent_disc), $body);
			$body = str_replace('%KINMU_NAME%', $fetch->ag_kinmu_name, $body);
			$body = str_replace('%KINMU_UNIT%', $fetch->ag_kinmu_unit, $body);
			$body = str_replace('%KINMU_POST%', $fetch->ag_kinmu_post, $body);
			$body = str_replace('%AGENT_UNIT%', $fetch->ag_agent_unit, $body);
			$fetch2 = get_system_info('sy_agent_reg_to');
			if ($fetch2->sy_agent_reg_to != '') {
				send_mail($subject, $fetch2->sy_agent_reg_to, $from, $body, $cc, $bcc, $reply_to);
				$msg = "��Ͽ����λ���ޤ�����";
			}
		}
	} else
		$msg = "��Ͽ�˼��Ԥ��ޤ�����";
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<?=menu_script($top)?>
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
						<p><input type="button" id="ok" value="����롡" onclick="location.href='login/login.php'"></p>
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
