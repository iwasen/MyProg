// SetKyotsuKoteiDlg.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "workscheduler.h"

#include "MenuDispCtrl.h"

#include "SetKyotsuKoteiDlg001.h"
#include "SetKyotsuKoteiDlg.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

// Modify ... ( CHANGE )
// Lixxxtrlに変更
// 共通工程スプレッドカラム位置
#define	COL_KYOTSU_KOTEI		0
#define	COL_KYOTSU_YOSOSAGYOGUN	1
#define	COL_KYOTSU_JIDOSHUDO	2
#define	COL_KYOTSU_GROUPNO		3

/*enum EKyotsuKoteiList {
	eKyotsuKoteiListKoteiName = 1,
	eKyotsuKoteiListYosoSagyogunName = 2,
	eKyotsuKoteiListJidoShudo = 3,
	eKyotsuKoteiListGroupNo = 4
};*/

#define	COLOR_TEXT		RGB(0x00, 0x00, 0x00)
#define	COLOR_ERRDATA	RGB(0xff, 0x00, 0x00)
#define	COLOR_OKDATA	RGB(0xff, 0xff, 0xff)
#define	COLOR_CHECKNON	RGB(0xff, 0xff, 0xff)
// By Y.Itabashi (xxxxx) 2007.01.26

/////////////////////////////////////////////////////////////////////////////
// CSetKyotsuKoteiDlg ダイアログ


CSetKyotsuKoteiDlg::CSetKyotsuKoteiDlg(CWnd* pParent /*=NULL*/)
	: CDialogEx(CSetKyotsuKoteiDlg::IDD, pParent)
{
	//{{AFX_DATA_INIT(CSetKyotsuKoteiDlg)
		// メモ - ClassWizard はこの位置にマッピング用のマクロを追加または削除します。
	//}}AFX_DATA_INIT
}


void CSetKyotsuKoteiDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialogEx::DoDataExchange(pDX);
	//{{AFX_DATA_MAP(CSetKyotsuKoteiDlg)
	DDX_Control(pDX, IDC_COMBO_SUBLINE, m_cComboSubline);
//	DDX_Control(pDX, IDC_SPREAD, m_cSpread);
	DDX_Control(pDX, IDC_LIST001, m_cList);
	//}}AFX_DATA_MAP
}


BEGIN_MESSAGE_MAP(CSetKyotsuKoteiDlg, CDialogEx)
	//{{AFX_MSG_MAP(CSetKyotsuKoteiDlg)
	ON_CBN_SELCHANGE(IDC_COMBO_SUBLINE, OnSelchangeComboSubline)
	ON_COMMAND(IDM_KYOTSU_KOTEI_ZEN_SETTEI, OnKyotsuKoteiZenSettei)
	ON_COMMAND(IDM_KYOTSU_KOTEI_ZEN_KAIJO, OnKyotsuKoteiZenKaijo)
	ON_COMMAND(IDM_KYOTSU_KOTEI_KAIJO, OnKyotsuKoteiKaijo)
	ON_WM_SIZE()
	ON_NOTIFY(NM_RCLICK, IDC_LIST001, OnRclickList001)
	//}}AFX_MSG_MAP
	ON_MESSAGE(WM_USER + 0x0503, OnChangeCombo)
	ON_MESSAGE(WM_USER + 0x0502, CreateComboList)
END_MESSAGE_MAP()

BEGIN_EVENTSINK_MAP(CSetKyotsuKoteiDlg, CDialogEx)
    //{{AFX_EVENTSINK_MAP(CSetKyotsuKoteiDlg)
//	ON_EVENT(CSetKyotsuKoteiDlg, IDC_SPREAD, 22 /* RightClick */, OnRightClickSpread, VTS_I2 VTS_I4 VTS_I4 VTS_I4 VTS_I4)
//	ON_EVENT(CSetKyotsuKoteiDlg, IDC_SPREAD, 4 /* Change */, OnChangeSpread, VTS_I4 VTS_I4)
	//}}AFX_EVENTSINK_MAP
END_EVENTSINK_MAP()

/////////////////////////////////////////////////////////////////////////////
// CSetKyotsuKoteiDlg メッセージ ハンドラ

