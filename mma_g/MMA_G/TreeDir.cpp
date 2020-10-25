#include "StdAfx.h"
#include "io.h"
#include "TreeDir.h"
#include "MMA_G.h"
#include "General.h"

CTreeDir::CTreeDir(void)
{
	// ���[�g�����쐬���Ă���
	m_Tree = new struct ST_TREE;
	m_Tree->m_parent = NULL;
	m_Tree->m_idx = 0;
	m_Tree->m_name = "data";
}

CTreeDir::~CTreeDir(void)
{
	// �c���[�̓��I�m�ۃG���A�����
	DelChild(&m_Tree->m_child);

	// ���[�g�������
	delete m_Tree;
}

// �w��t�H���_��ǂ�
bool CTreeDir::ReadDir(CStringArray &path, CTreeCtrl& treeCtrl, HTREEITEM hTree)
{
	int	idx = 0;

	// �c���[�̓��I�m�ۃG���A�����
	DelChild(&m_Tree->m_child);

	treeCtrl.SetItemData(hTree, 0);

	// �w��t�H���_���ɑ��݂���t�H���_�����擾����
	for (int i = 0; i < path.GetSize(); i++)
		SetDir(path[i], m_Tree, 0, 0, &idx, treeCtrl, hTree);

	return true;
}

// �w��t�H���_���ɑ��݂���t�H���_�����擾����
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

    /* �J�����g �f�B���N�g�����̍ŏ��̃t�H���_��T���܂��B*/
	if( (hFile = _findfirst(wpath, &c_file )) != -1L ) {
		do {
			// �t�H���_�H
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
									, "�ߋ��f�[�^", 0, 0, 0, 0, ID_OLD_DATA , hTreeParent, TVI_SORT);
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

// �t�H���_����idb�t�@�C�������݂��邩�`�F�b�N
BOOL CTreeDir::CheckIdbFile(LPCTSTR pFolderPath)
{
	CFileFind oFileFind;

	return oFileFind.FindFile((CString)pFolderPath + "\\*.idb");
}

// �c���[�̓��I�m�ۃG���A�����
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

// �Y��IDX��FULL PATH���擾����
int CTreeDir::SearchIdx(int idx, CString& path)
{
	return GetPathByIdx(idx, path, m_Tree);
}

// �Y��IDX��FULL PATH���擾����
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

// �Y��IDX��ST_TREE���擾����
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

