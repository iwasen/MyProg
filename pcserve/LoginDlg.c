#include "pcsos.h"
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include "pcsclnt.h"
#include "pcswin32res.h"

#define LEN_USERID		10
#define LEN_PASSWORD	10

extern HINSTANCE hInst;

static char iniFile[] = "pcswin.ini";

BOOL CALLBACK LoginDialogProc(HWND hwndDlg, UINT uMsg, WPARAM wParam, LPARAM lParam);
static void InitDialog(HWND hwndDlg, char *pServerName);
static BOOL LoginPcserve(HWND hwndDlg, PCCB pCCB);
static void EncodePassword(char *pPasswordIn, char *pPasswordOut);
static void DecodePassword(char *pPasswordIn, char *pPasswordOut);
static void ChangePasswordDialog(HWND hwndDlg, PCCB pCCB);
BOOL CALLBACK ChangePassowrdDialogProc(HWND hwndDlg, UINT uMsg, WPARAM wParam, LPARAM lParam);
static BOOL ChangePassword(HWND hwndDlg, PCCB pCCB);

BOOL LoginDialog(PCCB pCCB)
{
	if (DialogBoxParam(hInst, MAKEINTRESOURCE(IDD_LOGIN), NULL, LoginDialogProc, (LPARAM)pCCB) == IDOK)
		return TRUE;
	else
		return FALSE;
}

BOOL CALLBACK LoginDialogProc(HWND hwndDlg, UINT uMsg, WPARAM wParam, LPARAM lParam)
{
	static PCCB pCCB;

	switch (uMsg) {
	case WM_INITDIALOG:
		pCCB = (PCCB)lParam;
		InitDialog(hwndDlg, pCCB->serverName);
		return FALSE;
	case WM_COMMAND:
		switch (wParam) {
		case IDOK:
			if (LoginPcserve(hwndDlg, pCCB))
				EndDialog(hwndDlg, IDOK);
			return TRUE;
		case IDCANCEL:
			EndDialog(hwndDlg, IDCANCEL);
			return TRUE;
		case IDC_CHANGE_PASSWORD:
			ChangePasswordDialog(hwndDlg, pCCB);
			return TRUE;
		}
		break;
	}

	return FALSE;
}

static void InitDialog(HWND hwndDlg, char *pServerName)
{
	char savePassword[2];
	char userID[LEN_USERID + 1];
	char password[LEN_PASSWORD + 1];
	char password2[(LEN_PASSWORD + 1) * 2 + 1];

	SetDlgItemText(hwndDlg, IDC_SERVER_NAME, pServerName);
	SendDlgItemMessage(hwndDlg, IDC_USERID, EM_LIMITTEXT, LEN_USERID, 0L);
	SendDlgItemMessage(hwndDlg, IDC_PASSWORD, EM_LIMITTEXT, LEN_PASSWORD, 0L);

	GetPrivateProfileString(pServerName, "SavePassword", "N", savePassword, sizeof(savePassword), iniFile);
	GetPrivateProfileString(pServerName, "UserID", "", userID, sizeof(userID), iniFile);
	if (savePassword[0] == 'Y') {
		GetPrivateProfileString(pServerName, "Password", "", password2, sizeof(password2), iniFile);
		if (password2[0] != '\0')
			DecodePassword(password2, password);
		SendDlgItemMessage(hwndDlg, IDC_SAVE_PASSWORD, BM_SETCHECK, 1, 0);
	} else
		password[0] = '\0';

	if (userID[0] == '\0') {
		GetPrivateProfileString("CurrentSettings", "UserID", "", userID, sizeof(userID), iniFile);
		password[0] = '\0';
	}

	SetDlgItemText(hwndDlg, IDC_USERID, userID);
	SetDlgItemText(hwndDlg, IDC_PASSWORD, password);

	if (userID[0] == '\0')
		SetFocus(GetDlgItem(hwndDlg, IDC_USERID));
	else if (password[0] == '\0')
		SetFocus(GetDlgItem(hwndDlg, IDC_PASSWORD));
	else
		SetFocus(GetDlgItem(hwndDlg, IDOK));
}

static BOOL LoginPcserve(HWND hwndDlg, PCCB pCCB)
{
	char userID[LEN_USERID + 1];
	char password[LEN_PASSWORD + 1];
	char password2[(LEN_PASSWORD + 1) * 2 + 1];

	GetDlgItemText(hwndDlg, IDC_USERID, userID, sizeof(userID));
	GetDlgItemText(hwndDlg, IDC_PASSWORD, password, sizeof(password));

	pCCB->errorCode = PSLogin(pCCB, userID, password);
	if (pCCB->errorCode == ERROR_USER_PASSWORD) {
		MessageBox(NULL, "PCSERVE のログインに失敗しました。", "PCSERVE", MB_OK | MB_ICONEXCLAMATION);
		return FALSE;
	} else if (pCCB->errorCode != 0)
		return TRUE;

	WritePrivateProfileString("CurrentSettings", "UserID", userID, iniFile);
	WritePrivateProfileString(pCCB->serverName, "UserID", userID, iniFile);

	EncodePassword(password, password2);
	WritePrivateProfileString(pCCB->serverName, "Password", password2, iniFile);

	if (SendDlgItemMessage(hwndDlg, IDC_SAVE_PASSWORD, BM_GETCHECK, 0, 0) != 0)
		WritePrivateProfileString(pCCB->serverName, "SavePassword", "Y", iniFile);
	else
		WritePrivateProfileString(pCCB->serverName, "SavePassword", "N", iniFile);

	return TRUE;
}

