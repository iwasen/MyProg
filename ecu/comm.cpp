// comm.cpp : �C���v�������e�[�V���� �t�@�C��
//

#include "stdafx.h"
#include "EcuRecv.h"
#include "comm.h"
#include <process.h>

// defile
#define BUFSIZE		1024	// ����M�o�b�t�@�T�C�Y

// �ʐM�R�[�h
#define CODE_STX	0x02
#define CODE_ETX	0x03
#define CODE_FF		0xff

// �����ԃ^�C���A�E�g����
#define TIMEOUT_CHAR	1

// �t���[���R�[�h
#define FRAME_RESPONSE	'R'
#define FRAME_COMMTEST	'T'
#define FRAME_TESTSTART	'S'
#define FRAME_HEADER	'H'
#define FRAME_DATA		'D'
#define FRAME_DATAEND	'F'
#define FRAME_TESTEND	'E'

// ���X�|���X�R�[�h
#define RESPONSE_NORMAL		'0'
#define RESPONSE_BCCERR		'1'
#define RESPONSE_NOTREADY	'2'
#define RESPONSE_SEQERR		'3'

// �f�[�^��M���[�h
#define MODE_STOP		0
#define MODE_START		1
#define MODE_HEADER		2
#define MODE_DATA		3

// �t���[����M�X�e�[�g
#define STATE_IDLE	0
#define STATE_DATA	1
#define STATE_BCC	2

/* �o�`�l�f�[�^�E�w�b�_�\���� */
struct PAM_HEADER {
	char	date[10];	/* �L�^�J�n���t */
	char	time[8];	/* �L�^�J�n���� */
	char	mode[2];	/* PAM����Ӱ�� */
	char	rec[4];		/* ں��ސ� */
	char	dummy[8];	/* �\�� */
};

/* �t�@�C���w�b�_�\���� */
struct FILE_HEADER {
	char seqNo[3];
	char state[1];
	struct PAM_HEADER pamHeader;
};

/* �o�`�l�f�[�^�E���R�[�h���e */
struct PAM_DATA {
	BYTE	clk;		/* 55msec���� */
	BYTE	CmdHit;		/* ����ޑō��׸� */
    BYTE	sds;        /* ����ޯ� */
	BYTE	dummy1[5];	/* ��а */
	struct SLM_DATA {
		BYTE	ls;			/* �ЯĽ����ð�� */
		BYTE	pwm;		/* �w��PWM�l */
		WORD	rad;		/* ��]�p */
		short   vr;			/* �����x */	
		WORD	vo;			/* �w�����x */
		WORD	bibun;		/* �����l */
		WORD	sekibun;	/* �ϕ��l */
		BYTE	elec;		/* ���d�� */
		BYTE	sdj;     	/* ����ޯ���Խð�� */
	} slmdat[4];
};

// �����X�^�e�B�b�N�ϐ�
static int mode;
static HANDLE handle;
static HANDLE hThread;
static CString sSaveFolder;
static CFile file;
static CWnd *pDlgWnd;

// �����֐��v���g�^�C�v
static BOOL OpenComm();
static void ControlComm(LPVOID param);
static void ReceiveHeader(FILE_HEADER *pFileHeader);
static void ReceiveData(PAM_DATA *pPamData);
static void EndData();
static PBYTE SetFrame(PBYTE pBuf, BYTE c);
static BOOL PutComm(PBYTE pBuf, int len);
static int GetFrame(PBYTE pData);
static int GetComm();
static void SendResponse(int response);
static void SendNotify(WPARAM wParam, LPARAM lParam);

//===================================================================
//
//	�@�\�@�F�ʐM�n���h���N������
//
//	�����@�F����
//
//	�߂�l�FTRUE  ����
//			FALSE �N�����s
//
//===================================================================
BOOL StartCommHandler()
{
	// �V���A���|�[�g�I�[�v��
	if (!OpenComm()) {
		AfxMessageBox("�V���A���|�[�g(COM1)�̃I�[�v���Ɏ��s���܂����B");
		return FALSE;
	}

	// �V���A���|�[�g����X���b�h�쐬
	if ((hThread = (HANDLE)_beginthread(ControlComm, 0, NULL)) == (HANDLE)-1) {
		AfxMessageBox("�V���A���|�[�g����X���b�h�̍쐬�Ɏ��s���܂����B");
		return FALSE;
	}

	return TRUE;
}

