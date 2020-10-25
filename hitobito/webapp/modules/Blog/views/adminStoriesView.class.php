<?php
/**
 * ShortDescription
 *
 * Description
 * * 
 * @package Blog
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: adminStoriesView.class.php,v 1.1 2005/11/22 15:47:29 ryu Exp $
 */

require_once MO_WEBAPP_DIR . '/modules/Blog/lib/BlogBaseView.class.php';
class AdminStoriesInputView extends BlogBaseView
{
    public function execute ()
    {

        $this->setTemplate('adminStories.html');

    }
}


class AdminStoriesSuccessView extends BlogBaseView
{
    public function execute ()
    {
        $this->setTemplate('adminStories.html');

    }
}

class AdminStoriesErrorView extends BlogBaseView
{
    public function execute ()
    {

        $this->setTemplate('adminStories.html');

    }
}
?>