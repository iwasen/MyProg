<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * ��������WEB �ӥ塼���ܥ��饹
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Kuchikomi/lib/KuchikomiDatabase.class.php');

abstract class KuchikomiBaseAction extends Action {
	protected $db;
	protected $navi_page_id;

	// �������
	public function initialize($context) {
		parent::initialize($context);

		// �ǡ����١�����³
		$this->db = new KuchikomiDatabase();

		// �ʥӥڡ���ID����
		$request = $context->getRequest();
//		$this->navi_page_id = $request->getParameter('navipage_id');
		
		$this->navi_page_id = hitobito::getNavipageInfo()->getId();

		return true;
	}
}
?>