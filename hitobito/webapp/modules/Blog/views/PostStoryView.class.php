<?php
/**
 * ShortDescription
 * 
 * Description
 * 
 * @package Blog
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: PostStoryView.class.php,v 1.8 2007/11/20 08:51:25 cvshitobito Exp $
 */
require_once MO_WEBAPP_DIR . '/modules/Blog/lib/BlogBaseView.class.php';
require_once MO_WEBAPP_DIR . '/modules/Blog/lib/BlogImage.class.php';

class PostStoryInputView extends BlogBaseView
{
    public function execute ()
    {
        $this->setTemplate('storyform.html');
        
        
        $open_flag_options = array('1'=>'����', '0'=>'�����', '2' => '���ɥ쥹ľ�ǤΤ�ɽ��');
        $this->setAttribute('open_flag_options', $open_flag_options);
        $navi_select_flag_options = array('0'=> '�ʥӵ����Τ�','2' => '�ʥӥ��쥯�ȤΤ�','1'=>'ξ��' );
        $this->setAttribute('navi_select_flag_options', $navi_select_flag_options);
        $comment_flag_options = array('1' => '����', '0'=> '�����');
        $this->setAttribute('comment_flag_options', $comment_flag_options);
        $trackback_flag_options = array('1' => '����', '0'=> '�����');
        $this->setAttribute('trackback_flag_options', $trackback_flag_options);
        
        $b_story_id = $this->request->getParameter('blog_story_id', 0);
        $this->setAttribute('b_story_id', $b_story_id);
        
        
        // category
        $categoryManager = new BlogCategoryManager();
        $categoris = $categoryManager->getCategoriesByBlogId($this->request->getAttribute('blog_id'));
        $categories_options = array();
        foreach($categoris as $obj){
            $categories_options[$obj->getId()] = $obj->getCategoryName();
        }
        $this->setAttribute('categories_options', $categories_options);
        
        // ����
        $image_options = array(0 => '----------');
        $imageManager = new BlogImageManager();
        $images = $imageManager->getObjects('bli_blog_id='.$this->request->getAttribute('blog_id'));
        foreach($images as $obj){
            $image_options[$obj->getId()] = $obj->getAttribute('bli_title');
        }
        $this->setAttribute('image_options', $image_options);
        $this->appendAttribute('HNbJavaScriptFile', 'js/edit.js');
        
        
        $editform = $this->request->getAttribute('editform');
        
        if($editform->bla_blog_image_id){
            $index_image_title = $image_options[$editform->bla_blog_image_id];
        }else{
            $index_image_title = '';
        }
        $this->setAttribute('blog_image_title', $index_image_title);
        
        //�������饸��
        $blog_date_options = array(
            '0' => HNb::tr('���߻���ˤ��롣'),
            '1' => HNb::tr('���������ˤ��롣'));
        $this->setAttribute('date_options', $blog_date_options);
    }
}

class PostStorySuccessView extends BlogBaseView
{
    public function execute ()
    {
        $this->setTemplate('result.html');
    }
}

class PostStoryErrorView extends BlogBaseView
{
    public function execute ()
    {
        $this->setTemplate('storyform.html');
    }
}
?>