// ExcelLineKiseiError.cpp: CExcelLineKiseiError �N���X�̃C���v�������e�[�V����
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "workscheduler.h"
#include "ExcelLineKiseiError.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

// �G�N�Z���J�������
static CExcelFile::SColumnInfo s_aColumnInfo[] = {
//	��̫��	�^						����	����	�ŏ�	�ő�	�L������
	{NULL,	eDataTypeAlphaNum,		10,		0,		0,		0,		"/"},		// ����
	{NULL,	eDataTypeAlphaNum,		8,		0,		0,		0,		":"},		// ����
	{NULL,	eDataTypeAlphaNumKana,	32,		0,		0,		0,		NULL},		// �ړ����ײݖ�
	{NULL,	eDataTypeNumber,		32,		0,		0,		0,		NULL},		// �ړ��H���ԍ�
	{NULL,	eDataTypeKoteiName,		128,	0,		0,		0,		NULL},		// �ړ��H����
	{NULL,	eDataTypeKoteiName,		128,	0,		0,		0,		NULL},		// �ړ��v�f��ƌQ��
	{NULL,	eDataTypeAlphaNumKana,	32,		0,		0,		0,		NULL},		// �ړ�����ײݖ�
	{NULL,	eDataTypeNumber,		32,		0,		0,		0,		NULL},		// �ړ���̑O�̍H���ԍ�
	{NULL,	eDataTypeKoteiName,		128,	0,		0,		0,		NULL},		// �ړ���̑O�̍H����
	{NULL,	eDataTypeKoteiName,		128,	0,		0,		0,		NULL},		// �ړ���̑O�̗v�f��ƌQ��
	{NULL,	eDataTypeNumber,		32,		0,		0,		0,		NULL},		// �ړ���̌�̍H���ԍ�
	{NULL,	eDataTypeKoteiName,		128,	0,		0,		0,		NULL},		// �ړ���̌�̍H����
	{NULL,	eDataTypeKoteiName,		128,	0,		0,		0,		NULL},		// �ړ���̌�̗v�f��ƌQ��
	{NULL,	eDataTypeAll,			0,		0,		0,		0,		NULL},		// �H���K���ᔽ

};

//////////////////////////////////////////////////////////////////////
// �\�z/����
//////////////////////////////////////////////////////////////////////

CExcelLineKiseiError::CExcelLineKiseiError()
{
	m_sFileName = "e-mtx-check.xls";
	m_pColumnInfo = s_aColumnInfo;
	m_nColumnInfo = sizeof(s_aColumnInfo) / sizeof(CExcelFile::SColumnInfo);
	m_nMaxSheet = MAX_KISHU;
	m_nFileNo = 12;
	m_bSetSheetName = 1;

// Modify ... ( ADD )
	m_bFileLock = 0;
	nErrorDataSize = 0;
// By Y.Itabashi (xxxxx) 2007.02.19
}

BOOL CExcelLineKiseiError::AccessCheck(LPCTSTR pFolderName)
{
	return FileAccessCheck(pFolderName, m_sFileName, FALSE);
}

BOOL CExcelLineKiseiError::ReadFile()
{
	m_sPathName = CGlobal::MakePathName(m_sFolderName, m_sFileName);

	if (!CExcelFile::ReadFile()){
		m_sPathName.Empty();
		return FALSE;
	}

	return TRUE;
}
