// mma_check2Dlg.cpp : �����t�@�C��
//

#include "stdafx.h"
#include "Complement.h"
#include "io.h"
#include "General.h"
#include "shlwapi.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#endif

#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <math.h>
#include <dos.h>
#include <time.h>

#pragma warning(disable:4996)

Complement::Complement()
{
	memset(EventErrTime, 0, sizeof(EventErrTime));

// 2010/10/01 modified by y.GOTO for ver.4.02 ->
	m_perior_DACMval = 0;
	m_initial = 1;
// <- 2010/10/01 modified by y.GOTO for ver.4.02
}

long Complement::l_EndianExchange(long x)
{
	long y;

	y =  (x & 0x000000ff) << 24;
	y += (x & 0x0000ff00) << 8;
	y += (x & 0x00ff0000) >> 8;
	y += (x & 0xff000000) >> 24;
	return y;
}

unsigned short int Complement::i_EndianExchange(unsigned short int x)
{
	unsigned short int y;

	y =  (unsigned short int)((x & 0x00ff) << 8);
	y += (unsigned short int)((x & 0xff00) >> 8);
	return y;
}

char* Complement::get_time_from_YYMMDDmsec(char* time, unsigned long sampleNo)
{
	unsigned long ms;
	int days, hours, minutes, seconds;

	ms = (unsigned long)(l_EndianExchange(binfilebuf.binfile.header.miliseconds) // �v���J�n����
			+ (l_EndianExchange(binfilebuf.binfile.header.SAMPLE_INDEX)+ sampleNo)
			* 0.5120 * i_EndianExchange(binfilebuf.binfile.header.SAMPLE_RATE));
	for (   days = 0; ms >= 86400000; days++,    ms -= 86400000);
	for (  hours = 0; ms >=  3600000; hours++,   ms -=  3600000);
	for (minutes = 0; ms >=    60000; minutes++, ms -=    60000);
	for (seconds = 0; ms >=     1000; seconds++, ms -=     1000);
	sprintf(time, "%04d_%02d_%02d-%02d_%02d_%02d_%03d",
	i_EndianExchange(binfilebuf.binfile.header.year),
			binfilebuf.binfile.header.month,
			binfilebuf.binfile.header.day + days,
			hours,
			minutes,
			seconds,
			ms);
	return time;
}

char* Complement::get_time_from_sampleNo(char* time, unsigned long sampleNo)
{
	unsigned long ms;
	int days, hours, minutes, seconds;

	ms = (unsigned long)(l_EndianExchange(binfilebuf.binfile.header.miliseconds) // �v���J�n����
			+ (l_EndianExchange(binfilebuf.binfile.header.SAMPLE_INDEX)+ sampleNo)
			* 0.5120 * i_EndianExchange(binfilebuf.binfile.header.SAMPLE_RATE));
	for (   days = 0; ms >= 86400000; days++,    ms -= 86400000);
	for (  hours = 0; ms >=  3600000; hours++,   ms -=  3600000);
	for (minutes = 0; ms >=    60000; minutes++, ms -=    60000);
	for (seconds = 0; ms >=     1000; seconds++, ms -=     1000);
	sprintf(time, "%04d_%02d_%02d-%02d_%02d_%02d_%03d",
			i_EndianExchange(binfilebuf.binfile.header.year),
			binfilebuf.binfile.header.month,
			binfilebuf.binfile.header.day + days,
			hours,
			minutes,
			seconds,
			ms);
	return time;
}


bool Complement::is_DACMdata(unsigned char* chptr)
{
// 2010/10/01 modified by y.GOTO for ver.4.02 ->
//	return((*chptr>>4) == 7 && *(chptr+1) ==0 && *(chptr+2) ==0);
	if ((*(chptr+1) != 0 || *(chptr+2) != 0))
	{
		// ����2byte��0�łȂ����False
		return (false);
	}
	if (m_initial || abs(*chptr-m_perior_DACMval) <= 4)
	{
		// �O��l�Ƃ̍���4�ȓ��Ȃ��True
		m_perior_DACMval	= *chptr;
		m_initial			= 0;
		return (true);
	}
	return (false);
// <- 2010/10/01 modified by y.GOTO for ver.4.02
}

