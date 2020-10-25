/************************************************************************
 *
 *			�o�b�|�u�h�c�d�n
 *
 *		����		: �Ăяo������
 *		�t�@�C����	: call.cpp
 *		�쐬��		: k.suzuki
 *
 ************************************************************************/

// call.cpp : �C���v�������e�[�V���� �t�@�C��
//

#include "stdafx.h"
#include "resource.h"
#include "pcvcomm.h"
#include "pcvideo.h"
#include "call.h"

#ifdef _DEBUG
#undef THIS_FILE
static char BASED_CODE THIS_FILE[] = __FILE__;
#endif

static	char	RequestType;
static	char	UserName[32];
static	CALLHDR	ReceiveHdr;
static	CALLHDR	CallHdr;
static	BOOL	bStartRing = FALSE;
static	void	SendCall(char *, int);
static	void StartVoice();
static	void StartRing();
static	void StopRing();

/////////////////////////////////////////////////////////////////////////////
// CCallWnd �_�C�A���O

CCallWnd::CCallWnd(CWnd* pParent /*=NULL*/)
	: CDialog(CCallWnd::IDD, pParent)
{
	//{{AFX_DATA_INIT(CCallWnd)
		// ����: ClassWizard �͂��̈ʒu�Ƀ����o�̏�����������ǉ����܂��B
	//}}AFX_DATA_INIT

	Create(IDD_CALL, pParent);
	if (iniData.settings.bFrontKeep) {
		SetWindowPos(&wndTopMost,
				0, 0, 0, 0,
				SWP_NOMOVE|SWP_NOSIZE|
				SWP_NOACTIVATE);
	}
}

void CCallWnd::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	//{{AFX_DATA_MAP(CCallWnd)
		// ����: ClassWizard �͂��̈ʒu�� DDX �� DDV �̌Ăяo���R�[�h��ǉ����܂��B
	//}}AFX_DATA_MAP
}

BEGIN_MESSAGE_MAP(CCallWnd, CDialog)
	//{{AFX_MSG_MAP(CCallWnd)
	ON_WM_CLOSE()
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CCallWnd ���b�Z�[�W�n���h��

BOOL CCallWnd::OnInitDialog()
{
	CDialog::OnInitDialog();

	int	index;
	PUSER	pUser;

	index = pMainListWnd->GetCurSel();
	if ((pUser = GetUser(index)) != NULL) {
		CString	mes;

		mes.Format(IDS_CALLING, (LPSTR)pUser->userName);
		SetDlgItemText(IDC_CALLMES, mes);

		/* �h�o�A�h���X�i�󂯑��j */
		CallHdr.id = pUser->id;
		CallHdr.type = RequestType;	// �����^�C�v
		CallHdr.callans = ST_CALL;	// �v��
		CallHdr.status = (char)0x00;	// �X�e�[�^�X
		SendCall((char *)&CallHdr, sizeof(CallHdr));
	}

	return TRUE;
}

void CCallWnd::OnClose()
{
	CallHdr.status = ST_CANCEL;	// �v��CANCEL
	SendCall((char *)&CallHdr, sizeof(CallHdr));
	DestroyWindow();
}

void CCallWnd::OnCancel()
{
	PostMessage(WM_CLOSE, 0, 0);
}

void	CCallWnd::PostNcDestroy()
{
	CDialog::PostNcDestroy();

	pCallWnd = NULL;
	delete this;
}

/////////////////////////////////////////////////////////////////////////////
// CAnswerWnd �_�C�A���O

CAnswerWnd::CAnswerWnd(CWnd* pParent /*=NULL*/)
	: CDialog(CAnswerWnd::IDD, pParent)
{
	//{{AFX_DATA_INIT(CAnswerWnd)
		// ����: ClassWizard �͂��̈ʒu�Ƀ����o�̏�����������ǉ����܂��B
	//}}AFX_DATA_INIT

	Create(IDD_ANSWER, pParent);
	if (iniData.settings.bFrontKeep) {
		SetWindowPos(&wndTopMost,
				0, 0, 0, 0,
				SWP_NOMOVE|SWP_NOSIZE|
				SWP_NOACTIVATE);
	}
}

void CAnswerWnd::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	//{{AFX_DATA_MAP(CAnswerWnd)
		// ����: ClassWizard �͂��̈ʒu�� DDX �� DDV �̌Ăяo���R�[�h��ǉ����܂��B
	//}}AFX_DATA_MAP
}

