<?
/******************************************************
' System :ICP���ڥ졼���ڡ���
' Content:���̥إå����եå�
'******************************************************/

define('COPYRIGHT', 'Copyright &copy; 2004 xx xxxxx Inc. All Rights Reserved.');

// �إå�
function ope_header() {
	global $top, $g_operator_id;
?>
<table border="0" cellspacing="0" cellpadding="0" width="100%" class="head">
<tr>
	<td width="50%">icp.net ���ڥ졼�����ڡ���</td>
	<td width="50%" align="right" style="font-size:12px; color:#ffffff; font-weight:normal;">
<?
	if ($g_operator_id) {
?>
		<a href="<?=$top?>/login/logoff.php" title="������">������</a>
<?
	}
?>
	</td>
</tr>
</table>
<?
}

// �եå�
function ope_footer() {
?>
<table border="0" cellspacing="0" cellpadding="0" width="96%" class="copyright">
<tr>
	<td align="center"><?=COPYRIGHT?></td>
</tr>
</table>
<?
}

// ��˥塼
function ope_menu() {
	global $top;

	$search_pjt = $_SESSION[SCD]['search_pjt'];
	$search_org = $_SESSION[SCD]['search_org'];
	$search_ope = $_SESSION[SCD]['search_ope'];
?>
	<td width="250" valign="top">
		<table border="0" cellspacing="0" cellpadding="10" width="100%">
		<tr>
			<td>
			<form name="search">
				<table border="0" cellspacing="0" cellpadding="1" width="100%" bgcolor="#831621">
				<tr>
					<td>
						<table border="0" cellspacing="0" cellpadding="3" width="100%" class="menu">
						<tr>
							<td><font color="#FFFFFF"><b>��˥塼</b></font></td>
						</tr>
						<tr>
							<td bgcolor="#ffffff"><a href="<?=$top?>/pjt/list.php" title="�ץ������Ȱ���">�ץ������Ȱ���</a></td>
						</tr>
						<tr>
							<td bgcolor="#ffffff">�ץ������ȸ���</td>
						</tr>
						<tr>
							<td bgcolor="#ffffff" align="center"><input type="text" name="search_pjt" size="20" class="form" <?=value($search_pjt)?>>
							<input type="button" value="����" class="btn" onclick="top.ctrl.on_search_pjt(document.search.search_pjt.value)"></td>
						</tr>
<?
	if ($search_pjt != null) {
?>
						<tr>
							<td bgcolor="#ffffff" align="center">
								<table border="1" cellspacing="0" cellpadding="0" class="searchresult" width="220">
								<tr>
									<th width="30">pjt CD</th>
									<th width="160">pjt ̾</th>
									<th width="30">job��</th>
								</tr>
<?
		$sql = "SELECT pj_pjt_cd,pj_name,pj_job_num"
				. " FROM t_pjt"
				. " WHERE pj_name LIKE '%$search_pjt%'"
				. " ORDER BY pj_pjt_cd";
		$result = db_exec($sql);
		$nrow = pg_num_rows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
?>
								<tr>
									<td align="center"><?=$fetch->pj_pjt_cd?></td>
									<td><?=htmlspecialchars($fetch->pj_name)?></td>
									<td align="right"><?=$fetch->pj_job_num?></td>
								</tr>
<?
		}
?>
								</table>
							</td>
						</tr>
<?
	}
?>
						<tr>
							<td bgcolor="#ffffff" align="center"><img src="<?=$top?>/img/line_200.gif" width="200" height="5" border="0"></td>
						</tr>
						<tr>
							<td bgcolor="#ffffff"><a href="<?=$top?>/org/list.php" title="��Ͽ�Ѥ��ȿ�����">��Ͽ�Ѥ��ȿ�����</a></td>
						</tr>
						<tr>
							<td bgcolor="#ffffff">��Ͽ�Ѥ��ȿ�����</td>
						</tr>
						<tr>
							<td bgcolor="#ffffff" align="center"><input type="text" name="search_org" size="20" class="form" <?=value($search_org)?>>
							<input type="button" value="����" class="btn" onclick="top.ctrl.on_search_org(document.search.search_org.value)"></td>
						</tr>
<?
	if ($search_org != null) {
?>
						<tr>
							<td bgcolor="#ffffff" align="center">
								<table border="1" cellspacing="0" cellpadding="0" class="searchresult" width="220">
								<tr>
									<th width="40">�ȿ� CD</th>
									<th width="180">�ȿ� ̾</th>
								</tr>
<?
		$sql = "SELECT or_org_id,or_name"
				. " FROM m_org"
				. " WHERE or_name LIKE '%$search_org%'"
				. " ORDER BY or_org_id";
		$result = db_exec($sql);
		$nrow = pg_num_rows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
?>
								<tr>
									<td align="center"><?=$fetch->or_org_id?></td>
									<td><?=htmlspecialchars($fetch->or_name)?></td>
								</tr>
<?
		}
?>
								</table>
							</td>
						</tr>
<?
	}
?>
<?
	if ($_SESSION[SCD]['admin_auth']) {
?>
						<tr>
							<td bgcolor="#ffffff" align="center"><img src="<?=$top?>/img/line_200.gif" width="200" height="5" border="0"></td>
						</tr>
						<tr>
							<td bgcolor="#ffffff"><a href="<?=$top?>/op/list.php" title="���ڥ졼��������">���ڥ졼��������</a></td>
						</tr>
						<tr>
							<td bgcolor="#ffffff">���ڥ졼��������</td>
						</tr>
						<tr>
							<td bgcolor="#ffffff" align="center"><input type="text" name="search_ope" size="20" class="form" <?=value($search_ope)?>>
							<input type="button" value="����" class="btn" onclick="top.ctrl.on_search_ope(document.search.search_ope.value)"></td>
						</tr>
<?
		if ($search_ope != null) {
?>
						<tr>
							<td bgcolor="#ffffff" align="center">
								<table border="1" cellspacing="0" cellpadding="0" class="searchresult" width="220">
								<tr>
									<th width="40">���ڥ졼���� CD</th>
									<th width="180">���ڥ졼���� ̾</th>
								</tr>
<?
			$sql = "SELECT op_operator_id,op_name1,op_name2"
					. " FROM t_operator"
					. " WHERE op_name1 LIKE '%$search_ope%' OR op_name2 LIKE '%$search_ope%'"
					. " ORDER BY op_operator_id";
			$result = db_exec($sql);
			$nrow = pg_num_rows($result);
			for ($i = 0; $i < $nrow; $i++) {
				$fetch = pg_fetch_object($result, $i);
?>
								<tr>
									<td align="center"><?=$fetch->op_operator_id?></td>
									<td><?=htmlspecialchars($fetch->op_name1)?>��<?=htmlspecialchars($fetch->op_name2)?></td>
								</tr>
<?
			}
?>
								</table>
							</td>
						</tr>
<?
		}
	}
?>
						</table>
					</td>
				</tr>
				</table>
			</form>
			<div align="center" class="backtotop"><br>
<?
if ($_SESSION[SCD]['admin_auth']) {
?>
				<a href="<?=$top?>/master/menu.php" title="�ޥ������ƥʥ�">�ޥ������ƥʥ�</a><br><br>
<?
}
?>
				<a href="<?=$top?>/top.php" title="�ȥåץڡ��������">���ȥåץڡ��������</a>
			</div>
			</td>
		</tr>
		</table>
	</td>
<?
}
?>