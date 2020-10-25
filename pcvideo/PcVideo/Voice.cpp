// Voice.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "pcvideo.h"
#include "pcvcomm.h"
#include "Voice.h"
#include "Mixer.h"
#include <math.h>

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

#define IDT_BUFFERING	9800
#define MIN_LEVEL	-50
#define dB10(x)	(log10((double)(x)) * 10)

static USER_BUF *pTopUserBuf;
static WAVEFORMATEX waveFormat16 = {	// 16bit Waveデータの形式
	WAVE_FORMAT_PCM,					// フォーマット：PCM
	N_CHANNELS,							// チャネル数
	N_SAMPLES_PER_SEC,					// １秒あたりのサンプル数
	N_SAMPLES_PER_SEC * N_BYTES_PER_SAMPLE_16,	// １秒あたりのバイト数
	N_BYTES_PER_SAMPLE_16,				// １サンプルあたりのバイト数
	N_BITS_PER_CHANNEL_16,				// １サンプル・１チャネルあたりのビット数
	sizeof(WAVEFORMATEX)
};
static WAVEFORMATEX waveFormat8 = {		// 8bit Waveデータの形式
	WAVE_FORMAT_PCM,					// フォーマット：PCM
	N_CHANNELS,							// チャネル数
	N_SAMPLES_PER_SEC,					// １秒あたりのサンプル数
	N_SAMPLES_PER_SEC * N_BYTES_PER_SAMPLE_8,	// １秒あたりのバイト数
	N_BYTES_PER_SAMPLE_8,				// １サンプルあたりのバイト数
	N_BITS_PER_CHANNEL_8,				// １サンプル・１チャネルあたりのビット数
	sizeof(WAVEFORMATEX)
};

BOOL	InitializeVoice()
{
	// 入力デバイスの個数を取得
	if (waveInGetNumDevs() == 0)
		return FALSE;

	// 出力デバイスの個数を取得
	if (waveOutGetNumDevs() == 0)
		return FALSE;

	InitializeAudioCompress();

	return TRUE;
}

void	OpenVoiceWindow()
{
	if (pVoiceWnd == NULL) {
		pVoiceWnd = new CVoiceWnd();
		SendConxxxtAll();
	}
}

void	CloseVoiceWindow()
{
	if (pVoiceWnd != NULL)
		pVoiceWnd->DestroyWindow();
}

void	ReceiveVoice(PUSER pUser, char *pRecvBuf, int len)
{
	if (pVoiceWnd != NULL)
		pVoiceWnd->ReceiveVoicePacket(pUser, (VOICE_PACKET *)pRecvBuf, len);
}

void	StartVoiceInput()
{
}

/////////////////////////////////////////////////////////////////////////////
// CVoiceWnd ダイアログ


CVoiceWnd::CVoiceWnd(CWnd* pParent /*=NULL*/)
	: CDialog(CVoiceWnd::IDD, pParent)
{
	//{{AFX_DATA_INIT(CVoiceWnd)
	m_iMode = -1;
	//}}AFX_DATA_INIT

	m_hWaveIn = NULL;
	m_hWaveOut = NULL;
	m_pWaveInHdr = NULL;
	m_pWaveOutHdr = NULL;
	m_bSendVoice = FALSE;
	m_nSendStatus = SEND_STATUS_STOP;
	m_idTimerBuffering = 0;
	m_nWaveInDevice = iniData.voiceWindow.nInputDevice;
	m_nWaveOutDevice = iniData.voiceWindow.nOutputDevice;
	m_bWaveInError = FALSE;
	m_bWaveOutError = FALSE;
	m_cMultipleItems = 0;

	Create(IDD_VOICE, (CWnd *)&wndTop);

	if (iniData.settings.bFrontKeep)
		SetWindowPos(&wndTopMost, 0, 0, 0, 0, SWP_NOMOVE | SWP_NOSIZE | SWP_NOACTIVATE);
}


void CVoiceWnd::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	//{{AFX_DATA_MAP(CVoiceWnd)
	DDX_Control(pDX, IDC_AUTO_LEVEL_CONTROL, m_cAutoLevelControl);
	DDX_Control(pDX, IDC_FULL_DUPLEX_MODE, m_cFullDuplexMode);
	DDX_Control(pDX, IDC_INPUT_DEVICE, m_cInputDevice);
	DDX_Control(pDX, IDC_OUTPUT_VOLUME, m_cOutputVolume);
	DDX_Control(pDX, IDC_INPUT_VOLUME, m_cInputVolume);
	DDX_Control(pDX, IDC_AUTO_SEND_LEVEL2, m_cAutoSendLevel2);
	DDX_Control(pDX, IDC_SENDER_LIST, m_cSenderList);
	DDX_Control(pDX, IDC_OUTPUT_LEVEL, m_cOutputLevel);
	DDX_Control(pDX, IDC_SPEAKER_ICON, m_cSpeakerIcon);
	DDX_Control(pDX, IDC_MIC_ICON, m_cMicIcon);
	DDX_Control(pDX, IDC_INPUT_LEVEL, m_cInputLevel);
	DDX_Control(pDX, IDC_AUTO_SEND_LEVEL, m_cAutoSendLevel);
	DDX_Control(pDX, IDC_SWITCH, m_cSwitch);
	DDX_Control(pDX, IDC_SEND, m_cSend);
	DDX_Radio(pDX, IDC_MANUAL_MODE, m_iMode);
	//}}AFX_DATA_MAP
}


BEGIN_MESSAGE_MAP(CVoiceWnd, CDialog)
	//{{AFX_MSG_MAP(CVoiceWnd)
	ON_MESSAGE(MM_WIM_DATA, OnWaveInData)
	ON_MESSAGE(MM_WOM_DONE, OnWaveOutDone)
	ON_WM_DESTROY()
	ON_WM_HSCROLL()
	ON_BN_CLICKED(ID_VOICECALL, OnVoicecall)
	ON_BN_CLICKED(IDC_MANUAL_MODE, OnManualMode)
	ON_BN_CLICKED(IDC_AUTO_MODE, OnAutoMode)
	ON_BN_CLICKED(IDC_FIXED_MODE, OnFixedMode)
	ON_BN_CLICKED(IDC_FULL_DUPLEX_MODE, OnFullDuplexMode)
	ON_BN_CLICKED(IDC_SWITCH, OnSwitch)
	ON_WM_TIMER()
	ON_CBN_SELCHANGE(IDC_INPUT_DEVICE, OnSelchangeInputDevice)
	ON_BN_CLICKED(IDC_AUTO_LEVEL_CONTROL, OnAutoLevelControl)
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CVoiceWnd メッセージ ハンドラ

