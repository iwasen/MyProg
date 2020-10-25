// MmeSendDlg.cpp : 実装ファイル
//

#include "stdafx.h"
#include "MmeSend.h"
#include "MmeSendDlg.h"
#include "AnalogMonitor.h"
#include "AccMonitor.h"
#include "HaltError.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#endif

#define PORT_NASA 35026
#define PORT_HCOR 35032

//#define _TEST

// アプリケーションのバージョン情報に使われる CAboutDlg ダイアログ

class CAboutDlg : public CDialog
{
public:
	CAboutDlg();

// ダイアログ データ
	enum { IDD = IDD_ABOUTBOX };

	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

// 実装
protected:
	DECLARE_MESSAGE_MAP()
};

CAboutDlg::CAboutDlg() : CDialog(CAboutDlg::IDD)
{
}

void CAboutDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
}

BEGIN_MESSAGE_MAP(CAboutDlg, CDialog)
END_MESSAGE_MAP()


// CMmeSendDlg ダイアログ

static STelemetry s_oTelemetry = {
	{
		0x35,		// nPayloadId;
		0x80,		// nDataId;
		0x01,		// nFrameId;
		0x00,		// nEndSegment;
		0,			// nSegmentCount;
		1072569616,	// nTimestamp; (2014/01/01 00:00:00)
		0x0000,		// nFineTime;
		0x00,		// nMeasurementMode;
		0x01,		// nMeasurementRange;
		100,		// nSamplingRate;
		70,			// nDataNum;
		0x6d7a,		// SStatusWord oStatusWord;
	},
	{
		 1,  2,  3,  4,  5,  6,  7,  8,
		11, 12, 13, 14, 15, 16, 17, 18,
		21, 22, 23, 24, 25, 26, 27, 28
	},			// oAnalogData;
	{
		 1,
		 1,  2,  3,
		11, 12, 13,
		21, 22, 23
	}			// oDigitalData;
};

static SMmeStatus s_oMmeStatus = {
	{
		0x35,		// nPayloadId;
		0x80,		// nDataId;
		0x04,		// nFrameId;
		0x01,		// nEndSegment;
		0,			// nSegmentCount;
		1072569616,	// nTimestamp; (2014/01/01 00:00:00)
		0x0000,		// nFineTime;
		112,		// nDataNum;
		0x6d7a,		// SStatusWord oStatusWord;
	},
	{
		"MDTB",		// sContrxxxodeTableName;
		0x01,		// dwMeasureModeId;
		0x02,		// dwMeasureStatus;
		0x0003,		// dwCurrentRangeId;
		"DPTH",		// sDownlinkPathName;
		2,			// dwDownlinkPathData;
		"BCLK",		// sTimerCountName;
		0x12345678,	// dwTimerCountData;
		"STRT",		// sMeasStartTimeName;
		0x11223344,	// dwMeasStartTimeData;
		"ENDT",		// sMeasEndTimeName;
		0x55667788,	// dwMeasEndTimeData;
		{
			 1,  2,  3,  4,  5,  6,  7,  8,
			11, 12, 13, 14, 15, 16, 17, 18,
			21, 22, 23, 24, 25, 26, 27, 28
		},			// oAnalogData;
		{
			0x00,	// dwSessionNumber;
			0x00,	// dwType;
			0x00,	// dwNature;
			"",		// sTaskName;
			0x00,	// dwErrorCode;
			0x00,	// nErrorTime;
		}			// oLasttimeHalt;
	}
};

static SErrorStatus s_oErrorStatus = {
	{
		0x35,		// nPayloadId;
		0x80,		// nDataId;
		0x05,		// nFrameId;
		0x01,		// nEndSegment;
		0,			// nSegmentCount;
		1072569616,	// nTimestamp; (2014/01/01 00:00:00)
		0x0000,		// nFineTime;
		0x01,		// nErrorCode;
		0x00,		// nSubCode;
		200,		// nDataNum;
		0x6d7a,		// SStatusWord oStatusWord;
	},
	{
		"TASK1",	// sTaskName;
		"ERROR1"	// sErrorMessage;
	}
};

static SMessage s_oMessage = {
	{
		0x35,		// nPayloadId;
		0x80,		// nDataId;
		0x06,		// nFrameId;
		0x01,		// nEndSegment;
		0,			// nSegmentCount;
		1072569616,	// nTimestamp; (2014/01/01 00:00:00)
		0x0000,		// nFineTime;
		80,			// nDataNum;
		0x6d7a,		// SStatusWord oStatusWord;
	},
	{
		"Hello MME Ready", // sMessage;
		{
			0x00,	// dwSessionNumber;
			0x00,	// dwType;
			0x00,	// dwNature;
			"",		// sTaskName;
			0x00,	// dwErrorCode;
			0x00,	// nErrorTime;
		}			// oLasttimeHalt;
	}
};

CMmeSendDlg::CMmeSendDlg(CWnd* pParent /*=NULL*/)
	: CDialog(CMmeSendDlg::IDD, pParent)
	, m_sEditSendFile(_T("Sample.s"))
