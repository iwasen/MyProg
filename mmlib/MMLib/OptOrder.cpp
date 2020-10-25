// OptOrder.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "MMLib.h"
#include "OptOrder.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

#define DEFAULT_ORDER	"0123456"

/////////////////////////////////////////////////////////////////////////////
// COptOrder プロパティ ページ

IMPLEMENT_DYNCREATE(COptOrder, CPropertyPage)

COptOrder::COptOrder() : CPropertyPage(COptOrder::IDD)
{
	m_iViewText = -1;
	m_iSmallSize = -1;

	m_bChangeOrder = FALSE;
	m_bChangeText = FALSE;
	m_bChangeSmallSize = FALSE;
}

COptOrder::~COptOrder()
{
}

void COptOrder::DoDataExchange(CDataExchange* pDX)
{
	CPropertyPage::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_ORDER_LIST, m_cOrderList);
	DDX_Radio(pDX, IDC_TITLE_CODE, m_iViewText);
	DDX_Radio(pDX, IDC_SMALL_NORMAL, m_iSmallSize);
}


BEGIN_MESSAGE_MAP(COptOrder, CPropertyPage)
	ON_BN_CLICKED(IDC_UP, OnUp)
	ON_BN_CLICKED(IDC_DOWN, OnDown)
	ON_BN_CLICKED(IDC_DEFAULT, OnDefault)
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// COptOrder メッセージ ハンドラ

BOOL COptOrder::OnInitDialog() 
{
	m_nViewText = AfxGetApp()->GetProfileInt(g_SectionOptions, "ViewText", 0);
	m_iViewText = m_nViewText;

	m_nSmallSize = AfxGetApp()->GetProfileInt(g_SectionOptions, "SmallSize", 0);
	m_iSmallSize = m_nSmallSize;

	CPropertyPage::OnInitDialog();

	m_Order = AfxGetApp()->GetProfileString(g_SectionOptions, "ViewOrder", DEFAULT_ORDER);
	SetListBox(m_Order);

	return TRUE;
}

void COptOrder::OnUp() 
{
	int nIndex;
	CString str;
	DWORD data;

	if ((nIndex = m_cOrderList.GetCurSel()) == LB_ERR)
		return;

	if (nIndex == 0)
		return;

	m_cOrderList.GetText(nIndex, str);
	data = (DWORD)m_cOrderList.GetItemData(nIndex);
	m_cOrderList.DeleteString(nIndex);

	m_cOrderList.InsertString(--nIndex, str);
	m_cOrderList.SetItemData(nIndex, data);

	m_cOrderList.SetCurSel(nIndex);
}

void COptOrder::OnDown() 
{
	int nIndex;
	CString str;
	DWORD data;

	if ((nIndex = m_cOrderList.GetCurSel()) == LB_ERR)
		return;

	if (nIndex == m_cOrderList.GetCount() - 1)
		return;

	m_cOrderList.GetText(nIndex, str);
	data = (DWORD)m_cOrderList.GetItemData(nIndex);
	m_cOrderList.DeleteString(nIndex);

	m_cOrderList.InsertString(++nIndex, str);
	m_cOrderList.SetItemData(nIndex, data);

	m_cOrderList.SetCurSel(nIndex);
}

void COptOrder::OnOK() 
{
	int nCount;
	int i;

	UpdateData(TRUE);

	nCount = m_cOrderList.GetCount();
	CString str(' ', nCount);

	for (i = 0; i < nCount; i++)
		str.SetAt(i, (char)(m_cOrderList.GetItemData(i) + '0'));

	if (str != m_Order) {
		AfxGetApp()->WriteProfileString(g_SectionOptions, "ViewOrder", str);
		m_bChangeOrder = TRUE;
	}

	if (m_iViewText != m_nViewText) {
		AfxGetApp()->WriteProfileInt(g_SectionOptions, "ViewText", m_iViewText);
		m_bChangeText = TRUE;
	}

	if (m_iSmallSize != m_nSmallSize) {
		AfxGetApp()->WriteProfileInt(g_SectionOptions, "SmallSize", m_iSmallSize);
		m_bChangeSmallSize = TRUE;
	}

	CPropertyPage::OnOK();
}

void COptOrder::OnDefault() 
{
	SetListBox(DEFAULT_ORDER);

	m_iViewText = 0;
	m_iSmallSize = 0;
	UpdateData(FALSE);
}

void COptOrder::SetListBox(LPCTSTR pOrder)
{
	static UINT item[] = {
		IDS_NAME, IDS_CODE, IDS_CREATE_DATE, IDS_UPDATE_DATE, IDS_CREATOR, IDS_REMARK, IDS_KEYWORD
	};
	int i, nIndex;

	m_cOrderList.ResetContent();
	for (i = 0; i < m_Order.GetLength(); i++) {
		nIndex = pOrder[i] - '0';
		m_cOrderList.InsertString(i, GetString(item[nIndex]));
		m_cOrderList.SetItemData(i, nIndex);
	}
}
