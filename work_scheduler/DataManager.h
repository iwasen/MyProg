#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		DataManager.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		CDataManager クラスのインターフェイス
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************

#include "ExcelSeisanDaisu.h"
#include "ExcelKinmuTaisei.h"
#include "ExcelBuhinhyoLink.h"
#include "ExcelDSTRSanshutsu.h"
#include "ExcelYosoSagyoHyo.h"
#include "ExcelLineKiseiMatrix.h"
#include "ExcelKatashikiKiseiMatrix.h"
#include "ExcelKittingTemplate.h"
#include "ExcelSeisanJoken.h"
#include "ExcelBuhinKitting.h"
#include "ExcelKyotsuKotei.h"
#include "ExcelKittingTaishogai.h"
#include "ExcelKittingTaishoBuhin.h"
#include "ExcelYosoSagyoTemplate.h"
#include "ExcelKatashikiKiseiError.h"
#include "ExcelLineKiseiError.h"
#include "ExcelExportGraphTemplate.h"

// ステータスコード
#define STATUS_NOREAD			0
#define STATUS_READ_DATA		1
#define STATUS_SEISAN_JOKEN		2
// Modify ... ( ADD )
#define STATUS_JIDO_HENSEI		3
#define STATUS_SHUDO_HENSEI		4
// By Y.Itabashi (xxxxx) 2007.03.06

// キット化工程記号
#define KITTING_KOTEI_KIGO			"VK0K"

// 工順規制ファイル編集用メッセージコード
#define WM_OPEN_EXCEL			(WM_USER + 300)
#define WM_CLOSE_EXCEL			(WM_USER + 301)
#define WM_ERROR_EXCEL			(WM_USER + 302)
#define EXCEL_LINE_KISEI_MATRIX			0
#define EXCEL_KATASHIKI_KISEI_MATRIX	1

// 生産台数データ
struct SSeisanDaisu {
	CString sYexxxonth;			// 年月(YYYYMM)
	CIntArrayEx aSeisanDaisu;	// 生産台数
};
typedef CArrayEx2 <SSeisanDaisu, SSeisanDaisu&> CSeisanDaisuArray;

// 生産条件データ
struct SSeisanJoken {
	double fKinmuTaisei;	// 勤務体制(H)
	int nKyusokuJikan;		// 休憩時間(分)
	int nKadoNissu;			// 稼動日数(Day/月)
	int nKotaiKinmu;		// 交代勤務(直)
	double fKadoJikan;		// 稼動時間(H/月)
	BOOL bKyoseiKudo;		// 強制駆動
	double fKadoRitsu;		// 稼動率(%)
	double fTact;			// タクト(分/台)
	double fJogen;			// 上限(+%)
	double fKagen;			// 下限（-%)
	BOOL bSaved;			// 保存済みフラグ

	// コンストラクタ
	SSeisanJoken() {
		fKinmuTaisei = 0;
		nKyusokuJikan = 0;
		nKadoNissu = 0;
		nKotaiKinmu = 0;
		fKadoJikan = 0;
		bKyoseiKudo = FALSE;
		fKadoRitsu = 100;
		fTact = 0;
		fJogen = 0;
		fKagen = 0;
		bSaved = FALSE;
	}
};

// 作業順データ
struct SSagyojun {
	int nIndex;					// 配列インデックス
	int nYosoSagyogunId;		// 要素作業群ID
	int nSagyojun;				// 作業順
	int nKishuId;				// 機種ID
};
typedef CArrayEx <SSagyojun, SSagyojun&> CSagyojunArray;

// 要素作業データ
struct SYosoSagyo {
	CString sYosoSagyoName;		// 要素作業名
	CString sYosoSagyoNo;		// 要素作業No
	int nSagyoNo;				// 作業No
	CString sDeleteFlag;		// 削除フラグ
	CString sInsertFlag;		// 追加フラグ
	int nRow;					// 行位置
	double fTime;				// 作業時間
	CString sEnzanShiki;		// 演算式
	double fS1;					// S1
	double fS2;					// S2
	double fM;					// M
	double fN;					// N
	double fEnzangoSagyoJikan;	// 演算後の作業時間
	int nHassexxxndo;			// 発生頻度
	int nKurikaeshiKaisu;		// 繰り返し回数
	CString sKoteibuHendobu;	// 固定部/変動部
	CString sSagyoFlag;			// 作業フラグ
	int nKijunKotei;			// 基準/工程
	BOOL bKittingDelete;		// キット化による削除フラグ

	// コンストラクタ
	SYosoSagyo() {
		bKittingDelete = FALSE;
	}
};
typedef CArrayEx <SYosoSagyo, SYosoSagyo&> CYosoSagyoArray;

// 機種別作業データ
struct SKishuSagyo {
	int nSagyojun;				// 作業順
	int nSagyoshaId;			// 作業者ID
	int nGroupNo;				// グループ番号
	BOOL bKyotsuKotei;			// 共通工程フラグ
	BOOL bNewMove;				// 最新移動フラグ
};
typedef CArrayEx <SKishuSagyo, SKishuSagyo&> CKishuSagyoArray;

// 要素作業群データ
struct SYosoSagyogun {
	CString sKoteiKigo;				// 工程記号
	CString sKoteiNo;				// 工程番号
	CString sKoteiName;				// 工程名
	CString sYosoSagyogunName;		// 要素作業群名
	CString sJidoYosoSagyogun;		// 要素作業群の自動種別
	CString sJidoKotei;				// 工程の自動種別
	CYosoSagyoArray aYosoSagyo;		// 要素作業データ配列
	CKishuSagyoArray aKishuSagyo;	// 機種別作業データ配列
	int nMoveSublineId;				// 移動前サブラインID
	BOOL bKittingCancel;			// キット化解除による復活フラグ

	// コンストラクタ
	SYosoSagyogun() {
		bKittingCancel = FALSE;
	}

	// コピーコンストラクタ
	SYosoSagyogun(SYosoSagyogun &src) {
		*this = src;
	}

	// 作業時間取得
	double GetTime() {
		double fTime = 0;
		int nSize = aYosoSagyo.GetSize();
		for (int i = 0; i < nSize; i++) {
			if (aYosoSagyo[i].sDeleteFlag.IsEmpty())
				fTime += aYosoSagyo[i].fTime;
		}
		return fTime;
	}

