//*****************************************************************************************************
//  1. ファイル名
//		ManualEditDocument.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		CManualEditDocument クラスのインプリメンテーション
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************

#include "stdafx.h"
#include "WorkScheduler.h"

#include "MenuDispCtrl.h"

#include "ManualEditWnd001.h"
#include "AboutDlg.h"
#include "ManualEditDocument.h"
#include "ManualEditFrameWnd.h"
#include "ManualViewTypeDlg.h"
#include "DataManager.h"
#include "SetKittingDlg.h"
#include "SetKyotsuKoteiDlg.h"
#include "KatashikiKiseiMatrixDlg.h"
#include "ManualEditSublineDlg.h"
#include "SaveProjectDlg.h"
#include "ManualEditView1.h"

// Modify ... ( ADD )
#include "FileExportDlg.h"
// By K.Kimura ( xxxxx )07.02.06

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

IMPLEMENT_DYNCREATE(CManualEditDocument, CDocument)

//*****************************************************************************************************
//  1. 関数名
//		CManualEditDocument::CManualEditDocument
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		コンストラクタ
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		クラス変数の初期化、タイトル表示、グラフデータ作成を行う。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
CManualEditDocument::CManualEditDocument()
{
	// クラス変数初期化
// Modify ... ( DELETE )
//	m_nGraphKishu = 0;
// By Y.Itabashi (xxxxx) 2007.02.26
	m_nGraphKind = 0;
	m_bSlimMode = FALSE;
	m_pDrop = NULL;
	m_nKittingIndex = -1;
	m_bDispNewMove = TRUE;
	m_bSelectKitting = FALSE;
// Modify ... ( ADD )
	m_aSelectYosoSagyogun.RemoveAll();
	m_pSelectWnd = NULL;
	m_pDragWnd = NULL;
// By Y.Itabashi (xxxxx) 2007.02.27

	// 最新移動フラグクリア
	g_pDataManager->ClearNewMoveFlag();

	// キット化解除フラグクリア
	g_pDataManager->ClearKittingCancelFlag();

	// タイトル表示
	SetWindowTitle();

	// グラフデータ作成
	g_pDataManager->SetSagyojun();
	MakeGraphData();

	// Undoバッファリセット
	g_pDataManager->ResetUndoBuf();

}


BEGIN_MESSAGE_MAP(CManualEditDocument, CDocument)
	//{{AFX_MSG_MAP(CManualEditDocument)
	ON_BN_CLICKED(IDC_RADIO_GRAPH_KOTEI, OnRadioGraphKotei)
	ON_BN_CLICKED(IDC_RADIO_GRAPH_YOSOSAGYOGUN, OnRadioGraphYosoSagyogun)
	ON_COMMAND(IDM_WINDOW_VIEW_TYPE, OnWindowViewType)
	ON_COMMAND(IDM_HELP_VERSION, OnHelpVersion)
	ON_COMMAND(IDM_VIEW_SLIM_GRAPH, OnViewSlimGraph)
	ON_UPDATE_COMMAND_UI(IDM_VIEW_SLIM_GRAPH, OnUpdateViewSlimGraph)
	ON_COMMAND(IDM_VIEW_SELECT_SUBLINE, OnViewSelectSubline)
	ON_COMMAND(IDM_TOOL_KITTING_SET, OnToolKittingSet)
	ON_COMMAND(IDM_TOOL_LINE_KISEI, OnToolLineKisei)
	ON_COMMAND(IDM_TOOL_KATASHIKI_KISEI, OnToolKatashikiKisei)
	ON_COMMAND(IDM_TOOL_KYOTSU_KOTEI, OnToolKyotsuKotei)
	ON_UPDATE_COMMAND_UI(IDM_TOOL_LINE_KISEI, OnUpdateToolLineKisei)
	ON_UPDATE_COMMAND_UI(IDM_TOOL_KATASHIKI_KISEI, OnUpdateToolKatashikiKisei)
	ON_COMMAND(IDM_FILE_SAVE, OnFileSave)
	ON_COMMAND(IDM_FILE_SAVE_AS, OnFileSaveAs)
	ON_COMMAND(IDM_EDIT_UNDO, OnEditUndo)
	ON_UPDATE_COMMAND_UI(IDM_EDIT_UNDO, OnUpdateEditUndo)
	ON_COMMAND(IDM_EDIT_REDO, OnEditRedo)
	ON_UPDATE_COMMAND_UI(IDM_EDIT_REDO, OnUpdateEditRedo)
	ON_COMMAND(IDM_WINDOW_SEIRETSU, OnWindowSeiretsu)
	ON_COMMAND(IDM_FILE_EXPORT, OnFileExport)
	ON_UPDATE_COMMAND_UI(IDM_TOOL_KITTING_SET, OnUpdateToolKittingSet)
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()

//*****************************************************************************************************
//  1. 関数名
//		CManualEditDocument::OnSelchangeComboKishu
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		機種コンボボックス選択変更処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		グラフを選択された機種に変更する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
/*void CManualEditDocument::OnSelchangeComboKishu()
{
	CManualEditFrameWnd *pFrameWnd = (CManualEditFrameWnd *)AfxGetMainWnd();

	// グラフを選択された機種に変更
	ChangeGraphKishu(pFrameWnd->GetSelectedKishu());
}*/

