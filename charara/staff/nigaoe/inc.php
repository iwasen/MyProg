<?
// 性別表示
function disp_sex($code) {
	if ($code != '') {
		if ($code == 1)
			return '<font color="blue">男性</font>';
		else
			return '<font color="red">女性</font>';
	}
}

// 性別
function disp_age($age) {
	if ($age != '')
		return $age;
	else
		return '未入力';
}

// 状態
function disp_status($status) {
	switch ($status) {
	case STATUS_PHOTO_RECEIVED:
		return '未着手';
	case STATUS_STAFF_ASSIGNED:
		return '割り当て済み';
	case STATUS_MAKING:
		return '似顔絵作成中';
	case STATUS_COMPLETED:
		return '似顔絵完成';
	case STATUS_CHECK_OK:
		return 'チェックOK';
	}
}
?>