	// 削除チェック
	BOOL CheckDeleted() {
		int nSize = aYosoSagyo.GetSize();
		for (int i = 0; i < nSize; i++) {
			if (aYosoSagyo[i].sDeleteFlag != "1" && aYosoSagyo[i].sDeleteFlag != "2")
				return FALSE;
		}
		return TRUE;
	}
};

class CYosoSagyogunArray : public CArrayEx2 <SYosoSagyogun, SYosoSagyogun&> {
public:
	void MakeKey() {
		m_cMap.RemoveAll();
		int nSize = GetSize();
		for (int i = 0; i < nSize; i++) {
			SYosoSagyogun &c = ElementAt(i);
			m_cMap.SetAt(c.sKoteiName + c.sYosoSagyogunName, i);
		}
	}
};

// 工程データ
struct SKotei {
	CString sKoteiName;			// 工程名
	double fTime;				// 作業時間
	CIntArrayEx aYosoSagyogunId;	// 要素作業群ID配列
};
typedef CArrayEx <SKotei, SKotei&> CKoteiArray;

// 型式規制マトリクス列データ
struct SKatashikiKiseiCol {
	CString sKishuName;			// 機種名
	CString sSublineName;		// サブライン名
	CString sKoteiNo;			// 工程番号
	CString sKoteiName;			// 工程名
	CString sYosoSagyogunName;	// 要素作業群名
	CString sKisei;				// 規制
	CString sRiyu;				// 規制理由
};
typedef CArrayEx2 <SKatashikiKiseiCol, SKatashikiKiseiCol&> CKatashikiKiseiColArray;

// 型式規制マトリクス行データ
struct SKatashikiKisei {
	CString sKishuName;			// 機種名
	CString sSublineName;		// サブライン名
	CString sKoteiNo;			// 工程番号
	CString sKoteiName;			// 工程名
	CString sYosoSagyogunName;	// 要素作業群名
	CKatashikiKiseiColArray aKatashikiKiseiCol;	// 型式規制マトリクス列データ
};
typedef CArrayEx2 <SKatashikiKisei, SKatashikiKisei&> CKatashikiKiseiArray;

// ライン規制データ
struct SLineKisei {
	CString sSublineName;		// サブライン名
	CString sKoteiNo;			// 工程番号
	CString sKoteiName;			// 工程名
	CString sYosoSagyogunName;	// 要素作業群名
	CString sEngGenjo;			// ENG姿勢
	BOOL bEngSeiritsuFr;		// ENG正立Frフラグ
	BOOL bEngSeiritsuRr;		// ENG正立Rrフラグ
	BOOL bEngToritsuFr;			// ENG倒立Frフラグ
	BOOL bEngToritsuRr;			// ENG倒立Rrフラグ
	BOOL bEngChokuritsuTop;		// ENG直立Topフラグ
	BOOL bEngChokuritsuBottom;	// ENG直立Bottomフラグ
// --Add 2007/03/02 aizawa 不懸D-147
	int nRow;					// ライン規制マトリクスファイルの行位置
// --Add

	// コンストラクタ
	SLineKisei() {
		bEngSeiritsuFr = FALSE;
		bEngSeiritsuRr = FALSE;
		bEngToritsuFr = FALSE;
		bEngToritsuRr = FALSE;
		bEngChokuritsuTop = FALSE;
		bEngChokuritsuBottom = FALSE;
	}
};
typedef CArrayEx2 <SLineKisei, SLineKisei&> CLineKiseiArray;

// 型式規制違反データ
struct SKatashikiError {
	int	nKishuID;					// 機種ID
	CString sKishuName;				// 機種名
	CString sDay;					// 日にち
	CString sTime;					// 時間
	CString sSublineFrom;			// 移動サブライン名
	CString sKoteiNoFrom;			// 移動工程番号
	CString sKoteiNameFrom;			// 移動工程名
	CString sYosoSagyogunNameFrom;	// 移動要素作業群名
	CString sSublineTo;				// 移動先サブライン名
	CString sKoteiNoTo;				// 移動先工程番号
	CString sKoteiNameTo;			// 移動先工程名
	CString sYosoSagyogunNameTo;	// 移動先要素作業群名
	CString sSublineNg;				// 違反サブライン名
	CString sKoteiNoNg;				// 違反工程番号
	CString sKoteiNameNg;			// 違反工程名
	CString sYosoSagyogunNameNg;	// 違反要素作業群名
	CString sRiyuNo;				// 工順規制違反理由番号
	CString sRiyuMsg;				// 工順規制違反理由
};
typedef CArrayEx2 <SKatashikiError, SKatashikiError&> CKatashikiErrorArray;

// ライン規制違反データ
struct SLineError {
	int	nKishuID;					// 機種ID
	CString sDay;					// 日にち
	CString sTime;					// 時間
	CString sSublineFrom;			// 移動サブライン名
	CString sKoteiNoFrom;			// 移動工程番号
	CString sKoteiNameFrom;			// 移動工程名
	CString sYosoSagyogunNameFrom;	// 移動要素作業群名
	CString sSublineTo;				// 移動先サブライン名
	CString sKoteiNoToB;			// 移動先の前の工程番号
	CString sKoteiNameToB;			// 移動先の前の工程名
	CString sYosoSagyogunNameToB;	// 移動先の前の要素作業群名
	CString sKoteiNoToA;			// 移動先の後の工程番号
	CString sKoteiNameToA;			// 移動先の後の工程名
	CString sYosoSagyogunNameToA;	// 移動先の後の要素作業群名
	CString sRiyuNo;				// 工順規制違反理由番号
	CString sRiyuMsg;				// 工順規制違反理由
};
typedef CArrayEx2 <SLineError, SLineError&> CLineErrorArray;

// DSTR算出データ
struct SDSTRSanshutsu {
	double fHyojunJikan;			// 標準時間
	double fTeikiTenkenJikan;		// 定期点検時間
	double fHinshitsuLoss;			// 品質ﾛｽ
	double fSetsubiFuguaiLoss;		// 設備不具合ﾛｽ
	double fShujukuLoss;			// 習熟ﾛｽ
	double fLineTeishiLoss;			// ﾗｲﾝ停止ﾛｽ
	double fHenseiLoss;				// 編成ﾛｽ
	double fHukugouNoritsuBun;		// 複合能率分
	double fKanriTeishiJikan;		// 管理停止時間
	double fDST;					// DST