//*****************************************************************************************************
//  1. 関数名
//		CManualEditDocument::OnRadioGraphKotei
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		工程表示ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		グラフを工程表示に変更する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CManualEditDocument::OnRadioGraphKotei()
{
	// グラフを工程表示に変更
	ChangeGraphKind(GRAPH_KOTEI);
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditDocument::OnRadioGraphYosoSagyogun
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		要素作業群表示ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		グラフを工程表示に変更する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CManualEditDocument::OnRadioGraphYosoSagyogun()
{
	// グラフを要素作業群表示に変更
	ChangeGraphKind(GRAPH_YOSO_SAGYOGUN);
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditDocument::MakeGraphData
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		グラフ表示データ作成
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		グラフ表示に必要なデータを作成する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CManualEditDocument::MakeGraphData()
{
	// 既存のグラフデータを削除
	m_aGraphSubline.RemoveAll();

	// サブライン別データ作成
	int nSublineNum = g_pDataManager->GetSublineNum();
	for (int nSublineId = 0; nSublineId < nSublineNum; nSublineId++) {
		SSubline *pSubline = g_pDataManager->GetSublineData(nSublineId);

		if (pSubline->bManualEditFlag)
			MakeGraphDataSubline(nSublineId);
	}

	// キット化データ作成
	MakeGraphDataKitting();
}

void CManualEditDocument::MakeGraphDataSubline(int nSublineId)
{
	SSubline *pSubline = g_pDataManager->GetSublineData(nSublineId);
	CYosoSagyogunArray &aYosoSagyogun = pSubline->aYosoSagyogun;
	double fTime;
	SGraphSagyosha *pGraphSagyosha;
	CString sSagyoshaName;
	int nSagyoshaId;
	int nYosoSagyogunId;
	CKyotsuKoteiGroupArray aKyotsuKoteiGroup;
	int aKoteiGraphBlockIndex[MAX_KISHU];
	CSagyojunArray aSagyojun;

	g_pDataManager->GetKyotsuKoteiGroup(nSublineId, aKyotsuKoteiGroup);

	SGraphSubline *pGraphSubline = m_aGraphSubline.AddElement();
	pGraphSubline->nSublineId = nSublineId;
	pGraphSubline->sSublineName = pSubline->sSublineName;

	// 全生産台数、作業者数、機種数を取得
// Modify ... ( CHANGE )
	m_nTotalSeisanDaisu = g_pDataManager->GetTotalSeisanDaisu( nSublineId );
//	m_nTotalSeisanDaisu = g_pDataManager->GetTotalSeisanDaisu();
// By Y.Itabashi (xxxxx) 2007.02.11
	int nSagyoshaNum = g_pDataManager->GetSagyoshaNum(nSublineId);
	int nKishuNum = g_pDataManager->GetKishuNum();

	g_pDataManager->GetSagyojun(nSublineId, -1, aSagyojun);
	int nSagyojunNum = aSagyojun.GetSize();

	// 作業者によるループ
// Modify ... ( CHANGE )
// --Delete 2007/03/06 aizawa 不懸(83)
/*
	int nCheckOKSagyoshaNo = -1;
	// 作業者番号順に表示をする
	for( int icnt = 0; icnt < nSagyoshaNum; icnt++ ){

		// 作業者番号を小さいものから順に作業者IDを取得してグラフを作成
		CString sSagyoshaNo;
		CString sCheckSagyoshaNo;
		sCheckSagyoshaNo.Format( "%03d", icnt+1 );
		int jcnt = 0;
		int nCheckSagyoshaNo = 9999;
		while ( jcnt < nSagyoshaNum ){
			sSagyoshaName = g_pDataManager->GetSagyoshaName(nSublineId, jcnt);
			sSagyoshaNo = sSagyoshaName.Mid( 1, 3 );
			int nSagyoshaNo = atoi( (LPCTSTR)sSagyoshaNo );
			if( nCheckOKSagyoshaNo < nSagyoshaNo && nSagyoshaNo < nCheckSagyoshaNo ){
				nCheckSagyoshaNo = nSagyoshaNo;
				nSagyoshaId = jcnt;
			}
			jcnt++;
		}
		nCheckOKSagyoshaNo = nCheckSagyoshaNo;
*/
// --Delete
//	for (nSagyoshaId = 0; nSagyoshaId < nSagyoshaNum; nSagyoshaId++) {
// --Add 2007/03/06 aizawa 不懸(83)
	for (nSagyoshaId = 0; nSagyoshaId < nSagyoshaNum; nSagyoshaId++) {
// --Add
// By Y.Itabashi (xxxxx) 2007.02.11
		// 作業者名を取得
		sSagyoshaName = g_pDataManager->GetSagyoshaName(nSublineId, nSagyoshaId);

		// 作業者ごとの情報を作成
		pGraphSagyosha = pGraphSubline->aGraphSagyosha.AddElement(sSagyoshaName);
//		pGraphSagyosha->sSagyoshaDispName = "作業者" + sSagyoshaName.Mid(1);
		pGraphSagyosha->sSagyoshaDispName = sSagyoshaName;
		pGraphSagyosha->sSagyoshaName = sSagyoshaName;
		pGraphSagyosha->fTime = 0;
		pGraphSagyosha->nSagyoshaId = nSagyoshaId;
		pGraphSagyosha->bKitting = FALSE;

		// 機種によるループ
		for (int nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
			SKishu *pKishu = g_pDataManager->GetKishuData(nKishuId);

			// 機種ごとの情報を作成
			SGraphKishu *pGraphKishu = pGraphSagyosha->aKishu.AddElement();
			pGraphKishu->sKishuName = pKishu->sKishuName;
			pGraphKishu->fTime = 0;

			aKoteiGraphBlockIndex[nKishuId] = -1;
		}

		// 要素作業群によるループ
		for (int i = 0; i < nSagyojunNum; i++) {
			SSagyojun &cSagyojun = aSagyojun[i];
			nYosoSagyogunId = cSagyojun.nYosoSagyogunId;
			nKishuId = cSagyojun.nKishuId;

			SYosoSagyogun &cYosoSagyogun = pSubline->aYosoSagyogun[nYosoSagyogunId];
			if (!cYosoSagyogun.CheckDeleted()) {
				fTime = cYosoSagyogun.GetTime();

				if (aKoteiGraphBlockIndex[nKishuId] != -1) {
					if (pGraphSagyosha->aKotei[aKoteiGraphBlockIndex[nKishuId]].sKoteiName != cYosoSagyogun.sKoteiName)
						aKoteiGraphBlockIndex[nKishuId] = -1;
				}

				SKishuSagyo &cKishuSagyo = cYosoSagyogun.aKishuSagyo[nKishuId];

				if (nSagyoshaId == cKishuSagyo.nSagyoshaId) {
					SGraphKishu &cGraphKishu = pGraphSagyosha->aKishu[nKishuId];

					// 工程グラフ用データ
					SGraphBlock *pKoteiBlock;
					if (aKoteiGraphBlockIndex[nKishuId] == -1) {
						pKoteiBlock = pGraphSagyosha->aKotei.AddElement(aKoteiGraphBlockIndex[nKishuId]);
						pKoteiBlock->nSublineId = nSublineId;
						pKoteiBlock->nKishuId = nKishuId;
						pKoteiBlock->sKoteiName = cYosoSagyogun.sKoteiName;
						pKoteiBlock->fTime = 0;
						pKoteiBlock->sJido = cYosoSagyogun.sJidoKotei;
						pKoteiBlock->bKyotsuKotei = FALSE;
						pKoteiBlock->bKitting = FALSE;
						pKoteiBlock->bNewMove = cKishuSagyo.bNewMove;
						pKoteiBlock->bKittingCancel = FALSE;

						SKyotsuKoteiGroup *pKyotsuKoteiGroup = aKyotsuKoteiGroup.LookupElement(cYosoSagyogun.sKoteiName);
						if (pKyotsuKoteiGroup != NULL)
							pKoteiBlock->nGroupNo = pKyotsuKoteiGroup->aKishuGroupNo[nKishuId].nKoteiGroupNo;
						else
							pKoteiBlock->nGroupNo = 0;
					} else
						pKoteiBlock = &pGraphSagyosha->aKotei[aKoteiGraphBlockIndex[nKishuId]];
						
					pKoteiBlock->fTime += fTime;
					pKoteiBlock->bKyotsuKotei |= cKishuSagyo.bKyotsuKotei;
					pKoteiBlock->bKittingCancel |= cYosoSagyogun.bKittingCancel;
					pKoteiBlock->aYosoSagyogunId.Add(nYosoSagyogunId);

					// 要素作業群グラフ用データ
					SGraphBlock *pYosoSagyogunBlock = pGraphSagyosha->aYosoSagyogun.AddElement();
					pYosoSagyogunBlock->nSublineId = nSublineId;
					pYosoSagyogunBlock->nKishuId = nKishuId;
					pYosoSagyogunBlock->sKoteiName = cYosoSagyogun.sKoteiName;
					pYosoSagyogunBlock->sYosoSagyogunName = cYosoSagyogun.sYosoSagyogunName;
					pYosoSagyogunBlock->fTime = fTime;
// Modify ... ( CHANGE )
					// 表示時は、要素作業群表示時も工程の自手区分を参照
					pYosoSagyogunBlock->sJido = cYosoSagyogun.sJidoKotei;
//					pYosoSagyogunBlock->sJido = cYosoSagyogun.sJidoYosoSagyogun;
// By Y.Itabashi (xxxxx) 2007.02.12
					pYosoSagyogunBlock->bKyotsuKotei = cKishuSagyo.bKyotsuKotei;
					pYosoSagyogunBlock->nGroupNo = cKishuSagyo.nGroupNo;
					pYosoSagyogunBlock->bKitting = FALSE;
					pYosoSagyogunBlock->aYosoSagyogunId.Add(nYosoSagyogunId);
					pYosoSagyogunBlock->bNewMove = cKishuSagyo.bNewMove;
					pYosoSagyogunBlock->bKittingCancel = cYosoSagyogun.bKittingCancel;

					// 機種別合計時間
					cGraphKishu.fTime += fTime;

					// 加重平均
					SKishu *pKishu = g_pDataManager->GetKishuData(nKishuId);
					pGraphSagyosha->fTime += fTime * pKishu->nSeisanDaisu / m_nTotalSeisanDaisu;
				}
			}
		}
	}

	// 目標タクト及び上下限設定
	pGraphSubline->fMokugyoTact = pSubline->cSeisanJoken.fTact;
	pGraphSubline->fJogen = pGraphSubline->fMokugyoTact * (1 + pSubline->cSeisanJoken.fJogen / 100);
	pGraphSubline->fKagen = pGraphSubline->fMokugyoTact * (1 - pSubline->cSeisanJoken.fKagen / 100);
	pGraphSubline->fKadoRitsu = pSubline->cSeisanJoken.fKadoRitsu;
}

void CManualEditDocument::MakeGraphDataKitting()
{
	double fTime;
	SGraphSagyosha *pGraphSagyosha;
	CString sKishuName;
	CString sSagyoshaName;
	CString sKey;
	int nSagyoshaId;
	CString sKoteiName;
	CIntArrayEx aRemoveSagyoshaId;
	int aKoteiGraphBlockIndex[MAX_KISHU];

	SGraphSubline *pGraphSubline = m_aGraphSubline.AddElement(m_nKittingIndex);
	pGraphSubline->nSublineId = -1;
	pGraphSubline->sSublineName = "キット化作業";

	// 全生産台数、機種数を取得
// Modify ... ( CHANGE )
	m_nTotalSeisanDaisu = g_pDataManager->GetTotalSeisanDaisu( -1 );
//	m_nTotalSeisanDaisu = g_pDataManager->GetTotalSeisanDaisu();
// By Y.Itabashi (xxxxx) 2007.02.11
	int nKishuNum = g_pDataManager->GetKishuNum();

	// 作業者によるループ
	int nSagyoshaNum = g_pDataManager->GetKittingSagyoshaNum();
	for (nSagyoshaId = 0; nSagyoshaId < nSagyoshaNum; nSagyoshaId++) {
		// 作業者名を取得
		sSagyoshaName = g_pDataManager->GetKittingSagyoshaName(nSagyoshaId);

		// 作業者ごとの情報を作成
		pGraphSagyosha = pGraphSubline->aGraphSagyosha.AddElement(sSagyoshaName);
		pGraphSagyosha->sSagyoshaDispName = sSagyoshaName.Left(1) + sSagyoshaName.Mid(3);
		pGraphSagyosha->sSagyoshaName = sSagyoshaName;
		pGraphSagyosha->fTime = 0;
		pGraphSagyosha->nSagyoshaId = nSagyoshaId;
		pGraphSagyosha->bKitting = TRUE;

		// 機種によるループ
		for (int nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
			SKishu *pKishu = g_pDataManager->GetKishuData(nKishuId);

			// 機種ごとの情報を作成
			SGraphKishu *pGraphKishu = pGraphSagyosha->aKishu.AddElement();
			pGraphKishu->sKishuName = pKishu->sKishuName;
			pGraphKishu->fTime = 0;
		}
	}

	// サブラインによるループ
	int nSublineNum = g_pDataManager->GetSublineNum();
	for (int nSublineId = 0; nSublineId < nSublineNum; nSublineId++) {
		SSubline *pSubline = g_pDataManager->GetSublineData(nSublineId);

		sKoteiName.Empty();

		// 要素作業群によるループ
		int nYosoSagyogunNum = pSubline->aYosoSagyogunKit.GetSize();
		for (int nYosoSagyogunId = 0; nYosoSagyogunId < nYosoSagyogunNum; nYosoSagyogunId++) {
			SYosoSagyogun &cYosoSagyogun = pSubline->aYosoSagyogunKit[nYosoSagyogunId];
			if (!cYosoSagyogun.CheckDeleted()) {
				fTime = cYosoSagyogun.GetTime();

				// 工程用グラフ用データ初期化
				if (sKoteiName != cYosoSagyogun.sKoteiName) {
					sKoteiName = cYosoSagyogun.sKoteiName;

					for (int nKishuId = 0; nKishuId < nKishuNum; nKishuId++)
						aKoteiGraphBlockIndex[nKishuId] = -1;
				}

				// 機種によるループ
				for (int nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
					SKishuSagyo &cKishuSagyo = cYosoSagyogun.aKishuSagyo[nKishuId];
					nSagyoshaId = cYosoSagyogun.aKishuSagyo[nKishuId].nSagyoshaId;
					if (nSagyoshaId != -1) {
						sSagyoshaName = g_pDataManager->GetKittingSagyoshaName(nSagyoshaId);
						pGraphSagyosha = pGraphSubline->aGraphSagyosha.LookupElement(sSagyoshaName);
						if (pSubline->bManualEditFlag) {
							SGraphKishu &cGraphKishu = pGraphSagyosha->aKishu[nKishuId];

							// 工程グラフ用データ
							SGraphBlock *pKoteiBlock;
							if (aKoteiGraphBlockIndex[nKishuId] == -1) {
								pKoteiBlock = pGraphSagyosha->aKotei.AddElement(aKoteiGraphBlockIndex[nKishuId]);
								pKoteiBlock->nSublineId = nSublineId;
								pKoteiBlock->nKishuId = nKishuId;
								pKoteiBlock->sKoteiName = cYosoSagyogun.sKoteiName;
								pKoteiBlock->fTime = 0;
								pKoteiBlock->sJido = cYosoSagyogun.sJidoKotei;
								pKoteiBlock->bKyotsuKotei = FALSE;
								pKoteiBlock->nGroupNo = 0;
								pKoteiBlock->bKitting = TRUE;
								pKoteiBlock->bNewMove = cKishuSagyo.bNewMove;
								pKoteiBlock->bKittingCancel = FALSE;
							} else
								pKoteiBlock = &pGraphSagyosha->aKotei[aKoteiGraphBlockIndex[nKishuId]];

							pKoteiBlock->fTime += fTime;
							pKoteiBlock->aYosoSagyogunId.Add(nYosoSagyogunId);

							// 要素作業群グラフ用データ
							SGraphBlock *pYosoSagyogunBlock = pGraphSagyosha->aYosoSagyogun.AddElement();
							pYosoSagyogunBlock->nSublineId = nSublineId;
							pYosoSagyogunBlock->nKishuId = nKishuId;
							pYosoSagyogunBlock->sKoteiName = cYosoSagyogun.sKoteiName;
							pYosoSagyogunBlock->sYosoSagyogunName = cYosoSagyogun.sYosoSagyogunName;
							pYosoSagyogunBlock->fTime = fTime;
// Modify ... ( CHANGE )
							// 表示時は、要素作業群表示時も工程の自手区分を参照
							pYosoSagyogunBlock->sJido = cYosoSagyogun.sJidoKotei;
//							pYosoSagyogunBlock->sJido = cYosoSagyogun.sJidoYosoSagyogun;
// By Y.Itabashi (xxxxx) 2007.02.12
							pYosoSagyogunBlock->bKyotsuKotei = FALSE;
							pYosoSagyogunBlock->nGroupNo = 0;
							pYosoSagyogunBlock->bKitting = TRUE;
							pYosoSagyogunBlock->aYosoSagyogunId.Add(nYosoSagyogunId);
							pYosoSagyogunBlock->bNewMove = cKishuSagyo.bNewMove;
							pYosoSagyogunBlock->bKittingCancel = FALSE;

							// 機種別合計時間
							cGraphKishu.fTime += fTime;

							// 加重平均
							SKishu *pKishu = g_pDataManager->GetKishuData(nKishuId);
							pGraphSagyosha->fTime += fTime * pKishu->nSeisanDaisu / m_nTotalSeisanDaisu;
						} else
							aRemoveSagyoshaId.AddUnique(nSagyoshaId);
					}
				}
			}
		}
	}

	// 表示対象外の作業者を削除
	for (int i = 0; i < pGraphSubline->aGraphSagyosha.GetSize(); ) {
		SGraphSagyosha &cGraphSagyosha = pGraphSubline->aGraphSagyosha[i];
		if (aRemoveSagyoshaId.Find(cGraphSagyosha.nSagyoshaId) != -1 && cGraphSagyosha.fTime == 0)
			pGraphSubline->aGraphSagyosha.RemoveAt(i);
		else
			i++;
	}

	// 表示対象のサブラインに作業者がいない場合は表示しない
	if (pGraphSubline->aGraphSagyosha.GetSize() == 0) {
		m_aGraphSubline.RemoveAt(m_nKittingIndex);
		m_nKittingIndex = -1;
		return;
	}

	// 目標タクト及び上下限設定
	nSublineId = g_pDataManager->GetMinTactSubline();
	if (nSublineId != -1) {
		SSubline *pSubline = g_pDataManager->GetSublineData(nSublineId);
		pGraphSubline->fMokugyoTact = pSubline->cSeisanJoken.fTact;
		pGraphSubline->fJogen = pGraphSubline->fMokugyoTact * (1 + pSubline->cSeisanJoken.fJogen / 100);
		pGraphSubline->fKagen = pGraphSubline->fMokugyoTact * (1 - pSubline->cSeisanJoken.fKagen / 100);
		pGraphSubline->fKadoRitsu = pSubline->cSeisanJoken.fKadoRitsu;
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditDocument::ChangeGraphKishu
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		機種変更処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		int		nGraphKishu			[I] 機種ID
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		指定された機種のグラフ表示に切り替える。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
// Modify ... ( CHANGE )
void CManualEditDocument::ChangeGraphKishu(int nGraphKishu, CFrameWnd *pWnd)
//void CManualEditDocument::ChangeGraphKishu(int nGraphKishu)
// By Y.Itabashi (xxxxx) 2007.02.27
{
// Modify ... ( DELETE )
//	m_nGraphKishu = nGraphKishu;
// By Y.Itabashi (xxxxx) 2007.02.26

// Modify ... ( CHANGE )
	if( pWnd == m_pSelectWnd )
		m_aSelectYosoSagyogun.RemoveAll();
//	m_aSelectedBox.RemoveAll();
// By Y.Itabashi (xxxxx) 2007.02.27

// Modify ... ( CHANGE )
	UpdateAllViews(NULL, UPDATE_GRAPH_KISHU, pWnd);
//	UpdateAllViews(NULL, UPDATE_GRAPH_KISHU);

	AddSelectKoteiOtherWindow();
// By Y.Itabashi (xxxxx) 2007.02.27
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditDocument::ChangeGraphKind
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		グラフ種類変更処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		int		nGraphKind			[I] グラフ種類（工程 or 要素作業群）
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		指定された種類のグラフ表示に切り替える。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CManualEditDocument::ChangeGraphKind(int nGraphKind)
{
	m_nGraphKind = nGraphKind;

// Modify ... ( CHANGE )
	m_aSelectYosoSagyogun.RemoveAll();
	InitReferenceYosoSagyogun();
//	m_aSelectedBox.RemoveAll();
// By Y.Itabashi (xxxxx) 2007.03.04

	UpdateAllViews(NULL, UPDATE_GRAPH_KIND);
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditDocument::UpdateGraph
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		グラフ表示を更新
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		グラフを再描画する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CManualEditDocument::UpdateGraph(BOOL bSetSagyojun)
{
	if (bSetSagyojun)
		g_pDataManager->SetSagyojun();

	MakeGraphData();
	UpdateAllViews(NULL, UPDATE_GRAPH_UPDATE);
}

void CManualEditDocument::DispCenterSagyosha(int nSublineId, int nSagyoshaId)
{
	UpdateGraph(TRUE);

	SZahyoSagyosha cZahyoSagyosha;
	cZahyoSagyosha.nSublineId = nSublineId;
	cZahyoSagyosha.nSagyoshaId = nSagyoshaId;
	UpdateAllViews(NULL, UPDATE_SCROLL, (CObject *)&cZahyoSagyosha);
}

void CManualEditDocument::OnWindowViewType()
{
	CManualViewTypeDlg dlg;

	if (dlg.DoModal() == IDOK) {
		CManualEditFrameWnd *pFrameWnd = (CManualEditFrameWnd *)AfxGetMainWnd();
		pFrameWnd->DisplayViews(TRUE);
	}
}

void CManualEditDocument::OnHelpVersion()
{
	CAboutDlg dlgAbout;
	dlgAbout.DoModal();
}

void CManualEditDocument::OnViewSlimGraph()
{
	m_bSlimMode = !m_bSlimMode;
	UpdateAllViews(NULL, UPDATE_CHANGE_SLIM);
}

void CManualEditDocument::OnUpdateViewSlimGraph(CCmdUI* pCmdUI)
{
	pCmdUI->SetCheck(m_bSlimMode);
}

void CManualEditDocument::OnViewSelectSubline() 
{
	CManualEditSublineDlg dlg;
	if (dlg.DoModal() == IDOK) {
		// タイトル表示
		SetWindowTitle();

		// グラフデータ作成
		MakeGraphData();

		UpdateAllViews(NULL, UPDATE_CHANGE_SUBLINE);
	}
}

void CManualEditDocument::OnToolKittingSet() 
{
	CSetKittingDlg dlg;
	if (dlg.DoModal() == IDOK) {
		// キット化作業自動編成
		CIntArrayEx aSublineId;
		int nSublineNum = g_pDataManager->GetSublineNum();
		for (int nSublineId = 0; nSublineId < nSublineNum; nSublineId++) {
			SSubline *pSubline = g_pDataManager->GetSublineData(nSublineId);
			if (pSubline->bSagyoshaWaritsuke)
				aSublineId.Add(nSublineId);
		}
		g_pDataManager->JidoHensei1Kitting(aSublineId);

		MakeGraphData();
		UpdateAllViews(NULL, UPDATE_CHANGE_SUBLINE);

		// 現在のデータをUndoバッファに保存
		g_pDataManager->SaveUndoBuf();
	}
}

void CManualEditDocument::OnToolLineKisei() 
{
	g_pDataManager->OpenLineKiseiMatrix();
}

void CManualEditDocument::OnToolKatashikiKisei() 
{
	CKatashikiKiseiMatrixDlg dlg;
	if (dlg.DoModal() == IDOK)
		g_pDataManager->OpenKatashikiKiseiMatrix();
}

void CManualEditDocument::OnToolKyotsuKotei() 
{
	CSetKyotsuKoteiDlg dlg;
	if (dlg.DoModal() == IDOK) {
		MakeGraphData();
		UpdateAllViews(NULL, UPDATE_GRAPH_UPDATE);

		// 現在のデータをUndoバッファに保存
		g_pDataManager->SaveUndoBuf();
	}
}

void CManualEditDocument::OnUpdateToolLineKisei(CCmdUI* pCmdUI) 
{
	pCmdUI->Enable(!g_pDataManager->m_bOpenLineKiseiMatrix);
}

void CManualEditDocument::OnUpdateToolKatashikiKisei(CCmdUI* pCmdUI) 
{
	pCmdUI->Enable(!g_pDataManager->m_bOpenKatashikiKiseiMatrix);
}

void CManualEditDocument::SetWindowTitle()
{
	CString sSubline;
	int nSublineNum = g_pDataManager->GetSublineNum();
	for (int nSublineId = 0; nSublineId < nSublineNum; nSublineId++) {
		SSubline *pSubline = g_pDataManager->GetSublineData(nSublineId);
		if (pSubline->bManualEditFlag) {
			if (!sSubline.IsEmpty())
				sSubline += "/";
			sSubline += pSubline->sSublineName;
		}
	}

	CString sWindowTitle;
// Modify ... ( CHANGE )
	// タイトルにバージョン番号を追加
	CString sWindowVersion;
	int j = 0;
	for( int i = 0; i < g_pConst->m_sVersion.GetLength(); i++ ){
		if( g_pConst->m_sVersion[i] == '.' ){
			j++;
			if( j == 2 ){
				sWindowVersion.Format("%s", g_pConst->m_sVersion.Left(i) );
				break;
			}
		}
	}

	CString str = g_pSystem->m_cMenuDisp.GetMenu( Menu_ManualWindowTitle[0].MenuNo, Menu_ManualWindowTitle[0].ItemNo );
	sWindowTitle.Format(str, sSubline, sWindowVersion);
//	sWindowTitle.Format("サブライン：%s - 手動編成 (Ver.%s)", sSubline, g_pConst->m_sVersion);
// By Y.Itabashi (xxxxx) 2007.01.26
	AfxGetMainWnd()->SetWindowText(sWindowTitle);
}

void CManualEditDocument::OnFileSave() 
{
	if (g_pSystem->DispMessage("Q003003") == IDYES) {
		if (g_pDataManager->SaveFile())
			g_pSystem->DispMessage("I003004");
	}
}

void CManualEditDocument::OnFileSaveAs() 
{
	CSaveProjectDlg dlg;
	if (dlg.DoModal() == IDOK)
		g_pSystem->DispMessage("I002005");
}

void CManualEditDocument::OnEditUndo() 
{
	g_pDataManager->Undo(TRUE);
	MakeGraphData();
	UpdateAllViews(NULL, UPDATE_CHANGE_SUBLINE);
}

void CManualEditDocument::OnUpdateEditUndo(CCmdUI* pCmdUI) 
{
	pCmdUI->Enable(g_pDataManager->CheckUndo());
}

void CManualEditDocument::OnEditRedo() 
{
	g_pDataManager->Redo();
	MakeGraphData();
	UpdateAllViews(NULL, UPDATE_CHANGE_SUBLINE);
}

void CManualEditDocument::OnUpdateEditRedo(CCmdUI* pCmdUI) 
{
	pCmdUI->Enable(g_pDataManager->CheckRedo());
}

void CManualEditDocument::OnWindowSeiretsu() 
{
	CManualEditFrameWnd *pFrameWnd = (CManualEditFrameWnd *)AfxGetMainWnd();
	pFrameWnd->DisplayViews(TRUE);
}

void CManualEditDocument::StopBlink(BOOL bStopBlink)
{
	CManualEditFrameWnd *pFrameWnd = (CManualEditFrameWnd *)AfxGetMainWnd();
	pFrameWnd->StopBlink(bStopBlink);
}

SGraphSubline *CManualEditDocument::GetGraphSublineData(int nSublineId)
{
	int nSize = m_aGraphSubline.GetSize();
	for (int i = 0; i < nSize; i++) {
		if (nSublineId == m_aGraphSubline[i].nSublineId)
			return &m_aGraphSubline[i];
	}

	return NULL;
}

void CManualEditDocument::MoveItem(int nSublineIdFrom, CGraphBlockPtrArray &aGraphBlockFrom, int nSublineIdTo, SGraphBlock *pGraphBlockTo, int nSagyoshaId, BOOL bLastBlock)
{
	if (nSublineIdFrom == nSublineIdTo)
		MoveItem1(nSublineIdFrom, aGraphBlockFrom, pGraphBlockTo, nSagyoshaId, bLastBlock);
	else
		MoveItem2(nSublineIdFrom, aGraphBlockFrom, nSublineIdTo, pGraphBlockTo, nSagyoshaId, bLastBlock);
}

// 同一サブライン内の移動
void CManualEditDocument::MoveItem1(int nSublineId, CGraphBlockPtrArray &aGraphBlockFrom, SGraphBlock *pGraphBlockTo, int nSagyoshaId, BOOL bLastBlock)
{
	int i, j;

	// 移動先情報
	int nYosoSagyogunIdTo = bLastBlock ? pGraphBlockTo->aYosoSagyogunId[pGraphBlockTo->aYosoSagyogunId.GetSize() - 1] : pGraphBlockTo->aYosoSagyogunId[0];
	int nKishuIdTo = pGraphBlockTo->nKishuId;

	int nSize = aGraphBlockFrom.GetSize();
	if (bLastBlock) {
		for (i = 0; i < nSize; i++) {
			SGraphBlock *pGraphBlockFrom = aGraphBlockFrom[nSize - i - 1];

			int nSize = pGraphBlockFrom->aYosoSagyogunId.GetSize();
			for (j = nSize - 1; j >= 0; j--)
				MoveItem1Sub(nSublineId, pGraphBlockFrom->aYosoSagyogunId[j], pGraphBlockFrom->nKishuId, nYosoSagyogunIdTo, nKishuIdTo, nSagyoshaId, bLastBlock);
		}
	} else {
		for (i = 0; i < nSize; i++) {
			SGraphBlock *pGraphBlockFrom = aGraphBlockFrom[i];

			int nSize = pGraphBlockFrom->aYosoSagyogunId.GetSize();
			for (j = 0; j < nSize; j++)
				MoveItem1Sub(nSublineId, pGraphBlockFrom->aYosoSagyogunId[j], pGraphBlockFrom->nKishuId, nYosoSagyogunIdTo, nKishuIdTo, nSagyoshaId, bLastBlock);
		}
	}
}

void CManualEditDocument::MoveItem1Sub(int nSublineId, int nYosoSagyogunIdFrom, int nKishuIdFrom, int nYosoSagyogunIdTo, int nKishuIdTo, int nSagyoshaId, BOOL bLastBlock)
{
	int i;

	SSubline *pSubline = g_pDataManager->GetSublineData(nSublineId);
	CYosoSagyogunArray &aYosoSagyogun = pSubline->aYosoSagyogun;

	// 作業順取得
	CSagyojunArray aSagyojun;
	g_pDataManager->GetSagyojun(nSublineId, -1, aSagyojun);

	// 移動元の要素作業群を削除
	SSagyojun cMoveSagyojun;
	for (i = 0; i < aSagyojun.GetSize(); i++) {
		SSagyojun &cSagyojun = aSagyojun[i];
		if (cSagyojun.nYosoSagyogunId == nYosoSagyogunIdFrom && cSagyojun.nKishuId == nKishuIdFrom) {
			SYosoSagyogun &cYosoSagyogun = aYosoSagyogun[nYosoSagyogunIdFrom];

			cMoveSagyojun = aSagyojun[i];
			aSagyojun.RemoveAt(i);

			// 作業者を書き換え
			if (cYosoSagyogun.aKishuSagyo[nKishuIdFrom].nSagyoshaId != -1)
				cYosoSagyogun.aKishuSagyo[nKishuIdFrom].nSagyoshaId = nSagyoshaId;
			break;
		}
	}

	// 移動先に挿入
	int nSagyojunNum = aSagyojun.GetSize();
	for (i = 0; i < nSagyojunNum; i++) {
		SSagyojun &cSagyojun = aSagyojun[i];
		if (cSagyojun.nYosoSagyogunId == nYosoSagyogunIdTo && cSagyojun.nKishuId == nKishuIdTo) {
			if (bLastBlock)
				i++;
			aSagyojun.InsertAt(i, cMoveSagyojun);
			break;
		}
	}

	// 最新移動表示
	aYosoSagyogun[nYosoSagyogunIdFrom].aKishuSagyo[nKishuIdFrom].bNewMove = TRUE;
	aYosoSagyogun[nYosoSagyogunIdFrom].nMoveSublineId = nSublineId;

	// 要素作業順を並び替え
	g_pDataManager->OrderBySagyojun(nSublineId, aSagyojun);
}

// 異なるサブライン間の移動
void CManualEditDocument::MoveItem2(int nSublineIdFrom, CGraphBlockPtrArray &aGraphBlockFrom, int nSublineIdTo, SGraphBlock *pGraphBlockTo, int nSagyoshaId, BOOL bLastBlock)
{
	int i, j;
	SSubline *pSublineFrom = g_pDataManager->GetSublineData(nSublineIdFrom);

	// 移動先情報
	int nYosoSagyogunIdTo = bLastBlock ? pGraphBlockTo->aYosoSagyogunId[pGraphBlockTo->aYosoSagyogunId.GetSize() - 1] : pGraphBlockTo->aYosoSagyogunId[0];
	int nKishuIdTo = pGraphBlockTo->nKishuId;
	SSubline *pToSubline = g_pDataManager->GetSublineData(nSublineIdTo);
	SYosoSagyogun *pToYosoSagyogun = g_pDataManager->GetYosoSagyogun(nSublineIdTo, nYosoSagyogunIdTo);

	int nYosoSagyogunNum = pToSubline->aYosoSagyogun.GetSize();
	CSagyojunArray aSagyojunFrom;
	CIntArrayEx aYosoSagyogunIdFrom;
	int nSize = aGraphBlockFrom.GetSize();
	for (i = 0; i < nSize; i++) {
		SGraphBlock *pGraphBlockFrom = aGraphBlockFrom[i];

		int nSize = pGraphBlockFrom->aYosoSagyogunId.GetSize();
		for (j = 0; j < nSize; j++) {
			SSagyojun *pSagyojun = aSagyojunFrom.AddElement();
			pSagyojun->nYosoSagyogunId = nYosoSagyogunNum + aYosoSagyogunIdFrom.AddUnique(pGraphBlockFrom->aYosoSagyogunId[j]);
			pSagyojun->nKishuId = pGraphBlockFrom->nKishuId;
		}
	}

	CYosoSagyogunArray aYosoSagyogun;
	g_pDataManager->GetYosoSagyogun(nSublineIdFrom, aYosoSagyogunIdFrom, aYosoSagyogun);

// --Change 2007/03/02 aizawa 不懸D-147
//	g_pDataManager->DeleteYosoSagyogun(nSublineIdFrom, aYosoSagyogunIdFrom, m_nGraphKind == GRAPH_KOTEI ? "1" : "2");
	g_pDataManager->DeleteYosoSagyogun(nSublineIdFrom, aYosoSagyogunIdFrom, "1");
// --Change

	// 作業者を書き換え
	nSize = aYosoSagyogun.GetSize();
	for (i = 0; i < nSize; i++) {
		SYosoSagyogun &cYosoSagyogun = aYosoSagyogun[i];
		cYosoSagyogun.sKoteiKigo = pToYosoSagyogun->sKoteiKigo;

		int nKishuNum = cYosoSagyogun.aKishuSagyo.GetSize();
		for (j = 0; j < nKishuNum; j++) {
			SKishuSagyo &cKishuSagyo = cYosoSagyogun.aKishuSagyo[j];
			if (cKishuSagyo.nSagyoshaId != -1) {
				cKishuSagyo.nSagyoshaId = nSagyoshaId;
				cKishuSagyo.bNewMove = TRUE;
			}
		}

		int nYosoSagyoNum = cYosoSagyogun.aYosoSagyo.GetSize();
		for (j = 0; j < nYosoSagyoNum; j++)
// --Change 2007/03/02 aizawa 不懸D-147
//			cYosoSagyogun.aYosoSagyo[j].sInsertFlag = m_nGraphKind == GRAPH_KOTEI ? "1" : "2";
			cYosoSagyogun.aYosoSagyo[j].sInsertFlag = "1";
// --Change

		cYosoSagyogun.nMoveSublineId = nSublineIdFrom;
	}

	// 作業順取得
	CSagyojunArray aSagyojunTo;
	g_pDataManager->GetSagyojun(nSublineIdTo, -1, aSagyojunTo);

	// 移動先に挿入
	int nSagyojunNum = aSagyojunTo.GetSize();
	for (i = 0; i < nSagyojunNum; i++) {
		SSagyojun &cSagyojun = aSagyojunTo[i];
		if (cSagyojun.nYosoSagyogunId == nYosoSagyogunIdTo && cSagyojun.nKishuId == nKishuIdTo) {
			if (bLastBlock)
				i++;
			aSagyojunTo.InsertAt(i, &aSagyojunFrom);
			break;
		}
	}

	g_pDataManager->AddYosoSagyogun(nSublineIdTo, aYosoSagyogun);

	// 要素作業順を並び替え
	g_pDataManager->OrderBySagyojun(nSublineIdTo, aSagyojunTo);
}

SGraphBlock *CManualEditDocument::GetFirstGraphBlock(int nSublineId)
{
	return GetNextGraphBlock(nSublineId, -1);
}


SGraphBlock *CManualEditDocument::GetNextGraphBlock(int nSublineId, int nSagyoshaId)
{
	SGraphSubline *pGraphSubline = GetGraphSublineData(nSublineId);
	if (pGraphSubline != NULL) {
		int nSagyoshaNum = pGraphSubline->aGraphSagyosha.GetSize();
		for (nSagyoshaId++; nSagyoshaId < nSagyoshaNum; nSagyoshaId++) {
			SGraphSagyosha &cGraphSagyosha = pGraphSubline->aGraphSagyosha[nSagyoshaId];
			CGraphBlockArray &aGraphBlock = m_nGraphKind == GRAPH_YOSO_SAGYOGUN ? cGraphSagyosha.aYosoSagyogun : cGraphSagyosha.aKotei;
			if (aGraphBlock.GetSize() != 0)
				return &aGraphBlock[0];
		}
	}

	return NULL;
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditDocument::CheckSameKoteiMove
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		工順規制チェック
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		SGraphBlock		*pGraphBlockFrom	[I] 移動元データ
//		SGraphBlock		*pGraphBlockTo		[I] 移動先データ
//		BOOL			bLastBlock			[I] 移動先の位置（最後尾かのフラグ）、
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL		TRUE:正常，FALSE:工順規制エラー有り
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		指定された工程が同一工程内の移動かチェックする。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2007.02.10 Y.Itabashi (xxxxx) 新規作成
//*****************************************************************************************************
BOOL CManualEditDocument::CheckSameKoteiMove( SGraphBlock *pGraphBlockFrom, SGraphBlock *pGraphBlockTo, BOOL bLastBlock )
{
// Modify ... ( CHANGE )
	// 仕様が明確になるまで、一時的にチェックをはずす
	return TRUE;
// By Y.Itabashi (xxxxx) 2007.02.12

	int nSublineIdFrom = pGraphBlockFrom->nSublineId;
	int nSublineIdTo = pGraphBlockTo->nSublineId;

	if (nSublineIdFrom != nSublineIdTo)
		return TRUE;

	if (m_nGraphKind != GRAPH_YOSO_SAGYOGUN)
		return TRUE;

	CSagyojunArray aSagyojun;
	SSubline *pSubline = g_pDataManager->GetSublineData(nSublineIdTo);
	int nToYosoSagyogunId = pGraphBlockTo->aYosoSagyogunId[0];
	int nToKishuId = pGraphBlockTo->nKishuId;
	g_pDataManager->GetSagyojun(nSublineIdFrom, -1, aSagyojun);
	int nSagyojunNum = aSagyojun.GetSize();

	for (int i = 0; i < nSagyojunNum; i++) {
		SSagyojun &cSagyojun = aSagyojun[i];
		if (cSagyojun.nYosoSagyogunId == nToYosoSagyogunId && cSagyojun.nKishuId == nToKishuId)
			break;
	}
	int nSagyojunTo = i;

	int nYosoSagyogunIdFrom = pGraphBlockFrom->aYosoSagyogunId[0];
	int nKishuIdFrom = pGraphBlockFrom->nKishuId;
	SSubline *pSublineFrom = g_pDataManager->GetSublineData(nSublineIdFrom);
	CString sKoteiNameFrom = pSublineFrom->aYosoSagyogun[nYosoSagyogunIdFrom].sKoteiName;
	CString sYosoSagyogunNameFrom = pSublineFrom->aYosoSagyogun[nYosoSagyogunIdFrom].sYosoSagyogunName;

	// 移動する前の位置を取得
	for (int j = 0; j < nSagyojunNum; j++) {
		SSagyojun &cSagyojun = aSagyojun[j];
		if (cSagyojun.nYosoSagyogunId == nYosoSagyogunIdFrom && cSagyojun.nKishuId == nKishuIdFrom)
			break;
	}
	int nSagyojunFrom = j;

	int nPrevCheckId = 0;
	int nNextCheckId = 0;

	// 最後尾への移動の場合、移動先の位置が最後尾となることを考慮
	if( bLastBlock )
		nNextCheckId = 1;
	else
		nPrevCheckId = 1;

	int nPrevSagyojun, nNextSagyojun;

	CString sPrevKoteiName, sNextKoteiName;
	CString sPrevYosoSagyogunName, sNextYosoSagyogunName;

	for (nPrevSagyojun = nSagyojunTo - nPrevCheckId; nPrevSagyojun >= 0; nPrevSagyojun--) {
		sPrevKoteiName = pSubline->aYosoSagyogun[aSagyojun[nPrevSagyojun].nYosoSagyogunId].sKoteiName;
		sPrevYosoSagyogunName = pSubline->aYosoSagyogun[aSagyojun[nPrevSagyojun].nYosoSagyogunId].sYosoSagyogunName;
		if (aSagyojun[nPrevSagyojun].nKishuId == nKishuIdFrom)
			break;
	}
	for (nNextSagyojun = nSagyojunTo + nNextCheckId; nNextSagyojun < nSagyojunNum; nNextSagyojun++) {
		sNextKoteiName = pSubline->aYosoSagyogun[aSagyojun[nNextSagyojun].nYosoSagyogunId].sKoteiName;
		sNextYosoSagyogunName = pSubline->aYosoSagyogun[aSagyojun[nNextSagyojun].nYosoSagyogunId].sYosoSagyogunName;
		if (aSagyojun[nNextSagyojun].nKishuId == nKishuIdFrom)
			break;
	}

	if ( (nPrevSagyojun >= 0 && sKoteiNameFrom == sPrevKoteiName)
			|| (nNextSagyojun < nSagyojunNum && sKoteiNameFrom == sNextKoteiName)) {

		// 下流から同一工程の最後尾に群を移動、上流から同一工程の先頭に群を移動は許可
		if( ( nSagyojunFrom > nSagyojunTo && sPrevKoteiName == sKoteiNameFrom && sNextKoteiName != sKoteiNameFrom ) ||
			( nSagyojunFrom < nSagyojunTo && sPrevKoteiName != sKoteiNameFrom && sNextKoteiName == sKoteiNameFrom ) )
			return TRUE;

		// 選択した工程の順番が変わらないときは移動可
		// 最後尾の作業者を次の作業者の先頭に移動・先頭の作業者を前の作業者の最後尾へ移動など
		if( ( sPrevKoteiName == sKoteiNameFrom && sPrevYosoSagyogunName == sYosoSagyogunNameFrom ) ||
			( sNextKoteiName == sKoteiNameFrom && sNextYosoSagyogunName == sYosoSagyogunNameFrom ) )
			return TRUE;

		// 上記二つの条件に一致しないときはエラー
		g_pSystem->DispMessage("E013005");
		return FALSE;

	}

	return TRUE;
}

// Modify ... ( ADD )
void CManualEditDocument::OnFileExport()
{
	CFileExportDlg dlg;
	if ( dlg.DoModal() == IDOK ) {
		CManualEditFrameWnd *pFrameWnd = (CManualEditFrameWnd *)AfxGetMainWnd();
		pFrameWnd->DisplayViews( TRUE );
	}
}
// By K.Kimura ( xxxxx )07.02.06

void CManualEditDocument::OnUpdateToolKittingSet(CCmdUI* pCmdUI) 
{
	// UP4用モジュール
//	pCmdUI->Enable( FALSE );
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditDocument::AddSelectKotei
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		機種変更処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		指定の工程を選択工程エリアに登録
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		200702.27 Y.Itabashi (xxxxx) 新規作成
//*****************************************************************************************************
void CManualEditDocument::AddSelectKotei( SZahyoDrag sZahyoDrag, int nGraphKishu )
{
	SSelectYosoSagyogun sSelectSagyo;

	int nCheckIndex = sZahyoDrag.nIndex;
	GetGraphIndex( nCheckIndex, nGraphKishu, sZahyoDrag.pGraphBlock->nKishuId,
		sSelectSagyo.nIndex, sSelectSagyo.nIndexKishu );
	sSelectSagyo.nSublineId			= sZahyoDrag.pGraphBlock->nSublineId;
	sSelectSagyo.nKishuId			= sZahyoDrag.pGraphBlock->nKishuId;
	sSelectSagyo.nSagyoshaId		= sZahyoDrag.nSagyoshaId;
	sSelectSagyo.sKoteiName			= sZahyoDrag.pGraphBlock->sKoteiName;
	sSelectSagyo.sYosoSagyogunName	= sZahyoDrag.pGraphBlock->sYosoSagyogunName;
	sSelectSagyo.bKyotsuKotei		= sZahyoDrag.pGraphBlock->bKyotsuKotei;
	sSelectSagyo.nGroupNo			= sZahyoDrag.pGraphBlock->nGroupNo;
	sSelectSagyo.bKitting			= sZahyoDrag.pGraphBlock->bKitting;

	CSelectYosoSagyogunArray aSelectSagyo;
	aSelectSagyo.RemoveAll();

	BOOL fSetData = 0;
	int nBeforeKishu = -1;
	for( int nSelect = 0; nSelect < m_aSelectYosoSagyogun.GetSize(); nSelect++ ){
		if( nBeforeKishu == -1 )
			nBeforeKishu = m_aSelectYosoSagyogun[nSelect].nKishuId;
		if( sSelectSagyo.nKishuId == m_aSelectYosoSagyogun[nSelect].nKishuId ){

			if( sSelectSagyo.nIndexKishu < m_aSelectYosoSagyogun[nSelect].nIndexKishu ){

				aSelectSagyo.Add( sSelectSagyo );
				fSetData = 1;
				break;

			}else if( sSelectSagyo.nIndexKishu == m_aSelectYosoSagyogun[nSelect].nIndexKishu ){
				if( nGraphKishu == -1 )
					m_aSelectYosoSagyogun[nSelect].nIndex = sSelectSagyo.nIndex;
				fSetData = 2;
				break;

			}else
				aSelectSagyo.Add( m_aSelectYosoSagyogun[nSelect] );

		}else if( nBeforeKishu == sSelectSagyo.nKishuId &&
			nBeforeKishu != sSelectSagyo.nKishuId ){

			aSelectSagyo.Add( sSelectSagyo );
			aSelectSagyo.Add( m_aSelectYosoSagyogun[nSelect] );
			fSetData = 1;
			break;

		}else
			aSelectSagyo.Add( m_aSelectYosoSagyogun[nSelect] );

		nBeforeKishu = m_aSelectYosoSagyogun[nSelect].nKishuId;

	}

	if( fSetData == 0 )
		aSelectSagyo.Add( sSelectSagyo );

	if( fSetData == 1 ){
		for( ; nSelect < m_aSelectYosoSagyogun.GetSize(); nSelect++ ){
			aSelectSagyo.Add( m_aSelectYosoSagyogun[nSelect] );
		}
	}

	if( fSetData != 2 ){

		m_aSelectYosoSagyogun.RemoveAll();

		for( nSelect = 0; nSelect < aSelectSagyo.GetSize(); nSelect++ )
			m_aSelectYosoSagyogun.Add( aSelectSagyo[nSelect] );

	}

	aSelectSagyo.RemoveAll();
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditDocument::AddReferenceKotei
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		機種変更処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		指定の工程を参照工程エリアに登録
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		200702.27 Y.Itabashi (xxxxx) 新規作成
//*****************************************************************************************************
void CManualEditDocument::AddReferenceKotei( SZahyoDrag sZahyoDrag, int nGraphKishu )
{
	SReferenceYosoSagyogun sRefSagyo;

	int nCheckIndex = sZahyoDrag.nIndex;
	GetGraphIndex( nCheckIndex, nGraphKishu, sZahyoDrag.pGraphBlock->nKishuId,
		sRefSagyo.nIndex, sRefSagyo.nIndexKishu );
	sRefSagyo.nSublineId		= sZahyoDrag.pGraphBlock->nSublineId;
	sRefSagyo.nKishuId			= sZahyoDrag.pGraphBlock->nKishuId;
	sRefSagyo.nSagyoshaId		= sZahyoDrag.nSagyoshaId;
	sRefSagyo.sKoteiName			= sZahyoDrag.pGraphBlock->sKoteiName;
	sRefSagyo.sYosoSagyogunName	= sZahyoDrag.pGraphBlock->sYosoSagyogunName;

	// 選択工程に含まれていたら、登録しない
	for( int nSelect = 0; nSelect < m_aSelectYosoSagyogun.GetSize(); nSelect++ ){
		if( m_aSelectYosoSagyogun[nSelect].nKishuId != sRefSagyo.nKishuId )
			continue;

		if( m_aSelectYosoSagyogun[nSelect].nIndexKishu == sRefSagyo.nIndexKishu )
			return;
	}

	CReferenceYosoSagyogunArray aRefSagyo;
	aRefSagyo.RemoveAll();

	BOOL fSetData = 0;
	int nBeforeKishu = -1;
	for( nSelect = 0; nSelect < m_aRefYosoSagyogun.GetSize(); nSelect++ ){
		if( sRefSagyo.nKishuId == m_aRefYosoSagyogun[nSelect].nKishuId ){

			if( sRefSagyo.nIndexKishu < m_aRefYosoSagyogun[nSelect].nIndexKishu ){

				aRefSagyo.Add( sRefSagyo );
				fSetData = 1;
				break;

			}else if( sRefSagyo.nIndexKishu == m_aRefYosoSagyogun[nSelect].nIndexKishu ){
				fSetData = 2;
				break;

			}else
				aRefSagyo.Add( m_aRefYosoSagyogun[nSelect] );

		}else if( nBeforeKishu == sRefSagyo.nKishuId &&
			nBeforeKishu != sRefSagyo.nKishuId ){

			aRefSagyo.Add( sRefSagyo );
			aRefSagyo.Add( m_aRefYosoSagyogun[nSelect] );
			fSetData = 1;
			break;

		}else
			aRefSagyo.Add( m_aRefYosoSagyogun[nSelect] );

		nBeforeKishu = m_aRefYosoSagyogun[nSelect].nKishuId;

	}

	if( fSetData == 0 )
		aRefSagyo.Add( sRefSagyo );

	if( fSetData == 1 ){
		for( ; nSelect < m_aRefYosoSagyogun.GetSize(); nSelect++ ){
			aRefSagyo.Add( m_aRefYosoSagyogun[nSelect] );
		}
	}

	if( fSetData != 2 ){

// Modify ... ( CHANGE )
		InitReferenceYosoSagyogun();
//		m_aRefYosoSagyogun.RemoveAll();
// By Y.Itabashi (xxxxx) 2007.03.04

		for( nSelect = 0; nSelect < aRefSagyo.GetSize(); nSelect++ )
			m_aRefYosoSagyogun.Add( aRefSagyo[nSelect] );

	}

	aRefSagyo.RemoveAll();
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditDocument::AddSelectOtherWindow
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		機種変更処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		選択された工程に対し、その他の工程で共通工程の工程も選択
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		200702.27 Y.Itabashi (xxxxx) 新規作成
//*****************************************************************************************************
void CManualEditDocument::AddSelectKoteiOtherWindow()
{
	POSITION pos = GetFirstViewPosition();

	while( pos ){
		CView *pView = GetNextView( pos );

		if( !pView->IsKindOf( RUNTIME_CLASS(CManualEditView1) ) )
			continue;

		CManualEditView1 *pView1 = (CManualEditView1 *)pView;
		int nGraphKishu = ((CManualEditChildWnd *)pView1->GetParentFrame())->m_nGraphKishu;

		// 共通工程の選択
		pView1->AddSelectKyotsuKotei();

		// キット化作業の選択
		pView1->AddSelectKittingKotei();

		// 参照工程の登録
		pView1->AddReferenceKotei();
	}
}

// 指定機種が選択中かのチェック
BOOL CManualEditDocument::CheckSelectKishu( int nKishuId )
{
	for( int i = 0; i < m_aSelectYosoSagyogun.GetSize(); i++ ){

		if( m_aSelectYosoSagyogun[i].nKishuId == nKishuId )
			return TRUE;
	}

	return FALSE;
}

// 指定インデックスの全機種・機種別インデックス取得
void CManualEditDocument::GetGraphIndex( int nIndex, int nGraphKishu, int nKishuId, int &nIndexAll, int &nIndexKishu )
{
	nIndexAll = 0;
	nIndexKishu = 0;
	for( int nSubline = 0; nSubline < m_aGraphSubline.GetSize(); nSubline++ ){

		CGraphSagyoshaArray &aGraphSagyosha = m_aGraphSubline[nSubline].aGraphSagyosha;

		for( int nSagyosha = 0; nSagyosha < aGraphSagyosha.GetSize(); nSagyosha++ ){

			SGraphSagyosha &sGraphSagyosha = aGraphSagyosha[nSagyosha];
			CGraphBlockArray &aGraphBlock = m_nGraphKind == GRAPH_YOSO_SAGYOGUN ? sGraphSagyosha.aYosoSagyogun : sGraphSagyosha.aKotei;
			
			for( int nBlock = 0; nBlock < aGraphBlock.GetSize(); nBlock++ ){

				if( nGraphKishu == -1 && nIndexAll == nIndex )
					return;

// Modify ... ( CHANGE )
				if( nKishuId == aGraphBlock[nBlock].nKishuId ){
					if( nGraphKishu != -1 && nIndexKishu == nIndex )
						return;
					nIndexKishu++;
				}
/*				else if( nGraphKishu != -1 && nIndexKishu == nIndex )
					return;

				if( nKishuId == aGraphBlock[nBlock].nKishuId )
					nIndexKishu++;*/
// By Y.Itabashi (xxxxx) 2007.03.04

				nIndexAll++;
			}
		}
	}
}

// 参照工程のキャンセル
void CManualEditDocument::CancelReferenceYosoSagyogun( CString sKoteiName, CString sYosoSagyogunName )
{
	POSITION firstpos = GetFirstViewPosition();
	CView *pView;
	CManualEditChildWnd *pWnd;

	for( int i = 0; i < m_aRefYosoSagyogun.GetSize(); ){
		if( m_aRefYosoSagyogun[i].sKoteiName == sKoteiName &&
			m_aRefYosoSagyogun[i].sYosoSagyogunName == sYosoSagyogunName ){
			m_aRefYosoSagyogun.RemoveAt( i );

			POSITION pos = firstpos;
			while( pos ){

				pView = GetNextView( pos );
				pWnd = (CManualEditChildWnd *)(pView->GetParentFrame());

				if( i <= pWnd->m_nRefIndex )
					pWnd->m_nRefIndex++;
			}
		}else
			i++;
	}
}

// 参照工程の初期化
void CManualEditDocument::InitReferenceYosoSagyogun()
{
	POSITION pos = GetFirstViewPosition();

	while( pos ){

		CView *pView = GetNextView( pos );
		CManualEditChildWnd *pWnd = (CManualEditChildWnd *)(pView->GetParentFrame());

		pWnd->m_nRefIndex = 0;
	}

	m_aRefYosoSagyogun.RemoveAll();
}

// 自動工程の選択追加
void CManualEditDocument::AddSelectAutoKotei()
{
	POSITION pos = GetFirstViewPosition();

	while( pos ){

		CView *pView = GetNextView( pos );
		if( pView->IsKindOf( RUNTIME_CLASS( CManualEditView1 ) ) ){
			((CManualEditView1 *)pView)->AddSelectAutiKotei();
		}
	}
}