//	, m_sEditSendFile(_T("C:\\Documents and Settings\\aizawa\\デスクトップ\\MME-G SW_NASA_LINK_提示資料\\04.参考データ\\02.受信データ\\通常シーケンス受信データ\\01.受信データ\\MME_NASA_20130716.s"))
	, m_iEditTimer(1000)
	, m_iRadioPort(0)
	, m_sEditIpAddress(_T("127.0.0.1"))
	, m_sEditTelemetryPayloadId(_T(""))
	, m_sEditTelemetryDataId(_T(""))
	, m_sEditTelemetryFrameId(_T(""))
	, m_sEditTelemetryEndSegment(_T(""))
	, m_sEditTelemetrySegmentCount(_T(""))
	, m_sEditTelemetryTimestamp(_T(""))
	, m_sEditTelemetryFineTime(_T(""))
	, m_sEditTelemetryMeasurementMode(_T(""))
	, m_sEditTelemetryMeasurementRange(_T(""))
	, m_sEditTelemetrySamplingRate(_T(""))
	, m_sEditTelemetryDataNum(_T(""))
	, m_sEditTelemetryStatusWord(_T(""))
	, m_sEditStatusPayloadId(_T(""))
	, m_sEditStatusDataId(_T(""))
	, m_sEditStatusFrameId(_T(""))
	, m_sEditStatusEndSegment(_T(""))
	, m_sEditStatusSegmentCount(_T(""))
	, m_sEditStatusTimestamp(_T(""))
	, m_sEditStatusFineTime(_T(""))
	, m_sEditStatusDataNum(_T(""))
	, m_sEditStatusStatusWord(_T(""))
	, m_sEditStatusControlName(_T(""))
	, m_sEditStatusMeasureModeId(_T(""))
	, m_sEditStatusMeasureStatus(_T(""))
	, m_sEditStatusCurrentRangeId(_T(""))
	, m_sEditStatusDownlinkName(_T(""))
	, m_sEditStatusDownlinkData(_T(""))
	, m_sEditStatusTimerName(_T(""))
	, m_sEditStatusTimerData(_T(""))
	, m_sEditStatusStartName(_T(""))
	, m_sEditStatusStartData(_T(""))
	, m_sEditStatusEndName(_T(""))
	, m_sEditStatusEndData(_T(""))
	, m_sEditErrorPayloadId(_T(""))
	, m_sEditErrorDataId(_T(""))
	, m_sEditErrorFrameId(_T(""))
	, m_sEditErrorEndSegment(_T(""))
	, m_sEditErrorSegmentCount(_T(""))
	, m_sEditErrorTimestamp(_T(""))
	, m_sEditErrorFineTime(_T(""))
	, m_sEditErrorErrorCode(_T(""))
	, m_sEditErrorSubCode(_T(""))
	, m_sEditErrorDataNum(_T(""))
	, m_sEditErrorStatusWord(_T(""))
	, m_sEditErrorTaskName(_T(""))
	, m_sEditErrorMessage(_T(""))
	, m_sEditMessagePayloadId(_T(""))
	, m_sEditMessageDataId(_T(""))
	, m_sEditMessageFrameId(_T(""))
	, m_sEditMessageEndSegment(_T(""))
	, m_sEditMessageSegmentCount(_T(""))
	, m_sEditMessageTimestamp(_T(""))
	, m_sEditMessageFineTime(_T(""))
	, m_sEditMessageDataNum(_T(""))
	, m_sEditMessageStatusWord(_T(""))
	, m_sEditMessageMessage(_T(""))
{
	m_hIcon = AfxGetApp()->LoadIcon(IDR_MAINFRAME);
}

void CMmeSendDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	DDX_Text(pDX, IDC_EDIT_SEND_FILE, m_sEditSendFile);
	DDX_Text(pDX, IDC_EDIT_TIMER, m_iEditTimer);
	DDX_Control(pDX, IDC_EDIT_COUNT, m_cEditCount);
	DDX_Radio(pDX, IDC_RADIO_PORT_NASA, m_iRadioPort);
	DDX_Text(pDX, IDC_EDIT_IP_ADDRESS, m_sEditIpAddress);
	DDX_Text(pDX, IDC_EDIT_TELEMETRY_PAYLOAD_ID, m_sEditTelemetryPayloadId);
	DDX_Text(pDX, IDC_EDIT_TELEMETRY_DATA_ID, m_sEditTelemetryDataId);
	DDX_Text(pDX, IDC_EDIT_TELEMETRY_FRAME_ID, m_sEditTelemetryFrameId);
	DDX_Text(pDX, IDC_EDIT_TELEMETRY_END_SEGMENT, m_sEditTelemetryEndSegment);
	DDX_Text(pDX, IDC_EDIT_TELEMETRY_SEGMENT_COUNT, m_sEditTelemetrySegmentCount);
	DDX_Text(pDX, IDC_EDIT_TELEMETRY_TIMESTAMP, m_sEditTelemetryTimestamp);
	DDX_Text(pDX, IDC_EDIT_TELEMETRY_FINE_TIME, m_sEditTelemetryFineTime);
	DDX_Text(pDX, IDC_EDIT_TELEMETRY_MEASUREMENT_MODE, m_sEditTelemetryMeasurementMode);
	DDX_Text(pDX, IDC_EDIT_TELEMETRY_MEASUREMENT_RANGE, m_sEditTelemetryMeasurementRange);
	DDX_Text(pDX, IDC_EDIT_TELEMETRY_SAMPLING_RATE, m_sEditTelemetrySamplingRate);
	DDX_Text(pDX, IDC_EDIT_TELEMETRY_DATA_NUM, m_sEditTelemetryDataNum);
	DDX_Text(pDX, IDC_EDIT_TELEMETRY_STATUS_WORD, m_sEditTelemetryStatusWord);
	DDX_Text(pDX, IDC_EDIT_STATUS_PAYLOAD_ID, m_sEditStatusPayloadId);
	DDX_Text(pDX, IDC_EDIT_STATUS_DATA_ID, m_sEditStatusDataId);
	DDX_Text(pDX, IDC_EDIT_STATUS_FRAME_ID, m_sEditStatusFrameId);
	DDX_Text(pDX, IDC_EDIT_STATUS_END_SEGMENT, m_sEditStatusEndSegment);
	DDX_Text(pDX, IDC_EDIT_STATUS_SEGMENT_COUNT, m_sEditStatusSegmentCount);
	DDX_Text(pDX, IDC_EDIT_STATUS_TIMESTAMP, m_sEditStatusTimestamp);
	DDX_Text(pDX, IDC_EDIT_STATUS_FINE_TIME, m_sEditStatusFineTime);
	DDX_Text(pDX, IDC_EDIT_STATUS_DATA_NUM, m_sEditStatusDataNum);
	DDX_Text(pDX, IDC_EDIT_STATUS_STATUS_WORD, m_sEditStatusStatusWord);
	DDX_Text(pDX, IDC_EDIT_STATUS_CONTROL_NAME, m_sEditStatusControlName);
	DDX_Text(pDX, IDC_EDIT_STATUS_MEASURE_MODE_ID, m_sEditStatusMeasureModeId);
	DDX_Text(pDX, IDC_EDIT_STATUS_MEASURE_STATUS, m_sEditStatusMeasureStatus);
	DDX_Text(pDX, IDC_EDIT_STATUS_CURRENT_RANGE_ID, m_sEditStatusCurrentRangeId);
	DDX_Text(pDX, IDC_EDIT_STATUS_DOWNLINK_NAME, m_sEditStatusDownlinkName);
	DDX_Text(pDX, IDC_EDIT_STATUS_DOWNLINK_DATA, m_sEditStatusDownlinkData);
	DDX_Text(pDX, IDC_EDIT_STATUS_TIMER_NAME, m_sEditStatusTimerName);
	DDX_Text(pDX, IDC_EDIT_STATUS_TIMER_DATA, m_sEditStatusTimerData);
	DDX_Text(pDX, IDC_EDIT_STATUS_START_NAME, m_sEditStatusStartName);
	DDX_Text(pDX, IDC_EDIT_STATUS_START_DATA, m_sEditStatusStartData);
	DDX_Text(pDX, IDC_EDIT_STATUS_END_NAME, m_sEditStatusEndName);
	DDX_Text(pDX, IDC_EDIT_STATUS_END_DATA, m_sEditStatusEndData);
	DDX_Text(pDX, IDC_EDIT_ERROR_PAYLOAD_ID, m_sEditErrorPayloadId);
	DDX_Text(pDX, IDC_EDIT_ERROR_DATA_ID, m_sEditErrorDataId);
	DDX_Text(pDX, IDC_EDIT_ERROR_FRAME_ID, m_sEditErrorFrameId);
	DDX_Text(pDX, IDC_EDIT_ERROR_END_SEGMENT, m_sEditErrorEndSegment);
	DDX_Text(pDX, IDC_EDIT_ERROR_SEGMENT_COUNT, m_sEditErrorSegmentCount);
	DDX_Text(pDX, IDC_EDIT_ERROR_TIMESTAMP, m_sEditErrorTimestamp);
	DDX_Text(pDX, IDC_EDIT_ERROR_FINE_TIME, m_sEditErrorFineTime);
	DDX_Text(pDX, IDC_EDIT_ERROR_ERROR_CODE, m_sEditErrorErrorCode);
	DDX_Text(pDX, IDC_EDIT_ERROR_SUB_CODE, m_sEditErrorSubCode);
	DDX_Text(pDX, IDC_EDIT_ERROR_DATA_NUM, m_sEditErrorDataNum);
	DDX_Text(pDX, IDC_EDIT_ERROR_STATUS_WORD, m_sEditErrorStatusWord);
	DDX_Text(pDX, IDC_EDIT_ERROR_TASK_NAME, m_sEditErrorTaskName);
	DDX_Text(pDX, IDC_EDIT_ERROR_MESSAGE, m_sEditErrorMessage);
	DDX_Text(pDX, IDC_EDIT_MESSAGE_PAYLOAD_ID, m_sEditMessagePayloadId);
	DDX_Text(pDX, IDC_EDIT_MESSAGE_DATA_ID, m_sEditMessageDataId);
	DDX_Text(pDX, IDC_EDIT_MESSAGE_FRAME_ID, m_sEditMessageFrameId);
	DDX_Text(pDX, IDC_EDIT_MESSAGE_END_SEGMENT, m_sEditMessageEndSegment);
	DDX_Text(pDX, IDC_EDIT_MESSAGE_SEGMENT_COUNT, m_sEditMessageSegmentCount);
	DDX_Text(pDX, IDC_EDIT_MESSAGE_TIMESTAMP, m_sEditMessageTimestamp);
	DDX_Text(pDX, IDC_EDIT_MESSAGE_FINE_TIME, m_sEditMessageFineTime);
	DDX_Text(pDX, IDC_EDIT_MESSAGE_DATA_NUM, m_sEditMessageDataNum);
	DDX_Text(pDX, IDC_EDIT_MESSAGE_STATUS_WORD, m_sEditMessageStatusWord);
	DDX_Text(pDX, IDC_EDIT_STATUS_MEASURE_MODE_ID, m_sEditStatusMeasureModeId);
	DDX_Text(pDX, IDC_EDIT_STATUS_MEASURE_STATUS, m_sEditStatusMeasureStatus);
	DDX_Text(pDX, IDC_EDIT_STATUS_CURRENT_RANGE_ID, m_sEditStatusCurrentRangeId);
	DDX_Text(pDX, IDC_EDIT_MESSAGE_MESSAGE, m_sEditMessageMessage);
}

