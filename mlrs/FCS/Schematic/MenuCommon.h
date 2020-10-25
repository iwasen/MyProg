#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		MenuCommon.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�ڑ��n���}�E�@�\�n���}�E�@�\�����}�̑��상�j���[�̋��ʐe�N���X
//----------------------------------------------------------------------------------------------------
//  3. ���l
//*****************************************************************************************************

#include "DialogEx.h"

// CMenuCommon �_�C�A���O

class CMenuCommon : public CDialogEx
{
	DECLARE_DYNAMIC(CMenuCommon)

public:
	CMenuCommon(UINT nID, CWnd* pParent);   // �W���R���X�g���N�^

	virtual void ActionEnd(int nActionNo) {}
	void EnableSpeedButton(BOOL bStart);

	CRect m_rectClient;			// �N���C�A���g�̈�̈ʒu�Ƒ傫��

protected:
	void SendAction(int nActionNo, CUIntArray *pParamArray = NULL);
	LRESULT SendMessageParent(UINT nMessage, WPARAM wParam = 0, LPARAM lParam = 0);
	void EnableButton(UINT nID, BOOL bEnable);

	BOOL m_bSlowSpeed;

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

	DECLARE_MESSAGE_MAP()
	virtual BOOL OnInitDialog();
	virtual void OnCancel();
	afx_msg void OnBnClickedButtonZoomIn();
	afx_msg void OnBnClickedButtonZoomOut();
	afx_msg void OnBnClickedButtonSizeReset();
	afx_msg void OnBnClickedButtonPrint();
	afx_msg void OnBnClickedButtonExit();
	afx_msg void OnNcLButtonDblClk(UINT nHitTest, CPoint point);
	afx_msg void OnBnClickedButtonPause();
	afx_msg void OnBnClickedButtonSlow();
	afx_msg void OnBnClickedButtonNormal();
};
