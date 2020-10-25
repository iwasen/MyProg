#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		ManualEditView.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		CManualEditView �N���X�̃C���^�[�t�F�C�X
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************

#include "ManualEditChildWnd.h"
#include "ManualEditDocument.h"
#include "ManualEditSublineInfo.h"

// ��ƍ��W�f�[�^���
#define COORDINATE_KIND_SAGYO			0
#define COORDINATE_KIND_JIDO			1
#define COORDINATE_KIND_HANJIDO			2
#define COORDINATE_KIND_JIDO_KYOTSU		3
#define COORDINATE_KIND_HANJIDO_KYOTSU	4
#define COORDINATE_KIND_KYOTSU_KOTEI	5

// �T�u���C���ʍ��W�f�[�^
struct SZahyoSubline {
	int nSublineId;				// �T�u���C��ID
	int nLeft;					// ���[
	int nRight;					// �E�[
	CManualEditSublineInfo cSublineInfo; 
};
typedef CArrayEx <SZahyoSubline, SZahyoSubline&> CZahyoSublineArray;

// �L�b�g���T�u���C����؂���W�f�[�^
struct SZahyoKittingSubline {
	CPoint pointLine;
	CString sSiblineName1;
	CString sSiblineName2;
};
typedef CArrayEx <SZahyoKittingSubline, SZahyoKittingSubline&> CZahyoKittingSublineArray;

// �O���t�\�����W�f�[�^
struct SZahyoGraph {
	CRect rect;					// ���W
	CString sText;				// �\���e�L�X�g
	int nKind;					// �\�����
	int nKishuId;
};
typedef CArrayEx <SZahyoGraph, SZahyoGraph&> CZahyoGraphArray;

// ���ʍH���g�̍��W
struct SZahyoKyotsuKoteiFrame {
	CRect rect;
};
typedef CArrayEx <SZahyoKyotsuKoteiFrame, SZahyoKyotsuKoteiFrame&> CZahyoKyotsuKoteiFrameArray;

// �蓮�Ґ����ʃr���[
class CManualEditView : public CScrollView
{
protected:
	CManualEditView();           // ���I�����Ɏg�p�����v���e�N�g �R���X�g���N�^�B
	DECLARE_DYNCREATE(CManualEditView)

// �A�g���r���[�g
public:

// �I�y���[�V����
public:

// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	//{{AFX_VIRTUAL(CManualEditView)
	protected:
	virtual void OnInitialUpdate();     // �\�z��A�ŏ��̏����B
	virtual void OnDraw(CDC* pDC);
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����
protected:

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
	//{{AFX_MSG(CManualEditView)
	afx_msg void OnHScroll(UINT nSBCode, UINT nPos, CScrollBar* pScrollBar);
	afx_msg void OnVScroll(UINT nSBCode, UINT nPos, CScrollBar* pScrollBar);
	//}}AFX_MSG
	afx_msg void OnGetToolTipText(NMHDR* pNMH, LRESULT* pRes);
	DECLARE_MESSAGE_MAP()

protected:
	int m_nLeftMargin;			// ���}�[�W��
	int m_nTopMargin;			// ��}�[�W��
	int m_nRightMargin;			// �E�}�[�W��
	int m_nBottomMargin;		// ���}�[�W��
	int m_nBarWidth;			// �o�[�̕�
	int m_nLeftSpacing;			// �c���ƈ�ԍ��̃o�[�̊Ԃ̃X�y�[�X
	int m_nBarSpacing;			// �o�[�Ԃ̃X�y�[�X
	int m_moveHight;			// �Ґ���ʂ̏c���ړ� 20040214 xxxxx kuwa
	CZahyoSublineArray m_aZahyoSubline;	// �T�u���C���ʍ��W�f�[�^
	CZahyoGraphArray m_aZahyoGraph;	// �O���t�\�����W�f�[�^
	CZahyoKittingSublineArray m_aZahyoKittingSubline;	// �L�b�g���T�u���C����؂���W�f�[�^
	CZahyoKyotsuKoteiFrameArray m_aZahyoKyotsuKoteiFrame;	// ���ʍH�����W�f�[�^
	CZahyoSagyoshaArray m_aZahyoSagyosha;
	CRect m_rectView;			// �r���[�S�̂̑傫��
	CRect m_rectGraph;			// �O���t�����̑傫��
	CPen m_penGraph;			// �O���t�`��p�̃y��
	CPen m_penAxis;				// ���`��p�̃y��
	CPen m_penTact;				// �ڕW�^�N�g�`��p�̃y��
	CPen m_penTactLimit;		// �ڕW�^�N�g�̏㉺���`��p�̃y��
	CPen m_penSublineBar;		// �T�u���C���ԋ�؂���p�̃y��
	CPen m_penKyotsuKotei;		// ���ʍH���`��p�̃y��
	CPen m_penKittingLine;		// �T�u���C���E�L�b�g�����C���ԋ�؂���`��p�̃y��
	CPen m_penKittingSubline;	// �L�b�g����ƃT�u���C���ԋ�؂���`��p�̃y��
	CFont m_fontGraph;			// �����`��p�t�H���g
	CFont m_fontH;				// �����`��p�t�H���g
	CFont m_fontV;				// �c���`��p�t�H���g
	CFont m_fontTime;			// ��Ǝ��ԕ`��p�t�H���g
	CBrush m_brushJido;			// �����H���`��p�u���V
	CBrush m_brushHanJido;		// �������H���`��p�u���V
	CBrush m_brushKyotsuJido;		// �����H���i���ʍH���j�`��p�u���V
	CBrush m_brushKyotsuHanJido;	// �������H���i���ʍH���j�`��p�u���V
	CBrush m_brushGraph[MAX_KISHU];	// �@��ʂ̃o�[�`��p�u���V
	CBitmap m_bmpKyotsuKotei;	// ���ʍH���}�[�N�̃r�b�g�}�b�v
	CDC m_dcCompatible;			// ���ʍH���}�[�N�`��pDC
	BOOL m_bInitialize;			// �������ς݃t���O
	int m_nGraphSublineNum;		// �O���t�\������T�u���C���̐�
	CSize m_sizeKyotsuKotei;	// ���ʍH���}�[�N�̃T�C�Y
	double m_fScaleFactor;		// �P��������̃s�N�Z����

