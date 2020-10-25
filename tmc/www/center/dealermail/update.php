<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:販売店メール更新処理
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");
include("$inc/mail.php");
include("$inc/search.php");

// レコード新規追加処理
function rec_insert() {
	db_begin_trans();

	// 販売店メールIDを取得
	$sql = "SELECT max(dml_dealer_mail_id) FROM t_dealer_mail";
	$dealer_mail_id = db_fetch1($sql) + 1;

	// t_dealer_mailに挿入
	$rec['dml_dealer_mail_id'] = sql_number($dealer_mail_id);
	$rec['dml_status'] = sql_number($_POST['status'], 1);
	$rec['dml_subject'] = sql_char($_POST['subject']);
	$rec['dml_send_date'] = sql_datetime($_POST['send_date_check'], $_POST['send_date_y'], $_POST['send_date_m'], $_POST['send_date_d'], $_POST['send_date_h']);
	$rec['dml_from_name'] = sql_char($_POST['from_name']);
	db_insert('t_dealer_mail', $rec);

	// クリックカウンタ登録
	$body = trim_crlf($_POST['body']);
	regist_click_counter($dealer_mail_id, $body);

	// t_dealer_mail_contentsに挿入
	$rec['dmc_dealer_mail_id'] = sql_number($dealer_mail_id);
	$rec['dmc_header'] = sql_char($_POST['header']);
	$rec['dmc_body'] = sql_char($body);
	$rec['dmc_footer'] = sql_char($_POST['footer']);
	db_insert('t_dealer_mail_contents', $rec);

	db_commit_trans();

	return $dealer_mail_id;
}

// レコード更新処理
function rec_update() {
	$dealer_mail_id = $_POST['dealer_mail_id'];

	db_begin_trans();

	// t_dealer_mail更新
	$rec['dml_status'] = sql_number($_POST['status'], 1);
	$rec['dml_subject'] = sql_char($_POST['subject']);
	$rec['dml_send_date'] = sql_datetime($_POST['send_date_check'], $_POST['send_date_y'], $_POST['send_date_m'], $_POST['send_date_d'], $_POST['send_date_h']);
	$rec['dml_from_name'] = sql_char($_POST['from_name']);
	db_update('t_dealer_mail', $rec, "dml_dealer_mail_id=$dealer_mail_id");

	// クリックカウンタ登録
	$body = $_POST['body'];
	regist_click_counter($dealer_mail_id, $body);

	// t_dealer_mail_contents更新
	$rec['dmc_header'] = sql_char($_POST['header']);
	$rec['dmc_body'] = sql_char($body);
	$rec['dmc_footer'] = sql_char($_POST['footer']);
	db_update('t_dealer_mail_contents', $rec, "dmc_dealer_mail_id=$dealer_mail_id");

	db_commit_trans();
}

// レコード削除処理
function rec_delete() {
	$dealer_mail_id = $_POST['dealer_mail_id'];

	db_begin_trans();

	// t_search削除
	$sql = "SELECT dml_search_id FROM t_dealer_mail WHERE dml_dealer_mail_id=$dealer_mail_id";
	$result = db_exec($sql);
	$search_id = pg_fetch_result($result, 0, 0);
	if ($search_id)
		db_delete('t_search', "src_search_id=$search_id");

	// t_dealer_mail削除
	db_delete('t_dealer_mail', "dml_dealer_mail_id=$dealer_mail_id");

	// t_dealer_mail_contents削除
	db_delete('t_dealer_mail_contents', "dmc_dealer_mail_id=$dealer_mail_id");

	db_commit_trans();
}

// 検索条件保存
function save_search() {
	$dealer_mail_id = $_POST['dealer_mail_id'];

	$sql = "SELECT dml_search_id FROM t_dealer_mail WHERE dml_dealer_mail_id=$dealer_mail_id";
	$result = db_exec($sql);
	$search_id = pg_fetch_result($result, 0, 0);

	$search = new search_class;
	$search->get_form();
	$search_id = $search->write_db($search_id);

	$rec['dml_search_id'] = sql_number($search_id);
	db_update('t_dealer_mail', $rec, "dml_dealer_mail_id=$dealer_mail_id AND dml_search_id IS NULL");
}

