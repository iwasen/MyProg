<?php

class GuardCloseNaviPageFilter extends Filter 
{

    // +-----------------------------------------------------------------------+
    // | METHODS                                                               |
    // +-----------------------------------------------------------------------+

    /**
     * Execute this filter.
     *
     * @param FilterChain The filter chain.
     *
     * @return void
     *
     * @throws <b>InitializeException</b> If an error occurs during view
     *                                    initialization.
     * @throws <b>ViewException</b>       If an error occurs while executing
     *                                    the view.
     *
     * @author Sean Kerr (skerr@mojavi.org)
     * @since  1.0.0
     */
    public function execute ($filterChain)
    {
            $context    = $this->getContext();
            $controller = $context->getController();
            $navipage = hitobito::getNaviPageInfo();
            if($navipage->getAttribute('nvp_open_flag') == 9){
            	$controller->redirect('index.php?module=Default&action=Error404');
            	die('close');
            }
            $filterChain->execute();
	}


}
?>