// ExcelYosoSagyoHyo.cpp: CExcelYosoSagyoHyo �N���X�̃C���v�������e�[�V����
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "workscheduler.h"
#include "ExcelYosoSagyoHyo.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

// �G�N�Z���J�������
static CExcelFile::SColumnInfo s_aColumnInfo[] = {
//	��̫��	�^						����	����	�ŏ�			�ő�			�L������
	{NULL,	eDataTypeNone,			1,		0,		0,				0,				"123"},		// �폜�׸�
	{NULL,	eDataTypeNone,			1,		0,		0,				0,				"123"},		// �ǉ��׸�
	{NULL,	eDataTypeAlphaNumKana,	32,		0,		0,				0,				NULL},		// ���ײݖ�
	{NULL,	eDataTypeAlphaNum,		80,		0,		0,				0,				NULL},		// �H���L��
	{NULL,	eDataTypeAlphaNum,		32,		0,		0,				0,				NULL},		// �H���ԍ�
	{NULL,	eDataTypeKoteiName,		128,	0,		0,				0,				NULL},		// �H������
	{NULL,	eDataTypeKoteiName,		128,	0,		0,				0,				NULL},		// �v�f��ƌQ����
	{NULL,	eDataTypeAll,			6,		0,		0,				0,				NULL},		// �����^�蓮(�v�f��ƌQ)
	{NULL,	eDataTypeAlphaNum,		64,		0,		0,				0,				NULL},		// ��Ǝ�
	{NULL,	eDataTypeNumber,		8,		0,		0,				9999,			NULL},		// ���No.
	{NULL,	eDataTypeAll,			128,	0,		0,				0,				NULL},		// ��Ɠ��e
	{NULL,	eDataTypeNumber,		8,		0,		0,				0,				NULL},		// �v�f���No.
	{NULL,	eDataTypeAlphaNum,		/*8*/32,0,		0,				0,				"/*+-"},	// ���Z��
	{"1",	eDataTypeNumber,		10,		4,		-9999.9999,		9999.9999,		NULL},		// S1
	{"1",	eDataTypeNumber,		10,		4,		-9999.9999,		9999.9999,		NULL},		// S2
	{"1",	eDataTypeNumber,		10,		4,		-9999.9999,		9999.9999,		NULL},		// M
	{"1",	eDataTypeNumber,		10,		4,		-9999.9999,		9999.9999,		NULL},		// N
	{"0",	eDataTypeNumber,		12,		6,		-9999.999999,	9999.999999,	NULL},		// ���Z��̍�Ǝ���
	{"1",	eDataTypeNumber,		4,		0,		1,				9999,			NULL},		// �����p�x
	{"0",	eDataTypeNumber,		4,		0,		0,				9999,			NULL},		// �J��Ԃ���
// Modify ... ( CHANGE )
	{"0",	eDataTypeNumber,		21,		6,		-9999.999999,	9999.999999,	NULL},		// �䂠����̗v�f��Ɩ���Ǝ���
//	{"0",	eDataTypeNumber,		21,		6,		0,				9999.999999,	NULL},		// �䂠����̗v�f��Ɩ���Ǝ���
// By Y.Itabashi (xxxxx) 2007.02.05
	{NULL,	eDataTypeAll,			256,	0,		0,				0,				NULL},		// ����
	{NULL,	eDataTypeNone,			1,		0,		0,				0,				"1"},		// �ޯ����ߍH��
	{NULL,	eDataTypeAll,			6,		0,		0,				0,				NULL},		// �����^�蓮(�H��)
	{NULL,	eDataTypeNumber,		12,		0,		0,				999999999999,	NULL},		// �v�f��ƌQ����
	{NULL,	eDataTypeNone,			2,		0,		0,				0,				"Yy"},		// FMEA�׸�
	{NULL,	eDataTypeNone,			2,		0,		0,				0,				"Yy"},		// ����_���׸�
	{NULL,	eDataTypeAll,			32,		0,		0,				0,				NULL},		// ����_���p�x
	{NULL,	eDataTypeAll,			6,		0,		0,				0,				NULL},		// �Œ蕔/�ϓ����׸�
	{NULL,	eDataTypeNone,			2,		0,		0,				0,				"Yy"},		// �����׸�
	{NULL,	eDataTypeAll,			10,		0,		0,				0,				NULL},		// ����׸�
	{NULL,	eDataTypeAll,			1,		0,		0,				0,				NULL},		// �-��҂��׸�
	{NULL,	eDataTypeAll,			1,		0,		0,				0,				NULL},		// �(1)or�H��(2)�׸�
	{NULL,	eDataTypeNumber,		21,		0,		0,				0,				NULL},		// �����ԍ�
	{NULL,	eDataTypeNumber,		21,		6,		0,				9999.999999,	NULL},		// �������v
	{NULL,	eDataTypeNumber,		21,		6,		0,				9999.999999,	NULL},		// ��ƌQ���v����
	{NULL,	eDataTypeNumber,		21,		6,		0,				9999.999999,	NULL},		// �䗦�v�Z���ƌQ���v����
	{NULL,	eDataTypeNumber,		21,		6,		0,				9999.1000000,	NULL},		// �H�������v�W������
	{NULL,	eDataTypeAlphaNum,		64,		0,		0,				9999.1000000,	NULL},		// �����ʒu(�ԍ�)
	{NULL,	eDataTypeAlphaNum,		64,		0,		0,				0,				NULL},		// �ݼ�݌���(�L��)
	{NULL,	eDataTypeAlphaNum,		256,	0,		0,				0,				NULL},		// �p��No
	{NULL,	eDataTypeAlphaNum,		64,		0,		0,				0,				NULL},		// �d��No
	{NULL,	eDataTypeNone,			1,		0,		0,				0,				"1234"},	// �敪
	{NULL,	eDataTypeAlphaNum,		64,		0,		0,				0,				NULL},		// �ˑ䍂��
	{NULL,	eDataTypeAlphaNum,		64,		0,		0,				0,				NULL},		// ��گč���
	{"0",	eDataTypeNone,			1,		0,		0,				0,				"01"},		// ���͑��u
	{NULL,	eDataTypeAlphaNum,		64,		0,		0,				0,				NULL},		// �ݔ�(����/��)
	{NULL,	eDataTypeAlphaNum,		64,		0,		0,				0,				NULL}		// �H��(����/�d��)
};

//////////////////////////////////////////////////////////////////////
// �\�z/����
//////////////////////////////////////////////////////////////////////

CExcelYosoSagyoHyo::CExcelYosoSagyoHyo()
{
	m_sFileName = "element.xls";
	m_pColumnInfo = s_aColumnInfo;
	m_nColumnInfo = sizeof(s_aColumnInfo) / sizeof(CExcelFile::SColumnInfo);
	m_nFileNo = 5;
}

BOOL CExcelYosoSagyoHyo::AccessCheck(LPCTSTR pFolderName)
{
	return FileAccessCheck(pFolderName, "??????_??????_" + m_sFileName, TRUE);
}

BOOL CExcelYosoSagyoHyo::ReadFile()
{
	if (!CExcelFile::ReadFile())
		return FALSE;

	GetKishuCols(eColSagyosha);
	GetKishuCols(eColYosoSagyogunJunban);
	GetKishuCols(eColSagyogunGokeiJikan);
	GetKishuCols(eColHiritsukeisangoSagyogunGokeiJikan);

	return TRUE;
}

CString CExcelYosoSagyoHyo::GetNewFileName()
{
	return g_pSystem->m_cSaveTime.Format("%y%m%d_%H%M%S_") + m_sFileName;
}
