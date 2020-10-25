#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		MmeRecvDlg.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		メインダイアログクラスの定義
//----------------------------------------------------------------------------------------------------
//  3. 備考
//		無し
//*****************************************************************************************************

#include "MyCtrl.h"
#include "MmeData.h"
#include "GraphWnd.h"
#include "GraphData.h"
#include <winsock2.h>

// CMmeRecvDlg ダイアログ
class CMmeRecvDlg : public CDialog
{
	struct SSettings {
		struct {
			int nUdpPort;
			BOOL bAutoFileName;
			BOOL bPysicalQuantity;
			BOOL bAccumulate;
		} oNasa;
		struct {
			int nUdpPort;
			BOOL bAutoFileName;
			BOOL bPysicalQuantity;
			BOOL bAccumulate;
		} oHcor;
		struct {
			int nTimeSpan;
			double fTempRange1;
			double fTempRange2;
			double fVoltageRange1;
			double fVoltageRange2;
			SGraphLine oGraphLine;
		} oGraph;
		struct {
			BOOL bEnableTempChk;
			BOOL bEnableVoltageChk;
			double fNormalTempRange1[N_UNIT][N_TEMP];
			double fNormalTempRange2[N_UNIT][N_TEMP];
			double fNormalVoltageRange1[N_UNIT];
			double fNormalVoltageRange2[N_UNIT];
		} oMonitoring;
	};

	struct SAccumulate {
		BYTE aUserHeader[USER_HEADER_SIZE];
		BYTE aAnalogData[ANALOG_DATA_SIZE];
		WORD wAccCount;
		int nAcc[N_UNIT][N_ACC];
	};

	struct SMonitoringCounter {
		int aTempCount[N_UNIT][N_TEMP];
		int aVoltageCount[N_UNIT];
	};

// コンストラクション
public:
	CMmeRecvDlg(CWnd* pParent = NULL);	// 標準コンストラクタ
	~CMmeRecvDlg();

// ダイアログ データ
	enum { IDD = IDD_MMERECV_DIALOG };

	protected:
	virtual void DoDataExchange(CDataExchange* pDX);	// DDX/DDV サポート

// 実装
protected:
	HICON m_hIcon;

	CStaticEx m_cStaticReceive;
	CButtonEx m_cButtonReceive;
	CEditEx m_cEditNasaPort;
	CEditEx m_cEditNasaFileName;
	CButtonEx m_cCheckNasaAutoFileName;
	CButtonEx m_cRadioNasaSampleRate;
	CButtonEx m_cRadioNasaAccumulate;
	CEditEx m_cEditHcorPort;
	CEditEx m_cEditHcorFileName;
	CButtonEx m_cCheckHcorAutoFileName;
	CButtonEx m_cRadioHcorSampleRate;
	CButtonEx m_cRadioHcorAccumulate;
	CEditEx m_cEditGraphTimeSpan;
	CEditEx m_cEditGraphTempRange1;
	CEditEx m_cEditGraphTempRange2;
	CEditEx m_cEditGraphVoltageRange1;
	CEditEx m_cEditGraphVoltageRange2;
	CButtonEx m_cCheckGraph[N_UNIT][N_GRAPH];
	CStaticEx m_cStaticGraph[N_UNIT][N_GRAPH];
	CStaticEx m_cStaticHealthStatus;
	CEditEx m_cEditAlxxxTime;
	CButtonEx m_cButtonStopAlxxx;
	CButtonEx m_cButtonClearStatus;
	CEditEx m_cEditHealthStatus;
	CButtonEx m_cCheckEnableTempChk;
	CButtonEx m_cCheckEnableVoltageChk;
	CEditEx m_cEditNasaUserHeaderText;
	CStaticEx m_cStaticNasaAcc[N_ACC];
	CEditEx m_cEditNasaDigitalAcc[N_UNIT][N_ACC];
	CStaticEx m_cStaticNasaAnalog[N_TEMP];
	CStaticEx m_cStaticNasaAnalogHeater;
	CEditEx m_cEditNasaAnalogAcc[N_UNIT][N_ACC];
	CEditEx m_cEditNasaAnalogTemp[N_UNIT][N_TEMP];
	CEditEx m_cEditNasaAnalogHeater[N_UNIT];
	CStaticEx m_cStaticNasaReceiveStatus;
	CEditEx m_cEditNasaReceiveTime;
	CEditEx m_cEditNasaSequenceCount;
	CGraphWnd m_cStaticNasaGraph;
	CButtonEx m_cButtonHcorGraph;
	CButtonEx m_cButtonNasaGraph;
	CListBox m_cListNasaStatusHistory;
	CEditEx m_cEditNasaStatusData;
	CEditEx m_cEditHcorUserHeaderText;
	CStaticEx m_cStaticHcorAcc[N_ACC];
	CEditEx m_cEditHcorDigitalAcc[N_UNIT][N_ACC];
	CStaticEx m_cStaticHcorAnalog[N_TEMP];
	CStaticEx m_cStaticHcorAnalogHeater;
	CEditEx m_cEditHcorAnalogAcc[N_UNIT][N_ACC];
	CEditEx m_cEditHcorAnalogTemp[N_UNIT][N_TEMP];
	CEditEx m_cEditHcorAnalogHeater[N_UNIT];
	CStaticEx m_cStaticHcorReceiveStatus;
	CEditEx m_cEditHcorReceiveTime;
	CEditEx m_cEditHcorSequenceCount;
	CGraphWnd m_cStaticHcorGraph;
	CListBox m_cListHcorStatusHistory;
	CEditEx m_cEditHcorStatusData;

