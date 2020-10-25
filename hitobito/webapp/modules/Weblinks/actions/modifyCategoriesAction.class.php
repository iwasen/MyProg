<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Weblinks
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: modifyCategoriesAction.class.php,v 1.2 2006/06/12 05:29:38 ryu Exp $
 */
require_once MO_WEBAPP_DIR .'/lib/HNbActionForm.class.php';
require_once MO_WEBAPP_DIR .'/modules/Weblinks/lib/category.class.php';
require_once MO_WEBAPP_DIR .'/modules/Weblinks/lib/link.class.php';
class modifyCategoriesAction extends Action
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
		
		Navi::GuardNaviEdit($this->getContext()->getUser());
		
		$categoryManager = new WeblinksCategoryManager();

		// ���ƥ�������ˤϸ��ߤΥʥӥڡ����Ǻ������줿���ƥ���Τߤ�ɽ������
		$categories = $categoryManager->getCategoriesByModifyNavipageId(hitobito::getNaviPageInfo()->getId());
		
		// ���ƥ�����¤ӽ��10�����Ӥǥ�ʥ�Х�󥰤���
		$renumber = 10;
		foreach(array_keys($categories) as $key){
			$categories[$key]->setAttribute('lct_order', $renumber);
			$renumber += 10;
		}
		
		$editform = new WeblinksAdminCategoriesForm();
		if($editform->init($categories, $request) === HNB_ACTIONFORM_POST_SUCCESS){
		    // ����ܥ���ʤ����¹�
			if($request->hasParameter('weblinks_deleteCategory_x')){
			    $deleteIdList = $editform->weblinks_delete_id_list;
			    $where = 'lct_link_category_id IN ('.implode(',', $deleteIdList).')';
			    $categoryManager->deletes($where);
			    $request->setAttribute('message', '������ޤ�����');
		    }else{
		        // update
		        $editform->update($categories, $request);
    		    
		        foreach($categories as &$obj){
                    $categoryManager->insert($obj);		        
                }
            }
		    return View::SUCCESS;
        }else{
            $request->setAttribute('editform', $editform);
            return View::INPUT;
        }	
		        
        $request->setAttribute('categories', $categories);
        
        return View::INPUT;
    }

    public function getRequestMethods()
    {
        
        return Request::POST | Request::GET;
    }

    public function isSecure()
    {
        return true;
    }
}


class WeblinksAdminCategoriesForm extends HNbActionForm
{
    public $weblinks_itemlist = array();
    public $weblinks_delete_id_list = array();
    public function fetch($master, $request)
    {
        parent::fetch($master, $request);
        
        $deletes = $request->getParameter('weblinks_delete_id_list');
        if(count($deletes)){
            foreach($deletes as $id){
                // delete����������
                if($this->weblinks_itemlist[$id]['can_delete'] != true){
                    $this->addError($this->weblinks_itemlist[$id]['title'].'�Ϻ���Ǥ��ޤ���');
                }
                $this->weblinks_delete_id_list[] = intval($id);
            }
        }
        $itemlist = $request->getParameter('weblinks_itemlist');
        
        foreach($itemlist as $id=>$vars){
            $this->weblinks_itemlist[$id]['title'] = trim($vars['title']);
            $this->weblinks_itemlist[$id]['order'] = intval($vars['order']);
            $this->weblinks_itemlist[$id]['delete_check'] = intval($vars['delete_check']);
            // titleɬ��
            if(strlen($this->weblinks_itemlist[$id]['title']) ==0){
                $this->addError('��󥯥��ƥ��꡼�����ȥ�����Ϥ��Ƥ�������');
            }            
        }
    }
    public function load($master, $request)
    {
        foreach($master as $obj){
            $this->weblinks_itemlist[$obj->getId()] = array(
                'id' => $obj->getId(),
                'title' => $obj->getAttribute('lct_category_name'),
                'order' => $obj->getAttribute('lct_order'),
                'can_delete' =>$obj->canDelete(),
                'delete_check' =>0
            );
        }
    }
    public function update($master, $request)
    {
        foreach($master as &$obj){
            $obj->setAttribute('lct_category_name', $this->weblinks_itemlist[$obj->getId()]['title']);
            $obj->setAttribute('lct_order', $this->weblinks_itemlist[$obj->getId()]['order']);
        }
        
    }  
}
?>