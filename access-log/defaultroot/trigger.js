//postして次のページへ移動
function doPostData(theForm, nextURL){
    with(theForm){
        action = nextURL;
        method = 'post';
        submit();
    }
}

//getで次のページへ移動
function doGetData(theForm, nextURL){
    with(theForm){
        action = nextURL;
        method = 'get';
        submit();
    }
}

//メンバーズログイン画面
function c_memberLogin(theForm, nextURL){
    with(theForm){
        if(isNull(id, "", false, false)||isNull(pw, "", false, false)||
            isNotNumber(id, "", false, false)||isNotAsciiEisu(pw, "", false, false)
        ){
            alert("会員番号 または ログインパスワードが正しくありません。");
            id.focus();
            return;
        }
    }

    doPostData(theForm, nextURL);
}

//ogc_fun/form用
//登録項目チェック
function c_ogcFunEntryF(theForm, nextURL){
    with(theForm){
    //必須項目
        if(isNull(familyName, "氏名", true, true)) return;
        if(isNotZenkaku(familyName, "氏名", true, true)) return;
        if(gtMaxColumn(familyName, 15, "氏名", true, true)) return;
        if(!chkDependChar(familyName, "氏名", true, true)) return;
        if(isFullSpaceOnly(familyName, "氏名")) return;
        if(isNull(givenName, "氏名", true, true)) return;
        if(isNotZenkaku(givenName, "氏名", true, true)) return;
        if(gtMaxColumn(givenName, 15, "氏名", true, true)) return;
        if(!chkDependChar(givenName, "氏名", true, true)) return;
        if(isFullSpaceOnly(givenName, "氏名")) return;
        if(isNotMailAddress(mailEntry, 1, "メールアドレス", true, true)) return;
        if(isNotMailAddress(mailEntryAscert, 1, "メールアドレス", true, true)) return;
        if(cmpNotTwoValues(mailEntry, mailEntryAscert, "メールアドレス", true, true)) return;

    //アンケート
        if(isNotNull(q2, "", false, false)){
            if(isNotNumber(q2, "生まれた年", true, true)) return;
            if(isNotSameColumn(q2, 4, "生まれた年", true, true)) return;
            if(q2.value<1800){
                alert("生まれた年は1800年以降に設定してください");
                q2.focus();
                return;
            }
        }
        if(isNotNull(q5, "", false, false)){
            if(!chkDependChar(q5, "ご意見等", true, true)) return;
            if(!chkNGChar(q5, "ご意見等", true, true)) return;
        }
    }

    doPostData(theForm, nextURL);
}

//ogc_fun/form用
//退会項目チェック
function c_ogcFunResignF(theForm, nextURL){
    with(theForm){
    //必須項目
        if(isNotMailAddress(email, 1, "メールアドレス", true, true)) return;
        if(isNotMailAddress(emailAscert, 1, "確認用メールアドレス", true, true)) return;
        if(cmpNotTwoValues(emailAscert, email, "メールアドレス", true, true)) return;
    }

    doPostData(theForm, nextURL);
}

//ogc_exp/form用
//OGC Express購読
function c_ogcExpEntryF(theForm, nextURL){
    with(theForm){
//        if(isNull(mailEntry, "メールアドレス", true, true)) return;
        if(isNotMailAddress(mailEntry, 1, "メールアドレス", true, true)) return;
        if(isNotMailAddress(mailEntryAscert, 1, "確認用メールアドレス", true, true)) return;
        if(cmpNotTwoValues(mailEntryAscert, mailEntry, "メールアドレス", true, true)) return;
    }

    doPostData(theForm, nextURL);
}

//OGCEXPRESS
//退会項目チェック
function c_ogcExpResignF(theForm, nextURL){
    with(theForm){
    //必須項目
        if(isNotMailAddress(email, 1, "メールアドレス", true, true)) return;
        if(isNotMailAddress(emailAscert, 1, "確認用メールアドレス", true, true)) return;
        if(cmpNotTwoValues(emailAscert, email, "メールアドレス", true, true)) return;
    }

    doPostData(theForm, nextURL);
}

