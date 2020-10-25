<?
if (isset($_REQUEST['mypro'])) {
	$mypro = $_REQUEST['mypro'];
	$_SESSION['ss_mypro'] = $mypro;
} else
	$mypro = $_SESSION['ss_mypro'];

if ($mypro == 'my') {
	$class = 'myenquete';
	$section = 'マーケター管理｜Ｍｙアンケート管理';
} else {
	$class = 'proenquete';
	$section = 'Ｐｒｏアンケート管理';
}
