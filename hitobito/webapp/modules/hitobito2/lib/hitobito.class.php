<?php
require_once MO_WEBAPP_DIR.'/hitobito/hitobitoNavipage.class.php';
class hitobito
{
	public function getUrl()
	{
		static $url;
		if(!isset($url)){
			$db = HNb::getAdodb();
			$url = $db->GetOne("SELECT sys_value FROM m_system WHERE sys_key='user_page_url'");
		}
		return $url;
	}
	
	public function getSiteName()
	{
		static $siteName;
		if(!isset($siteName)){
			$db = HNb::getAdodb();
			$siteName = $db->GetOne("SELECT sys_value FROM m_system WHERE sys_key='site_name'");
		}
		return $siteName;
		
	}
	
	/**
	 * �ꥯ�����ȥѥ�᡼�����鼫ưŪ�˸��ߤΥʥӥڡ�������Ф��ƥʥӥڡ���������֤�
	 * 
	 * �б����Ƥ���ꥯ�����ȥѥ�᡼��
	 * -navipage_id
	 */
	public function getNaviPageInfo()
	{
		static $naviPage ;
		if(!isset($naviPage)){
			$naviManager = new hitobitoNavipageManager();
			$navipage_id = isset($_REQUEST['navipage_id']) ? intval($_REQUEST['navipage_id']) : null;
			if($navipage_id){
				$naviPage = $naviManager->get($navipage_id);
			}else{
				$module = isset($_REQUEST['module']) ? $_REQUEST['module'] : '';
				
				switch($module){
//					case 'Waiwai':
//						$waiwaiId = intval($_REQUEST['id']);
//						$naviPage = $naviManager->GetOne('nvp_room_id='.$waiwaiId);
//						break;
                  case 'Blog':
                    $story_id = 0;
                    if(isset($_REQUEST['blog_story_id'])) {
                        $story_id = intval($_REQUEST['blog_story_id']);
                    }
                    if(isset($_REQUEST['story_id'])){
                        $story_id = intval($_REQUEST['story_id']);
                    }
                    
                    if($story_id > 0) {
                        $db = HNb::getAdodb();
                        $navipage_id = $db->GetOne('SELECT nvp_navi_page_id FROM t_navi_page INNER JOIN t_blog ON nvp_blog_id=blg_blog_id INNER JOIN t_blog_article ON bla_blog_id=blg_blog_id WHERE bla_article_id='.$story_id);
                        $naviPage = $naviManager->get($navipage_id);
                    }
                    break;
                  case 'Enquete':
                    if(isset($_REQUEST['enquete_id'])){
                        $enqId = intval($_REQUEST['enquete_id']);
                        $db = HNb::getAdodb();
                        $navipage_id = $db->GetOne('SELECT nen_navi_page_id FROM t_navi_enquete WHERE nen_enquete_id='.$enqId);
                        $naviPage = $naviManager->get($navipage_id);
                    }
                    break;
                  case 'MailMag':
                    if(isset($_REQUEST['contents_id'])){
                        $contents_id = intval($_REQUEST['contents_id']);
                        $db = HNb::getAdodb();
                        $navipage_id = $db->GetOne('SELECT nvp_navi_page_id FROM t_navi_page INNER JOIN t_melmaga_contents ON nvp_melmaga_id=mmc_melmaga_id WHERE mmc_melmaga_contents_id='.$contents_id);
                        $naviPage = $naviManager->get($navipage_id);
                    }
                    break;
				  default:
						// TODO ¾�Υ⥸�塼��ξ��
				}
			}
			// ���ߡ����å�
			if(!isset($naviPage)){
				$naviPage = $naviManager->create();
			}
				
		}
		return $naviPage;
	}
	
	/**
	 * �ꥯ�����ȥѥ�᡼�����鼫ưŪ�˸��ߤ�channel_id����Ф����֤�
	 * �б����Ƥ���ꥯ�����ȥѥ�᡼��
	 * -category_id
	 */
	 public function getChannelId()
	 {
	 	static $channelId;
	 	if(!isset($channelId)){
	 		$channelId = isset($_REQUEST['category_id']) ? intval($_REQUEST['category_id']) : (hitobito::getNaviPageInfo()->getChannelId());
	 	}
	 	return $channelId;
	 }
	
