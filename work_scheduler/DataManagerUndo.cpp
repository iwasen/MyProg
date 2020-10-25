//*****************************************************************************************************
//  1. �t�@�C����
//		DataManagerUndo.cpp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		CDataManager �N���X�̃C���v�������e�[�V�����iUndo�֘A�j
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************

#include "stdafx.h"
#include "workscheduler.h"

// Undo�o�b�t�@���Z�b�g
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

// Undo�o�b�t�@�Ƀf�[�^�ۑ�
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

// Undo�Ώۂ̃f�[�^��ۑ�
void CDataManager::SaveUndoData()
{
	SUndoData &cUndoData = m_aUndoBuf.aUndoData[m_aUndoBuf.nIndex];

	cUndoData.nCounter = m_aUndoBuf.nCounter;

	// �T�u���C���f�[�^��ۑ�
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
// --Add 2007/03/02 aizawa �s��D-147
	cUndoData.m_aLineKisei = m_aLineKisei;
// --Add
}

// Undo���\���`�F�b�N
BOOL CDataManager::CheckUndo()
{
	int nIndex = (m_aUndoBuf.nIndex + MAX_UNDO - 1) % MAX_UNDO;
	return m_aUndoBuf.aUndoData[nIndex].nCounter == m_aUndoBuf.nCounter - 1;
}

// Redo���\���`�F�b�N
BOOL CDataManager::CheckRedo()
{
	int nIndex = (m_aUndoBuf.nIndex + 1) % MAX_UNDO;
	return m_aUndoBuf.aUndoData[nIndex].nCounter == m_aUndoBuf.nCounter + 1;
}

// Undo�����s
void CDataManager::Undo(BOOL bRedo)
{
	if (!CheckUndo())
		return;

	m_aUndoBuf.nIndex = (m_aUndoBuf.nIndex + MAX_UNDO - 1) % MAX_UNDO;
	m_aUndoBuf.nCounter--;
	RestoreUndoData();
}

// Redo�����s
void CDataManager::Redo()
{
	if (!CheckRedo())
		return;

	m_aUndoBuf.nIndex = (m_aUndoBuf.nIndex + 1) % MAX_UNDO;
	m_aUndoBuf.nCounter++;
	RestoreUndoData();
}

// Undo�f�[�^�̕���
void CDataManager::RestoreUndoData()
{
	SUndoData &cUndoData = m_aUndoBuf.aUndoData[m_aUndoBuf.nIndex];

	// �T�u���C���f�[�^�𕜌�
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
// --Add 2007/03/02 aizawa �s��D-147
	m_aLineKisei = cUndoData.m_aLineKisei;
// --Add
}
