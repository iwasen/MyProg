<?php
/**
 * @author Ryuji
 * @version $Id: RegistMemberAction.class.php,v 1.8 2006/06/07 07:32:07 aizawa Exp $
 */
// サブアクション作成して、ActionForm使用するように変更。メインアクションはWaiwaiBaseAction使用に変更
require_once MO_WEBAPP_DIR.'/modules/Waiwai/lib/WaiwaiBaseAction.class.php';
require_once MO_WEBAPP_DIR.'/modules/Waiwai/lib/RegistNicknameSubAction.class.php';
class RegistMemberAction extends WaiwaiBaseAction
{
	public function execute()
	{
		$this->setBrowseCommon();

		$post = $this->getParameters();

		switch($this->getSubmitButton()) {

		// 確認ボタン
		case 'waiwai_confirm_btn':
			$this->setSubitem('confirm');
			return 'SubitemConfirm';
			break;

		// クリアボタン
		case 'waiwai_clear_btn':
			$this->setSubitem('clear');
			return 'SubitemInput';
			break;

		// 戻るボタン
		case 'waiwai_back_btn':
			$this->setSubitem('back');
			return 'SubitemInput';
			break;

		// 送信ボタン
		case 'waiwai_send_btn':
			$this->setSubitem('send');
			$subaction = new RegistNicknameSubAction();
			$subaction->initialize($this->getContext());
			return $subaction->execute();
			break;

		// ゲスト登録ボタン
		default:
			$this->setSubitem('default');

			$err = $this->checkNickname();
			if ($err) {
				return View::INPUT;
			} else {
				return 'SubitemInput';
			}
			break;
		}
	}

	// ■アクション実行メソッド指定
	public function getRequestMethods() {
		return Request::POST;
	}

	public function isSecure()
	{
		return TRUE;
	}

	// ■Validator登録
	public function registerValidators($validatorManager) {
		$btn = $this->getSubmitButton();
		$post = $this->getParameters();

		if ($btn == '' || $btn == 'default') {
			$validatorManager->registerName("waiwai_nickname", true, 'ニックネームを入力してください。');
		} elseif ($btn == 'waiwai_confirm_btn') {
			$item_ary = array();
			$required_item = $this->db->getRequiredItem($this->room_id);
			$item_ary = explode(',', $required_item);

			foreach($item_ary as $item) {
				switch($item) {
				case 1:
					$validatorManager->registerName("waiwai_name", true, 'お名前を入力してください。');
					break;
				case 2:
					$validatorManager->registerName("waiwai_name_kana", true, 'ふりがなを入力してください。');
					break;
				case 3:
					$validatorManager->registerName("waiwai_sex_cd", true, '性別を入力してください。');
					break;
				case 4:
					if (!$post['birth_y'] || !$post['birth_m'] || !$post['birth_d'])
						$validatorManager->registerName("waiwai_birth_input", true, '生年月日を入力してください。');
					break;
				case 5:
					if (in_array("", $post['zip']))
						$validatorManager->registerName("waiwai_zip_input", true, '郵便番号を入力してください。');
					break;
				case 6:
					if ($post['address1'] == 0)
						$validatorManager->registerName("waiwai_address1_input", true, '都道府県を入力してください。');
					break;
				case 7:
					$validatorManager->registerName("waiwai_address2", true, '都道府県以下を入力してください。');
					break;
				case 8:
					if (in_array("", $post['tel']))
						$validatorManager->registerName("waiwai_tel_input", true, '電話番号を入力してください。');
					break;
				case 9:
					$validatorManager->registerName("waiwai_self_introduction", true, '自己紹介を入力してください。');
					break;
				}
			}
		}
	}

