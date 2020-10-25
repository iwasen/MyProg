<?php
/**
 * ShortDescription
 *
 * Description
 * * 
 * @package Blog
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: UploadImageView.class.php,v 1.5 2006/04/24 10:34:08 ryu Exp $
 */
 
 
abstract class UploadImageBaseView extends SmartyView
{
    const IMAGE_MANAGER_MODE = 1;
    protected $mode = 'adminimage';
    public function initialize($context)
    {
        parent::initialize($context);
        $request = $this->getContext()->getRequest();
        if($request->getParameter('imagemanager') == UploadImageBaseView::IMAGE_MANAGER_MODE){
            // image manager mode
            $this->setImageOptions();
            $this->mode = 'imagemanager';
       }else{
            // admin image monde
            $this->useMainTemplate();
            $this->mode = 'adminimage';
        }
        
        return TRUE;
    }
    
    protected function setImageOptions()
    {
        $image_options = array(0 => '----------');
        $imageManager = new BlogImageManager();
        $images = $imageManager->getObjects('bli_blog_id='.hitobito::getNaviPageInfo()->getBlog_id());
        foreach($images as $obj){
        	$image_options[$obj->getId()] = $obj->getAttribute('bli_title');
        }
        $this->setAttribute('image_options', $image_options);
    }
}

class UploadImageInputView extends UploadImageBaseView
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        if($this->mode == 'adminimage'){
            $this->setTemplate('UploadImageForm.html');
        }else{
            $this->setTemplate('popup_image.html');
        }

        // assign
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
		$resize_options = array(
			'0' => HNb::tr('伉扔奶朮仄卅中'),
			'1' => HNb::tr('產60x衝60pixl'),
			'2' => HNb::tr('產75x衝50pixl'),
			'3' => HNb::tr('產80x衝80pixl'),
			'4' => HNb::tr('產100x衝100pixl'),
			'5' => HNb::tr('產200x衝200pixl'),
			'6' => HNb::tr('產300x衝300pixl'),
			'7' => HNb::tr('產400x衝400pixl'),
			'8' => HNb::tr('產500x衝500pixl'),
		);
		$this->setAttribute('resize_options', $resize_options);

        if($user->hasAttribute('last_image_id','Blog')){
            $imageManager = new BlogImageManager();
            $last_image = $imageManager->get($user->getAttribute('last_image_id','Blog'));
            $this->setAttribute('last_image', $last_image);
            $user->removeAttribute('last_image_id', 'Blog');
        }
        

    }
}

class UploadImageSuccessView extends UploadImageBaseView
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        if($this->mode == 'adminimage'){
            $this->setTemplate('result.html');
        }else{
            $this->setTemplate('popup_image.html');
        }

        // assign
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
        
        $this->setAttribute('return_url','index.php?module=Blog&action=adminImage&navipage_id='.hitobito::getNaviPageInfo()->getId());
                
        if($this->mode =='imagemanager'){
            $imageManager = new BlogImageManager();
            $last_image = $imageManager->getOne('bli_blog_id='.hitobito::getNaviPageInfo()->getBlog_id() .' ORDER BY bli_blog_image_id DESC', TRUE);
            $user->setAttribute('last_image_id', $last_image->getId(), 'Blog');

            $controller->redirect('index.php?module=Blog&action=UploadImage&navipage_id='.hitobito::getNaviPageInfo()->getId().'&imagemanager=1');
        }
    }
}

class UploadImageErrorView extends UploadImageBaseView
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        $this->setTemplate('error.html');

        // assign
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
        
        $this->setAttribute('return_url','index.php?module=Blog&action=adminImage&navipage_id='.hitobito::getNaviPageInfo()->getId());
    }
}
?>