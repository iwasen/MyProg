<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:OneToOne�᡼�롦�᡼����������
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");
include("$inc/mail.php");
include("$inc/encrypt.php");

// ���ϥѥ�᡼��
$comm_id = $_POST['comm_id'];
$new_comm_id = $_POST['new_comm_id'];
$user_id = $_POST['user_id'];
$status = $_POST['end_flag'] ? 9 : 1;

db_begin_trans();

// ����åɥȥå�ID����
$sql = "SELECT cml_thread_comm_id FROM t_comm_log WHERE cml_comm_id=$comm_id";
$result = db_exec($sql);
$thread_comm_id = pg_fetch_result($result, 0, 0);
if ($thread_comm_id) {
	// �����᡼��ƥ�ץ졼�ȼ���
	get_mail_template('onetoone', $subject, $from, $cc, $bcc, $repty_to, $body, $footer);
	separate_address($from, $from_addr, $from_name);

	// ô����From̾����
	$sql = "SELECT tts_from_name,tts_from_addr"
			. " FROM t_user_manage"
			. " JOIN t_dealer ON dlr_dealer_cd=umg_dealer_cd"
			. " JOIN t_tantousha ON tts_tantousha_id=dlr_tantousha_id"
			. " WHERE umg_user_id=$user_id";
	$result = db_exec($sql);
	if (pg_num_rows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$from_name = $fetch->tts_from_name;
		$from_addr = $fetch->tts_from_addr;
	}

	// ���ߥ�˥��������������ײ����ե饰��ꥻ�å�
	$rec['cml_answer_flag'] = 'false';
	db_update('t_comm_log', $rec, "cml_comm_id=$comm_id AND cml_answer_flag=true");

	// ����åɴ����ơ��֥빹��
	$rec['cmt_staff_id'] = sql_number($g_staff_id);
	$rec['cmt_status'] = sql_number($status);
	$rec['cmt_answer_flag'] = "EXISTS(SELECT * FROM t_comm_log WHERE cml_thread_comm_id=cmt_comm_id AND cml_answer_flag=true)";
	db_update('t_comm_thread', $rec, "cmt_comm_id=$thread_comm_id");

	// ���ߥ�˥�����������Ƥ���¸
	$rec['cmc_comm_type'] = 4;
	$rec['cmc_subject'] = sql_char($_POST['subject']);
	$rec['cmc_body'] = sql_char($_POST['body']);
	db_insert('t_comm_contents', $rec);
	$contents_id = get_current_seq('t_comm_contents', 'cmc_contents_id');

	// ���ߥ�˥���������������¸
	$rec['cml_comm_id'] = sql_number($new_comm_id);
	$rec['cml_parent_comm_id'] = sql_number($comm_id);
	$rec['cml_thread_comm_id'] = sql_number($thread_comm_id);
	$rec['cml_contents_id'] = sql_number($contents_id);
	$rec['cml_user_id'] = sql_number($user_id);
	$rec['cml_date'] = 'current_timestamp';
	$rec['cml_send_recv'] = sql_char('S');
	$rec['cml_answer_flag'] = 'false';
	$rec['cml_from_addr'] = sql_char($from_addr);
	$rec['cml_from_name'] = sql_char($from_name);
	$rec['cml_to_addr'] = sql_char($_POST['to_addr']);
	db_insert('t_comm_log', $rec);

	// �����¸�᡼�����
	db_delete('t_mail_save', "msv_comm_id=$comm_id");

	// �ֿ��᡼�������������Ͽ
	if ($status == 1) {
		$rec['mrp_comm_id'] = sql_number($new_comm_id);
		$rec['mrp_date'] = "current_date + interval '7 day'";
		$sql = "SELECT COUNT(*) FROM t_mail_reply WHERE mrp_user_id=$user_id";
		if (db_fetch1($sql) == 0) {
			$rec['mrp_user_id'] = sql_number($user_id);
			db_insert('t_mail_reply', $rec);
		} else {
			db_update('t_mail_reply', $rec, "mrp_user_id=$user_id");
		}
	}

	// ���ƽ�λ���Ƥ�����᡼���ۿ��Ƴ�
	if ($status == 9) {
		$sql = "SELECT COUNT(*)"
				. " FROM t_comm_thread JOIN t_comm_log ON cml_comm_id=cmt_comm_id"
				. " WHERE cmt_status=1 AND cml_user_id=$user_id";
		if (db_fetch1($sql) == 0) {
			$rec['ust_rel_stop_flag'] = 'false';
			db_update('t_user_status', $rec, "ust_user_id=$user_id AND ust_rel_stop_flag=true");
		}
	}

	// ID�դ�From���ɥ쥹
	$from_addr = str_replace('@', '-C' . encrypt_id($new_comm_id) . '@', $from_addr);
	$from = "$from_name <$from_addr>";

	// In-Reply-To:�إå��ղ�
	if ($_POST['message_id'])
		$header .= "In-Reply-To: {$_POST['message_id']}\n";

	// References:�إå��ղ�
	if ($_POST['references'])
		$header .= "References: {$_POST['references']}\n {$_POST['message_id']}\n";

	// ź�եե��������
	$sql = "SELECT mat_file_name,mat_file_type,mat_data FROM t_mail_attachment WHERE mat_comm_id=$new_comm_id ORDER BY mat_file_no";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$attachment[$i]['file'] = $fetch->mat_file_name;
		$attachment[$i]['type'] = $fetch->mat_file_type;
		$attachment[$i]['data'] = $fetch->mat_data;
	}

	// �᡼������
	if (db_errormessage() == '')
		$ok = send_mail($_POST['subject'], $_POST['to_addr'], $from, $_POST['body'], $cc, $bcc, $repty_to, '', $header, $attachment);
}

if ($ok) {
	db_commit_trans();
	$msg = '�᡼����������������ޤ�����';
} else {
	db_rollback();
	$msg = '�᡼��������˼��Ԥ��ޤ�����';
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
</head>
<body onLoad="document.all.ok.focus();top.main.user.location.reload()">

<? center_header('OneToOne�᡼��å᡼������') ?>

<div align="center">
<p class="msg"><?=$msg?></p>
<p><input type="button" id="ok" value="����롡" onclick="location.href='<?=$_POST['url']?>'"></p>
</div>

<? center_footer() ?>

</body>
</html>
