// MmeSendDlg.h : ヘッダー ファイル
//

#pragma once

#include "afxsock.h"

// CMmeSendDlg ダイアログ
class CMmeSendDlg : public CDialog
{
// コンストラクション
public:
	CMmeSendDlg(CWnd* pParent = NULL);	// 標準コンストラクタ

// ダイアログ データ
	enum { IDD = IDD_MMESEND_DIALOG };

	protected:
	virtual void DoDataExchange(CDataExchange* pDX);	// DDX/DDV サポート


// 実装
protected:
	HICON m_hIcon;
	CSocket m_oSock;
	CFile m_oFile;
	int m_nCounter;
	CString m_sEditSendFile;
	int m_iEditTimer;
	CEdit m_cEditCount;
	int m_iRadioPort;
	CString m_sEditIpAddress;
	CString m_sEditTelemetryPayloadId;
	CString m_sEditTelemetryDataId;
	CString m_sEditTelemetryFrameId;
	CString m_sEditTelemetryEndSegment;
	CString m_sEditTelemetrySegmentCount;
	CString m_sEditTelemetryTimestamp;
	CString m_sEditTelemetryFineTime;
	CString m_sEditTelemetryMeasurementMode;
	CString m_sEditTelemetryMeasurementRange;
	CString m_sEditTelemetrySamplingRate;
	CString m_sEditTelemetryDataNum;
	CString m_sEditTelemetryStatusWord;
	CString m_sEditStatusPayloadId;
	CString m_sEditStatusDataId;
	CString m_sEditStatusFrameId;
	CString m_sEditStatusEndSegment;
	CString m_sEditStatusSegmentCount;
	CString m_sEditStatusTimestamp;
	CString m_sEditStatusFineTime;
	CString m_sEditStatusDataNum;
	CString m_sEditStatusStatusWord;
	CString m_sEditStatusControlName;
	CString m_sEditStatusMeasureModeId;
	CString m_sEditStatusMeasureStatus;
	CString m_sEditStatusCurrentRangeId;
	CString m_sEditStatusDownlinkName;
	CString m_sEditStatusDownlinkData;
	CString m_sEditStatusTimerName;
	CString m_sEditStatusTimerData;
	CString m_sEditStatusStartName;
	CString m_sEditStatusStartData;
	CString m_sEditStatusEndName;
	CString m_sEditStatusEndData;
	CString m_sEditErrorPayloadId;
	CString m_sEditErrorDataId;
	CString m_sEditErrorFrameId;
	CString m_sEditErrorEndSegment;
	CString m_sEditErrorSegmentCount;
	CString m_sEditErrorTimestamp;
	CString m_sEditErrorFineTime;
	CString m_sEditErrorErrorCode;
	CString m_sEditErrorSubCode;
	CString m_sEditErrorDataNum;
	CString m_sEditErrorStatusWord;
	CString m_sEditErrorTaskName;
	CString m_sEditErrorMessage;
	CString m_sEditMessagePayloadId;
	CString m_sEditMessageDataId;
	CString m_sEditMessageFrameId;
	CString m_sEditMessageEndSegment;
	CString m_sEditMessageSegmentCount;
	CString m_sEditMessageTimestamp;
	CString m_sEditMessageFineTime;
	CString m_sEditMessageDataNum;
	CString m_sEditMessageStatusWord;
	CString m_sEditMessageMessage;

	STelemetry m_oTelemetry;
	SMmeStatus m_oMmeStatus;
	SErrorStatus m_oErrorStatus;
	SMessage m_oMessage;

	BOOL SendPacket();
	void DispData();
	void SetUserHeaderCommon(BYTE *&pBuf, const SUserHeaderCommon &oUserHeaderCommon);
	void SetHaltInfo(BYTE *&pBuf, const SLasttimeHalt &oLasttimeHalt);
	void SetAnalogData(BYTE *&pBuf, const SAnalogData &oAnalogData);
	void SetText(BYTE *&pBuf, const CString &sText, int nSize);
	void SetWord(BYTE *&pBuf, UINT nData);
	void SetDWord(BYTE *&pBuf, UINT nData);

	// 生成された、メッセージ割り当て関数
	DECLARE_MESSAGE_MAP()
	virtual BOOL OnInitDialog();
	afx_msg void OnSysCommand(UINT nID, LPARAM lParam);
	afx_msg void OnPaint();
	afx_msg HCURSOR OnQueryDragIcon();
	afx_msg void OnBnClickedButtonSendFile();
	afx_msg void OnBnClickedButtonReference();
	afx_msg void OnTimer(UINT_PTR nIDEvent);
	afx_msg void OnBnClickedButtonSendStop();
	afx_msg void OnBnClickedButtonMmeStatus();
	afx_msg void OnBnClickedButtonErrorStatus();
	afx_msg void OnBnClickedButtonMessage();
	afx_msg void OnBnClickedButtonTelemetry();
	afx_msg void OnBnClickedButtonTelemetrySend();
	afx_msg void OnBnClickedButtonStatusSend();
	afx_msg void OnBnClickedButtonErrorSend();
	afx_msg void OnBnClickedButtonMessageSend();
	afx_msg void OnBnClickedButtonTelemetryAnalogMonitor();
	afx_msg void OnBnClickedButtonTelemetryAccMonitor();
	afx_msg void OnBnClickedButtonStatusAnalogMonitor();
	afx_msg void OnBnClickedButtonStatusHaltError();
	afx_msg void OnBnClickedButtonMessageHaltError();
};
