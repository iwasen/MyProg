// OptFont.cpp : �C���v�������e�[�V���� �t�@�C��
//

#include "stdafx.h"
#include "MMLib.h"
#include "OptFont.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// COptFont �v���p�e�B �y�[�W

IMPLEMENT_DYNCREATE(COptFont, CPropertyPage)

COptFont::COptFont() : CPropertyPage(COptFont::IDD)
{
	m_iFontSize = -1;
	m_iViewFontSize = 0;
	m_sViewFontName = _T("");

	m_bChangeViewFont = FALSE;
}

COptFont::~COptFont()
{
}

void COptFont::DoDataExchange(CDataExchange* pDX)
{
	CPropertyPage::DoDataExchange(pDX);
	DDX_Radio(pDX, IDC_NORMAL_FONT, m_iFontSize);
	DDX_Text(pDX, IDC_VIEW_FONT_SIZE, m_iViewFontSize);
	DDX_Text(pDX, IDC_VIEW_FONT_NAME, m_sViewFontName);
}


BEGIN_MESSAGE_MAP(COptFont, CPropertyPage)
	ON_BN_CLICKED(IDC_VIEW_FONT_CHANGE, OnViewFontChange)
	ON_BN_CLICKED(IDC_DEFAULT, OnDefault)
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// COptFont ���b�Z�[�W �n���h��

BOOL COptFont::OnInitDialog() 
{
	m_sViewFontName = g_ViewFontName;
	m_iViewFontSize = g_nViewFontSize / 10;

	m_iFontSize = g_nFontSize;

	CPropertyPage::OnInitDialog();
	
	return TRUE;
}

void COptFont::OnOK() 
{
	CWinApp *pWinApp = AfxGetApp();

	UpdateData(TRUE);

	if (m_bChangeViewFont) {
		g_ViewFontName = m_sViewFontName;
		pWinApp->WriteProfileString(g_SectionOptions, "ViewFontName", g_ViewFontName);

		g_nViewFontSize = m_iViewFontSize * 10;
		pWinApp->WriteProfileInt(g_SectionOptions, "ViewFontSize", g_nViewFontSize);
	}

	g_nFontSize = m_iFontSize;
	pWinApp->WriteProfileInt(g_SectionOptions, "FontSize", m_iFontSize);
	
	CPropertyPage::OnOK();
}

void COptFont::OnViewFontChange() 
{
	LOGFONT	lf;
	char	szStyle[LF_FACESIZE];

	memset(&lf, 0, sizeof(lf));

	CFontDialog	fontDialog(&lf, 0, NULL, this);
	fontDialog.m_cf.lStructSize = sizeof(CHOOSEFONT);
	fontDialog.m_cf.hwndOwner = m_hWnd;
	fontDialog.m_cf.lpLogFont = &lf;
	fontDialog.m_cf.lpszStyle = szStyle;
	fontDialog.m_cf.Flags |= CF_INITTOLOGFONTSTRUCT | CF_SCREENFONTS;

	lstrcpy(fontDialog.m_cf.lpLogFont->lfFaceName, g_ViewFontName);
	HDC hDC = ::GetDC(m_hWnd);
	fontDialog.m_cf.lpLogFont->lfHeight = -MulDiv(g_nViewFontSize, GetDeviceCaps(hDC, LOGPIXELSY), 720);
	::ReleaseDC(m_hWnd, hDC);

	if (fontDialog.DoModal() == IDOK) {
		m_sViewFontName = fontDialog.GetFaceName();
		m_iViewFontSize = fontDialog.GetSize() / 10;
		m_bChangeViewFont = TRUE;

		UpdateData(FALSE);
	}
}

void COptFont::OnDefault() 
{
	m_sViewFontName.LoadString(IDS_DEFAULT_FONT);
	m_iViewFontSize = 9;
	m_iFontSize = 0;
	m_bChangeViewFont = TRUE;

	UpdateData(FALSE);
}
