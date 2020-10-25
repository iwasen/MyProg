#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		ManualEditDocument.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		CManualEditDocument �N���X�̃C���^�[�t�F�C�X
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************

// �O���t���
#define GRAPH_KOTEI			0		// �H���\��
#define GRAPH_YOSO_SAGYOGUN	1		// �v�f��ƌQ�\��

// �O���t�X�V���
#define UPDATE_GRAPH_UPDATE	0		// �f�[�^�X�V
#define UPDATE_GRAPH_KISHU	1		// �@��ύX
#define UPDATE_GRAPH_KIND	2		// ��ʕύX
#define UPDATE_SCROLL		3		// �X�N���[��
#define UPDATE_CHANGE_SLIM	4		// �X�����\���ύX
#define UPDATE_CHANGE_SUBLINE	5	// �T�u���C���ύX
#define UPDATE_EDIT_VIEW	6		// �ҏW�r���[�X�V
#define UPDATE_SELECT_BLINK	7		// �I��g�_��
#define UPDATE_ERASE_MOVE_TRIANGLE	8	// �ړ���}�[�N�i�O�p�j����
#define UPDATE_SCROLL_ACTIVE	13	// �X�N���[��(�A�N�e�B�u��ʂ̂�)

// �O���t�u���b�N�i�Ϗグ�o�[�̎l�p�`�j�f�[�^
struct SGraphBlock {
	int nSublineId;					// �T�u���C��ID
	int nKishuId;					// �@��ID
	CString sKoteiName;				// �H����
	CString sYosoSagyogunName;		// �v�f��ƌQ��
	CString sJido;					// �����H�����
	double fTime;					// ��Ǝ���
	BOOL bKyotsuKotei;				// ���ʍH���t���O
	int nGroupNo;					// �O���[�v�ԍ�
	BOOL bKitting;					// �L�b�g����ƃt���O
	CIntArrayEx aYosoSagyogunId;	// �v�f��ƌQID�z��
	BOOL bNewMove;					// �ŐV�ړ��t���O
	BOOL bKittingCancel;			// �L�b�g�������t���O
};
typedef CArrayEx <SGraphBlock, SGraphBlock&> CGraphBlockArray;
typedef CArrayEx <SGraphBlock *, SGraphBlock *> CGraphBlockPtrArray;

// �O���t�̋@��ʃf�[�^
struct SGraphKishu {
	CString sKishuName;				// �@�햼
	double fTime;
};
typedef CArrayEx <SGraphKishu, SGraphKishu&> CGraphKishuArray;

// �O���t�̍�Ǝҕʃf�[�^
struct SGraphSagyosha {
	CString sSagyoshaName;			// ��ƎҖ�
	CString sSagyoshaDispName;		// ��Ǝҕ\���p����
	CGraphKishuArray aKishu;		// �@��f�[�^�z��
	CGraphBlockArray aYosoSagyogun;	// �v�f��ƌQ�f�[�^�z��
	CGraphBlockArray aKotei;		// �H���f�[�^�z��
	double fTime;					// ��Ǝ���
	int nSagyoshaId;				// ��Ǝ�ID
	BOOL bKitting;					// �L�b�g����ƃt���O
};
typedef CArrayEx2 <SGraphSagyosha, SGraphSagyosha&> CGraphSagyoshaArray;

// �T�u���C���ʂ̃f�[�^
struct SGraphSubline {
	int nSublineId;					// �T�u���C��ID�i�L�b�g����Ƃ�-1�j
	CString sSublineName;			// �T�u���C����
	CGraphSagyoshaArray aGraphSagyosha;	// ��ƎҕʃO���t�f�[�^�z��
	double fMokugyoTact;			// �ڕW�^�N�g
	double fJogen;					// ���
	double fKagen;					// ����
	double fKadoRitsu;				// �ғ���
};
typedef CArrayEx <SGraphSubline, SGraphSubline&> CGraphSublineArray;

// �h���b�O���W�f�[�^
struct SZahyoDrag {
	CRect rect;					// ���W
	SGraphBlock *pGraphBlock;	// �u���b�N�f�[�^�ւ̃|�C���^
	int nSagyoshaId;			// ��Ǝ�ID
	int nIndex;
};
typedef CArrayEx <SZahyoDrag, SZahyoDrag&> CZahyoDragArray;