BEGIN_MESSAGE_MAP(CMmeSendDlg, CDialog)
	ON_WM_SYSCOMMAND()
	ON_WM_PAINT()
	ON_WM_QUERYDRAGICON()
	//}}AFX_MSG_MAP
	ON_BN_CLICKED(IDC_BUTTON_SEND_FILE, &CMmeSendDlg::OnBnClickedButtonSendFile)
	ON_BN_CLICKED(IDC_BUTTON_REFERENCE, &CMmeSendDlg::OnBnClickedButtonReference)
	ON_WM_TIMER()
	ON_BN_CLICKED(IDC_BUTTON_SEND_STOP, &CMmeSendDlg::OnBnClickedButtonSendStop)
	ON_BN_CLICKED(IDC_BUTTON_TELEMETRY_SEND, &CMmeSendDlg::OnBnClickedButtonTelemetrySend)
	ON_BN_CLICKED(IDC_BUTTON_STATUS_SEND, &CMmeSendDlg::OnBnClickedButtonStatusSend)
	ON_BN_CLICKED(IDC_BUTTON_ERROR_SEND, &CMmeSendDlg::OnBnClickedButtonErrorSend)
	ON_BN_CLICKED(IDC_BUTTON_MESSAGE_SEND, &CMmeSendDlg::OnBnClickedButtonMessageSend)
	ON_BN_CLICKED(IDC_BUTTON_TELEMETRY_ANALOG_MONITOR, &CMmeSendDlg::OnBnClickedButtonTelemetryAnalogMonitor)
	ON_BN_CLICKED(IDC_BUTTON_TELEMETRY_ACC_MONITOR, &CMmeSendDlg::OnBnClickedButtonTelemetryAccMonitor)
	ON_BN_CLICKED(IDC_BUTTON_STATUS_ANALOG_MONITOR, &CMmeSendDlg::OnBnClickedButtonStatusAnalogMonitor)
	ON_BN_CLICKED(IDC_BUTTON_STATUS_HALT_ERROR, &CMmeSendDlg::OnBnClickedButtonStatusHaltError)
	ON_BN_CLICKED(IDC_BUTTON_MESSAGE_HALT_ERROR, &CMmeSendDlg::OnBnClickedButtonMessageHaltError)
END_MESSAGE_MAP()


// CMmeSendDlg メッセージ ハンドラ

