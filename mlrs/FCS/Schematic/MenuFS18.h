#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		MenuFS18.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���ˋ@�쓮���u�i�@�\�X�P�}�`�b�N�j��ʂ̑��상�j���[����
//----------------------------------------------------------------------------------------------------
//  3. ���l
//*****************************************************************************************************

#include "MenuCommon.h"
#include "MenuButton.h"

// CMenuFS18 �_�C�A���O

class CMenuFS18 : public CMenuCommon
{
	DECLARE_DYNCREATE(CMenuFS18)

	// �ˌ������p�l���d������X�e�[�^�X
	enum EPanelStatus {
		STATUS_PANEL_OFF,		// OFF
		STATUS_PANEL_ON,		// ON
		STATUS_PANEL_READY		// ���슮��
	};

	// �d����������X�e�[�^�X
	enum EPowerStatus {
		STATUS_POWER_OFF,		// OFF
		STATUS_POWER_ON,		// ON
		STATUS_POWER_READY		// ���슮��
	};

	// �ˌ������p�l������X�e�[�^�X
	enum EFireStatus {
		STATUS_FIRE_HIDE,		// ��\��
		STATUS_FIRE_INITIAL,	// �������
		STATUS_FIRE_WORKING,	// ���쒆
		STATUS_FIRE_STOP,		// ����I��
		STATUS_FIRE_STRAGE		// ���[
	};

	// �ˌ������p�l������X�e�[�^�X
	enum EBoomStatus {
		STATUS_BOOM_HIDE,		// ��\��
		STATUS_BOOM_INITIAL,	// �������
		STATUS_BOOM_WORKING,	// ���쒆
		STATUS_BOOM_STOP		// ����I��
	};

public:
	CMenuFS18(CWnd* pParent = NULL);   // �W���R���X�g���N�^

// �_�C�A���O �f�[�^
	enum { IDD = IDD_MENU_FS18 };

protected:
	void SendAction(int nActionNo);
	virtual void ActionEnd(int nActionNo);
	void SetStatus(EPanelStatus ePanelStatus, EPowerStatus ePowerStatus, EFireStatus eFireStatus, EBoomStatus eBoomStatus);
	void SetMenuButton();

	EPanelStatus m_ePanelStatus;	// �ˌ������p�l���d������X�e�[�^�X
	EPowerStatus m_ePowerStatus;	// �d����������X�e�[�^�X
	EFireStatus m_eFireStatus;		// �ˌ������p�l������X�e�[�^�X
	EBoomStatus m_eBoomStatus;		// �ˌ������p�l������X�e�[�^�X
	BOOL m_bPause;					// �ꎞ��~�t���O

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

	DECLARE_MESSAGE_MAP()
	virtual BOOL OnInitDialog();
	afx_msg void OnBnClickedButtonAction1();
	afx_msg void OnBnClickedButtonAction2();
	afx_msg void OnBnClickedButtonAction3();
	afx_msg void OnBnClickedButtonAction4();
	afx_msg void OnBnClickedButtonAction5();
	afx_msg void OnBnClickedButtonAction6();
	afx_msg void OnBnClickedButtonAction7();
	afx_msg void OnBnClickedButtonAction8();
	afx_msg void OnBnClickedButtonAction9();
	afx_msg void OnBnClickedButtonAction10();
	afx_msg void OnBnClickedButtonAction11();
	afx_msg void OnBnClickedButtonAction12();
	afx_msg void OnBnClickedButtonAction13();
	afx_msg void OnBnClickedButtonAction14();
	afx_msg void OnBnClickedButtonAction15();

	CMenuButton m_cButtonAction1;
	CMenuButton m_cButtonAction2;
	CMenuButton m_cButtonAction3;
	CMenuButton m_cButtonAction4;
	CMenuButton m_cButtonAction5;
	CMenuButton m_cButtonAction6;
	CMenuButton m_cButtonAction7;
	CMenuButton m_cButtonAction8;
	CMenuButton m_cButtonAction9;
	CMenuButton m_cButtonAction10;
	CMenuButton m_cButtonAction11;
	CMenuButton m_cButtonAction12;
	CMenuButton m_cButtonAction13;
	CMenuButton m_cButtonAction14;
	CMenuButton m_cButtonAction15;
	CStatic m_cStaticFirePanel;
	CStatic m_cStaticBoomPanel;
};
