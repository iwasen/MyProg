// ExcelKatashikiKiseiMatrix.cpp: CExcelKatashikiKiseiMatrix �N���X�̃C���v�������e�[�V����
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "workscheduler.h"
#include "ExcelKatashikiKiseiMatrix.h"

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
	{NULL,	eDataTypeAll,			128,	0,		0,		0,				NULL},		// �H����
	{NULL,	eDataTypeAll,			128,	0,		0,		0,				NULL},		// �v�f��ƌQ��
	{NULL,	eDataTypeNumber,		32,		0,		0,		0,				NULL},		// �i�K�����e�j��i
	{NULL,	eDataTypeNumber,		32,		0,		0,		0,				NULL},		// �i�K�����R�j���i
};

//////////////////////////////////////////////////////////////////////
// �\�z/����
//////////////////////////////////////////////////////////////////////

CExcelKatashikiKiseiMatrix::CExcelKatashikiKiseiMatrix()
{
	m_sFileName = "p-mtx.xls";
	m_pColumnInfo = s_aColumnInfo;
	m_nColumnInfo = sizeof(s_aColumnInfo) / sizeof(CExcelFile::SColumnInfo);
	m_nMaxSheet = 0;
	m_nFileNo = 7;
// Modify ... ( CHANGE )
	m_bColumnCheck = 0;
// By Y.Itabashi (xxxxx) 2007.02.20
}

BOOL CExcelKatashikiKiseiMatrix::AccessCheck(LPCTSTR pFolderName)
{
	return FileAccessCheck(pFolderName, m_sFileName, TRUE);
}
