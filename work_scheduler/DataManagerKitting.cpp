//*****************************************************************************************************
//  1. �t�@�C����
//		DataManagerKitting.cpp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		CDataManager �N���X�̃C���v�������e�[�V�����i�L�b�g���֘A�j
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
//		CDataManager::GetKittingData
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�L�b�g�����擾
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�����N��񂩂�L�b�g���ɕK�v�ȏ����擾����B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
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

	// �폜�t���O�ɕs�v��Ƃ����邩�`�F�b�N
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

	// ���i�\���H�������N�f�[�^�ɂ�郋�[�v
	int nBuhinhyoLink = m_aBuhinhyoLink.GetSize();
	for (i = 0; i < nBuhinhyoLink; i++) {
		SBuhinhyoLink &cBuhinhyoLink = m_aBuhinhyoLink[i];

		if (cBuhinhyoLink.sSublineName.CompareNoCase(cSubline.sSublineName) == 0) {
			if (CheckKittingBuhinNo(cBuhinhyoLink)) {
				// �v�f��ƌQ�ɂ�郋�[�v
				int nYosoSagyogunNum = cBuhinhyoLink.aYosoSagyogunName.GetSize();
				for (j = 0;j < nYosoSagyogunNum; j++) {
					CString &sYosoSagyogunName = cBuhinhyoLink.aYosoSagyogunName[j];
					SYosoSagyogun *pYosoSagyogun = GetYosoSagyogun(nSublineId, cBuhinhyoLink.sKoteiName, sYosoSagyogunName);
					if (pYosoSagyogun != NULL) {
						// �H��/�v�f��ƌQ���Z�b�g
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

						// ���i���Z�b�g
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
//  1. �֐���
//		CDataManager::CheckKittingBuhinNo
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�L�b�g���Ώۂ̕��i�ԍ����`�F�b�N
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CString		&sBuhinNo			[I] ���i�ԍ�
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL		FALSE�F�L�b�g���Ώە��i�łȂ��CTRUE�F�L�b�g���Ώە��i�ł���
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�w�肳�ꂽ���i�ԍ����L�b�g���Ώە��i���`�F�b�N����B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
BOOL CDataManager::CheckKittingBuhinNo(const SBuhinhyoLink &cBuhinhyoLink)
{
/*
	int i;

	// ���i�ԍ��̕��������擾
	int nLength = sBuhinNo.GetLength();

	// �T���������Ȃ�ΏۊO
	if (nLength < 5)
		return FALSE;

	// �擪�T�����ɐ����ȊO����������ΏۊO
	for (i = 0; i < 5; i++) {
		if (sBuhinNo[i] < '0' || sBuhinNo[i] > '9')
			return FALSE;
	}
*/
	// �{���g�t���O��ON�Ȃ�ΏۊO
	if (cBuhinhyoLink.bBoltFlag)
		return FALSE;

	// �ΏۊO���i�t�@�C���Ɋ܂܂�Ă���ΑΏۊO
	if (m_aKittingTaishogai.LookupElement(cBuhinhyoLink.sBuhinNo.Left(5)) != NULL)
		return FALSE;

	return TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CDataManager::CheckDefaultSakujoSagyo
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�f�t�H���g�̍폜�Ώۍ�ƃ`�F�b�N
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CString		&sYosoSagyoNo			[I] �v�f��Ɣԍ�
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL		FALSE�F�폜�Ώۍ�ƂłȂ��CTRUE�F�폜�Ώۍ�Ƃł���
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�w�肳�ꂽ�v�f��Ɣԍ����L�b�g���ɂ��폜�Ώۂ��`�F�b�N����B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
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
//  1. �֐���
//		CDataManager::DeleteKittingSagyo
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�L�b�g���ɂ��v�f��ƍ폜
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�L�b�g���ɂ��폜�ΏۂƂȂ����v�f��Ƃ�v�f��ƌQ�f�[�^����폜����B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
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

	// �f�[�^�X�V�t���O�ݒ�
	m_bUpdate = TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CDataManager::AddKittingTemplate
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�L�b�g����ƃe���v���[�g�ǉ�
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�v�f��ƕ\�ɃL�b�g����Ƃ�ǉ�����B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
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

	// �e���v���[�g�̍s�����擾
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

	// �e���v���[�g�P���R�s�[
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

	// �e���v���[�g�Q���R�s�[
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

				// �@��ʂɗv�f��ƌQ���Ԃ�ݒ�
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

	// �v�f��ƌQ�𒊏o
	ExtractYosoSagyogunSub(nSublineId, nLastRowIndex);

	// �v�f��ƌQ���Ԃ�ݒ�
	int nKishuNum = m_aKishu.GetSize();
	for (nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
		CSagyojunArray aSagyojun;
		GetKittingSagyojun(nSublineId, nKishuId, aSagyojun);
		OrderByKittingSagyojun(nSublineId, aSagyojun);
	}

	// �f�[�^�X�V�t���O�ݒ�
	m_bUpdate = TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CDataManager::CancelKitting
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�L�b�g������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�L�b�g����Ƃ�v�f��ƌQ�f�[�^����폜���A�L�b�g���ɂ��폜���ꂽ�v�f��Ƃ𕜊�������B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
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

	// �f�[�^�X�V�t���O�ݒ�
	m_bUpdate = TRUE;

	// �L�b�g���L���t���O���Z�b�g
	cSubline.bSetKitting = FALSE;
}

// ����̕��i�̃L�b�g��������
void CDataManager::CancelKitting(int nSublineId, LPCTSTR pKoteiName)
{
	SSubline &cSubline = m_aSubline[nSublineId];

	// �L�b�g���e���v���[�g����H�������擾
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

	// �H�������畔�i���𒊏o
	CString sBuhinName = pKoteiName;
	sKoteiName.Replace("$", "");
	int nPos = sBuhinName.Find(sKoteiName);
	if (nPos >= 0) {
		sBuhinName.Delete(nPos, sKoteiName.GetLength());

		// ���i�\���H�������N�f�[�^�ɂ�郋�[�v
		int nBuhinhyoLink = m_aBuhinhyoLink.GetSize();
		for (int i = 0; i < nBuhinhyoLink; i++) {
			SBuhinhyoLink &cBuhinhyoLink = m_aBuhinhyoLink[i];

			// ���i�\���H�������N����T�u���C�����ƕ��i������v����f�[�^��T��
			if (cBuhinhyoLink.sSublineName == cSubline.sSublineName && cBuhinhyoLink.sBuhinName == sBuhinName) {
				int nSize = cBuhinhyoLink.aYosoSagyogunName.GetSize();
				for (int j = 0; j < nSize; j++) {
					CString &sYosoSagyogunName = cBuhinhyoLink.aYosoSagyogunName[j];

					// ���i�Ƀ����N���Ă���v�f��ƌQ�𕜊�������
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

				// ���i�̃L�b�g���t���O���N���A
				SKittingBuhin *pKittingBuhin = cSubline.aKittingBuhin.LookupElement(cBuhinhyoLink.sBuhinNo);
				if (pKittingBuhin != NULL)
					pKittingBuhin->bCheck = FALSE;
			}
		}

		// �L�b�g����Ƃ���Y�����i�̍�Ƃ��폜
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

// ���i�L�b�g�����t�@�C���ݒ�
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

// �L�b�g�������t���O�N���A
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

// �v�f��ƕ\�ɃL�b�g���ɂ��폜�t���O���Z�b�g
void CDataManager::SetKittingDeleteFlag()
{
	int nSublineNum = m_aSubline.GetSize();
	for (int nSublineId = 0; nSublineId < nSublineNum; nSublineId++)
		SetKittingDeleteFlagSub(nSublineId);
}

// �v�f��ƕ\�ɃL�b�g���ɂ��폜�t���O���Z�b�g
void CDataManager::SetKittingDeleteFlagSub(int nSublineId)
{
	SSubline &cSubline = m_aSubline[nSublineId];

	// �L�b�g���ɂ��폜�t���O����U�S�Đݒ肷��
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

	// �L�b�g���ݒ肳��Ă��Ȃ����i�̗v�f��Ƃ̍폜�t���O����������
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
