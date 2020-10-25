// DlgTrain.cpp : 実装ファイル
//

#include "stdafx.h"
#include "LLog.h"
#include "DlgTrain.h"


// CDlgTrain ダイアログ

IMPLEMENT_DYNAMIC(CDlgTrain, CDlgCommon)

CDlgTrain::CDlgTrain(CWnd* pParent /*=NULL*/)
	: CDlgCommon(CDlgTrain::IDD, pParent)
{
	m_bUpdate = FALSE;
}

void CDlgTrain::DoDataExchange(CDataExchange* pDX)
{
	CDlgCommon::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_EDIT_DATE, m_cEditDate);
	DDX_Control(pDX, IDC_LIST_TRAIN, m_cListTrain);
	DDX_Control(pDX, IDC_COMBO_FROM_LINE, m_cComboFromLine);
	DDX_Control(pDX, IDC_COMBO_FROM_STATION, m_cComboFromStation);
	DDX_Control(pDX, IDC_COMBO_TO_LINE, m_cComboToLine);
	DDX_Control(pDX, IDC_COMBO_TO_STATION, m_cComboToStation);
}


BEGIN_MESSAGE_MAP(CDlgTrain, CDlgCommon)
	ON_BN_CLICKED(IDC_BUTTON_ADD, &CDlgTrain::OnBnClickedButtonAdd)
	ON_BN_CLICKED(IDC_BUTTON_DELETE, &CDlgTrain::OnBnClickedButtonDelete)
	ON_LBN_SELCHANGE(IDC_LIST_TRAIN, &CDlgTrain::OnLbnSelchangeListTrain)
	ON_BN_CLICKED(IDC_BUTTON_UPDATE, &CDlgTrain::OnBnClickedButtonUpdate)
	ON_CBN_KILLFOCUS(IDC_COMBO_FROM_LINE, &CDlgTrain::OnCbnKillfocusComboFromLine)
END_MESSAGE_MAP()


// CDlgTrain メッセージ ハンドラ

BOOL CDlgTrain::OnInitDialog()
{
	CDlgCommon::OnInitDialog();

	m_cEditDate = m_oDate.Format("%Y/%m/%d");

	SetComboLine(m_cComboFromLine);
	SetComboLine(m_cComboToLine);
	SetComboStation(m_cComboFromStation);
	SetComboStation(m_cComboToStation);

	DispData();

	return TRUE;
}

void CDlgTrain::SetComboLine(CComboBox &oCombo)
{
	oCombo.AddString("京急");
	oCombo.AddString("JR");
	oCombo.AddString("東急");
	oCombo.AddString("都営地下鉄");
	oCombo.AddString("東京メトロ");
}

void CDlgTrain::SetComboStation(CComboBox &oCombo)
{
	oCombo.AddString("平和島");
	oCombo.AddString("大森町");
	oCombo.AddString("品川");
	oCombo.AddString("蒲田");
	oCombo.AddString("川崎");
	oCombo.AddString("横浜");
}

void CDlgTrain::DispData()
{
	CString sSql;
	CDbResult oDbResult;
	CString sText;

	sSql.Format("SELECT tr_seq_no,tr_from_line,tr_from_station,tr_to_line,tr_to_station FROM t_train WHERE tr_date=%s ORDER BY tr_seq_no", m_pDataBase->SqlDate(m_oDate));
	if (m_pDataBase->Select(sSql, oDbResult)) {
		m_cListTrain.ResetContent();
		int nRec =  oDbResult.GetCount();
		for (int i = 0; i < nRec; i++) {
			sText.Format("%s %s → %s %s", oDbResult.GetTextValue(i, "tr_from_line"), oDbResult.GetTextValue(i, "tr_from_station"), oDbResult.GetTextValue(i, "tr_to_line"), oDbResult.GetTextValue(i, "tr_to_station"));
			m_cListTrain.SetItemData(m_cListTrain.AddString(sText), oDbResult.GetIntValue(i, "tr_seq_no"));
		}
	}
}

