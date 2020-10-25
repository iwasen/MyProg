//post���Ď��̃y�[�W�ֈړ�
function doPostData(theForm, nextURL){
    with(theForm){
        action = nextURL;
        method = 'post';
        submit();
    }
}

//get�Ŏ��̃y�[�W�ֈړ�
function doGetData(theForm, nextURL){
    with(theForm){
        action = nextURL;
        method = 'get';
        submit();
    }
}

//�����o�[�Y���O�C�����
function c_memberLogin(theForm, nextURL){
    with(theForm){
        if(isNull(id, "", false, false)||isNull(pw, "", false, false)||
            isNotNumber(id, "", false, false)||isNotAsciiEisu(pw, "", false, false)
        ){
            alert("����ԍ� �܂��� ���O�C���p�X���[�h������������܂���B");
            id.focus();
            return;
        }
    }

    doPostData(theForm, nextURL);
}

//ogc_fun/form�p
//�o�^���ڃ`�F�b�N
function c_ogcFunEntryF(theForm, nextURL){
    with(theForm){
    //�K�{����
        if(isNull(familyName, "����", true, true)) return;
        if(isNotZenkaku(familyName, "����", true, true)) return;
        if(gtMaxColumn(familyName, 15, "����", true, true)) return;
        if(!chkDependChar(familyName, "����", true, true)) return;
        if(isFullSpaceOnly(familyName, "����")) return;
        if(isNull(givenName, "����", true, true)) return;
        if(isNotZenkaku(givenName, "����", true, true)) return;
        if(gtMaxColumn(givenName, 15, "����", true, true)) return;
        if(!chkDependChar(givenName, "����", true, true)) return;
        if(isFullSpaceOnly(givenName, "����")) return;
        if(isNotMailAddress(mailEntry, 1, "���[���A�h���X", true, true)) return;
        if(isNotMailAddress(mailEntryAscert, 1, "���[���A�h���X", true, true)) return;
        if(cmpNotTwoValues(mailEntry, mailEntryAscert, "���[���A�h���X", true, true)) return;

    //�A���P�[�g
        if(isNotNull(q2, "", false, false)){
            if(isNotNumber(q2, "���܂ꂽ�N", true, true)) return;
            if(isNotSameColumn(q2, 4, "���܂ꂽ�N", true, true)) return;
            if(q2.value<1800){
                alert("���܂ꂽ�N��1800�N�ȍ~�ɐݒ肵�Ă�������");
                q2.focus();
                return;
            }
        }
        if(isNotNull(q5, "", false, false)){
            if(!chkDependChar(q5, "���ӌ���", true, true)) return;
            if(!chkNGChar(q5, "���ӌ���", true, true)) return;
        }
    }

    doPostData(theForm, nextURL);
}

//ogc_fun/form�p
//�މ�ڃ`�F�b�N
function c_ogcFunResignF(theForm, nextURL){
    with(theForm){
    //�K�{����
        if(isNotMailAddress(email, 1, "���[���A�h���X", true, true)) return;
        if(isNotMailAddress(emailAscert, 1, "�m�F�p���[���A�h���X", true, true)) return;
        if(cmpNotTwoValues(emailAscert, email, "���[���A�h���X", true, true)) return;
    }

    doPostData(theForm, nextURL);
}

//ogc_exp/form�p
//OGC Express�w��
function c_ogcExpEntryF(theForm, nextURL){
    with(theForm){
//        if(isNull(mailEntry, "���[���A�h���X", true, true)) return;
        if(isNotMailAddress(mailEntry, 1, "���[���A�h���X", true, true)) return;
        if(isNotMailAddress(mailEntryAscert, 1, "�m�F�p���[���A�h���X", true, true)) return;
        if(cmpNotTwoValues(mailEntryAscert, mailEntry, "���[���A�h���X", true, true)) return;
    }

    doPostData(theForm, nextURL);
}

//OGCEXPRESS
//�މ�ڃ`�F�b�N
function c_ogcExpResignF(theForm, nextURL){
    with(theForm){
    //�K�{����
        if(isNotMailAddress(email, 1, "���[���A�h���X", true, true)) return;
        if(isNotMailAddress(emailAscert, 1, "�m�F�p���[���A�h���X", true, true)) return;
        if(cmpNotTwoValues(emailAscert, email, "���[���A�h���X", true, true)) return;
    }

    doPostData(theForm, nextURL);
}

