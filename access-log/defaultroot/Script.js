// �Ǝ�h�����_�E��
function categorypost(categoryVal) {
  var f = document.form1;
  f.category.value = categoryVal;
  f.submit();
}

// �R�[�i�[�h�����_�E��
function cornerpost(cornerVal, cornerdepthVal) {
  var f = document.form1;
  f.corner.value = cornerVal;
  f.cornerdepth.value = cornerdepthVal;
  f.submit();
}

// URL�ʃA�N�Z�X�\��
function urlAccessCommon(date, corner, category, attribute) {
  window.open("UrlAccess.jsp?date=" + date + "&corner=" + corner + "&category=" + category + "&attribute=" + attribute,
    "UrlAccess",
    "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=auto,resizable=yes,width=600,height=750,left=0,top=0");
}

// ����l�^�ڍ׃��[�h�ύX�i�i�r�Q�[�^���j���[�j
function modechg(mode) {
  var f = document.form1;
  f.mode.value = mode;
  f.init.value = "1";
  f.submit();
}

// �y�[�W�ύX�i�i�r�Q�[�^���j���[�j
function pagechg(page) {
  var f = document.form1;
  f.page.value = page;
  if (f.mode.value == "3")
    f.mode.value = "2";
  f.submit();
}

// ���ԁi���j���̓`�F�b�N
function checkMonth() {
  var f = document.form1;
  if (f.monthFromY.value == "") {
    alert("�N��I�����Ă��������B");
    f.monthFromY.focus();
    return false;
  }
  if (f.monthFromM.value == "") {
    alert("����I�����Ă��������B");
    f.monthFromM.focus();
    return false;
  }
  if (f.monthToY.value == "") {
    alert("�N��I�����Ă��������B");
    f.monthToY.focus();
    return false;
  }
  if (f.monthToM.value == "") {
    alert("����I�����Ă��������B");
    f.monthToM.focus();
    return false;
  }
  if (new Date(f.monthFromY.value, f.monthFromM.value - 1, 1) > new Date(f.monthToY.value, f.monthToM.value - 1, 1)) {
    alert("���Ԃ̎w�肪����������܂���B");
    f.monthToM.focus();
    return false;
  }
  f.mode.value = "3";
  return true;
}

// ���ԁi�T�j���̓`�F�b�N
function checkWeek() {
  var f = document.form1;
  if (f.weekFromY.value == "") {
    alert("�N��I�����Ă��������B");
    f.weekFromY.focus();
    return false;
  }
  var weekFromW = "weekFromW" + f.weekFromY.value;
  if (f[weekFromW].value == "") {
    alert("�T��I�����Ă��������B");
    f[weekFromW].focus();
    return false;
  }
  if (f.weekToY.value == "") {
    alert("�N��I�����Ă��������B");
    f.weekToY.focus();
    return false;
  }
  var weekToW = "weekToW" + f.weekToY.value;
  if (f[weekToW].value == "") {
    alert("�T��I�����Ă��������B");
    f[weekToW].focus();
    return false;
  }
  if (f.weekFromY.value > f.weekToY.value || f.weekFromY.value == f.weekToY.value && f[weekFromW].value > f[weekToW].value) {
    alert("���Ԃ̎w�肪����������܂���B");
    f[weekToW].focus();
    return false;
  }
  f.mode.value = "3";
  return true;
}

