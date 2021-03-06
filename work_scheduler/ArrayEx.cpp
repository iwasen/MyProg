//*****************************************************************************************************
//  1. ファイル名
//		ArrayEx.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		CStringArrayEx, CIntArrayEx クラスのインプリメンテーション
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************

#include "stdafx.h"
#include "workscheduler.h"
#include "ArrayEx.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

//*****************************************************************************************************
//  1. 関数名
//		CStringArrayEx::Unique
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		文字列配列の重複を削除してユニーク化
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		文字列配列から重複している文字列を削除する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CStringArrayEx::Unique()
{
	CMapStringToPtr cMap;
	void *pDummy;

	for (int i = 0; i < GetSize(); ) {
		if (cMap.Lookup(ElementAt(i), pDummy))
			RemoveAt(i);
		else {
			cMap.SetAt(ElementAt(i), NULL);
			i++;
		}
	}
}

int CStringArrayEx::Find(LPCTSTR pStr)
{
	int nSize = GetSize();
	for (int i = 0; i < nSize; i++) {
		if (ElementAt(i) == pStr)
			return i;
	}

	return -1;
}

int CStringArrayEx::AddUnique(LPCTSTR pStr)
{
	int nIndex;

	if ((nIndex = Find(pStr)) == -1)
		nIndex = Add(pStr);

	return nIndex;
}

// 文字列を結合
CString CStringArrayEx::Join(LPCTSTR pDelimiter)
{
	CString sString;

	int nSize = GetSize();
	for (int i = 0; i < nSize; i++) {
		if (i != 0)
			sString += pDelimiter;
		sString += ElementAt(i);
	}

	return sString;
}

// 文字列を分割
void CStringArrayEx::Split(LPCTSTR pDelimiter, const CString &sText)
{
	int nLen = strlen(pDelimiter);
	int nPos1 = 0;
	int nPos2;

	RemoveAll();
	while (true) {
		nPos2 = sText.Find(pDelimiter, nPos1);
		if (nPos2 != -1) {
			Add(sText.Mid(nPos1, nPos2 - nPos1));
			nPos1 = nPos2 + nLen;
		} else {
			Add(sText.Mid(nPos1));
			break;
		}
	}
}

void CIntArrayEx::Unique()
{
	CMapPtrToPtr cMap;
	void *pDummy;

	for (int i = 0; i < GetSize(); ) {
		if (cMap.Lookup((void *)ElementAt(i), pDummy))
			RemoveAt(i);
		else {
			cMap.SetAt((void *)ElementAt(i), NULL);
			i++;
		}
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CIntArrayEx::Sort
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		整数配列ソート
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		整数配列の要素を昇順にソートする。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CIntArrayEx::Sort()
{
	qsort(GetData(), GetSize(), sizeof(UINT), CompareSort);
}

//*****************************************************************************************************
//  1. 関数名
//		CIntArrayEx::Find
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		整数配列検索
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		UINT		n		[I] 検索するデータ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		int			見つかった要素のインデックス（見つからなかった場合は-1）
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		整数配列から指定されたデータを検索し、見つかった要素のインデックスを返す。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
int CIntArrayEx::Find(int n)
{
	int nSize = GetSize();
	for (int i = 0; i < nSize; i++) {
		if (ElementAt(i) == n)
			return i;
	}

	return -1;
}

//*****************************************************************************************************
//  1. 関数名
//		CIntArrayEx::AddUnique
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ユニークな値を配列に追加
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		UINT		n		[I] 追加するデータ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL		TRUE：追加した
//					FALSE：既に同じデータがあったので追加しなかった
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		整数配列に指定されたデータがあるか検索し、無ければそのデータを追加する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
int CIntArrayEx::AddUnique(int n)
{
	int nIndex;

	if ((nIndex = Find(n)) == -1)
		nIndex = Add(n);

	return nIndex;
}

//*****************************************************************************************************
//  1. 関数名
//		CIntArrayEx::AddUnique
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		整数配列データ比較
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		void		*arg1		[I] 比較するデータ
//		void		*arg2		[I] 比較するもう一方のデータ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		int			比較結果
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		整数配列に指定されたデータがあるか検索し、無ければそのデータを追加する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//		qsort()からコールバックされる。
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
int CIntArrayEx::CompareSort(const void *arg1, const void *arg2)
{
	return *(int *)arg1 - *(int *)arg2;
}
