// MainListView.cpp : 実装ファイル
//

#include "stdafx.h"
#include "MMA_G.h"
#include "MainListView.h"
#include "MMA_GDoc.h"
#include <math.h>


// CMainListView

IMPLEMENT_DYNCREATE(CMainListView, CListView)

CMainListView::CMainListView()
{

}

CMainListView::~CMainListView()
{
}

BEGIN_MESSAGE_MAP(CMainListView, CListView)
END_MESSAGE_MAP()


// CMainListView 診断

#ifdef _DEBUG
void CMainListView::AssertValid() const
{
	CListView::AssertValid();
}

#ifndef _WIN32_WCE
void CMainListView::Dump(CDumpContext& dc) const
{
	CListView::Dump(dc);
}
#endif
#endif //_DEBUG


// CMainListView メッセージ ハンドラ

// ウィンドウ作成前処理
BOOL CMainListView::PreCreateWindow(CREATESTRUCT& cs)
{
	cs.style |= LVS_REPORT | LVS_SHOWSELALWAYS;

	return CListView::PreCreateWindow(cs);
}

// ビュー初期化処理
void CMainListView::OnInitialUpdate()
{
	CListView::OnInitialUpdate();

	// 選択したとき行全体が反転表示するように設定
	GetLixxxtrl().SetExtendedStyle(LVS_EX_FULLROWSELECT);

	// リストヘッダを設定
	SetListHeader();
}

// ビュー更新処理
void CMainListView::OnUpdate(CView* /*pSender*/, LPARAM /*lHint*/, CObject* /*pHint*/)
{
	CMMA_GDoc *pDoc = (CMMA_GDoc *)GetDocument();

	// リストデータ設定
	if ((pDoc->m_nSelectedItem & ID_KIND) == ID_DATA)
		SetListData();
	else
		ResetListData();
}

// リストヘッダ設定処理
void CMainListView::SetListHeader()
{
	struct LIxxxOLUMN {
		char *pText;
		int fmt;
		int width;
	} tColumnData[] = {
		{"項    目", LVCFMT_LEFT, 200},
		{"内　　容", LVCFMT_LEFT, 650}
	}, *pColumn;
	int i;
	int nColumn;
	CString str;
	LV_COLUMN lvc;
	CLixxxtrl &lc = GetLixxxtrl();
	CHeaderCtrl &hc = *lc.GetHeaderCtrl();

	// 全てのアイテムを削除
	lc.DeleteAllItems();

	// 全てのカラムを削除
	while (hc.GetItemCount() != 0)
		lc.DeleteColumn(0);

	// カラムデータ
	pColumn = tColumnData;
	nColumn  = sizeof(tColumnData) / sizeof(LIxxxOLUMN);

	// カラムを設定
	for (i = 0; i < nColumn; i++) {
		lvc.mask = LVCF_FMT | LVCF_WIDTH | LVCF_TEXT;
		lvc.fmt = pColumn->fmt;
		lvc.pszText = pColumn->pText;
		lvc.cx = pColumn->width;
		lc.InsertColumn(i, &lvc);
		pColumn++;
	}
}

// リストデータ設定処理
void CMainListView::SetListData()
{
	CMMA_GDoc *pDoc = (CMMA_GDoc *)GetDocument();
	CLixxxtrl &lixxxtrl = GetLixxxtrl();
	CString path;

	// 全てのアイテムを削除
	lixxxtrl.DeleteAllItems();

	// 選択Noを検索し、FULLPathを取得する
	if (! pDoc->m_TreeDir.SearchIdx(pDoc->m_nSelectedItem, path))
		return;

	// FULLPathのIDBを読む
	CIdbFile idb;
	if (! idb.Read((LPCSTR)path))
		return;

	switch (pDoc->m_nSelectedItem & ID_DATA_TYPE) {
	case DATA_TYPE_MMA:
		SetListDataMma(lixxxtrl, idb);
		break;
	case DATA_TYPE_MME:
		SetListDataMme(lixxxtrl, idb);
		break;
	case DATA_TYPE_ASU:
		SetListDataAsu(lixxxtrl, idb);
		break;
	}
}

