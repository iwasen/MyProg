<?php
/**
 * �ҤȤӤȎ�net �ѥȥ�����ڡ���
 *
 * �����եڡ���
 *
 *
 * @package
 * @author
 * @version
 */

session_start();
session_destroy();
header('location: login.php');
exit;
?>