//===================================================================
//
//	�@�\�@�F�ʐM�J�n����
//
//	�����@�FpWnd - ���b�Z�[�W�ʒm�E�B���h�E
//
//	�߂�l�FTRUE ���݂���, FALSE ���݂��Ȃ�
//
//===================================================================
void StartTest(CWnd *pWnd, LPCTSTR pSaveFolder)
{
	// �ʒm�E�B���h�E�ۑ�
	pDlgWnd = pWnd;

	// �t�H���_���ۑ�
	sSaveFolder = pSaveFolder;

	// �������[�h���J�n��ԂփZ�b�g
	mode = MODE_START;

	// ��M��ԕ\��
	SendNotify(NOTIFY_RECVSTATUS, (LPARAM)"�f�[�^��M�҂�");
}

//===================================================================
//
//	�@�\�@�F�ʐM�I������
//
//	�����@�F����
//
//	�߂�l�F����
//
//===================================================================
void StopTest()
{
	// �������[�h���~��ԂփZ�b�g
	mode = MODE_STOP;

	// �ʒm�E�B���h�E���Z�b�g
	pDlgWnd = NULL;
}

//===================================================================
//
//	�@�\�@�F�V���A���|�[�g�I�[�v������
//
//	�����@�F����
//
//	�߂�l�FTRUE ����, FALSE �I�[�v�����s
//
//===================================================================
static BOOL OpenComm()
{
	DCB	dcb;
	COMMTIMEOUTS	ctmo;

	// �I�[�v���ς݂Ȃ牽�����Ȃ�
	if (handle != NULL)
		return TRUE;

	// �V���A���|�[�g�I�[�v��
	if ((handle = CreateFile("COM1", GENERIC_READ | GENERIC_WRITE, 0,
			NULL, OPEN_EXISTING, 0, NULL))
			== INVALID_HANDLE_VALUE) {
		handle = NULL;
		return FALSE;
	}

	// ����M�o�b�t�@�T�C�Y�ݒ�
	if (!SetupComm(handle, 1024, 1024)) {
		CloseHandle(handle);
		handle = NULL;
		return FALSE;
	}

	// �ʐM�����ݒ�
	memset(&dcb, 0, sizeof(dcb));
	dcb.DCBlength = sizeof(DCB);
	dcb.BaudRate = CBR_9600;
	dcb.fBinary = TRUE;
//	dcb.fOutxCtsFlow = TRUE;
	dcb.fDtrControl = DTR_CONTROL_ENABLE;
	dcb.fRtsControl = RTS_CONTROL_ENABLE;
	dcb.ByteSize = 8;
	dcb.Parity = NOPARITY;
	dcb.StopBits = ONESTOPBIT;
	if (!SetCommState(handle, &dcb)) {
		CloseHandle(handle);
		handle = NULL;
		return FALSE;
	}

	// �^�C���A�E�g���Ԑݒ�
	memset(&ctmo, 0, sizeof(ctmo));
	ctmo.ReadTotalTimeoutConstant = TIMEOUT_CHAR * 1000;
	ctmo.WriteTotalTimeoutConstant = TIMEOUT_CHAR * 1000;
	if (!SetCommTimeouts(handle, &ctmo)) {
		CloseHandle(handle);
		handle = NULL;
		return FALSE;
	}

	return TRUE;
}