// �h���b�v���W�f�[�^
struct SZahyoDrop {
	CRect rect;					// ���W
	CPoint pointTriangle;		// �O�p�`��\��������W
	SGraphBlock *pGraphBlock;	// �u���b�N�f�[�^�ւ̃|�C���^
	int nSagyoshaId;			// ��Ǝ�ID
	int nIndex;
	BOOL bLastBlock;
};
typedef CArrayEx <SZahyoDrop, SZahyoDrop&> CZahyoDropArray;

// ��ƎҖ��̍��W
struct SZahyoSagyosha {
	CRect rect;
	int nSublineId;
	int nSagyoshaId;
};
typedef CArrayEx <SZahyoSagyosha, SZahyoSagyosha&> CZahyoSagyoshaArray;

// �I��������Ǝ�
struct SSelectSagyosha {
	BOOL bSelect;
	int nSubnlineId;
	int nSagyoshaId;

	SSelectSagyosha() {
		bSelect = FALSE;
	}
};

// �I�������H��
struct SSelectYosoSagyogun {
	int		nIndex;				// �O���t��ID(�@�했�̃C���f�b�N�X)
	int		nIndexKishu;			// �@�했��Index
	int		nSublineId;			// �T�u���C��ID
	int		nKishuId;			// �@��ID
	int		nSagyoshaId;		// ��Ǝ�ID
	CString	sKoteiName;			// �H������
	CString	sYosoSagyogunName;	// �v�f��ƌQ����
	BOOL	bKyotsuKotei;		// ���ʍH���t���O
	int		nGroupNo;			// ���ʍH���O���[�v�ԍ�
	BOOL	bKitting;			// �L�b�g����ƃt���O
};
typedef CArrayEx <SSelectYosoSagyogun, SSelectYosoSagyogun&> CSelectYosoSagyogunArray;

// �Q�ƍH��
struct SReferenceYosoSagyogun {
	int		nIndex;				// �O���t��ID(�@�했�̃C���f�b�N�X)
	int		nIndexKishu;			// �@�했��Index
	int		nSublineId;			// �T�u���C��ID
	int		nKishuId;			// �@��ID
	int		nSagyoshaId;		// ��Ǝ�ID
	CString	sKoteiName;			// �H������
	CString	sYosoSagyogunName;	// �v�f��ƌQ����
};
typedef CArrayEx <SReferenceYosoSagyogun, SReferenceYosoSagyogun&> CReferenceYosoSagyogunArray;

