#if !defined(AFX_VOICE_H__E0903DA1_C48A_11D2_8BDD_00104B939DF5__INCLUDED_)
#define AFX_VOICE_H__E0903DA1_C48A_11D2_8BDD_00104B939DF5__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000
// Voice.h : �w�b�_�[ �t�@�C��
//

#define MODE_MANUAL	0			// �蓮�ؑ�
#define MODE_AUTO	1			// �����ؑ�
#define MODE_FIXED	2			// �Œ�ؑ�
#define MODE_FULL	3			// �S��d

#define COMPRESS_TYPE0		0	/* ���k�Ȃ� */
#define COMPRESS_TYPE1		1	/* DPCM ���k */
#define COMPRESS_TYPE2		2	/* Huffman ���k */

#define BUTTON_SEND		0		// ���M�{�^��
#define BUTTON_SWITCH	1		// ����M�ؑփ{�^��

#define WAVEHIGH	0xff
#define WAVECENTER	0x80
#define WAVELOW		0x00

#define SEND_STATUS_STOP		0
#define SEND_STATUS_BUFFERING	1
#define SEND_STATUS_SENDING		2

#define WAVEBUF_SIZE	1024
#define WAVEBUF_NUM		3

#define	N_SAMPLES_PER_SEC	8000
#define N_BITS_PER_CHANNEL_16	16
#define N_BITS_PER_CHANNEL_8	8
#define N_CHANNELS			1
#define	N_BYTES_PER_SAMPLE_16	(N_BITS_PER_CHANNEL_16 * N_CHANNELS / 8)
#define	N_BYTES_PER_SAMPLE_8	(N_BITS_PER_CHANNEL_8 * N_CHANNELS / 8)

#define N_USER_BUFNUM		m_nUserBufNum
#define N_START_BUFNUM		(N_USER_BUFNUM - WAVEBUF_NUM)

#define MAX_CONTROL_ID		20

struct VOICE_HEADER {
	HEADER header;
	char compressType;
};

struct VOICE_PACKET {
	VOICE_HEADER header;
	char data[MAX_PACKET_SIZE - sizeof(VOICE_HEADER)];
};

struct USER_BUF {
	struct USER_BUF *pChain;
	BOOL bStart;
	int nUsedBuf;
	int nUsedBufPrev;
	int nPutPtr;
	int nGetPtr;
	DWORD id;
	char (*buf)[WAVEBUF_SIZE];
};

/////////////////////////////////////////////////////////////////////////////
// CLevelMeter �E�B���h�E

class CLevelMeter : public CWnd
{
// �R���X�g���N�V����
public:
	CLevelMeter();

// �A�g���r���[�g
public:

// �I�y���[�V����
public:

// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B

	//{{AFX_VIRTUAL(CLevelMeter)
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����
public:
	virtual ~CLevelMeter();

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
protected:
	//{{AFX_MSG(CLevelMeter)
	afx_msg void OnPaint();
	afx_msg BOOL OnEraseBkgnd(CDC* pDC);
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

public:
	double DrawLevelMeter(BYTE *pData);
	void Reset();

protected:
	CBrush m_BrushGreen;
	CBrush m_BrushYellow;
	CBrush m_BrushRed;
	CBrush m_BrushGray;
	double m_fLevel;
	BOOL m_bEnable;
	int m_nPower;
	BOOL m_bReset;
};
/////////////////////////////////////////////////////////////////////////////
// CSendButton �E�B���h�E

class CSendButton : public CButton
{
// �R���X�g���N�V����
public:
	CSendButton();

// �A�g���r���[�g
public:

// �I�y���[�V����
public:

// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	//{{AFX_VIRTUAL(CSendButton)
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����
public:
	virtual ~CSendButton();

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
protected:
	//{{AFX_MSG(CSendButton)
	afx_msg void OnLButtonDblClk(UINT nFlags, CPoint point);
	afx_msg void OnLButtonDown(UINT nFlags, CPoint point);
	afx_msg void OnLButtonUp(UINT nFlags, CPoint point);
	//}}AFX_MSG

	DECLARE_MESSAGE_MAP()
};

/////////////////////////////////////////////////////////////////////////////
// CVoiceWnd �_�C�A���O

