<?
if (isset($_REQUEST['mypro'])) {
	$mypro = $_REQUEST['mypro'];
	$_SESSION['ss_mypro'] = $mypro;
} else
	$mypro = $_SESSION['ss_mypro'];

if ($mypro == 'my') {
	$class = 'myenquete';
	$section = '�ޡ������������ãͣ����󥱡��ȴ���';
} else {
	$class = 'proenquete';
	$section = '�У�異�󥱡��ȴ���';
}