int Complement::cal_hyoka(unsigned short int u,
	unsigned short int v,
	unsigned short int a,
	unsigned short int b) {

	u = i_EndianExchange(u);
	v = i_EndianExchange(v);
	a = i_EndianExchange(a);
	b = i_EndianExchange(b);

	return((u-a)*(u-a)+(v-b)*(v-b));
}

void Complement::insertX(unsigned char* a, unsigned char* x, FILE *fptr)
{
	memmove(a+3, a, 6);   // a,b��3Byte���ɂ��炷
	memcpy(a, x, 3);      // �󂢂����ɑO�񂘃f�[�^���R�s�[
	fprintf(fptr, " -> x data was inserted.\n\n");
}

void Complement::insertY(unsigned char* b, unsigned char* y, FILE *fptr)
{
	memmove(b+3, b, 3);   // b��3Byte���ɂ��炷
	memcpy(b, y, 3);      // �󂢂����ɑO��y�f�[�^���R�s�[
	fprintf(fptr, " -> y data was inserted.\n\n");
}

void Complement::insertZ(unsigned char* b, unsigned char* z, FILE *fptr)
{
	memcpy(b+3, z, 3);      // �󂢂Ă��鏊�ɑO��z�f�[�^���R�s�[
	fprintf(fptr, " -> z data was inserted.\n\n");
}

void Complement::insertXY(unsigned char* a, unsigned char* x, FILE *fptr)
{
	memmove(a+6, a, 3);   // a��6Byte���ɂ��炷
	memcpy(a, x, 6);      // �󂢂����ɑO��,y�f�[�^���R�s�[
	fprintf(fptr, " -> x,y data was inserted.\n\n");
}

void Complement::insertYZ(unsigned char* a, unsigned char* y, FILE *fptr)
{
	memcpy(a+3, y, 6);      // �󂢂����ɑO��y,Z�f�[�^���R�s�[
	fprintf(fptr, " -> y,z data was inserted.\n\n");
}

void Complement::proc_for_4nPlusX_datalost(int* datanum, FILE* fptr, int i, int xx)
{
	unsigned char *x, *y, *z, *a, *b;
	unsigned int pos;
	char buf[300];

	pos = sizeof(binfilebuf.binfile.header)+i*3;
	fprintf(fptr, "4N+%1d data lost.\n", xx);
	fprintf(fptr, "@%s (Byte:%x)\n", get_time_from_sampleNo(buf, i), pos);

	x = &binfilebuf.charbuf[pos-21];
	y = x+3;
	z = y+3;
	a = x+12;
	b = a+3;
	fprintf(fptr, "x,y,z @(k-1): %02X%02X%02X,%02X%02X%02X,%02X%02X%02X\n",
	*x, *(x+1), *(x+2), *y, *(y+1), *(y+2), *z, *(z+1), *(z+2));
	fprintf(fptr, "x,y,z @k    : %02X%02X%02X,%02X%02X%02X,%02X%02X%02X\n",
	*a, *(a+1), *(a+2), *b, *(b+1), *(b+2), *(b+3), *(b+4), *(b+5));

	memmove(&binfilebuf.charbuf[pos],       // �f�[�^�}���ꏊ�m��
	&binfilebuf.charbuf[pos-xx*3],
	(*datanum-i+xx)*3);
	(*datanum) += xx;                        // �f�[�^���X�V

	if (xx == 1) {  //�@4N+1�T���v�������̏ꍇ
		unsigned int hyokaX, hyokaY, hyokaZ;

		hyokaX = cal_hyoka(*(unsigned short int*)y,
		*(unsigned short int*)z,
		*(unsigned short int*)a,
		*(unsigned short int*)b);
		hyokaY = cal_hyoka(*(unsigned short int*)x,
		*(unsigned short int*)z,
		*(unsigned short int*)a,
		*(unsigned short int*)b);
		hyokaZ = cal_hyoka(*(unsigned short int*)x,
		*(unsigned short int*)y,
		*(unsigned short int*)a,
		*(unsigned short int*)b);

		// �]�����ʂɂ���āAx�y�z �̉��ꂩ�̑O��f�[�^��}��
		if (hyokaX < hyokaY) {
			if (hyokaX < hyokaZ) insertX(a, x, fptr);
			else insertZ(b, z, fptr);
		}else if (hyokaY < hyokaZ) insertY(b, y, fptr);
		else insertZ(b, z, fptr);
	} else if (xx == 2) {  //�@4N+2�T���v�������̏ꍇ
		unsigned int hyokaXY, hyokaYZ;

		// X,Y�������A���Ȃ킿�c�����P�T���v����z�f�[�^�Ɖ��肵���ۂ̕]��
		hyokaXY = cal_hyoka(*(unsigned short int*)y,  // y�͎����]�����Ȃ�
		*(unsigned short int*)z,
		*(unsigned short int*)y,   // y�͎����]�����Ȃ�
		*(unsigned short int*)a);
		// Y,Z�������A���Ȃ킿�c�����P�T���v����x�f�[�^�Ɖ��肵���ۂ̕]��
		hyokaYZ = cal_hyoka(*(unsigned short int*)x,
		*(unsigned short int*)y,   // y�͎����]�����Ȃ�
		*(unsigned short int*)a,
		*(unsigned short int*)y);   // y�͎����]�����Ȃ�

		// �]�����ʂɂ���āAx�y� �܂���y,z �̉��ꂩ�̑O��f�[�^��}�� ������
		if (hyokaXY < hyokaYZ)insertXY(b, z, fptr);
		else insertYZ(b, z, fptr);
	} else {       //�@4N+2�܂���3�T���v�������̏ꍇ
		// �O���x,y,z�f�[�^��}���B
		memcpy(&binfilebuf.charbuf[pos-3*3],
		&binfilebuf.charbuf[pos-3*3-12],
		3*3);
		fprintf(fptr, " -> x,y,z data were inserted.\n\n");
	}
}