BOOL CVoiceWnd::OnInitDialog() 
{
	CDialog::OnInitDialog();

	SetIcon(hIconMic, TRUE);		// 大きいアイコンを設定
	SetIcon(hIconMic, FALSE);		// 小さいアイコンを設定

	MixerInitialize(m_nWaveInDevice, m_nWaveOutDevice);

	m_iMode = iniData.voiceWindow.mode;
	m_nUserBufNum = iniData.voiceWindow.nBufferNum + WAVEBUF_NUM;

	m_cInputVolume.SetRange(0, 100);
	m_cInputVolume.SetTicFreq(10);

	SetInputSelect();
	MuteMicAndLineIn();

	m_cAutoLevelControl.SetCheck(iniData.voiceWindow.bAutoLevelControl);

	m_cOutputVolume.SetRange(0, 100);
	m_cOutputVolume.SetTicFreq(10);

	m_cAutoSendLevel.SetRange(MIN_LEVEL, 0);
	m_cAutoSendLevel.SetPos(iniData.voiceWindow.autoSendLevel);

	CheckDuplexMode();

	if (OpenWaveOut(FALSE)) {
		DWORD dwVolume;
		waveOutGetVolume(m_hWaveOut, &dwVolume);
		m_cOutputVolume.SetPos((dwVolume & 0xffff) * 100 / 65535);
		CloseWaveOut();
	}

	m_cFullDuplexMode.EnableWindow(m_bFullDuplex);

	UpdateData(FALSE);

	SetMode(iniData.voiceWindow.mode);

	if (m_bAutoLevelControl)
		SetSelectedInputVolume(50);

	return TRUE;
}

void CVoiceWnd::OnDestroy() 
{
	StopWaveIn();
	StopWaveOut();

	pVoiceWnd = NULL;
	SendConxxxtAll();

	CDialog::OnDestroy();
}

void CVoiceWnd::PostNcDestroy() 
{
	CDialog::PostNcDestroy();

	delete this;
}

void CVoiceWnd::OnCancel() 
{
	DestroyWindow();
}

void CVoiceWnd::OnHScroll(UINT nSBCode, UINT nPos, CScrollBar* pScrollBar) 
{
	switch (pScrollBar->GetDlgCtrlID()) {
	case IDC_INPUT_VOLUME:
		SetInputVolume(m_dwSrcRecVolID[m_nSelectInput], m_cInputVolume.GetPos() * 65535 / 100);
		break;
	case IDC_OUTPUT_VOLUME:
		SetWaveOutVolume(m_cOutputVolume.GetPos());
		break;
	case IDC_AUTO_SEND_LEVEL:
		iniData.voiceWindow.autoSendLevel = m_cAutoSendLevel.GetPos();
		break;
	}

	CDialog::OnHScroll(nSBCode, nPos, pScrollBar);
}

void CVoiceWnd::OnSelchangeInputDevice() 
{
	int i;
	MIXERCONTROLDETAILS_BOOLEAN mxcdb[MAX_CONTROL_ID];

	m_nSelectInput = m_cInputDevice.GetCurSel();

	for (i = 0; i < (int)m_cSrcItems; i++) {
		if (m_dwSrcRecMuteID[i] != -1)
			MixerSetBoolControl(m_nWaveInDevice, m_dwSrcRecMuteID[i], (i == m_nSelectInput) ? 0 : 1);
	}

	if (m_cMultipleItems != 0) {
		for (i = 0; i < (int)m_cMultipleItems; i++)
			mxcdb[i].fValue = (i == m_nSelectInput) ? 1 : 0;

		MixerSetControlValue(m_nWaveInDevice, m_dwDxxxontrolID, m_cMultipleItems, mxcdb, sizeof(MIXERCONTROLDETAILS_BOOLEAN));
	}

	InitAutoLevel();

	m_cInputVolume.SetPos(GetInputVolume(m_dwSrcRecVolID[m_nSelectInput]) * 100 / 65535);
}

BOOL CVoiceWnd::SetInputSelect()
{
	MIXERLINE mxl;
	MIXERCONTROL mxc;
	MIXERCONTROLDETAILS_BOOLEAN mxcdb[MAX_CONTROL_ID];
	MIXERCONTROLDETAILS_LISTTEXT mxcdl[MAX_CONTROL_ID];
	DWORD i, dst, src;

	// 録音マスターの LineID を取得
	memset(&mxl, 0, sizeof(mxl));
	mxl.cbStruct = sizeof(mxl);
	mxl.dwComponentType	= MIXERLINE_COMPONENTTYPE_DST_WAVEIN;
	if (mixerGetLineInfo((HMIXEROBJ)m_nWaveInDevice, &mxl, MIXER_GETLINEINFOF_COMPONENTTYPE) != MMSYSERR_NOERROR)
		return FALSE;

	// 録音入力セレクタ
	if (MixerGetControlsByType(m_nWaveInDevice, mxl.dwLineID, MIXERCONTROL_CONTROLTYPE_MUX, mxc) == MMSYSERR_NOERROR ||
			MixerGetControlsByType(m_nWaveInDevice, mxl.dwLineID, MIXERCONTROL_CONTROLTYPE_MIXER, mxc) == MMSYSERR_NOERROR) {
		m_cSrcItems = 0;
		m_dwDxxxontrolID = mxc.dwControlID;
		m_cMultipleItems = mxc.cMultipleItems;
		if (m_cMultipleItems > MAX_CONTROL_ID)
			m_cMultipleItems = MAX_CONTROL_ID;

		// セレクタリスト取得
		if (MixerGetControlList(m_nWaveInDevice, m_dwDxxxontrolID, m_cMultipleItems, mxcdl, sizeof(MIXERCONTROLDETAILS_LISTTEXT)) != MMSYSERR_NOERROR)
			return FALSE;

		// セレクト状態取得
		if (MixerGetControlValue(m_nWaveInDevice, m_dwDxxxontrolID, m_cMultipleItems, mxcdb, sizeof(MIXERCONTROLDETAILS_BOOLEAN)) != MMSYSERR_NOERROR)
			return FALSE;

		// 入力機器のボリュームの ControlID 取得
		for (i = 0; i < m_cMultipleItems; i++) {
			m_dwSrcRecVolID[i] = MixerGetControlID(m_nWaveInDevice, mxcdl[i].dwParam1, MIXERCONTROL_CONTROLTYPE_VOLUME);
			m_dwSrcRecMuteID[i] = -1;

			m_cInputDevice.AddString(mxcdl[i].szName);

			if (mxcdb[i].fValue != 0)
				m_nSelectInput = i;
		}
		m_nInputDevice = m_cMultipleItems;
	} else {
		m_cMultipleItems = 0;
		m_cSrcItems = mxl.cConxxxtions;
		if (m_cSrcItems > MAX_CONTROL_ID)
			m_cSrcItems = MAX_CONTROL_ID;
		dst = mxl.dwDestination;
		m_nSelectInput = 0;
		for (src = 0; src < m_cSrcItems; src++) {
			// 入力機器の LineID 取得
			memset(&mxl, 0, sizeof(mxl));
			mxl.cbStruct = sizeof(mxl);
			mxl.dwDestination = dst;
			mxl.dwSource = src;
			if (mixerGetLineInfo((HMIXEROBJ)m_nWaveInDevice, &mxl, MIXER_GETLINEINFOF_SOURCE) != MMSYSERR_NOERROR)
				return FALSE;

			// 入力機器のボリュームの ControlID 取得
			m_dwSrcRecVolID[src] = MixerGetControlID(m_nWaveInDevice, mxl.dwLineID, MIXERCONTROL_CONTROLTYPE_VOLUME);
			m_dwSrcRecMuteID[src] = MixerGetControlID(m_nWaveInDevice, mxl.dwLineID, MIXERCONTROL_CONTROLTYPE_MUTE);

			// 入力機器のミュート状態取得
			if (m_dwSrcRecMuteID[src] != -1) {
				if (MixerGetBoolControl(m_nWaveInDevice, m_dwSrcRecMuteID[src]) == 0)
					m_nSelectInput = src;
			}

			m_cInputDevice.AddString(mxl.szName);
		}
		m_nInputDevice = m_cSrcItems;
	}

	m_cInputDevice.SetCurSel(m_nSelectInput);
	m_cInputVolume.SetPos(GetInputVolume(m_dwSrcRecVolID[m_nSelectInput]) * 100 / 65535);
	OnSelchangeInputDevice();

	return TRUE;
}

