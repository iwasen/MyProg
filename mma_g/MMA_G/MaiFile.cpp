// MaiFile.cpp: CMaiFile �N���X�̃C���v�������e�[�V����
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "direct.h"
#include "MaiFile.h"
#include "io.h"
#include "binfile.h"
#include "calcfile.h"
#include "idbfile.h"
#include "GlobalData.h"
#include "General.h"
#include "SelectRsu.h"
#include "SelectStartTimeDlg.h"
#include "MMA_G.h"
#include <math.h>

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

#define	WORK_PATH		"\\temp"
#define	FN_DAT0	"data0.dat"
#define	FN_DAT1	"data1.dat"
#define	FN_DAT2	"data2.dat"
#define	FN_DAT3	"data3.dat"

static	int cmpfunc(const void *prm1, const void *prm2);
static	unsigned long GetMSecFromFileName(LPCSTR fname);

//////////////////////////////////////////////////////////////////////
// �\�z/����
//////////////////////////////////////////////////////////////////////

CMaiFile::CMaiFile()
: m_maiFname(_T(""))
, m_WorkPath(_T(""))
, m_ConvDataPath(_T(""))
, m_CalcFile(NULL)
{
	CString	str;

	str = gRootPath;
	_mkdir((LPCSTR)str);
	str = gRootData;
	_mkdir((LPCSTR)str);

	m_WorkPath.Format("%s%s", gRootPath, WORK_PATH);	// �����t�@�C���������݃t�H���_
	_mkdir((LPCSTR)m_WorkPath);

	m_ConvDataPath = ".";
}

CMaiFile::~CMaiFile()
{

}

// �H�w�l�f�[�^�������݃t�H���_�쐬
int CMaiFile::mkDir(LPCSTR pos, LPCTSTR category, LPCTSTR measurement_kind)
{
	CString	str;
	str.Format("%s\\%s", gRootData, pos);
	// �ʒu�t�H���_�쐬
	_mkdir(str);

	// �J�e�S���t�H���_�쐬
	CString str1;
	str1.Format("%s\\%s", str, category);
	_mkdir(str1);

	// �v����ރt�H���_�쐬
	CString str2;
	str2.Format("%s\\%s", str1, measurement_kind);
	_mkdir(str2);

	CString	str3;
	str3.Format("%s\\%s", str2, m_maiFname);
	// mai�t�@�C�����t�H���_�쐬
	if(_mkdir(str3)){
		if(errno == EEXIST){
			if(AfxMessageBox("���̕ϊ��f�[�^�͊��ɑ��݂��܂��B���̂܂ܕϊ��������s���ƁC�����f�[�^���㏑������܂��B\n�ϊ��������p�����܂����H",MB_ICONQUESTION|MB_YESNO) == IDNO){
				return -1;
			}
		}
	}

	// �H�w�l�������݃t�H���_�ۑ�
	m_ConvDataPath = str3;

	return 0;
}

// �f�[�^���Z�b�g
void CMaiFile::ResetData(void)
{
	m_Name.RemoveAll();		// �w��t�H���_�ɑ��݂���S�Ă�mai�t�@�C�����ۑ��G���A
	m_Rsu.RemoveAll();	// mai�t�@�C�����̃Z���T���ۑ��G���A
	m_BinName0.RemoveAll();	// �`�����l��No0��BIN�t�@�C�����ۑ��G���A
	m_BinName1.RemoveAll();	// �`�����l��No1��BIN�t�@�C�����ۑ��G���A
	m_BinName2.RemoveAll();	// �`�����l��No2��BIN�t�@�C�����ۑ��G���A
	m_BinName3.RemoveAll();	// �`�����l��No3��BIN�t�@�C�����ۑ��G���A
	m_maiDat.RemoveAll();	// mai�t�@�C���̓��e��S�ēǂݍ��ރG���A
	m_Samples0.RemoveAll();
	m_Samples1.RemoveAll();
	m_Samples2.RemoveAll();
	m_Samples3.RemoveAll();
}



// �w��t�H���_���ɑ��݂���mai�t�@�C������S�Ď擾����
int CMaiFile::GetMaiFileName(LPCSTR path)
{
	char wpath[MAX_PATH];
	struct _finddata_t c_file;
    intptr_t hFile;

	m_Name.RemoveAll();
	memset(wpath, 0, sizeof(wpath));
	strcpy_s(wpath, path);
	strcat_s(wpath, "\\*.mai");

    /* �J�����g �f�B���N�g�����̍ŏ��� .mai �t�@�C����T���܂��B*/
	if( (hFile = _findfirst(wpath, &c_file )) != -1L ) {
		m_Name.Add(c_file.name);
		/* �c��� .mai�t�@�C����T���܂��B */
		while( _findnext( hFile, &c_file ) == 0 ) {
			m_Name.Add(c_file.name);
		}
		_findclose( hFile );
	}
	return (int)m_Name.GetSize();
}

