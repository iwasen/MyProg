<?
/******************************************************
' System :ICP���̥��󥯥롼�ɥե�����
' Content:�����ɽ���ؿ�
'******************************************************/

define('DEFAULT_SELECT', '- ���򤷤Ƥ������� -');

// <option>��������
function option_tag($value, $selected, $display) {
	echo '<option ', value_selected($value, $selected), '>', $display, '</option>', "\n";
}

// ǯ
function select_year($start_year, $default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	for ($i = $start_year; $i < $start_year + 3; $i++) {
		echo '<option ', value_selected($i, $selected), ">$i</option>\n";
	}
}

// ��
function select_month($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	for ($i = 1; $i <= 12; $i++)
		echo '<option ', value_selected($i, $selected), ">$i</option>\n";
}

// ��
function select_day($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	for ($i = 1; $i <= 31; $i++)
		echo '<option ', value_selected($i, $selected), ">$i</option>\n";
}

// ��
function select_hour($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	for ($i = 0; $i < 24; $i++) {
		echo '<option ', value_selected($i, $selected), ">$i</option>\n";
	}
}

// ʬ
function select_minute($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	for ($i = 0; $i < 60; $i++) {
		echo '<option ', value_selected($i, $selected), ">$i</option>\n";
	}
}

// ɽ���Կ�
function select_displine($selected) {
	$ary = array(10, 20, 50, 100, 200, 500, 1000);
	option_tag(0, $selected, '- ���� -');
	foreach ($ary as $i)
		option_tag($i, $selected, $i);
}

// ����
function select_seibetsu($selected) {
	option_tag('', $selected, DEFAULT_SELECT);
	option_tag('1', $selected, '����');
	option_tag('2', $selected, '����');
}

// �ȿ�
function select_org($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	$sql = "SELECT t1.or_org_id,t1.or_name,t2.or_name AS parent_name"
			. " FROM m_org t1"
			. " LEFT JOIN m_org t2 ON t2.or_org_id=t1.or_parent_org_id"
			. " WHERE t1.or_status=1"
			. " ORDER BY t1.or_org_id";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo '<option ', value_selected($fetch->or_org_id, $selected), '>', $fetch->or_org_id, '.', htmlspecialchars($fetch->or_name);
		if ($fetch->parent_name != '')
			echo '��', htmlspecialchars($fetch->parent_name), '��';
		echo "</option>\n";
	}
}

// ���绲�ÿͿ�
function select_max_member($selected) {
	for ($i = 10; $i <= 100; $i += 10)
		option_tag($i, $selected, $i);
}

// �ޡ������Խ�
function select_marking($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	$sql = "SELECT mk_marking_id,mk_name FROM m_marking ORDER BY mk_marking_id";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		option_tag($fetch->mk_marking_id, $selected, $fetch->mk_name);
	}
}
?>