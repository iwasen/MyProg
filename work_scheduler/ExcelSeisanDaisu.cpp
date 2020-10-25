// ExcelSeisanDaisu.cpp: CExcelSeisanDaisu �N���X�̃C���v�������e�[�V����
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "workscheduler.h"
#include "ExcelSeisanDaisu.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

// �G�N�Z���J�������
static CExcelFile::SColumnInfo s_aColumnInfo[] = {
//	��̫��	�^					����	����	�ŏ�	�ő�		�L������
	{NULL,	eDataTypeAlphaNum,	6,		0,		0,		0,			NULL},		// �@��
	{NULL,	eDataTypeNumber,	6,		0,		0,		0,			NULL},		// �N��
	{NULL,	eDataTypeNumber,	8,		0,		0,		99999999,	NULL}		// ���Y�䐔
};

//////////////////////////////////////////////////////////////////////
// �\�z/����
//////////////////////////////////////////////////////////////////////

CExcelSeisanDaisu::CExcelSeisanDaisu()
{
	m_sFileName = "prdnum.xls";
	m_pColumnInfo = s_aColumnInfo;
	m_nColumnInfo = sizeof(s_aColumnInfo) / sizeof(CExcelFile::SColumnInfo);
	m_nFileNo = 1;
}

BOOL CExcelSeisanDaisu::AccessCheck(LPCTSTR pFolderName)
{
	return FileAccessCheck(pFolderName, "??????_??????_" + m_sFileName, TRUE);
}

CString CExcelSeisanDaisu::GetNewFileName()
{
	return g_pSystem->m_cSaveTime.Format("%y%m%d_%H%M%S_") + m_sFileName;
}
