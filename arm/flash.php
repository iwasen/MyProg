<?
/******************************************************
* system  : xxx リスク診断
* title   : Flash通信処理
* content : Flashからのリクエストに対してレスポンスを返す
* version : 1.0
* author  : aizawa
*******************************************************/

// 処理開始時間取得（テスト用）
$start_time = array_sum(explode(' ', microtime()));

// エラーハンドラ設定
set_error_handler('my_error_handler');
error_reporting(0);

// 出力文字コードを変換しない
ini_set('mbstring.http_output', 'pass');

// 共通インクルード
include('inc/database.php');
include('inc/xml_support.php');
include('inc/info_basic.php');
include('inc/calc_common.php');

// XMLタグ定義
define('TAG_ROOT', 'ARaiM');
define('TAG_REQUEST', 'Request');
define('TAG_RESPONSE', 'Response');
define('TAG_SESSION_ID', 'SessionID');
define('TAG_RESULT', 'Result');

// POSTで受け取ったデータをDOMに変換
$dom_req = domxml_open_mem($HTTP_RAW_POST_DATA);

// ＤＢトランザクション開始
db_begin_trans();

// リクエストを処理
if (is_object($dom_req)) {
	$root = $dom_req->document_element();
	if ($root->node_name() == TAG_ROOT) {
		$g_format = $root->get_attribute('format');

		for ($node = $root->first_child(); is_object($node); $node = $node->next_sibling()) {
			if ($node->node_name() == TAG_REQUEST) {
				$g_request_code = $node->get_attribute('code');

				switch ($g_request_code) {
				case '1':		// ユーザ基本情報取得要求
					request_user_data($node);
					break;
				case '2':		// ユーザ基本情報保存要求
					request_save_data($node);
					break;
				case '3':		// 診断結果要求
					include('inc/const.php');
					include('inc/excel.php');
					include('inc/comment.php');
					include('inc/info_company.php');
					include('inc/info_hoken.php');
					include('inc/info_osusume.php');
					include('inc/calc_basic.php');
					include('inc/calc_shibo.php');
					include('inc/calc_iryo.php');
					include('inc/calc_shugyofuno.php');
					include('inc/calc_rogo.php');
					include('inc/calc_songai.php');
					include('inc/calc_sogo.php');
					include('inc/calc_company.php');
					include('inc/calc_seiho.php');
					include('inc/calc_sonpo.php');
					include('inc/get_risk_data.php');
					include('inc/get_risk_comm.php');
					request_risk_data($node);
					break;
				case '4':		// 保険評価要求
					include('inc/const.php');
					include('inc/excel.php');
					include('inc/comment.php');
					include('inc/info_company.php');
					include('inc/info_hoken.php');
					include('inc/info_osusume.php');
					include('inc/calc_basic.php');
					include('inc/calc_company.php');
					include('inc/calc_seiho.php');
					include('inc/calc_sonpo.php');
					include('inc/calc_shibo_hoken.php');
					include('inc/calc_iryo_hoken.php');
					include('inc/calc_shoken_shindan.php');
					include('inc/get_hyoka_data.php');
					include('inc/get_hyoka_comm.php');
					request_hyoka_data($node);
					break;
				case '5':		// おすすめ要求
					include('inc/const.php');
					include('inc/excel.php');
					include('inc/comment.php');
					include('inc/info_company.php');
					include('inc/info_hoken.php');
					include('inc/info_osusume.php');
					include('inc/calc_basic.php');
					include('inc/calc_company.php');
					include('inc/calc_seiho.php');
					include('inc/calc_sonpo.php');
					include('inc/calc_shibo_hoken.php');
					include('inc/calc_iryo_hoken.php');
					include('inc/calc_shoken_shindan.php');
					include('inc/get_hyoka_data.php');
					include('inc/get_hyoka_comm.php');
					request_osusume_data($node);
					break;
				}
			}
		}
	}
}

// ＤＢトランザクションコミット
db_commit_trans();

// 処理終了時間取得（テスト用）
$end_time = array_sum(explode(' ', microtime()));