	// コンストラクタ
	SDSTRSanshutsu() {
		fHyojunJikan = 0;
		fTeikiTenkenJikan = 0;
		fHinshitsuLoss = 0;
		fSetsubiFuguaiLoss = 0;
		fShujukuLoss = 0;
		fLineTeishiLoss = 0;
		fHenseiLoss = 0;
		fHukugouNoritsuBun = 0;
		fKanriTeishiJikan = 0;
		fDST = 0;
	}
};

// 部品表＆工程リンクデータ
struct SBuhinhyoLink {
	CString sKatashiki;				// 型式
	CString sKishu;					// 機種
	CString sBlockNo;				// ﾌﾞﾛｯｸNo
	CString sBuhinNo;				// 部品番号
	CString sBuhinName;				// 部品名称
	int nPC;						// P/C
	BOOL bBoltFlag;					// ﾎﾞﾄﾙﾌﾗｸﾞ
	CString sSublineName;			// ﾘﾝｸ先ｻﾌﾞﾗｲﾝ名
	CString sKoteiKigo;				// ﾘﾝｸ先工程記号
	CString sKoteiNo;				// ﾘﾝｸ先工程番号
	CString sKoteiName;				// ﾘﾝｸ先工程名称
	CStringArray aYosoSagyogunName;	// ﾘﾝｸ先要素作業群名
};
typedef CArrayEx <SBuhinhyoLink, SBuhinhyoLink&> CBuhinhyoLinkArray;

// 要素作業修正データ
struct SYosoSagyoInfo {
	CString sSubline;			// サブライン名
	CString sKoteiKigo;			// 工程記号
	CString sKoteiNo;			// 工程番号
	CString sSagyoshaName;		// 作業者名
	CString sKoteiName;			// 工程名
	CString sYosoSagyogunName;	// 要素作業群名
	CString sJidoShudo;			// 自動／手動
};

// 移動処理データ
struct SMoveData {
	CString sName;				// 移動対象名
	int nOrg;					// 移動前
};
typedef CArrayEx <SMoveData, SMoveData&> CMoveDataArray;

// キット化要素作業データ
struct SKittingYosoSagyo {
	CString sYosoSagyoName;		// 要素作業名
	int nSagyoNo;				// 作業No
	int nYosoSagyoId;			// 要素作業ID
	BOOL bSakujoFlag;			// 削除フラグ
	double fTime;				// 作業時間
};
typedef CArrayEx <SKittingYosoSagyo, SKittingYosoSagyo&> CKittingYosoSagyoArray;

// キット化要素作業群データ
struct SKittingYosoSagyogun {
	CString sKoteiName;			// 工程名
	CString sYosoSagyogunName;	// 要素作業群名
	CKittingYosoSagyoArray aKittingYosoSagyo;	// キット化要素作業配列
};
typedef CArrayEx2 <SKittingYosoSagyogun, SKittingYosoSagyogun&> CKittingYosoSagyogunArray;

// キット化部品データ
struct SKittingBuhin {
	CString sBlockNo;			// ブロックNo
	CString sBuhinNo;			// 部品No
	CString sBuhinName;			// 部品名
	CIntArrayEx aPC;			// PC
	BOOL bCheck;				// キット化対象フラグ
	CIntArrayEx aKittingYosoSagyogunId;	// キット化要素作業群配列
};
typedef CArrayEx2 <SKittingBuhin, SKittingBuhin&> CKittingBuhinArray;

// 部品キット化データ
struct SBuhinKitting {
	CString sBlockNo;			// ブロックNo
	CString sBuhinNo;			// 部品No
	CString sBuhinName;			// 部品名
	BOOL bKittingFlag;			// キット化設定フラグ
};
typedef CArrayEx2 <SBuhinKitting, SBuhinKitting&> CBuhinKittingArray;

// キット化対象外部品
struct SKittingTaishogai {
	CString sBuhinNo;				// 部品No
};
typedef CArrayEx2 <SKittingTaishogai, SKittingTaishogai&> CKittingTaishogaiArray;

// サブラインデータ
struct SSubline {
	CString sSublineName;				// サブライン名
	CIntArrayEx aRowArray;				// 行位置配列
	CIntArrayEx aSeisanKishuFlag;		// 生産機種フラグ
	SSeisanJoken cSeisanJoken;			// 生産条件データ
	CStringArrayEx aSagyosha;			// 作業者データ配列
	CYosoSagyogunArray aYosoSagyogun;	// 要素作業群データ配列
	CYosoSagyogunArray aYosoSagyogunKit;	// キット化要素作業群データ配列
	CKittingYosoSagyogunArray aKittingYosoSagyogun;	// キット化要素作業群データ配列
	CKittingBuhinArray aKittingBuhin;	// キット化部品データ配列
	CBuhinKittingArray aBuhinKitting;	// 部品キット化データ配列
	CStringArrayEx aCheckBuhin;			// チェック済み部品
// Modify ... ( ADD )
	CStringArrayEx aCheckKotei;			// チェック済み工程
	CStringArrayEx aCheckYosoSagyogun;	// チェック済み要素作業群
// By Y.Itabashi (xxxxx) 2007.01.29
	CString sSagyoshaName;				// 作業者名の先頭１文字
	BOOL bSagyoshaWaritsuke;			// 作業者割付済みフラグ
	BOOL bSetKitting;					// キット化設定済みフラグ
	BOOL bManualEditFlag;				// 手動編成対象フラグ

	SSubline() {
		bSagyoshaWaritsuke = FALSE;
		bSetKitting = FALSE;
		bManualEditFlag = FALSE;
	}
};
typedef CArrayEx2 <SSubline, SSubline&> CSublineArray;

// 機種データ
struct SKishu {
	CString sKishuName;				// 機種名
	CString sKatashiki;				// 型式
	int nSeisanDaisu;				// 生産台数
	SDSTRSanshutsu cDSTRSanshutsu;	// DSTR算出データ
	CIntArrayEx aHonryuSublineId;	// 本流サブラインID

