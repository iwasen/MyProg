// ExcelKinmuTaisei.cpp: CExcelKinmuTaisei �N���X�̃C���v�������e�[�V����
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "workscheduler.h"
#include "ExcelKinmuTaisei.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

// �G�N�Z���J�������
static CExcelFile::SColumnInfo s_aColumnInfo[] = {
//	��̫��	�^						����	����	�ŏ�	�ő�		�L������
	{NULL,	eDataTypeAlphaNumKana,	32,		0,		0,		0,			NULL},		// ���ײݖ���
	{NULL,	eDataTypeNumber,		6,		0,		0,		999999,		NULL},		// ���ԉғ�����
	{NULL,	eDataTypeNumber,		1,		0,		0,		1,			NULL},		// �����쓮�׸�
	{"0",	eDataTypeNumber,		10,		4,		0,		100,		NULL},		// ������藦
	{"100",	eDataTypeNumber,		64,		1,		0,		100,		NULL}		// �ғ���
};

//////////////////////////////////////////////////////////////////////
// �\�z/����
//////////////////////////////////////////////////////////////////////

CExcelKinmuTaisei::CExcelKinmuTaisei()
{
	m_sFileName = "worktime.xls";
	m_pColumnInfo = s_aColumnInfo;
	m_nColumnInfo = sizeof(s_aColumnInfo) / sizeof(CExcelFile::SColumnInfo);
	m_nFileNo = 2;

	SetKeys(1, eColSublineName);
}

BOOL CExcelKinmuTaisei::AccessCheck(LPCTSTR pFolderName)
{
	return FileAccessCheck(pFolderName, "??????_??????_" + m_sFileName, TRUE);
}

CString CExcelKinmuTaisei::GetNewFileName()
{
	return g_pSystem->m_cSaveTime.Format("%y%m%d_%H%M%S_") + m_sFileName;
}