// レスポンス送信
if (is_object($dom_res)) {
	// 処理時間セット（テスト用）
	$_root = $dom_res->document_element();
	$_res = $_root->get_elements_by_tagname(TAG_RESPONSE);
	$_res[0]->set_attribute('time', round($end_time - $start_time, 4));

	// レスポンスXML送信
	echo $dom_res->dump_mem($g_format == '1', 'UTF-8');
}

exit;

// ■ユーザ基本情報取得要求(Request=1)
function request_user_data($node) {
	$result = 0;
	$add_data = null;

	// ユーザID取得
	$user_id = get_user_id($node);
	if ($user_id) {
		// 基本情報クラス生成
		$basic_info = new basic_info_class($user_id);

		// DBから基本情報読み込み
		if ($basic_info->read_from_db())
			$add_data['基本情報'] = $basic_info->m_basic_info;
		else {
			// デフォルトの基本情報読み込み
			$dom = domxml_open_file('default.xml');
			dom_to_array($dom, $add_data);
			$result = 2;
		}
	} else
		$result = 1;

	// レスポンスセット
	set_response('1', $result, $add_data);
}

// ■ユーザ基本情報保存要求(Request=2)
function request_save_data($node) {
	$result = 0;

	// ユーザID取得
	$user_id = get_user_id($node);
	if ($user_id) {
		// 受信データから基本情報のノードを取り出し
		$nodes = $node->get_elements_by_tagname(toUTF('基本情報'));
		if (is_array($nodes) && count($nodes) > 0) {
			// 基本情報クラス生成
			$basic_info = new basic_info_class($user_id);

			// 基本情報をDBに保存
			$basic_info->read_from_dom($nodes[0]);
			$basic_info->write_to_db();

			// スタータス処理過程ステータス追加
			$basic_info->add_trans_process('F101');
		}
	} else
		$result = 1;

	// レスポンスセット
	set_response('2', $result);
}

// ■診断結果要求(Request=3)
function request_risk_data($node) {
	$result = 0;
	$add_data = null;

	// ユーザID取得
	$user_id = get_user_id($node);
	if ($user_id) {
		// 基本情報クラス生成
		$basic_info = new basic_info_class($user_id);

		// DBから基本情報と会社情報読み込み
		if ($basic_info->read_from_db()) {
			// 会社情報情報クラス生成
			$company_info = new company_info_class($user_id, $basic_info->m_company_id);
			$company_info->read_from_db();

			// 保険情報クラス生成
			$hoken_info = new hoken_info_class($user_id);
			$hoken_info->read_from_db();

			// おすすめ保険情報クラス生成
			$osusume_info = new osusume_info_class($user_id);
			$osusume_info->read_from_db(true);

			// 診断結果取得
			$add_data['診断結果'] = get_risk_data($basic_info, $company_info, $hoken_info, $osusume_info);

			// 診断コメント取得
			$add_data['コメント'] = get_risk_comm();

			// スタータス処理過程ステータス追加
			$basic_info->add_trans_process('F102');
		} else
			$result = 2;
	} else
		$result = 1;

	// レスポンスセット
	set_response('3', $result, $add_data);
}

// ■保険評価要求(Request=4)
function request_hyoka_data($node) {
	$result = 0;
	$add_data = null;

	// ユーザID取得
	$user_id = get_user_id($node);
	if ($user_id) {
		// 基本情報クラス生成
		$basic_info = new basic_info_class($user_id);

		// DBから基本情報読み込み
		if ($basic_info->read_from_db()) {
			// 会社情報情報クラス生成
			$company_info = new company_info_class($user_id, $basic_info->m_company_id);
			$company_info->read_from_db();

			// 保険情報クラス生成
			$hoken_info = new hoken_info_class($user_id);
			$hoken_info->read_from_db();

			// おすすめ保険情報クラス生成
			$osusume_info = new osusume_info_class($user_id);
			$osusume_info->read_from_db();

			// 保険評価取得
			$add_data['保険評価'] = get_hyoka_data($basic_info, $company_info, $hoken_info, $osusume_info, 'hoken');

			// 評価コメント取得
			$add_data['コメント'] = get_hyoka_comm();
		} else
			$result = 2;
	} else
		$result = 1;

	// レスポンスセット
	set_response('4', $result, $add_data);
}