//===================================================================
//
//	�@�\�@�F�ʐM���䏈��
//
//	�����@�Fparam - �X���b�h�p�����[�^�i�s�g�p�j
//
//	�߂�l�F����
//
//===================================================================
static void ControlComm(LPVOID param)
{
	BYTE recvBuf[BUFSIZE];
	int len;

	for (;;) {
		// �P�t���[����M
		len = GetFrame(recvBuf);
		if (len == 0)
			continue;

		// �t���[���R�[�h�ɂ��U�蕪��
		switch (recvBuf[0]) {
		case FRAME_COMMTEST:	// �ʐM�e�X�g�t���[��
			// Normal �𑗐M
			SendResponse(RESPONSE_NORMAL);
			break;
		case FRAME_TESTSTART:	// �����J�n�t���[��
			// �������[�h�ɂ��U�蕪��
			switch (mode) {
			case MODE_START:	// �����J�n�t���[���҂�
				// �����񐔂�\��
				recvBuf[4] = '\0';
				SendNotify(NOTIFY_TExxxOUNT, (LPARAM)(recvBuf + 1));

				// �����J�n�t���[������M������ NORMAL ��Ԃ�
				SendResponse(RESPONSE_NORMAL);
				mode = MODE_HEADER;
				break;
			case MODE_STOP:
				// �����J�n���Ă��Ȃ������� Not Ready ��Ԃ�
				SendResponse(RESPONSE_NOTREADY);
				break;
			default:
				// ����ȊO�̓V�[�P���X�G���[
				SendResponse(RESPONSE_SEQERR);
				break;
			}
			break;
		case FRAME_HEADER:		// �w�b�_�t���[��
			// �������[�h�ɂ��U�蕪��
			switch (mode) {
			case MODE_HEADER:
				// �w�b�_��M����
				SendNotify(NOTIFY_RECVSTATUS, (LPARAM)"�f�[�^��M��");
				ReceiveHeader((FILE_HEADER *)(recvBuf + 1));
				SendResponse(RESPONSE_NORMAL);
				mode = MODE_DATA;
				break;
			case MODE_STOP:
				// �����J�n���Ă��Ȃ������� Not Ready ��Ԃ�
				SendResponse(RESPONSE_NOTREADY);
				break;
			default:
				// ����ȊO�̓V�[�P���X�G���[
				SendResponse(RESPONSE_SEQERR);
				break;
			}
			break;
		case FRAME_DATA:	// �f�[�^�t���[��
			// �������[�h�ɂ��U�蕪��
			switch (mode) {
			case MODE_DATA:
				// �f�[�^��M����
				ReceiveData((PAM_DATA *)(recvBuf + 1));
				SendResponse(RESPONSE_NORMAL);
				break;
			case MODE_STOP:
				// �����J�n���Ă��Ȃ������� Not Ready ��Ԃ�
				SendResponse(RESPONSE_NOTREADY);
				break;
			default:
				// ����ȊO�̓V�[�P���X�G���[
				SendResponse(RESPONSE_SEQERR);
				break;
			}
			break;
		case FRAME_DATAEND:	// ���R�[�h�I���t���[��
			// �������[�h�ɂ��U�蕪��
			switch (mode) {
			case MODE_DATA:
				// �f�[�^��M�I������
				SendNotify(NOTIFY_RECVSTATUS, (LPARAM)"�f�[�^��M�҂�");
				EndData();
				SendResponse(RESPONSE_NORMAL);
				mode = MODE_HEADER;
				break;
			case MODE_STOP:
				// �����J�n���Ă��Ȃ������� Not Ready ��Ԃ�
				SendResponse(RESPONSE_NOTREADY);
				break;
			default:
				// ����ȊO�̓V�[�P���X�G���[
				SendResponse(RESPONSE_SEQERR);
				break;
			}
			break;
		case FRAME_TESTEND:	// �����I���t���[��
			// �t�@�C�����I�[�v�����Ȃ�N���[�Y����
			if (file.m_hFile != CFile::hFileNull)
				file.Close();

			// Normal �𑗐M
			SendResponse(RESPONSE_NORMAL);

			// �������[�h���~��Ԃɐݒ�
			mode = MODE_STOP;

			// �ʐM�I�����b�Z�[�W�\��
			SendNotify(NOTIFY_RECVSTATUS, (LPARAM)"�ʐM�I��");

			// �_�C�A���O�Ɏ����I���𑗐M
			SendNotify(NOTIFY_TESTEND, 0);
			break;
		}
	}
}

