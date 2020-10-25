function check_input(c, item) {
	if (c.value == "") {
		alert(item + "を入力してください。");
		c.focus();
		return false;
	}
	return true;
}
function check_checked(c, item) {
	if (c.length) {
		for (var i = 0; i < c.length; i++) {
			if (c[i].checked)
				return true;
		}
		alert(item + "を選択してください。");
		c[0].focus();
	} else {
		if (c.checked)
			return true;
		alert(item + "を選択してください。");
		c.focus();
	}
	return false;
}
function check_select(c, item) {
	if (c.selectedIndex == -1 || c.options[c.selectedIndex].value == "") {
		alert(item + "を選択してください。");
		c.focus();
		return false;
	}
	return true;
}
function check_number(c, item) {
	if (c.value != "") {
		if (!c.value.match(/^[0-9]+(\.[0-9]*)?$/)) {
			alert(item + "は半角数字で入力してください。");
			c.focus();
			return false;
		}
	}
	return true;
}
function check_mail(c, item) {
	if (c.value != "") {
		if (!c.value.match(/^[a-zA-Z0-9._\/-]+@[a-zA-Z0-9_-]+\.[a-zA-Z0-9._-]+[^.]$/)) {
			alert(item + "が正しく入力されていません。");
			c.focus();
			return false;
		}
	}
	return true;
}
function check_url(c, item) {
	if (c.value != "") {
		if (!c.value.match(/^https?:\/\/.+$/)) {
			alert(item + "が正しく入力されていません。");
			c.focus();
			return false;
		}
	}
	return true;
}
function check_date(y, m, d, item) {
	var dt = new Date(y.value, m.value - 1, d.value);
	if (!isNaN(dt)) {
		if (dt.getYear() == y.value && dt.getMonth() + 1 == m.value && dt.getDate() == d.value)
			return true;
	}
	alert(item + "が正しく入力されていません。");
	return false;
}