void Complement::proc_for_DACM_datalost(int* datanum, FILE* fptr, int i)
{
	unsigned int pos;
	char buf[300];

	fprintf(fptr, "DACM data lost.\n");
	fprintf(fptr, "@%s (dataNo.=%d)", get_time_from_sampleNo(buf, i), i);
	fprintf(fptr, " -> DACM data were inserted.\n");

	pos = sizeof(binfilebuf.binfile.header)+i*3;
	memmove(&binfilebuf.charbuf[pos+3],
			&binfilebuf.charbuf[pos],
			(*datanum-i)*3);
	memcpy(&binfilebuf.charbuf[pos],
			&binfilebuf.charbuf[pos-12],
			3);
	(*datanum)++;
}

int Complement::add_extention_bak_to_FileName(LPCTSTR FileName_w_Path, LPCTSTR outPath)
{
	char tempbuf[300];

	strcpy(tempbuf, FileName_w_Path);
	PathStripPath(tempbuf);

	CString sBakPath;
	sBakPath.Format("%s%s.bak", outPath, tempbuf);
	if (!MoveFileEx(FileName_w_Path, sBakPath, MOVEFILE_REPLACE_EXISTING)) {
		CGeneral::Alert("%s���쐬�ł��܂���B", sBakPath);
		return(-1);
	};
	return(0);
}

