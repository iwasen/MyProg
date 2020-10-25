// MMA_GDoc.cpp : CMMA_GDoc クラスの実装
//

#include "stdafx.h"
#include "MMA_G.h"
#include "MMA_GDoc.h"
#include "GraphFrm.h"
#include "DigitalDataDlg.h"
#include "ConvDlg.h"
#include "ConvMmeDlg.h"
#include "ConvAsuDlg.h"
#include "IdbFile.h"
#include "CsvData.h"
#include "General.h"
#include "Define.h"
#include "ChangeInfoDlg.h"
#include "ChangeInfoMmeDlg.h"
#include "ChangeInfoAsuDlg.h"
#include "RealtimeData.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#endif


// CMMA_GDoc

IMPLEMENT_DYNCREATE(CMMA_GDoc, CDocument)

BEGIN_MESSAGE_MAP(CMMA_GDoc, CDocument)
	ON_COMMAND(IDM_DISP_GRAPH, &CMMA_GDoc::OnDispGraph)
	ON_COMMAND(IDM_DISP_DIGITAL_DATA, &CMMA_GDoc::OnDispDigitalData)
	ON_COMMAND(IDM_CONVERT, &CMMA_GDoc::OnConvert)
	ON_COMMAND(IDM_OUTPUT_CSV, &CMMA_GDoc::OnOutputCsv)
	ON_COMMAND(IDM_DELETE, &CMMA_GDoc::OnDelete)
	ON_COMMAND(IDM_EXEC, &CMMA_GDoc::OnExec)
	ON_COMMAND(IDM_RELOAD, &CMMA_GDoc::OnReload)
	ON_COMMAND(IDM_DISP_TEMP_GRAPH, &CMMA_GDoc::OnDispTempGraph)
	ON_COMMAND(IDM_CHANGE_INFO, &CMMA_GDoc::OnChangeInfo)
	ON_UPDATE_COMMAND_UI(IDM_CHANGE_INFO, &CMMA_GDoc::OnUpdateChangeInfo)
	ON_UPDATE_COMMAND_UI(IDM_DISP_GRAPH, &CMMA_GDoc::OnUpdateDispGraph)
	ON_UPDATE_COMMAND_UI(IDM_DISP_TEMP_GRAPH, &CMMA_GDoc::OnUpdateDispTempGraph)
	ON_UPDATE_COMMAND_UI(IDM_DISP_DIGITAL_DATA, &CMMA_GDoc::OnUpdateDispDigitalData)
	ON_UPDATE_COMMAND_UI(IDM_OUTPUT_CSV, &CMMA_GDoc::OnUpdateOutputCsv)
	ON_COMMAND(IDM_REALTIME_START, &CMMA_GDoc::OnRealtimeStart)
	ON_UPDATE_COMMAND_UI(IDM_REALTIME_START, &CMMA_GDoc::OnUpdateRealtimeStart)
	ON_COMMAND(IDM_REALTIME_STOP, &CMMA_GDoc::OnRealtimeStop)
	ON_UPDATE_COMMAND_UI(IDM_REALTIME_STOP, &CMMA_GDoc::OnUpdateRealtimeStop)
	ON_COMMAND(IDM_EXPORT, &CMMA_GDoc::OnExport)
	ON_UPDATE_COMMAND_UI(IDM_DELETE, &CMMA_GDoc::OnUpdateDelete)
	ON_COMMAND(IDM_CONVERT_MME, &CMMA_GDoc::OnConvertMme)
	ON_COMMAND(IDM_CONVERT_ASU, &CMMA_GDoc::OnConvertAsu)
END_MESSAGE_MAP()


// CMMA_GDoc コンストラクション/デストラクション

CMMA_GDoc::CMMA_GDoc()
{
	m_nSelectedItem = 0;
}

CMMA_GDoc::~CMMA_GDoc()
{
}

BOOL CMMA_GDoc::OnNewDocument()
{
	if (!CDocument::OnNewDocument())
		return FALSE;

	return TRUE;
}




// CMMA_GDoc シリアル化

void CMMA_GDoc::Serialize(CArchive& ar)
{
	if (ar.IsStoring())
	{
		// TODO: 格納するコードをここに追加してください。
	}
	else
	{
		// TODO: 読み込むコードをここに追加してください。
	}
}


// CMMA_GDoc 診断

#ifdef _DEBUG
void CMMA_GDoc::AssertValid() const
{
	CDocument::AssertValid();
}

