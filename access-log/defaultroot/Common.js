//FORM送信
function postForm(theForm,servlet){
   with(theForm){
      action=servlet;
      method='post';
      submit();
  }
}


//button送信
function pageMove (servlet){
  location.href=servlet;
}

//全角チェック
function isNotZenkaku ( input_obj )
{
	var len = input_obj.value.length;
	var val = input_obj.value.replace(/[!-~。-゜ ]/g,"");
	if ( val.length != len )
	{
		alert("全角文字で入力して下さい。");
		input_obj.focus();
		input_obj.select();
		return true;
	}
	return false;
}

//全角ひらがなチェック
function isNotZenkakuhiragana ( input_obj )
{
	if ( input_obj.value.replace(/[ぁあぃいぅうぇえぉおかがきぎくぐけげこごさざしじすずせぜそぞただちぢっつづてでとどなにぬねのはばぱひびぴふぶぷへべぺほぼぽまみむめもゃやゅゆょよらりるれろゎわゑをん゛゜ゝゞー]/g,"").length != 0 )
	{
		alert("全角ひらがな以外は使えません。");
		input_obj.focus();
		input_obj.select();
		return true;
	}

	return false;
}


//全角カタカナチェック
function isNotZenkakuKatakana ( input_obj )
{
	if ( input_obj.value.replace(/[ァアィイゥウェエォオカガキギクグケゲコゴサザシジスズセゼソゾタダチヂッツヅテデトドナニヌネノハバパヒビピフブプヘベペホボポマミムメモャヤュユョヨラリルレロヮワヰヱヲンヴヵヶーヽヾ]/g,"").length != 0 )
	{
		alert("全角カナ以外は使えません。");
		input_obj.focus();
		input_obj.select();
		return true;
	}

	return false;
}

//ラジオボタンチェック
function alertNoCheckedRadio ( radio_obj, message )
{
	var flag = true;
	for ( i = 0 ; i < radio_obj.length ; i++ )
		if( radio_obj[i].checked ) flag = false;
	if ( flag )
	{
		alert( message + "選択必須項目です。\n　　　選択をお願いします。");
		radio_obj[0].focus();
	}
	return flag;
}

//チェックボックスクリア
function clearCheck ( checkbox_obj )
{
	for ( i = 0; i < checkbox_obj.length; i++ )
		checkbox_obj[i].checked = false;
}

//入力必須項目チェック
function alertEmpty ( input_obj, message)
{
       	if (input_obj.value == null || input_obj.value == "" )
	{
		alert(message + "入力必須項目です。\n　　　入力をお願いします。");
		input_obj.focus();
		return true;
	}
	return false;
}

//文字数チェック(○文字以内)
function alertOverLength ( input_obj, len )
{
	if ( input_obj.value.length > len )
	{
		alert(len + "文字以内で入力して下さい。");
		input_obj.focus();
		input_obj.select();
		return true;
	}
	return false;
}
//文字数チェック(○文字以上)
function alertShortLength ( input_obj, len )
{
	if ( input_obj.value.length < len )
	{
		alert(len + "文字以上で入力して下さい。");
		input_obj.focus();
		input_obj.select();
		return true;
	}
	return false;
}

//文字数チェック(バイト数)
function alertOverByte(input_obj, len){
  if(getLength(input_obj)>len){
    alert(len+"バイト以内で入力してください。");
    input_obj.focus();
    input_obj.select();
    return true;
  }
  return false;
}

//文字数チェック(○文字指定)
function alertSameLength ( input_obj, len, message )
{
	if ( input_obj.value.length != len )
	{
		alert( message + "は" + len + "桁で記入して下さい。");
		input_obj.focus();
		input_obj.select();
		return true;
	}
	return false;
}

//半角英字・数字チェック
function isNotHankaku ( input_obj )
{
	var len = input_obj.value.length;
	var val = input_obj.value.replace ( /[0-9A-Za-z]/g,"" );
	if ( val.length != 0)
	{
		alert ( "半角の英字、数字以外が記入されていないか確認して下さい。" );
		input_obj.focus();
		input_obj.select();
		return true;
	}
	return false;
}

//半角数字・桁数チェック
function isNotValidPostalCode ( input_obj, clen, message )
{
	var len = input_obj.value.length;
	var val = input_obj.value.replace(/[0-9]/g,"");
	if ( val.length != 0 )
	{
		alert(message + "半角数字以外は入力できません。");
		input_obj.focus();
		input_obj.select();
		return true;
	}
	if ( len != clen )
	{
		alert(message + clen + "桁の半角数字で入力して下さい。");
		input_obj.focus();
		input_obj.select();
		return true;
	}
	return false;
}