void Complement::EventErr_set(CString path, int nUnitID)
{
	FILE *fptr2;
	int cnt, pos;
	char buf1[300], buf2[300];
	CString sMelPath;

	// �ŐV��mel�t�@�C��������
	CFileFind FileFind;
	CString sSearchFile;
	sSearchFile.Format("%s\\*.mel", path);
	if (!FileFind.FindFile(sSearchFile)) 
		return; 

	BOOL bContinue = TRUE;
	while (bContinue) { 
		bContinue = FileFind.FindNextFile();

		int unitID = atoi(FileFind.GetFileName().Left(4));

		if (unitID == nUnitID) {
			if (FileFind.GetFileName() > m_sMelFile)
				m_sMelFile = FileFind.GetFileName();
		}
	}

	if (!m_sMelFile.IsEmpty()) {
		if ((fptr2 = fopen(path + m_sMelFile, "rb")) != NULL) {
			cnt = 0;
			pos = 0;
			while (fread(buf1, 26, 1, fptr2) == 1) {
				char *e;
				int len;
				buf1[26] = 0;
				len = strtol(&buf1[22], &e, 16);
				fread(buf2, (1 + len + 1) / 2 * 2, 1, fptr2);    // .PARAM_X��raed�@�A������byte
				if (strncmp(&buf1[17], "8309", 4) == 0) {                // GetPacketHeaderError
					buf1[8] = 0;
					EventErrTime[cnt].time_t = strtol(buf1, &e, 16);
					EventErrTime[cnt].nPosition = pos;
					EventErrTime[cnt].bDelete = false;
					cnt++;
				}

				pos++;
			}
			EventErrTime[cnt].time_t = 0;    // data END
			fclose(fptr2);
		}
	}
}

// 2010/11/17 modified by y.GOTO mai�t�@�C���s�������ύX ver4.03 ->
//char maiFileBuf[10000][80];     // ��800kB�܂�
char maiFileBuf[30000][80];
// <- 2010/11/17 modified by y.GOTO mai�t�@�C���s�������ύX ver4.03

//---------------------------------------------------------------------------
//  �t�@�C���`�F�b�N�A�f�[�^�⊮
//---------------------------------------------------------------------------
bool Complement::ComplementData(CString sDataDir, CString sMaiFile)
{
	FILE *in = NULL, *in_bin = NULL, *outf_e = NULL;

	// �f�[�^�⊮�����{��
	bool result = ComplementData2(sDataDir, sMaiFile, in, in_bin, outf_e);

	// �t�@�C���N���[�Y
	if (in != NULL)
		fclose(in);
	if (in_bin != NULL)
		fclose(in_bin);
	if (outf_e != NULL)
		fclose(outf_e);

	return result;
}

