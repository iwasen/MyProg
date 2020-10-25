<?php
/**
 * ShortDescription
 *
 * Description
 * * 
 * @package Member
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: EditView.class.php,v 1.2 2006/01/23 03:19:22 ryu Exp $
 */

class EditInputView extends SmartyView
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        $this->setTemplate('regist_input.html');

       // assign
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
        
        $this->useMainTemplate();
		// radio 
		$hitobito_letter_options = array('1' => '購読', '0'=> '購読解除');
		$this->setAttribute('hitobito_letter_options', $hitobito_letter_options);
		$status_options = array('1'=>'有効', '3' => '一時停止');
		$this->setAttribute('status_options', $status_options);
		
		// editform
//		$editform = $request->getAttribute('editform');
//		$formvars = array();
//		$formvars['mail'] = $editform->mail;
//		$formvars['hitobito_letter'] = $editform->hitobito_letter;
//		$formvars['status'] = $editform->status;
//		$this->setAttribute('editform', $formvars);
//		$this->setAttribute('errors', $editform->getErrors());
 		
    }
}

class EditAlertView extends SmartyView
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        $this->setTemplate('regist_confirm.html');

        // assign
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
//		$editform = $request->getAttribute('editform');
//		$formvars = array();
//		$formvars['mail'] = $editform->mail;
//		$formvars['hitobito_letter'] = $editform->hitobito_letter;
//		$formvars['status'] = $editform->status;
//		$this->setAttribute('editform', $formvars);
        
        $this->useMainTemplate();
    }
}
class EditSuccessView extends SmartyView
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        $this->setTemplate('save_new_account.html');

        // assign
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
//		$editform = $request->getAttribute('editform');
//		$formvars = array();
//		$formvars['mail'] = $editform->mail;
//		$formvars['hitobito_letter'] = $editform->hitobito_letter;
//		$formvars['status'] = $editform->status;
//		$this->setAttribute('editform', $formvars);
        
        $this->useMainTemplate();
        $this->setAttribute('message', HNb::tr('基本情報を更新しました。'));		
    }
}

class EditErrorView extends SmartyView
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        $this->setTemplate('save_new_account.html');

        // assign
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
        
        $this->useMainTemplate();
        $this->setAttribute('message', HNb::tr('更新に失敗しました。時間をあけて再度更新してください'));		
        
    }
}
?>