void CVoiceWnd::MuteMicAndLineIn()
{
	MIXERLINE mxl;
	DWORD dst, src;
	DWORD cSrcItems;
	DWORD dwSrcRecMuteID;
	static char *name[] = {"ﾏｲｸ", "マイク", "ﾗｲﾝ", "ライン", "Mic", "Line", NULL};
	char **pName;

	// 再生マスターの LineID を取得
	memset(&mxl, 0, sizeof(mxl));
	mxl.cbStruct = sizeof(mxl);
	mxl.dwComponentType	= MIXERLINE_COMPONENTTYPE_DST_SPEAKERS;
	if (mixerGetLineInfo((HMIXEROBJ)m_nWaveOutDevice, &mxl, MIXER_GETLINEINFOF_COMPONENTTYPE) != MMSYSERR_NOERROR)
		return;

	// 再生入力セレクタ
	cSrcItems = mxl.cConxxxtions;
	dst = mxl.dwDestination;
	for (src = 0; src < cSrcItems; src++) {
		// 出力機器の LineID 取得
		memset(&mxl, 0, sizeof(mxl));
		mxl.cbStruct = sizeof(mxl);
		mxl.dwDestination = dst;
		mxl.dwSource = src;
		if (mixerGetLineInfo((HMIXEROBJ)m_nWaveOutDevice, &mxl, MIXER_GETLINEINFOF_SOURCE) != MMSYSERR_NOERROR)
			return;

		// 出力機器のミュートの ControlID 取得
		dwSrcRecMuteID = MixerGetControlID(m_nWaveOutDevice, mxl.dwLineID, MIXERCONTROL_CONTROLTYPE_MUTE);

		// 出力機器のミュート状態取得
		if (dwSrcRecMuteID != -1) {
			for (pName = name; *pName != NULL; pName++) {
				if (strnicmp(mxl.szName, *pName, strlen(*pName)) == 0) {
					if (MixerGetBoolControl(m_nWaveInDevice, dwSrcRecMuteID) == 0)
						MixerSetBoolControl(m_nWaveInDevice, dwSrcRecMuteID, 1);
					break;
				}
			}
		}
	}
}

DWORD CVoiceWnd::GetInputVolume(DWORD dwControlID)
{
	return MixerGetUnsignedControl(m_nWaveInDevice, dwControlID);
}

void CVoiceWnd::SetInputVolume(DWORD dwControlID, DWORD dwLevel)
{
	MixerSetUnsignedControl(m_nWaveInDevice, dwControlID, dwLevel);
}

void CVoiceWnd::SetSelectedInputVolume(int nLevel)
{
	m_cInputVolume.SetPos(nLevel);
	SetInputVolume(m_dwSrcRecVolID[m_nSelectInput], nLevel * 65535 / 100);
}

void CVoiceWnd::UpDownInputVolume(int nUpDown)
{
	int nLevel;

	nLevel = m_cInputVolume.GetPos() + nUpDown;
	if (nLevel < 0)
		nLevel = 0;
	else if (nLevel > 100)
		nLevel = 100;

	m_cInputVolume.SetPos(nLevel);
	SetInputVolume(m_dwSrcRecVolID[m_nSelectInput], nLevel * 65535 / 100);
}

void CVoiceWnd::CheckDuplexMode()
{
	m_bFullDuplex = FALSE;
	m_b16bitOut = FALSE;

	if (OpenWaveIn(FALSE)) {
		if (OpenWaveOut(FALSE)) {
			m_bFullDuplex = TRUE;
			CloseWaveOut();
		} else {
			m_b16bitOut = TRUE;
			if (OpenWaveOut(FALSE)) {
				m_bFullDuplex = TRUE;
				CloseWaveOut();
			} else {
				m_bFullDuplex = FALSE;
				m_b16bitOut = FALSE;
			}
		}

		CloseWaveIn();
	}
}

BOOL CVoiceWnd::OpenWaveIn(BOOL bShowErrorMessage)
{
	MMRESULT result;

	if ((result = waveInOpen(&m_hWaveIn, m_nWaveInDevice, &waveFormat16,
		               (DWORD)m_hWnd, NULL, CALLBACK_WINDOW)) != MMSYSERR_NOERROR) {
		if (bShowErrorMessage)
			ErrorMessageWaveIn(result);

		return FALSE;
	}

	return TRUE;
}

void CVoiceWnd::CloseWaveIn()
{
	if (m_hWaveIn != NULL) {
		waveInClose(m_hWaveIn);
		m_hWaveIn = NULL;
	}
}

BOOL CVoiceWnd::StartWaveIn()
{
	if (m_hWaveIn == NULL) {
		if (!m_bFullDuplex)
			StopWaveOut();

		if (!OpenWaveIn(TRUE))
			return FALSE;

		if (!AllocWaveInBuffer()) {
			CloseWaveIn();
			return FALSE;
		}

		LPWAVEHDR pWaveHdr = m_pWaveInHdr;
		while (pWaveHdr != NULL) {
			waveInAddBuffer(m_hWaveIn, pWaveHdr, sizeof(WAVEHDR));

			pWaveHdr = (LPWAVEHDR)pWaveHdr->dwUser;
		}

		InitAutoLevel();
	}

	m_cInputLevel.Reset();

	waveInStart(m_hWaveIn);

	return TRUE;
}

