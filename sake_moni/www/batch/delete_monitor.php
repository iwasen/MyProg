<?
/******************************************************
' System :お酒と買物のアンケートモニターバッチ処理
' Content:ユーザ論理削除処理
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/check.php");

define('FILE_NAME', __FILE__);

//ファイルの列番号
define('COL_NET_ID', 0);		// キーＩＤ

//マスク文字列
define('MASK_NAME1', 		'＊＊＊');
define('MASK_NAME2', 		'＊＊＊');
define('MASK_NAME1_KANA', 	'＊＊＊');
define('MASK_NAME2_KANA', 	'＊＊＊');
define('MASK_MAIL_ADDR', 	'dummy_addr');
define('MASK_MAIL_ADDR_LOW','dummy_addr');



// 最大実行時間（３分）
set_time_limit(180);

message("■お酒と買物アンケートモニターシステム　ユーザ削除プログラム");



//***** インポートファイル名の取得
if ($_SERVER['argc'] != 0) {
	// コマンドラインから起動
	for ($i = 1; $i < $_SERVER['argc']; $i++) {
		switch ($_SERVER['argv'][$i]) {
		case '-f':
			$force = true;
			break;
		case '-c':
			$check = true;
			break;
		default:
			$import_file = $_SERVER['argv'][$i];
			break;
		}
	}
} else {
	// Apacheから起動
	$import_file = $_FILES['delete_import_file']['tmp_name'];
	$force = $_POST['force'];
	$check = $_POST['check'];
}
if ($import_file == '') {
	message('削除用インポートファイルの指定がありません。');
	exit;
}

if (($fp = fopen($import_file, 'r')) == false) {
	message('ファイルのオープンに失敗しました。');
	exit;
}



//***** ファイルの読み込み
$line_no = 0;
$ok_count = 0;
$ng_count = 0;
$data_ary = array();

while ($data = fgetcsv($fp, 10000, ",")) {

	//文字コードをEUC-JPにする
	mb_convert_variables('EUC-JP', 'SJIS', $data);
	$line_no++;

	if ($data[0] != '') {
		$err = null;

		//エラーチェック
		//if (!check_num($data[COL_NET_ID]))
			//$err[] = 'キーＩＤが数字ではありません。';

		//エラー表示
		if ($err) {
			foreach ($err as $e)
				message("{$line_no}行目（キーＩＤ={$data[0]}）の{$e}");

			$err_flag = true;
			$ng_count++;
		} else
			$ok_count++;

		$data['err'] = $err;
		$data_ary[] = $data;
	}
}



//***** ＤＢ登録
$import_cont = 0;

if ($force || (!$check && !$err_flag)) {
	db_begin_trans();

	foreach ($data_ary as $data) {
		if ($data['err'] == null) {
			// update句の作成
			$rec['mn_status']		= sql_number(9);
//			$rec['mn_name1']		= sql_char(MASK_NAME1);
//			$rec['mn_name2']		= sql_char(MASK_NAME1);
//			$rec['mn_name1_kana']	= sql_char(MASK_NAME1_KANA);;
//			$rec['mn_name2_kana']	= sql_char(MASK_NAME2_KANA);
//			$rec['mn_mail_addr']	= sql_char(MASK_MAIL_ADDR);
//			$rec['mn_mail_addr_low']= sql_char(MASK_MAIL_ADDR_LOW);
			
			//where句の作成
			$where = 'mn_net_id = ' . sql_char($data[COL_NET_ID]);

			//データの論理削除
			db_update('t_monitor', $rec, $where);

			if (db_errormessage() != '')
				break;

			$import_cont++;
		}
	}

	if (db_errormessage() == '')
		db_commit_trans();
	else {
		db_rollback();
		$import_cont = 0;
	}
}



//***** 件数表示
message('');
message("　正常データ：{$ok_count}件");
message("エラーデータ：{$ng_count}件");

message('');
if ($import_cont != 0)
	message("{$import_cont}件削除しました。");
else
	message("削除は行いませんでした。");

exit;

//-----------------------------
// メッセージ出力
//-----------------------------
function message($text) {
	echo $text, "\n";
	if ($_SERVER['argc'] == 0)
		echo '<br>';
}
?>