//*****************************************************************************************************
//  1. �t�@�C����
//		DataManager.cpp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		CDataManager �N���X�̃C���v�������e�[�V����
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************

#include "stdafx.h"
#include "workscheduler.h"
#include "ManualEditFrameWnd.h"
#include "ManualEditDocument.h"
#include <math.h>

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

// �v�f��ƌQ���Ԑݒ�p�f�[�^
struct SYosoSagyogunJunban {
	int nYosoSagyogunId;
	int nKishuId;
	int nGroupNo;
	int nSagyoshaId;
	CString sKoteiName;
	BOOL bKyotsuKotei;
};
typedef CArrayEx <SYosoSagyogunJunban, SYosoSagyogunJunban&> CYosoSagyogunJunbanArray;

//*****************************************************************************************************
//  1. �֐���
//		CDataManager::CDataManager
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�R���X�g���N�^
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�N���X�ϐ�������������B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
CDataManager::CDataManager()
{
	// �N���X�ϐ���������
	m_nStatus = STATUS_NOREAD;
	m_nYear = 0;
	m_nMonth = 0;
	m_bUpdate = FALSE;
	m_bOpenLineKiseiMatrix = FALSE;
	m_bOpenKatashikiKiseiMatrix = FALSE;
	m_nCopyCount = 1;
// Modify ... ( ADD )
	aBeforeMoveSagyojun.RemoveAll();
// By Y.Itabashi (xxxxx) 2007.02.06
// Modify ... ( ADD )
	m_sKittingKoteiKigo = KITTING_KOTEI_KIGO;
// By Y.Itabasshi (xxxxx) 2007.03.07
}

//*****************************************************************************************************
//  1. �֐���
//		CDataManager::GetSagyojun
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�T�u���C����Ə��擾
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		int				nSublineId		[I] �T�u���C��ID
//		int				nKishuId		[I] �@��ID
//		CSagyojunArray	&aSagyojun		[O] ��Ə��f�[�^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�w�肳�ꂽ�T�u���C���A�@��̍�Ə������o���B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CDataManager::GetSagyojun(int nSublineId, int nKishuId, CSagyojunArray &aSagyojun)
{
	GetSagyojunSub(m_aSubline[nSublineId].aYosoSagyogun, nKishuId, aSagyojun);
}

//*****************************************************************************************************
//  1. �֐���
//		CDataManager::GetKittingSagyojun
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�L�b�g����Ə��擾
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		int				nSublineId		[I] �T�u���C��ID
//		int				nKishuId		[I] �@��ID
//		CSagyojunArray	&aSagyojun		[O] ��Ə��f�[�^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�w�肳�ꂽ�T�u���C���A�@��̃L�b�g����Ə������o���B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CDataManager::GetKittingSagyojun(int nSublineId, int nKishuId, CSagyojunArray &aSagyojun)
{
	GetSagyojunSub(m_aSubline[nSublineId].aYosoSagyogunKit, nKishuId, aSagyojun);
}

