//*****************************************************************************************************
//  1. ファイル名
//		CGraphDigitalDataRowCol.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		グラフ表示デジタル数値表示ダイアログクラスの実装
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2008.01.01 M.Hama 新規作成
//*****************************************************************************************************

#include "stdafx.h"
#include "MMA_G.h"
#include "GraphDigitalDataRowCol.h"
#include "General.h"

static const char *gTitle = "No,Time";

// CGraphDigitalDataRowCol ダイアログ

IMPLEMENT_DYNAMIC(CGraphDigitalDataRowCol, CDialogEx)

CGraphDigitalDataRowCol::CGraphDigitalDataRowCol(CWnd* pParent /*=NULL*/)
	: CDialogEx(CGraphDigitalDataRowCol::IDD, pParent)
	, m_pData(NULL)
	, m_nRow(0)
	, m_nCol(0)
	, m_sTitle(_T(""))
	, m_nPeriod(0)
{

}

CGraphDigitalDataRowCol::~CGraphDigitalDataRowCol()
{
}

void CGraphDigitalDataRowCol::DoDataExchange(CDataExchange* pDX)
{
	CDialogEx::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_DATA_LIST_ROW, m_ListRow);
	DDX_Control(pDX, IDC_DATA_LIST_COL, m_Lixxxol);
}


BEGIN_MESSAGE_MAP(CGraphDigitalDataRowCol, CDialogEx)
	ON_BN_CLICKED(IDC_BUTTON_CSV, &CGraphDigitalDataRowCol::OnBnClickedButtonCsv)
	ON_NOTIFY(LVN_GETDISPINFO, IDC_DATA_LIST_COL, &CGraphDigitalDataRowCol::OnLvnGetdispinfoDataLixxxol)
	ON_NOTIFY(LVN_GETDISPINFO, IDC_DATA_LIST_ROW, &CGraphDigitalDataRowCol::OnLvnGetdispinfoDataListRow)
	ON_NOTIFY(LVN_ITEMCHANGED, IDC_DATA_LIST_ROW, &CGraphDigitalDataRowCol::OnLvnItemchangedDataListRow)
	ON_WM_DESTROY()
END_MESSAGE_MAP()

// ツールチップ表示データ
static const SToolTipData s_aToolTip[] = {
	{IDC_BUTTON_CSV, "表示されている数値データをCSVファイルに出力します。"},
	{IDCANCEL, "このダイアログを閉じます。"},
	{0, NULL}
};

