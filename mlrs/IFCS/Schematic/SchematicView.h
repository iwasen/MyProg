#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		SchematicView.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�ڑ��n���}�E�@�\�n���}�E�@�\�����}��Flash�\���p�r���[����
//----------------------------------------------------------------------------------------------------
//  3. ���l
//*****************************************************************************************************

#include "CShockwaveFlash.h"
#include "MenuCommon.h"

//#define _DEVELOP		// �J���p�@�\��L���ɂ���i�����ɂ���ɂ̓R�����g�A�E�g����j

// CSchematicView �E�B���h�E

class CSchematicView : public CWnd
{
	DECLARE_DYNCREATE(CSchematicView)
	DECLARE_EVENTSINK_MAP()

// �R���X�g���N�V����
public:
	CSchematicView();

// ����
protected:
	CShockwaveFlash m_cFlash;		// Flash�E�B���h�E
	class CSchematicFrame *m_pSchematicFrm;	// �t���[���E�B���h�E�̃|�C���^
	CSize m_sizeDefault;			// �f�t�H���g�̃r���[�T�C�Y
	CSize m_sizeView;				// ���݂̃r���[�T�C�Y
	CSize m_sizeStage;				// Flash�̃X�e�[�W�T�C�Y
	CRect m_rectFlash;				// Flash�E�B���h�E�̈ʒu�Ƒ傫��
	CMenuCommon *m_pMenuDlg;		// ���상�j���[�ւ̃|�C���^
	double m_fZoomRatio;			// �Y�[���{��
	BOOL m_bInScroll;				// �X�N���[�����t���O�i�ē��h�~�p�j
	CPoint m_pointScroll;			// �X�N���[�����̃}�E�X�J�[�\���ʒu
	HCURSOR m_hCursorHandClose;		// �������`�̃}�E�X�J�[�\��
	HCURSOR m_hCursorSave;			// �ύX�O�̃}�E�X�J�[�\���ۑ��p
	BOOL m_bScroll;					// �}�E�X�ɂ��X�N���[���t���O
	BOOL m_bQuickMode;				// �N�C�b�N���샂�[�h�t���O�i�e�X�g�p�j

// ����
public:
	void ShowSchematic(CRuntimeClass *pMenuClass, const CString &sFlashFileName, int nActionCode);

// �I�[�o�[���C�h
	protected:
	virtual BOOL PreCreateWindow(CREATESTRUCT& cs);

// ����
public:

protected:
	void FlashAction(int nActionCode, CUIntArray *pParams);
	void InitFlashRect();
	int GetNextScrollPos(int nBar, UINT nSBCode, UINT nPos);
	void ScrollFlashWindow();
	void SetZoomRatio(double fZoomRatio);
	void FSCommandFlash(LPCTSTR command, LPCTSTR args);
	void CallFlashFunction(LPCTSTR pFunc, LPCTSTR pParams);

	// �������ꂽ�A���b�Z�[�W���蓖�Ċ֐�
protected:
	DECLARE_MESSAGE_MAP()
	virtual void PostNcDestroy();
	virtual BOOL PreTranslateMessage(MSG* pMsg);
	afx_msg void OnPaint();
	afx_msg void OnSize(UINT nType, int cx, int cy);
	afx_msg void OnHScroll(UINT nSBCode, UINT nPos, CScrollBar* pScrollBar);
	afx_msg void OnVScroll(UINT nSBCode, UINT nPos, CScrollBar* pScrollBar);
	afx_msg int OnCreate(LPCREATESTRUCT lpCreateStruct);
	afx_msg void OnLButtonDown(UINT nFlags, CPoint point);
	afx_msg void OnLButtonUp(UINT nFlags, CPoint point);
	afx_msg void OnMouseMove(UINT nFlags, CPoint point);
	afx_msg LRESULT OnUserZoomIn(WPARAM wParam, LPARAM lParam);
	afx_msg LRESULT OnUserZoomOut(WPARAM wParam, LPARAM lParam);
	afx_msg LRESULT OnUserSizeReset(WPARAM wParam, LPARAM lParam);
	afx_msg LRESULT OnUserPrint(WPARAM wParam, LPARAM lParam);
	afx_msg LRESULT OnUserExit(WPARAM wParam, LPARAM lParam);
	afx_msg LRESULT OnUserAction(WPARAM wParam, LPARAM lParam);
	afx_msg LRESULT OnUserPause(WPARAM wParam, LPARAM lParam);
	afx_msg LRESULT OnUserSlow(WPARAM wParam, LPARAM lParam);
	afx_msg LRESULT OnUserNormal(WPARAM wParam, LPARAM lParam);
#ifdef _DEVELOP
	afx_msg void OnRButtonUp(UINT nFlags, CPoint point);
	afx_msg void OnQuickMode();
	afx_msg void OnScreenCopy();
	afx_msg void OnStartAll();
	void ScreenShot(CWnd *pWnd);
#endif
};

