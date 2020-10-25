<?
/******************************************************
' System :ICP共通インクルードファイル
' Content:権限
'******************************************************/

define('MAX_STAFF_AUTH', 10);
define('MAX_PARTICIPANT_AUTH', 10);
define('AUTH_ON', '1');
define('AUTH_OFF', '0');

// スタッフ系権限
$g_staff_auth = array(
	'プロジェクトメンバ管理' => 0,
	'ジョブ管理' => 1,
	'発言ログエクスポート' => 2,
	'発言録エクスポート' => 3,
	'参加者メンバエクスポート' => 4,
	'発言集計エクスポート' => 5
);

// 参加者系権限
$g_participant_auth = array(
	'司会者' => 0,
	'Web発言' => 1,
	'メール送信' => 2,
	'メール受信' => 3,
	'発言ON/OFF' => 4
);

// スタッフ系権限初期値
function init_staff_auth() {
	return str_repeat(AUTH_OFF, MAX_STAFF_AUTH);
}

// 参加者系権限初期値
function init_participant_auth() {
	return str_repeat('0', MAX_PARTICIPANT_AUTH);
}

// オペレータのスタッフ系デフォルト権限
function operator_staff_auth() {
	$auth = init_staff_auth();

	add_staff_auth('プロジェクトメンバ管理', $auth);
	add_staff_auth('ジョブ管理', $auth);
	add_staff_auth('発言ログエクスポート', $auth);
	add_staff_auth('発言録エクスポート', $auth);
	add_staff_auth('参加者メンバエクスポート', $auth);
	add_staff_auth('発言集計エクスポート', $auth);

	return $auth;
}

// オペレータの参加者系デフォルト権限
function operator_participant_auth() {
	$auth = init_participant_auth();

	add_participant_auth('司会者', $auth);
	add_participant_auth('メール受信', $auth);
	add_participant_auth('発言ON/OFF', $auth);

	return $auth;
}

// 通常メンバのスタッフ系デフォルト権限
function member_staff_auth() {
	$auth = init_staff_auth();

	return $auth;
}

// 通常メンバの参加者系デフォルト権限
function member_participant_auth() {
	$auth = init_participant_auth();

	add_participant_auth('Web発言', $auth);
	add_participant_auth('メール送信', $auth);
	add_participant_auth('メール受信', $auth);
	add_participant_auth('発言ON/OFF', $auth);

	return $auth;
}

// スタッフ系権限の有無チェック
function check_staff_auth($auth_key, $auth = null) {
	global $g_staff_auth;

	if ($auth == null)
		$auth = &$_SESSION[SCD]['staff_auth'];

	return $auth{$g_staff_auth[$auth_key]} == AUTH_ON;
}

// スタッフ系権限追加
function add_staff_auth($auth_key, &$auth) {
	global $g_staff_auth;

	$auth{$g_staff_auth[$auth_key]} = AUTH_ON;
}

// スタッフ系権限削除
function del_staff_auth($auth_key, &$auth) {
	global $g_staff_auth;

	$auth{$g_staff_auth[$auth_key]} = AUTH_OFF;
}

// 参加者系権限の有無チェック
function check_participant_auth($auth_key, $auth = null) {
	global $g_participant_auth;

	if ($auth == null)
		$auth = &$_SESSION[SCD]['participant_auth'];

	return $auth{$g_participant_auth[$auth_key]} == AUTH_ON;
}

// 参加者系権限追加
function add_participant_auth($auth_key, &$auth) {
	global $g_participant_auth;

	$auth{$g_participant_auth[$auth_key]} = AUTH_ON;
}

// 参加者系権限削除
function del_participant_auth($auth_key, &$auth) {
	global $g_participant_auth;

	$auth{$g_participant_auth[$auth_key]} = AUTH_OFF;
}

// カンマ区切りのスタッフ権限コードから編集
function str_staff_auth($str) {
	$auth = init_staff_auth();

	$ary = explode(',', $str);
	foreach ($ary as $auth_cd) {
		if ($auth_cd !== '')
			$auth{$auth_cd} = AUTH_ON;
	}

	return $auth;
}

// カンマ区切りの参加者権限コードから編集
function str_participant_auth($str) {
	$auth = init_participant_auth();

	$ary = explode(',', $str);
	foreach ($ary as $auth_cd) {
		if ($auth_cd !== '')
			$auth{$auth_cd} = AUTH_ON;
	}

	return $auth;
}

// スタッフ権限SQL条件文
function cond_staff_auth($auth_key) {
	global $g_staff_auth;

	return "substr(pm_staff_auth," . ($g_staff_auth[$auth_key] + 1) . ",1)='" . AUTH_ON . "'";
}

// 参加者権限SQL条件文
function cond_participant_auth($auth_key) {
	global $g_participant_auth;

	return "substr(jm_participant_auth," . ($g_participant_auth[$auth_key] + 1) . ",1)='" . AUTH_ON . "'";
}
?>