static void EncodePassword(char *pPasswordIn, char *pPasswordOut)
{
	int i;

	srand(123);

	for (i = 0; i < LEN_PASSWORD + 1; i++) {
		*pPasswordOut++ = (pPasswordIn[i] >> 4) + 0x40 + (rand() % 10);
		*pPasswordOut++ = (pPasswordIn[i] & 0x0f) + 0x40 + (rand() % 10);
	}

	*pPasswordOut = '\0';
}

static void DecodePassword(char *pPasswordIn, char *pPasswordOut)
{
	int c1, c2, i;

	srand(123);

	for (i = 0; i < LEN_PASSWORD + 1; i++) {
		c1 = *pPasswordIn++;
		c1 = (c1 - 0x40 - (rand() % 10)) << 4;
		c2 = *pPasswordIn++;
		c2 = (c2 - 0x40 - (rand() % 10));
		*pPasswordOut++ = c1 | c2;
	}

	*pPasswordOut = '\0';
}

static void ChangePasswordDialog(HWND hwndDlg, PCCB pCCB)
{
	char userID[LEN_USERID + 1];
	char password[LEN_PASSWORD + 1];

	GetDlgItemText(hwndDlg, IDC_USERID, userID, sizeof(userID));
	GetDlgItemText(hwndDlg, IDC_PASSWORD, password, sizeof(password));

	if (PSGWCheckUser(pCCB, userID, password) == 0) {
		MessageBox(hwndDlg, "ﾕｰｻﾞIDまたはﾊﾟｽﾜｰﾄﾞが違います。正しいﾕｰｻﾞIDとﾊﾟｽﾜｰﾄﾞを入力してください。", "PCSERVE", MB_OK | MB_ICONEXCLAMATION);
		return;
	}

	DialogBoxParam(hInst, MAKEINTRESOURCE(IDD_CHANGE_PASSWORD), hwndDlg, ChangePassowrdDialogProc, (LPARAM)pCCB);
}

BOOL CALLBACK ChangePassowrdDialogProc(HWND hwndDlg, UINT uMsg, WPARAM wParam, LPARAM lParam)
{
	static PCCB pCCB;

	switch (uMsg) {
	case WM_INITDIALOG:
		pCCB = (PCCB)lParam;
		SendDlgItemMessage(hwndDlg, IDC_NEW_PASSWORD, EM_LIMITTEXT, LEN_PASSWORD, 0L);
		SendDlgItemMessage(hwndDlg, IDC_VERIFY, EM_LIMITTEXT, LEN_PASSWORD, 0L);
		return TRUE;
	case WM_COMMAND:
		switch (wParam) {
		case IDOK:
			if (ChangePassword(hwndDlg, pCCB))
				EndDialog(hwndDlg, IDOK);
			return TRUE;
		case IDCANCEL:
			EndDialog(hwndDlg, IDCANCEL);
			return TRUE;
		}
		break;
	}

	return FALSE;
}

static BOOL ChangePassword(HWND hwndDlg, PCCB pCCB)
{
	char userID[LEN_USERID + 1];
	char oldPassword[LEN_PASSWORD + 1];
	char newPassword[LEN_PASSWORD + 1];
	char verify[LEN_PASSWORD + 1];
	HWND hwndParent;

	GetDlgItemText(hwndDlg, IDC_NEW_PASSWORD, newPassword, sizeof(newPassword));
	GetDlgItemText(hwndDlg, IDC_VERIFY, verify, sizeof(verify));

	if (strcmp(newPassword, verify) != 0) {
		MessageBox(hwndDlg, "新ﾊﾟｽﾜｰﾄﾞが確認入力と一致していません。", "PCSERVE", MB_OK | MB_ICONEXCLAMATION);
		return FALSE;
	}

	hwndParent = GetParent(hwndDlg);
	GetDlgItemText(hwndParent, IDC_USERID, userID, sizeof(userID));
	GetDlgItemText(hwndParent, IDC_PASSWORD, oldPassword, sizeof(oldPassword));

	if (PSGWSetPassword(pCCB, userID, oldPassword, newPassword) == 0) {
		MessageBox(hwndDlg, "ﾊﾟｽﾜｰﾄﾞの変更はできませんでした。", "PCSERVE", MB_OK | MB_ICONEXCLAMATION);
		return FALSE;
	}

	SetDlgItemText(hwndParent, IDC_PASSWORD, newPassword);
	MessageBox(hwndDlg, "ﾊﾟｽﾜｰﾄﾞの変更は正常に行われました。", "PCSERVE", MB_OK | MB_ICONINFORMATION);

	return TRUE;
}