	// コンストラクタ
	SKishu() {
		nSeisanDaisu = 0;
	}
};
typedef CArrayEx2 <SKishu, SKishu&> CKishuArray;

// 型式データ
struct SKatashiki {
	CString sKatashiki;				// 型式名
	CExcelKatashikiKiseiMatrix eKatashikiKiseiMatrix;	// 型式規制マトリクスエクセルデータ
	BOOL bEditFlag;
};
typedef CArrayEx2 <SKatashiki, SKatashiki&> CKatashikiArray;

// 工程・機種別共通グループデータ
struct SKyotsuKoteiGroup {
	CString sKoteiName;			// 工程名
	struct SKishuGroupNo {
		int nKoteiGroupNo;		// 工程グループ番号
		CIntArrayEx aGroupNo;	// 機種別グループ番号
	};
	CArrayEx <SKishuGroupNo, SKishuGroupNo&> aKishuGroupNo;
};
typedef CArrayEx2 <SKyotsuKoteiGroup, SKyotsuKoteiGroup&> CKyotsuKoteiGroupArray;

// 工程別自動編成データ
struct SHenseiKotei {
	CString sKoteiNo;			// 工程番号
	CString sKoteiName;			// 工程名
	double fKajuHeikinTime;		// 加重平均時間
	BOOL bJido;					// 自動工程フラグ
	BOOL bMovable;				// 移動可能フラグ
	int nKishuId;				// 機種ID
	int nSagyoshaId;			// 作業者ID
	double fTime;				// 作業時間
	CIntArrayEx aYosoSagyogunId;	// 要素作業群ID配列
	CIntArrayEx aSagyojun;		// 作業順インデックス配列
	CIntArrayEx aGroupNo;		// グループ番号配列
};
typedef CArrayEx <SHenseiKotei, SHenseiKotei&> CHenseiKoteiArray;

// 機種別自動編成作業時間データ
struct SHenseiTime {
	double fKajuHeikinTime;			// 加重平均時間
	CArray <double, double> aTime;	// 作業時間配列
};

// 工程割付データ
struct SKoteiWaritsuke {
	int nSagyoshaId;			// 作業者ID
	int nGroupNo;				// グループ番号
};
typedef CArrayEx2 <SKoteiWaritsuke, SKoteiWaritsuke&> CKoteiWaritsukeArray;

// サブラインUndoデータ
struct SUndoSubline {
	CStringArrayEx aSagyosha;				// 作業者データ配列
	CYosoSagyogunArray aYosoSagyogun;		// 要素作業群データ配列
	CYosoSagyogunArray aYosoSagyogunKit;	// キット化要素作業群データ配列
	CBuhinKittingArray aBuhinKitting;	// 部品キット化データ配列
	CStringArrayEx aCheckBuhin;			// チェック済み部品
// Modify ... ( ADD )
	CStringArrayEx aCheckKotei;			// チェック済み工程
	CStringArrayEx aCheckYosoSagyogun;	// チェック済み要素作業群
// By Y.Itabashi (xxxxx) 2007.01.29
};
typedef CArrayEx <SUndoSubline, SUndoSubline&> CSUndoSublineArray;

// サブラインUndoデータ
struct SUndoData {
	int nCounter;						// Undoカウンタ
	CSUndoSublineArray aUndoSubline;	// サブラインUndoデータ
	CStringArrayEx aKittingSagyosha;	// キット化作業者データ配列
// --Add 2007/03/02 aizawa 不懸D-147
	CLineKiseiArray m_aLineKisei;		// ライン規制データ配列
// --Add

	// コンストラクタ
	SUndoData() {
		nCounter = -1;
	}
};

// Undoバッファ
struct SUndoBuf {
	SUndoData aUndoData[MAX_UNDO];		// Undoデータバッファ
	int nIndex;							// Undoデータバッファインデックス
	int nCounter;						// Undoカウンタ

	// コンストラクタ
	SUndoBuf() {
		nIndex = 0;
		nCounter = 1;
	}
};

// 工順規制チェック種別
enum EKojunCheckType {
	eKojunCheckTypeNoMessage,			// エラーメッセージ無し
	eKojunCheckTypeKotei,				// 工程移動チェック
	eKojunCheckTypeYosoSagyogun			// 要素作業群移動チェック
};

// 工順規制エラーコード
enum EKojunErr {
	eKojunErrNormal,					// エラー無し
	eKojunErrKatashikiJoryuNG,			// 上流NG
	eKojunErrKatashikiKaryuNG			// 下流NG
};

// 工順エラー情報
struct SKojunErrInfo {
	EKojunErr nErrCode;					// エラーコード
	CString sTargetKoteiName;			// 移動元工程名
	CString sTargetYosoSagyogunName;	// 移動元要素作業群名
	CString sErrorKoteiName;			// 移動先工程名
	CString sErrorYosoSagyogunName;		// 移動先要素作業群名
};
typedef CArrayEx <SKojunErrInfo, SKojunErrInfo&> CKojunErrInfoArray;

// クリップボードデータ
typedef char ClipBoardData[48][256];

// 要素作業テンプレートデータ
struct SYosoSagyoTemplate {
	CString sYosoSagyoNo;			// 要素作業NO.
	CString sSagyoNaiyo;			// 作業内容
	CString sEnzanShiki;			// 演算式
	double fS1;						// S1
	double fS2;						// S2
	double fM;						// M
	double fN;						// N
	double fEnzangoSagyoJikan;		// 演算後の作業時間
	int nHassexxxndo;				// 発生頻度
	int nKurikaeshiKaisu;			// 繰り返し回数
	double fDaiatariSagyoJikan;		// 台あたりの要素作業毎作業時間
	CString sComment;				// ｺﾒﾝﾄ
	CString sSagyoFlag;				// 作業ﾌﾗｸﾞ
};
typedef CArrayEx2 <SYosoSagyoTemplate, SYosoSagyoTemplate&> CYosoSagyoTemplateArray;

// データ管理クラス
class CDataManager
{
public:
	CDataManager();

	int m_nStatus;				// ステータス
	int m_nYear;				// 対象年
	int m_nMonth;				// 対象月
	BOOL m_bUpdate;				// 更新有無フラグ
	BOOL m_bOpenLineKiseiMatrix;		// ライン規制マトリクスファイルオープン中フラグ
	BOOL m_bOpenKatashikiKiseiMatrix;	// 型式規制マトリクスファイルオープン中フラグ