// テストメール送信
function test_mail() {
	$sql = "SELECT dml_subject,dml_from_name,dmc_header,dmc_body,dmc_footer"
			. " FROM t_dealer_mail JOIN t_dealer_mail_contents ON dml_dealer_mail_id=dmc_dealer_mail_id"
			. " WHERE dml_dealer_mail_id={$_POST['dealer_mail_id']}";
	$result = db_exec($sql);
	if (pg_num_rows($result)) {
		$fetch = pg_fetch_object($result, 0);

		$from_addr = get_system_info('addr_dealer_mail');
		$contents = make_contents($fetch->dmc_header, $fetch->dmc_body, $fetch->dmc_footer);
		send_mail($fetch->dml_subject, $_SESSION['s_mail_addr'], "$fetch->dml_from_name <$from_addr>", $contents);
	}
}

// 状態コード設定
function set_status($status) {
	$dealer_mail_id = $_POST['dealer_mail_id'];
	$rec['dml_status'] = $status;
	db_update('t_dealer_mail', $rec, "dml_dealer_mail_id=$dealer_mail_id");
}

// クリックカウンタ登録
function regist_click_counter($dealer_mail_id, &$body) {
	if ($_POST['cc_no'] != '') {
		$cc_no = explode('||', $_POST['cc_no']);
		$cc_url_name = explode('||', $_POST['cc_url_name']);
		$cc_jump_url = explode('||', $_POST['cc_jump_url']);

		for ($i = 0; $i < count($cc_no); $i++) {
			$rec['clc_url_name'] = sql_char($cc_url_name[$i]);
			$rec['clc_jump_url'] = sql_char($cc_jump_url[$i]);
			$rec['clc_start_date'] = sql_date($_POST['cc_start_date']);
			$rec['clc_end_date'] = sql_date($_POST['cc_end_date']);
			db_insert('t_click_counter', $rec);
			$cc_id = get_current_seq('t_click_counter', 'clc_cc_id');

			$rec['dmn_dealer_mail_id'] = sql_number($dealer_mail_id);
			$rec['dmn_cc_id'] = sql_number($cc_id);
			db_insert('t_dealer_mail_click', $rec);

			$body = str_replace("%CTID{$cc_no[$i]}%", $cc_id, $body);
		}
	}
}

// メイン処理
switch ($_POST['next_action']) {
case 'new':
	$dealer_mail_id = rec_insert();
	$msg = '販売店メールを登録しました。';
	$back = "location.href='contents.php?dealer_mail_id=$dealer_mail_id'";
	break;
case 'update':
	rec_update();
	$msg = '販売店メールを更新しました。';
	$back = "location.href='contents.php?dealer_mail_id={$_POST['dealer_mail_id']}'";
	break;
case 'delete':
	rec_delete();
	$msg = '販売店メールを削除しました。';
	$back = "location.href='list.php'";
	break;
case 'search':
	save_search();
	$msg = '検索条件を登録しました。';
	$back = "location.href='contents.php?dealer_mail_id={$_POST['dealer_mail_id']}'";
	break;
case 'test':
	test_mail();
	$msg = "{$_SESSION['s_mail_addr']} にテストメールを送信しました。";
	$back = "location.href='contents.php?dealer_mail_id={$_POST['dealer_mail_id']}'";
	break;
case 'ready':
	set_status(2);
	$msg = '作成を完了しました。';
	$back = "location.href='contents.php?dealer_mail_id={$_POST['dealer_mail_id']}'";
	break;
default:
	redirect('list.php');
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
</head>
<body onLoad="document.all.ok.focus()">

<? center_header('販売店メール配信｜更新処理') ?>

<div align="center">
<p class="msg"><?=$msg?></p>
<p><input type="button" id="ok" value="　戻る　" onclick="<?=$back?>"></p>
</div>

<? center_footer() ?>

</body>
</html>
