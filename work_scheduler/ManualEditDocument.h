#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		ManualEditDocument.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		CManualEditDocument クラスのインターフェイス
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************

// グラフ種別
#define GRAPH_KOTEI			0		// 工程表示
#define GRAPH_YOSO_SAGYOGUN	1		// 要素作業群表示

// グラフ更新種別
#define UPDATE_GRAPH_UPDATE	0		// データ更新
#define UPDATE_GRAPH_KISHU	1		// 機種変更
#define UPDATE_GRAPH_KIND	2		// 種別変更
#define UPDATE_SCROLL		3		// スクロール
#define UPDATE_CHANGE_SLIM	4		// スリム表示変更
#define UPDATE_CHANGE_SUBLINE	5	// サブライン変更
#define UPDATE_EDIT_VIEW	6		// 編集ビュー更新
#define UPDATE_SELECT_BLINK	7		// 選択枠点滅
#define UPDATE_ERASE_MOVE_TRIANGLE	8	// 移動先マーク（三角）消去
#define UPDATE_SCROLL_ACTIVE	13	// スクロール(アクティブ画面のみ)

// グラフブロック（積上げバーの四角形）データ
struct SGraphBlock {
	int nSublineId;					// サブラインID
	int nKishuId;					// 機種ID
	CString sKoteiName;				// 工程名
	CString sYosoSagyogunName;		// 要素作業群名
	CString sJido;					// 自動工程種別
	double fTime;					// 作業時間
	BOOL bKyotsuKotei;				// 共通工程フラグ
	int nGroupNo;					// グループ番号
	BOOL bKitting;					// キット化作業フラグ
	CIntArrayEx aYosoSagyogunId;	// 要素作業群ID配列
	BOOL bNewMove;					// 最新移動フラグ
	BOOL bKittingCancel;			// キット化解除フラグ
};
typedef CArrayEx <SGraphBlock, SGraphBlock&> CGraphBlockArray;
typedef CArrayEx <SGraphBlock *, SGraphBlock *> CGraphBlockPtrArray;

// グラフの機種別データ
struct SGraphKishu {
	CString sKishuName;				// 機種名
	double fTime;
};
typedef CArrayEx <SGraphKishu, SGraphKishu&> CGraphKishuArray;

// グラフの作業者別データ
struct SGraphSagyosha {
	CString sSagyoshaName;			// 作業者名
	CString sSagyoshaDispName;		// 作業者表示用名称
	CGraphKishuArray aKishu;		// 機種データ配列
	CGraphBlockArray aYosoSagyogun;	// 要素作業群データ配列
	CGraphBlockArray aKotei;		// 工程データ配列
	double fTime;					// 作業時間
	int nSagyoshaId;				// 作業者ID
	BOOL bKitting;					// キット化作業フラグ
};
typedef CArrayEx2 <SGraphSagyosha, SGraphSagyosha&> CGraphSagyoshaArray;

// サブライン別のデータ
struct SGraphSubline {
	int nSublineId;					// サブラインID（キット化作業は-1）
	CString sSublineName;			// サブライン名
	CGraphSagyoshaArray aGraphSagyosha;	// 作業者別グラフデータ配列
	double fMokugyoTact;			// 目標タクト
	double fJogen;					// 上限
	double fKagen;					// 下限
	double fKadoRitsu;				// 稼働率
};
typedef CArrayEx <SGraphSubline, SGraphSubline&> CGraphSublineArray;

// ドラッグ座標データ
struct SZahyoDrag {
	CRect rect;					// 座標
	SGraphBlock *pGraphBlock;	// ブロックデータへのポインタ
	int nSagyoshaId;			// 作業者ID
	int nIndex;
};
typedef CArrayEx <SZahyoDrag, SZahyoDrag&> CZahyoDragArray;

// ドロップ座標データ
struct SZahyoDrop {
	CRect rect;					// 座標
	CPoint pointTriangle;		// 三角形を表示する座標
	SGraphBlock *pGraphBlock;	// ブロックデータへのポインタ
	int nSagyoshaId;			// 作業者ID
	int nIndex;
	BOOL bLastBlock;
};
typedef CArrayEx <SZahyoDrop, SZahyoDrop&> CZahyoDropArray;

