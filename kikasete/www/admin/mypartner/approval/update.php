<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:Myパートナー・リクルート承認処理
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/enq_temp.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_partner.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

// メイン処理
set_global('mypartner', 'Myパートナー管理', 'Myパートナー承認', BACK_TOP);

// Myパートナー情報取得
$myp = new my_partner_class;
$myp->read_db($pjt_id);

db_begin_trans();

if ($flag == '1') {
	// プロジェクトのステータスセット
	$myp->status = MPS_REC_SHOUNIN;

	// 件名が未設定ならデフォルトをセット
	if ($myp->subject == '')
		$myp->subject = $myp->enquete->title;

	// メールが未設定ならデフォルトをセット
	if ($myp->body == '')
		get_enq_body($myp, $myp->header, $myp->body, $myp->footer);

	// リクルート開始日時を取得
	$myp->recruit_start_y = $start_date_y;
	$myp->recruit_start_m = $start_date_m;
	$myp->recruit_start_d = $start_date_d;
	$myp->recruit_start_h = $start_date_h;
	$myp->recruit_start = sprintf("%d/%d/%d %d:0:0", $start_date_y, $start_date_m, $start_date_d, $start_date_h);

	// Myパートナー情報更新
	$myp->write_db();

	// アンケートのステータスをセット
	$rec['en_status'] = sql_number(4);

	// アンケート開始終了日時をセット
	$rec['en_start_date'] = sql_date($myp->add_send_date == '' ? $myp->recruit_start : $myp->add_send_date);
	$rec['en_end_date'] = sql_date($myp->recruit_end);

	// アンケート説明文が未設定ならデフォルトをセット
	if ($myp->enquete->description == '')
		$rec['en_description'] = sql_char(get_enq_description($myp));

	// アンケート更新
	db_update('t_enquete', $rec, "en_enquete_id=$myp->enquete_id");

	$msg = 'Ｍｙパートナーを承認しました。';
} elseif ($flag == '2') {
	// プロジェクトのステータスセット
	$myp->status = MPS_REC_JUNBI;

	// Myパートナー情報更新
	$myp->write_db();

	$msg = 'Ｍｙパートナーを差し戻しました。';
}

db_commit_trans();
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body onLoad="document.all.ok.focus()">
<? page_header() ?>

<div align="center">
<p class="msg"><?=$msg?></p>
<p><input type="button" id="ok" value="　戻る　" onclick="location.href='list.php'"></p>
</div>

<? page_footer() ?>
</body>
</html>
