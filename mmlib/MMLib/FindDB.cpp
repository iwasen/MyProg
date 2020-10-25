// FindDB.cpp: CFindDB �N���X�̃C���v�������e�[�V����
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "MMLib.h"
#include "FindDB.h"
#include "MMLibDB.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

//////////////////////////////////////////////////////////////////////
// �\�z/����
//////////////////////////////////////////////////////////////////////

CFindDB::CFindDB()
{
	m_Pcserve.Conxxxt(CHT_LOCAL, "", "MMLIB");
}

CFindDB::~CFindDB()
{
	m_FindDB.DBClose();
	m_Pcserve.Disconxxxt();
}

BOOL CFindDB::Open(BOOL bCreate)
{
	static DBF_I   field[] = {
		{"FINDID",	    'C', LEN_ID,       0},	// �����f�[�^�h�c
		{"LIBRARYID",	'C', LEN_ID,       0},	// �����Ώۃ��C�u�����h�c
		{"FINDTYPE",	'C', LEN_FINDTYPE, 0},	// �������
		{"FNAME",		'C', LEN_TITLE,    0},	// �t�H���_����

		{"CDATEFROM1",	'C', LEN_DATE,     0},	// �B�e�N��������From
		{"CDATETO1",	'C', LEN_DATE,	   0},	// �B�e�N��������To
		{"CDATEBEFO1",	'C', LEN_DATE,     0},	// �B�e�N��������Before
		{"UDATEFROM1",	'C', LEN_DATE,     0},	// �X�V�N��������From
		{"UDATETO1",	'C', LEN_DATE,     0},	// �X�V�N��������To
		{"UDATEBEFO1",	'C', LEN_DATE,     0},	// �X�V�N��������Before
		{"CREATOR1",	'C', LEN_CREATOR,  0},	// �쐬��
		{"CODE1",		'C', LEN_CODE,     0},	// �R�[�h
		{"TITLE1",		'C', LEN_TITLE,    0},	// �摜�^�C�g��
		{"REMARK11",	'C', LEN_REMARK,   0},	// ���l
		{"REMARK21",	'C', LEN_KEYWORD,  0},	// �L�[���[�h
		{"TEXT1",		'C', LEN_TEXT,	   0},	// �e�L�X�g�f�[�^
		{"MATCHING1",	'C', LEN_MATCHING, 0},	// �}�b�`���O�I�v�V����

		{"CDATEFROM2",	'C', LEN_DATE,     0},	// �B�e�N��������From
		{"CDATETO2",	'C', LEN_DATE,	   0},	// �B�e�N��������To
		{"CDATEBEFO2",	'C', LEN_DATE,     0},	// �B�e�N��������Before
		{"UDATEFROM2",	'C', LEN_DATE,     0},	// �X�V�N��������From
		{"UDATETO2",	'C', LEN_DATE,     0},	// �X�V�N��������To
		{"UDATEBEFO2",	'C', LEN_DATE,     0},	// �X�V�N��������Before
		{"CREATOR2",	'C', LEN_CREATOR,  0},	// �쐬��
		{"CODE2",		'C', LEN_CODE,     0},	// �R�[�h
		{"TITLE2",		'C', LEN_TITLE,    0},	// �摜�^�C�g��
		{"REMARK12",	'C', LEN_REMARK,   0},	// ���l
		{"REMARK22",	'C', LEN_KEYWORD,  0},	// �L�[���[�h
		{"TEXT2",		'C', LEN_TEXT,	   0},	// �e�L�X�g�f�[�^
		{"MATCHING2",	'C', LEN_MATCHING, 0},	// �}�b�`���O�I�v�V����

		{"CDATEFROM3",	'C', LEN_DATE,     0},	// �B�e�N��������From
		{"CDATETO3",	'C', LEN_DATE,	   0},	// �B�e�N��������To
		{"CDATEBEFO3",	'C', LEN_DATE,     0},	// �B�e�N��������Before
		{"UDATEFROM3",	'C', LEN_DATE,     0},	// �X�V�N��������From
		{"UDATETO3",	'C', LEN_DATE,     0},	// �X�V�N��������To
		{"UDATEBEFO3",	'C', LEN_DATE,     0},	// �X�V�N��������Before
		{"CREATOR3",	'C', LEN_CREATOR,  0},	// �쐬��
		{"CODE3",		'C', LEN_CODE,     0},	// �R�[�h
		{"TITLE3",		'C', LEN_TITLE,    0},	// �摜�^�C�g��
		{"REMARK13",	'C', LEN_REMARK,   0},	// ���l
		{"REMARK23",	'C', LEN_KEYWORD,  0},	// �L�[���[�h
		{"TEXT3",		'C', LEN_TEXT,	   0},	// �e�L�X�g�f�[�^
		{"MATCHING3",	'C', LEN_MATCHING, 0},	// �}�b�`���O�I�v�V����

		{"CDATEFROM4",	'C', LEN_DATE,     0},	// �B�e�N��������From
		{"CDATETO4",	'C', LEN_DATE,	   0},	// �B�e�N��������To
		{"CDATEBEFO4",	'C', LEN_DATE,     0},	// �B�e�N��������Before
		{"UDATEFROM4",	'C', LEN_DATE,     0},	// �X�V�N��������From
		{"UDATETO4",	'C', LEN_DATE,     0},	// �X�V�N��������To
		{"UDATEBEFO4",	'C', LEN_DATE,     0},	// �X�V�N��������Before
		{"CREATOR4",	'C', LEN_CREATOR,  0},	// �쐬��
		{"CODE4",		'C', LEN_CODE,     0},	// �R�[�h
		{"TITLE4",		'C', LEN_TITLE,    0},	// �摜�^�C�g��
		{"REMARK14",	'C', LEN_REMARK,   0},	// ���l
		{"REMARK24",	'C', LEN_KEYWORD,  0},	// �L�[���[�h
		{"TEXT4",		'C', LEN_TEXT,	   0},	// �e�L�X�g�f�[�^
		{"MATCHING4",	'C', LEN_MATCHING, 0},	// �}�b�`���O�I�v�V����

		{"CDATEFROM5",	'C', LEN_DATE,     0},	// �B�e�N��������From
		{"CDATETO5",	'C', LEN_DATE,	   0},	// �B�e�N��������To
		{"CDATEBEFO5",	'C', LEN_DATE,     0},	// �B�e�N��������Before
		{"UDATEFROM5",	'C', LEN_DATE,     0},	// �X�V�N��������From
		{"UDATETO5",	'C', LEN_DATE,     0},	// �X�V�N��������To
		{"UDATEBEFO5",	'C', LEN_DATE,     0},	// �X�V�N��������Before
		{"CREATOR5",	'C', LEN_CREATOR,  0},	// �쐬��
		{"CODE5",		'C', LEN_CODE,     0},	// �R�[�h
		{"TITLE5",		'C', LEN_TITLE,    0},	// �摜�^�C�g��
		{"REMARK15",	'C', LEN_REMARK,   0},	// ���l
		{"REMARK25",	'C', LEN_KEYWORD,  0},	// �L�[���[�h
		{"TEXT5",		'C', LEN_TEXT,	   0},	// �e�L�X�g�f�[�^
		{"MATCHING5",	'C', LEN_MATCHING, 0}	// �}�b�`���O�I�v�V����
	};
	CString dirName;
	CString pathName;
	BOOL bCreateIndex = FALSE;
	int nIdxNo;
	int err;

	dirName = g_pMMLibDB->m_CacheDir;
open:
	pathName = dirName + FN_FIND_DBF;
	if ((err = m_FindDB.DBOpen(m_Pcserve, pathName)) != 0) {
		bCreateIndex = TRUE;
		if (err = ERROR_DB_NOFILE && bCreate)
			err = m_FindDB.DBCreate(m_Pcserve, pathName, field, sizeof(field) / sizeof(DBF_I));
		if (err != 0)
			return FALSE;
	} else {
		int nRecSize;
		CPSDB newDB;

		if ((err = m_FindDB.DBRecSize(&nRecSize)) != 0)
			return err;

		if (nRecSize - 1 < sizeof(FINDBUF)) {
			CString pathName2 = dirName + "FINDTMP.DBF";
			if ((err = newDB.DBCreate(m_Pcserve, pathName2, field, sizeof(field) / sizeof(DBF_I))) == 0) {
				if ((err = newDB.DBCopy(m_FindDB)) != 0)
					return err;
				m_FindDB.DBClose();
				newDB.DBClose();
				m_Pcserve.CFDelete(pathName);
				m_Pcserve.CFRename(pathName2, pathName);
				goto open;
			}
		}
	}

	pathName = dirName + FN_FIND_NDX;
	if (bCreateIndex || m_FindDB.DBIndex(pathName, &nIdxNo) != 0) {
		if ((err = m_FindDB.DBIdxCreate(pathName, "FINDID", 1, &nIdxNo)) != 0)
			return FALSE;
	}

	return TRUE;
}

