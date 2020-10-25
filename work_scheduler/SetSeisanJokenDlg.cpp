// SetSeisanJokenDlg.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "workscheduler.h"
#include "SetSeisanJokenDlg.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CSetSeisanJokenDlg ダイアログ


CSetSeisanJokenDlg::CSetSeisanJokenDlg(UINT nID, CWnd* pParent /*=NULL*/)
	: CDialogEx(nID, pParent)
{
	//{{AFX_DATA_INIT(CSetSeisanJokenDlg)
		// メモ - ClassWizard はこの位置にマッピング用のマクロを追加または削除します。
	//}}AFX_DATA_INIT
}


void CSetSeisanJokenDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialogEx::DoDataExchange(pDX);
	//{{AFX_DATA_MAP(CSetSeisanJokenDlg)
		// メモ - ClassWizard はこの位置にマッピング用のマクロを追加または削除します。
	//}}AFX_DATA_MAP
	DDX_Control(pDX, IDC_COMBO_YEAR, m_cComboYear);
	DDX_Control(pDX, IDC_COMBO_MONTH, m_cComboMonth);
//	DDX_Control(pDX, IDC_SPREAD, m_cSpread);
	DDX_Control(pDX, IDC_LIST001, m_cList);
	DDX_Control(pDX, IDC_EDIT_KISHU1, m_aEditKishu[0]);
	DDX_Control(pDX, IDC_EDIT_KISHU2, m_aEditKishu[1]);
	DDX_Control(pDX, IDC_EDIT_KISHU3, m_aEditKishu[2]);
	DDX_Control(pDX, IDC_EDIT_KISHU4, m_aEditKishu[3]);
	DDX_Control(pDX, IDC_EDIT_KISHU5, m_aEditKishu[4]);
	DDX_Control(pDX, IDC_EDIT_DAISU1, m_aEditDaisu[0]);
	DDX_Control(pDX, IDC_EDIT_DAISU2, m_aEditDaisu[1]);
	DDX_Control(pDX, IDC_EDIT_DAISU3, m_aEditDaisu[2]);
	DDX_Control(pDX, IDC_EDIT_DAISU4, m_aEditDaisu[3]);
	DDX_Control(pDX, IDC_EDIT_DAISU5, m_aEditDaisu[4]);
}


BEGIN_MESSAGE_MAP(CSetSeisanJokenDlg, CDialogEx)
	//{{AFX_MSG_MAP(CSetSeisanJokenDlg)
	ON_WM_SIZE()
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CSetSeisanJokenDlg メッセージ ハンドラ

void CSetSeisanJokenDlg::SetYearCombo()
{
	int i;
	CStringArrayEx aYear;
	int nSize;

	nSize = g_pDataManager->GetSeisanDaisuNum();
	for (i = 0; i < nSize; i++) {
		SSeisanDaisu *pSeisanDaisu = g_pDataManager->GetSeisanDaisuData(i);
		aYear.Add(pSeisanDaisu->sYexxxonth.Left(4));
	}
	aYear.Unique();

	nSize = aYear.GetSize();
	for (i = 0; i < nSize; i++)
		m_cComboYear.AddString(aYear[i]);

	if (g_pDataManager->m_nYear != 0) {
		m_sYear.Format("%04d",g_pDataManager->m_nYear);
		m_cComboYear.SetWindowText(m_sYear);
	}
}

void CSetSeisanJokenDlg::SetMonthCombo()
{
	int i;
	CString sMonth;
	int nSize;

	m_cComboMonth.ResetContent();

	nSize = g_pDataManager->GetSeisanDaisuNum();
	for (i = 0; i < nSize; i++) {
		SSeisanDaisu *pSeisanDaisu = g_pDataManager->GetSeisanDaisuData(i);
// Modify ... ( CHANGE )
		if (m_sYear == pSeisanDaisu->sYexxxonth.Left(4)){
			int nIndex = m_cComboMonth.AddString(pSeisanDaisu->sYexxxonth.Mid(4));
			if( m_sMonth == pSeisanDaisu->sYexxxonth.Mid(4) )
				m_cComboMonth.SetCurSel( nIndex );
		}
// By Y.Itabashi (xxxxx) 2007.02.05
	}

// Modify ... ( ADD )
	if( m_cComboMonth.GetCurSel() == CB_ERR )
		m_sMonth.Empty();
// By Y.itabashi (xxxxx) 2007.02.05

	if (g_pDataManager->m_nMonth != 0) {
		m_sMonth.Format("%02d",g_pDataManager->m_nMonth);
		m_cComboMonth.SetWindowText(m_sMonth);
	}
}

