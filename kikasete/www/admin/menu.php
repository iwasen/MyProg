<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:�ȥåץ�˥塼����
'******************************************************/

$top = '.';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");

set_global('kikasete', '', '�������ơ�net ��̳�ɥ�˥塼', BACK_LOGOFF);

// ���������
set_return_page('active.php');
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body>

<? page_header() ?>
<table align="center" border=0 cellpadding=8 cellspacing=0 width="90%">
	<tr>
		<td>
			<table width="100%" <?=LIST_TABLE?>>
				<tr><th class="monitor" colspan=3 align="left">����˥�������</th></tr>
				<tr>
					<td width="33%"><a href="monitor/personal/find.php">��˥����Ŀ͸���</a></td>
					<td width="33%"><a href="monitor/profile/show.php">��˥����ץ�ե�����</a></td>
					<td width="33%"><a href="monitor/search/condition.php">��˥���������</a></td>
				</tr>
				<tr>
					<td><a href="monitor/news/list.php">�᡼��˥塼���ۿ�</a></td>
					<td><a href="monitor/regist/show.php">�����Խ���</a></td>
					<td><a href="monitor/promotion/list.php">�ץ�⡼��������</a></td>
				</tr>
				<tr>
					<td><a href="monitor/undeliver/list.php">��ã�᡼�����</a></td>
					<td><a href="monitor/blacklist/list.php">�֥�å��ꥹ�ȴ���</a></td>
					<td><a href="monitor/enquete/list.php">�����ͥ��󥱡��ȴ���</a></td>
				</tr>
				<tr>
					<td><a href="monitor/point/menu.php">�ݥ����������</a></td>
					<td><a href="monitor/affiliate/list.php">���ե��ꥨ���ȴ���</a></td>
					<td><a href="monitor/ma_profile/list.php">�ץ�ե������ư�ɲ�</a></td>
				</tr>
				<tr>
					<td><a href="monitor/birthday/list.php">�������᡼���ۿ�</a></td>
					<td></td>
					<td></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" <?=LIST_TABLE?>>
				<tr><th class="marketer" colspan=3 align="left">���ޡ�����������</a></th></tr>
				<tr>
					<td width="33%"><a href="marketer/personal/find.php">�ޡ��������Ŀ͸���</a></td>
					<td width="33%"><a href="marketer/login/list.php">����������</a></td>
					<td width="33%"><a href="marketer/ag_change/list.php">������������ѹ�</a></td>
				</tr>
				<tr>
					<td><a href="marketer/news/list.php">�᡼��˥塼���ۿ�</a></td>
					<td><a href="marketer/regist/show.php">�����Խ���</a></td>
					<td><a href="marketer/mailaddr/list.php">�᡼�륢�ɥ쥹ȯ��</a></td>
				</tr>
				<tr>
          <td><a href="marketer/undeliver/list.php">��ã�᡼�����</a></td>
          <td><a href="marketer/proxy_regist/new.php">ͭ���ޡ�������������Ͽ</a></td>
					<td><a href="marketer/takeover/edit.php">�����ӥ��ΰ��Ѥ�</a></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" <?=LIST_TABLE?>>
				<tr><th class="myenquete" colspan=3 align="left">��My���󥱡��ȴ���</a></th></tr>
				<tr>
					<td width="33%"><a href="marketer/myenq/list.php">My���󥱡���</a></td>
					<td width="33%"><a href="marketer/myenq/send_sum/index.php">ȯ��������</a></td>
					<td width="33%"><a href="marketer/panel/category_list.php">���ڥ����ѥͥ����</a></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" <?=LIST_TABLE?>>
				<tr><th class="proenquete" colspan=3 align="left">��PRO���󥱡��ȴ���</a></th></tr>
				<tr>
					<td width="33%"><a href="proenq/manage/list.php">Pro���󥱡���</a></td>
					<td width="33%"><a href="proenq/price/list.php">����ɽ����</a></td>
					<td width="33%"><a href="proenq/send_sum/index.php">ȯ��������</a></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" <?=LIST_TABLE?>>
				<tr><th class="mypartner" colspan=3 align="left">��My�ѡ��ȥʡ�����</th></tr>
				<tr>
					<td width="33%"><a href="mypartner/pjt/pjt_list.php">My�ѡ��ȥʡ�����</a></td>
					<td width="33%"><a href="mypartner/pjt/active.php">My�ѡ��ȥʡ��ʹԾ���</a></td>
					<td width="33%"><a href="mypartner/approval/list.php">My�ѡ��ȥʡ���ǧ����</a></td>
				</tr>
				<tr>
					<td width="33%"><a href="mypartner/pjt/search.php">My�ѡ��ȥʡ��������</a></td>
					<td width="33%"><a href="mypartner/master/list.php">�ɲ�ȯ��Ƚ�ǥޥ�������</a></td>
					<td width="33%"><a href="mypartner/send_sum/index.php">ȯ��������</a></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" <?=LIST_TABLE?>>
				<tr><th class="agent" colspan=3 align="left">������������ȴ���</th></tr>
				<tr>
					<td width="33%"><a href="agent/personal/find.php">����������ȸĿ͸���</a></td>
					<td width="33%"><a href="agent/login/list.php">����������</a></td>
					<td width="33%"><a href="agent/alert/list.php">���顼�ȥ᡼��</a></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" <?=LIST_TABLE?>>
				<tr><th class="project" colspan=3 align="left">���ץ������ȴ���</th></tr>
				<tr>
					<td width="33%"><a href="project/list2.php">�ץ������ȿʹԾ���</a></td>
					<td width="33%"><a href="project/list.php">�ץ������ȴ���</a></td>
					<td width="33%"><a href="project/client/list.php">��ȡ�ô������Ͽ</a></td>
				</tr>
				<tr>
					<td width="33%"><a href="project/approval/list.php">����־�ǧ����</a></td>
					<td width="33%"><a href="project/schedule/index.php">�������塼��</a></td>
					<td><a href="project/send_sum/index.php">ȯ��������</a></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" <?=LIST_TABLE?>>
				<tr><th class="etc" colspan=3 align="left">������¾����</th></tr>
					<td width="33%"><a href="etc/point/list.php ">�ݥ��������</a></td>
					<td width="33%"><a href="etc/send_all/list.php">���ۿ�</a></td>
					<td width="33%"><a href="etc/comm/list.php">��å���������������</a></td>
				</tr>
				<tr>
					<td><a href="etc/message/list.php">��̳�ɤ���Τ��Τ餻</a></td>
					<td><a href="etc/master/menu.php">�ޥ������ƥʥ�</a></td>
					<td><a href="etc/batch_log/list.php">�Хå���</a></td>
				</tr>
				<tr>
					<td><a href="etc/send_sum/index.php">��ȯ��������</a></td>
					<td></td>
					<td></td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<? page_footer() ?>

</body>
</html>