class CVoiceWnd : public CDialog
{
// �R���X�g���N�V����
public:
	CVoiceWnd(CWnd* pParent = NULL);   // �W���̃R���X�g���N�^

// �_�C�A���O �f�[�^
	//{{AFX_DATA(CVoiceWnd)
	enum { IDD = IDD_VOICE };
	CButton	m_cAutoLevelControl;
	CButton	m_cFullDuplexMode;
	CComboBox	m_cInputDevice;
	CSliderCtrl	m_cOutputVolume;
	CSliderCtrl	m_cInputVolume;
	CStatic	m_cAutoSendLevel2;
	CListBox	m_cSenderList;
	CLevelMeter	m_cOutputLevel;
	CStatic	m_cSpeakerIcon;
	CStatic	m_cMicIcon;
	CLevelMeter	m_cInputLevel;
	CSliderCtrl	m_cAutoSendLevel;
	CButton	m_cSwitch;
	CSendButton	m_cSend;
	int		m_iMode;
	//}}AFX_DATA


// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	//{{AFX_VIRTUAL(CVoiceWnd)
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g
	virtual void PostNcDestroy();
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����
protected:

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
	//{{AFX_MSG(CVoiceWnd)
	afx_msg LONG OnWaveInData(WPARAM wParam, LPARAM lParam);
	afx_msg LONG OnWaveOutDone(WPARAM wParam, LPARAM lParam);
	virtual BOOL OnInitDialog();
	afx_msg void OnDestroy();
	virtual void OnCancel();
	afx_msg void OnHScroll(UINT nSBCode, UINT nPos, CScrollBar* pScrollBar);
	afx_msg void OnVoicecall();
	afx_msg void OnManualMode();
	afx_msg void OnAutoMode();
	afx_msg void OnFixedMode();
	afx_msg void OnFullDuplexMode();
	afx_msg void OnSwitch();
	afx_msg void OnTimer(UINT nIDEvent);
	afx_msg void OnSelchangeInputDevice();
	afx_msg void OnAutoLevelControl();
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

public:
	void ReceiveVoicePacket(PUSER pUser, VOICE_PACKET *pRecvBuf, int len);

protected:
	BOOL m_bFullDuplex;
	HWAVEIN m_hWaveIn;
	HWAVEOUT m_hWaveOut;
	LPWAVEHDR m_pWaveInHdr;
	LPWAVEHDR m_pWaveOutHdr;
	BOOL m_bSendVoice;
	int m_nWaveOutCount;
	int m_nSendStatus;
	UINT m_idTimerBuffering;
	int m_nInOutMode;
	DWORD m_dwDxxxontrolID;
	DWORD m_cMultipleItems;
	DWORD m_cSrcItems;
	DWORD m_dwSrcRecVolID[MAX_CONTROL_ID];
	DWORD m_dwSrcRecMuteID[MAX_CONTROL_ID];
	int m_nSelectInput;
	int m_nInputDevice;
	int m_nWaveInDevice;
	int m_nWaveOutDevice;
	int m_nAutoSendCounter;
	BOOL m_bWaveInError;
	BOOL m_bWaveOutError;
	int m_nAutoLevel;
	BOOL m_bAutoLevelControl;
	int m_nNoiseLevel;
	BOOL m_b16bitOut;
	int m_nUserBufNum;

	friend CSendButton;
	BOOL SetInputSelect();
	DWORD GetInputVolume(DWORD dwControlID);
	void SetInputVolume(DWORD dwControlID, DWORD dwLevel);
	void SetSelectedInputVolume(int nLevel);
	void UpDownInputVolume(int nUpDown);
	void MuteMicAndLineIn();
	void CheckDuplexMode();
	BOOL OpenWaveIn(BOOL bShowErrorMessage);
	void CloseWaveIn();
	BOOL StartWaveIn();
	void StopWaveIn();
	BOOL AllocWaveInBuffer();
	void FreeWaveInBuffer();
	void ErrorMessageWaveIn(MMRESULT mmrError);
	void InitAutoLevel();
	void AutoLevelControl(short *pInData, BYTE *pOutData);
	void CheckAutoSendLevel(double fInputLevel);
	BOOL OpenWaveOut(BOOL bShowErrorMessage);
	void CloseWaveOut();
	BOOL StartWaveOut();
	void StopWaveOut();
	BOOL AllocWaveOutBuffer();
	void FreeWaveOutBuffer();
	void ErrorMessageWaveOut(MMRESULT mmrError);
	void SetWaveOutVolume(int nVolume);
	void OutputBlock(LPWAVEHDR pWaveHdr);
	void SendButtonOn();
	void SendButtonOff();
	void StartSendVoice();
	void StopSendVoice();
	void SetMode(int nMode);
	void ShowButton(int nShowButton, BOOL bEnable);
	void EnableAutoSendLevel(BOOL bEnable);
	void SendVoicePacket(BYTE *pData);
	USER_BUF *GetUserBuf(PUSER pUser);
	USER_BUF *AddUserBuf(PUSER pUser);
	void FreeUserBuf(USER_BUF *pUserBuf);
	void FreeAllUserBuf();
	void StartOutputTrigger();
	void GetWaveData(LPSTR pData, USER_BUF *pUserBuf);
	int VoiceCompress(LPSTR pData, VOICE_PACKET *pPacket);
	int VoiceDecompress(VOICE_PACKET *pPacket, LPSTR pData, int len);
	int CompressWave(LPSTR p1, unsigned char *p2, int len);
	int DecompressWave(unsigned char *p1, unsigned char *p2, int len);
};

/////////////////////////////////////////////////////////////////////////////
//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ �͑O�s�̒��O�ɒǉ��̐錾��}�����܂��B

#endif // !defined(AFX_VOICE_H__E0903DA1_C48A_11D2_8BDD_00104B939DF5__INCLUDED_)
