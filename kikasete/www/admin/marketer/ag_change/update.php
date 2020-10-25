<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:エージェント変更
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/mail.php");

function mail_send($mail_addr, $marketer_name, $old_agent_name, $new_agent_name) {
	get_mail_template('agc_mk', $subject, $from, $cc, $bcc, $repty_to, $body);
	$body = str_replace('%MARKETER_NAME%', $marketer_name, $body);
	$body = str_replace('%OLD_AGENT_NAME%', $old_agent_name, $body);
	$body = str_replace('%NEW_AGENT_NAME%', $new_agent_name, $body);
	send_mail($subject, $mail_addr, $from, $body, $cc, $bcc, $reply_to);
}

// メイン処理
set_global('marketer', 'マーケター管理', 'エージェント変更', BACK_TOP);

// 発行メールアドレス取得
$sql = "SELECT ac_agent_id,mr_mail_addr,mr_name1,ag_cur.ag_name1 AS cur_ag_name1,ag_cur.ag_name2 AS cur_ag_name2,ag_new.ag_name1 AS new_ag_name1,ag_new.ag_name2 AS new_ag_name2"
		. " FROM ((t_agent_change JOIN t_marketer ON ac_marketer_id=mr_marketer_id)"
		. " LEFT JOIN t_agent AS ag_cur ON mr_agent_id=ag_cur.ag_agent_id)"
		. " LEFT JOIN t_agent AS ag_new ON ac_agent_id=ag_new.ag_agent_id"
		. " WHERE mr_marketer_id=$marketer_id $order_by $limit";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);

	db_begin_trans();

	// マーケターのエージェントを変更
	$sql = "UPDATE t_marketer SET mr_agent_id=$fetch->ac_agent_id WHERE mr_marketer_id=$marketer_id";
	if (db_exec($sql)) {
		// エージェント変更依頼を削除
		$sql = "DELETE FROM t_agent_change WHERE ac_marketer_id=$marketer_id";
		db_exec($sql);

		db_commit_trans();

		// 変更通知メール送信
		mail_send($fetch->mr_mail_addr, $fetch->mr_name1, "$fetch->cur_ag_name1 $fetch->cur_ag_name2", "$fetch->new_ag_name1 $fetch->new_ag_name2");

		$msg = 'エージェントを変更しました。';
		$back = "location.href='list.php'";
	} else {
		$msg = 'エージェント変更はできませんでした。';
		$back = 'history.back()';
	}
} else {
	$msg = '指定したマーケターのエージェント変更依頼はありません。';
	$back = 'history.back()';
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
<p><input type="button" id="ok" value="　戻る　" onclick="<?=$back?>"></p>
</div>

<? page_footer() ?>
</body>
</html>
