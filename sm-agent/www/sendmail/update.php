<?
/******************************************************
' System :メール配信サービス
' Content:配信メール更新
'******************************************************/

$top = '..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/header.php");
include("$inc/database.php");
include("$inc/mail.php");
include("$inc/check.php");

// テストメール送信
function test_mail() {
	global $subject, $from_addr, $reply_to, $body, $from_name, $plug_vars, $user_login_id;

	$sql = "SELECT us_mail_addr FROM m_user WHERE us_user_id=$user_login_id";
	$mail_addr = db_fetch1($sql);

	$send_mail_id = send_mail_data($subject, $from_addr, $reply_to, $body, '', '', $from_name);
	send_mail_list($send_mail_id, $mail_addr, 1);

	$plug_ary = explode(',', $plug_vars);

	$i = 1;
	foreach ($plug_ary as $plug_var) {
		$plug_var = trim($plug_var);
		if ($plug_var != '' && $plug_var != '%%FROM_ADDR%%') {
			send_mail_embed($send_mail_id, 1, $plug_var, "[差込データ$i]");
			$i++;
		}
	}

	send_mail_ok($send_mail_id);
}

// 送信先リスト保存
function add_mail_list($file, $send_mail_id, &$count0, &$count1) {
	$fp = fopen($file, 'r');
	$i = 0;
	while ($data = fgetcsv($fp, 1000, ',')) {
		if (trim($data[0]) != '') {
			$addr[$i] = mb_convert_encoding(trim($data[0]), 'EUC-JP', 'SJIS');
			$plug[$i] = mb_convert_encoding(join("\n", array_slice($data, 1)), 'EUC-JP', 'SJIS');
			$i++;
		}
	}

	$count = 0;
	$count0 = 0;
	$count1 = 0;
	foreach (array_keys(array_unique($addr)) as $i) {
		$mail_addr = $addr[$i];

		if (check_mail_addr($mail_addr)) {
			$status = 0;
			$count0++;
		} else {
			$status = 1;
			$count1++;
		}

		$sql = sprintf("INSERT INTO t_mail_list (ml_send_mail_id,ml_sub_seq,ml_mail_addr,md_plug_data,ml_status) VALUES (%s,%s,%s,%s,%s)",
				sql_number($send_mail_id),
				sql_number(++$count),
				sql_char($mail_addr),
				sql_char($plug[$i]),
				sql_number($status));
		db_exec($sql);
	}

	return $count;
}

// メイン処理
set_global('配信メール登録', BACK_TOP);

switch ($next_action) {
case 'new':
	db_begin_trans();

	$send_mail_id = send_mail_data($subject, $from_addr, $reply_to, $body, '', '', $from_name);

	add_mail_list($list_file, $send_mail_id, $count0, $count1);

	$sql = sprintf("INSERT INTO t_mail_data (md_send_mail_id,md_user_id,md_seq_no,md_send_date,md_limit_date,md_plug_vars) VALUES (%s,%s,%s,%s,%s,%s)",
				sql_number($send_mail_id),
				sql_number($user_login_id),
				"(SELECT COALESCE(MAX(md_seq_no),0)+1 FROM t_mail_data WHERE md_user_id=$user_login_id)",
				sql_datetime($send_date_check, $send_date_y, $send_date_m, $send_date_d, $send_date_h, $send_date_i),
				sql_datetime($limit_date_check, $limit_date_y, $limit_date_m, $limit_date_d),
				sql_char($plug_vars));
	db_exec($sql);

	$sql = sprintf("INSERT INTO t_mail_count (mc_send_mail_id,mc_count0,mc_count1) VALUES (%s,%s,%s)",
				sql_number($send_mail_id),
				sql_number($count0),
				sql_number($count1));
	db_exec($sql);

	db_commit_trans();

	$msg = '配信メールを登録しました。';
	$back = "location.href='list.php'";
	break;
case 'update':
	db_begin_trans();

	$sql = sprintf("UPDATE t_send_mail_data SET sd_subject=%s,sd_from=%s,sd_reply_to=%s,sd_body=%s,sd_personal=%s WHERE sd_send_mail_id=$send_mail_id",
			sql_char($subject),
			sql_char($from_addr),
			sql_char($reply_to),
			sql_char($body),
			sql_char($from_name));
	db_exec($sql);

	if ($list_file) {
		$sql = "DELETE FROM t_mail_list WHERE ml_send_mail_id=$send_mail_id";
		db_exec($sql);

		add_mail_list($list_file, $plug_vars, $send_mail_id, $count0, $count1);

		$sql = sprintf("UPDATE t_mail_data SET md_send_date=%s,md_limit_date=%s,md_plug_vars=%s WHERE md_send_mail_id=$send_mail_id",
				sql_datetime($send_date_check, $send_date_y, $send_date_m, $send_date_d, $send_date_h, $send_date_i),
				sql_datetime($limit_date_check, $limit_date_y, $limit_date_m, $limit_date_d),
				sql_char($plug_vars));
		db_exec($sql);

		$sql = sprintf("UDPATE t_mail_count SET mc_count0=%s,mc_count1=%s WHERE mc_send_mail_id=$send_mail_id",
				sql_number($count0),
				sql_number($count1));
		db_exec($sql);
	} else {
		$sql = sprintf("UPDATE t_mail_data SET md_send_date=%s,md_limit_date=%s,md_plug_vars=%s WHERE md_send_mail_id=$send_mail_id",
				sql_datetime($send_date_check, $send_date_y, $send_date_m, $send_date_d, $send_date_h, $send_date_i),
				sql_datetime($limit_date_check, $limit_date_y, $limit_date_m, $limit_date_d),
				sql_char($plug_vars));
		db_exec($sql);
	}

	db_commit_trans();

	$msg = '配信メールを更新しました。';
	$back = "location.href='list.php'";
	break;
case 'delete':
	db_begin_trans();

	$sql = "DELETE FROM t_send_mail_data WHERE sd_send_mail_id=$send_mail_id";
	db_exec($sql);

	$sql = "DELETE FROM t_send_mail_list WHERE sl_send_mail_id=$send_mail_id";
	db_exec($sql);

	$sql = "DELETE FROM t_send_mail_embed WHERE se_send_mail_id=$send_mail_id";
	db_exec($sql);

	$sql = "DELETE FROM t_mail_data WHERE md_send_mail_id=$send_mail_id";
	db_exec($sql);

	$sql = "DELETE FROM t_mail_list WHERE ml_send_mail_id=$send_mail_id";
	db_exec($sql);

	$sql = "DELETE FROM t_mail_count WHERE mc_send_mail_id=$send_mail_id";
	db_exec($sql);

	db_commit_trans();

	$msg = '配信メールを削除しました。';
	$back = "location.href='list.php'";
	break;
case 'test':
	test_mail();
	$msg = 'テストメールを送信しました。<br><span class="note">（届くまで１分ほどかかります）</span>';
	$back = "history.back()";
	break;
default:
	redirect('list.php');
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
