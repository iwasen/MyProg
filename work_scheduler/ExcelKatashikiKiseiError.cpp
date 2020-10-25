// ExcelKatashikiKiseiError.cpp: CExcelKatashikiKiseiError �N���X�̃C���v�������e�[�V����
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "workscheduler.h"
#include "ExcelKatashikiKiseiError.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

// �G�N�Z���J�������
static CExcelFile::SColumnInfo s_aColumnInfo[] = {
//	��̫��	�^						����	����	�ŏ�	�ő�	�L������
	{NULL,	eDataTypeAlphaNum,		10,		0,		0,		0,		"/"},		// ���ɂ�
	{NULL,	eDataTypeAlphaNum,		8,		0,		0,		0,		":"},		// ����
	{NULL,	eDataTypeAlphaNumKana,	32,		0,		0,		0,		NULL},		// �ړ����ײݖ�
	{NULL,	eDataTypeNumber,		32,		0,		0,		0,		NULL},		// �ړ��H���ԍ�
	{NULL,	eDataTypeKoteiName,		128,	0,		0,		0,		NULL},		// �ړ��H����
	{NULL,	eDataTypeKoteiName,		128,	0,		0,		0,		NULL},		// �ړ��v�f��ƌQ��
	{NULL,	eDataTypeAlphaNumKana,	32,		0,		0,		0,		NULL},		// �ړ�����ײݖ�
	{NULL,	eDataTypeNumber,		32,		0,		0,		0,		NULL},		// �ړ���H���ԍ�
	{NULL,	eDataTypeKoteiName,		128,	0,		0,		0,		NULL},		// �ړ���H����
	{NULL,	eDataTypeKoteiName,		128,	0,		0,		0,		NULL},		// �ړ���v�f��ƌQ��
	{NULL,	eDataTypeAlphaNumKana,	32,		0,		0,		0,		NULL},		// �ᔽ���ײݖ�
	{NULL,	eDataTypeNumber,		32,		0,		0,		0,		NULL},		// �ᔽ�H���ԍ�
	{NULL,	eDataTypeKoteiName,		128,	0,		0,		0,		NULL},		// �ᔽ�H����
	{NULL,	eDataTypeKoteiName,		128,	0,		0,		0,		NULL},		// �ᔽ�v�f��ƌQ��
	{NULL,	eDataTypeAll,			0,		0,		0,		0,		NULL},		// �H���K���ᔽ
};

//////////////////////////////////////////////////////////////////////
// �\�z/����
//////////////////////////////////////////////////////////////////////

CExcelKatashikiKiseiError::CExcelKatashikiKiseiError()
{
	m_sFileName = "p-mtx-check.xls";
	m_pColumnInfo = s_aColumnInfo;
	m_nColumnInfo = sizeof(s_aColumnInfo) / sizeof(CExcelFile::SColumnInfo);
	m_nMaxSheet = MAX_KISHU;
	m_nFileNo = 13;
	m_bSetSheetName = 1;

// Modify ... ( ADD )
	m_bFileLock = 0;
	nErrorDataSize = 0;
// By Y.Itabashi (xxxxx) 2007.02.19
}

BOOL CExcelKatashikiKiseiError::AccessCheck(LPCTSTR pFolderName)
{
	return FileAccessCheck(pFolderName, m_sFileName, FALSE);
}

BOOL CExcelKatashikiKiseiError::ReadFile()
{
	m_sPathName = CGlobal::MakePathName(m_sFolderName, m_sFileName);

	if (!CExcelFile::ReadFile()){
		m_sPathName.Empty();
		return FALSE;
	}

	return TRUE;
}
