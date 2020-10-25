<?
/******************************************************
* system  : xxx �ꥹ������
* title   : �ǡ�������׽���
* content : Flash����Υꥯ�����Ȥ��Ф��������ѿ������פ���
* version : 1.0
* author  : aizawa
*******************************************************/

// ���顼�ϥ�ɥ�����
//set_error_handler('my_error_handler');
error_reporting(0);

// ����ʸ�������ɤ��Ѵ����ʤ�
ini_set('mbstring.http_output', 'pass');

// ���̥��󥯥롼��
include('inc/database.php');
include('inc/xml_support.php');
include('inc/info_basic.php');
include('inc/calc_common.php');

// XML�������
define('TAG_ROOT', 'ARaiM');
define('TAG_REQUEST', 'Request');
define('TAG_RESPONSE', 'Response');
define('TAG_SESSION_ID', 'SessionID');
define('TAG_RESULT', 'Result');

// POST�Ǽ�����ä��ǡ�����DOM���Ѵ�
$dom_req = domxml_open_mem($HTTP_RAW_POST_DATA);

// �ģ¥ȥ�󥶥�����󳫻�
db_begin_trans();

// �ꥯ�����Ȥ����
if (is_object($dom_req)) {
	$root = $dom_req->document_element();
	if ($root->node_name() == TAG_ROOT) {
		$g_format = $root->get_attribute('format');

		for ($node = $root->first_child(); is_object($node); $node = $node->next_sibling()) {
			if ($node->node_name() == TAG_REQUEST) {
				$g_request_code = $node->get_attribute('code');

				switch ($g_request_code) {
				case '1':		// �桼�����ܾ�������׵�
					request_user_data($node);
					break;
				case '2':		// �桼�����ܾ�����¸�׵�
					request_save_data($node);
					break;
				case '3':		// ���Ƿ���׵�
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
				case '4':		// �ݸ�ɾ���׵�
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
				case '5':		// ���������׵�
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

// �ģ¥ȥ�󥶥���������Хå�
db_rollback();

header('Pragma: no-cache');
header('Content-Type: application/download');
header('Content-Disposition: attachment; filename="flash.dat"');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');

echo "REQUEST=$g_request_code\r\n";
echo "USER_ID=$g_user_id\r\n\r\n";
echo chunk_split(base64_encode(serialize($g_sheets)));

exit;

// ���桼�����ܾ�������׵�(Request=1)
function request_user_data($node) {
	$result = 0;
	$add_data = null;

	// �桼��ID����
	$user_id = get_user_id($node);
	if ($user_id) {
		// ���ܾ��󥯥饹����
		$basic_info = new basic_info_class($user_id);

		// DB������ܾ����ɤ߹���
		if ($basic_info->read_from_db())
			$add_data['���ܾ���'] = $basic_info->m_basic_info;
		else {
			// �ǥե���Ȥδ��ܾ����ɤ߹���
			$dom = domxml_open_file('default.xml');
			dom_to_array($dom, $add_data);
			$result = 2;
		}
	} else
		$result = 1;

	// �쥹�ݥ󥹥��å�
	set_response('1', $result, $add_data);
}

// ���桼�����ܾ�����¸�׵�(Request=2)
function request_save_data($node) {
	$result = 0;

	// �桼��ID����
	$user_id = get_user_id($node);
	if ($user_id) {
		// �����ǡ���������ܾ���ΥΡ��ɤ���Ф�
		$nodes = $node->get_elements_by_tagname(toUTF('���ܾ���'));
		if (is_array($nodes) && count($nodes) > 0) {
			// ���ܾ��󥯥饹����
			$basic_info = new basic_info_class($user_id);

			// ���ܾ����DB����¸
			$basic_info->read_from_dom($nodes[0]);
			$basic_info->write_to_db();

			// ���������������������ơ������ɲ�
			$basic_info->add_trans_process('F101');
		}
	} else
		$result = 1;

	// �쥹�ݥ󥹥��å�
	set_response('2', $result);
}

// �����Ƿ���׵�(Request=3)
function request_risk_data($node) {
	$result = 0;
	$add_data = null;

	// �桼��ID����
	$user_id = get_user_id($node);
	if ($user_id) {
		// ���ܾ��󥯥饹����
		$basic_info = new basic_info_class($user_id);

		// DB������ܾ���Ȳ�Ҿ����ɤ߹���
		if ($basic_info->read_from_db()) {
			// ��Ҿ�����󥯥饹����
			$company_info = new company_info_class($user_id, $basic_info->m_company_id);
			$company_info->read_from_db();

			// �ݸ����󥯥饹����
			$hoken_info = new hoken_info_class($user_id);
			$hoken_info->read_from_db();

			// ���������ݸ����󥯥饹����
			$osusume_info = new osusume_info_class($user_id);
			$osusume_info->read_from_db(true);

			// ���Ƿ�̼���
			$add_data['���Ƿ��'] = get_risk_data($basic_info, $company_info, $hoken_info, $osusume_info);

			// ���ǥ����ȼ���
			$add_data['������'] = get_risk_comm();

			// ���������������������ơ������ɲ�
			$basic_info->add_trans_process('F102');
		} else
			$result = 2;
	} else
		$result = 1;

	// �쥹�ݥ󥹥��å�
	set_response('3', $result, $add_data);
}

// ���ݸ�ɾ���׵�(Request=4)
function request_hyoka_data($node) {
	$result = 0;
	$add_data = null;

	// �桼��ID����
	$user_id = get_user_id($node);
	if ($user_id) {
		// ���ܾ��󥯥饹����
		$basic_info = new basic_info_class($user_id);

		// DB������ܾ����ɤ߹���
		if ($basic_info->read_from_db()) {
			// ��Ҿ�����󥯥饹����
			$company_info = new company_info_class($user_id, $basic_info->m_company_id);
			$company_info->read_from_db();

			// �ݸ����󥯥饹����
			$hoken_info = new hoken_info_class($user_id);
			$hoken_info->read_from_db();

			// ���������ݸ����󥯥饹����
			$osusume_info = new osusume_info_class($user_id);
			$osusume_info->read_from_db();

			// �ݸ�ɾ������
			$add_data['�ݸ�ɾ��'] = get_hyoka_data($basic_info, $company_info, $hoken_info, $osusume_info, 'hoken');

			// ɾ�������ȼ���
			$add_data['������'] = get_hyoka_comm();
		} else
			$result = 2;
	} else
		$result = 1;

	// �쥹�ݥ󥹥��å�
	set_response('4', $result, $add_data);
}

// �������������׵�(Request=5)
function request_osusume_data($node) {
	$result = 0;
	$add_data = null;

	// �桼��ID����
	$user_id = get_user_id($node);
	if ($user_id) {
		// ���ܾ��󥯥饹����
		$basic_info = new basic_info_class($user_id);

		// DB������ܾ����ɤ߹���
		if ($basic_info->read_from_db()) {
			// ��Ҿ�����󥯥饹����
			$company_info = new company_info_class($user_id, $basic_info->m_company_id);
			$company_info->read_from_db();

			// �ݸ����󥯥饹����
			$hoken_info = new hoken_info_class($user_id);
			$hoken_info->read_from_db();

			// ���������ݸ����󥯥饹����
			$osusume_info = new osusume_info_class($user_id);
			$osusume_info->read_from_db();

			// �ݸ�ɾ������
			$add_data['�ݸ�ɾ��'] = get_hyoka_data($basic_info, $company_info, $hoken_info, $osusume_info, 'osusume');

			// ɾ�������ȼ���
			$add_data['������'] = get_hyoka_comm();
		} else
			$result = 2;
	} else
		$result = 1;

	// �쥹�ݥ󥹥��å�
	set_response('5', $result, $add_data);
}

// ���桼��ID�����
function get_user_id($node) {
	global $g_user_id;

	$user_id = 0;

	$nodes = $node->get_elements_by_tagname(TAG_SESSION_ID);
	if (is_array($nodes) && count($nodes) > 0) {
		// ���å����ID����
		$session_id = $nodes[0]->get_content();

		// ���å���󤫤�桼��ID�����
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

	$g_user_id = $user_id;

	return $user_id;
}

// ���쥹�ݥ󥹤򥻥å�
function set_response($response_code, $result, $add_data = null) {
	global $dom_res;

	if (is_object($dom_res)) {
		$root = $dom_res->document_element();
	} else {
		// DOM������
		$dom_res = domxml_new_doc('1.0');

		// �롼�ȥΡ��ɤ��ղ�
		$root = $dom_res->create_element(TAG_ROOT);
		$dom_res->append_child($root);
	}

	// Response�Ρ��ɤ��ղ�
	$res = $dom_res->create_element(TAG_RESPONSE);
	$res->set_attribute('code', $response_code);
	$root->append_child($res);

	// Result�Ρ��ɤ��ղ�
	$rid = $dom_res->create_element(TAG_RESULT);
	if (is_object($result)) {
		$rid->set_attribute('code', $result->code);
		$rid->set_content($result->msg);
	} else
		$rid->set_attribute('code', $result);
	$res->append_child($rid);

	// �ɲåΡ��ɤ��ղ�
	if ($add_data != null)
		array_to_dom($dom_res, $res, $add_data);
}

// �����顼�ϥ�ɥ�
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