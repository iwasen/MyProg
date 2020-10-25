//*****************************************************************************************************
//  1. ファイル名
//		DigitalDataDlg.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		デジタル数値表示ダイアログクラスの実装
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2007.08.09 S.Aizawa 新規作成
//*****************************************************************************************************

#include "stdafx.h"
#include "MMA_G.h"
#include "DigitalDataDlg.h"
#include "TimeRangeDlg.h"
#include "General.h"

#define PAGE_SIZE	1000		// 1ページ当りのデータ件数

// CDigitalDataDlg ダイアログ

IMPLEMENT_DYNAMIC(CDigitalDataDlg, CDialogEx)

//*****************************************************************************************************
//  1. 関数名
//		CDigitalDataDlg::CDigitalDataDlg
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
CDigitalDataDlg::CDigitalDataDlg(CWnd *pParent)
	: CDialogEx(CDigitalDataDlg::IDD, pParent)
{
}

void CDigitalDataDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialogEx::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_LIST_DATA, m_cListData);
	DDX_Control(pDX, IDC_EDIT_PAGE_NO, m_cEditPageNo);
	DDX_Control(pDX, IDC_BUTTON_PREV_PAGE, m_cButtonPrevPage);
	DDX_Control(pDX, IDC_BUTTON_NEXT_PAGE, m_cButtonNextPage);
	DDX_Control(pDX, IDC_COMBO_SAMPLE_RATE, m_cComboSampleRate);
}


BEGIN_MESSAGE_MAP(CDigitalDataDlg, CDialogEx)
	ON_BN_CLICKED(IDC_BUTTON_PREV_PAGE, &CDigitalDataDlg::OnBnClickedButtonPrevPage)
	ON_BN_CLICKED(IDC_BUTTON_NEXT_PAGE, &CDigitalDataDlg::OnBnClickedButtonNextPage)
	ON_CBN_SELCHANGE(IDC_COMBO_SAMPLE_RATE, &CDigitalDataDlg::OnCbnSelchangeComboSampleRate)
	ON_BN_CLICKED(IDC_BUTTON_PRINT, &CDigitalDataDlg::OnBnClickedButtonPrint)
	ON_BN_CLICKED(IDC_BUTTON_SELECT_ALL, &CDigitalDataDlg::OnBnClickedButtonSelectAll)
	ON_WM_DESTROY()
END_MESSAGE_MAP()

// ツールチップ表示データ
static const SToolTipData s_aToolTip[] = {
	{IDC_BUTTON_PREV_PAGE, "前ページのデータを表示します。"},
	{IDC_BUTTON_NEXT_PAGE, "次ページのデータを表示します。"},
	{IDC_BUTTON_SELECT_ALL, "全データを選択して印刷できるようにします。"},
	{IDC_BUTTON_PRINT, "選択しているデータを印刷します。"},
	{IDCANCEL, "このダイアログを閉じます。"},
	{0, NULL}
};

//*****************************************************************************************************
//  1. 関数名
//		CDigitalDataDlg::DispList
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		リスト表示処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		LPCTSTR		pFilePath		[I] 情報ファイルのパス名
//		int			nDataType		[I] データの種類（MMA or MME）
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CDigitalDataDlg::DispList(LPCTSTR pFilePath, int nDataType)
{
	// データ種別(MMA or MME)保存
	m_cDataFile.m_nDataType = nDataType;

	// 情報ファイル読み込み
	if (!m_cDataFile.ReadInfoFile(pFilePath)) {
		delete this;
		return;
	}

	// 時間範囲設定ダイアログ表示
	CTimeRangeDlg cTimeRangeDlg;
	cTimeRangeDlg.m_dStarttime = m_cDataFile.m_dStarttime;
	cTimeRangeDlg.m_nDataSec = (int)m_cDataFile.m_fTotalSecond;
	cTimeRangeDlg.m_sKind = "数値データ";
	if (cTimeRangeDlg.DoModal() == IDCANCEL) {
		delete this;
		return;
	}

	// パラメータを保存
	m_sFilePath = pFilePath;
	m_nBeginTime = cTimeRangeDlg.m_nDispBegin;
	m_nEndTime = cTimeRangeDlg.m_nDispEnd;
	m_cDataFile.m_dInStarttime = cTimeRangeDlg.m_dInStarttime;
	m_cDataFile.m_dInEndtime = cTimeRangeDlg.m_dInEndtime;

	// データ読み込み
	if (!m_cDataFile.ReadDataFile(m_nBeginTime, m_nEndTime, TRUE, TRUE, FALSE)) {
		delete this;
		return;
	}

	// ダイアログ表示
	DoModeless();
}

