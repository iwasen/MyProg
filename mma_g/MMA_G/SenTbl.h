#pragma once

#include	"GlobalData.h"

typedef struct TAG_SEN_TBL {
	int	sen_id;
	int	ch;
	int	type;
	double	item[10];
} SEN_TBL;

class CSenTbl
{
public:
	CSenTbl(void);
	~CSenTbl(void);

private:
	CStringArray	m_Dat;	// �t�@�C���̓��e��S�ēǂݍ��ރG���A
	CArray<SEN_TBL, SEN_TBL>m_tbl;

public:
	// �e�[�u������
	bool SetTbl(void);

private:
	// �e�[�u���f�[�^�ǂݍ���
	bool Read(void);
	// �ǂݍ��݃f�[�^����e�A�C�e�����擾
	bool GetItem(LPCSTR dat, SEN_TBL * tbl);
public:
	// Sensor��񂩂�W�����擾����
	bool GetTbl(int id, int ch, KEISUU_INFO* info);
};
