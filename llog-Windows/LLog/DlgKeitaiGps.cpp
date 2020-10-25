// DlgKeitaiGps.cpp : 実装ファイル
//

#include "stdafx.h"
#include "LLog.h"
#include "DlgKeitaiGps.h"
#include "afxinet.h"


// CDlgKeitaiGps ダイアログ

IMPLEMENT_DYNAMIC(CDlgKeitaiGps, CDlgCommon)

CDlgKeitaiGps::CDlgKeitaiGps(CWnd* pParent /*=NULL*/)
	: CDlgCommon(CDlgKeitaiGps::IDD, pParent)
{
	m_bUpdate = FALSE;
}

void CDlgKeitaiGps::DoDataExchange(CDataExchange* pDX)
{
	CDlgCommon::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_LIST_KEITAI_GPS, m_cListKeitaiGps);
	DDX_Control(pDX, IDC_EDIT_COMMENT, m_cEditComment);
	DDX_Control(pDX, IDC_STATIC_MAP, m_cStaticMap);
}


BEGIN_MESSAGE_MAP(CDlgKeitaiGps, CDlgCommon)
	ON_BN_CLICKED(IDC_BUTTON_DELETE, &CDlgKeitaiGps::OnBnClickedButtonDelete)
	ON_BN_CLICKED(IDC_BUTTON_SET, &CDlgKeitaiGps::OnBnClickedButtonSet)
	ON_NOTIFY(LVN_ITEMCHANGED, IDC_LIST_KEITAI_GPS, &CDlgKeitaiGps::OnLvnItemchangedListKeitaiGps)
	ON_BN_CLICKED(IDC_BUTTON_DESTINATION, &CDlgKeitaiGps::OnBnClickedButtonDestination)
	ON_NOTIFY(NM_DBLCLK, IDC_LIST_KEITAI_GPS, &CDlgKeitaiGps::OnNMDblclkListKeitaiGps)
END_MESSAGE_MAP()


// CDlgKeitaiGps メッセージ ハンドラ

BOOL CDlgKeitaiGps::OnInitDialog()
{
	CDlgCommon::OnInitDialog();

	//m_cStaticMap.SetWindowPos(NULL, 0, 0, 522, 392, SWP_NOMOVE | SWP_NOZORDER);

	m_cListKeitaiGps.SetExtendedStyle(LVS_EX_FULLROWSELECT);
	SetListHeader();
	DispData();

	return TRUE;
}

void CDlgKeitaiGps::SetListHeader()
{
	struct LIxxxOLUMN {
		char *pText;
		int fmt;
		int width;
	} tColumnData[] = {
		{"時刻", LVCFMT_LEFT, 50},
		{"住所", LVCFMT_LEFT, 200},
		{"コメント", LVCFMT_LEFT, 250}
	}, *pColumn;
	int i;
	int nColumn;
	LV_COLUMN lvc;

	// カラムデータ
	pColumn = tColumnData;
	nColumn  = sizeof(tColumnData) / sizeof(LIxxxOLUMN);

	// カラムを設定
	for (i = 0; i < nColumn; i++) {
		lvc.mask = LVCF_FMT | LVCF_WIDTH | LVCF_TEXT;
		lvc.fmt = pColumn->fmt;
		lvc.pszText = pColumn->pText;
		lvc.cx = pColumn->width;
		m_cListKeitaiGps.InsertColumn(i, &lvc);
		pColumn++;
	}
}

void CDlgKeitaiGps::DispData()
{
	CString sSql;
	CDbResult oDbResult;

	sSql.Format("SELECT kg_seq_no,kg_datetime,kg_address,kg_comment FROM t_keitai_gps WHERE kg_date=%s ORDER BY kg_seq_no", m_pDataBase->SqlDate(m_oDate));
	if (m_pDataBase->Select(sSql, oDbResult)) {
		m_cListKeitaiGps.DeleteAllItems();
		int nRec = oDbResult.GetCount();
		for (int i = 0; i < nRec; i++) {
			CString sAddress = oDbResult.GetTextValue(i, "kg_address");
			if (sAddress.GetLength() == 0)
				sAddress = "不明";
			m_cListKeitaiGps.InsertItem(i, CString(oDbResult.GetTextValue(i, "kg_datetime")).Right(8));
			m_cListKeitaiGps.SetItemText(i, 1, sAddress);
			m_cListKeitaiGps.SetItemText(i, 2, oDbResult.GetTextValue(i, "kg_comment"));
			m_cListKeitaiGps.SetItemData(i, oDbResult.GetIntValue(i, "kg_seq_no"));
		}
	}
}