//数字チェック
function isNotSuuji ( input_obj, min, max, message )
{
	var len = input_obj.value.length;
	var val = input_obj.value;
	if ( isNotANumber ( input_obj, true, true ) ) return true;
	if ( val < min || max < val )
	{
		alert ( message + "は" + min + "～" + max + "の値を入力してください。" );
		input_obj.focus();
		input_obj.select();
		return true;
	}
	if ( len == 1 )
	{
		input_obj.value = "0" + val;
	}
	return false;
}

//テキスト数値比較
function textnumerical ( textA, textB ){
	if(parseInt(textA,10) > parseInt(textB,10)){
		alert("年齢が不正確です。\n　設定し直して下さい。");
		return true;
	}
	return false;
}

/*
//テキスト数値比較(オブジェクト版)
function textnumerical ( objA, objB )
{
        if ( parseInt(objA.value) > ParseInt(objB.value) )
        {
                alert ( "年齢が不正確です。\n　設定し直して下さい。" );
                objA.focus();
                objA.select();
                return true;
        }
        return false;
}
*/
//年月日比較
function ymdnumerical ( n_year, n_month, n_day, n2_year, n2_month, n2_day, message )
{
        num1 = n_year + n_month + n_day;
        num2 = n2_year + n2_month + n2_day;
        if ( num1 > num2 )
        {
                alert ( message + "不正確です。\n 設定し直して下さい。" );
                return true;
        }
        return false;
}

//年月日比較配列版
function ymdnumericalArray(ymd_from, ymd_to){
	return ymdnumerical(ymd_from[0].value,ymd_from[1].value,ymd_from[2].value,
								ymd_to[0].value,ymd_to[1].value,ymd_to[2].value,"日付の指定が");
}


//メールアドレスチェック
function isNotValidMailAddress ( obj )
{
	if ( obj.value.substring( 0 , 1 ) == "@" )
	{
		alert( "メールアドレスが不正です。" );
		obj.focus();
		obj.select();
		return true;
	}
	if ( obj.value.indexOf("@") == -1 )
	{
		alert("@ マークがありません。");
		obj.focus();
		obj.select();
		return true;
	}
	if ( obj.value.replace(/[!-~]/g,"") != 0 )
	{
		alert("半角英数字以外は使えません。");
		obj.focus();
		obj.select();
		return true;
	}
	if ( obj.value.substring ( obj.value.indexOf("@") + 1, obj.value.length ).indexOf("@") != -1 )
	{
		alert("@ マークが２つ以上あります。");
		obj.focus();
		obj.select();
		return true;
	}

	var user = obj.value.split("@")[0];
	var domain = obj.value.split("@")[1];

	var user_message = "メールアドレスのユーザー名";

	if ( isUseThisChar( obj, user, "<", user_message ) ) return true;
	if ( isUseThisChar( obj, user, ">", user_message ) ) return true;
	if ( isUseThisChar( obj, user, "(", user_message ) ) return true;
	if ( isUseThisChar( obj, user, ")", user_message ) ) return true;
	if ( isUseThisChar( obj, user, "[", user_message ) ) return true;
	if ( isUseThisChar( obj, user, "]", user_message ) ) return true;
	if ( isUseThisChar( obj, user, "\\", user_message ) ) return true;
	if ( isUseThisChar( obj, user, ",", user_message ) ) return true;
	if ( isUseThisChar( obj, user, ";", user_message ) ) return true;
	if ( isUseThisChar( obj, user, ":", user_message ) ) return true;
	if ( isUseThisChar( obj, user, "\"", user_message ) ) return true;
	if ( isUseThisChar( obj, user, "\\t", user_message ) ) return true;
	if ( isUseThisChar( obj, user, "\\n", user_message ) ) return true;
	if ( isUseThisChar( obj, user, "\\r", user_message ) ) return true;
	if ( isUseThisChar( obj, user, "\\f", user_message ) ) return true;

	var domain_message = "メールアドレスのドメイン名";

	if ( domain.indexOf(".") == -1 )
	{
		alert(domain_message + "には . が必要です。");
		obj.focus();
		obj.select();
		return true;
	}

	if ( domain.charAt(0).replace(/[0-9a-zA-Z]/g,"").length != 0 )
	{
		alert(domain_message + "の最初の一文字が英数字ではありません。");
		obj.focus();
		obj.select();
		return true;
	}

	var domain_len = domain.length;

	if ( domain.replace(/[.\-0-9a-zA-Z]/g,"").length != 0 )
	{
		alert(domain_message + "に「英数字」「-」「.」以外の文字が使われています。");
		obj.focus();
		obj.select();
		return true;
	}

	if ( domain.charAt(domain_len - 1).replace(/[0-9a-zA-Z]/g,"").length != 0 )
	{
		alert(domain_message + "の最後の一文字が英数字ではありません。");
		obj.focus();
		obj.select();
		return true;
	}

	return false;
}

