<?php
	$print_msg = "";
	$Chk_flg = TRUE;

	if ($pub_chg1) {
		$mail_add_sub = $mail_add_main;
	}

	// ���ܥ᡼�륢�ɥ쥹�Υ����å�
	if (strlen($mail_add_main) == 0)
	{
		$print_msg = "���ܥ᡼�륢�ɥ쥹����������Ƥ��ʤ��褦�Ǥ���<br>\n";
	} else {
		// ���ܥ᡼�륢�ɥ쥹��Ĺ�������å�
		if (strlen($mail_add_main) > 50 || strlen($mail_add_sub) > 50)
		{
			$print_msg = $print_msg .  "�᡼�륢�ɥ쥹�ϡ�Ⱦ�ѣ���ʸ����������Ϥ��Ƥ���������<br>\n";
		} else {
			// ���ܥ᡼�륢�ɥ쥹���ͥ����å�
			if ( !(ereg ("^([0-9a-zA-Z._-]+)(@[0-9a-zA-Z._-]+)$", $mail_add_main)) )
			{
				$print_msg = $print_msg .  "���ܥ᡼�륢�ɥ쥹�����������Ϥ���Ƥ��ʤ��褦�Ǥ���<br>\n";
			} else {
				// ���ĤΥ��ɥ쥹��Ʊ���������å�
				if  (strcmp($mail_add_main, $mail_add_sub) != 0)
				{
					$print_msg = $print_msg .  "���Ϥ��줿���ĤΥ᡼�륢�ɥ쥹�����פ��Ƥ��ʤ��褦�Ǥ�������ǧ����������<br>\n";
				}

			}
		}
	}
	if ($print_msg == "") {
		// �᡼�륢�ɥ쥹��ʣ�����å�
		$wk_flg = 0;
//		$conn = db_conxxxt();
		$sql = "SELECT * FROM M_PUBLISHER WHERE mail_add_main = '" . $mail_add_main . "'";
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
/* **
		$stmt = OCIParse($conn, $sql);
		OCIDefinebyname($stmt, "PUBLISHER_ID", &$wk_pub_id);
		$ncols = OCIExecute($stmt, OCI_DEFAULT);
		while (OCIFetch($stmt)) {
*/
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
			$wk_pub_id = $fetch->publisher_id;
			if ($wk_pub_id != $publisher_id) {
				$wk_flg = 1;
			}
		}
