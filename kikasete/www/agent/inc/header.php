<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:������������ѥإå����եå�
'******************************************************/

header("Content-type: text/html; charset=EUC-JP");

define('SERVICE', 'agent');
$agent_id = $agent_main_id;

// �����Х��ѿ�����
function set_global($title, $back) {
	global $g_title, $g_back;

	$g_title = $title;
	$g_back = $back;
}
function set_global2($class, $section, $title, $back) {
	set_global($title, $back);
}

//menu���å����start
session_register('ss_marketer_menu','ss_proxy_menu', 'ss_project_menu');

// ���̥إå�
function page_header() {
	global $top;
?>
<table cellspacing=0 cellpadding=0 width="100%">
	<tr>
		<td width="138" height="60"><img src="<?=$top?>/images/logo_m.gif" border="0"></td>
		<td align="center" onclick="location.reload()">
			<SPAN style="width:100%; color: #10007A; font-size:large; font-weight:bold">�������ơ�net ����������ȥ��󥿡��ե�����</span>
		</td>
		<td width="10%" align="right" valign=bottom norap><?=back_link()?></td>
	</tr>
</table>
<hr class="header">
<?
}
function menu_script() {
	global $top;
?>
<script type="text/javascript">
<!--
function dispChange(id,name) {
	var f = document.form0;
	if(id.style.display == "block"){
		id.style.display = "none";
	} else {
		id.style.display = "block";
	}
	switch(name) {
	case "marketer":
		f.marketer_menu.value = id.style.display;
		break;
	case "proxy":
		f.proxy_menu.value = id.style.display;
		break;
	case "project":
		f.project_menu.value = id.style.display;
		break;
	}
}
function change_menu(url) {
	var f = document.form0;
	f.action = "<?=$top?>" + url;
	f.submit();
}
//-->
</script>
<?
}
//��˥塼
function menu_start() {
	global $top;
	global $marketer_menu, $proxy_menu, $project_menu,$sy_ag_inq_to;
	if (!isset($_SESSION['ss_marketer_menu'])) {
		$_SESSION['ss_marketer_menu'] = "none";
		$_SESSION['ss_proxy_menu'] = "none";
		$_SESSION['ss_project_menu'] = "none";
	}

	if ($marketer_menu == "" && $proxy_menu == "" && $project_menu == "") {
		$marketer_menu = $_SESSION['ss_marketer_menu'];
		$proxy_menu = $_SESSION['ss_proxy_menu'];
		$project_menu = $_SESSION['ss_project_menu'];
	} else {
		$_SESSION['ss_marketer_menu'] = $marketer_menu;
		$_SESSION['ss_proxy_menu'] = $proxy_menu;
		$_SESSION['ss_project_menu'] = $project_menu;
	}
?>
<table border=0 cellspacing=0 cellpadding=1>
<tr>
<td valign="top" align="left" width=10%>
<form method="post" name="form0">

<input type="hidden" name="marketer_menu" <?=value($marketer_menu)?>>
<input type="hidden" name="proxy_menu" <?=value($proxy_menu)?>>
<input type="hidden" name="project_menu" <?=value($project_menu)?>>

<input type="hidden" name="pset" value=1>
<table border=0 cellspacing=8 cellpadding=0 width=100% bgcolor="#FFFFCC">
	<tr>
		<td width=13><img src="<?=$top?>/images/orangepin.gif" width=13 height=13 alt=""></td>
		<td colspan=2 class="menutitle"><a href="JavaScript:change_menu('/mypage.php')"><nobr>TOP PAGE</nobr></a></td>
	</tr>
	<tr><td colspan=3><hr size="2"></td></tr>
	<tr>
		<td><img src="<?=$top?>/images/orangepin.gif" width=13 height=13 alt=""></td>
		<td colspan=2 class="menutitle"><a href="JavaScript:change_menu('/message/list.php')"><nobr>��̳�ɤ���Τ��Τ餻</nobr></a></td>
	</tr>
	<tr><td colspan=3><hr size="2"></td></tr>
	<tr>
		<td><img src="<?=$top?>/images/orangepin.gif" width=13 height=13 alt=""></td>
		<td colspan=2 class="menutitle"><a href="JavaScript:dispChange(marketer_M,'marketer')"><nobr>ô���ޡ�������</nobr></a></td>
	</tr>
</table>
<div ID="marketer_M" style="display:<?=$_SESSION['ss_marketer_menu']?>">
<table border=0 cellspacing=8 cellpadding=0 width=100% bgcolor="#FFFFCC">
	<tr>
		<td width=13></td>
		<td width=10><img src="<?=$top?>/images/orangetile.gif" width="10" height="10" alt=""></td>
		<td class="menusub"><a href="JavaScript:change_menu('/marketer/list/list.php')"><nobr>����ɽ��</nobr></a></td>
	</tr>
	<tr>
		<td></td>
		<td><img src="<?=$top?>/images/orangetile.gif" width="10" height="10" alt=""></td>
		<td class="menusub"><a href="JavaScript:change_menu('/marketer/getmsg/list.php')"><nobr>������å�����</nobr></a></td>
	</tr>
	<tr>
		<td></td>
		<td><img src="<?=$top?>/images/orangetile.gif" width="10" height="10" alt=""></td>
		<td class="menusub"><a href="JavaScript:change_menu('/marketer/sendmsg/list.php')"><nobr>������å�����</nobr></a></td>
	</tr>
</table>
</div>
<table border=0 cellspacing=8 cellpadding=0 width=100% bgcolor="#FFFFCC">
	<tr><td colspan=3><hr size="2"></td></tr>
	<tr>
		<td width=13><img src="<?=$top?>/images/orangepin.gif" width=13 height=13 alt=""></td>
		<td colspan=2 class="menutitle"><a href="JavaScript:change_menu('/search/condition.php')"><nobr>��˥�������</nobr></a></td>
	</tr>
	<tr>
		<td><img src="<?=$top?>/images/orangepin.gif" width=13 height=13 alt=""></td>
		<td colspan=2 class="menutitle"><a href="pdf/kikasete_monitor_profile_my_200906.pdf" target="_blank"><nobr>��˥����ץ�ե�����</nobr></a></td>
	</tr>
	<tr><td colspan=3><hr size="2"></td></tr>
	<tr>
		<td><img src="<?=$top?>/images/orangepin.gif" width=13 height=13 alt=""></td>
		<td colspan=2 class="menutitle"><a href="JavaScript:dispChange(proxy_menu_M,'proxy')"><nobr>�����»�</nobr></a></td>
	</tr>
</table>
<div ID="proxy_menu_M" style="display:<?=$_SESSION['ss_proxy_menu']?>">
<table border=0 cellspacing=8 cellpadding=0 width=100% bgcolor="#FFFFCC">
	
	<tr>
		<td width=13></td>
		<td><img src="<?=$top?>/images/orangetile.gif" width="10" height="10" alt=""></td>
		<td class="menusub"><a href="JavaScript:change_menu('/proxy/myenquete.php')"><nobr>My���󥱡���</nobr></a></td>

	<tr>
		<td></td>
		<td><img src="<?=$top?>/images/orangetile.gif" width="10" height="10" alt=""></td>
		<td class="menusub"><a href="JavaScript:change_menu('/mypartner/pjt/pjt_list.php')"><nobr>My�ѡ��ȥʡ�</nobr></a></td>
	</tr>
	<tr>
		<td></td>
		<td><img src="<?=$top?>/images/orangetile.gif" width="10" height="10" alt=""></td>
		<td class="menusub"><a href="JavaScript:change_menu('/proxy/mysearch.php')"><nobr>My������</nobr></a></td>
	</tr>
	</tr>
<?
// kim_start Pro���󥱡���Button �ɲ�
?>
	<tr>
		<td width=13></td>
		<td><img src="<?=$top?>/images/orangetile.gif" width="10" height="10" alt=""></td>
		<td class="menusub"><a href="JavaScript:change_menu('/proxy/proenquete.php')"><nobr>Pro���󥱡���</nobr></a></td>
	</tr>
<?
//kim_end
?>
</table>
</div>
<table border=0 cellspacing=8 cellpadding=0 width=100% bgcolor="#FFFFCC">
	<tr><td colspan=3><hr size="2"></td></tr>
	<tr>
		<td width=13><img src="<?=$top?>/images/orangepin.gif" width=13 height=13 alt=""></td>
		<td colspan=2 class="menutitle"><a href="JavaScript:dispChange(project_menu_M,'project')"><nobr>�ץ������Ȥ�����</nobr></a></td>
	</tr>
</table>
<div ID="project_menu_M" style="display:<?=$_SESSION['ss_project_menu']?>">
<table border=0 cellspacing=8 cellpadding=0 width=100% bgcolor="#FFFFCC">
	<tr>
		<td width=13></td>
		<td><img src="<?=$top?>/images/orangetile.gif" width="10" height="10" alt=""></td>
		<td class="menusub"><a href="http://www.kikasete.net/agent/EXmail/kikasetemp.html" target="_blank"><nobr>�Уʣԥ᡼�����</nobr></a></td>
	</tr>
	<tr>
		<td width=13></td>
		<td><img src="<?=$top?>/images/orangetile.gif" width="10" height="10" alt=""></td>
		<td class="menusub"><a href="JavaScript:change_menu('/project/list.php')"><nobr>�ץ������ȴ���</nobr></a></td>
	</tr>
	<tr>
		<td></td>
		<td><img src="<?=$top?>/images/orangetile.gif" width="10" height="10" alt=""></td>
		<td class="menusub"><a href="JavaScript:change_menu('/project/client/list.php')"><nobr>��ȡ�ô������Ͽ</nobr></a></td>
	</tr>
	<tr>
		<td></td>
		<td><img src="<?=$top?>/images/orangetile.gif" width="10" height="10" alt=""></td>
		<td class="menusub"><a href="JavaScript:change_menu('/project/schedule/index.php')"><nobr>�������塼��</nobr></a></td>
	</tr>
</table>
</div>
<table border=0 cellspacing=8 cellpadding=0 width=100% bgcolor="#FFFFCC">
	<tr><td colspan=3><hr size="2"></td></tr>
	<tr>
		<td width=13><img src="<?=$top?>/images/orangepin.gif" width=13 height=13 alt=""></td>
		<td colspan=2 class="menutitle"><a href="JavaScript:change_menu('/update/edit.php')"><nobr>��Ͽ���󹹿�</nobr></a></td>
	</tr>
<?
	if ($_SESSION['agent_type'] == 1) {
?>
	<tr>
		<td width=13><img src="<?=$top?>/images/orangepin.gif" width=13 height=13 alt=""></td>
		<td colspan=2 class="menutitle"><a href="JavaScript:change_menu('/subagent/list.php')"><nobr>���֥����������</nobr></a></td>
	</tr>
<?
}
?>
	<tr>
		<td><img src="<?=$top?>/images/orangepin.gif" width=13 height=13 alt=""></td>
		<td colspan=2 class="menutitle"><a href="JavaScript:change_menu('/proxy_regist/new.php')"><nobr>�ޡ�������������Ͽ</nobr></a></td>
	</tr>
	<tr>
		<td><img src="<?=$top?>/images/orangepin.gif" width=13 height=13 alt=""></td>
		<td colspan=2 class="menutitle"><a href="mailto:<?=$_SESSION['sy_ag_inq_to']?>"><nobr>�����碌</nobr></a></td>
	</tr>
	<tr>
		<td><img src="<?=$top?>/images/orangepin.gif" width=13 height=13 alt=""></td>
		<td colspan=2 class="menutitle"><a href="JavaScript:change_menu('/taikai/taikai1.php')"><nobr>���</nobr></a></td>
	</tr>
	<tr><td colspan=3><hr size="2"></td></tr>
	<tr>
		<td><img src="<?=$top?>/images/orangepin.gif" width=13 height=13 alt=""></td>
		<td colspan=2 class="menutitle"><a href="pdf/manual.pdf" target="_blank"><nobr>�ޥ˥奢����������</nobr></a></td>
	</tr>
	
</TABLE>
</form>
</td>
<td valign="top" style="padding-left:10" width=90%>
<?
}