	// DataManager.cpp
	int GetSublineNum() { return m_aSubline.GetSize(); }
	int GetKishuNum() { return m_aKishu.GetSize(); }
	int GetKatashikiNum() { return m_aKatashiki.GetSize(); }
	int GetSagyoshaNum(int nSublineId) { return m_aSubline[nSublineId].aSagyosha.GetSize(); }
	int GetKittingSagyoshaNum() { return m_aKittingSagyosha.GetSize(); }
	int GetSeisanDaisuNum() { return m_aSeisanDaisu.GetSize(); }
	int GetYosoSagyoTemplateNum() { return m_aYosoSagyoTemplate.GetSize(); }
	SSubline *GetSublineData(LPCTSTR pSublineName) { return m_aSubline.LookupElement(pSublineName); }
	SSubline *GetSublineData(int nSublineId) { return &m_aSubline[nSublineId]; }
	SKishu *GetKishuData(int nKishuId) { return &m_aKishu[nKishuId]; }
	SKishu *GetKishuData(LPCTSTR pKishuName) { return m_aKishu.LookupElement(pKishuName); }
	SKatashiki *GetKatashikiData(int nKatashikiId) { return &m_aKatashiki[nKatashikiId]; }
	SSeisanDaisu *GetSeisanDaisuData(int nIndex) { return &m_aSeisanDaisu[nIndex]; }
	SSeisanDaisu *GetSeisanDaisuData(LPCTSTR pYexxxonth) { return m_aSeisanDaisu.LookupElement(pYexxxonth); }
	SYosoSagyogun *GetYosoSagyogun(int nSublineId, int nIndex) { return &m_aSubline[nSublineId].aYosoSagyogun.ElementAt(nIndex); }
	SYosoSagyogun *GetYosoSagyogun(int nSublineId, const CString &sKoteiName, const CString &sYosoSagyogunName);
	SYosoSagyoTemplate *GetYosoSagyoTemplateData(int nIndex) { return &m_aYosoSagyoTemplate[nIndex]; }
	SYosoSagyoTemplate *GetYosoSagyoTemplateData(LPCTSTR pYosoSagyoNo) { return m_aYosoSagyoTemplate.LookupElement(pYosoSagyoNo); }
	CString &GetSagyoshaName(int nSublineId, int nIndex) { return m_aSubline[nSublineId].aSagyosha[nIndex]; }
	CString &GetKittingSagyoshaName(int nIndex) { return m_aKittingSagyosha[nIndex]; }
	void SetUpdate() { m_bUpdate = TRUE; }
// Modify ... ( ADD )
	SDSTRSanshutsu *GetTotalDSTRSanshutsu()	{ return &m_cTotalDSTRSanshutsu;	}
	int GetTotalDSTRSetFlag()	{ return m_aTotalDSTRSanshutsu;	}
	void SetTotalDSTRSetFlag( int nFlag )	{ m_aTotalDSTRSanshutsu = nFlag;	}
// By Y.Itabashi (xxxxx) 2007.0214

	void FindSagyoWariate(int nSublineId, int nSagyoshaId, int nKishuId, CIntArrayEx &aYosoSagyogunId);
	void SetSeisanDaisu(SSeisanDaisu &cSeisanDaisu, BOOL bCopy);
// Modify ... ( CHANGE )
	int GetTotalSeisanDaisu( int nSublineId );
//	int GetTotalSeisanDaisu();
// By Y.Itabashi (xxxxx) 2007.02.11
	void GetSagyojun(int nSublineId, int nKishuId, CSagyojunArray &aSagyojun);
	void GetKittingSagyojun(int nSublineId, int nKishuId, CSagyojunArray &aSagyojun);
	void GetSagyojunSub(CYosoSagyogunArray &aYosoSagyogun, int nKishuId, CSagyojunArray &aSagyojun);
	void GetKotei(int nSublineId, const CString &sKoteiName, SKotei &cKotei);
	void GetKoteiArray(int nSublineId, int nKishuId, CKoteiArray &cKoteiArray);
	void GetKittingKoteiArray(int nSublineId, CKoteiArray &cKoteiArray);
	void OrderBySagyojun(int nSublineId, CSagyojunArray &aSagyojun);
	void OrderByKittingSagyojun(int nSublineId, CSagyojunArray &aSagyojun);
	void OrderBySagyojunSub(CYosoSagyogunArray &aYosoSagyogun, CSagyojunArray &aSagyojun);
// Modify ... ( CHANGE )
	BOOL CheckMokuhyoTactJoukagen(int nSublineId, int nSagyoshaId, int nKishuId, int nKishuType);
//	BOOL CheckMokuhyoTactJoukagen(int nSublineId, int nSagyoshaId, int nKishuId);
// By Y.Itabashi (xxxxx) 2007.02.12
	int GetNewGroupNo(int nSublineId);
	int GetSeisanDaisu(int nSublineId);
	void SetKyotsuKoteiFlag();
	void GetKyotsuKoteiGroup(int nSublineId, CKyotsuKoteiGroupArray &aKyotsuKoteiGroup);
	int GetMinTactSubline();
	double GetKittingTact();
	void SetSagyojun();
	void SetSagyojunSub(int nSublineId);
// Modify ... ( ADD )
	CString RemoveUnderbarDigit(const CString &sName);
// By Y.Itabashi (xxxxx) 2007.02.27

	// DataManagerEdit.cpp
	void KoteiBunkatsu(int nSublineId, const CString &sKoteiName, CStringArray &aYosoSagyogunName);
	void YosoSagyogunBunkatsu(int nSublineId, const CString &sKoteiName, const CString &sYosoSagyogunName, CIntArrayEx &aYosoSagyo);
	void InsertSagyosha(int nSublineId, int nSagyoshaId);
	void DeleteSagyosha(int nSublineId, int nSagyoshaId);
	BOOL CheckSagyoshaSagyo(int nSublineId, int nSagyoshaId);
	void CopyClipboard(int nSublineId, CIntArrayEx &aYosoSagyogunId, LPCTSTR pTsuikaFlag);
	BOOL PasteClipboard(int nSublineId, int nYosoSagyogunId, int nKishuId, int nSagyoshaId);
	void GetYosoSagyogun(int nSublineId, CIntArrayEx &aYosoSagyogunId, CYosoSagyogunArray &aYosoSagyogun);
	void DeleteYosoSagyogun(int nSublineId, CIntArrayEx &aYosoSagyogunId, LPCTSTR pDeleteFlag);
	void AddYosoSagyogun(int nSublineId, CYosoSagyogunArray &aYosoSagyogun);
	void ClearNewMoveFlag();
	int AddYosoSagyoHyo();
// --Add 2007/02/26 aizawa 不懸D-187
	void RenumberSagyoNo();
// --Add