BOOL CMmeSendDlg::OnInitDialog()
{
	DispData();

	CDialog::OnInitDialog();

	// "バージョン情報..." メニューをシステム メニューに追加します。

	// IDM_ABOUTBOX は、システム コマンドの範囲内になければなりません。
	ASSERT((IDM_ABOUTBOX & 0xFFF0) == IDM_ABOUTBOX);
	ASSERT(IDM_ABOUTBOX < 0xF000);

	CMenu* pSysMenu = GetSystemMenu(FALSE);
	if (pSysMenu != NULL)
	{
		CString strAboutMenu;
		strAboutMenu.LoadString(IDS_ABOUTBOX);
		if (!strAboutMenu.IsEmpty())
		{
			pSysMenu->AppendMenu(MF_SEPARATOR);
			pSysMenu->AppendMenu(MF_STRING, IDM_ABOUTBOX, strAboutMenu);
		}
	}

	// このダイアログのアイコンを設定します。アプリケーションのメイン ウィンドウがダイアログでない場合、
	//  Framework は、この設定を自動的に行います。
	SetIcon(m_hIcon, TRUE);			// 大きいアイコンの設定
	SetIcon(m_hIcon, FALSE);		// 小さいアイコンの設定

	AfxSocketInit();
	m_oSock.Create(0, SOCK_DGRAM);

	return TRUE;  // フォーカスをコントロールに設定した場合を除き、TRUE を返します。
}

void CMmeSendDlg::OnSysCommand(UINT nID, LPARAM lParam)
{
	if ((nID & 0xFFF0) == IDM_ABOUTBOX)
	{
		CAboutDlg dlgAbout;
		dlgAbout.DoModal();
	}
	else
	{
		CDialog::OnSysCommand(nID, lParam);
	}
}

// ダイアログに最小化ボタンを追加する場合、アイコンを描画するための
//  下のコードが必要です。ドキュメント/ビュー モデルを使う MFC アプリケーションの場合、
//  これは、Framework によって自動的に設定されます。

void CMmeSendDlg::OnPaint()
{
	if (IsIconic())
	{
		CPaintDC dc(this); // 描画のデバイス コンテキスト

		SendMessage(WM_ICONERASEBKGND, reinterpret_cast<WPARAM>(dc.GetSafeHdc()), 0);

		// クライアントの四角形領域内の中央
		int cxIcon = GetSystemMetrics(SM_CXICON);
		int cyIcon = GetSystemMetrics(SM_CYICON);
		CRect rect;
		GetClientRect(&rect);
		int x = (rect.Width() - cxIcon + 1) / 2;
		int y = (rect.Height() - cyIcon + 1) / 2;

		// アイコンの描画
		dc.DrawIcon(x, y, m_hIcon);
	}
	else
	{
		CDialog::OnPaint();
	}
}

// ユーザーが最小化したウィンドウをドラッグしているときに表示するカーソルを取得するために、
//  システムがこの関数を呼び出します。
HCURSOR CMmeSendDlg::OnQueryDragIcon()
{
	return static_cast<HCURSOR>(m_hIcon);
}


// バイトデータからワードデータへ変換
#define GETWORD(b1, b2) (((WORD)(b1) << 8) | (b2))
#define GETDWORD(b1, b2, b3, b4) (((((((DWORD)(b1) << 8) | (b2)) << 8) | (b3)) << 8) | (b4))

// パケットデータサイズ
#define PACKET_TIME_SIZE	8
#define USER_HEADER_SIZE	16
#define ACC_DATA_SIZE		20

void CMmeSendDlg::OnBnClickedButtonSendFile()
{
	UpdateData(TRUE);

	if (m_oFile.m_hFile != INVALID_HANDLE_VALUE)
		m_oFile.Close();

	if (m_oFile.Open(m_sEditSendFile, CFile::modeRead | CFile::shareDenyNone)) {
		KillTimer(0);
		m_nCounter = 0;
		SetTimer(0, m_iEditTimer, NULL);
	} else {
		int err = ::GetLastError();
	}
}

void CMmeSendDlg::OnBnClickedButtonReference()
{
	CFileDialog dlg(TRUE, ".s", NULL, 0, ".s|*.s||", this, 0);
	if (dlg.DoModal() == IDOK) {
		m_sEditSendFile = dlg.GetPathName();
		UpdateData(FALSE);
	}
}

