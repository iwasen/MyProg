<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:ユーザ情報表示・引継ぎ情報登録
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");

// 複数選択チェックボックス取得
function get_multi_checkbox($checkbox, $len) {
	$s = str_repeat('0', $len);
	if (is_array($checkbox)) {
		foreach ($checkbox as $c)
			$s{$c} = '1';
	}
	return $s;
}

// 入力パラメータ
$user_id = $_POST['user_id'];
$hot = $_POST['hot'];

db_begin_trans();

// ユーザ対応結果登録
$rec['urs_sonota_kanshin'] = sql_number($_POST['sonota_kanshin']);
$rec['urs_kanshin_maker'] = sql_number($_POST['kanshin_maker']);
$rec['urs_kanshin_car'] = sql_char($_POST['kanshin_car']);
$rec['urs_jishuu_enent'] = sql_number($_POST['jishuu_enent']);
$rec['urs_shiryou_souhu'] = sql_char(get_multi_checkbox($_POST['shiryou_souhu'], 10));
$rec['urs_shiryou_comment'] = sql_char($_POST['shiryou_comment']);
$rec['urs_teikyou_keizoku'] = sql_number($_POST['teikyou_keizoku']);
$rec['urs_teikyou_keitai'] = sql_char(get_multi_checkbox($_POST['teikyou_keitai'], 10));
$rec['urs_mise_youbou'] = sql_number($_POST['mise_youbou']);
$rec['urs_commant'] = sql_char($_POST['commant']);
$rec['urs_shumi'] = sql_char($_POST['shumi']);
$rec['urs_kecchaku'] = sql_number(6/*$_POST['kecchaku']*/);
$rec['urs_rank'] = sql_char($_POST['rank']);
$rec['urs_commant1'] = sql_char($_POST['commant1']);
$rec['urs_commant2'] = sql_char($_POST['commant2']);
$rec['urs_commant3'] = sql_char($_POST['commant3']);
$rec['urs_commant4'] = sql_char($_POST['commant4']);
$rec['urs_commant5'] = sql_char($_POST['commant5']);
$rec['urs_commant6'] = sql_char($_POST['commant6']);
$rec['urs_kounyuu_joukyou'] = sql_number($_POST['kounyuu_joukyou']);
$rec['urs_kounyuu_shamei'] = sql_char($_POST['kounyuu_shamei']);

$sql = "SELECT COUNT(*) FROM t_user_result WHERE urs_user_id=$user_id";
$result = db_exec($sql);
if (pg_fetch_result($result, 0, 0) == 0) {
	$rec['urs_user_id'] = sql_number($user_id);
	db_insert('t_user_result', $rec);
} else {
	db_update('t_user_result', $rec, "urs_user_id=$user_id");
}

// HOT状態設定
if ($hot) {
	$rec['ust_status'] = 3;
	$rec['ust_handover_staff_id'] = $g_staff_id;
	$rec['ust_handover_date'] = 'current_timestamp';
	db_update('t_user_status', $rec, "ust_user_id=$user_id");

	$rec['urg_handover_date'] = 'current_timestamp';
	db_update('t_user_regist', $rec, "urg_user_id=$user_id");
}

db_commit_trans();

$msg = 'ユーザ対応結果は正常に登録されました。';
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=SYSTEM_NAME?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
</head>
<body onLoad="document.all.ok.focus()">

<? center_header('ユーザ情報表示｜引継ぎ情報登録') ?>

<div align="center">
<p class="msg"><?=$msg?></p>
<p><input type="button" id="ok" value="　戻る　" onclick="location.href='info.php?user_id=<?=$user_id?>'"></p>
</div>

<? center_footer() ?>

</body>
</html>
