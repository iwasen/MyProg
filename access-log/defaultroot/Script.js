// 業種ドリルダウン
function categorypost(categoryVal) {
  var f = document.form1;
  f.category.value = categoryVal;
  f.submit();
}

// コーナードリルダウン
function cornerpost(cornerVal, cornerdepthVal) {
  var f = document.form1;
  f.corner.value = cornerVal;
  f.cornerdepth.value = cornerdepthVal;
  f.submit();
}

// URL別アクセス表示
function urlAccessCommon(date, corner, category, attribute) {
  window.open("UrlAccess.jsp?date=" + date + "&corner=" + corner + "&category=" + category + "&attribute=" + attribute,
    "UrlAccess",
    "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=auto,resizable=yes,width=600,height=750,left=0,top=0");
}

// 既定値／詳細モード変更（ナビゲータメニュー）
function modechg(mode) {
  var f = document.form1;
  f.mode.value = mode;
  f.init.value = "1";
  f.submit();
}

// ページ変更（ナビゲータメニュー）
function pagechg(page) {
  var f = document.form1;
  f.page.value = page;
  if (f.mode.value == "3")
    f.mode.value = "2";
  f.submit();
}

// 期間（月）入力チェック
function checkMonth() {
  var f = document.form1;
  if (f.monthFromY.value == "") {
    alert("年を選択してください。");
    f.monthFromY.focus();
    return false;
  }
  if (f.monthFromM.value == "") {
    alert("月を選択してください。");
    f.monthFromM.focus();
    return false;
  }
  if (f.monthToY.value == "") {
    alert("年を選択してください。");
    f.monthToY.focus();
    return false;
  }
  if (f.monthToM.value == "") {
    alert("月を選択してください。");
    f.monthToM.focus();
    return false;
  }
  if (new Date(f.monthFromY.value, f.monthFromM.value - 1, 1) > new Date(f.monthToY.value, f.monthToM.value - 1, 1)) {
    alert("期間の指定が正しくありません。");
    f.monthToM.focus();
    return false;
  }
  f.mode.value = "3";
  return true;
}

// 期間（週）入力チェック
function checkWeek() {
  var f = document.form1;
  if (f.weekFromY.value == "") {
    alert("年を選択してください。");
    f.weekFromY.focus();
    return false;
  }
  var weekFromW = "weekFromW" + f.weekFromY.value;
  if (f[weekFromW].value == "") {
    alert("週を選択してください。");
    f[weekFromW].focus();
    return false;
  }
  if (f.weekToY.value == "") {
    alert("年を選択してください。");
    f.weekToY.focus();
    return false;
  }
  var weekToW = "weekToW" + f.weekToY.value;
  if (f[weekToW].value == "") {
    alert("週を選択してください。");
    f[weekToW].focus();
    return false;
  }
  if (f.weekFromY.value > f.weekToY.value || f.weekFromY.value == f.weekToY.value && f[weekFromW].value > f[weekToW].value) {
    alert("期間の指定が正しくありません。");
    f[weekToW].focus();
    return false;
  }
  f.mode.value = "3";
  return true;
}

// 期間（日）入力チェック
function checkDay() {
  var f = document.form1;
  if (f.dayFromY.value == "") {
    alert("年を選択してください。");
    f.dayFromY.focus();
    return false;
  }
  if (f.dayFromM.value == "") {
    alert("月を選択してください。");
    f.dayFromM.focus();
    return false;
  }
  if (f.dayFromD.value == "") {
    alert("日を選択してください。");
    f.dayFromD.focus();
    return false;
  }
  if (!validDate(f.dayFromY.value, f.dayFromM.value, f.dayFromD.value)) {
    alert("有効な年月日ではありません。");
    f.dayFromD.focus();
    return false;
  }
  if (f.dayToY.value == "") {
    alert("年を選択してください。");
    f.dayToY.focus();
    return false;
  }
  if (f.dayToM.value == "") {
    alert("月を選択してください。");
    f.dayToM.focus();
    return false;
  }
  if (f.dayToD.value == "") {
    alert("日を選択してください。");
    f.dayToD.focus();
    return false;
  }
  if (!validDate(f.dayToY.value, f.dayToM.value, f.dayToD.value)) {
    alert("有効な年月日ではありません。");
    f.dayToD.focus();
    return false;
  }
  if (new Date(f.dayFromY.value, f.dayFromM.value - 1, f.dayFromD.value) > new Date(f.dayToY.value, f.dayToM.value - 1, f.dayToD.value)) {
    alert("期間の指定が正しくありません。");
    f.dayToD.focus();
    return false;
  }
  f.mode.value = "3";
  return true;
}

// 期間（時）入力チェック
function checkHour() {
  var f = document.form1;
  if (f.hourFromY.value == "") {
    alert("年を選択してください。");
    f.hourFromY.focus();
    return false;
  }
  if (f.hourFromM.value == "") {
    alert("月を選択してください。");
    f.hourFromM.focus();
    return false;
  }
  if (f.hourFromD.value == "") {
    alert("日を選択してください。");
    f.hourFromD.focus();
    return false;
  }
  if (f.hourFromH.value == "") {
    alert("時を選択してください。");
    f.hourFromH.focus();
    return false;
  }
  if (!validDate(f.hourFromY.value, f.hourFromM.value, f.hourFromD.value)) {
    alert("有効な年月日ではありません。");
    f.hourFromD.focus();
    return false;
  }
  if (f.hourToY.value == "") {
    alert("年を選択してください。");
    f.hourToY.focus();
    return false;
  }
  if (f.hourToM.value == "") {
    alert("月を選択してください。");
    f.hourToM.focus();
    return false;
  }
  if (f.hourToD.value == "") {
    alert("日を選択してください。");
    f.hourToD.focus();
    return false;
  }
  if (f.hourToH.value == "") {
    alert("時を選択してください。");
    f.hourToH.focus();
    return false;
  }
  if (!validDate(f.hourToY.value, f.hourToM.value, f.hourToD.value)) {
    alert("有効な年月日ではありません。");
    f.hourToD.focus();
    return false;
  }
  if (new Date(f.hourFromY.value, f.hourFromM.value - 1, f.hourFromD.value, f.hourFromH.value) > new Date(f.hourToY.value, f.hourToM.value - 1, f.hourToD.value, f.hourToH.value)) {
    alert("期間の指定が正しくありません。");
    f.hourToD.focus();
    return false;
  }
  f.mode.value = "3";
  return true;
}

// 日付の有効性チェック
function validDate(y, m, d) {
	var dt = new Date(y, m - 1, d);
	if (!isNaN(dt)) {
		if (dt.getFullYear() == y && dt.getMonth() + 1 == m && dt.getDate() == d)
			return true;
	}
	return false;
}

// 期間の週リストの表示を選択されている年に合わせて初期表示
function resetWeekList() {
  var f = document.form1;
  if (f.weekFromY) {
    changeYear(f.weekFromY);
    changeYear(f.weekToY);
  }
}

// 期間の週の年を選択（週のリストを切り替える）
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
