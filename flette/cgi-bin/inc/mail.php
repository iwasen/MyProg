<?
/******************************************************
' System :flette�����åեڡ���
' Content:�᡼����������
'******************************************************/

// �᡼��ƥ�ץ졼�ȼ���
function get_mail_template($template_id, &$subject, &$from, &$from_name, &$cc, &$bcc, &$to_addr, &$body) {
	$sql = "SELECT * FROM m_mail_template WHERE mt_template_id='$template_id'";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$subject = $fetch->mt_subject;
		$from = $fetch->mt_from_addr;
		$from_name = $fetch->mt_from_name;
		$cc = $fetch->mt_cc_addr;
		$bcc = $fetch->mt_bcc_addr;
		$to_addr = $fetch->mt_to_addr;
		$body = str_replace("\r\n", "\n", $fetch->mt_body);
	}
}

// �᡼������
function send_mail($subject, $to, $from, $from_name, $body, $cc=0, $bcc=0, $reply_to=0) {
	$header = "From: " . encode_address($from_name, $from) . "\n";

	if ($cc)
		$header .= "Cc: " . multi_address($cc) . "\n";

	if ($bcc)
		$header .= "Bcc: " . multi_address($bcc) . "\n";

	if ($reply_to)
		$header .= "Reply-to: $reply_to\n";

	return mb_send_mail($to, $subject, str_replace("\r", '', $body), $header, "-f $from");
}

// ̾���դ����ɥ쥹���󥳡���
function encode_address($name, $addr) {
	if ($name != '') {
		return mb_encode_mimeheader(trim($name), 'ISO-2022-JP') . ' <' . trim($addr) . '>';
	} else
		return $addr;
}

// ʣ�����ɥ쥹�򥫥�޶��ڤ�Ƿ��
function multi_address($addr) {
	$ary = array();
	$tok = strtok($addr, " ,\r\n");
	while($tok) {
	   $ary[] = $tok;
	   $tok = strtok(" ,\r\n");
	} 

	return join(',', $ary);
}
?>