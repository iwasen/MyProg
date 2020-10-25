<?
/******************************************************
' System :Eco-footprint �����ڡ���
' Content:�ץ쥼��ȴ�����Ͽ����
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
include("$inc/csv.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/mail.php");
include("$inc/decode.php");
include("$inc/format.php");

// �����ѹ�����᡼������
function mail_send($fetch) {
	get_mail_template('present1', $subject, $from, $cc, $bcc, $repty_to, $body);
	$fetch2 = get_system_info('sy_url_top');
	$body = str_replace('%MEMBER_NAME%', "$fetch->mb_name1 $fetch->mb_name2", $body);
	$body = str_replace('%TOP_URL%', $fetch2->sy_url_top, $body);
	send_mail($subject, $fetch->mb_mail_addr, $from, $body, $cc, $bcc, $reply_to);
}

// �ƥ��ȥ᡼�������ʽ����ѹ������
function test_mail($from) {

	// �����襢�ɥ쥹����
	$sql = "SELECT ad_mail_addr FROM m_admin WHERE ad_admin_id={$_COOKIE['admin_login_id']}";
	$to = db_fetch1($sql);

	get_mail_template('present1', $subject, $from, $cc, $bcc, $repty_to, $body);
	$fetch2 = get_system_info('sy_url_top');
	$body = str_replace('%TOP_URL%', $fetch2->sy_url_top, $body);

	send_mail($subject, $to, $from, $body);
}

// �ᥤ�����
set_global('etc', '����¾����', '�ץ쥼��ȴ���', BACK_TOP);

switch ($next_action) {

case 'regist':
	$sql = "select coalesce(max(li_ps_sub_no), 0) from t_present_list where li_ps_no=$ps_no";
	$sub_no = db_fetch1($sql);
	db_begin_trans();
	
	$i = 0;
	while($seq_no[$i]) {
		$sub_no = $sub_no + 1;
		$sql = "insert into t_present_list (li_ps_no,li_ps_sub_no,li_mb_seq_no,li_status,li_regist_date) values ("
			. sql_number($ps_no) . ","
			. sql_number($sub_no) . ","
			. sql_number($seq_no[$i]) . ","
			. sql_number($r_check[$i]) . ","
			. "'now')";
		db_exec($sql);
		$i = $i + 1;
	}
	db_commit_trans();
	$msg = '��Ͽ���ޤ�����';
	$back = "location.href='list.php'";
	break;

case 'delivery':
	db_begin_trans();
	$i = 0;
	while($seq_no[$i]) {
		if ($r_check[$i] == 5) {
			$sql = "update t_present_list set"
				. " li_status=" . sql_number($r_check[$i])
				. ",li_delivery_date=" . sql_date2($delivery_date_y,$delivery_date_m,$delivery_date_d)
				. ",li_update='now'"
				. " where li_ps_no=$ps_no and li_mb_seq_no=$seq_no[$i]";
		} else {
			$sql = "delete from t_present_list where li_ps_no=$ps_no and li_mb_seq_no=$seq_no[$i]";
		}
		db_exec($sql);

		$i = $i + 1;
	}
	// CSV�ǡ�������
	$filename = "ecopri_$delivery_date_y$delivery_date_m$delivery_date_d.csv";

	$csv = '"ID",��̾,͹���ֹ�,����,��Ͽ����,�᡼�륢�ɥ쥹';
	$csv .= "\r\n";
	
	$i = 0;
	while($seq_no[$i]) {
		$sql = "select mb_id,mb_name1,mb_name2,mb_zip,mb_area_cd,mb_address,mb_regist_date,mb_mail_addr from t_member where mb_seq_no=$seq_no[$i]"
			. " order by mb_regist_date";
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);

			$csv .= $fetch->mb_id;
			set_csv($csv, "$fetch->mb_name1 $fetch->mb_name2");
			set_csv($csv, substr($fetch->mb_zip,0,3) . "-" . substr($fetch->mb_zip,3,4));
			set_csv($csv, decode_area($fetch->mb_area_cd) . "$fetch->mb_address");
			set_csv($csv, format_datetime($fetch->mb_regist_date));
			set_csv($csv, $fetch->mb_mail_addr);
			$csv .= "\r\n";
		}
		$i = $i + 1;
	}
	
	// �᡼������
	$sql = "select ps_name,ps_contact_addr1,ps_contact_addr2,ps_contact_addr3 from t_present where ps_present_no=$ps_no";
	$result = db_exec($sql);
	$fetch = pg_fetch_object($result, 0);
	
	get_mail_template('present2', $subject, $from, $cc, $bcc, $repty_to, $body);
	/* ��������ʸ���Ѵ� */
	$body = str_replace('%TITLE%', $fetch->ps_name, $body);
	$body = str_replace('%SEND_DATE%', $delivery_date_y."ǯ ".$delivery_date_m."�� ".$delivery_date_d."��", $body);

	$contact_addr[] = $fetch->ps_contact_addr1;
	$contact_addr[] = $fetch->ps_contact_addr2;
	$contact_addr[] = $fetch->ps_contact_addr3;

	$csv_file = mb_convert_encoding($csv, 'SJIS', 'EUC-JP');

	if (is_array($contact_addr)) {
		foreach($contact_addr as $to)
		sendAttachMail( $from, $to, $subject, $body, $csv_file, $filename);
	}
	db_commit_trans();
	$msg = "CSV�ե�����( $i ��)��᡼���������ޤ�����";
	$back = "location.href='list.php'";
	break;

