<?php
/**
 * ナビエディット:リンク管理
 *
 * [Description]
 * * 
 * @package Blog
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: modifyStoriesAction.class.php,v 1.2 2006/06/12 07:15:20 ryu Exp $
 */
require_once MO_WEBAPP_DIR .'/lib/HNbActionForm.class.php';
require_once MO_WEBAPP_DIR . '/modules/Blog/lib/BlogBaseAction.class.php';

class modifyStoriesAction extends BlogBaseAction
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
		
		Navi::GuardNaviEdit($this->getContext()->getUser());
		
        $itemManager = new BlogOneBlogStoryManager($this->blog_id);

        $category_id = intval($request->getParameter('blog_category_id'));
        $itemlist = $itemManager->getStoriesByCategoryId($category_id);
        $request->setAttribute('itemlist', $itemlist);

		
		if($category_id > 0){
			$editform = new BlogModifyItemsInCategoryForm();
			$request->setAttribute('can_edit_order', true);
			// 並び順を10番飛びでリナンバリングする(カテゴリ内のみ)
			$renumber = 10;
			foreach(array_keys($itemlist) as $key){
				$itemlist[$key]->setAttribute('bla_order', $renumber);
				$renumber += 10;
			}
		}else{
			$editform = new BlogModifyAllItemsForm();
			$request->setAttribute('can_edit_order', false);
		}			

		if($editform->init($itemlist, $request) === HNB_ACTIONFORM_POST_SUCCESS){
		    // 削除ボタンなら削除実行
			if($request->hasParameter('blog_deleteCategory_x')){
			    
			    $deleteIdList = $editform->blog_delete_id_list;
			    $where = 'bla_article_id IN ('.implode(',', $deleteIdList).')';
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


class BlogModifyAllItemsForm extends HNbActionForm
{
    public $blog_itemlist = array();
    public $blog_delete_id_list = array();
    public function fetch($master, $request)
    {
        parent::fetch($master, $request);
        
        
        $deletes = $request->getParameter('blog_delete_id_list');
        if(count($deletes)){
            foreach($deletes as $id){
                $this->blog_delete_id_list[] = intval($id);
            }
        }
        
        $itemlist = $request->getParameter('blog_itemlist');
        
    }
    public function load($master, $request)
    {
        foreach($master as $obj){
            $this->blog_itemlist[$obj->getId()] = array(
                'id' => $obj->getId(),
                'title' => $obj->getAttribute('bla_title'),
            );
        }
    }
    public function update($master, $request)
    {
    }  
}


class BlogModifyItemsInCategoryForm extends HNbActionForm
{
    public $blog_itemlist = array();
    public $blog_delete_id_list = array();
    public function fetch($master, $request)
    {
        parent::fetch($master, $request);
        
        
        $deletes = $request->getParameter('blog_delete_id_list');
        if(count($deletes)){
            foreach($deletes as $id){
                $this->blog_delete_id_list[] = intval($id);
            }
        }
        
        $itemlist = $request->getParameter('blog_itemlist');
        
        foreach($itemlist as $id=>$vars){
            //$this->blog_itemlist[$id]['title'] = trim($vars['title']);
            $this->blog_itemlist[$id]['order'] = intval($vars['order']);
        }
    }
    public function load($master, $request)
    {
        foreach($master as $obj){
            $this->blog_itemlist[$obj->getId()] = array(
                'id' => $obj->getId(),
                'title' => $obj->getAttribute('bla_title'),
                'order' => $obj->getAttribute('bla_order'),
            );
        }
    }
    public function update($master, $request)
    {
        foreach($master as &$obj){
            $obj->setAttribute('bla_order', $this->blog_itemlist[$obj->getId()]['order']);
        }
        
    }  
}

?>