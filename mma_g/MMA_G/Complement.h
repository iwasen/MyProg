// mma_check2Dlg.h : �w�b�_�[ �t�@�C��
//

#pragma once

// �Ή��\�ȁAEventLog����"DACM A GetPacketHeader Errors 4"���b�Z�[�W��MAX
#define EventLogErrMax  1000

// �C�x���g���O��̃G���[��bin�t�@�C�����f�[�^�����Ƃ�
// ���ԍ��̋��e�l [sec]
#define TIME_TOLERANCE     2

struct ST_EventErrTime {
	time_t time_t;            // �װ��������(GMT�b),�@0:�ް�end
	long nPosition;
	bool bDelete;
};

struct str_header {
	unsigned short int year;            // MSB:[0]
	unsigned char month;
	unsigned char day;
	unsigned long int miliseconds;
	unsigned char dummy1[10];
	unsigned short int SAMPLE_RATE;
	unsigned char dummy2[14];
	unsigned short int AUTO_ZERO;
	unsigned long int SAMPLE_INDEX;
	unsigned long int DATA_SIZE;
};

struct str_binfile {
	str_header header;
	unsigned char tempdata[40*1024/3][3];        // 40kB��
};

// struct binfile  binfile;
union uni_binfile {
	str_binfile binfile;
	unsigned char charbuf[300*1024];  // ��300kB�܂� ��bin�t�@�C���ɑΉ���
};


// Cmma_check2Dlg �_�C�A���O
class Complement
{
public:
	Complement();
	bool ComplementData(CString sDataDir, CString sMaiFile);

	CString m_sErrLogPath;

protected:
	long l_EndianExchange(long x);
	unsigned short int i_EndianExchange(unsigned short int x);
	char* get_time_from_YYMMDDmsec(char* time, unsigned long sampleNo);
	char* get_time_from_sampleNo(char* time, unsigned long sampleNo);
	bool is_DACMdata(unsigned char* chptr);
	int cal_hyoka(unsigned short int u, unsigned short int v, unsigned short int a, unsigned short int b);
	void insertX(unsigned char* a, unsigned char* x, FILE *fptr);
	void insertY(unsigned char* b, unsigned char* y, FILE *fptr);
	void insertZ(unsigned char* b, unsigned char* z, FILE *fptr);
	void insertXY(unsigned char* a, unsigned char* x, FILE *fptr);
	void insertYZ(unsigned char* a, unsigned char* y, FILE *fptr);
	void proc_for_4nPlusX_datalost(int* datanum, FILE* fptr, int i, int xx);
	void proc_for_DACM_datalost(int* datanum, FILE* fptr, int i);
	int add_extention_bak_to_FileName(LPCTSTR FileName_w_Path, LPCTSTR outPath);
	void EventErr_set(CString path, int nUnitID);
	bool ComplementData2(CString sDataDir, CString sMaiFile, FILE *&in, FILE *&in_bin, FILE *&outf_e);

	struct ST_EventErrTime EventErrTime[EventLogErrMax];
	unsigned long int SampleAtError[EventLogErrMax];    // �װ����SampleNo,�@0xFFFFFFFF:�ް�end
	unsigned long int ErrPos[EventLogErrMax];           // BIN�t�@�C�������Z�װ����SampleNo,�@0xFFFFFFFF:�ް�end
	struct tm starttime, endtime;
	time_t starttime_t, endtime_t;
	union uni_binfile binfilebuf;
	CString m_sMelFile;

// 2010/10/01 modified by y.GOTO for ver.4.02 ->
	unsigned char	m_perior_DACMval;
	int				m_initial;
// <- 2010/10/01 modified by y.GOTO for ver.4.02
};
