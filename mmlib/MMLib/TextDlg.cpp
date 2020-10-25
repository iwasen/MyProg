// TextDlg.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "MMLib.h"
#include "TextDlg.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CTextDlg ダイアログ


CTextDlg::CTextDlg(CWnd* pParent /*=NULL*/)
	: CDialog(CTextDlg::IDD, pParent)
{
	m_sText = _T("");

	CWinApp *pWinApp = AfxGetApp();
	m_FontName = pWinApp->GetProfileString(g_SectionSettings, "FontName", GetString(IDS_DEFAULT_FONT));
	m_nFontSize = pWinApp->GetProfileInt(g_SectionSettings, "FontSize", 90);
	m_Font.CreatePointFont(m_nFontSize, m_FontName);
}


void CTextDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_TEXT, m_cText);
	DDX_Text(pDX, IDC_TEXT, m_sText);
}


BEGIN_MESSAGE_MAP(CTextDlg, CDialog)
	ON_COMMAND(IDM_FONT, OnFont)
	ON_WM_SIZE()
	ON_COMMAND(IDM_CLOSE_TEXT, OnCloseText)
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CTextDlg メッセージ ハンドラ

BOOL CTextDlg::OnInitDialog() 
{
	CDialog::OnInitDialog();

	m_cText.SetFont(&m_Font);
	m_cText.SetFocus();
	m_cText.SetSel(-1, 0);

	return FALSE;
}

void CTextDlg::OnFont() 
{
	CWinApp *pWinApp = AfxGetApp();
	LOGFONT	lf;
	char	szStyle[LF_FACESIZE];

	memset(&lf, 0, sizeof(lf));

	CFontDialog	fontDialog(&lf, 0, NULL, this);
	fontDialog.m_cf.lStructSize = sizeof(CHOOSEFONT);
	fontDialog.m_cf.hwndOwner = m_hWnd;
	fontDialog.m_cf.iPointSize = m_nFontSize;
	fontDialog.m_cf.lpLogFont = &lf;
	fontDialog.m_cf.lpszStyle = szStyle;
	fontDialog.m_cf.Flags |= CF_INITTOLOGFONTSTRUCT | CF_SCREENFONTS;

	lstrcpy(lf.lfFaceName, m_FontName);
	lf.lfHeight = -MulDiv(m_nFontSize, GetDC()->GetDeviceCaps(LOGPIXELSY), 720);
	lf.lfCharSet = SHIFTJIS_CHARSET;

	if (fontDialog.DoModal()) {
		m_Font.DeleteObject();
		m_Font.CreateFontIndirect(&lf);
		m_cText.SetFont(&m_Font, TRUE);

		m_FontName = fontDialog.m_cf.lpLogFont->lfFaceName;
		m_nFontSize = fontDialog.m_cf.iPointSize;
		pWinApp->WriteProfileString(g_SectionSettings, "FontName", m_FontName);
		pWinApp->WriteProfileInt(g_SectionSettings, "FontSize", m_nFontSize);
	}
}

void CTextDlg::OnSize(UINT nType, int cx, int cy) 
{
	CDialog::OnSize(nType, cx, cy);

	if (m_cText.m_hWnd != NULL) {
		CRect rect;
		GetClientRect(rect);
		m_cText.SetWindowPos(NULL, 0, 0, rect.Width(), rect.Height(), SWP_NOZORDER | SWP_NOMOVE);
	}
}

void CTextDlg::OnCloseText() 
{
	UpdateData(TRUE);

	EndDialog(IDOK);
}
