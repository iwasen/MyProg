sub get_seq {
	local($dbh, $table_id) = @_;
	local($ret, $sql, $key_count, $len_max, $id_form);

	$ret = '';
	$sql = "SELECT KEY_COUNT,LEN_MAX FROM M_SQU_MGR WHERE TABLE_ID='$table_id' FOR UPDATE";

	if (($key_count, $len_max) = $dbh->selectrow_array($sql)) {
		$key_count += 1;
		if (length(sprintf('%d', $key_count)) > $len_max) {
			$key_count = 1;
		}
		$sql = "UPDATE M_SQU_MGR SET KEY_COUNT=$key_count WHERE TABLE_ID='$table_id'";
		if ($dbh->do($sql)) {
			$id_form = "%0" . $len_max . "d";
			$ret = sprintf($id_form, $key_count);
		}
	}
	return $ret;
}
1;
