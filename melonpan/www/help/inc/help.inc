<?
function get_title($cd){
	switch($cd) {
		case 1:
			return '�ɼԸ����䤤��碌���';
		case 2:
			return 'ȯ�ԼԸ����䤤��碌���';
		case 3:
			return '������͡�����ط��������';
		case 4:
			return '����¾�������䤤��碌���';
		break;
	}
}

function check_input($cd, $to_adr, $mailadr, $body, $name){

	if(trim($name) == '')
		$err_msg = '��̾�������Ϥ���Ƥ��ʤ��褦�Ǥ���<br>';
	if(trim($mailadr) == '')
		$err_msg .= '���᡼�륢�ɥ쥹�����Ϥ���Ƥ��ʤ��褦�Ǥ���<br>';
	if(trim($body) == '')
		$err_msg .= '����ʸ�����Ϥ���Ƥ��ʤ��褦�Ǥ���<br>';

	if ($err_msg) {
		include("err_msg.php");
		exit();
	} else {
	
		$body = $name . " ��\n\n" . $body;
		echo "sadfa=".send_mail2(get_title($cd), $to_adr, $mailadr, $bcc, $body, $reply_to=0);
	}
}

?>