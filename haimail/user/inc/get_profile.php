<?
//=== ��̣������������ּ��� ===
function GetKyoumiGenre() {
	$s = '';
	for ($i = 1; $i <= MAX_KYOUMIGENRE; $i++) {
		if (isset($_POST["kyoumi$i"]))
			$s .= $_POST["kyoumi$i"];
		else
			$s .= '0';
	}
	return $s;
}

//=== �褯�Ԥ�Ź������ּ��� ===
function GetYokuikuMise() {
	$s = '';
	for ($i = 1; $i <= MAX_YOKUIKUMISE; $i++) {
		if (isset($_POST["yokuiku_mise$i"]))
			$s .= $_POST["yokuiku_mise$i"];
		else
			$s .= '0';
	}
	return $s;
}

//=== ���ʤ��Ϥɤä���������ּ��� ===
function GetDocchiHa() {
	$s = '';
	for ($i = 1; $i <= MAX_DOCCHIHA; $i++) {
		if (isset($_POST["docchiha$i"]))
			$s .= $_POST["docchiha$i"];
		else
			$s .= '0';
	}
	return $s;
}
?>