void CMMA_GDoc::Dump(CDumpContext& dc) const
{
	CDocument::Dump(dc);
}
#endif //_DEBUG


// CMMA_GDoc コマンド

// 加速度グラフ表示
void CMMA_GDoc::OnDispGraph()
{
	// 選択データ判定
	if ((m_nSelectedItem & ID_KIND) == ID_DATA) {
		CString path, fname;
		// 選択Noを検索し、FULLPathを取得する
		if (m_TreeDir.SearchIdx(m_nSelectedItem, path)) {
			// FULLPathのIDBを読む
			CIdbFile idb;
			// IDBファイル名を取得する
			if (idb.GetFname(path, fname)) {
				CGraphFrame* pFrame = new CGraphFrame;
				if (!pFrame->DispGraph(fname, GRAPH_ACCEL, m_nSelectedItem & ID_DATA_TYPE))
					delete pFrame;
			}
		}
	}
}

// 温度グラフ表示
void CMMA_GDoc::OnDispTempGraph()
{
	// 選択データ判定
	if ((m_nSelectedItem & ID_KIND) == ID_DATA) {
		CString path, fname;
		// 選択Noを検索し、FULLPathを取得する
		if (m_TreeDir.SearchIdx(m_nSelectedItem, path)) {
			// FULLPathのIDBを読む
			CIdbFile idb;
			// IDBファイル名を取得する
			if (idb.GetFname(path, fname)) {
				CGraphFrame* pFrame = new CGraphFrame;
				if (!pFrame->DispGraph(fname, GRAPH_TEMP, m_nSelectedItem & ID_DATA_TYPE))
					delete pFrame;
			}
		}
	}
}

// デジタル数値表示
void CMMA_GDoc::OnDispDigitalData()
{
	// 選択データ判定
	if ((m_nSelectedItem & ID_KIND) == ID_DATA) {
		CString path, fname;
		// 選択Noを検索し、FULLPathを取得する
		if (m_TreeDir.SearchIdx(m_nSelectedItem, path)) {
			// FULLPathのIDBを読む
			CIdbFile idb;
			// IDBファイル名を取得する
			if (idb.GetFname(path, fname)) {
				CDigitalDataDlg* pdlg = new CDigitalDataDlg;
				pdlg->DispList(fname, m_nSelectedItem & ID_DATA_TYPE);
			}
		}
	}
}

// MMA工学値変換処理
void CMMA_GDoc::OnConvert()
{
	CConvDlg dlg;
	dlg.SetDocPtr(this);
	dlg.DoModal();
}

// CSV出力処理
void CMMA_GDoc::OnOutputCsv()
{
	// 選択データ判定
	if ((m_nSelectedItem & ID_KIND) == ID_DATA) {
		CString path, fname;
		// 選択Noを検索し、FULLPathを取得する
		if (m_TreeDir.SearchIdx(m_nSelectedItem, path)) {
			// FULLPathのIDBを読む
			CIdbFile idb;
			// IDBファイル名を取得する
			if (idb.GetFname(path, fname)) {
				CCsvData dlg;
				dlg.DspModal(fname, m_nSelectedItem & ID_DATA_TYPE);
			}
		}
	}
}

// 指定したフォルダを削除する
void CMMA_GDoc::OnDelete()
{
	CString path;

	CString a_strMsg;
	// 表示する文字列を作成する。
	a_strMsg = _T("このPCから，指定フォルダ以下の，工学値変換済ファイルを含む全てのファイルが削除されます。\n本当に削除しますか？");

	// 選択Noを検索し、FULLPathを取得する
	if (m_TreeDir.SearchIdx(m_nSelectedItem, path)) {
		if (AfxMessageBox(a_strMsg, MB_YESNO) == IDYES) {
			CGeneral::DelDir((LPCSTR)path);
			UpdateAllViews(NULL);
		}
	}
}

// 他プログラム起動
void CMMA_GDoc::OnExec()
{
	WinExec(gToolExe, SW_SHOW);
}

// 最新の情報に更新
void CMMA_GDoc::OnReload()
{
	UpdateAllViews(NULL);
}

