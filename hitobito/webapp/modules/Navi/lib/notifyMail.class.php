<?php

/**
*
* @author Ryuji
* @version $Id: notifyMail.class.php,v 1.2 2006/10/16 16:16:55 ryu Exp $
*/
require_once MO_WEBAPP_DIR . '/hitobito/db/t_navi_page_notify.class.php';

class NaviNotifyMail
{
    public function notify($navipage, $notify_name, $values)
    {
        if($this->isActive($navipage->getId(), $notify_name)){
		    $mail_template_name = $notify_name.'2navi';
		    
		    $mailer = hitobito::getMailer();
		    
		    $mailer->loadTemplate($mail_template_name);
		    foreach($values as $key=>$var){
		        $mailer->setAttribute($key, $var);
		    }
		    $mailer->setAttribute('NAVIPAGE_TITLE', $navipage->getTitle());
		    $mailer->setAttribute('NAVIEDIT_URL', hitobito::getUrl().'index.php?module=Navi&action=ShowNaviEdit&navipage_id='.$navipage->getId());
    		
		    $to = $navipage->getNaviEmail();
		    $result = $mailer->send($to);
	    }else{
	        $result = true;
	    }
	    return $result;
    }
    protected function isActive($navipage_id, $notify_name)
    {
		$handler = new t_navi_page_notifyObjectHandler();
		$notifyConfig = $handler->get($navipage_id);
		$attributeName = 'nnt_'.$notify_name.'_notify';
		$active = ($notifyConfig->getAttribute($attributeName) == 1) ? true : FALSE;
		if($notify_name == 'contact' && $notifyConfig->isNew()) {
		    // 問い合わせ通知のみ未設定時は通知する
		    $active = TRUE;
		}
		return $active;
	}
}
?>