//*****************************************************************************************************
//  1. �t�@�C����
//		ManualEditDocument.cpp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		CManualEditDocument �N���X�̃C���v�������e�[�V����
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************

#include "stdafx.h"
#include "WorkScheduler.h"

#include "MenuDispCtrl.h"

#include "ManualEditWnd001.h"
#include "AboutDlg.h"
#include "ManualEditDocument.h"
#include "ManualEditFrameWnd.h"
#include "ManualViewTypeDlg.h"
#include "DataManager.h"
#include "SetKittingDlg.h"
#include "SetKyotsuKoteiDlg.h"
#include "KatashikiKiseiMatrixDlg.h"
#include "ManualEditSublineDlg.h"
#include "SaveProjectDlg.h"
#include "ManualEditView1.h"

// Modify ... ( ADD )
#include "FileExportDlg.h"
// By K.Kimura ( xxxxx )07.02.06

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

IMPLEMENT_DYNCREATE(CManualEditDocument, CDocument)

//*****************************************************************************************************
//  1. �֐���
//		CManualEditDocument::CManualEditDocument
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
//		�N���X�ϐ��̏������A�^�C�g���\���A�O���t�f�[�^�쐬���s���B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
CManualEditDocument::CManualEditDocument()
{
	// �N���X�ϐ�������
// Modify ... ( DELETE )
//	m_nGraphKishu = 0;
// By Y.Itabashi (xxxxx) 2007.02.26
	m_nGraphKind = 0;
	m_bSlimMode = FALSE;
	m_pDrop = NULL;
	m_nKittingIndex = -1;
	m_bDispNewMove = TRUE;
	m_bSelectKitting = FALSE;
// Modify ... ( ADD )
	m_aSelectYosoSagyogun.RemoveAll();
	m_pSelectWnd = NULL;
	m_pDragWnd = NULL;
// By Y.Itabashi (xxxxx) 2007.02.27

	// �ŐV�ړ��t���O�N���A
	g_pDataManager->ClearNewMoveFlag();

	// �L�b�g�������t���O�N���A
	g_pDataManager->ClearKittingCancelFlag();

	// �^�C�g���\��
	SetWindowTitle();

	// �O���t�f�[�^�쐬
	g_pDataManager->SetSagyojun();
	MakeGraphData();

	// Undo�o�b�t�@���Z�b�g
	g_pDataManager->ResetUndoBuf();

}


BEGIN_MESSAGE_MAP(CManualEditDocument, CDocument)
	//{{AFX_MSG_MAP(CManualEditDocument)
	ON_BN_CLICKED(IDC_RADIO_GRAPH_KOTEI, OnRadioGraphKotei)
	ON_BN_CLICKED(IDC_RADIO_GRAPH_YOSOSAGYOGUN, OnRadioGraphYosoSagyogun)
	ON_COMMAND(IDM_WINDOW_VIEW_TYPE, OnWindowViewType)
	ON_COMMAND(IDM_HELP_VERSION, OnHelpVersion)
	ON_COMMAND(IDM_VIEW_SLIM_GRAPH, OnViewSlimGraph)
	ON_UPDATE_COMMAND_UI(IDM_VIEW_SLIM_GRAPH, OnUpdateViewSlimGraph)
	ON_COMMAND(IDM_VIEW_SELECT_SUBLINE, OnViewSelectSubline)
	ON_COMMAND(IDM_TOOL_KITTING_SET, OnToolKittingSet)
	ON_COMMAND(IDM_TOOL_LINE_KISEI, OnToolLineKisei)
	ON_COMMAND(IDM_TOOL_KATASHIKI_KISEI, OnToolKatashikiKisei)
	ON_COMMAND(IDM_TOOL_KYOTSU_KOTEI, OnToolKyotsuKotei)
	ON_UPDATE_COMMAND_UI(IDM_TOOL_LINE_KISEI, OnUpdateToolLineKisei)
	ON_UPDATE_COMMAND_UI(IDM_TOOL_KATASHIKI_KISEI, OnUpdateToolKatashikiKisei)
	ON_COMMAND(IDM_FILE_SAVE, OnFileSave)
	ON_COMMAND(IDM_FILE_SAVE_AS, OnFileSaveAs)
	ON_COMMAND(IDM_EDIT_UNDO, OnEditUndo)
	ON_UPDATE_COMMAND_UI(IDM_EDIT_UNDO, OnUpdateEditUndo)
	ON_COMMAND(IDM_EDIT_REDO, OnEditRedo)
	ON_UPDATE_COMMAND_UI(IDM_EDIT_REDO, OnUpdateEditRedo)
	ON_COMMAND(IDM_WINDOW_SEIRETSU, OnWindowSeiretsu)
	ON_COMMAND(IDM_FILE_EXPORT, OnFileExport)
	ON_UPDATE_COMMAND_UI(IDM_TOOL_KITTING_SET, OnUpdateToolKittingSet)
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()

