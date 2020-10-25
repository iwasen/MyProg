<?php
/** 
 * @package	HNb
 * @author Ryuji
 * @version $Id: HNbWebController.class.php,v 1.5 2006/02/22 07:57:27 ryu Exp $
 */

class HNbWebController extends FrontWebController
{
	
	private function getShortViewFileName($moduleName, $orgViewName)
	{
		if(preg_match('/([_a-zA-Z0-9]+)[A-Z][a-z]+$/', $orgViewName, $reg))
		{
	        $file = MO_MODULE_DIR . '/' . $moduleName . '/views/' . $reg[1] .
                'View.class.php';
		}else{
			// ありえない？
	        $file = MO_MODULE_DIR . '/' . $moduleName . '/views/' . $orgViewName .
                'View.class.php';
		}
		return $file;
		
	}

    /**
     * Indicates whether or not a module has a specific view.
     *
     * @param string A module name.
     * @param string A view name.
     *
     * @return bool true, if the view exists, otherwise false.
     *
     * @author Sean Kerr (skerr@mojavi.org)
     * @since  3.0.0
     */
    public function viewExists ($moduleName, $viewName)
    {
		$shortFile = $this->getShortViewFileName($moduleName, $viewName);
		if($result = is_readable($shortFile)){
			return $result;
		}
        $file = MO_MODULE_DIR . '/' . $moduleName . '/views/' . $viewName .
                'View.class.php';

        return is_readable($file);

    }

    /**
     * Retrieve a View implementation instance.
     *
     * @param string A module name.
     * @param string A view name.
     *
     * @return View A View implementation instance, if the model exists,
     *              otherwise null.
     *
     * @author Sean Kerr (skerr@mojavi.org)
     * @since  3.0.0
     */
    public function getView ($moduleName, $viewName)
    {
		$shortFile = $this->getShortViewFileName($moduleName, $viewName);
		if($result = is_readable($shortFile)){
			$file = $shortFile;
		}else{
			$file = MO_MODULE_DIR . '/' . $moduleName . '/views/' . $viewName .
                'View.class.php';
		}
        require_once($file);

        $position = strrpos($viewName, '/');

        if ($position > -1)
        {

            $viewName = substr($viewName, $position + 1);

        }

        $class = $viewName . 'View';

        // fix for same name classes
        $moduleClass = $moduleName . '_' . $class;

        if (class_exists($moduleClass, false))
        {

            $class = $moduleClass;

        }

        return new $class();

    }
    
}

class HNbLocalDebugWebController extends HNbWebController
{
    /**
    * adodbの例外をキャッチできるようにした
    */
    public function dispatch ()
    {

        try
        {

            // initialize the controller
            $this->initialize();

            // get the application context
            $context = $this->getContext();

            // determine our module and action
            $moduleName = $context->getRequest()
                                  ->getParameter(MO_MODULE_ACCESSOR);

            $actionName = $context->getRequest()
                                  ->getParameter(MO_ACTION_ACCESSOR);

            if ($moduleName == null)
            {

                // no module has been specified
                $moduleName = MO_DEFAULT_MODULE;

            }

            if ($actionName == null)
            {

                // no action has been specified
                if ($this->actionExists($moduleName, 'Index'))
                {

                    // an Index action exists
                    $actionName = 'Index';

                } else
                {

                    // use the default action
                    $actionName = MO_DEFAULT_ACTION;

                }

            }

            // make the first request
            $this->forward($moduleName, $actionName);

        } catch (MojaviException $e)
        {

            $e->printStackTrace();

		} catch(ADODB_Exception $e)
		{
			adodb_backtrace($e->gettrace());
            $e = new MojaviException($e->getMessage());

            $e->printStackTrace();
        } catch (Exception $e)
        {

            // most likely an exception from a third-party library
            $e = new MojaviException($e->getMessage());

            $e->printStackTrace();

        }

    }

}
?>