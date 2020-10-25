<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/login_log.php");
$inc = "$top/inc";
include("$inc/mk_header.php");

// ���å����˥ޡ�������ID����¸
function set_session($marketer_id, $owner_id, $marketer_type, $permission) {
	session_start();
	$_SESSION['ss_marketer_id'] = $marketer_id;
	$_SESSION['ss_owner_id'] = $owner_id;
	$_SESSION['ss_marketer_type'] = $marketer_type;
	$_SESSION['ss_permission'] = $permission;
}

if (isset($mail_addr)) {
	// �᡼�륢�ɥ쥹�򥯥å�������¸
	setcookie('ck_marketer_mail_addr', $mail_addr, COOKIE_EXPIRE, '/');
	$_COOKIE['ck_marketer_mail_addr'] = $mail_addr;

	if ($mail_addr == '')
		$msg[] = '�᡼�륢�ɥ쥹�����Ϥ���Ƥ��ʤ��褦�Ǥ���';

	if ($password == '')
		$msg[] = '�ѥ���ɤ����Ϥ���Ƥ��ʤ��褦�Ǥ���';
} elseif ($marketer_id == '')
	$msg[] = '�����󤷤Ƥ��ʤ������ޤ��ϥ����󤬥����ॢ���Ȥ��ޤ������⤦���٥����󤷤Ƥ���������';

if (!$msg) {
	$sql = "SELECT mr_marketer_id,mr_password,mr_type,mr_parent_id,mr_permission"
			. " FROM t_marketer";
	if ($mail_addr != '')
			$sql .= " WHERE mr_mail_addr_low=" . sql_char(strtolower($mail_addr)) . " AND mr_status<>9";
	elseif ($marketer_id != '')
			$sql .= " WHERE mr_marketer_id=$marketer_id AND mr_status<>9";

	$result = db_exec($sql);
	if (pg_numrows($result) == 0)
		$msg[] = '��Ͽ����λ���Ƥ��ʤ��������ɥ쥹���ְ�äƤ��붲�줬����ޤ����⤦���٤��Τ���ξ塢���Ϥ��Ƥ���������';
	else {
		$fetch = pg_fetch_object($result, 0);
		if ($fetch->mr_password != $password)
			$msg[] = '�ѥ���ɤ��ְ�äƤ��붲�줬����ޤ����⤦���٤��Τ���ξ塢���Ϥ��Ƥ���������';
		else {
			switch ($fetch->mr_type) {
			case MK_FREE:
				set_session($fetch->mr_marketer_id, $fetch->mr_marketer_id, $fetch->mr_type, $fetch->mr_permission);
				login_log(2, $mail_addr, $password, $fetch->mr_marketer_id);
				$url = 'mku_index.php';
				redirect($ssl ? https_url($url) : http_url($url));
			case MK_PAY:
				login_log(2, $mail_addr, $password, $fetch->mr_marketer_id);
				set_session($fetch->mr_marketer_id, $fetch->mr_parent_id != '' ? $fetch->mr_parent_id : $fetch->mr_marketer_id, $fetch->mr_type, $fetch->mr_permission);
				if ($url == '')
					$url = 'mkk_index.php';
				redirect($ssl ? https_url($url) : http_url($url));
			case MK_ASSISTANT:
				login_log(2, $mail_addr, $password, $fetch->mr_marketer_id);
				set_session($fetch->mr_marketer_id, $fetch->mr_parent_id, $fetch->mr_type, $fetch->mr_permission);
				$url = 'mkk_index.php';
				redirect($ssl ? https_url($url) : http_url($url));
			default:
				$msg[] = '��Ͽ����λ���Ƥ��ʤ��������ɥ쥹���ְ�äƤ��붲�줬����ޤ����⤦���٤��Τ���ξ塢���Ϥ��Ƥ���������';
			}
		}
	}
}

login_log(2, $mail_addr, $password, null);

marketer_header('������', PG_NULL);
?>
<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<table width="788" border="0" cellspacing="2" cellpadding="0" bgcolor="#ffffff">
  <tr> 
    <td rowspan="2" width="20"><img src="images/common/spacer.gif" width="20" height="1" border="0"></td>
    <td width="768"><img src="images/common/spacer.gif" width="1" height="20" border="0"></td>
  </tr>
  <tr> 
    <td> 
      <form method="post" action="index.php">
        ������Ǥ��ޤ���Ǥ�����
        <Hr Width="95%" Size="1" Color="#666666" Align="left" Noshade>
        <br>
        <?=disp_msg($msg, '��', '<br>')?>
        <br>

<input type="image" src="images/mkk_bt/back.gif" alt="���" width="108" height="31">
      </form>
    </td>
  </tr>
  <tr>
    <td width="20">��</td>
    <td>��</td>
  </tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" 

border="0"></td>
  </tr>
</table>
<? marketer_footer() ?>