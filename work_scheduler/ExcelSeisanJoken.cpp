// ExcelSeisanJoken.cpp: CExcelSeisanJoken �N���X�̃C���v�������e�[�V����
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "workscheduler.h"
#include "ExcelSeisanJoken.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

// �G�N�Z���J�������
static CExcelFile::SColumnInfo s_aColumnInfo[] = {
//	��̫��	�^						����	����	�ŏ�	�ő�	�L������
	{NULL,	eDataTypeAlphaNumKana,	32,		0,		0,		0,		NULL},		// �T�u���C����
	{NULL,	eDataTypeNumber,		6,		1,		0,		24,		NULL},		// �Ζ��̐�
	{NULL,	eDataTypeNumber,		6,		0,		0,		60,		NULL},		// �x�e����
	{NULL,	eDataTypeNumber,		6,		0,		0,		31,		NULL},		// �ғ�����
	{NULL,	eDataTypeNumber,		6,		0,		0,		10,		NULL},		// ���Ζ�
	{NULL,	eDataTypeNumber,		6,		1,		0,		100,	NULL},		// ���
	{NULL,	eDataTypeNumber,		6,		1,		0,		100,	NULL}		// ����
};

//////////////////////////////////////////////////////////////////////
// �\�z/����
//////////////////////////////////////////////////////////////////////

CExcelSeisanJoken::CExcelSeisanJoken()
{
	m_sFileName = "worktime-data.xls";
	m_pColumnInfo = s_aColumnInfo;
	m_nColumnInfo = sizeof(s_aColumnInfo) / sizeof(CExcelFile::SColumnInfo);
	m_nFileNo = 9;

	SetKeys(1, eColSublineName);
}

BOOL CExcelSeisanJoken::AccessCheck(LPCTSTR pFolderName)
{
	return FileAccessCheck(pFolderName, m_sFileName, FALSE);
}