// 情報変更処理
void CMMA_GDoc::OnChangeInfo()
{
	CString path;

	if (m_TreeDir.SearchIdx(m_nSelectedItem, path)) {
		switch (m_nSelectedItem & ID_DATA_TYPE) {
		case DATA_TYPE_MMA:
			{
				CChangeInfoDlg dlg;
				if (dlg.ChangeInfo(path) == IDOK)
					UpdateAllViews(NULL, (LPARAM)(LPCTSTR)dlg.m_sIdbPathName);
			}
			break;
		case DATA_TYPE_MME:
			{
				CChangeInfoMmeDlg dlg;
				if (dlg.ChangeInfo(path) == IDOK)
					UpdateAllViews(NULL, (LPARAM)(LPCTSTR)dlg.m_sIdbPathName);
			}
			break;
		case DATA_TYPE_ASU:
			{
				CChangeInfoAsuDlg dlg;
				if (dlg.ChangeInfo(path) == IDOK)
					UpdateAllViews(NULL, (LPARAM)(LPCTSTR)dlg.m_sIdbPathName);
			}
			break;
		}
	}
}

// 情報変更メニュー有効化設定
void CMMA_GDoc::OnUpdateChangeInfo(CCmdUI *pCmdUI)
{
	pCmdUI->Enable((m_nSelectedItem & ID_KIND) == ID_DATA);
}

// 加速度グラフメニュー有効化設定
void CMMA_GDoc::OnUpdateDispGraph(CCmdUI *pCmdUI)
{
	pCmdUI->Enable((m_nSelectedItem & ID_KIND) == ID_DATA);
}

// 温度グラフメニュー有効化設定
void CMMA_GDoc::OnUpdateDispTempGraph(CCmdUI *pCmdUI)
{
	pCmdUI->Enable((m_nSelectedItem & ID_KIND) == ID_DATA && (m_nSelectedItem & ID_DATA_TYPE) == DATA_TYPE_MMA);
}

// 数値データメニュー有効化設定
void CMMA_GDoc::OnUpdateDispDigitalData(CCmdUI *pCmdUI)
{
	pCmdUI->Enable((m_nSelectedItem & ID_KIND) == ID_DATA);
}

// CSV出力メニュー有効化設定
void CMMA_GDoc::OnUpdateOutputCsv(CCmdUI *pCmdUI)
{
	pCmdUI->Enable((m_nSelectedItem & ID_KIND) == ID_DATA);
}

// リアルタイム表示開始処理
void CMMA_GDoc::OnRealtimeStart()
{
	// 開始確認メッセージ
	if (AfxMessageBox("リアルタイム表示を開始しますか？", MB_OKCANCEL | MB_ICONQUESTION) == IDCANCEL)
		return;

	// iniファイルのREALTIME_DATA_PATH設定チェック
	if (gRealtimeDataPath[0] == '\0') {
		AfxMessageBox("iniファイルに REALTIME_DATA_PATH が設定されていないため、リアルタイム表示を開始できません。", MB_OK | MB_ICONEXCLAMATION);
		return;
	}

	// iniファイルのREALTIME_TEMP_PATH設定チェック
	if (gRealtimeTempPath[0] == '\0') {
		AfxMessageBox("iniファイルに REALTIME_TEMP_PATH が設定されていないため、リアルタイム表示を開始できません。", MB_OK | MB_ICONEXCLAMATION);
		return;
	}

	// リアルタイム表示開始
	g_RealtimeData.Start();
}

// リアルタイム表示開始メニュー有効化設定
void CMMA_GDoc::OnUpdateRealtimeStart(CCmdUI *pCmdUI)
{
	// リアルタイム表示が開始されていなければ有効
	pCmdUI->Enable(!g_RealtimeData.m_bStartFlag);
}

// リアルタイム表示終了処理
void CMMA_GDoc::OnRealtimeStop()
{
	// 終了確認メッセージ
	if (AfxMessageBox("リアルタイム表示を終了しますか？", MB_OKCANCEL | MB_ICONQUESTION) == IDCANCEL)
		return;

	// リアルタイム表示終了
	g_RealtimeData.Stop();
}

// リアルタイム表示終了メニュー有効化設定
void CMMA_GDoc::OnUpdateRealtimeStop(CCmdUI *pCmdUI)
{
	// リアルタイム表示が開始されていれば有効
	pCmdUI->Enable(g_RealtimeData.m_bStartFlag);
}