	// DataManagerKojunKisei.cpp
// Modify ... ( CHANGE )
	BOOL CheckKojunKisei(int nSublineIdFrom, int nSublineId, EKojunCheckType eKojunCheckType);
//	BOOL CheckKojunKisei(int nSublineId, EKojunCheckType eKojunCheckType);
// By Y.Itabashi (xxxxx) 2007.02.07
	BOOL CheckKojunKisei(int nSublineId, CIntArrayEx &aYosoSagyogunId, int nKishuId, CSagyojunArray &aSagyojun);
	void OpenLineKiseiMatrix();
	void OpenKatashikiKiseiMatrix();
	void OpenExcelNotify(UINT nParam);
	void CloseExcelNotify(UINT nParam);
	void ErrorExcelNotify(UINT nParam);
// Modify ... ( ADD )
// Modify ... ( CHANGE )
	// 工程/要素作業群の移動実行前の位置を設定
	void SetMoveBeforeSagyojun(int nSublineId, int nSublineToid, CString sKoteiName, CString sYosoSagyogunName, CString sCheckKoteiName, CString sCheckYosoSagyogunName, int nYosoSagyogunId, CIntArrayEx &aYosoSagyogunId, int nKishuId, EKojunCheckType eKojunCheckType);
	void SetMoveBeforeSagyojun(int nSublineId, CString sKoteiName, CString sYosoSagyogunName,
								int nKishuId, int nSetSagyojun, EKojunCheckType eKojunCheckType);
// By Y.Itabashi (xxxxx) 2007.02.28
	// 工程/要素作業群の移動実行前の位置を取得
	int GetMoveBeforeSagyojun(int nSublineId, CString sKoteiName, CString sYosoSagyogunName, int nKishuId);
	// 工程/要素作業群の移動実行前の位置を初期化
	void InitMoveBeforeSagyojun();
// By Y.Itabashi (xxxxx) 2007.02.06
// --Add 2007/03/02 aizawa 不懸D-147
	void UpdateLineKiseiSubline(int nSublineIdFrom, int nSublineIdTo);
	void ChangeLineKiseiSubline(int nSublineIdFrom, int nSublineIdTo, const CString &sKoteiNo, const CString &sKoteiName, const CString &sYosoSagyogunName);
// --Add

	// DataManagerKitting.cpp
	void GetKittingData();
	void DeleteKittingSagyo(int nSublineId);
	void AddKittingTemplate(int nSublineId);
	void CancelKitting(int nSublineId);
	void CancelKitting(int nSublineId, LPCTSTR pKoteiName);
	void SetBuhinKitting();
	void ClearKittingCancelFlag();

	// DataManagerJidoHensei.cpp
	void JidoHensei(CIntArrayEx &aSublineId, int nHenseiMode, int nTactKeisan, int nKijunKishuId);
	void JidoHensei1Kitting(CIntArrayEx &aSublineId);

	// DataManagerFile.cpp
	BOOL ReadFile(LPCTSTR pFolderName);
	BOOL ReadYosoSagyoTemplate();
	BOOL SaveFile();
	BOOL SaveAsFile(LPCTSTR pFolderName);
	BOOL SaveKittingTaishoBuhin(LPCTSTR sPathName);
	void ConvertHankaku(CString &sName);
	// Modify ... ( ADD )
	BOOL ExportGraphTemplate(LPCTSTR pPathName);
	CString GetNewExportGraphTemplateFileName() {return m_eExportGraphTemplate.GetNewFileName2();};
	// By K.Kimura (xxxxx) 2007.02.07
	// Modify ... ( ADD )
	void SetExportCondition(bool* m_aCheckedSubline, bool* m_aCheckedKishu, bool m_bCheckedWaritsuke, bool m_bCheckedZenkishu, bool m_bCheckedKajuHeikin);
	// By K.Kimura (xxxxx) 2007.02.16
// Modify ... ( ADD )
	void SaveKojunKiseiError();
	void AddKojunKiseiError(SKatashikiError cKatashikiError, SLineError cLineError);
// By Y.Itabashi (xxxxx) 2007.02.19

