//FORM���M
function postForm(theForm,servlet){
   with(theForm){
      action=servlet;
      method='post';
      submit();
  }
}


//button���M
function pageMove (servlet){
  location.href=servlet;
}

//�S�p�`�F�b�N
function isNotZenkaku ( input_obj )
{
	var len = input_obj.value.length;
	var val = input_obj.value.replace(/[!-~�B-�K ]/g,"");
	if ( val.length != len )
	{
		alert("�S�p�����œ��͂��ĉ������B");
		input_obj.focus();
		input_obj.select();
		return true;
	}
	return false;
}

//�S�p�Ђ炪�ȃ`�F�b�N
function isNotZenkakuhiragana ( input_obj )
{
	if ( input_obj.value.replace(/[�����������������������������������������������������������������������ÂĂłƂǂȂɂʂ˂̂͂΂ςЂт҂ӂԂՂւׂ؂قڂۂ܂݂ނ߂�������������������J�K�T�U�[]/g,"").length != 0 )
	{
		alert("�S�p�Ђ炪�ȈȊO�͎g���܂���B");
		input_obj.focus();
		input_obj.select();
		return true;
	}

	return false;
}


//�S�p�J�^�J�i�`�F�b�N
function isNotZenkakuKatakana ( input_obj )
{
	if ( input_obj.value.replace(/[�@�A�B�C�D�E�F�G�H�I�J�K�L�M�N�O�P�Q�R�S�T�U�V�W�X�Y�Z�[�\�]�^�_�`�a�b�c�d�e�f�g�h�i�j�k�l�m�n�o�p�q�r�s�t�u�v�w�x�y�z�{�|�}�~�����������������������������������������������[�R�S]/g,"").length != 0 )
	{
		alert("�S�p�J�i�ȊO�͎g���܂���B");
		input_obj.focus();
		input_obj.select();
		return true;
	}

	return false;
}

//���W�I�{�^���`�F�b�N
function alertNoCheckedRadio ( radio_obj, message )
{
	var flag = true;
	for ( i = 0 ; i < radio_obj.length ; i++ )
		if( radio_obj[i].checked ) flag = false;
	if ( flag )
	{
		alert( message + "�I��K�{���ڂł��B\n�@�@�@�I�������肢���܂��B");
		radio_obj[0].focus();
	}
	return flag;
}

//�`�F�b�N�{�b�N�X�N���A
function clearCheck ( checkbox_obj )
{
	for ( i = 0; i < checkbox_obj.length; i++ )
		checkbox_obj[i].checked = false;
}

//���͕K�{���ڃ`�F�b�N
function alertEmpty ( input_obj, message)
{
       	if (input_obj.value == null || input_obj.value == "" )
	{
		alert(message + "���͕K�{���ڂł��B\n�@�@�@���͂����肢���܂��B");
		input_obj.focus();
		return true;
	}
	return false;
}

//�������`�F�b�N(�������ȓ�)
function alertOverLength ( input_obj, len )
{
	if ( input_obj.value.length > len )
	{
		alert(len + "�����ȓ��œ��͂��ĉ������B");
		input_obj.focus();
		input_obj.select();
		return true;
	}
	return false;
}
//�������`�F�b�N(�������ȏ�)
function alertShortLength ( input_obj, len )
{
	if ( input_obj.value.length < len )
	{
		alert(len + "�����ȏ�œ��͂��ĉ������B");
		input_obj.focus();
		input_obj.select();
		return true;
	}
	return false;
}

//�������`�F�b�N(�o�C�g��)
function alertOverByte(input_obj, len){
  if(getLength(input_obj)>len){
    alert(len+"�o�C�g�ȓ��œ��͂��Ă��������B");
    input_obj.focus();
    input_obj.select();
    return true;
  }
  return false;
}

//�������`�F�b�N(�������w��)
function alertSameLength ( input_obj, len, message )
{
	if ( input_obj.value.length != len )
	{
		alert( message + "��" + len + "���ŋL�����ĉ������B");
		input_obj.focus();
		input_obj.select();
		return true;
	}
	return false;
}

//���p�p���E�����`�F�b�N
function isNotHankaku ( input_obj )
{
	var len = input_obj.value.length;
	var val = input_obj.value.replace ( /[0-9A-Za-z]/g,"" );
	if ( val.length != 0)
	{
		alert ( "���p�̉p���A�����ȊO���L������Ă��Ȃ����m�F���ĉ������B" );
		input_obj.focus();
		input_obj.select();
		return true;
	}
	return false;
}

//���p�����E�����`�F�b�N
function isNotValidPostalCode ( input_obj, clen, message )
{
	var len = input_obj.value.length;
	var val = input_obj.value.replace(/[0-9]/g,"");
	if ( val.length != 0 )
	{
		alert(message + "���p�����ȊO�͓��͂ł��܂���B");
		input_obj.focus();
		input_obj.select();
		return true;
	}
	if ( len != clen )
	{
		alert(message + clen + "���̔��p�����œ��͂��ĉ������B");
		input_obj.focus();
		input_obj.select();
		return true;
	}
	return false;
}

//�����`�F�b�N
function isNotSuuji ( input_obj, min, max, message )
{
	var len = input_obj.value.length;
	var val = input_obj.value;
	if ( isNotANumber ( input_obj, true, true ) ) return true;
	if ( val < min || max < val )
	{
		alert ( message + "��" + min + "�`" + max + "�̒l����͂��Ă��������B" );
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

//�e�L�X�g���l��r
function textnumerical ( textA, textB ){
	if(parseInt(textA,10) > parseInt(textB,10)){
		alert("�N��s���m�ł��B\n�@�ݒ肵�����ĉ������B");
		return true;
	}
	return false;
}

/*
//�e�L�X�g���l��r(�I�u�W�F�N�g��)
function textnumerical ( objA, objB )
{
        if ( parseInt(objA.value) > ParseInt(objB.value) )
        {
                alert ( "�N��s���m�ł��B\n�@�ݒ肵�����ĉ������B" );
                objA.focus();
                objA.select();
                return true;
        }
        return false;
}
*/
//�N������r
function ymdnumerical ( n_year, n_month, n_day, n2_year, n2_month, n2_day, message )
{
        num1 = n_year + n_month + n_day;
        num2 = n2_year + n2_month + n2_day;
        if ( num1 > num2 )
        {
                alert ( message + "�s���m�ł��B\n �ݒ肵�����ĉ������B" );
                return true;
        }
        return false;
}

//�N������r�z���
function ymdnumericalArray(ymd_from, ymd_to){
	return ymdnumerical(ymd_from[0].value,ymd_from[1].value,ymd_from[2].value,
								ymd_to[0].value,ymd_to[1].value,ymd_to[2].value,"���t�̎w�肪");
}


//���[���A�h���X�`�F�b�N
function isNotValidMailAddress ( obj )
{
	if ( obj.value.substring( 0 , 1 ) == "@" )
	{
		alert( "���[���A�h���X���s���ł��B" );
		obj.focus();
		obj.select();
		return true;
	}
	if ( obj.value.indexOf("@") == -1 )
	{
		alert("@ �}�[�N������܂���B");
		obj.focus();
		obj.select();
		return true;
	}
	if ( obj.value.replace(/[!-~]/g,"") != 0 )
	{
		alert("���p�p�����ȊO�͎g���܂���B");
		obj.focus();
		obj.select();
		return true;
	}
	if ( obj.value.substring ( obj.value.indexOf("@") + 1, obj.value.length ).indexOf("@") != -1 )
	{
		alert("@ �}�[�N���Q�ȏ゠��܂��B");
		obj.focus();
		obj.select();
		return true;
	}

	var user = obj.value.split("@")[0];
	var domain = obj.value.split("@")[1];

	var user_message = "���[���A�h���X�̃��[�U�[��";

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

	var domain_message = "���[���A�h���X�̃h���C����";

	if ( domain.indexOf(".") == -1 )
	{
		alert(domain_message + "�ɂ� . ���K�v�ł��B");
		obj.focus();
		obj.select();
		return true;
	}

	if ( domain.charAt(0).replace(/[0-9a-zA-Z]/g,"").length != 0 )
	{
		alert(domain_message + "�̍ŏ��̈ꕶ�����p�����ł͂���܂���B");
		obj.focus();
		obj.select();
		return true;
	}

	var domain_len = domain.length;

	if ( domain.replace(/[.\-0-9a-zA-Z]/g,"").length != 0 )
	{
		alert(domain_message + "�Ɂu�p�����v�u-�v�u.�v�ȊO�̕������g���Ă��܂��B");
		obj.focus();
		obj.select();
		return true;
	}

	if ( domain.charAt(domain_len - 1).replace(/[0-9a-zA-Z]/g,"").length != 0 )
	{
		alert(domain_message + "�̍Ō�̈ꕶ�����p�����ł͂���܂���B");
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
		alert( message + "�� " + c + " �͎g���܂���B");
		input_obj.focus();
		input_obj.select();
		return true;
	}
	return false;
}

//��r���ڃ`�F�b�N
function isNotEquals ( obj1, obj2, message )
{
	if ( obj1.value != obj2.value )
	{
		alert(message + "����v���܂���B");
		obj2.focus();
		obj2.select();
		return true;
	}
	return false;
}

//�N�E���E���`�F�b�N
function isValidateDate( year, month, day, alert_option, focus_option )
{
//	var alert_option = true; // �A���[�g���b�Z�[�W���o�����ǂ����̐ݒ�B(true:�L�� false:����)
//	var focus_option = true; // �G���[������ɁA�ΏۂƂȂ���͗��Ƀt�H�[�J�X�����킹�邩�ǂ����̐ݒ�
	                         // (true:���킹�� false:���킹�Ȃ�)

	var obj = new Array(  // �ΏۂƂȂ�t�H�[���I�u�W�F�N�g�����ĉ������B
		year,   // �u�N�v�̓��͗�
		month,  // �u���v�̓��͗�
		day     // �u���v�̓��͗�
	);

	// ���͔N��������������� true ��Ԃ��B
	return ( vaidateDate( obj, alert_option, focus_option ) );
}

function vaidateDate( obj, alert_option, focus_option )
{
//	var y = new String("19") +  obj[0].value;//�S���łɏC��
	var y = obj[0].value;
	var m = obj[1].value;
	var d = obj[2].value;

	// ���p�����`�F�b�N
	if ( isNotANumber( obj[0], alert_option, focus_option ) ) return false;
	if ( isNotANumber( obj[1], alert_option, focus_option ) ) return false;
	if ( isNotANumber( obj[2], alert_option, focus_option ) ) return false;

	if ( m < 1 || 12 < m )
	{
		if ( alert_option )
			alert("1�`12�̒l����͂��ĉ������B");
		if ( focus_option )
		{
			obj[1].focus();
			obj[1].select();
		}
		return false;
	}

	// �P�E�R�E�T�E�V�E�W�E�P�O�E�P�Q���̏ꍇ
	if ( m == 1 || m == 3 || m == 5 || m == 7 || m == 8 || m == 10 || m == 12 )
	{
		if ( d < 1 || 31 < d )
		{
			if ( alert_option )
				alert("1�`31�̒l����͂��ĉ������B");
			if ( focus_option )
			{
				obj[2].focus();
				obj[2].select();
			}
			return false;
		}
	}
	// �S�E�U�E�X�E�P�P���̏ꍇ
	else if ( m == 4 || m == 6 || m == 9 || m == 11 )
	{
		if ( d < 1 || 30 < d )
		{
			if ( alert_option )
				alert("1�`30�̒l����͂��ĉ������B");
			if ( focus_option )
			{
				obj[2].focus();
				obj[2].select();
			}
			return false;
		}
	}
	// �Q���̏ꍇ
	else
	{
		// ���邤�N���Z
		var leafyear = false;
		if ( y % 4 == 0 ) leafyear = true;
		if ( y % 100 == 0 ) leafyear = false;
		if ( y % 400 == 0 ) leafyear = true;

		var max = leafyear ? 29 : 28;
		if ( d < 1 || max < d )
		{
			if ( alert_option )
				alert("1�`" + max + "�̒l����͂��ĉ������B");
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

//���݂��O�łȂ����m�F
//�߂�l:�ߋ����w��=true,�������w��=false;
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
      alert(message+"�����݂��O�ɐݒ肳��Ă��܂�");
    }
    return true;
  }
  return false;
}

/*
  �g�� isNaN() �֐��B
  ���p�����łȂ���΁Atrue ��Ԃ��B
*/
function isNotANumber( obj, alert_option, focus_option )
{
	if ( isNaN(obj.value) )
	{
		if ( alert_option )
			alert("���p�����œ��͂��ĉ������B");
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
			alert("�����_�͎g���܂���B");
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
	alert("�o�C�g����"+i_len+"�ł��B");

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
      alert("�r���ɋ�̍��ڂ�����܂�");
      theForm.elements[i].focus();
      return true;
    }
  }
  return false;
}

//********************************************************************
//
//                  �@��ˑ������`�F�b�N JavaScript
//
//                             ver0.01
//
//                         R.MIZOGUCHI(xxxx)
//                            2001.08.10
//
//********************************************************************



/*
 * �@��ˑ������`�F�b�N�R���g���[���[�B
 * �N���C�A���g�̋@��𔻕ʂ��āA���ꂼ��̃`�F�b�N�֐����Ăяo���B
 *
 * �Ԃ�l�F�@��ˑ��������Ȃ���� true �A����΂��̍ŏ��̈ꕶ���B
 *
 * �������_�ł� Windows �̂ݑΉ��B
 */
function checkPlafChar ( check_str )
{
	// Windows �̏ꍇ
	if ( navigator.userAgent.indexOf("Win") > -1 )
		return checkWindowsQualifiedCharacters( check_str );

	// ����ȊO�̃v���b�g�t�H�[���́A���݃T�|�[�g���Ă��Ȃ��B
	return true;
}



/*
 * Windows ���L�̋@��ˑ��������`�F�b�N����B
 * �@��ˑ��������Ȃ���� true ���A����΂��̍ŏ��̈ꕶ����Ԃ��B
 *
 * �T�|�[�g�u���E�U�o�[�W�����FIE3.0, NN2.0 �ȏ�B
 *
 * 2001.08.10
 */
function checkWindowsQualifiedCharacters( check_str )
{
	var result = true; // �Ԃ�l

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
					"�",
					"�",
					"�",
					"�",
					"�",
					"�",
					"�",
					"�",
					"�",
					"�",
					"�",
					"�",
					"�",
					"�",
					"�",
					"�",
					"�",
					"�",
					"�",
					"�",
					"�",
					"�",
					"�",
					"�",
					"�",
					"�",
					"�",
					"�",
					"�",
					"�",
					"�",
					"�",
					"�",
					"�",
					"�",
					"�",
					"�",
					"�",
					"�",
					"�",
					"�",
					"�",
					"�",
					"�",
					"�",
					"�",
					"�",
					"�",
					"�",
					"�",
					"�",
					"�",
					"�",
					"�",
					"�",
					"�",
					"�",
					"�",
					"�",
					"�",
					"�",
					"�",
					"�"
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
 * checkPlafChar() �� <INPUT type="text" ...> �ւ̎����p�֐�
 *
 * �ȉ��̌`���Ńe�L�X�g�t�B�[���h�ɓK�p����
 *
 * <INPUT type="text" onBlur="pcCheckForTField(this)">
 *
 * 2001.09.19
 */
function pcCheckForTField( obj )
{
	var chkValue = checkPlafChar ( obj.value );
	// �`�F�b�N�n�j�Ȃ牽�����Ȃ��B
	if ( chkValue == true ) return false;

	// �G���[�̏ꍇ�̓A���[�g�\�������āA�t�H�[�J�X�𓖂Ă鎖�ɂ��A
	// ���̃I�u�W�F�N�g�ւ̓��͂𒆎~������B
	alert("���͕����́u" + chkValue + "�v�͎g�p�s�\�����ł��B\n�@�@�@���̕����ɕύX���Ă��������B");
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
   el.innerHTML = '��';
 }

function clearDynamicMessage(EnqID) {
   el = document.all(EnqID);
   el.innerHTML = '��';
 }

//���p�����`�F�b�N
function isNotHankakuSuuji ( input_obj )
{
 var len = input_obj.value.length;
 var val = input_obj.value.replace ( /[0-9]/g,"" );
 if ( val.length != 0)
 {
  alert ( "���p�̐����ȊO�͓��͂ł��܂���B" );
  input_obj.focus();
  input_obj.select();
  return true;
 }
 return false;
}

//�S�p�`�F�b�N
function isNotKanji ( input_obj )
{
 var len = input_obj.value.length;
 var val = input_obj.value.replace(/[!-~�B-�K ]/g,"");
 if ( val.length != len )
 {
  alert("�S�p�����œ��͂��ĉ������B");
  input_obj.focus();
  input_obj.select();
  return true;
 }
 return false;
}

function wopen(wopen){
	window.open(wopen,"check","scrollbars=yes,resizable=yes,width=400,height=300")
}


//���[�J���t�@�C���p�X�l�̓���ւ�
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
 * �̂悤�ɃI�u�W�F�N�g��z��ɂ��ēn��
 * �R�t�B�[���h�̓��t�̐����`�F�b�N�Ɍ��肵�Ă��܂��B
 *
 */
function DateCheck(ymd){
//�����`�F�b�N
  if ( ymd[0].value != "" ){
    if ( alertEmpty( ymd[1] , "��" ) ) return true;
    if ( alertEmpty( ymd[2] , "��" ) ) return true;
  }

  if (ymd[1].value != "" ){
    if ( alertEmpty( ymd[0] , "�N" ) ) return true;
    if ( alertEmpty( ymd[2] , "��" ) ) return true;
  }

  if ( ymd[2].value != "" ){
    if ( alertEmpty( ymd[0] , "�N" ) ) return true;
    if ( alertEmpty( ymd[1] , "��" ) ) return true;
  }

  if ( ymd[0].value!="" && ymd[1].value!="" && ymd[2].value!="" ){
		if ( isNotANumber ( ymd[0] , true , true ) ) return true;
		if ( alertSameLength ( ymd[0] , 4 , "�N" ) ) return true;
		if ( isNotSuuji ( ymd[1] , 1 , 12 , "��" ) ) return true;
		if ( isNotANumber ( ymd[2] , true , true ) ) return true;
		if ( alertSameLength ( ymd[2] , 2 , "��" ) ) return true;
		if ( !vaidateDate( ymd , true , true ) ) return true;
	}
	return false;
}

/**
 * @�o�ߓ������v�Z���܂�
 * @param yf,mf,df �n�܂�̓��t�̔N�A���A��
 * @param yt,mt,dt �I���̓��t�̔N�A���A��
 * @return yf/mf/df�`yt/mt/dt�̌o�ߓ�����Ԃ�
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
 * �o�ߓ������擾����
 * @param ymdf �n�܂�̓��t
 * @param ymdt �I���̓��t
 * @return ymdf�`ymdt�̌o�ߓ���
 */
function getPastDays(ymdf, ymdt){
	var ret = ymdt.getTime()-ymdf.getTime();

	return Math.ceil(ret/(24*60*60*1000));

}