BEGIN_MESSAGE_MAP(CAnswerWnd, CDialog)
	//{{AFX_MSG_MAP(CAnswerWnd)
	ON_WM_CLOSE()
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CAnswerWnd ���b�Z�[�W�n���h��

BOOL CAnswerWnd::OnInitDialog()
{
	CDialog::OnInitDialog();

	CString	mes;
	
	StartRing();

	switch (ReceiveHdr.type) {
	case TYPE_CHAT:
		mes.Format(IDS_CHAT_CALL, (LPSTR)UserName);
		break;
	case TYPE_VOICE:
		mes.Format(IDS_VOICE_CALL, (LPSTR)UserName);
		break;
	}

	SetDlgItemText(IDC_ANSMES, mes);
	SetDialogPositionCenter(m_hWnd);

	return TRUE;
}

void CAnswerWnd::OnClose()
{
	StopRing();

	/* �h�o�A�h���X�i�󂯑��j */
	ReceiveHdr.id = ReceiveHdr.header.id;
	ReceiveHdr.callans = ST_ANSWER;		// ����
	ReceiveHdr.status = ST_CANCEL;		// ����CANCEL
	SendCall((char *)&ReceiveHdr, sizeof(ReceiveHdr));
	DestroyWindow();
}

void CAnswerWnd::OnCancel()
{
	PostMessage(WM_CLOSE, 0, 0);
}

void CAnswerWnd::OnOK()
{
	ReceiveHdr.id = ReceiveHdr.header.id;
	ReceiveHdr.callans = ST_ANSWER;		// ����
	ReceiveHdr.status = ST_OK;	// ����OK
	SendCall((char *)&ReceiveHdr, sizeof(ReceiveHdr));
	StopRing();
	DestroyWindow();
	if (ReceiveHdr.type == TYPE_CHAT)
		OpenChatWindow();
	else if (ReceiveHdr.type == TYPE_VOICE)
		StartVoice();
}

void	CAnswerWnd::PostNcDestroy()
{
	CDialog::PostNcDestroy();

	pAnswerWnd = NULL;
	delete this;
}

static	void	SendCall(char *buf, int len)
{
	PSEND pSend;
	PCALLHDR pCallHdr;

	pCallHdr = (PCALLHDR)buf;

	pCallHdr->header.flag = (u_char)FLAG_CALL;// �����t���O(FLAG_CALL 0x80)
	pCallHdr->header.id = localIPAddress;	// �h�o�A�h���X

	for (pSend = pTopSend; pSend != NULL; pSend = pSend->pChain)
		SendData(&pSend->sock, buf, len);
}

void OpenCallWindow(CWnd *pParent, char type)
{
	if (pCallWnd == NULL) {
		RequestType = type;
		if (pMainListWnd->GetCurSel() == LB_ERR) {
			MessageBoxID(pParent, IDS_SEL_CALL, MB_OK | MB_ICONINFORMATION);
			return;
		}
		pCallWnd = new CCallWnd(pParent);
	}
}

void CloseCallWindow()
{
	if (pCallWnd)
		pCallWnd->SendMessage(WM_CLOSE, 0, 0);
}

void OpenAnswerWindow()
{
	if (pAnswerWnd == NULL)
		pAnswerWnd = new CAnswerWnd;
}

void CloseAnswerWindow()
{
	if (pAnswerWnd)
		pAnswerWnd->SendMessage(WM_CLOSE, 0, 0);
}

/* �Ăяo���������� */
void	ReceiveCall(PUSER pUser, char *recvbuf, int len)
{
	BOOL	support = TRUE;
	BOOL	active = FALSE;
	CString	mes;
	HDC		hdc;
	HICON	hicon;

	/* �w�b�_�[�����O�X����M���Ȃ��ꍇ�͏������Ȃ� */
	if (len != sizeof(CALLHDR))	return;

	memcpy(&ReceiveHdr, recvbuf, len);

	if (localIPAddress != ReceiveHdr.id)	return;

	lstrcpy((LPSTR)UserName, pUser->userName);

	switch (ReceiveHdr.callans) {
	case ST_CALL:
		switch (ReceiveHdr.type) {
		case TYPE_CHAT:
			if (pChatWnd)
				active = TRUE;
			break;
		case TYPE_VOICE:
			if (bVoice == FALSE)
				support = FALSE;
			if (pVoiceWnd)
				active = TRUE;
			break;
		default:
			return;
		}
		if (support == FALSE) {
			/* �h�o�A�h���X�i�󂯑��j */
			ReceiveHdr.id = ReceiveHdr.header.id;
			ReceiveHdr.callans = ST_ANSWER;		// ����
			ReceiveHdr.status = ST_NOSUPPORT; // �T�|�[�g���ĂȂ�
			SendCall((char *)&ReceiveHdr, sizeof(ReceiveHdr));
			break;
		}
		if (active == TRUE) {
			/* �h�o�A�h���X�i�󂯑��j */
			ReceiveHdr.id = ReceiveHdr.header.id;
			ReceiveHdr.callans = ST_ANSWER;		// ����
			ReceiveHdr.status = ST_ACTIVE;	// ���ɋN���ς�
			SendCall((char *)&ReceiveHdr, sizeof(ReceiveHdr));
			break;
		}
		if (ReceiveHdr.status == ST_CANCEL) {
			CloseAnswerWindow();
		} else if (!pAnswerWnd) {
			OpenAnswerWindow();
		}
		break;
	case ST_ANSWER:
		if (pCallWnd) {
			MessageBeep(0);
			if (ReceiveHdr.status == ST_OK) {
				mes.Format(IDS_RESPONSE, (LPSTR)pUser->userName);
				pCallWnd->SetDlgItemText(IDC_CALLMES, mes);
				hicon = LoadIcon(theApp.m_hInstance, MAKEINTRESOURCE(IDI_ICON3));
			} else if (ReceiveHdr.status == ST_CANCEL) {
				mes.Format(IDS_NO_RESPONSE, (LPSTR)pUser->userName);
				pCallWnd->SetDlgItemText(IDC_CALLMES, mes);
				hicon = LoadIcon(theApp.m_hInstance,
						MAKEINTRESOURCE(IDI_ICON5));
			} else if (ReceiveHdr.status == ST_NOSUPPORT) {
				if (ReceiveHdr.type == TYPE_CHAT)
					mes.LoadString(IDS_NO_CHAT);
				else if (ReceiveHdr.type == TYPE_VOICE)
					mes.LoadString(IDS_NO_VOICE);
				hicon = LoadIcon(theApp.m_hInstance,
						MAKEINTRESOURCE(IDI_ICON5));
				pCallWnd->SetDlgItemText(IDC_CALLMES, mes);
			} else if (ReceiveHdr.status == ST_ACTIVE) {
				if (ReceiveHdr.type == TYPE_CHAT)
					mes.LoadString(IDS_ON_CHAT);
				else if (ReceiveHdr.type == TYPE_VOICE)
					mes.LoadString(IDS_ON_VOICE);
				hicon = LoadIcon(theApp.m_hInstance,
						MAKEINTRESOURCE(IDI_ICON5));
				pCallWnd->SetDlgItemText(IDC_CALLMES, mes);
			}
			hdc = ::GetDC(GetDlgItem(pCallWnd->m_hWnd, IDC_STATIC));
			SetMapMode(hdc, MM_TEXT);
			DrawIcon(hdc, 0, 0, LoadIcon(theApp.m_hInstance,
						MAKEINTRESOURCE(IDI_ICON6)));
			DrawIcon(hdc, 0, 0, hicon);
			pCallWnd->SetDlgItemText(IDCANCEL, " �n �j ");
		}
		break;
	}
}

static	void StartVoice()
{
	if (!pVoiceWnd) {
		StopRing();
		OpenVoiceWindow();
	}
}

static	void StartRing()
{
	int		i;

	if (!bStartRing) {
		if (sndPlaySound("ringring.wav", SND_LOOP | SND_ASYNC) == TRUE) {
			bStartRing = TRUE;
		} else {
			for (i = 0; i < 3; i++)
				MessageBeep(0);
			bStartRing = FALSE;
		}
	}
}

static	void StopRing()
{
	if (bStartRing) {
		sndPlaySound(NULL, 0);
		bStartRing = FALSE;
	}
}