void CMainListView::SetListDataMma(CLixxxtrl &lixxxtrl, const CIdbFile &idb)
{
	int	idx = 0;
	CString	buf;

	// データを表示
	// 取得期間
	lixxxtrl.InsertItem(idx, "データ範囲");
	buf.Format("%s/%s/%s %s:%s:%s - %s/%s/%s %s:%s:%s"
		, idb.m_period.Left(4)
		, idb.m_period.Mid(4,2)
		, idb.m_period.Mid(6,2)
		, idb.m_period.Mid(8,2)
		, idb.m_period.Mid(10,2)
		, idb.m_period.Mid(12,2)
		, idb.m_period.Mid(15,4)
		, idb.m_period.Mid(19,2)
		, idb.m_period.Mid(21,2)
		, idb.m_period.Mid(23,2)
		, idb.m_period.Mid(25,2)
		, idb.m_period.Mid(27,2));
	lixxxtrl.SetItemText(idx, 1, buf);

	// タイムゼロ
	idx++;
	lixxxtrl.InsertItem(idx, "データ開始日時");
	buf.Format("%s/%s/%s %s:%s:%s"
		, idb.m_timezero.Left(4)
		, idb.m_timezero.Mid(4,2)
		, idb.m_timezero.Mid(6,2)
		, idb.m_timezero.Mid(8,2)
		, idb.m_timezero.Mid(10,2)
		, idb.m_timezero.Mid(12,2));
	lixxxtrl.SetItemText(idx, 1, buf);

	// ISS Config
	idx++;
	lixxxtrl.InsertItem(idx, "ISSConfiguration");
	lixxxtrl.SetItemText(idx, 1, idb.m_issconfig);

	// ブランク行
	idx++;
	lixxxtrl.InsertItem(idx, "");

	// Sensor Co Sys
	idx++;
	lixxxtrl.InsertItem(idx, "センサ設置位置");
	lixxxtrl.SetItemText(idx, 1, idb.m_sensorcosys);

	// ネットワークID
	idx++;
	buf.Format("%d", idb.m_networkid);
	lixxxtrl.InsertItem(idx, "ネットワークID");
	lixxxtrl.SetItemText(idx, 1, buf);

	// ユニットID
	idx++;
	buf.Format("%d", idb.m_rsuid);
	lixxxtrl.InsertItem(idx, "RSU ID");
	lixxxtrl.SetItemText(idx, 1, buf);

	// センサーID
	idx++;
	buf.Format("%d", idb.m_sensorid);
	lixxxtrl.InsertItem(idx, "TAA ID");
	lixxxtrl.SetItemText(idx, 1, buf);

	// サンプルレート
	idx++;
	buf.Format("%.2lf [Hz]", idb.m_samplerate);
	lixxxtrl.InsertItem(idx, "SampleRate");
	lixxxtrl.SetItemText(idx, 1, buf);

	// カットオフ周波数
	idx++;
	buf.Format("%d [Hz]", idb.m_cutoff);
	lixxxtrl.InsertItem(idx, "Cutoff Frequency");
	lixxxtrl.SetItemText(idx, 1, buf);

	// ゲイン
	idx++;
	buf.Format("%d", idb.m_gain);
	lixxxtrl.InsertItem(idx, "GAIN");
	lixxxtrl.SetItemText(idx, 1, buf);

	// Bias Co Eff
	idx++;
	buf.Format("%d", idb.m_biascoeff);
	lixxxtrl.InsertItem(idx, "BiasCoeff");
	lixxxtrl.SetItemText(idx, 1, buf);

	// スケールファクター
	idx++;
	buf.Format("%d", idb.m_scalefactor);
	lixxxtrl.InsertItem(idx, "ScaleFactor");
	lixxxtrl.SetItemText(idx, 1, buf);

	// Data Quality
	idx++;
	lixxxtrl.InsertItem(idx, "Data Quality Measure");
	lixxxtrl.SetItemText(idx, 1, idb.m_dataquality);

	// ブランク行
	idx++;
	lixxxtrl.InsertItem(idx, "");

	// ダウンリンク日
	idx++;
	lixxxtrl.InsertItem(idx, "ダウンリンク日");
	lixxxtrl.SetItemText(idx, 1, idb.m_downlink);

	// 保存パス名
	idx++;
	lixxxtrl.InsertItem(idx, "データ保存先");
	lixxxtrl.SetItemText(idx, 1, idb.m_path);

	// コメント
	idx++;
	lixxxtrl.InsertItem(idx, "備考");
	lixxxtrl.SetItemText(idx, 1, idb.m_comment);
}

