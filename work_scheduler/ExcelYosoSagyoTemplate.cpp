// ExcelYosoSagyoTemplate.cpp: CExcelYosoSagyoTemplate �N���X�̃C���v�������e�[�V����
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "workscheduler.h"
#include "ExcelYosoSagyoTemplate.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

// �G�N�Z���J�������
static CExcelFile::SColumnInfo s_aColumnInfo[] = {
//	��̫��	�^						����	����	�ŏ�			�ő�			�L������
	{NULL,	eDataTypeNumber,		8,		0,		0,				0,				NULL},		// �v�f���No.
	{NULL,	eDataTypeAll,			128,	0,		0,				0,				NULL},		// ��Ɠ��e
	{NULL,	eDataTypeAlphaNum,		32,		0,		0,				0,				"/*+-"},	// ���Z��
	{"1",	eDataTypeNumber,		10,		4,		-9999.9999,		9999.9999,		NULL},		// S1
	{"1",	eDataTypeNumber,		10,		4,		-9999.9999,		9999.9999,		NULL},		// S2
	{"1",	eDataTypeNumber,		10,		4,		-9999.9999,		9999.9999,		NULL},		// M
	{"1",	eDataTypeNumber,		10,		4,		-9999.9999,		9999.9999,		NULL},		// N
	{"0",	eDataTypeNumber,		12,		6,		-9999.999999,	9999.999999,	NULL},		// ���Z��̍�Ǝ���
	{"1",	eDataTypeNumber,		4,		0,		1,				9999,			NULL},		// �����p�x
	{"0",	eDataTypeNumber,		4,		0,		0,				9999,			NULL},		// �J��Ԃ���
	{"0",	eDataTypeNumber,		21,		6,		-9999.999999,	9999.999999,	NULL},		// �䂠����̗v�f��Ɩ���Ǝ���
	{NULL,	eDataTypeAll,			256,	0,		0,				0,				NULL},		// ����
	{NULL,	eDataTypeAll,			10,		0,		0,				0,				NULL},		// ����׸�
};

//////////////////////////////////////////////////////////////////////
// �\�z/����
//////////////////////////////////////////////////////////////////////

CExcelYosoSagyoTemplate::CExcelYosoSagyoTemplate()
{
	m_sFileName = "";
	m_pColumnInfo = s_aColumnInfo;
	m_nColumnInfo = sizeof(s_aColumnInfo) / sizeof(CExcelFile::SColumnInfo);
// Modify ... ( CHANGE )
	m_nFileNo = 17;
//	m_nFileNo = 0;
// By Y.Itabashi (xxxxx) 2007.02.20
}
