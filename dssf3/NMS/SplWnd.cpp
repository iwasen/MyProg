// SplWnd.cpp : �C���v�������e�[�V���� �t�@�C��
//

#include "stdafx.h"
#include "Nms.h"
#include "SplWnd.h"
#include "SetData.h"
#include "Help\ContextHelp.h"

/////////////////////////////////////////////////////////////////////////////
// CSplWnd �_�C�A���O


CSplWnd::CSplWnd(CWnd* pParent /*=NULL*/)
	: CDialog(CSplWnd::IDD, pParent)
{
	Create(IDD, (CWnd *)&wndTop);
}


void CSplWnd::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_DB, m_cDB);
	DDX_Control(pDX, IDC_SPL, m_cSpl);
}


BEGIN_MESSAGE_MAP(CSplWnd, CDialog)
	ON_WM_DESTROY()
	ON_WM_HELPINFO()
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CSplWnd ���b�Z�[�W �n���h��

BOOL CSplWnd::OnInitDialog()
{
	CDialog::OnInitDialog();

	CString str;
	LOGFONT font;
	memset(&font, 0, sizeof(font));
	font.lfHeight = 180;
	font.lfCharSet = DEFAULT_CHARSET;
	str.LoadString(IDS_DEFAULTFONT);
	strcpy_s(font.lfFaceName, str);
	m_Font.CreatePointFontIndirect(&font);
	m_cSpl.SetFont(&m_Font);

	static const char *unit[] = {"dB", "dB(A)", "dB(B)", "dB(C)"};
	m_cDB.SetWindowText(unit[g_oSetData.Measurement.AcfCondition.nFreqWeighting]);

	SetWindowPos(&wndTopMost, g_oSetData.SplWindow.left, g_oSetData.SplWindow.top, 0, 0, SWP_NOSIZE);
	ShowWindow(SW_SHOW);

	return TRUE;
}

void CSplWnd::OnCancel()
{
	DestroyWindow();
}

void CSplWnd::OnOK()
{
}

void CSplWnd::OnDestroy()
{
	WINDOWPLACEMENT winpl;

	winpl.length = sizeof(WINDOWPLACEMENT);
	GetWindowPlacement(&winpl);			// �_�C�A���O�\���ʒu�擾
	g_oSetData.SplWindow.left = winpl.rcNormalPosition.left;
	g_oSetData.SplWindow.top = winpl.rcNormalPosition.top;

	CDialog::OnDestroy();
}

void CSplWnd::PostNcDestroy()
{
	delete this;
	g_pSplWnd  = NULL;

	CDialog::PostNcDestroy();
}

void CSplWnd::ShowSpl(double fSpl)
{
	if (_finite(fSpl) && fSpl > -200)
		m_cSpl.Format("%3.1lf", fSpl);
	else
		m_cSpl = "";
}

void CSplWnd::Clear()
{
	m_cSpl = "";
}

BOOL CSplWnd::OnHelpInfo(HELPINFO* pHelpInfo)
{
	static const UINT aIDs[] = {
		IDCANCEL, IDH_SPL_CANCEL,
		IDC_SPL, IDH_SPL,
		0
	};

	DispContextHelp(pHelpInfo, aIDs);

	return TRUE;
}