function isUseThisChar( input_obj, str, c, message )
{
	if ( str.indexOf(c) != -1 )
	{
		alert( message + "に " + c + " は使えません。");
		input_obj.focus();
		input_obj.select();
		return true;
	}
	return false;
}

//比較項目チェック
function isNotEquals ( obj1, obj2, message )
{
	if ( obj1.value != obj2.value )
	{
		alert(message + "が一致しません。");
		obj2.focus();
		obj2.select();
		return true;
	}
	return false;
}

//年・月・日チェック
function isValidateDate( year, month, day, alert_option, focus_option )
{
//	var alert_option = true; // アラートメッセージを出すかどうかの設定。(true:有り false:無し)
//	var focus_option = true; // エラー発生後に、対象となる入力欄にフォーカスをあわせるかどうかの設定
	                         // (true:あわせる false:あわせない)

	var obj = new Array(  // 対象となるフォームオブジェクトを入れて下さい。
		year,   // 「年」の入力欄
		month,  // 「月」の入力欄
		day     // 「日」の入力欄
	);

	// 入力年月日が正しければ true を返す。
	return ( vaidateDate( obj, alert_option, focus_option ) );
}

function vaidateDate( obj, alert_option, focus_option )
{
//	var y = new String("19") +  obj[0].value;//４桁版に修正
	var y = obj[0].value;
	var m = obj[1].value;
	var d = obj[2].value;

	// 半角数字チェック
	if ( isNotANumber( obj[0], alert_option, focus_option ) ) return false;
	if ( isNotANumber( obj[1], alert_option, focus_option ) ) return false;
	if ( isNotANumber( obj[2], alert_option, focus_option ) ) return false;

	if ( m < 1 || 12 < m )
	{
		if ( alert_option )
			alert("1～12の値を入力して下さい。");
		if ( focus_option )
		{
			obj[1].focus();
			obj[1].select();
		}
		return false;
	}

	// １・３・５・７・８・１０・１２月の場合
	if ( m == 1 || m == 3 || m == 5 || m == 7 || m == 8 || m == 10 || m == 12 )
	{
		if ( d < 1 || 31 < d )
		{
			if ( alert_option )
				alert("1～31の値を入力して下さい。");
			if ( focus_option )
			{
				obj[2].focus();
				obj[2].select();
			}
			return false;
		}
	}
	// ４・６・９・１１月の場合
	else if ( m == 4 || m == 6 || m == 9 || m == 11 )
	{
		if ( d < 1 || 30 < d )
		{
			if ( alert_option )
				alert("1～30の値を入力して下さい。");
			if ( focus_option )
			{
				obj[2].focus();
				obj[2].select();
			}
			return false;
		}
	}
	// ２月の場合
	else
	{
		// うるう年演算
		var leafyear = false;
		if ( y % 4 == 0 ) leafyear = true;
		if ( y % 100 == 0 ) leafyear = false;
		if ( y % 400 == 0 ) leafyear = true;

		var max = leafyear ? 29 : 28;
		if ( d < 1 || max < d )
		{
			if ( alert_option )
				alert("1～" + max + "の値を入力して下さい。");
			if ( focus_option )
			{
				obj[2].focus();
				obj[2].select();
			}
			return false;
		}
	}

	return true;
}

//現在より前でないか確認
//戻り値:過去を指定=true,未来を指定=false;
function alertPast(ymd, message, fg_alert){
  NOW = new Date();
  var y = ymd[0].value;
  var M = ymd[1].value-1;
  var d = ymd[2].value;
  var h = ymd[3].value;
  var m = ymd[4].value;
  var s = ymd[5].value;

  SendDate = new Date(y,M,d,h,m);
  if(NOW>SendDate){
    if(fg_alert){
      alert(message+"が現在より前に設定されています");
    }
    return true;
  }
  return false;
}