//*****************************************************************************************************
//  1. 関数名
//		CDigitalDataDlg::OnInitDialog
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
BOOL CDigitalDataDlg::OnInitDialog()
{
	CDialogEx::OnInitDialog();

	// 行全体を選択できるようにする
	m_cListData.SetExtendedStyle(LVS_EX_FULLROWSELECT);

	// リストヘッダ設定
	SetListHeader();

	// データ表示
	DispData(1);

	// サンプリング周波数のコンボボックスを設定
	CGeneral::SetSampleRateList(m_cComboSampleRate, m_cDataFile.m_fSampleRate);

	// コントロールの位置を調整
	SetCtlPosition(IDC_LIST_DATA, 0, 0, 1, 1);
	SetCtlPosition(IDC_STATIC_PAGE_CHANGE, 0, 1, 0, 1);
	SetCtlPosition(IDC_BUTTON_PREV_PAGE, 0, 1, 0, 1);
	SetCtlPosition(IDC_BUTTON_NEXT_PAGE, 0, 1, 0, 1);
	SetCtlPosition(IDC_STATIC_SAMPLE_RATE, 0, 1, 0, 1);
	SetCtlPosition(IDC_COMBO_SAMPLE_RATE, 0, 1, 0, 1);
	SetCtlPosition(IDC_EDIT_PAGE_NO, 0, 1, 0, 1);
	SetCtlPosition(IDC_STATIC_PRINT, 0, 1, 0, 1);
	SetCtlPosition(IDC_BUTTON_SELECT_ALL, 0, 1, 0, 1);
	SetCtlPosition(IDC_BUTTON_PRINT, 0, 1, 0, 1);
	SetCtlPosition(IDCANCEL, 1, 1, 1, 1);

	// ツールチップ表示設定
	CGeneral::SetToolTip(m_tooltip, s_aToolTip, this);

	return TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CDigitalDataDlg::SetListHeader
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
void CDigitalDataDlg::SetListHeader()
{
	struct LIxxxOLUMN {
		char *pText;
		int fmt;
		int width;
	} tColumnDataMma[] = {
		{"No", LVCFMT_LEFT, 70},
// 077FD050-GYM961-0_002 2007/9/6 modified by yG ->
//		{"Time", LVCFMT_LEFT, 150},
		{"Time(YYYY/MM/DD hh:mm:ss.tttt)", LVCFMT_LEFT, 150},
// 077FD050-GYM961-0_002 2007/9/6 modified by yG <-
		{"X-Axis(μg)", LVCFMT_LEFT, 90},
		{"Y-Axis(μg)", LVCFMT_LEFT, 90},
		{"Z-Axis(μg)", LVCFMT_LEFT, 90},
		{"D-Temp(℃)", LVCFMT_LEFT, 75},
		{"X-Temp(℃)", LVCFMT_LEFT, 75},
		{"Y-Temp(℃)", LVCFMT_LEFT, 75},
		{"Z-Temp(℃)", LVCFMT_LEFT, 75}
	}, tColumnDataMme[] = {
		{"No", LVCFMT_LEFT, 70},
		{"Time(YYYY/MM/DD hh:mm:ss.tttt)", LVCFMT_LEFT, 150},
		{"X-Axis(μg)", LVCFMT_LEFT, 90},
		{"Y-Axis(μg)", LVCFMT_LEFT, 90},
		{"Z-Axis(μg)", LVCFMT_LEFT, 90},
	}, tColumnDataAsu[] = {
		{"No", LVCFMT_LEFT, 70},
		{"Time(YYYY/MM/DD hh:mm:ss.tttt)", LVCFMT_LEFT, 150},
		{"X-Axis(μg)", LVCFMT_LEFT, 90},
		{"Y-Axis(μg)", LVCFMT_LEFT, 90},
		{"Z-Axis(μg)", LVCFMT_LEFT, 90},
	}, *pColumn;
	int i;
	int nColumn;
	LV_COLUMN lvc;

	// カラムデータ
	switch (m_cDataFile.m_nDataType) {
	case DATA_TYPE_MMA:
		pColumn = tColumnDataMma;
		nColumn  = sizeof(tColumnDataMma) / sizeof(LIxxxOLUMN);
		break;
	case DATA_TYPE_MME:
		pColumn = tColumnDataMme;
		nColumn  = sizeof(tColumnDataMme) / sizeof(LIxxxOLUMN);
		break;
	case DATA_TYPE_ASU:
		pColumn = tColumnDataMme;
		nColumn  = sizeof(tColumnDataMme) / sizeof(LIxxxOLUMN);
		break;
	default:
		return;
	}

	// カラムを設定
	for (i = 0; i < nColumn; i++) {
		lvc.mask = LVCF_FMT | LVCF_WIDTH | LVCF_TEXT;
		lvc.fmt = pColumn->fmt;
		lvc.pszText = pColumn->pText;
		lvc.cx = pColumn->width;
		m_cListData.InsertColumn(i, &lvc);
		pColumn++;
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CDigitalDataDlg::DispData
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		データ表示処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		int		nPageNo		[I] 表示するページ番号
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CDigitalDataDlg::DispData(int nPageNo)
{
	CString sText;

	// ページ番号保存
	m_nPageNo = nPageNo;

	// ページ数取得
	m_nPageNum = (m_cDataFile.m_nDataSize / m_cDataFile.m_nDownRate + PAGE_SIZE - 1) / PAGE_SIZE;

	// 再描画禁止
	m_cListData.SetRedraw(FALSE);

	// 全てのデータを消去
	m_cListData.DeleteAllItems();

	// データのインデックス取得
	int nIndex = (nPageNo - 1) * PAGE_SIZE * m_cDataFile.m_nDownRate;

	// リストコントロールにデータを表示
	for (int i = 0; i < PAGE_SIZE && nIndex < m_cDataFile.m_nDataSize; i++, nIndex += m_cDataFile.m_nDownRate) {
		sText.Format("%d", nIndex / m_cDataFile.m_nDownRate + 1);
		m_cListData.InsertItem(i, sText);

		CDataDateTime Begin;
		Begin.SetTime(m_cDataFile.m_dInStarttime.m_YY
			, m_cDataFile.m_dInStarttime.m_MM
			, m_cDataFile.m_dInStarttime.m_DD
			, (int)((m_nBeginTime + nIndex / m_cDataFile.m_fSampleRate) * 1000));
		sText = Begin.GetStrYMD();
		sText += CGeneral::FormatTime(m_nBeginTime + nIndex / m_cDataFile.m_fSampleRate, 4);
		m_cListData.SetItemText(i, 1, sText);

		sText.Format("%f", m_cDataFile.m_pDataX[nIndex]);
		m_cListData.SetItemText(i, 2, sText);

		sText.Format("%f", m_cDataFile.m_pDataY[nIndex]);
		m_cListData.SetItemText(i, 3, sText);

		sText.Format("%f", m_cDataFile.m_pDataZ[nIndex]);
		m_cListData.SetItemText(i, 4, sText);

		if (m_cDataFile.m_nDataType == DATA_TYPE_MMA) {
			sText.Format("%.2f", m_cDataFile.m_pTempDACM[nIndex]);
			m_cListData.SetItemText(i, 5, sText);

			sText.Format("%.2f", m_cDataFile.m_pTempX[nIndex]);
			m_cListData.SetItemText(i, 6, sText);

			sText.Format("%.2f", m_cDataFile.m_pTempY[nIndex]);
			m_cListData.SetItemText(i, 7, sText);

			sText.Format("%.2f", m_cDataFile.m_pTempZ[nIndex]);
			m_cListData.SetItemText(i, 8, sText);
		}
	}

	// 再描画許可
	m_cListData.SetRedraw(TRUE);

	// ページ番号表示
	m_cEditPageNo.Format("%d / %d", nPageNo, m_nPageNum);

	// 前頁／次頁ボタン有効化
	m_cButtonPrevPage.EnableWindow(m_nPageNo > 1);
	m_cButtonNextPage.EnableWindow(m_nPageNo < m_nPageNum);
}

//*****************************************************************************************************
//  1. 関数名
//		CDigitalDataDlg::OnBnClickedButtonPrevPage
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		前頁ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CDigitalDataDlg::OnBnClickedButtonPrevPage()
{
	if (m_nPageNo > 1)
		DispData(m_nPageNo - 1);
}

//*****************************************************************************************************
//  1. 関数名
//		CDigitalDataDlg::OnBnClickedButtonNextPage
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		次頁ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CDigitalDataDlg::OnBnClickedButtonNextPage()
{
	if (m_nPageNo < m_nPageNum)
		DispData(m_nPageNo + 1);
}

//*****************************************************************************************************
//  1. 関数名
//		CDigitalDataDlg::OnCbnSelchangeComboSampleRate
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		サンプル周波数コンボボックス選択変更処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CDigitalDataDlg::OnCbnSelchangeComboSampleRate()
{
	int nIndex = m_cComboSampleRate.GetCurSel();
	if (nIndex >= 0) {
		// 間引きレート取得
		m_cDataFile.m_nDownRate = (int)m_cComboSampleRate.GetItemData(nIndex);

		// グラフ表示を更新
		DispData(1);
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CDigitalDataDlg::OnBnClickedButtonPrint
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		印刷ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CDigitalDataDlg::OnBnClickedButtonPrint()
{
	int i;

	// 選択されている行があるかチェック
	int nItem = m_cListData.GetItemCount();
	for (i = 0; i < nItem; i++) {
		if (m_cListData.GetItemState(i, LVIS_SELECTED))
			break;
	}
	if (i == nItem) {
		CGeneral::Alert("印刷するデータを選択してください。");
		return;
	}

	CString sTitle = "デジタルデータ";
	CPrintDialog dlg(FALSE);

	// 印刷開始ダイアログ表示
	if(dlg.DoModal() == IDOK) {
		// 印刷用CDC取得
		CDC	dc;
		dc.Attach(dlg.GetPrinterDC());

		// 印刷サイズ取得
		int nWidth = dc.GetDeviceCaps(HORZRES);
		int nHeight = dc.GetDeviceCaps(VERTRES);

		// マージン設定
		CRect rectView(0, 0, nWidth, nHeight);
		rectView.DeflateRect(nWidth / 25, nHeight / 15, nWidth / 25, nHeight / 20);

		// DOCINFOを用意
		DOCINFO	docinfo;
		memset(&docinfo, 0, sizeof(docinfo));
		docinfo.cbSize  = sizeof(docinfo);
		docinfo.lpszDocName = sTitle;

		// 印刷開始
		dc.StartDoc(&docinfo);

		// リスト印刷
		PrintData(dc, rectView, sTitle, m_cListData);

		// 印刷終了
		dc.EndDoc();
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CDigitalDataDlg::PrintData
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		リストデータ印刷処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CDC			&dc				[I] デバイスコンテキスト
//		CRect		rectView		[I] 印刷範囲
//		LPCTSTR		pTitle			[I] タイトル
//		CLixxxtrl	&cLixxxtrl		[I] 印刷するリストコントロール
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CDigitalDataDlg::PrintData(CDC &dc, CRect rectView, LPCTSTR pTitle, CLixxxtrl &cLixxxtrl)
{
	int i, j, k;
	int x, y;
	char textBuf[32];

	// フォント作成
	CFont fontTitle;
	fontTitle.CreatePointFont(80, "MS UI Gothic", &dc);

	// フォントを選択
	CFont *pFontOrg = dc.SelectObject(&fontTitle);

	// ペンを作成
	CPen penBlack(PS_SOLID, 2, RGB(0, 0, 0));

	// ペン、ブラシを選択
	CPen *pPenOrg = dc.SelectObject(&penBlack);
	dc.SelectStockObject(NULL_BRUSH);

	// フォントのサイズを取得
	TEXTMETRIC tm;
	dc.GetTextMetrics(&tm);

	// 文字間スペース設定
	int nLeftSpace = tm.tmAveCharWidth / 2;
	int nTopSpace = tm.tmHeight / 4;
	int nBottomSpace = tm.tmHeight / 4;

	// １行の高さを取得
	int nLineHeight = tm.tmHeight + nTopSpace + nBottomSpace;

	// 選択されている行数を取得
	CUIntArray aSelectedIndex;
	int nItem = cLixxxtrl.GetItemCount();
	for (i = 0; i < nItem; i++) {
		if (cLixxxtrl.GetItemState(i, LVIS_SELECTED))
			aSelectedIndex.Add(i);
	}
	int nLineNum = (int)aSelectedIndex.GetCount();

	// １ページの行数を取得
	int nPageLine = rectView.Height() / nLineHeight;

	// ページ数を取得
	int nPageNum = (nLineNum + nPageLine - 1) / nPageLine;

	// カラム数を取得
	CHeaderCtrl *pHeaderCtrl = cLixxxtrl.GetHeaderCtrl();
	int nColumnNum = pHeaderCtrl->GetItemCount();

	// 全カラムの幅の合計を取得
	HDITEM hditem;
	int nListWidth = 0;
	for (i = 0; i < nColumnNum; i++) {
		hditem.mask = HDI_WIDTH;
		pHeaderCtrl->GetItem(i, &hditem);
		nListWidth += hditem.cxy;
	}

	CString	sBegin, sEnd, sStart;
	CString	sPrTitle, sPrTitle2;

	CDataDateTime Start;
	Start.SetTime(m_cDataFile.m_dInStarttime.m_YY
		, m_cDataFile.m_dInStarttime.m_MM
		, m_cDataFile.m_dInStarttime.m_DD
		, (int)(m_cDataFile.m_fStartSecond * 1000));
	sStart = Start.GetStrYMDHMS();
	sBegin = cLixxxtrl.GetItemText(aSelectedIndex[0], 1);
	sEnd = cLixxxtrl.GetItemText(aSelectedIndex[nLineNum - 1], 1);
	sPrTitle.Format("Title:%s 測定開始時間:%s 印刷範囲:%s-%s"
		, pTitle
		, sStart, sBegin, sEnd);

	switch (m_cDataFile.m_nDataType) {
	case DATA_TYPE_MMA:
		sPrTitle2.Format("SensorID:%d SampleRate:%.2f Cutoff:%d Gain:%d 位置:%s"
			, m_cDataFile.m_nSensorId
			, m_cDataFile.m_fSampleRate
			, m_cDataFile.m_nGain
			, m_cDataFile.m_nCutoff
			, m_cDataFile.m_SensorPos);
		break;
	case DATA_TYPE_MME:
		sPrTitle2.Format("SensorID:%d SampleRate:%g 位置:%s"
			, m_cDataFile.m_nSensorId
			, m_cDataFile.m_fSampleRate
			, m_cDataFile.m_SensorPos);
		break;
	case DATA_TYPE_ASU:
		sPrTitle2.Format("SensorID:%d SampleRate:%g 位置:%s"
			, m_cDataFile.m_nSensorId
			, m_cDataFile.m_fSampleRate
			, m_cDataFile.m_SensorPos);
		break;
	}

	// ページのループ
	int nIndex = 0;
	for (i = 0; i < nPageNum; i++) {
		y = rectView.top;

		// ページ印刷開始
		dc.StartPage();

		// タイトル等印刷
		PrintInfo(dc, rectView, sPrTitle, sPrTitle2);

		// ヘッダを印刷
		x = rectView.left;
		for (j = 0; j < nColumnNum; j++) {
			hditem.mask = HDI_WIDTH | HDI_TEXT;
			hditem.pszText = textBuf;
			hditem.cchTextMax = sizeof(textBuf);
			pHeaderCtrl->GetItem(j, &hditem);

			dc.TextOut(x + nLeftSpace, y + nTopSpace, textBuf);

			x += hditem.cxy * rectView.Width() / nListWidth;
		}
		y += nLineHeight;

		// 行のループ
		for (j = 0; j < nPageLine && nIndex < nLineNum; j++) {
			x = rectView.left;

			// カラムのループ
			for (k = 0; k < nColumnNum; k++) {
				dc.TextOut(x + nLeftSpace, y + nTopSpace, cLixxxtrl.GetItemText(aSelectedIndex[nIndex], k));

				hditem.mask = HDI_WIDTH;
				pHeaderCtrl->GetItem(k, &hditem);
				x += hditem.cxy * rectView.Width() / nListWidth;
			}

			nIndex++;
			y += nLineHeight;
		}

		// 罫線を印刷
		dc.Rectangle(rectView.left, rectView.top, rectView.right, y);
		dc.MoveTo(rectView.left, rectView.top + nLineHeight);
		dc.LineTo(rectView.right, rectView.top + nLineHeight);
		x = rectView.left;
		for (j = 0; j < nColumnNum - 1; j++) {
			hditem.mask = HDI_WIDTH;
			pHeaderCtrl->GetItem(j, &hditem);
			x += hditem.cxy * rectView.Width() / nListWidth;

			dc.MoveTo(x, rectView.top);
			dc.LineTo(x, y);
		}

		// ページ印刷終了
		dc.EndPage();
	}

	// 描画オブジェクトの選択を解除
	dc.SelectObject(pFontOrg);
	dc.SelectObject(pPenOrg);
}

//*****************************************************************************************************
//  1. 関数名
//		CDigitalDataDlg::PrintInfo
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		タイトル等印刷処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CDC			&dc				[I] デバイスコンテキスト
//		CRect		rectView		[I] 印刷範囲
//		LPCTSTR		pTitle			[I] タイトル
//		LPCTSTR		pTitle2			[I] タイトル２
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CDigitalDataDlg::PrintInfo(CDC &dc, CRect rectView, LPCTSTR pTitle, LPCTSTR pTitle2)
{
	// フォント作成
	CFont fontTitle;
	fontTitle.CreatePointFont(80, "MS UI Gothic", &dc);

	// フォントを選択
	CFont *pFontOrg = dc.SelectObject(&fontTitle);

	// タイトルのサイズを取得
	CSize sizeText = dc.GetOutputTextExtent(pTitle);

	// タイトルを印刷
	dc.TextOut(rectView.CenterPoint().x - sizeText.cx / 2, rectView.top - sizeText.cy * 4, pTitle);
	dc.TextOut(rectView.CenterPoint().x - sizeText.cx / 2, (int)(rectView.top - sizeText.cy * 2.5), pTitle2);

	// フォントの選択を解除
	dc.SelectObject(pFontOrg);
}

//*****************************************************************************************************
//  1. 関数名
//		CDigitalDataDlg::OnBnClickedButtonSelectAll
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		全選択ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CDigitalDataDlg::OnBnClickedButtonSelectAll()
{
	int nItem = m_cListData.GetItemCount();
	for (int i = 0; i < nItem; i++)
		m_cListData.SetItemState(i, LVIS_SELECTED, LVIS_SELECTED);

	m_cListData.SetFocus();
}

void CDigitalDataDlg::OnCancel()
{
	DestroyWindow();
}

//*****************************************************************************************************
//  1. 関数名
//		CDigitalDataDlg::PreTranslateMessage
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
BOOL CDigitalDataDlg::PreTranslateMessage(MSG* pMsg)
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