// ���ԁi���j���̓`�F�b�N
function checkDay() {
  var f = document.form1;
  if (f.dayFromY.value == "") {
    alert("�N��I�����Ă��������B");
    f.dayFromY.focus();
    return false;
  }
  if (f.dayFromM.value == "") {
    alert("����I�����Ă��������B");
    f.dayFromM.focus();
    return false;
  }
  if (f.dayFromD.value == "") {
    alert("����I�����Ă��������B");
    f.dayFromD.focus();
    return false;
  }
  if (!validDate(f.dayFromY.value, f.dayFromM.value, f.dayFromD.value)) {
    alert("�L���ȔN�����ł͂���܂���B");
    f.dayFromD.focus();
    return false;
  }
  if (f.dayToY.value == "") {
    alert("�N��I�����Ă��������B");
    f.dayToY.focus();
    return false;
  }
  if (f.dayToM.value == "") {
    alert("����I�����Ă��������B");
    f.dayToM.focus();
    return false;
  }
  if (f.dayToD.value == "") {
    alert("����I�����Ă��������B");
    f.dayToD.focus();
    return false;
  }
  if (!validDate(f.dayToY.value, f.dayToM.value, f.dayToD.value)) {
    alert("�L���ȔN�����ł͂���܂���B");
    f.dayToD.focus();
    return false;
  }
  if (new Date(f.dayFromY.value, f.dayFromM.value - 1, f.dayFromD.value) > new Date(f.dayToY.value, f.dayToM.value - 1, f.dayToD.value)) {
    alert("���Ԃ̎w�肪����������܂���B");
    f.dayToD.focus();
    return false;
  }
  f.mode.value = "3";
  return true;
}

// ���ԁi���j���̓`�F�b�N
function checkHour() {
  var f = document.form1;
  if (f.hourFromY.value == "") {
    alert("�N��I�����Ă��������B");
    f.hourFromY.focus();
    return false;
  }
  if (f.hourFromM.value == "") {
    alert("����I�����Ă��������B");
    f.hourFromM.focus();
    return false;
  }
  if (f.hourFromD.value == "") {
    alert("����I�����Ă��������B");
    f.hourFromD.focus();
    return false;
  }
  if (f.hourFromH.value == "") {
    alert("����I�����Ă��������B");
    f.hourFromH.focus();
    return false;
  }
  if (!validDate(f.hourFromY.value, f.hourFromM.value, f.hourFromD.value)) {
    alert("�L���ȔN�����ł͂���܂���B");
    f.hourFromD.focus();
    return false;
  }
  if (f.hourToY.value == "") {
    alert("�N��I�����Ă��������B");
    f.hourToY.focus();
    return false;
  }
  if (f.hourToM.value == "") {
    alert("����I�����Ă��������B");
    f.hourToM.focus();
    return false;
  }
  if (f.hourToD.value == "") {
    alert("����I�����Ă��������B");
    f.hourToD.focus();
    return false;
  }
  if (f.hourToH.value == "") {
    alert("����I�����Ă��������B");
    f.hourToH.focus();
    return false;
  }
  if (!validDate(f.hourToY.value, f.hourToM.value, f.hourToD.value)) {
    alert("�L���ȔN�����ł͂���܂���B");
    f.hourToD.focus();
    return false;
  }
  if (new Date(f.hourFromY.value, f.hourFromM.value - 1, f.hourFromD.value, f.hourFromH.value) > new Date(f.hourToY.value, f.hourToM.value - 1, f.hourToD.value, f.hourToH.value)) {
    alert("���Ԃ̎w�肪����������܂���B");
    f.hourToD.focus();
    return false;
  }
  f.mode.value = "3";
  return true;
}

// ���t�̗L�����`�F�b�N
function validDate(y, m, d) {
	var dt = new Date(y, m - 1, d);
	if (!isNaN(dt)) {
		if (dt.getFullYear() == y && dt.getMonth() + 1 == m && dt.getDate() == d)
			return true;
	}
	return false;
}

// ���Ԃ̏T���X�g�̕\����I������Ă���N�ɍ��킹�ď����\��
function resetWeekList() {
  var f = document.form1;
  if (f.weekFromY) {
    changeYear(f.weekFromY);
    changeYear(f.weekToY);
  }
}

// ���Ԃ̏T�̔N��I���i�T�̃��X�g��؂�ւ���j
function changeYear(c) {
  if (c.name.substr(0, 4) == "week") {
    var f = document.form1;
    var len = c.name.length;
    var wn = c.name.substr(0, len - 1) + "W";
    for (var i = 0; i < c.options.length; i++) {
      f[wn + c.options[i].value].style.display = (c.value == c.options[i].value) ? "" : "none";
    }
  }
}