function menu_end() {
?>
</td>
</tr>
</table>
<?
}

// ���̥եå�
function page_footer() {
	global $agent_login_id,$agent_mail_addr;

	if ($agent_login_id)
		$login = "Login: $agent_mail_addr";
?>
<hr class="footer">
<table cellspacing=0 cellpadding=0 width="100%">
	<tr>
		<td align="left" style="font-size:x-small;font-weight:bold;color:#666666"><?=$login?></td>
		<td align="right" valign=bottom norap><?=back_link()?></td>
	</tr>
</table>
<div align="center">Copyright(c) <?=date(Y)?> xx xxxxx Inc. All rights reserved.</div>
<?
}

// �����
function back_link() {
	global $g_back, $top;

	switch ($g_back) {
	case BACK_NONE:
		$back = '<font color="white">ɽ������</font>';
		break;
	case BACK_LOGOFF:
		$back = '<small><font color=blue>��</font><a href="login/logoff.php">������</a></small>';
		break;
	case BACK_TOP:
		$back = "<small><font color=blue>��</font><a href=\"$top/mypage.php\">�ȥåפ����</a></small>";
		break;
	case BACK_SUB:
		$back = "<small><font color=blue>��</font><a href=\"$top/project/template/submenu.php\">���֥�˥塼�����</a></small>";
		break;
	case BACK_CLOSE:
		$back = "<small><font color=blue>��</font><a href=\"javascript:window.close()\">�Ĥ���</a></small>";
		break;
	}

	return $back;
}

// �ơ��֥�β���
function width($w) {
	echo '100%';
}
?>