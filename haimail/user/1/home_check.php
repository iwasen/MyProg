<?
include('../inc/common.php');
include('../inc/reginfo.php');
include('../inc/get_profile.php');

//=== メイン処理 ===
session_start();
$reginfo = new CRegInfo;

$reginfo->kyoumi_genre = GetKyoumiGenre();

$reginfo->ClearErrFlag();

$err_flag = false;
if (strstr($reginfo->kyoumi_genre, '1') == false && strstr($reginfo->kyoumi_genre, '2') == false) {
	$reginfo->err_kyoumi_genre = true;
	$err_flag = true;
}

$reginfo->SaveData();

if ($err_flag)
	redirect('home_error.html');
else
	redirect('1-2.php');
?>