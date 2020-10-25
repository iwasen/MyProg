#pragma once

// OptOrder.h : �w�b�_�[ �t�@�C��
//

/////////////////////////////////////////////////////////////////////////////
// COptOrder �_�C�A���O

class COptOrder : public CPropertyPage
{
	DECLARE_DYNCREATE(COptOrder)

// �R���X�g���N�V����
public:
	COptOrder();
	~COptOrder();

// �_�C�A���O �f�[�^
	enum { IDD = IDD_OPT_ORDER };
	CListBox	m_cOrderList;
	int		m_iViewText;
	int		m_iSmallSize;


// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B

	public:
	virtual void OnOK();
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

// �C���v�������e�[�V����
protected:
	// �������ꂽ���b�Z�[�W �}�b�v�֐�
	virtual BOOL OnInitDialog();
	afx_msg void OnUp();
	afx_msg void OnDown();
	afx_msg void OnDefault();
	DECLARE_MESSAGE_MAP()

public:
	BOOL m_bChangeOrder;
	BOOL m_bChangeText;
	BOOL m_bChangeSmallSize;

private:
	CString m_Order;
	int m_nViewText;
	int m_nSmallSize;

	void SetListBox(LPCTSTR pOrder);
};