/*
  拡張 isNaN() 関数。
  半角数字でなければ、true を返す。
*/
function isNotANumber( obj, alert_option, focus_option )
{
	if ( isNaN(obj.value) )
	{
		if ( alert_option )
			alert("半角数字で入力して下さい。");
		if ( focus_option )
		{
			obj.focus();
			obj.select();
		}
		return true;
	}
	else if ( obj.value.indexOf(".") > -1 )
	{
		if ( alert_option )
			alert("小数点は使えません。");
		if ( focus_option )
		{
			obj.focus();
			obj.select();
		}
		return true;
	}
	else
		return false;
}

//calculate the length
function showLength(obj)
{
	var i_len = 0;  //byte length
	var i = 0;
	var s_value = "";
	s_value = obj.value;
	for(i=0 ;i<s_value.length ;i++ ){
		if(isAll(s_value.charAt(i))){

			i_len += 2;
		}else{
			i_len += 1;
		}
	}//for
	alert("バイト数は"+i_len+"です。");

}//function showLength()

function getLength(obj){
  var i_len = 0;  //byte length
  var i = 0;
  var s_value = "";
  s_value = obj.value;
  for(i=0 ;i<s_value.length ;i++ ){
    if(isAll(s_value.charAt(i))){
      i_len += 2;
    }else{
      i_len += 1;
    }
  }//for
  return i_len;
}
//check string is or not DBCS
function isAll(strtemp)
{
	var i = 0;
	for(i=0;i<strtemp.length;i++){
		if(strtemp.charCodeAt(i)<10000)
			return false;
		if(strtemp.charCodeAt(i)>65370)
			return false;
	}// for
	return true;
}//function isAll(strtemp)

function blankAlert( theForm ){
  ExistFlg = false;
  for(i=55; i>5; i--){
    if(theForm.elements[i].value!="") ExistFlg=true;
    if(theForm.elements[i].value==""&&ExistFlg){
      alert("途中に空の項目があります");
      theForm.elements[i].focus();
      return true;
    }
  }
  return false;
}

//********************************************************************
//
//                  機種依存文字チェック JavaScript
//
//                             ver0.01
//
//                         R.MIZOGUCHI(xxxx)
//                            2001.08.10
//
//********************************************************************



/*
 * 機種依存文字チェックコントローラー。
 * クライアントの機種を判別して、それぞれのチェック関数を呼び出す。
 *
 * 返り値：機種依存文字がなければ true 、あればその最初の一文字。
 *
 * ※現時点では Windows のみ対応。
 */
function checkPlafChar ( check_str )
{
	// Windows の場合
	if ( navigator.userAgent.indexOf("Win") > -1 )
		return checkWindowsQualifiedCharacters( check_str );

	// それ以外のプラットフォームは、現在サポートしていない。
	return true;
}



/*
 * Windows 特有の機種依存文字をチェックする。
 * 機種依存文字がなければ true を、あればその最初の一文字を返す。
 *
 * サポートブラウザバージョン：IE3.0, NN2.0 以上。
 *
 * 2001.08.10
 */
function checkWindowsQualifiedCharacters( check_str )
{
	var result = true; // 返り値

	var plafchars = new Array
					(
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"?",
					"｡",
					"｢",
					"｣",
					"､",
					"･",
					"ｦ",
					"ｧ",
					"ｨ",
					"ｩ",
					"ｪ",
					"ｫ",
					"ｬ",
					"ｭ",
					"ｮ",
					"ｯ",
					"ｰ",
					"ｱ",
					"ｲ",
					"ｳ",
					"ｴ",
					"ｵ",
					"ｶ",
					"ｷ",
					"ｸ",
					"ｹ",
					"ｺ",
					"ｻ",
					"ｼ",
					"ｽ",
					"ｾ",
					"ｿ",
					"ﾀ",
					"ﾁ",
					"ﾂ",
					"ﾃ",
					"ﾄ",
					"ﾅ",
					"ﾆ",
					"ﾇ",
					"ﾈ",
					"ﾉ",
					"ﾊ",
					"ﾋ",
					"ﾌ",
					"ﾍ",
					"ﾎ",
					"ﾏ",
					"ﾐ",
					"ﾑ",
					"ﾒ",
					"ﾓ",
					"ﾔ",
					"ﾕ",
					"ﾖ",
					"ﾗ",
					"ﾘ",
					"ﾙ",
					"ﾚ",
					"ﾛ",
					"ﾜ",
					"ﾝ",
					"ﾞ",
					"ﾟ"
					);

	for ( i = 0 ; i < plafchars.length ; i++ )
	{
		if ( check_str.indexOf( plafchars[i] ) > -1 )
		{
			result = plafchars[i];
			break;
		}
	}

	return result;
}


