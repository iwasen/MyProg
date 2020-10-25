// ExcelBuhinKitting.h: CExcelBuhinKitting �N���X�̃C���^�[�t�F�C�X
//
//////////////////////////////////////////////////////////////////////

#if !defined(AFX_EXCELBUHINKITTING_H__C05E64AB_1449_42E6_A54B_DAA4E739A754__INCLUDED_)
#define AFX_EXCELBUHINKITTING_H__C05E64AB_1449_42E6_A54B_DAA4E739A754__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

#include "ExcelFile.h"

class CExcelBuhinKitting : public CExcelFile
{
public:
	enum ColumnNo {
		eColSublineName = 1,	// ���ײݖ�
		eColBlock = 2,			// BLOCK
		eColBuhinNo = 3,		// ����
		eColName = 4,			// ����
		eColKitingFlag = 5		// ��ĉ��׸�
	};

	CExcelBuhinKitting();

	BOOL AccessCheck(LPCTSTR pFolderName);
};

#endif // !defined(AFX_EXCELBUHINKITTING_H__C05E64AB_1449_42E6_A54B_DAA4E739A754__INCLUDED_)
