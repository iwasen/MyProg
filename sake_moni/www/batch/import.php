<?
/******************************************************
' System :お酒と買物のアンケートモニターバッチ処理
' Content:ユーザインポート処理
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/check.php");

define('FILE_NAME', __FILE__);

define('COL_KEY_ID', 0);		// キーＩＤ
define('COL_NAME1', 1);			// 姓
define('COL_NAME2', 2);			// 名
define('COL_SEX', 3);				// 性別
define('COL_MIKIKON', 4);		// 未既婚
define('COL_BIRTHDAY', 5);	// 生年月日
define('COL_CHILD', 6);			// 子供の有無
define('COL_SHOKUGYOU', 7);	// 職業
define('COL_MAIL_ADDR', 8);	// メールアドレス
define('COL_PASSWORD', 9);	// パスワード
define('COL_NET_ID', 10);		// きかせてID／imiID
define('COL_RESEARCH', 11);	// 関連調査への参加可能Flg
define('COL_NET_FLAG', 12);	// きかせて/imi識別フラグ
define('COL_CHAIN', 13);		// チェーン
define('COL_ZIP', 14);			// 郵便番号
define('COL_ADDRESS1', 15);	// 居住地域
define('COL_ADDRESS2', 16);	// 住所２（市区町村、番地、ビル名）

// 最大実行時間（３分）
set_time_limit(180);

message("■お酒と買物アンケートモニターシステム　ユーザインポートプログラム");

// インポートファイル名の取得
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
	$import_file = $_FILES['import_file']['tmp_name'];
	$force = $_POST['force'];
	$check = $_POST['check'];
}
if ($import_file == '') {
	message('インポートファイルの指定がありません。');
	exit;
}

if (($fp = fopen($import_file, 'r')) == false) {
	message('ファイルのオープンに失敗しました。');
	exit;
}

// 登録済みのメールアドレスを取得
// $sql = "SELECT mn_mail_addr_low FROM t_monitor WHERE mn_status<>9";
$sql = "SELECT mn_mail_addr_low, count(*) as C FROM t_chain LEFT JOIN t_monitor ON mn_monitor_id = dc_monitor_id WHERE mn_status<>9 GROUP BY mn_mail_addr_low";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
//	$mail_addr_ary[$fetch->mn_mail_addr_low] = true;
	$mail_addr_ary[$fetch->mn_mail_addr_low] = $fetch->c ;
}

$line_no = 0;
$ok_count = 0;
$ng_count = 0;
$data_ary = array();

$data = fgetcsv($fp, 10000, ",");
while ($data = fgetcsv($fp, 10000, ",")) {
	mb_convert_variables('EUC-JP', 'SJIS', $data);
	$line_no++;

//	if ($data[0] != '') {
	if ($data[1] != '') {
		$err = null;

//		if (!check_num($data[COL_KEY_ID]))
//			$err[] = 'キーＩＤが数字ではありません。';

		if ($data[COL_NAME1] == '')
			$err[] = '姓がありません。';

		if (!check_num($data[COL_SEX], 1, 2))
			$err[] = '性別が正しくありません。';

		if (!check_num($data[COL_MIKIKON], 1, 2))
			$err[] = '未既婚が正しくありません。';

		if (!check_date($data[COL_BIRTHDAY]))
			$err[] = '生年月日が正しくありません。';

		if (!check_num($data[COL_SHOKUGYOU]))
			$err[] = '職業が正しくありません。';

		if (!check_mail_addr($data[COL_MAIL_ADDR]))
			$err[] = 'メールアドレスが正しくありません。';
		else {
			$mail_addr = strtolower($data[COL_MAIL_ADDR]);
//			if ($mail_addr_ary[$mail_addr])
			if ($mail_addr_ary[$mail_addr] == 2)
//				$err[] = "メールアドレスは重複しています。({$mail_addr})";
				$err[] = "メールアドレスは既に2チェーンに登録されています。({$mail_addr})";
			else
//				$mail_addr_ary[$mail_addr] = true;
				++$mail_addr_ary[$mail_addr];
		}

		if ($data[COL_PASSWORD] == '')
			$err[] = 'パスワードがありません。';

		if ($data[COL_NET_ID] == '')
			$err[] = 'きかせてID／imiIDがありません。';

		if (!check_num($data[COL_RESEARCH], 1, 2))
			$err[] = '関連調査への参加可能Flgが正しくありません。';

		if (!check_num($data[COL_NET_FLAG], 1, 2))
			$err[] = 'きかせて/imi識別フラグが正しくありません。';

		if ($data[COL_CHAIN] == '')
			$err[] = 'チェーンが正しくありません。';

		if(!is_numeric($data[COL_ADDRESS1]))
			$data[COL_ADDRESS1] = get_area_cd($data[COL_ADDRESS1]);
		if ($data[COL_ADDRESS1] == '')
			$err[] = '居住地域が正しくありません。';

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

$import_cont = 0;

if ($force || (!$check && !$err_flag)) {
	db_begin_trans();

	foreach ($data_ary as $data) {
		if ($data['err'] == null) {
			// モニター情報登録
			$rec['mn_net_kind'] = sql_number($data[COL_NET_FLAG]);
			$rec['mn_net_id'] = sql_char($data[COL_NET_ID]);
			$rec['mn_name1'] = sql_char($data[COL_NAME1]);
			$rec['mn_name2'] = sql_char($data[COL_NAME2]);
			$rec['mn_birthday'] = sql_date($data[COL_BIRTHDAY]);
			$rec['mn_sex'] = sql_number($data[COL_SEX]);
			$rec['mn_mikikon'] = sql_number($data[COL_MIKIKON]);
			$rec['mn_mail_addr'] = sql_char($data[COL_MAIL_ADDR]);
			$rec['mn_mail_addr_low'] = sql_char(strtolower($data[COL_MAIL_ADDR]));
			$rec['mn_password'] = sql_char($data[COL_PASSWORD]);
			$rec['mn_jitaku_zip'] = sql_char($data[COL_ZIP]);
			$rec['mn_jitaku_area'] = sql_number($data[COL_ADDRESS1]);
			$rec['mn_jitaku_addr2'] = sql_char($data[COL_ADDRESS2]);
			$rec['mn_shokugyou_cd'] = sql_number($data[COL_SHOKUGYOU]);
			$rec['mn_research'] = sql_number($data[COL_RESEARCH]);
			$rec['mn_child'] = sql_number($data[COL_CHILD]);
			db_insert('t_monitor', $rec);
			$monitor_id = get_currval('t_monitor_mn_monitor_id_seq');

			// チェーン情報登録
			$chain_ary = explode(',', $data[COL_CHAIN]);
			if (is_array($chain_ary)) {
				foreach ($chain_ary as $chain) {
					if ($chain != '') {
						$sql = "SELECT count(*) FROM t_chain WHERE dc_monitor_id=" . sql_number($monitor_id) . " AND dc_chain_cd=" . sql_number($chain);
						if (db_fetch1($sql) == 0) {
							$rec['dc_monitor_id'] = sql_number($monitor_id);
							$rec['dc_chain_cd'] = sql_number($chain);
							db_insert('t_chain', $rec);
						}
					}
				}
			}

			// ポイント情報レコード作成
			$rec['mp_monitor_id'] = sql_number($monitor_id);
			db_insert('t_monitor_point', $rec);

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

// 件数表示
message('');
message("　正常データ：{$ok_count}件");
message("エラーデータ：{$ng_count}件");

message('');
if ($import_cont != 0)
	message("{$import_cont}件インポートしました。");
else
	message("インポートは行いませんでした。");

exit;

// 都道府県コード取得
function get_area_cd($area) {
	$sql = "SELECT ar_area_cd FROM m_area WHERE ar_area_name=" . sql_char($area);
	return db_fetch1($sql);
}

// メッセージ出力
function message($text) {
	echo $text, "\n";
	if ($_SERVER['argc'] == 0)
		echo '<br>';
}
?>