void CSetSeisanJokenDlg::SetKishuName()
{
	int nSize = g_pDataManager->GetKishuNum();
	for (int nKishuId = 0; nKishuId < nSize; nKishuId++) {
		SKishu *pKishu = g_pDataManager->GetKishuData(nKishuId);
		m_aEditKishu[nKishuId] = pKishu->sKishuName;
	}

	for (; nKishuId < MAX_KISHU; nKishuId++) {
		m_aEditKishu[nKishuId].ShowWindow(SW_HIDE);
		m_aEditDaisu[nKishuId].ShowWindow(SW_HIDE);
	}
}

void CSetSeisanJokenDlg::SetSeisanDaisu()
{
	if (!m_sYear.IsEmpty() && !m_sMonth.IsEmpty()) {
		CString sKey;
		sKey.Format("%04d%02d", atoi(m_sYear), atoi(m_sMonth));
		SSeisanDaisu *pSeisanDaisu = g_pDataManager->GetSeisanDaisuData(sKey);
		if (pSeisanDaisu != NULL) {
			int nSize = g_pDataManager->GetKishuNum();
			for (int nKishuId = 0; nKishuId < nSize; nKishuId++)
				m_aEditDaisu[nKishuId] = (pSeisanDaisu != NULL) ? pSeisanDaisu->aSeisanDaisu[nKishuId] : 0;
		}
// Modify ... ( ADD )
	}else{
		int nSize = g_pDataManager->GetKishuNum();
		for (int nKishuId = 0; nKishuId < nSize; nKishuId++)
			m_aEditDaisu[nKishuId] = "";
// By Y.Itabashi (xxxxx) 2007.02.05
	}
}

BOOL CSetSeisanJokenDlg::CheckDataInputSub(SSpreadDataCheck aDataCheck[], int nDataCheck)
{
	static CDataCheck cDaisuCheck = {
		eDataTypeNumber, 6, 0, 0, 999999, NULL
	};
	static CDataCheck cYearCheck = {
		eDataTypeNumber, 4, 0, 1, 2999, NULL
	};
	static CDataCheck cMonthCheck = {
		eDataTypeNumber, 2, 0, 1, 12, NULL
	};
	CString sData;

	if (!EditDataCheck(cYearCheck, m_cComboYear, "年"))
		return FALSE;

	if (!EditDataCheck(cMonthCheck, m_cComboMonth, "月"))
		return FALSE;

	for (int i = 0; i < MAX_KISHU; i++) {
		if (!EditDataCheck(cDaisuCheck, m_aEditDaisu[i], "生産台数"))
			return FALSE;
	}

// Modify ... ( CAHNGE )
// Lixxxtrlに変更
	int nMaxRows = m_cList.GetItemCount();
	int nMaxCols = m_cList.GetItemCount();

	for (int nRow = 0; nRow < nMaxRows; nRow++) {
		for (int i = 0; i < nDataCheck; i++) {
			int nCol = aDataCheck[i].nCol-1;
			CDataCheck &cDataCheck = aDataCheck[i].cDataCheck;
			sData = m_cList.GetItemText(nRow, nCol);
			if (!cDataCheck.BlankCheck(sData)) {
				CString sSublineName;
				m_cList.GetValue(nRow, 0, sSublineName);
				CString sItemName;
				m_cList.GetValue(0, nCol, sItemName);
				sItemName.Replace("\n", "");
				m_cList.SelectChange( nRow );
				g_pSystem->DispMessage("E005001", sSublineName, sItemName);
				return FALSE;
			}
		}
	}

/*	int nMaxRows = m_cSpread.GetMaxRows();
	int nMaxCols = m_cSpread.GetMaxCols();

	for (int nRow = 1; nRow <= nMaxRows; nRow++) {
		for (int i = 0; i < nDataCheck; i++) {
			int nCol = aDataCheck[i].nCol;
			CDataCheck &cDataCheck = aDataCheck[i].cDataCheck;
			m_cSpread.GetValue(nRow, nCol, sData);
			if (!cDataCheck.BlankCheck(sData)) {
				CString sSublineName;
				m_cSpread.GetValue(nRow, 1, sSublineName);
				CString sItemName;
				m_cSpread.GetValue(0, nCol, sItemName);
				sItemName.Replace("\n", "");
				m_cSpread.SetActiveCell(nCol, nRow);
				g_pSystem->DispMessage("E005001", sSublineName, sItemName);
				return FALSE;
			}
		}
	}*/
// By Y.Itabashi (xxxxx) 2007.01.26

	return TRUE;
}

