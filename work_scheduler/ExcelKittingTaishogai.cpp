// ExcelKittingTaishogai.cpp: CExcelKittingTaishogai �N���X�̃C���v�������e�[�V����
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "workscheduler.h"
#include "ExcelKittingTaishogai.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

// �G�N�Z���J�������
static CExcelFile::SColumnInfo s_aColumnInfo[] = {
//	��̫��	�^					����	����	�ŏ�	�ő�	�L������
	{NULL,	eDataTypeAlphaNum,	32,		0,		0,		0,		NULL},		// �ΏۊO���i�ԍ�
	{NULL,	eDataTypeAll,		256,	0,		0,		0,		NULL}		// �R�����g
};

//////////////////////////////////////////////////////////////////////
// �\�z/����
//////////////////////////////////////////////////////////////////////

CExcelKittingTaishogai::CExcelKittingTaishogai()
{
	m_sFileName = "kitt_del_parts.xls";
	m_pColumnInfo = s_aColumnInfo;
	m_nColumnInfo = sizeof(s_aColumnInfo) / sizeof(CExcelFile::SColumnInfo);
	m_nFileNo = 14;
}

BOOL CExcelKittingTaishogai::AccessCheck(LPCTSTR pFolderName)
{
	return FileAccessCheck(pFolderName, m_sFileName, FALSE);
}
