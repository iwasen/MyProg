//*****************************************************************************************************
//  1. ファイル名
//		DataManagerJidoHensei.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		CDataManager クラスのインプリメンテーション（自動編成関連）
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************

#include "stdafx.h"
#include "workscheduler.h"

//*****************************************************************************************************
//  1. 関数名
//		CDataManager::JidoHensei
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		自動編成処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		int		nHenseiMode				[I] 編成モード（0:工順入れ替え無し、1:工順入れ替え有り）
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		自動編成処理を実行する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CDataManager::JidoHensei(CIntArrayEx &aSublineId, int nHenseiMode, int nTactKeisan, int nKijunKishuId)
{
	int nSize = aSublineId.GetSize();
// Modify ... ( ADD )
	for (int j = 0; j < nSize; j++){
		if( m_aSubline[aSublineId[j]].cSeisanJoken.fTact <= 0 ){
			g_pSystem->DispMessage("E009003");
			return;
		}
	}
// By Y.Itabashi (xxxxx) 2007.02.02

// Modify ... ( ADD )
	m_nStatus = STATUS_JIDO_HENSEI;
// By Y.Itabashi (xxxxx) 2007.03.06

	int nKishuNum = m_aKishu.GetSize();
// --Change 2007/03/01 aizawa 不懸D-198
//	g_pSystem->SetProgressBar(nSize * nKishuNum * (nHenseiMode == 0 ? 2 : 3));
	g_pSystem->SetProgressBar(nSize * nKishuNum * 2 + (nHenseiMode == 0 ? 0 : nSize));
// --Change

	for (int i = 0; i < nSize; i++)
// --Change 2007/03/01 aizawa 不懸D-198
//		JidoHenseiSub(aSublineId[i], nHenseiMode, nTactKeisan, nKijunKishuId);
		JidoHenseiSub(aSublineId[i], nHenseiMode, nTactKeisan, nKijunKishuId, i + 1, nSize);
// --Change

	// キット化作業の自動編成
	JidoHensei1Kitting(aSublineId);

	g_pSystem->ClearProgressBar();

	// データ更新フラグ設定
	m_bUpdate = TRUE;

// Modify ... ( ADD )
	m_nStatus = STATUS_SHUDO_HENSEI;
// By Y.Itabashi (xxxxx) 2007.03.06
}

	// キット化以外の自動編成