//*****************************************************************************************************
//  1. �֐���
//		CManualEditDocument::OnSelchangeComboKishu
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�@��R���{�{�b�N�X�I��ύX����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�O���t��I�����ꂽ�@��ɕύX����B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
/*void CManualEditDocument::OnSelchangeComboKishu()
{
	CManualEditFrameWnd *pFrameWnd = (CManualEditFrameWnd *)AfxGetMainWnd();

	// �O���t��I�����ꂽ�@��ɕύX
	ChangeGraphKishu(pFrameWnd->GetSelectedKishu());
}*/

//*****************************************************************************************************
//  1. �֐���
//		CManualEditDocument::OnRadioGraphKotei
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�H���\���{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�O���t���H���\���ɕύX����B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CManualEditDocument::OnRadioGraphKotei()
{
	// �O���t���H���\���ɕύX
	ChangeGraphKind(GRAPH_KOTEI);
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditDocument::OnRadioGraphYosoSagyogun
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�v�f��ƌQ�\���{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�O���t���H���\���ɕύX����B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CManualEditDocument::OnRadioGraphYosoSagyogun()
{
	// �O���t��v�f��ƌQ�\���ɕύX
	ChangeGraphKind(GRAPH_YOSO_SAGYOGUN);
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditDocument::MakeGraphData
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�O���t�\���f�[�^�쐬
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�O���t�\���ɕK�v�ȃf�[�^���쐬����B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CManualEditDocument::MakeGraphData()
{
	// �����̃O���t�f�[�^���폜
	m_aGraphSubline.RemoveAll();

	// �T�u���C���ʃf�[�^�쐬
	int nSublineNum = g_pDataManager->GetSublineNum();
	for (int nSublineId = 0; nSublineId < nSublineNum; nSublineId++) {
		SSubline *pSubline = g_pDataManager->GetSublineData(nSublineId);

		if (pSubline->bManualEditFlag)
			MakeGraphDataSubline(nSublineId);
	}

	// �L�b�g���f�[�^�쐬
	MakeGraphDataKitting();
}

void CManualEditDocument::MakeGraphDataSubline(int nSublineId)
{
	SSubline *pSubline = g_pDataManager->GetSublineData(nSublineId);
	CYosoSagyogunArray &aYosoSagyogun = pSubline->aYosoSagyogun;
	double fTime;
	SGraphSagyosha *pGraphSagyosha;
	CString sSagyoshaName;
	int nSagyoshaId;
	int nYosoSagyogunId;
	CKyotsuKoteiGroupArray aKyotsuKoteiGroup;
	int aKoteiGraphBlockIndex[MAX_KISHU];
	CSagyojunArray aSagyojun;

	g_pDataManager->GetKyotsuKoteiGroup(nSublineId, aKyotsuKoteiGroup);

	SGraphSubline *pGraphSubline = m_aGraphSubline.AddElement();
	pGraphSubline->nSublineId = nSublineId;
	pGraphSubline->sSublineName = pSubline->sSublineName;

	// �S���Y�䐔�A��ƎҐ��A�@�퐔���擾
// Modify ... ( CHANGE )
	m_nTotalSeisanDaisu = g_pDataManager->GetTotalSeisanDaisu( nSublineId );
//	m_nTotalSeisanDaisu = g_pDataManager->GetTotalSeisanDaisu();
// By Y.Itabashi (xxxxx) 2007.02.11
	int nSagyoshaNum = g_pDataManager->GetSagyoshaNum(nSublineId);
	int nKishuNum = g_pDataManager->GetKishuNum();

	g_pDataManager->GetSagyojun(nSublineId, -1, aSagyojun);
	int nSagyojunNum = aSagyojun.GetSize();

	// ��Ǝ҂ɂ�郋�[�v
// Modify ... ( CHANGE )
// --Delete 2007/03/06 aizawa �s��(83)
/*
	int nCheckOKSagyoshaNo = -1;
	// ��ƎҔԍ����ɕ\��������
	for( int icnt = 0; icnt < nSagyoshaNum; icnt++ ){

		// ��ƎҔԍ������������̂��珇�ɍ�Ǝ�ID���擾���ăO���t���쐬
		CString sSagyoshaNo;
		CString sCheckSagyoshaNo;
		sCheckSagyoshaNo.Format( "%03d", icnt+1 );
		int jcnt = 0;
		int nCheckSagyoshaNo = 9999;
		while ( jcnt < nSagyoshaNum ){
			sSagyoshaName = g_pDataManager->GetSagyoshaName(nSublineId, jcnt);
			sSagyoshaNo = sSagyoshaName.Mid( 1, 3 );
			int nSagyoshaNo = atoi( (LPCTSTR)sSagyoshaNo );
			if( nCheckOKSagyoshaNo < nSagyoshaNo && nSagyoshaNo < nCheckSagyoshaNo ){
				nCheckSagyoshaNo = nSagyoshaNo;
				nSagyoshaId = jcnt;
			}
			jcnt++;
		}
		nCheckOKSagyoshaNo = nCheckSagyoshaNo;
*/
// --Delete
//	for (nSagyoshaId = 0; nSagyoshaId < nSagyoshaNum; nSagyoshaId++) {
// --Add 2007/03/06 aizawa �s��(83)
	for (nSagyoshaId = 0; nSagyoshaId < nSagyoshaNum; nSagyoshaId++) {
// --Add
// By Y.Itabashi (xxxxx) 2007.02.11
		// ��ƎҖ����擾
		sSagyoshaName = g_pDataManager->GetSagyoshaName(nSublineId, nSagyoshaId);

		// ��Ǝ҂��Ƃ̏����쐬
		pGraphSagyosha = pGraphSubline->aGraphSagyosha.AddElement(sSagyoshaName);
//		pGraphSagyosha->sSagyoshaDispName = "��Ǝ�" + sSagyoshaName.Mid(1);
		pGraphSagyosha->sSagyoshaDispName = sSagyoshaName;
		pGraphSagyosha->sSagyoshaName = sSagyoshaName;
		pGraphSagyosha->fTime = 0;
		pGraphSagyosha->nSagyoshaId = nSagyoshaId;
		pGraphSagyosha->bKitting = FALSE;

		// �@��ɂ�郋�[�v
		for (int nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
			SKishu *pKishu = g_pDataManager->GetKishuData(nKishuId);

			// �@�킲�Ƃ̏����쐬
			SGraphKishu *pGraphKishu = pGraphSagyosha->aKishu.AddElement();
			pGraphKishu->sKishuName = pKishu->sKishuName;
			pGraphKishu->fTime = 0;

			aKoteiGraphBlockIndex[nKishuId] = -1;
		}

		// �v�f��ƌQ�ɂ�郋�[�v
		for (int i = 0; i < nSagyojunNum; i++) {
			SSagyojun &cSagyojun = aSagyojun[i];
			nYosoSagyogunId = cSagyojun.nYosoSagyogunId;
			nKishuId = cSagyojun.nKishuId;

			SYosoSagyogun &cYosoSagyogun = pSubline->aYosoSagyogun[nYosoSagyogunId];
			if (!cYosoSagyogun.CheckDeleted()) {
				fTime = cYosoSagyogun.GetTime();

				if (aKoteiGraphBlockIndex[nKishuId] != -1) {
					if (pGraphSagyosha->aKotei[aKoteiGraphBlockIndex[nKishuId]].sKoteiName != cYosoSagyogun.sKoteiName)
						aKoteiGraphBlockIndex[nKishuId] = -1;
				}

				SKishuSagyo &cKishuSagyo = cYosoSagyogun.aKishuSagyo[nKishuId];

				if (nSagyoshaId == cKishuSagyo.nSagyoshaId) {
					SGraphKishu &cGraphKishu = pGraphSagyosha->aKishu[nKishuId];

					// �H���O���t�p�f�[�^
					SGraphBlock *pKoteiBlock;
					if (aKoteiGraphBlockIndex[nKishuId] == -1) {
						pKoteiBlock = pGraphSagyosha->aKotei.AddElement(aKoteiGraphBlockIndex[nKishuId]);
						pKoteiBlock->nSublineId = nSublineId;
						pKoteiBlock->nKishuId = nKishuId;
						pKoteiBlock->sKoteiName = cYosoSagyogun.sKoteiName;
						pKoteiBlock->fTime = 0;
						pKoteiBlock->sJido = cYosoSagyogun.sJidoKotei;
						pKoteiBlock->bKyotsuKotei = FALSE;
						pKoteiBlock->bKitting = FALSE;
						pKoteiBlock->bNewMove = cKishuSagyo.bNewMove;
						pKoteiBlock->bKittingCancel = FALSE;

						SKyotsuKoteiGroup *pKyotsuKoteiGroup = aKyotsuKoteiGroup.LookupElement(cYosoSagyogun.sKoteiName);
						if (pKyotsuKoteiGroup != NULL)
							pKoteiBlock->nGroupNo = pKyotsuKoteiGroup->aKishuGroupNo[nKishuId].nKoteiGroupNo;
						else
							pKoteiBlock->nGroupNo = 0;
					} else
						pKoteiBlock = &pGraphSagyosha->aKotei[aKoteiGraphBlockIndex[nKishuId]];
						
					pKoteiBlock->fTime += fTime;
					pKoteiBlock->bKyotsuKotei |= cKishuSagyo.bKyotsuKotei;
					pKoteiBlock->bKittingCancel |= cYosoSagyogun.bKittingCancel;
					pKoteiBlock->aYosoSagyogunId.Add(nYosoSagyogunId);

					// �v�f��ƌQ�O���t�p�f�[�^
					SGraphBlock *pYosoSagyogunBlock = pGraphSagyosha->aYosoSagyogun.AddElement();
					pYosoSagyogunBlock->nSublineId = nSublineId;
					pYosoSagyogunBlock->nKishuId = nKishuId;
					pYosoSagyogunBlock->sKoteiName = cYosoSagyogun.sKoteiName;
					pYosoSagyogunBlock->sYosoSagyogunName = cYosoSagyogun.sYosoSagyogunName;
					pYosoSagyogunBlock->fTime = fTime;
// Modify ... ( CHANGE )
					// �\�����́A�v�f��ƌQ�\�������H���̎���敪���Q��
					pYosoSagyogunBlock->sJido = cYosoSagyogun.sJidoKotei;
//					pYosoSagyogunBlock->sJido = cYosoSagyogun.sJidoYosoSagyogun;
// By Y.Itabashi (xxxxx) 2007.02.12
					pYosoSagyogunBlock->bKyotsuKotei = cKishuSagyo.bKyotsuKotei;
					pYosoSagyogunBlock->nGroupNo = cKishuSagyo.nGroupNo;
					pYosoSagyogunBlock->bKitting = FALSE;
					pYosoSagyogunBlock->aYosoSagyogunId.Add(nYosoSagyogunId);
					pYosoSagyogunBlock->bNewMove = cKishuSagyo.bNewMove;
					pYosoSagyogunBlock->bKittingCancel = cYosoSagyogun.bKittingCancel;

					// �@��ʍ��v����
					cGraphKishu.fTime += fTime;

					// ���d����
					SKishu *pKishu = g_pDataManager->GetKishuData(nKishuId);
					pGraphSagyosha->fTime += fTime * pKishu->nSeisanDaisu / m_nTotalSeisanDaisu;
				}
			}
		}
	}

	// �ڕW�^�N�g�y�я㉺���ݒ�
	pGraphSubline->fMokugyoTact = pSubline->cSeisanJoken.fTact;
	pGraphSubline->fJogen = pGraphSubline->fMokugyoTact * (1 + pSubline->cSeisanJoken.fJogen / 100);
	pGraphSubline->fKagen = pGraphSubline->fMokugyoTact * (1 - pSubline->cSeisanJoken.fKagen / 100);
	pGraphSubline->fKadoRitsu = pSubline->cSeisanJoken.fKadoRitsu;
}

void CManualEditDocument::MakeGraphDataKitting()
{
	double fTime;
	SGraphSagyosha *pGraphSagyosha;
	CString sKishuName;
	CString sSagyoshaName;
	CString sKey;
	int nSagyoshaId;
	CString sKoteiName;
	CIntArrayEx aRemoveSagyoshaId;
	int aKoteiGraphBlockIndex[MAX_KISHU];

	SGraphSubline *pGraphSubline = m_aGraphSubline.AddElement(m_nKittingIndex);
	pGraphSubline->nSublineId = -1;
	pGraphSubline->sSublineName = "�L�b�g�����";

	// �S���Y�䐔�A�@�퐔���擾
// Modify ... ( CHANGE )
	m_nTotalSeisanDaisu = g_pDataManager->GetTotalSeisanDaisu( -1 );
//	m_nTotalSeisanDaisu = g_pDataManager->GetTotalSeisanDaisu();
// By Y.Itabashi (xxxxx) 2007.02.11
	int nKishuNum = g_pDataManager->GetKishuNum();

	// ��Ǝ҂ɂ�郋�[�v
	int nSagyoshaNum = g_pDataManager->GetKittingSagyoshaNum();
	for (nSagyoshaId = 0; nSagyoshaId < nSagyoshaNum; nSagyoshaId++) {
		// ��ƎҖ����擾
		sSagyoshaName = g_pDataManager->GetKittingSagyoshaName(nSagyoshaId);

		// ��Ǝ҂��Ƃ̏����쐬
		pGraphSagyosha = pGraphSubline->aGraphSagyosha.AddElement(sSagyoshaName);
		pGraphSagyosha->sSagyoshaDispName = sSagyoshaName.Left(1) + sSagyoshaName.Mid(3);
		pGraphSagyosha->sSagyoshaName = sSagyoshaName;
		pGraphSagyosha->fTime = 0;
		pGraphSagyosha->nSagyoshaId = nSagyoshaId;
		pGraphSagyosha->bKitting = TRUE;

		// �@��ɂ�郋�[�v
		for (int nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
			SKishu *pKishu = g_pDataManager->GetKishuData(nKishuId);

			// �@�킲�Ƃ̏����쐬
			SGraphKishu *pGraphKishu = pGraphSagyosha->aKishu.AddElement();
			pGraphKishu->sKishuName = pKishu->sKishuName;
			pGraphKishu->fTime = 0;
		}
	}

	// �T�u���C���ɂ�郋�[�v
	int nSublineNum = g_pDataManager->GetSublineNum();
	for (int nSublineId = 0; nSublineId < nSublineNum; nSublineId++) {
		SSubline *pSubline = g_pDataManager->GetSublineData(nSublineId);

		sKoteiName.Empty();

		// �v�f��ƌQ�ɂ�郋�[�v
		int nYosoSagyogunNum = pSubline->aYosoSagyogunKit.GetSize();
		for (int nYosoSagyogunId = 0; nYosoSagyogunId < nYosoSagyogunNum; nYosoSagyogunId++) {
			SYosoSagyogun &cYosoSagyogun = pSubline->aYosoSagyogunKit[nYosoSagyogunId];
			if (!cYosoSagyogun.CheckDeleted()) {
				fTime = cYosoSagyogun.GetTime();

				// �H���p�O���t�p�f�[�^������
				if (sKoteiName != cYosoSagyogun.sKoteiName) {
					sKoteiName = cYosoSagyogun.sKoteiName;

					for (int nKishuId = 0; nKishuId < nKishuNum; nKishuId++)
						aKoteiGraphBlockIndex[nKishuId] = -1;
				}

				// �@��ɂ�郋�[�v
				for (int nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
					SKishuSagyo &cKishuSagyo = cYosoSagyogun.aKishuSagyo[nKishuId];
					nSagyoshaId = cYosoSagyogun.aKishuSagyo[nKishuId].nSagyoshaId;
					if (nSagyoshaId != -1) {
						sSagyoshaName = g_pDataManager->GetKittingSagyoshaName(nSagyoshaId);
						pGraphSagyosha = pGraphSubline->aGraphSagyosha.LookupElement(sSagyoshaName);
						if (pSubline->bManualEditFlag) {
							SGraphKishu &cGraphKishu = pGraphSagyosha->aKishu[nKishuId];

							// �H���O���t�p�f�[�^
							SGraphBlock *pKoteiBlock;
							if (aKoteiGraphBlockIndex[nKishuId] == -1) {
								pKoteiBlock = pGraphSagyosha->aKotei.AddElement(aKoteiGraphBlockIndex[nKishuId]);
								pKoteiBlock->nSublineId = nSublineId;
								pKoteiBlock->nKishuId = nKishuId;
								pKoteiBlock->sKoteiName = cYosoSagyogun.sKoteiName;
								pKoteiBlock->fTime = 0;
								pKoteiBlock->sJido = cYosoSagyogun.sJidoKotei;
								pKoteiBlock->bKyotsuKotei = FALSE;
								pKoteiBlock->nGroupNo = 0;
								pKoteiBlock->bKitting = TRUE;
								pKoteiBlock->bNewMove = cKishuSagyo.bNewMove;
								pKoteiBlock->bKittingCancel = FALSE;
							} else
								pKoteiBlock = &pGraphSagyosha->aKotei[aKoteiGraphBlockIndex[nKishuId]];

							pKoteiBlock->fTime += fTime;
							pKoteiBlock->aYosoSagyogunId.Add(nYosoSagyogunId);

							// �v�f��ƌQ�O���t�p�f�[�^
							SGraphBlock *pYosoSagyogunBlock = pGraphSagyosha->aYosoSagyogun.AddElement();
							pYosoSagyogunBlock->nSublineId = nSublineId;
							pYosoSagyogunBlock->nKishuId = nKishuId;
							pYosoSagyogunBlock->sKoteiName = cYosoSagyogun.sKoteiName;
							pYosoSagyogunBlock->sYosoSagyogunName = cYosoSagyogun.sYosoSagyogunName;
							pYosoSagyogunBlock->fTime = fTime;
// Modify ... ( CHANGE )
							// �\�����́A�v�f��ƌQ�\�������H���̎���敪���Q��
							pYosoSagyogunBlock->sJido = cYosoSagyogun.sJidoKotei;
//							pYosoSagyogunBlock->sJido = cYosoSagyogun.sJidoYosoSagyogun;
// By Y.Itabashi (xxxxx) 2007.02.12
							pYosoSagyogunBlock->bKyotsuKotei = FALSE;
							pYosoSagyogunBlock->nGroupNo = 0;
							pYosoSagyogunBlock->bKitting = TRUE;
							pYosoSagyogunBlock->aYosoSagyogunId.Add(nYosoSagyogunId);
							pYosoSagyogunBlock->bNewMove = cKishuSagyo.bNewMove;
							pYosoSagyogunBlock->bKittingCancel = FALSE;

							// �@��ʍ��v����
							cGraphKishu.fTime += fTime;

							// ���d����
							SKishu *pKishu = g_pDataManager->GetKishuData(nKishuId);
							pGraphSagyosha->fTime += fTime * pKishu->nSeisanDaisu / m_nTotalSeisanDaisu;
						} else
							aRemoveSagyoshaId.AddUnique(nSagyoshaId);
					}
				}
			}
		}
	}

	// �\���ΏۊO�̍�Ǝ҂��폜
	for (int i = 0; i < pGraphSubline->aGraphSagyosha.GetSize(); ) {
		SGraphSagyosha &cGraphSagyosha = pGraphSubline->aGraphSagyosha[i];
		if (aRemoveSagyoshaId.Find(cGraphSagyosha.nSagyoshaId) != -1 && cGraphSagyosha.fTime == 0)
			pGraphSubline->aGraphSagyosha.RemoveAt(i);
		else
			i++;
	}

	// �\���Ώۂ̃T�u���C���ɍ�Ǝ҂����Ȃ��ꍇ�͕\�����Ȃ�
	if (pGraphSubline->aGraphSagyosha.GetSize() == 0) {
		m_aGraphSubline.RemoveAt(m_nKittingIndex);
		m_nKittingIndex = -1;
		return;
	}

	// �ڕW�^�N�g�y�я㉺���ݒ�
	nSublineId = g_pDataManager->GetMinTactSubline();
	if (nSublineId != -1) {
		SSubline *pSubline = g_pDataManager->GetSublineData(nSublineId);
		pGraphSubline->fMokugyoTact = pSubline->cSeisanJoken.fTact;
		pGraphSubline->fJogen = pGraphSubline->fMokugyoTact * (1 + pSubline->cSeisanJoken.fJogen / 100);
		pGraphSubline->fKagen = pGraphSubline->fMokugyoTact * (1 - pSubline->cSeisanJoken.fKagen / 100);
		pGraphSubline->fKadoRitsu = pSubline->cSeisanJoken.fKadoRitsu;
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditDocument::ChangeGraphKishu
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�@��ύX����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		int		nGraphKishu			[I] �@��ID
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�w�肳�ꂽ�@��̃O���t�\���ɐ؂�ւ���B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
// Modify ... ( CHANGE )
void CManualEditDocument::ChangeGraphKishu(int nGraphKishu, CFrameWnd *pWnd)
//void CManualEditDocument::ChangeGraphKishu(int nGraphKishu)
// By Y.Itabashi (xxxxx) 2007.02.27
{
// Modify ... ( DELETE )
//	m_nGraphKishu = nGraphKishu;
// By Y.Itabashi (xxxxx) 2007.02.26

// Modify ... ( CHANGE )
	if( pWnd == m_pSelectWnd )
		m_aSelectYosoSagyogun.RemoveAll();
//	m_aSelectedBox.RemoveAll();
// By Y.Itabashi (xxxxx) 2007.02.27

// Modify ... ( CHANGE )
	UpdateAllViews(NULL, UPDATE_GRAPH_KISHU, pWnd);
//	UpdateAllViews(NULL, UPDATE_GRAPH_KISHU);

	AddSelectKoteiOtherWindow();
// By Y.Itabashi (xxxxx) 2007.02.27
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditDocument::ChangeGraphKind
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�O���t��ޕύX����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		int		nGraphKind			[I] �O���t��ށi�H�� or �v�f��ƌQ�j
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�w�肳�ꂽ��ނ̃O���t�\���ɐ؂�ւ���B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CManualEditDocument::ChangeGraphKind(int nGraphKind)
{
	m_nGraphKind = nGraphKind;

// Modify ... ( CHANGE )
	m_aSelectYosoSagyogun.RemoveAll();
	InitReferenceYosoSagyogun();
//	m_aSelectedBox.RemoveAll();
// By Y.Itabashi (xxxxx) 2007.03.04

	UpdateAllViews(NULL, UPDATE_GRAPH_KIND);
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditDocument::UpdateGraph
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�O���t�\�����X�V
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�O���t���ĕ`�悷��B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CManualEditDocument::UpdateGraph(BOOL bSetSagyojun)
{
	if (bSetSagyojun)
		g_pDataManager->SetSagyojun();

	MakeGraphData();
	UpdateAllViews(NULL, UPDATE_GRAPH_UPDATE);
}

void CManualEditDocument::DispCenterSagyosha(int nSublineId, int nSagyoshaId)
{
	UpdateGraph(TRUE);

	SZahyoSagyosha cZahyoSagyosha;
	cZahyoSagyosha.nSublineId = nSublineId;
	cZahyoSagyosha.nSagyoshaId = nSagyoshaId;
	UpdateAllViews(NULL, UPDATE_SCROLL, (CObject *)&cZahyoSagyosha);
}

void CManualEditDocument::OnWindowViewType()
{
	CManualViewTypeDlg dlg;

	if (dlg.DoModal() == IDOK) {
		CManualEditFrameWnd *pFrameWnd = (CManualEditFrameWnd *)AfxGetMainWnd();
		pFrameWnd->DisplayViews(TRUE);
	}
}

void CManualEditDocument::OnHelpVersion()
{
	CAboutDlg dlgAbout;
	dlgAbout.DoModal();
}

void CManualEditDocument::OnViewSlimGraph()
{
	m_bSlimMode = !m_bSlimMode;
	UpdateAllViews(NULL, UPDATE_CHANGE_SLIM);
}

void CManualEditDocument::OnUpdateViewSlimGraph(CCmdUI* pCmdUI)
{
	pCmdUI->SetCheck(m_bSlimMode);
}

void CManualEditDocument::OnViewSelectSubline() 
{
	CManualEditSublineDlg dlg;
	if (dlg.DoModal() == IDOK) {
		// �^�C�g���\��
		SetWindowTitle();

		// �O���t�f�[�^�쐬
		MakeGraphData();

		UpdateAllViews(NULL, UPDATE_CHANGE_SUBLINE);
	}
}

void CManualEditDocument::OnToolKittingSet() 
{
	CSetKittingDlg dlg;
	if (dlg.DoModal() == IDOK) {
		// �L�b�g����Ǝ����Ґ�
		CIntArrayEx aSublineId;
		int nSublineNum = g_pDataManager->GetSublineNum();
		for (int nSublineId = 0; nSublineId < nSublineNum; nSublineId++) {
			SSubline *pSubline = g_pDataManager->GetSublineData(nSublineId);
			if (pSubline->bSagyoshaWaritsuke)
				aSublineId.Add(nSublineId);
		}
		g_pDataManager->JidoHensei1Kitting(aSublineId);

		MakeGraphData();
		UpdateAllViews(NULL, UPDATE_CHANGE_SUBLINE);

		// ���݂̃f�[�^��Undo�o�b�t�@�ɕۑ�
		g_pDataManager->SaveUndoBuf();
	}
}

void CManualEditDocument::OnToolLineKisei() 
{
	g_pDataManager->OpenLineKiseiMatrix();
}

void CManualEditDocument::OnToolKatashikiKisei() 
{
	CKatashikiKiseiMatrixDlg dlg;
	if (dlg.DoModal() == IDOK)
		g_pDataManager->OpenKatashikiKiseiMatrix();
}

void CManualEditDocument::OnToolKyotsuKotei() 
{
	CSetKyotsuKoteiDlg dlg;
	if (dlg.DoModal() == IDOK) {
		MakeGraphData();
		UpdateAllViews(NULL, UPDATE_GRAPH_UPDATE);

		// ���݂̃f�[�^��Undo�o�b�t�@�ɕۑ�
		g_pDataManager->SaveUndoBuf();
	}
}

void CManualEditDocument::OnUpdateToolLineKisei(CCmdUI* pCmdUI) 
{
	pCmdUI->Enable(!g_pDataManager->m_bOpenLineKiseiMatrix);
}

void CManualEditDocument::OnUpdateToolKatashikiKisei(CCmdUI* pCmdUI) 
{
	pCmdUI->Enable(!g_pDataManager->m_bOpenKatashikiKiseiMatrix);
}

void CManualEditDocument::SetWindowTitle()
{
	CString sSubline;
	int nSublineNum = g_pDataManager->GetSublineNum();
	for (int nSublineId = 0; nSublineId < nSublineNum; nSublineId++) {
		SSubline *pSubline = g_pDataManager->GetSublineData(nSublineId);
		if (pSubline->bManualEditFlag) {
			if (!sSubline.IsEmpty())
				sSubline += "/";
			sSubline += pSubline->sSublineName;
		}
	}

	CString sWindowTitle;
// Modify ... ( CHANGE )
	// �^�C�g���Ƀo�[�W�����ԍ���ǉ�
	CString sWindowVersion;
	int j = 0;
	for( int i = 0; i < g_pConst->m_sVersion.GetLength(); i++ ){
		if( g_pConst->m_sVersion[i] == '.' ){
			j++;
			if( j == 2 ){
				sWindowVersion.Format("%s", g_pConst->m_sVersion.Left(i) );
				break;
			}
		}
	}

	CString str = g_pSystem->m_cMenuDisp.GetMenu( Menu_ManualWindowTitle[0].MenuNo, Menu_ManualWindowTitle[0].ItemNo );
	sWindowTitle.Format(str, sSubline, sWindowVersion);
//	sWindowTitle.Format("�T�u���C���F%s - �蓮�Ґ� (Ver.%s)", sSubline, g_pConst->m_sVersion);
// By Y.Itabashi (xxxxx) 2007.01.26
	AfxGetMainWnd()->SetWindowText(sWindowTitle);
}

void CManualEditDocument::OnFileSave() 
{
	if (g_pSystem->DispMessage("Q003003") == IDYES) {
		if (g_pDataManager->SaveFile())
			g_pSystem->DispMessage("I003004");
	}
}

void CManualEditDocument::OnFileSaveAs() 
{
	CSaveProjectDlg dlg;
	if (dlg.DoModal() == IDOK)
		g_pSystem->DispMessage("I002005");
}

void CManualEditDocument::OnEditUndo() 
{
	g_pDataManager->Undo(TRUE);
	MakeGraphData();
	UpdateAllViews(NULL, UPDATE_CHANGE_SUBLINE);
}

void CManualEditDocument::OnUpdateEditUndo(CCmdUI* pCmdUI) 
{
	pCmdUI->Enable(g_pDataManager->CheckUndo());
}

void CManualEditDocument::OnEditRedo() 
{
	g_pDataManager->Redo();
	MakeGraphData();
	UpdateAllViews(NULL, UPDATE_CHANGE_SUBLINE);
}

void CManualEditDocument::OnUpdateEditRedo(CCmdUI* pCmdUI) 
{
	pCmdUI->Enable(g_pDataManager->CheckRedo());
}

void CManualEditDocument::OnWindowSeiretsu() 
{
	CManualEditFrameWnd *pFrameWnd = (CManualEditFrameWnd *)AfxGetMainWnd();
	pFrameWnd->DisplayViews(TRUE);
}

void CManualEditDocument::StopBlink(BOOL bStopBlink)
{
	CManualEditFrameWnd *pFrameWnd = (CManualEditFrameWnd *)AfxGetMainWnd();
	pFrameWnd->StopBlink(bStopBlink);
}

SGraphSubline *CManualEditDocument::GetGraphSublineData(int nSublineId)
{
	int nSize = m_aGraphSubline.GetSize();
	for (int i = 0; i < nSize; i++) {
		if (nSublineId == m_aGraphSubline[i].nSublineId)
			return &m_aGraphSubline[i];
	}

	return NULL;
}

void CManualEditDocument::MoveItem(int nSublineIdFrom, CGraphBlockPtrArray &aGraphBlockFrom, int nSublineIdTo, SGraphBlock *pGraphBlockTo, int nSagyoshaId, BOOL bLastBlock)
{
	if (nSublineIdFrom == nSublineIdTo)
		MoveItem1(nSublineIdFrom, aGraphBlockFrom, pGraphBlockTo, nSagyoshaId, bLastBlock);
	else
		MoveItem2(nSublineIdFrom, aGraphBlockFrom, nSublineIdTo, pGraphBlockTo, nSagyoshaId, bLastBlock);
}

// ����T�u���C�����̈ړ�
void CManualEditDocument::MoveItem1(int nSublineId, CGraphBlockPtrArray &aGraphBlockFrom, SGraphBlock *pGraphBlockTo, int nSagyoshaId, BOOL bLastBlock)
{
	int i, j;

	// �ړ�����
	int nYosoSagyogunIdTo = bLastBlock ? pGraphBlockTo->aYosoSagyogunId[pGraphBlockTo->aYosoSagyogunId.GetSize() - 1] : pGraphBlockTo->aYosoSagyogunId[0];
	int nKishuIdTo = pGraphBlockTo->nKishuId;

	int nSize = aGraphBlockFrom.GetSize();
	if (bLastBlock) {
		for (i = 0; i < nSize; i++) {
			SGraphBlock *pGraphBlockFrom = aGraphBlockFrom[nSize - i - 1];

			int nSize = pGraphBlockFrom->aYosoSagyogunId.GetSize();
			for (j = nSize - 1; j >= 0; j--)
				MoveItem1Sub(nSublineId, pGraphBlockFrom->aYosoSagyogunId[j], pGraphBlockFrom->nKishuId, nYosoSagyogunIdTo, nKishuIdTo, nSagyoshaId, bLastBlock);
		}
	} else {
		for (i = 0; i < nSize; i++) {
			SGraphBlock *pGraphBlockFrom = aGraphBlockFrom[i];

			int nSize = pGraphBlockFrom->aYosoSagyogunId.GetSize();
			for (j = 0; j < nSize; j++)
				MoveItem1Sub(nSublineId, pGraphBlockFrom->aYosoSagyogunId[j], pGraphBlockFrom->nKishuId, nYosoSagyogunIdTo, nKishuIdTo, nSagyoshaId, bLastBlock);
		}
	}
}

void CManualEditDocument::MoveItem1Sub(int nSublineId, int nYosoSagyogunIdFrom, int nKishuIdFrom, int nYosoSagyogunIdTo, int nKishuIdTo, int nSagyoshaId, BOOL bLastBlock)
{
	int i;

	SSubline *pSubline = g_pDataManager->GetSublineData(nSublineId);
	CYosoSagyogunArray &aYosoSagyogun = pSubline->aYosoSagyogun;

	// ��Ə��擾
	CSagyojunArray aSagyojun;
	g_pDataManager->GetSagyojun(nSublineId, -1, aSagyojun);

	// �ړ����̗v�f��ƌQ���폜
	SSagyojun cMoveSagyojun;
	for (i = 0; i < aSagyojun.GetSize(); i++) {
		SSagyojun &cSagyojun = aSagyojun[i];
		if (cSagyojun.nYosoSagyogunId == nYosoSagyogunIdFrom && cSagyojun.nKishuId == nKishuIdFrom) {
			SYosoSagyogun &cYosoSagyogun = aYosoSagyogun[nYosoSagyogunIdFrom];

			cMoveSagyojun = aSagyojun[i];
			aSagyojun.RemoveAt(i);

			// ��Ǝ҂���������
			if (cYosoSagyogun.aKishuSagyo[nKishuIdFrom].nSagyoshaId != -1)
				cYosoSagyogun.aKishuSagyo[nKishuIdFrom].nSagyoshaId = nSagyoshaId;
			break;
		}
	}

	// �ړ���ɑ}��
	int nSagyojunNum = aSagyojun.GetSize();
	for (i = 0; i < nSagyojunNum; i++) {
		SSagyojun &cSagyojun = aSagyojun[i];
		if (cSagyojun.nYosoSagyogunId == nYosoSagyogunIdTo && cSagyojun.nKishuId == nKishuIdTo) {
			if (bLastBlock)
				i++;
			aSagyojun.InsertAt(i, cMoveSagyojun);
			break;
		}
	}

	// �ŐV�ړ��\��
	aYosoSagyogun[nYosoSagyogunIdFrom].aKishuSagyo[nKishuIdFrom].bNewMove = TRUE;
	aYosoSagyogun[nYosoSagyogunIdFrom].nMoveSublineId = nSublineId;

	// �v�f��Ə�����ёւ�
	g_pDataManager->OrderBySagyojun(nSublineId, aSagyojun);
}

// �قȂ�T�u���C���Ԃ̈ړ�
void CManualEditDocument::MoveItem2(int nSublineIdFrom, CGraphBlockPtrArray &aGraphBlockFrom, int nSublineIdTo, SGraphBlock *pGraphBlockTo, int nSagyoshaId, BOOL bLastBlock)
{
	int i, j;
	SSubline *pSublineFrom = g_pDataManager->GetSublineData(nSublineIdFrom);

	// �ړ�����
	int nYosoSagyogunIdTo = bLastBlock ? pGraphBlockTo->aYosoSagyogunId[pGraphBlockTo->aYosoSagyogunId.GetSize() - 1] : pGraphBlockTo->aYosoSagyogunId[0];
	int nKishuIdTo = pGraphBlockTo->nKishuId;
	SSubline *pToSubline = g_pDataManager->GetSublineData(nSublineIdTo);
	SYosoSagyogun *pToYosoSagyogun = g_pDataManager->GetYosoSagyogun(nSublineIdTo, nYosoSagyogunIdTo);

	int nYosoSagyogunNum = pToSubline->aYosoSagyogun.GetSize();
	CSagyojunArray aSagyojunFrom;
	CIntArrayEx aYosoSagyogunIdFrom;
	int nSize = aGraphBlockFrom.GetSize();
	for (i = 0; i < nSize; i++) {
		SGraphBlock *pGraphBlockFrom = aGraphBlockFrom[i];

		int nSize = pGraphBlockFrom->aYosoSagyogunId.GetSize();
		for (j = 0; j < nSize; j++) {
			SSagyojun *pSagyojun = aSagyojunFrom.AddElement();
			pSagyojun->nYosoSagyogunId = nYosoSagyogunNum + aYosoSagyogunIdFrom.AddUnique(pGraphBlockFrom->aYosoSagyogunId[j]);
			pSagyojun->nKishuId = pGraphBlockFrom->nKishuId;
		}
	}

	CYosoSagyogunArray aYosoSagyogun;
	g_pDataManager->GetYosoSagyogun(nSublineIdFrom, aYosoSagyogunIdFrom, aYosoSagyogun);

// --Change 2007/03/02 aizawa �s��D-147
//	g_pDataManager->DeleteYosoSagyogun(nSublineIdFrom, aYosoSagyogunIdFrom, m_nGraphKind == GRAPH_KOTEI ? "1" : "2");
	g_pDataManager->DeleteYosoSagyogun(nSublineIdFrom, aYosoSagyogunIdFrom, "1");
// --Change

	// ��Ǝ҂���������
	nSize = aYosoSagyogun.GetSize();
	for (i = 0; i < nSize; i++) {
		SYosoSagyogun &cYosoSagyogun = aYosoSagyogun[i];
		cYosoSagyogun.sKoteiKigo = pToYosoSagyogun->sKoteiKigo;

		int nKishuNum = cYosoSagyogun.aKishuSagyo.GetSize();
		for (j = 0; j < nKishuNum; j++) {
			SKishuSagyo &cKishuSagyo = cYosoSagyogun.aKishuSagyo[j];
			if (cKishuSagyo.nSagyoshaId != -1) {
				cKishuSagyo.nSagyoshaId = nSagyoshaId;
				cKishuSagyo.bNewMove = TRUE;
			}
		}

		int nYosoSagyoNum = cYosoSagyogun.aYosoSagyo.GetSize();
		for (j = 0; j < nYosoSagyoNum; j++)
// --Change 2007/03/02 aizawa �s��D-147
//			cYosoSagyogun.aYosoSagyo[j].sInsertFlag = m_nGraphKind == GRAPH_KOTEI ? "1" : "2";
			cYosoSagyogun.aYosoSagyo[j].sInsertFlag = "1";
// --Change

		cYosoSagyogun.nMoveSublineId = nSublineIdFrom;
	}

	// ��Ə��擾
	CSagyojunArray aSagyojunTo;
	g_pDataManager->GetSagyojun(nSublineIdTo, -1, aSagyojunTo);

	// �ړ���ɑ}��
	int nSagyojunNum = aSagyojunTo.GetSize();
	for (i = 0; i < nSagyojunNum; i++) {
		SSagyojun &cSagyojun = aSagyojunTo[i];
		if (cSagyojun.nYosoSagyogunId == nYosoSagyogunIdTo && cSagyojun.nKishuId == nKishuIdTo) {
			if (bLastBlock)
				i++;
			aSagyojunTo.InsertAt(i, &aSagyojunFrom);
			break;
		}
	}

	g_pDataManager->AddYosoSagyogun(nSublineIdTo, aYosoSagyogun);

	// �v�f��Ə�����ёւ�
	g_pDataManager->OrderBySagyojun(nSublineIdTo, aSagyojunTo);
}

SGraphBlock *CManualEditDocument::GetFirstGraphBlock(int nSublineId)
{
	return GetNextGraphBlock(nSublineId, -1);
}


SGraphBlock *CManualEditDocument::GetNextGraphBlock(int nSublineId, int nSagyoshaId)
{
	SGraphSubline *pGraphSubline = GetGraphSublineData(nSublineId);
	if (pGraphSubline != NULL) {
		int nSagyoshaNum = pGraphSubline->aGraphSagyosha.GetSize();
		for (nSagyoshaId++; nSagyoshaId < nSagyoshaNum; nSagyoshaId++) {
			SGraphSagyosha &cGraphSagyosha = pGraphSubline->aGraphSagyosha[nSagyoshaId];
			CGraphBlockArray &aGraphBlock = m_nGraphKind == GRAPH_YOSO_SAGYOGUN ? cGraphSagyosha.aYosoSagyogun : cGraphSagyosha.aKotei;
			if (aGraphBlock.GetSize() != 0)
				return &aGraphBlock[0];
		}
	}

	return NULL;
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditDocument::CheckSameKoteiMove
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�H���K���`�F�b�N
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		SGraphBlock		*pGraphBlockFrom	[I] �ړ����f�[�^
//		SGraphBlock		*pGraphBlockTo		[I] �ړ���f�[�^
//		BOOL			bLastBlock			[I] �ړ���̈ʒu�i�Ō�����̃t���O�j�A
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL		TRUE:����CFALSE:�H���K���G���[�L��
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�w�肳�ꂽ�H��������H�����̈ړ����`�F�b�N����B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2007.02.10 Y.Itabashi (xxxxx) �V�K�쐬
//*****************************************************************************************************
BOOL CManualEditDocument::CheckSameKoteiMove( SGraphBlock *pGraphBlockFrom, SGraphBlock *pGraphBlockTo, BOOL bLastBlock )
{
// Modify ... ( CHANGE )
	// �d�l�����m�ɂȂ�܂ŁA�ꎞ�I�Ƀ`�F�b�N���͂���
	return TRUE;
// By Y.Itabashi (xxxxx) 2007.02.12

	int nSublineIdFrom = pGraphBlockFrom->nSublineId;
	int nSublineIdTo = pGraphBlockTo->nSublineId;

	if (nSublineIdFrom != nSublineIdTo)
		return TRUE;

	if (m_nGraphKind != GRAPH_YOSO_SAGYOGUN)
		return TRUE;

	CSagyojunArray aSagyojun;
	SSubline *pSubline = g_pDataManager->GetSublineData(nSublineIdTo);
	int nToYosoSagyogunId = pGraphBlockTo->aYosoSagyogunId[0];
	int nToKishuId = pGraphBlockTo->nKishuId;
	g_pDataManager->GetSagyojun(nSublineIdFrom, -1, aSagyojun);
	int nSagyojunNum = aSagyojun.GetSize();

	for (int i = 0; i < nSagyojunNum; i++) {
		SSagyojun &cSagyojun = aSagyojun[i];
		if (cSagyojun.nYosoSagyogunId == nToYosoSagyogunId && cSagyojun.nKishuId == nToKishuId)
			break;
	}
	int nSagyojunTo = i;

	int nYosoSagyogunIdFrom = pGraphBlockFrom->aYosoSagyogunId[0];
	int nKishuIdFrom = pGraphBlockFrom->nKishuId;
	SSubline *pSublineFrom = g_pDataManager->GetSublineData(nSublineIdFrom);
	CString sKoteiNameFrom = pSublineFrom->aYosoSagyogun[nYosoSagyogunIdFrom].sKoteiName;
	CString sYosoSagyogunNameFrom = pSublineFrom->aYosoSagyogun[nYosoSagyogunIdFrom].sYosoSagyogunName;

	// �ړ�����O�̈ʒu���擾
	for (int j = 0; j < nSagyojunNum; j++) {
		SSagyojun &cSagyojun = aSagyojun[j];
		if (cSagyojun.nYosoSagyogunId == nYosoSagyogunIdFrom && cSagyojun.nKishuId == nKishuIdFrom)
			break;
	}
	int nSagyojunFrom = j;

	int nPrevCheckId = 0;
	int nNextCheckId = 0;

	// �Ō���ւ̈ړ��̏ꍇ�A�ړ���̈ʒu���Ō���ƂȂ邱�Ƃ��l��
	if( bLastBlock )
		nNextCheckId = 1;
	else
		nPrevCheckId = 1;

	int nPrevSagyojun, nNextSagyojun;

	CString sPrevKoteiName, sNextKoteiName;
	CString sPrevYosoSagyogunName, sNextYosoSagyogunName;

	for (nPrevSagyojun = nSagyojunTo - nPrevCheckId; nPrevSagyojun >= 0; nPrevSagyojun--) {
		sPrevKoteiName = pSubline->aYosoSagyogun[aSagyojun[nPrevSagyojun].nYosoSagyogunId].sKoteiName;
		sPrevYosoSagyogunName = pSubline->aYosoSagyogun[aSagyojun[nPrevSagyojun].nYosoSagyogunId].sYosoSagyogunName;
		if (aSagyojun[nPrevSagyojun].nKishuId == nKishuIdFrom)
			break;
	}
	for (nNextSagyojun = nSagyojunTo + nNextCheckId; nNextSagyojun < nSagyojunNum; nNextSagyojun++) {
		sNextKoteiName = pSubline->aYosoSagyogun[aSagyojun[nNextSagyojun].nYosoSagyogunId].sKoteiName;
		sNextYosoSagyogunName = pSubline->aYosoSagyogun[aSagyojun[nNextSagyojun].nYosoSagyogunId].sYosoSagyogunName;
		if (aSagyojun[nNextSagyojun].nKishuId == nKishuIdFrom)
			break;
	}

	if ( (nPrevSagyojun >= 0 && sKoteiNameFrom == sPrevKoteiName)
			|| (nNextSagyojun < nSagyojunNum && sKoteiNameFrom == sNextKoteiName)) {

		// �������瓯��H���̍Ō���ɌQ���ړ��A�㗬���瓯��H���̐擪�ɌQ���ړ��͋���
		if( ( nSagyojunFrom > nSagyojunTo && sPrevKoteiName == sKoteiNameFrom && sNextKoteiName != sKoteiNameFrom ) ||
			( nSagyojunFrom < nSagyojunTo && sPrevKoteiName != sKoteiNameFrom && sNextKoteiName == sKoteiNameFrom ) )
			return TRUE;

		// �I�������H���̏��Ԃ��ς��Ȃ��Ƃ��͈ړ���
		// �Ō���̍�Ǝ҂����̍�Ǝ҂̐擪�Ɉړ��E�擪�̍�Ǝ҂�O�̍�Ǝ҂̍Ō���ֈړ��Ȃ�
		if( ( sPrevKoteiName == sKoteiNameFrom && sPrevYosoSagyogunName == sYosoSagyogunNameFrom ) ||
			( sNextKoteiName == sKoteiNameFrom && sNextYosoSagyogunName == sYosoSagyogunNameFrom ) )
			return TRUE;

		// ��L��̏����Ɉ�v���Ȃ��Ƃ��̓G���[
		g_pSystem->DispMessage("E013005");
		return FALSE;

	}

	return TRUE;
}

// Modify ... ( ADD )
void CManualEditDocument::OnFileExport()
{
	CFileExportDlg dlg;
	if ( dlg.DoModal() == IDOK ) {
		CManualEditFrameWnd *pFrameWnd = (CManualEditFrameWnd *)AfxGetMainWnd();
		pFrameWnd->DisplayViews( TRUE );
	}
}
// By K.Kimura ( xxxxx )07.02.06

void CManualEditDocument::OnUpdateToolKittingSet(CCmdUI* pCmdUI) 
{
	// UP4�p���W���[��
//	pCmdUI->Enable( FALSE );
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditDocument::AddSelectKotei
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�@��ύX����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�w��̍H����I���H���G���A�ɓo�^
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		200702.27 Y.Itabashi (xxxxx) �V�K�쐬
//*****************************************************************************************************
void CManualEditDocument::AddSelectKotei( SZahyoDrag sZahyoDrag, int nGraphKishu )
{
	SSelectYosoSagyogun sSelectSagyo;

	int nCheckIndex = sZahyoDrag.nIndex;
	GetGraphIndex( nCheckIndex, nGraphKishu, sZahyoDrag.pGraphBlock->nKishuId,
		sSelectSagyo.nIndex, sSelectSagyo.nIndexKishu );
	sSelectSagyo.nSublineId			= sZahyoDrag.pGraphBlock->nSublineId;
	sSelectSagyo.nKishuId			= sZahyoDrag.pGraphBlock->nKishuId;
	sSelectSagyo.nSagyoshaId		= sZahyoDrag.nSagyoshaId;
	sSelectSagyo.sKoteiName			= sZahyoDrag.pGraphBlock->sKoteiName;
	sSelectSagyo.sYosoSagyogunName	= sZahyoDrag.pGraphBlock->sYosoSagyogunName;
	sSelectSagyo.bKyotsuKotei		= sZahyoDrag.pGraphBlock->bKyotsuKotei;
	sSelectSagyo.nGroupNo			= sZahyoDrag.pGraphBlock->nGroupNo;
	sSelectSagyo.bKitting			= sZahyoDrag.pGraphBlock->bKitting;

	CSelectYosoSagyogunArray aSelectSagyo;
	aSelectSagyo.RemoveAll();

	BOOL fSetData = 0;
	int nBeforeKishu = -1;
	for( int nSelect = 0; nSelect < m_aSelectYosoSagyogun.GetSize(); nSelect++ ){
		if( nBeforeKishu == -1 )
			nBeforeKishu = m_aSelectYosoSagyogun[nSelect].nKishuId;
		if( sSelectSagyo.nKishuId == m_aSelectYosoSagyogun[nSelect].nKishuId ){

			if( sSelectSagyo.nIndexKishu < m_aSelectYosoSagyogun[nSelect].nIndexKishu ){

				aSelectSagyo.Add( sSelectSagyo );
				fSetData = 1;
				break;

			}else if( sSelectSagyo.nIndexKishu == m_aSelectYosoSagyogun[nSelect].nIndexKishu ){
				if( nGraphKishu == -1 )
					m_aSelectYosoSagyogun[nSelect].nIndex = sSelectSagyo.nIndex;
				fSetData = 2;
				break;

			}else
				aSelectSagyo.Add( m_aSelectYosoSagyogun[nSelect] );

		}else if( nBeforeKishu == sSelectSagyo.nKishuId &&
			nBeforeKishu != sSelectSagyo.nKishuId ){

			aSelectSagyo.Add( sSelectSagyo );
			aSelectSagyo.Add( m_aSelectYosoSagyogun[nSelect] );
			fSetData = 1;
			break;

		}else
			aSelectSagyo.Add( m_aSelectYosoSagyogun[nSelect] );

		nBeforeKishu = m_aSelectYosoSagyogun[nSelect].nKishuId;

	}

	if( fSetData == 0 )
		aSelectSagyo.Add( sSelectSagyo );

	if( fSetData == 1 ){
		for( ; nSelect < m_aSelectYosoSagyogun.GetSize(); nSelect++ ){
			aSelectSagyo.Add( m_aSelectYosoSagyogun[nSelect] );
		}
	}

	if( fSetData != 2 ){

		m_aSelectYosoSagyogun.RemoveAll();

		for( nSelect = 0; nSelect < aSelectSagyo.GetSize(); nSelect++ )
			m_aSelectYosoSagyogun.Add( aSelectSagyo[nSelect] );

	}

	aSelectSagyo.RemoveAll();
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditDocument::AddReferenceKotei
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�@��ύX����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�w��̍H�����Q�ƍH���G���A�ɓo�^
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		200702.27 Y.Itabashi (xxxxx) �V�K�쐬
//*****************************************************************************************************
void CManualEditDocument::AddReferenceKotei( SZahyoDrag sZahyoDrag, int nGraphKishu )
{
	SReferenceYosoSagyogun sRefSagyo;

	int nCheckIndex = sZahyoDrag.nIndex;
	GetGraphIndex( nCheckIndex, nGraphKishu, sZahyoDrag.pGraphBlock->nKishuId,
		sRefSagyo.nIndex, sRefSagyo.nIndexKishu );
	sRefSagyo.nSublineId		= sZahyoDrag.pGraphBlock->nSublineId;
	sRefSagyo.nKishuId			= sZahyoDrag.pGraphBlock->nKishuId;
	sRefSagyo.nSagyoshaId		= sZahyoDrag.nSagyoshaId;
	sRefSagyo.sKoteiName			= sZahyoDrag.pGraphBlock->sKoteiName;
	sRefSagyo.sYosoSagyogunName	= sZahyoDrag.pGraphBlock->sYosoSagyogunName;

	// �I���H���Ɋ܂܂�Ă�����A�o�^���Ȃ�
	for( int nSelect = 0; nSelect < m_aSelectYosoSagyogun.GetSize(); nSelect++ ){
		if( m_aSelectYosoSagyogun[nSelect].nKishuId != sRefSagyo.nKishuId )
			continue;

		if( m_aSelectYosoSagyogun[nSelect].nIndexKishu == sRefSagyo.nIndexKishu )
			return;
	}

	CReferenceYosoSagyogunArray aRefSagyo;
	aRefSagyo.RemoveAll();

	BOOL fSetData = 0;
	int nBeforeKishu = -1;
	for( nSelect = 0; nSelect < m_aRefYosoSagyogun.GetSize(); nSelect++ ){
		if( sRefSagyo.nKishuId == m_aRefYosoSagyogun[nSelect].nKishuId ){

			if( sRefSagyo.nIndexKishu < m_aRefYosoSagyogun[nSelect].nIndexKishu ){

				aRefSagyo.Add( sRefSagyo );
				fSetData = 1;
				break;

			}else if( sRefSagyo.nIndexKishu == m_aRefYosoSagyogun[nSelect].nIndexKishu ){
				fSetData = 2;
				break;

			}else
				aRefSagyo.Add( m_aRefYosoSagyogun[nSelect] );

		}else if( nBeforeKishu == sRefSagyo.nKishuId &&
			nBeforeKishu != sRefSagyo.nKishuId ){

			aRefSagyo.Add( sRefSagyo );
			aRefSagyo.Add( m_aRefYosoSagyogun[nSelect] );
			fSetData = 1;
			break;

		}else
			aRefSagyo.Add( m_aRefYosoSagyogun[nSelect] );

		nBeforeKishu = m_aRefYosoSagyogun[nSelect].nKishuId;

	}

	if( fSetData == 0 )
		aRefSagyo.Add( sRefSagyo );

	if( fSetData == 1 ){
		for( ; nSelect < m_aRefYosoSagyogun.GetSize(); nSelect++ ){
			aRefSagyo.Add( m_aRefYosoSagyogun[nSelect] );
		}
	}

	if( fSetData != 2 ){

// Modify ... ( CHANGE )
		InitReferenceYosoSagyogun();
//		m_aRefYosoSagyogun.RemoveAll();
// By Y.Itabashi (xxxxx) 2007.03.04

		for( nSelect = 0; nSelect < aRefSagyo.GetSize(); nSelect++ )
			m_aRefYosoSagyogun.Add( aRefSagyo[nSelect] );

	}

	aRefSagyo.RemoveAll();
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditDocument::AddSelectOtherWindow
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�@��ύX����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�I�����ꂽ�H���ɑ΂��A���̑��̍H���ŋ��ʍH���̍H�����I��
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		200702.27 Y.Itabashi (xxxxx) �V�K�쐬
//*****************************************************************************************************
void CManualEditDocument::AddSelectKoteiOtherWindow()
{
	POSITION pos = GetFirstViewPosition();

	while( pos ){
		CView *pView = GetNextView( pos );

		if( !pView->IsKindOf( RUNTIME_CLASS(CManualEditView1) ) )
			continue;

		CManualEditView1 *pView1 = (CManualEditView1 *)pView;
		int nGraphKishu = ((CManualEditChildWnd *)pView1->GetParentFrame())->m_nGraphKishu;

		// ���ʍH���̑I��
		pView1->AddSelectKyotsuKotei();

		// �L�b�g����Ƃ̑I��
		pView1->AddSelectKittingKotei();

		// �Q�ƍH���̓o�^
		pView1->AddReferenceKotei();
	}
}

// �w��@�킪�I�𒆂��̃`�F�b�N
BOOL CManualEditDocument::CheckSelectKishu( int nKishuId )
{
	for( int i = 0; i < m_aSelectYosoSagyogun.GetSize(); i++ ){

		if( m_aSelectYosoSagyogun[i].nKishuId == nKishuId )
			return TRUE;
	}

	return FALSE;
}

// �w��C���f�b�N�X�̑S�@��E�@��ʃC���f�b�N�X�擾
void CManualEditDocument::GetGraphIndex( int nIndex, int nGraphKishu, int nKishuId, int &nIndexAll, int &nIndexKishu )
{
	nIndexAll = 0;
	nIndexKishu = 0;
	for( int nSubline = 0; nSubline < m_aGraphSubline.GetSize(); nSubline++ ){

		CGraphSagyoshaArray &aGraphSagyosha = m_aGraphSubline[nSubline].aGraphSagyosha;

		for( int nSagyosha = 0; nSagyosha < aGraphSagyosha.GetSize(); nSagyosha++ ){

			SGraphSagyosha &sGraphSagyosha = aGraphSagyosha[nSagyosha];
			CGraphBlockArray &aGraphBlock = m_nGraphKind == GRAPH_YOSO_SAGYOGUN ? sGraphSagyosha.aYosoSagyogun : sGraphSagyosha.aKotei;
			
			for( int nBlock = 0; nBlock < aGraphBlock.GetSize(); nBlock++ ){

				if( nGraphKishu == -1 && nIndexAll == nIndex )
					return;

// Modify ... ( CHANGE )
				if( nKishuId == aGraphBlock[nBlock].nKishuId ){
					if( nGraphKishu != -1 && nIndexKishu == nIndex )
						return;
					nIndexKishu++;
				}
/*				else if( nGraphKishu != -1 && nIndexKishu == nIndex )
					return;

				if( nKishuId == aGraphBlock[nBlock].nKishuId )
					nIndexKishu++;*/
// By Y.Itabashi (xxxxx) 2007.03.04

				nIndexAll++;
			}
		}
	}
}

// �Q�ƍH���̃L�����Z��
void CManualEditDocument::CancelReferenceYosoSagyogun( CString sKoteiName, CString sYosoSagyogunName )
{
	POSITION firstpos = GetFirstViewPosition();
	CView *pView;
	CManualEditChildWnd *pWnd;

	for( int i = 0; i < m_aRefYosoSagyogun.GetSize(); ){
		if( m_aRefYosoSagyogun[i].sKoteiName == sKoteiName &&
			m_aRefYosoSagyogun[i].sYosoSagyogunName == sYosoSagyogunName ){
			m_aRefYosoSagyogun.RemoveAt( i );

			POSITION pos = firstpos;
			while( pos ){

				pView = GetNextView( pos );
				pWnd = (CManualEditChildWnd *)(pView->GetParentFrame());

				if( i <= pWnd->m_nRefIndex )
					pWnd->m_nRefIndex++;
			}
		}else
			i++;
	}
}

// �Q�ƍH���̏�����
void CManualEditDocument::InitReferenceYosoSagyogun()
{
	POSITION pos = GetFirstViewPosition();

	while( pos ){

		CView *pView = GetNextView( pos );
		CManualEditChildWnd *pWnd = (CManualEditChildWnd *)(pView->GetParentFrame());

		pWnd->m_nRefIndex = 0;
	}

	m_aRefYosoSagyogun.RemoveAll();
}

// �����H���̑I��ǉ�
void CManualEditDocument::AddSelectAutoKotei()
{
	POSITION pos = GetFirstViewPosition();

	while( pos ){

		CView *pView = GetNextView( pos );
		if( pView->IsKindOf( RUNTIME_CLASS( CManualEditView1 ) ) ){
			((CManualEditView1 *)pView)->AddSelectAutiKotei();
		}
	}
}
