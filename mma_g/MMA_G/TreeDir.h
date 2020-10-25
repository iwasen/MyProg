#pragma once


struct ST_TREE {
	CString	m_name;		// ����
	CString	m_fullPath;	// FULL PATH
	int	m_idx;			// �C���f�b�N�X
	struct ST_TREE *m_parent;
	CArray<struct ST_TREE *, struct ST_TREE *>m_child;
};

class CTreeDir
{
public:
	CTreeDir(void);
	~CTreeDir(void);

private:
	struct ST_TREE *m_Tree;

public:
	bool ReadDir(CStringArray &path, CTreeCtrl& treeCtrl, HTREEITEM hTree);
	// �Y��IDX��FULL PATH���擾����
	int SearchIdx(int idx, CString& path);
	ST_TREE *GetTreeByIdx(int idx, ST_TREE *parent = NULL);

private:
	bool SetDir(LPCSTR path, struct ST_TREE *parent, int level, int type, int *idx, CTreeCtrl& treeCtrl, HTREEITEM hTree);
	void DelChild(CArray<struct ST_TREE *, struct ST_TREE *>*arr);
	int GetPathByIdx(int idx, CString& path, struct ST_TREE *parent);
	BOOL CheckIdbFile(LPCTSTR pFolderPath);
};