void CMainListView::SetListDataMme(CLixxxtrl &lixxxtrl, const CIdbFile &idb)
{
	int	idx = 0;
	CString	buf;
	// データを表示
	// 取得期間
	lixxxtrl.InsertItem(idx, "データ範囲");
	buf.Format("%s/%s/%s %s:%s:%s - %s/%s/%s %s:%s:%s"
		, idb.m_period.Left(4)
		, idb.m_period.Mid(4,2)
		, idb.m_period.Mid(6,2)
		, idb.m_period.Mid(8,2)
		, idb.m_period.Mid(10,2)
		, idb.m_period.Mid(12,2)
		, idb.m_period.Mid(15,4)
		, idb.m_period.Mid(19,2)
		, idb.m_period.Mid(21,2)
		, idb.m_period.Mid(23,2)
		, idb.m_period.Mid(25,2)
		, idb.m_period.Mid(27,2));
	lixxxtrl.SetItemText(idx, 1, buf);
	// タイムゼロ
	idx++;
	lixxxtrl.InsertItem(idx, "データ開始日時");
	buf.Format("%s/%s/%s %s:%s:%s"
		, idb.m_timezero.Left(4)
		, idb.m_timezero.Mid(4,2)
		, idb.m_timezero.Mid(6,2)
		, idb.m_timezero.Mid(8,2)
		, idb.m_timezero.Mid(10,2)
		, idb.m_timezero.Mid(12,2));
	lixxxtrl.SetItemText(idx, 1, buf);
	// ブランク行
	idx++;
	lixxxtrl.InsertItem(idx, "");
	// Sensor Co Sys
	idx++;
	lixxxtrl.InsertItem(idx, "センサ設置位置");
	lixxxtrl.SetItemText(idx, 1, idb.m_sensorcosys);
	// センサーID
	idx++;
	buf.Format("%d", idb.m_sensorid);
	lixxxtrl.InsertItem(idx, "Sensor ID");
	lixxxtrl.SetItemText(idx, 1, buf);
	// サンプルレート
	idx++;
	buf.Format("%g [Hz]", idb.m_samplerate);
	lixxxtrl.InsertItem(idx, "Sample Rate");
	lixxxtrl.SetItemText(idx, 1, buf);
	// ブランク行
	idx++;
	lixxxtrl.InsertItem(idx, "");
	// ダウンリンク日
	idx++;
	lixxxtrl.InsertItem(idx, "ダウンリンク日");
	lixxxtrl.SetItemText(idx, 1, idb.m_downlink);
	// 保存パス名
	idx++;
	lixxxtrl.InsertItem(idx, "データ保存先");
	lixxxtrl.SetItemText(idx, 1, idb.m_path);
	// コメント
	idx++;
	lixxxtrl.InsertItem(idx, "備考");
	lixxxtrl.SetItemText(idx, 1, idb.m_comment);
}

void CMainListView::SetListDataAsu(CLixxxtrl &lixxxtrl, const CIdbFile &idb)
{
	int	idx = 0;
	CString	buf;
	// データを表示
	// 取得期間
	lixxxtrl.InsertItem(idx, "データ範囲");
	buf.Format("%s/%s/%s %s:%s:%s - %s/%s/%s %s:%s:%s"
		, idb.m_period.Left(4)
		, idb.m_period.Mid(4,2)
		, idb.m_period.Mid(6,2)
		, idb.m_period.Mid(8,2)
		, idb.m_period.Mid(10,2)
		, idb.m_period.Mid(12,2)
		, idb.m_period.Mid(15,4)
		, idb.m_period.Mid(19,2)
		, idb.m_period.Mid(21,2)
		, idb.m_period.Mid(23,2)
		, idb.m_period.Mid(25,2)
		, idb.m_period.Mid(27,2));
	lixxxtrl.SetItemText(idx, 1, buf);
	// タイムゼロ
	idx++;
	lixxxtrl.InsertItem(idx, "データ開始日時");
	buf.Format("%s/%s/%s %s:%s:%s"
		, idb.m_timezero.Left(4)
		, idb.m_timezero.Mid(4,2)
		, idb.m_timezero.Mid(6,2)
		, idb.m_timezero.Mid(8,2)
		, idb.m_timezero.Mid(10,2)
		, idb.m_timezero.Mid(12,2));
	lixxxtrl.SetItemText(idx, 1, buf);
	// ブランク行
	idx++;
	lixxxtrl.InsertItem(idx, "");
	// Sensor Co Sys
	idx++;
	lixxxtrl.InsertItem(idx, "センサ設置位置");
	lixxxtrl.SetItemText(idx, 1, idb.m_sensorcosys);
	// センサーID
	idx++;
	buf.Format("%d", idb.m_sensorid);
	lixxxtrl.InsertItem(idx, "Sensor ID");
	lixxxtrl.SetItemText(idx, 1, buf);
	// サンプルレート
	idx++;
	buf.Format("%g [Hz]", idb.m_samplerate);
	lixxxtrl.InsertItem(idx, "Sample Rate");
	lixxxtrl.SetItemText(idx, 1, buf);
	// ブランク行
	idx++;
	lixxxtrl.InsertItem(idx, "");
	// ダウンリンク日
	idx++;
	lixxxtrl.InsertItem(idx, "ダウンリンク日");
	lixxxtrl.SetItemText(idx, 1, idb.m_downlink);
	// 保存パス名
	idx++;
	lixxxtrl.InsertItem(idx, "データ保存先");
	lixxxtrl.SetItemText(idx, 1, idb.m_path);
	// コメント
	idx++;
	lixxxtrl.InsertItem(idx, "備考");
	lixxxtrl.SetItemText(idx, 1, idb.m_comment);
}

// リストデータリセット処理
void CMainListView::ResetListData()
{
	CLixxxtrl &lixxxtrl = GetLixxxtrl();

	// 全てのアイテムを削除
	lixxxtrl.DeleteAllItems();
}
