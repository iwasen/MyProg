// DlgEvent.cpp : 実装ファイル
//

#include "stdafx.h"
#include "LLog.h"
#include "DlgEvent.h"


// CDlgEvent ダイアログ

IMPLEMENT_DYNAMIC(CDlgEvent, CDlgCommon)

CDlgEvent::CDlgEvent(CWnd* pParent /*=NULL*/)
	: CDlgCommon(CDlgEvent::IDD, pParent)
{

}

void CDlgEvent::DoDataExchange(CDataExchange* pDX)
{
	CDlgCommon::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_EDIT_DATE, m_cEditDate);
	DDX_Control(pDX, IDC_LIST_EVENT_FROM, m_cListEventFrom);
	DDX_Control(pDX, IDC_LIST_EVENT_TO, m_cListEventTo);
}


BEGIN_MESSAGE_MAP(CDlgEvent, CDlgCommon)
	ON_BN_CLICKED(IDC_BUTTON_ADD, &CDlgEvent::OnBnClickedButtonAdd)
	ON_BN_CLICKED(IDC_BUTTON_DELETE, &CDlgEvent::OnBnClickedButtonDelete)
	ON_LBN_DBLCLK(IDC_LIST_EVENT_FROM, &CDlgEvent::OnLbnDblclkListEventFrom)
	ON_LBN_DBLCLK(IDC_LIST_EVENT_TO, &CDlgEvent::OnLbnDblclkListEventTo)
END_MESSAGE_MAP()


// CDlgEvent メッセージ ハンドラ

BOOL CDlgEvent::OnInitDialog()
{
	CDlgCommon::OnInitDialog();

	m_cEditDate = m_oDate.Format("%Y/%m/%d");

	DispData();

	return TRUE;
}

void CDlgEvent::DispData()
{
	CString sSql;
	CDbResult oDbResult;

	sSql.Format("SELECT em_event_id,em_text,ev_event_id FROM m_event LEFT JOIN t_event ON ev_event_id=em_event_id AND ev_date=%s ORDER BY em_event_id", m_pDataBase->SqlDate(m_oDate));
	if (m_pDataBase->Select(sSql, oDbResult)) {
		m_cListEventTo.ResetContent();
		int nRec = oDbResult.GetCount();
		for (int i = 0; i < nRec; i++) {
			if (oDbResult.IsNull(i, "ev_event_id"))
				m_cListEventFrom.SetItemData(m_cListEventFrom.AddString(oDbResult.GetTextValue(i, "em_text")), oDbResult.GetIntValue(i, "em_event_id"));
			else
				m_cListEventTo.SetItemData(m_cListEventTo.AddString(oDbResult.GetTextValue(i, "em_text")), oDbResult.GetIntValue(i, "em_event_id"));
		}
	}
}

void CDlgEvent::OnBnClickedButtonAdd()
{
	int nIndex = m_cListEventFrom.GetCurSel();
	if (nIndex == LB_ERR)
		return;

	CString sEvent;
	m_cListEventFrom.GetText(nIndex, sEvent);
	int nEventId = m_cListEventFrom.GetItemData(nIndex);

	m_cListEventFrom.DeleteString(nIndex);
	m_cListEventTo.SetItemData(m_cListEventTo.AddString(sEvent), nEventId);
}

void CDlgEvent::OnBnClickedButtonDelete()
{
	int nIndex = m_cListEventTo.GetCurSel();
	if (nIndex == LB_ERR)
		return;

	CString sEvent;
	m_cListEventTo.GetText(nIndex, sEvent);
	int nEventId = m_cListEventTo.GetItemData(nIndex);

	m_cListEventTo.DeleteString(nIndex);
	m_cListEventFrom.SetItemData(m_cListEventFrom.AddString(sEvent), nEventId);
}

void CDlgEvent::OnOK()
{
	CString sSql;
	sSql.Format("DELETE FROM t_event WHERE ev_date=%s", m_pDataBase->SqlDate(m_oDate));
	m_pDataBase->Exec(sSql);

	int nCount = m_cListEventTo.GetCount();
	for (int i = 0; i < nCount; i++) {
		sSql.Format("INSERT INTO t_event (ev_date,ev_event_id) VALUES (%s,%d)",
				m_pDataBase->SqlDate(m_oDate),
				m_cListEventTo.GetItemData(i)),
		m_pDataBase->Exec(sSql);
	}

	CDlgCommon::OnOK();
}

void CDlgEvent::OnLbnDblclkListEventFrom()
{
	OnBnClickedButtonAdd();
}

void CDlgEvent::OnLbnDblclkListEventTo()
{
	OnBnClickedButtonDelete();
}
