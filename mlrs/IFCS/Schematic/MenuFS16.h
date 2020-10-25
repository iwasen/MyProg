#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		MenuFS16.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���ʁE����ʒu���o��y�ы쓮�T�[�{���[�^�i�@�\�X�P�}�`�b�N�j��ʂ̑��상�j���[����
//----------------------------------------------------------------------------------------------------
//  3. ���l
//*****************************************************************************************************

#include "MenuCommon.h"
#include "MenuButton.h"

// CMenuFS16 �_�C�A���O

class CMenuFS16 : public CMenuCommon
{
	DECLARE_DYNCREATE(CMenuFS16)

	// �k�o�^�b����X�e�[�^�X
	enum ELpcStatus {
		STATUS_LPC_INITIAL,			// �������
		STATUS_LPC_KAIHO,			// ���
		STATUS_LPC_KOTEI			// �Œ�
	};

	// ���S�x������X�e�[�^�X
	enum EAnzenStatus {
		STATUS_ANZEN_INITIAL,			// �������
		STATUS_ANZEN_TORITSUKE,			// ��t
		STATUS_ANZEN_TORIHAZUSHI		// ��O��
	};

public:
	CMenuFS16(CWnd* pParent = NULL);   // �W���R���X�g���N�^

// �_�C�A���O �f�[�^
	enum { IDD = IDD_MENU_FS16 };

protected:
	virtual void ActionEnd(int nActionNo);
	void SetStatus(ELpcStatus eLpcStatus, EAnzenStatus eAnzenStatus);
	void SetMenuButton();

	ELpcStatus m_eLpcStatus;			// �k�o�b����X�e�[�^�X
	EAnzenStatus m_eAnzenStatus;		// ���S�x������X�e�[�^�X
	BOOL m_bWorking;					// ���쒆�t���O

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

	DECLARE_MESSAGE_MAP()
	virtual BOOL OnInitDialog();
	afx_msg void OnBnClickedButtonAction1();
	afx_msg void OnBnClickedButtonAction2();
	afx_msg void OnBnClickedButtonAction3();
	afx_msg void OnBnClickedButtonAction4();
	afx_msg void OnBnClickedButtonAction5();

	CMenuButton m_cButtonAction1;
	CMenuButton m_cButtonAction2;
	CMenuButton m_cButtonAction3;
	CMenuButton m_cButtonAction4;
	CMenuButton m_cButtonAction5;
};