BOOL CSetKyotsuKoteiDlg::OnInitDialog() 
{
	CDialogEx::OnInitDialog();

// Modify ... ( ADD )
	g_pSystem->m_cMenuDisp.SetMenuText( this, Menu_SetKyotsuKoteiDlg );
// By Y.Itabashi (xxxxx) 2007.01.24

	// サブライン表示
	int nSublineNum = g_pDataManager->GetSublineNum();
	for (int nSublineId = 0; nSublineId < nSublineNum; nSublineId++) {
		SSubline *pSubline = g_pDataManager->GetSublineData(nSublineId);
		m_cComboSubline.SetItemData(m_cComboSubline.AddString(pSubline->sSublineName), nSublineId);
	}

// Modify ... ( CAHNGE )
// Lixxxtrlに変更
	// スプレッドに機種ごとの列を追加
	int nKishuNum = g_pDataManager->GetKishuNum();
	for (int i = 0; i < nKishuNum; i++) {
		SKishu *pKishu = g_pDataManager->GetKishuData(i);
		sprintf( Menu_SetKyotsuKoteiDlg[List_SetKyotsuKoteiDlg[i+COL_KYOTSU_GROUPNO].nHeaderNo].str1, "%s", pKishu->sKishuName );
	}

	SetLixxxolumn( m_cList, Menu_SetKyotsuKoteiDlg, List_SetKyotsuKoteiDlg, eSELECTTYPE_MULTI );

	m_cList.DeleteAllItems();

/*	int nKishuNum = g_pDataManager->GetKishuNum();
	m_cSpread.SetMaxRows(0);
	m_cSpread.SetMaxCols(eKyotsuKoteiListGroupNo - 1 + nKishuNum);
	for (int i = 0; i < nKishuNum; i++) {
		SKishu *pKishu = g_pDataManager->GetKishuData(i);

		m_cSpread.SetValue(0, eKyotsuKoteiListGroupNo + i, pKishu->sKishuName);

		m_cSpread.SetRow(-1);
		m_cSpread.SetCol(eKyotsuKoteiListGroupNo + i);
		m_cSpread.SetTypeHAlign(1);

		m_cSpread.SetCellType(8);
	}*/
// By Y.Itabashi (xxxxx) 2007.01.26

	// コントロールの位置を調整
//	SetCtlPosition(IDC_SPREAD, 0, 0, 1, 1);
	SetCtlPosition(IDC_LIST001, 0, 0, 1, 1);
	SetCtlPosition(IDOK, 1, 1, 1, 1);
	SetCtlPosition(IDCANCEL, 1, 1, 1, 1);

	return TRUE;
}

// サブライン選択
void CSetKyotsuKoteiDlg::OnSelchangeComboSubline() 
{
	int nIndex = m_cComboSubline.GetCurSel();
	if (nIndex != -1) {
		m_nSublineId = m_cComboSubline.GetItemData(nIndex);

// Modify ... ( CAHNGE )
// Lixxxtrlに変更
		SetList();
//		SetSpread();
// By Y.Itabashi (xxxxx) 2007.01.26
	}
}

// Modify ... ( CAHNGE )
// Lixxxtrlに変更
// スプレッドで右クリック
/*void CSetKyotsuKoteiDlg::OnRightClickSpread(short ClickType, long Col, long Row, long MouseX, long MouseY) 
{
	if (Row > 0) {
		m_cSpread.SetActiveCell(Col, Row);

		// クリックされた行,列を保存
		m_nRightClickRow = Row;
		m_nRightClickCol = Col;

		// クリックされた位置にポップアップメニューを表示
		CPoint point;
		::GetCursorPos(&point);
		CMenu cMenu;
		cMenu.LoadMenu(IDR_KYOTSU_KOTEI);
		CMenu* pPopup = cMenu.GetSubMenu(0);

// Modify ... ( ADD )
		g_pSystem->m_cMenuDisp.SetMenuText( (CWnd *)pPopup, Menu_SetKyotsuKoteiPopup );
// By Y.Itabashi (xxxxx) 2007.01.26

		if (Col < eKyotsuKoteiListGroupNo)
			pPopup->EnableMenuItem(IDM_KYOTSU_KOTEI_KAIJO, MF_GRAYED);

		pPopup->TrackPopupMenu(TPM_LEFTALIGN | TPM_RIGHTBUTTON, point.x, point.y, this);
	}
}

// スプレッドの内容を変更
void CSetKyotsuKoteiDlg::OnChangeSpread(long Col, long Row) 
{
	SSubline *pSubline = g_pDataManager->GetSublineData(m_nSublineId);
	int nYosoSagyogunId = m_cSpread.GetRowItemData(Row);
	int nKishuId = Col - eKyotsuKoteiListGroupNo;
	m_cSpread.GetValue(Row, Col, pSubline->aYosoSagyogun[nYosoSagyogunId].aKishuSagyo[nKishuId].nGroupNo);

	SetGroupNo();
}*/
// By Y.Itabashi (xxxxx) 2007.01.26

