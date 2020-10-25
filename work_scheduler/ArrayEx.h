#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		ArrayEx.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		CArrayEx,CArrayEx2,CIntArrayEx,CStringArrayEx クラスのインターフェイス
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************

// CArrayクラスの拡張（要素追加機能付加）
template< class TYPE, class ARG_TYPE >
class CArrayEx : public CArray < TYPE, ARG_TYPE >
{
public:
	// 要素を１つ追加してそのポインタとインデックスを返す
	TYPE *AddElement(int &nIndex)
	{
		nIndex = GetSize();
		SetSize(nIndex + 1);
		return &ElementAt(nIndex);
	}

	// 要素を１つ追加してそのポインタを返す
	TYPE *AddElement()
	{
		int nIndex;
		return AddElement(nIndex);
	}

	CArrayEx& operator=(const CArrayEx& other) {
		Copy(other);
		return *this;
	}
};

// CArrayクラスの拡張（検索機能付加）
template< class TYPE, class ARG_TYPE >
class CArrayEx2 : public CArray < TYPE, ARG_TYPE >
{
protected:
	// 検索用のCMap
	CMap <CString, LPCTSTR, int, int> m_cMap;

public:
	// 要素を１つ追加してそのポインタとインデックスを返す
	TYPE *AddElement(LPCTSTR pKey, int &nIndex)
	{
		CString sKey(pKey);
		sKey.MakeLower();
		nIndex = GetSize();
		SetSize(nIndex + 1);
		m_cMap.SetAt(sKey, nIndex);
		return &ElementAt(nIndex);
	}

	// 要素を１つ追加してそのポインタを返す
	TYPE *AddElement(LPCTSTR pKey)
	{
		int nIndex;
		return AddElement(pKey, nIndex);
	}

	// キーを検索して、見つかったらそのポインタとインデックスを返す
	TYPE *LookupElement(LPCTSTR pKey, int &nIndex)
	{
		CString sKey(pKey);
		sKey.MakeLower();
		if (m_cMap.Lookup(sKey, nIndex))
			return &ElementAt(nIndex);
		else
			return NULL;
	}

	// キーを検索して、見つかったらそのポインタを返す
	TYPE *LookupElement(LPCTSTR pKey)
	{
		int nIndex;
		return LookupElement(pKey, nIndex);
	}

	// 全ての要素を削除する
	void RemoveAll()
	{
		m_cMap.RemoveAll();
		CArray< TYPE, ARG_TYPE >::RemoveAll();
	}

// --Add 2007/03/02 aizawa 不懸D-147
	// キーを変更する
	void ChangeKey(LPCTSTR pKeyOld, LPCTSTR pKeyNew)
	{
		int nIndex;
		CString sKey(pKeyOld);
		sKey.MakeLower();
		if (m_cMap.Lookup(sKey, nIndex)) {
			m_cMap.RemoveKey(sKey);
			sKey = pKeyNew;
			sKey.MakeLower();
			m_cMap.SetAt(sKey, nIndex);
		}
	}
// --Add

	CArrayEx2& operator=(const CArrayEx2& other) {
		Copy(other);
		m_cMap.RemoveAll();
		CString sKey;
		int nIndex;
		for (POSITION pos = other.m_cMap.GetStartPosition(); pos != NULL; ) {
			other.m_cMap.GetNextAssoc(pos, sKey, nIndex);
			m_cMap.SetAt(sKey, nIndex);
		}
		return *this;
	}
};

// 整数配列クラスの拡張
class CIntArrayEx : public CArray < int, int >
{
public:
	void Unique();
	void Sort();
	int Find(int n);
	int AddUnique(int n);

	CIntArrayEx& operator=(const CIntArrayEx& other) {
		Copy(other);
		return *this;
	}

protected:
	static int CompareSort(const void *arg1, const void *arg2);
};

// 文字列配列クラスの拡張
class CStringArrayEx : public CStringArray
{
public:
	void Unique();
	int Find(LPCTSTR pStr);
	int AddUnique(LPCTSTR pStr);
	CString Join(LPCTSTR pDelimiter);
	void Split(LPCTSTR pDelimiter, const CString &sText);

	CStringArrayEx& operator=(const CStringArrayEx& other) {
		Copy(other);
		return *this;
	}
};
