// ExcelLineKiseiMatrix.cpp: CExcelLineKiseiMatrix �N���X�̃C���v�������e�[�V����
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "workscheduler.h"
#include "ExcelLineKiseiMatrix.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

// �G�N�Z���J�������
static CExcelFile::SColumnInfo s_aColumnInfo[] = {
//	��̫��	�^						����	����	�ŏ�	�ő�			�L������
	{NULL,	eDataTypeNumber,		8,		0,		0,		0,				NULL},		// No.
	{NULL,	eDataTypeAlphaNumKana,	32,		0,		0,		0,				NULL},		// �T�u���C������
	{NULL,	eDataTypeAlphaNum,		80,		0,		0,		0,				NULL},		// �H���L��
	{NULL,	eDataTypeNumber,		32,		0,		0,		0,				NULL},		// �H���ԍ�
	{NULL,	eDataTypeAll,			0,		0,		0,		0,				NULL},		// �i�\���j
	{NULL,	eDataTypeAlphaNumKana,	32,		0,		0,		0,				NULL},		// �Ԏ햼
	{NULL,	eDataTypeNumber,		12,		0,		0,		999999999999,	NULL},		// �v�f��ƕ\��ƌQ����
	{NULL,	eDataTypeNumber,		21,		6,		0,		9999.999999,	NULL},		// �v�f��ƌQ���v
	{NULL,	eDataTypeAll,			6,		0,		0,		0,				NULL},		// ��/��
	{NULL,	eDataTypeAlphaNumKana,	64,		0,		0,		0,				NULL},		// ���i��/�ݔ���
	{NULL,	eDataTypeKoteiName,		128,	0,		0,		0,				NULL},		// �H����
	{NULL,	eDataTypeKoteiName,		128,	0,		0,		0,				NULL},		// �v�f��ƌQ��
	{NULL,	eDataTypeAll,			6,		0,		0,		0,				NULL},		// ENG�p��-����
	{NULL,	eDataTypeAll,			1,		0,		0,		0,				NULL},		// ENG�p��-����Fr
	{NULL,	eDataTypeAll,			1,		0,		0,		0,				NULL},		// ENG�p��-����Rr
	{NULL,	eDataTypeAll,			1,		0,		0,		0,				NULL},		// ENG�p��-�|��Fr
	{NULL,	eDataTypeAll,			1,		0,		0,		0,				NULL},		// ENG�p��-�|��Rr
	{NULL,	eDataTypeAll,			1,		0,		0,		0,				NULL},		// ENG�p��-����į��
	{NULL,	eDataTypeAll,			1,		0,		0,		0,				NULL}		// ENG�p��-��������
};

//////////////////////////////////////////////////////////////////////
// �\�z/����
//////////////////////////////////////////////////////////////////////

CExcelLineKiseiMatrix::CExcelLineKiseiMatrix()
{
	m_sFileName = "e-mtx.xls";
	m_pColumnInfo = s_aColumnInfo;
	m_nColumnInfo = sizeof(s_aColumnInfo) / sizeof(CExcelFile::SColumnInfo);
	m_nFileNo = 6;
}

BOOL CExcelLineKiseiMatrix::AccessCheck(LPCTSTR pFolderName)
{
	return FileAccessCheck(pFolderName, "*_" + m_sFileName, TRUE);
}

// --Add 2007/03/07 aizawa �s��D-147�i�ǉ��C���j
CString CExcelLineKiseiMatrix::GetNewFileName()
{
	return CGlobal::GetFileNameFromPath(m_sPathName);
}
// --Add