// CSVファイルエクスポート処理
void CMMA_GDoc::OnExport()
{
	CFileDialog dlg(FALSE, "csv", NULL, OFN_OVERWRITEPROMPT, _T("CSV ファイル (*.csv)|*.csv||"));
	if (dlg.DoModal() == IDOK) {
		CStringArray saCsvData;

		POSITION pos = GetFirstViewPosition();
		CTreeView *pView = (CTreeView *)GetNextView(pos);
		CTreeCtrl &treeCtrl = pView->GetTreeCtrl();
		HTREEITEM hTree = treeCtrl.GetSelectedItem();
		if (hTree != NULL)
			GetCsvData(treeCtrl, hTree, saCsvData);

		CStdioFile oFile;
		oFile.Open(dlg.GetPathName(), CFile::modeCreate | CFile::modeWrite | CFile::shareDenyWrite);

		oFile.WriteString(
			"データ範囲,"
			"データ開始日時,"
			"ISSConfiguration,"
			"センサ設置位置,"
			"ネットワークID,"
			"RSU ID,"
			"TAA ID,"
			"SampleRate,"
			"Cutoff Frequency,"
			"GAIN,"
			"BiasCoeff,"
			"ScaleFactor,"
			"Data Quality Measure,"
			"ダウンリンク日,"
			"データ保存先,"
			"備考\n");

		for (int i = 0; i < saCsvData.GetCount(); i++)
			oFile.WriteString(saCsvData[i] + "\n");
	}
}

// CSVデータ取得処理
void CMMA_GDoc::GetCsvData(CTreeCtrl &treeCtrl, HTREEITEM hTree, CStringArray &saCsvData)
{
	ST_TREE *st = m_TreeDir.GetTreeByIdx((int)treeCtrl.GetItemData(hTree));

	if (st != NULL && (st->m_idx & ID_KIND) == ID_DATA) {
		CIdbFile idb;
		idb.Read(st->m_fullPath);

		CString csv;
		CString buf;
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
		AddCsvData(csv, buf);
		// タイムゼロ
		buf.Format("%s/%s/%s %s:%s:%s"
			, idb.m_timezero.Left(4)
			, idb.m_timezero.Mid(4,2)
			, idb.m_timezero.Mid(6,2)
			, idb.m_timezero.Mid(8,2)
			, idb.m_timezero.Mid(10,2)
			, idb.m_timezero.Mid(12,2));
		AddCsvData(csv, buf);
		// ISS Config
		AddCsvData(csv, idb.m_issconfig);
		// Sensor Co Sys
		AddCsvData(csv, idb.m_sensorcosys);
		// ネットワークID
		buf.Format("%d", idb.m_networkid);
		AddCsvData(csv, buf);
		// ユニットID
		buf.Format("%d", idb.m_rsuid);
		AddCsvData(csv, buf);
		// センサーID
		buf.Format("%d", idb.m_sensorid);
		AddCsvData(csv, buf);
		// サンプルレート
		buf.Format("%.2lf(Hz)", idb.m_samplerate);
		AddCsvData(csv, buf);
		// カットオフ周波数
		buf.Format("%d(Hz)", idb.m_cutoff);
		AddCsvData(csv, buf);
		// ゲイン
		buf.Format("%d", idb.m_gain);
		AddCsvData(csv, buf);
		// Bias Co Eff
		buf.Format("%d", idb.m_biascoeff);
		AddCsvData(csv, buf);
		// スケールファクター
		buf.Format("%d", idb.m_scalefactor);
		AddCsvData(csv, buf);
		// Data Quality
		AddCsvData(csv, idb.m_dataquality);
		// ダウンリンク日
		AddCsvData(csv, idb.m_downlink);
		// 保存パス名
		AddCsvData(csv, idb.m_path);
		// コメント
		AddCsvData(csv, idb.m_comment);

		saCsvData.Add(csv);
	} else {
		HTREEITEM hTreeChild = treeCtrl.GetChildItem(hTree);
		while (hTreeChild) {
			GetCsvData(treeCtrl, hTreeChild, saCsvData);
			hTreeChild = treeCtrl.GetNextSiblingItem(hTreeChild);
		}
	}
}

void CMMA_GDoc::AddCsvData(CString &csv, CString data)
{
	data.Replace("\"", "\"\"");

	if (data.FindOneOf(",") != -1)
		data = "\"" + data + "\"";

	if (!csv.IsEmpty())
		csv += ",";

	csv += data;
}

void CMMA_GDoc::OnUpdateDelete(CCmdUI *pCmdUI)
{
	pCmdUI->Enable((m_nSelectedItem & ID_KIND) == ID_FOLDER || (m_nSelectedItem & ID_KIND) == ID_DATA);
}

// MMA工学値変換処理
void CMMA_GDoc::OnConvertMme()
{
	CConvMmeDlg dlg;
	dlg.ShowDialog(this);
}

void CMMA_GDoc::OnConvertAsu()
{
	CConvAsuDlg dlg;
	dlg.ShowDialog(this);
}
