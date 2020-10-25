// MmeSend.h : PROJECT_NAME アプリケーションのメイン ヘッダー ファイルです。
//

#pragma once

#ifndef __AFXWIN_H__
	#error "PCH に対してこのファイルをインクルードする前に 'stdafx.h' をインクルードしてください"
#endif

#include "resource.h"		// メイン シンボル

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

// ユーザヘッダ共通部
struct SUserHeaderCommon {
	UINT nPayloadId;
	UINT nDataId;
	UINT nFrameId;
	UINT nEndSegment;
	UINT nSegmentCount;
	UINT nTimestamp;
	UINT nFineTime;
};

// 前回HALT時エラー情報
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

// デジタルデータ
struct SDigitalData {
	int nSequrnceCount;
	struct {
		int nAcc[N_ACC];
	} aUnit[N_UNIT];
};

// アナログデータ
struct SAnalogData {
	struct {
		int nAcc[N_ACC];
		int nTemp[N_TEMP];
		int nHeater;
	} aUnit[N_UNIT];
};

// ステータスワード
struct SStatusWord {
	struct {
		UINT nOnOffStatus;
		UINT nRangeStatus;
	} aUnit[N_UNIT];
};

// テレメトリパケット
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

// MMEステータスパケット
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

// エラーステータスパケット
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

// メッセージパケット
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
// このクラスの実装については、MmeSend.cpp を参照してください。
//

class CMmeSendApp : public CWinApp
{
public:
	CMmeSendApp();

// オーバーライド
	public:
	virtual BOOL InitInstance();

// 実装

	DECLARE_MESSAGE_MAP()
};

extern CMmeSendApp theApp;

extern UINT GetInt(const CString &sData);