//OGC����t�H�[���`�F�b�N
function c_entryF(theForm, nextURL){
    with(theForm){
        if(isListDefault(institutionCode, "���Z�@��", true, true)) return;
        if(isNull(branchName, "�x�X��", true, true)) return;
        if(isNotZenkaku(branchName, "�x�X��", true, true)) return;
        if(gtMaxColumn(branchName, 10, "�x�X��", true, true)) return;
        if(chkNotDependChar(branchName, "�x�X��", true, true)) return;
        if(isFullSpaceOnly(branchName, "�x�X��")) return;
        if(isNull(branchCode, "�X�ԍ�", true, true)) return;
        if(isNotNumber(branchCode, "�X�ԍ�", true, true)) return;
        if(isNotSameColumn(branchCode, 3, "�X�ԍ�", true, true)) return;
        if(isListDefault(depositCode, "�a�����", true, true)) return;
        if(isNull(accountNo, "�����ԍ�", true, true)) return;
        if(isNotNumber(accountNo, "�����ԍ�", true, true)) return;
        if(isNotSameColumn(accountNo, 7, "�����ԍ�", true, true)) return;
        if(isNull(familyname_knj, "����", true, true)) return;
        if(isNotZenkaku(familyname_knj, "����", true, true)) return;
        if(gtMaxColumn(familyname_knj, 15, "����", true, true)) return;
        if(chkNotDependChar(familyname_knj, true, true)) return;
        if(isFullSpaceOnly(familyname_knj, "����")) return;
        if(isNull(givenname_knj, "����", true, true)) return;
        if(isNotZenkaku(givenname_knj, "����", true, true)) return;
        if(gtMaxColumn(givenname_knj, 15, "����", true, true)) return;
        if(chkNotDependChar(givenname_knj, true, true)) return;
        if(isFullSpaceOnly(givenname_knj, "����")) return;
        if(isNull(familyname_kna, "�J�i����", true, true)) return;
        if(gtMaxColumn(familyname_kna, 15, "�J�i����", true, true)) return;
        if(isNotKatakana(familyname_kna, "�J�i����", true, true)) return;
        if(chkNotDependChar(familyname_kna, true, true)) return;
        if(isNull(givenname_kna, "�J�i����", true, true)) return;
        if(gtMaxColumn(givenname_kna, 15, "�J�i����", true, true)) return;
        if(isNotKatakana(givenname_kna, "�J�i����", true, true)) return;
        if(chkNotDependChar(givenname_kna, true, true)) return;
        if(isNull(pw, "���O�C���p�X���[�h", true, true)) return;
        if(isNotAsciiEisu(pw, "���O�C���p�X���[�h", true, true)) return;
        if(ltMinColumn(pw, 6, "���O�C���p�X���[�h", true, true)) return;
        if(gtMaxColumn(pw, 8, "���O�C���p�X���[�h", true, true)) return;
        if(isAllSameChar(pw, "���O�C���p�X���[�h", true, true)) return;
        if(isNull(pwAscert, "�m�F�p���O�C���p�X���[�h", true, true)) return;
        if(isNotAsciiEisu(pwAscert, "�m�F�p���O�C���p�X���[�h", true, true)) return;
        if(ltMinColumn(pwAscert, 6, "�m�F�p���O�C���p�X���[�h", true, true)) return;
        if(gtMaxColumn(pwAscert, 8, "�m�F�p���O�C���p�X���[�h", true, true)) return;
        if(isAllSameChar(pwAscert, "�m�F�p���O�C���p�X���[�h", true, true)) return;
        if(cmpNotTwoValues(pwAscert, pw, "���O�C���p�X���[�h", true, true)) return;
        if(isNotDate(birthDateY, birthDateM, birthDateD, "00", "���N����", true, true)) return;
        if(isNotRadioChecked(sex, "����", true, true)) return;
        if(isNotPostalCode(homeZip1, homeZip2, 1, "����X�֔ԍ�", true, true)) return;
        if(isListDefault(homePrefecture, "�s���{��", true, true)) return;
        if(isNull(homeAddr1, "����Z��", true, true)) return;
        if(isFullSpaceOnly(homeAddr1, "����Z��")) return;
        if(isNotZenkaku(homeAddr1, "����Z��", true, true)) return;
        if(gtMaxColumn(homeAddr1, 25, "����Z��", true, true)) return;
        if(chkNotDependChar(homeAddr1, true, true)) return;
        if(isNotZenkaku(homeAddr2, "����Z��", true, true)) return;
        if(gtMaxColumn(homeAddr2, 25, "����Z��", true, true)) return;
        if(chkNotDependChar(homeAddr2, true, true)) return;
        if(isNotTelNumber(homeTel1, homeTel2, homeTel3, 1, "����d�b�ԍ�", true, true)) return;
        if(isNotMailAddress(mail, 1, "���[���A�h���X", true, true)) return;
        if(gtMaxColumn(mail, 64, "���[���A�h���X", true, true)) return;
        if(isNotMailAddress(mailAscert, 1, "�m�F�p���[���A�h���X", true, true)) return;
        if(cmpNotTwoValues(mailAscert,mail, "���[���A�h���X", true, true)) return;
        if(isListDefault(jobCode, "�E��", true, true)) return;
        if(gtMaxColumn(q8, 200, "���ӌ��E����]", true, true)) return;
        if(chkJobPlace(daytimeContact, jobCode, businessTel1, businessTel2, businessTel3,
             otherTel1, otherTel2, otherTel3, mailingZip1, mailingZip2, mailingPrefecture, mailingAddr1, mailingAddr2)) return;
        if(!chkNGChar(q8, "���ӌ��E����]", true, true)) return;
        if(chkNotDependChar(q8, true, true)) return;
    }
    doPostData(theForm, nextURL);
}


