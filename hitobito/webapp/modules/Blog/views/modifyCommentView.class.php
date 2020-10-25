<?php
/**
 * ShortDescription
 *
 * Description
 * * 
 * @package Blog
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: modifyCommentView.class.php,v 1.1 2005/11/22 15:47:29 ryu Exp $
 */
require_once MO_WEBAPP_DIR . '/modules/Blog/lib/BlogBaseView.class.php';

class ModifyCommentInputView extends BlogBaseView
{
    public function execute ()
    {
        $this->setTemplate('comment_form.html');
    }
    
    public function useMainTemplate()
    {
    }
}

class ModifyCommentSuccessView extends BlogBaseView
{
    public function execute ()
    {
        $this->setTemplate('comment_form.html');
    }
    public function useMainTemplate()
    {
    }
}

class ModifyCommentErrorView extends BlogBaseView
{
    public function execute ()
    {
        $this->setTemplate('category_form.html');
    }
}
?>