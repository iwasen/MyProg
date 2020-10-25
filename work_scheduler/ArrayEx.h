#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		ArrayEx.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		CArrayEx,CArrayEx2,CIntArrayEx,CStringArrayEx �N���X�̃C���^�[�t�F�C�X
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************

// CArray�N���X�̊g���i�v�f�ǉ��@�\�t���j
template< class TYPE, class ARG_TYPE >
class CArrayEx : public CArray < TYPE, ARG_TYPE >
{
public:
	// �v�f���P�ǉ����Ă��̃|�C���^�ƃC���f�b�N�X��Ԃ�
	TYPE *AddElement(int &nIndex)
	{
		nIndex = GetSize();
		SetSize(nIndex + 1);
		return &ElementAt(nIndex);
	}

	// �v�f���P�ǉ����Ă��̃|�C���^��Ԃ�
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

// CArray�N���X�̊g���i�����@�\�t���j
template< class TYPE, class ARG_TYPE >
class CArrayEx2 : public CArray < TYPE, ARG_TYPE >
{
protected:
	// �����p��CMap
	CMap <CString, LPCTSTR, int, int> m_cMap;

public:
	// �v�f���P�ǉ����Ă��̃|�C���^�ƃC���f�b�N�X��Ԃ�
	TYPE *AddElement(LPCTSTR pKey, int &nIndex)
	{
		CString sKey(pKey);
		sKey.MakeLower();
		nIndex = GetSize();
		SetSize(nIndex + 1);
		m_cMap.SetAt(sKey, nIndex);
		return &ElementAt(nIndex);
	}

	// �v�f���P�ǉ����Ă��̃|�C���^��Ԃ�
	TYPE *AddElement(LPCTSTR pKey)
	{
		int nIndex;
		return AddElement(pKey, nIndex);
	}

	// �L�[���������āA���������炻�̃|�C���^�ƃC���f�b�N�X��Ԃ�
	TYPE *LookupElement(LPCTSTR pKey, int &nIndex)
	{
		CString sKey(pKey);
		sKey.MakeLower();
		if (m_cMap.Lookup(sKey, nIndex))
			return &ElementAt(nIndex);
		else
			return NULL;
	}

	// �L�[���������āA���������炻�̃|�C���^��Ԃ�
	TYPE *LookupElement(LPCTSTR pKey)
	{
		int nIndex;
		return LookupElement(pKey, nIndex);
	}

	// �S�Ă̗v�f���폜����
	void RemoveAll()
	{
		m_cMap.RemoveAll();
		CArray< TYPE, ARG_TYPE >::RemoveAll();
	}

// --Add 2007/03/02 aizawa �s��D-147
	// �L�[��ύX����
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

// �����z��N���X�̊g��
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

// ������z��N���X�̊g��
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