// 作業者名の座標
struct SZahyoSagyosha {
	CRect rect;
	int nSublineId;
	int nSagyoshaId;
};
typedef CArrayEx <SZahyoSagyosha, SZahyoSagyosha&> CZahyoSagyoshaArray;

// 選択した作業者
struct SSelectSagyosha {
	BOOL bSelect;
	int nSubnlineId;
	int nSagyoshaId;

	SSelectSagyosha() {
		bSelect = FALSE;
	}
};

// 選択した工程
struct SSelectYosoSagyogun {
	int		nIndex;				// グラフのID(機種毎のインデックス)
	int		nIndexKishu;			// 機種毎のIndex
	int		nSublineId;			// サブラインID
	int		nKishuId;			// 機種ID
	int		nSagyoshaId;		// 作業者ID
	CString	sKoteiName;			// 工程名称
	CString	sYosoSagyogunName;	// 要素作業群名称
	BOOL	bKyotsuKotei;		// 共通工程フラグ
	int		nGroupNo;			// 共通工程グループ番号
	BOOL	bKitting;			// キット化作業フラグ
};
typedef CArrayEx <SSelectYosoSagyogun, SSelectYosoSagyogun&> CSelectYosoSagyogunArray;

// 参照工程
struct SReferenceYosoSagyogun {
	int		nIndex;				// グラフのID(機種毎のインデックス)
	int		nIndexKishu;			// 機種毎のIndex
	int		nSublineId;			// サブラインID
	int		nKishuId;			// 機種ID
	int		nSagyoshaId;		// 作業者ID
	CString	sKoteiName;			// 工程名称
	CString	sYosoSagyogunName;	// 要素作業群名称
};
typedef CArrayEx <SReferenceYosoSagyogun, SReferenceYosoSagyogun&> CReferenceYosoSagyogunArray;

// 手動編成ドキュメント
class CManualEditDocument : public CDocument
{
protected:
	CManualEditDocument();           // 動的生成に使用されるプロテクト コンストラクタ。
	DECLARE_DYNCREATE(CManualEditDocument)

// アトリビュート
public:

// オペレーション
public:

// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	//{{AFX_VIRTUAL(CManualEditDocument)
	//}}AFX_VIRTUAL

// インプリメンテーション
public:
	CGraphSublineArray m_aGraphSubline;		// サブライン別データ配列
// Modify ... ( DELETE )
//	int m_nGraphKishu;				// 表示中の機種
// By Y.Itabashi (xxxxx) 2007.02.26
	int m_nGraphKind;				// 表示中のグラフ種別
	BOOL m_bSlimMode;				// スリムモードフラグ
	SZahyoDrop *m_pDrop;			// ドロップ座標データ
	int m_nTotalSeisanDaisu;		// 全機種の生産台数の合計
	int m_nKittingIndex;			// キット化作業のインデックス
	SSelectSagyosha m_cSelectSagyosha;	// 選択作業者
// Modify ... ( CHANGE )
//	CIntArrayEx m_aSelectedBox;		// 選択作業
	CSelectYosoSagyogunArray m_aSelectYosoSagyogun;		// 選択作業
	CReferenceYosoSagyogunArray m_aRefYosoSagyogun;		// 参照作業
	CFrameWnd *m_pSelectWnd;			// 作業が選択された画面
	CFrameWnd *m_pDragWnd;				// ドラッグされた画面
// By Y.Itabashi (xxxxx) 2007.02.27
	BOOL m_bSelectKitting;			// キット化作業選択中フラグ
	BOOL m_bDispNewMove;			// 最新移動表示フラグ
	int m_nDropSublineId;			// ドロップした場所のサブラインエリア

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

	// メッセージ マップ関数の生成
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
// Microsoft Visual C++ は前行の直前に追加の宣言を挿入します。
