// ExcelBuhinKitting.cpp: CExcelBuhinKitting �N���X�̃C���v�������e�[�V����
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "workscheduler.h"
#include "ExcelBuhinKitting.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

// �G�N�Z���J�������
static CExcelFile::SColumnInfo s_aColumnInfo[] = {
//	��̫��	�^						����	����	�ŏ�	�ő�	�L������
	{NULL,	eDataTypeAlphaNumKana,	32,		0,		0,		0,		NULL},		// ���ײݖ�
	{NULL,	eDataTypeAlphaNum,		80,		0,		0,		0,		NULL},		// BLOCK
	{NULL,	eDataTypeAlphaNum,		32,		0,		0,		0,		NULL},		// ����
	{NULL,	eDataTypeAlphaNumKana,	64,		0,		0,		0,		NULL},		// ����
	{"1",	eDataTypeNone,			1,		0,		0,		0,		"01"}		// ��ĉ��׸�
};

//////////////////////////////////////////////////////////////////////
// �\�z/����
//////////////////////////////////////////////////////////////////////

CExcelBuhinKitting::CExcelBuhinKitting()
{
	m_sFileName = "kitting_parts.xls";
	m_pColumnInfo = s_aColumnInfo;
	m_nColumnInfo = sizeof(s_aColumnInfo) / sizeof(CExcelFile::SColumnInfo);
	m_nFileNo = 10;

	SetKeys(0);
}

BOOL CExcelBuhinKitting::AccessCheck(LPCTSTR pFolderName)
{
	return FileAccessCheck(pFolderName, m_sFileName, FALSE);
}