	/**
	 * ��̳�ɥ֥�ID���֤�
	 */
	public function getAdminBlogId()
	{
		$naviManager = new hitobitoNavipageManager();
		$navi = $naviManager->get(hitobito::getAdminNavipageId());
		return $navi->getBlog_id();
	}
	
	/**
	 * ��̳�ɥʥӥڡ���ID���֤�
	 */
	public function getAdminNavipageId()
	{
		$master = hitobito::getSystemMasterArray();
		return $master['admin_navipage_id'];
	}
	
	/**
	 * �����ƥ�ޥ������ͤ�Ϣ��������֤�
	 */
	public function getSystemMasterArray()
	{
		static $master;
		if(!isset($master)){
			$db = HNb::getAdodb();
			$result = $db->GetAll("SELECT * FROM m_system");
			foreach($result as $vars){
				$master[$vars['sys_key']] = $vars['sys_value'];
			}
		}
		return $master;
	}
	public function getSystemMaster($key)
	{
		$master = hitobito::getSystemMasterArray();
		return $master[$key];
	}
	
	/**
	 * ��ƻ�ܸ��ޥ������ͤ�ID �򥭡��ˤ����֤�
	 */
	public function getTodofukenArray()
	{
		$db = HNb::getAdodb();
		$result = $db->GetAll("SELECT * FROM m_todofuken");
		$ret = array();
		foreach($result as $row){
			$ret[$row['tdf_area_id']] = $row['tdf_name'];
		}
		return $ret;
	}
	
	/**
	 * ���ȥޥ������ͤ�ID�򥭡��ˤ����֤�
	 */
	public function getShokugyoArray()
	{
		$db = HNb::getAdodb();
		$result = $db->GetAll("SELECT * FROM m_shokugyo ORDER BY skg_order ASC");
		$ret = array();
		foreach($result as $row){
			$ret[$row['skg_shokugyo_id']] = $row['skg_shokugyo_name'];
		}
		return $ret;	
	}
	
	public function getMailer()
	{
		require_once MO_WEBAPP_DIR.'/modules/hitobito2/lib/hitobitoMailer.class.php';
		return new hitobitoMailer();	
	}

	// �������ȥ������󥹼���
	public function getCurrentSequence($table, $field) {
		$db = HNb::getAdodb();
		$sql = "SELECT currval('{$table}_{$field}_seq')";
		return $db->GetOne($sql);
	}
	
	// �������ڡ����ӥ塼�����󥿼���
	public function getNewPageViewId($log_flag = true) {
		$db = HNb::getAdodb();
		$rec = array();
		$rec['pvc_log_flag'] = $log_flag ? 1 : 0;
		$db->AutoExecute('c_page_view', $rec, 'INSERT');
		return hitobito::getCurrentSequence('c_page_view', 'pvc_pv_id');
	}

	// ���ڡ����ӥ塼�������
	public function countPageView($pv_id) {
		$db = HNb::getAdodb();
		if ($pv_id) {
			$db->BeginTrans();

			// �ڡ����ӥ塼�������
			$rec = array();
			$rec['pvc_pv_count'] = "pvc_pv_count + 1";
			$db->AutoExecute('c_page_view', $rec, 'UPDATE', "pvc_pv_id=$pv_id");

			// �ڡ����ӥ塼����
			$sql = "SELECT pvc_log_flag FROM c_page_view WHERE pvc_pv_id=$pv_id";
			if ($db->GetOne($sql)) {
				$rec = array();
				$rec['pvl_pv_id'] = $pv_id;
				$rec['pvl_ip_addr'] = $_SERVER['REMOTE_ADDR'];
				$db->AutoExecute('l_page_view', $rec, 'INSERT');
			}

			$db->CommitTrans();
		}
	}
	
}
?>