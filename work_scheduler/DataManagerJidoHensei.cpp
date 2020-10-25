//*****************************************************************************************************
//  1. �t�@�C����
//		DataManagerJidoHensei.cpp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		CDataManager �N���X�̃C���v�������e�[�V�����i�����Ґ��֘A�j
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************

#include "stdafx.h"
#include "workscheduler.h"

//*****************************************************************************************************
//  1. �֐���
//		CDataManager::JidoHensei
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�����Ґ�����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		int		nHenseiMode				[I] �Ґ����[�h�i0:�H������ւ������A1:�H������ւ��L��j
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�����Ґ����������s����B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
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
// --Change 2007/03/01 aizawa �s��D-198
//	g_pSystem->SetProgressBar(nSize * nKishuNum * (nHenseiMode == 0 ? 2 : 3));
	g_pSystem->SetProgressBar(nSize * nKishuNum * 2 + (nHenseiMode == 0 ? 0 : nSize));
// --Change

	for (int i = 0; i < nSize; i++)
// --Change 2007/03/01 aizawa �s��D-198
//		JidoHenseiSub(aSublineId[i], nHenseiMode, nTactKeisan, nKijunKishuId);
		JidoHenseiSub(aSublineId[i], nHenseiMode, nTactKeisan, nKijunKishuId, i + 1, nSize);
// --Change

	// �L�b�g����Ƃ̎����Ґ�
	JidoHensei1Kitting(aSublineId);

	g_pSystem->ClearProgressBar();

	// �f�[�^�X�V�t���O�ݒ�
	m_bUpdate = TRUE;

// Modify ... ( ADD )
	m_nStatus = STATUS_SHUDO_HENSEI;
// By Y.Itabashi (xxxxx) 2007.03.06
}

	// �L�b�g���ȊO�̎����Ґ�
