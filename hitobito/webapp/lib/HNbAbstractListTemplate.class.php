<?php
/**
 * リスト表示用 Template Method pattern class
 * 
 * @package HNb
 * @author Ryuji
 * @version $Id: HNbAbstractListTemplate.class.php,v 1.1 2006/01/11 06:27:21 ryu Exp $
 */
 
require_once MO_WEBAPP_DIR.'/hitobito/hitobitoPageNavigation.class.php';
 
abstract class HNbAbstractListTemplate
{
	private $defaultLimit = 10;
	private $context;
	public function __construct($context)
	{
		$this->context = $context;
	}
	protected function getContext()
	{
		return $this->context;
	}
	public function execute()
	{
		$request = $this->getContext()->getRequest();
        
        $filter = $this->getFilter();
        $start = intval($request->getParameter($this->getStartParameterName()));
        $limit = $this->getLimit();
        
        $filter->fetch($this->getContext());
        $criteria = $filter->getCriteria();
        $criteria->setLimit($limit);
        $criteria->setStart($start);
        
        $total = $this->getCount($criteria);
        $request->setAttribute('total', $total);
        
        $itemlist = $this->getItemList($criteria);        
        $request->setAttribute('itemlist', $itemlist);
        
        $request->setAttribute('start_num', $start + 1);
        $request->setAttribute('end_num', $start + count($itemlist));
        
        $navigation = new hitobitoPageNavigation($total, $limit, $start, $this->getStartParameterName());
        $navigation->addParameter('module', $this->getContext()->getModuleName());
        $navigation->addParameter('action', $this->getContext()->getActionName());
        $navigation->addParameters($filter->getExtraParameters());
        $request->setAttribute('navigation', $navigation);
	}
	protected function getStartParameterName()
	{
		$name = $this->getContext()->getModuleName() . '_start';
		return $name;
	}
	protected function getLimit()
	{
		return $this->defaultLimit;
	}
	
	abstract protected function getFilter();
	abstract protected function getCount($criteria);
	abstract protected function getItemList($criteria);
}

abstract class HNbAbstractListUseManagerTemplate extends HNbAbstractListTemplate
{
	abstract protected function getManager();
//	abstract protected function getFilter();
	protected function getCount($criteria)
	{
		return $this->getManager()->getCount($criteria);
	}
	protected function getItemList($criteria)
	{
		return $this->getManager()->getObjects($criteria);
	}
	
}
?>