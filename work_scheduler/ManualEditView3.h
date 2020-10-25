#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		ManualEditView3.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		CManualEditView3 �N���X�̃C���^�[�t�F�C�X
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************

#include "ManualEditView.h"

// �蓮�Ґ���ʂ̉����r���[�i�S���j
class CManualEditView3 : public CManualEditView
{
protected:
	CManualEditView3();           // ���I�����Ɏg�p�����v���e�N�g �R���X�g���N�^�B
	DECLARE_DYNCREATE(CManualEditView3)

// �A�g���r���[�g
public:

// �I�y���[�V����
public:

// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	//{{AFX_VIRTUAL(CManualEditView3)
	public:
	virtual void OnInitialUpdate();
	protected:
	virtual void OnDraw(CDC* pDC);      // ���̃r���[��`�悷�邽�߂ɃI�[�o�[���C�h���܂����B
	virtual void OnUpdate(CView* pSender, LPARAM lHint, CObject* pHint);
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����
protected:

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
	//{{AFX_MSG(CManualEditView3)
	afx_msg void OnSize(UINT nType, int cx, int cy);
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

	void MakeGraph();
	int MakeZahyoSagyo(int nSublineId, CGraphBlockArray &cArray, int nSagyoshaId, int nKishuId, int nLeft, int nTop, int &nKittingSublineId);
	void SetViewSize();
	void DrawHorizontalAxisText(CDC *pDC);
	void DrawTotalTime(CDC *pDC);
	void SetScaleParamaeter();
};

/////////////////////////////////////////////////////////////////////////////

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ �͑O�s�̒��O�ɒǉ��̐錾��}�����܂��B