/*
 * checkPlafChar() の <INPUT type="text" ...> への実装用関数
 *
 * 以下の形式でテキストフィールドに適用する
 *
 * <INPUT type="text" onBlur="pcCheckForTField(this)">
 *
 * 2001.09.19
 */
function pcCheckForTField( obj )
{
	var chkValue = checkPlafChar ( obj.value );
	// チェックＯＫなら何もしない。
	if ( chkValue == true ) return false;

	// エラーの場合はアラート表示をして、フォーカスを当てる事により、
	// 他のオブジェクトへの入力を中止させる。
	alert("入力文中の「" + chkValue + "」は使用不能文字です。\n　　　他の文字に変更してください。");
	obj.focus();
	obj.select();
	return true;
}








/*

*/
function checkCookie(){
  var EnqID;
  for(var i=1;i<6;i++){
    var EnqName="Enq"+i;
    EnqID=getCookie(EnqName);
    if(EnqID!=null){
      //alert(EnqID);
      if(EnqID!=0) showDynamicMessage(EnqID);
      //if(EnqID==0) clearDynamicMessage(EnqID);
    }
  }
}

function checkPage(value){
  var PageName="PAGE";
  var PageID=getCookie(PageName);
    if(PageID!=value){
      return true;
    }else{
			return false;
    }
}

function getCookie(name){
  var cName=name +"=";
  var cNameLen=cName.length;
  var myCookie=document.cookie;
  var startPos=myCookie.indexOf(cName);
  var endPos;
  if(document.cookie.length==0) return null;
  if(startPos!=-1){
    startPos=startPos+cNameLen;
    endPos=myCookie.indexOf(";",startPos);
    if(endPos==-1)endPos=document.cookie.length;
    return myCookie.substring(startPos,endPos);
  }
  return null;
}

function setCookie(value){
  for(var i=1;i<6;i++){
    var EnqName="Enq"+i;
    EnqID=getCookie(EnqName);
    if(EnqID==0||EnqID==null){
      window.opener.document.cookie=EnqName +"=" +value;
      //alert('set cookie');
      return null;
    }
  }
}

function setPage(value){
    var PageName="PAGE";
    PageID=getCookie(PageName);
    //if(PageID==null){
      document.cookie=PageName +"=" +value;
      //alert('set cookie');
    //}
}

function setCookie2(value){
  for(var i=1;i<6;i++){
    var EnqName="Enq"+i;
    EnqID=getCookie(EnqName);
    if(EnqID==0||EnqID==null){
      document.cookie=EnqName +"=" +value;
//      alert('set cookie');
      return null;
    }
  }
}

function setToZeroCookie(value){
  for(var i=1;i<6;i++){
    var EnqName="Enq"+i;
    EnqID=getCookie(EnqName);
    if(EnqID==value){
      window.opener.document.cookie=EnqName +"=0";
      //alert('set To Zero cookie');
      return null;
    }
  }
}

function setToZeroCookie2(value){
  for(var i=1;i<6;i++){
    var EnqName="Enq"+i;
    EnqID=getCookie(EnqName);
    if(EnqID==value){
      document.cookie=EnqName +"=0";
//      alert('set To Zero cookie');
      return null;
    }
  }
}

function clearCookie(){
  document.cookie="Enq1=0;Enq2=0;Enq3=0;Enq4=0;Enq5=0";
  //alert('clear cookie');
}

function showDynamicMessage(EnqID) {
   el = document.all(EnqID);
   el.innerHTML = '★';
 }

function clearDynamicMessage(EnqID) {
   el = document.all(EnqID);
   el.innerHTML = '☆';
 }

//半角数字チェック
function isNotHankakuSuuji ( input_obj )
{
 var len = input_obj.value.length;
 var val = input_obj.value.replace ( /[0-9]/g,"" );
 if ( val.length != 0)
 {
  alert ( "半角の数字以外は入力できません。" );
  input_obj.focus();
  input_obj.select();
  return true;
 }
 return false;
}

