//*****************************************************************************************************
//  1. ファイル名
//		DataManagerKojunKisei.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		CDataManager クラスのインプリメンテーション（工順規制関連）
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************

#include "stdafx.h"
#include "workscheduler.h"

// エクセルオープン情報
struct SExcelOpenInfo {
	UINT nParam;
	CStringArray aPathName;
};

// 工順規制チェック
// Modify ... ( CHANGE )			サブラインが変更されても、移動する要素の現在いるサブラインで規制を検索する（UG1確認済み）
BOOL CDataManager::CheckKojunKisei(int nSublineIdFrom, int nSublineId, EKojunCheckType eKojunCheckType)
//BOOL CDataManager::CheckKojunKisei(int nSublineId, EKojunCheckType eKojunCheckType)
// By Y.Itabashi (xxxxx) 2007.02.07
{
	SSubline &cSubline = m_aSubline[nSublineId];
	CSagyojunArray aSagyojun;
	BOOL bNoCheck = FALSE;

	int nKishuNum = m_aKishu.GetSize();

	// ライン規制チェック
	BOOL bLineKiseiError = FALSE;
	for (int nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
		GetSagyojun(nSublineId, nKishuId, aSagyojun);

		int nYosoSagyogunId1, nYosoSagyogunId2;
		BOOL bCheckFlag = FALSE;
		int nSagyojunNum = aSagyojun.GetSize();
		for (int i = 0; i < nSagyojunNum; i++) {
			int nYosoSagyogunId = aSagyojun[i].nYosoSagyogunId;
			SYosoSagyogun &cYosoSagyogun = cSubline.aYosoSagyogun[nYosoSagyogunId];
			if (cYosoSagyogun.aKishuSagyo[nKishuId].bNewMove) {
				if (!bCheckFlag) {
					nYosoSagyogunId1 = nYosoSagyogunId;
					bCheckFlag = TRUE;
				}
			} else {
				if (bCheckFlag) {
					nYosoSagyogunId2 = aSagyojun[i - 1].nYosoSagyogunId;
// Modify ... ( CHANGE )			サブラインが変更されても、移動する要素の現在いるサブラインで規制を検索する（UG1確認済み）
					if (!LineKiseiCheck(nKishuId, nSublineIdFrom, nSublineId, nYosoSagyogunId1, nYosoSagyogunId2, aSagyojun, bNoCheck))
//					if (!LineKiseiCheck(nKishuId, cYosoSagyogun.nMoveSublineId, nSublineId, nYosoSagyogunId1, nYosoSagyogunId2, aSagyojun, bNoCheck))
// By Y.Itabashi (xxxxx) 2007.02.07
						bLineKiseiError = TRUE;
					bCheckFlag = FALSE;
				}
			}
		}
// Modify ... ( CHANGE )			最後尾に移動する際も片側だけライン規制チェックを行う
		if (bCheckFlag) {
			nYosoSagyogunId2 = aSagyojun[i - 1].nYosoSagyogunId;
			if (!LineKiseiCheck(nKishuId, nSublineIdFrom, nSublineId, nYosoSagyogunId1, nYosoSagyogunId2, aSagyojun, bNoCheck))
				bLineKiseiError = TRUE;
			bCheckFlag = FALSE;
		}
// By Y.Itabashi (xxxxx) 2007.02.07
	}
	if (bLineKiseiError) {
		if (!LineKiseiError(eKojunCheckType))
			return FALSE;
	}
// Modify ... ( ADD )
	// 規制チェック無しメッセージ（ライン規制）
	if (bNoCheck)
		g_pSystem->DispMessage("I013018");
// By Y.Itabashi (xxxxx) 2007.02.21

	// 型式規制チェック
	CKojunErrInfoArray aKojunErrInfo;
	int nYosoSagyogunNum = cSubline.aYosoSagyogun.GetSize();
	for (int nYosoSagyogunId = 0; nYosoSagyogunId < nYosoSagyogunNum; nYosoSagyogunId++) {
		SYosoSagyogun &cYosoSagyogun = cSubline.aYosoSagyogun[nYosoSagyogunId];

		for (int nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
			SKishuSagyo &cKishuSagyo = cYosoSagyogun.aKishuSagyo[nKishuId];
			if (cKishuSagyo.bNewMove) {
				GetSagyojun(nSublineId, nKishuId, aSagyojun);

// Modify ... ( CHANGE )			サブラインが変更されても、移動する要素の現在いるサブラインで規制を検索する（UG1確認済み）
				if(!KatashikiKiseiCheck(nKishuId, nSublineIdFrom, nSublineId, nYosoSagyogunId, aSagyojun, aKojunErrInfo, TRUE, eKojunCheckType, bNoCheck)) {
//				if(!KatashikiKiseiCheck(nKishuId, cYosoSagyogun.nMoveSublineId, nSublineId, nYosoSagyogunId, aSagyojun, aKojunErrInfo, TRUE)) {
// By Y.Itabashi (xxxxx) 2007.02.21
					if (!KatashikiKiseiError(eKojunCheckType, aKojunErrInfo))
						return FALSE;
				}
			}
		}
	}

// Modify ... ( CHANGE )
	// 規制チェック無しメッセージ（型式規制）
	if (bNoCheck)
		g_pSystem->DispMessage("I013022");
// By Y.Itabashi (xxxxx) 2007.02.21

// Modify ... ( ADD )
	// 工順規制違反ファイル
	SaveKojunKiseiError();
// By Y.Itabashi (xxxxx) 2007.02.14

	return TRUE;
}