	SSettings m_oSettings;
	SOCKET m_sockNasa;
	SOCKET m_sockHcor;
	CFont m_font2;
	BOOL m_bRunning;
	int m_nNasaReceiveIdleCounter;
	int m_nNasaDigitalIdleCounter;
	int m_nNasaAnalogIdleCounter;
	int m_nHcorReceiveIdleCounter;
	int m_nHcorDigitalIdleCounter;
	int m_nHcorAnalogIdleCounter;
	BOOL m_bNasaReceiveStatus;
	BOOL m_bNasaEnableDigitalData;
	BOOL m_bNasaEnableAnalogData;
	BOOL m_bHcorReceiveStatus;
	BOOL m_bHcorEnableDigitalData;
	BOOL m_bHcorEnableAnalogData;
	CButtonEx m_cCheckNasaPQ;
	CButtonEx m_cCheckHcorPQ;
	BOOL m_bValidNasaDigitalData;
	BOOL m_bValidNasaAnalogData;
	BOOL m_bValidHcorDigitalData;
	BOOL m_bValidHcorAnalogData;
	SDigitalData m_oNasaLastDigitalData;
	SAnalogData m_oNasaLastAnalogData;
	SDigitalData m_oHcorLastDigitalData;
	SAnalogData m_oHcorLastAnalogData;
	SGraphData *m_pNasaGraphData;
	SGraphData *m_pHcorGraphData;
	CGraphData m_oNasaGraphData;
	CGraphData m_oHcorGraphData;
	BOOL bAlertOn;
	SAccumulate m_oNasaAccumulate;
	SAccumulate m_oHcorAccumulate;
	SMonitoringCounter m_oNasaMonitoringCounter;
	SMonitoringCounter m_oHcorMonitoringCounter;

	BOOL InitSocket();
	void StartReceive();
	void StopReceive();
	int ReceivePacket(SOCKET sock, BYTE *pPacket);
	void AccumulateAcc(BYTE *pPacketBuf, const SUserHeader &oUserHeader, SAccumulate &oAccumulate, int &nPacketLen);
	void DispNasaReceiveInfo();
	void DispNasaUserHeader(const SUserHeader &oUserHeader);
	void DispNasaDigitalData(const SDigitalData &oDitalData);
	void DispNasaAnalogData(const SAnalogData &oAnalogData);
	void DispNasaMmeStatusData(const SUserHeader &oUserHeader, const SMmeStatusData &oMmeStatusData);
	void DispNasaErrorStatusData(const SUserHeader &oUserHeader, const SErrorStatusData &oErrorStatusData);
	void DispNasaMessageData(const SUserHeader &oUserHeader, const SMessageData &oMessageData);
	void AddNasaStatusList(LPCTSTR pPacketName, LPCTSTR pDataText);
	void ClearNasaStatusList();
	void DispHcorReceiveInfo();
	void DispHcorUserHeader(const SUserHeader &oUserHeader);
	void DispHcorDigitalData(const SDigitalData &oDitalData);
	void DispHcorAnalogData(const SAnalogData &oAnalogData);
	void DispHcorMmeStatusData(const SUserHeader &oUserHeader, const SMmeStatusData &oMmeStatusData);
	void DispHcorErrorStatusData(const SUserHeader &oUserHeader, const SErrorStatusData &oErrorStatusData);
	void DispHcorMessageData(const SUserHeader &oUserHeader, const SMessageData &oMessageData);
	void AddHcorStatusList(LPCTSTR pPacketName, LPCTSTR pDataText);
	void ClearHcorStatusList();
	void SetHealthStatus(LPCTSTR pPacketData);
	void ClearHealthStatus();
	void StopAlxxx();
	void ReadSettings();
	void WriteSettings();
	void SetNasaPysicalQuantity();
	void SetHcorPysicalQuantity();
	CString GetFrameIdText(UINT nFrameId);
	CString GetMeasurementModeText(UINT nMeasurentMode);
	CString GetMeasurementRangeText(UINT nMeasurementRange);
	CString GetMeasurementRangeText2(UINT nMeasurementRange);
	CString GetStatusWordText(const SStatusWord &oStatusWord);
	CString GetUserHeaderCommonText(const SUserHeader &oUserHeader);
	CString GetLasttimeHaltText(const SLasttimeHalt &oLasttimeHalt);
	CString GetMeasureModeIdText(UINT nMeasureModeId);
	CString GetMeasureStatusText(UINT nMeasureStatus);
	CString GetCurrentRangeIdText(UINT nCurrentRangeId);
	CString GetBitStatusTypeText(UINT nBitStatusType);
	CString GetBitStatusNatureText(UINT nBitStatusNature);
	CString GetHaltInfoErrorCodeText(UINT nHaltInfoErrorCode);
	CString GetErrorCodeText(UINT nErrorCode);
	CString GetSubCodeText(UINT nErrorCode, UINT nSubCode);
	void GetUserHeaderText(const SUserHeader &oUserHeader, CString &sText);
	void GetMmeStatusDataText(const SUserHeader &oUserHeader, const SMmeStatusData &oMmeStatusData, CString &sText);
	void GetErrorStatusDataText(const SUserHeader &oUserHeader, const SErrorStatusData &oErrorStatusData, CString &sText);
	void GetMessageDataText(const SUserHeader &oUserHeader, const SMessageData &oMessageData, CString &sText);
	void DispNasaGraph();
	void DispHcorGraph();
	void Monitoring(const CTime &oTimestamp, const SAnalogData &oAnalogData, const SStatusWord &oStatusWord, SMonitoringCounter &oMonitoringCounter, LPCTSTR pKeiro);
	void SetNasaReceiveStatus(BOOL bReceiveStatus, BOOL bForce = FALSE);
	void SetNasaEnableDigitalData(BOOL bEnable);
	void SetNasaEnableAnalogData(BOOL bEnable);
	void SetHcorReceiveStatus(BOOL bReceiveStatus, BOOL bForce = FALSE);
	void SetHcorEnableDigitalData(BOOL bEnable);
	void SetHcorEnableAnalogData(BOOL bEnable);
	void CloseDialog();