// --Change 2007/03/01 aizawa �s��D-198
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

	// �����̍�Ǝ҂��폜
	aSagyosha.RemoveAll();

	// �����̍�ƎҊ��t������
	int nYosoSagyogun = aYosoSagyogun.GetSize();
	for (nYosoSagyogunId = 0; nYosoSagyogunId < nYosoSagyogun; nYosoSagyogunId++) {
		SYosoSagyogun &cYosoSagyogun = aYosoSagyogun[nYosoSagyogunId];
		for (nKishuId = 0; nKishuId < nKishuNum; nKishuId++)
			cYosoSagyogun.aKishuSagyo[nKishuId].nSagyoshaId = -1;
	}

	// �^�N�g�؂�E��ƎҊ��t
	JidoHensei1(nSublineId, nTactKeisan, nKijunKishuId, nMaxSagyoshaId);

	// ��Ǝ҂���Ə��ɕ��ёւ�
	RenumberSagyoshaId(nSublineId);

	// �[���x�A�b�v
	if (nHenseiMode == 1) {
// --Add 2007/03/01 aizawa �s��D-198
		// �X�e�[�^�X�o�[�A�v���O���X�o�[�X�V
		DispProgressBarSubline(nSublineId, g_pSystem->m_cMenuDisp.GetMenu("D005001", "S001015"), nProcessSublixxxnt, nProcessSublineNum);
// --Add
		JidoHensei2(nSublineId, nTactKeisan);

		// ��Ǝ҂���Ə��ɕ��ёւ�
		RenumberSagyoshaId(nSublineId);
	}

	// ��ƎҖ���U�蒼��
	RenameSagyoshaName(nSublineId);

	// ��ƎҊ��t�ς݃t���O�ݒ�
	cSubline.bSagyoshaWaritsuke = TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CDataManager::JidoHensei1
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�^�N�g�؂菈��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		BOOL	bKitting				[I] �L�b�g���H���t���O�iFALSE:�ʏ�H���ATRUE:�L�b�g���H���j
//		int		&nMaxSagyoshaId			[I/O] �ő��Ǝ�ID
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�H�������ւ����Ƀ^�N�g�؂���s���B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CDataManager::JidoHensei1(int nSublineId, int nTactKeisan, int nKijunKishuId, int &nMaxSagyoshaId)
{
	SSubline &cSubline = m_aSubline[nSublineId];
	CKoteiArray aKotei;
	CKoteiWaritsukeArray cKoteiWaritsukeArray;
	int nMaxSagyoshaId2 = nMaxSagyoshaId;

	// ��@��
	int nProcessCount = 0;
// --Change 2007/03/01 aizawa �s��D-198
//	DispProgressBar(nKijunKishuId, "�^�N�g�؂�", ++nProcessCount);
	DispProgressBar(nKijunKishuId, g_pSystem->m_cMenuDisp.GetMenu("D005001", "S001013"), ++nProcessCount);
// --Change
	GetKoteiArray(nSublineId, nKijunKishuId, aKotei);
// --Change 2007/03/01 aizawa �s��D-198
//	DispProgressBar(nKijunKishuId, "��ƎҊ��t", nProcessCount);
	DispProgressBar(nKijunKishuId, g_pSystem->m_cMenuDisp.GetMenu("D005001", "S001014"), nProcessCount);
// --Change
	JidoHensei1Sub(nSublineId, nMaxSagyoshaId, nMaxSagyoshaId2, nKijunKishuId, aKotei, cKoteiWaritsukeArray, FALSE, TRUE, TRUE, nTactKeisan);

	// ���̑��̋@��
	int nKishuNum = m_aKishu.GetSize();
	for (int nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
		if (nKishuId != nKijunKishuId) {
// --Change 2007/03/01 aizawa �s��D-198
//			DispProgressBar(nKishuId, "�^�N�g�؂�", ++nProcessCount);
			DispProgressBar(nKishuId, g_pSystem->m_cMenuDisp.GetMenu("D005001", "S001013"), ++nProcessCount);
// --Change

			// �H�����ɕ��ёւ�
			GetKoteiArray(nSublineId, nKishuId, aKotei);

// --Change 2007/03/01 aizawa �s��D-198
//			DispProgressBar(nKishuId, "��ƎҊ��t", nProcessCount);
			DispProgressBar(nKishuId, g_pSystem->m_cMenuDisp.GetMenu("D005001", "S001014"), nProcessCount);
// --Change

			JidoHensei1Sub(nSublineId, nMaxSagyoshaId, nMaxSagyoshaId2, nKishuId, aKotei, cKoteiWaritsukeArray, FALSE, FALSE, FALSE, nTactKeisan);
			JidoHensei1Sub(nSublineId, nMaxSagyoshaId, nMaxSagyoshaId2, nKishuId, aKotei, cKoteiWaritsukeArray, TRUE, FALSE, FALSE, nTactKeisan);
			JidoHensei1Sub(nSublineId, nMaxSagyoshaId, nMaxSagyoshaId2, nKishuId, aKotei, cKoteiWaritsukeArray, FALSE, TRUE, FALSE, nTactKeisan);
		}
	}

	// �P�ƍH����Ǝ҂𓝍�
	while (JidoHensei1Sub2(nSublineId))
		;
}