	// DataManagerUndo.cpp
	void ResetUndoBuf();
	void SaveUndoBuf();
	BOOL CheckUndo();
	BOOL CheckRedo();
	void Undo(BOOL bRedo);
	void Redo();
	void RestoreUndoData();

protected:
	CExcelSeisanDaisu m_eSeisanDaisu;					// 生産台数情報エクセルデータ
	CExcelKinmuTaisei m_eKinmuTaisei;					// 勤務体制情報エクセルデータ
	CExcelBuhinhyoLink m_eBuhinhyoLink;					// 部品表＆工程リンク情報エクセルデータ
	CExcelDSTRSanshutsu m_eDSTRSanshutsu;				// DSTR算出用情報エクセルデータ
	CExcelYosoSagyoHyo m_eYosoSagyoHyo;					// 要素作業表エクセルデータ
	CExcelLineKiseiMatrix m_eLineKiseiMatrix;			// ライン規制マトリクスエクセルデータ
	CExcelKittingTemplate m_eKittingTemplate;			// キット化テンプレートエクセルデータ
	CExcelSeisanJoken m_eSeisanJoken;					// 生産条件設定エクセルデータ
	CExcelBuhinKitting m_eBuhinKitting;					// 部品キット化情報エクセルデータ
	CExcelKyotsuKotei m_eKyotsuKotei;					// 共通工程情報エクセルデータ
	CExcelKittingTaishogai m_eKittingTaishogai;			// キット対象外部品リストエクセルデータ
// Modify ... ( ADD )
	CExcelKatashikiKiseiError m_eKatashikiError;		// 型式規制違反エクセルデータ
	CExcelLineKiseiError m_eLineError;					// ライン規制違反エクセルデータ
// By Y.Itabashi (xxxxx) 2007.02.14
	CSublineArray m_aSubline;							// サブラインデータ配列
	CKishuArray m_aKishu;								// 機種データ配列
	CSeisanDaisuArray m_aSeisanDaisu;					// 生産台数配列
	CBuhinhyoLinkArray m_aBuhinhyoLink;					// 部品表＆工程リンクデータ配列
	CKittingTaishogaiArray m_aKittingTaishogai;			// キット化対象外部品データ配列
	CLineKiseiArray m_aLineKisei;						// ライン規制データ配列
	CKatashikiKiseiArray m_aKatashikiKisei;				// 型式規制マトリクスデータ配列
	CKatashikiArray m_aKatashiki;						// 型式データ配列
	CStringArrayEx m_aKittingSagyosha;					// キット化作業者データ配列
	CYosoSagyoTemplateArray m_aYosoSagyoTemplate;		// 要素作業テンプレートデータ配列
	SUndoBuf m_aUndoBuf;								// Undoバッファ
	int m_nCopyCount;									// コピーカウンタ
// Modify ... ( ADD )
	CArrayEx2 <int, CString&> aBeforeMoveSagyojun;		// 工程/要素作業群移動時の元の位置
// By Y.Itabashi (xxxxx) 2007.02.06
// Modfiy ... ( ADD )
	CExcelExportGraphTemplate m_eExportGraphTemplate;	// 割付作業グラフエクセルデータ
// By K.Kimura (xxxxx) 2007.02.06
// Modify ... ( ADD )
	SDSTRSanshutsu m_cTotalDSTRSanshutsu;				// 全機種DSTR算出データ
	int m_aTotalDSTRSanshutsu;							// 全機種DSTR算出データセットフラグ
	CKatashikiErrorArray m_aKatashikiError;				// 型式規制違反データ
	CLineErrorArray m_aLineError;							// ライン規制違反データ
// By Y.Itabashi (xxxxx) 2007.02.14
// Modify ... ( ADD )
	CString m_sKittingKoteiKigo;						// キット化作業の工程記号
// By Y.Itabasshi (xxxxx) 2007.03.07

	// DataManager.cpp
	void SortSagyojun(CSagyojunArray &aSagyojun);
	static int CompareSagyojun(const void *arg1, const void *arg2);
	CString NewKoteiName(int nSublineId, const CString &sName, int nPostfix);
	CString NewYosoSagyogunName(int nSublineId, const CString &sName, int nPostfix);
	double GetSagyoJikan(int nSublineId, int nSagyoshaId, int nKishuId);
	void RenumberSagyoshaId(int nSublineId);
	void GetSagyoshaJun(int nSublineId, CIntArrayEx &aSagyoshaJun);
	void UpdateSagyoshaId(int nSublineId, CIntArrayEx &aSagyoshaJun);
	void RenameSagyoshaName(int nSublineId);
	void SetDefaultGroupNo(int nSublineId);
// Modify ... ( DELETE )
//	CString RemoveUnderbarDigit(const CString &sName);
// By Y.Itabashi (xxxxx) 2007.02.27

	// DataManagerEdit.cpp
	void AdjustSagyoshaId(int nSublineId, int nSagyoshaId, int nAdjust);

	// DataManagerKojunKisei.cpp
	BOOL LineKiseiCheck(int nKishuId, int nSublineIdFrom, int nSublineIdTo, int nYosoSagyogunId1, int nYosoSagyogunId2, CSagyojunArray &aSagyojun, BOOL &bNoCheck);
	BOOL LineKiseiError(EKojunCheckType eKojunCheckType);
// Modify ... ( CHANGE )
	BOOL KatashikiKiseiCheck(int nKishuId, int nSublineIdFrom, int nSublineIdTo, int nYosoSagyogunId, CSagyojunArray &aSagyojun, CKojunErrInfoArray &aKojunErrInfo, BOOL bAllCheck, EKojunCheckType eKojunCheckType, BOOL &bNoCheck);
//	BOOL KatashikiKiseiCheck(int nKishuId, int nSublineIdFrom, int nSublineIdTo, int nYosoSagyogunId, CSagyojunArray &aSagyojun, CKojunErrInfoArray &aKojunErrInfo, BOOL bAllCheck);
// By Y.Itabashi (xxxxx) 2007.02.21
	BOOL KatashikiKiseiError(EKojunCheckType eKojunCheckType, CKojunErrInfoArray &aKojunErrInfo);
	SLineKisei *FindLineKisei(int nSublineId, const CString &sKoteiNo, const CString &sKoteiName, const CString &sYosoSagyogunName);
	SKatashikiKisei *FindKatashikiKisei(const CString &sKishuName, int nSublineId, const CString &sKoteiNo, const CString &sKoteiName, const CString &sYosoSagyogunName);
	SKatashikiKiseiCol *FindKatashikiKiseiCol(SKatashikiKisei *pKatashikiKisei, const CString &sKishuName, int nSublineId, const CString &sKoteiNo, const CString &sKoteiName, const CString &sYosoSagyogunName);
	static UINT OpenExcelThread(LPVOID pParam);

	// DataManagerKitting.cpp
	void GetKittingDataSub(int nSublineId);
	BOOL CheckKittingBuhinNo(const SBuhinhyoLink &cBuhinhyoLink);
	BOOL CheckDefaultSakujoSagyo(const CString &sYosoSagyoNo);
	void SetKittingDeleteFlag();
	void SetKittingDeleteFlagSub(int nSublineId);