BOOL CMmeSendDlg::SendPacket()
{
	struct {
		char time[PACKET_TIME_SIZE];
		BYTE header[USER_HEADER_SIZE];
	} buf;
	BYTE data[2048];
	struct {
		BYTE ocs_header[20];
		BYTE ehs_primary[16];
		BYTE ehs_secondary[12];
		BYTE ccsds_header[16];
		BYTE data_zone[2048];
	} sendBuf;
	UINT nDataCount;
	UINT nDataNum;
	UINT nReadSize;
	int nHour, nMinute, nSecond;

	UpdateData(TRUE);

	// .sファイルの中をスキャンする
	BOOL bLoop = TRUE;
	while (bLoop) {
		// 受信時刻と受信パケットのヘッダを読み込む
		if (m_oFile.Read(&buf, sizeof(buf)) != sizeof(buf))
			return FALSE;

		// データ受信時刻フォーマットチェック
		if (sscanf_s(buf.time, "%2d:%2d:%2d", &nHour, &nMinute, &nSecond) != 3)
			return FALSE;

		UINT nPayloadId = buf.header[0];
		UINT nDataId = buf.header[1];
		UINT nFrameId = buf.header[2] >> 1;
		UINT nEndSegment = buf.header[2] & 0x01;
		UINT nSegmentCount = buf.header[3];
		UINT nTimeStamp = (buf.header[4] << 24) | (buf.header[5] << 16) | (buf.header[6] << 8) | buf.header[7];

		nDataCount = 0;

		if (nPayloadId == 0x35) {
			// データ識別ID
			if (nDataId == 0x80) {
				// フレーム構造識別ID
				switch (nFrameId) {
				case 0x01:	// 通常シーケンスLoレンジ計測モード
				case 0x02:	// 通常シーケンスHiレンジ計測モード
				case 0x03:	// 通常シーケンス拡張レンジ計測モード
				case 0x11:	// 連続シーケンスLoレンジ計測モード
				case 0x12:	// 連続シーケンスHiレンジ計測モード
				case 0x13:	// 連続シーケンス拡張レンジ計測モード
					// テレメトリパケットの内容を取り出す
					nDataNum = GETWORD(buf.header[12], buf.header[13]);
#ifdef _TEST
if (nTimeStamp == 0x3fee1d10)
	nDataNum = 1;
#endif
					// アナログモニタをスキップ
					if (nSegmentCount == 0) {
						m_oFile.Read(data + nDataCount, 48);
						nDataCount += 48;
					}

					// データバッファが指定されていればそこに読み込む。指定されていなければ読み飛ばす
					nReadSize = nDataNum * ACC_DATA_SIZE;
					m_oFile.Read(data + nDataCount, nReadSize);
					nDataCount += nReadSize;
					break;
				case 0x04:	// MMEステータスパケット
				case 0x05:	// エラーステータスパケット
				case 0x06:	// メッセージパケット
					// データ部分を読み飛ばす
					nDataNum = GETWORD(buf.header[12], buf.header[13]);
#ifdef _TEST
{
int nErrCode = buf.header[10];
if (nTimeStamp == 0x3fee1d10 && nFrameId == 5 && !(nErrCode == 2 || nErrCode == 3 || nErrCode == 4 || nErrCode == 5))
	nDataNum = 0;
}
#endif
					m_oFile.Read(data + nDataCount, nDataNum);
					nDataCount += nDataNum;
					break;
				}
			}

			if (nEndSegment != 0)
				bLoop = FALSE;
		} else
			bLoop = FALSE;

		memset(&sendBuf, 0, sizeof(sendBuf));
		*(int *)&sendBuf = m_nCounter++;
		memcpy(sendBuf.data_zone, buf.header, USER_HEADER_SIZE);
		memcpy(sendBuf.data_zone + USER_HEADER_SIZE, data, nDataCount);
		int nSendSize = sizeof(sendBuf) - sizeof(sendBuf.data_zone) + USER_HEADER_SIZE + nDataCount + 2;
		if (m_oSock.SendTo(&sendBuf, nSendSize, m_iRadioPort ? PORT_HCOR : PORT_NASA, m_sEditIpAddress, 0) != nSendSize) {
			KillTimer(0);
			AfxMessageBox("Send Error");
			return FALSE;
		}

		Sleep(0);
	}

	CString sCount;
	sCount.Format("%d", m_nCounter);
	m_cEditCount.SetWindowText(sCount);

	return TRUE;
}

void CMmeSendDlg::OnTimer(UINT_PTR nIDEvent)
{
	if (nIDEvent == 0) {
		if (!SendPacket()) {
			KillTimer(0);
			if (m_oFile.m_hFile != INVALID_HANDLE_VALUE)
				m_oFile.Close();
			AfxMessageBox("送信終了");
		}
	}

	CDialog::OnTimer(nIDEvent);
}

void CMmeSendDlg::OnBnClickedButtonSendStop()
{
	KillTimer(0);
	if (m_oFile.m_hFile != INVALID_HANDLE_VALUE)
		m_oFile.Close();
}

void CMmeSendDlg::DispData()
{
	m_sEditTelemetryPayloadId.Format("0x%02x", s_oTelemetry.oHeader.oCommon.nPayloadId);
	m_sEditTelemetryDataId.Format("0x%02x", s_oTelemetry.oHeader.oCommon.nDataId);
	m_sEditTelemetryFrameId.Format("0x%02x", s_oTelemetry.oHeader.oCommon.nFrameId);
	m_sEditTelemetryEndSegment.Format("%d", s_oTelemetry.oHeader.oCommon.nEndSegment);
	m_sEditTelemetrySegmentCount.Format("%d", s_oTelemetry.oHeader.oCommon.nSegmentCount);
	m_sEditTelemetryTimestamp.Format("0x%08x", s_oTelemetry.oHeader.oCommon.nTimestamp);
	m_sEditTelemetryFineTime.Format("0x%04x", s_oTelemetry.oHeader.oCommon.nFineTime);
	m_sEditTelemetryMeasurementMode.Format("0x%02x", s_oTelemetry.oHeader.nMeasurementMode);
	m_sEditTelemetryMeasurementRange.Format("0x%02x", s_oTelemetry.oHeader.nMeasurementRange);
	m_sEditTelemetrySamplingRate.Format("%d", s_oTelemetry.oHeader.nSamplingRate);
	m_sEditTelemetryDataNum.Format("%d", s_oTelemetry.oHeader.nDataNum);
	m_sEditTelemetryStatusWord.Format("0x%04x", s_oTelemetry.oHeader.nStatusWord);
	m_sEditStatusPayloadId.Format("0x%02x", s_oMmeStatus.oHeader.oCommon.nPayloadId);
	m_sEditStatusDataId.Format("0x%02x", s_oMmeStatus.oHeader.oCommon.nDataId);
	m_sEditStatusFrameId.Format("0x%02x", s_oMmeStatus.oHeader.oCommon.nFrameId);
	m_sEditStatusEndSegment.Format("%d", s_oMmeStatus.oHeader.oCommon.nEndSegment);
	m_sEditStatusSegmentCount.Format("%d", s_oMmeStatus.oHeader.oCommon.nSegmentCount);
	m_sEditStatusTimestamp.Format("0x%08x", s_oMmeStatus.oHeader.oCommon.nTimestamp);
	m_sEditStatusFineTime.Format("0x%04x", s_oMmeStatus.oHeader.oCommon.nFineTime);
	m_sEditStatusDataNum.Format("%d", s_oMmeStatus.oHeader.nDataNum);
	m_sEditStatusStatusWord.Format("0x%04x", s_oMmeStatus.oHeader.nStatusWord);
	m_sEditStatusControlName = s_oMmeStatus.oData.sContrxxxodeTableName;
	m_sEditStatusMeasureModeId.Format("%d", s_oMmeStatus.oData.nMeasureModeId);
	m_sEditStatusMeasureStatus.Format("%d", s_oMmeStatus.oData.nMeasureStatus);
	m_sEditStatusCurrentRangeId.Format("%d", s_oMmeStatus.oData.nCurrentRangeId);
	m_sEditStatusDownlinkName = s_oMmeStatus.oData.sDownlinkPathName;
	m_sEditStatusDownlinkData.Format("0x%08x", s_oMmeStatus.oData.nDownlinkPathData);
	m_sEditStatusTimerName = s_oMmeStatus.oData.sTimerCountName;
	m_sEditStatusTimerData.Format("0x%08x", s_oMmeStatus.oData.nTimerCountData);
	m_sEditStatusStartName = s_oMmeStatus.oData.sMeasStartTimeName;
	m_sEditStatusStartData.Format("0x%08x", s_oMmeStatus.oData.nMeasStartTimeData);
	m_sEditStatusEndName = s_oMmeStatus.oData.sMeasEndTimeName;
	m_sEditStatusEndData.Format("0x%08x", s_oMmeStatus.oData.nMeasEndTimeData);
	m_sEditErrorPayloadId.Format("0x%02x", s_oErrorStatus.oHeader.oCommon.nPayloadId);
	m_sEditErrorDataId.Format("0x%02x", s_oErrorStatus.oHeader.oCommon.nDataId);
	m_sEditErrorFrameId.Format("0x%02x", s_oErrorStatus.oHeader.oCommon.nFrameId);
	m_sEditErrorEndSegment.Format("%d", s_oErrorStatus.oHeader.oCommon.nEndSegment);
	m_sEditErrorSegmentCount.Format("%d", s_oErrorStatus.oHeader.oCommon.nSegmentCount);
	m_sEditErrorTimestamp.Format("0x%08x", s_oErrorStatus.oHeader.oCommon.nTimestamp);
	m_sEditErrorFineTime.Format("0x%04x", s_oErrorStatus.oHeader.oCommon.nFineTime);
	m_sEditErrorErrorCode.Format("0x%02x", s_oErrorStatus.oHeader.nErrorCode);
	m_sEditErrorSubCode.Format("0x%02x", s_oErrorStatus.oHeader.nSubCode);
	m_sEditErrorDataNum.Format("%d", s_oErrorStatus.oHeader.nDataNum);
	m_sEditErrorStatusWord.Format("0x%04x", s_oErrorStatus.oHeader.nStatusWord);
	m_sEditErrorTaskName = s_oErrorStatus.oData.sTaskName;
	m_sEditErrorMessage = s_oErrorStatus.oData.sErrorMessage;
	m_sEditMessagePayloadId.Format("0x%02x", s_oMessage.oHeader.oCommon.nPayloadId);
	m_sEditMessageDataId.Format("0x%02x", s_oMessage.oHeader.oCommon.nDataId);
	m_sEditMessageFrameId.Format("0x%02x", s_oMessage.oHeader.oCommon.nFrameId);
	m_sEditMessageEndSegment.Format("%d", s_oMessage.oHeader.oCommon.nEndSegment);
	m_sEditMessageSegmentCount.Format("%d", s_oMessage.oHeader.oCommon.nSegmentCount);
	m_sEditMessageTimestamp.Format("0x%08x", s_oMessage.oHeader.oCommon.nTimestamp);
	m_sEditMessageFineTime.Format("0x%04x", s_oMessage.oHeader.oCommon.nFineTime);
	m_sEditMessageDataNum.Format("%d", s_oMessage.oHeader.nDataNum);
	m_sEditMessageStatusWord.Format("0x%04x", s_oMessage.oHeader.nStatusWord);
	m_sEditMessageMessage = s_oMessage.oData.sMessage;
}