// �蓮�Ґ��h�L�������g
class CManualEditDocument : public CDocument
{
protected:
	CManualEditDocument();           // ���I�����Ɏg�p�����v���e�N�g �R���X�g���N�^�B
	DECLARE_DYNCREATE(CManualEditDocument)

// �A�g���r���[�g
public:

// �I�y���[�V����
public:

// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	//{{AFX_VIRTUAL(CManualEditDocument)
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����
public:
	CGraphSublineArray m_aGraphSubline;		// �T�u���C���ʃf�[�^�z��
// Modify ... ( DELETE )
//	int m_nGraphKishu;				// �\�����̋@��
// By Y.Itabashi (xxxxx) 2007.02.26
	int m_nGraphKind;				// �\�����̃O���t���
	BOOL m_bSlimMode;				// �X�������[�h�t���O
	SZahyoDrop *m_pDrop;			// �h���b�v���W�f�[�^
	int m_nTotalSeisanDaisu;		// �S�@��̐��Y�䐔�̍��v
	int m_nKittingIndex;			// �L�b�g����Ƃ̃C���f�b�N�X
	SSelectSagyosha m_cSelectSagyosha;	// �I����Ǝ�
// Modify ... ( CHANGE )
//	CIntArrayEx m_aSelectedBox;		// �I�����
	CSelectYosoSagyogunArray m_aSelectYosoSagyogun;		// �I�����
	CReferenceYosoSagyogunArray m_aRefYosoSagyogun;		// �Q�ƍ��
	CFrameWnd *m_pSelectWnd;			// ��Ƃ��I�����ꂽ���
	CFrameWnd *m_pDragWnd;				// �h���b�O���ꂽ���
// By Y.Itabashi (xxxxx) 2007.02.27
	BOOL m_bSelectKitting;			// �L�b�g����ƑI�𒆃t���O
	BOOL m_bDispNewMove;			// �ŐV�ړ��\���t���O
	int m_nDropSublineId;			// �h���b�v�����ꏊ�̃T�u���C���G���A

// Modify ... ( CHANGE )
	void ChangeGraphKishu(int nGraphKishu, CFrameWnd *pWnd);
//	void ChangeGraphKishu(int nGraphKishu);
// By Y.Itabashi (xxxxx) 2007.02.27
	void ChangeGraphKind(int nGraphKind);
	void MakeGraphData();
	void UpdateGraph(BOOL bSetSagyojun);
	void DispCenterSagyosha(int nSublineId, int nSagyoshaId);
	void StopBlink(BOOL bStopBlink);
	SGraphSubline *GetGraphSublineData(int nSublineId);
	void MoveItem(int nSublineIdFrom, CGraphBlockPtrArray &aGraphBlockFrom, int nSublineIdTo, SGraphBlock *pGraphBlockTo, int nSagyoshaId, BOOL bLastBlock);
	void MoveItem1(int nSublineId, CGraphBlockPtrArray &aGraphBlockFrom, SGraphBlock *pGraphBlockTo, int nSagyoshaId, BOOL bLastBlock);
	void MoveItem1Sub(int nSublineId, int nYosoSagyogunIdFrom, int nKishuIdFrom, int nYosoSagyogunIdTo, int nKishuIdTo, int nSagyoshaId, BOOL bLastBlock);
	void MoveItem2(int nSublineIdFrom, CGraphBlockPtrArray &aGraphBlockFrom, int nSublineIdTo, SGraphBlock *pGraphBlockTo, int nSagyoshaId, BOOL bLastBlock);
	SGraphBlock *GetFirstGraphBlock(int nSublineId);
	SGraphBlock *GetNextGraphBlock(int nSublineId, int nSagyoshaId);
// Modify ... ( ADD )
	BOOL CheckSameKoteiMove( SGraphBlock *pGraphBlockFrom, SGraphBlock *pGraphBlockTo, BOOL bLastBlock );
// By Y.Itabashi (xxxxx) 2007.02.10
// Modify ... ( ADD )
	void AddSelectKotei( SZahyoDrag sZahyoDrag, int nGraphKishu );
	void AddReferenceKotei( SZahyoDrag sZahyoDrag, int nGraphKishu );
	void AddSelectKoteiOtherWindow();
// By Y.Itabashi (xxxxx) 2007.02.27
// Modify ... ( ADD )
	void CancelReferenceYosoSagyogun( CString sKoteiName, CString sYosoSagyogunName );
	void InitReferenceYosoSagyogun();
// By Y.Itabashi (xxxxx) 2007.03.06
// Modify ... ( ADD )
	void AddSelectAutoKotei();
// By Y.Itabashi (xxxxx) 2007.03.07

	// ���b�Z�[�W �}�b�v�֐��̐���
protected:
	//{{AFX_MSG(CManualEditDocument)
	afx_msg void OnRadioGraphKotei();
	afx_msg void OnRadioGraphYosoSagyogun();
	afx_msg void OnWindowViewType();
	afx_msg void OnHelpVersion();
	afx_msg void OnViewSlimGraph();
	afx_msg void OnUpdateViewSlimGraph(CCmdUI* pCmdUI);
	afx_msg void OnViewSelectSubline();
	afx_msg void OnToolKittingSet();
	afx_msg void OnToolLineKisei();
	afx_msg void OnToolKatashikiKisei();
	afx_msg void OnToolKyotsuKotei();
	afx_msg void OnUpdateToolLineKisei(CCmdUI* pCmdUI);
	afx_msg void OnUpdateToolKatashikiKisei(CCmdUI* pCmdUI);
	afx_msg void OnFileSave();
	afx_msg void OnFileSaveAs();
	afx_msg void OnEditUndo();
	afx_msg void OnUpdateEditUndo(CCmdUI* pCmdUI);
	afx_msg void OnEditRedo();
	afx_msg void OnUpdateEditRedo(CCmdUI* pCmdUI);
	afx_msg void OnWindowSeiretsu();
	afx_msg void OnFileExport();
	afx_msg void OnUpdateToolKittingSet(CCmdUI* pCmdUI);
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

	void MakeGraphDataSubline(int nSublineId);
	void MakeGraphDataKitting();
	void SetWindowTitle();
// Modify ... ( ADD )
	BOOL CheckSelectKishu( int nKishuId );
	void GetGraphIndex( int nIndex, int nGraphKishu, int nKishuId, int &nIndexAll, int &nIndexKishu );
// By Y.Itabashi (xxxxx) 2007.02.27
};

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ �͑O�s�̒��O�ɒǉ��̐錾��}�����܂��B
