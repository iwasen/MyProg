//*****************************************************************************************************
//  1. ファイル名
//		DataManager.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		CDataManager クラスのインプリメンテーション
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************

#include "stdafx.h"
#include "workscheduler.h"
#include "ManualEditFrameWnd.h"
#include "ManualEditDocument.h"
#include <math.h>

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

// 要素作業群順番設定用データ
struct SYosoSagyogunJunban {
	int nYosoSagyogunId;
	int nKishuId;
	int nGroupNo;
	int nSagyoshaId;
	CString sKoteiName;
	BOOL bKyotsuKotei;
};
typedef CArrayEx <SYosoSagyogunJunban, SYosoSagyogunJunban&> CYosoSagyogunJunbanArray;

//*****************************************************************************************************
//  1. 関数名
//		CDataManager::CDataManager
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		コンストラクタ
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		クラス変数を初期化する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
CDataManager::CDataManager()
{
	// クラス変数を初期化
	m_nStatus = STATUS_NOREAD;
	m_nYear = 0;
	m_nMonth = 0;
	m_bUpdate = FALSE;
	m_bOpenLineKiseiMatrix = FALSE;
	m_bOpenKatashikiKiseiMatrix = FALSE;
	m_nCopyCount = 1;
// Modify ... ( ADD )
	aBeforeMoveSagyojun.RemoveAll();
// By Y.Itabashi (xxxxx) 2007.02.06
// Modify ... ( ADD )
	m_sKittingKoteiKigo = KITTING_KOTEI_KIGO;
// By Y.Itabasshi (xxxxx) 2007.03.07
}

//*****************************************************************************************************
//  1. 関数名
//		CDataManager::GetSagyojun
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		サブライン作業順取得
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		int				nSublineId		[I] サブラインID
//		int				nKishuId		[I] 機種ID
//		CSagyojunArray	&aSagyojun		[O] 作業順データ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		指定されたサブライン、機種の作業順を取り出す。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CDataManager::GetSagyojun(int nSublineId, int nKishuId, CSagyojunArray &aSagyojun)
{
	GetSagyojunSub(m_aSubline[nSublineId].aYosoSagyogun, nKishuId, aSagyojun);
}

//*****************************************************************************************************
//  1. 関数名
//		CDataManager::GetKittingSagyojun
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		キット化作業順取得
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		int				nSublineId		[I] サブラインID
//		int				nKishuId		[I] 機種ID
//		CSagyojunArray	&aSagyojun		[O] 作業順データ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		指定されたサブライン、機種のキット化作業順を取り出す。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CDataManager::GetKittingSagyojun(int nSublineId, int nKishuId, CSagyojunArray &aSagyojun)
{
	GetSagyojunSub(m_aSubline[nSublineId].aYosoSagyogunKit, nKishuId, aSagyojun);
}

//*****************************************************************************************************
//  1. 関数名
//		CDataManager::GetSagyojunSub
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		作業順取得
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CYosoSagyogunArray	&aYosoSagyogun		[I] 要素作業群配列データ
//		int					nKishuId			[I] 機種ID
//		CSagyojunArray		&aSagyojun			[O] 作業順データ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		指定された要素作業群配列、機種から作業順を取り出す。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CDataManager::GetSagyojunSub(CYosoSagyogunArray &aYosoSagyogun, int nKishuId, CSagyojunArray &aSagyojun)
{
	int i;
	int nIndex;
	int nYosoSagyogunNum = aYosoSagyogun.GetSize();

	aSagyojun.RemoveAll();
	for (i = 0; i < nYosoSagyogunNum; i++) {
		SYosoSagyogun &cYosoSagyogun = aYosoSagyogun[i];
		if (!cYosoSagyogun.CheckDeleted()) {
			if (nKishuId != -1) {
				// 機種指定が無い場合
				SKishuSagyo &cKishuSagyo = cYosoSagyogun.aKishuSagyo[nKishuId];

				if (cKishuSagyo.nSagyojun != 0) {
					SSagyojun *pSagyojun = aSagyojun.AddElement(nIndex);
					pSagyojun->nIndex = nIndex;
					pSagyojun->nYosoSagyogunId = i;
					pSagyojun->nSagyojun = cKishuSagyo.nSagyojun;
					pSagyojun->nKishuId = nKishuId;
				}
			} else {
				// 機種指定が有る場合
				int nKishuNum = m_aKishu.GetSize();
				for (int nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
					SKishuSagyo &cKishuSagyo = cYosoSagyogun.aKishuSagyo[nKishuId];

					if (cKishuSagyo.nSagyojun != 0) {
						SSagyojun *pSagyojun = aSagyojun.AddElement(nIndex);
						pSagyojun->nIndex = nIndex;
						pSagyojun->nYosoSagyogunId = i;
						pSagyojun->nSagyojun = cKishuSagyo.nSagyojun;
						pSagyojun->nKishuId = nKishuId;
					}
				}
			}
		}
	}

	// 作業順ソート
	SortSagyojun(aSagyojun);
}

//*****************************************************************************************************
//  1. 関数名
//		CDataManager::SortSagyojun
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		作業順ソート
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CSagyojunArray	&aSagyojun		[I/O] 作業順データ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		作業順データを作業順にソートする。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CDataManager::SortSagyojun(CSagyojunArray &aSagyojun)
{
	qsort(aSagyojun.GetData(), aSagyojun.GetSize(), sizeof(SSagyojun), CompareSagyojun);
}