//*****************************************************************************************************
//  1. �֐���
//		CDataManager::JidoHensei1
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�^�N�g�؂菈��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		BOOL					bKitting				[I] �L�b�g���H���t���O�iFALSE:�ʏ�H���ATRUE:�L�b�g���H���j
//		int						&nMaxSagyoshaId			[I/O] �ő��Ǝ�ID
//		int						nMaxSagyoshaId2			[I] �O��ő��Ǝ�ID
//		int						nKishuId				[I] �@��ID
//		CKoteiArray				&aKotei					[I] �H���z��f�[�^
//		CKoteiWaritsukeArray	&cKoteiWaritsukeArray	[I] ���t�z��f�[�^
//		BOOL					bReverse				[I] �t�������t�t���O
//		BOOL					bNewWaritsuke			[I] �V�K��ƎҊ��t�t���O
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		��Ǝ��Ԃ�ݐς��A�ڕW�^�N�g�ɒB����܂ł̍�Ƃ���Ǝ҂Ɋ���t����B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
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

	// �H�����Ƃ̊��t
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

		// ���ʍH�����`�F�b�N
		SKoteiWaritsuke *pKoteiWaritsuke = NULL;
		int nYosoSagyogunNum = cKotei.aYosoSagyogunId.GetSize();
		for (nYosoSagyogunId = 0; nYosoSagyogunId < nYosoSagyogunNum; nYosoSagyogunId++) {
			SYosoSagyogun &cYosoSagyogun = aYosoSagyogun[cKotei.aYosoSagyogunId[nYosoSagyogunId]];
			sKey.Format("%d", cYosoSagyogun.aKishuSagyo[nKishuId].nGroupNo);
			pKoteiWaritsuke = cKoteiWaritsukeArray.LookupElement(sKey);
			if (pKoteiWaritsuke != NULL)
				break;
		}

		// ���Ɋ��t�ς݂̍H�����`�F�b�N
		if (pKoteiWaritsuke != NULL) {
			// ���ʍH���͓�����Ǝ҂Ɋ���t����
			nSagyoshaId = pKoteiWaritsuke->nSagyoshaId;
		} else {
			// ��Ǝ҃`�F���W
			if (bChange || (bJidoKotei && aYosoSagyogun[cKotei.aYosoSagyogunId[0]].sJidoKotei != g_pConst->m_sJido) || (cKotei.fTime != 0 && GetSagyoJikan(nSublineId, nSagyoshaId, nKishuId) + cKotei.fTime > cSubline.cSeisanJoken.fTact)) {
				if (bNewWaritsuke) {
					nSagyoshaId = ++nMaxSagyoshaId;

					// ��ƎҖ���ݒ�
					sSagyoshaName.Format("%s%03d", cSubline.sSagyoshaName, nSagyoshaId - nMaxSagyoshaId2);
					aSagyosha.Add(sSagyoshaName);
				} else
					nSagyoshaId = -1;
			}
		}
		bChange = FALSE;

		// �v�f��ƌQ�ɍ�Ǝ҂����t
		if (nSagyoshaId != -1) {
			bJidoKotei = FALSE;
			for (nYosoSagyogunId = 0; nYosoSagyogunId < nYosoSagyogunNum; nYosoSagyogunId++) {
				SYosoSagyogun &cYosoSagyogun = aYosoSagyogun[cKotei.aYosoSagyogunId[nYosoSagyogunId]];
				if (cYosoSagyogun.aKishuSagyo[nKishuId].nSagyoshaId == -1) {
					// ��Ǝ�ID�ݒ�
					cYosoSagyogun.aKishuSagyo[nKishuId].nSagyoshaId = nSagyoshaId;

					// ���ʍH���Z�b�g
					sKey.Format("%d", cYosoSagyogun.aKishuSagyo[nKishuId].nGroupNo);
					pKoteiWaritsuke = cKoteiWaritsukeArray.LookupElement(sKey);
					if (pKoteiWaritsuke == NULL) {
						pKoteiWaritsuke = cKoteiWaritsukeArray.AddElement(sKey);
						pKoteiWaritsuke->nSagyoshaId = nSagyoshaId;
						pKoteiWaritsuke->nGroupNo = cYosoSagyogun.aKishuSagyo[nKishuId].nGroupNo;
					}
				}

				// �����H�����������Ǝ҃`�F���W
				if (nTactKeisan == 1 && cYosoSagyogun.sJidoKotei == g_pConst->m_sJido)
					bJidoKotei = TRUE;
			}
		}
	}
}

struct SKishuTime {
	double fTime[MAX_KISHU];
};

// �L�b�g����Ƃ̎����Ґ�
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

		// �����̍�ƎҊ��t������
		int nYosoSagyogun = cSubline.aYosoSagyogunKit.GetSize();
		for (nYosoSagyogunId = 0; nYosoSagyogunId < nYosoSagyogun; nYosoSagyogunId++) {
			SYosoSagyogun &cYosoSagyogun = cSubline.aYosoSagyogunKit[nYosoSagyogunId];
			for (nKishuId = 0; nKishuId < nKishuNum; nKishuId++)
				cYosoSagyogun.aKishuSagyo[nKishuId].nSagyoshaId = -1;
		}
	}

	// �L�b�g���H���̃^�N�g���擾
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

							// ��ƎҖ���ݒ�
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

