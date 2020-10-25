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
@brief GET/POST の動作に応じて挙動を変更するアクションフォーム
このアクションフォームは、 GET リクエストの際は load メソッドを、POST リクエストの際は fetch メソッドを
実行します。
*/
class HNbActionForm
{

    protected $msg_ = array ();
    protected $attributes = array ();

    /**
    @brief このアクションフォームをリクエストに応じて実行する。
    \par ActionForm は POST リクエスト時のみに fetch を行う。
    \par GET リクエストの際には load を行う。
    @param $master fetch および load への引数
    @param $request Request インスタンス
    @return POST リクエスト時、fetch で問題が発生すれば HNB_ACTIONFORM_INIT_FAIL を
    		問題が発生しなければ TRUE を返します
    		\par GET リクエスト時、load で問題が発生すれば ACTIONFORM_INIT_FAIL を
    		問題が発生しなければ ACTIONFORM_INIT_FAIL を返します
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
    エラーメッセージを文字列で返します。
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
     * 入力必須チェック
     * @param array $params パラメータ名がキー、値にエラーメッセージ　とした連想配列
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
     * 文字列パラメータキーの配列を返す
     */
    abstract protected function getStringParameterKeys();
    /**
     * 整数パラメータキーの配列を返す
     */
    abstract protected function getIntegerParameterKeys();
    /**
     * 入力必須パラメータをキー、値にエラーメセッージを入れた連想配列を返す
     * @example return array('email' => 'メールアドレスが入力されていません');
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
     * アクションフォームのインスタンスを返す
     * @example return new BlogCategoryForm(); 
     */
    abstract protected function getActionForm();

    /**
     * DBとのやりとりをするマネージャインスタンスを返す
     * @example return new BlogCategoryManager();
     */
    abstract protected function getManager();

    /**
     * 編集するオブジェクトのIDを返す
     * @example return $this->request->getParameter('blog_category_id', 0);
     */
    abstract protected function fetchId();

    /**
     * 新規作成時のオブジェクトの初期設定を行う
     * @example $obj->setBlog_id($this->blog_id); 
     */
    protected function setUpNewObject($obj)
    {
    }

    /**
     * 編集権限があればTRUEを返す
     */
    protected function isEdit($obj)
    {
        return TRUE;
    }

    /**
     * 確認画面が必要であればTRUEを返すようにする
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
                    return View :: ALERT; // confirm画面
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
     * 保存前後に処理を追加したいときはこのメソッドを上書きして処理を追加する
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