// update 2008/03/18 by hama�@��
// �擾����mai�t�@�C��������Z���TNo���擾����
int CMaiFile::GetRsuFromMaiFileName(void)
{
	int	i, cnt;
	char buf[128], *token, *nexttoken;
	char *sep = "-_.";

	m_Rsu.RemoveAll();

	// mai�t�@�C�����擾
	if ((cnt = (int)m_Name.GetSize()) != 0) {
		// mai�t�@�C���������[�v
		for (i = 0; i < cnt; i++) {
			strcpy_s(buf, (LPCSTR)m_Name.GetAt(i));
			// �g�[�N���擾
			if ((token = strtok_s( buf, sep, &nexttoken)) != NULL) {
				// �Z���TNo���擾�ς�����
				if (! IsRsu(token)) {
					// �t�@�C�����̍ŏ��̍��ڂ��擾
					m_Rsu.Add(token);
				}
			}
		}
	}
	return (int)m_Rsu.GetSize();
}

// �Z���TNo���擾�ς�����
bool CMaiFile::IsRsu(LPCSTR rsu)
{
	int	cnt = (int)m_Rsu.GetSize();
	int	i;

	for (i = 0; i < cnt; i++) {
		if (strcmp(rsu, (LPCSTR)m_Rsu.GetAt(i)) == 0)
			return true;
	}
	return false;
}
// update 2008/03/18 by hama�@��

// �擾����mai�t�@�C�����̒���MAX�̐������������t�@�C����T��
bool CMaiFile::GetMaxMaiFileName(CString &fname, CString RsuNo)
{
	int	i, cnt, cntToken;
	unsigned long	l_max, value;	
	char buf[128], *token, *nexttoken, tokenDat[64];
	char *sep = "-_.";
	CString str = "";

	l_max = 0;
	// mai�t�@�C�����擾
	if ((cnt = (int)m_Name.GetSize()) == 0)
		return false;

	// mai�t�@�C���������[�v
	for (i = 0; i < cnt; i++) {
		cntToken = 0;
		memset(tokenDat, 0, sizeof(tokenDat));
		strcpy_s(buf, (LPCSTR)m_Name.GetAt(i));
		// �g�[�N���擾
		token = strtok_s(buf, sep, &nexttoken);
// update 2008/03/18 by hama�@��
		if (strcmp(token, RsuNo) != 0)
			continue;
// update 2008/03/18 by hama�@��
		while (token != NULL) {
			cntToken++;
			// �t�@�C�����̍Ō�̍��ڎ擾�H
			if (cntToken > 8) {
				// �t�@�C�����̍Ō�̍��ڂ̌����ȍ~�擾
				strcpy_s(tokenDat, token+4);
				value = (unsigned long)atoi(tokenDat);
				// MAX�l����
				if (l_max < value) {
					l_max = value;
					str = m_Name.GetAt(i);
				}
				break;
			}
			/* "string" �Ƀg�[�N�����Ȃ��Ȃ�܂ŌJ��Ԃ��܂��B */
			/* ���̃g�[�N�����擾���܂��B */
			token = strtok_s( NULL, sep, &nexttoken );
		}
	}
	// MAX�l�擾�ł����H
	if (l_max != 0) {
		fname = str;
		return true;
	}
	return false;
}

// mai�t�@�C���̓��e��ǂݍ���
int CMaiFile::GetMaFileiData(LPCSTR path)
{
	CStdioFile	fp;
	CFileException fileException;

	m_maiDat.RemoveAll();

	if (! fp.Open(path, CFile::modeRead, &fileException)) {
		TRACE( "Can't open file %s, error = %u\n", path, fileException.m_cause );
		return 0;
	}

	CString	str;
	for(;;) {
		if (! fp.ReadString(str))
			break;
		m_maiDat.Add(str);
	}
	fp.Close();
	return (int)m_maiDat.GetSize();
}

