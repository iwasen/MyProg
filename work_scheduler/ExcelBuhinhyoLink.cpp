// ExcelBuhinhyoLink.cpp: CExcelBuhinhyoLink �N���X�̃C���v�������e�[�V����
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "workscheduler.h"
#include "ExcelBuhinhyoLink.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

// �G�N�Z���J�������
static CExcelFile::SColumnInfo s_aColumnInfo[] = {
//	��̫��	�^						����	����	�ŏ�	�ő�		�L������
	{NULL,	eDataTypeAlphaNum,		6,		0,		0,		0,			NULL},		// �^��
	{NULL,	eDataTypeAlphaNum,		6,		0,		0,		0,			NULL},		// �@��
	{NULL,	eDataTypeAlphaNum,		80,		0,		0,		0,			NULL},		// ��ۯ�No
	{NULL,	eDataTypeAlphaNum,		32,		0,		0,		0,			NULL},		// ���i�ԍ�
	{NULL,	eDataTypeAlphaNumKana,	64,		0,		0,		0,			NULL},		// ���i����
	{NULL,	eDataTypeNumber,		32,		2,		0,		9999999999,	NULL},		// P/C
	{NULL,	eDataTypeNone,			1,		0,		0,		0,			"1"},		// �����׸�
	{NULL,	eDataTypeAlphaNumKana,	32,		0,		0,		0,			NULL},		// �ݸ����ײݖ�
	{NULL,	eDataTypeAlphaNum,		80,		0,		0,		0,			NULL},		// �ݸ��H���L��
	{NULL,	eDataTypeAlphaNum,		32,		0,		0,		0,			NULL},		// �ݸ��H���ԍ�
	{NULL,	eDataTypeKoteiName,		128,	0,		0,		0,			NULL},		// �ݸ��H������
	{NULL,	eDataTypeKoteiName,		128,	0,		0,		0,			NULL},		// �ݸ��v�f��ƌQ��
};

//////////////////////////////////////////////////////////////////////
// �\�z/����
//////////////////////////////////////////////////////////////////////

CExcelBuhinhyoLink::CExcelBuhinhyoLink()
{
	m_sFileName = "parts.xls";
	m_pColumnInfo = s_aColumnInfo;
	m_nColumnInfo = sizeof(s_aColumnInfo) / sizeof(CExcelFile::SColumnInfo);
	m_nMaxSheet = MAX_KISHU;
	m_nFileNo = 3;
}

BOOL CExcelBuhinhyoLink::AccessCheck(LPCTSTR pFolderName)
{
	return FileAccessCheck(pFolderName, "??????_??????_" + m_sFileName, TRUE);
}

CString CExcelBuhinhyoLink::GetNewFileName()
{
	return g_pSystem->m_cSaveTime.Format("%y%m%d_%H%M%S_") + m_sFileName;
}
