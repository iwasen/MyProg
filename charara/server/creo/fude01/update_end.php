<?
/******************************************************
' System :�֤�����Factory�ץ��쥪�����ڡ���
' Content:���ϲ���
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mail.php");

$file_type = $_FILES['upload_file']['type'];
$file_size = $_FILES['upload_file']['size'];
$file_tmp_name = $_FILES['upload_file']['tmp_name'];
$file_error = $_FILES[upload_file]['error'];
$file_name = $_FILES['upload_file']['name'];

$uploaddir = '/var/charara/photo/';
$upload_file_name = $id . substr($file_name, -4);
$uploadfile = $uploaddir . $upload_file_name;

// �̿����դ��᡼������
function photo_mail_send($name1, $name2, $mail_addr) {
	get_mail_template('sv_creo1', $subject, $from, $cc, $bcc, $repty_to, $body);
	$body = str_replace('%NAME1%', $name1, $body);
	$body = str_replace('%NAME2%', $name2, $body);
	send_mail($subject, $mail_addr, $from, $body, $cc, $bcc, $reply_to);
}

// ������������å�
if ($id) {
	// ���ơ�����
	$sql = "SELECT nd_status FROM t_nigaoe_data WHERE nd_random_id=" . sql_char($id);
	$status = db_fetch1($sql);
	if ($status < 3) {
		// ���åץ��ɥե�����
		if (!$file_error) {
			if (!is_uploaded_file($upload_file))
				$msg[] = "�̿��ե����뤬��������Ͽ����Ƥ��ʤ��褦�Ǥ���";
			else {
				if (!(strstr($file_type,'gif') || strstr($file_type,'jpeg') || strstr($file_type,'png') || strstr($file_type,'bmp')))
					$msg[] = "�̿��ե�����η������������ʤ��褦�Ǥ���<br>�����ʼ��ղ�ǽ�ե����������JPG��PNG��GIF��BMP ��";
				if ($file_size > 1000000)
					$msg[] = "�̿��ե�����Υ��������礭�᤮��褦�Ǥ���";
			}
			// ����
			if ($sex == '')
				$msg[] = "���̤����򤵤�Ƥ��ʤ��褦�Ǥ���";
			// �˥å��͡���
			if ($name_text == '')
				$msg[] = "�˥å��͡��ब���Ϥ���Ƥ��ʤ��褦�Ǥ���";
		} else
			$msg[] = "�̿��ե�����Υ��åץ��ɤ˼��Ԥ��ޤ�����";
	} else
		$msg[] = "���˼��դ���λ���Ƥ��ޤ���";
} else {
	redirect('error.php');
}

// �ǡ�����¸
if (!$msg) {
	// ���ܾ���
	$campaign_cd = 'fude01';
	$product_cd = '01';

	// �����ڡ���������
	$sql = "SELECT cp_nouki, cp_brushwork FROM m_campaign WHERE cp_campaign_cd=" . sql_char($campaign_cd);
	$result = db_exec($sql);
	$fetch = pg_fetch_object($result, 0);

	// ����
	$brushwork = $fetch->cp_brushwork;
	// Ǽ��
	$nouki = add_date(date("Y-m-d"), 0, 0, $fetch->cp_nouki) . " " . date("H:i");

	// �̿���¸�����鳨���󹹿�
	if (move_uploaded_file($_FILES['upload_file']['tmp_name'], $uploadfile)) {
			$sql = "UPDATE t_nigaoe_data set "
				. "nd_status=3"
				. ",nd_photo_date='now'"
				. ",nd_product_cd=" . sql_char($product_cd)
				. ",nd_campaign_cd=". sql_char($campaign_cd)
				. ",nd_sex_code=" . sql_number($sex)
				. ",nd_brushwork=" . sql_number($brushwork)
				. ",nd_photo_file=" . sql_char($upload_file_name)
				. ",nd_nouki_date=" . sql_date($nouki)
				. ",nd_name_text=" . sql_char($name_text)
				. " WHERE nd_random_id=" . sql_char($id);
			db_exec($sql);
			photo_mail_send($df_name1, $df_name2, $nd_mail_addr);
	    $success_msg = "��Ͽ����λ���ޤ�����";
	} else {
			$msg[] = "�̿��ե�����Υ��åץ��ɤ˼��Ԥ��ޤ�����";
	}
}
?>

<!doctype html public "-//W3C//DTD HTML 4.0 Transitional//EN">
<html lang="ja">
<head>
<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>�֤ˤ������ץ����ڡ����</title>

</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<div align="center">
<br>
<?
if ($msg) {
?>
<table>
	<tr>
		<td>
			<table border="0" width="400" cellspacing="10">
				<td colspan="2" height="2" bgcolor="#CCFF33"><img src="img/spacer.gif" width="400" height="2"></td>
					<tr>
						<td align="center">
							<font size="2" color="red"><br>�� ����ǧ���ꤤ���ޤ�<br></font>
						</td>
					</tr>
					<tr>
						<td>
							<font size="2"><br><?=disp_msg($msg, '����', '<br><br>')?><br></font>
						</td>
					</tr>
					<tr>
						<td colspan="2" height="2" bgcolor="#CCFF33"><img src="img/spacer.gif" width="400" height="2"></td>
					</tr>
				</td>
			</table>
		</td>
	</tr>
	<tr>
		<td align="center">
			<input type="image" src="img/back.gif" onclick="history.back()" width=106 height=46 alt="���">
		</td>
	</tr>
<table>
<?
} else {
?>
<table>
	<tr>
		<td>
			<table border="0" width="400" cellspacing="10">
				<td colspan="2" height="2" bgcolor="#FF3366"><img src="img/spacer.gif" width="400" height="2"></td>
					<tr>
						<td align="center">
							<font size="2" color="red"><br>���åץ��ɤ���λ���ޤ�����<br></font>
						</td>
					</tr>
					<tr>
						<td align="center">
							<font size="2"><br>���鳨���Ϥ�����ޤǡ����Ф餯���Ԥ�����������<br><br></font>
						</td>
					</tr>
					<tr>
						<td colspan="2" height="2" bgcolor="#FF3366"><img src="img/spacer.gif" width="400" height="2"></td>
					</tr>
				</td>
			</table>
		</td>
	</tr>
	<tr>
		<td align="center">
			<input type="image" src="img/close.gif" onclick="window.close()" width=106 height=46 alt="�Ĥ���">
		</td>
	</tr>
<table>
<?
}
?>
</div>
</body>
</html>