// �P�ƍH����Ǝ҂𓝍�
BOOL CDataManager::JidoHensei1Sub2(int nSublineId)
{
	SSubline &cSubline = m_aSubline[nSublineId];
	CYosoSagyogunArray &aYosoSagyogun = cSubline.aYosoSagyogun;
	BOOL bSagyoshaKishu[MAX_KISHU];
	BOOL bMargeKishu[MAX_KISHU];
	int nMargeKishuId;
	int nMargeSagyoshaId = -1;
	BOOL bMarge = FALSE;

	// ��Ǝ҂���Ə��ɕ��ёւ�
	RenumberSagyoshaId(nSublineId);

	// �@�퐔�擾
	int nKishuNum = m_aKishu.GetSize();

	// �v�f��ƌQ���擾
	int nYosoSagyogunNum = aYosoSagyogun.GetSize();

	// �P�ƍH���݂̂̍�Ƃ𓝍�����
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
					// ��Ǝ�ID����������
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
//  1. �֐���
//		CDataManager::JidoHensei2
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�[���x�A�b�v����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�H���K���ɔ����Ȃ������ōH�������ւ��āA��Ǝ��Ԃɗ]�T�̂����Ǝ҂ɍH��������t�������B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
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

	// ��Ǝ҂ɂ�郋�[�v
	int nSagyoshaNum = cSubline.aSagyosha.GetSize();
	for (nSagyoshaId = 0; nSagyoshaId < nSagyoshaNum; nSagyoshaId++) {
		// �ŏI�����H���N���A
		memset(bLastJidoKotei, 0, sizeof(bLastJidoKotei));

// Modify ... ( CHANGE )
		// ���݃`�F�b�N���Ă����Ǝ҂Ɋ���t����ꂽ�H�����Ȃ��ꍇ�́A�[���xUP���s��Ȃ�
		// ����t���ς݂̍H��������
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

		// �H���ɂ�郋�[�v
		for (nKoteiId = 0; nKoteiId < nHenseiKoteiNum; nKoteiId++) {
			SHenseiKotei &cHenseiKotei = aHenseiKotei[nKoteiId];

			// �����ςłȂ�����
// Modify ... ( CHANGE )
//			if (cHenseiKotei.nSagyoshaId <= nSagyoshaId || aKyotsuKoteiId.Find(nKoteiId) != -1)
			if (!cHenseiKotei.bMovable || aKyotsuKoteiId.Find(nKoteiId) != -1)
// By Y.Itabashi (xxxxx) 2007.03.07
				continue;

			// ���ʍH����T��
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
// --Delete 2007/02/26 aizawa �s��D-194
//								break;
// --Delete
							}
						}
					}
				}
			}

			// �ړ��ł��Ȃ��������ɖ߂����ߕۑ�
			aHenseiKoteiSave.Copy(aHenseiKotei);
			aSagyojunSave.Copy(aSagyojun);

			BOOL bOK = TRUE;
			int nHenseiKoteiIdNum = aHenseiKoteiId.GetSize();
			for (i = 0; i < nHenseiKoteiIdNum; i++) {
				SHenseiKotei &cHenseiKotei2 = aHenseiKotei[aHenseiKoteiId[i]];
				int nKishuId = cHenseiKotei2.nKishuId;

				// �����ςłȂ�����
				if (!cHenseiKotei2.bMovable) {
					bOK = FALSE;
					break;
				}

				// ������H���D��ŋ�؂飂�I�����Ă���ꍇ�ŁA��Ǝ҂̍Ōオ�����H���̏ꍇ�͏[���xUP�����Ȃ�
				if (nTactKeisan == 1 && bLastJidoKotei[cHenseiKotei2.nKishuId]) {
					bOK = FALSE;
					break;
				}

// --Add 2007/02/26 aizawa �s��D-193
				// ������H���D��ŋ�؂飂�I�����Ă���ꍇ�͎����H�����ړ������Ȃ�
				if (nTactKeisan == 1 && cHenseiKotei2.bJido) {
					bOK = FALSE;
					break;
				}
// --Add

				// ��Ǝ��Ԃ��擾
				GetHenseiTime(nSublineId, aHenseiKotei, nSagyoshaId, cHenseiTime);

				// ���d���ς��ڕW�^�N�g�𒴂��Ȃ�����
// Modify ... ( DELETE )
/*				if (cHenseiTime.fKajuHeikinTime + cHenseiKotei2.fKajuHeikinTime > cSubline.cSeisanJoken.fTact) {
					bOK = FALSE;
					break;
				}*/
// By Y.Itabashi (xxxxx) 2007.03.06

				// �@��ʂ̍�Ǝ��Ԃ�����܂��͖ڕW�^�N�g�𒴂��Ȃ�����
				if (cHenseiTime.aTime[nKishuId] + cHenseiKotei2.fTime > fLimit) {
					bOK = FALSE;
					break;
				}

				// �ړ�����擾
				nLastSagyoJun = 0;
				for (j = 0; j < nSagyojunNum; j++) {
// --Change 2007/02/26 aizawa �s��D-194,D-195
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

				// �H���ړ�
				int nSize = cHenseiKotei2.aSagyojun.GetSize();
				for (j = 0; j < nSize; j++) {
					int nSagyojun = cHenseiKotei2.aSagyojun[j];
					for (k = 0; k < nSagyojunNum; k++) {
						if (aSagyojun[k].nIndex == nSagyojun) {

// Modify ... ( ADD )
							CString sKoteiName = m_aSubline[nSublineId].aYosoSagyogun[aSagyojun[k].nYosoSagyogunId].sKoteiName;
							CString sYosoSagyogunName = m_aSubline[nSublineId].aYosoSagyogun[aSagyojun[k].nYosoSagyogunId].sYosoSagyogunName;

							// �ړ��O�̈ʒu��ݒ�
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

				// �H���K���`�F�b�N
				if (!CheckKojunKisei(nSublineId, cHenseiKotei2.aYosoSagyogunId, nKishuId, aSagyojun)) {
					bOK = FALSE;
					break;
				}

				// ��Ǝҏ�������
				cHenseiKotei2.nSagyoshaId = nSagyoshaId;
				cHenseiKotei2.bMovable = FALSE;
			}

			if (bOK) {
				// OK�Ȃ��Ǝҏ�������
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
				// NG�Ȃ猳�ɖ߂�
				aHenseiKotei.Copy(aHenseiKoteiSave);
				aSagyojun.Copy(aSagyojunSave);
			}
		}

// Modify ... ( ADD )
		}
// By Y.Itabashi (xxxxx) 2007.03.07
	}

	// ��Ə��ɏ]���ėv�f��ƌQ���Ԃ���������
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

	// �S���Y�䐔�A��ƎҐ��A�@�퐔���擾
	int nSagyoshaNum = g_pDataManager->GetSagyoshaNum(nSublineId);
	int nKishuNum = g_pDataManager->GetKishuNum();
	int nSagyojunNum = aSagyojun.GetSize();

	// ���Y�䐔�̑������ɋ@����\�[�g
	CIntArrayEx aKishuJun;
	for (nKishuId = 0; nKishuId < nKishuNum; nKishuId++)
		aKishuJun.Add(nKishuId);
	qsort(aKishuJun.GetData(), aKishuJun.GetSize(), sizeof(int), CompareKishuJun);

	// ��Ǝ҂ɂ�郋�[�v
	for (nSagyoshaId = 0; nSagyoshaId < nSagyoshaNum; nSagyoshaId++) {
		// �@��ɂ�郋�[�v
		for (int nKishuIndex = 0; nKishuIndex < nKishuNum; nKishuIndex++) {
			nKishuId = aKishuJun[nKishuIndex];
			nKoteiIndex = -1;

			// �v�f��ƌQ�ɂ�郋�[�v
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
// --Change 2007/02/26 aizawa �s��D-193
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
// --Add 2007/02/26 aizawa �s��D-193
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
// --Change 2007/02/26 aizawa �s��D-194
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
//  1. �֐���
//		CDataManager::GetHenseiTime
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�[���x�A�b�v����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		int				nSagyoshaId			[I] ��Ǝ�ID
//		SHenseiTime		&cHenseiTime		[O] ��Ǝ���
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�w�肳�ꂽ��Ǝ҂Ɋ���t�����Ă���H���̍�Ǝ��Ԃ��擾����B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
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

// --Change 2007/03/01 aizawa �s��D-198
//	sStatusBar.Format("%s��%s���s��", m_aKishu[nKishuId].sKishuName, pProcess);
	sStatusBar.Format(pProcess, m_aKishu[nKishuId].sKishuName, pProcess);
// --Change
	g_pSystem->StepProgressBar(sStatusBar, nCount, m_aKishu.GetSize());
}

// --Add 2007/03/01 aizawa �s��D-198
void CDataManager::DispProgressBarSubline(int nSublineId, LPCTSTR pProcess, int nProcessCnt, int ProcessNum)
{
	CString sStatusBar;

	sStatusBar.Format(pProcess, m_aSubline[nSublineId].sSublineName, pProcess);
	g_pSystem->StepProgressBar(sStatusBar, nProcessCnt, ProcessNum);
}
// --Add
