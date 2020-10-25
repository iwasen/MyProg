//*****************************************************************************************************
//  1. ファイル名
//		GraphDigitalData.cpp
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
#include "GraphDigitalData.h"
#include "General.h"
#include <math.h>

// CSVファイルの項目名
static const char *gTitle[] = {
	"No,Freq(Hz),X-PSD(G^2/Hz),Y-PSD(G^2/Hz),Z-PSD(G^2/Hz),G-PSD(G^2/Hz)\n",					// スペクトル出力用
	"No,Freq(Hz),X-Acceleration(G),Y-Acceleration(G),Z-Acceleration(G),G-Acceleration(G)\n"		// 1/3オクターブ出力用
};

// CGraphDigitalData ダイアログ

IMPLEMENT_DYNAMIC(CGraphDigitalData, CDialogEx)

//*****************************************************************************************************
//  1. 関数名
//		CGraphDigitalData::CGraphDigitalData
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		コンストラクタ
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CWnd		*pParent		[I] 親ウィンドウポインタ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
CGraphDigitalData::CGraphDigitalData(CWnd *pParent)
	: CDialogEx(CGraphDigitalData::IDD, pParent)
	, m_nCount(0)
	, m_pX(NULL)
	, m_sCsvFName(_T(""))
	, m_nPeriod(0)
{

}

CGraphDigitalData::~CGraphDigitalData()
{
}

void CGraphDigitalData::DoDataExchange(CDataExchange* pDX)
{
	CDialogEx::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_DATA_LIST, m_List);
	DDX_Control(pDX, IDC_BUTTON_CSV, m_Button_Csv);
}


