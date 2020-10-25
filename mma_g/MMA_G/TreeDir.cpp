#include "StdAfx.h"
#include "io.h"
#include "TreeDir.h"
#include "MMA_G.h"
#include "General.h"

CTreeDir::CTreeDir(void)
{
	// ルート情報を作成しておく
	m_Tree = new struct ST_TREE;
	m_Tree->m_parent = NULL;
	m_Tree->m_idx = 0;
	m_Tree->m_name = "data";
}

CTreeDir::~CTreeDir(void)
{
	// ツリーの動的確保エリアを解放
	DelChild(&m_Tree->m_child);

	// ルート情報を解放
	delete m_Tree;
}

// 指定フォルダを読む
bool CTreeDir::ReadDir(CStringArray &path, CTreeCtrl& treeCtrl, HTREEITEM hTree)
{
	int	idx = 0;

	// ツリーの動的確保エリアを解放
	DelChild(&m_Tree->m_child);

	treeCtrl.SetItemData(hTree, 0);

	// 指定フォルダ内に存在するフォルダ名を取得する
	for (int i = 0; i < path.GetSize(); i++)
		SetDir(path[i], m_Tree, 0, 0, &idx, treeCtrl, hTree);

	return true;
}

// 指定フォルダ内に存在するフォルダ名を取得する
bool CTreeDir::SetDir(LPCSTR path, struct ST_TREE *parent, int level, int type, int *idx, CTreeCtrl& treeCtrl, HTREEITEM hTreeParent)
{
	char wpath[MAX_PATH];
	struct _finddata_t c_file;
    intptr_t hFile;
	CString	str;
	CString filename;
	struct ST_TREE *st;
	HTREEITEM	hTree;
	INT_PTR	No;
	BOOL bOldData;
	HTREEITEM	hOldDataTree = NULL;

	if (level > 3)
		return false;

	memset(wpath, 0, sizeof(wpath));
	strcpy_s(wpath, path);
	strcat_s(wpath, "\\*.*");

    /* カレント ディレクトリ内の最初のフォルダを探します。*/
	if( (hFile = _findfirst(wpath, &c_file )) != -1L ) {
		do {
			// フォルダ？
			if ((c_file.attrib & _A_SUBDIR) && c_file.name[0] != '.') {
				filename = c_file.name;
				bOldData = FALSE;
				
				switch (level) {
				case 0:
					if (filename == "MME")
						type = DATA_TYPE_MME;
					else if (filename == "ASU")
						type = DATA_TYPE_ASU;
					else
						type = DATA_TYPE_MMA;
					break;
				case 1:
					if (filename.Right(4).MakeLower() == ".mai") {
						bOldData = TRUE;
						if (hOldDataTree == NULL) {
							HTREEITEM hChildItem = treeCtrl.GetChildItem(hTreeParent);

							while (hChildItem != NULL) {
								if (treeCtrl.GetItemData(hChildItem) == ID_OLD_DATA) {
									hOldDataTree = hChildItem;
									break;
								}
								hChildItem = treeCtrl.GetNextItem(hChildItem, TVGN_NEXT);
							}

							if (hOldDataTree == NULL) {
								hOldDataTree = treeCtrl.InsertItem(TVIF_HANDLE | TVIF_PARAM | TVIF_TEXT
									, "過去データ", 0, 0, 0, 0, ID_OLD_DATA , hTreeParent, TVI_SORT);
							}
						}
					}
					break;
				}

				hTree = NULL;
				if (treeCtrl.ItemHasChildren(hTreeParent)) {
					HTREEITEM hChildItem = treeCtrl.GetChildItem(hTreeParent);

					while (hChildItem != NULL) {
						CString sItemText = treeCtrl.GetItemText(hChildItem);
						if (sItemText == filename) {
							hTree = hChildItem;
							break;
						}
						hChildItem = treeCtrl.GetNextItem(hChildItem, TVGN_NEXT);
					}
				}

				str.Format("%s\\%s", path, c_file.name);
				if (hTree == NULL) {
					st = new struct ST_TREE;
					st->m_name = c_file.name;
					st->m_fullPath = str;
					st->m_parent = parent;
					st->m_idx = (CheckIdbFile(str) ? ID_DATA : ID_FOLDER) | type | *idx;
					(*idx)++;
					No = parent->m_child.Add(st);

					hTree = treeCtrl.InsertItem(TVIF_HANDLE | TVIF_PARAM | TVIF_TEXT
							, filename, 0, 0, 0, 0, st->m_idx , bOldData ? hOldDataTree : hTreeParent, TVI_SORT);
				} else {
					for (int i = 0; i < parent->m_child.GetSize(); i++) {
						if (parent->m_child[i]->m_name == c_file.name) {
							No = i;
							break;
						}
					}
				}

				SetDir((LPCSTR)str, parent->m_child[No], level + 1, type, idx, treeCtrl, hTree);
			}
		} while( _findnext( hFile, &c_file ) == 0 );
		_findclose( hFile );
	}
	return true;
}

// フォルダ内にidbファイルが存在するかチェック
BOOL CTreeDir::CheckIdbFile(LPCTSTR pFolderPath)
{
	CFileFind oFileFind;

	return oFileFind.FindFile((CString)pFolderPath + "\\*.idb");
}

// ツリーの動的確保エリアを解放
void CTreeDir::DelChild(CArray<struct ST_TREE *, struct ST_TREE *>*arr)
{
	INT_PTR	i, cnt;

	cnt = arr->GetSize();
	for (i = 0; i < cnt; i++) {
		DelChild(&arr->GetAt(i)->m_child);
		delete arr->GetAt(i);
	}
	arr->RemoveAll();
}

// 該当IDXのFULL PATHを取得する
int CTreeDir::SearchIdx(int idx, CString& path)
{
	return GetPathByIdx(idx, path, m_Tree);
}

// 該当IDXのFULL PATHを取得する
int CTreeDir::GetPathByIdx(int idx, CString& path, struct ST_TREE *parent)
{
	struct ST_TREE *my;
	INT_PTR	i, cnt;

	cnt = parent->m_child.GetSize();
	for (i = 0; i < cnt; i++) {
		my = parent->m_child.GetAt(i);
		if (my->m_child.GetSize() != 0) {
			if (GetPathByIdx(idx, path, my) != 0)
				return 1;
		}
		if (my->m_idx == idx) {
			path = my->m_fullPath;
			return 1;
		}
	}
	return 0;
}

// 該当IDXのST_TREEを取得する
ST_TREE *CTreeDir::GetTreeByIdx(int idx, ST_TREE *st)
{
	INT_PTR	i, cnt;

	if (st == NULL)
		st = m_Tree;

	if (st->m_idx == idx)
		return st;

	cnt = st->m_child.GetSize();
	for (i = 0; i < cnt; i++) {
		ST_TREE *st2 = GetTreeByIdx(idx, st->m_child[i]);
		if (st2 != NULL)
			return st2;
	}

	return NULL;
}