int CSetSeisanJokenDlg::GetSeisanDaisu(int nSublineId)
{
	int nSeisanDaisu = 0;
	SSubline *pSubline = g_pDataManager->GetSublineData(nSublineId);
	int nKishuNum = g_pDataManager->GetKishuNum();
	for (int nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
		if (pSubline->aSeisanKishuFlag[nKishuId])
			nSeisanDaisu += (int)m_aEditDaisu[nKishuId];
	}

	return nSeisanDaisu;
}

void CSetSeisanJokenDlg::SetIkoHanei(int nCol)
{
	CString sValue;
	CString chkValue;
// Modify ... ( CAHNGE )
// Lixxxtrlに変更
	int nMaxRows = m_cList.GetItemCount();
	for (int nRow = 0; nRow < nMaxRows; nRow++) {
		chkValue = m_cList.GetItemText(nRow, nCol);
		if (!chkValue.IsEmpty())
			sValue = m_cList.GetItemText(nRow, nCol);
		else
			m_cList.SetItemText(nRow, nCol, sValue);
	}
/*	int nMaxRows = m_cSpread.GetMaxRows();
	for (int nRow = 1; nRow <= nMaxRows; nRow++) {
		if (!m_cSpread.IsEmpty(nRow, nCol))
			m_cSpread.GetValue(nRow, nCol, sValue);
		else
			m_cSpread.SetValue(nRow, nCol, sValue);
	}*/
// By Y.Itabashi (xxxxx) 2007.01.26
}

void CSetSeisanJokenDlg::SaveYMDaisu()
{
	SSeisanDaisu cSeisanDaisu;

	cSeisanDaisu.sYexxxonth.Format("%04d%02d", atoi(m_sYear), atoi(m_sMonth));

	int nKishuNum = g_pDataManager->GetKishuNum();
	for (int nKishuId = 0; nKishuId < nKishuNum; nKishuId++)
		cSeisanDaisu.aSeisanDaisu.Add(m_aEditDaisu[nKishuId]);

	g_pDataManager->SetSeisanDaisu(cSeisanDaisu, TRUE);
}

// Enterキーでダイアログが閉じるのを抑止
void CSetSeisanJokenDlg::OnOK()
{
}

// WM_SIZEメッセージハンドラ
void CSetSeisanJokenDlg::OnSize(UINT nType, int cx, int cy) 
{
	CDialogEx::OnSize(nType, cx, cy);

	CWnd *pParentWnd = GetParent();
	if (pParentWnd != NULL) {
		switch (nType) {
		case SIZE_MINIMIZED:
			// 親ウィンドウを最小化
			pParentWnd->ShowWindow(SW_MINIMIZE);
			pParentWnd->EnableWindow(TRUE);
			break;
		case SIZE_RESTORED:
			// 親ウィンドウを無効化
			pParentWnd->EnableWindow(FALSE);
			EnableWindow(TRUE);
			break;
		}
	}
}
