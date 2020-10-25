//*****************************************************************************************************
//  1. ファイル名
//		DataManagerEdit.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		CDataManager クラスのインプリメンテーション（編集関連）
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
//		CDataManager::KoteiBunkatsu
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		要素作業群分割処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		int				nSublineId			[I] サブラインID
//		CString			&sKoteiName			[I] 工程名
//		CStringArray	&aYosoSagyogun		[I] 分割する要素作業群名の配列
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		指定された工程名の工程を分割する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CDataManager::KoteiBunkatsu(int nSublineId, const CString &sKoteiName, CStringArray &aYosoSagyogunName)
{
	SSubline &cSubline = m_aSubline[nSublineId];
	CYosoSagyogunArray &aYosoSagyogun = cSubline.aYosoSagyogun;
	int i, j;

	// 指定された要素作業の数を取得
	int nYosoSagyogun = aYosoSagyogunName.GetSize();
	if (nYosoSagyogun == 0)
		return;

	// 指定された工程の要素作業群を取得
	SKotei cKotei;
	GetKotei(nSublineId, sKoteiName, cKotei);

	// 新しい工程名
	CString sNewKoteiName1 = NewKoteiName(nSublineId, sKoteiName, 1);
	CString sNewKoteiName2 = NewKoteiName(nSublineId, sKoteiName, 2);

	// 工程名に"_1","_2"を付加する
	int nYosoSagyogun2 = cKotei.aYosoSagyogunId.GetSize();
	for (i = 0; i < nYosoSagyogun2; i++) {
		SYosoSagyogun &cYosoSagyogun = aYosoSagyogun[cKotei.aYosoSagyogunId[i]];
		SYosoSagyogun cYosoSagyogun2 = cYosoSagyogun;

		for (j = 0; j < nYosoSagyogun; j++) {
			if (cYosoSagyogun.sYosoSagyogunName == aYosoSagyogunName[j]) {
				cYosoSagyogun2.sKoteiName = sNewKoteiName2;
				break;
			}
		}

		if (j == nYosoSagyogun)
			cYosoSagyogun2.sKoteiName = sNewKoteiName1;

		int nYosoSagyoNum = cYosoSagyogun.aYosoSagyo.GetSize();
		for (int j = 0; j < nYosoSagyoNum; j++) {
			cYosoSagyogun.aYosoSagyo[j].sDeleteFlag = "1";
			cYosoSagyogun2.aYosoSagyo[j].sInsertFlag = "1";
		}

		aYosoSagyogun.Add(cYosoSagyogun2);
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CDataManager::YosoSagyogunBunkatsu
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		要素作業群分割処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		int			nSublineId				[I] サブラインID
//		CString		&sKoteiName				[I] 工程名
//		CString		&sYosoSagyogunName		[I] 要素作業群名
//		CIntArrayEx	&cYosoSagyoArray		[I] 分割する要素作業の配列
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		指定された工程名、要素作業群名の要素作業群を分割する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CDataManager::YosoSagyogunBunkatsu(int nSublineId, const CString &sKoteiName, const CString &sYosoSagyogunName, CIntArrayEx &aYosoSagyo)
{
	SSubline &cSubline = m_aSubline[nSublineId];
	CYosoSagyogunArray &aYosoSagyogun = cSubline.aYosoSagyogun;
	int i;
	int nYosoSagyogunId;
	SYosoSagyogun *pYosoSagyogun;
	int nIndex;

	// 指定された要素作業の数を取得
	if (aYosoSagyo.GetSize() == 0)
		return;

	// 要素作業をソート
	aYosoSagyo.Sort();

	// 指定された工程、要素作業群データを取得
	int nSize = aYosoSagyogun.GetSize();
	for (nYosoSagyogunId = 0; nYosoSagyogunId < nSize; nYosoSagyogunId++) {
		pYosoSagyogun = &aYosoSagyogun[nYosoSagyogunId];
		if (!pYosoSagyogun->CheckDeleted()) {
			if (pYosoSagyogun->sKoteiName == sKoteiName && pYosoSagyogun->sYosoSagyogunName == sYosoSagyogunName)
				break;
		}
	}

	// 分割した要素作業群を作成
	SYosoSagyogun cYosoSagyogun1 = *pYosoSagyogun;
	SYosoSagyogun cYosoSagyogun2 = *pYosoSagyogun;

	cYosoSagyogun1.sYosoSagyogunName = NewYosoSagyogunName(nSublineId, sYosoSagyogunName, 1);
	cYosoSagyogun2.sYosoSagyogunName = NewYosoSagyogunName(nSublineId, sYosoSagyogunName, 2);

	cYosoSagyogun1.aYosoSagyo.RemoveAll();
	cYosoSagyogun2.aYosoSagyo.RemoveAll();

	int nYosoSagyoNum = pYosoSagyogun->aYosoSagyo.GetSize();
	for (i = 0; i < nYosoSagyoNum; i++) {
		if (aYosoSagyo.Find(i) == -1) {
			nIndex = cYosoSagyogun1.aYosoSagyo.Add(pYosoSagyogun->aYosoSagyo[i]);
			cYosoSagyogun1.aYosoSagyo[nIndex].sInsertFlag = "2";
		} else {
			nIndex = cYosoSagyogun2.aYosoSagyo.Add(pYosoSagyogun->aYosoSagyo[i]);
			cYosoSagyogun2.aYosoSagyo[nIndex].sInsertFlag = "2";
		}

		pYosoSagyogun->aYosoSagyo[i].sDeleteFlag = "2";
	}

	// 分割した要素作業群を追加
	aYosoSagyogun.InsertAt(nYosoSagyogunId + 1, cYosoSagyogun1);
	aYosoSagyogun.InsertAt(nYosoSagyogunId + 2, cYosoSagyogun2);
}

CString CDataManager::NewKoteiName(int nSublineId, const CString &sName, int nPostfix)
{
	SSubline &cSubline = m_aSubline[nSublineId];
	CYosoSagyogunArray &aYosoSagyogun = cSubline.aYosoSagyogun;
	int i;
	CString sBaseName = sName;

	// その番号が既に使われていたら次の番号を取得
	CString sNewName;
	int nSize = aYosoSagyogun.GetSize();
	while (true) {
		sNewName.Format("%s_%d", sBaseName, nPostfix);
		for (i = 0; i < nSize; i++) {
			if (sNewName == aYosoSagyogun[i].sKoteiName)
				break;
		}
		if (i == nSize)
			break;
		else
			nPostfix++;
	}

	return sNewName;
}

//*****************************************************************************************************
//  1. 関数名
//		CDataManager::NewYosoSagyogunName
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		要素作業群名の後ろに数字を付加
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		int			nSublineId			[I] サブラインID
//		CString		&sName		[I] 要素作業群ID配列
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		CString		数字が付加された要素作業群名
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		要素作業群名の後ろに数字を付加する。その名前が既に存在したら次の番号とする。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
CString CDataManager::NewYosoSagyogunName(int nSublineId, const CString &sName, int nPostfix)
{
	SSubline &cSubline = m_aSubline[nSublineId];
	CYosoSagyogunArray &aYosoSagyogun = cSubline.aYosoSagyogun;
	int i;
	CString sBaseName = sName;

	// その番号が既に使われていたら次の番号を取得
	CString sNewName;
	int nSize = aYosoSagyogun.GetSize();
	while (true) {
		sNewName.Format("%s_%d", sBaseName, nPostfix);
		for (i = 0; i < nSize; i++) {
			if (sNewName == aYosoSagyogun[i].sYosoSagyogunName)
				break;
		}
		if (i == nSize)
			break;
		else
			nPostfix++;
	}

	return sNewName;
}

// 作業者追加
void CDataManager::InsertSagyosha(int nSublineId, int nSagyoshaId)
{
	if (nSublineId != -1)
		m_aSubline[nSublineId].aSagyosha.InsertAt(nSagyoshaId, "");
	else
		m_aKittingSagyosha.InsertAt(nSagyoshaId, "");

	AdjustSagyoshaId(nSublineId, nSagyoshaId, 1);

	RenameSagyoshaName(nSublineId);
}

// 作業者削除
void CDataManager::DeleteSagyosha(int nSublineId, int nSagyoshaId)
{
	if (nSublineId != -1)
		m_aSubline[nSublineId].aSagyosha.RemoveAt(nSagyoshaId);
	else
		m_aKittingSagyosha.RemoveAt(nSagyoshaId);

	AdjustSagyoshaId(nSublineId, nSagyoshaId, -1);

	RenameSagyoshaName(nSublineId);
}

// 作業者ID補正
void CDataManager::AdjustSagyoshaId(int nSublineId, int nSagyoshaId, int nAdjust)
{
	if (nSublineId != -1) {
		SSubline &cSubline = m_aSubline[nSublineId];

		int nYosoSagyogunNum = cSubline.aYosoSagyogun.GetSize();
		for (int nYosoSagyogunId = 0; nYosoSagyogunId < nYosoSagyogunNum; nYosoSagyogunId++) {
			SYosoSagyogun &cYosoSagyogun = cSubline.aYosoSagyogun[nYosoSagyogunId];
			int nKishuNum = cYosoSagyogun.aKishuSagyo.GetSize();
			for (int nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
				SKishuSagyo &cKishuSagyo = cYosoSagyogun.aKishuSagyo[nKishuId];
				if (cKishuSagyo.nSagyoshaId != -1 && cKishuSagyo.nSagyoshaId >= nSagyoshaId)
					cKishuSagyo.nSagyoshaId += nAdjust;
			}
		}
	} else {
		int nSublineNum = m_aSubline.GetSize();
		for (int nSublineId = 0; nSublineId < nSublineNum; nSublineId++) {
			SSubline &cSubline = m_aSubline[nSublineId];
		
			int nYosoSagyogunNum = cSubline.aYosoSagyogunKit.GetSize();
			for (int nYosoSagyogunId = 0; nYosoSagyogunId < nYosoSagyogunNum; nYosoSagyogunId++) {
				SYosoSagyogun &cYosoSagyogun = cSubline.aYosoSagyogunKit[nYosoSagyogunId];
				int nKishuNum = cYosoSagyogun.aKishuSagyo.GetSize();
				for (int nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
					SKishuSagyo &cKishuSagyo = cYosoSagyogun.aKishuSagyo[nKishuId];
					if (cKishuSagyo.nSagyoshaId != -1 && cKishuSagyo.nSagyoshaId >= nSagyoshaId)
						cKishuSagyo.nSagyoshaId += nAdjust;
				}
			}
		}
	}
}

// 指定した作業者に作業があるかチェック
BOOL CDataManager::CheckSagyoshaSagyo(int nSublineId, int nSagyoshaId)
{
	if (nSublineId != -1) {
		SSubline &cSubline = m_aSubline[nSublineId];

		int nYosoSagyogunNum = cSubline.aYosoSagyogun.GetSize();
		for (int nYosoSagyogunId = 0; nYosoSagyogunId < nYosoSagyogunNum; nYosoSagyogunId++) {
			SYosoSagyogun &cYosoSagyogun = cSubline.aYosoSagyogun[nYosoSagyogunId];

			if (!cYosoSagyogun.CheckDeleted()) {
				int nKishuNum = cYosoSagyogun.aKishuSagyo.GetSize();
				for (int nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
					SKishuSagyo &cKishuSagyo = cYosoSagyogun.aKishuSagyo[nKishuId];
					if (cKishuSagyo.nSagyoshaId == nSagyoshaId)
						return TRUE;
				}
			}
		}
	} else {
		int nSublineNum = m_aSubline.GetSize();
		for (int nSublineId = 0; nSublineId < nSublineNum; nSublineId++) {
			SSubline &cSubline = m_aSubline[nSublineId];
		
			int nYosoSagyogunNum = cSubline.aYosoSagyogunKit.GetSize();
			for (int nYosoSagyogunId = 0; nYosoSagyogunId < nYosoSagyogunNum; nYosoSagyogunId++) {
				SYosoSagyogun &cYosoSagyogun = cSubline.aYosoSagyogunKit[nYosoSagyogunId];

				if (!cYosoSagyogun.CheckDeleted()) {
					int nKishuNum = cYosoSagyogun.aKishuSagyo.GetSize();
					for (int nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
						SKishuSagyo &cKishuSagyo = cYosoSagyogun.aKishuSagyo[nKishuId];
						if (cKishuSagyo.nSagyoshaId == nSagyoshaId)
							return TRUE;
					}
				}
			}
		}
	}

	return FALSE;
}

// クリップボードに要素作業群をコピー
void CDataManager::CopyClipboard(int nSublineId, CIntArrayEx &aYosoSagyogunId, LPCTSTR pTsuikaFlag)
{
	if (::OpenClipboard(NULL)) {
		::EmptyClipboard();

		SSubline &cSubline = m_aSubline[nSublineId];
		CYosoSagyogunArray &aYosoSagyogun = cSubline.aYosoSagyogun;
		CString sData;
		int i, j;
		int nData = 0;

		int nYosoSagyo = 0;
		int nSize = aYosoSagyogunId.GetSize();
		for (i = 0; i < nSize; i++)
			nYosoSagyo += aYosoSagyogun[aYosoSagyogunId[i]].aYosoSagyo.GetSize();

		HANDLE hMem = ::GlobalAlloc(GHND, sizeof(ClipBoardData) * nYosoSagyo);

		ClipBoardData *pClipBoardData = (ClipBoardData *)::GlobalLock(hMem);
		for (i = 0; i < nSize; i++) {
			SYosoSagyogun &cYosoSagyogun = aYosoSagyogun[aYosoSagyogunId[i]];
			for (j = 0; j < cYosoSagyogun.aYosoSagyo.GetSize(); j++) {
				SYosoSagyo &cYosoSagyo = cYosoSagyogun.aYosoSagyo[j];

				for (int nCol = 1; nCol < 48; nCol++) {
					m_eYosoSagyoHyo.GetElement(cYosoSagyo.nRow, m_eYosoSagyoHyo.GetColumnNo(nCol), sData);

					if (nCol == m_eYosoSagyoHyo.eColTsuikaFlag)
						sData = pTsuikaFlag;

					strcpy(pClipBoardData[nData][nCol - 1], sData);
				}

				nData++;
			}
		}

		::GlobalUnlock(hMem);

		UINT nFormatId = ::RegisterClipboardFormat("YOSO_SAGYO_HYO");
		::SetClipboardData(nFormatId, hMem);

		::CloseClipboard();
	}
}

// クリップボードから要素作業群を貼り付け
BOOL CDataManager::PasteClipboard(int nSublineId, int nYosoSagyogunId, int nKishuId, int nSagyoshaId)
{
	BOOL bOK = FALSE;

	if (::OpenClipboard(NULL)) {
		SSubline &cSubline = m_aSubline[nSublineId];
		SYosoSagyogun &cYosoSagyogun = cSubline.aYosoSagyogun[nYosoSagyogunId];
		int nRow;
		int i;
		CString sName;
		SKishuCols *pKishuCols;

		UINT nFormatId = ::RegisterClipboardFormat("YOSO_SAGYO_HYO");
		HGLOBAL hMem = ::GetClipboardData(nFormatId);
		if (hMem) {
			bOK = TRUE;

			ClipBoardData *pClipBoardData = (ClipBoardData *)::GlobalLock(hMem);
			int nData = ::GlobalSize(hMem) / sizeof(ClipBoardData);

			int nLastRowIndex = cSubline.aRowArray.GetSize();

// Modify ... ( ADD )
			CString sKoteiName, sYosoSagyogunName;
			CString sKoteiNameB, sYosoSagyogunNameB;
// By Y.Itabashi (xxxxx) 2007.02.14

			for (i = 0; i < nData; i++) {
				nRow = m_eYosoSagyoHyo.AddRows();

				for (int nCol = 1; nCol < 48; nCol++) {
					if (nCol != m_eYosoSagyoHyo.eColSagyosha
							&& nCol != m_eYosoSagyoHyo.eColYosoSagyogunJunban
							&& nCol != m_eYosoSagyoHyo.eColSagyogunGokeiJikan
							&& nCol != m_eYosoSagyoHyo.eColHiritsukeisangoSagyogunGokeiJikan) {
						LPCTSTR pData = pClipBoardData[i][nCol - 1];

						if (nCol == m_eYosoSagyoHyo.eColSublineName)
							pData = cSubline.sSublineName;
// Modify ... ( ADD )
						else if (nCol == m_eYosoSagyoHyo.eColKoteiKigo)
							pData = cYosoSagyogun.sKoteiKigo;
// By Y.Itabashi (xxxxx) 2007.02.15
						else if (nCol == m_eYosoSagyoHyo.eColKoteiNo)
							pData = cYosoSagyogun.sKoteiNo;
						else if (nCol == m_eYosoSagyoHyo.eColKoteiName || nCol == m_eYosoSagyoHyo.eColYosoSagyogunName) {
							sName.Format("COPY(%d)%s", m_nCopyCount, pData);
							sName = sName.Left(128);
							pData = sName;

// Modify ... ( ADD )
							if( nCol == m_eYosoSagyoHyo.eColKoteiName )
								sKoteiName = sName;
							else
								sYosoSagyogunName = sName;
// By Y.Itabashi (xxxxx) 2007.02.14
						}

						m_eYosoSagyoHyo.PutElement(nRow, m_eYosoSagyoHyo.GetColumnNo(nCol), pData);
					}
				}

// Modify ... ( ADD )
				if( sKoteiName != sKoteiNameB || sYosoSagyogunName != sYosoSagyogunNameB ){

					SKatashikiError cKatashikiError;
					SLineError cLineError;

					cKatashikiError.nKishuID = nKishuId;
					cKatashikiError.sSublineFrom = cSubline.sSublineName;
					cKatashikiError.sKoteiNoFrom = cYosoSagyogun.sKoteiNo;
					cKatashikiError.sKoteiNameFrom = sKoteiName;
					cKatashikiError.sYosoSagyogunNameFrom = sYosoSagyogunName;
					cKatashikiError.sRiyuNo = "E020003";

					cLineError.nKishuID = nKishuId;
					cLineError.sSublineFrom = cSubline.sSublineName;
					cLineError.sKoteiNoFrom = cYosoSagyogun.sKoteiNo;
					cLineError.sKoteiNameFrom = sKoteiName;
					cLineError.sYosoSagyogunNameFrom = sYosoSagyogunName;
					cLineError.sRiyuNo = "E020003";

					AddKojunKiseiError( cKatashikiError, cLineError );
				}

				sKoteiNameB = sKoteiName;
				sYosoSagyogunNameB = sYosoSagyogunName;
// By Y.Itabashi (xxxxx) 2007.02.14

				// 作業者名
				pKishuCols = m_eYosoSagyoHyo.m_aKishuCols[0].aKishuCols.LookupElement(m_aKishu[nKishuId].sKishuName);
				m_eYosoSagyoHyo.PutElement(nRow, pKishuCols->nCol, cSubline.aSagyosha[nSagyoshaId]);

				// 要素作業順番
				pKishuCols = m_eYosoSagyoHyo.m_aKishuCols[1].aKishuCols.LookupElement(m_aKishu[nKishuId].sKishuName);
				m_eYosoSagyoHyo.PutElement(nRow, pKishuCols->nCol, 1);

				cSubline.aRowArray.Add(nRow);
			}

			// 作業順取得
			CSagyojunArray aSagyojun, aSagyojun2;
			GetSagyojun(nSublineId, -1, aSagyojun);

			// 追加する前の要素作業群数取得
			int nYosoSagyogunNum1 = cSubline.aYosoSagyogun.GetSize();

			// 要素作業群抽出
			ExtractYosoSagyogunSub(nSublineId, nLastRowIndex);

			// 追加した後の要素作業群数取得
			int nYosoSagyogunNum2 = cSubline.aYosoSagyogun.GetSize();

			int nSize = aSagyojun.GetSize();
			for (i = 0; i < nSize; i++) {
				if (aSagyojun[i].nYosoSagyogunId == nYosoSagyogunId && aSagyojun[i].nKishuId == nKishuId) {
					for (int nYosoSagyogunId = nYosoSagyogunNum1; nYosoSagyogunId < nYosoSagyogunNum2; nYosoSagyogunId++) {
						SSagyojun cSagyojun;
						cSagyojun.nKishuId = nKishuId;
						cSagyojun.nYosoSagyogunId = nYosoSagyogunId;
						aSagyojun.InsertAt(++i, cSagyojun);
					}
					break;
				}
			}

			OrderBySagyojun(nSublineId, aSagyojun);
			RenumberSagyoshaId(nSublineId);

			m_nCopyCount++;

			GlobalUnlock(hMem);
		}

		::CloseClipboard();
	}

// Modify ... ( ADD )
	// 工順規制違反ファイル
	SaveKojunKiseiError();
// By Y.Itabashi (xxxxx) 2007.02.14

	return bOK;
}

// 要素作業群ID配列から要素作業群配列を取得
void CDataManager::GetYosoSagyogun(int nSublineId, CIntArrayEx &aYosoSagyogunId, CYosoSagyogunArray &aYosoSagyogun)
{
	aYosoSagyogun.RemoveAll();

	SSubline &cSubline = m_aSubline[nSublineId];
	int nSize = aYosoSagyogunId.GetSize();
	for (int i = 0; i < nSize; i++)
		aYosoSagyogun.Add(cSubline.aYosoSagyogun[aYosoSagyogunId[i]]);
}

// 要素作業群削除
void CDataManager::DeleteYosoSagyogun(int nSublineId, CIntArrayEx &aYosoSagyogunId, LPCTSTR pDeleteFlag)
{
	SSubline &cSubline = m_aSubline[nSublineId];
	int i, j;

	int nSize = aYosoSagyogunId.GetSize();
	for (i = 0; i < nSize; i++) {
		SYosoSagyogun &cYosoSagyogun = cSubline.aYosoSagyogun[aYosoSagyogunId[i]];

		int nYosoSagyoNum = cYosoSagyogun.aYosoSagyo.GetSize();
		for (j = 0; j < nYosoSagyoNum; j++) {
			SYosoSagyo &cYosoSagyo = cYosoSagyogun.aYosoSagyo[j];

			if (cYosoSagyo.sDeleteFlag.IsEmpty())
				cYosoSagyo.sDeleteFlag = pDeleteFlag;
		}

		int nKishuSagyoNum = cYosoSagyogun.aKishuSagyo.GetSize();
		for (j = 0; j < nKishuSagyoNum; j++) {
			SKishuSagyo &cKishuSagyo = cYosoSagyogun.aKishuSagyo[j];
			cKishuSagyo.nSagyoshaId = -1;
		}
	}
}

// 要素作業群追加
void CDataManager::AddYosoSagyogun(int nSublineId, CYosoSagyogunArray &aYosoSagyogun)
{
	SSubline &cSubline = m_aSubline[nSublineId];

	cSubline.aYosoSagyogun.Append(aYosoSagyogun);
}

// 最新移動フラグクリア
void CDataManager::ClearNewMoveFlag()
{
	int i;

	int nSublineNum = m_aSubline.GetSize();
	for (int nSublineId = 0; nSublineId < nSublineNum; nSublineId++) {
		SSubline &cSubline = m_aSubline[nSublineId];
		int nYosoSagyogunNum;
		int nYosoSagyogunId;

		nYosoSagyogunNum = cSubline.aYosoSagyogun.GetSize();
		for (nYosoSagyogunId = 0; nYosoSagyogunId < nYosoSagyogunNum; nYosoSagyogunId++) {
			SYosoSagyogun &cYosoSagyogun = cSubline.aYosoSagyogun[nYosoSagyogunId];
			int nSize = cYosoSagyogun.aKishuSagyo.GetSize();
			for (i = 0; i < nSize; i++)
				cYosoSagyogun.aKishuSagyo[i].bNewMove = FALSE;
		}
	
		nYosoSagyogunNum = cSubline.aYosoSagyogunKit.GetSize();
		for (nYosoSagyogunId = 0; nYosoSagyogunId < nYosoSagyogunNum; nYosoSagyogunId++) {
			SYosoSagyogun &cYosoSagyogun = cSubline.aYosoSagyogunKit[nYosoSagyogunId];
			int nSize = cYosoSagyogun.aKishuSagyo.GetSize();
			for (i = 0; i < nSize; i++)
				cYosoSagyogun.aKishuSagyo[i].bNewMove = FALSE;
		}
	}
}

// 要素作業表に１行追加
int CDataManager::AddYosoSagyoHyo()
{
	int nRow = m_eYosoSagyoHyo.AddRows();
	m_eYosoSagyoHyo.SetDefaultData(nRow);

	return nRow;
}

// --Add 2007/02/26 aizawa 不懸D-187
// 作業Noリナンバ処理
void CDataManager::RenumberSagyoNo()
{
	int nSublineNum = m_aSubline.GetSize();
	for (int nSublineId = 0; nSublineId < nSublineNum; nSublineId++) {
		SSubline &cSubline = m_aSubline[nSublineId];

		int nYosoSagyogunNum = cSubline.aYosoSagyogun.GetSize();
		for (int nYosoSagyogunId = 0; nYosoSagyogunId < nYosoSagyogunNum; nYosoSagyogunId++) {
			SYosoSagyogun &cYosoSagyogun = cSubline.aYosoSagyogun[nYosoSagyogunId];

			int nYosoSagyoNo = 1;
			int nYosoSagyoNum = cYosoSagyogun.aYosoSagyo.GetSize();
			for (int nYosoSagyoId = 0; nYosoSagyoId < nYosoSagyoNum; nYosoSagyoId++)
				cYosoSagyogun.aYosoSagyo[nYosoSagyoId].nSagyoNo = nYosoSagyoNo++;
		}
	}
}
// --Add
