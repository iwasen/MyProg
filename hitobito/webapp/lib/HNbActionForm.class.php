<?php


/**
 * @package	HNb
 * @author Ryuji
 * @version $Id: HNbActionForm.class.php,v 1.7 2006/05/13 04:50:15 ryu Exp $
 * 
 */
require MO_WEBAPP_DIR.'/lib/HNbException.class.php';
define("HNB_ACTIONFORM_INIT_FAIL", '__error__actionform_init_fail__');
define("HNB_ACTIONFORM_INIT_SUCCESS", '__actionform_init_success__');
define("HNB_ACTIONFORM_POST_SUCCESS", '__actionform_post_success__');

/**
@brief GET/POST ��ư��˱����Ƶ�ư���ѹ����륢�������ե�����
���Υ��������ե�����ϡ� GET �ꥯ�����Ȥκݤ� load �᥽�åɤ�POST �ꥯ�����Ȥκݤ� fetch �᥽�åɤ�
�¹Ԥ��ޤ���
*/
class HNbActionForm
{

    protected $msg_ = array ();
    protected $attributes = array ();

    /**
    @brief ���Υ��������ե������ꥯ�����Ȥ˱����Ƽ¹Ԥ��롣
    \par ActionForm �� POST �ꥯ�����Ȼ��Τߤ� fetch ��Ԥ���
    \par GET �ꥯ�����Ȥκݤˤ� load ��Ԥ���
    @param $master fetch ����� load �ؤΰ���
    @param $request Request ���󥹥���
    @return POST �ꥯ�����Ȼ���fetch �����꤬ȯ������� HNB_ACTIONFORM_INIT_FAIL ��
    		���꤬ȯ�����ʤ���� TRUE ���֤��ޤ�
    		\par GET �ꥯ�����Ȼ���load �����꤬ȯ������� ACTIONFORM_INIT_FAIL ��
    		���꤬ȯ�����ʤ���� ACTIONFORM_INIT_FAIL ���֤��ޤ�
    */
    public function init(& $master, & $request)
    {
        $this->err_render_ = new HNbFormErrorRender();

        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $this->fetch($master, $request);
            return count($this->msg_) ? HNB_ACTIONFORM_INIT_FAIL : HNB_ACTIONFORM_POST_SUCCESS;
        } else
        {
            $this->load($master, $request);
            return count($this->msg_) ? HNB_ACTIONFORM_INIT_FAIL : HNB_ACTIONFORM_INIT_SUCCESS;
        }
    }

    public function fetch(& $master, $request)
    {
        $this->load($master, $request);
    }

    public function load(& $master, & $request)
    {
    }

    public function update($master, $request)
    {

    }

    public function getErrors()
    {
        return $this->msg_;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    protected function addError($message)
    {
        $this->msg_[] = $message;
    }

    /**
    ���顼��å�������ʸ������֤��ޤ���
    @return string
    */
    public function getHtmlErrors()
    {
        $this->err_render_->init($this);
        return $this->err_render_->render();
    }
    public function hasError()
    {
    	if(count($this->msg_) > 0){
    		return TRUE;
    	}else{
    		return FALSE;
    	}
    }
    /**
     * ����ɬ�ܥ����å�
     * @param array $params �ѥ�᡼��̾���������ͤ˥��顼��å��������Ȥ���Ϣ������
     */
    protected function checkRequireParameter($require_params)
    {
        foreach ($require_params as $key => $message)
        {
            if (empty ($this-> $key))
            {
                $this->addError($message);
            }
        }
    }

    protected function fetchStringParameter($request, $strParamsKey)
    {
        foreach ($strParamsKey as $key)
        {
            $this-> $key = trim($request->getParameter($key, ''));
        }
    }
    protected function fetchIntegerParameter($request, $intParamsKey)
    {
        foreach ($intParamsKey as $key)
        {
            $this-> $key = intval($request->getParameter($key, 0));
        }
    }
}

abstract class HNbSemiAutoActionForm extends HNbActionForm
{
    /**
     * ʸ����ѥ�᡼��������������֤�
     */
    abstract protected function getStringParameterKeys();
    /**
     * �����ѥ�᡼��������������֤�
     */
    abstract protected function getIntegerParameterKeys();
    /**
     * ����ɬ�ܥѥ�᡼���򥭡����ͤ˥��顼�᥻�á��������줿Ϣ��������֤�
     * @example return array('email' => '�᡼�륢�ɥ쥹�����Ϥ���Ƥ��ޤ���');
     */
    abstract protected function getRequireParameterKeys();

