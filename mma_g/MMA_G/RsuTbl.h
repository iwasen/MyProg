#pragma once

#include	"GlobalData.h"

typedef struct TAG_RSU_TBL {
	int	rsu_id;
	int	ch;
	int	cutoff;
	double	item[15];
} RSU_TBL;

class CRsuTbl
{
public:
	CRsuTbl(void);
	~CRsuTbl(void);

private:
	CStringArray	m_Dat;	// �t�@�C���̓��e��S�ēǂݍ��ރG���A
	CArray<RSU_TBL, RSU_TBL>m_tbl;

public:
	// �e�[�u������
	bool SetTbl(void);

private:
	// �e�[�u���f�[�^�ǂݍ���
	bool Read(void);
	// �ǂݍ��݃f�[�^����e�A�C�e�����擾
	bool GetItem(LPCSTR dat, RSU_TBL * tbl);
public:
	// RSU��񂩂�W�����擾����
	bool GetTbl(int id, int ch, int cutoff, KEISUU_INFO* info);
	void EditData(RSU_TBL *tbl, KEISUU_INFO* info);
	void EditData(int cutoff, int from_cutoff, int to_cutoff, RSU_TBL* from_tbl, RSU_TBL* to_tbl, KEISUU_INFO* info);
};
