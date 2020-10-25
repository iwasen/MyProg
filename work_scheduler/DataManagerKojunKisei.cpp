//*****************************************************************************************************
//  1. �t�@�C����
//		DataManagerKojunKisei.cpp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		CDataManager �N���X�̃C���v�������e�[�V�����i�H���K���֘A�j
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************

#include "stdafx.h"
#include "workscheduler.h"

// �G�N�Z���I�[�v�����
struct SExcelOpenInfo {
	UINT nParam;
	CStringArray aPathName;
};

// �H���K���`�F�b�N
// Modify ... ( CHANGE )			�T�u���C�����ύX����Ă��A�ړ�����v�f�̌��݂���T�u���C���ŋK������������iUG1�m�F�ς݁j
BOOL CDataManager::CheckKojunKisei(int nSublineIdFrom, int nSublineId, EKojunCheckType eKojunCheckType)
//BOOL CDataManager::CheckKojunKisei(int nSublineId, EKojunCheckType eKojunCheckType)
// By Y.Itabashi (xxxxx) 2007.02.07
{
	SSubline &cSubline = m_aSubline[nSublineId];
	CSagyojunArray aSagyojun;
	BOOL bNoCheck = FALSE;

	int nKishuNum = m_aKishu.GetSize();

	// ���C���K���`�F�b�N
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
// Modify ... ( CHANGE )			�T�u���C�����ύX����Ă��A�ړ�����v�f�̌��݂���T�u���C���ŋK������������iUG1�m�F�ς݁j
					if (!LineKiseiCheck(nKishuId, nSublineIdFrom, nSublineId, nYosoSagyogunId1, nYosoSagyogunId2, aSagyojun, bNoCheck))
//					if (!LineKiseiCheck(nKishuId, cYosoSagyogun.nMoveSublineId, nSublineId, nYosoSagyogunId1, nYosoSagyogunId2, aSagyojun, bNoCheck))
// By Y.Itabashi (xxxxx) 2007.02.07
						bLineKiseiError = TRUE;
					bCheckFlag = FALSE;
				}
			}
		}
// Modify ... ( CHANGE )			�Ō���Ɉړ�����ۂ��Б��������C���K���`�F�b�N���s��
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
	// �K���`�F�b�N�������b�Z�[�W�i���C���K���j
	if (bNoCheck)
		g_pSystem->DispMessage("I013018");