// --Change 2007/03/01 aizawa 不懸D-198
//void CDataManager::JidoHenseiSub(int nSublineId, int nHenseiMode, int nTactKeisan, int nKijunKishuId)
void CDataManager::JidoHenseiSub(int nSublineId, int nHenseiMode, int nTactKeisan, int nKijunKishuId, int nProcessSublixxxnt, int nProcessSublineNum)
// --Change
{
	CWaitCursor cWaitCursor;
	SSubline &cSubline = m_aSubline[nSublineId];
	CYosoSagyogunArray &aYosoSagyogun = cSubline.aYosoSagyogun;
	CStringArray &aSagyosha = cSubline.aSagyosha;
	int nYosoSagyogunId;
	int nKishuId;
	int nKishuNum = m_aKishu.GetSize();
	int nMaxSagyoshaId = -1;

	// 既存の作業者を削除
	aSagyosha.RemoveAll();

	// 既存の作業者割付を解除
	int nYosoSagyogun = aYosoSagyogun.GetSize();
	for (nYosoSagyogunId = 0; nYosoSagyogunId < nYosoSagyogun; nYosoSagyogunId++) {
		SYosoSagyogun &cYosoSagyogun = aYosoSagyogun[nYosoSagyogunId];
		for (nKishuId = 0; nKishuId < nKishuNum; nKishuId++)
			cYosoSagyogun.aKishuSagyo[nKishuId].nSagyoshaId = -1;
	}

	// タクト切り・作業者割付
	JidoHensei1(nSublineId, nTactKeisan, nKijunKishuId, nMaxSagyoshaId);

	// 作業者を作業順に並び替え
	RenumberSagyoshaId(nSublineId);

	// 充実度アップ
	if (nHenseiMode == 1) {
// --Add 2007/03/01 aizawa 不懸D-198
		// ステータスバー、プログレスバー更新
		DispProgressBarSubline(nSublineId, g_pSystem->m_cMenuDisp.GetMenu("D005001", "S001015"), nProcessSublixxxnt, nProcessSublineNum);
// --Add
		JidoHensei2(nSublineId, nTactKeisan);

		// 作業者を作業順に並び替え
		RenumberSagyoshaId(nSublineId);
	}

	// 作業者名を振り直し
	RenameSagyoshaName(nSublineId);

	// 作業者割付済みフラグ設定
	cSubline.bSagyoshaWaritsuke = TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CDataManager::JidoHensei1
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		タクト切り処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		BOOL	bKitting				[I] キット化工程フラグ（FALSE:通常工程、TRUE:キット化工程）
//		int		&nMaxSagyoshaId			[I/O] 最大作業者ID
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		工順を入れ替えずにタクト切りを行う。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CDataManager::JidoHensei1(int nSublineId, int nTactKeisan, int nKijunKishuId, int &nMaxSagyoshaId)
{
	SSubline &cSubline = m_aSubline[nSublineId];
	CKoteiArray aKotei;
	CKoteiWaritsukeArray cKoteiWaritsukeArray;
	int nMaxSagyoshaId2 = nMaxSagyoshaId;

	// 基準機種
	int nProcessCount = 0;
// --Change 2007/03/01 aizawa 不懸D-198
//	DispProgressBar(nKijunKishuId, "タクト切り", ++nProcessCount);
	DispProgressBar(nKijunKishuId, g_pSystem->m_cMenuDisp.GetMenu("D005001", "S001013"), ++nProcessCount);
// --Change
	GetKoteiArray(nSublineId, nKijunKishuId, aKotei);
// --Change 2007/03/01 aizawa 不懸D-198
//	DispProgressBar(nKijunKishuId, "作業者割付", nProcessCount);
	DispProgressBar(nKijunKishuId, g_pSystem->m_cMenuDisp.GetMenu("D005001", "S001014"), nProcessCount);
// --Change
	JidoHensei1Sub(nSublineId, nMaxSagyoshaId, nMaxSagyoshaId2, nKijunKishuId, aKotei, cKoteiWaritsukeArray, FALSE, TRUE, TRUE, nTactKeisan);

	// その他の機種
	int nKishuNum = m_aKishu.GetSize();
	for (int nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
		if (nKishuId != nKijunKishuId) {
// --Change 2007/03/01 aizawa 不懸D-198
//			DispProgressBar(nKishuId, "タクト切り", ++nProcessCount);
			DispProgressBar(nKishuId, g_pSystem->m_cMenuDisp.GetMenu("D005001", "S001013"), ++nProcessCount);
// --Change

			// 工程順に並び替え
			GetKoteiArray(nSublineId, nKishuId, aKotei);

// --Change 2007/03/01 aizawa 不懸D-198
//			DispProgressBar(nKishuId, "作業者割付", nProcessCount);
			DispProgressBar(nKishuId, g_pSystem->m_cMenuDisp.GetMenu("D005001", "S001014"), nProcessCount);
// --Change

			JidoHensei1Sub(nSublineId, nMaxSagyoshaId, nMaxSagyoshaId2, nKishuId, aKotei, cKoteiWaritsukeArray, FALSE, FALSE, FALSE, nTactKeisan);
			JidoHensei1Sub(nSublineId, nMaxSagyoshaId, nMaxSagyoshaId2, nKishuId, aKotei, cKoteiWaritsukeArray, TRUE, FALSE, FALSE, nTactKeisan);
			JidoHensei1Sub(nSublineId, nMaxSagyoshaId, nMaxSagyoshaId2, nKishuId, aKotei, cKoteiWaritsukeArray, FALSE, TRUE, FALSE, nTactKeisan);
		}
	}

	// 単独工程作業者を統合
	while (JidoHensei1Sub2(nSublineId))
		;
}

//*****************************************************************************************************
//  1. 関数名
//		CDataManager::JidoHensei1
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		タクト切り処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		BOOL					bKitting				[I] キット化工程フラグ（FALSE:通常工程、TRUE:キット化工程）
//		int						&nMaxSagyoshaId			[I/O] 最大作業者ID
//		int						nMaxSagyoshaId2			[I] 前回最大作業者ID
//		int						nKishuId				[I] 機種ID
//		CKoteiArray				&aKotei					[I] 工程配列データ
//		CKoteiWaritsukeArray	&cKoteiWaritsukeArray	[I] 割付配列データ
//		BOOL					bReverse				[I] 逆方向割付フラグ
//		BOOL					bNewWaritsuke			[I] 新規作業者割付フラグ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		作業時間を累積し、目標タクトに達するまでの作業を作業者に割り付ける。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CDataManager::JidoHensei1Sub(int nSublineId, int &nMaxSagyoshaId, int nMaxSagyoshaId2, int nKishuId, CKoteiArray &aKotei, CKoteiWaritsukeArray &cKoteiWaritsukeArray, BOOL bReverse, BOOL bNewWaritsuke, BOOL bKijunKishu, int nTactKeisan)
{
	SSubline &cSubline = m_aSubline[nSublineId];
	CYosoSagyogunArray &aYosoSagyogun = cSubline.aYosoSagyogun;
	CStringArray &aSagyosha = cSubline.aSagyosha;
	CString sKey;
	int nKoteiId;
	int nYosoSagyogunId;
	int nSagyoshaId = -1;
	CString sSagyoshaName;
	int nStartKotei;
	int nEndKotei;
	int nStep;
	BOOL bJidoKotei = FALSE;
	BOOL bChange = TRUE;

	// 工程ごとの割付
	int nKotei = aKotei.GetSize();

	if (!bReverse) {
		nStartKotei = 0;
		nEndKotei = nKotei;
		nStep = 1;
	} else {
		nStartKotei = nKotei - 1;
		nEndKotei = -1;
		nStep = -1;
	}

	for (nKoteiId = nStartKotei; nKoteiId != nEndKotei; nKoteiId += nStep) {
		SKotei &cKotei = aKotei[nKoteiId];

		// 共通工程をチェック
		SKoteiWaritsuke *pKoteiWaritsuke = NULL;
		int nYosoSagyogunNum = cKotei.aYosoSagyogunId.GetSize();
		for (nYosoSagyogunId = 0; nYosoSagyogunId < nYosoSagyogunNum; nYosoSagyogunId++) {
			SYosoSagyogun &cYosoSagyogun = aYosoSagyogun[cKotei.aYosoSagyogunId[nYosoSagyogunId]];
			sKey.Format("%d", cYosoSagyogun.aKishuSagyo[nKishuId].nGroupNo);
			pKoteiWaritsuke = cKoteiWaritsukeArray.LookupElement(sKey);
			if (pKoteiWaritsuke != NULL)
				break;
		}

		// 既に割付済みの工程をチェック
		if (pKoteiWaritsuke != NULL) {
			// 共通工程は同じ作業者に割り付ける
			nSagyoshaId = pKoteiWaritsuke->nSagyoshaId;
		} else {
			// 作業者チェンジ
			if (bChange || (bJidoKotei && aYosoSagyogun[cKotei.aYosoSagyogunId[0]].sJidoKotei != g_pConst->m_sJido) || (cKotei.fTime != 0 && GetSagyoJikan(nSublineId, nSagyoshaId, nKishuId) + cKotei.fTime > cSubline.cSeisanJoken.fTact)) {
				if (bNewWaritsuke) {
					nSagyoshaId = ++nMaxSagyoshaId;

					// 作業者名を設定
					sSagyoshaName.Format("%s%03d", cSubline.sSagyoshaName, nSagyoshaId - nMaxSagyoshaId2);
					aSagyosha.Add(sSagyoshaName);
				} else
					nSagyoshaId = -1;
			}
		}
		bChange = FALSE;

		// 要素作業群に作業者を割付
		if (nSagyoshaId != -1) {
			bJidoKotei = FALSE;
			for (nYosoSagyogunId = 0; nYosoSagyogunId < nYosoSagyogunNum; nYosoSagyogunId++) {
				SYosoSagyogun &cYosoSagyogun = aYosoSagyogun[cKotei.aYosoSagyogunId[nYosoSagyogunId]];
				if (cYosoSagyogun.aKishuSagyo[nKishuId].nSagyoshaId == -1) {
					// 作業者ID設定
					cYosoSagyogun.aKishuSagyo[nKishuId].nSagyoshaId = nSagyoshaId;

					// 共通工程セット
					sKey.Format("%d", cYosoSagyogun.aKishuSagyo[nKishuId].nGroupNo);
					pKoteiWaritsuke = cKoteiWaritsukeArray.LookupElement(sKey);
					if (pKoteiWaritsuke == NULL) {
						pKoteiWaritsuke = cKoteiWaritsukeArray.AddElement(sKey);
						pKoteiWaritsuke->nSagyoshaId = nSagyoshaId;
						pKoteiWaritsuke->nGroupNo = cYosoSagyogun.aKishuSagyo[nKishuId].nGroupNo;
					}
				}

				// 自動工程だったら作業者チェンジ
				if (nTactKeisan == 1 && cYosoSagyogun.sJidoKotei == g_pConst->m_sJido)
					bJidoKotei = TRUE;
			}
		}
	}
}

struct SKishuTime {
	double fTime[MAX_KISHU];
};

// キット化作業の自動編成
void CDataManager::JidoHensei1Kitting(CIntArrayEx &aSublineId)
{
	CKoteiArray aKotei;
	int i;
	CString sSagyoshaName;
	int nKishuId;
	int nYosoSagyogunId;
	typedef double tTime[MAX_KISHU];
	CArray <SKishuTime, SKishuTime&> aTime;

	m_aKittingSagyosha.RemoveAll();

	int nKishuNum = m_aKishu.GetSize();

	int nSublineNum = m_aSubline.GetSize();
	for (int nSublineId = 0; nSublineId < nSublineNum; nSublineId++) {
		SSubline &cSubline = m_aSubline[nSublineId];

		// 既存の作業者割付を解除
		int nYosoSagyogun = cSubline.aYosoSagyogunKit.GetSize();
		for (nYosoSagyogunId = 0; nYosoSagyogunId < nYosoSagyogun; nYosoSagyogunId++) {
			SYosoSagyogun &cYosoSagyogun = cSubline.aYosoSagyogunKit[nYosoSagyogunId];
			for (nKishuId = 0; nKishuId < nKishuNum; nKishuId++)
				cYosoSagyogun.aKishuSagyo[nKishuId].nSagyoshaId = -1;
		}
	}

	// キット化工程のタクトを取得
	double fTact = GetKittingTact();
	if (fTact == 0)
		return;

	int nMaxSagyoshaId = -1;
	double fTime = 0;

	int nSize = aSublineId.GetSize();
	for (i = 0; i < nSize; i++) {
		int nSublineId = aSublineId[i];
		SSubline &cSubline = m_aSubline[nSublineId];

		GetKittingKoteiArray(nSublineId, aKotei);
		int nKoteiNum = aKotei.GetSize();

		int nStartSagyoshaId = nMaxSagyoshaId;

		for (nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
			int nSagyoshaId = nStartSagyoshaId;

			for (int nKoteiId = 0; nKoteiId < nKoteiNum; nKoteiId++) {
				SKotei &cKotei = aKotei[nKoteiId];

				if (cSubline.aYosoSagyogunKit[cKotei.aYosoSagyogunId[0]].aKishuSagyo[nKishuId].nSagyojun != 0) {
					if (nSagyoshaId == -1 || aTime[nSagyoshaId].fTime[nKishuId] + cKotei.fTime > fTact) {
						if (++nSagyoshaId > nMaxSagyoshaId) {
							aTime.SetSize(++nMaxSagyoshaId + 1);

							// 作業者名を設定
							sSagyoshaName.Format("KIT%03d", nSagyoshaId + 1);
							m_aKittingSagyosha.Add(sSagyoshaName);
						}
					}

					aTime[nSagyoshaId].fTime[nKishuId] += cKotei.fTime;

					int nYosoSagyogunNum = cKotei.aYosoSagyogunId.GetSize();
					for (nYosoSagyogunId = 0; nYosoSagyogunId < nYosoSagyogunNum; nYosoSagyogunId++) {
						SYosoSagyogun &cYosoSagyogun = cSubline.aYosoSagyogunKit[cKotei.aYosoSagyogunId[nYosoSagyogunId]];

						if (cYosoSagyogun.aKishuSagyo[nKishuId].nSagyojun != 0)
							cYosoSagyogun.aKishuSagyo[nKishuId].nSagyoshaId = nSagyoshaId;
					}
				}
			}
		}
	}
}

// 単独工程作業者を統合
BOOL CDataManager::JidoHensei1Sub2(int nSublineId)
{
	SSubline &cSubline = m_aSubline[nSublineId];
	CYosoSagyogunArray &aYosoSagyogun = cSubline.aYosoSagyogun;
	BOOL bSagyoshaKishu[MAX_KISHU];
	BOOL bMargeKishu[MAX_KISHU];
	int nMargeKishuId;
	int nMargeSagyoshaId = -1;
	BOOL bMarge = FALSE;

	// 作業者を作業順に並び替え
	RenumberSagyoshaId(nSublineId);

	// 機種数取得
	int nKishuNum = m_aKishu.GetSize();

	// 要素作業群数取得
	int nYosoSagyogunNum = aYosoSagyogun.GetSize();

	// 単独工程のみの作業を統合する
	int nSagyoshaNum = cSubline.aSagyosha.GetSize();
	for (int nSagyoshaId = 0; nSagyoshaId < nSagyoshaNum; nSagyoshaId++) {
		memset(bSagyoshaKishu, 0, sizeof(bSagyoshaKishu));

		int nKishuCount = 0;
		for (int nYosoSagyogunId = 0; nYosoSagyogunId < nYosoSagyogunNum; nYosoSagyogunId++) {
			SYosoSagyogun &cYosoSagyogun = cSubline.aYosoSagyogun[nYosoSagyogunId];
			for (int nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
				if (cYosoSagyogun.aKishuSagyo[nKishuId].nSagyoshaId == nSagyoshaId && !bSagyoshaKishu[nKishuId]) {
					bSagyoshaKishu[nKishuId] = TRUE;
					nKishuCount++;
					nMargeKishuId = nKishuId;
				}
			}
		}

		if (nKishuCount == 1) {
			if (nMargeSagyoshaId == -1) {
				nMargeSagyoshaId = nSagyoshaId;
				memset(bMargeKishu, 0, sizeof(bMargeKishu));
				bMargeKishu[nMargeKishuId] = TRUE;
			} else {
				if (!bMargeKishu[nMargeKishuId]) {
					// 作業者IDを書き換え
					for (int nYosoSagyogunId = 0; nYosoSagyogunId < nYosoSagyogunNum; nYosoSagyogunId++) {
						SYosoSagyogun &cYosoSagyogun = cSubline.aYosoSagyogun[nYosoSagyogunId];
						for (int nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
							SKishuSagyo &cKishuSagyo = cYosoSagyogun.aKishuSagyo[nKishuId];
							if (cKishuSagyo.nSagyoshaId == nSagyoshaId)
								cKishuSagyo.nSagyoshaId = nMargeSagyoshaId;
						}
					}
					bMargeKishu[nMargeKishuId] = TRUE;
					bMarge = TRUE;
				}
			}
		} else {
			nMargeSagyoshaId = -1;
		}
	}

	return bMarge;
}

//*****************************************************************************************************
//  1. 関数名
//		CDataManager::JidoHensei2
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		充実度アップ処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		工順規制に反しない条件で工順を入れ替えて、作業時間に余裕のある作業者に工程を割り付け直す。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CDataManager::JidoHensei2(int nSublineId, int nTactKeisan)
{
	SSubline &cSubline = m_aSubline[nSublineId];
	CYosoSagyogunArray &aYosoSagyogun = cSubline.aYosoSagyogun;
	int nKoteiId, nKoteiId2;
	int nSagyoshaId;
	int i, j, k;
	SHenseiTime cHenseiTime;
	int nLastSagyoJun;
	SSagyojun cSagyojun;
	CIntArrayEx aHenseiKoteiId;
	CIntArrayEx aKyotsuKoteiId;
	BOOL bLastJidoKotei[MAX_KISHU];
	double fLimit = cSubline.cSeisanJoken.bKyoseiKudo ? cSubline.cSeisanJoken.fTact * (1 + cSubline.cSeisanJoken.fJogen / 100) : cSubline.cSeisanJoken.fTact;

	CSagyojunArray aSagyojun;
	CSagyojunArray aSagyojunSave;
	g_pDataManager->GetSagyojun(nSublineId, -1, aSagyojun);
	int nSagyojunNum = aSagyojun.GetSize();

	CHenseiKoteiArray aHenseiKotei;
	CHenseiKoteiArray aHenseiKoteiSave;
	GetHenseiKotei(nSublineId, aSagyojun, aHenseiKotei);
	int nHenseiKoteiNum = aHenseiKotei.GetSize();

	// 作業者によるループ
	int nSagyoshaNum = cSubline.aSagyosha.GetSize();
	for (nSagyoshaId = 0; nSagyoshaId < nSagyoshaNum; nSagyoshaId++) {
		// 最終自動工程クリア
		memset(bLastJidoKotei, 0, sizeof(bLastJidoKotei));

// Modify ... ( CHANGE )
		// 現在チェックしている作業者に割り付けられた工程がない場合は、充実度UPを行わない
		// 割り付け済みの工程を除く
		BOOL bSagyosha = FALSE;
		for (nKoteiId = 0; nKoteiId < nHenseiKoteiNum; nKoteiId++) {
			SHenseiKotei &cHenseiKotei = aHenseiKotei[nKoteiId];

			if (cHenseiKotei.nSagyoshaId == nSagyoshaId) {
				cHenseiKotei.bMovable = FALSE;
				bLastJidoKotei[cHenseiKotei.nKishuId] = cHenseiKotei.bJido;
				bSagyosha = TRUE;
			}
		}

		if( !bSagyosha )
			continue;
// By Y.Itabashi (xxxxx) 2007.03.08

		aKyotsuKoteiId.RemoveAll();

// Modify ... ( ADD )
		BOOL bHenseiMove = TRUE;
		while( bHenseiMove ){

			bHenseiMove = FALSE;
// By Y.Itabashi (xxxxx) 2007.03.07

		// 工程によるループ
		for (nKoteiId = 0; nKoteiId < nHenseiKoteiNum; nKoteiId++) {
			SHenseiKotei &cHenseiKotei = aHenseiKotei[nKoteiId];

			// 処理済でないこと
// Modify ... ( CHANGE )
//			if (cHenseiKotei.nSagyoshaId <= nSagyoshaId || aKyotsuKoteiId.Find(nKoteiId) != -1)
			if (!cHenseiKotei.bMovable || aKyotsuKoteiId.Find(nKoteiId) != -1)
// By Y.Itabashi (xxxxx) 2007.03.07
				continue;

			// 共通工程を探す
			aHenseiKoteiId.RemoveAll();
			aHenseiKoteiId.Add(nKoteiId);
			int nSize = cHenseiKotei.aGroupNo.GetSize();
			for (i = 0; i < nSize; i++) {
				int nGroupNo = cHenseiKotei.aGroupNo[i];
				if (nGroupNo != 0) {
					for (nKoteiId2 = 0; nKoteiId2 < nHenseiKoteiNum; nKoteiId2++) {
						SHenseiKotei &cHenseiKotei2 = aHenseiKotei[nKoteiId2];
						if (nKoteiId != nKoteiId2) {
							if (cHenseiKotei2.aGroupNo.Find(cHenseiKotei.aGroupNo[i]) != -1) {
								aHenseiKoteiId.AddUnique(nKoteiId2);
								aKyotsuKoteiId.AddUnique(nKoteiId2);
// --Delete 2007/02/26 aizawa 不懸D-194
//								break;
// --Delete
							}
						}
					}
				}
			}

			// 移動できなかった時に戻すため保存
			aHenseiKoteiSave.Copy(aHenseiKotei);
			aSagyojunSave.Copy(aSagyojun);

			BOOL bOK = TRUE;
			int nHenseiKoteiIdNum = aHenseiKoteiId.GetSize();
			for (i = 0; i < nHenseiKoteiIdNum; i++) {
				SHenseiKotei &cHenseiKotei2 = aHenseiKotei[aHenseiKoteiId[i]];
				int nKishuId = cHenseiKotei2.nKishuId;

				// 処理済でないこと
				if (!cHenseiKotei2.bMovable) {
					bOK = FALSE;
					break;
				}

				// ｢自動工程優先で区切る｣を選択している場合で、作業者の最後が自動工程の場合は充実度UPをしない
				if (nTactKeisan == 1 && bLastJidoKotei[cHenseiKotei2.nKishuId]) {
					bOK = FALSE;
					break;
				}

// --Add 2007/02/26 aizawa 不懸D-193
				// ｢自動工程優先で区切る｣を選択している場合は自動工程を移動させない
				if (nTactKeisan == 1 && cHenseiKotei2.bJido) {
					bOK = FALSE;
					break;
				}
// --Add

				// 作業時間を取得
				GetHenseiTime(nSublineId, aHenseiKotei, nSagyoshaId, cHenseiTime);

				// 加重平均が目標タクトを超えないこと
// Modify ... ( DELETE )
/*				if (cHenseiTime.fKajuHeikinTime + cHenseiKotei2.fKajuHeikinTime > cSubline.cSeisanJoken.fTact) {
					bOK = FALSE;
					break;
				}*/
// By Y.Itabashi (xxxxx) 2007.03.06

				// 機種別の作業時間が上限または目標タクトを超えないこと
				if (cHenseiTime.aTime[nKishuId] + cHenseiKotei2.fTime > fLimit) {
					bOK = FALSE;
					break;
				}

				// 移動先を取得
				nLastSagyoJun = 0;
				for (j = 0; j < nSagyojunNum; j++) {
// --Change 2007/02/26 aizawa 不懸D-194,D-195
//					if (aYosoSagyogun[aSagyojun[j].nYosoSagyogunId].aKishuSagyo[nKishuId].nSagyoshaId <= nSagyoshaId)
					int nSagyoshaId2 = aYosoSagyogun[aSagyojun[j].nYosoSagyogunId].aKishuSagyo[aSagyojun[j].nKishuId].nSagyoshaId;
					if (nSagyoshaId2 != -1 && nSagyoshaId2 <= nSagyoshaId)
// --Change
						nLastSagyoJun = j;
				}

// Modify ... ( ADD )
				InitMoveBeforeSagyojun();
				CString sCheckKoteiName;
				int nSetSagyojun = -1;
// By Y.Itabashi (xxxxx) 2007.03.07

				// 工程移動
				int nSize = cHenseiKotei2.aSagyojun.GetSize();
				for (j = 0; j < nSize; j++) {
					int nSagyojun = cHenseiKotei2.aSagyojun[j];
					for (k = 0; k < nSagyojunNum; k++) {
						if (aSagyojun[k].nIndex == nSagyojun) {

// Modify ... ( ADD )
							CString sKoteiName = m_aSubline[nSublineId].aYosoSagyogun[aSagyojun[k].nYosoSagyogunId].sKoteiName;
							CString sYosoSagyogunName = m_aSubline[nSublineId].aYosoSagyogun[aSagyojun[k].nYosoSagyogunId].sYosoSagyogunName;

							// 移動前の位置を設定
							if( sCheckKoteiName.IsEmpty() ){
								sCheckKoteiName = m_aSubline[nSublineId].aYosoSagyogun[aSagyojun[k].nYosoSagyogunId].sKoteiName;
								nSetSagyojun = -1;
								for( int icnt = 0; icnt <= k; icnt++ ){
									if( aSagyojun[icnt].nKishuId == nKishuId )
										nSetSagyojun++;
								}
							}else if( sCheckKoteiName != sKoteiName ){
								sCheckKoteiName = m_aSubline[nSublineId].aYosoSagyogun[aSagyojun[k].nYosoSagyogunId].sKoteiName;
								nSetSagyojun = -1;
								for( int icnt = 0; icnt <= k; icnt++ ){
									if( aSagyojun[icnt].nKishuId == nKishuId )
										nSetSagyojun++;
								}
							}

							SetMoveBeforeSagyojun(nSublineId, sKoteiName, sYosoSagyogunName,
										 nKishuId, nSetSagyojun, eKojunCheckTypeKotei);

							m_aSubline[nSublineId].aYosoSagyogun[aSagyojun[k].nYosoSagyogunId].aKishuSagyo[nKishuId].bNewMove
								= TRUE;
// By Y.Itabashi (xxxxx) 2007.03.07

							cSagyojun = aSagyojun[k];
							aSagyojun.RemoveAt(k);
							aSagyojun.InsertAt(++nLastSagyoJun, cSagyojun);
							break;
						}
					}
				}

				// 工順規制チェック
				if (!CheckKojunKisei(nSublineId, cHenseiKotei2.aYosoSagyogunId, nKishuId, aSagyojun)) {
					bOK = FALSE;
					break;
				}

				// 作業者書き換え
				cHenseiKotei2.nSagyoshaId = nSagyoshaId;
				cHenseiKotei2.bMovable = FALSE;
			}

			if (bOK) {
				// OKなら作業者書き換え
				for (i = 0; i < nHenseiKoteiIdNum; i++) {
					SHenseiKotei &cHenseiKotei2 = aHenseiKotei[aHenseiKoteiId[i]];
					int nKishuId = cHenseiKotei2.nKishuId;
					int nSize = cHenseiKotei2.aSagyojun.GetSize();
					for (j = 0; j < nSize; j++) {
						int nSagyojun = cHenseiKotei2.aSagyojun[j];
						for (k = 0; k < nSagyojunNum; k++) {
							if (aSagyojun[k].nIndex == nSagyojun) {
								SYosoSagyogun &cYosoSagyogun = aYosoSagyogun[aSagyojun[k].nYosoSagyogunId];
								if (cYosoSagyogun.aKishuSagyo[nKishuId].nSagyojun != 0)
									cYosoSagyogun.aKishuSagyo[nKishuId].nSagyoshaId = nSagyoshaId;
								break;
							}
						}
					}
				}

// Modify ... ( ADD )
				bHenseiMove = TRUE;
				ClearNewMoveFlag();
// By Y.Itabashi (xxxxx) 2007.03.07

			} else {
				// NGなら元に戻す
				aHenseiKotei.Copy(aHenseiKoteiSave);
				aSagyojun.Copy(aSagyojunSave);
			}
		}

// Modify ... ( ADD )
		}
// By Y.Itabashi (xxxxx) 2007.03.07
	}

	// 作業順に従って要素作業群順番を書き換え
	g_pDataManager->OrderBySagyojun(nSublineId, aSagyojun);
}

void CDataManager::GetHenseiKotei(int nSublineId, CSagyojunArray &aSagyojun, CHenseiKoteiArray &aHenseiKotei)
{
	SSubline *pSubline = g_pDataManager->GetSublineData(nSublineId);
	CYosoSagyogunArray &aYosoSagyogun = pSubline->aYosoSagyogun;
	double fTime;
	int nSagyoshaId;
	int nYosoSagyogunId;
	int nKoteiIndex;
	int nKishuId;
	int nSeisanDaisu = GetSeisanDaisu(nSublineId);

	// 全生産台数、作業者数、機種数を取得
	int nSagyoshaNum = g_pDataManager->GetSagyoshaNum(nSublineId);
	int nKishuNum = g_pDataManager->GetKishuNum();
	int nSagyojunNum = aSagyojun.GetSize();

	// 生産台数の多い順に機種をソート
	CIntArrayEx aKishuJun;
	for (nKishuId = 0; nKishuId < nKishuNum; nKishuId++)
		aKishuJun.Add(nKishuId);
	qsort(aKishuJun.GetData(), aKishuJun.GetSize(), sizeof(int), CompareKishuJun);

	// 作業者によるループ
	for (nSagyoshaId = 0; nSagyoshaId < nSagyoshaNum; nSagyoshaId++) {
		// 機種によるループ
		for (int nKishuIndex = 0; nKishuIndex < nKishuNum; nKishuIndex++) {
			nKishuId = aKishuJun[nKishuIndex];
			nKoteiIndex = -1;

			// 要素作業群によるループ
			for (int i = 0; i < nSagyojunNum; i++) {
				SSagyojun &cSagyojun = aSagyojun[i];
				nYosoSagyogunId = cSagyojun.nYosoSagyogunId;
				if (cSagyojun.nKishuId == nKishuId) {
					SYosoSagyogun &cYosoSagyogun = pSubline->aYosoSagyogun[nYosoSagyogunId];
					if (!cYosoSagyogun.CheckDeleted()) {
						fTime = cYosoSagyogun.GetTime();

						if (nKoteiIndex != -1) {
							if (aHenseiKotei[nKoteiIndex].sKoteiName != cYosoSagyogun.sKoteiName && cYosoSagyogun.sJidoKotei != g_pConst->m_sJido)
								nKoteiIndex = -1;
						}

						SKishuSagyo &cKishuSagyo = cYosoSagyogun.aKishuSagyo[nKishuId];

						if (nSagyoshaId == cKishuSagyo.nSagyoshaId) {
							SHenseiKotei *pHenseiKotei;
							if (nKoteiIndex == -1) {
								pHenseiKotei = aHenseiKotei.AddElement(nKoteiIndex);
								pHenseiKotei->sKoteiNo = cYosoSagyogun.sKoteiNo;
								pHenseiKotei->sKoteiName = cYosoSagyogun.sKoteiName;
								pHenseiKotei->fKajuHeikinTime = 0;
// --Change 2007/02/26 aizawa 不懸D-193
//								pHenseiKotei->bJido = cYosoSagyogun.sJidoKotei == g_pConst->m_sJido;
								pHenseiKotei->bJido = FALSE;
//								pHenseiKotei->bMovable = cYosoSagyogun.sJidoKotei != g_pConst->m_sJido;
								pHenseiKotei->bMovable = TRUE;
// --Change
								pHenseiKotei->nKishuId = nKishuId;
								pHenseiKotei->nSagyoshaId = cYosoSagyogun.aKishuSagyo[nKishuId].nSagyoshaId;
								pHenseiKotei->fTime = 0;
							} else
								pHenseiKotei = &aHenseiKotei[nKoteiIndex];
								
							pHenseiKotei->fTime += fTime;
							pHenseiKotei->fKajuHeikinTime += fTime * m_aKishu[nKishuId].nSeisanDaisu / nSeisanDaisu;
							pHenseiKotei->aYosoSagyogunId.Add(nYosoSagyogunId);
							pHenseiKotei->aSagyojun.Add(cSagyojun.nIndex);
							pHenseiKotei->aGroupNo.Add(cKishuSagyo.nGroupNo);
// --Add 2007/02/26 aizawa 不懸D-193
							pHenseiKotei->bJido |= (BOOL)(cYosoSagyogun.sJidoKotei == g_pConst->m_sJido);
// --Add
						}
					}
				}
			}
		}
	}
}

int CDataManager::CompareKishuJun(const void *arg1, const void *arg2)
{
// --Change 2007/02/26 aizawa 不懸D-194
//	SKishu *pKishu1 = g_pDataManager->GetKishuData(*(int *)arg1);
//	SKishu *pKishu2 = g_pDataManager->GetKishuData(*(int *)arg2);
//
//	return pKishu2->nSeisanDaisu - pKishu1->nSeisanDaisu;
	int nKishuId1 = *(int *)arg1;
	int nKishuId2 = *(int *)arg2;
	SKishu *pKishu1 = g_pDataManager->GetKishuData(nKishuId1);
	SKishu *pKishu2 = g_pDataManager->GetKishuData(nKishuId2);

	if (pKishu2->nSeisanDaisu != pKishu1->nSeisanDaisu)
		return pKishu2->nSeisanDaisu - pKishu1->nSeisanDaisu;
	else
		return nKishuId1 - nKishuId2;
// --Change
}

//*****************************************************************************************************
//  1. 関数名
//		CDataManager::GetHenseiTime
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		充実度アップ処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		int				nSagyoshaId			[I] 作業者ID
//		SHenseiTime		&cHenseiTime		[O] 作業時間
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		指定された作業者に割り付けられている工程の作業時間を取得する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CDataManager::GetHenseiTime(int nSublineId, CHenseiKoteiArray &aHenseiKotei, int nSagyoshaId, SHenseiTime &cHenseiTime)
{
	int nSeisanDaisu = GetSeisanDaisu(nSublineId);
	int nKishuNum = m_aKishu.GetSize();
	int i;

	cHenseiTime.fKajuHeikinTime = 0;
	cHenseiTime.aTime.SetSize(nKishuNum);

	for (i = 0; i < nKishuNum; i++)
		cHenseiTime.aTime[i] = 0;

	int nHenseiKoteiNum = aHenseiKotei.GetSize();
	for (i = 0; i < nHenseiKoteiNum; i++) {
		SHenseiKotei &cHenseiKotei = aHenseiKotei[i];
		if (cHenseiKotei.nSagyoshaId == nSagyoshaId) {
			cHenseiTime.aTime[cHenseiKotei.nKishuId] += cHenseiKotei.fTime;
			cHenseiTime.fKajuHeikinTime += cHenseiKotei.fTime * m_aKishu[cHenseiKotei.nKishuId].nSeisanDaisu / nSeisanDaisu;
		}
	}
}

void CDataManager::DispProgressBar(int nKishuId, LPCTSTR pProcess, int nCount)
{
	CString sStatusBar;

// --Change 2007/03/01 aizawa 不懸D-198
//	sStatusBar.Format("%sの%s実行中", m_aKishu[nKishuId].sKishuName, pProcess);
	sStatusBar.Format(pProcess, m_aKishu[nKishuId].sKishuName, pProcess);
// --Change
	g_pSystem->StepProgressBar(sStatusBar, nCount, m_aKishu.GetSize());
}

// --Add 2007/03/01 aizawa 不懸D-198
void CDataManager::DispProgressBarSubline(int nSublineId, LPCTSTR pProcess, int nProcessCnt, int ProcessNum)
{
	CString sStatusBar;

	sStatusBar.Format(pProcess, m_aSubline[nSublineId].sSublineName, pProcess);
	g_pSystem->StepProgressBar(sStatusBar, nProcessCnt, ProcessNum);
}
// --Add