//OGC入会フォームチェック
function c_entryF(theForm, nextURL){
    with(theForm){
        if(isListDefault(institutionCode, "金融機関", true, true)) return;
        if(isNull(branchName, "支店名", true, true)) return;
        if(isNotZenkaku(branchName, "支店名", true, true)) return;
        if(gtMaxColumn(branchName, 10, "支店名", true, true)) return;
        if(chkNotDependChar(branchName, "支店名", true, true)) return;
        if(isFullSpaceOnly(branchName, "支店名")) return;
        if(isNull(branchCode, "店番号", true, true)) return;
        if(isNotNumber(branchCode, "店番号", true, true)) return;
        if(isNotSameColumn(branchCode, 3, "店番号", true, true)) return;
        if(isListDefault(depositCode, "預金種別", true, true)) return;
        if(isNull(accountNo, "口座番号", true, true)) return;
        if(isNotNumber(accountNo, "口座番号", true, true)) return;
        if(isNotSameColumn(accountNo, 7, "口座番号", true, true)) return;
        if(isNull(familyname_knj, "氏名", true, true)) return;
        if(isNotZenkaku(familyname_knj, "氏名", true, true)) return;
        if(gtMaxColumn(familyname_knj, 15, "氏名", true, true)) return;
        if(chkNotDependChar(familyname_knj, true, true)) return;
        if(isFullSpaceOnly(familyname_knj, "氏名")) return;
        if(isNull(givenname_knj, "氏名", true, true)) return;
        if(isNotZenkaku(givenname_knj, "氏名", true, true)) return;
        if(gtMaxColumn(givenname_knj, 15, "氏名", true, true)) return;
        if(chkNotDependChar(givenname_knj, true, true)) return;
        if(isFullSpaceOnly(givenname_knj, "氏名")) return;
        if(isNull(familyname_kna, "カナ氏名", true, true)) return;
        if(gtMaxColumn(familyname_kna, 15, "カナ氏名", true, true)) return;
        if(isNotKatakana(familyname_kna, "カナ氏名", true, true)) return;
        if(chkNotDependChar(familyname_kna, true, true)) return;
        if(isNull(givenname_kna, "カナ氏名", true, true)) return;
        if(gtMaxColumn(givenname_kna, 15, "カナ氏名", true, true)) return;
        if(isNotKatakana(givenname_kna, "カナ氏名", true, true)) return;
        if(chkNotDependChar(givenname_kna, true, true)) return;
        if(isNull(pw, "ログインパスワード", true, true)) return;
        if(isNotAsciiEisu(pw, "ログインパスワード", true, true)) return;
        if(ltMinColumn(pw, 6, "ログインパスワード", true, true)) return;
        if(gtMaxColumn(pw, 8, "ログインパスワード", true, true)) return;
        if(isAllSameChar(pw, "ログインパスワード", true, true)) return;
        if(isNull(pwAscert, "確認用ログインパスワード", true, true)) return;
        if(isNotAsciiEisu(pwAscert, "確認用ログインパスワード", true, true)) return;
        if(ltMinColumn(pwAscert, 6, "確認用ログインパスワード", true, true)) return;
        if(gtMaxColumn(pwAscert, 8, "確認用ログインパスワード", true, true)) return;
        if(isAllSameChar(pwAscert, "確認用ログインパスワード", true, true)) return;
        if(cmpNotTwoValues(pwAscert, pw, "ログインパスワード", true, true)) return;
        if(isNotDate(birthDateY, birthDateM, birthDateD, "00", "生年月日", true, true)) return;
        if(isNotRadioChecked(sex, "性別", true, true)) return;
        if(isNotPostalCode(homeZip1, homeZip2, 1, "自宅郵便番号", true, true)) return;
        if(isListDefault(homePrefecture, "都道府県", true, true)) return;
        if(isNull(homeAddr1, "自宅住所", true, true)) return;
        if(isFullSpaceOnly(homeAddr1, "自宅住所")) return;
        if(isNotZenkaku(homeAddr1, "自宅住所", true, true)) return;
        if(gtMaxColumn(homeAddr1, 25, "自宅住所", true, true)) return;
        if(chkNotDependChar(homeAddr1, true, true)) return;
        if(isNotZenkaku(homeAddr2, "自宅住所", true, true)) return;
        if(gtMaxColumn(homeAddr2, 25, "自宅住所", true, true)) return;
        if(chkNotDependChar(homeAddr2, true, true)) return;
        if(isNotTelNumber(homeTel1, homeTel2, homeTel3, 1, "自宅電話番号", true, true)) return;
        if(isNotMailAddress(mail, 1, "メールアドレス", true, true)) return;
        if(gtMaxColumn(mail, 64, "メールアドレス", true, true)) return;
        if(isNotMailAddress(mailAscert, 1, "確認用メールアドレス", true, true)) return;
        if(cmpNotTwoValues(mailAscert,mail, "メールアドレス", true, true)) return;
        if(isListDefault(jobCode, "職業", true, true)) return;
        if(gtMaxColumn(q8, 200, "ご意見・ご希望", true, true)) return;
        if(chkJobPlace(daytimeContact, jobCode, businessTel1, businessTel2, businessTel3,
             otherTel1, otherTel2, otherTel3, mailingZip1, mailingZip2, mailingPrefecture, mailingAddr1, mailingAddr2)) return;
        if(!chkNGChar(q8, "ご意見・ご希望", true, true)) return;
        if(chkNotDependChar(q8, true, true)) return;
    }
    doPostData(theForm, nextURL);
}