// By Y.Itabashi (xxxxx) 2007.02.21

	// �^���K���`�F�b�N
	CKojunErrInfoArray aKojunErrInfo;
	int nYosoSagyogunNum = cSubline.aYosoSagyogun.GetSize();
	for (int nYosoSagyogunId = 0; nYosoSagyogunId < nYosoSagyogunNum; nYosoSagyogunId++) {
		SYosoSagyogun &cYosoSagyogun = cSubline.aYosoSagyogun[nYosoSagyogunId];

		for (int nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
			SKishuSagyo &cKishuSagyo = cYosoSagyogun.aKishuSagyo[nKishuId];
			if (cKishuSagyo.bNewMove) {
				GetSagyojun(nSublineId, nKishuId, aSagyojun);

// Modify ... ( CHANGE )			�T�u���C�����ύX����Ă��A�ړ�����v�f�̌��݂���T�u���C���ŋK������������iUG1�m�F�ς݁j
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
	// �K���`�F�b�N�������b�Z�[�W�i�^���K���j
	if (bNoCheck)
		g_pSystem->DispMessage("I013022");
// By Y.Itabashi (xxxxx) 2007.02.21

// Modify ... ( ADD )
	// �H���K���ᔽ�t�@�C��
	SaveKojunKiseiError();
// By Y.Itabashi (xxxxx) 2007.02.14

	return TRUE;
}

// �H���K���`�F�b�N
BOOL CDataManager::CheckKojunKisei(int nSublineId, CIntArrayEx &aYosoSagyogunId, int nKishuId, CSagyojunArray &aSagyojun)
{
	CKojunErrInfoArray aKojunErrInfo;
	int nSize = aYosoSagyogunId.GetSize();

	// ���C���K���`�F�b�N
	BOOL bNoCheck;
	if (!LineKiseiCheck(nKishuId, nSublineId, nSublineId, aYosoSagyogunId[0], aYosoSagyogunId[nSize - 1], aSagyojun, bNoCheck))
		return FALSE;

	// �^���K���`�F�b�N
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
//  1. �֐���
//		CDataManager::CheckLineKisei
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		ENG�p���`�F�b�N
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CString			&sKoteiName			[I] �H����
//		CStringArray	&aKoteiOrder		[I] �H����
//		CString			&sErrMsg			[O] �G���[���b�Z�[�W
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL		TRUE:����CFALSE:ENG�p���G���[�L��
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�w�肳�ꂽ�H�����̒��̍H����ENG�p���ɔ����邩�`�F�b�N����B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
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

	// �T�u���C���ԃ`�F�b�N
	if (nSublineIdFrom != nSublineIdTo) {
		if (m_aKishu[nKishuId].aHonryuSublineId.Find(nSublineIdFrom) == -1 || m_aKishu[nKishuId].aHonryuSublineId.Find(nSublineIdTo) == -1)
			return TRUE;
	}

	// �ŏ��̗v�f��ƌQ�̍�Ə����擾
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
		// �ړ������C���K�����擾
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
			// �ړ���̒��O�̃��C���K�������擾
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

				// ENG�p���̃`�F�b�N
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

	// �Ō�̗v�f��ƌQ�̍�Ə����擾
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
		// �ړ���̒���̃��C���K�������擾
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

				// ENG�p���̃`�F�b�N
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
	// ���O���㗼����NG�Ȃ�G���[
	if (bNG1 && bNG2){
		if( m_nStatus != STATUS_JIDO_HENSEI ){
			cLineError.sRiyuNo = "E013021";
			m_aLineError.Add( cLineError );
		}
		return FALSE;
// Modify ... ( CHANGE )	�擪�ƍŌ���͕Е��̋K�����`�F�b�N�ɕύX
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
//  1. �֐���
//		CDataManager::LineKiseiError
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�w�肳�ꂽ�H���̍H���K�����`�F�b�N
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CString			&sKoteiName			[I] �H����
//		CStringArray	&aKoteiOrder		[I] �H����
//		CString			&sErrMsg			[O] �G���[���b�Z�[�W
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL		TRUE:����CFALSE:�H���K���G���[�L��
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�w�肳�ꂽ�H�����̒��̍H����ENG�p���܂��͍H���K���ɔ����邩�`�F�b�N����B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
BOOL CDataManager::LineKiseiError(EKojunCheckType eKojunCheckType)
{
	switch (eKojunCheckType) {
	case eKojunCheckTypeNoMessage:
		// ���b�Z�[�W����
		return FALSE;
	case eKojunCheckTypeKotei:
		// �H���ړ�
		if (g_pSystem->m_cUserInfo.m_nKojunKiseiIhan == 0) {
			g_pSystem->DispMessage("E013003");
			return FALSE;
		} else {
			if (g_pSystem->DispMessage("W013006") == IDCANCEL)
				return FALSE;
		}
		break;
	case eKojunCheckTypeYosoSagyogun:
		// �v�f��ƌQ�ړ�
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
//  1. �֐���
//		CDataManager::CheckKatashikiKisei
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�H���K���`�F�b�N
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CString			&sKoteiName			[I] �H����
//		CStringArray	&aKoteiOrder		[I] �H����
//		CString			&sErrMsg			[O] �G���[���b�Z�[�W
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL		TRUE:����CFALSE:�H���K���G���[�L��
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�w�肳�ꂽ�H�����̒��̍H�����H���K���ɔ����邩�`�F�b�N����B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
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

	// �Y���H���̈ʒu���擾
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
			// �w��v�f��ƌQ�̍H���ɑ��݂���v�f��ƌQ���J�E���g
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
	// �ړ����ƈړ��悪�{�����̔���
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

			// �e�T�u���C���̃X�^�[�g�ƃG���h�̍�Ə��Ԃ��`�F�b�N����
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

		// �G���[����
		switch (eKojunCheckType) {
		case eKojunCheckTypeNoMessage:
			// ���b�Z�[�W����
			return FALSE;
		case eKojunCheckTypeKotei:
			// �H���ړ�
			switch (cKojunErrInfo.nErrCode) {
			case eKojunErrKatashikiJoryuNG:
				if (aKoteiNameJoryu.Find(cKojunErrInfo.sErrorKoteiName) == -1) {
					// �㗬�s��
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
					// �����s��
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
			// �v�f��ƌQ�ړ�
			switch (cKojunErrInfo.nErrCode) {
			case eKojunErrKatashikiJoryuNG:
				// �㗬�s��
				if (g_pSystem->m_cUserInfo.m_nKojunKiseiIhan == 0) {
					g_pSystem->DispMessage("E013011", cKojunErrInfo.sTargetYosoSagyogunName, cKojunErrInfo.sErrorYosoSagyogunName);
					return FALSE;
				} else {
					if (g_pSystem->DispMessage("W013015", cKojunErrInfo.sTargetYosoSagyogunName, cKojunErrInfo.sErrorYosoSagyogunName) == IDCANCEL)
						return FALSE;
				}
				break;
			case eKojunErrKatashikiKaryuNG:
				// �����s��
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
//  1. �֐���
//		CDataManager::FindLineKisei
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�H���K������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CString			&sKoteiName			[I] �H����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		SKojunKisei*	�H���K���f�[�^�|�C���^
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�w�肳�ꂽ�H�������H���K���f�[�^���猟�����A���������炻�̃|�C���^��Ԃ��B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
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

			// �{���łȂ��ꍇ�A���̃V�[�g���Ō�������
			if( m_aKishu[i].aHonryuSublineId.Find(nSublineId) == -1 ){
				sKey = sKishuName + m_aSubline[nSublineId].sSublineName + sKoteiName + RemoveUnderbarDigit(sYosoSagyogunName);
				break;
			}

			// �{���̏ꍇ�A���݂̃T�u���C�����猟��
			sKey = sKishuName + m_aSubline[nSublineId].sSublineName + sKoteiName + RemoveUnderbarDigit(sYosoSagyogunName);
			SKatashikiKisei *pKisei = m_aKatashikiKisei.LookupElement( sKey );
			if( pKisei )				return pKisei;

			// ���݂̃T�u���C�����猟�o�ł��Ȃ������ꍇ
			for( int j = 0; j < m_aKishu[i].aHonryuSublineId.GetSize(); j++ ){

				if( m_aKishu[i].aHonryuSublineId[j] == nSublineId )
					continue;

				sKey = sKishuName + m_aSubline[j].sSublineName + sKoteiName + RemoveUnderbarDigit(sYosoSagyogunName);
				pKisei = m_aKatashikiKisei.LookupElement( sKey );
				if( pKisei )				return pKisei;

			}
		}
	}

	// �K���̎擾�̍ہA�H���ԍ��͖�������
	return m_aKatashikiKisei.LookupElement( sKey );
//	return m_aKatashikiKisei.LookupElement(sKishuName + m_aSubline[nSublineId].sSublineName + sKoteiNo + sKoteiName + RemoveUnderbarDigit(sYosoSagyogunName));
// By Y.Itabashi (xxxxx) 2007.02.10
}

//*****************************************************************************************************
//  1. �֐���
//		CDataManager::FindKojunKiseiCol
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�H���K���}�g���N�X�擾
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		SKojunKisei		*pKojunKisei		[I] �H���K���f�[�^�|�C���^
//		CString			&sKoteiName			[I] �H����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		SKojunKisei*	�H���K���f�[�^�|�C���^
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�w�肳�ꂽ�H�������H���K���}�g���N�X���猟�����A���������炻�̃|�C���^��Ԃ��B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
SKatashikiKiseiCol *CDataManager::FindKatashikiKiseiCol(SKatashikiKisei *pKatashikiKisei, const CString &sKishuName, int nSublineId, const CString &sKoteiNo, const CString &sKoteiName, const CString &sYosoSagyogunName)
{
// Modify ... ( CHANGE )
	CString sKey;
	for( int i = 0; i < m_aKishu.GetSize(); i++ ){
		if( m_aKishu[i].sKishuName == sKishuName ){

			// �{���łȂ��ꍇ�A���̃V�[�g���Ō�������
			if( m_aKishu[i].aHonryuSublineId.Find(nSublineId) == -1 ){
				sKey = sKishuName + m_aSubline[nSublineId].sSublineName + sKoteiName + RemoveUnderbarDigit(sYosoSagyogunName);
				break;
			}

			// �{���̏ꍇ�A���݂̃T�u���C�����猟��
			sKey = sKishuName + m_aSubline[nSublineId].sSublineName + sKoteiName + RemoveUnderbarDigit(sYosoSagyogunName);
			SKatashikiKiseiCol *pCol = pKatashikiKisei->aKatashikiKiseiCol.LookupElement( sKey );
			if( pCol )				return pCol;

			// ���݂̃T�u���C�����猟�o�ł��Ȃ������ꍇ
			for( int j = 0; j < m_aKishu[i].aHonryuSublineId.GetSize(); j++ ){

				if( m_aKishu[i].aHonryuSublineId[j] == nSublineId )
					continue;

				sKey = sKishuName + m_aSubline[j].sSublineName + sKoteiName + RemoveUnderbarDigit(sYosoSagyogunName);
				pCol = pKatashikiKisei->aKatashikiKiseiCol.LookupElement( sKey );
				if( pCol )				return pCol;

			}
		}
	}

	// �K���̎擾�̍ہA�H���ԍ��͖�������
	return pKatashikiKisei->aKatashikiKiseiCol.LookupElement( sKey );
//	return pKatashikiKisei->aKatashikiKiseiCol.LookupElement(sKishuName + m_aSubline[nSublineId].sSublineName + sKoteiNo + sKoteiName + RemoveUnderbarDigit(sYosoSagyogunName));
// By Y.Itabashi (xxxxx) 2007.02.10
}

// ���C���K���}�g���N�X�t�@�C�����J��
void CDataManager::OpenLineKiseiMatrix()
{
// --Add 2007/03/02 aizawa �s��D-147
	// ���C���K���}�g���N�X�t�@�C�����J���O�ɕۑ�
	SaveLineKiseiMatrix(NULL, "E003002");
// --Add

	SExcelOpenInfo *pExcelOpenInfo = new SExcelOpenInfo;
	pExcelOpenInfo->nParam = EXCEL_LINE_KISEI_MATRIX;
	pExcelOpenInfo->aPathName.Add(m_eLineKiseiMatrix.m_sPathName);

	AfxBeginThread(OpenExcelThread, pExcelOpenInfo);
}

// �^���K���}�g���N�X�t�@�C�����J��
void CDataManager::OpenKatashikiKiseiMatrix()
{
	SExcelOpenInfo *pExcelOpenInfo = new SExcelOpenInfo;
	pExcelOpenInfo->nParam = EXCEL_KATASHIKI_KISEI_MATRIX;

	// �^���K���}�g���N�X�t�@�C���͕�������̂ŁA�I�����ꂽ�t�@�C�����擾����
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

// �ʃX���b�h�ŃG�N�Z�����N������
UINT CDataManager::OpenExcelThread(LPVOID pParam)
{
	::OleInitialize(NULL);

	SExcelOpenInfo *pExcelOpenInfo = (SExcelOpenInfo *)pParam;

	// ���C���X���b�h�ɃI�[�v���ʒm
	g_pSystem->m_pMainDlg->SendMessage(WM_OPEN_EXCEL, pExcelOpenInfo->nParam);

	// �G�N�Z�����J��
	CExcelLib cExcelLib;
	if (cExcelLib.Exec(pExcelOpenInfo->aPathName)) {
		// ���C���X���b�h�ɃN���[�Y�ʒm
		g_pSystem->m_pMainDlg->PostMessage(WM_CLOSE_EXCEL, pExcelOpenInfo->nParam);
	} else {
		// ���C���X���b�h�ɃG���[�ʒm
		g_pSystem->m_pMainDlg->PostMessage(WM_ERROR_EXCEL, pExcelOpenInfo->nParam);
	}

	delete pExcelOpenInfo;

	::OleUninitialize();

	return 0;
}

// �G�N�Z���I�[�v���ʒm����
void CDataManager::OpenExcelNotify(UINT nParam)
{
	switch (nParam) {
	case EXCEL_LINE_KISEI_MATRIX:
		// ���C���K���}�g���N�X�t�@�C���I�[�v�����t���O�Z�b�g
		m_bOpenLineKiseiMatrix = TRUE;

		// �t�@�C�����b�N����
		m_eLineKiseiMatrix.FileUnlock();
		break;
	case EXCEL_KATASHIKI_KISEI_MATRIX:
		// �^���K���}�g���N�X�t�@�C���I�[�v�����t���O�Z�b�g
		m_bOpenKatashikiKiseiMatrix = TRUE;

		// �t�@�C�����b�N����
		for (int i = 0; i < m_aKatashiki.GetSize(); i++)
			m_aKatashiki[i].eKatashikiKiseiMatrix.FileUnlock();
		break;
	}
}

// �G�N�Z���N���[�Y�ʒm����
void CDataManager::CloseExcelNotify(UINT nParam)
{
	CWaitCursor cWaitCursor;

	switch (nParam) {
	case EXCEL_LINE_KISEI_MATRIX:
		// ���C���K���}�g���N�X�t�@�C���I�[�v�����t���O�N���A
		m_bOpenLineKiseiMatrix = FALSE;

		// �t�@�C�����b�N
		m_eLineKiseiMatrix.FileLock();

		// ���C���K���}�g���N�X�t�@�C���ǂݍ���
		ReadLineKiseiMatrix();
		break;
	case EXCEL_KATASHIKI_KISEI_MATRIX:
		// �^���K���}�g���N�X�t�@�C���I�[�v�����t���O�N���A
		m_bOpenKatashikiKiseiMatrix = FALSE;

		// �t�@�C�����b�N
		for (int i = 0; i < m_aKatashiki.GetSize(); i++)
			m_aKatashiki[i].eKatashikiKiseiMatrix.FileLock();

		// �^���K���}�g���N�X�t�@�C���ǂݍ���
		ReadKatashikiKiseiMatrix();
		break;
	}
}

// �G�N�Z���N���[�Y�ʒm����
void CDataManager::ErrorExcelNotify(UINT nParam)
{
	CWaitCursor cWaitCursor;

	switch (nParam) {
	case EXCEL_LINE_KISEI_MATRIX:
		// ���C���K���}�g���N�X�t�@�C���I�[�v�����t���O�N���A
		m_bOpenLineKiseiMatrix = FALSE;

		// �t�@�C�����b�N
		m_eLineKiseiMatrix.FileLock();

		// �G���[���b�Z�[�W
		g_pSystem->DispMessage("E006001");
		break;
	case EXCEL_KATASHIKI_KISEI_MATRIX:
		// �^���K���}�g���N�X�t�@�C���I�[�v�����t���O�N���A
		m_bOpenKatashikiKiseiMatrix = FALSE;

		// �t�@�C�����b�N
		for (int i = 0; i < m_aKatashiki.GetSize(); i++)
			m_aKatashiki[i].eKatashikiKiseiMatrix.FileLock();

		// �G���[���b�Z�[�W
		g_pSystem->DispMessage("E006101");
		break;
	}
}

// �H��/�v�f��ƌQ�̈ړ����s�O�̈ʒu��ݒ�
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

// �H��/�v�f��ƌQ�̈ړ����s�O�̈ʒu���擾
int CDataManager::GetMoveBeforeSagyojun(int nSublineId, CString sKoteiName, CString sYosoSagyogunName, int nKishuId)
{
	SSubline &cSubline = m_aSubline[nSublineId];

	CString sKey = cSubline.sSublineName+m_aKishu[nKishuId].sKishuName+sKoteiName+sYosoSagyogunName;
	int *nBeforeSagyojun = aBeforeMoveSagyojun.LookupElement( sKey );

	if( nBeforeSagyojun == NULL )
		return -1;

	return *nBeforeSagyojun;
}

// �H��/�v�f��ƌQ�̈ړ����s�O�̈ʒu��������
void CDataManager::InitMoveBeforeSagyojun()
{
	aBeforeMoveSagyojun.RemoveAll();
}

// --Add 2007/03/02 aizawa �s��D-147
// �T�u���C���Ԉړ��ɂ�胉�C���K���}�g���N�X�̃T�u���C�����X�V
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

// ���C���K���}�g���N�X�̃T�u���C����ύX
void CDataManager::ChangeLineKiseiSubline(int nSublineIdFrom, int nSublineIdTo, const CString &sKoteiNo, const CString &sKoteiName, const CString &sYosoSagyogunName)
{
	// ���C���K���}�g���N�X������
	SLineKisei *pLineKisei = FindLineKisei(nSublineIdFrom, sKoteiNo, sKoteiName, sYosoSagyogunName);
	if (pLineKisei != NULL) {
		// ����������T�u���C����������������
		pLineKisei->sSublineName = m_aSubline[nSublineIdTo].sSublineName;

		// �����L�[��ύX����
		CString sKeyOld = m_aSubline[nSublineIdFrom].sSublineName + sKoteiNo + sKoteiName + RemoveUnderbarDigit(sYosoSagyogunName);
		CString sKeyNew = m_aSubline[nSublineIdTo].sSublineName + sKoteiNo + sKoteiName + RemoveUnderbarDigit(sYosoSagyogunName);
		m_aLineKisei.ChangeKey(sKeyOld, sKeyNew);
	}
}
// --Add