// ■おすすめ結果要求(Request=5)
function request_osusume_data($node) {
	$result = 0;
	$add_data = null;

	// ユーザID取得
	$user_id = get_user_id($node);
	if ($user_id) {
		// 基本情報クラス生成
		$basic_info = new basic_info_class($user_id);

		// DBから基本情報読み込み
		if ($basic_info->read_from_db()) {
			// 会社情報情報クラス生成
			$company_info = new company_info_class($user_id, $basic_info->m_company_id);
			$company_info->read_from_db();

			// 保険情報クラス生成
			$hoken_info = new hoken_info_class($user_id);
			$hoken_info->read_from_db();

			// おすすめ保険情報クラス生成
			$osusume_info = new osusume_info_class($user_id);
			$osusume_info->read_from_db();

			// 保険評価取得
			$add_data['保険評価'] = get_hyoka_data($basic_info, $company_info, $hoken_info, $osusume_info, 'osusume');

			// 評価コメント取得
			$add_data['コメント'] = get_hyoka_comm();
		} else
			$result = 2;
	} else
		$result = 1;

	// レスポンスセット
	set_response('5', $result, $add_data);
}

// ■ユーザIDを取得
function get_user_id($node) {
	$user_id = 0;

	$nodes = $node->get_elements_by_tagname(TAG_SESSION_ID);
	if (is_array($nodes) && count($nodes) > 0) {
		// セッションID取得
		$session_id = $nodes[0]->get_content();

		// セッションからユーザIDを取得
		if ($session_id != '') {
			if ($session_id{0} == '#')
				$user_id = substr($session_id, 1);
			else {
				session_id($session_id);
				session_start();
				if (isset($_SESSION['USER_ID']))
					$user_id = $_SESSION['USER_ID'];
			}
		}
	}

	return $user_id;
}

// ■レスポンスをセット
function set_response($response_code, $result, $add_data = null) {
	global $dom_res;

	if (is_object($dom_res)) {
		$root = $dom_res->document_element();
	} else {
		// DOMを生成
		$dom_res = domxml_new_doc('1.0');

		// ルートノードを付加
		$root = $dom_res->create_element(TAG_ROOT);
		$dom_res->append_child($root);
	}

	// Responseノードを付加
	$res = $dom_res->create_element(TAG_RESPONSE);
	$res->set_attribute('code', $response_code);
	$root->append_child($res);

	// Resultノードを付加
	$rid = $dom_res->create_element(TAG_RESULT);
	if (is_object($result)) {
		$rid->set_attribute('code', $result->code);
		$rid->set_content($result->msg);
	} else
		$rid->set_attribute('code', $result);
	$res->append_child($rid);

	// 追加ノードを付加
	if ($add_data != null)
		array_to_dom($dom_res, $res, $add_data);
}

// ■エラーハンドラ
function my_error_handler($errno, $errstr, $errfile, $errline) {
	if ($errno & (E_ERROR | E_WARNING | E_PARSE)) {
		if ($errstr != 'Division by zero') {
			global $dom_res;
			global $g_request_code;
			$errortype = array (
					1   => 'Error',
					2   => 'Warning',
					4   => 'Parsing Error',
					8   => 'Notice',
					16  => 'Core Error',
					32  => 'Core Warning',
					64  => 'Compile Error',
					128 => 'Compile Warning',
					256 => 'User Error',
					512 => 'User Warning',
					1024=> 'User Notice'
			);

			db_rollback();

			$result->code = 9;
			$result->msg = "{$errortype[$errno]}: $errstr in $errfile on line $errline";

			set_response($g_request_code, $result);
			echo $dom_res->dump_mem(true, 'UTF-8');
			exit;
		}
	}
}
?>