//===================================================================
//
//	�@�\�@�F�w�b�_�t���[����M����
//
//	�����@�FpFileHeader - �w�b�_�f�[�^
//
//	�߂�l�F����
//
//===================================================================
static void ReceiveHeader(FILE_HEADER *pFileHeader)
{
	char fileName[6];

	// �t�@�C�����I�[�v�����Ȃ�N���[�Y
	if (file.m_hFile != CFile::hFileNull)
		file.Close();

	// �ۑ��t�@�C������ҏW
	memcpy(fileName, pFileHeader->seqNo, 3);
	fileName[3] = '-';
	fileName[4] = pFileHeader->state[0];
	fileName[5] = '\0';

	// �ۑ��t�@�C�����I�[�v��
	file.Open(sSaveFolder + "\\" + fileName + ".DAT", CFile::modeCreate | CFile::modeWrite | CFile::shareExclusive);

	// �t�@�C���Ƀw�b�_����������
	file.Write(&pFileHeader->pamHeader, sizeof(PAM_HEADER));

	// �_�C�A���O�Ƀf�[�^��M�𑗐M
	SendNotify(NOTIFY_RECVCOUNT, (LPARAM)fileName);
}

//===================================================================
//
//	�@�\�@�FPAM�f�[�^�t���[����M����
//
//	�����@�FpPamData - PAM��M�f�[�^
//
//	�߂�l�F����
//
//===================================================================
static void ReceiveData(PAM_DATA *pPamData)
{
	// �t�@�C�����I�[�v������Ă��Ȃ���Ή������Ȃ�
	if (file.m_hFile == CFile::hFileNull)
		return;

	// �t�@�C���Ƀf�[�^����������
	file.Write(pPamData, sizeof(PAM_DATA));
}

//===================================================================
//
//	�@�\�@�F�f�[�^�I���t���[����M����
//
//	�����@�F����
//
//	�߂�l�F����
//
//===================================================================
static void EndData()
{
	// �t�@�C�����I�[�v������Ă��Ȃ���Ή������Ȃ�
	if (file.m_hFile == CFile::hFileNull)
		return;

	// �t�@�C���Ƀf�[�^����������
	file.Close();
}

//===================================================================
//
//	�@�\�@�F�t���[�����M����
//
//	�����@�FpData - ���M�f�[�^
//			len - ���M�f�[�^��
//
//	�߂�l�FTRUE - ����
//			FALSE - ���M���s
//
//===================================================================
static BOOL PutFrame(PBYTE pData, int len)
{
	BYTE sendBuf[BUFSIZE];
	PBYTE pBuf;
	BYTE bcc;
	int i;

	// ���M�o�b�t�@��STX,�f�[�^,ETX���Z�b�g
	pBuf = sendBuf;
	*pBuf++ = CODE_STX;
	bcc = CODE_STX + CODE_ETX;
	for (i = 0; i < len; i++) {
		bcc += *pData;
		pBuf = SetFrame(pBuf, *pData++);
	}
	*pBuf++ = CODE_ETX;

	// �`�F�b�N�T�����Z�b�g
	*pBuf++ = bcc;

	// �f�[�^���M
	return PutComm(sendBuf, pBuf - sendBuf);
}

//===================================================================
//
//	�@�\�@�F���M�t���[���Z�b�g
//
//	�����@�FpBuf - ���M�o�b�t�@
//			c - ���M�f�[�^
//
//	�߂�l�F���M�o�b�t�@�̎��̃A�h���X
//
//===================================================================
static PBYTE SetFrame(PBYTE pBuf, BYTE c)
{
	// STX,ETX,0xff �������� 0xff���Z�b�g���Ĕ��]�f�[�^���Z�b�g
	if (c == CODE_STX || c == CODE_ETX || c == CODE_FF) {
		*pBuf++ = CODE_FF;
		*pBuf++ = ~c;
	} else
		*pBuf++ = c;

	return pBuf;
}

//===================================================================
//
//	�@�\�@�F�f�[�^���M����
//
//	�����@�FpBuf - ���M�f�[�^
//
//	�߂�l�FTRUE - ����
//			FALSE - ���M���s
//
//===================================================================
static BOOL PutComm(PBYTE pBuf, int len)
{
	DWORD numBytes;

	// �V���A���|�[�g�ɏ�������
	WriteFile(handle, pBuf, len, &numBytes, NULL);

	// �S�f�[�^�������߂��琳��
	return (numBytes == (DWORD)len) ? TRUE : FALSE;
}

