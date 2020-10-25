<?
//=== 興味ジャンル選択状態取得 ===
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

//=== よく行く店選択状態取得 ===
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

//=== あなたはどっち派選択状態取得 ===
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