// Const.h: CConst �N���X�̃C���^�[�t�F�C�X
//
//////////////////////////////////////////////////////////////////////

#if !defined(AFX_CONST_H__E27E1431_0DBD_4132_8951_DC043D5E59A4__INCLUDED_)
#define AFX_CONST_H__E27E1431_0DBD_4132_8951_DC043D5E59A4__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

#define MAX_KISHU		5		// �ő�@�퐔
#define MAX_SUBLINE		15		// �ő�T�u���C����
#define MAX_UNDO		6		// �ő�Undo���i�{�P�������j

class CConst
{
public:
	CConst();

	CString m_sVersion;
	CString m_sDefaultFontName;
	CString m_sJido;
	CString m_sHanJido;
	CString m_sShudo;
	CString m_sEngShiseiFlag;
	CString m_sSeiritsuFr;
	CString m_sSeiritsuRr;
	CString m_sToritsuFr;
	CString m_sToritsuRr;
	CString m_sChokuritsuTop;
	CString m_sChokuritsuBottom;
	CString m_sJoryu;
	CString m_sKaryu;
	CString m_sKijun;
	CString m_sKotei;
};

#endif // !defined(AFX_CONST_H__E27E1431_0DBD_4132_8951_DC043D5E59A4__INCLUDED_)
