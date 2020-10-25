<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Weblinks
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: modifyCategoriesAction.class.php,v 1.2 2006/06/12 05:33:58 ryu Exp $
 */
require_once MO_WEBAPP_DIR . '/modules/Blog/lib/BlogBaseAction.class.php';
require_once MO_WEBAPP_DIR .'/lib/HNbActionForm.class.php';

class modifyCategoriesAction extends BlogBaseAction
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
		
		Navi::GuardNaviEdit($this->getContext()->getUser());
		
        $manager = new BlogCategoryManager();
        $categories = $manager->getCategoriesByBlogId($this->blog_id);

		// カテゴリの並び順を10番飛びでリナンバリングする
		$renumber = 10;
		foreach(array_keys($categories) as $key){
			$categories[$key]->setAttribute('category_order', $renumber);
			$renumber += 10;
		}


		$editform = new BlogModifyCategoriesForm();
		if($editform->init($categories, $request) === HNB_ACTIONFORM_POST_SUCCESS){
		    // 削除ボタンなら削除実行
			if($request->hasParameter('blog_deleteCategory_x')){
			    $deleteIdList = $editform->delete_id_list;
			    $where = 'blog_category_id IN ('.implode(',', $deleteIdList).')';
			    $manager->deletes($where);
			    $request->setAttribute('message', '削除しました。');
		    }else{
		        // update
		        $editform->update($categories, $request);
    		    
		        foreach($categories as &$obj){
                    $manager->insert($obj);		        
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


class BlogModifyCategoriesForm extends HNbActionForm
{
    public $blog_itemlist = array();
    public $delete_id_list = array();
    public function fetch($master, $request)
    {
        parent::fetch($master, $request);
        
        $deletes = $request->getParameter('blog_delete_id_list');
        if(count($deletes)){
            foreach($deletes as $id){
                // delete妥当性検査
                if($this->blog_itemlist[$id]['can_delete'] != true){
                    $this->addError($this->blog_itemlist[$id]['title'].'は削除できません');
                }
                $this->delete_id_list[] = intval($id);
            }
        }
        $itemlist = $request->getParameter('blog_itemlist');
        
        foreach($itemlist as $id=>$vars){
            $this->blog_itemlist[$id]['title'] = trim($vars['title']);
            $this->blog_itemlist[$id]['order'] = intval($vars['order']);
            // title必須
            if(strlen($this->blog_itemlist[$id]['title']) ==0){
                $this->addError('カテゴリータイトルを入力してください');
            }            
        }
    }
    public function load($master, $request)
    {
        $BlogStoryManager = new BlogStoryManager();
        foreach($master as $obj){
            if($BlogStoryManager->getCount('bla_blog_category_id='.$obj->getId())){
                $canDelete = false;
            }else{
                $canDelete = true;
            }
            $this->blog_itemlist[$obj->getId()] = array(
                'id' => $obj->getId(),
                'title' => $obj->getCategoryName(),
                'order' => $obj->getAttribute('category_order'),
                'can_delete' =>$canDelete,
            );
        }
    }
    public function update($master, $request)
    {
        foreach($master as &$obj){
            $obj->setAttribute('category_name', $this->blog_itemlist[$obj->getId()]['title']);
            $obj->setAttribute('category_order', $this->blog_itemlist[$obj->getId()]['order']);
        }
        
    }  
}
?>