case 'mail':
	db_begin_trans();
	$i = 0;
	$count1 = 0;
	$count2 = 0;
	while($seq_no[$i]) {
		if ($r_check[$i] == 2) {
			$sql = "update t_present_list set"
				. " li_status=" . sql_number($r_check[$i])
				. ",li_update='now'"
				. " where li_ps_no=$ps_no and li_mb_seq_no=$seq_no[$i]";
			db_exec($sql);

			// ��礻�᡼������
			$sql = "select mb_mail_addr,mb_name1,mb_name2 from t_member where mb_seq_no=$seq_no[$i]";
			$result = db_exec($sql);
			if (pg_numrows($result)) {
				$fetch = pg_fetch_object($result, 0);
				mail_send($fetch);
				$count1 = $count1 + 1;
			}

		} elseif ($r_check[$i] == 9) {
			$sql = "delete from t_present_list where li_ps_no=$ps_no and li_mb_seq_no=$seq_no[$i]";
			db_exec($sql);
			$count2 = $count2 + 1;
		}
		$i = $i + 1;
	}
	db_commit_trans();

	if ($count1)
	$msg[] = "��礻�᡼��� $count1 ���������ޤ�����";
	if ($count2)
	$msg[] = "��礻�� $count2 �������ޤ�����";
	if (!$msg)
	$msg = "�¹Ԥ���ǡ���������ޤ���Ǥ�����";
	$back = "location.href='list.php'";
	break;
case 'test':
	test_mail($from);
	$msg = '�ƥ��ȥ᡼����������ޤ�����';
	$back = "history.back()";
	break;

case 'kajo':
	db_begin_trans();
	$i = 0;
	while($seq_no[$i]) {
		if ($r_check[$i] == 9) {
			$sql = "delete from t_present_list where li_ps_no=$ps_no and li_mb_seq_no=$seq_no[$i]";
		}
		db_exec($sql);

		$i = $i + 1;
	}
	db_commit_trans();
	$msg = '�����������ޤ�����';
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
</head>
<body onLoad="document.all.ok.focus()">
<? page_header() ?>

<div align="center">
<p class="msg"><?=disp_msg($msg, '', '<br><br>')?></p>
<p><input type="button" id="ok" value="����롡" onclick="<?=$back?>"></p>
</div>

<? page_footer() ?>
</body>
</html>