BEGIN_MESSAGE_MAP(CGraphDigitalData, CDialogEx)
	ON_NOTIFY(LVN_GETDISPINFO, IDC_DATA_LIST, &CGraphDigitalData::OnLvnGetdispinfoDataList)
	ON_BN_CLICKED(IDC_BUTTON_CSV, &CGraphDigitalData::OnBnClickedButtonCsv)
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
//		CGraphDigitalData::SetDataInfo
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		リスト表示処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		int			mode			[I]	0:スペクトルデータ出力　1:1/3オクターブデータ出力
//		int			nCount			[I]	データ数
//		double		*pX				[I] Xデータのポインタ
//		double		*pY				[I] Yデータのポインタ
//		double		*pZ				[I] Zデータのポインタ
//		double		*pG				[I] 合力データのポインタ
//		LPCSTR		title			[I] タイトル
//		long		period			[I] 出力範囲
//		double		step			[I] 周波数のステップ
//		double		*pStepTable		[I] 周波数のテーブル
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphDigitalData::SetDataInfo(int mode, int nCount, const double *pX, const double *pY, const double *pZ, const double *pG, LPCSTR title, long period, double step, const double *pStepTable)
{
	m_nMode = mode;
	m_nCount = nCount;
	m_pX = pX;
	m_pY = pY;
	m_pZ = pZ;
	m_pG = pG;
	m_nPeriod = period;
	m_fStep = step;
	m_pStepTable = pStepTable;

	if (m_pX == NULL || m_pY == NULL || m_pZ == NULL || m_pG == NULL)
		m_nCount = 0;

	m_sTitle = title;
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphDigitalData::OnDestroy
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
void CGraphDigitalData::OnDestroy()
{
	CDialogEx::OnDestroy();

	EndDialog(IDCANCEL);
}


//*****************************************************************************************************
//  1. 関数名
//		CGraphDigitalData::OnLvnGetdispinfoDataList
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
void CGraphDigitalData::OnLvnGetdispinfoDataList(NMHDR *pNMHDR, LRESULT *pResult)
{
	NMLVDISPINFO *pDispInfo = reinterpret_cast<NMLVDISPINFO*>(pNMHDR);

	double fFreq;
	int nFreqDigit;
	CString sFormat;

	if (pDispInfo->item.mask & LVIF_TEXT) {
		CString str;
		switch(pDispInfo->item.iSubItem) {
		case 0:
			str.Format("%d", pDispInfo->item.iItem + 1);
			lstrcpy(pDispInfo->item.pszText, (LPCSTR)str);
			break;
		case 1:
			nFreqDigit = 3;
			switch(m_nMode) {
			case 0:
				fFreq = m_fStep * pDispInfo->item.iItem;
				break;
			case 1:
				fFreq = m_pStepTable[pDispInfo->item.iItem * 2 + 1];

				if (fFreq > 0 && fFreq < 1e-2)
					nFreqDigit = 1 - (int)floor(log10(fFreq));
				break;
			}

			sFormat.Format("%%.%df", nFreqDigit);
			str.Format(sFormat, fFreq);
			lstrcpy(pDispInfo->item.pszText, (LPCSTR)str);
			break;
		case 2:
			if (m_pX != NULL) {
				str.Format("%e", m_pX[pDispInfo->item.iItem]);
				lstrcpy(pDispInfo->item.pszText, (LPCSTR)str);
			}
			break;
		case 3:
			if (m_pY != NULL) {
				str.Format("%e", m_pY[pDispInfo->item.iItem]);
				lstrcpy(pDispInfo->item.pszText, (LPCSTR)str);
			}
			break;
		case 4:
			if (m_pZ != NULL) {
				str.Format("%e", m_pZ[pDispInfo->item.iItem]);
				lstrcpy(pDispInfo->item.pszText, (LPCSTR)str);
			}
			break;
		case 5:
			if (m_pG != NULL) {
				str.Format("%e", m_pG[pDispInfo->item.iItem]);
				lstrcpy(pDispInfo->item.pszText, (LPCSTR)str);
			}
			break;
		}
	}
	*pResult = 0;
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphDigitalData::OnInitDialog
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
BOOL CGraphDigitalData::OnInitDialog()
{
	CDialogEx::OnInitDialog();

	// 行全体を選択できるようにする
	m_List.SetExtendedStyle(LVS_EX_FULLROWSELECT);

	// リストヘッダ設定
	SetListHeader();

	// データ総数を設定
	m_List.SetItemCount(m_nCount);

	SetWindowText(m_sTitle);

	// コントロールの位置を調整
	SetCtlPosition(IDC_DATA_LIST, 0, 0, 1, 1);
	SetCtlPosition(IDC_BUTTON_CSV, 1, 1, 1, 1);
	SetCtlPosition(IDCANCEL, 1, 1, 1, 1);

	// ツールチップ表示設定
	CGeneral::SetToolTip(m_tooltip, s_aToolTip, this);

	return TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphDigitalData::SetListHeader
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
void CGraphDigitalData::SetListHeader()
{
	struct LIxxxOLUMN {
		char *pText;
		int fmt;
		int width;
	} tColumnData[][6] = {
		{
			// スペクトル表示用
			{"No", LVCFMT_LEFT, 70},
			{"Freq(Hz)", LVCFMT_LEFT, 70},
			{"X-PSD(G^2/Hz)", LVCFMT_LEFT, 120},
			{"Y-PSD(G^2/Hz)", LVCFMT_LEFT, 120},
			{"Z-PSD(G^2/Hz)", LVCFMT_LEFT, 120},
			{"G-PSD(G^2/Hz)", LVCFMT_LEFT, 120}
		}, {
			// 1/3オクターブ表示用
			{"No", LVCFMT_LEFT, 70},
			{"Freq(Hz)", LVCFMT_LEFT, 70},
			{"X-Acceleration(G)", LVCFMT_LEFT, 120},
			{"Y-Acceleration(G)", LVCFMT_LEFT, 120},
			{"Z-Acceleration(G)", LVCFMT_LEFT, 120},
			{"G-Acceleration(G)", LVCFMT_LEFT, 120}
		}
	}, *pColumn;
	int i;
	LV_COLUMN lvc;

	// カラムデータ
	pColumn = tColumnData[m_nMode];

	// カラムを設定
	for (i = 0; i < 6; i++) {
		lvc.mask = LVCF_FMT | LVCF_WIDTH | LVCF_TEXT;
		lvc.fmt = pColumn->fmt;
		lvc.pszText = pColumn->pText;
		lvc.cx = pColumn->width;
		m_List.InsertColumn(i, &lvc);
		pColumn++;
	}
}


//*****************************************************************************************************
//  1. 関数名
//		CGraphDigitalData::OnBnClickedButtonCsv
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
void CGraphDigitalData::OnBnClickedButtonCsv()
{
	if (m_nCount == 0)
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
//		CGraphDigitalData::WriteFileThread
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
BOOL CGraphDigitalData::WriteFileThread(LPVOID pParam, CProgressBar *pProgressBar)
{
	return ((CGraphDigitalData *)pParam)->WriteFileThread2(pProgressBar);
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphDigitalData::WriteFileThread2
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
BOOL CGraphDigitalData::WriteFileThread2(CProgressBar *pProgressBar)
{
	CFile cWFile;
	int	i;
	CString str;
	double fFreq;
	int nFreqDigit;
	CString sFormat;

	// 書き込みファイルオープン
	if (!cWFile.Open(m_sCsvFName, CFile::modeCreate | CFile::modeWrite | CFile::shareDenyWrite)) {
		CGeneral::Alert("書き込みファイルが書けません。");
		return FALSE;
	}

	{
		CWaitCursor	cursor;
		// データ件数が0でなければ，ヘッダ書き込み
		if (m_nCount != 0) {
			str = gTitle[m_nMode];
			cWFile.Write(str, str.GetLength());
		}
		// データ数分ループ
		for (i = 0; i < m_nCount; i++) {
			nFreqDigit = 3;
			switch(m_nMode) {
			case 0:
				fFreq = m_fStep * i;
				break;
			case 1:
				fFreq = m_pStepTable[i * 2 + 1];

				if (fFreq > 0 && fFreq < 1e-2)
					nFreqDigit = 1 - (int)floor(log10(fFreq));
				break;
			}
			sFormat.Format("%%d,%%.%df,%%e,%%e,%%e,%%e\n", nFreqDigit);
			str.Format(sFormat
					, i + 1
					, fFreq
					, m_pX[i]
					, m_pY[i]
					, m_pZ[i]
					, m_pG[i]);
			cWFile.Write(str, str.GetLength());

			if ((i % 100) == 0) {
				// プログレスバーを更新
				if (!pProgressBar->SetProgress((i + 1) * 10 / m_nCount)) {
					// データファイルをクローズ
					cWFile.Close();
					return FALSE;
				}
			}
		}
		// データファイルをクローズ
		cWFile.Close();
	}
	AfxMessageBox("CSVファイルへの出力が完了しました。", MB_ICONINFORMATION|MB_OK);
	return TRUE;
}


//*****************************************************************************************************
//  1. 関数名
//		CGraphDigitalData::PreTranslateMessage
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
BOOL CGraphDigitalData::PreTranslateMessage(MSG* pMsg)
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
