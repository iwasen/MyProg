// ExcelDSTRSanshutsu.cpp: CExcelDSTRSanshutsu �N���X�̃C���v�������e�[�V����
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "workscheduler.h"
#include "ExcelDSTRSanshutsu.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

// �G�N�Z���J�������
static CExcelFile::SColumnInfo s_aColumnInfo[] = {
//	��̫��	�^					����	����	�ŏ�	�ő�		�L������
	{NULL,	eDataTypeAlphaNum,	6,		0,		0,		0,			"�S�@��"},		// �@��
	{"0",	eDataTypeNumber,	64,		4,		0,		166666666,	NULL},		// �W������
	{"0",	eDataTypeNumber,	64,		4,		0,		166666666,	NULL},		// �����W������
	{"0",	eDataTypeNumber,	64,		4,		0,		166666666,	NULL},		// ����_������
	{"0",	eDataTypeNumber,	64,		4,		0,		166666666,	NULL},		// �i��۽
	{"0",	eDataTypeNumber,	64,		4,		0,		166666666,	NULL},		// �ޯ�����۽
	{"0",	eDataTypeNumber,	64,		4,		0,		166666666,	NULL},		// �ݔ��s�۽
	{"0",	eDataTypeNumber,	64,		4,		0,		166666666,	NULL},		// �K�n۽
	{"0",	eDataTypeNumber,	64,		4,		0,		166666666,	NULL},		// ����۽
	{"0",	eDataTypeNumber,	64,		4,		0,		166666666,	NULL},		// ײݒ�~۽
	{"0",	eDataTypeNumber,	64,		4,		0,		166666666,	NULL},		// �Ґ�۽
	{"0",	eDataTypeNumber,	64,		4,		0,		166666666,	NULL},		// �Ґ�+����+ײݒ�~۽
	{"0",	eDataTypeNumber,	64,		4,		0,		166666666,	NULL},		// �����\����
	{"0",	eDataTypeNumber,	64,		4,		0,		166666666,	NULL},		// �Ǘ���~����
	{"0",	eDataTypeNumber,	64,		4,		0,		166666666,	NULL}		// DST
};

//////////////////////////////////////////////////////////////////////
// �\�z/����
//////////////////////////////////////////////////////////////////////

CExcelDSTRSanshutsu::CExcelDSTRSanshutsu()
{
	m_sFileName = "dstr.xls";
	m_pColumnInfo = s_aColumnInfo;
	m_nColumnInfo = sizeof(s_aColumnInfo) / sizeof(CExcelFile::SColumnInfo) - 3;
	m_nFileNo = 4;

	SetKeys(1, eColKishuName);
}

BOOL CExcelDSTRSanshutsu::AccessCheck(LPCTSTR pFolderName)
{
	return FileAccessCheck(pFolderName, "??????_??????_" + m_sFileName, TRUE);
}

CString CExcelDSTRSanshutsu::GetNewFileName()
{
	return g_pSystem->m_cSaveTime.Format("%y%m%d_%H%M%S_") + m_sFileName;
}