void CDlgTrain::OnBnClickedButtonAdd()
{
	CString sFromLine;
	CString sFromStation;
	CString sToLine;
	CString sToStation;

	m_cComboFromLine.GetWindowText(sFromLine);
	m_cComboFromStation.GetWindowText(sFromStation);
	m_cComboToLine.GetWindowText(sToLine);
	m_cComboToStation.GetWindowText(sToStation);

	if (!sFromLine.IsEmpty() && !sFromStation.IsEmpty() && !sToLine.IsEmpty() && !sToStation.IsEmpty()) {
		CString sSql;
		sSql.Format("INSERT INTO t_train (tr_date,tr_from_line,tr_from_station,tr_to_line,tr_to_station) VALUES (%s,%s,%s,%s,%s)",
				m_pDataBase->SqlDate(m_oDate),
				m_pDataBase->SqlText(sFromLine),
				m_pDataBase->SqlText(sFromStation),
				m_pDataBase->SqlText(sToLine),
				m_pDataBase->SqlText(sToStation));
		m_pDataBase->Exec(sSql);
		m_bUpdate = TRUE;

		DispData();

		m_cComboFromLine.SetWindowText("");
		m_cComboFromStation.SetWindowText("");
		m_cComboToLine.SetWindowText("");
		m_cComboToStation.SetWindowText("");
	}
}

void CDlgTrain::OnBnClickedButtonDelete()
{
	int nIndex = m_cListTrain.GetCurSel();
	if (nIndex == LB_ERR)
		return;

	if (::AfxMessageBox("削除しますか？", MB_OKCANCEL | MB_ICONQUESTION) == IDCANCEL)
		return;

	CString sSql;
	sSql.Format("DELETE FROM t_train WHERE tr_seq_no=%d", m_cListTrain.GetItemData(nIndex));
	m_pDataBase->Exec(sSql);
	m_bUpdate = TRUE;

	DispData();

	m_cComboFromLine.SetWindowText("");
	m_cComboFromStation.SetWindowText("");
	m_cComboToLine.SetWindowText("");
	m_cComboToStation.SetWindowText("");
}

void CDlgTrain::OnCancel()
{
	EndDialog(m_bUpdate ? IDOK : IDCANCEL);
}

void CDlgTrain::OnLbnSelchangeListTrain()
{
	int nIndex = m_cListTrain.GetCurSel();
	if (nIndex != LB_ERR) {
		CString sSql;
		CDbResult oDbResult;
		sSql.Format("SELECT tr_from_line,tr_from_station,tr_to_line,tr_to_station FROM t_train WHERE tr_seq_no=%d", m_cListTrain.GetItemData(nIndex));
		if (m_pDataBase->Select(sSql, oDbResult)) {
			m_cComboFromLine.SetWindowText(oDbResult.GetTextValue(0, "tr_from_line"));
			m_cComboFromStation.SetWindowText(oDbResult.GetTextValue(0, "tr_from_station"));
			m_cComboToLine.SetWindowText(oDbResult.GetTextValue(0, "tr_to_line"));
			m_cComboToStation.SetWindowText(oDbResult.GetTextValue(0, "tr_to_station"));
		}
	}
}

void CDlgTrain::OnBnClickedButtonUpdate()
{
	int nIndex = m_cListTrain.GetCurSel();
	if (nIndex == LB_ERR)
		return;

	if (::AfxMessageBox("更新しますか？", MB_OKCANCEL | MB_ICONQUESTION) == IDCANCEL)
		return;

	CString sFromLine;
	CString sFromStation;
	CString sToLine;
	CString sToStation;

	m_cComboFromLine.GetWindowText(sFromLine);
	m_cComboFromStation.GetWindowText(sFromStation);
	m_cComboToLine.GetWindowText(sToLine);
	m_cComboToStation.GetWindowText(sToStation);

	if (!sFromLine.IsEmpty() && !sFromStation.IsEmpty() && !sToLine.IsEmpty() && !sToStation.IsEmpty()) {
		CString sSql;
		sSql.Format("UPDATE t_train SET tr_from_line=%s,tr_from_station=%s,tr_to_line=%s,tr_to_station=%s WHERE tr_seq_no=%d",
				m_pDataBase->SqlText(sFromLine),
				m_pDataBase->SqlText(sFromStation),
				m_pDataBase->SqlText(sToLine),
				m_pDataBase->SqlText(sToStation),
				m_cListTrain.GetItemData(nIndex));
		m_pDataBase->Exec(sSql);
		m_bUpdate = TRUE;

		DispData();
	}
}

void CDlgTrain::OnCbnKillfocusComboFromLine()
{
	CString sText;

	m_cComboToLine.GetWindowText(sText);
	if (sText.IsEmpty()) {
		m_cComboFromLine.GetWindowText(sText);
		m_cComboToLine.SetWindowText(sText);
	}
}