// 共通工程全設定
void CSetKyotsuKoteiDlg::OnKyotsuKoteiZenSettei() 
{
	SSubline *pSubline = g_pDataManager->GetSublineData(m_nSublineId);
// Modify ... ( CAHNGE )
// 複数行選択対応
// Lixxxtrlに変更
	CYosoSagyogunArray &aYosoSagyogun = pSubline->aYosoSagyogun;
	int nKishuNum = g_pDataManager->GetKishuNum();
	int nGroupNo = 0;
	int nKishuId;
	CArrayEx2 <int, int> nSetGroupNo;

	// 連続した選択か確認
	POSITION pos = m_cList.GetFirstSelectedItemPosition();
	int nRow;
	int bRow = m_cList.GetNextSelectedItem( pos );
	while( pos ){
		nRow = m_cList.GetNextSelectedItem( pos );
		if( abs(bRow - nRow) != 1 )
			return;				// 不連続なものは設定しない
		bRow = nRow;
	}
	
	// 同一工程内かのチェック
	CIntArrayEx aYosoSagyogunId;
	nSetGroupNo.RemoveAll();
	pos = m_cList.GetFirstSelectedItemPosition();
	int nYosoSagyogunId;
	CString bKoteiName = "";
	CString sKoteiName = "";
// Modify ... ( ADD )
	int nSagyoshaId = -1;
// By Y.Itabashi (xxxxx) 2007.03.07
	while( pos ){
		nRow = m_cList.GetNextSelectedItem( pos );
		nYosoSagyogunId = m_cList.GetItemRowData(nRow);
		sKoteiName = aYosoSagyogun[nYosoSagyogunId].sKoteiName;

		if( !bKoteiName.IsEmpty() ){
			if( bKoteiName.Compare( sKoteiName ) != 0 ){
				nSetGroupNo.RemoveAll();
				return;				// 同一工程内でないものは設定しない
			}
		}
		bKoteiName = sKoteiName;

		// 変更するグループ番号を取得
		for (nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
			if (aYosoSagyogun[nYosoSagyogunId].aKishuSagyo[nKishuId].nGroupNo != 0) {
				nSetGroupNo.Add( aYosoSagyogun[nYosoSagyogunId].aKishuSagyo[nKishuId].nGroupNo );

				// 最初のグループ番号を取得
				if( nGroupNo == 0 )
					nGroupNo = aYosoSagyogun[nYosoSagyogunId].aKishuSagyo[nKishuId].nGroupNo;

// Modify ... ( ADD )
				if( nSagyoshaId == -1 )
					nSagyoshaId = aYosoSagyogun[nYosoSagyogunId].aKishuSagyo[nKishuId].nSagyoshaId;
				else if( nSagyoshaId != aYosoSagyogun[nYosoSagyogunId].aKishuSagyo[nKishuId].nSagyoshaId ){
					g_pSystem->DispMessage("E007005");
					return;
				}
// By Y.Itabashi (xxxxx) 2007.03.07
			}
		}
		aYosoSagyogunId.Add(nYosoSagyogunId);
	}

	// 全機種で作業順が連続しているかチェック
	for (nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
		BOOL bFind = FALSE, bEnd = FALSE;
		CSagyojunArray aSagyojun;
		g_pDataManager->GetSagyojun(m_nSublineId, nKishuId, aSagyojun);
		int nSize = aSagyojun.GetSize();
		for (int i = 0; i < nSize; i++) {
			if (aYosoSagyogunId.Find(aSagyojun[i].nYosoSagyogunId) != -1) {
				if (bEnd) {
					g_pSystem->DispMessage("E007003");
					return;
				} else
					bFind = TRUE;
			} else {
				if (bFind)
					bEnd = TRUE;
			}
		}
	}

	if (nGroupNo == 0)
		nGroupNo = g_pDataManager->GetNewGroupNo(m_nSublineId);

	// 取得したグループ番号を全て同一の工程番号にする
	int nMaxRows = m_cList.GetItemCount();
	for (nRow = 0; nRow < nMaxRows; nRow++) {
		int nYosoSagyogunId = m_cList.GetItemRowData(nRow);

		for (int nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {

			int chkGroupNo = aYosoSagyogun[nYosoSagyogunId].aKishuSagyo[nKishuId].nGroupNo;
			for( int i = 0; i < nSetGroupNo.GetSize(); i++ ){
				if( nSetGroupNo.GetAt(i) == chkGroupNo )
					aYosoSagyogun[nYosoSagyogunId].aKishuSagyo[nKishuId].nGroupNo = nGroupNo;
			}
		}
	}
/*	int nYosoSagyogunId = m_cList.GetItemRowData(m_nRightClickRow);
//	int nYosoSagyogunId = m_cSpread.GetRowItemData(m_nRightClickRow);
	SYosoSagyogun &cYosoSagyogun = pSubline->aYosoSagyogun[nYosoSagyogunId];
	int nGroupNo = 0;
	int nKishuId;

	int nKishuNum = g_pDataManager->GetKishuNum();
	for (nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
		SKishuSagyo &cKishuSagyo = cYosoSagyogun.aKishuSagyo[nKishuId];
		if (cKishuSagyo.nGroupNo != 0) {
			nGroupNo = cKishuSagyo.nGroupNo;
			break;
		}
	}

	if (nGroupNo == 0)
		nGroupNo = g_pDataManager->GetNewGroupNo(m_nSublineId);

	for (nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
		SKishuSagyo &cKishuSagyo = cYosoSagyogun.aKishuSagyo[nKishuId];
		if (cKishuSagyo.nSagyojun != 0)
			cKishuSagyo.nGroupNo = nGroupNo;
	}*/
// By Y.Itabashi (xxxxx) 2007.01.26

	SetGroupNo();
}

// 共通工程全解除
void CSetKyotsuKoteiDlg::OnKyotsuKoteiZenKaijo() 
{
	SSubline *pSubline = g_pDataManager->GetSublineData(m_nSublineId);
// 複数行選択対応
// Lixxxtrlに変更
	// 同一工程内かのチェック
	int nKishuNum = g_pDataManager->GetKishuNum();
	POSITION pos = m_cList.GetFirstSelectedItemPosition();
	int nRow;
	int nYosoSagyogunId;
	CIntArrayEx aGroupNo;
	CIntArrayEx aGroupFlag;

	while( pos ){
		nRow = m_cList.GetNextSelectedItem( pos );
		nYosoSagyogunId = m_cList.GetItemRowData(nRow);
		SYosoSagyogun &cYosoSagyogun = pSubline->aYosoSagyogun[nYosoSagyogunId];

		// 変更するグループ番号を取得
		for (int nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
			if (cYosoSagyogun.aKishuSagyo[nKishuId].nGroupNo != 0) {
				aGroupNo.Add(cYosoSagyogun.aKishuSagyo[nKishuId].nGroupNo);
				aGroupFlag.Add(0);
			}
		}
		
	}

	// 取得したグループ番号を全て同一の工程番号にする
	int nMaxRows = m_cList.GetItemCount();
	for (nRow = 0; nRow < nMaxRows; nRow++) {
		int nYosoSagyogunId = m_cList.GetItemRowData(nRow);
		SYosoSagyogun &cYosoSagyogun = pSubline->aYosoSagyogun[nYosoSagyogunId];
		if (!cYosoSagyogun.CheckDeleted()) {
			for (int nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
				SKishuSagyo &cKishuSagyo = cYosoSagyogun.aKishuSagyo[nKishuId];
				if (cKishuSagyo.nGroupNo != 0) {
					int nIndex = aGroupNo.Find(cKishuSagyo.nGroupNo);
					if (nIndex != -1) {
						if (aGroupFlag[nIndex] == 0)
							aGroupFlag[nIndex] = 1;
						else
							cKishuSagyo.nGroupNo = g_pDataManager->GetNewGroupNo(m_nSublineId);
					}
				}
			}
		}
	}
/*	int nYosoSagyogunId = m_cList.GetItemRowData(m_nRightClickRow);
//	int nYosoSagyogunId = m_cSpread.GetRowItemData(m_nRightClickRow);
	SYosoSagyogun &cYosoSagyogun = pSubline->aYosoSagyogun[nYosoSagyogunId];
	int nKishuId;
	CIntArrayEx aGroupNo;
	CIntArrayEx aGroupFlag;

	int nKishuNum = g_pDataManager->GetKishuNum();
	for (nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
		SKishuSagyo &cKishuSagyo = cYosoSagyogun.aKishuSagyo[nKishuId];
		if (cKishuSagyo.nGroupNo != 0) {
			aGroupNo.Add(cKishuSagyo.nGroupNo);
			aGroupFlag.Add(0);
			break;
		}
	}

	int nYosoSagyogunNum = pSubline->aYosoSagyogun.GetSize();
	for (nYosoSagyogunId = 0; nYosoSagyogunId < nYosoSagyogunNum; nYosoSagyogunId++) {
		SYosoSagyogun &cYosoSagyogun = pSubline->aYosoSagyogun[nYosoSagyogunId];
		if (!cYosoSagyogun.CheckDeleted()) {
			for (nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
				SKishuSagyo &cKishuSagyo = cYosoSagyogun.aKishuSagyo[nKishuId];
				if (cKishuSagyo.nGroupNo != 0) {
					int nIndex = aGroupNo.Find(cKishuSagyo.nGroupNo);
					if (nIndex != -1) {
						if (aGroupFlag[nIndex] == 0)
							aGroupFlag[nIndex] = 1;
						else
							cKishuSagyo.nGroupNo = g_pDataManager->GetNewGroupNo(m_nSublineId);
					}
				}
			}
		}
	}*/
// By Y.Itabashi (xxxxx) 2007.01.26

	SetGroupNo();
}

// 共通工程解除
void CSetKyotsuKoteiDlg::OnKyotsuKoteiKaijo() 
{
	SSubline *pSubline = g_pDataManager->GetSublineData(m_nSublineId);
// Modify ... ( CAHNGE )
// Lixxxtrlに変更
	int nYosoSagyogunId = m_cList.GetItemRowData(m_nRightClickRow);
//	int nYosoSagyogunId = m_cSpread.GetRowItemData(m_nRightClickRow);
	SYosoSagyogun &cYosoSagyogun = pSubline->aYosoSagyogun[nYosoSagyogunId];
	SKishuSagyo &cKishuSagyo = cYosoSagyogun.aKishuSagyo[m_nRightClickCol - COL_KYOTSU_GROUPNO];
// By Y.Itabashi (xxxxx) 2007.01.26

	if (cKishuSagyo.nGroupNo != 0) {
		// 同一グループ番号が存在するかチェック
		if (CountGroupNo(cKishuSagyo.nGroupNo) >= 2) {
			cKishuSagyo.nGroupNo = g_pDataManager->GetNewGroupNo(m_nSublineId);
			SetGroupNo();
		}
	}
}

// キャンセルボタンクリック
void CSetKyotsuKoteiDlg::OnCancel() 
{
	// Undoバッファから復元
	g_pDataManager->RestoreUndoData();

	EndDialog(IDCANCEL);
}

// ＯＫボタンクリック
void CSetKyotsuKoteiDlg::OnOK() 
{
	if (!DataCheck())
		return;

	// 共通工程フラグ設定
	g_pDataManager->SetKyotsuKoteiFlag();

	// データ更新フラグ設定
	g_pDataManager->SetUpdate();

	EndDialog(IDOK);
}

// Modify ... ( CAHNGE )
// Lixxxtrlに変更
// スプレッドにデータを設定
void CSetKyotsuKoteiDlg::SetList()
{
	SSubline *pSubline = g_pDataManager->GetSublineData(m_nSublineId);
	CIntArrayEx &aErrorLine = m_aErrorLine[m_nSublineId];

	int nYosoSagyogunNum = pSubline->aYosoSagyogun.GetSize();
	m_cList.DeleteAllItems();

	int nRow = 0;
	for (int nYosoSagyogunId = 0; nYosoSagyogunId < nYosoSagyogunNum; nYosoSagyogunId++) {
		SYosoSagyogun &cYosoSagyogun = pSubline->aYosoSagyogun[nYosoSagyogunId];
		if (!cYosoSagyogun.CheckDeleted()) {
			m_cList.SetValue(nRow, COL_KYOTSU_KOTEI, cYosoSagyogun.sKoteiName);
			m_cList.SetValue(nRow, COL_KYOTSU_YOSOSAGYOGUN, cYosoSagyogun.sYosoSagyogunName);
			m_cList.SetValue(nRow, COL_KYOTSU_JIDOSHUDO, cYosoSagyogun.sJidoYosoSagyogun.IsEmpty() ? g_pConst->m_sShudo : cYosoSagyogun.sJidoYosoSagyogun);

			m_cList.SetItemRowColor(nRow, COLOR_TEXT,
				aErrorLine.Find(nYosoSagyogunId) == -1 ? COLOR_OKDATA : COLOR_ERRDATA);

			m_cList.SetItemRowData(nRow, nYosoSagyogunId);

			nRow++;
		}
	}

	SetGroupNo();
}

// スプレッドにデータを設定
/*void CSetKyotsuKoteiDlg::SetSpread()
{
	SSubline *pSubline = g_pDataManager->GetSublineData(m_nSublineId);
	CIntArrayEx &aErrorLine = m_aErrorLine[m_nSublineId];

	int nYosoSagyogunNum = pSubline->aYosoSagyogun.GetSize();
	m_cSpread.SetMaxRows(nYosoSagyogunNum);
	m_cSpread.SetTopRow(0);

	int nRow = 1;
	for (int nYosoSagyogunId = 0; nYosoSagyogunId < nYosoSagyogunNum; nYosoSagyogunId++) {
		SYosoSagyogun &cYosoSagyogun = pSubline->aYosoSagyogun[nYosoSagyogunId];
		if (!cYosoSagyogun.CheckDeleted()) {
			m_cSpread.SetValue(nRow, eKyotsuKoteiListKoteiName, cYosoSagyogun.sKoteiName);
			m_cSpread.SetValue(nRow, eKyotsuKoteiListYosoSagyogunName, cYosoSagyogun.sYosoSagyogunName);
			m_cSpread.SetValue(nRow, eKyotsuKoteiListJidoShudo, cYosoSagyogun.sJidoYosoSagyogun.IsEmpty() ? g_pConst->m_sShudo : cYosoSagyogun.sJidoYosoSagyogun);

			m_cSpread.SetBkColor(nRow, -1, aErrorLine.Find(nYosoSagyogunId) == -1 ? RGB(255, 255, 255) : RGB(255, 0, 0));

			m_cSpread.SetRowItemData(nRow, nYosoSagyogunId);

			nRow++;
		}
	}

	m_cSpread.SetMaxRows(nRow - 1);

	SetGroupNo();
}*/
// By Y.Itabashi (xxxxx) 2007.01.26

// スプレッドのグループ番号のプルダウンを設定
void CSetKyotsuKoteiDlg::SetGroupNo()
{
	SSubline *pSubline = g_pDataManager->GetSublineData(m_nSublineId);
	CYosoSagyogunArray &aYosoSagyogun = pSubline->aYosoSagyogun;
	int nYosoSagyogunNum = aYosoSagyogun.GetSize();
	int nKishuNum = g_pDataManager->GetKishuNum();
//	int nMin, nMax;
	CIntArrayEx aGroupNo;
	CString sGroupNoList;
	CString sGroupNo;

// Modify ... ( CAHNGE )
// Lixxxtrlに変更
	int nMaxRows = m_cList.GetItemCount();
	for (int nRow = 0; nRow < nMaxRows; nRow++) {
		int nYosoSagyogunId = m_cList.GetItemRowData(nRow);

		for (int nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
			int nGroupNo = aYosoSagyogun[nYosoSagyogunId].aKishuSagyo[nKishuId].nGroupNo;
			CString	str;
			if( nGroupNo == 0 )
				str.Format( "" );
			else
				str.Format( "%d", nGroupNo );
			m_cList.SetValue( nRow, COL_KYOTSU_GROUPNO + nKishuId, str );
		}
	}

/*	int nMaxRows = m_cSpread.GetMaxRows();
	for (int nRow = 1; nRow <= nMaxRows; nRow++) {
		int nYosoSagyogunId = m_cSpread.GetRowItemData(nRow);
		CString &sKoteiName = aYosoSagyogun[nYosoSagyogunId].sKoteiName;

		for (int nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
			for (nMin = nYosoSagyogunId; ; nMin--) {
				if (nMin != nYosoSagyogunId) {
					if (aYosoSagyogun[nMin].sKoteiName != sKoteiName) {
						nMin++;
						break;
					}

					if (aYosoSagyogun[nMin].aKishuSagyo[nKishuId].nGroupNo != 0)
						break;
				}

				if (nMin <= 0)
					break;
			}

			for (nMax = nYosoSagyogunId; ; nMax++) {
				if (nMax != nYosoSagyogunId) {
					if (aYosoSagyogun[nMax].sKoteiName != sKoteiName) {
						nMax--;
						break;
					}

					if (aYosoSagyogun[nMax].aKishuSagyo[nKishuId].nGroupNo != 0)
						break;
				}

				if (nMax >= nYosoSagyogunNum - 1)
					break;
			}

			aGroupNo.RemoveAll();
			for (int nYosoSagyogunId2 = nMin; nYosoSagyogunId2 <= nMax; nYosoSagyogunId2++) {
				for (int nKishuId2 = 0; nKishuId2 < nKishuNum; nKishuId2++) {
					int nGroupNo = aYosoSagyogun[nYosoSagyogunId2].aKishuSagyo[nKishuId2].nGroupNo;
					if (nGroupNo != 0)
						aGroupNo.AddUnique(nGroupNo);
				}
			}

			int nIndex = -1;
			int nGroupNo = aYosoSagyogun[nYosoSagyogunId].aKishuSagyo[nKishuId].nGroupNo;
			sGroupNoList.Empty();
			aGroupNo.Sort();
			int nSize = aGroupNo.GetSize();
			for (int i = 0; i < nSize; i++) {
				sGroupNo.Format("%d", aGroupNo[i]);
				if (i != 0)
					sGroupNoList += "\t";
				sGroupNoList += sGroupNo;

				if (aGroupNo[i] == nGroupNo)
					nIndex = i;
			}
			m_cSpread.SetComboList(nRow, eKyotsuKoteiListGroupNo + nKishuId, sGroupNoList, nIndex);
		}
	}*/
// By Y.Itabashi (xxxxx) 2007.01.26
}

// 指定したグループ番号の個数を取得
int CSetKyotsuKoteiDlg::CountGroupNo(int nGroupNo)
{
	SSubline *pSubline = g_pDataManager->GetSublineData(m_nSublineId);

	int count = 0;
	int nKishuNum = g_pDataManager->GetKishuNum();
	int nYosoSagyogunNum = pSubline->aYosoSagyogun.GetSize();
	for (int nYosoSagyogunId = 0; nYosoSagyogunId < nYosoSagyogunNum; nYosoSagyogunId++) {
		SYosoSagyogun &cYosoSagyogun = pSubline->aYosoSagyogun[nYosoSagyogunId];
		for (int nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
			if (cYosoSagyogun.aKishuSagyo[nKishuId].nGroupNo == nGroupNo)
				count++;
		}
	}

	return count;	
}

// データチェック
BOOL CSetKyotsuKoteiDlg::DataCheck()
{
	if (!KoteiCheck()) {
// Modify ... ( CAHNGE )
// Lixxxtrlに変更
		SetList();
//		SetSpread();
// By Y.Itabashi (xxxxx) 2007.01.26
		g_pSystem->DispMessage("E007001");
		return FALSE;
	}

	return TRUE;
}

// 工程またがりチェック
BOOL CSetKyotsuKoteiDlg::KoteiCheck()
{
	CMap <int, int, CString, LPCTSTR> aGroupNo;
	CString sKoteiName;
	BOOL bError = FALSE;
	int nKishuNum = g_pDataManager->GetKishuNum();

	int nSublineNum = g_pDataManager->GetSublineNum();
	for (int nSublineId = 0; nSublineId < nSublineNum; nSublineId++) {
		SSubline *pSubline = g_pDataManager->GetSublineData(nSublineId);

		CIntArrayEx &aErrorLine = m_aErrorLine[nSublineId];
		aErrorLine.RemoveAll();
		aGroupNo.RemoveAll();

		int nYosoSagyogunNum = pSubline->aYosoSagyogun.GetSize();
		for (int nYosoSagyogunId = 0; nYosoSagyogunId < nYosoSagyogunNum; nYosoSagyogunId++) {
			SYosoSagyogun &cYosoSagyogun = pSubline->aYosoSagyogun[nYosoSagyogunId];
			if (!cYosoSagyogun.CheckDeleted()) {
				for (int nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
					int nGroupNo = cYosoSagyogun.aKishuSagyo[nKishuId].nGroupNo;
					if (nGroupNo != 0) {
						if (aGroupNo.Lookup(nGroupNo, sKoteiName)) {
							if (sKoteiName != cYosoSagyogun.sKoteiName) {
								aErrorLine.Add(nYosoSagyogunId);
								bError = TRUE;
								break;
							}
						} else
							aGroupNo.SetAt(nGroupNo, cYosoSagyogun.sKoteiName);
					}
				}
			}
		}
	}

	return !bError;
}

// WM_SIZEメッセージハンドラ
void CSetKyotsuKoteiDlg::OnSize(UINT nType, int cx, int cy) 
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

void CSetKyotsuKoteiDlg::OnRclickList001(NMHDR* pNMHDR, LRESULT* pResult) 
{
	NM_LISTVIEW* pNMListView = (NM_LISTVIEW*)pNMHDR;

	int Row = pNMListView->iItem;
	int Col = pNMListView->iSubItem;

	if (Row >= 0) {
		// クリックされた行,列を保存
		m_nRightClickRow = Row;
		m_nRightClickCol = Col;

		// クリックされた位置にポップアップメニューを表示
		CPoint point;
		::GetCursorPos(&point);
		CMenu cMenu;
		cMenu.LoadMenu(IDR_KYOTSU_KOTEI);
		CMenu* pPopup = cMenu.GetSubMenu(0);

// Modify ... ( ADD )
		g_pSystem->m_cMenuDisp.SetMenuText( (CWnd *)pPopup, Menu_SetKyotsuKoteiPopup );
// By Y.Itabashi (xxxxx) 2007.01.26

// Modify ... ( CHANGE )
// 複数行選択対応
		int nSel = m_cList.GetSelectedCount();
		if (Col < COL_KYOTSU_GROUPNO || nSel != 1)
//		if (Col < COL_KYOTSU_GROUPNO)
			pPopup->EnableMenuItem(IDM_KYOTSU_KOTEI_KAIJO, MF_GRAYED);
// By Y.Itabashi (xxxxx) Y.Itabashi

		pPopup->TrackPopupMenu(TPM_LEFTALIGN | TPM_RIGHTBUTTON, point.x, point.y, this);
	}
	
	*pResult = 0;
}

LRESULT CSetKyotsuKoteiDlg::OnChangeCombo(WPARAM wp, LPARAM lp)
{
	LV_DISPINFO *dispinfo = (LV_DISPINFO*)wp;

// Modify ... ( CHANGE )
	if( dispinfo->item.pszText == NULL )
		return TRUE;
// By Y.Itabashi (xxxxx) 2007.02.12

	int Row = dispinfo->item.iItem;
	int Col = dispinfo->item.iSubItem;
	int nGroupNo = atoi(dispinfo->item.pszText);
	
	if( Col >= COL_KYOTSU_GROUPNO ){
		SSubline *pSubline = g_pDataManager->GetSublineData(m_nSublineId);
		int nYosoSagyogunId = m_cList.GetItemRowData(Row);
		int nKishuId = Col - COL_KYOTSU_GROUPNO;
		pSubline->aYosoSagyogun[nYosoSagyogunId].aKishuSagyo[nKishuId].nGroupNo = nGroupNo;
		m_cList.SetValue(Row, Col, nGroupNo);

		SetGroupNo();
	}

	return TRUE;
}

LRESULT CSetKyotsuKoteiDlg::CreateComboList(WPARAM wp, LPARAM lp)
{
	NM_LISTVIEW* pNMListView = (NM_LISTVIEW*)wp;

// Modify ... ( ADD )
// 複数行選択対応
	int nSel = m_cList.GetSelectedCount();
	if( nSel > 1 )
		return FALSE;
// By Y.Itabashi (xxxxx) 2007.01.29

	int nRow = pNMListView->iItem;
	int nCol = pNMListView->iSubItem;

	SSubline *pSubline = g_pDataManager->GetSublineData(m_nSublineId);
	CYosoSagyogunArray &aYosoSagyogun = pSubline->aYosoSagyogun;
	int nYosoSagyogunNum = aYosoSagyogun.GetSize();
	int nKishuNum = g_pDataManager->GetKishuNum();
	int nMin, nMax;
	CIntArrayEx aGroupNo;
	CString sGroupNo;

	int nYosoSagyogunId = m_cList.GetItemRowData(nRow);
	CString &sKoteiName = aYosoSagyogun[nYosoSagyogunId].sKoteiName;

	int nKishuId = nCol - COL_KYOTSU_GROUPNO;
	m_cList.GetValue(nRow, nCol, pSubline->aYosoSagyogun[nYosoSagyogunId].aKishuSagyo[nKishuId].nGroupNo);

	// 作業順の位置を取得
	CSagyojunArray aSagyojun;
	g_pDataManager->GetSagyojun(m_nSublineId, nKishuId, aSagyojun);
	int nSagyojunIndex;
	int nSagyojunNum = aSagyojun.GetSize();
	for (nSagyojunIndex = 0; nSagyojunIndex < nSagyojunNum; nSagyojunIndex++) {
		if (aSagyojun[nSagyojunIndex].nYosoSagyogunId == nYosoSagyogunId)
			 break;
	}

	int nSagyojunIndex1, nSagyojunIndex2;
	nSagyojunIndex1 = nSagyojunIndex2 = nSagyojunIndex;
	for (nMin = nYosoSagyogunId; ; nMin--) {
		if (nMin != nYosoSagyogunId) {
			if (aYosoSagyogun[nMin].sKoteiName != sKoteiName) {
				nMin++;
				break;
			}

			if (nSagyojunIndex1 > 0 && aSagyojun[nSagyojunIndex1 - 1].nYosoSagyogunId == nMin)
				nSagyojunIndex1--;
			else if (nSagyojunIndex2 < nSagyojunNum - 1 && aSagyojun[nSagyojunIndex2 + 1].nYosoSagyogunId == nMin)
				nSagyojunIndex2++;
			else {
				nMin++;
				break;
			}

			if (aYosoSagyogun[nMin].aKishuSagyo[nKishuId].nGroupNo != 0)
				break;
		}

		if (nMin <= 0)
			break;
	}

	nSagyojunIndex1 = nSagyojunIndex2 = nSagyojunIndex;
	for (nMax = nYosoSagyogunId; ; nMax++) {
		if (nMax != nYosoSagyogunId) {
			if (aYosoSagyogun[nMax].sKoteiName != sKoteiName) {
				nMax--;
				break;
			}

			if (nSagyojunIndex1 > 0 && aSagyojun[nSagyojunIndex1 - 1].nYosoSagyogunId == nMax)
				nSagyojunIndex1--;
			else if (nSagyojunIndex2 < nSagyojunNum - 1 && aSagyojun[nSagyojunIndex2 + 1].nYosoSagyogunId == nMax)
				nSagyojunIndex2++;
			else {
				nMax--;
				break;
			}

			if (aYosoSagyogun[nMax].aKishuSagyo[nKishuId].nGroupNo != 0)
				break;
		}

		if (nMax >= nYosoSagyogunNum - 1)
			break;
	}

	aGroupNo.RemoveAll();
	for (int nYosoSagyogunId2 = nMin; nYosoSagyogunId2 <= nMax; nYosoSagyogunId2++) {
		for (int nKishuId2 = 0; nKishuId2 < nKishuNum; nKishuId2++) {
			int nGroupNo = aYosoSagyogun[nYosoSagyogunId2].aKishuSagyo[nKishuId2].nGroupNo;
			if (nGroupNo != 0)
				aGroupNo.AddUnique(nGroupNo);
		}
	}

	int nIndex = -1;
	int nGroupNo = aYosoSagyogun[nYosoSagyogunId].aKishuSagyo[nKishuId].nGroupNo;
	aGroupNo.Sort();
	CStringList	lstGroupNo;
	int nSize = aGroupNo.GetSize();
	for (int i = 0; i < nSize; i++) {
		sGroupNo.Format("%d", aGroupNo[i]);
		lstGroupNo.AddTail( sGroupNo );
		if (aGroupNo[i] == nGroupNo)
			nIndex = i;
	}
	m_cList.SetComboList( nRow, nCol, lstGroupNo, nIndex );

	return TRUE;
}
