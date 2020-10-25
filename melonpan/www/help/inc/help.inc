<?
function get_title($cd){
	switch($cd) {
		case 1:
			return '読者向け問い合わせ窓口';
		case 2:
			return '発行者向け問い合わせ窓口';
		case 3:
			return '広告主様・広告関係向け窓口';
		case 4:
			return 'その他、全般問い合わせ窓口';
		break;
	}
}

function check_input($cd, $to_adr, $mailadr, $body, $name){

	if(trim($name) == '')
		$err_msg = '・名前が入力されていないようです。<br>';
	if(trim($mailadr) == '')
		$err_msg .= '・メールアドレスが入力されていないようです。<br>';
	if(trim($body) == '')
		$err_msg .= '・本文が入力されていないようです。<br>';

	if ($err_msg) {
		include("err_msg.php");
		exit();
	} else {
	
		$body = $name . " 様\n\n" . $body;
		echo "sadfa=".send_mail2(get_title($cd), $to_adr, $mailadr, $bcc, $body, $reply_to=0);
	}
}

?>