void CVoiceWnd::StopWaveIn()
{
	StopSendVoice();

	if (m_hWaveIn != NULL) {
		waveInStop(m_hWaveIn);
		waveInReset(m_hWaveIn);
		FreeWaveInBuffer();
		CloseWaveIn();

		if (m_hWnd != NULL) {
			MSG msg;
			for (;;) {
				if (!::PeekMessage(&msg, m_hWnd, MM_WIM_DATA, MM_WIM_DATA, PM_REMOVE))
					break;
			}
		}
	}

	m_cInputLevel.DrawLevelMeter(NULL);
}

BOOL CVoiceWnd::AllocWaveInBuffer()
{
	int	i;
	HGLOBAL hData, hWaveHdr;
	LPSTR	pData;
	LPWAVEHDR pWaveHdr, pWaveHdr2;

	pWaveHdr2 = NULL;

	for (i = 0; i < WAVEBUF_NUM; i++) {
		if ((hData = ::GlobalAlloc(GMEM_MOVEABLE | GMEM_SHARE, WAVEBUF_SIZE * 2)) == NULL) {
			FreeWaveInBuffer();
			return FALSE;
		}

		if ((hWaveHdr = ::GlobalAlloc(GMEM_MOVEABLE | GMEM_SHARE | GMEM_ZEROINIT, sizeof(WAVEHDR))) == NULL) {
			::GlobalFree(hData);
			FreeWaveInBuffer();
			return FALSE;
		}

		pData = (LPSTR)::GlobalLock(hData);
		pWaveHdr = (LPWAVEHDR)::GlobalLock(hWaveHdr);

		m_pWaveInHdr = pWaveHdr;

		pWaveHdr->lpData = pData;
		pWaveHdr->dwBufferLength = WAVEBUF_SIZE * 2;
		pWaveHdr->dwUser = (DWORD)pWaveHdr2;
		pWaveHdr2 = pWaveHdr;

		if (waveInPrepareHeader(m_hWaveIn, pWaveHdr, sizeof(WAVEHDR)) != 0) {
			FreeWaveInBuffer();
			return FALSE;
		}
	}

	return TRUE;
}

void CVoiceWnd::FreeWaveInBuffer()
{
	LPWAVEHDR pWaveHdr, pWaveHdr2;
	HGLOBAL hMem;
	int i;

	pWaveHdr = m_pWaveInHdr;

	for (i = 0; i < WAVEBUF_NUM && pWaveHdr != NULL; i++) {
		pWaveHdr2 = (LPWAVEHDR)pWaveHdr->dwUser;

		waveInUnprepareHeader(m_hWaveIn, pWaveHdr, sizeof(WAVEHDR));

		hMem = ::GlobalHandle(pWaveHdr->lpData);
		::GlobalUnlock(hMem);
		::GlobalFree(hMem);

		hMem = ::GlobalHandle(pWaveHdr);
		::GlobalUnlock(hMem);
		::GlobalFree(hMem);

		pWaveHdr = pWaveHdr2;
	}

	m_pWaveInHdr = NULL;
}

void CVoiceWnd::ErrorMessageWaveIn(MMRESULT mmrError)
{
	char message[128];

	if (waveInGetErrorText(mmrError, message, sizeof(message)) != MMSYSERR_NOERROR)
		strcpy(message, GetString(IDS_UNDEFINED_ERR));

	AfxMessageBox(message);
}

LONG CVoiceWnd::OnWaveInData(WPARAM wParam, LPARAM lParam)
{
	LPWAVEHDR pWaveHdr = (LPWAVEHDR)lParam;
	double fInputLevel;
	BYTE waveBuf[WAVEBUF_SIZE];

	AutoLevelControl((short *)pWaveHdr->lpData, waveBuf);

	fInputLevel = m_cInputLevel.DrawLevelMeter(waveBuf);

	if (iniData.voiceWindow.mode == MODE_AUTO)
		CheckAutoSendLevel(fInputLevel);

	if (m_bSendVoice)
		SendVoicePacket(waveBuf);

	waveInAddBuffer(m_hWaveIn, pWaveHdr, sizeof(WAVEHDR));

	return 0;
}

#define LEVEL_REFERENCE	8192
#define LEVEL_OVER		25000
#define LEVEL_UNDER		1024

void CVoiceWnd::InitAutoLevel()
{
	m_nAutoLevel = 0;
	m_nNoiseLevel = LEVEL_UNDER;
}

void CVoiceWnd::AutoLevelControl(short *pInData, BYTE *pOutData)
{
	int i;
	int nPeak, nAbs, nAutoLevelPrev;

	if (m_bAutoLevelControl) {
		nPeak = pInData[0];
		for (i = 0; i < WAVEBUF_SIZE; i++) {
			nAbs = abs(pInData[i]);
			if (nAbs > nPeak)
				nPeak = nAbs;
		}

		if (nPeak < ++m_nNoiseLevel)
			m_nNoiseLevel = nPeak;

		if (nPeak > LEVEL_OVER)
			UpDownInputVolume(-2);
		else if (nPeak < LEVEL_UNDER && nPeak > m_nNoiseLevel * 5)
			UpDownInputVolume(1);

		if (nPeak < LEVEL_REFERENCE)
			nPeak = (LEVEL_REFERENCE + nPeak) / 2;

		if (nPeak > m_nAutoLevel) {
			nAutoLevelPrev = nPeak;
			m_nAutoLevel = nPeak;
		} else {
			nAutoLevelPrev = m_nAutoLevel;
			m_nAutoLevel += (nPeak - m_nAutoLevel) / 2;
		}

		for (i = 0; i < WAVEBUF_SIZE; i++)
			pOutData[i] = (BYTE)(pInData[i] * 120 / (nAutoLevelPrev + (m_nAutoLevel - nAutoLevelPrev) * i / WAVEBUF_SIZE) + 0x80);
			
	} else {
		for (i = 0; i < WAVEBUF_SIZE; i++)
			pOutData[i] = (BYTE)((pInData[i] >> 8) + 0x80);
	}
}

void CVoiceWnd::CheckAutoSendLevel(double fInputLevel)
{
	if (fInputLevel > iniData.voiceWindow.autoSendLevel) {
		if (!m_bSendVoice) {
			StartSendVoice();
			m_nAutoSendCounter = 10;
		}
	} else {
		if (m_nAutoSendCounter != 0) {
			if (--m_nAutoSendCounter == 0)
				StopSendVoice();
		}
	}
}

BOOL CVoiceWnd::OpenWaveOut(BOOL bShowErrorMessage)
{
	MMRESULT result;

	if ((result = waveOutOpen(&m_hWaveOut, m_nWaveOutDevice, m_b16bitOut ? &waveFormat16 : &waveFormat8,
		               (DWORD)m_hWnd, NULL, CALLBACK_WINDOW)) != MMSYSERR_NOERROR ) {
		if (bShowErrorMessage)
			ErrorMessageWaveOut(result);

		return FALSE;
	}

	return TRUE;
}