// BIN�t�@�C������0,1,2,3���ɐU�蕪����
bool CMaiFile::FuriwakeBinFileName(void)
{
	int	i, chNo;
	int	cnt = (int)m_maiDat.GetSize();
	char *sep="File";
	char *sep2="Samples";
	CString	Files, Samples;
	long	sample;

	m_BinName0.RemoveAll();
	m_BinName1.RemoveAll();
	m_BinName2.RemoveAll();
	m_BinName3.RemoveAll();
	m_Samples0.RemoveAll();
	m_Samples1.RemoveAll();
	m_Samples2.RemoveAll();
	m_Samples3.RemoveAll();
	m_Total0 = 0;
	m_Total1 = 0;
	m_Total2 = 0;
	m_Total3 = 0;

	for (i = 0; i < cnt;) {
		// mai�t�@�C���f�[�^����BIN�t�@�C�������擾����
		if (GetDataFromMai(m_maiDat.GetAt(i), sep, Files) != false) {
			// BIN�t�@�C��������`�����l��No���擾����
			chNo = GetChNoFromFName((LPCSTR)Files);
			// �`�����l��No���ɐU�蕪����
			switch(chNo) {
			case 0:
				// �t�@�C�����Z�[�u
				m_BinName0.Add(Files);
				// �t�@�C���Ɋ܂܂��T���v�����擾
				if (GetDataFromMai(m_maiDat.GetAt(i + 1), sep2, Samples) != false) {
					sample = atol((LPCSTR)Samples);
					m_Total0 += sample;
					m_Samples0.Add(sample);
					i++;
				}
				break;
			case 1:
				// �t�@�C�����Z�[�u
				m_BinName1.Add(Files);
				// �t�@�C���Ɋ܂܂��T���v�����擾
				if (GetDataFromMai(m_maiDat.GetAt(i + 1), sep2, Samples) != false) {
					sample = atol((LPCSTR)Samples);
					m_Total1 += sample;
					m_Samples1.Add(sample);
					i++;
				}
				break;
			case 2:
				// �t�@�C�����Z�[�u
				m_BinName2.Add(Files);
				// �t�@�C���Ɋ܂܂��T���v�����擾
				if (GetDataFromMai(m_maiDat.GetAt(i + 1), sep2, Samples) != false) {
					sample = atol((LPCSTR)Samples);
					m_Total2 += sample;
					m_Samples2.Add(sample);
					i++;
				}
				break;
			case 3:
				// �t�@�C�����Z�[�u
				m_BinName3.Add(Files);
				// �t�@�C���Ɋ܂܂��T���v�����擾
				if (GetDataFromMai(m_maiDat.GetAt(i + 1), sep2, Samples) != false) {
					sample = atol((LPCSTR)Samples);
					m_Total3 += sample;
					m_Samples3.Add(sample);
					i++;
				}
				break;
			}
		}
		i++;
	}

	return true;
}

// mai�t�@�C���f�[�^����w�荀�ڂ��擾����
bool CMaiFile::GetDataFromMai(LPCSTR dat, LPCSTR itemStr, CString &oDat)
{
	char buf[128], *token, *nexttoken;
	char tokenDat[2][256];
	char *sep = "=\0";
	CString str = "";
	int	tokenCnt;

	memset(tokenDat, 0, sizeof(tokenDat));
	strcpy_s(buf, dat);
	tokenCnt = 0;
	// �g�[�N���擾
	token = strtok_s( buf, sep, &nexttoken);
	while (token != NULL) {
		strcpy_s(tokenDat[tokenCnt], token);
		tokenCnt++;
		if (tokenCnt > 1)
			break;
		token = strtok_s( NULL, sep, &nexttoken);
	}
	if (strcmp(itemStr, tokenDat[0]) != 0)
		return false;

	oDat = tokenDat[1];
	return true;
}

// BIN�t�@�C��������`�����l��No���擾����
int CMaiFile::GetChNoFromFName(LPCSTR fname)
{
	int	cntToken, rtNo;
	char buf[128], *token, *nexttoken, tokenDat[2][10];
	char *sep = "-";

	cntToken = 0;
	memset(tokenDat, 0, sizeof(tokenDat));
	strcpy_s(buf, fname);
	// �g�[�N���擾
	token = strtok_s( buf, sep, &nexttoken);
	while( token != NULL ) {
		strcpy_s(tokenDat[cntToken], token);
		cntToken++;
		if (cntToken > 1)
			break;
		/* "string" �Ƀg�[�N�����Ȃ��Ȃ�܂ŌJ��Ԃ��܂��B */
		/* ���̃g�[�N�����擾���܂��B */
		token = strtok_s( NULL, sep, &nexttoken );
	}
	rtNo = atoi(tokenDat[1]);
	return rtNo;
}


// BIN�t�@�C���̓���t�@�C�����͍폜����
int CMaiFile::DelSameName(void)
{
	int	arrcnt, i, j, isCnt;
	CString str;
	CStringArray *arr;
	CArray<long,long> *arr_l;

	for (arrcnt = 0; arrcnt < 4; arrcnt++) {
		switch(arrcnt) {
		case 0:
			arr = &m_BinName0;
			arr_l = &m_Samples0;
			break;
		case 1:
			arr = &m_BinName1;
			arr_l = &m_Samples1;
			break;
		case 2:
			arr = &m_BinName2;
			arr_l = &m_Samples2;
			break;
		case 3:
			arr = &m_BinName3;
			arr_l = &m_Samples3;
			break;
		}
		// �������邩���ׂ�
		for (i = 0; i < arr->GetSize(); i++) {
			str = arr->GetAt(i);
			isCnt = 0;
			for (j = 0; j < arr->GetSize(); ) {
				if (str == arr->GetAt(j)) {
					// ��������ꍇ�A�폜����
					if (isCnt != 0) {
						arr->RemoveAt(j);
						arr_l->RemoveAt(j);
						continue;
					} else {
						isCnt++;
					}
				}
				j++;
			}
		}
	}
	return 0;
}

