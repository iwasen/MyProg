<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ワイワイ会議室 スレッド移動アクション
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseAction.class.php');

class MoveThreadAction extends WaiwaiBaseAction {
	// ■アクション実行
	public function execute() {
		$request = $this->getContext()->getRequest();

		// スレッド移動処理
		$from_id = $request->getAttribute('waiwai_move_from_id');
		$to_id = $request->getAttribute('waiwai_move_to_id');
		$option = $request->getAttribute('waiwai_move_option');
		$this->db->moveThread($from_id, $to_id, $option);

		// 閲覧共通設定
		$this->setBrowseCommon();

		return View::SUCCESS;
	}

	// ■デフォルトビュー表示
	public function getDefaultView() {
		$request = $this->getContext()->getRequest();

		// 閲覧共通設定
		$this->setBrowseCommon();

		$move['move_from'] = $request->getParameter('waiwai_id');
		$request->setAttribute('waiwai_move', $move);

		return View::INPUT;
	}

	// ■アクション実行メソッド指定
	public function getRequestMethods() {
		return Request::POST;
	}

	// ■Validator登録
	public function registerValidators($validatorManager) {
		$context = $this->getContext();

		// 入力有無チェック
		$validatorManager->registerName('waiwai_move_from', TRUE, '移動元発言IDが未入力です。');
		$validatorManager->registerName('waiwai_move_to', TRUE, '移動先発言IDが未入力です。');
		$validatorManager->registerName('waiwai_move_option', TRUE, 'オプションが未選択です。');

		// 移動元発言ID数字チェック
		$params = array(
			'nan_error' => '移動元発言IDが半角数字ではありません。',
			'min' => 1,
			'min_error' => '移動元発言IDは1以上の数字で指定してください。');
		$validator = new NumberValidator;
		$validator->initialize($context, $params);
		$validatorManager->registerValidator('waiwai_move_from', $validator);

		// 移動先発言ID数字チェック
		$params = array(
			'nan_error' => '移動先発言IDが半角数字ではありません。',
			'min' => 0,
			'min_error' => '移動先発言IDは0以上の数字で指定してください。');
		$validator = new NumberValidator;
		$validator->initialize($context, $params);
		$validatorManager->registerValidator('waiwai_move_to', $validator);
	}

	// ■入力データチェック
	public function validate() {
		$request = $this->getContext()->getRequest();

		$move = $this->getParameters();

		if ($move['move_from'] == $move['move_to']) {
			$request->setError('waiwai_move_from', '移動元発言IDと移動先発言IDに同じIDは指定できません。');
			return false;
		}

		// 移動元IDチェック
		$from_id = $this->db->getRemarkId($this->room_id, $move['move_from']);
		if (!$from_id) {
			$request->setError('waiwai_move_from', "入力された移動元発言IDは存在しません。");
			return false;
		}

		// 移動先IDチェック
		if ($move['move_to'] == 0)
			$to_id = 0;
		else {
			$to_id = $this->db->getRemarkId($this->room_id, $move['move_to']);
			if (!$to_id) {
				$request->setError('waiwai_move_to', "入力された移動先発言IDは存在しません。");
				return false;
			}
		}

		// 親子関係チェック
		if ($move['move_option'] == 2 && $to_id != 0) {
			if (!$this->db->checkParentChild($this->room_id, $from_id, $to_id)) {
				$request->setError('waiwai_move_to', 'スレッド全体をそのスレッドの子の位置に移動させることはできません。');
				return false;
			}
		}

		$request->setAttribute('waiwai_move_from_id', $from_id);
		$request->setAttribute('waiwai_move_to_id', $to_id);
		$request->setAttribute('waiwai_move_option', $move['move_option']);

		return true;
	}

	// ■入力エラー処理
	public function handleError() {
		$request = $this->getContext()->getRequest();

		// 閲覧共通設定
		$this->setBrowseCommon();

		// 発言ID設定
		$move = $this->getParameters();
		$request->setAttribute('waiwai_move', $move);

		return View::INPUT;
	}

	// ■パラメータ取得
	private function getParameters() {
		$request = $this->getContext()->getRequest();

		$params['move_from'] = $request->getParameter('waiwai_move_from');
		$params['move_to'] = $request->getParameter('waiwai_move_to');
		$params['move_option'] = $request->getParameter('waiwai_move_option');

		return $params;
	}

	// ■認証を要求
    public function isSecure() {
        return true;
    }

	// ■ナビ編集ページ
	protected function isNaviEditPage() {
		return true;
	}
}
?>