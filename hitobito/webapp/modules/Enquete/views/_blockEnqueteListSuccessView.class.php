<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �ʥӥ��󥱡��� ���󥱡��Ȱ���ɽ���ӥ塼
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Enquete/lib/EnqueteBaseView.class.php');

class _blockEnqueteListSuccessView extends EnqueteBaseView
{
	// ���ӥ塼�¹�
	public function execute() {
		$request = $this->getContext()->getRequest();

		// �ƥ�ץ졼������
		$this->setTemplate('_blockEnqueteList.html');

		// ���󥱡��ȥꥹ������
		$navi_page_id = $request->getAttribute('navi_page_id');
		$enquete_list_ary = $request->getAttribute('enquete_list_ary');
		$this->setAttribute('enquete_list_ary', $this->formatList($enquete_list_ary, $navi_page_id));
	}

	// ���ꥹ�Ȥ�ɽ���Ѥ˥ե����ޥå�
	private function formatList($list_ary, $navi_page_id) {
		$format_list_ary = array();
		if (is_array($list_ary)) {
			foreach ($list_ary as $index => $list) {
				$list['start_date'] = $this->format_date($list['start_date']);
				$list['end_date'] = $this->format_date($list['end_date']);
				$list['bgcolor'] = $index % 2 ? '#FFFFFF' : '#E6F2FF';

				switch ($list['status']) {
				case 2:
					$list['status_gif'] = 'enquete_time_on.gif';
					$list['status_alt'] = '���������桪';
					$list['status_text'] = '����������';
					$list['status_flag'] = true;
					$list['status_color'] = '#EE615E';
					$list['link'] = "?module=Enquete&action=AnswerEnquete&navipage_id=$navi_page_id&enquete_id={$list['enquete_id']}";
					break;
				case 3:
					$list['status_gif'] = 'enquete_time_off.gif';
					$list['status_alt'] = '�������ֽ�λ';
					$list['status_text'] = '�������ֽ�λ';
					$list['status_flag'] = false;
					$list['status_color'] = '#999999';
					$list['link'] = "?module=Enquete&action=ShowResult&navipage_id=$navi_page_id&enquete_id={$list['enquete_id']}";
					break;
				}

				$format_list_ary[] = $list;
			}
		}

		return $format_list_ary;
	}
}
?>