//連絡先チェック(自宅以外)
function chkJobPlace(daytimeContact, jobCode, businessTel1, businessTel2, businessTel3,
 otherTel1, otherTel2, otherTel3, mailingZip1, mailingZip2, mailingPrefecture, mailingAddr1, mailingAddr2){
        var p_nes = new Array(2);
        for(i=0; i<p_nes.length; i++){
            p_nes[i] = 0;
        }

//日中の連絡先が自宅でないときのチェック
        switch(getRadioValue(daytimeContact)){
            case "2"://勤務先
                p_nes[0] = 1;
                break;
            case "3"://その他
                p_nes[1] = 1;
                break;
            default:
                break;
        }

        if(gtMaxColumn(mailingAddr1, 25, "郵便物送付先の住所", true, true)) return true;
        if(gtMaxColumn(mailingAddr2, 25, "郵便物送付先の住所", true, true)) return true;
        if(isNotRadioChecked(daytimeContact, "昼間の連絡先", true, true)) return true;
        if(isNotTelNumber(businessTel1, businessTel2, businessTel3, p_nes[0], "勤務先電話番号", true, true)) return true;
        if(isNotTelNumber(otherTel1, otherTel2, otherTel3, p_nes[1], "その他の電話番号", true, true)) return true;

//郵送先の設定が正しいかチェック
        if(chkAddress(mailingZip1, mailingZip2, mailingPrefecture, mailingAddr1, mailingAddr2)){
//            alert("郵便物郵送先の設定が正しくありません。");
//          mailingZip1.focus();
            return true;
        }

        return false;
}

//住所の組合わせが正しいかチェック
function chkAddress(Zip1, Zip2, Prefecture, Addr1, Addr2){
    var NotNullFlg = isNotNull(Zip1, "", false, false) & isNotNull(Zip2, "", false, false)&
                                    isNotListDefault(Prefecture, "", false, false) & isNotNull(Addr1, "", false, false);
    var AllNullFlg = isNull(Zip1, "", false, false) & isNull(Zip2, "", false, false)&
                                    isListDefault(Prefecture, "", false, false) &
                                    isNull(Addr1, "", false, false) & isNull(Addr2, "", false, false);
    var SendFlg = !(NotNullFlg | AllNullFlg);

    if(SendFlg){
            alert("郵便物送付先の設定が正しくありません。");
            Zip1.focus();
        return true;
    }
    if(isNotPostalCode(Zip1, Zip2, 0, "郵便物送付先郵便番号", true, true)) return true;
    if(isNotZenkaku(Addr1, "郵便物送付先住所", true, true)) return true;
    if(isNotZenkaku(Addr2, "郵便物送付先住所", true, true)) return true;
    if(chkNotDependChar(Addr1, true, true)) return true;
    if(chkNotDependChar(Addr2, true, true)) return true;
    return false;
}
