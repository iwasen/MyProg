<?php
/**
* �ʥӥڡ����ǻȤ���⥸�塼��ζ��̥��󥿡��ե�����
*  
* @author Ryuji
* @version $Id: NaviPageModuleInterface.class.php,v 1.1 2006/02/17 18:06:59 ryu Exp $
*/

interface NaviPageModuleInterface
{
    /**
    * �ʥӤ����Τ�ɬ�פʥ�å��������֤�
    *
    * @return   array ��å�������������Ѥ���֤�
    */
    public function getNotify2Navi($navipageId);
}

?>