BOOL CFindDB::ReadFind(LPCTSTR pLibraryID, FINDREC *pFindRec)
{
	FINDBUF findBuf;
	int nRead;
	int err;

	if (pLibraryID != NULL) {
		CString filter;
		filter.Format("LIBRARYID=%s", pLibraryID);
		if ((err = m_FindDB.DBSetFilter(filter)) != 0)
			return FALSE;
	}

	if ((err = m_FindDB.DBReadNext(1, &findBuf, &nRead)) != 0)
		return FALSE;

	if (nRead == 0)
		return FALSE;

	m_FindDB.DBGetFieldBuf(&findBuf, pFindRec);

	return TRUE;
}

BOOL CFindDB::AddFind(FINDREC *pFindRec)
{
	FINDBUF findBuf;
	FINDREC findRec;
	int err;

	if ((err = MakeFindID(pFindRec->findID)) != 0)
		return FALSE;

	m_FindDB.DBSetFieldBuf(&findBuf, pFindRec);

	if ((err = m_FindDB.DBSetFilter(NULL)) != 0)
		return FALSE;

	if ((err = m_FindDB.DBStoreUniq(&findBuf)) != 0)
		return FALSE;

	return TRUE;
}

BOOL CFindDB::UpdateFind(FINDREC *pFindRec)
{
	FINDBUF findBuf;
	int err;

	m_FindDB.DBSetFieldBuf(&findBuf, pFindRec);

	if ((err = m_FindDB.DBSetFilter(NULL)) != 0)
		return FALSE;

	if ((err = m_FindDB.DBUpdateKey(pFindRec->findID, LEN_ID, &findBuf)) != 0)
		return FALSE;

	return TRUE;
}

BOOL CFindDB::DeleteFind(LPCTSTR did)
{
	int err;

	if ((err = m_FindDB.DBSetFilter(NULL)) != 0)
		return FALSE;

	if ((err = m_FindDB.DBDeleteKey(did, LEN_ID, 1)) != 0)
		return FALSE;

	return TRUE;
}

int CFindDB::MakeFindID(CString &id)
{
	time_t ltime;
	int find;
	int err;

	time(&ltime);

	if ((err = m_FindDB.DBSetFilter(NULL)) != 0)
		return err;

	for (;;) {
		MakeID('S', (DWORD)ltime, id);

		if ((err = m_FindDB.DBSearch2(id, LEN_ID, &find)) != 0)
			return err;

		if (find != 1)
			break;

		ltime++;
	}

	return 0;
}
