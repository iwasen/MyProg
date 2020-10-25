// MmeSend.h : PROJECT_NAME �A�v���P�[�V�����̃��C�� �w�b�_�[ �t�@�C���ł��B
//

#pragma once

#ifndef __AFXWIN_H__
	#error "PCH �ɑ΂��Ă��̃t�@�C�����C���N���[�h����O�� 'stdafx.h' ���C���N���[�h���Ă�������"
#endif

#include "resource.h"		// ���C�� �V���{��

#define N_UNIT	3
#define UNIT_1	0
#define UNIT_2	1
#define UNIT_3	2

#define N_RANGE		3
#define RANGE_LO	0
#define RANGE_HI	1
#define RANGE_EX	2

#define N_ACC	3
#define ACC_X	0
#define ACC_Y	1
#define ACC_Z	2

#define N_TEMP	4
#define TEMP_X	0
#define TEMP_Y	1
#define TEMP_Z	2
#define TEMP_SB	3

#define USER_HEADER_SIZE	16
#define ANALOG_DATA_SIZE	48
#define DIGITAL_DATA_SIZE	20

// ���[�U�w�b�_���ʕ�
struct SUserHeaderCommon {
	UINT nPayloadId;
	UINT nDataId;
	UINT nFrameId;
	UINT nEndSegment;
	UINT nSegmentCount;
	UINT nTimestamp;
	UINT nFineTime;
};

// �O��HALT���G���[���
struct SLasttimeHalt {
	struct {
		UINT nSessionNumber;
		UINT nType;
		UINT nNature;
	} oBitStatus;
	struct {
		CString sTaskName;
		UINT nErrorCode;
		UINT nErrorTime;
	} oHaltInfo;
};

// �f�W�^���f�[�^
struct SDigitalData {
	int nSequrnceCount;
	struct {
		int nAcc[N_ACC];
	} aUnit[N_UNIT];
};

// �A�i���O�f�[�^
struct SAnalogData {
	struct {
		int nAcc[N_ACC];
		int nTemp[N_TEMP];
		int nHeater;
	} aUnit[N_UNIT];
};

// �X�e�[�^�X���[�h
struct SStatusWord {
	struct {
		UINT nOnOffStatus;
		UINT nRangeStatus;
	} aUnit[N_UNIT];
};

// �e�����g���p�P�b�g
struct STelemetry {
	struct SHeader {
		SUserHeaderCommon oCommon;
		UINT nMeasurementMode;
		UINT nMeasurementRange;
		UINT nSamplingRate;
		UINT nDataNum;
		UINT nStatusWord;
	} oHeader;
	SAnalogData oAnalogData;
	SDigitalData oDigitalData;
};

// MME�X�e�[�^�X�p�P�b�g
struct SMmeStatus {
	struct SHeader {
		SUserHeaderCommon oCommon;
		UINT nDataNum;
		UINT nStatusWord;
	} oHeader;
	struct SData {
		CString sContrxxxodeTableName;
		UINT nMeasureModeId;
		UINT nMeasureStatus;
		UINT nCurrentRangeId;
		CString sDownlinkPathName;
		UINT nDownlinkPathData;
		CString sTimerCountName;
		UINT nTimerCountData;
		CString sMeasStartTimeName;
		UINT nMeasStartTimeData;
		CString sMeasEndTimeName;
		UINT nMeasEndTimeData;
		SAnalogData oAnalogData;
		SLasttimeHalt oLasttimeHalt;
	} oData;
};

// �G���[�X�e�[�^�X�p�P�b�g
struct SErrorStatus {
	struct SHeader {
		SUserHeaderCommon oCommon;
		UINT nErrorCode;
		UINT nSubCode;
		UINT nDataNum;
		UINT nStatusWord;
	} oHeader;
	struct SData {
		CString sTaskName;
		CString sErrorMessage;
	} oData;
};

// ���b�Z�[�W�p�P�b�g
struct SMessage {
	struct SHeader {
		SUserHeaderCommon oCommon;
		UINT nDataNum;
		UINT nStatusWord;
	} oHeader;
	struct SData {
		CString sMessage;
		SLasttimeHalt oLasttimeHalt;
	} oData;
};

// CMmeSendApp:
// ���̃N���X�̎����ɂ��ẮAMmeSend.cpp ���Q�Ƃ��Ă��������B
//

class CMmeSendApp : public CWinApp
{
public:
	CMmeSendApp();

// �I�[�o�[���C�h
	public:
	virtual BOOL InitInstance();

// ����

	DECLARE_MESSAGE_MAP()
};

extern CMmeSendApp theApp;

extern UINT GetInt(const CString &sData);
