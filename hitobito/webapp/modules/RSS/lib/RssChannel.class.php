<?php
abstract class RssChannel
{
    protected $context;
    public function __construct($context)
    {
        $this->context = $context;
    }
    public function getContext()
    {
        return $this->context;
    }
    abstract function getTitle();
    abstract function getDescription();
    abstract function getLink();
    abstract function getCategoryName();
    abstract function getCriteria($request);
}

class GlobalStoriesRssChannel extends RssChannel
{
    public function getLink()
    {
        return hitobito :: getUrl();
    }
    public function getCategoryName()
    {
        return HNb :: tr('全体：新着記事');
    }
    public function getTitle()
    {
        return hitobito :: getSiteName();
    }
    public function getDescription()
    {
        return HNb :: tr('教えて？に答えるコミュニティ');
    }
    public function getCriteria($request)
    {
        $criteria = new HNbCriteria();
        $criteria->setWhere('bla_navi_select_flag < 2');
        $criteria->setOrder('ORDER BY bla_date DESC');
        return $criteria;
    }
}

class GlobalNaviPageRssChannel extends RssChannel
{
    public function getLink()
    {
        return hitobito :: getUrl();
    }
    public function getCategoryName()
    {
        return HNb :: tr('全体：新着ナビページ');
    }
    public function getTitle()
    {
        return hitobito :: getSiteName();
    }
    public function getDescription()
    {
        return HNb :: tr('教えて？に答えるコミュニティ');
    }
    public function getCriteria($request)
    {
        $criteria = new HNbCriteria();
        $criteria->setWhere('nvp_open_flag=1 AND nvp_status=1');
        $criteria->setOrder('ORDER BY nvp_open_date DESC');
        return $criteria;
    }
}

class NaviPageStoriesRssChannel extends RssChannel
{
    private $navipage;
    public function __construct($context)
    {
        parent :: __construct($context);
        $manager = new hitobitoNaviPageManager();
        $this->navipage = $manager->get(intval($this->getContext()->getRequest()->getParameter('navipage_id')));
    }
    public function getLink()
    {
        return hitobito :: getUrl().'index.php?module=Navi&amp;navipage_id='.$this->navipage->getId();
    }
    public function getTitle()
    {
        return hitobito :: getSiteName().' : '.$this->navipage->getTitle();
    }
    public function getDescription()
    {
        return $this->navipage->getAttribute('nvp_outline');
    }

    public function getCategoryName()
    {
        return sprintf(HNb :: tr('%s：新着記事'), $this->navipage->getTitle());
    }
    public function getCriteria($request)
    {
        $criteria = new HNbCriteria();
        $criteria->setWhere('bla_navi_select_flag < 2');
        $criteria->addWhere('bla_blog_id='.$this->navipage->getBlog_id());
        $criteria->setOrder('ORDER BY bla_date DESC');
        return $criteria;
    }
}

class CategoryStoriesRssChannel extends RssChannel
{
    private $channel;
    public function __construct($context)
    {
        parent :: __construct($context);
        $manager = new hitobitoChannelManager();
        $this->channel = $manager->get(intval($this->getContext()->getRequest()->getParameter('category_id')));
    }
    public function getTitle()
    {
        return hitobito :: getSiteName().' : '.$this->channel->getTitle();
    }
    public function getLink()
    {
        return hitobito :: getUrl().'index.php?module=Category&amp;action=ShowMainCategory&amp;category_id='.$this->channel->getId();
    }
    public function getDescription()
    {
        return HNb :: tr('教えて？に答えるコミュニティ');
    }
    public function getCategoryName()
    {
        return sprintf(HNb :: tr('%s：新着記事'), $this->channel->getTitle());
    }
    public function getCriteria($request)
    {
        $criteria = new HNbCriteria();
        $criteria->setWhere('bla_navi_select_flag < 2');
        $criteria->addWhere('nvp_channel_id='.$this->channel->getId());
        $criteria->setOrder('ORDER BY bla_date DESC');
        return $criteria;
    }
}

class CategoryNaviPageRssChannel extends RssChannel
{
    private $channel;
    public function __construct($context)
    {
        parent :: __construct($context);
        $manager = new hitobitoChannelManager();
        $this->channel = $manager->get(intval($this->getContext()->getRequest()->getParameter('category_id')));
    }
    public function getTitle()
    {
        return hitobito :: getSiteName().' : '.$this->channel->getTitle();
    }
    public function getLink()
    {
        return hitobito :: getUrl().'index.php?module=Category&amp;action=ShowMainCategory&amp;category_id='.$this->channel->getId();
    }
    public function getDescription()
    {
        return HNb :: tr('教えて？に答えるコミュニティ');
    }
    public function getCategoryName()
    {
        return sprintf(HNb :: tr('%s：新着ナビページ'), $this->channel->getTitle());
    }
    public function getCriteria($request)
    {
        $criteria = new HNbCriteria();
        $criteria->setWhere('nvp_open_flag=1 AND nvp_status=1');
        $criteria->addWhere('nvp_channel_id='.$this->channel->getId());
        $criteria->setOrder('ORDER BY nvp_open_date DESC');
        return $criteria;
    }
}
?>