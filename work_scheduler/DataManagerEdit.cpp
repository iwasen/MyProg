//*****************************************************************************************************
//  1. �t�@�C����
//		DataManagerEdit.cpp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		CDataManager �N���X�̃C���v�������e�[�V�����i�ҏW�֘A�j
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
//		CDataManager::KoteiBunkatsu
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�v�f��ƌQ��������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		int				nSublineId			[I] �T�u���C��ID
//		CString			&sKoteiName			[I] �H����
//		CStringArray	&aYosoSagyogun		[I] ��������v�f��ƌQ���̔z��
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�w�肳�ꂽ�H�����̍H���𕪊�����B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CDataManager::KoteiBunkatsu(int nSublineId, const CString &sKoteiName, CStringArray &aYosoSagyogunName)
{
	SSubline &cSubline = m_aSubline[nSublineId];
	CYosoSagyogunArray &aYosoSagyogun = cSubline.aYosoSagyogun;
	int i, j;

	// �w�肳�ꂽ�v�f��Ƃ̐����擾
	int nYosoSagyogun = aYosoSagyogunName.GetSize();
	if (nYosoSagyogun == 0)
		return;

	// �w�肳�ꂽ�H���̗v�f��ƌQ���擾
	SKotei cKotei;
	GetKotei(nSublineId, sKoteiName, cKotei);

	// �V�����H����
	CString sNewKoteiName1 = NewKoteiName(nSublineId, sKoteiName, 1);
	CString sNewKoteiName2 = NewKoteiName(nSublineId, sKoteiName, 2);

	// �H������"_1","_2"��t������
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
//  1. �֐���
//		CDataManager::YosoSagyogunBunkatsu
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�v�f��ƌQ��������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		int			nSublineId				[I] �T�u���C��ID
//		CString		&sKoteiName				[I] �H����
//		CString		&sYosoSagyogunName		[I] �v�f��ƌQ��
//		CIntArrayEx	&cYosoSagyoArray		[I] ��������v�f��Ƃ̔z��
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�w�肳�ꂽ�H�����A�v�f��ƌQ���̗v�f��ƌQ�𕪊�����B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CDataManager::YosoSagyogunBunkatsu(int nSublineId, const CString &sKoteiName, const CString &sYosoSagyogunName, CIntArrayEx &aYosoSagyo)
{
	SSubline &cSubline = m_aSubline[nSublineId];
	CYosoSagyogunArray &aYosoSagyogun = cSubline.aYosoSagyogun;
	int i;
	int nYosoSagyogunId;
	SYosoSagyogun *pYosoSagyogun;
	int nIndex;

	// �w�肳�ꂽ�v�f��Ƃ̐����擾
	if (aYosoSagyo.GetSize() == 0)
		return;

	// �v�f��Ƃ��\�[�g
	aYosoSagyo.Sort();

	// �w�肳�ꂽ�H���A�v�f��ƌQ�f�[�^���擾
	int nSize = aYosoSagyogun.GetSize();
	for (nYosoSagyogunId = 0; nYosoSagyogunId < nSize; nYosoSagyogunId++) {
		pYosoSagyogun = &aYosoSagyogun[nYosoSagyogunId];
		if (!pYosoSagyogun->CheckDeleted()) {
			if (pYosoSagyogun->sKoteiName == sKoteiName && pYosoSagyogun->sYosoSagyogunName == sYosoSagyogunName)
				break;
		}
	}

	// ���������v�f��ƌQ���쐬
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

	// ���������v�f��ƌQ��ǉ�
	aYosoSagyogun.InsertAt(nYosoSagyogunId + 1, cYosoSagyogun1);
	aYosoSagyogun.InsertAt(nYosoSagyogunId + 2, cYosoSagyogun2);
}

CString CDataManager::NewKoteiName(int nSublineId, const CString &sName, int nPostfix)
{
	SSubline &cSubline = m_aSubline[nSublineId];
	CYosoSagyogunArray &aYosoSagyogun = cSubline.aYosoSagyogun;
	int i;
	CString sBaseName = sName;

	// ���̔ԍ������Ɏg���Ă����玟�̔ԍ����擾
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
//  1. �֐���
//		CDataManager::NewYosoSagyogunName
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�v�f��ƌQ���̌��ɐ�����t��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		int			nSublineId			[I] �T�u���C��ID
//		CString		&sName		[I] �v�f��ƌQID�z��
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		CString		�������t�����ꂽ�v�f��ƌQ��
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�v�f��ƌQ���̌��ɐ�����t������B���̖��O�����ɑ��݂����玟�̔ԍ��Ƃ���B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
CString CDataManager::NewYosoSagyogunName(int nSublineId, const CString &sName, int nPostfix)
{
	SSubline &cSubline = m_aSubline[nSublineId];
	CYosoSagyogunArray &aYosoSagyogun = cSubline.aYosoSagyogun;
	int i;
	CString sBaseName = sName;

	// ���̔ԍ������Ɏg���Ă����玟�̔ԍ����擾
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

// ��ƎҒǉ�
void CDataManager::InsertSagyosha(int nSublineId, int nSagyoshaId)
{
	if (nSublineId != -1)
		m_aSubline[nSublineId].aSagyosha.InsertAt(nSagyoshaId, "");
	else
		m_aKittingSagyosha.InsertAt(nSagyoshaId, "");

	AdjustSagyoshaId(nSublineId, nSagyoshaId, 1);

	RenameSagyoshaName(nSublineId);
}

// ��Ǝҍ폜
void CDataManager::DeleteSagyosha(int nSublineId, int nSagyoshaId)
{
	if (nSublineId != -1)
		m_aSubline[nSublineId].aSagyosha.RemoveAt(nSagyoshaId);
	else
		m_aKittingSagyosha.RemoveAt(nSagyoshaId);

	AdjustSagyoshaId(nSublineId, nSagyoshaId, -1);

	RenameSagyoshaName(nSublineId);
}

// ��Ǝ�ID�␳
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

// �w�肵����Ǝ҂ɍ�Ƃ����邩�`�F�b�N
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

// �N���b�v�{�[�h�ɗv�f��ƌQ���R�s�[
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

// �N���b�v�{�[�h����v�f��ƌQ��\��t��
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

				// ��ƎҖ�
				pKishuCols = m_eYosoSagyoHyo.m_aKishuCols[0].aKishuCols.LookupElement(m_aKishu[nKishuId].sKishuName);
				m_eYosoSagyoHyo.PutElement(nRow, pKishuCols->nCol, cSubline.aSagyosha[nSagyoshaId]);

				// �v�f��Ə���
				pKishuCols = m_eYosoSagyoHyo.m_aKishuCols[1].aKishuCols.LookupElement(m_aKishu[nKishuId].sKishuName);
				m_eYosoSagyoHyo.PutElement(nRow, pKishuCols->nCol, 1);

				cSubline.aRowArray.Add(nRow);
			}

			// ��Ə��擾
			CSagyojunArray aSagyojun, aSagyojun2;
			GetSagyojun(nSublineId, -1, aSagyojun);

			// �ǉ�����O�̗v�f��ƌQ���擾
			int nYosoSagyogunNum1 = cSubline.aYosoSagyogun.GetSize();

			// �v�f��ƌQ���o
			ExtractYosoSagyogunSub(nSublineId, nLastRowIndex);

			// �ǉ�������̗v�f��ƌQ���擾
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
	// �H���K���ᔽ�t�@�C��
	SaveKojunKiseiError();
// By Y.Itabashi (xxxxx) 2007.02.14

	return bOK;
}

// �v�f��ƌQID�z�񂩂�v�f��ƌQ�z����擾
void CDataManager::GetYosoSagyogun(int nSublineId, CIntArrayEx &aYosoSagyogunId, CYosoSagyogunArray &aYosoSagyogun)
{
	aYosoSagyogun.RemoveAll();

	SSubline &cSubline = m_aSubline[nSublineId];
	int nSize = aYosoSagyogunId.GetSize();
	for (int i = 0; i < nSize; i++)
		aYosoSagyogun.Add(cSubline.aYosoSagyogun[aYosoSagyogunId[i]]);
}

// �v�f��ƌQ�폜
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

// �v�f��ƌQ�ǉ�
void CDataManager::AddYosoSagyogun(int nSublineId, CYosoSagyogunArray &aYosoSagyogun)
{
	SSubline &cSubline = m_aSubline[nSublineId];

	cSubline.aYosoSagyogun.Append(aYosoSagyogun);
}

// �ŐV�ړ��t���O�N���A
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

// �v�f��ƕ\�ɂP�s�ǉ�
int CDataManager::AddYosoSagyoHyo()
{
	int nRow = m_eYosoSagyoHyo.AddRows();
	m_eYosoSagyoHyo.SetDefaultData(nRow);

	return nRow;
}

// --Add 2007/02/26 aizawa �s��D-187
// ���No���i���o����
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
