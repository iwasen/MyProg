<?
/******************************************************
' System :「きゃららFactory」管理者用ページル
' Content:似顔絵チェックリスト 
'******************************************************/

$top = "../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/mail.php");

set_global('staff', 'スタッフ情報管理', '似顔絵チェックリスト', BACK_TOP);

// 似顔絵完了メール送信
function download_mail_send($random_id,$name_text, $mail_addr) {
	get_mail_template('sv_creo2', $subject, $from, $cc, $bcc, $repty_to, $body);
	$body = str_replace('%NAME_TEXT%', $name_text, $body);
	$body = str_replace('%RANDOM_ID%', $random_id, $body);
	return send_mail($subject, $mail_addr, $from, $body, $cc, $bcc, $reply_to);
}

$sql = "SELECT nd_random_id,nd_mail_addr,nd_name_text FROM t_nigaoe_data WHERE nd_status=6 AND nd_nigaoe_id=$id";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	switch ($next_action) {
	case 'OK':
		// ステータス更新
		$sql = "UPDATE t_nigaoe_data set"
			. " nd_status=7"
			. " WHERE nd_nigaoe_id=$id";
		db_exec($sql);

		// メール送信
		if (download_mail_send($fetch->nd_random_id, $fetch->nd_name_text, $fetch->nd_mail_addr)) {
			// ステータス更新
			$sql = "UPDATE t_nigaoe_data set"
				. " nd_status=8"
				. ", nd_dl_mail_date='now'"
				. " WHERE nd_nigaoe_id=$id";
			db_exec($sql);

			$msg = 'ユーザに「似顔絵完了通知」を送信しました。';
			$ret = 'location.href=\'list.php\'';
		} else {
			$msg = 'ユーザに「似顔絵完了通知」を送信できませんでした。';
			$ret = 'location.href=history.back()';
		}
		break;
	case 'NG':
		// ステータス更新
		$sql = "UPDATE t_nigaoe_data set"
			. " nd_status=4"
			. ", nd_completion_date=null"
			. " WHERE nd_nigaoe_id=$id";
		db_exec($sql);
		$msg = '担当者に修正依頼しました。';
	$ret = 'location.href=\'list.php\'';
		break;
	default:
		redirect('list.php');
	}
} else {
	$msg = '実行できませんでした。';
	$ret = 'location.href=\'list.php\'';
}
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
<p><input type="button" id="ok" value="　戻る　" onclick="<?=$ret?>"></p>
</div>

<? page_footer() ?>
</body>
</html>
