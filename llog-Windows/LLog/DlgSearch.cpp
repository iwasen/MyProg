// DlgSearch.cpp : 実装ファイル
//

#include "stdafx.h"
#include "LLog.h"
#include "DlgSearch.h"


// CDlgSearch ダイアログ

IMPLEMENT_DYNAMIC(CDlgSearch, CDialog)

CDlgSearch::CDlgSearch(CWnd* pParent /*=NULL*/)
	: CDialog(CDlgSearch::IDD, pParent)
{

}

void CDlgSearch::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
}


BEGIN_MESSAGE_MAP(CDlgSearch, CDialog)
	ON_BN_CLICKED(IDC_BUTTON_SEARCH_PHOTO, &CDlgSearch::OnBnClickedButtonSearchPhoto)
	ON_BN_CLICKED(IDC_BUTTON_SEARCH_GPS_LOGGER, &CDlgSearch::OnBnClickedButtonSearchGpsLogger)
	ON_BN_CLICKED(IDC_BUTTON_SEARCH_KEITAI_GPS, &CDlgSearch::OnBnClickedButtonSearchKeitaiGps)
END_MESSAGE_MAP()


// CDlgSearch メッセージ ハンドラ

void CDlgSearch::OnBnClickedButtonSearchPhoto()
{
	m_nSearchType = SEARCH_PHOTO;
	EndDialog(IDOK);
}

void CDlgSearch::OnBnClickedButtonSearchGpsLogger()
{
	m_nSearchType = SEARCH_GPS_LOGGER;
	EndDialog(IDOK);
}

void CDlgSearch::OnBnClickedButtonSearchKeitaiGps()
{
	m_nSearchType = SEARCH_KEITAI_GPS;
	EndDialog(IDOK);
}
