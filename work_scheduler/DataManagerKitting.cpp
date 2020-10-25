//*****************************************************************************************************
//  1. ファイル名
//		DataManagerKitting.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		CDataManager クラスのインプリメンテーション（キット化関連）
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
//		CDataManager::GetKittingData
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		キット化情報取得
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		リンク情報からキット化に必要な情報を取得する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CDataManager::GetKittingData()
{
	int nSublineNum = m_aSubline.GetSize();
	for (int nSublineId = 0; nSublineId < nSublineNum; nSublineId++)
		GetKittingDataSub(nSublineId);
}

void CDataManager::GetKittingDataSub(int nSublineId)
{
	SSubline &cSubline = m_aSubline[nSublineId];
	int i, j, k;
	int nIndex;
	int nKishuId;
	int nKishuNum = m_aKishu.GetSize();

	cSubline.aKittingYosoSagyogun.RemoveAll();
	cSubline.aKittingBuhin.RemoveAll();

	// 削除フラグに不要作業があるかチェック
	BOOL bSetFuyoSagyo = FALSE;
	int nYosoSagyogunNum = cSubline.aYosoSagyogun.GetSize();
	for (int nYosoSagyogunId = 0; nYosoSagyogunId < nYosoSagyogunNum; nYosoSagyogunId++) {
		SYosoSagyogun &cYosoSagyogun = cSubline.aYosoSagyogun[nYosoSagyogunId];
		int nYosoSagyoNum = cYosoSagyogun.aYosoSagyo.GetSize();
		for (int nYosoSagyoId = 0; nYosoSagyoId < nYosoSagyoNum; nYosoSagyoId++) {
			if (cYosoSagyogun.aYosoSagyo[nYosoSagyoId].bKittingDelete) {
				bSetFuyoSagyo = TRUE;
				break;
			}
		}
		if (bSetFuyoSagyo)
			break;
	}

	// 部品表＆工程リンクデータによるループ
	int nBuhinhyoLink = m_aBuhinhyoLink.GetSize();
	for (i = 0; i < nBuhinhyoLink; i++) {
		SBuhinhyoLink &cBuhinhyoLink = m_aBuhinhyoLink[i];

		if (cBuhinhyoLink.sSublineName.CompareNoCase(cSubline.sSublineName) == 0) {
			if (CheckKittingBuhinNo(cBuhinhyoLink)) {
				// 要素作業群によるループ
				int nYosoSagyogunNum = cBuhinhyoLink.aYosoSagyogunName.GetSize();
				for (j = 0;j < nYosoSagyogunNum; j++) {
					CString &sYosoSagyogunName = cBuhinhyoLink.aYosoSagyogunName[j];
					SYosoSagyogun *pYosoSagyogun = GetYosoSagyogun(nSublineId, cBuhinhyoLink.sKoteiName, sYosoSagyogunName);
					if (pYosoSagyogun != NULL) {
						// 工程/要素作業群情報セット
						SKittingYosoSagyogun *pKittingYosoSagyogun = cSubline.aKittingYosoSagyogun.LookupElement(cBuhinhyoLink.sKoteiName + sYosoSagyogunName, nIndex);
						if (pKittingYosoSagyogun == NULL) {
							pKittingYosoSagyogun = cSubline.aKittingYosoSagyogun.AddElement(cBuhinhyoLink.sKoteiName + sYosoSagyogunName, nIndex);
							pKittingYosoSagyogun->sKoteiName = cBuhinhyoLink.sKoteiName;
							pKittingYosoSagyogun->sYosoSagyogunName = sYosoSagyogunName;

							int nYosoSagyo = pYosoSagyogun->aYosoSagyo.GetSize();
							for (k = 0; k < nYosoSagyo; k++) {
								SYosoSagyo &cYosoSagyo = pYosoSagyogun->aYosoSagyo[k];
								if (cYosoSagyo.sDeleteFlag != "1" && cYosoSagyo.sDeleteFlag != "2") {
									SKittingYosoSagyo *pKittingYosoSagyo = pKittingYosoSagyogun->aKittingYosoSagyo.AddElement();
									pKittingYosoSagyo->sYosoSagyoName = cYosoSagyo.sYosoSagyoName;
									pKittingYosoSagyo->nSagyoNo = cYosoSagyo.nSagyoNo;
									pKittingYosoSagyo->nYosoSagyoId = k;
									pKittingYosoSagyo->fTime = cYosoSagyo.fTime;
									pKittingYosoSagyo->bSakujoFlag = bSetFuyoSagyo ? cYosoSagyo.bKittingDelete : CheckDefaultSakujoSagyo(cYosoSagyo.sYosoSagyoNo);
								}
							}
						}

						// 部品情報セット
						SKittingBuhin *pKittingBuhin = cSubline.aKittingBuhin.LookupElement(cBuhinhyoLink.sBuhinNo);
						if (pKittingBuhin == NULL) {
							pKittingBuhin = cSubline.aKittingBuhin.AddElement(cBuhinhyoLink.sBuhinNo);
							pKittingBuhin->sBlockNo = cBuhinhyoLink.sBlockNo;
							pKittingBuhin->sBuhinNo = cBuhinhyoLink.sBuhinNo;
							pKittingBuhin->sBuhinName = cBuhinhyoLink.sBuhinName;
							pKittingBuhin->aPC.SetSize(nKishuNum);

							SBuhinKitting *pBuhinKitting = cSubline.aBuhinKitting.LookupElement(cBuhinhyoLink.sBuhinNo);
							pKittingBuhin->bCheck = (pBuhinKitting != NULL) ? pBuhinKitting->bKittingFlag : (cSubline.aBuhinKitting.GetSize() == 0);
						}

						if (m_aKishu.LookupElement(cBuhinhyoLink.sKishu, nKishuId) != NULL)
							pKittingBuhin->aPC[nKishuId] = cBuhinhyoLink.nPC;

						int nSize = pKittingBuhin->aKittingYosoSagyogunId.GetSize();
						for (k = 0; k < nSize; k++) {
							if (pKittingBuhin->aKittingYosoSagyogunId[k] == nIndex)
								break;
						}
						if (k == nSize)
							pKittingBuhin->aKittingYosoSagyogunId.Add(nIndex);
					}
				}
			}
		}
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CDataManager::CheckKittingBuhinNo
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		キット化対象の部品番号かチェック
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CString		&sBuhinNo			[I] 部品番号
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL		FALSE：キット化対象部品でない，TRUE：キット化対象部品である
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		指定された部品番号がキット化対象部品化チェックする。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
BOOL CDataManager::CheckKittingBuhinNo(const SBuhinhyoLink &cBuhinhyoLink)
{
/*
	int i;

	// 部品番号の文字数を取得
	int nLength = sBuhinNo.GetLength();

	// ５文字未満なら対象外
	if (nLength < 5)
		return FALSE;

	// 先頭５文字に数字以外があったら対象外
	for (i = 0; i < 5; i++) {
		if (sBuhinNo[i] < '0' || sBuhinNo[i] > '9')
			return FALSE;
	}
*/
	// ボルトフラグがONなら対象外
	if (cBuhinhyoLink.bBoltFlag)
		return FALSE;

	// 対象外部品ファイルに含まれていれば対象外
	if (m_aKittingTaishogai.LookupElement(cBuhinhyoLink.sBuhinNo.Left(5)) != NULL)
		return FALSE;

	return TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CDataManager::CheckDefaultSakujoSagyo
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		デフォルトの削除対象作業チェック
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CString		&sYosoSagyoNo			[I] 要素作業番号
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL		FALSE：削除対象作業でない，TRUE：削除対象作業である
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		指定された要素作業番号がキット化による削除対象かチェックする。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
BOOL CDataManager::CheckDefaultSakujoSagyo(const CString &sYosoSagyoNo)
{
	if (sYosoSagyoNo == "031"
		|| sYosoSagyoNo == "093"
		|| sYosoSagyoNo == "097"
		|| sYosoSagyoNo == "098"
		|| sYosoSagyoNo == "100")
		return TRUE;

	return FALSE;
}

//*****************************************************************************************************
//  1. 関数名
//		CDataManager::DeleteKittingSagyo
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		キット化による要素作業削除
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		キット化により削除対象となった要素作業を要素作業群データから削除する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CDataManager::DeleteKittingSagyo(int nSublineId)
{
	SSubline &cSubline = m_aSubline[nSublineId];
	int i, j, k;

	int nKittingBuhin = cSubline.aKittingBuhin.GetSize();
	for (i = 0; i < nKittingBuhin; i++) {
		SKittingBuhin &cKittingBuhin = cSubline.aKittingBuhin[i];
		int nYosoSagyogun = cKittingBuhin.aKittingYosoSagyogunId.GetSize();
		for (j = 0; j < nYosoSagyogun; j++) {
			SKittingYosoSagyogun &cKittingYosoSagyogun = cSubline.aKittingYosoSagyogun[cKittingBuhin.aKittingYosoSagyogunId[j]];
			SYosoSagyogun *pYosoSagyogun = GetYosoSagyogun(nSublineId, cKittingYosoSagyogun.sKoteiName, cKittingYosoSagyogun.sYosoSagyogunName);
			if (pYosoSagyogun != NULL) {
				int nYosoSagyo = cKittingYosoSagyogun.aKittingYosoSagyo.GetSize();
				for (k = 0; k < nYosoSagyo; k++) {
					SKittingYosoSagyo &cKittingYosoSagyo = cKittingYosoSagyogun.aKittingYosoSagyo[k];
					pYosoSagyogun->aYosoSagyo[cKittingYosoSagyo.nYosoSagyoId].bKittingDelete = cKittingYosoSagyo.bSakujoFlag;
				}
			}
		}
	}

	// データ更新フラグ設定
	m_bUpdate = TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CDataManager::AddKittingTemplate
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		キット化作業テンプレート追加
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		要素作業表にキット化作業を追加する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CDataManager::AddKittingTemplate(int nSublineId)
{
	CWaitCursor cWaitCursor;
	SSubline &cSubline = m_aSubline[nSublineId];
	int i, j;
	int nRow, nCol;
	long nUsedRows, nUsedCols;
	CString sKoteiNo;
	CIntArrayEx aTemplate1;
	CIntArrayEx aTemplate2;
	CIntArrayEx aKittingBuhin;
	_variant_t vData;
	CString sText;
	int nKishuId;
	int nYosoSagyogunJunban;

	// テンプレートの行数を取得
	nUsedRows = m_eKittingTemplate.GetUsedRows();
	for (nRow = 1; nRow <= nUsedRows; nRow++) {
		m_eKittingTemplate.GetElement(nRow, m_eYosoSagyoHyo.eColKoteiNo, sKoteiNo);

		if (sKoteiNo == "001")
			aTemplate1.Add(nRow);

		if (sKoteiNo == "002")
			aTemplate2.Add(nRow);
	}
	int nTemplate1Rows = aTemplate1.GetSize();
	int nTemplate2Rows = aTemplate2.GetSize();

	int nLastRowIndex = cSubline.aRowArray.GetSize();

	// テンプレート１をコピー
	nUsedCols = m_eKittingTemplate.GetUsedCols();
	for (i = 0; i < nTemplate1Rows; i++) {
		nRow = m_eYosoSagyoHyo.AddRows();
		for (nCol = 1; nCol <= nUsedCols; nCol++) {
			m_eKittingTemplate.GetElement(aTemplate1[i], nCol, vData);

			if (nCol == m_eYosoSagyoHyo.eColTsuikaFlag)
// Modify ... ( CHANGE )
				vData = "1";
//				vData = "3";
// By Y.Itabashi (xxxxx) 2007.02.21
			else if (nCol == m_eYosoSagyoHyo.eColSublineName)
				vData = cSubline.sSublineName;

// Modify ... ( ADD )
			if( nCol == m_eYosoSagyoHyo.eColKoteiKigo )
				m_eKittingTemplate.GetElement(aTemplate1[i], nCol, m_sKittingKoteiKigo);
// By Y.Itabasshi (xxxxx) 2007.03.07

			m_eYosoSagyoHyo.PutElement(nRow, nCol, vData);
		}
		cSubline.aRowArray.Add(nRow);
	}

	// テンプレート２をコピー
	int nKittingBuhin = cSubline.aKittingBuhin.GetSize();
	for (i = 0; i < nKittingBuhin; i++) {
		SKittingBuhin &cKittingBuhin = cSubline.aKittingBuhin[i];

		if (cKittingBuhin.bCheck) {
			for (j = 0; j < nTemplate2Rows; j++) {
				nRow = m_eYosoSagyoHyo.AddRows();
				for (nCol = 1; nCol <= nUsedCols; nCol++) {
					m_eKittingTemplate.GetElement(aTemplate2[j], nCol, vData);

					if (nCol == m_eYosoSagyoHyo.eColTsuikaFlag)
// Modify ... ( CHANGE )
						vData = "1";
//						vData = "3";
// By Y.Itabashi (xxxxx) 2007.02.21
					else if (nCol == m_eYosoSagyoHyo.eColSublineName)
						vData = cSubline.sSublineName;
					else if (nCol == m_eYosoSagyoHyo.eColKoteiNo) {
						sText.Format("%03d", i + 2);
						vData = sText;
					} else if (nCol == m_eYosoSagyoHyo.eColKoteiName || nCol == m_eYosoSagyoHyo.eColYosoSagyogunName) {
						m_eKittingTemplate.GetElement(aTemplate2[j], nCol, sText);
						sText.Replace("$", cKittingBuhin.sBuhinName);
						vData = sText;
					}
					m_eYosoSagyoHyo.PutElement(nRow, nCol, vData);
				}

				// 機種別に要素作業群順番を設定
				int nSize = m_eYosoSagyoHyo.m_aKishuCols[1].aKishuCols.GetSize();
				for (int k = 0; k < nSize; k++) {
					nYosoSagyogunJunban = 0;
					SKishuCols &cKishuCols = m_eYosoSagyoHyo.m_aKishuCols[1].aKishuCols[k];
					if (m_aKishu.LookupElement(cKishuCols.sKishuName, nKishuId) != NULL) {
						if (cKittingBuhin.aPC[nKishuId] != 0)
							nYosoSagyogunJunban = 1;
					}
					m_eYosoSagyoHyo.PutElement(nRow, cKishuCols.nCol, nYosoSagyogunJunban);
				}

				cSubline.aRowArray.Add(nRow);
			}
		}
	}

	// 要素作業群を抽出
	ExtractYosoSagyogunSub(nSublineId, nLastRowIndex);

	// 要素作業群順番を設定
	int nKishuNum = m_aKishu.GetSize();
	for (nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
		CSagyojunArray aSagyojun;
		GetKittingSagyojun(nSublineId, nKishuId, aSagyojun);
		OrderByKittingSagyojun(nSublineId, aSagyojun);
	}

	// データ更新フラグ設定
	m_bUpdate = TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CDataManager::CancelKitting
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		キット化解除
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		キット化作業を要素作業群データから削除し、キット化により削除された要素作業を復活させる。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CDataManager::CancelKitting(int nSublineId)
{
	SSubline &cSubline = m_aSubline[nSublineId];
	CYosoSagyogunArray &aYosoSagyogun = cSubline.aYosoSagyogun;
	int i, j;

	cSubline.aYosoSagyogunKit.RemoveAll();

	int nYosoSagyogun = aYosoSagyogun.GetSize();
	for (i = 0; i < nYosoSagyogun; i++) {
		SYosoSagyogun &cYosoSagyogun = aYosoSagyogun[i];
		int nYosoSagyo = cYosoSagyogun.aYosoSagyo.GetSize();
		for (j = 0; j < nYosoSagyo; j++) {
			SYosoSagyo &cYosoSagyo = cYosoSagyogun.aYosoSagyo[j];
			if (cYosoSagyo.sDeleteFlag == "3")
				cYosoSagyo.sDeleteFlag = "";
		}
	}

	aYosoSagyogun.MakeKey();

	// データ更新フラグ設定
	m_bUpdate = TRUE;

	// キット化有無フラグリセット
	cSubline.bSetKitting = FALSE;
}

// 特定の部品のキット化を解除
void CDataManager::CancelKitting(int nSublineId, LPCTSTR pKoteiName)
{
	SSubline &cSubline = m_aSubline[nSublineId];

	// キット化テンプレートから工程名を取得
	CString sKoteiNo;
	CString sKoteiName;
	int nUsedRows = m_eKittingTemplate.GetUsedRows();
	for (int nRow = 1; nRow <= nUsedRows; nRow++) {
		m_eKittingTemplate.GetElement(nRow, m_eYosoSagyoHyo.eColKoteiNo, sKoteiNo);
		if (sKoteiNo == "002") {
			m_eKittingTemplate.GetElement(nRow, m_eYosoSagyoHyo.eColKoteiName, sKoteiName);
			break;
		}
	}

	// 工程名から部品名を抽出
	CString sBuhinName = pKoteiName;
	sKoteiName.Replace("$", "");
	int nPos = sBuhinName.Find(sKoteiName);
	if (nPos >= 0) {
		sBuhinName.Delete(nPos, sKoteiName.GetLength());

		// 部品表＆工程リンクデータによるループ
		int nBuhinhyoLink = m_aBuhinhyoLink.GetSize();
		for (int i = 0; i < nBuhinhyoLink; i++) {
			SBuhinhyoLink &cBuhinhyoLink = m_aBuhinhyoLink[i];

			// 部品表＆工程リンクからサブライン名と部品名が一致するデータを探す
			if (cBuhinhyoLink.sSublineName == cSubline.sSublineName && cBuhinhyoLink.sBuhinName == sBuhinName) {
				int nSize = cBuhinhyoLink.aYosoSagyogunName.GetSize();
				for (int j = 0; j < nSize; j++) {
					CString &sYosoSagyogunName = cBuhinhyoLink.aYosoSagyogunName[j];

					// 部品にリンクしている要素作業群を復活させる
					int nYosoSagyogunNum = cSubline.aYosoSagyogun.GetSize();
					for (int nYosoSagyogunId = 0; nYosoSagyogunId < nYosoSagyogunNum; nYosoSagyogunId++) {
						SYosoSagyogun &cYosoSagyogun = cSubline.aYosoSagyogun[nYosoSagyogunId];
						if (cYosoSagyogun.sYosoSagyogunName == sYosoSagyogunName) {
							int nYosoSagyoNum = cYosoSagyogun.aYosoSagyo.GetSize();
							for (int nYosoSagyoId = 0; nYosoSagyoId < nYosoSagyoNum; nYosoSagyoId++) {
								SYosoSagyo &cYosoSagyo = cYosoSagyogun.aYosoSagyo[nYosoSagyoId];
								if (cYosoSagyo.sDeleteFlag == "3")
									cYosoSagyo.sDeleteFlag.Empty();
							}
							cYosoSagyogun.bKittingCancel = TRUE;
						}
					}
				}

				// 部品のキット化フラグをクリア
				SKittingBuhin *pKittingBuhin = cSubline.aKittingBuhin.LookupElement(cBuhinhyoLink.sBuhinNo);
				if (pKittingBuhin != NULL)
					pKittingBuhin->bCheck = FALSE;
			}
		}

		// キット化作業から該当部品の作業を削除
		int nYosoSagyogunNum = cSubline.aYosoSagyogunKit.GetSize();
		for (int nYosoSagyogunId = 0; nYosoSagyogunId < nYosoSagyogunNum; nYosoSagyogunId++) {
			SYosoSagyogun &cYosoSagyogun = cSubline.aYosoSagyogunKit[nYosoSagyogunId];
			if (cYosoSagyogun.sKoteiName == pKoteiName) {
				cSubline.aYosoSagyogunKit.RemoveAt(nYosoSagyogunId);
				break;
			}
		}
	}
}

// 部品キット化情報ファイル設定
void CDataManager::SetBuhinKitting()
{
	int nSublineNum = m_aSubline.GetSize();
	for (int nSublineId = 0; nSublineId < nSublineNum; nSublineId++) {
		SSubline &cSubline = m_aSubline[nSublineId];
	
		cSubline.aBuhinKitting.RemoveAll();

		int nBuhinNum = cSubline.aKittingBuhin.GetSize();
		for (int i = 0; i < nBuhinNum; i++) {
			SKittingBuhin &cKittingBuhin = cSubline.aKittingBuhin[i];
			SBuhinKitting *pBuhinKitting = cSubline.aBuhinKitting.AddElement(cKittingBuhin.sBuhinNo);
			pBuhinKitting->sBlockNo = cKittingBuhin.sBlockNo;
			pBuhinKitting->sBuhinNo = cKittingBuhin.sBuhinNo;
			pBuhinKitting->sBuhinName = cKittingBuhin.sBuhinName;
			pBuhinKitting->bKittingFlag = cKittingBuhin.bCheck;
		}

		SetKittingDeleteFlagSub(nSublineId);
	}
}

// キット化解除フラグクリア
void CDataManager::ClearKittingCancelFlag()
{
	int nSublineNum = m_aSubline.GetSize();
	for (int nSublineId = 0; nSublineId < nSublineNum; nSublineId++) {
		SSubline &cSubline = m_aSubline[nSublineId];

		int nYosoSagyogunNum = cSubline.aYosoSagyogun.GetSize();
		for (int nYosoSagyogunId = 0; nYosoSagyogunId < nYosoSagyogunNum; nYosoSagyogunId++)
			cSubline.aYosoSagyogun[nYosoSagyogunId].bKittingCancel = FALSE;
	}
}

// 要素作業表にキット化による削除フラグをセット
void CDataManager::SetKittingDeleteFlag()
{
	int nSublineNum = m_aSubline.GetSize();
	for (int nSublineId = 0; nSublineId < nSublineNum; nSublineId++)
		SetKittingDeleteFlagSub(nSublineId);
}

// 要素作業表にキット化による削除フラグをセット
void CDataManager::SetKittingDeleteFlagSub(int nSublineId)
{
	SSubline &cSubline = m_aSubline[nSublineId];

	// キット化による削除フラグを一旦全て設定する
	int nYosoSagyogunNum = cSubline.aYosoSagyogun.GetSize();
	for (int nYosoSagyogunId = 0; nYosoSagyogunId < nYosoSagyogunNum; nYosoSagyogunId++) {
		SYosoSagyogun &cYosoSagyogun = cSubline.aYosoSagyogun[nYosoSagyogunId];

		int nYosoSagyoNum = cYosoSagyogun.aYosoSagyo.GetSize();
		for (int nYosoSagyoId = 0; nYosoSagyoId < nYosoSagyoNum; nYosoSagyoId++) {
			SYosoSagyo &cYosoSagyo = cYosoSagyogun.aYosoSagyo[nYosoSagyoId];
			if (cYosoSagyo.bKittingDelete && cYosoSagyo.sDeleteFlag.IsEmpty())
				cYosoSagyo.sDeleteFlag = "3";
		}
	}

	// キット化設定されていない部品の要素作業の削除フラグを解除する
	int nBuhinhyoLinkNum = m_aBuhinhyoLink.GetSize();
	for (int i = 0; i < nBuhinhyoLinkNum; i++) {
		SBuhinhyoLink &cBuhinhyoLink = m_aBuhinhyoLink[i];

		SBuhinKitting *pBuhinKitting = cSubline.aBuhinKitting.LookupElement(cBuhinhyoLink.sBuhinNo);
		if (pBuhinKitting != NULL) {
			int nSize = cBuhinhyoLink.aYosoSagyogunName.GetSize();
			for (int j = 0; j < nSize; j++) {
				SYosoSagyogun *pYosoSagyogun = GetYosoSagyogun(nSublineId, cBuhinhyoLink.sKoteiName, cBuhinhyoLink.aYosoSagyogunName[j]);

				if (pYosoSagyogun != NULL) {
					int nYosoSagyoNum = pYosoSagyogun->aYosoSagyo.GetSize();
					for (int nYosoSagyoId = 0; nYosoSagyoId < nYosoSagyoNum; nYosoSagyoId++) {
						SYosoSagyo &cYosoSagyo = pYosoSagyogun->aYosoSagyo[nYosoSagyoId];
						if (!pBuhinKitting->bKittingFlag && cYosoSagyo.sDeleteFlag == "3")
							cYosoSagyo.sDeleteFlag.Empty();
					}
				}
			}
		}
	}
}
