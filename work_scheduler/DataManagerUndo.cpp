//*****************************************************************************************************
//  1. ファイル名
//		DataManagerUndo.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		CDataManager クラスのインプリメンテーション（Undo関連）
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************

#include "stdafx.h"
#include "workscheduler.h"

// Undoバッファリセット
void CDataManager::ResetUndoBuf()
{
	m_aUndoBuf.nIndex = 0;
	m_aUndoBuf.nCounter = 1;

	for (int i = 0; i < MAX_UNDO; i++) {
		SUndoData &cUndoData = m_aUndoBuf.aUndoData[i];
		cUndoData.nCounter = -1;
		cUndoData.aUndoSubline.RemoveAll();
	}

	SaveUndoData();
}

// Undoバッファにデータ保存
void CDataManager::SaveUndoBuf()
{
	m_aUndoBuf.nIndex = (m_aUndoBuf.nIndex + 1) % MAX_UNDO;
	m_aUndoBuf.nCounter++;

	SaveUndoData();

	for (int i = 0; i < MAX_UNDO; i++) {
		if (m_aUndoBuf.aUndoData[i].nCounter > m_aUndoBuf.nCounter)
			m_aUndoBuf.aUndoData[i].nCounter = -1;
	}
}

// Undo対象のデータを保存
void CDataManager::SaveUndoData()
{
	SUndoData &cUndoData = m_aUndoBuf.aUndoData[m_aUndoBuf.nIndex];

	cUndoData.nCounter = m_aUndoBuf.nCounter;

	// サブラインデータを保存
	cUndoData.aUndoSubline.RemoveAll();
	int nSublineNum = m_aSubline.GetSize();
	for (int nSublineId = 0; nSublineId < nSublineNum; nSublineId++) {
		SUndoData &cUndoData = m_aUndoBuf.aUndoData[m_aUndoBuf.nIndex];
		SSubline &cSubline = m_aSubline[nSublineId];

		SUndoSubline *pUndoSubline = cUndoData.aUndoSubline.AddElement();
		pUndoSubline->aSagyosha = cSubline.aSagyosha;
		pUndoSubline->aYosoSagyogun = cSubline.aYosoSagyogun;
		pUndoSubline->aYosoSagyogunKit = cSubline.aYosoSagyogunKit;
		pUndoSubline->aBuhinKitting = cSubline.aBuhinKitting;
		pUndoSubline->aCheckBuhin = cSubline.aCheckBuhin;
// Modify ... ( ADD )
		pUndoSubline->aCheckKotei = cSubline.aCheckKotei;
		pUndoSubline->aCheckYosoSagyogun = cSubline.aCheckYosoSagyogun;
// By Y.Itabashi (xxxxx) 2007.01.29
	}

	cUndoData.aKittingSagyosha = m_aKittingSagyosha;
// --Add 2007/03/02 aizawa 不懸D-147
	cUndoData.m_aLineKisei = m_aLineKisei;
// --Add
}

// Undoが可能かチェック
BOOL CDataManager::CheckUndo()
{
	int nIndex = (m_aUndoBuf.nIndex + MAX_UNDO - 1) % MAX_UNDO;
	return m_aUndoBuf.aUndoData[nIndex].nCounter == m_aUndoBuf.nCounter - 1;
}

// Redoが可能かチェック
BOOL CDataManager::CheckRedo()
{
	int nIndex = (m_aUndoBuf.nIndex + 1) % MAX_UNDO;
	return m_aUndoBuf.aUndoData[nIndex].nCounter == m_aUndoBuf.nCounter + 1;
}

// Undoを実行
void CDataManager::Undo(BOOL bRedo)
{
	if (!CheckUndo())
		return;

	m_aUndoBuf.nIndex = (m_aUndoBuf.nIndex + MAX_UNDO - 1) % MAX_UNDO;
	m_aUndoBuf.nCounter--;
	RestoreUndoData();
}

// Redoを実行
void CDataManager::Redo()
{
	if (!CheckRedo())
		return;

	m_aUndoBuf.nIndex = (m_aUndoBuf.nIndex + 1) % MAX_UNDO;
	m_aUndoBuf.nCounter++;
	RestoreUndoData();
}

// Undoデータの復元
void CDataManager::RestoreUndoData()
{
	SUndoData &cUndoData = m_aUndoBuf.aUndoData[m_aUndoBuf.nIndex];

	// サブラインデータを復元
	int nSublineNum = m_aSubline.GetSize();
	for (int nSublineId = 0; nSublineId < nSublineNum; nSublineId++) {
		SUndoSubline &cUndoSubline = cUndoData.aUndoSubline[nSublineId];
		SSubline &cSubline = m_aSubline[nSublineId];
		cSubline.aSagyosha = cUndoSubline.aSagyosha;
		cSubline.aYosoSagyogun = cUndoSubline.aYosoSagyogun;
		cSubline.aYosoSagyogunKit = cUndoSubline.aYosoSagyogunKit;
		cSubline.aBuhinKitting = cUndoSubline.aBuhinKitting;
		cSubline.aCheckBuhin = cUndoSubline.aCheckBuhin;
// Modify ... ( ADD )
		cSubline.aCheckKotei = cUndoSubline.aCheckKotei;
		cSubline.aCheckYosoSagyogun = cUndoSubline.aCheckYosoSagyogun;
// By Y.Itabashi (xxxxx) 2007.01.29
	}

	m_aKittingSagyosha = cUndoData.aKittingSagyosha;
// --Add 2007/03/02 aizawa 不懸D-147
	m_aLineKisei = cUndoData.m_aLineKisei;
// --Add
}