//		db_logoff();
		if ($wk_flg == 1) {
			$print_msg = $print_msg . "���������줿�᡼�륢�ɥ쥹�Ϥ��Ǥ���Ͽ����Ƥ���褦�Ǥ���"
				     . "�����Ѥ�Ǥ�ʣ���Υ��ޥ���ȯ�ԼԴ��������ڡ�������"
				     . "�������Ǥ��ޤ���";
		}
	}

	// ��ȯ�ԼԻ�̾�ʴ����ˡפΥ����å�
	$owner_nm_kanj = i18n_ja_jp_hantozen($owner_nm_kanj,"K");
	if (strlen($owner_nm_kanj) == 0)
	{
		$print_msg = $print_msg .  "ȯ�ԼԻ�̾�ʴ����ˤ���������Ƥ��ʤ��褦�Ǥ���<br>\n";
	} else {
		// ��ȯ�ԼԻ�̾�ʴ����ˡפ�Ĺ�������å�
		if (strlen($owner_nm_kanj) > 50)
		{
			$print_msg = $print_msg .  "ȯ�ԼԻ�̾�ʴ����ˤϡ����ѣ���ʸ����������Ϥ��Ƥ���������<br>\n";
		}
	}
	// ��ȯ�ԼԻ�̾�ʥ��ʡˡפΥ����å�
	$owner_nm_kana = i18n_ja_jp_hantozen($owner_nm_kana,"K");
	if (strlen($owner_nm_kana) == 0)
	{
		$print_msg = $print_msg .  "ȯ�ԼԻ�̾�ʥ��ʡˤ���������Ƥ��ʤ��褦�Ǥ���<br>\n";
	} else {
		// ��ȯ�ԼԻ�̾�ʥ��ʡˡפ�Ĺ�������å�
		if (strlen($owner_nm_kana) > 50)
		{
			$print_msg = $print_msg .  "ȯ�ԼԻ�̾�ʥ��ʡˤϡ����ѣ���ʸ����������Ϥ��Ƥ���������<br>\n";
		}
	}
	// ��Ϣ����͹���ֹ�פΥ����å�
	if (strlen($owner_zip1) == 0 || strlen($owner_zip2) == 0)
	{
		$print_msg = $print_msg .  "Ϣ����͹���ֹ椬��������Ƥ��ʤ��褦�Ǥ���<br>\n";
	} else {
		// ��Ϣ����͹���ֹ�פΥ����å�
		if (strlen($owner_zip1) <> 3 || strlen($owner_zip2) <> 4)
		{
			$print_msg = $print_msg .  "Ϣ����͹���ֹ�ϡ�Ⱦ�ѣ��塽Ⱦ�ѣ�������Ϥ��Ƥ���������<br>\n";
		} else {
			if ( !(ereg ("^[0-9]{3}$", $owner_zip1)) || !(ereg ("^[0-9]{4}$", $owner_zip2)) )
			{
				$print_msg = $print_msg .  "����Ϣ����͹���ֹ椬���������Ϥ���Ƥ��ʤ��褦�Ǥ���<br>\n";
			}
		}
	}
	// ��Ϣ���轻��פΥ����å�
	if (strlen($owner_add) == 0)
	{
		$print_msg = $print_msg .  "Ϣ���轻�꤬��������Ƥ��ʤ��褦�Ǥ���<br>\n";
	} else {
		// ��Ϣ���轻��פΥ����å�
		if (strlen($owner_add) > 100)
		{
			$print_msg = $print_msg .  "Ϣ���轻��ϡ����ѣ���ʸ����������Ϥ��Ƥ���������<br>\n";
		}
	}
	// ��Ϣ���������ֹ�פΥ����å�
	if (strlen($owner_tel) == 0)
	{
		$print_msg = $print_msg .  "Ϣ���������ֹ椬��������Ƥ��ʤ��褦�Ǥ���<br>\n";
	} else {
		// ��Ϣ���������ֹ�פΥ����å�
		if (strlen($owner_tel) > 15)
		{
			$print_msg = $print_msg .  "Ϣ���������ֹ�ϡ�Ⱦ�ѣ���ʸ����������Ϥ��Ƥ���������<br>\n";
		} elseif(strlen($owner_tel) < 5) {
			$print_msg = $print_msg .  "Ϣ���������ֹ�ϡ�Ⱦ�ѣ�ʸ���ʾ�����Ϥ��Ƥ���������<br>\n";
		} else {
			if ( !(ereg ("^[0-9()\-]+$", $owner_tel)) )
			{
				$print_msg = $print_msg .  "Ϣ���������ֹ椬���������Ϥ���Ƥ��ʤ��褦�Ǥ���<br>\n";
			}
		}
	}
	// �֥ѥ���ɡפΥ����å�
	if (strlen($owner_pswd_new) == 0 )
	{
		$print_msg = $print_msg .  "�ѥ���ɤ����Ϥ���Ƥ��ʤ��褦�Ǥ�<br>\n";
	} else {
		// �֥ѥ���ɡפΥ����å�
		if (strlen($owner_pswd_new) < 4 || strlen($owner_pswd_new) > 8)
		{
			$print_msg = $print_msg .  "0�ѥ���ɤϡ�Ⱦ�Ѥǣ�ʸ���ʾ塢��ʸ����������Ϥ��Ƥ���������<br>\n";
		} else {
			if ( !(ereg ("^[0-9a-zA-Z]{4,}$", $owner_pswd_new)) )
			{
				$print_msg = $print_msg .  "�ѥ���ɤ����������Ϥ���Ƥ��ʤ��褦�Ǥ���<br>\n";
			}
		}
	}
	if ($print_msg != "")
	{
		$Chk_flg = FALSE;
	}

?>