// 工順規制チェック
BOOL CDataManager::CheckKojunKisei(int nSublineId, CIntArrayEx &aYosoSagyogunId, int nKishuId, CSagyojunArray &aSagyojun)
{
	CKojunErrInfoArray aKojunErrInfo;
	int nSize = aYosoSagyogunId.GetSize();

	// ライン規制チェック
	BOOL bNoCheck;
	if (!LineKiseiCheck(nKishuId, nSublineId, nSublineId, aYosoSagyogunId[0], aYosoSagyogunId[nSize - 1], aSagyojun, bNoCheck))
		return FALSE;

	// 型式規制チェック
	for (int i = 0; i < nSize; i++) {
// Modify ... ( CHANGE )
		if(!KatashikiKiseiCheck(nKishuId, nSublineId, nSublineId, aYosoSagyogunId[i], aSagyojun, aKojunErrInfo, FALSE, eKojunCheckTypeKotei, bNoCheck))
//		if(!KatashikiKiseiCheck(nKishuId, nSublineId, nSublineId, aYosoSagyogunId[i], aSagyojun, aKojunErrInfo, FALSE))
// By Y.Itabashi (xxxxx) 2007.02.21
			return FALSE;
	}

	return TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CDataManager::CheckLineKisei
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ENG姿勢チェック
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CString			&sKoteiName			[I] 工程名
//		CStringArray	&aKoteiOrder		[I] 工程順
//		CString			&sErrMsg			[O] エラーメッセージ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL		TRUE:正常，FALSE:ENG姿勢エラー有り
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		指定された工程順の中の工程がENG姿勢に反するかチェックする。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
BOOL CDataManager::LineKiseiCheck(int nKishuId, int nSublineIdFrom, int nSublineIdTo, int nYosoSagyogunId1, int nYosoSagyogunId2, CSagyojunArray &aSagyojun, BOOL &bNoCheck)
{
	SSubline &cSublineTo = m_aSubline[nSublineIdTo];
	int i;
	CString sEngGenjo1, sEngGenjo2;
	BOOL bNG1 = FALSE;
	BOOL bNG2 = FALSE;
	SLineKisei *pLineKisei;
// Modify ... ( ADD )
	SLineError cLineError;
// By Y.Itabashi (xxxxx) 2007.02.14

// Modify ... ( ADD )
	bNoCheck = FALSE;
// By Y.Itabashi (xxxxx) 2007.02.21

	// サブライン間チェック
	if (nSublineIdFrom != nSublineIdTo) {
		if (m_aKishu[nKishuId].aHonryuSublineId.Find(nSublineIdFrom) == -1 || m_aKishu[nKishuId].aHonryuSublineId.Find(nSublineIdTo) == -1)
			return TRUE;
	}

	// 最初の要素作業群の作業順を取得
// Modify ... ( CHANGE )
	int nSagyojun1 = -1;
//	int nSagyojun1 = 0;
// By Y.Itabashi (xxxxx) 2007.02.07
	int nSagyojunNum = aSagyojun.GetSize();
	for (i = 0; i < nSagyojunNum; i++) {
		if (aSagyojun[i].nYosoSagyogunId == nYosoSagyogunId1) {
			nSagyojun1 = i;
			break;
		}
	}

// Modify ... ( CHANGE )
	if( nSagyojun1 == 0 ){
		int nHoryuSublineIndex = m_aKishu[nKishuId].aHonryuSublineId.Find(nSublineIdTo);
		if( m_aKishu[nKishuId].aHonryuSublineId.Find(nSublineIdTo) == -1 )
			nSagyojun1 = -1;
		else if( m_aKishu[nKishuId].aHonryuSublineId.Find(nSublineIdTo) == 0 )
			nSagyojun1 = -1;
		else if( m_aKishu[nKishuId].aHonryuSublineId.Find(nSublineIdTo) == m_aKishu[nKishuId].aHonryuSublineId.GetSize() - 1 )
			nSagyojun1 = -1;
	}

	if (nSagyojun1 != -1) {
//	if (nSagyojun1 != 0) {
// By Y.Itabashi (xxxxx) 2007.02.07
		// 移動元ライン規制情報取得
		SYosoSagyogun &cYosoSagyogun1 = cSublineTo.aYosoSagyogun[aSagyojun[nSagyojun1].nYosoSagyogunId];
		pLineKisei = FindLineKisei(nSublineIdFrom, cYosoSagyogun1.sKoteiNo, cYosoSagyogun1.sKoteiName, cYosoSagyogun1.sYosoSagyogunName);

// Modify ... ( ADD )
		if( m_nStatus != STATUS_JIDO_HENSEI ){
			cLineError.nKishuID = nKishuId;
			cLineError.sSublineFrom = m_aSubline[nSublineIdFrom].sSublineName;
			cLineError.sKoteiNoFrom = cYosoSagyogun1.sKoteiNo;
			cLineError.sKoteiNameFrom = cYosoSagyogun1.sKoteiName;
			cLineError.sYosoSagyogunNameFrom = cYosoSagyogun1.sYosoSagyogunName;
		}
// By Y.Itabashi (xxxxx) 2007.03.06

		if (pLineKisei != NULL) {
			// 移動先の直前のライン規制情報を取得
// Modify ... ( CHANGE )
			SLineKisei *pLineKisei2 = NULL;
			if( nSagyojun1 == 0 ){
				int nSubline = m_aKishu[nKishuId].aHonryuSublineId[m_aKishu[nKishuId].aHonryuSublineId.Find(nSublineIdTo)-1];
				CSagyojunArray aSagyojun1;
				GetSagyojun(nSubline, nKishuId, aSagyojun1);
				SYosoSagyogun &cYosoSagyogun = m_aSubline[nSubline].aYosoSagyogun[aSagyojun1[aSagyojun1.GetSize()-1].nYosoSagyogunId];
				pLineKisei2 = FindLineKisei(nSubline, cYosoSagyogun.sKoteiNo, cYosoSagyogun.sKoteiName, cYosoSagyogun.sYosoSagyogunName);
// Modify ... ( CHANGE )
				if( m_nStatus != STATUS_JIDO_HENSEI ){
					cLineError.sSublineTo = cSublineTo.sSublineName;
					cLineError.sKoteiNoToB = cYosoSagyogun.sKoteiNo;
					cLineError.sKoteiNameToB = cYosoSagyogun.sKoteiName;
					cLineError.sYosoSagyogunNameToB = cYosoSagyogun.sYosoSagyogunName;
				}
// By Y.Itabashi (xxxxx) 2007.03.06
			}else{
				SYosoSagyogun &cYosoSagyogun = cSublineTo.aYosoSagyogun[aSagyojun[nSagyojun1 - 1].nYosoSagyogunId];
				pLineKisei2 = FindLineKisei(nSublineIdTo, cYosoSagyogun.sKoteiNo, cYosoSagyogun.sKoteiName, cYosoSagyogun.sYosoSagyogunName);
// Modify ... ( CHANGE )
				if( m_nStatus != STATUS_JIDO_HENSEI ){
					cLineError.sSublineTo = cSublineTo.sSublineName;
					cLineError.sKoteiNoToB = cYosoSagyogun.sKoteiNo;
					cLineError.sKoteiNameToB = cYosoSagyogun.sKoteiName;
					cLineError.sYosoSagyogunNameToB = cYosoSagyogun.sYosoSagyogunName;
				}
// By Y.Itabashi (xxxxx) 2007.03.06
			}
//			SYosoSagyogun &cYosoSagyogun = cSublineTo.aYosoSagyogun[aSagyojun[nSagyojun1 - 1].nYosoSagyogunId];
//			SLineKisei *pLineKisei2 = FindLineKisei(nSublineIdTo, cYosoSagyogun.sKoteiNo, cYosoSagyogun.sKoteiName, cYosoSagyogun.sYosoSagyogunName);
// By Y.Itabashi (xxxxx) 2007.02.07
			if (pLineKisei2 != NULL) {
				if (!pLineKisei2->sEngGenjo.IsEmpty()) {
					sEngGenjo1 = pLineKisei2->sEngGenjo;
				}

				// ENG姿勢のチェック
				if (sEngGenjo1 == g_pConst->m_sSeiritsuFr) {
					if (pLineKisei->bEngSeiritsuFr)
						bNG1 = TRUE;
				} else if (sEngGenjo1 == g_pConst->m_sSeiritsuRr) {
					if (pLineKisei->bEngSeiritsuRr)
						bNG1 = TRUE;
				} else if (sEngGenjo1 == g_pConst->m_sToritsuFr) {
					if (pLineKisei->bEngToritsuFr)
						bNG1 = TRUE;
				} else if (sEngGenjo1 == g_pConst->m_sToritsuRr) {
					if (pLineKisei->bEngToritsuRr)
						bNG1 = TRUE;
				} else if (sEngGenjo1 == g_pConst->m_sChokuritsuTop) {
					if (pLineKisei->bEngChokuritsuTop)
						bNG1 = TRUE;
				} else if (sEngGenjo1 == g_pConst->m_sChokuritsuBottom) {
					if (pLineKisei->bEngChokuritsuBottom)
						bNG1 = TRUE;
				}
			} else
				bNoCheck = TRUE;
		} else
			bNoCheck = TRUE;
	}

	// 最後の要素作業群の作業順を取得
// Modify ... ( CHANGE )
	int nSagyojun2 = -1;
//	int nSagyojun2 = 0;
// By Y.Itabashi (xxxxx) 2007.02.07
	for (i = 0; i < nSagyojunNum; i++) {
		if (aSagyojun[i].nYosoSagyogunId == nYosoSagyogunId2) {
			nSagyojun2 = i;
			break;
		}
	}

// Modify ... ( CHANGE )
	if( nSagyojun2 == nSagyojunNum - 1 ){
		int nHoryuSublineIndex = m_aKishu[nKishuId].aHonryuSublineId.Find(nSublineIdTo);
		if( m_aKishu[nKishuId].aHonryuSublineId.Find(nSublineIdTo) == -1 )
			nSagyojun2 = -1;
		else if( m_aKishu[nKishuId].aHonryuSublineId.Find(nSublineIdTo) == 0 )
			nSagyojun2 = -1;
		else if( m_aKishu[nKishuId].aHonryuSublineId.Find(nSublineIdTo) == m_aKishu[nKishuId].aHonryuSublineId.GetSize() - 1 )
			nSagyojun2 = -1;
	}

	if (nSagyojun2 != -1) {
//	if (nSagyojun2 != nSagyojunNum - 1) {
// By Y.Itabashi (xxxxx) 2007.02.07
		// 移動先の直後のライン規制情報を取得
		SYosoSagyogun &cYosoSagyogun2 = cSublineTo.aYosoSagyogun[aSagyojun[nSagyojun2].nYosoSagyogunId];
		pLineKisei = FindLineKisei(nSublineIdFrom, cYosoSagyogun2.sKoteiNo, cYosoSagyogun2.sKoteiName, cYosoSagyogun2.sYosoSagyogunName);

// Modify ... ( ADD )
		if( m_nStatus != STATUS_JIDO_HENSEI && nSagyojun1 == -1 ){
			cLineError.nKishuID = nKishuId;
			cLineError.sSublineFrom = m_aSubline[nSublineIdFrom].sSublineName;
			cLineError.sKoteiNoFrom = cYosoSagyogun2.sKoteiNo;
			cLineError.sKoteiNameFrom = cYosoSagyogun2.sKoteiName;
			cLineError.sYosoSagyogunNameFrom = cYosoSagyogun2.sYosoSagyogunName;
		}
// By Y.Itabashi (xxxxx) 2007.03.06

		if (pLineKisei != NULL) {
// Modify ... ( CHANGE )
			SLineKisei *pLineKisei2 = NULL;
			if( nSagyojun2 == nSagyojunNum - 1 ){
				int nSubline = m_aKishu[nKishuId].aHonryuSublineId[m_aKishu[nKishuId].aHonryuSublineId.Find(nSublineIdTo)+1];
				CSagyojunArray aSagyojun2;
				GetSagyojun(nSubline, nKishuId, aSagyojun2);
				SYosoSagyogun &cYosoSagyogun = m_aSubline[nSubline].aYosoSagyogun[aSagyojun2[0].nYosoSagyogunId];
				pLineKisei2 = FindLineKisei(nSubline, cYosoSagyogun.sKoteiNo, cYosoSagyogun.sKoteiName, cYosoSagyogun.sYosoSagyogunName);
// Modify ... ( ADD )
				if( m_nStatus != STATUS_JIDO_HENSEI ){
					cLineError.sKoteiNoToA = cYosoSagyogun.sKoteiNo;
					cLineError.sKoteiNameToA = cYosoSagyogun.sKoteiName;
					cLineError.sYosoSagyogunNameToA = cYosoSagyogun.sYosoSagyogunName;
				}
// By Y.Itabashi (xxxxx) 2007.03.06
			}else{
				SYosoSagyogun &cYosoSagyogun = cSublineTo.aYosoSagyogun[aSagyojun[nSagyojun2 + 1].nYosoSagyogunId];
				pLineKisei2 = FindLineKisei(nSublineIdTo, cYosoSagyogun.sKoteiNo, cYosoSagyogun.sKoteiName, cYosoSagyogun.sYosoSagyogunName);
// Modify ... ( ADD )
				if( m_nStatus != STATUS_JIDO_HENSEI ){
					cLineError.sSublineTo = cSublineTo.sSublineName;
					cLineError.sKoteiNoToA = cYosoSagyogun.sKoteiNo;
					cLineError.sKoteiNameToA = cYosoSagyogun.sKoteiName;
					cLineError.sYosoSagyogunNameToA = cYosoSagyogun.sYosoSagyogunName;
				}
// By Y.Itabashi (xxxxx) 2007.03.06
			}
//			SYosoSagyogun &cYosoSagyogun = cSublineTo.aYosoSagyogun[aSagyojun[nSagyojun2 + 1].nYosoSagyogunId];
//			SLineKisei *pLineKisei2 = FindLineKisei(nSublineIdTo, cYosoSagyogun.sKoteiNo, cYosoSagyogun.sKoteiName, cYosoSagyogun.sYosoSagyogunName);
// By Y.Itabashi (xxxxx) 2007.02.07
			if (pLineKisei2 != NULL) {
				if (!pLineKisei2->sEngGenjo.IsEmpty()) {
					sEngGenjo2 = pLineKisei2->sEngGenjo;
				}

				// ENG姿勢のチェック
				if (sEngGenjo2 == g_pConst->m_sSeiritsuFr) {
					if (pLineKisei->bEngSeiritsuFr)
						bNG2 = TRUE;
				} else if (sEngGenjo2 == g_pConst->m_sSeiritsuRr) {
					if (pLineKisei->bEngSeiritsuRr)
						bNG2 = TRUE;
				} else if (sEngGenjo2 == g_pConst->m_sToritsuFr) {
					if (pLineKisei->bEngToritsuFr)
						bNG2 = TRUE;
				} else if (sEngGenjo2 == g_pConst->m_sToritsuRr) {
					if (pLineKisei->bEngToritsuRr)
						bNG2 = TRUE;
				} else if (sEngGenjo2 == g_pConst->m_sChokuritsuTop) {
					if (pLineKisei->bEngChokuritsuTop)
						bNG2 = TRUE;
				} else if (sEngGenjo2 == g_pConst->m_sChokuritsuBottom) {
					if (pLineKisei->bEngChokuritsuBottom)
						bNG2 = TRUE;
				}
			} else
				bNoCheck = TRUE;
		} else
			bNoCheck = TRUE;
	}

// Modify ... ( CHANGE )
	// 直前直後両方がNGならエラー
	if (bNG1 && bNG2){
		if( m_nStatus != STATUS_JIDO_HENSEI ){
			cLineError.sRiyuNo = "E013021";
			m_aLineError.Add( cLineError );
		}
		return FALSE;
// Modify ... ( CHANGE )	先頭と最後尾は片方の規制をチェックに変更
	}else if( nSagyojun1 == -1 && bNG2 ){
		if( m_nStatus != STATUS_JIDO_HENSEI ){
			cLineError.sRiyuNo = "E013021";
			m_aLineError.Add( cLineError );
		}
		return FALSE;
	}else if( ( nSagyojun2 == -1 ) && bNG1 ){
		if( m_nStatus != STATUS_JIDO_HENSEI ){
			cLineError.sRiyuNo = "E013021";
			m_aLineError.Add( cLineError );
		}
		return FALSE;
	}
// By Y.Itabashi (xxxxx) 2007.02.07
// By Y.Itabashi (xxxxx) 2007.03.06

// Modify ... ( ADD )
	if( m_nStatus != STATUS_JIDO_HENSEI && bNoCheck ){
		cLineError.sRiyuNo = "E013023";
		m_aLineError.Add( cLineError );
	}
// By Y.Itabashi (xxxxx) 2007.03.06

	return TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CDataManager::LineKiseiError
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		指定された工程の工順規制をチェック
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CString			&sKoteiName			[I] 工程名
//		CStringArray	&aKoteiOrder		[I] 工程順
//		CString			&sErrMsg			[O] エラーメッセージ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL		TRUE:正常，FALSE:工順規制エラー有り
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		指定された工程順の中の工程がENG姿勢または工順規制に反するかチェックする。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
BOOL CDataManager::LineKiseiError(EKojunCheckType eKojunCheckType)
{
	switch (eKojunCheckType) {
	case eKojunCheckTypeNoMessage:
		// メッセージ無し
		return FALSE;
	case eKojunCheckTypeKotei:
		// 工程移動
		if (g_pSystem->m_cUserInfo.m_nKojunKiseiIhan == 0) {
			g_pSystem->DispMessage("E013003");
			return FALSE;
		} else {
			if (g_pSystem->DispMessage("W013006") == IDCANCEL)
				return FALSE;
		}
		break;
	case eKojunCheckTypeYosoSagyogun:
		// 要素作業群移動
		if (g_pSystem->m_cUserInfo.m_nKojunKiseiIhan == 0) {
			g_pSystem->DispMessage("E013004");
			return FALSE;
		} else {
			if (g_pSystem->DispMessage("W013007") == IDCANCEL)
				return FALSE;
		}
		break;
	}

	return TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CDataManager::CheckKatashikiKisei
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		工順規制チェック
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CString			&sKoteiName			[I] 工程名
//		CStringArray	&aKoteiOrder		[I] 工程順
//		CString			&sErrMsg			[O] エラーメッセージ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL		TRUE:正常，FALSE:工順規制エラー有り
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		指定された工程順の中の工程が工順規制に反するかチェックする。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
// Modify ... ( CHANGE )
BOOL CDataManager::KatashikiKiseiCheck(int sKishuId, int nSublineIdFrom, int nSublineIdTo, int nYosoSagyogunId, CSagyojunArray &aSagyojun, CKojunErrInfoArray &aKojunErrInfo, BOOL bAllCheck, EKojunCheckType eKojunCheckType, BOOL &bNoCheck)
//BOOL CDataManager::KatashikiKiseiCheck(int sKishuId, int nSublineIdFrom, int nSublineIdTo, int nYosoSagyogunId, CSagyojunArray &aSagyojun, CKojunErrInfoArray &aKojunErrInfo, BOOL bAllCheck)
// By Y.Itabashi (xxxxx) 2007.02.21
{
	SSubline &cSubline = m_aSubline[nSublineIdTo];
	CString &sKishuName = m_aKishu[sKishuId].sKishuName;
	int i;
	SKatashikiKisei *pKatashikiKisei;
	SKojunErrInfo *pKojunErrInfo;

// Modify ... ( CHANGE )
	bNoCheck = FALSE;
// By Y.Itabashi (xxxxx) 2007.02.21

	aKojunErrInfo.RemoveAll();

	// 該当工程の位置を取得
	int nSagyojun = -1;
	int nSagyojunNum = aSagyojun.GetSize();
// Modify ... ( ADD )
	int nYosoSagyogunNum = 0;
	CString s_bKoteiName;
	s_bKoteiName.Empty();
	int nKoteiIndex = 0;
	int nKoteiSagyojun = 0;
	CString sKoteiName = cSubline.aYosoSagyogun[nYosoSagyogunId].sKoteiName;
	CString sYosoSagyogunName = cSubline.aYosoSagyogun[nYosoSagyogunId].sYosoSagyogunName;
// By Y.Itabashi (xxxxx) 2007.02.06

// Modify ... ( ADD )
	int nSagyojunKishu = 0;
// By Y.Itabashi (xxxxx) 2007.03.07

	for (i = 0; i < nSagyojunNum; i++) {

// Modify ... ( CHANGE )
		SYosoSagyogun &cYosoSagyogun = cSubline.aYosoSagyogun[aSagyojun[i].nYosoSagyogunId];
		if( s_bKoteiName.IsEmpty() ){
			s_bKoteiName = cYosoSagyogun.sKoteiName;
			nKoteiIndex = nSagyojunKishu;
//			nKoteiIndex = i;
		}else if( s_bKoteiName != cYosoSagyogun.sKoteiName ){
			s_bKoteiName = cYosoSagyogun.sKoteiName;
			nKoteiIndex = nSagyojunKishu;
//			nKoteiIndex = i;
		}

		if (aSagyojun[i].nYosoSagyogunId == nYosoSagyogunId) {
			nSagyojun = nSagyojunKishu;
//			nSagyojun = i;
// By Y.Itabashi (xxxxx) 2007.03.08

// Modify ... ( ADD )
			// 指定要素作業群の工程に存在する要素作業群をカウント
			for (i = nKoteiIndex; i < nSagyojunNum; i++) {
				if( i == nKoteiIndex )
					nKoteiSagyojun = i;
				SYosoSagyogun &cYosoSagyogun = cSubline.aYosoSagyogun[aSagyojun[i].nYosoSagyogunId];
				if (cYosoSagyogun.sKoteiName == sKoteiName && cYosoSagyogun.aKishuSagyo[sKishuId].bNewMove ) {
					nYosoSagyogunNum++;
				}else
					break;
			}
// By Y.Itabashi (xxxxx) 2007.02.06
			break;
		}

// Modify ... ( ADD )
		if( aSagyojun[i].nKishuId == sKishuId )
			nSagyojunKishu++;
// By Y.Itabashi (xxxxx) 2007.03.07
	}

// Modify ... ( ADD )
	// 移動元と移動先が本流かの判定
	BOOL bCheckKiseiFrom = FALSE;
	BOOL bCheckKiseiTo = FALSE;
	for( int j = 0; j < m_aKishu[sKishuId].aHonryuSublineId.GetSize(); j++ ){
		int nHonryuSubline = m_aKishu[sKishuId].aHonryuSublineId[j];
		if( nHonryuSubline == nSublineIdFrom ){
			bCheckKiseiFrom = TRUE;
		}else if( nHonryuSubline == nSublineIdTo ){
			bCheckKiseiTo = TRUE;
		}
	}

	if (nSagyojun != -1 &&
		( ( bCheckKiseiFrom && bCheckKiseiTo ) || nSublineIdFrom == nSublineIdTo ) ) {
//	if (nSagyojun != -1) {

		SYosoSagyogun &cYosoSagyogun1 = cSubline.aYosoSagyogun[aSagyojun[nSagyojun].nYosoSagyogunId];

		int nStartSubline;
		int nEndSubline;
		if( nSublineIdFrom < nSublineIdTo ){
			nStartSubline = nSublineIdFrom;
			nEndSubline = nSublineIdTo;
		}else{
			nStartSubline = nSublineIdTo;
			nEndSubline = nSublineIdFrom;
		}
		for( int nSubline = nStartSubline; nSubline <= nEndSubline; nSubline++ ){

			SSubline &cCheckSubline = m_aSubline[nSubline];

			BOOL bCheckKisei = FALSE;
			for( int j = 0; j < m_aKishu[sKishuId].aHonryuSublineId.GetSize(); j++ ){
				int nHonryuSubline = m_aKishu[sKishuId].aHonryuSublineId[j];
				if( nHonryuSubline == nSubline ){
					bCheckKisei = TRUE;
				}
			}
// Modify ... ( CHANGE )
			if( !bCheckKisei && nSublineIdFrom != nSublineIdTo )
//			if( !bCheckKisei )
				continue;
// By Y.Itabashi (xxxxx) 2007.02.07

			// 各サブラインのスタートとエンドの作業順番をチェックする
			int nStartSagyojun = 0;
			int nMaxSagyojun = nSagyojunNum;
			int nCheckSagyojun = nSagyojun;
			int nBeforeSagyojun = GetMoveBeforeSagyojun( nSublineIdFrom, sKoteiName, sYosoSagyogunName, sKishuId );
// Modify ... ( CHANGE )
			CSagyojunArray aSagyojunKishu;
			for( int i = 0; i < aSagyojun.GetSize(); i++ ){
				if( aSagyojun[i].nKishuId == sKishuId )
					aSagyojunKishu.Add( aSagyojun[i] );
			}
//			CSagyojunArray aSagyojun;
//			GetSagyojun(nSubline, sKishuId, aSagyojun);
// By Y.Itabashi (xxxxx) 2007.03.07
			if( nSublineIdFrom == nSublineIdTo && nBeforeSagyojun != -1 ){
				if( nBeforeSagyojun < nSagyojun ){
					nStartSagyojun = nBeforeSagyojun;
					if( eKojunCheckType == eKojunCheckTypeKotei )
						nMaxSagyojun = nKoteiSagyojun;
					else
						nMaxSagyojun = nSagyojun;
				}else{
					if( eKojunCheckType == eKojunCheckTypeKotei ){
						nStartSagyojun = nKoteiSagyojun + nYosoSagyogunNum;
						nMaxSagyojun = nBeforeSagyojun + nYosoSagyogunNum;
					}else{
						nStartSagyojun = nSagyojun + 1;
						nMaxSagyojun = nBeforeSagyojun + 1;
					}
				}
			}else if( nSubline == nSublineIdFrom && nBeforeSagyojun != -1 ){
				if( nSublineIdFrom < nSublineIdTo ){
					nCheckSagyojun = aSagyojunKishu.GetSize();
					nStartSagyojun = nBeforeSagyojun;
					nMaxSagyojun = aSagyojunKishu.GetSize();
				}else{
					nCheckSagyojun = 0;
					nStartSagyojun = 0;
// Modify ... ( CHANGE )
					nMaxSagyojun = nBeforeSagyojun;
/*					if( eKojunCheckType == eKojunCheckTypeKotei )
						nMaxSagyojun = nBeforeSagyojun + nYosoSagyogunNum;
					else
						nMaxSagyojun = nBeforeSagyojun + 1;*/
// By Y.Itabashi (xxxxx) 2007.02.28
				}
			}else if( nSubline == nSublineIdTo && nBeforeSagyojun != -1 ){
				if( nSublineIdFrom < nSublineIdTo ){
					nStartSagyojun = 0;
					if( eKojunCheckType == eKojunCheckTypeKotei )
						nMaxSagyojun = nKoteiSagyojun;
					else
						nMaxSagyojun = nSagyojun;
				}else{
					nMaxSagyojun = aSagyojunKishu.GetSize();
					if( eKojunCheckType == eKojunCheckTypeKotei )
						nStartSagyojun = nKoteiSagyojun + nYosoSagyogunNum;
					else
						nStartSagyojun = nSagyojun + 1;
				}
			}else{
				nStartSagyojun = 0;
				nMaxSagyojun = aSagyojunKishu.GetSize();
				if( nSublineIdFrom < nSublineIdTo )
					nCheckSagyojun = aSagyojunKishu.GetSize();
				else
					nCheckSagyojun = 0;
			}
		
		for (i = nStartSagyojun; i < nMaxSagyojun; i++) {
//		for (i = 0; i < nSagyojunNum; i++) {
			SYosoSagyogun &cYosoSagyogun2 = cCheckSubline.aYosoSagyogun[aSagyojunKishu[i].nYosoSagyogunId];
			pKatashikiKisei = FindKatashikiKisei(sKishuName, nSublineIdFrom, cYosoSagyogun1.sKoteiNo, cYosoSagyogun1.sKoteiName, cYosoSagyogun1.sYosoSagyogunName);
			if (pKatashikiKisei != NULL) {
				SKatashikiKiseiCol *pKatashikiKiseiCol = FindKatashikiKiseiCol(pKatashikiKisei, sKishuName, nSubline, cYosoSagyogun2.sKoteiNo, cYosoSagyogun2.sKoteiName, cYosoSagyogun2.sYosoSagyogunName);
//				SKatashikiKiseiCol *pKatashikiKiseiCol = FindKatashikiKiseiCol(pKatashikiKisei, sKishuName, nSublineIdTo, cYosoSagyogun2.sKoteiNo, cYosoSagyogun2.sKoteiName, cYosoSagyogun2.sYosoSagyogunName);
				if (pKatashikiKiseiCol != NULL) {
					if (i < nCheckSagyojun) {
//					if (i < nSagyojun) {
						if (pKatashikiKiseiCol->sKisei == g_pConst->m_sKaryu) {
							if (!bAllCheck)
								return FALSE;
// Modify ... ( CHANGE )
							if( m_nStatus != STATUS_JIDO_HENSEI ){
								SKatashikiError cKatashikiError;
								cKatashikiError.nKishuID = sKishuId;
								cKatashikiError.sSublineFrom = m_aSubline[nSublineIdFrom].sSublineName;
								cKatashikiError.sKoteiNoFrom = cYosoSagyogun1.sKoteiNo;
								cKatashikiError.sKoteiNameFrom = cYosoSagyogun1.sKoteiName;
								cKatashikiError.sYosoSagyogunNameFrom = cYosoSagyogun1.sYosoSagyogunName;
								cKatashikiError.sSublineTo = m_aSubline[nSublineIdTo].sSublineName;
								cKatashikiError.sKoteiNoTo = cSubline.aYosoSagyogun[nYosoSagyogunId].sKoteiNo;
								cKatashikiError.sKoteiNameTo = cSubline.aYosoSagyogun[nYosoSagyogunId].sKoteiName;
								cKatashikiError.sYosoSagyogunNameTo = cSubline.aYosoSagyogun[nYosoSagyogunId].sYosoSagyogunName;
								cKatashikiError.sSublineNg = cCheckSubline.sSublineName;
								cKatashikiError.sKoteiNoNg = cYosoSagyogun2.sKoteiNo;
								cKatashikiError.sKoteiNameNg = cYosoSagyogun2.sKoteiName;
								cKatashikiError.sYosoSagyogunNameNg = cYosoSagyogun2.sYosoSagyogunName;
								cKatashikiError.sRiyuNo = "E013019";
								m_aKatashikiError.Add( cKatashikiError );

								pKojunErrInfo = aKojunErrInfo.AddElement();
								pKojunErrInfo->nErrCode = eKojunErrKatashikiKaryuNG;
								pKojunErrInfo->sTargetKoteiName = cYosoSagyogun1.sKoteiName;
								pKojunErrInfo->sTargetYosoSagyogunName = cYosoSagyogun1.sYosoSagyogunName;
								pKojunErrInfo->sErrorKoteiName = cYosoSagyogun2.sKoteiName;
								pKojunErrInfo->sErrorYosoSagyogunName = cYosoSagyogun2.sYosoSagyogunName;
							}
// By Y.Itabashi (xxxxx) 2007.03.06
						}
					} else if (i > nCheckSagyojun) {
//					} else if (i > nSagyojun) {
						if (pKatashikiKiseiCol->sKisei == g_pConst->m_sJoryu) {
							if (!bAllCheck)
								return FALSE;
// Modify ... ( CHANGE )
							if( m_nStatus != STATUS_JIDO_HENSEI ){
								SKatashikiError cKatashikiError;
								cKatashikiError.nKishuID = sKishuId;
								cKatashikiError.sSublineFrom = m_aSubline[nSublineIdFrom].sSublineName;
								cKatashikiError.sKoteiNoFrom = cYosoSagyogun1.sKoteiNo;
								cKatashikiError.sKoteiNameFrom = cYosoSagyogun1.sKoteiName;
								cKatashikiError.sYosoSagyogunNameFrom = cYosoSagyogun1.sYosoSagyogunName;
								cKatashikiError.sSublineTo = m_aSubline[nSublineIdTo].sSublineName;
								cKatashikiError.sKoteiNoTo = cSubline.aYosoSagyogun[nYosoSagyogunId].sKoteiNo;
								cKatashikiError.sKoteiNameTo = cSubline.aYosoSagyogun[nYosoSagyogunId].sKoteiName;
								cKatashikiError.sYosoSagyogunNameTo = cSubline.aYosoSagyogun[nYosoSagyogunId].sYosoSagyogunName;
								cKatashikiError.sSublineNg = cCheckSubline.sSublineName;
								cKatashikiError.sKoteiNoNg = cYosoSagyogun2.sKoteiNo;
								cKatashikiError.sKoteiNameNg = cYosoSagyogun2.sKoteiName;
								cKatashikiError.sYosoSagyogunNameNg = cYosoSagyogun2.sYosoSagyogunName;
								cKatashikiError.sRiyuNo = "E013020";
								m_aKatashikiError.Add( cKatashikiError );

								pKojunErrInfo = aKojunErrInfo.AddElement();
								pKojunErrInfo->nErrCode = eKojunErrKatashikiJoryuNG;
								pKojunErrInfo->sTargetKoteiName = cYosoSagyogun1.sKoteiName;
								pKojunErrInfo->sTargetYosoSagyogunName = cYosoSagyogun1.sYosoSagyogunName;
								pKojunErrInfo->sErrorKoteiName = cYosoSagyogun2.sKoteiName;
								pKojunErrInfo->sErrorYosoSagyogunName = cYosoSagyogun2.sYosoSagyogunName;
							}
// By Y.Itabashi (xxxxx) 2007.03.06
						}
					}
				}
// Modify ... ( ADD )
			}else{
				if( m_nStatus != STATUS_JIDO_HENSEI ){
					SKatashikiError cKatashikiError;
					cKatashikiError.nKishuID = sKishuId;
					cKatashikiError.sSublineFrom = m_aSubline[nSublineIdFrom].sSublineName;
					cKatashikiError.sKoteiNoFrom = cYosoSagyogun1.sKoteiNo;
					cKatashikiError.sKoteiNameFrom = cYosoSagyogun1.sKoteiName;
					cKatashikiError.sYosoSagyogunNameFrom = cYosoSagyogun1.sYosoSagyogunName;
					cKatashikiError.sRiyuNo = "E013023";
					m_aKatashikiError.Add( cKatashikiError );
					bNoCheck = TRUE;
				}
// By Y.Itabashi (xxxxx) 2007.03.06
			}
		}
		}
// By Y.Itabashi (xxxxx) 2007.02.06
	}

	return aKojunErrInfo.GetSize() == 0;
}

BOOL CDataManager::KatashikiKiseiError(EKojunCheckType eKojunCheckType, CKojunErrInfoArray &aKojunErrInfo)
{
	CStringArrayEx aKoteiNameJoryu, aKoteiNameKaryu;

	int nSize = aKojunErrInfo.GetSize();
	for (int i = 0; i < nSize; i++) {
		SKojunErrInfo &cKojunErrInfo = aKojunErrInfo[i];

		// エラー処理
		switch (eKojunCheckType) {
		case eKojunCheckTypeNoMessage:
			// メッセージ無し
			return FALSE;
		case eKojunCheckTypeKotei:
			// 工程移動
			switch (cKojunErrInfo.nErrCode) {
			case eKojunErrKatashikiJoryuNG:
				if (aKoteiNameJoryu.Find(cKojunErrInfo.sErrorKoteiName) == -1) {
					// 上流不可
					if (g_pSystem->m_cUserInfo.m_nKojunKiseiIhan == 0) {
						g_pSystem->DispMessage("E013010", cKojunErrInfo.sTargetKoteiName, cKojunErrInfo.sErrorKoteiName);
						return FALSE;
					} else {
						if (g_pSystem->DispMessage("W013014", cKojunErrInfo.sTargetKoteiName, cKojunErrInfo.sErrorKoteiName) == IDCANCEL)
							return FALSE;
					}
					aKoteiNameJoryu.Add(cKojunErrInfo.sErrorKoteiName);
				}
				break;
			case eKojunErrKatashikiKaryuNG:
				if (aKoteiNameKaryu.Find(cKojunErrInfo.sErrorKoteiName) == -1) {
					// 下流不可
					if (g_pSystem->m_cUserInfo.m_nKojunKiseiIhan == 0) {
						g_pSystem->DispMessage("E013012", cKojunErrInfo.sTargetKoteiName, cKojunErrInfo.sErrorKoteiName);
						return FALSE;
					} else {
						if (g_pSystem->DispMessage("W013016", cKojunErrInfo.sTargetKoteiName, cKojunErrInfo.sErrorKoteiName) == IDCANCEL)
							return FALSE;
					}
					aKoteiNameKaryu.Add(cKojunErrInfo.sErrorKoteiName);
				}
				break;
			}
			break;
		case eKojunCheckTypeYosoSagyogun:
			// 要素作業群移動
			switch (cKojunErrInfo.nErrCode) {
			case eKojunErrKatashikiJoryuNG:
				// 上流不可
				if (g_pSystem->m_cUserInfo.m_nKojunKiseiIhan == 0) {
					g_pSystem->DispMessage("E013011", cKojunErrInfo.sTargetYosoSagyogunName, cKojunErrInfo.sErrorYosoSagyogunName);
					return FALSE;
				} else {
					if (g_pSystem->DispMessage("W013015", cKojunErrInfo.sTargetYosoSagyogunName, cKojunErrInfo.sErrorYosoSagyogunName) == IDCANCEL)
						return FALSE;
				}
				break;
			case eKojunErrKatashikiKaryuNG:
				// 下流不可
				if (g_pSystem->m_cUserInfo.m_nKojunKiseiIhan == 0) {
					g_pSystem->DispMessage("E013013", cKojunErrInfo.sTargetYosoSagyogunName, cKojunErrInfo.sErrorYosoSagyogunName);
					return FALSE;
				} else {
					if (g_pSystem->DispMessage("W013017", cKojunErrInfo.sTargetYosoSagyogunName, cKojunErrInfo.sErrorYosoSagyogunName) == IDCANCEL)
						return FALSE;
				}
				break;
			}
			break;
		}
	}

	return TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CDataManager::FindLineKisei
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		工順規制検索
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CString			&sKoteiName			[I] 工程名
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		SKojunKisei*	工順規制データポインタ
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		指定された工程名を工順規制データから検索し、見つかったらそのポインタを返す。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
SLineKisei *CDataManager::FindLineKisei(int nSublineId, const CString &sKoteiNo, const CString &sKoteiName, const CString &sYosoSagyogunName)
{
	return m_aLineKisei.LookupElement(m_aSubline[nSublineId].sSublineName + sKoteiNo + sKoteiName + RemoveUnderbarDigit(sYosoSagyogunName));
}

SKatashikiKisei *CDataManager::FindKatashikiKisei(const CString &sKishuName, int nSublineId, const CString &sKoteiNo, const CString &sKoteiName, const CString &sYosoSagyogunName)
{
// Modify ... ( CHANGE )
	CString sKey;
	for( int i = 0; i < m_aKishu.GetSize(); i++ ){
		if( m_aKishu[i].sKishuName == sKishuName ){

			// 本流でない場合、そのシート内で検索する
			if( m_aKishu[i].aHonryuSublineId.Find(nSublineId) == -1 ){
				sKey = sKishuName + m_aSubline[nSublineId].sSublineName + sKoteiName + RemoveUnderbarDigit(sYosoSagyogunName);
				break;
			}

			// 本流の場合、現在のサブラインから検索
			sKey = sKishuName + m_aSubline[nSublineId].sSublineName + sKoteiName + RemoveUnderbarDigit(sYosoSagyogunName);
			SKatashikiKisei *pKisei = m_aKatashikiKisei.LookupElement( sKey );
			if( pKisei )				return pKisei;

			// 現在のサブラインから検出できなかった場合
			for( int j = 0; j < m_aKishu[i].aHonryuSublineId.GetSize(); j++ ){

				if( m_aKishu[i].aHonryuSublineId[j] == nSublineId )
					continue;

				sKey = sKishuName + m_aSubline[j].sSublineName + sKoteiName + RemoveUnderbarDigit(sYosoSagyogunName);
				pKisei = m_aKatashikiKisei.LookupElement( sKey );
				if( pKisei )				return pKisei;

			}
		}
	}

	// 規制の取得の際、工程番号は無視する
	return m_aKatashikiKisei.LookupElement( sKey );
//	return m_aKatashikiKisei.LookupElement(sKishuName + m_aSubline[nSublineId].sSublineName + sKoteiNo + sKoteiName + RemoveUnderbarDigit(sYosoSagyogunName));
// By Y.Itabashi (xxxxx) 2007.02.10
}

//*****************************************************************************************************
//  1. 関数名
//		CDataManager::FindKojunKiseiCol
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		工順規制マトリクス取得
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		SKojunKisei		*pKojunKisei		[I] 工順規制データポインタ
//		CString			&sKoteiName			[I] 工程名
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		SKojunKisei*	工順規制データポインタ
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		指定された工程名を工順規制マトリクスから検索し、見つかったらそのポインタを返す。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
SKatashikiKiseiCol *CDataManager::FindKatashikiKiseiCol(SKatashikiKisei *pKatashikiKisei, const CString &sKishuName, int nSublineId, const CString &sKoteiNo, const CString &sKoteiName, const CString &sYosoSagyogunName)
{
// Modify ... ( CHANGE )
	CString sKey;
	for( int i = 0; i < m_aKishu.GetSize(); i++ ){
		if( m_aKishu[i].sKishuName == sKishuName ){

			// 本流でない場合、そのシート内で検索する
			if( m_aKishu[i].aHonryuSublineId.Find(nSublineId) == -1 ){
				sKey = sKishuName + m_aSubline[nSublineId].sSublineName + sKoteiName + RemoveUnderbarDigit(sYosoSagyogunName);
				break;
			}

			// 本流の場合、現在のサブラインから検索
			sKey = sKishuName + m_aSubline[nSublineId].sSublineName + sKoteiName + RemoveUnderbarDigit(sYosoSagyogunName);
			SKatashikiKiseiCol *pCol = pKatashikiKisei->aKatashikiKiseiCol.LookupElement( sKey );
			if( pCol )				return pCol;

			// 現在のサブラインから検出できなかった場合
			for( int j = 0; j < m_aKishu[i].aHonryuSublineId.GetSize(); j++ ){

				if( m_aKishu[i].aHonryuSublineId[j] == nSublineId )
					continue;

				sKey = sKishuName + m_aSubline[j].sSublineName + sKoteiName + RemoveUnderbarDigit(sYosoSagyogunName);
				pCol = pKatashikiKisei->aKatashikiKiseiCol.LookupElement( sKey );
				if( pCol )				return pCol;

			}
		}
	}

	// 規制の取得の際、工程番号は無視する
	return pKatashikiKisei->aKatashikiKiseiCol.LookupElement( sKey );
//	return pKatashikiKisei->aKatashikiKiseiCol.LookupElement(sKishuName + m_aSubline[nSublineId].sSublineName + sKoteiNo + sKoteiName + RemoveUnderbarDigit(sYosoSagyogunName));
// By Y.Itabashi (xxxxx) 2007.02.10
}

// ライン規制マトリクスファイルを開く
void CDataManager::OpenLineKiseiMatrix()
{
// --Add 2007/03/02 aizawa 不懸D-147
	// ライン規制マトリクスファイルを開く前に保存
	SaveLineKiseiMatrix(NULL, "E003002");
// --Add

	SExcelOpenInfo *pExcelOpenInfo = new SExcelOpenInfo;
	pExcelOpenInfo->nParam = EXCEL_LINE_KISEI_MATRIX;
	pExcelOpenInfo->aPathName.Add(m_eLineKiseiMatrix.m_sPathName);

	AfxBeginThread(OpenExcelThread, pExcelOpenInfo);
}

// 型式規制マトリクスファイルを開く
void CDataManager::OpenKatashikiKiseiMatrix()
{
	SExcelOpenInfo *pExcelOpenInfo = new SExcelOpenInfo;
	pExcelOpenInfo->nParam = EXCEL_KATASHIKI_KISEI_MATRIX;

	// 型式規制マトリクスファイルは複数あるので、選択されたファイルを取得する
	int nKatashikiNum = m_aKatashiki.GetSize();
	for (int nKatashikiId = 0; nKatashikiId < nKatashikiNum; nKatashikiId++) {
		SKatashiki &cKatashiki = m_aKatashiki[nKatashikiId];
		if (cKatashiki.bEditFlag && !cKatashiki.eKatashikiKiseiMatrix.m_sPathName.IsEmpty())
			pExcelOpenInfo->aPathName.Add(cKatashiki.eKatashikiKiseiMatrix.m_sPathName);
	}

	if (pExcelOpenInfo->aPathName.GetSize() > 0)
		AfxBeginThread(OpenExcelThread, pExcelOpenInfo);
	else
		delete pExcelOpenInfo;
}

// 別スレッドでエクセルを起動する
UINT CDataManager::OpenExcelThread(LPVOID pParam)
{
	::OleInitialize(NULL);

	SExcelOpenInfo *pExcelOpenInfo = (SExcelOpenInfo *)pParam;

	// メインスレッドにオープン通知
	g_pSystem->m_pMainDlg->SendMessage(WM_OPEN_EXCEL, pExcelOpenInfo->nParam);

	// エクセルを開く
	CExcelLib cExcelLib;
	if (cExcelLib.Exec(pExcelOpenInfo->aPathName)) {
		// メインスレッドにクローズ通知
		g_pSystem->m_pMainDlg->PostMessage(WM_CLOSE_EXCEL, pExcelOpenInfo->nParam);
	} else {
		// メインスレッドにエラー通知
		g_pSystem->m_pMainDlg->PostMessage(WM_ERROR_EXCEL, pExcelOpenInfo->nParam);
	}

	delete pExcelOpenInfo;

	::OleUninitialize();

	return 0;
}

// エクセルオープン通知処理
void CDataManager::OpenExcelNotify(UINT nParam)
{
	switch (nParam) {
	case EXCEL_LINE_KISEI_MATRIX:
		// ライン規制マトリクスファイルオープン中フラグセット
		m_bOpenLineKiseiMatrix = TRUE;

		// ファイルロック解除
		m_eLineKiseiMatrix.FileUnlock();
		break;
	case EXCEL_KATASHIKI_KISEI_MATRIX:
		// 型式規制マトリクスファイルオープン中フラグセット
		m_bOpenKatashikiKiseiMatrix = TRUE;

		// ファイルロック解除
		for (int i = 0; i < m_aKatashiki.GetSize(); i++)
			m_aKatashiki[i].eKatashikiKiseiMatrix.FileUnlock();
		break;
	}
}

// エクセルクローズ通知処理
void CDataManager::CloseExcelNotify(UINT nParam)
{
	CWaitCursor cWaitCursor;

	switch (nParam) {
	case EXCEL_LINE_KISEI_MATRIX:
		// ライン規制マトリクスファイルオープン中フラグクリア
		m_bOpenLineKiseiMatrix = FALSE;

		// ファイルロック
		m_eLineKiseiMatrix.FileLock();

		// ライン規制マトリクスファイル読み込み
		ReadLineKiseiMatrix();
		break;
	case EXCEL_KATASHIKI_KISEI_MATRIX:
		// 型式規制マトリクスファイルオープン中フラグクリア
		m_bOpenKatashikiKiseiMatrix = FALSE;

		// ファイルロック
		for (int i = 0; i < m_aKatashiki.GetSize(); i++)
			m_aKatashiki[i].eKatashikiKiseiMatrix.FileLock();

		// 型式規制マトリクスファイル読み込み
		ReadKatashikiKiseiMatrix();
		break;
	}
}

// エクセルクローズ通知処理
void CDataManager::ErrorExcelNotify(UINT nParam)
{
	CWaitCursor cWaitCursor;

	switch (nParam) {
	case EXCEL_LINE_KISEI_MATRIX:
		// ライン規制マトリクスファイルオープン中フラグクリア
		m_bOpenLineKiseiMatrix = FALSE;

		// ファイルロック
		m_eLineKiseiMatrix.FileLock();

		// エラーメッセージ
		g_pSystem->DispMessage("E006001");
		break;
	case EXCEL_KATASHIKI_KISEI_MATRIX:
		// 型式規制マトリクスファイルオープン中フラグクリア
		m_bOpenKatashikiKiseiMatrix = FALSE;

		// ファイルロック
		for (int i = 0; i < m_aKatashiki.GetSize(); i++)
			m_aKatashiki[i].eKatashikiKiseiMatrix.FileLock();

		// エラーメッセージ
		g_pSystem->DispMessage("E006101");
		break;
	}
}

// 工程/要素作業群の移動実行前の位置を設定
void CDataManager::SetMoveBeforeSagyojun(int nSublineId, int nSublineToId, CString sKoteiName, CString sYosoSagyogunName,
										 CString sCheckKoteiName, CString sCheckYosoSagyogunName, int nCheckYosoSagyogunId,
										 CIntArrayEx &aYosoSagyogunId, int nKishuId, EKojunCheckType eKojunCheckType)
//void CDataManager::SetMoveBeforeSagyojun(int nSublineId, CString sKoteiName, CString sYosoSagyogunName, CIntArrayEx &aYosoSagyogunId, int nKishuId, EKojunCheckType eKojunCheckType)
{
	SSubline &cSubline = m_aSubline[nSublineId];
	SYosoSagyogun &cMoveYosoSagyogun = cSubline.aYosoSagyogun[aYosoSagyogunId[0]];

	CSagyojunArray aSagyojun;
	GetSagyojun(nSublineId, nKishuId, aSagyojun);

	int nSagyojun = -1;
	int nSagyojunNum = aSagyojun.GetSize();

// Modify ... ( ADD )
	if( nSublineId == nSublineToId ){
// By Y.Itabashi (xxxxx) 2007.02.28

	for ( int i = 0; i < nSagyojunNum; i++) {
		SYosoSagyogun &cYosoSagyogun = cSubline.aYosoSagyogun[aSagyojun[i].nYosoSagyogunId];
		if (cYosoSagyogun.sKoteiName == cMoveYosoSagyogun.sKoteiName &&
			cYosoSagyogun.sYosoSagyogunName == cMoveYosoSagyogun.sYosoSagyogunName) {
			nSagyojun = i;

			CString sKey = cSubline.sSublineName+m_aKishu[nKishuId].sKishuName+cMoveYosoSagyogun.sKoteiName+cMoveYosoSagyogun.sYosoSagyogunName;
			int *nBeforeSagyojun = aBeforeMoveSagyojun.AddElement( sKey );
			*nBeforeSagyojun = nSagyojun;

			if( eKojunCheckType == eKojunCheckTypeKotei ){
				for( i = i+1; i < nSagyojunNum; i++ ){
					SYosoSagyogun &cYosoSagyogun = cSubline.aYosoSagyogun[aSagyojun[i].nYosoSagyogunId];
					if (cYosoSagyogun.sKoteiName == cMoveYosoSagyogun.sKoteiName ) {

						CString sChildKey = cSubline.sSublineName+m_aKishu[nKishuId].sKishuName+cYosoSagyogun.sKoteiName+cYosoSagyogun.sYosoSagyogunName;
						int *nBeforeSagyojun = aBeforeMoveSagyojun.AddElement( sChildKey );
						*nBeforeSagyojun = nSagyojun;
					}else
						break;
				}
			}
			break;
		}
	}

// Modify ... ( ADD )
	}else{
		for ( int i = 0; i < nSagyojunNum; i++) {
			SYosoSagyogun &cYosoSagyogun = cSubline.aYosoSagyogun[aSagyojun[i].nYosoSagyogunId];
			if (aSagyojun[i].nYosoSagyogunId == nCheckYosoSagyogunId) {
				nSagyojun = i;
				break;
			}
		}
		if( nSublineId > nSublineToId )
			nSagyojun = nSagyojun - 1;

		for ( i = 0; i < nSagyojunNum; i++) {
			SYosoSagyogun &cYosoSagyogun = cSubline.aYosoSagyogun[aSagyojun[i].nYosoSagyogunId];
			if (cYosoSagyogun.sKoteiName == cMoveYosoSagyogun.sKoteiName &&
				cYosoSagyogun.sYosoSagyogunName == cMoveYosoSagyogun.sYosoSagyogunName) {

				CString sKey = cSubline.sSublineName+m_aKishu[nKishuId].sKishuName+cMoveYosoSagyogun.sKoteiName+cMoveYosoSagyogun.sYosoSagyogunName;
				int *nBeforeSagyojun = aBeforeMoveSagyojun.AddElement( sKey );
				*nBeforeSagyojun = nSagyojun;

				if( eKojunCheckType == eKojunCheckTypeKotei ){
					for( i = i+1; i < nSagyojunNum; i++ ){
						SYosoSagyogun &cYosoSagyogun = cSubline.aYosoSagyogun[aSagyojun[i].nYosoSagyogunId];
						if (cYosoSagyogun.sKoteiName == cMoveYosoSagyogun.sKoteiName ) {

							CString sChildKey = cSubline.sSublineName+m_aKishu[nKishuId].sKishuName+cYosoSagyogun.sKoteiName+cYosoSagyogun.sYosoSagyogunName;
							int *nBeforeSagyojun = aBeforeMoveSagyojun.AddElement( sChildKey );
							*nBeforeSagyojun = nSagyojun;
						}else
							break;
					}
				}
				break;
			}
		}
	}
// By Y.Itabashi (xxxxx) 2007.02.28
}

// Modify ... ( ADD )
void CDataManager::SetMoveBeforeSagyojun(int nSublineId, CString sKoteiName, CString sYosoSagyogunName,
										 int nKishuId, int nSetSagyojun, EKojunCheckType eKojunCheckType)
{
	SSubline &cSubline = m_aSubline[nSublineId];

	CString sKey = cSubline.sSublineName+m_aKishu[nKishuId].sKishuName+sKoteiName+sYosoSagyogunName;
	int *nBeforeSagyojun = aBeforeMoveSagyojun.AddElement( sKey );
	*nBeforeSagyojun = nSetSagyojun;
}
// By Y.Itabashi (xxxxx) 2007.03.07

// 工程/要素作業群の移動実行前の位置を取得
int CDataManager::GetMoveBeforeSagyojun(int nSublineId, CString sKoteiName, CString sYosoSagyogunName, int nKishuId)
{
	SSubline &cSubline = m_aSubline[nSublineId];

	CString sKey = cSubline.sSublineName+m_aKishu[nKishuId].sKishuName+sKoteiName+sYosoSagyogunName;
	int *nBeforeSagyojun = aBeforeMoveSagyojun.LookupElement( sKey );

	if( nBeforeSagyojun == NULL )
		return -1;

	return *nBeforeSagyojun;
}

// 工程/要素作業群の移動実行前の位置を初期化
void CDataManager::InitMoveBeforeSagyojun()
{
	aBeforeMoveSagyojun.RemoveAll();
}

// --Add 2007/03/02 aizawa 不懸D-147
// サブライン間移動によりライン規制マトリクスのサブラインを更新
void CDataManager::UpdateLineKiseiSubline(int nSublineIdFrom, int nSublineIdTo)
{
	SSubline &cSublineTo = m_aSubline[nSublineIdTo];

	int nYosoSagyogunNum = cSublineTo.aYosoSagyogun.GetSize();
	for (int nYosoSagyogunId = 0; nYosoSagyogunId < nYosoSagyogunNum; nYosoSagyogunId++) {
		SYosoSagyogun &cYosoSagyogun = cSublineTo.aYosoSagyogun[nYosoSagyogunId];

		int nKishuSagyoNum = cYosoSagyogun.aKishuSagyo.GetSize();
		for (int i = 0; i < nKishuSagyoNum; i++) {
			SKishuSagyo &cKishuSagyo = cYosoSagyogun.aKishuSagyo[i];
			if (cKishuSagyo.bNewMove) {
				ChangeLineKiseiSubline(nSublineIdFrom, nSublineIdTo, cYosoSagyogun.sKoteiNo, cYosoSagyogun.sKoteiName, cYosoSagyogun.sYosoSagyogunName);
				break;
			}
		}
	}
}

// ライン規制マトリクスのサブラインを変更
void CDataManager::ChangeLineKiseiSubline(int nSublineIdFrom, int nSublineIdTo, const CString &sKoteiNo, const CString &sKoteiName, const CString &sYosoSagyogunName)
{
	// ライン規制マトリクスを検索
	SLineKisei *pLineKisei = FindLineKisei(nSublineIdFrom, sKoteiNo, sKoteiName, sYosoSagyogunName);
	if (pLineKisei != NULL) {
		// 見つかったらサブライン名を書き換える
		pLineKisei->sSublineName = m_aSubline[nSublineIdTo].sSublineName;

		// 検索キーを変更する
		CString sKeyOld = m_aSubline[nSublineIdFrom].sSublineName + sKoteiNo + sKoteiName + RemoveUnderbarDigit(sYosoSagyogunName);
		CString sKeyNew = m_aSubline[nSublineIdTo].sSublineName + sKoteiNo + sKoteiName + RemoveUnderbarDigit(sYosoSagyogunName);
		m_aLineKisei.ChangeKey(sKeyOld, sKeyNew);
	}
}
// --Add