	// DataManagerJidoHensei.cpp
// --Change 2007/03/01 aizawa 不懸D-198
//	void JidoHenseiSub(int nSublineId, int nHenseiMode, int nTactKeisan, int nKijunKishuId);
	void JidoHenseiSub(int nSublineId, int nHenseiMode, int nTactKeisan, int nKijunKishuId, int nProcessSublixxxnt, int nProcessSublineNum);
// --Change
	void JidoHensei1(int nSublineId, int nTactKeisan, int nKijunKishuId, int &nMaxSagyoshaId);
	void JidoHensei1Sub(int nSublineId, int &nMaxSagyoshaId, int nMaxSagyoshaId2, int nKishuId, CKoteiArray &aKotei, CKoteiWaritsukeArray &cKoteiWaritsukeArray, BOOL bReverse, BOOL bNewWaritsuke, BOOL bKijunKishu, int nTactKeisan);
	BOOL JidoHensei1Sub2(int nSublineId);
	void JidoHensei2(int nSublineId, int nTactKeisan);
	static int CompareKishuJun(const void *arg1, const void *arg2);
	void GetHenseiKotei(int nSublineId, CSagyojunArray &aSagyojun, CHenseiKoteiArray &aHenseiKotei);
	void GetHenseiTime(int nSublineId, CHenseiKoteiArray &aHenseiKotei, int nSagyoshaId, SHenseiTime &cHenseiTime);
	void DispProgressBar(int nKishuId, LPCTSTR pProcess, int nCount);
// --Add 2007/03/01 aizawa 不懸D-198
	void DispProgressBarSubline(int nSublineId, LPCTSTR pProcess, int nProcessCnt, int ProcessNum);
// --Add

	// DataManagerFile.cpp
	BOOL ReadFileSub();
	BOOL CheckProjectFile();
	BOOL CopyFromServer(LPCTSTR pFileName);
	BOOL CopyFromServer(LPCTSTR pServerFolder, LPCTSTR pFileName);
	BOOL ReadProjectFile();
	void DispReadProcess(CExcelFile &cExcelFile);
	BOOL ReadYosoSagyoHyo();
	BOOL ExtractSubline();
	BOOL ExtractKishuName();
	BOOL ExtractYosoSagyogun();
	BOOL ExtractYosoSagyogunSub(int nSublineId, int nStartRowIndex = 0);
	BOOL CheckYosoSagyoHyo();
	void CheckCopyCount(const CString &sName);
	BOOL ReadSeisanDaisu();
	BOOL ReadKinmuTaisei();
	BOOL ReadSeisanJoken();
	BOOL ReadDSTRSanshutsu();
	BOOL ReadBuhinhyoLink();
	BOOL ReadLineKiseiMatrix();
	BOOL GetLineKiseiFlag(int nRow, int nCol);
	BOOL ReadKatashikiKiseiMatrix();
	BOOL ReadKatashikiKiseiMatrixSub1(CExcelKatashikiKiseiMatrix &cExcelKatashikiKiseiMatrix);
// Modify ( CHANGE )
	BOOL ReadKatashikiKiseiMatrixSub2(CExcelKatashikiKiseiMatrix &cExcelKatashikiKiseiMatrix);
	BOOL ReadKatashikiKiseiMatrixSub3(CExcelKatashikiKiseiMatrix &cExcelKatashikiKiseiMatrix, int nRow);
//	void ReadKatashikiKiseiMatrixSub2(CExcelKatashikiKiseiMatrix &cExcelKatashikiKiseiMatrix);
//	void ReadKatashikiKiseiMatrixSub3(CExcelKatashikiKiseiMatrix &cExcelKatashikiKiseiMatrix, int nRow);
// By Y.Itabashi (xxxxx) 2007.02.20
	BOOL CheckKatashikiKiseiMatrix();
	BOOL ReadKittingTemplate();
	BOOL ReadBuhinKitting();
	BOOL ReadKyotsuKotei();
	BOOL ReadKittingTaishogai();
// Modify ... ( ADD )
	BOOL ReadKatashikiError();
	BOOL ReadLineError();
// By Y.Itabashi (xxxxx) 2007.02.14
	BOOL SaveAsFileSub(LPCTSTR pFolderName);
	BOOL SaveFileSub();
	BOOL SaveProjectFile(LPCTSTR pFolderName, LPCTSTR pErrCode);
	void DispSaveProcess(CExcelFile &cExcelFile);
	BOOL MakeFolder(LPCTSTR pFolderName);
	BOOL CopyProjectFile(LPCTSTR pFolderName);
	BOOL SaveSeisanDaisu(LPCTSTR pFolderName, LPCTSTR pErrCode);
	BOOL SaveKinmuTaisei(LPCTSTR pFolderName, LPCTSTR pErrCode);
	BOOL SaveDSTRSanshutsu(LPCTSTR pFolderName, LPCTSTR pErrCode);
	BOOL SaveYosoSagyoHyo(LPCTSTR pFolderName, LPCTSTR pErrCode);
// Modify ... ( ADD )
	BOOL SaveKatashikiError(LPCTSTR pFolderName, LPCTSTR pErrCode);
	BOOL SaveLineError(LPCTSTR pFolderName, LPCTSTR pErrCode);
// By Y.Itabashi (xxxxx) 2007.02.14
	void UpdateYosoSagyoHyo();
	void UpdateYosoSagyoHyoSub(CString &sSublineName, CYosoSagyogunArray &aYosoSagyogun, CStringArray &aSagyosha, CExcelData &cTempArray, int &nRow);
	BOOL SaveSeisanJoken(LPCTSTR pFolderName, LPCTSTR pErrCode);
	BOOL SaveBuhinKitting(LPCTSTR pFolderName, LPCTSTR pErrCode);
	BOOL SaveKyotsuKotei(LPCTSTR pFolderName, LPCTSTR pErrCode);
// --Add 2007/03/02 aizawa 不懸D-147
	BOOL SaveLineKiseiMatrix(LPCTSTR pFolderName, LPCTSTR pErrCode);
// --Add
	// Add 
	BOOL ExportGraphTemplateSub1(CExcelExportGraphTemplate &cExcelGraphTemplate, LPCTSTR pPathName);
	BOOL ExportGraphTemplateSub2(CExcelExportGraphTemplate &cExcelGraphTemplate, LPCTSTR pPathName);
    // By K.Fujii (xxxxx) 2007.02.16
// Modify ... ( ADD )
	BOOL ExportGraphTemplateWaritsuke1(CExcelExportGraphTemplate &cExcelGraphTemplate, LPCTSTR pPathName);
	BOOL ExportGraphTemplateWaritsuke2(CExcelExportGraphTemplate &cExcelGraphTemplate, LPCTSTR pPathName);
// By Y.Itabashi (xxxxx) 2007.02.19

	// DataManagerUndo.cpp
	void SaveUndoData();
};