	// 生成された、メッセージ割り当て関数
	DECLARE_MESSAGE_MAP()
	virtual BOOL OnInitDialog();
	afx_msg void OnSysCommand(UINT nID, LPARAM lParam);
	afx_msg void OnPaint();
	afx_msg HCURSOR OnQueryDragIcon();
	afx_msg LRESULT OnSocketNasa(WPARAM wParam, LPARAM lParam);
	afx_msg LRESULT OnSocketHcor(WPARAM wParam, LPARAM lParam);
	afx_msg void OnDestroy();
	afx_msg void OnLbnSelchangeListNasaStatusHistory();
	afx_msg void OnLbnSelchangeListHcorStatusHistory();
	afx_msg void OnBnClickedButtonStopAlxxx();
	afx_msg void OnBnClickedButtonClearStatus();
	afx_msg void OnBnClickedButtonReceive();
	afx_msg void OnBnClickedButtonReadFile();
	afx_msg void OnTimer(UINT_PTR nIDEvent);
	afx_msg void OnBnClickedButtonNasaGraphWindow();
	afx_msg void OnBnClickedButtonHcorGraphWindow();
	afx_msg void OnBnClickedCheckGraph();
	afx_msg void OnBnClickedCheckNasaPq();
	afx_msg void OnBnClickedCheckHcorPq();
	afx_msg void OnEnChangeEditNasaPort();
	afx_msg void OnEnChangeEditHcorPort();
	afx_msg void OnEnChangeEditGraphTimeSpan();
	afx_msg void OnEnChangeEditGraphTempRange1();
	afx_msg void OnEnChangeEditGraphTempRange2();
	afx_msg void OnEnChangeEditGraphVoltageRange1();
	afx_msg void OnEnChangeEditGraphVoltageRange2();
	afx_msg void OnBnClickedCheckNasaAutoFileName();
	afx_msg void OnBnClickedCheckHcorAutoFileName();
	afx_msg void OnBnClickedCheckEnableTempChk();
	afx_msg void OnBnClickedCheckEnableVoltageChk();
	afx_msg void OnBnClickedButtonSetMonitoring();
	afx_msg void OnBnClickedRadioNasaSampleRate();
	afx_msg void OnBnClickedRadioNasaAccumulate();
	afx_msg void OnBnClickedRadioHcorSampleRate();
	afx_msg void OnBnClickedRadioHcorAccumulate();
	virtual void OnOK();
	virtual void OnCancel();
	afx_msg void OnBnClickedButtonClose();
	afx_msg LRESULT OnMouseIn(WPARAM wParam, LPARAM lParam);
	afx_msg LRESULT OnMouseOut(WPARAM wParam, LPARAM lParam);
};