void CMmeSendDlg::OnBnClickedButtonTelemetrySend()
{
	UpdateData(TRUE);

	s_oTelemetry.oHeader.oCommon.nPayloadId = GetInt(m_sEditTelemetryPayloadId);
	s_oTelemetry.oHeader.oCommon.nDataId = GetInt(m_sEditTelemetryDataId);
	s_oTelemetry.oHeader.oCommon.nFrameId = GetInt(m_sEditTelemetryFrameId);
	s_oTelemetry.oHeader.oCommon.nEndSegment = GetInt(m_sEditTelemetryEndSegment);
	s_oTelemetry.oHeader.oCommon.nSegmentCount = GetInt(m_sEditTelemetrySegmentCount);
	s_oTelemetry.oHeader.oCommon.nTimestamp = GetInt(m_sEditTelemetryTimestamp);
	s_oTelemetry.oHeader.oCommon.nFineTime = GetInt(m_sEditTelemetryFineTime);
	s_oTelemetry.oHeader.nMeasurementMode = GetInt(m_sEditTelemetryMeasurementMode);
	s_oTelemetry.oHeader.nMeasurementRange = GetInt(m_sEditTelemetryMeasurementRange);
	s_oTelemetry.oHeader.nSamplingRate = GetInt(m_sEditTelemetrySamplingRate);
	s_oTelemetry.oHeader.nDataNum = GetInt(m_sEditTelemetryDataNum);
	s_oTelemetry.oHeader.nStatusWord = GetInt(m_sEditTelemetryStatusWord);

	BYTE aBuf[2048];
	BYTE *pBuf = aBuf + 64;
	memset(aBuf, 0, sizeof(aBuf));
	SetUserHeaderCommon(pBuf, s_oTelemetry.oHeader.oCommon);
	SetWord(pBuf, ((s_oTelemetry.oHeader.nMeasurementMode & 0x03) << 14) | ((s_oTelemetry.oHeader.nMeasurementRange & 0x03) << 12) | (s_oTelemetry.oHeader.nSamplingRate & 0xfff));
	SetWord(pBuf, s_oTelemetry.oHeader.nDataNum);
	SetWord(pBuf, s_oTelemetry.oHeader.nStatusWord);
	SetAnalogData(pBuf, s_oTelemetry.oAnalogData);
	SetWord(pBuf, s_oTelemetry.oDigitalData.nSequrnceCount | 0x7000);
	for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
		SetWord(pBuf, s_oTelemetry.oDigitalData.aUnit[nUnit].nAcc[ACC_X]);
		SetWord(pBuf, s_oTelemetry.oDigitalData.aUnit[nUnit].nAcc[ACC_Y]);
		SetWord(pBuf, s_oTelemetry.oDigitalData.aUnit[nUnit].nAcc[ACC_Z]);
	}

	m_oSock.SendTo(aBuf, 64 + USER_HEADER_SIZE + ANALOG_DATA_SIZE + DIGITAL_DATA_SIZE * s_oTelemetry.oHeader.nDataNum + 2, m_iRadioPort ? PORT_HCOR : PORT_NASA, m_sEditIpAddress, 0);
}