//�A����`�F�b�N(����ȊO)
function chkJobPlace(daytimeContact, jobCode, businessTel1, businessTel2, businessTel3,
 otherTel1, otherTel2, otherTel3, mailingZip1, mailingZip2, mailingPrefecture, mailingAddr1, mailingAddr2){
        var p_nes = new Array(2);
        for(i=0; i<p_nes.length; i++){
            p_nes[i] = 0;
        }

//�����̘A���悪����łȂ��Ƃ��̃`�F�b�N
        switch(getRadioValue(daytimeContact)){
            case "2"://�Ζ���
                p_nes[0] = 1;
                break;
            case "3"://���̑�
                p_nes[1] = 1;
                break;
            default:
                break;
        }

        if(gtMaxColumn(mailingAddr1, 25, "�X�֕����t��̏Z��", true, true)) return true;
        if(gtMaxColumn(mailingAddr2, 25, "�X�֕����t��̏Z��", true, true)) return true;
        if(isNotRadioChecked(daytimeContact, "���Ԃ̘A����", true, true)) return true;
        if(isNotTelNumber(businessTel1, businessTel2, businessTel3, p_nes[0], "�Ζ���d�b�ԍ�", true, true)) return true;
        if(isNotTelNumber(otherTel1, otherTel2, otherTel3, p_nes[1], "���̑��̓d�b�ԍ�", true, true)) return true;

//�X����̐ݒ肪���������`�F�b�N
        if(chkAddress(mailingZip1, mailingZip2, mailingPrefecture, mailingAddr1, mailingAddr2)){
//            alert("�X�֕��X����̐ݒ肪����������܂���B");
//          mailingZip1.focus();
            return true;
        }

        return false;
}

//�Z���̑g���킹�����������`�F�b�N
function chkAddress(Zip1, Zip2, Prefecture, Addr1, Addr2){
    var NotNullFlg = isNotNull(Zip1, "", false, false) & isNotNull(Zip2, "", false, false)&
                                    isNotListDefault(Prefecture, "", false, false) & isNotNull(Addr1, "", false, false);
    var AllNullFlg = isNull(Zip1, "", false, false) & isNull(Zip2, "", false, false)&
                                    isListDefault(Prefecture, "", false, false) &
                                    isNull(Addr1, "", false, false) & isNull(Addr2, "", false, false);
    var SendFlg = !(NotNullFlg | AllNullFlg);

    if(SendFlg){
            alert("�X�֕����t��̐ݒ肪����������܂���B");
            Zip1.focus();
        return true;
    }
    if(isNotPostalCode(Zip1, Zip2, 0, "�X�֕����t��X�֔ԍ�", true, true)) return true;
    if(isNotZenkaku(Addr1, "�X�֕����t��Z��", true, true)) return true;
    if(isNotZenkaku(Addr2, "�X�֕����t��Z��", true, true)) return true;
    if(chkNotDependChar(Addr1, true, true)) return true;
    if(chkNotDependChar(Addr2, true, true)) return true;
    return false;
}
