<?php
/**
 * ShortDescription
 *
 * Description
 * * 
 * @package Blog
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: receiveTrackbackView.class.php,v 1.1 2005/11/22 15:47:29 ryu Exp $
 */
require_once MO_WEBAPP_DIR . '/modules/Blog/lib/BlogBaseView.class.php';


class ReceiveTrackbackSuccessView extends SmartyView
{
    public function execute ()
    {
        $this->setTemplate('trackback_success.xml');
    }
    public function useMainTemplate()
    {
    }
}

class ReceiveTrackbackErrorView extends SmartyView
{
    public function execute ()
    {
        $this->setTemplate('trackback_error.xml');
    }
}
?>