//===================================================================
//
//	�@�\�@�F�t���[����M����
//
//	�����@�FpData - ��M�t���[���o�b�t�@
//
//	�߂�l�F��M�o�C�g��
//
//===================================================================
static int GetFrame(PBYTE pData)
{
	PBYTE pBuf;
	int c;
	int timeout;
	int state;
	int count;
	BYTE bcc;
	BOOL bCodeFF;
	BOOL bLoopSw;

	// �ϐ�������
	timeout = 0;
	bCodeFF = FALSE;
	state = STATE_IDLE;

	// �P�t���[������M����܂Ń��[�v
	bLoopSw = TRUE;
	while (bLoopSw) {
		// �P�o�C�g��M
		c = GetComm();

		// -1 ��������P�b�̃^�C���A�E�g
		if (c != -1) {
			// ��M���(state)�ɂ�鏈���U�蕪��
			switch (state) {
			case STATE_IDLE:		// �A�C�h�����
				// STX ����M������f�[�^��M��ԂɈڍs
				if (c == CODE_STX) {
					pBuf = pData;
					count = 0;
					bcc = CODE_STX;
					bCodeFF = FALSE;
					state = STATE_DATA;
				}
				break;
			case STATE_DATA:		// �f�[�^��M���
				// ETX ����M����܂Ŏ�M�f�[�^���o�b�t�@�ɕۑ�
				if (c == CODE_ETX)
					state = STATE_BCC;
				else {
					// 0xff ����M�����玟�̕����𔽓]����
					if (bCodeFF) {
						c ^= 0xff;
						bCodeFF = FALSE;
					} else {
						if (c == CODE_FF) {
							bCodeFF = TRUE;
							break;
						}
					}

					// �f�[�^�T�C�Y�`�F�b�N
					// �o�b�t�@�T�C�Y���z�����ꍇ�̓S�~����M�����Ƃ݂Ȃ��Ď̂Ă�
					if (++count == BUFSIZE)
						state = STATE_IDLE;
					else
						*pBuf++ = c;
				}

				// �`�F�b�N�T���̌v�Z
				bcc += c;
				break;
			case STATE_BCC:			// BCC ��M�҂����
				// �`�F�b�N�T�����`�F�b�N�B��v���Ȃ��ꍇ�� NAK �𑗐M
				if (bcc != c) {
					SendResponse(RESPONSE_BCCERR);
					state = STATE_IDLE;
				} else
					bLoopSw = FALSE;
				break;
			}
				
			timeout = 0;
		} else {
			// �K�莞�ԃf�[�^�����Ȃ������珉����Ԃɖ߂�
			state = STATE_IDLE;
		}
	}

	return pBuf - pData;
}

//===================================================================
//
//	�@�\�@�F�P�o�C�g��M����
//
//	�����@�F����
//
//	�߂�l�F��M�f�[�^�i�P�b�^�C���A�E�g������ -1�j
//
//===================================================================
static int GetComm()
{
	int	c;
	DWORD	numBytes;

	c = 0;
	ReadFile(handle, &c, 1, &numBytes, NULL);
	if (numBytes == 0)
		c = -1;

	return c;
}

//===================================================================
//
//	�@�\�@�F���X�|���X���M����
//
//	�����@�Fresponse - ���M���X�|���X
//
//	�߂�l�F����
//
//===================================================================
static void SendResponse(int response)
{
	BYTE sendBuf[2];

	// ���X�|���X��ҏW
	sendBuf[0] = FRAME_RESPONSE;
	sendBuf[1] = (BYTE)response;

	// ���M
	PutFrame(sendBuf, sizeof(sendBuf));
}

//===================================================================
//
//	�@�\�@�F�_�C�A���O�Ƀ��b�Z�[�W�ʒm����
//
//	�����@�FwParam - ���b�Z�[�W�p�����[�^�P
//			lParam - ���b�Z�[�W�p�����[�^�Q
//
//	�߂�l�F����
//
//===================================================================
static void SendNotify(WPARAM wParam, LPARAM lParam)
{
	if (pDlgWnd != NULL)
		pDlgWnd->SendMessage(WM_RECV_NOTIFY, wParam, lParam);
}
