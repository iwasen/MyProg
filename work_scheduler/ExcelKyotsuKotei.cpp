// ExcelKyotsuKotei.cpp: CExcelKyotsuKotei �N���X�̃C���v�������e�[�V����
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "workscheduler.h"
#include "ExcelKyotsuKotei.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

// �G�N�Z���J�������
static CExcelFile::SColumnInfo s_aColumnInfo[] = {
//	��̫��	�^						����	����	�ŏ�	�ő�	�L������
	{NULL,	eDataTypeAlphaNumKana,	32,		0,		0,		0,		NULL},		// ���ײݖ�
	{NULL,	eDataTypeKoteiName,		128,	0,		0,		0,		NULL},		// �H����
	{NULL,	eDataTypeKoteiName,		128,	0,		0,		0,		NULL},		// �v�f��ƌQ��
	{NULL,	eDataTypeAll,			6,		0,		0,		0,		NULL},		// ����/�蓮
	{NULL,	eDataTypeNumber,		8,		0,		0,		99999,	NULL}		// ���ʍH����ٰ�ߔԍ�
};

//////////////////////////////////////////////////////////////////////
// �\�z/����
//////////////////////////////////////////////////////////////////////

CExcelKyotsuKotei::CExcelKyotsuKotei()
{
	m_sFileName = "com_process.xls";
	m_pColumnInfo = s_aColumnInfo;
	m_nColumnInfo = sizeof(s_aColumnInfo) / sizeof(CExcelFile::SColumnInfo);
	m_nFileNo = 11;

	SetKeys(3, eColSublineName, eColKoteiName, eColYosoSagyogunName);
}

BOOL CExcelKyotsuKotei::AccessCheck(LPCTSTR pFolderName)
{
	return FileAccessCheck(pFolderName, m_sFileName, FALSE);
}

BOOL CExcelKyotsuKotei::ReadFile()
{
	if (!CExcelFile::ReadFile())
		return FALSE;

	GetKishuCols(eColKyotsuKoteiGroupNo);

	return TRUE;
}