void CVoiceWnd::CloseWaveOut()
{
	if (m_hWaveOut != NULL) {
		waveOutClose(m_hWaveOut);
		m_hWaveOut = NULL;
	}
}

BOOL CVoiceWnd::StartWaveOut()
{
	if (m_hWaveOut == NULL) {
		if (!m_bFullDuplex)
			StopWaveIn();

		if (!OpenWaveOut(TRUE))
			return FALSE;

		if (!AllocWaveOutBuffer()) {
			CloseWaveOut();
			return FALSE;
		}

		SetWaveOutVolume(m_cOutputVolume.GetPos());

		m_nWaveOutCount = 0;
		m_cSpeakerIcon.SetIcon(hIconSpeaker);
	}

	m_cOutputLevel.Reset();

	return TRUE;
}

void CVoiceWnd::StopWaveOut()
{
	if (m_hWaveOut != NULL) {
		waveOutReset(m_hWaveOut);
		FreeWaveOutBuffer();
		waveOutClose(m_hWaveOut);
		m_hWaveOut = NULL;

		if (m_hWnd != NULL) {
			MSG msg;
			for (;;) {
				if (!::PeekMessage(&msg, m_hWnd, MM_WOM_DONE, MM_WOM_DONE, PM_REMOVE))
					break;
			}
		}

		m_cSpeakerIcon.SetIcon(hIconSpeakerOff);
	}

	FreeAllUserBuf();
	m_nSendStatus = SEND_STATUS_STOP;

	m_cOutputLevel.DrawLevelMeter(NULL);
}

BOOL CVoiceWnd::AllocWaveOutBuffer()
{
	int	i;
	HGLOBAL hData, hWaveHdr;
	LPSTR	pData;
	LPWAVEHDR pWaveHdr, pWaveHdr2;
	int nBufSize = (m_b16bitOut ? WAVEBUF_SIZE * 2 : WAVEBUF_SIZE);

	pWaveHdr2 = NULL;

	for (i = 0; i < WAVEBUF_NUM; i++) {
		if ((hData = ::GlobalAlloc(GMEM_MOVEABLE | GMEM_SHARE, nBufSize)) == NULL) {
			FreeWaveOutBuffer();
			return FALSE;
		}

		if ((hWaveHdr = ::GlobalAlloc(GMEM_MOVEABLE | GMEM_SHARE | GMEM_ZEROINIT, sizeof(WAVEHDR))) == NULL) {
			::GlobalFree(hData);
			FreeWaveOutBuffer();
			return FALSE;
		}

		pData = (LPSTR)::GlobalLock(hData);
		pWaveHdr = (LPWAVEHDR)::GlobalLock(hWaveHdr);

		m_pWaveOutHdr = pWaveHdr;

		pWaveHdr->lpData = pData;
		pWaveHdr->dwBufferLength = nBufSize;
		pWaveHdr->dwUser = (DWORD)pWaveHdr2;
		pWaveHdr2 = pWaveHdr;

		if (waveOutPrepareHeader(m_hWaveOut, pWaveHdr, sizeof(WAVEHDR)) != 0) {
			FreeWaveOutBuffer();
			return FALSE;
		}
	}

	return TRUE;
}

void CVoiceWnd::FreeWaveOutBuffer()
{
	LPWAVEHDR pWaveHdr, pWaveHdr2;
	HGLOBAL hMem;
	int i;

	pWaveHdr = m_pWaveOutHdr;

	for (i = 0; i < WAVEBUF_NUM && pWaveHdr != NULL; i++) {
		pWaveHdr2 = (LPWAVEHDR)pWaveHdr->dwUser;

		waveOutUnprepareHeader(m_hWaveOut, pWaveHdr, sizeof(WAVEHDR));

		hMem = ::GlobalHandle(pWaveHdr->lpData);
		::GlobalUnlock(hMem);
		::GlobalFree(hMem);

		hMem = ::GlobalHandle(pWaveHdr);
		::GlobalUnlock(hMem);
		::GlobalFree(hMem);

		pWaveHdr = pWaveHdr2;
	}

	m_pWaveOutHdr = NULL;
}

void CVoiceWnd::ErrorMessageWaveOut(MMRESULT mmrError)
{
	char message[128];

	if (waveOutGetErrorText(mmrError, message, sizeof(message)) != MMSYSERR_NOERROR)
		strcpy(message, GetString(IDS_UNDEFINED_ERR));

	MessageBox(message, title, MB_OK | MB_ICONEXCLAMATION);
}

LONG CVoiceWnd::OnWaveOutDone(WPARAM wParam, LPARAM lParam)
{
	LPWAVEHDR pWaveHdr = (LPWAVEHDR)lParam;

	if (m_nWaveOutCount-- == WAVEBUF_NUM)
		OutputBlock(pWaveHdr);
	else
		m_nSendStatus = SEND_STATUS_STOP;

	if (m_nWaveOutCount == 0) {
		StopWaveOut();
		StartWaveIn();
	}

	return 0;
}

void CVoiceWnd::SetWaveOutVolume(int nVolume)
{
	DWORD dwVolume;

	dwVolume = nVolume * 65535 / 100;
	dwVolume = dwVolume | (dwVolume << 16);
	waveOutSetVolume(m_hWaveOut, dwVolume);
}

void CVoiceWnd::OutputBlock(LPWAVEHDR pWaveHdr)
{
	LPSTR pData = pWaveHdr->lpData;
	USER_BUF *pUserBuf;
	char buf[WAVEBUF_SIZE];

	pData = m_b16bitOut ? buf : pWaveHdr->lpData;
	memset(pData, WAVECENTER, WAVEBUF_SIZE);

	if (pTopUserBuf != NULL) {
		for (pUserBuf = pTopUserBuf; pUserBuf != NULL; pUserBuf = pUserBuf->pChain) {
			pUserBuf->nUsedBufPrev = pUserBuf->nUsedBuf;
			if (pUserBuf->bStart || pUserBuf->nUsedBuf >= N_START_BUFNUM)
				GetWaveData(pData, pUserBuf);
		}

		if (m_b16bitOut) {
			short *pData = (short *)pWaveHdr->lpData;
			for (int i = 0; i < WAVEBUF_SIZE; i++)
				*pData++ = (buf[i] - 0x80) << 8;
		}

		waveOutWrite(m_hWaveOut, pWaveHdr, sizeof(*pWaveHdr));
		m_nWaveOutCount++;
	}

	m_cOutputLevel.DrawLevelMeter((BYTE *)pData);

	while (pTopUserBuf != NULL) {
		for (pUserBuf = pTopUserBuf; pUserBuf != NULL; pUserBuf = pUserBuf->pChain) {
			if (pUserBuf->nUsedBuf == 0 && pUserBuf->nUsedBufPrev == 0) {
				FreeUserBuf(pUserBuf);
				break;
			}
		}

		if (pUserBuf == NULL)
			break;
	}
}

