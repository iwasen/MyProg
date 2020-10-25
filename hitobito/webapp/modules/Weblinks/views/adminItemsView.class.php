<?php
/**
 * ShortDescription
 *
 * Description
 * * 
 * @package Weblinks
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: adminItemsView.class.php,v 1.1 2005/12/12 08:10:09 ryu Exp $
 */

require_once MO_WEBAPP_DIR . '/modules/Weblinks/lib/WeblinksBaseView.class.php';
class AdminItemsInputView extends WeblinksBaseView
{
    public function execute ()
    {

        $this->setTemplate('adminItems.html');

    }
}


class AdminItemsSuccessView extends WeblinksBaseView
{
    public function execute ()
    {
        $this->setTemplate('result.html');

    }
}

class AdminItemsErrorView extends WeblinksBaseView
{
    public function execute ()
    {

        $this->setTemplate('error.html');

    }
}
?>