//*****************************************************************************************************
//  1. 関数名
//		CDataManager::CompareSagyojun
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		作業順比較
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		void	*arg1		[I] 比較する作業順データ
//		void	*arg2		[I] 比較するもう一方の作業順データ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		int		比較結果
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		作業順データの作業順を比較する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//		qsort()からコールバックされる。
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
int CDataManager::CompareSagyojun(const void *arg1, const void *arg2)
{
	SSagyojun *pSagyojun1 = (SSagyojun *)arg1;
	SSagyojun *pSagyojun2 = (SSagyojun *)arg2;

	// 作業順が同じ場合は要素作業群IDで比較
	if (pSagyojun1->nSagyojun == pSagyojun2->nSagyojun)
		return pSagyojun1->nYosoSagyogunId - pSagyojun2->nYosoSagyogunId;

	// 作業順を比較
	return pSagyojun1->nSagyojun - pSagyojun2->nSagyojun;
}

//*****************************************************************************************************
//  1. 関数名
//		CDataManager::FindSagyoWariate
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		指定作業者、機種の要素作業群を取得
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		int			nSublineId			[I] サブラインID
//		int			nSagyoshaId			[I] 作業者ID
//		int			nKishuId			[I] 機種ID
//		CIntArrayEx	&aYosoSagyogunId	[O] 要素作業群ID配列
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		指定されたサブラインID、作業者ID、機種IDの要素作業群を抽出する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CDataManager::FindSagyoWariate(int nSublineId, int nSagyoshaId, int nKishuId, CIntArrayEx &aYosoSagyogunId)
{
	int i, j, k;
	SSubline &cSubline = m_aSubline[nSublineId];
	CYosoSagyogunArray &aYosoSagyogun = cSubline.aYosoSagyogun;

	aYosoSagyogunId.RemoveAll();

	if (nKishuId != -1) {
		// 機種指定がある場合
		CSagyojunArray aSagyojun;
		GetSagyojun(nSublineId, nKishuId, aSagyojun);
		int nSagyojun = aSagyojun.GetSize();
		for (i = 0; i < nSagyojun; i++) {
			int nYosoSagyogunId = aSagyojun[i].nYosoSagyogunId;
			if (aYosoSagyogun[nYosoSagyogunId].aKishuSagyo[nKishuId].nSagyoshaId == nSagyoshaId)
				aYosoSagyogunId.Add(nYosoSagyogunId);
		}
	} else {
		// 機種指定がない場合
		int nYosoSagyogun = aYosoSagyogun.GetSize();
		int nKishuNum = GetKishuNum();
		for (i = 0; i < nYosoSagyogun; i++) {
			for (j = 0; j < nKishuNum; j++) {
				if (aYosoSagyogun[i].aKishuSagyo[j].nSagyoshaId == nSagyoshaId) {
					int nYosoSagyogunId = aYosoSagyogunId.GetSize();
					for (k = 0; k < nYosoSagyogunId; k++) {
						if (i == aYosoSagyogunId[k])
							break;
					}
					if (k == nYosoSagyogunId)
						aYosoSagyogunId.Add(i);
				}
			}
		}
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CDataManager::SetSeisanDaisu
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		生産台数設定
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		SSeisanDaisu	&cSeisanDaisu		[I] 生産台数情報
//		BOOL			bCopy				[I] コピーフラグ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		生産台数をデータ管理エリアに設定する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CDataManager::SetSeisanDaisu(SSeisanDaisu &cSeisanDaisu, BOOL bCopy)
{
	// 生産台数情報エリアにコピー
	if (bCopy) {
		m_aSeisanDaisu.RemoveAll();
		SSeisanDaisu *pSeisanDaisu = m_aSeisanDaisu.AddElement(cSeisanDaisu.sYexxxonth);
		pSeisanDaisu->sYexxxonth = cSeisanDaisu.sYexxxonth;
		pSeisanDaisu->aSeisanDaisu.Copy(cSeisanDaisu.aSeisanDaisu);
	}

	// 年月日を設定
	m_nYear = atoi(cSeisanDaisu.sYexxxonth.Left(4));
	m_nMonth = atoi(cSeisanDaisu.sYexxxonth.Mid(4));

	// 機種別生産台数を設定
	int nKishuNum = m_aKishu.GetSize();
	for (int nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
		SKishu &cKishu = m_aKishu[nKishuId];
		cKishu.nSeisanDaisu = cSeisanDaisu.aSeisanDaisu[nKishuId];;
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CDataManager::GetTotalSeisanDaisu
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		全機種の合計生産台数取得
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		int		nSublineId			[I] サブラインID
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		int		全機種の合計生産台数
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		機種データから全ての生産台数の合計を取得する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//		2007.02.11 Y.Itabashi (xxxxx) サブラインごとに総生産台数を算出
//*****************************************************************************************************
// Modify ... ( CHANGE )
int CDataManager::GetTotalSeisanDaisu( int nSublineId )
//int CDataManager::GetTotalSeisanDaisu()
{
	int nDaisuTotal = 0;
	int nSize = m_aKishu.GetSize();

	if( nSublineId >= 0 ){
		SSubline &cSubline = m_aSubline[nSublineId];
		for (int i = 0; i < nSize; i++){
			if( cSubline.aSeisanKishuFlag[i] == 1 )
				nDaisuTotal += m_aKishu[i].nSeisanDaisu;
		}
	}else{
		for (int i = 0; i < nSize; i++)
			nDaisuTotal += m_aKishu[i].nSeisanDaisu;
	}

	return nDaisuTotal;
/*	int nDaisuTotal = 0;
	int nSize = m_aKishu.GetSize();
	for (int i = 0; i < nSize; i++)
		nDaisuTotal += m_aKishu[i].nSeisanDaisu;

	return nDaisuTotal;*/
}
// By Y.Itabashi (xxxxx) 2007.02.11

//*****************************************************************************************************
//  1. 関数名
//		CDataManager::GetYosoSagyogun
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		要素作業群取得
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		int		nSublineId				[I] サブラインID
//		CString	&sKoteiName				[I] 工程名
//		CString	&sYosoSagyogunName		[I] 要素作業群名
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		SYosoSagyogun*	要素作業群データ
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		指定された工程名、要素作業群名の要素作業群データを検索し、そのポインタを返す。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
SYosoSagyogun *CDataManager::GetYosoSagyogun(int nSublineId, const CString &sKoteiName, const CString &sYosoSagyogunName)
{
	SSubline &cSubline = m_aSubline[nSublineId];
	CYosoSagyogunArray &aYosoSagyogun = cSubline.aYosoSagyogun;
	int nSize = aYosoSagyogun.GetSize();

	// 指定された工程名、要素作業群名の要素作業データを検索
	for (int i = 0; i < nSize; i++) {
		SYosoSagyogun &cYosoSagyogun = aYosoSagyogun[i];
		if (!cYosoSagyogun.CheckDeleted()) {
			if (cYosoSagyogun.sKoteiName == sKoteiName && cYosoSagyogun.sYosoSagyogunName == sYosoSagyogunName)
				return &cYosoSagyogun;
		}
	}

	// 見つからなかった場合
	return NULL;
}

//*****************************************************************************************************
//  1. 関数名
//		CDataManager::GetKotei
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		工程データ取得
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		int		nSublineId			[I] サブラインID
//		CString	&sKoteiName			[I] 工程名
//		SKotei	&cKotei				[O] 工程データ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		指定された工程名の要素作業群を抽出する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CDataManager::GetKotei(int nSublineId, const CString &sKoteiName, SKotei &cKotei)
{
	SSubline &cSubline = m_aSubline[nSublineId];
	CYosoSagyogunArray &aYosoSagyogun = cSubline.aYosoSagyogun;

	// 指定された工程名の要素作業群を検索
	int nYosoSagyogun = aYosoSagyogun.GetSize();
	for (int i = 0; i < nYosoSagyogun; i++) {
		SYosoSagyogun &cYosoSagyogun = aYosoSagyogun[i];
		if (!cYosoSagyogun.CheckDeleted()) {
			if (sKoteiName == cYosoSagyogun.sKoteiName) {
				if (cKotei.sKoteiName.IsEmpty()) {
					cKotei.sKoteiName = cYosoSagyogun.sKoteiName;
					cKotei.fTime = 0;
				}
				cKotei.fTime += cYosoSagyogun.GetTime();
				cKotei.aYosoSagyogunId.Add(i);
			}
		}
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CDataManager::GetKoteiArray
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		指定された機種の全工程取得
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		int			nSublineId			[I] サブラインID
//		int			nKishuId			[I] 機種ID
//		CKoteiArray	&cKoteiArray		[O] 工程配列データ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		指定された機種IDの工程を抽出する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CDataManager::GetKoteiArray(int nSublineId, int nKishuId, CKoteiArray &cKoteiArray)
{
	SSubline &cSubline = m_aSubline[nSublineId];
	CYosoSagyogunArray &aYosoSagyogun = cSubline.aYosoSagyogun;
	SKotei *pKotei = NULL;

	cKoteiArray.RemoveAll();

	// 指定された機種の作業が存在する要素作業群を検索
	CSagyojunArray aSagyojun;
	GetSagyojun(nSublineId, nKishuId, aSagyojun);
	int nSagyojun = aSagyojun.GetSize();
	for (int i = 0; i < nSagyojun; i++) {
		int nYosoSagyogunId = aSagyojun[i].nYosoSagyogunId;
		SYosoSagyogun &cYosoSagyogun = aYosoSagyogun[nYosoSagyogunId];
		if (!cYosoSagyogun.CheckDeleted()) {
			if (pKotei == NULL || pKotei->sKoteiName != cYosoSagyogun.sKoteiName) {
				pKotei = cKoteiArray.AddElement();
				pKotei->sKoteiName = cYosoSagyogun.sKoteiName;
				pKotei->fTime = 0;
			}
			pKotei->fTime += cYosoSagyogun.GetTime();
			pKotei->aYosoSagyogunId.Add(nYosoSagyogunId);
		}
	}
}

void CDataManager::GetKittingKoteiArray(int nSublineId, CKoteiArray &cKoteiArray)
{
	SSubline &cSubline = m_aSubline[nSublineId];
	CYosoSagyogunArray &aYosoSagyogun = cSubline.aYosoSagyogunKit;
	SKotei *pKotei = NULL;

	cKoteiArray.RemoveAll();

	// 指定された機種の作業が存在する要素作業群を検索
	int nYosoSagyogunNum = aYosoSagyogun.GetSize();
	for (int nYosoSagyogunId = 0; nYosoSagyogunId < nYosoSagyogunNum; nYosoSagyogunId++) {
		SYosoSagyogun &cYosoSagyogun = aYosoSagyogun[nYosoSagyogunId];
		if (!cYosoSagyogun.CheckDeleted()) {
			if (pKotei == NULL || pKotei->sKoteiName != cYosoSagyogun.sKoteiName) {
				pKotei = cKoteiArray.AddElement();
				pKotei->sKoteiName = cYosoSagyogun.sKoteiName;
				pKotei->fTime = 0;
			}
			pKotei->fTime += cYosoSagyogun.GetTime();
			pKotei->aYosoSagyogunId.Add(nYosoSagyogunId);
		}
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CDataManager::OrderBySagyojun
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		作業順に要素作業群を並び替え
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		int				nSublineId			[I] サブラインID
//		int				nKishuId			[I] 機種ID
//		CSagyojunArray	&aSagyojun			[I] 作業順配列データ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		指定された工程順に要素作業群データの作業順を設定する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CDataManager::OrderBySagyojun(int nSublineId, CSagyojunArray &aSagyojun)
{
	OrderBySagyojunSub(m_aSubline[nSublineId].aYosoSagyogun, aSagyojun);
}

void CDataManager::OrderByKittingSagyojun(int nSublineId, CSagyojunArray &aSagyojun)
{
	OrderBySagyojunSub(m_aSubline[nSublineId].aYosoSagyogunKit, aSagyojun);
}

void CDataManager::OrderBySagyojunSub(CYosoSagyogunArray &aYosoSagyogun, CSagyojunArray &aSagyojun)
{
	int nSagyojun = 1;

	int nSagyojunNum = aSagyojun.GetSize();
	for (int i = 0; i < nSagyojunNum; i++) {
		SSagyojun &cSagyojun = aSagyojun[i];
		aYosoSagyogun[cSagyojun.nYosoSagyogunId].aKishuSagyo[cSagyojun.nKishuId].nSagyojun = nSagyojun++;
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CDataManager::CheckMokuhyoTactJoukagen
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		目標タクト上下限チェック
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		int			nSublineId			[I] サブラインID
//		int			nSagyoshaId			[I] 作業者ID
//		int			nKishuId			[I] 機種ID
//		CString		&sErrMsg			[O] エラーメッセージ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL		TRUE：上限を超えていない，FALSE：上限を超えている
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		指定された作業者が目標タクトの上限を超えているかチェックする。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//		初期の仕様では下限もチェックしていたが、現仕様は上限のみチェック。
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
// Modify ... ( CHANGE )
BOOL CDataManager::CheckMokuhyoTactJoukagen(int nSublineId, int nSagyoshaId, int nKishuId, int nKishuType)
//BOOL CDataManager::CheckMokuhyoTactJoukagen(int nSublineId, int nSagyoshaId, int nKishuId)
// By Y.Itabashi (xxxxx) 2007.02.12
{
	SSubline &cSubline = m_aSubline[nSublineId];
	CYosoSagyogunArray &aYosoSagyogun = cSubline.aYosoSagyogun;
	CStringArray &aSagyosha = cSubline.aSagyosha;
	//del kuwama CManualEditFrameWnd *pWnd = (CManualEditFrameWnd *)(AfxGetMainWnd()->GetActiveWindow());
	//del kuwama int nKishuType = pWnd->GetSelectedKishu();

	// 作業時間を計算
	double fTime = 0;
	CIntArrayEx aYosoSagyogunId;
	FindSagyoWariate(nSublineId, nSagyoshaId, nKishuId, aYosoSagyogunId);
	int nSize = aYosoSagyogunId.GetSize();
	for (int i = 0; i < nSize; i++)
		fTime += aYosoSagyogun[aYosoSagyogunId[i]].GetTime();

	// 上下限取得
	double fTact = cSubline.cSeisanJoken.fTact;
	double fJogen = cSubline.cSeisanJoken.fTact * (1 + cSubline.cSeisanJoken.fJogen / 100);
	double fKagen = cSubline.cSeisanJoken.fTact * (1 - cSubline.cSeisanJoken.fKagen / 100);

	// 上限チェック
// Modify ... ( CHANGE )
	if( cSubline.cSeisanJoken.bKyoseiKudo == 1 ){	// 強制駆動
		if ( fabs( fTime - fJogen ) > 0.0000001e0 && fTime > fJogen )
			return FALSE;
	}else{											// 非強制駆動
		if ( fabs( fTime - fTact ) > 0.0000001e0 && fTime > fTact )
			return FALSE;
	}

// Modify ... ( ADD )
	// 加重平均時は目標タクトでのチェック
	//　機種毎のタクトチェックも必要なため、上記チェックも必要
	if( nKishuType == -1 ){
		int nTotalSeisanDaisu = g_pDataManager->GetTotalSeisanDaisu( nSublineId );
		int nSeisanDaisu = m_aKishu[nKishuId].nSeisanDaisu;
		fTime = 0;
		for (int i = 0; i < nSize; i++){
			fTime += aYosoSagyogun[aYosoSagyogunId[i]].GetTime() * nSeisanDaisu / nTotalSeisanDaisu;
		}

		if ( fabs( fTime - fTact ) > 0.0000001e0 && fTime > fTact )
			return FALSE;
	}
// By Y.Itabashi (xxxxx) 2007.02.11
/*	if (fTime > fJogen)
		return FALSE;*/
// By Y.Itabashi (xxxxx) 2007.02.03
	// OK
	return TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CDataManager::GetSagyoJikan
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		指定作業者、機種の作業時間を取得
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		int		nSublineId			[I] サブラインID
//		int		nSagyoshaId			[I] 作業者ID
//		int		nKishuId			[I] 機種ID
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		double		作業時間
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		指定作業者、機種の作業時間の合計を求める。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
double CDataManager::GetSagyoJikan(int nSublineId, int nSagyoshaId, int nKishuId)
{
	SSubline &cSubline = m_aSubline[nSublineId];
	CYosoSagyogunArray &aYosoSagyogun = cSubline.aYosoSagyogun;
	double fTime = 0;

	int nYosoSagyogun = aYosoSagyogun.GetSize();
	for (int i = 0; i < nYosoSagyogun; i++) {
		SYosoSagyogun &cYosoSagyogun = aYosoSagyogun[i];
		if (!cYosoSagyogun.CheckDeleted()) {
			if (cYosoSagyogun.aKishuSagyo[nKishuId].nSagyoshaId == nSagyoshaId)
				fTime += cYosoSagyogun.GetTime();
		}
	}

	return fTime;
}

//*****************************************************************************************************
//  1. 関数名
//		CDataManager::RenumberSagyoshaId
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		作業者IDリナンバ
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		int		nSublineId			[I] サブラインID
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		作業者の順番に作業者IDを振り直す。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CDataManager::RenumberSagyoshaId(int nSublineId)
{
	CIntArrayEx aSagyoshaJun;

	GetSagyoshaJun(nSublineId, aSagyoshaJun);
	UpdateSagyoshaId(nSublineId, aSagyoshaJun);
}

//*****************************************************************************************************
//  1. 関数名
//		CDataManager::GetSagyoshaJun
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		作業者の順番を取得
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		int			nSublineId			[I] サブラインID
//		CIntArrayEx	&aSagyoshaJun		[O] 作業者順
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		要素作業群の作業順に、割り付けられた作業者の順番を求める。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CDataManager::GetSagyoshaJun(int nSublineId, CIntArrayEx &aSagyoshaJun)
{
	SSubline &cSubline = m_aSubline[nSublineId];
	CYosoSagyogunArray &aYosoSagyogun = cSubline.aYosoSagyogun;
	int nKishuNum = GetKishuNum();

	aSagyoshaJun.RemoveAll();

	CSagyojunArray aSagyojun;
	SetSagyojunSub(nSublineId);
	GetSagyojun(nSublineId, -1, aSagyojun);

	int nSize = aSagyojun.GetSize();
	for (int i = 0; i < nSize; i++) {
		SSagyojun &cSagyojun = aSagyojun[i];
		SYosoSagyogun &cYosoSagyogun = aYosoSagyogun[aSagyojun[i].nYosoSagyogunId];
		if (!cYosoSagyogun.CheckDeleted()) {
			int nSagyoshaId = cYosoSagyogun.aKishuSagyo[cSagyojun.nKishuId].nSagyoshaId;
			if (nSagyoshaId != -1)
				aSagyoshaJun.AddUnique(nSagyoshaId);
		}
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CDataManager::UpdateSagyoshaId
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		作業者の順番を取得
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		int			nSublineId			[I] サブラインID
//		CIntArrayEx	&aSagyoshaJun		[I] 作業者順
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		指定された作業者順に作業者IDを振り直す。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CDataManager::UpdateSagyoshaId(int nSublineId, CIntArrayEx &aSagyoshaJun)
{
	SSubline &cSubline = m_aSubline[nSublineId];
	CYosoSagyogunArray &aYosoSagyogun = cSubline.aYosoSagyogun;
	CStringArray &aSagyosha = cSubline.aSagyosha;
	int i, j;
	int nSagyoshaNum = aSagyosha.GetSize();
	int *pSagyoshaId = new int[nSagyoshaNum];

	CStringArray aSagyosha2;
	int nSagyoshaJun = aSagyoshaJun.GetSize();
	aSagyosha2.SetSize(nSagyoshaJun);
	for (i = 0; i < nSagyoshaJun; i++) {
		pSagyoshaId[aSagyoshaJun[i]] = i;
		aSagyosha2[i] = aSagyosha[aSagyoshaJun[i]];
	}

	aSagyosha.Copy(aSagyosha2);

	int nYosoSagyogun = aYosoSagyogun.GetSize();
	for (i = 0; i < nYosoSagyogun; i++) {
		SYosoSagyogun &cYosoSagyogun = aYosoSagyogun[i];
		int nKishuNum = cYosoSagyogun.aKishuSagyo.GetSize();
		for (j = 0; j < nKishuNum; j++) {
			SKishuSagyo &cKishuSagyo = cYosoSagyogun.aKishuSagyo[j];
			if (cKishuSagyo.nSagyoshaId != -1)
				cKishuSagyo.nSagyoshaId = pSagyoshaId[cKishuSagyo.nSagyoshaId];
		}
	}

	delete [] pSagyoshaId;
}

//*****************************************************************************************************
//  1. 関数名
//		CDataManager::RenameSagyoshaName
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		作業者名
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		int		nSublineId			[I] サブラインID
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		作業順に連番になるように作業者名(Dxx,Kxx)を付け直す。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CDataManager::RenameSagyoshaName(int nSublineId)
{
	int nSagyoshaNo = 0;

	if (nSublineId != -1) {
		SSubline &cSubline = m_aSubline[nSublineId];
		CStringArray &aSagyosha = cSubline.aSagyosha;
		int nSagyoshaNum = aSagyosha.GetSize();
		for (int i = 0; i < nSagyoshaNum; i++)
			aSagyosha[i].Format("%s%03d", cSubline.sSagyoshaName, ++nSagyoshaNo);
	} else {
		int nSagyoshaNum = m_aKittingSagyosha.GetSize();
		for (int i = 0; i < nSagyoshaNum; i++)
			m_aKittingSagyosha[i].Format("KIT%03d", ++nSagyoshaNo);
	}
}

// デフォルトのグループ番号を設定
void CDataManager::SetDefaultGroupNo(int nSublineId)
{
	SSubline &cSubline = m_aSubline[nSublineId];

	int nKishuId;
	int nGroupNo;
	int nNewGroupNo = GetNewGroupNo(nSublineId);
	int nKishuNum = m_aKishu.GetSize();

	int nYosoSagyogunNum = cSubline.aYosoSagyogun.GetSize();
	for (int nYosoSagyogunId = 0; nYosoSagyogunId < nYosoSagyogunNum; nYosoSagyogunId++) {
		SYosoSagyogun &cYosoSagyogun = cSubline.aYosoSagyogun[nYosoSagyogunId];
		if (!cYosoSagyogun.CheckDeleted()) {
			// 既存のグループ番号取得
			nGroupNo = 0;
			for (nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
				SKishuSagyo &cKishuSagyo = cYosoSagyogun.aKishuSagyo[nKishuId];
				if (cKishuSagyo.nGroupNo != 0) {
					nGroupNo = cKishuSagyo.nGroupNo;
					break;
				}
			}

			// 割り当てられていなければ新しい番号を付与
			if (nGroupNo == 0)
				nGroupNo = nNewGroupNo++;

			// グループ番号を設定
			for (nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
				SKishuSagyo &cKishuSagyo = cYosoSagyogun.aKishuSagyo[nKishuId];
				if (cKishuSagyo.nSagyojun != 0 && cKishuSagyo.nGroupNo == 0)
					cKishuSagyo.nGroupNo = nGroupNo;
			}
		}
	}
}

// 新しいグループ番号を取得
int CDataManager::GetNewGroupNo(int nSublineId)
{
	SSubline &cSubline = m_aSubline[nSublineId];

	int nMaxGroupNo = 0;
	int nKishuNum = m_aKishu.GetSize();
	int nYosoSagyogunNum = cSubline.aYosoSagyogun.GetSize();
	for (int nYosoSagyogunId = 0; nYosoSagyogunId < nYosoSagyogunNum; nYosoSagyogunId++) {
		SYosoSagyogun &cYosoSagyogun = cSubline.aYosoSagyogun[nYosoSagyogunId];
		if (!cYosoSagyogun.CheckDeleted()) {
			for (int nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
				nMaxGroupNo = max(nMaxGroupNo, cYosoSagyogun.aKishuSagyo[nKishuId].nGroupNo);
			}
		}
	}

	return nMaxGroupNo + 1;
}

// アンダーバーとその後の数字を削除
CString CDataManager::RemoveUnderbarDigit(const CString &sName)
{
	BOOL bDigit = FALSE;
	int nLen = sName.GetLength();
	for (int i = nLen - 1; i >= 0; i--) {
		if (isdigit(sName[i]))
			bDigit = TRUE;
		else if (sName[i] == '_') {
			if (bDigit) {
				nLen = i;
				bDigit = FALSE;
			} else
				break;
		} else
			break;
	}

	return sName.Left(nLen);
}

// サブライン別生産台数取得
int CDataManager::GetSeisanDaisu(int nSublineId)
{
	int nSeisanDaisu = 0;
	int nKishuNum = m_aKishu.GetSize();
	for (int nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
		if (nSublineId == -1 || m_aSubline[nSublineId].aSeisanKishuFlag[nKishuId])
			nSeisanDaisu += m_aKishu[nKishuId].nSeisanDaisu;
	}

	return nSeisanDaisu;
}

// 共通工程フラグ設定
void CDataManager::SetKyotsuKoteiFlag()
{
	CArrayEx2 <CIntArrayEx, CIntArrayEx&> aKotei;

	int nKishuNum = m_aKishu.GetSize();

	int nSublineNum = m_aSubline.GetSize();
	for (int nSublineId = 0; nSublineId < nSublineNum; nSublineId++) {
		SSubline &cSubline = m_aSubline[nSublineId];

		// 全工程を取得
		aKotei.RemoveAll();
		int nYosoSagyogunNum = cSubline.aYosoSagyogun.GetSize();
		for (int nYosoSagyogunId = 0; nYosoSagyogunId < nYosoSagyogunNum; nYosoSagyogunId++) {
			SYosoSagyogun &cYosoSagyogun = cSubline.aYosoSagyogun[nYosoSagyogunId];
			if (!cYosoSagyogun.CheckDeleted()) {
				CIntArrayEx *pKotei = aKotei.LookupElement(cYosoSagyogun.sKoteiName);
				if (pKotei == NULL)
					pKotei = aKotei.AddElement(cYosoSagyogun.sKoteiName);

				pKotei->Add(nYosoSagyogunId);

				// 共通工程フラグクリア
				for (int nKishuId = 0; nKishuId < nKishuNum; nKishuId++)
					cYosoSagyogun.aKishuSagyo[nKishuId].bKyotsuKotei = FALSE;
			}
		}

		// 工程によるループ
		int nKoteiNum = aKotei.GetSize();
		for (int nKoteiId = 0; nKoteiId < nKoteiNum; nKoteiId++) {
			CIntArrayEx &cKotei = aKotei[nKoteiId];

			// 要素作業群によるループ
			int nSize = cKotei.GetSize();
			for (int i = 0; i < nSize; i++) {
				SYosoSagyogun &cYosoSagyogun = cSubline.aYosoSagyogun[cKotei[i]];
				if (!cYosoSagyogun.CheckDeleted()) {
					// 機種によるループ
					for (int nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
						SKishuSagyo &cKishuSagyo = cYosoSagyogun.aKishuSagyo[nKishuId];

						if (cKishuSagyo.nGroupNo != 0) {
							// 他機種の要素作業群によるループ
							for (int j = 0; j < nSize; j++) {
								SYosoSagyogun &cYosoSagyogun2 = cSubline.aYosoSagyogun[cKotei[j]];

								// 他機種によるループ
								for (int nKishuId2 = nKishuId + 1; nKishuId2 < nKishuNum; nKishuId2++) {
									SKishuSagyo &cKishuSagyo2 = cYosoSagyogun2.aKishuSagyo[nKishuId2];
									if (cKishuSagyo.nGroupNo == cKishuSagyo2.nGroupNo) {
										cKishuSagyo.bKyotsuKotei = TRUE;
										cKishuSagyo2.bKyotsuKotei = TRUE;
									}
								}
							}
						}
					}
				}
			}
		}
	}
}

// 工程単位の共通工程グループ番号取得
void CDataManager::GetKyotsuKoteiGroup(int nSublineId, CKyotsuKoteiGroupArray &aKyotsuKoteiGroup)
{
	SSubline &cSubline = m_aSubline[nSublineId];
	int nKishuId, nKishuId2;

	aKyotsuKoteiGroup.RemoveAll();

	// 工程・機種ごとにグループ番号を取得
	int nKishuNum = m_aKishu.GetSize();
	int nYosoSagyogunNum = cSubline.aYosoSagyogun.GetSize();
	for (int nYosoSagyogunId = 0; nYosoSagyogunId < nYosoSagyogunNum; nYosoSagyogunId++) {
		SYosoSagyogun &cYosoSagyogun = cSubline.aYosoSagyogun[nYosoSagyogunId];
		if (!cYosoSagyogun.CheckDeleted()) {
			SKyotsuKoteiGroup *pKyotsuKoteiGroup = aKyotsuKoteiGroup.LookupElement(cYosoSagyogun.sKoteiName);
			if (pKyotsuKoteiGroup == NULL) {
				pKyotsuKoteiGroup = aKyotsuKoteiGroup.AddElement(cYosoSagyogun.sKoteiName);
				pKyotsuKoteiGroup->sKoteiName = cYosoSagyogun.sKoteiName;
				pKyotsuKoteiGroup->aKishuGroupNo.SetSize(nKishuNum);
			}

			for (nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
				SKishuSagyo &cKishuSagyo = cYosoSagyogun.aKishuSagyo[nKishuId];

				if (cKishuSagyo.nGroupNo != 0)
					pKyotsuKoteiGroup->aKishuGroupNo[nKishuId].aGroupNo.Add(cKishuSagyo.nGroupNo);
			}
		}
	}

	// 同一グループ番号を含む工程に同じ番号を与える
	int nKoteiGroupNo = 0;
	int nSize = aKyotsuKoteiGroup.GetSize();
	for (int i = 0; i < nSize; i++) {
		SKyotsuKoteiGroup &cKyotsuKoteiGroup = aKyotsuKoteiGroup[i];
		for (nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
			SKyotsuKoteiGroup::SKishuGroupNo &cKishuGroupNo = cKyotsuKoteiGroup.aKishuGroupNo[nKishuId];
			if (cKishuGroupNo.nKoteiGroupNo == 0) {
				int nSize2 = cKishuGroupNo.aGroupNo.GetSize();
				if (nSize2 != 0) {
					cKishuGroupNo.nKoteiGroupNo = ++nKoteiGroupNo;
					for (int j = 0; j < nSize2; j++) {
						for (nKishuId2 = nKishuId + 1; nKishuId2 < nKishuNum; nKishuId2++) {
							SKyotsuKoteiGroup::SKishuGroupNo &cKishuGroupNo2 = cKyotsuKoteiGroup.aKishuGroupNo[nKishuId2];
							if (cKishuGroupNo2.aGroupNo.Find(cKishuGroupNo.aGroupNo[j]) != -1)
								cKishuGroupNo2.nKoteiGroupNo = nKoteiGroupNo;
						}
					}
				}
			}
		}
	}
}

// 最小の目標タクトのサブラインを取得
int CDataManager::GetMinTactSubline()
{
	// キット化されているサブラインの中で最小のタクトを取得
	double fTact = 0;
	int nMinSublineId = -1;
	int nSublineNum = m_aSubline.GetSize();
	for (int nSublineId = 0; nSublineId < nSublineNum; nSublineId++) {
		SSubline &cSubline = m_aSubline[nSublineId];

		if (cSubline.bSetKitting) {
			if (fTact == 0 || cSubline.cSeisanJoken.fTact < fTact) {
				fTact = cSubline.cSeisanJoken.fTact;
				nMinSublineId = nSublineId;
			}
		}
	}

	return nMinSublineId;
}

// キット化作業の目標タクトを取得
double CDataManager::GetKittingTact()
{
	int nSublineId = GetMinTactSubline();
	if (nSublineId != -1)
		return m_aSubline[nSublineId].cSeisanJoken.fTact;

	return 0;
}

void CDataManager::SetSagyojun()
{
	// サブライン別データ作成
	int nSublineNum = GetSublineNum();
	for (int nSublineId = 0; nSublineId < nSublineNum; nSublineId++)
		SetSagyojunSub(nSublineId);
}

// 共通工程が連続するように作業順を設定する
void CDataManager::SetSagyojunSub(int nSublineId)
{
	SSubline *pSubline = GetSublineData(nSublineId);
	CYosoSagyogunArray &aYosoSagyogun = pSubline->aYosoSagyogun;
	CSagyojunArray aSagyojun;
	CYosoSagyogunJunbanArray aYosoSagyogunJunban;
	int i, j;
// --Add 2007/02/28 aizawa 不懸D-189-1
	int nInsertIndex;
// --Add

	int nKishuNum = GetKishuNum();
	for (int nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
		GetSagyojun(nSublineId, nKishuId, aSagyojun);
		int nSize = aSagyojun.GetSize();

// --Add 2007/02/26 aizawa 不懸D-194
		int nSagyoshaId2 = -1;
// --Add
		int nIndex = 0;
		for (i = 0; i < aYosoSagyogunJunban.GetSize(); i++) {
			SYosoSagyogunJunban &cYosoSagyogunJunban = aYosoSagyogunJunban[i];
			int nGroupNo = cYosoSagyogunJunban.nGroupNo;
			int nSagyoshaId = cYosoSagyogunJunban.nSagyoshaId;
			CString sKoteiName = cYosoSagyogunJunban.sKoteiName;
			BOOL bKyotsuKotei = cYosoSagyogunJunban.bKyotsuKotei;

// --Add 2007/02/28 aizawa 不懸D-189-1
			if (nSagyoshaId2 != nSagyoshaId)
				nInsertIndex = i;
// --Add

			if (nGroupNo != 0) {
				BOOL bInsertKyotsuKotei = FALSE;
// --Add 2007/02/28 aizawa 不懸D-189-1
				BOOL bExistKyotsuKotei = FALSE;
// --Add
				for (j = 0; j < nSize; j++) {
					SSagyojun &cSagyojun = aSagyojun[j];
					SKishuSagyo &cKishuSagyo = aYosoSagyogun[cSagyojun.nYosoSagyogunId].aKishuSagyo[nKishuId];

// --Add 2007/02/28 aizawa 不懸D-189-1
					int nSize2 = aYosoSagyogunJunban.GetSize();
					for (int k = i; k < nSize2; k++) {
						if (aYosoSagyogunJunban[k].nSagyoshaId != nSagyoshaId)
							break;

						if (aYosoSagyogunJunban[k].nGroupNo == cKishuSagyo.nGroupNo) {
							bExistKyotsuKotei = TRUE;
							break;
						}
					}
// --Add

					if (cKishuSagyo.nGroupNo == nGroupNo ||
// --Change 2007/02/28 aizawa 不懸D-189-1
//							(!bKyotsuKotei && !bInsertKyotsuKotei && aYosoSagyogun[cSagyojun.nYosoSagyogunId].sKoteiName == sKoteiName) ||
							(!bKyotsuKotei && !bInsertKyotsuKotei && !bExistKyotsuKotei && cKishuSagyo.nSagyoshaId == nSagyoshaId && aYosoSagyogun[cSagyojun.nYosoSagyogunId].sKoteiName == sKoteiName) ||
// --Change
// --Add 2007/02/26 aizawa 不懸D-194
// --Change 2007/02/27 aizawa 不懸D-194 （作業者が割り当てられていない場合に対応）
//							(nSagyoshaId2 != nSagyoshaId && cKishuSagyo.nSagyoshaId == nSagyoshaId2)) {
							(nSagyoshaId2 != -1 && nSagyoshaId2 != nSagyoshaId && cKishuSagyo.nSagyoshaId == nSagyoshaId2)) {
// --Change
// --Add
						for ( ; nIndex <= j; nIndex++) {
							SYosoSagyogun &cYosoSagyogun = aYosoSagyogun[aSagyojun[nIndex].nYosoSagyogunId];
							SYosoSagyogunJunban cYosoSagyogunJunban2;
							cYosoSagyogunJunban2.nYosoSagyogunId = aSagyojun[nIndex].nYosoSagyogunId;
							cYosoSagyogunJunban2.nKishuId = nKishuId;
							cYosoSagyogunJunban2.nGroupNo = cYosoSagyogun.aKishuSagyo[nKishuId].nGroupNo;
							cYosoSagyogunJunban2.nSagyoshaId = cYosoSagyogun.aKishuSagyo[nKishuId].nSagyoshaId;
							cYosoSagyogunJunban2.sKoteiName = cYosoSagyogun.sKoteiName;
							cYosoSagyogunJunban2.bKyotsuKotei = cYosoSagyogun.aKishuSagyo[nKishuId].bKyotsuKotei;
// --Change 2007/02/28 aizawa 不懸D-189-1
//							aYosoSagyogunJunban.InsertAt(i, cYosoSagyogunJunban2);
							if (cYosoSagyogun.aKishuSagyo[nKishuId].nSagyoshaId == nSagyoshaId)
								aYosoSagyogunJunban.InsertAt(i, cYosoSagyogunJunban2);
							else
								aYosoSagyogunJunban.InsertAt(nInsertIndex++, cYosoSagyogunJunban2);
// --Change
							i++;
						}
						if (cKishuSagyo.nGroupNo == nGroupNo)
							bInsertKyotsuKotei = TRUE;

// --Add 2007/02/28 aizawa 不懸D-189-1
						bExistKyotsuKotei = FALSE;
// --Add
					}
				}
			}
// --Add 2007/02/26 aizawa 不懸D-194
			nSagyoshaId2 = nSagyoshaId;
// --Add
		}

		for ( ; nIndex < nSize; nIndex++) {
			SYosoSagyogun &cYosoSagyogun = aYosoSagyogun[aSagyojun[nIndex].nYosoSagyogunId];
			SYosoSagyogunJunban cYosoSagyogunJunban2;
			cYosoSagyogunJunban2.nYosoSagyogunId = aSagyojun[nIndex].nYosoSagyogunId;
			cYosoSagyogunJunban2.nKishuId = nKishuId;
			cYosoSagyogunJunban2.nGroupNo = cYosoSagyogun.aKishuSagyo[nKishuId].nGroupNo;
			cYosoSagyogunJunban2.nSagyoshaId = cYosoSagyogun.aKishuSagyo[nKishuId].nSagyoshaId;
			cYosoSagyogunJunban2.sKoteiName = cYosoSagyogun.sKoteiName;
			cYosoSagyogunJunban2.bKyotsuKotei = cYosoSagyogun.aKishuSagyo[nKishuId].bKyotsuKotei;
			aYosoSagyogunJunban.Add(cYosoSagyogunJunban2);
		}
	}

	int nSagyojun = 1;
	int nSize = aYosoSagyogunJunban.GetSize();
	for (i = 0; i < nSize; i++) {
		SYosoSagyogunJunban &cYosoSagyogunJunban = aYosoSagyogunJunban[i];
		aYosoSagyogun[cYosoSagyogunJunban.nYosoSagyogunId].aKishuSagyo[cYosoSagyogunJunban.nKishuId].nSagyojun = nSagyojun++;
	}
}