void CDlgKeitaiGps::OnBnClickedButtonDelete()
{
	POSITION nPos = m_cListKeitaiGps.GetFirstSelectedItemPosition();
	if (nPos == NULL)
		return;

	if (::AfxMessageBox("削除しますか？", MB_OKCANCEL | MB_ICONQUESTION) == IDCANCEL)
		return;

	int nIndex = m_cListKeitaiGps.GetNextSelectedItem(nPos);

	CString sSql;
	sSql.Format("DELETE FROM t_keitai_gps WHERE kg_seq_no=%d", m_cListKeitaiGps.GetItemData(nIndex));
	m_pDataBase->Exec(sSql);
	m_bUpdate = TRUE;

	DispData();
}

void CDlgKeitaiGps::OnBnClickedButtonSet()
{
	POSITION nPos = m_cListKeitaiGps.GetFirstSelectedItemPosition();
	if (nPos == NULL)
		return;

	int nIndex = m_cListKeitaiGps.GetNextSelectedItem(nPos);

	CString sSql;
	sSql.Format("UPDATE t_keitai_gps SET kg_comment=%s WHERE kg_seq_no=%d", m_pDataBase->SqlText((CString)m_cEditComment), m_cListKeitaiGps.GetItemData(nIndex));
	m_pDataBase->Exec(sSql);
	m_bUpdate = TRUE;

	DispData();
}

void CDlgKeitaiGps::OnCancel()
{
	EndDialog(m_bUpdate ? IDOK : IDCANCEL);
}

void CDlgKeitaiGps::OnLvnItemchangedListKeitaiGps(NMHDR *pNMHDR, LRESULT *pResult)
{
	LPNMLISTVIEW pNMLV = reinterpret_cast<LPNMLISTVIEW>(pNMHDR);

	if (pNMLV->uNewState & LVIS_SELECTED) {
		CString sSql;
		CDbResult oDbResult;

		sSql.Format("SELECT kg_comment,kg_point[0] AS kg_lat,kg_point[1] AS kg_lon FROM t_keitai_gps WHERE kg_seq_no=%d", m_cListKeitaiGps.GetItemData(pNMLV->iItem));
		if (m_pDataBase->Select(sSql, oDbResult)) {
			if (oDbResult.GetCount() > 0) {
				m_cEditComment = oDbResult.GetTextValue(0, "kg_comment");
				m_cStaticMap.DispMap(oDbResult.GetFloatValue(0, "kg_lat"), oDbResult.GetFloatValue(0, "kg_lon"), NULL);
			}
		}
	}

	*pResult = 0;
}

void CDlgKeitaiGps::OnBnClickedButtonDestination()
{
	CString sUrl;
	sUrl.Format("%s/kg_destination.php?date=%s", URL_LLOG, m_oDate.Format("%Y-%m-%d"));
	::ShellExecute(NULL, "open", sUrl, NULL, NULL, SW_SHOWNORMAL);
}

void CDlgKeitaiGps::OnNMDblclkListKeitaiGps(NMHDR *pNMHDR, LRESULT *pResult)
{
	int nItem = m_cListKeitaiGps.GetNextItem(-1, LVNI_SELECTED);
	if (nItem != -1) {
		CString sUrl;
		sUrl.Format("%s/kg_gmap.php?seq_no=%d", URL_LLOG, m_cListKeitaiGps.GetItemData(nItem));
		::ShellExecute(NULL, "open", sUrl, NULL, NULL, SW_SHOWNORMAL);
	}

	*pResult = 0;
}