    public function fetch($master, $request)
    {
        parent :: fetch($master, $request);
        $this->fetchStringParameter($request, $this->getStringParameterKeys());
        $this->fetchIntegerParameter($request, $this->getIntegerParameterKeys());
        $this->checkRequireParameter($this->getRequireParameterKeys());
    }
}

class HNbFormErrorRender
{
    var $form_ = null;

    public function init($form)
    {
        $this->form_ = $form;
    }

    public function render()
    {
        $ret = "<ul>";
        foreach ($this->form_->getErrors() as $m)
        {
            $ret .= @ sprintf("<li style='color:#FF0000;'>%s</li>\n", $m);
        }
        $ret .= "</ul>";
        return $ret;
    }
}

abstract class HNbEditAction extends Action
{
    protected $request; // request class
    protected $user; // user class

    public function initialize($context)
    {
    	parent::initialize($context);
        $this->request = $context->getRequest();
        $this->user = $context->getUser();

        return TRUE;
    }

    /**
     * ���������ե�����Υ��󥹥��󥹤��֤�
     * @example return new BlogCategoryForm(); 
     */
    abstract protected function getActionForm();

    /**
     * DB�ȤΤ��Ȥ�򤹤�ޥ͡����㥤�󥹥��󥹤��֤�
     * @example return new BlogCategoryManager();
     */
    abstract protected function getManager();

    /**
     * �Խ����륪�֥������Ȥ�ID���֤�
     * @example return $this->request->getParameter('blog_category_id', 0);
     */
    abstract protected function fetchId();

    /**
     * �����������Υ��֥������Ȥν�������Ԥ�
     * @example $obj->setBlog_id($this->blog_id); 
     */
    protected function setUpNewObject($obj)
    {
    }

    /**
     * �Խ����¤������TRUE���֤�
     */
    protected function isEdit($obj)
    {
        return TRUE;
    }

    /**
     * ��ǧ���̤�ɬ�פǤ����TRUE���֤��褦�ˤ���
     * 
     */
    protected function isConfirm()
    {
        return FALSE;
    }

    public function execute()
    {
        try
        {
            $this->manager = $this->getManager();
            if ($this->isConfirm() && $this->request->hasParameter('save_execute'))
            {
                if ($this->save())
                {
                    return View :: SUCCESS;
                } else
                {
                    return View :: ERROR;
                }

            }
            $editform = $this->getActionForm();

            $id = $this->fetchId();

            $master = $this->manager->get($id);
            if ($master->isNew())
            {
                $this->setUpNewObject($master);
            }

            if (!$this->isEdit($master))
            {
                return View :: ERROR;
            }
            if ($editform->init($master, $this->request) === HNB_ACTIONFORM_POST_SUCCESS)
            {
                // update
                $editform->update($master, $this->request);

                $this->request->setAttribute('editform', $editform);
                $this->request->setAttribute('obj', $master);

                if ($this->isConfirm())
                {
                    $values = $master->getAttributes();
                    $this->user->setAttribute('objectAttributes', $values, 'HNbEditAction');
                    return View :: ALERT; // confirm����
                }

                $result = $this->insert($master);
                if ($result == TRUE)
                {
                    return View :: SUCCESS;
                } else
                {
                    return View :: ERROR;
                }
            } else
            {
                $this->request->setAttribute('editform', $editform);
                $this->request->setAttribute('obj', $master);
                return View :: INPUT;
            }
        } catch (HNbException $e)
        {
            if ($e->hasForward())
            {
                $this->getContext()->getController()->forward($e->getForwardModule(), $e->getForwardAction());
                return View :: NONE;
            }
            if($e->hasReturnView()){
            	return $e->getReturnView();
            }
        }
    }

    protected function save()
    {
        $values = $this->user->getAttribute('objectAttributes', 'HNbEditAction');
        $master = $this->manager->create();
        $master->setAttributes($values);
        $result = $this->insert($master);
        $this->user->removeAttribute('objectAttributes', 'HNbEditAction');
        $this->request->setAttribute('EditActionMaster', $master);
        return $result;
    }
    
    /**
     * ��¸����˽������ɲä������Ȥ��Ϥ��Υ᥽�åɤ��񤭤��ƽ������ɲä���
     * 
     * ex)
     * protected function insert($master)
     * {
     *  if(prent::insert($master)){
     *   $email = $master->getEmail();
     *   return mb_send_mail($email.....);
     *  }
     *  return FALSE;
     * }
     * 
     */
    protected function insert($master)
    {
    	return $this->manager->insert($master);
    }

}
?>