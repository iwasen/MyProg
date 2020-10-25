//*****************************************************************************************************
//  1. �t�@�C����
//		General.cpp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�ėp�֐��N���X�̎���
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2007.08.09 S.Aizawa �V�K�쐬
//*****************************************************************************************************

#include "StdAfx.h"
#include "General.h"
#include "DigitalFilter.h"
#include "GlobalData.h"
#include <math.h>
#include <direct.h>
#include <io.h>

//*****************************************************************************************************
//  1. �֐���
//		CGeneral::FormatTime
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�~���b�P�ʂ̐��l����"HH:MM:SS.999"�`���ɕϊ�
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		double		fSecond			[I] �b��
//		int			nDecimal		[I] �~���b�ȉ��̌���
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		CString		�ϊ�����������
//*****************************************************************************************************
CString CGeneral::FormatTime(double fSecond, int nDecimal)
{
	CString sText;

	int nDigit = 1;
	for (int i = 0; i < nDecimal; i++)
		nDigit *= 10;

	int nMiliSecond = (int)(fSecond * nDigit + 0.5);
	int nHour = nMiliSecond / (60 * 60 * nDigit);
	nMiliSecond -= nHour * (60 * 60 * nDigit);

	int nMinute = nMiliSecond / (60 * nDigit);
	nMiliSecond -= nMinute * (60 * nDigit);

	int nSecond = nMiliSecond / nDigit;
	nMiliSecond -= nSecond * nDigit;

	if (nHour >= 24)
		nHour = nHour % 24;

	if (nDecimal) {
		CString sFormat;
		sFormat.Format("%%02d:%%02d:%%02d.%%0%dd", nDecimal);
		sText.Format(sFormat, nHour, nMinute, nSecond, nMiliSecond);
	} else
		sText.Format("%02d:%02d:%02d", nHour, nMinute, nSecond);

	return sText;
}

//*****************************************************************************************************
//  1. �֐���
//		CGeneral::Alert
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�A���[�g���b�Z�[�W�\��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		LPCTSTR		pMessage			[I] �\�����郁�b�Z�[�W
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGeneral::Alert(LPCTSTR pMessage, ...)
{
	va_list args;
	va_start(args, pMessage);

	CString sMessage;
	sMessage.FormatV(pMessage, args);

	CWnd::GetActiveWindow()->MessageBox(sMessage, NULL, MB_OK | MB_ICONEXCLAMATION | MB_SYSTEMMODAL);
}

//*****************************************************************************************************
//  1. �֐���
//		CGeneral::SetSampleRateList
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�T���v�����O���g���̃R���{�{�b�N�X��ݒ�
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CComboBox		&cComboBox			[O] �R���{�{�b�N�X
//		double			fSampleRate			[I] �T���v�����O���g��
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGeneral::SetSampleRateList(CComboBox &cComboBox, double fSampleRate)
{
	CString sText;

	// �T���v�����O���g���̃R���{�{�b�N�X��ݒ�
	int nDiv = 1;
	// �Ԉ����\���́C1/1�C1/2�C1/4�ŊԈ���
	for (int i = 0; i < 3; i++) {
		sText.Format("1/%d", nDiv);
		cComboBox.SetItemData(cComboBox.AddString(sText), nDiv);

		nDiv *= 2;
	}

	cComboBox.SetCurSel(0);
}		

//*****************************************************************************************************
//  1. �֐���
//		CGeneral::DelDir
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�w��t�H���_���ɑ��݂���t�@�C���S�Ă��폜����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		LPCSTR		path			[I] �w��t�H���_
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		bool	true�F�����@false�F���s
//*****************************************************************************************************
bool CGeneral::DelDir(LPCSTR path)
{
	char wpath[MAX_PATH];
	struct _finddata_t c_file;
    intptr_t hFile;
	CString	str;

	memset(wpath, 0, sizeof(wpath));
	strcpy_s(wpath, path);
	strcat_s(wpath, "\\*.*");

    /* �J�����g �f�B���N�g�����̍ŏ��̃t�H���_��T���܂��B*/
	if( (hFile = _findfirst(wpath, &c_file )) != -1L ) {
		// �t�H���_�H
		if (c_file.attrib & _A_SUBDIR) {
			if (c_file.name[0] != '.') {
				str.Format("%s\\%s", path, c_file.name);
				if (! DelDir((LPCSTR)str)) {
					_findclose( hFile );
					return false;
				}
			}
		} else {
			str.Format("%s\\%s", path, c_file.name);
			if (remove((LPCSTR)str) == -1) {
				_findclose( hFile );
				return false;
			}
		}
		/* �c��̃t�H���_��T���܂��B */
		while( _findnext( hFile, &c_file ) == 0 ) {
			// �t�H���_�H
			if (c_file.attrib & _A_SUBDIR) {
				if (c_file.name[0] != '.') {
					str.Format("%s\\%s", path, c_file.name);
					if (! DelDir((LPCSTR)str)) {
						_findclose( hFile );
						return false;
					}
				}
			} else {
				str.Format("%s\\%s", path, c_file.name);
				if (remove((LPCSTR)str) == -1) {
					_findclose( hFile );
					return false;
				}
			}
		}
		_findclose( hFile );
		_rmdir(path);
	}
	return true;
}

//*****************************************************************************************************
//  1. �֐���
//		CGeneral::SetFilterBandList
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�R���{�{�b�N�X�Ƀt�B���^�̎�ނ��Z�b�g����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CComboBox		&cComboBox			[O] �R���{�{�b�N�X
//		int				nCurSel				[I] �����I���ʒu
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGeneral::SetFilterBandList(CComboBox &cComboBox, int nCurSel)
{
	cComboBox.SetItemData(cComboBox.AddString("����"), -1);
	cComboBox.SetItemData(cComboBox.AddString("LPF"), EDF_LPF);
	cComboBox.SetItemData(cComboBox.AddString("HPF"), EDF_HPF);
	cComboBox.SetItemData(cComboBox.AddString("BPF"), EDF_BPF);
	cComboBox.SetItemData(cComboBox.AddString("BSF"), EDF_BSF);

	cComboBox.SetCurSel(nCurSel);
}

//*****************************************************************************************************
//  1. �֐���
//		CGeneral::SetFilterOrderList
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�R���{�{�b�N�X�Ƀt�B���^�̎������Z�b�g����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CComboBox		&cComboBox			[O] �R���{�{�b�N�X
//		int				nCurSel				[I] �����I���ʒu
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGeneral::SetFilterOrderList(CComboBox &cComboBox, int nCurSel)
{
	CString sText;

	for (int nOrder = 1; nOrder <= 10; nOrder++) {
		sText.Format("%d", nOrder);
		cComboBox.SetItemData(cComboBox.AddString(sText), nOrder);
	}

	cComboBox.SetCurSel(nCurSel);
}

//*****************************************************************************************************
//  1. �֐���
//		CGeneral::SetFilterTypeList
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�R���{�{�b�N�X�Ƀt�B���^�̓������Z�b�g����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CComboBox		&cComboBox			[O] �R���{�{�b�N�X
//		int				nCurSel				[I] �����I���ʒu
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGeneral::SetFilterTypeList(CComboBox &cComboBox, int nCurSel)
{
	cComboBox.SetItemData(cComboBox.AddString("Butterworth"), EDF_Butterworth);
	cComboBox.SetItemData(cComboBox.AddString("Chebyshev"), EDF_Chebyshev);
	cComboBox.SetItemData(cComboBox.AddString("Bessel"), EDF_Bessel);

	cComboBox.SetCurSel(nCurSel);
}

//*****************************************************************************************************
//  1. �֐���
//		CGeneral::FormatExp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�w�肵�������̎w���`���ɕϊ�
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		double		fData			[I] �ϊ�����f�[�^
//		int			nDigit			[I] ����������
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		CString		�ϊ�����������
//*****************************************************************************************************
CString CGeneral::FormatExp(double fData, int nDigit)
{
	CString sFormat, sText;

	sFormat.Format("%%.%de", nDigit);
	sText.Format(sFormat, fData);
	int nPos = sText.Find('e');
	if (nPos >= 0) {
		CString sExp;
		sExp.Format("%d", atoi(sText.Mid(nPos + 1)));
		return sText.Left(nPos + 1) + sExp;
	} else
		return sText;
}

//*****************************************************************************************************
//  1. �֐���
//		CGeneral::SetToolTip
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�c�[���`�b�v��ݒ肷��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CToolTipCtrl	&tooltip			[I] �c�[���`�b�v�R���g���[��
//		SToolTipData	*pToolTipData		[I] �c�[���`�b�v�\���f�[�^
//		CWnd			*pWnd				[I] �E�B���h�E�|�C���^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGeneral::SetToolTip(CToolTipCtrl &tooltip, const SToolTipData *pToolTipData, CWnd *pWnd)
{
	tooltip.Create(pWnd);

	while (pToolTipData->nID) {
		CWnd *pWndControl = pWnd->GetDlgItem(pToolTipData->nID);
		if (pWndControl != NULL)
			tooltip.AddTool(pWndControl, pToolTipData->pMsg);
		pToolTipData++;
	}

	tooltip.SetMaxTipWidth(400);
	tooltip.EnableToolTips();
}

//*****************************************************************************************************
//  1. �֐���
//		CGeneral::StrSplit
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�Z�p���[�^�ŋ�؂�ꂽ������𕪉����Ĕz��ɃZ�b�g����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		LPCTSTR			pText			[I] ������
//		LPCTSTR			pSeparator		[I] �Z�p���[�^
//		CStringArray	&saText			[O] ������z��
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGeneral::StrSplit(LPCTSTR pText, LPCTSTR pSeparator, CStringArray &saText)
{
	saText.RemoveAll();

	int nPos= 0;
	CString sText(pText);
	CString sToken = sText.Tokenize(pSeparator, nPos);

	while (sToken != "") {
		saText.Add(sToken);
		sToken = sText.Tokenize(pSeparator, nPos);
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CGeneral::StrJoin
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		������z����Z�p���[�^�ŘA������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CStringArray	&saText			[I] ������z��
//		LPCTSTR			pSeparator		[I] �Z�p���[�^
//		CString			&sText			[O] �A������������
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGeneral::StrJoin(const CStringArray &saText, LPCTSTR pSeparator, CString &sText)
{
	sText.Empty();

	for (int i = 0; i < saText.GetSize(); i++) {
		if (i != 0)
			sText += pSeparator;

		sText += saText[i];
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CGeneral::CheckFileName
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�t�@�C�����i�f�B���N�g�����j�Ƃ��ėL�����`�F�b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CString			sText			[I] �`�F�b�N���镶����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL		TRUE:�L���CFALSE:����
//*****************************************************************************************************
BOOL CGeneral::CheckFileName(CString sText)
{
	return sText.SpanExcluding("\\/:*?\"<>|") == sText;
}

//*****************************************************************************************************
//  1. �֐���
//		CGeneral::SetComboCategory
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�R���{�{�b�N�X�ɃJ�e�S���̑I������ݒ肷��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CComboBox		&oComboBox			[I] �J�e�S���̃R���{�{�b�N�X
//		CString			sChoiceText			[O] ���ݐݒ肳��Ă��镶����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGeneral::SetComboCategory(CComboBox &oComboBox, CString sChoiceText)
{
	oComboBox.ResetContent();
	for (int i = 0; i < gChoiceCategory.GetSize(); i++) {
		if (CheckFileName(gChoiceCategory[i]))
			oComboBox.AddString(gChoiceCategory[i]);
	}

	oComboBox.SetWindowText(sChoiceText);
}

//*****************************************************************************************************
//  1. �֐���
//		CGeneral::SetComboMeasurementKind
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�R���{�{�b�N�X�Ɍv����ނ̑I������ݒ肷��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CComboBox		&oComboBox			[I] �J�e�S���̃R���{�{�b�N�X
//		CString			sChoiceText			[O] ���ݐݒ肳��Ă��镶����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGeneral::SetComboMeasurementKind(CComboBox &oComboBox, CString sChoiceText)
{
	oComboBox.ResetContent();
	for (int i = 0; i < gChoiceMeasurementKind.GetSize(); i++) {
		if (CheckFileName(gChoiceMeasurementKind[i]))
			oComboBox.AddString(gChoiceMeasurementKind[i]);
	}

	oComboBox.SetWindowText(sChoiceText);
}

//*****************************************************************************************************
//  1. �֐���
//		CGeneral::CheckFileName
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�t�@�C�����i�f�B���N�g�����j�Ƃ��ėL�����`�F�b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CString			sItem			[I] ���ږ�
//		CString			sText			[I] �`�F�b�N���镶����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL		TRUE:�L���CFALSE:����
//*****************************************************************************************************
BOOL CGeneral::CheckFileName(CString sItem, CString sText)
{
	if (!CheckFileName(sText)) {
		Alert("%s�Ɂu\\/:*?\"<>|�v���܂ނ��Ƃ͂ł��܂���B", sItem);
		return FALSE;
	}

	return TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CGeneral::CreateDirectoryAll
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�w�肳�ꂽ�f�B���N�g���܂őS�Ă̊K�w�̃f�B���N�g�����쐬����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		LPCTSTR			pDirPath		[I] �쐬����f�B���N�g��
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL		TRUE:����CFALSE:�G���[
//*****************************************************************************************************
BOOL CGeneral::CreateDirectoryAll(LPCTSTR pDirPath)
{
	DWORD err;
	char *p;
	char buf[_MAX_PATH];

	if (!::CreateDirectory(pDirPath, NULL)) {
		err = GetLastError();
		if (err == ERROR_ALREADY_EXISTS)
			return TRUE;
		else if (err == ERROR_PATH_NOT_FOUND) {
			strcpy_s(buf, pDirPath);
			if ((p = strrchr(buf, '\\')) != NULL) {
				*p = '\0';
				if (CreateDirectoryAll(buf))
					return CreateDirectory(pDirPath, NULL);
				else
					return FALSE;
			} else
				return FALSE;
		} else
			return FALSE;
	}

	return TRUE;
}