//*****************************************************************************************************
//  1. �֐���
//		CDataManager::GetSagyojunSub
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		��Ə��擾
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CYosoSagyogunArray	&aYosoSagyogun		[I] �v�f��ƌQ�z��f�[�^
//		int					nKishuId			[I] �@��ID
//		CSagyojunArray		&aSagyojun			[O] ��Ə��f�[�^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�w�肳�ꂽ�v�f��ƌQ�z��A�@�킩���Ə������o���B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CDataManager::GetSagyojunSub(CYosoSagyogunArray &aYosoSagyogun, int nKishuId, CSagyojunArray &aSagyojun)
{
	int i;
	int nIndex;
	int nYosoSagyogunNum = aYosoSagyogun.GetSize();

	aSagyojun.RemoveAll();
	for (i = 0; i < nYosoSagyogunNum; i++) {
		SYosoSagyogun &cYosoSagyogun = aYosoSagyogun[i];
		if (!cYosoSagyogun.CheckDeleted()) {
			if (nKishuId != -1) {
				// �@��w�肪�����ꍇ
				SKishuSagyo &cKishuSagyo = cYosoSagyogun.aKishuSagyo[nKishuId];

				if (cKishuSagyo.nSagyojun != 0) {
					SSagyojun *pSagyojun = aSagyojun.AddElement(nIndex);
					pSagyojun->nIndex = nIndex;
					pSagyojun->nYosoSagyogunId = i;
					pSagyojun->nSagyojun = cKishuSagyo.nSagyojun;
					pSagyojun->nKishuId = nKishuId;
				}
			} else {
				// �@��w�肪�L��ꍇ
				int nKishuNum = m_aKishu.GetSize();
				for (int nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
					SKishuSagyo &cKishuSagyo = cYosoSagyogun.aKishuSagyo[nKishuId];

					if (cKishuSagyo.nSagyojun != 0) {
						SSagyojun *pSagyojun = aSagyojun.AddElement(nIndex);
						pSagyojun->nIndex = nIndex;
						pSagyojun->nYosoSagyogunId = i;
						pSagyojun->nSagyojun = cKishuSagyo.nSagyojun;
						pSagyojun->nKishuId = nKishuId;
					}
				}
			}
		}
	}

	// ��Ə��\�[�g
	SortSagyojun(aSagyojun);
}

//*****************************************************************************************************
//  1. �֐���
//		CDataManager::SortSagyojun
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		��Ə��\�[�g
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CSagyojunArray	&aSagyojun		[I/O] ��Ə��f�[�^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		��Ə��f�[�^����Ə��Ƀ\�[�g����B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CDataManager::SortSagyojun(CSagyojunArray &aSagyojun)
{
	qsort(aSagyojun.GetData(), aSagyojun.GetSize(), sizeof(SSagyojun), CompareSagyojun);
}

//*****************************************************************************************************
//  1. �֐���
//		CDataManager::CompareSagyojun
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		��Ə���r
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		void	*arg1		[I] ��r�����Ə��f�[�^
//		void	*arg2		[I] ��r�����������̍�Ə��f�[�^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		int		��r����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		��Ə��f�[�^�̍�Ə����r����B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//		qsort()����R�[���o�b�N�����B
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
int CDataManager::CompareSagyojun(const void *arg1, const void *arg2)
{
	SSagyojun *pSagyojun1 = (SSagyojun *)arg1;
	SSagyojun *pSagyojun2 = (SSagyojun *)arg2;

	// ��Ə��������ꍇ�͗v�f��ƌQID�Ŕ�r
	if (pSagyojun1->nSagyojun == pSagyojun2->nSagyojun)
		return pSagyojun1->nYosoSagyogunId - pSagyojun2->nYosoSagyogunId;

	// ��Ə����r
	return pSagyojun1->nSagyojun - pSagyojun2->nSagyojun;
}

//*****************************************************************************************************
//  1. �֐���
//		CDataManager::FindSagyoWariate
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�w���ƎҁA�@��̗v�f��ƌQ���擾
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		int			nSublineId			[I] �T�u���C��ID
//		int			nSagyoshaId			[I] ��Ǝ�ID
//		int			nKishuId			[I] �@��ID
//		CIntArrayEx	&aYosoSagyogunId	[O] �v�f��ƌQID�z��
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�w�肳�ꂽ�T�u���C��ID�A��Ǝ�ID�A�@��ID�̗v�f��ƌQ�𒊏o����B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CDataManager::FindSagyoWariate(int nSublineId, int nSagyoshaId, int nKishuId, CIntArrayEx &aYosoSagyogunId)
{
	int i, j, k;
	SSubline &cSubline = m_aSubline[nSublineId];
	CYosoSagyogunArray &aYosoSagyogun = cSubline.aYosoSagyogun;

	aYosoSagyogunId.RemoveAll();

	if (nKishuId != -1) {
		// �@��w�肪����ꍇ
		CSagyojunArray aSagyojun;
		GetSagyojun(nSublineId, nKishuId, aSagyojun);
		int nSagyojun = aSagyojun.GetSize();
		for (i = 0; i < nSagyojun; i++) {
			int nYosoSagyogunId = aSagyojun[i].nYosoSagyogunId;
			if (aYosoSagyogun[nYosoSagyogunId].aKishuSagyo[nKishuId].nSagyoshaId == nSagyoshaId)
				aYosoSagyogunId.Add(nYosoSagyogunId);
		}
	} else {
		// �@��w�肪�Ȃ��ꍇ
		int nYosoSagyogun = aYosoSagyogun.GetSize();
		int nKishuNum = GetKishuNum();
		for (i = 0; i < nYosoSagyogun; i++) {
			for (j = 0; j < nKishuNum; j++) {
				if (aYosoSagyogun[i].aKishuSagyo[j].nSagyoshaId == nSagyoshaId) {
					int nYosoSagyogunId = aYosoSagyogunId.GetSize();
					for (k = 0; k < nYosoSagyogunId; k++) {
						if (i == aYosoSagyogunId[k])
							break;
					}
					if (k == nYosoSagyogunId)
						aYosoSagyogunId.Add(i);
				}
			}
		}
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CDataManager::SetSeisanDaisu
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���Y�䐔�ݒ�
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		SSeisanDaisu	&cSeisanDaisu		[I] ���Y�䐔���
//		BOOL			bCopy				[I] �R�s�[�t���O
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		���Y�䐔���f�[�^�Ǘ��G���A�ɐݒ肷��B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CDataManager::SetSeisanDaisu(SSeisanDaisu &cSeisanDaisu, BOOL bCopy)
{
	// ���Y�䐔���G���A�ɃR�s�[
	if (bCopy) {
		m_aSeisanDaisu.RemoveAll();
		SSeisanDaisu *pSeisanDaisu = m_aSeisanDaisu.AddElement(cSeisanDaisu.sYexxxonth);
		pSeisanDaisu->sYexxxonth = cSeisanDaisu.sYexxxonth;
		pSeisanDaisu->aSeisanDaisu.Copy(cSeisanDaisu.aSeisanDaisu);
	}

	// �N������ݒ�
	m_nYear = atoi(cSeisanDaisu.sYexxxonth.Left(4));
	m_nMonth = atoi(cSeisanDaisu.sYexxxonth.Mid(4));

	// �@��ʐ��Y�䐔��ݒ�
	int nKishuNum = m_aKishu.GetSize();
	for (int nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
		SKishu &cKishu = m_aKishu[nKishuId];
		cKishu.nSeisanDaisu = cSeisanDaisu.aSeisanDaisu[nKishuId];;
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CDataManager::GetTotalSeisanDaisu
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�S�@��̍��v���Y�䐔�擾
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		int		nSublineId			[I] �T�u���C��ID
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		int		�S�@��̍��v���Y�䐔
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�@��f�[�^����S�Ă̐��Y�䐔�̍��v���擾����B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//		2007.02.11 Y.Itabashi (xxxxx) �T�u���C�����Ƃɑ����Y�䐔���Z�o
//*****************************************************************************************************
// Modify ... ( CHANGE )
int CDataManager::GetTotalSeisanDaisu( int nSublineId )
//int CDataManager::GetTotalSeisanDaisu()
{
	int nDaisuTotal = 0;
	int nSize = m_aKishu.GetSize();

	if( nSublineId >= 0 ){
		SSubline &cSubline = m_aSubline[nSublineId];
		for (int i = 0; i < nSize; i++){
			if( cSubline.aSeisanKishuFlag[i] == 1 )
				nDaisuTotal += m_aKishu[i].nSeisanDaisu;
		}
	}else{
		for (int i = 0; i < nSize; i++)
			nDaisuTotal += m_aKishu[i].nSeisanDaisu;
	}

	return nDaisuTotal;
/*	int nDaisuTotal = 0;
	int nSize = m_aKishu.GetSize();
	for (int i = 0; i < nSize; i++)
		nDaisuTotal += m_aKishu[i].nSeisanDaisu;

	return nDaisuTotal;*/
}
// By Y.Itabashi (xxxxx) 2007.02.11

//*****************************************************************************************************
//  1. �֐���
//		CDataManager::GetYosoSagyogun
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�v�f��ƌQ�擾
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		int		nSublineId				[I] �T�u���C��ID
//		CString	&sKoteiName				[I] �H����
//		CString	&sYosoSagyogunName		[I] �v�f��ƌQ��
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		SYosoSagyogun*	�v�f��ƌQ�f�[�^
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�w�肳�ꂽ�H�����A�v�f��ƌQ���̗v�f��ƌQ�f�[�^���������A���̃|�C���^��Ԃ��B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
SYosoSagyogun *CDataManager::GetYosoSagyogun(int nSublineId, const CString &sKoteiName, const CString &sYosoSagyogunName)
{
	SSubline &cSubline = m_aSubline[nSublineId];
	CYosoSagyogunArray &aYosoSagyogun = cSubline.aYosoSagyogun;
	int nSize = aYosoSagyogun.GetSize();

	// �w�肳�ꂽ�H�����A�v�f��ƌQ���̗v�f��ƃf�[�^������
	for (int i = 0; i < nSize; i++) {
		SYosoSagyogun &cYosoSagyogun = aYosoSagyogun[i];
		if (!cYosoSagyogun.CheckDeleted()) {
			if (cYosoSagyogun.sKoteiName == sKoteiName && cYosoSagyogun.sYosoSagyogunName == sYosoSagyogunName)
				return &cYosoSagyogun;
		}
	}

	// ������Ȃ������ꍇ
	return NULL;
}

//*****************************************************************************************************
//  1. �֐���
//		CDataManager::GetKotei
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�H���f�[�^�擾
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		int		nSublineId			[I] �T�u���C��ID
//		CString	&sKoteiName			[I] �H����
//		SKotei	&cKotei				[O] �H���f�[�^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�w�肳�ꂽ�H�����̗v�f��ƌQ�𒊏o����B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CDataManager::GetKotei(int nSublineId, const CString &sKoteiName, SKotei &cKotei)
{
	SSubline &cSubline = m_aSubline[nSublineId];
	CYosoSagyogunArray &aYosoSagyogun = cSubline.aYosoSagyogun;

	// �w�肳�ꂽ�H�����̗v�f��ƌQ������
	int nYosoSagyogun = aYosoSagyogun.GetSize();
	for (int i = 0; i < nYosoSagyogun; i++) {
		SYosoSagyogun &cYosoSagyogun = aYosoSagyogun[i];
		if (!cYosoSagyogun.CheckDeleted()) {
			if (sKoteiName == cYosoSagyogun.sKoteiName) {
				if (cKotei.sKoteiName.IsEmpty()) {
					cKotei.sKoteiName = cYosoSagyogun.sKoteiName;
					cKotei.fTime = 0;
				}
				cKotei.fTime += cYosoSagyogun.GetTime();
				cKotei.aYosoSagyogunId.Add(i);
			}
		}
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CDataManager::GetKoteiArray
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�w�肳�ꂽ�@��̑S�H���擾
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		int			nSublineId			[I] �T�u���C��ID
//		int			nKishuId			[I] �@��ID
//		CKoteiArray	&cKoteiArray		[O] �H���z��f�[�^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�w�肳�ꂽ�@��ID�̍H���𒊏o����B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CDataManager::GetKoteiArray(int nSublineId, int nKishuId, CKoteiArray &cKoteiArray)
{
	SSubline &cSubline = m_aSubline[nSublineId];
	CYosoSagyogunArray &aYosoSagyogun = cSubline.aYosoSagyogun;
	SKotei *pKotei = NULL;

	cKoteiArray.RemoveAll();

	// �w�肳�ꂽ�@��̍�Ƃ����݂���v�f��ƌQ������
	CSagyojunArray aSagyojun;
	GetSagyojun(nSublineId, nKishuId, aSagyojun);
	int nSagyojun = aSagyojun.GetSize();
	for (int i = 0; i < nSagyojun; i++) {
		int nYosoSagyogunId = aSagyojun[i].nYosoSagyogunId;
		SYosoSagyogun &cYosoSagyogun = aYosoSagyogun[nYosoSagyogunId];
		if (!cYosoSagyogun.CheckDeleted()) {
			if (pKotei == NULL || pKotei->sKoteiName != cYosoSagyogun.sKoteiName) {
				pKotei = cKoteiArray.AddElement();
				pKotei->sKoteiName = cYosoSagyogun.sKoteiName;
				pKotei->fTime = 0;
			}
			pKotei->fTime += cYosoSagyogun.GetTime();
			pKotei->aYosoSagyogunId.Add(nYosoSagyogunId);
		}
	}
}

void CDataManager::GetKittingKoteiArray(int nSublineId, CKoteiArray &cKoteiArray)
{
	SSubline &cSubline = m_aSubline[nSublineId];
	CYosoSagyogunArray &aYosoSagyogun = cSubline.aYosoSagyogunKit;
	SKotei *pKotei = NULL;

	cKoteiArray.RemoveAll();

	// �w�肳�ꂽ�@��̍�Ƃ����݂���v�f��ƌQ������
	int nYosoSagyogunNum = aYosoSagyogun.GetSize();
	for (int nYosoSagyogunId = 0; nYosoSagyogunId < nYosoSagyogunNum; nYosoSagyogunId++) {
		SYosoSagyogun &cYosoSagyogun = aYosoSagyogun[nYosoSagyogunId];
		if (!cYosoSagyogun.CheckDeleted()) {
			if (pKotei == NULL || pKotei->sKoteiName != cYosoSagyogun.sKoteiName) {
				pKotei = cKoteiArray.AddElement();
				pKotei->sKoteiName = cYosoSagyogun.sKoteiName;
				pKotei->fTime = 0;
			}
			pKotei->fTime += cYosoSagyogun.GetTime();
			pKotei->aYosoSagyogunId.Add(nYosoSagyogunId);
		}
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CDataManager::OrderBySagyojun
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		��Ə��ɗv�f��ƌQ����ёւ�
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		int				nSublineId			[I] �T�u���C��ID
//		int				nKishuId			[I] �@��ID
//		CSagyojunArray	&aSagyojun			[I] ��Ə��z��f�[�^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�w�肳�ꂽ�H�����ɗv�f��ƌQ�f�[�^�̍�Ə���ݒ肷��B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CDataManager::OrderBySagyojun(int nSublineId, CSagyojunArray &aSagyojun)
{
	OrderBySagyojunSub(m_aSubline[nSublineId].aYosoSagyogun, aSagyojun);
}

void CDataManager::OrderByKittingSagyojun(int nSublineId, CSagyojunArray &aSagyojun)
{
	OrderBySagyojunSub(m_aSubline[nSublineId].aYosoSagyogunKit, aSagyojun);
}

void CDataManager::OrderBySagyojunSub(CYosoSagyogunArray &aYosoSagyogun, CSagyojunArray &aSagyojun)
{
	int nSagyojun = 1;

	int nSagyojunNum = aSagyojun.GetSize();
	for (int i = 0; i < nSagyojunNum; i++) {
		SSagyojun &cSagyojun = aSagyojun[i];
		aYosoSagyogun[cSagyojun.nYosoSagyogunId].aKishuSagyo[cSagyojun.nKishuId].nSagyojun = nSagyojun++;
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CDataManager::CheckMokuhyoTactJoukagen
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�ڕW�^�N�g�㉺���`�F�b�N
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		int			nSublineId			[I] �T�u���C��ID
//		int			nSagyoshaId			[I] ��Ǝ�ID
//		int			nKishuId			[I] �@��ID
//		CString		&sErrMsg			[O] �G���[���b�Z�[�W
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL		TRUE�F����𒴂��Ă��Ȃ��CFALSE�F����𒴂��Ă���
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�w�肳�ꂽ��Ǝ҂��ڕW�^�N�g�̏���𒴂��Ă��邩�`�F�b�N����B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//		�����̎d�l�ł͉������`�F�b�N���Ă������A���d�l�͏���̂݃`�F�b�N�B
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
// Modify ... ( CHANGE )
BOOL CDataManager::CheckMokuhyoTactJoukagen(int nSublineId, int nSagyoshaId, int nKishuId, int nKishuType)
//BOOL CDataManager::CheckMokuhyoTactJoukagen(int nSublineId, int nSagyoshaId, int nKishuId)
// By Y.Itabashi (xxxxx) 2007.02.12
{
	SSubline &cSubline = m_aSubline[nSublineId];
	CYosoSagyogunArray &aYosoSagyogun = cSubline.aYosoSagyogun;
	CStringArray &aSagyosha = cSubline.aSagyosha;
	//del kuwama CManualEditFrameWnd *pWnd = (CManualEditFrameWnd *)(AfxGetMainWnd()->GetActiveWindow());
	//del kuwama int nKishuType = pWnd->GetSelectedKishu();

	// ��Ǝ��Ԃ��v�Z
	double fTime = 0;
	CIntArrayEx aYosoSagyogunId;
	FindSagyoWariate(nSublineId, nSagyoshaId, nKishuId, aYosoSagyogunId);
	int nSize = aYosoSagyogunId.GetSize();
	for (int i = 0; i < nSize; i++)
		fTime += aYosoSagyogun[aYosoSagyogunId[i]].GetTime();

	// �㉺���擾
	double fTact = cSubline.cSeisanJoken.fTact;
	double fJogen = cSubline.cSeisanJoken.fTact * (1 + cSubline.cSeisanJoken.fJogen / 100);
	double fKagen = cSubline.cSeisanJoken.fTact * (1 - cSubline.cSeisanJoken.fKagen / 100);

	// ����`�F�b�N
// Modify ... ( CHANGE )
	if( cSubline.cSeisanJoken.bKyoseiKudo == 1 ){	// �����쓮
		if ( fabs( fTime - fJogen ) > 0.0000001e0 && fTime > fJogen )
			return FALSE;
	}else{											// �񋭐��쓮
		if ( fabs( fTime - fTact ) > 0.0000001e0 && fTime > fTact )
			return FALSE;
	}

// Modify ... ( ADD )
	// ���d���ώ��͖ڕW�^�N�g�ł̃`�F�b�N
	//�@�@�했�̃^�N�g�`�F�b�N���K�v�Ȃ��߁A��L�`�F�b�N���K�v
	if( nKishuType == -1 ){
		int nTotalSeisanDaisu = g_pDataManager->GetTotalSeisanDaisu( nSublineId );
		int nSeisanDaisu = m_aKishu[nKishuId].nSeisanDaisu;
		fTime = 0;
		for (int i = 0; i < nSize; i++){
			fTime += aYosoSagyogun[aYosoSagyogunId[i]].GetTime() * nSeisanDaisu / nTotalSeisanDaisu;
		}

		if ( fabs( fTime - fTact ) > 0.0000001e0 && fTime > fTact )
			return FALSE;
	}
// By Y.Itabashi (xxxxx) 2007.02.11
/*	if (fTime > fJogen)
		return FALSE;*/
// By Y.Itabashi (xxxxx) 2007.02.03
	// OK
	return TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CDataManager::GetSagyoJikan
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�w���ƎҁA�@��̍�Ǝ��Ԃ��擾
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		int		nSublineId			[I] �T�u���C��ID
//		int		nSagyoshaId			[I] ��Ǝ�ID
//		int		nKishuId			[I] �@��ID
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		double		��Ǝ���
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�w���ƎҁA�@��̍�Ǝ��Ԃ̍��v�����߂�B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
double CDataManager::GetSagyoJikan(int nSublineId, int nSagyoshaId, int nKishuId)
{
	SSubline &cSubline = m_aSubline[nSublineId];
	CYosoSagyogunArray &aYosoSagyogun = cSubline.aYosoSagyogun;
	double fTime = 0;

	int nYosoSagyogun = aYosoSagyogun.GetSize();
	for (int i = 0; i < nYosoSagyogun; i++) {
		SYosoSagyogun &cYosoSagyogun = aYosoSagyogun[i];
		if (!cYosoSagyogun.CheckDeleted()) {
			if (cYosoSagyogun.aKishuSagyo[nKishuId].nSagyoshaId == nSagyoshaId)
				fTime += cYosoSagyogun.GetTime();
		}
	}

	return fTime;
}

//*****************************************************************************************************
//  1. �֐���
//		CDataManager::RenumberSagyoshaId
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		��Ǝ�ID���i���o
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		int		nSublineId			[I] �T�u���C��ID
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		��Ǝ҂̏��Ԃɍ�Ǝ�ID��U�蒼���B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CDataManager::RenumberSagyoshaId(int nSublineId)
{
	CIntArrayEx aSagyoshaJun;

	GetSagyoshaJun(nSublineId, aSagyoshaJun);
	UpdateSagyoshaId(nSublineId, aSagyoshaJun);
}

//*****************************************************************************************************
//  1. �֐���
//		CDataManager::GetSagyoshaJun
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		��Ǝ҂̏��Ԃ��擾
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		int			nSublineId			[I] �T�u���C��ID
//		CIntArrayEx	&aSagyoshaJun		[O] ��Ǝҏ�
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�v�f��ƌQ�̍�Ə��ɁA����t����ꂽ��Ǝ҂̏��Ԃ����߂�B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CDataManager::GetSagyoshaJun(int nSublineId, CIntArrayEx &aSagyoshaJun)
{
	SSubline &cSubline = m_aSubline[nSublineId];
	CYosoSagyogunArray &aYosoSagyogun = cSubline.aYosoSagyogun;
	int nKishuNum = GetKishuNum();

	aSagyoshaJun.RemoveAll();

	CSagyojunArray aSagyojun;
	SetSagyojunSub(nSublineId);
	GetSagyojun(nSublineId, -1, aSagyojun);

	int nSize = aSagyojun.GetSize();
	for (int i = 0; i < nSize; i++) {
		SSagyojun &cSagyojun = aSagyojun[i];
		SYosoSagyogun &cYosoSagyogun = aYosoSagyogun[aSagyojun[i].nYosoSagyogunId];
		if (!cYosoSagyogun.CheckDeleted()) {
			int nSagyoshaId = cYosoSagyogun.aKishuSagyo[cSagyojun.nKishuId].nSagyoshaId;
			if (nSagyoshaId != -1)
				aSagyoshaJun.AddUnique(nSagyoshaId);
		}
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CDataManager::UpdateSagyoshaId
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		��Ǝ҂̏��Ԃ��擾
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		int			nSublineId			[I] �T�u���C��ID
//		CIntArrayEx	&aSagyoshaJun		[I] ��Ǝҏ�
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�w�肳�ꂽ��Ǝҏ��ɍ�Ǝ�ID��U�蒼���B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CDataManager::UpdateSagyoshaId(int nSublineId, CIntArrayEx &aSagyoshaJun)
{
	SSubline &cSubline = m_aSubline[nSublineId];
	CYosoSagyogunArray &aYosoSagyogun = cSubline.aYosoSagyogun;
	CStringArray &aSagyosha = cSubline.aSagyosha;
	int i, j;
	int nSagyoshaNum = aSagyosha.GetSize();
	int *pSagyoshaId = new int[nSagyoshaNum];

	CStringArray aSagyosha2;
	int nSagyoshaJun = aSagyoshaJun.GetSize();
	aSagyosha2.SetSize(nSagyoshaJun);
	for (i = 0; i < nSagyoshaJun; i++) {
		pSagyoshaId[aSagyoshaJun[i]] = i;
		aSagyosha2[i] = aSagyosha[aSagyoshaJun[i]];
	}

	aSagyosha.Copy(aSagyosha2);

	int nYosoSagyogun = aYosoSagyogun.GetSize();
	for (i = 0; i < nYosoSagyogun; i++) {
		SYosoSagyogun &cYosoSagyogun = aYosoSagyogun[i];
		int nKishuNum = cYosoSagyogun.aKishuSagyo.GetSize();
		for (j = 0; j < nKishuNum; j++) {
			SKishuSagyo &cKishuSagyo = cYosoSagyogun.aKishuSagyo[j];
			if (cKishuSagyo.nSagyoshaId != -1)
				cKishuSagyo.nSagyoshaId = pSagyoshaId[cKishuSagyo.nSagyoshaId];
		}
	}

	delete [] pSagyoshaId;
}

//*****************************************************************************************************
//  1. �֐���
//		CDataManager::RenameSagyoshaName
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		��ƎҖ�
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		int		nSublineId			[I] �T�u���C��ID
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		��Ə��ɘA�ԂɂȂ�悤�ɍ�ƎҖ�(Dxx,Kxx)��t�������B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CDataManager::RenameSagyoshaName(int nSublineId)
{
	int nSagyoshaNo = 0;

	if (nSublineId != -1) {
		SSubline &cSubline = m_aSubline[nSublineId];
		CStringArray &aSagyosha = cSubline.aSagyosha;
		int nSagyoshaNum = aSagyosha.GetSize();
		for (int i = 0; i < nSagyoshaNum; i++)
			aSagyosha[i].Format("%s%03d", cSubline.sSagyoshaName, ++nSagyoshaNo);
	} else {
		int nSagyoshaNum = m_aKittingSagyosha.GetSize();
		for (int i = 0; i < nSagyoshaNum; i++)
			m_aKittingSagyosha[i].Format("KIT%03d", ++nSagyoshaNo);
	}
}

// �f�t�H���g�̃O���[�v�ԍ���ݒ�
void CDataManager::SetDefaultGroupNo(int nSublineId)
{
	SSubline &cSubline = m_aSubline[nSublineId];

	int nKishuId;
	int nGroupNo;
	int nNewGroupNo = GetNewGroupNo(nSublineId);
	int nKishuNum = m_aKishu.GetSize();

	int nYosoSagyogunNum = cSubline.aYosoSagyogun.GetSize();
	for (int nYosoSagyogunId = 0; nYosoSagyogunId < nYosoSagyogunNum; nYosoSagyogunId++) {
		SYosoSagyogun &cYosoSagyogun = cSubline.aYosoSagyogun[nYosoSagyogunId];
		if (!cYosoSagyogun.CheckDeleted()) {
			// �����̃O���[�v�ԍ��擾
			nGroupNo = 0;
			for (nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
				SKishuSagyo &cKishuSagyo = cYosoSagyogun.aKishuSagyo[nKishuId];
				if (cKishuSagyo.nGroupNo != 0) {
					nGroupNo = cKishuSagyo.nGroupNo;
					break;
				}
			}

			// ���蓖�Ă��Ă��Ȃ���ΐV�����ԍ���t�^
			if (nGroupNo == 0)
				nGroupNo = nNewGroupNo++;

			// �O���[�v�ԍ���ݒ�
			for (nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
				SKishuSagyo &cKishuSagyo = cYosoSagyogun.aKishuSagyo[nKishuId];
				if (cKishuSagyo.nSagyojun != 0 && cKishuSagyo.nGroupNo == 0)
					cKishuSagyo.nGroupNo = nGroupNo;
			}
		}
	}
}

// �V�����O���[�v�ԍ����擾
int CDataManager::GetNewGroupNo(int nSublineId)
{
	SSubline &cSubline = m_aSubline[nSublineId];

	int nMaxGroupNo = 0;
	int nKishuNum = m_aKishu.GetSize();
	int nYosoSagyogunNum = cSubline.aYosoSagyogun.GetSize();
	for (int nYosoSagyogunId = 0; nYosoSagyogunId < nYosoSagyogunNum; nYosoSagyogunId++) {
		SYosoSagyogun &cYosoSagyogun = cSubline.aYosoSagyogun[nYosoSagyogunId];
		if (!cYosoSagyogun.CheckDeleted()) {
			for (int nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
				nMaxGroupNo = max(nMaxGroupNo, cYosoSagyogun.aKishuSagyo[nKishuId].nGroupNo);
			}
		}
	}

	return nMaxGroupNo + 1;
}

// �A���_�[�o�[�Ƃ��̌�̐������폜
CString CDataManager::RemoveUnderbarDigit(const CString &sName)
{
	BOOL bDigit = FALSE;
	int nLen = sName.GetLength();
	for (int i = nLen - 1; i >= 0; i--) {
		if (isdigit(sName[i]))
			bDigit = TRUE;
		else if (sName[i] == '_') {
			if (bDigit) {
				nLen = i;
				bDigit = FALSE;
			} else
				break;
		} else
			break;
	}

	return sName.Left(nLen);
}

// �T�u���C���ʐ��Y�䐔�擾
int CDataManager::GetSeisanDaisu(int nSublineId)
{
	int nSeisanDaisu = 0;
	int nKishuNum = m_aKishu.GetSize();
	for (int nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
		if (nSublineId == -1 || m_aSubline[nSublineId].aSeisanKishuFlag[nKishuId])
			nSeisanDaisu += m_aKishu[nKishuId].nSeisanDaisu;
	}

	return nSeisanDaisu;
}

// ���ʍH���t���O�ݒ�
void CDataManager::SetKyotsuKoteiFlag()
{
	CArrayEx2 <CIntArrayEx, CIntArrayEx&> aKotei;

	int nKishuNum = m_aKishu.GetSize();

	int nSublineNum = m_aSubline.GetSize();
	for (int nSublineId = 0; nSublineId < nSublineNum; nSublineId++) {
		SSubline &cSubline = m_aSubline[nSublineId];

		// �S�H�����擾
		aKotei.RemoveAll();
		int nYosoSagyogunNum = cSubline.aYosoSagyogun.GetSize();
		for (int nYosoSagyogunId = 0; nYosoSagyogunId < nYosoSagyogunNum; nYosoSagyogunId++) {
			SYosoSagyogun &cYosoSagyogun = cSubline.aYosoSagyogun[nYosoSagyogunId];
			if (!cYosoSagyogun.CheckDeleted()) {
				CIntArrayEx *pKotei = aKotei.LookupElement(cYosoSagyogun.sKoteiName);
				if (pKotei == NULL)
					pKotei = aKotei.AddElement(cYosoSagyogun.sKoteiName);

				pKotei->Add(nYosoSagyogunId);

				// ���ʍH���t���O�N���A
				for (int nKishuId = 0; nKishuId < nKishuNum; nKishuId++)
					cYosoSagyogun.aKishuSagyo[nKishuId].bKyotsuKotei = FALSE;
			}
		}

		// �H���ɂ�郋�[�v
		int nKoteiNum = aKotei.GetSize();
		for (int nKoteiId = 0; nKoteiId < nKoteiNum; nKoteiId++) {
			CIntArrayEx &cKotei = aKotei[nKoteiId];

			// �v�f��ƌQ�ɂ�郋�[�v
			int nSize = cKotei.GetSize();
			for (int i = 0; i < nSize; i++) {
				SYosoSagyogun &cYosoSagyogun = cSubline.aYosoSagyogun[cKotei[i]];
				if (!cYosoSagyogun.CheckDeleted()) {
					// �@��ɂ�郋�[�v
					for (int nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
						SKishuSagyo &cKishuSagyo = cYosoSagyogun.aKishuSagyo[nKishuId];

						if (cKishuSagyo.nGroupNo != 0) {
							// ���@��̗v�f��ƌQ�ɂ�郋�[�v
							for (int j = 0; j < nSize; j++) {
								SYosoSagyogun &cYosoSagyogun2 = cSubline.aYosoSagyogun[cKotei[j]];

								// ���@��ɂ�郋�[�v
								for (int nKishuId2 = nKishuId + 1; nKishuId2 < nKishuNum; nKishuId2++) {
									SKishuSagyo &cKishuSagyo2 = cYosoSagyogun2.aKishuSagyo[nKishuId2];
									if (cKishuSagyo.nGroupNo == cKishuSagyo2.nGroupNo) {
										cKishuSagyo.bKyotsuKotei = TRUE;
										cKishuSagyo2.bKyotsuKotei = TRUE;
									}
								}
							}
						}
					}
				}
			}
		}
	}
}

// �H���P�ʂ̋��ʍH���O���[�v�ԍ��擾
void CDataManager::GetKyotsuKoteiGroup(int nSublineId, CKyotsuKoteiGroupArray &aKyotsuKoteiGroup)
{
	SSubline &cSubline = m_aSubline[nSublineId];
	int nKishuId, nKishuId2;

	aKyotsuKoteiGroup.RemoveAll();

	// �H���E�@�킲�ƂɃO���[�v�ԍ����擾
	int nKishuNum = m_aKishu.GetSize();
	int nYosoSagyogunNum = cSubline.aYosoSagyogun.GetSize();
	for (int nYosoSagyogunId = 0; nYosoSagyogunId < nYosoSagyogunNum; nYosoSagyogunId++) {
		SYosoSagyogun &cYosoSagyogun = cSubline.aYosoSagyogun[nYosoSagyogunId];
		if (!cYosoSagyogun.CheckDeleted()) {
			SKyotsuKoteiGroup *pKyotsuKoteiGroup = aKyotsuKoteiGroup.LookupElement(cYosoSagyogun.sKoteiName);
			if (pKyotsuKoteiGroup == NULL) {
				pKyotsuKoteiGroup = aKyotsuKoteiGroup.AddElement(cYosoSagyogun.sKoteiName);
				pKyotsuKoteiGroup->sKoteiName = cYosoSagyogun.sKoteiName;
				pKyotsuKoteiGroup->aKishuGroupNo.SetSize(nKishuNum);
			}

			for (nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
				SKishuSagyo &cKishuSagyo = cYosoSagyogun.aKishuSagyo[nKishuId];

				if (cKishuSagyo.nGroupNo != 0)
					pKyotsuKoteiGroup->aKishuGroupNo[nKishuId].aGroupNo.Add(cKishuSagyo.nGroupNo);
			}
		}
	}

	// ����O���[�v�ԍ����܂ލH���ɓ����ԍ���^����
	int nKoteiGroupNo = 0;
	int nSize = aKyotsuKoteiGroup.GetSize();
	for (int i = 0; i < nSize; i++) {
		SKyotsuKoteiGroup &cKyotsuKoteiGroup = aKyotsuKoteiGroup[i];
		for (nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
			SKyotsuKoteiGroup::SKishuGroupNo &cKishuGroupNo = cKyotsuKoteiGroup.aKishuGroupNo[nKishuId];
			if (cKishuGroupNo.nKoteiGroupNo == 0) {
				int nSize2 = cKishuGroupNo.aGroupNo.GetSize();
				if (nSize2 != 0) {
					cKishuGroupNo.nKoteiGroupNo = ++nKoteiGroupNo;
					for (int j = 0; j < nSize2; j++) {
						for (nKishuId2 = nKishuId + 1; nKishuId2 < nKishuNum; nKishuId2++) {
							SKyotsuKoteiGroup::SKishuGroupNo &cKishuGroupNo2 = cKyotsuKoteiGroup.aKishuGroupNo[nKishuId2];
							if (cKishuGroupNo2.aGroupNo.Find(cKishuGroupNo.aGroupNo[j]) != -1)
								cKishuGroupNo2.nKoteiGroupNo = nKoteiGroupNo;
						}
					}
				}
			}
		}
	}
}

// �ŏ��̖ڕW�^�N�g�̃T�u���C�����擾
int CDataManager::GetMinTactSubline()
{
	// �L�b�g������Ă���T�u���C���̒��ōŏ��̃^�N�g���擾
	double fTact = 0;
	int nMinSublineId = -1;
	int nSublineNum = m_aSubline.GetSize();
	for (int nSublineId = 0; nSublineId < nSublineNum; nSublineId++) {
		SSubline &cSubline = m_aSubline[nSublineId];

		if (cSubline.bSetKitting) {
			if (fTact == 0 || cSubline.cSeisanJoken.fTact < fTact) {
				fTact = cSubline.cSeisanJoken.fTact;
				nMinSublineId = nSublineId;
			}
		}
	}

	return nMinSublineId;
}

// �L�b�g����Ƃ̖ڕW�^�N�g���擾
double CDataManager::GetKittingTact()
{
	int nSublineId = GetMinTactSubline();
	if (nSublineId != -1)
		return m_aSubline[nSublineId].cSeisanJoken.fTact;

	return 0;
}

void CDataManager::SetSagyojun()
{
	// �T�u���C���ʃf�[�^�쐬
	int nSublineNum = GetSublineNum();
	for (int nSublineId = 0; nSublineId < nSublineNum; nSublineId++)
		SetSagyojunSub(nSublineId);
}

// ���ʍH�����A������悤�ɍ�Ə���ݒ肷��
void CDataManager::SetSagyojunSub(int nSublineId)
{
	SSubline *pSubline = GetSublineData(nSublineId);
	CYosoSagyogunArray &aYosoSagyogun = pSubline->aYosoSagyogun;
	CSagyojunArray aSagyojun;
	CYosoSagyogunJunbanArray aYosoSagyogunJunban;
	int i, j;
// --Add 2007/02/28 aizawa �s��D-189-1
	int nInsertIndex;
// --Add

	int nKishuNum = GetKishuNum();
	for (int nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
		GetSagyojun(nSublineId, nKishuId, aSagyojun);
		int nSize = aSagyojun.GetSize();

// --Add 2007/02/26 aizawa �s��D-194
		int nSagyoshaId2 = -1;
// --Add
		int nIndex = 0;
		for (i = 0; i < aYosoSagyogunJunban.GetSize(); i++) {
			SYosoSagyogunJunban &cYosoSagyogunJunban = aYosoSagyogunJunban[i];
			int nGroupNo = cYosoSagyogunJunban.nGroupNo;
			int nSagyoshaId = cYosoSagyogunJunban.nSagyoshaId;
			CString sKoteiName = cYosoSagyogunJunban.sKoteiName;
			BOOL bKyotsuKotei = cYosoSagyogunJunban.bKyotsuKotei;

// --Add 2007/02/28 aizawa �s��D-189-1
			if (nSagyoshaId2 != nSagyoshaId)
				nInsertIndex = i;
// --Add

			if (nGroupNo != 0) {
				BOOL bInsertKyotsuKotei = FALSE;
// --Add 2007/02/28 aizawa �s��D-189-1
				BOOL bExistKyotsuKotei = FALSE;
// --Add
				for (j = 0; j < nSize; j++) {
					SSagyojun &cSagyojun = aSagyojun[j];
					SKishuSagyo &cKishuSagyo = aYosoSagyogun[cSagyojun.nYosoSagyogunId].aKishuSagyo[nKishuId];

// --Add 2007/02/28 aizawa �s��D-189-1
					int nSize2 = aYosoSagyogunJunban.GetSize();
					for (int k = i; k < nSize2; k++) {
						if (aYosoSagyogunJunban[k].nSagyoshaId != nSagyoshaId)
							break;

						if (aYosoSagyogunJunban[k].nGroupNo == cKishuSagyo.nGroupNo) {
							bExistKyotsuKotei = TRUE;
							break;
						}
					}
// --Add

					if (cKishuSagyo.nGroupNo == nGroupNo ||
// --Change 2007/02/28 aizawa �s��D-189-1
//							(!bKyotsuKotei && !bInsertKyotsuKotei && aYosoSagyogun[cSagyojun.nYosoSagyogunId].sKoteiName == sKoteiName) ||
							(!bKyotsuKotei && !bInsertKyotsuKotei && !bExistKyotsuKotei && cKishuSagyo.nSagyoshaId == nSagyoshaId && aYosoSagyogun[cSagyojun.nYosoSagyogunId].sKoteiName == sKoteiName) ||
// --Change
// --Add 2007/02/26 aizawa �s��D-194
// --Change 2007/02/27 aizawa �s��D-194 �i��Ǝ҂����蓖�Ă��Ă��Ȃ��ꍇ�ɑΉ��j
//							(nSagyoshaId2 != nSagyoshaId && cKishuSagyo.nSagyoshaId == nSagyoshaId2)) {
							(nSagyoshaId2 != -1 && nSagyoshaId2 != nSagyoshaId && cKishuSagyo.nSagyoshaId == nSagyoshaId2)) {
// --Change
// --Add
						for ( ; nIndex <= j; nIndex++) {
							SYosoSagyogun &cYosoSagyogun = aYosoSagyogun[aSagyojun[nIndex].nYosoSagyogunId];
							SYosoSagyogunJunban cYosoSagyogunJunban2;
							cYosoSagyogunJunban2.nYosoSagyogunId = aSagyojun[nIndex].nYosoSagyogunId;
							cYosoSagyogunJunban2.nKishuId = nKishuId;
							cYosoSagyogunJunban2.nGroupNo = cYosoSagyogun.aKishuSagyo[nKishuId].nGroupNo;
							cYosoSagyogunJunban2.nSagyoshaId = cYosoSagyogun.aKishuSagyo[nKishuId].nSagyoshaId;
							cYosoSagyogunJunban2.sKoteiName = cYosoSagyogun.sKoteiName;
							cYosoSagyogunJunban2.bKyotsuKotei = cYosoSagyogun.aKishuSagyo[nKishuId].bKyotsuKotei;
// --Change 2007/02/28 aizawa �s��D-189-1
//							aYosoSagyogunJunban.InsertAt(i, cYosoSagyogunJunban2);
							if (cYosoSagyogun.aKishuSagyo[nKishuId].nSagyoshaId == nSagyoshaId)
								aYosoSagyogunJunban.InsertAt(i, cYosoSagyogunJunban2);
							else
								aYosoSagyogunJunban.InsertAt(nInsertIndex++, cYosoSagyogunJunban2);
// --Change
							i++;
						}
						if (cKishuSagyo.nGroupNo == nGroupNo)
							bInsertKyotsuKotei = TRUE;

// --Add 2007/02/28 aizawa �s��D-189-1
						bExistKyotsuKotei = FALSE;
// --Add
					}
				}
			}
// --Add 2007/02/26 aizawa �s��D-194
			nSagyoshaId2 = nSagyoshaId;
// --Add
		}

		for ( ; nIndex < nSize; nIndex++) {
			SYosoSagyogun &cYosoSagyogun = aYosoSagyogun[aSagyojun[nIndex].nYosoSagyogunId];
			SYosoSagyogunJunban cYosoSagyogunJunban2;
			cYosoSagyogunJunban2.nYosoSagyogunId = aSagyojun[nIndex].nYosoSagyogunId;
			cYosoSagyogunJunban2.nKishuId = nKishuId;
			cYosoSagyogunJunban2.nGroupNo = cYosoSagyogun.aKishuSagyo[nKishuId].nGroupNo;
			cYosoSagyogunJunban2.nSagyoshaId = cYosoSagyogun.aKishuSagyo[nKishuId].nSagyoshaId;
			cYosoSagyogunJunban2.sKoteiName = cYosoSagyogun.sKoteiName;
			cYosoSagyogunJunban2.bKyotsuKotei = cYosoSagyogun.aKishuSagyo[nKishuId].bKyotsuKotei;
			aYosoSagyogunJunban.Add(cYosoSagyogunJunban2);
		}
	}

	int nSagyojun = 1;
	int nSize = aYosoSagyogunJunban.GetSize();
	for (i = 0; i < nSize; i++) {
		SYosoSagyogunJunban &cYosoSagyogunJunban = aYosoSagyogunJunban[i];
		aYosoSagyogun[cYosoSagyogunJunban.nYosoSagyogunId].aKishuSagyo[cYosoSagyogunJunban.nKishuId].nSagyojun = nSagyojun++;
	}
}