//*****************************************************************************************************
//  1. 関数名
//		CGraphDigitalDataRowCol::SetDataInfo
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		リスト表示処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		double			*pDat			[I] データのポインタ
//		int				nRow			[I]	行数
//		int				nCol			[I]	列数
//		LPCSTR			title			[I]	タイトル
//		CDataDateTime	tm				[I] 計測開始時刻
//		double			timeStep		[I] 時間ステップ
//		double			freqStep		[I] 周波数ステップ
//		long			period			[I] 出力範囲
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphDigitalDataRowCol::SetDataInfo(const double *pDat, int nRow, int nCol, LPCSTR title, CDataDateTime tm, double timeStep, double freqStep, long period)
{
	m_pData = pDat;
	m_nRow = nRow;
	m_nCol = nCol;
	m_sTitle = title;
	m_StartTime = tm;
	m_fStep = timeStep;
	m_nPeriod = period;
	m_fFreqStep = freqStep;

	if (m_pData == NULL) {
		m_nRow = 0;
		m_nCol = 0;
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphDigitalDataRowCol::OnInitDialog
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ダイアログ初期化処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL		TRUE
//*****************************************************************************************************
BOOL CGraphDigitalDataRowCol::OnInitDialog()
{
	CDialogEx::OnInitDialog();

	// 行全体を選択できるようにする
	m_ListRow.SetExtendedStyle(LVS_EX_FULLROWSELECT);
	m_Lixxxol.SetExtendedStyle(LVS_EX_FULLROWSELECT);

	// リストヘッダ設定
	SetListHeader();

	// データ総数を設定
	m_ListRow.SetItemCount(m_nRow);
	m_Lixxxol.SetItemCount(m_nCol);
	m_ListRow.SetItemState(0, LVIS_SELECTED | LVIS_FOCUSED, LVIS_SELECTED | LVIS_FOCUSED);

	SetWindowText(m_sTitle);

	// コントロールの位置を調整
	SetCtlPosition(IDC_STATIC_ROW, 0, 0, 0.5, 0);
	SetCtlPosition(IDC_STATIC_COL, 0.5, 0, 0.5, 0);
	SetCtlPosition(IDC_DATA_LIST_ROW, 0, 0, 0.5, 1);
	SetCtlPosition(IDC_DATA_LIST_COL, 0.5, 0, 1, 1);
	SetCtlPosition(IDC_BUTTON_CSV, 1, 1, 1, 1);
	SetCtlPosition(IDCANCEL, 1, 1, 1, 1);

	// ツールチップ表示設定
	CGeneral::SetToolTip(m_tooltip, s_aToolTip, this);

	return TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphDigitalDataRowCol::SetListHeader
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		リストヘッダ設定処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphDigitalDataRowCol::SetListHeader()
{
	struct LIxxxOLUMN {
		char *pText;
		int fmt;
		int width;
	} tColumnData[] = {
		{"No", LVCFMT_LEFT, 70},
		{"Time", LVCFMT_LEFT, 170}
	}, tColumnData2[] = {
		{"No", LVCFMT_LEFT, 70},
		{"Freq(Hz)", LVCFMT_LEFT, 70},
		{"PSD(G^2/Hz)", LVCFMT_LEFT, 120}
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
		m_ListRow.InsertColumn(i, &lvc);
		pColumn++;
	}

	// カラムデータ
	pColumn = tColumnData2;
	nColumn  = sizeof(tColumnData2) / sizeof(LIxxxOLUMN);

	// カラムを設定
	for (i = 0; i < nColumn; i++) {
		lvc.mask = LVCF_FMT | LVCF_WIDTH | LVCF_TEXT;
		lvc.fmt = pColumn->fmt;
		lvc.pszText = pColumn->pText;
		lvc.cx = pColumn->width;
		m_Lixxxol.InsertColumn(i, &lvc);
		pColumn++;
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphDigitalDataRowCol::OnBnClickedButtonCsv
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		CSVボタン処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphDigitalDataRowCol::OnBnClickedButtonCsv()
{
	if (m_nRow == 0 || m_nCol == 0)
		return;

#if 0
	if (m_Period > 60 * 60 + 1) {
		CGeneral::Alert("時間範囲：１時間分までにしてください。");
		return;
	}
#endif

	// ファイル選択ダイアログを開く
	CFileDialog cFDlg(FALSE,
						"csv",
						"*.csv",
						 OFN_HIDEREADONLY | OFN_OVERWRITEPROMPT ,
						"CSV(*.csv)|*.csv|全てのファイル (*.*)|*.*||",
						this
						);
	// キャンセルならば何もしない
	if (cFDlg.DoModal() == IDCANCEL) {
		return;
	}

	m_sCsvFName = cFDlg.GetPathName();
	CProgressBar cProgressBar;
	// プログレスバーを表示し、別スレッドでファイルを読み込む
	cProgressBar.ExecProcess("ファイル書き込み中", 10, this, WriteFileThread);
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphDigitalDataRowCol::WriteFileThread
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ファイル書き込みスレッドの入り口
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		LPVOID			pParam			[I] CDataFileクラスへのポインタ
//		CProgressBar	*pProgressBar	[I] プログレスバークラスへのポインタ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL	TRUE：正常　FALSE：エラー
//*****************************************************************************************************
BOOL CGraphDigitalDataRowCol::WriteFileThread(LPVOID pParam, CProgressBar *pProgressBar)
{
	return ((CGraphDigitalDataRowCol *)pParam)->WriteFileThread2(pProgressBar);
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphDigitalDataRowCol::WriteFileThread2
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ファイル書き込みスレッドの本体
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CProgressBar	*pProgressBar	[I] プログレスバークラスへのポインタ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL	TRUE：正常　FALSE：エラー
//*****************************************************************************************************
BOOL CGraphDigitalDataRowCol::WriteFileThread2(CProgressBar *pProgressBar)
{
	CFile cWFile;
	int	i, j;
	CString str, buf;

	// 書き込みファイルオープン
	if (!cWFile.Open(m_sCsvFName, CFile::modeCreate | CFile::modeWrite | CFile::shareDenyWrite)) {
		CGeneral::Alert("書き込みファイルが書けません。");
		return FALSE;
	}

	{
		CWaitCursor	cursor;
// update 2008/03/18 by hama　→
		CDataDateTime	tm;
		// タイトル行編集
		str = gTitle;
		for (i = 0; i < m_nCol; i++) {
			buf.Format(",%.3fHz", i * m_fFreqStep);
			str += buf;
		}
		str += "\n";
		cWFile.Write(str, str.GetLength());
		tm = m_StartTime;
// update 2008/03/18 by hama　←

		// データ数分ループ
		for (i = 0; i < m_nRow; i++) {
			str = "";
			buf.Format("%d", i+1);
			str += buf;
// update 2008/03/18 by hama　→
//			buf.Format(",%.3f", i * m_FreqStep);
//			str += buf;
			tm += (long)(m_fStep * i);
			buf.Format(",%s", tm.GetStr());
			str += buf;
			for (j = 0; j < m_nCol; j++) {
//				buf.Format(",%e", m_pData[m_nCol * i + (m_nCol - j - 1)]);
				buf.Format(",%e", m_pData[m_nCol * i + j]);
// update 2008/03/18 by hama　←
				str += buf;
			}
			str += "\n";
			cWFile.Write(str, str.GetLength());

			// プログレスバーを更新
			if (!pProgressBar->SetProgress((i + 1) * 10 / m_nRow)) {
				// データファイルをクローズ
				cWFile.Close();
				return FALSE;
			}

		}
		// データファイルをクローズ
		cWFile.Close();
	}

	AfxMessageBox("CSVファイルへの出力が完了しました。",MB_ICONINFORMATION|MB_OK);

	return TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphDigitalDataRowCol::OnLvnGetdispinfoDataLixxxol
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		LVN_GETDISPINFOメッセージハンドラ
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		NMHDR		*pNMHDR		[I] 表示情報
//		LRESULT		*pResult	[O] 結果情報
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphDigitalDataRowCol::OnLvnGetdispinfoDataLixxxol(NMHDR *pNMHDR, LRESULT *pResult)
{
	NMLVDISPINFO *pDispInfo = reinterpret_cast<NMLVDISPINFO*>(pNMHDR);

 	if (pDispInfo->item.mask & LVIF_TEXT) {
		int sel = m_ListRow.GetNextItem(-1, LVNI_ALL | LVNI_SELECTED);
		if (sel != -1) {
			CString str;
			switch(pDispInfo->item.iSubItem) {
			case 0:
				str.Format("%d_%d", sel + 1, pDispInfo->item.iItem + 1);
				lstrcpy(pDispInfo->item.pszText, (LPCSTR)str);
				break;
			case 1:
				str.Format("%.3f", pDispInfo->item.iItem * m_fFreqStep);
				lstrcpy(pDispInfo->item.pszText, (LPCSTR)str);
				break;
			case 2:
				if (m_pData != NULL) {
					str.Format("%e", m_pData[sel * m_nCol + pDispInfo->item.iItem]);
					lstrcpy(pDispInfo->item.pszText, (LPCSTR)str);
				}
				break;
			}
		}
	}

	*pResult = 0;
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphDigitalDataRowCol::OnLvnGetdispinfoDataListRow
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		LVN_GETDISPINFOメッセージハンドラ
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		NMHDR		*pNMHDR		[I] 表示情報
//		LRESULT		*pResult	[O] 結果情報
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphDigitalDataRowCol::OnLvnGetdispinfoDataListRow(NMHDR *pNMHDR, LRESULT *pResult)
{
	NMLVDISPINFO *pDispInfo = reinterpret_cast<NMLVDISPINFO*>(pNMHDR);

 	if (pDispInfo->item.mask & LVIF_TEXT) {
		CString str;
		CDataDateTime	tm;
		switch(pDispInfo->item.iSubItem) {
		case 0:
			str.Format("%d", pDispInfo->item.iItem + 1);
			lstrcpy(pDispInfo->item.pszText, (LPCSTR)str);
			break;
		case 1:
			tm = m_StartTime;
			tm += (long)(m_fStep * pDispInfo->item.iItem);
			str.Format("%s", tm.GetStr());
			lstrcpy(pDispInfo->item.pszText, (LPCSTR)str);
			break;
		}
	}

	*pResult = 0;
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphDigitalDataRowCol::OnLvnItemchangedDataListRow
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		LPNMLISTVIEWメッセージハンドラ
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		NMHDR		*pNMHDR		[I] 表示情報
//		LRESULT		*pResult	[O] 結果情報
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphDigitalDataRowCol::OnLvnItemchangedDataListRow(NMHDR *pNMHDR, LRESULT *pResult)
{
	LPNMLISTVIEW pNMLV = reinterpret_cast<LPNMLISTVIEW>(pNMHDR);

	// 行リストに変化があった
	if (pNMLV->uNewState != 0) {
		// 列リストを再描画
		m_Lixxxol.DeleteAllItems();
		m_Lixxxol.SetItemCount(m_nCol);
	}

	*pResult = 0;
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphDigitalDataRowCol::OnDestroy
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ウィンドウ破棄処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphDigitalDataRowCol::OnDestroy()
{
	CDialogEx::OnDestroy();

	EndDialog(IDCANCEL);
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphDigitalDataRowCol::PreTranslateMessage
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ウィンドウメッセージ処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		MSG*	pMsg			[I] MSG構造体へのポインタ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL	親クラスの返値をそのまま返す
//*****************************************************************************************************
BOOL CGraphDigitalDataRowCol::PreTranslateMessage(MSG* pMsg)
{
	// ツールチップを表示するための処理
	switch(pMsg->message){
	case WM_LBUTTONDOWN:            
	case WM_LBUTTONUP:              
	case WM_MOUSEMOVE:
		if (m_tooltip.m_hWnd)
			m_tooltip.RelayEvent(pMsg);
		break;
	}

	return CDialogEx::PreTranslateMessage(pMsg);
}
