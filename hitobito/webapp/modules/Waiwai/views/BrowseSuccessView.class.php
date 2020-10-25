<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �磻�磻��ļ� �����ӥ塼
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseView.class.php');

class BrowseSuccessView extends WaiwaiBaseView {
	// ���ӥ塼�¹�
	function execute() {
		// �ƥ�ץ졼������
		$this->setTemplate('BrowseSuccess.html');

		// ������������
		$this->setBowseCommon();

		// ȯ����������
		$this->setContents();

		// �������
		$this->setLinks();

		// �ᥤ��ƥ�ץ졼��
		$this->useMainTemplate();
	}

	// ��������ƥ������
	private function setLinks() {
		$request = $this->getContext()->getRequest();
		$waiwai_links = $request->getAttribute('waiwai_links');

		if ($waiwai_links) {
				// �����
			$link_data = $this->db->getContents($waiwai_links['befor_remark_id']);
			$waiwai_links['befor_link'] = $this->make_link_text('�������', $link_data);

			// ����
			$link_data = $this->db->getContents($waiwai_links['next_remark_id']);
			$waiwai_links['next_link'] = $this->make_link_text('������', $link_data);

			$this->setAttribute('waiwai_links', $waiwai_links);

		// ����ɽ����Ĵ����
		} else if($request->getParameter('view')) {
			$waiwai_contents['seq_no'] = $request->getParameter('waiwai_id');
			$waiwai_contents['view'] = $request->getParameter('view');
			$this->setAttribute('waiwai_contents', $waiwai_contents);
		}
	}

	// ��������ƥ������
	private function make_link_text($kind, $link_data) {
		$navi_page_id = $this->getAttribute('navi_page_id');
		$seq_no = $link_data['seq_no'];
		$url = 'index.php?module=Waiwai&action=Browse&navipage_id='.$navi_page_id.'&waiwai_id='.$seq_no.'#waiwai';
		$title = $link_data['subject'];
		$name = $link_data['nickname'];
		$date = $this->format_datetime($link_data['date']);

		if ($seq_no)
			$text = '<a href="'.$url.'">' . $kind . '��['.$seq_no.'] '.$title.'��by '.$name.'��'.$date.'</a>';

		return $text;
	}
}

?>