void CVoiceWnd::OnTimer(UINT nIDEvent) 
{
	LPWAVEHDR pWaveHdr;
	USER_BUF *pUserBuf;

	KillTimer(nIDEvent);

	if (!StartWaveOut()) {
		FreeAllUserBuf();
		m_nSendStatus = SEND_STATUS_STOP;
		return;
	}

	for (pUserBuf = pTopUserBuf; pUserBuf != NULL; pUserBuf = pUserBuf->pChain)
		pUserBuf->bStart = TRUE;

	for (pWaveHdr = m_pWaveOutHdr; pWaveHdr != NULL; pWaveHdr = (LPWAVEHDR)pWaveHdr->dwUser) {
		if (pTopUserBuf == NULL)
			break;

		OutputBlock(pWaveHdr);
	}

	m_nSendStatus = SEND_STATUS_SENDING;

	CDialog::OnTimer(nIDEvent);
}

void CVoiceWnd::SendButtonOn()
{
	m_cSend.SetWindowText(GetString(IDS_SENDING));
	StartSendVoice();
}

void CVoiceWnd::SendButtonOff()
{
	m_cSend.SetWindowText(GetString(IDS_SEND));
	StopSendVoice();
}

void CVoiceWnd::StartSendVoice()
{
	m_bSendVoice = TRUE;
	m_cMicIcon.SetIcon(hIconMic);
}

void CVoiceWnd::StopSendVoice()
{
	m_bSendVoice = FALSE;
	m_cMicIcon.SetIcon(hIconMicOff);
}

void CVoiceWnd::OnSwitch() 
{
	if (m_cSwitch.GetCheck()) {
		m_cSwitch.SetWindowText(GetString(IDS_SWITCH_SEND));
		StopWaveOut();
		StartWaveIn();
		StartSendVoice();
	} else {
		m_cSwitch.SetWindowText(GetString(IDS_SWITCH_RECV));
		StopSendVoice();
	}
}

void CVoiceWnd::OnVoicecall() 
{
	OpenCallWindow(this, FLAG_VOICE);
}

void CVoiceWnd::OnManualMode() 
{
	SetMode(MODE_MANUAL);
}

void CVoiceWnd::OnAutoMode() 
{
	SetMode(MODE_AUTO);
}

void CVoiceWnd::OnFixedMode() 
{
	SetMode(MODE_FIXED);
}

void CVoiceWnd::OnFullDuplexMode() 
{
	SetMode(MODE_FULL);
}

void CVoiceWnd::SetMode(int nMode)
{
	iniData.voiceWindow.mode = nMode;

	StartWaveIn();

	switch (nMode) {
	case MODE_MANUAL:
		StopSendVoice();
		StopWaveOut();
		ShowButton(BUTTON_SEND, TRUE);
		EnableAutoSendLevel(FALSE);
		break;
	case MODE_AUTO:
		StopSendVoice();
		StopWaveOut();
		ShowButton(BUTTON_SEND, FALSE);
		EnableAutoSendLevel(TRUE);
		break;
	case MODE_FIXED:
		StopSendVoice();
		StopWaveOut();
		ShowButton(BUTTON_SWITCH, TRUE);
		EnableAutoSendLevel(FALSE);
		break;
	case MODE_FULL:
		StartSendVoice();
		ShowButton(BUTTON_SEND, FALSE);
		EnableAutoSendLevel(FALSE);
		break;
	}
}

void CVoiceWnd::ShowButton(int nShowButton, BOOL bEnable)
{
	switch (nShowButton) {
	case BUTTON_SEND:
		m_cSend.ShowWindow(SW_SHOW);
		m_cSend.EnableWindow(bEnable);
		m_cSwitch.ShowWindow(SW_HIDE);
		break;
	case BUTTON_SWITCH:
		m_cSend.ShowWindow(SW_HIDE);
		m_cSwitch.SetCheck(0);
		m_cSwitch.EnableWindow(bEnable);
		m_cSwitch.ShowWindow(SW_SHOW);
		break;
	}
}

void CVoiceWnd::EnableAutoSendLevel(BOOL bEnable)
{
	if (bEnable) {
		m_cAutoSendLevel.ShowWindow(SW_SHOW);
		m_cAutoSendLevel2.ShowWindow(SW_SHOW);
		m_cAutoLevelControl.ShowWindow(SW_HIDE);
		m_bAutoLevelControl = FALSE;
	} else {
		m_cAutoSendLevel.ShowWindow(SW_HIDE);
		m_cAutoSendLevel2.ShowWindow(SW_HIDE);
		m_cAutoLevelControl.ShowWindow(SW_SHOW);
		m_bAutoLevelControl = iniData.voiceWindow.bAutoLevelControl;
	}

	m_cInputVolume.EnableWindow(!m_bAutoLevelControl);
}

void CVoiceWnd::SendVoicePacket(BYTE *pData)
{
	VOICE_PACKET packet;
	PSEND pSend;
	PUSER pUser;
	int len;

	packet.header.header.flag = FLAG_VOICE;
	packet.header.header.id = localIPAddress;

	len = VoiceCompress((char *)pData, &packet);

	for (pSend = pTopSend; pSend != NULL; pSend = pSend->pChain) {
		for (pUser = pSend->pUser; pUser != NULL; pUser = pUser->pSendUser) {
			if (pUser->modeFlag & MODE_VOICEREQ) {
				SendData(&pSend->sock, &packet, len);
				break;
			}
		}
	}
}

void CVoiceWnd::ReceiveVoicePacket(PUSER pUser, VOICE_PACKET *pRecvBuf, int len)
{
	USER_BUF *pUserBuf;

	if (!m_bFullDuplex && m_bSendVoice)
		return;

	if ((pUserBuf = GetUserBuf(pUser)) == NULL)
		return;

	if (pUserBuf->nUsedBuf >= N_USER_BUFNUM)
		return;

	VoiceDecompress(pRecvBuf, pUserBuf->buf[pUserBuf->nPutPtr], len);

	pUserBuf->nUsedBuf++;
	if (++pUserBuf->nPutPtr == N_USER_BUFNUM)
		pUserBuf->nPutPtr = 0;

	switch (m_nSendStatus) {
	case SEND_STATUS_STOP:
		m_nSendStatus = SEND_STATUS_BUFFERING;
		// no break
	case SEND_STATUS_BUFFERING:
		if (m_idTimerBuffering != 0) {
			KillTimer(m_idTimerBuffering);
			m_idTimerBuffering = NULL;
		}

		if (pUserBuf->nUsedBuf >= N_USER_BUFNUM) {
			m_nSendStatus = SEND_STATUS_SENDING;
			StartOutputTrigger();
		} else
			m_idTimerBuffering = SetTimer(IDT_BUFFERING, 500, NULL);
		break;
	}
}