// BIN�t�@�C�������\�[�g����
void CMaiFile::SortArr(void)
{
	int	i, cnt, arrcnt;
	char (*p)[MAX_PATH];
	CStringArray *arr;

	for (arrcnt = 0; arrcnt < 4; arrcnt++) {
		switch(arrcnt) {
		case 0:
			arr = &m_BinName0;
			break;
		case 1:
			arr = &m_BinName1;
			break;
		case 2:
			arr = &m_BinName2;
			break;
		case 3:
			arr = &m_BinName3;
			break;
		}
		cnt = (int)arr->GetSize();
		p = new char[cnt][MAX_PATH];
		memset(p, 0, cnt * MAX_PATH);
		for (i = 0; i < cnt; i++) {
			strcpy_s(p[i], (LPCSTR)arr->GetAt(i));
		}
		qsort(p, cnt, MAX_PATH, cmpfunc);
		arr->RemoveAll();
		for (i = 0; i < cnt; i++) {
			arr->Add(p[i]);
		}
		delete [] p;
	}
}

// BIN�t�@�C������m�b���擾���āA�召��r����
static	int cmpfunc(const void *prm1, const void *prm2)
{
	unsigned long p1, p2;

	p1 = GetMSecFromFileName((LPCSTR)prm1);
	p2 = GetMSecFromFileName((LPCSTR)prm2);
	return p1 - p2;
}

// BIN�t�@�C������m�b���擾����
static	unsigned long GetMSecFromFileName(LPCSTR fname)
{
	int	cntToken;
	char buf[128], *token, *nexttoken;
	char *sep = "-_.";
	unsigned long rt;

	rt = 0;
	cntToken = 0;
	strcpy_s(buf, fname);
	// �g�[�N���擾
	token = strtok_s( buf, sep, &nexttoken);
	while( token != NULL ) {
		cntToken++;
		// BIN�t�@�C������m�b��10���ږ�
		if (cntToken >= 10) {
			rt = (unsigned long)atol(token);
			break;
		}
		/* "string" �Ƀg�[�N�����Ȃ��Ȃ�܂ŌJ��Ԃ��܂��B */
		/* ���̃g�[�N�����擾���܂��B */
		token = strtok_s( NULL, sep, &nexttoken );
	}
	return rt;
}

// �f�[�^�����Čv�Z����
void CMaiFile::RecalcTotal(void)
{
	INT_PTR	i, cnt;

	m_Total0 = 0;
	m_Total1 = 0;
	m_Total2 = 0;
	m_Total3 = 0;

	cnt = m_Samples0.GetSize();
	for (i = 0; i < cnt; i++)
		m_Total0 += m_Samples0.GetAt(i);

	cnt = m_Samples1.GetSize();
	for (i = 0; i < cnt; i++)
		m_Total1 += m_Samples1.GetAt(i);

	cnt = m_Samples2.GetSize();
	for (i = 0; i < cnt; i++)
		m_Total2 += m_Samples2.GetAt(i);

	cnt = m_Samples3.GetSize();
	for (i = 0; i < cnt; i++)
		m_Total3 += m_Samples3.GetAt(i);
}

// �X�^�[�g�^�C�����擾����
bool CMaiFile::GetStartTime(void)
{
	int	i;
	int	cnt = (int)m_maiDat.GetSize();
	char *sep="Start Time";
	CString	str;
	long YY, MM, DD, mS;
	bool	rt;

	rt = false;
	m_StartYY = 0;	// �J�n�����F�N
	m_StartMM = 0;	// �J�n�����F��
	m_StartDD = 0;	// �J�n�����F��
	m_StartMS = 0;	// �J�n�����Fm�b

	for (i = 0; i < cnt; i++) {
		// mai�t�@�C���f�[�^����X�^�[�g�^�C����������擾����
		if (GetDataFromMai(m_maiDat.GetAt(i), sep, str) != false) {
			// �X�^�[�g�^�C�������񂩂�AYYMMDDmS���擾����
			if (GetStartYMDmS((LPCSTR)str, &YY, &MM, &DD, &mS) != false) {
				m_StartYY = YY;	// �J�n�����F�N
				m_StartMM = MM;	// �J�n�����F��
				m_StartDD = DD;	// �J�n�����F��
				m_StartMS = mS;	// �J�n�����Fm�b
				rt = true;
				break;
			}
		}
	}

	return rt;
}