//全角チェック
function isNotKanji ( input_obj )
{
 var len = input_obj.value.length;
 var val = input_obj.value.replace(/[!-~。-゜ ]/g,"");
 if ( val.length != len )
 {
  alert("全角漢字で入力して下さい。");
  input_obj.focus();
  input_obj.select();
  return true;
 }
 return false;
}

function wopen(wopen){
	window.open(wopen,"check","scrollbars=yes,resizable=yes,width=400,height=300")
}


//ローカルファイルパス値の入れ替え
function replaceURL ( input_obj ){
  var atype = new Array(9);
  var ltype = new Array(9);
  atype[0]=input_obj.a_gif1;
  atype[1]=input_obj.a_png1;
  atype[2]=input_obj.a_jpg1;
  atype[3]=input_obj.a_gif2;
  atype[4]=input_obj.a_png2;
  atype[5]=input_obj.a_jpg2;
  atype[6]=input_obj.a_gif3;
  atype[7]=input_obj.a_png3;
  atype[8]=input_obj.a_jpg3;
  ltype[0]=input_obj.l_gif1;
  ltype[1]=input_obj.l_png1;
  ltype[2]=input_obj.l_jpg1;
  ltype[3]=input_obj.l_gif2;
  ltype[4]=input_obj.l_png2;
  ltype[5]=input_obj.l_jpg2;
  ltype[6]=input_obj.l_gif3;
  ltype[7]=input_obj.l_png3;
  ltype[8]=input_obj.l_jpg3;

  for(i=0;i<9;i++){
    var val = atype[i].value;
    if(val.length>0)ltype[i].value=val;
  }
}

/**
 * var ymd = new Array(3);
 * ymd[0] = theForm.y;
 * ymd[1] = theForm.m;
 * ymd[2] = theForm.d;
 * DateCheck(ymd);
 * のようにオブジェクトを配列にして渡す
 * ３フィールドの日付の整合チェックに限定しています。
 *
 */
function DateCheck(ymd){
//整合チェック
  if ( ymd[0].value != "" ){
    if ( alertEmpty( ymd[1] , "月" ) ) return true;
    if ( alertEmpty( ymd[2] , "日" ) ) return true;
  }

  if (ymd[1].value != "" ){
    if ( alertEmpty( ymd[0] , "年" ) ) return true;
    if ( alertEmpty( ymd[2] , "日" ) ) return true;
  }

  if ( ymd[2].value != "" ){
    if ( alertEmpty( ymd[0] , "年" ) ) return true;
    if ( alertEmpty( ymd[1] , "月" ) ) return true;
  }

  if ( ymd[0].value!="" && ymd[1].value!="" && ymd[2].value!="" ){
		if ( isNotANumber ( ymd[0] , true , true ) ) return true;
		if ( alertSameLength ( ymd[0] , 4 , "年" ) ) return true;
		if ( isNotSuuji ( ymd[1] , 1 , 12 , "月" ) ) return true;
		if ( isNotANumber ( ymd[2] , true , true ) ) return true;
		if ( alertSameLength ( ymd[2] , 2 , "日" ) ) return true;
		if ( !vaidateDate( ymd , true , true ) ) return true;
	}
	return false;
}

/**
 * @経過日数を計算します
 * @param yf,mf,df 始まりの日付の年、月、日
 * @param yt,mt,dt 終わりの日付の年、月、日
 * @return yf/mf/df～yt/mt/dtの経過日数を返す
 */
function getPastDaysSplitVal(yf,mf,df,yt,mt,dt){
	var ymdf = new Array(3);
	ymdf[0] = yf;
	ymdf[1] = mf;
	ymdf[2] = df;
	var ymdt = new Array(3);
	ymdt[0] = yt;
	ymdt[1] = mt;
	ymdt[2] = dt;
	return getPastDaysArray(ymdf,ymdt);
}

function getPastDaysArray(ymd_from, ymd_to){
	var ymdf=new Date(ymd_from[0].value,ymd_from[1].value,ymd_from[2].value);
	var ymdt=new Date(ymd_to[0].value,ymd_to[1].value,ymd_to[2].value);

	return getPastDays(ymdf, ymdt);
}

/**
 * 経過日数を取得する
 * @param ymdf 始まりの日付
 * @param ymdt 終わりの日付
 * @return ymdf～ymdtの経過日数
 */
function getPastDays(ymdf, ymdt){
	var ret = ymdt.getTime()-ymdf.getTime();

	return Math.ceil(ret/(24*60*60*1000));

}