	// ■入力データチェック
	public function validate() {
		$request = $this->getContext()->getRequest();
		$post = $this->getParameters();

		if ($post['zip'][0] || $post['zip'][1]) {
			if(!(strlen($post['zip'][0]) == 3 && strlen($post['zip'][1]) == 4)) {
				$request->setError("err", '郵便番号を正しく入力してください。');
				return false;
			} elseif (!(is_numeric($post['zip'][0]) && is_numeric($post['zip'][1]))){
				$request->setError("err", '郵便番号を正しく入力してください。');
				return false;
			}
		}
		if ($post['tel'][0] || $post['tel'][1] || $post['tel'][2]) {
			if(!($post['tel'][0] && $post['tel'][1] && $post['tel'][2])) {
				$request->setError("err", '電話番号を正しく入力してください。');
				return false;
			} elseif (!(is_numeric($post['tel'][0]) && is_numeric($post['tel'][1]) && is_numeric($post['tel'][2]))){
				$request->setError("err", '電話番号を正しく入力してください。');
				return false;
			}
		}
		if ($post['birth_y'] || $post['birth_m'] || $post['birth_d']) {
			if (!checkdate($post['birth_m'], $post['birth_d'], $post['birth_y'])){
				$request->setError("err", '生年月日を正しく入力してください。');
				return false;
			}
		}
		return true;
	}

	// ■入力エラー処理
	public function handleError() {
		$this->setBrowseCommon();
		$post = $this->getParameters();

		if (trim($post['nickname']) == '') {
			return View::INPUT;
		} else {
			$this->setSubitem('back');
			return 'SubitemInput';
		}
	}

	// ■パラメータ取得
	private function getParameters() {
		$request = $this->getContext()->getRequest();

		$params['nickname'] = $request->getParameter('waiwai_nickname');
		$params['name'] = $request->getParameter('waiwai_name');
		$params['name_kana'] = $request->getParameter('waiwai_name_kana');
		$params['sex_cd'] = $request->getParameter('waiwai_sex_cd');
		$params['birth_y'] = $request->getParameter('waiwai_birth_y');
		$params['birth_m'] = $request->getParameter('waiwai_birth_m');
		$params['birth_d'] = $request->getParameter('waiwai_birth_d');
		$params['zip'] = $request->getParameter('waiwai_zip');
		$params['address1'] = $request->getParameter('waiwai_address1');
		$params['address2'] = $request->getParameter('waiwai_address2');
		$params['tel'] = $request->getParameter('waiwai_tel');
		$params['self_introduction'] = $request->getParameter('waiwai_self_introduction');

		return $params;
	}

	// ■入力情報設定
	private function setSubitem($status){
		$request = $this->getContext()->getRequest();
		$post = $this->getParameters();

		switch($status) {
		case 'confirm':
			$this->decode_data(&$post);
			$this->setRequired($post);
			$request->setAttribute('waiwai_regist', $post);
			break;
		case 'back':
			$this->setMemberSelect($post);
			$this->setRequired($post);
			$request->setAttribute('waiwai_regist', $post);
			break;
		case 'send':
			$where = ' AND ren_target IN (1,3)';
			$post['member_enq'] = $this->db->isEnqQuestion($this->room_id, $this->member_id, $where);
			$request->setAttribute('waiwai_regist', $post);
			break;
		case 'clear':
			$data['nickname'] = $post['nickname'];
			$this->setRequired($data);
			$this->setMemberSelect($data);
			$request->setAttribute('waiwai_regist', $data);
			break;
		case 'default':
			$where = ' AND ren_target IN (1,3)';
			$post['member_enq'] = $this->db->isEnqQuestion($this->room_id, $this->member_id, $where);
			$this->setRequired($post);
			$this->getMemberInfo($post);
			$this->setMemberSelect($post);
			$request->setAttribute('waiwai_regist', $post);
			break;
		}

	}