// �X�^�[�g�^�C�������񂩂�AYYMMDDmS���擾����
bool CMaiFile::GetStartYMDmS(LPCSTR str, long * YY, long * MM, long * DD, long * mS)
{
	int	cntToken;
	char buf[128], *token, *nexttoken;
	char *sep = " ";

	cntToken = 0;
	strcpy_s(buf, str);
	// �g�[�N���擾
	token = strtok_s( buf, sep, &nexttoken);
	while( token != NULL ) {
		// BIN�t�@�C������m�b��10���ږ�
		switch(cntToken) {
		case 0:
			*YY = atol(token);
			break;
		case 1:
			*MM = atol(token);
			break;
		case 2:
			*DD = atol(token);
			break;
		case 3:
			*mS = atol(token);
			break;
		}
		cntToken++;
		/* "string" �Ƀg�[�N�����Ȃ��Ȃ�܂ŌJ��Ԃ��܂��B */
		/* ���̃g�[�N�����擾���܂��B */
		token = strtok_s( NULL, sep, &nexttoken );
	}
	return (cntToken == 4);
}

// ���j�b�gID���擾����
bool CMaiFile::GetUnitID(void)
{
	int	i;
	int	cnt = (int)m_maiDat.GetSize();
	char *sep="Unit ID";
	CString	str;
	long	Unit;
	bool	rt;

	rt = false;
	m_RsuId = 0;

	for (i = 0; i < cnt; i++) {
		// mai�t�@�C���f�[�^���烆�j�b�gID��������擾����
		if (GetDataFromMai(m_maiDat.GetAt(i), sep, str) != false) {
			// �X�^�[�g�^�C�������񂩂�AYYMMDDmS���擾����
			Unit = strtol((LPCSTR)str, NULL, 0);
			m_RsuId = (Unit & 0xffff);
			m_NetworkId = ((Unit & 0xffff0000) >> 16);
			rt = true;
			break;
		}
	}
	return rt;
}

// �Z���T�[ID���擾����
bool CMaiFile::GetSensorID(void)
{
	int	i;
	int	cnt = (int)m_maiDat.GetSize();
	char *sep="Sensor";
	CString	str;
	long	Sensor;
	bool	rt;

	rt = false;
	m_SensorId = 0;

	for (i = 0; i < cnt; i++) {
		// mai�t�@�C���f�[�^���烆�j�b�gID��������擾����
		if (GetDataFromMai(m_maiDat.GetAt(i), sep, str) != false) {
			// �X�^�[�g�^�C�������񂩂�AYYMMDDmS���擾����
			Sensor = strtol((LPCSTR)str, NULL, 0);
			m_SensorId = (Sensor & 0xffff);
			rt = true;
			break;
		}
	}
	return rt;
}

// �T���v�����[�g���擾����
bool CMaiFile::GetSampleRate(void)
{
	int	i;
	int	cnt = (int)m_maiDat.GetSize();
	char *sep="Data Sample Rate";
	CString	str;
	long	Rate;
	bool	rt;

	rt = false;
	m_SampleRate = 0;

	for (i = 0; i < cnt; i++) {
		// mai�t�@�C���f�[�^���烆�j�b�gID��������擾����
		if (GetDataFromMai(m_maiDat.GetAt(i), sep, str) != false) {
			// �X�^�[�g�^�C�������񂩂�AYYMMDDmS���擾����
			Rate = strtol((LPCSTR)str, NULL, 0);
			m_CntPerHour = Rate;
			m_SampleRate = (double)Rate / 60.0 / 60.0;
			rt = true;
			break;
		}
	}
	return rt;
}