bool Complement::ComplementData2(CString sDataDir, CString sMaiFile, FILE *&in, FILE *&in_bin, FILE *&outf_e)
{
	CString maiFileName_w_Path;
	CString binFileName_w_Path;
	CString inPath, outPath, melPath;
	char maibuf[300];
	long mai_line_no;
	unsigned long sampleNo;
	int i, j, year, datanum;
	long file_size, this_inserted_data_num, next_inserted_data_num;
	long inserted_data_num;
	int file_count = 0;
	int binfile_is_OK, ERR;
	int next_first_DACM_pos;
	int binfile_update_required = 0;
	long tolerance;
	int hokanOK_NG = 0;  // �f�[�^�⊮�i�t�@�C���ǉ��A�ύX�j��
	int nUnitID;
	bool bLogFileBak;

	inPath.Format("%s\\", sDataDir);
	outPath.Format("%s\\Complement\\", sDataDir);
	melPath.Format("%s\\Event Logs\\", sDataDir);
	nUnitID = atoi(sMaiFile.Left(4));

	// Complement�f�B���N�g���쐬
	CreateDirectory(outPath, NULL);

	// EventLog(.mel�t�@�C��)�̌����A"DACM A GetPacketHeader Errors 4"�m�F
	// EventErrTime:Error���������O���j�b�W���ԁi�b�j���Z�b�g�����
	EventErr_set(melPath, nUnitID);

	ERR = 0; // error counter

	// error log file open
	m_sErrLogPath.Format("%smma_Err.log", outPath);
	if ((outf_e = fopen(m_sErrLogPath , "wt")) == NULL) {
		CGeneral::Alert("%s���쐬�ł��܂���B", m_sErrLogPath);
		return false;
	}

	// -- data check �J�n --
	int ErrPosIND = 0;

	// mai�t�@�C��open
	maiFileName_w_Path.Format("%s%s", inPath, sMaiFile);
	if ((in = fopen(maiFileName_w_Path, "rt")) == NULL) {
		CGeneral::Alert("%s���J���܂���B", maiFileName_w_Path);
		return false;
	}

	fprintf(outf_e, "%s\n", maiFileName_w_Path);  // mai�t�@�C������������

	j = 0;
	mai_line_no = 0;
	next_first_DACM_pos = 0;
	next_inserted_data_num = 0;
	inserted_data_num = 0;
	sampleNo = 0;
	bLogFileBak = false;

	// mai�t�@�C������v���J�n���ԁA�I���\�莞�Ԃ𒊏o
	{
		char *e;
		int ms, hours, minutes, seconds, unitID, cnt;
		long samples;
		unsigned int rate;
		float SampleRateHz;

		fgets(maibuf, 300, in);   // �ŏ��̍s�ǂݎ̂�
		fgets(maibuf, 300, in);
		maibuf[15] = maibuf[18] = maibuf[21] = 0;     // YY,MM,DD,msec��؂�
		maibuf[strlen(maibuf)] = 0; // \n -> 0
		starttime.tm_year = atoi(&maibuf[11])-1900;
		starttime.tm_mon = atoi(&maibuf[16])-1;
		starttime.tm_mday = atoi(&maibuf[19]);
		ms = atoi(&maibuf[22]);
		for(  hours = 0; ms>= 3600000; hours++,   ms-= 3600000);
		for(minutes = 0; ms>=   60000; minutes++, ms-=   60000);
		for(seconds = 0; ms>=    1000; seconds++, ms-=    1000);
		starttime.tm_hour = hours;
		starttime.tm_min = minutes;
		starttime.tm_sec = seconds;
		//        strftime(c, 100, "%Y_%m_%d-%H_%M_%S", &starttime);
		// mktime��starttime��JST�Ƃ��ĕϊ�����̂ŁA
		// JST�Ƃ̍�9Hr�������҂����B�����⏞
		starttime_t = mktime(&starttime) + 9*3600;
//			tmptr = localtime(&starttime_t);
//			strftime(c1, 100, "%Y_%m_%d-%H_%M_%S", tmptr);

		fgets(maibuf, 300, in);   // UnitID
		maibuf[strlen(maibuf)] = 0; // \n -> 0
		unitID = strtol(&maibuf[14], &e, 16);
		fgets(maibuf, 300, in);   // Module�s�ǂݎ̂�
		fgets(maibuf, 300, in);   // Sensor�s�ǂݎ̂�
		fgets(maibuf, 300, in);   // Sample Rate (samples/Hr; �����x�ް��j
		maibuf[strlen(maibuf)] = 0; // \n -> 0
		rate = atoi(&maibuf[17]);
		fgets(maibuf, 300, in);   // First Sample Num�s�ǂݎ̂�
		fgets(maibuf, 300, in);   // Samples�s
		maibuf[strlen(maibuf)] = 0; // \n -> 0
		samples = atoi(&maibuf[8]);
		// rate(samples/Hr)���I���\�莞�Ԃ��Z�o�B
		// ���x�ް��͉����x�ް���1/10�̃��[�g
		SampleRateHz = (float)(rate / 3600.0 / 10);
		tolerance = (long)(TIME_TOLERANCE * SampleRateHz);
		endtime_t = (time_t)(starttime_t + samples / SampleRateHz);
//			strcpy(c, ctime(&starttime_t));
//			strcpy(c1, ctime(&endtime_t));
//			fseek(in, 0L, SEEK_SET);

		for (j = 0, cnt = 0; j < EventLogErrMax; j++) {
			time_t diftime_t;

			if (EventErrTime[j].time_t == 0)break; // �ް����Ȃ���Δ�����
			diftime_t = EventErrTime[j].time_t - starttime_t;

			// �C�x���g���O�̴װ��������������mai�t�@�C���͈͓��Ȃ��
			// SampleAtError�@�ɑΉ�����T���v���ԍ����L�^
			if (diftime_t >= 0 && EventErrTime[j].time_t < endtime_t) {
				// ������cnt�͕K��EventLogErrMax��菬����
				SampleAtError[cnt++] = (unsigned long)(diftime_t * SampleRateHz);
				EventErrTime[j].bDelete = true;
				bLogFileBak = true;
			}
		}
		SampleAtError[cnt++] = 0xFFFFFFFF;      // �ް�end

	}

	// mai�t�@�C���������ǂݍ��݁Abin�t�@�C���`�F�b�N
	fseek(in, 0L, SEEK_SET);
	ErrPosIND = 0;
	while (fgets(maibuf, 300, in) != NULL) { // mai file line read
		unsigned long int ErrorSample;

		strcpy(maiFileBuf[mai_line_no++], maibuf);
// 2010/11/17 modified by y.GOTO mai�t�@�C���s�������ύX ver4.03 ->
//		if (mai_line_no >= 10000) {
		if (mai_line_no >= 30000) {
// <- 2010/11/17 modified by y.GOTO mai�t�@�C���s�������ύX ver4.03
			CGeneral::Alert("mai�t�@�C�����傫�����܂��B");
			return false;
		}

		// ���x�t�@�C�����łȂ����mai�t�@�C���̎��s�ǂݍ��݂�
		if (maibuf[0]!='F' || maibuf[10]!='0') continue;

		file_count++; // check file���X�V

		maibuf[strlen(maibuf)-1] = 0;    // ���s�R�[�h�폜
		binFileName_w_Path.Format("%s%s", inPath, &maibuf[5]);
//			fputs(maibuf, outf); // bin file name write
		year = atoi(&maibuf[12]);

		// BIN�t�@�C�����I�[�v��
		if ((in_bin = fopen(binFileName_w_Path, "rb")) == NULL)
		{
			fprintf(outf_e, "%s\n", maibuf);    // bin�t�@�C������������
			fprintf(outf_e, "*** File not found!\n");
			continue;
		}

		// BIN�t�@�C�����e��ǂݏo���ăo�b�t�@�Ɋi�[
		file_size = (long)fread(binfilebuf.charbuf, 1, sizeof(binfilebuf), in_bin);
		if (file_size >= (long)sizeof(binfilebuf)) {
			fprintf(outf_e, "%s\n", maibuf);    // bin�t�@�C������������
			fprintf(outf_e, "*** Too big file size. ( >%d kB)\n", sizeof(binfilebuf));
		}

		// BIN�t�@�C���t�H�[�}�b�g�`�F�b�N
		if (i_EndianExchange(binfilebuf.binfile.header.year) != year) {
			fprintf(outf_e, "%s\n", maibuf);    // bin�t�@�C������������
			fprintf(outf_e, "*** File Format Error (YEAR) : %d\n" ,
			binfilebuf.binfile.header.year);
		}
		if (binfilebuf.binfile.header.AUTO_ZERO != 0) {    // AUTO_ZERO check
			fprintf(outf_e, "%s\n", maibuf);    // bin�t�@�C������������
			fprintf(outf_e, "  File Format Error (AUTO_ZERO) : %02X,%02X\n",
			binfilebuf.binfile.header.AUTO_ZERO&0xFF,
			binfilebuf.binfile.header.AUTO_ZERO>>8);
		}

		// �C�x���g���O��̃G���[�ʒu���A����BIN�t�@�C����̃f�[�^�ʒu�Ɋ��Z
		for (i = ErrPosIND; i < EventLogErrMax; i++) {
			if (SampleAtError[i] == 0xFFFFFFFF)break; // �ް����Ȃ���Δ�����
			ErrPos[i] = SampleAtError[i] - l_EndianExchange(binfilebuf.binfile.header.SAMPLE_INDEX);
		}
		ErrPos[i] = 0xFFFFFFFF;

		binfile_is_OK = 1;
		ErrorSample = 0;

		// BIN�t�@�C�����f�[�^��4byte���Ƀ`�F�b�N
		// �ŏ��́A���O��BIN�t�@�C�����ǂ̃f�[�^�ŏI����������l��
		datanum = ((file_size - sizeof(binfilebuf.binfile.header)) / 3);
		sampleNo = next_first_DACM_pos + l_EndianExchange(binfilebuf.binfile.header.SAMPLE_INDEX);
		for (i = next_first_DACM_pos; i < datanum; i += 4, sampleNo += 4) {

			// DACM�f�[�^�łȂ���΃G���[
			if (!is_DACMdata(binfilebuf.binfile.tempdata[i])) // Error
			{
				ERR++;
				binfile_is_OK = 0; // error ����
				// �Ō�ɃG���[��������SampleNo. ��ۊ�
				ErrorSample = i+l_EndianExchange(binfilebuf.binfile.header.SAMPLE_INDEX);
				fprintf(outf_e, "%s\n", maibuf);  // binFileName write
				if (is_DACMdata(binfilebuf.binfile.tempdata[i-3])) {
					inserted_data_num += 3;
					proc_for_4nPlusX_datalost(&datanum, outf_e, i, 3);    // 4N+3 datalost
				}
				else if (is_DACMdata(binfilebuf.binfile.tempdata[i-2])) {
					inserted_data_num += 2;
					proc_for_4nPlusX_datalost(&datanum, outf_e, i, 2);    // 4N+3 datalost
				}
				else if (is_DACMdata(binfilebuf.binfile.tempdata[i-1])) {
					inserted_data_num += 1;
					proc_for_4nPlusX_datalost(&datanum, outf_e, i, 1);    // 4N+3 datalost
				}
				else{
					inserted_data_num += 1;
					proc_for_DACM_datalost(&datanum, outf_e, i);    // DACM datalost
				}
			}
			// �C�x���g���O��̃G���[�����ʒu�{���e�덷���߂���
			//              if (i > ErrPos[ErrPosIND]+ tolerance) {
			if (SampleAtError[ErrPosIND] != 0xFFFFFFFF && sampleNo > SampleAtError[ErrPosIND] + tolerance) {
				// �����O�ɂ݂�����BIN�t�@�C���G���[��
				// �u�C�x���g���O�G���[�����ʒu�v-���e�덷���ȑO�ł������ꍇ
				//                  if (ErrorSample < SampleAtError[ErrPosIND]-tolerance) {
				if (ErrorSample < SampleAtError[ErrPosIND] - tolerance) {
					// 4n �f�[�^�����Ɣ��f���ď���
					ERR++;
					binfile_is_OK = 0; // error ����
					inserted_data_num += 4;
					proc_for_4nPlusX_datalost(&datanum, outf_e, i, 4);    // 4N datalost
				}
				ErrPosIND++;
			}
		}
		next_first_DACM_pos = i - datanum;
		this_inserted_data_num = next_inserted_data_num;
		next_inserted_data_num = inserted_data_num;

		if (!binfile_is_OK) { // �ُ킠��
			binfile_update_required = 1;  // �ȍ~��bin�t�@�C���C���v
		}
		fclose(in_bin);
		in_bin = NULL;
//			Edit2->Text = file_count;
//			Edit3->Text = ERR;
		///////////////////
		if (hokanOK_NG!=2/*NG�ȊO*/ && binfile_update_required/* bin�t�@�C���C���v*/) {
			FILE *fptr;
			char tempbuf[300], new_binFileName[300];
			long sample_index;

			hokanOK_NG = 1; // OK �⊮����

			/////////  bin�t�@�C���C��  /////////

			// SAMPLE_INDEX,DATA_SIZE���X�V
			binfilebuf.binfile.header.DATA_SIZE = l_EndianExchange((long)datanum);

			sample_index = l_EndianExchange(binfilebuf.binfile.header.SAMPLE_INDEX) + this_inserted_data_num;

			binfilebuf.binfile.header.SAMPLE_INDEX = l_EndianExchange(sample_index);

			// bin�t�@�C����������
			// .bak�t�@�C���쐬
			// �@���f�B���N�g���̃t�@�C���𒼐ڏ���������ꍇ
			if (add_extention_bak_to_FileName(binFileName_w_Path, outPath) == -1) {
				return false;
			}
			strcpy(new_binFileName, binFileName_w_Path);
			sprintf(&new_binFileName[strlen(new_binFileName)-13], "%09d.bin", sample_index);
			// �@���f�B���N�g���͂��̂܂܂Ƃ��āA�ʃf�B���N�g���Ƀt�@�C�����쐬����ꍇ
			//            strcpy(new_binFileName, outPath);
			//            strcat(new_binFileName, &maibuf[5]);
			////////////////
			sprintf(&new_binFileName[strlen(new_binFileName)-13], "%09d.bin", sample_index);
			if ((fptr = fopen(new_binFileName, "wb")) == NULL)
			{
				CGeneral::Alert("�o�̓t�@�C�����J���܂���") ;
				return false;
			}
			file_size = (long)fwrite(binfilebuf.charbuf, 1, sizeof(binfilebuf.binfile.header) + datanum * 3, fptr);

			fclose(fptr);

			/////////  mai�t�@�C���C��  /////////
//				sprintf(&maiFileBuf[strlen(maiFileBuf)-14], "%09d.bin\n", sample_index);
			sprintf(&maiFileBuf[mai_line_no-1][37], "%09d.bin\n", sample_index);
			if (fgets(tempbuf, 300, in) == NULL) {
				CGeneral::Alert("mai�t�@�C���t�H�[�}�b�g�G���[�P") ;
				return false;
			}
			if (strncmp(tempbuf, "Samples=", 8)) {
				CGeneral::Alert("mai�t�@�C���t�H�[�}�b�g�G���[�Q") ;
				return false;
			}
			sprintf(tempbuf, "Samples=%08d\n", datanum);
//xxxx			strcat(maiFileBuf, tempbuf);
			strcpy(maiFileBuf[mai_line_no++], tempbuf);
		}
		///////////////////
	}
	//        fclose(outf);
	fclose(in);    // mai�t�@�C���N���[�Y
	in = NULL;
	if (hokanOK_NG == 1/*OK*/ && binfile_update_required) {
		FILE *fptr;
		char New_maiFileName[300];

		// �@���f�B���N�g���̃t�@�C���𒼐ڏ���������ꍇ
		if (add_extention_bak_to_FileName(maiFileName_w_Path, outPath) == -1) {
			return false;
		}
		strcpy(New_maiFileName, maiFileName_w_Path);
		// �@���f�B���N�g���͂��̂܂܂Ƃ��āA�ʃf�B���N�g���Ƀt�@�C�����쐬����ꍇ
		//          strcpy(New_maiFileName, outPath);
		//          strcat(New_maiFileName,
		//                  &maiFileName_w_Path[strlen(maiFileName_w_Path)-48]);
		////////////////
		if ((fptr = fopen(New_maiFileName , "wt")) == NULL)
		{
			CGeneral::Alert("�o�̓t�@�C�����J���܂���") ;
			return false;
		}
		//          file_size = fwrite(maiFileBuf, 1, strlen(maiFileBuf), fptr);
		for (i = 0; i < mai_line_no; i++) {
			fputs(maiFileBuf[i], fptr);
		}
		fclose(fptr);
	}
	fprintf(outf_e, "\n");

	// �G���[���O�t�@�C�����o�b�N�A�b�v
	if (bLogFileBak) {
		char buf[1024];
		FILE *fpr, *fpw;
		int i, cnt, pos, length;
		bool bDelete;

		add_extention_bak_to_FileName(melPath + m_sMelFile, outPath);

		if ((fpr = fopen(outPath + m_sMelFile + ".bak", "rb")) != NULL) {
			if ((fpw = fopen(melPath + m_sMelFile, "wb")) != NULL) {
				cnt = 0;
				pos = 0;
				while (fread(buf, 26, 1, fpr) == 1) {
					char *e;
					int len, len2;
					buf[26] = 0;
					len = strtol(&buf[22], &e, 16);
					len2 = (1 + len + 1) / 2 * 2;
					length = 26 + len2;
					fread(buf + 26, len2, 1, fpr);    // .PARAM_X��raed�@�A������byte

					bDelete = false;
					for (i = 0; i < EventLogErrMax; i++) {
						if (EventErrTime[i].time_t == 0)
							break;

						if (EventErrTime[i].bDelete && EventErrTime[i].nPosition == pos) {
							bDelete = true;
							break;
						}
					}
					if (!bDelete)
						fwrite(buf, length, 1, fpw);

					pos++;
				}
				fclose(fpw);
			}
			fclose(fpr);
		}
	}

	return hokanOK_NG == 1;
}