	CManualEditDocument *GetDocument() { return (CManualEditDocument *)CView::GetDocument(); }
	void InitializeZahyoData();
	void ClearZahyoData();
	void AddZahyoGraph(const CRect &rect, CString &sText, int nKind, int nKishuId = 0);
	int AddKyotsuKoteiFrame(const CRect &rect);
	void AddZahyoSagyosha(const CRect &rect, int nSublineId, int nSagyoshaId);
	SZahyoSagyosha *CheckZahyoSagyosha(CPoint point);
	void DrawGraph(CDC *pDC, BOOL bDrawText);
	void DrawKyotsuKoteiFrame(CDC *pDC);
	void DrawVerticalAxisLine(CDC *pDC);
	void DrawHorizontalAxisLine(CDC *pDC);
	void DrawTactLine(CDC *pDC);
	void DrawVerticalAxisText(CDC *pDC, LPCTSTR pText);
	void DrawLegends(CDC *pDC);
	void DrawLeftTriangle(CDC *pDC, const CRect &rect);
	void DrawRightTriangle(CDC *pDC, const CRect &rect);
	void DrawTextCenter(CDC *pDC, const CString &sText, CRect rect);
	void DrawTime(CDC *pDC, SGraphSubline &cGraphSubline, int nLeft, double fTime);
	void DrawSublineBoundLine(CDC *pDC);
	void DrawKittingSubline(CDC *pDC);
	int OnToolHitTest(CPoint point, TOOLINFO * pTI) const;
	void SetScaleFactor(BOOL bAdjust, int nKishuId);
	double GetMaxTime(int nKishuId);
	void SetSlimMode();
	void SetSlim(int &nScale);
	int GetTotalSagyoshaNum();
	int GetKittingSagyoshaNum();
	void DispSublineInfo();
	void SetSublineInfoPos();
	void SetSublineInfoPos2();
	void HideSublineInfo();
	int GetScaleHeight(double fMinute);
	void DispCenterSagyosha(int nSublineId, int nSagyoshaId);
	SZahyoSubline *GetZahyoSubline(CPoint point);
};

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ �͑O�s�̒��O�ɒǉ��̐錾��}�����܂��B