// �w��p�X��mai�t�@�C����ǂ݁A�J�n�����I���������Z�b�g����
int CMaiFile::ReadMaiFile(LPCSTR p_path, CDataDateTime * start, CDataDateTime * end)
{
	CString	path = p_path;
	CString	fname;
	CString	RsuNo;
	int	nRsu;

	// �f�[�^���Z�b�g
	ResetData();

	// mai�t�@�C������S�Ď擾
	if (GetMaiFileName(path) == 0) {
		CGeneral::Alert("�_�E�������N�t�@�C�������݂��܂���B");
		return 0;
	}

	// �擾����mai�t�@�C��������Z���TNo���擾����
	if ((nRsu = GetRsuFromMaiFileName()) == 0) {
		CGeneral::Alert("�_�E�������N�t�@�C�������݂��܂���B");
		return 0;
	}

// update 2008/03/18 by hama�@��
	// �����Z���T�[������ꍇ�́A�Z���T�[�I����ʕ\��
	if (nRsu > 1) {
		CSelectRsu	rsu;
		rsu.SetRsu(&m_Rsu);
		rsu.DoModal();
		rsu.GetRsu(RsuNo);
	} else {
		RsuNo = m_Rsu.GetAt(0);
	}
// update 2008/03/18 by hama�@��
/*
	// mai�t�@�C�����̓�MAX�l���܂ރt�@�C�������擾
	if (! GetMaxMaiFileName(fname, RsuNo)) {
		CGeneral::Alert("�_�E�������N�t�@�C�������݂��܂���B");
		return 0;
	}
*/
	// �����̊J�n���������f�[�^�����݂����ꍇ�́A�I����ʕ\��
	{
		CSelectStartTimeDlg dlg;
		fname = dlg.GetMaiFileName(RsuNo, m_Name);
	}

	// mai�t�@�C�����ۑ�
	m_maiFname = fname;

	//mai�t�@�C���f�[�^���擾
	if (ReadMaiFile2(path + "\\" + fname, TRUE) == 0)
		return 0;

	// mai�t�@�C������e���ڂ��擾
	if (!GetItems())
		return 0;

	// mai�t�@�C���̊J�n���Ԃƃf�[�^�����玞�Ԕ͈͎Z�o
	GetStartEnd(start, end);

	return 1;
}

// mai�t�@�C������e���ڂ��擾
BOOL CMaiFile::GetItems()
{
	// �X�^�[�g�^�C�����擾����
	if (! GetStartTime()) {
		CGeneral::Alert("�J�n�������擾�ł��܂���B");
		return 0;
	}

	// ���j�b�gID���擾����
	if (! GetUnitID()) {
		CGeneral::Alert("UnitID���擾�ł��܂���B");
		return 0;
	}

	// �Z���T�[ID���擾����
	if (! GetSensorID()) {
		CGeneral::Alert("SensorID���擾�ł��܂���B");
		return 0;
	}

	// �T���v�����[�g���擾����
	if (! GetSampleRate()) {
		CGeneral::Alert("DataSampleRate���擾�ł��܂���B");
		return 0;
	}

	return 1;
}

// mai�t�@�C���̊J�n���Ԃƃf�[�^�����玞�Ԕ͈͎擾
void CMaiFile::GetStartEnd(CDataDateTime *start, CDataDateTime *end)
{
	start->SetTime(m_StartYY, m_StartMM, m_StartDD, m_StartMS);
	*end = *start + (long)(m_Total0 / 4 * 40 / m_SampleRate * 1000);
}

// �w��mai�t�@�C����ǂ�
int CMaiFile::ReadMaiFile2(LPCSTR maiPath, BOOL bDispErrMsg)
{
	if (GetMaFileiData(maiPath) == 0) {
		if (bDispErrMsg)
			CGeneral::Alert("�_�E�������N�t�@�C�������݂��܂���B");
		return 0;
	}

	// BIN�t�@�C������0,1,2,3���ɐU�蕪����
	FuriwakeBinFileName();

	// ����t�@�C�����͍폜����
	DelSameName();

	// �t�@�C�������\�[�g����
	SortArr();

	// �f�[�^�����Čv�Z����
	RecalcTotal();

	return 1;
}

// BIN�t�@�C���̃w�b�_����IDB�ɕۑ�
void CMaiFile::GetBinInfo(LPCSTR fname)
{
	CBinFile binFile;
	if (binFile.OpenBin(fname)) {
		binFile.ReadHeader();
		binFile.CloseBin();
		// �Q�C���ݒ�
		m_Idb.SetGain((int)(pow(2.0, (double)(binFile.m_CalGain & 0x0f))));
		// �J�b�g�I�t���g��
		m_Idb.SetCutoff(binFile.m_CutOff);
		// BIAS�iTBD)
		m_Idb.SetBiascoeff(0);
		// �X�P�[���t�@�N�^�[�iTBD)
		m_Idb.SetScalefactor(0);
		m_Cutofff = binFile.m_CutOff;	// �J�b�g�I�t���g��
		m_Offset = binFile.m_Offset;	// �I�t�Z�b�g
	}
}

