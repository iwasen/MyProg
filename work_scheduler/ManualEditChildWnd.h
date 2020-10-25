#if !defined(AFX_MANUALEDITCHILDWND_H__0970CE9B_D0BF_4BED_97AF_67494666FD64__INCLUDED_)
#define AFX_MANUALEDITCHILDWND_H__0970CE9B_D0BF_4BED_97AF_67494666FD64__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000
// ManualEditChildWnd.h : �w�b�_�[ �t�@�C��
//

#include "ManualEditKishuBar.h"

/////////////////////////////////////////////////////////////////////////////
// CManualEditChildWnd �t���[��

class CManualEditChildWnd : public CMDIChildWnd
{
	DECLARE_DYNCREATE(CManualEditChildWnd)
protected:
	CManualEditChildWnd();           // ���I�����Ɏg�p�����v���e�N�g �R���X�g���N�^�B

// �A�g���r���[�g
public:

// �I�y���[�V����
public:
// Modify ... ( ADD )
	int m_nGraphKishu;				// �\�����̋@��
	int	m_nRefIndex;				// �Q�ƍH���̔ԍ�
// By Y.Itabashi (xxxxx) 2007.02.26

// Modify ... ( ADD )
	void DestroyKishuBar();				// �_�C�A���O�o�[����
// By Y.Itabashi (xxxxx) 2007.02.26

// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂�

	//{{AFX_VIRTUAL(CManualEditChildWnd)
	protected:
	virtual BOOL PreCreateWindow(CREATESTRUCT& cs);
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����
protected:
	virtual ~CManualEditChildWnd();

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
	//{{AFX_MSG(CManualEditChildWnd)
	afx_msg int OnCreate(LPCREATESTRUCT lpCreateStruct);
	afx_msg void OnSysCommand(UINT nID, LPARAM lParam);
	afx_msg void OnSelchangeComboKishu();
	afx_msg void OnBackRefKotei();
	afx_msg void OnNextRefKotei();
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

// Modify ... ( ADD )
	CManualEditKishuBar m_wndDlgBar;			// �_�C�A���O�o�[
// By Y.Itabashi (xxxxx) 2007.02.26

};

/////////////////////////////////////////////////////////////////////////////

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ �͑O�s�̒��O�ɒǉ��̐錾��}�����܂��B

#endif // !defined(AFX_MANUALEDITCHILDWND_H__0970CE9B_D0BF_4BED_97AF_67494666FD64__INCLUDED_)
