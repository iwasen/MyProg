<?php
$print_msg = "";
$Chk_flg = TRUE;

if ($pub_chg1) {
	$mail_add_sub = $mail_add_main;
}

// ���ܥ᡼�륢�ɥ쥹�Υ����å�
if (strlen($mail_add_main) == 0)
{
	$print_msg = "���ִ��ܥ᡼�륢�ɥ쥹�פ���������Ƥ��ʤ��褦�Ǥ���<br><br>\n";
} else {
	// ���ܥ᡼�륢�ɥ쥹��Ĺ�������å�
	if (strlen($mail_add_main) > 50 || strlen($mail_add_sub) > 50)
	{
		$print_msg = $print_msg .  "���֥᡼�륢�ɥ쥹�פϡ�Ⱦ�ѣ���ʸ����������Ϥ��Ƥ���������<br><br>\n";
	} else {
		// ���ܥ᡼�륢�ɥ쥹���ͥ����å�
		if ( !(ereg ("^([0-9a-zA-Z._-]+)(@[0-9a-zA-Z._-]+)$", $mail_add_main)) )
		{
			$print_msg = $print_msg .  "���ִ��ܥ᡼�륢�ɥ쥹�פ����������Ϥ���Ƥ��ʤ��褦�Ǥ���<br><br>\n";
		} else {
			// ���ĤΥ��ɥ쥹��Ʊ���������å�
			if  (strcmp($mail_add_main, $mail_add_sub) != 0)
			{
				$print_msg = $print_msg .  "���������줿���ĤΡִ��ܥ᡼�륢�ɥ쥹�פ����Ƥ����ʤ��褦�Ǥ���<br><br>\n";
			}

		}
	}
}
if ($print_msg == "") {
	// �᡼�륢�ɥ쥹��ʣ�����å�
	$wk_flg = 0;
	$conn = db_conxxxt();
	$sql = "SELECT * FROM M_PUBLISHER WHERE mail_add_main = '" . $mail_add_main . "'";
	$stmt = OCIParse($conn, $sql);
	OCIDefinebyname($stmt, "PUBLISHER_ID", &$wk_pub_id);
	$ncols = OCIExecute($stmt, OCI_DEFAULT);
	while (OCIFetch($stmt)) {
		if ($wk_pub_id != $publisher_id) {
			$wk_flg = 1;
		}
	}
	db_logoff();
	if ($wk_flg == 1) {
		$print_msg = $print_msg . "���������줿�᡼�륢�ɥ쥹�Ϥ��Ǥ���Ͽ����Ƥ���褦�Ǥ���"
			     . "�����Ѥ�Ǥ�ʣ���Υ��ޥ���ȯ�ԼԴ��������ڡ�������"
			     . "�������Ǥ��ޤ���";
	}
}

// ��ȯ�ԼԻ�̾�ʴ����ˡפΥ����å�
if (strlen($owner_nm_kanj) == 0)
{
	$print_msg = $print_msg .  "����ȯ�ԼԻ�̾�ʴ����ˡפ���������Ƥ��ʤ��褦�Ǥ���<br><br>\n";
} else {
	// ��ȯ�ԼԻ�̾�ʴ����ˡפ�Ĺ�������å�
	if (strlen($owner_nm_kanj) > 50)
	{
		$print_msg = $print_msg .  "����ȯ�ԼԻ�̾�ʴ����ˡפϡ����ѣ���ʸ����������Ϥ��Ƥ���������<br><br>\n";
	}
}
// ��ȯ�ԼԻ�̾�ʥ��ʡˡפΥ����å�
if (strlen($owner_nm_kana) == 0)
{
	$print_msg = $print_msg .  "����ȯ�ԼԻ�̾�ʥ��ʡˡפ���������Ƥ��ʤ��褦�Ǥ���<br><br>\n";
} else {
	// ��ȯ�ԼԻ�̾�ʥ��ʡˡפ�Ĺ�������å�
	if (strlen($owner_nm_kana) > 50)
	{
		$print_msg = $print_msg .  "����ȯ�ԼԻ�̾�ʥ��ʡˡפϡ����ѣ���ʸ����������Ϥ��Ƥ���������<br><br>\n";
	}
}
// ��Ϣ����͹���ֹ�פΥ����å�
if (strlen($owner_zip1) == 0 || strlen($owner_zip2) == 0)
{
	$print_msg = $print_msg .  "����Ϣ����͹���ֹ�פ���������Ƥ��ʤ��褦�Ǥ���<br><br>\n";
} else {
	// ��Ϣ����͹���ֹ�פΥ����å�
	if (strlen($owner_zip1) <> 3 || strlen($owner_zip2) <> 4)
	{
		$print_msg = $print_msg .  "����Ϣ����͹���ֹ�פϡ�Ⱦ�ѣ��塽Ⱦ�ѣ�������Ϥ��Ƥ���������<br><br>\n";
	} else {
		if ( !(ereg ("^[0-9]{3}$", $owner_zip1)) || !(ereg ("^[0-9]{4}$", $owner_zip2)) )
		{
			$print_msg = $print_msg .  "���ִ���Ϣ����͹���ֹ�פ����������Ϥ���Ƥ��ʤ��褦�Ǥ���<br><br>\n";
		}
	}
}
// ��Ϣ���轻��פΥ����å�
if (strlen($owner_add) == 0)
{
	$print_msg = $print_msg .  "����Ϣ���轻��פ���������Ƥ��ʤ��褦�Ǥ���<br><br>\n";
} else {
	// ��Ϣ���轻��פΥ����å�
	if (strlen($owner_add) > 100)
	{
		$print_msg = $print_msg .  "����Ϣ���轻��פϡ����ѣ���ʸ����������Ϥ��Ƥ���������<br><br>\n";
	}
}
// ��Ϣ���������ֹ�פΥ����å�
if (strlen($owner_tel) == 0)
{
	$print_msg = $print_msg .  "����Ϣ���������ֹ�פ���������Ƥ��ʤ��褦�Ǥ���<br><br>\n";
} else {
	// ��Ϣ���������ֹ�פΥ����å�
	if (strlen($owner_tel) > 15)
	{
		$print_msg = $print_msg .  "����Ϣ���������ֹ�פϡ�Ⱦ�ѣ���ʸ����������Ϥ��Ƥ���������<br><br>\n";
	} else {
		if ( !(ereg ("^[0-9-]+$", $owner_tel)) )
		{
			$print_msg = $print_msg .  "����Ϣ���������ֹ�פ����������Ϥ���Ƥ��ʤ��褦�Ǥ���<br><br>\n";
		}
	}
}
// �֥ѥ���ɡפΥ����å�
if (strlen($owner_pswd_new) == 0 )
{
	$print_msg = $print_msg .  "���֥ѥ���ɡפ���Ƥ���������<br><br>\n";
} else {
	// �֥ѥ���ɡפΥ����å�
	if (strlen($owner_pswd_new) < 4 || strlen($owner_pswd_new) > 8)
	{
		$print_msg = $print_msg .  "���֥ѥ���ɡפϡ�Ⱦ�Ѥǣ�ʸ���ʾ塢��ʸ����������Ϥ��Ƥ���������<br><br>\n";
	} else {
		if ( !(ereg ("^[0-9a-zA-Z]{4,}$", $owner_pswd_new)) )
		{
			$print_msg = $print_msg .  "���֥ѥ���ɡפ����������Ϥ���Ƥ��ʤ��褦�Ǥ���<br><br>\n";
		}
	}
}

if ($print_msg != "")
{
	$Chk_flg = FALSE;
}

?>