// �f�[�^���o�͂���i�����t�@�C�����H�w�l�t�@�C���܂Łj
BOOL CMaiFile::WriteData(LPCSTR p_path, long Start_msec, long End_msec)
{
	CProgressBar cProgressBar;
	BOOL	rt;

	m_path = p_path;		// �������݃t�H���_�p�X
	m_Start_msec = Start_msec;
	m_End_msec = End_msec;

	m_CalcFile = new CCalcFile;

	// BIN�t�@�C���̃w�b�_����IDB�ɕۑ�
	GetBinInfo(m_path+"\\"+m_BinName0.GetAt(0));

	// �p�����[�^���n��
	if (! m_CalcFile->SetArg(m_RsuId, m_SensorId, m_CntPerHour, m_Offset, m_Cutofff)) {
		delete m_CalcFile;
		CGeneral::Alert("�H�w�l�ϊ��p�萔�e�[�u���ɕs��������܂��B�ϊ��ł��܂���B");
		return FALSE;
	}

	// �v���O���X�o�[��\�����A�ʃX���b�h�Ńt�@�C����ǂݍ���
	if (cProgressBar.ExecProcess("�����t�@�C���������ݒ�", 10, this, WriteDataThread) == FALSE) {
		delete m_CalcFile;
		return FALSE;
	}

	// �v���O���X�o�[��\�����A�ʃX���b�h�Ńt�@�C����ǂݍ���
	rt = cProgressBar.ExecProcess("�H�w�l�ϊ��t�@�C���������ݒ�", 10, this, WriteDataThread2);

	delete m_CalcFile;

	return rt;
}

BOOL CMaiFile::WriteDataThread(LPVOID pParam, CProgressBar* pProgressBar)
{
	return ((CMaiFile *)pParam)->WriteKetugouFile(pProgressBar);
}

BOOL CMaiFile::WriteDataThread2(LPVOID pParam, CProgressBar* pProgressBar)
{
	return ((CMaiFile *)pParam)->WriteCalcData(pProgressBar);
}

// �w�莞����BIN�t�@�C�����������ď�������
BOOL CMaiFile::WriteKetugouFile(CProgressBar* pProgressBar)
{
	CStdioFile dfp;
	CFileException f;
	int	i, j, nBin;
	long Cnt, Total, sa, WriteCnt, zan, from, PTotalCnt, PWCnt;
	CArray<long, long>*smpl;
	CStringArray	*arr;
	long startCnt, endCnt, sv_startCnt, sv_endCnt;
	CString path = m_path;
	CString	fname;

	// �J�n�����̐擪����̌��A�I�������̐擪����̌����Z�o
	sv_startCnt = (long)(m_Start_msec * m_SampleRate / 1000);
	sv_endCnt = (long)(m_End_msec * m_SampleRate / 1000);
	PTotalCnt = (sv_endCnt - sv_startCnt) * 3 + (sv_endCnt - sv_startCnt) / 10;
	PWCnt = 0;

	// 0-3�A4�t�@�C�������[�v
	for (i = 0; i < 4; i++) {
		switch(i) {
		case 0:
			startCnt = sv_startCnt / 10;
			endCnt = sv_endCnt / 10;
			arr = &m_BinName0;
			smpl = &m_Samples0;
			fname.Format("%s\\%s", m_WorkPath, FN_DAT0);
			break;
		case 1:
			startCnt = sv_startCnt;
			endCnt = sv_endCnt;
			arr = &m_BinName1;
			smpl = &m_Samples1;
			fname.Format("%s\\%s", m_WorkPath, FN_DAT1);
			break;
		case 2:
			startCnt = sv_startCnt;
			endCnt = sv_endCnt;
			arr = &m_BinName2;
			smpl = &m_Samples2;
			fname.Format("%s\\%s", m_WorkPath, FN_DAT2);
			break;
		case 3:
			startCnt = sv_startCnt;
			endCnt = sv_endCnt;
			arr = &m_BinName3;
			smpl = &m_Samples3;
			fname.Format("%s\\%s", m_WorkPath, FN_DAT3);
			break;
		}
		Total = 0;
		WriteCnt = 0;
		zan = endCnt - startCnt;
		// �����t�@�C��1OPEN
		if (dfp.Open((LPCSTR)fname, CFile::modeCreate | CFile::modeWrite | CFile::typeBinary, &f)) {
			// �����J�E���g�����擾
			nBin = (int)smpl->GetSize();
			for (j = 0; j < nBin; j++) {
				// �{�t�@�C���̌��擾
//				Cnt = smpl->GetAt(i);
				Cnt = smpl->GetAt(j);
				// �������Z�o
				Total += Cnt;
				// �������ݐ����I�������傫����ΏI��
				if (WriteCnt >= endCnt)
					break;
				// �f�[�^�������J�n����菭�Ȃ���Ύ�
				if (Total < startCnt)
					continue;
				// �����ƊJ�n���Ƃ̍������߂�
				sa = Total - startCnt;
				// �{�t�@�C������荷���傫����ΑS�ď���
				if (sa >= Cnt)
					from = 0;
				else
					from = Cnt - sa;

				CBinFile binFile;
				if (binFile.OpenBin(path+"\\"+arr->GetAt(j))) {
					int	cnt;
					cnt = binFile.WriteData(dfp, from, zan);
					WriteCnt += cnt;
					PWCnt += cnt;
					binFile.CloseBin();
				} else {
					CString	str;
					str.Format("�f�[�^�s�����ł��B\nbin�t�@�C��[%s]�����݂��܂���̂Œ��~���܂��B", arr->GetAt(j));
					CGeneral::Alert(str);
					return FALSE;
				}
				zan = endCnt - WriteCnt;
				if (!pProgressBar->SetProgress(PWCnt * 10 / PTotalCnt)) {
					// �f�[�^�t�@�C�����N���[�Y
					dfp.Close();
					return FALSE;
				}
			}
			dfp.Close();
		}
	}
	return TRUE;
}