void CMmeSendDlg::OnBnClickedButtonStatusSend()
{
	UpdateData(TRUE);

	s_oMmeStatus.oHeader.oCommon.nPayloadId = GetInt(m_sEditStatusPayloadId);
	s_oMmeStatus.oHeader.oCommon.nDataId = GetInt(m_sEditStatusDataId);
	s_oMmeStatus.oHeader.oCommon.nFrameId = GetInt(m_sEditStatusFrameId);
	s_oMmeStatus.oHeader.oCommon.nEndSegment = GetInt(m_sEditStatusEndSegment);
	s_oMmeStatus.oHeader.oCommon.nSegmentCount = GetInt(m_sEditStatusSegmentCount);
	s_oMmeStatus.oHeader.oCommon.nTimestamp = GetInt(m_sEditStatusTimestamp);
	s_oMmeStatus.oHeader.oCommon.nFineTime = GetInt(m_sEditStatusFineTime);
	s_oMmeStatus.oHeader.nDataNum = GetInt(m_sEditStatusDataNum);
	s_oMmeStatus.oHeader.nStatusWord = GetInt(m_sEditStatusStatusWord);
	s_oMmeStatus.oData.sContrxxxodeTableName = m_sEditStatusControlName;
	s_oMmeStatus.oData.nMeasureModeId = GetInt(m_sEditStatusMeasureModeId);
	s_oMmeStatus.oData.nMeasureStatus = GetInt(m_sEditStatusMeasureStatus);
	s_oMmeStatus.oData.nCurrentRangeId = GetInt(m_sEditStatusCurrentRangeId);
	s_oMmeStatus.oData.sDownlinkPathName = m_sEditStatusDownlinkName;
	s_oMmeStatus.oData.nDownlinkPathData = GetInt(m_sEditStatusDownlinkData);
	s_oMmeStatus.oData.sTimerCountName = m_sEditStatusTimerName;
	s_oMmeStatus.oData.nTimerCountData = GetInt(m_sEditStatusTimerData);
	s_oMmeStatus.oData.sMeasStartTimeName = m_sEditStatusStartName;
	s_oMmeStatus.oData.nMeasStartTimeData = GetInt(m_sEditStatusStartData);
	s_oMmeStatus.oData.sMeasEndTimeName = m_sEditStatusEndName;
	s_oMmeStatus.oData.nMeasEndTimeData = GetInt(m_sEditStatusEndData);

	BYTE aBuf[2048];
	BYTE *pBuf = aBuf + 64;
	memset(aBuf, 0, sizeof(aBuf));
	SetUserHeaderCommon(pBuf, s_oMmeStatus.oHeader.oCommon);
	SetWord(pBuf, 0);
	SetWord(pBuf, s_oMmeStatus.oHeader.nDataNum);
	SetWord(pBuf, s_oMmeStatus.oHeader.nStatusWord);
	SetText(pBuf, s_oMmeStatus.oData.sContrxxxodeTableName, 4);
	*pBuf++ = s_oMmeStatus.oData.nMeasureModeId;
	*pBuf++ = s_oMmeStatus.oData.nMeasureStatus;
	SetWord(pBuf, s_oMmeStatus.oData.nCurrentRangeId);
	SetText(pBuf, s_oMmeStatus.oData.sDownlinkPathName, 4);
	SetDWord(pBuf, s_oMmeStatus.oData.nDownlinkPathData);
	SetText(pBuf, s_oMmeStatus.oData.sTimerCountName, 4);
	SetDWord(pBuf, s_oMmeStatus.oData.nTimerCountData);
	SetText(pBuf, s_oMmeStatus.oData.sMeasStartTimeName, 4);
	SetDWord(pBuf, s_oMmeStatus.oData.nMeasStartTimeData);
	SetText(pBuf, s_oMmeStatus.oData.sMeasEndTimeName, 4);
	SetDWord(pBuf, s_oMmeStatus.oData.nMeasEndTimeData);
	SetAnalogData(pBuf, s_oMmeStatus.oData.oAnalogData);
	SetHaltInfo(pBuf, s_oMmeStatus.oData.oLasttimeHalt);

	m_oSock.SendTo(aBuf, 64 + USER_HEADER_SIZE + s_oMmeStatus.oHeader.nDataNum + 2, m_iRadioPort ? PORT_HCOR : PORT_NASA, m_sEditIpAddress, 0);
}

void CMmeSendDlg::OnBnClickedButtonErrorSend()
{
	UpdateData(TRUE);

	s_oErrorStatus.oHeader.oCommon.nPayloadId = GetInt(m_sEditErrorPayloadId);
	s_oErrorStatus.oHeader.oCommon.nDataId = GetInt(m_sEditErrorDataId);
	s_oErrorStatus.oHeader.oCommon.nFrameId = GetInt(m_sEditErrorFrameId);
	s_oErrorStatus.oHeader.oCommon.nEndSegment = GetInt(m_sEditErrorEndSegment);
	s_oErrorStatus.oHeader.oCommon.nSegmentCount = GetInt(m_sEditErrorSegmentCount);
	s_oErrorStatus.oHeader.oCommon.nTimestamp = GetInt(m_sEditErrorTimestamp);
	s_oErrorStatus.oHeader.oCommon.nFineTime = GetInt(m_sEditErrorFineTime);
	s_oErrorStatus.oHeader.nErrorCode = GetInt(m_sEditErrorErrorCode);
	s_oErrorStatus.oHeader.nSubCode = GetInt(m_sEditErrorSubCode);
	s_oErrorStatus.oHeader.nDataNum = GetInt(m_sEditErrorDataNum);
	s_oErrorStatus.oHeader.nStatusWord = GetInt(m_sEditErrorStatusWord);
	s_oErrorStatus.oData.sTaskName = m_sEditErrorTaskName;
	s_oErrorStatus.oData.sErrorMessage = m_sEditErrorMessage;

	BYTE aBuf[2048];
	BYTE *pBuf = aBuf + 64;
	memset(aBuf, 0, sizeof(aBuf));
	SetUserHeaderCommon(pBuf, s_oErrorStatus.oHeader.oCommon);
	*pBuf++ = s_oErrorStatus.oHeader.nErrorCode;
	*pBuf++ = s_oErrorStatus.oHeader.nSubCode;
	SetWord(pBuf, s_oErrorStatus.oHeader.nDataNum);
	SetWord(pBuf, s_oErrorStatus.oHeader.nStatusWord);

	switch (s_oErrorStatus.oHeader.nErrorCode) {
	case 0x02:
		SetText(pBuf, s_oErrorStatus.oData.sTaskName, 8);
		SetText(pBuf, s_oErrorStatus.oData.sErrorMessage, 192);
		break;
	case 0x03:
	case 0x04:
	case 0x05:
		SetText(pBuf, s_oErrorStatus.oData.sErrorMessage, 200);
		break;
	}

	m_oSock.SendTo(aBuf, 64 + USER_HEADER_SIZE + s_oErrorStatus.oHeader.nDataNum + 2, m_iRadioPort ? PORT_HCOR : PORT_NASA, m_sEditIpAddress, 0);
}

