#if !defined(AFX_MANUALEDITKISHUBAR_H__7359734D_9B38_4EAE_91AF_205289D30995__INCLUDED_)
#define AFX_MANUALEDITKISHUBAR_H__7359734D_9B38_4EAE_91AF_205289D30995__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000
// ManualEditKishuBar.h : �w�b�_�[ �t�@�C��
//

/////////////////////////////////////////////////////////////////////////////
// CManualEditKishuBar �_�C�A���O

class CManualEditKishuBar : public CDialogBar
{
// �R���X�g���N�V����
public:
	CManualEditKishuBar();   // �W���̃R���X�g���N�^

// �_�C�A���O �f�[�^
	//{{AFX_DATA(CManualEditKishuBar)
	enum { IDD = IDD_MANUAL_KISHU_BAR };
		// ����: ClassWizard �͂��̈ʒu�Ƀf�[�^ �����o��ǉ����܂��B
	//}}AFX_DATA


// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	//{{AFX_VIRTUAL(CManualEditKishuBar)
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����
protected:

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
	//{{AFX_MSG(CManualEditKishuBar)
	afx_msg void OnUpdateBack(CCmdUI* pCmdUI);
	afx_msg void OnUpdateNext(CCmdUI* pCmdUI);
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()
};

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ �͑O�s�̒��O�ɒǉ��̐錾��}�����܂��B

#endif // !defined(AFX_MANUALEDITKISHUBAR_H__7359734D_9B38_4EAE_91AF_205289D30995__INCLUDED_)