BOOL CMaiFile::WriteCalcData(CProgressBar* pProgressBar)
{
	int	i;
	long	msecCount, period;
	char	c;

	CString	fn0, fn1, fn2, fn3;
	fn0.Format("%s\\%s", m_WorkPath, FN_DAT0);
	fn1.Format("%s\\%s", m_WorkPath, FN_DAT1);
	fn2.Format("%s\\%s", m_WorkPath, FN_DAT2);
	fn3.Format("%s\\%s", m_WorkPath, FN_DAT3);

	CDataDateTime TimeZero, to;
	TimeZero = m_In_startTime;
	to = TimeZero;
	// �f�[�^���Ԕ͈͂��Z�o�imsec)
	period = m_End_msec - m_Start_msec;

	// �����t�@�C�����H�w�l�ϊ���1���Ԗ��̃t�@�C���ɏ�������
	if (m_CalcFile->Open((LPCSTR)fn0, (LPCSTR)fn1, (LPCSTR)fn2, (LPCSTR)fn3)) {
		CString	str, str2, fullpath;
		// �����ԑ��݂��邩�Z�o
		int	nHour = period / 3600000 + ((period % 3600000) != 0);
		// ���݂��鎞�Ԑ������[�v
		for (i = 0; i < nHour; i++) {
			msecCount = 0;
			if (period >= (i+1) * 3600000)
				msecCount = 3600000;
			else
				msecCount = (period - i * 3600000);
			// �����t�@�C���t�@�C�����ҏW
			to += msecCount;
			if (i == 0) {
				// �����'-'�}�C�i�X
				c = '-';
			} else {
				// ����ȊO��'+'�v���X
				c = '+';
			}
// 2008/5/13 modified by yG for sensor id format (decimal to hex) ->
//			str.Format("%04d_%02d_%02d_%02d_%02d_%02d.%03d%c%04d_%02d_%02d_%02d_%02d_%02d.%03d.%04d"
			str.Format("%04d_%02d_%02d_%02d_%02d_%02d.%03d%c%04d_%02d_%02d_%02d_%02d_%02d.%03d.%04x"
// <- 2008/5/13 modified by yG for sensor id format (decimal to hex)
				, TimeZero.m_YY
				, TimeZero.m_MM
				, TimeZero.m_DD
				, TimeZero.m_hh
				, TimeZero.m_mm
				, TimeZero.m_ss
				, TimeZero.m_ttt
				, c
				, to.m_YY
				, to.m_MM
				, to.m_DD
				, to.m_hh
				, to.m_mm
				, to.m_ss
				, to.m_ttt
				, m_SensorId);
//			str.Format("%04d%04d.dat", m_RsuId, i+1);
			str2 = str;

			fullpath.Format("%s\\%s", m_ConvDataPath, str);
			m_CalcFile->ReadOneHour();
			m_CalcFile->WriteOneHour((LPCSTR)fullpath, i + 1);

			m_Idb.AddFile((LPCSTR)str);
			// �^�C���[��
			str.Format("%04d_%02d_%02d_%02d_%02d_%02d.%03d"
				, TimeZero.m_YY
				, TimeZero.m_MM
				, TimeZero.m_DD
				, TimeZero.m_hh
				, TimeZero.m_mm
				, TimeZero.m_ss
				, TimeZero.m_ttt);
			m_Idb.SetTimeZeroXML(str);
			m_Idb.WriteXml(str2, DATA_TYPE_MMA);
			TimeZero += 3600000;
			// �v���O���X�o�[���X�V
			if (!pProgressBar->SetProgress((i + 1) * 10 / nHour)) {
				// �f�[�^�t�@�C�����N���[�Y
				m_CalcFile->Close();
				return FALSE;
			}
		}
		m_CalcFile->Close();
		str.Format("%04d%04d%02d%02d%d", m_RsuId, m_StartYY, m_StartMM, m_StartDD, m_StartMS);
		m_Idb.Write(str);
	}

	return TRUE;
}
