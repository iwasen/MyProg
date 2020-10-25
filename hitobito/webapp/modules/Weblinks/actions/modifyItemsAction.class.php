<?php
/**
 * ナビエディット:リンク管理
 *
 * [Description]
 * * 
 * @package Weblinks
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: modifyItemsAction.class.php,v 1.3 2006/06/12 08:05:14 ryu Exp $
 */
require_once MO_WEBAPP_DIR .'/lib/HNbActionForm.class.php';
require_once MO_WEBAPP_DIR .'/modules/Weblinks/lib/category.class.php';
require_once MO_WEBAPP_DIR .'/modules/Weblinks/lib/link.class.php';

class modifyItemsAction extends Action
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
		
		Navi::GuardNaviEdit($this->getContext()->getUser());
		
        $itemManager = new WeblinksLinkManager();

        $category_id = intval($request->getParameter('weblinks_category_id'));
        $itemlist = $itemManager->getItemsByCategoryId($category_id);
        $request->setAttribute('itemlist', $itemlist);
		
		if($category_id > 0){
			$editform = new WeblinksModifyItemsInCategoryForm();
			$request->setAttribute('can_edit_order', true);
			// 並び順を10番飛びでリナンバリングする(カテゴリ内のみ)
			$renumber = 10;
			foreach(array_keys($itemlist) as $key){
				$itemlist[$key]->setAttribute('nol_order', $renumber);
				$renumber += 10;
			}
		}else{
			$editform = new WeblinksModifyAllItemsForm();
			$request->setAttribute('can_edit_order', false);
		}			

		if($editform->init($itemlist, $request) === HNB_ACTIONFORM_POST_SUCCESS){
		    // 削除ボタンなら削除実行
		    // TODO
			if($request->hasParameter('weblinks_deleteCategory_x')){
			    
			    $deleteIdList = $editform->weblinks_delete_id_list;
			    $where = 'nol_osusume_link_id IN ('.implode(',', $deleteIdList).')';
			    $itemManager->deletes($where);
			    $request->setAttribute('message', '削除しました。');
		    }else{
		        // update
		        $editform->update($itemlist, $request);
    		    
		        foreach($itemlist as &$obj){
                    $itemManager->insert($obj);		        
                }
            }
		    return View::SUCCESS;
        }else{
            $request->setAttribute('editform', $editform);
            return View::INPUT;
        }	
		        
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

class WeblinksModifyAllItemsForm extends HNbActionForm
{
    public $weblinks_itemlist = array();
    public $weblinks_delete_id_list = array();
    public function fetch($master, $request)
    {
        parent::fetch($master, $request);
        
        
        $deletes = $request->getParameter('weblinks_delete_id_list');
        if(count($deletes)){
            foreach($deletes as $id){
                // delete妥当性検査
//                if($this->weblinks_itemlist[$id]['can_delete'] != true){
//                    $this->addError($this->weblinks_itemlist[$id]['title'].'は削除できません');
//                }
                $this->weblinks_delete_id_list[] = intval($id);
            }
        }
        
        $itemlist = $request->getParameter('weblinks_itemlist');
        
        foreach($itemlist as $id=>$vars){
            $this->weblinks_itemlist[$id]['title'] = trim($vars['title']);
            $this->weblinks_itemlist[$id]['delete_check'] = intval($vars['delete_check']);
            // title必須
            if(strlen($this->weblinks_itemlist[$id]['title']) ==0){
                $this->addError('リンクカテゴリータイトルを入力してください');
            }            
        }
    }
    public function load($master, $request)
    {
        foreach($master as $obj){
            $this->weblinks_itemlist[$obj->getId()] = array(
                'id' => $obj->getId(),
                'title' => $obj->getAttribute('nol_link_name'),
            );
        }
    }
    public function update($master, $request)
    {
        foreach($master as &$obj){
            $obj->setAttribute('nol_link_name', $this->weblinks_itemlist[$obj->getId()]['title']);
        }
        
    }  
}


class WeblinksModifyItemsInCategoryForm extends HNbActionForm
{
    public $weblinks_itemlist = array();
    public $weblinks_delete_id_list = array();
    public function fetch($master, $request)
    {
        parent::fetch($master, $request);
        
        
        $deletes = $request->getParameter('weblinks_delete_id_list');
        if(count($deletes)){
            foreach($deletes as $id){
                // delete妥当性検査
//                if($this->weblinks_itemlist[$id]['can_delete'] != true){
//                    $this->addError($this->weblinks_itemlist[$id]['title'].'は削除できません');
//                }
                $this->weblinks_delete_id_list[] = intval($id);
            }
        }
        
        $itemlist = $request->getParameter('weblinks_itemlist');
        
        foreach($itemlist as $id=>$vars){
            $this->weblinks_itemlist[$id]['title'] = trim($vars['title']);
            $this->weblinks_itemlist[$id]['order'] = intval($vars['order']);
            $this->weblinks_itemlist[$id]['delete_check'] = intval($vars['delete_check']);
            // title必須
            if(strlen($this->weblinks_itemlist[$id]['title']) ==0){
                $this->addError('リンクカテゴリータイトルを入力してください');
            }            
        }
    }
    public function load($master, $request)
    {
        foreach($master as $obj){
            $this->weblinks_itemlist[$obj->getId()] = array(
                'id' => $obj->getId(),
                'title' => $obj->getAttribute('nol_link_name'),
                'order' => $obj->getAttribute('nol_order'),
            );
        }
    }
    public function update($master, $request)
    {
        foreach($master as &$obj){
            $obj->setAttribute('nol_link_name', $this->weblinks_itemlist[$obj->getId()]['title']);
            $obj->setAttribute('nol_order', $this->weblinks_itemlist[$obj->getId()]['order']);
        }
        
    }  
}

?>