USER_BUF *CVoiceWnd::GetUserBuf(PUSER pUser)
{
	USER_BUF *pUserBuf;

	for (pUserBuf = pTopUserBuf; pUserBuf != NULL; pUserBuf = pUserBuf->pChain) {
		if (pUserBuf->id == pUser->id)
			break;
	}

	if (pUserBuf == NULL)
		pUserBuf = AddUserBuf(pUser);

	return pUserBuf;
}

USER_BUF *CVoiceWnd::AddUserBuf(PUSER pUser)
{
	USER_BUF *pUserBuf;
	int nIndex;

	pUserBuf = new USER_BUF;
	if (pUserBuf != NULL) {
		pUserBuf->bStart = FALSE;
		pUserBuf->nUsedBuf = 0;
		pUserBuf->nUsedBufPrev = 0;
		pUserBuf->nPutPtr = 0;
		pUserBuf->nGetPtr = 0;
		pUserBuf->id = pUser->id;
		pUserBuf->pChain = pTopUserBuf;
		pUserBuf->buf = new char [m_nUserBufNum][WAVEBUF_SIZE];
		pTopUserBuf = pUserBuf;

		nIndex = m_cSenderList.AddString(pUser->userName);
		m_cSenderList.SetItemData(nIndex, pUser->id);
	}

	return pUserBuf;
}

void CVoiceWnd::FreeUserBuf(USER_BUF *pUserBuf)
{
	USER_BUF **ppUserBuf;
	int nCount;
	int i;

	for (ppUserBuf = &pTopUserBuf; *ppUserBuf != NULL; ppUserBuf = &(*ppUserBuf)->pChain) {
		if ((*ppUserBuf) == pUserBuf) {
			*ppUserBuf = (*ppUserBuf)->pChain;
			break;
		}
	}

	nCount = m_cSenderList.GetCount();
	for (i = 0; i < nCount; i++) {
		if (m_cSenderList.GetItemData(i) == pUserBuf->id) {
			m_cSenderList.DeleteString(i);
			break;
		}
	}

	delete [] pUserBuf->buf;
	delete pUserBuf;
}

void CVoiceWnd::FreeAllUserBuf()
{
	while (pTopUserBuf != NULL)
		FreeUserBuf(pTopUserBuf);
}

void CVoiceWnd::StartOutputTrigger()
{
	int nBufCount;
	LPWAVEHDR pWaveHdr;
	USER_BUF *pUserBuf;
	LPSTR pData;
	char buf[WAVEBUF_SIZE];

	if (!StartWaveOut()) {
		FreeAllUserBuf();
		m_nSendStatus = SEND_STATUS_STOP;
		return;
	}

	nBufCount = N_USER_BUFNUM;

	pWaveHdr = m_pWaveOutHdr;
	for (pWaveHdr = m_pWaveOutHdr; pWaveHdr != NULL; pWaveHdr = (LPWAVEHDR)pWaveHdr->dwUser) {
		pData = m_b16bitOut ? buf : pWaveHdr->lpData;
		memset(pData, WAVECENTER, WAVEBUF_SIZE);

		for (pUserBuf = pTopUserBuf; pUserBuf != NULL; pUserBuf = pUserBuf->pChain) {
			if (pUserBuf->nUsedBuf == nBufCount)
				GetWaveData(pData, pUserBuf);
		}

		if (m_b16bitOut) {
			short *pData = (short *)pWaveHdr->lpData;
			for (int i = 0; i < WAVEBUF_SIZE; i++)
				*pData++ = (buf[i] - 0x80) << 8;
		}

		waveOutWrite(m_hWaveOut, pWaveHdr, sizeof(*pWaveHdr));
		m_nWaveOutCount++;

		nBufCount--;
	}
}

void CVoiceWnd::GetWaveData(LPSTR pData, USER_BUF *pUserBuf)
{
	LPSTR pUserData;
	int nData;
	int i;

	if (pUserBuf->nUsedBuf == 0)
		return;

	pUserData = pUserBuf->buf[pUserBuf->nGetPtr];
	for (i = 0; i < WAVEBUF_SIZE; i++) {
		nData = ((int)(BYTE)*pData + (int)(BYTE)*pUserData++) - WAVECENTER;
		if (nData < WAVELOW)
			nData = WAVELOW;
		else if (nData > WAVEHIGH)
			nData = WAVEHIGH;

		*pData++ = (char)nData;
	}

	pUserBuf->nUsedBuf--;
	if (++pUserBuf->nGetPtr == N_USER_BUFNUM)
		pUserBuf->nGetPtr = 0;

	pUserBuf->bStart = TRUE;
}

int CVoiceWnd::VoiceCompress(LPSTR pData, VOICE_PACKET *pPacket)
{
	int	len;

	len = CompressAudio(pData, pPacket->data, WAVEBUF_SIZE, sizeof(pPacket->data));
	if (len > WAVEBUF_SIZE / 2 + 1) {
		len = CompressWave(pData, (unsigned char *)pPacket->data, WAVEBUF_SIZE);
		pPacket->header.compressType = (char)COMPRESS_TYPE1;
	} else
		pPacket->header.compressType = (char)COMPRESS_TYPE2;

	return len + sizeof(VOICE_HEADER);	// 送信レングスを返す
}

int CVoiceWnd::VoiceDecompress(VOICE_PACKET *pPacket, LPSTR pData, int len)
{
	len -= sizeof(VOICE_HEADER);

	switch (pPacket->header.compressType) {
	case COMPRESS_TYPE0:
		memcpy(pData, pPacket->data, len);
		return len;
	case COMPRESS_TYPE1:
		return DecompressWave((unsigned char *)pPacket->data, (unsigned char *)pData, len);
	case COMPRESS_TYPE2:
		return DecompressAudio(pPacket->data, pData);
	default:
		memset(pData, WAVECENTER, WAVEBUF_SIZE);
		return WAVEBUF_SIZE;
	}
}

int CVoiceWnd::CompressWave(LPSTR p1, unsigned char *p2, int len)
{
	int	c, d, x, e, n, ds, f, i, dm;
	unsigned int	t;
	int weight[8];
	double a, b;

	dm = 0;
	for (i = 1; i < len; i++) {
		d = (unsigned char)p1[i] - (unsigned char)p1[i - 1];
		d = abs(d);
		if (d > dm)
			dm = d;
	}

	a = pow(dm / 2, 1.0 / 7);
	b = 1;
	for (i = 0; i < 8; i++) {
		weight[i] = (int)(b + i * dm / 20 + 0.5);
		if (i != 0 && weight[i] <= weight[i - 1])
			weight[i] = weight[i - 1] + 1;
		b *= a;
	}

	*p2++ = (unsigned char)dm;
	c = *p2++ = (unsigned char)*p1;

	n = 2;
	e = 0;
	f = 0;
	while (len != 0) {
		d = ds = (unsigned char)*p1 - c + e;
		c = (unsigned char)*p1++;

		if (d < 0) {
			x = 0x08;
			d = -d;
		} else
			x = 0;

		for (i = 0; ; i++) {
			if (d <= weight[i] * 4 / 3 || i == 7)
				break;
			x++;
		}

		t = weight[i];
		if (x & 0x08)
			t = -(int)t;

		e = ds - t;
		if (f == 0) {
			*p2 = x << 4;
			f = 1;
		} else {
			*p2++ |= x;
			n++;
			f = 0;
		}

		len--;
	}
	return(n);
}

