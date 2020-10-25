/************************************************************************
 *
 *			‚o‚b|‚u‚h‚c‚d‚n
 *
 *		–¼Ì		: ‹¤’ÊƒTƒuƒ‹[ƒ`ƒ“ˆ—
 *		ƒtƒ@ƒCƒ‹–¼	: csub.cpp
 *		ì¬ŽÒ		: s.aizawa
 *
 ************************************************************************/

#include "stdafx.h"
#include "pcvcomm.h"

static	WNDPROC	lpfnOldListProc;
static	int	listBoxItemHeight;

static	BOOL	ChangeListOrder(HWND, LONG);
static	BOOL	ChangeCursor(HWND, LONG);
static	BOOL	GetListSelSpace(HWND, LONG, LPINT);
static	int	IsItemSpace(HWND, int);

LONG	PASCAL ListBoxWndProc(HWND, UINT, WPARAM, LPARAM);

int	CompressRL(char *src, char *dst, int len)
{
	int	count;
	char	*p, *sp;

	if (len == 0)
		return 0;

	sp = dst;
	count = 0;
	while (len > 0) {
		if (count == 0) {
			p = src++;
			count++;
		} else {
			if (*src != *p) {
				if (count >= 2 || (*p & 0xf0) == 0xf0) {
					*dst++ = 0xf0 | count;
					*dst++ = *p;
				} else {
					*dst++ = *p;
				}
				count = 0;
				continue;
			} else if (count >= 15) {
				*dst++ = 0xf0 | count;
				*dst++ = *p;
				count = 0;
				continue;
			} else {
				src++;
				count++;
			}
		}
		len--;
	}

	if (count >= 2 || (*p & 0xf0) == 0xf0) {
		*dst++ = 0xf0 | count;
		*dst++ = *p;
	} else if (count == 1)
		*dst++ = *p;

	return(dst - sp);
}

int	DecompressRL(char *src, char *dst, int len)
{
	int	count;
	char	*sp;

	sp = dst;
	while (--len >= 0) {
		if ((*src & 0xf0) == 0xf0) {
			count = *src++ & 0x0f;
			while (--count >= 0)
				*dst++ = *src;
			src++;
			len--;
		} else
			*dst++ = *src++;
	}

	return(dst - sp);
}

char	*stristr(char *str1, char *str2)
{
	char	*p1, *p2;

	for ( ; *str1 != '\0'; str1++) {
		for (p1 = str1, p2 = str2; *p1 != '\0' && *p2 != '\0'; p1++, p2++) {
			if (toupper(*p1) != toupper(*p2))
				break;
		}
		if (*p2 == '\0')
			return(str1);
	}
	return(NULL);
}

void	SetDialogPositionCenter(HWND hWnd)
{
	RECT	rect;
	int	cx, cy;

	GetWindowRect(hWnd, (LPRECT)&rect);

	cx = rect.right - rect.left;
	cy = rect.bottom - rect.top;

	SetWindowPos(hWnd, 0, (GetSystemMetrics(SM_CXSCREEN) - cx) / 2,
		(GetSystemMetrics(SM_CYSCREEN) - cy) / 2, 0, 0, SWP_NOSIZE);
}

void	SetChangeListBoxItem(HWND hWnd, int itemNo)
{
	HWND	hListBox;
	TEXTMETRIC tm;
	RECT	rect;
	HDC	hDC;

	hListBox = GetDlgItem(hWnd, itemNo);

	GetClientRect(hListBox, (LPRECT)&rect);
	hDC = GetDC(hListBox);
	GetTextMetrics(hDC, (LPTEXTMETRIC)&tm);
	ReleaseDC(hListBox, hDC);

	listBoxItemHeight = tm.tmHeight;

	lpfnOldListProc = (WNDPROC)SetWindowLong(hListBox, GWL_WNDPROC, (DWORD)ListBoxWndProc);
}

LONG	CALLBACK ListBoxWndProc(HWND hWnd, UINT Message, WPARAM wParam, LPARAM lParam)
{
	switch (Message) {
	case WM_MOUSEMOVE:
		if (ChangeCursor(hWnd, lParam))
			return(NULL);
		break;
	case WM_LBUTTONDOWN:
		if (ChangeListOrder(hWnd, lParam))
			return(NULL);
		break;
	}

	return(CallWindowProc(lpfnOldListProc, hWnd, Message, wParam, lParam));
}

static	BOOL	ChangeListOrder(HWND hWnd, LONG lParam)
{
	int	itemNo;
	int	currentSel;
	char	itemList[256];

	if (!GetListSelSpace(hWnd, lParam, (LPINT)&itemNo))
		return(FALSE);

	currentSel = (int)SendMessage(hWnd, LB_GETCURSEL, NULL, (LONG)NULL);

	SendMessage(hWnd, LB_GETTEXT, currentSel, (LONG)(LPSTR)itemList);
	SendMessage(hWnd, LB_DELETESTRING, currentSel, (LONG)NULL);
	SendMessage(hWnd, LB_INSERTSTRING, itemNo, (LONG)(LPSTR)itemList);
	SendMessage(hWnd, LB_SETCURSEL, itemNo, (LONG)NULL);

	return(TRUE);
}

static	BOOL	ChangeCursor(HWND hWnd, LONG lParam)
{
	int	currentSel;

	currentSel = (int)SendMessage(hWnd, LB_GETCURSEL, NULL, (LONG)NULL);
	if (currentSel < 0)
		return(FALSE);

	if (LOWORD(lParam) >= 20)
		return(FALSE);

	if (IsItemSpace(hWnd, HIWORD(lParam)) != -1)
		SetCursor(hSpaceCursor);

	return(TRUE);
}

static	BOOL	GetListSelSpace(HWND hWnd, LONG lParam, LPINT itemNo)
{
	int	currentSel;

	currentSel = (int)SendMessage(hWnd, LB_GETCURSEL, NULL, (LONG)NULL);
	if (currentSel < 0)
		return(FALSE);

	if (LOWORD(lParam) >= 20)
		return(FALSE);

	if ((*itemNo = IsItemSpace(hWnd, HIWORD(lParam))) == -1)
		return(FALSE);

	if (*itemNo > currentSel)
		*itemNo -= 1;

	return(TRUE);
}

static	int	IsItemSpace(HWND hWnd, int y)
{
	int	topIndex;
	int	itemCount;
	int	currentSel;
	int	item;

	y += 3;
	if (y % listBoxItemHeight <= 6) {
		topIndex = (int)SendMessage(hWnd, LB_GETTOPINDEX, NULL, (LONG)NULL);
		itemCount = (int)SendMessage(hWnd, LB_GETCOUNT, NULL, (LONG)NULL);
		item = y / listBoxItemHeight;
		if (topIndex + item <= itemCount) {
			currentSel = (int)SendMessage(hWnd, LB_GETCURSEL, NULL, (LONG)NULL);
			if (topIndex + item != currentSel && topIndex + item != currentSel + 1)
				return(topIndex + item);
		}
	}
	return(-1);
}

int MessageBoxID(CWnd *pWnd, UINT nID, UINT nType)
{
	CString msg;

	msg.LoadString(nID);
	return pWnd->MessageBox(msg, title, nType);
}

CString GetString(UINT nID)
{
	CString str;

	str.LoadString(nID);
	return str;
}