	// ■セレクト設定
	private function setMemberSelect(&$post) {
		$request = $this->getContext()->getRequest();

		// 性別
		if ($post['sex_cd'] == 1)
			$post['sex_1'] = 'checked';
		elseif ($post['sex_cd'] == 2)
			$post['sex_2'] = 'checked';

		// 生年月日（年）array
		$heisei = date('Y') - 1988;

		$eto = array(亥, 戌, 酉, 申, 未, 午, 巳, 辰, 卯, 寅, 丑, 子);
		$era = array('平成', '昭和', '大正', '明治');
		$num = array($heisei, 64, 15, 45);
		$birth_y[] = '年';

		$et = 1;
		$er = 0;
		$n = $heisei;
		for ($i = date('Y'); $i >= 1900; $i--) {

			if ($et == 12)
				$et = 0;

			if ($n == 1) {
				$er++;
				$n = $num[$er];
			}

			$birth_y[$i] = $i.'（' . $era[$er] . $n . '）' . $eto[$et];
			$et++; $n--;
		}
		$request->setAttribute('waiwai_birth_y', $birth_y);


		// 生年月日（月）
		$birth_m[] = '月';
		for ($i = 1; $i <= 12; $i++)
			$birth_m[$i] = $i . '月';
		$request->setAttribute('waiwai_birth_m', $birth_m);

		// 生年月日（日）
		$birth_d[] = '日';
		for ($i = 1; $i <= 31; $i++)
			$birth_d[$i] = $i . '日';
		$request->setAttribute('waiwai_birth_d', $birth_d);


		// 都道府県
		$address1_ary = $this->db->getTodofuken();

		$address1[] = '選択してください';
		foreach($address1_ary as $data)
			$address1[$data['area_id']] = $data['name'];
		$request->setAttribute('waiwai_address1', $address1);
	}

	// ■デコード設定
	private function decode_data(&$post) {
		$post['view_birth'] = $post['birth_y'] ? $post['birth_y'].'年'.$post['birth_m'].'月'.$post['birth_d'].'日' : '';
		$post['view_sex_cd'] = $this->decode_sex_cd($post['sex_cd']);
		$post['view_address1'] = $this->decode_address1($post['address1']);
		$post['view_zip'] = $this->decode_etc($post['zip']);
		$post['view_tel'] = $this->decode_etc($post['tel']);
	}

	// ■性別デコード
	private function decode_sex_cd($code) {
		switch ($code) {
		case 1:
			return '男';
		case 2:
			return '女';
		default:
			return '';
		}
	}

	// ■都道府県デコード
	private function decode_address1($code) {
		return $this->db->getArea1Name($code);
	}

	// ■その他デコード
	private function decode_etc($code_ary) {
		if ($code_ary[0])
			return implode('-', $code_ary);
		else
			return '';
	}

	// ■必須属性設定
	private function getItemName() {
		$item[1] = 'name';
		$item[2] = 'name_kana';
		$item[3] = 'sex_cd';
		$item[4] = 'birth_y';
		$item[5] = 'zip';
		$item[6] = 'address1';
		$item[7] = 'address2';
		$item[8] = 'tel';
		$item[9] = 'self_introduction';

		return $item;
	}

	// ■ニックネームチェック
	private function checkNickname() {
		$request = $this->getContext()->getRequest();

		$post = $this->getParameters();
		$nickname = $post['nickname'];

		if (trim($nickname) == '') {
			$request->setAttribute('waiwai_error', 'ニックネームが入力されていません');
			return true;
		}
		if ($this->db->checkNickname($this->room_id, $nickname, $this->member_id)) {
			$request->setAttribute('waiwai_error', 'すでに使われているニックネームです。');
			return true;
		}

		return false;
	}

	// ■既存メンバー情報取得
	private function getMemberInfo(&$post) {
		$data = $this->db->getNewMemberAttribute($this->member_id, $this->room_id);

		$post['name'] = $data['name'];
		$post['name_kana'] = $data['name_kana'];
		$post['sex_cd'] = $data['sex_cd'];
		$post['birth_y'] = $data['birth_y'];
		$post['birth_m'] = $data['birth_m'];
		$post['birth_d'] = $data['birth_d'];
		$post['zip'][] = substr(trim($data['zip_cd']), 0, 3);
		$post['zip'][] = substr(trim($data['zip_cd']), 3, 4);
		$post['address1'] = $data['address1'];
		$post['address2'] = $data['address2'];
		$post['tel'] = explode('-', $data['tel']);
		$post['self_introduction'] = $data['self_introduction'];
	}

	// ■必須項目設定
	private function setRequired(&$post) {
		$tag = '<font color="red">(必須)</font>';

		$item_ary = array();
		$required_item = $this->db->getRequiredItem($this->room_id);
		$item_ary = explode(',', $required_item);

		foreach($item_ary as $id)
			$post['required'][$id] = $tag;
	}
}
?>