int CVoiceWnd::DecompressWave(unsigned char *p1, unsigned char *p2, int len)
{
	int	c, x, d, s, n, i, dm;
	int weight[8];
	double a, b;

	dm = *p1++;
	a = pow(dm / 2, 1.0 / 7);
	b = 1;
	for (i = 0; i < 8; i++) {
		weight[i] = (int)(b + i * dm / 20 + 0.5);
		if (i != 0 && weight[i] <= weight[i - 1])
			weight[i] = weight[i - 1] + 1;
		b *= a;
	}

	c = *p1++;

	len -= 2;
	n = 0;
	while (len) {
		x = *p1 >> 4;
		if (x & 0x08) {
			s = -1;
			x &= 0x07;
		} else
			s = 1;

		d = weight[x] * s;
		c += d;

		if (c < 0)
			*p2++ = 0;
		else if (c > 255)
			*p2++ = 255;
		else
			*p2++ = c;

		n++;

		x = *p1++ & 0x0f;
		if (x & 0x08) {
			s = -1;
			x &= 0x07;
		} else
			s = 1;

		d = weight[x] * s;
		c += d;

		if (c < 0)
			*p2++ = 0;
		else if (c > 255)
			*p2++ = 255;
		else
			*p2++ = c;

		n++;

		len--;
	}

	return(n);
}

/////////////////////////////////////////////////////////////////////////////
// CLevelMeter

CLevelMeter::CLevelMeter()
{
	m_BrushGreen.CreateSolidBrush(RGB(0, 224, 0));
	m_BrushYellow.CreateSolidBrush(RGB(224, 224, 0));
	m_BrushRed.CreateSolidBrush(RGB(224, 0, 0));
	m_BrushGray.CreateSolidBrush(RGB(96, 96, 96));
	m_fLevel = 0;
	m_bEnable = FALSE;
	m_nPower = 0;
	m_bReset = TRUE;
}

CLevelMeter::~CLevelMeter()
{
}


BEGIN_MESSAGE_MAP(CLevelMeter, CWnd)
	//{{AFX_MSG_MAP(CLevelMeter)
	ON_WM_PAINT()
	ON_WM_ERASEBKGND()
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()


/////////////////////////////////////////////////////////////////////////////
// CLevelMeter メッセージ ハンドラ

void CLevelMeter::OnPaint() 
{
	CPaintDC dc(this); // 描画用のデバイス コンテキスト

	CRect rectClient;
	CRect rectDraw;

	GetClientRect(rectClient);

	int pos;
	if (m_bEnable) {
		pos = rectClient.right - (int)(m_fLevel / MIN_LEVEL * rectClient.right);

		if (pos < 4)
			pos = 4;
	} else
		pos = 0;

//	rectDraw.SetRect(0, 0, pos, rectClient.bottom);
//	dc.FillRect(rectDraw, &m_BrushBar);

	rectDraw.SetRect(pos / 4 * 4, 0, rectClient.right, rectClient.bottom);
	dc.FillRect(rectDraw, &m_BrushGray);

	int i;
	rectDraw.SetRect(0, 0, 0, rectClient.bottom);
	for (i = 0; i < pos - 3; i += 4) {
		rectDraw.left = i;
		rectDraw.right = i + 3;
		if (i > rectClient.right * 6 / 7)
			dc.FillRect(rectDraw, &m_BrushRed);
		else if (i > rectClient.right * 3 / 5)
			dc.FillRect(rectDraw, &m_BrushYellow);
		else
			dc.FillRect(rectDraw, &m_BrushGreen);
	}
}

BOOL CLevelMeter::OnEraseBkgnd(CDC* pDC) 
{
	CRect rect;

	GetClientRect(rect);
	pDC->FillRect(rect, &m_BrushGray);

	return TRUE;
}

double CLevelMeter::DrawLevelMeter(BYTE *pData)
{
	int nPower = 0;

	if (pData != NULL) {
		int i;
		int data;

		for (i = 0; i < WAVEBUF_SIZE; i++) {
			data = (signed char)((BYTE)0x80 - *(pData + i));
			nPower += data * data;
		}

		if (m_bReset) {
			m_nPower = nPower;
			m_bReset = FALSE;
		} else
			m_nPower = (m_nPower + nPower) / 2;

		if (m_nPower != 0)
			m_fLevel = dB10((double)m_nPower / (128 * 128 * WAVEBUF_SIZE));
		else
			m_fLevel = MIN_LEVEL;

		m_bEnable = TRUE;
	} else
		m_bEnable = FALSE;

	Invalidate(FALSE);

	return m_fLevel;
}

void CLevelMeter::Reset()
{
	m_bReset = TRUE;
}

/////////////////////////////////////////////////////////////////////////////
// CSendButton

CSendButton::CSendButton()
{
}

CSendButton::~CSendButton()
{
}


BEGIN_MESSAGE_MAP(CSendButton, CButton)
	//{{AFX_MSG_MAP(CSendButton)
	ON_WM_LBUTTONDBLCLK()
	ON_WM_LBUTTONDOWN()
	ON_WM_LBUTTONUP()
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CSendButton メッセージ ハンドラ

void CSendButton::OnLButtonDblClk(UINT nFlags, CPoint point) 
{
	((CVoiceWnd *)GetParent())->SendButtonOn();

	CButton::OnLButtonDblClk(nFlags, point);
}

void CSendButton::OnLButtonDown(UINT nFlags, CPoint point) 
{
	((CVoiceWnd *)GetParent())->SendButtonOn();
	
	CButton::OnLButtonDown(nFlags, point);
}

void CSendButton::OnLButtonUp(UINT nFlags, CPoint point) 
{
	((CVoiceWnd *)GetParent())->SendButtonOff();

	CButton::OnLButtonUp(nFlags, point);
}

void CVoiceWnd::OnAutoLevelControl() 
{
	iniData.voiceWindow.bAutoLevelControl = m_cAutoLevelControl.GetCheck();

	if (iniData.voiceWindow.bAutoLevelControl) {
		InitAutoLevel();
		SetSelectedInputVolume(50);
	}

	EnableAutoSendLevel(FALSE);
}
