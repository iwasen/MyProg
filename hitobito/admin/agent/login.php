<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * ������ID/Pass���ϥڡ���
 *
 *
 * @package
 * @author
 * @version
 */

$top = '..';
$inc = "$top/../inc";
require_once("$inc/header.php");
require_once("$inc/common.php");

$header = new header_class('�ҤȤӤȡ�net ����������ȥ�����', BACK_NONE);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta http-equiv="Content-Style-Type" content="text/css">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/hitobito.css">
<script type="text/javascript">
<!--
function onLoad_body() {
  document.form1.login_id.focus();
}
function onSubmit_form1(form) {
  if (form.login_id.value == "") {
    alert("������ɣĤ����Ϥ��Ƥ���������");
    form.login_id.focus();
    return false;
  }
  if (form.password.value == "") {
    alert("�ѥ���ɤ����Ϥ��Ƥ���������");
    form.password.focus();
    return false;
  }
  return true;
}
//-->
</script>
</head>
<body onload="onLoad_body()">

<?php $header->page_header() ?>

<div align="center">
<form method="post" name="form1" action="login_check.php" onSubmit="return onSubmit_form1(this)">
	<table border=0 cellspacing=1 cellpadding=0 width='600'>
	  <tr>
	    <td>��������ID�ȥѥ���ɤ����Ϥ��Ƥ�������</td>
	  </tr>
	  <tr>
	    <td>
	      <table border=0 cellspacing=2 cellpadding=2 width="100%">
	        <tr>
	          <td class="face">������ID</td>
	          <td class="bg"><input class="alpha" type="text" name="login_id" size=30 value=""></td>
	        </tr>
	        <tr>
	          <td class="face">�ѥ����</td>
	          <td class="bg"><input type="password" name="password" size=30 maxlength=20></td>
	        </tr>
	      </table>
	    </td>
	  </tr>
	</table>
	<span class="note">�ܥ����ƥ�� Internet Explorer 5.0 �ʾ�Ǥ����Ѥ�����������Netscape�Ǥ�ư��ޤ����</span>
  <br><br>
  <input type="submit" value=" ������ ">
</form>
</div>

<?php $header->page_footer() ?>

</body>
</html>