void CMmeSendDlg::OnBnClickedButtonMessageSend()
{
	UpdateData(TRUE);

	s_oMessage.oHeader.oCommon.nPayloadId = GetInt(m_sEditMessagePayloadId);
	s_oMessage.oHeader.oCommon.nDataId = GetInt(m_sEditMessageDataId);
	s_oMessage.oHeader.oCommon.nFrameId = GetInt(m_sEditMessageFrameId);
	s_oMessage.oHeader.oCommon.nEndSegment = GetInt(m_sEditMessageEndSegment);
	s_oMessage.oHeader.oCommon.nSegmentCount = GetInt(m_sEditMessageSegmentCount);
	s_oMessage.oHeader.oCommon.nTimestamp = GetInt(m_sEditMessageTimestamp);
	s_oMessage.oHeader.oCommon.nFineTime = GetInt(m_sEditMessageFineTime);
	s_oMessage.oHeader.nDataNum = GetInt(m_sEditMessageDataNum);
	s_oMessage.oHeader.nStatusWord = GetInt(m_sEditMessageStatusWord);
	s_oMessage.oData.sMessage = m_sEditMessageMessage;

	BYTE aBuf[2048];
	BYTE *pBuf = aBuf + 64;
	memset(aBuf, 0, sizeof(aBuf));
	SetUserHeaderCommon(pBuf, s_oMessage.oHeader.oCommon);
	SetWord(pBuf, 0);
	SetWord(pBuf, s_oMessage.oHeader.nDataNum);
	SetWord(pBuf, s_oMessage.oHeader.nStatusWord);
	SetText(pBuf, s_oMessage.oData.sMessage, 56);
	SetHaltInfo(pBuf, s_oMessage.oData.oLasttimeHalt);

	m_oSock.SendTo(aBuf, 64 + USER_HEADER_SIZE + s_oMessage.oHeader.nDataNum + 2, m_iRadioPort ? PORT_HCOR : PORT_NASA, m_sEditIpAddress, 0);
}

void CMmeSendDlg::SetUserHeaderCommon(BYTE *&pBuf, const SUserHeaderCommon &oUserHeaderCommon)
{
	*pBuf++ = oUserHeaderCommon.nPayloadId;
	*pBuf++ = oUserHeaderCommon.nDataId;
	*pBuf++ = (oUserHeaderCommon.nFrameId << 1) | (oUserHeaderCommon.nEndSegment & 0x01);
	*pBuf++ = oUserHeaderCommon.nSegmentCount;
	SetDWord(pBuf, oUserHeaderCommon.nTimestamp);
	SetWord(pBuf, oUserHeaderCommon.nFineTime);
}

void CMmeSendDlg::SetHaltInfo(BYTE *&pBuf, const SLasttimeHalt &oLasttimeHalt)
{
	SetDWord(pBuf, oLasttimeHalt.oBitStatus.nSessionNumber);
	SetDWord(pBuf, oLasttimeHalt.oBitStatus.nType);
	SetDWord(pBuf, oLasttimeHalt.oBitStatus.nNature);

	SetText(pBuf, oLasttimeHalt.oHaltInfo.sTaskName, 4);
	SetDWord(pBuf, oLasttimeHalt.oHaltInfo.nErrorCode);
	SetDWord(pBuf, oLasttimeHalt.oHaltInfo.nErrorTime);
}

void CMmeSendDlg::SetAnalogData(BYTE *&pBuf, const SAnalogData &oAnalogData)
{
	for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
		SetWord(pBuf, oAnalogData.aUnit[nUnit].nAcc[ACC_X]);
		SetWord(pBuf, oAnalogData.aUnit[nUnit].nAcc[ACC_Y]);
		SetWord(pBuf, oAnalogData.aUnit[nUnit].nAcc[ACC_Z]);
		SetWord(pBuf, oAnalogData.aUnit[nUnit].nTemp[TEMP_X]);
		SetWord(pBuf, oAnalogData.aUnit[nUnit].nTemp[TEMP_Y]);
		SetWord(pBuf, oAnalogData.aUnit[nUnit].nTemp[TEMP_Z]);
		SetWord(pBuf, oAnalogData.aUnit[nUnit].nTemp[TEMP_SB]);
		SetWord(pBuf, oAnalogData.aUnit[nUnit].nHeater);
	}
}

void CMmeSendDlg::SetText(BYTE *&pBuf, const CString &sText, int nSize)
{
	int nCopyLen = min(nSize, sText.GetLength());
	memcpy(pBuf, sText, nCopyLen);
	pBuf += nSize;
}

void CMmeSendDlg::SetWord(BYTE *&pBuf, UINT nData)
{
	*pBuf++ = nData >> 8;
	*pBuf++ = nData;
}

void CMmeSendDlg::SetDWord(BYTE *&pBuf, UINT nData)
{
	*pBuf++ = nData >> 24;
	*pBuf++ = nData >> 16;
	*pBuf++ = nData >> 8;
	*pBuf++ = nData;
}

void CMmeSendDlg::OnBnClickedButtonTelemetryAnalogMonitor()
{
	CAnalogMonitor dlg(this, s_oTelemetry.oAnalogData);
	dlg.DoModal();
}

void CMmeSendDlg::OnBnClickedButtonTelemetryAccMonitor()
{
	CAccMonitor dlg(this, s_oTelemetry.oDigitalData);
	dlg.DoModal();
}

void CMmeSendDlg::OnBnClickedButtonStatusAnalogMonitor()
{
	CAnalogMonitor dlg(this, s_oMmeStatus.oData.oAnalogData);
	dlg.DoModal();
}

void CMmeSendDlg::OnBnClickedButtonStatusHaltError()
{
	CHaltError dlg(this, s_oMmeStatus.oData.oLasttimeHalt);
	dlg.DoModal();
}

void CMmeSendDlg::OnBnClickedButtonMessageHaltError()
{
	CHaltError dlg(this, s_oMessage.oData.oLasttimeHalt);
	dlg.DoModal();
}
