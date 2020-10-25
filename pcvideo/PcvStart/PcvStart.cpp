// PcvStart.cpp : アプリケーション用のエントリ ポイントの定義
//

#include "stdafx.h"

int APIENTRY WinMain(HINSTANCE hInstance,
                     HINSTANCE hPrevInstance,
                     LPSTR     lpCmdLine,
                     int       nCmdShow )
{
	char *p;

	Sleep(3000);

	if ((p = strstr(lpCmdLine, "/DL=")) != NULL) {
		char *pSrcFileName, *pDstFileName;

		*(p - 1) = '\0';
		pSrcFileName = p + 4;
		if ((p = strrchr(pSrcFileName, '\\')) != NULL)
			pDstFileName = p + 1;
		else
			pDstFileName = pSrcFileName;

		CopyFile(pSrcFileName, pDstFileName, FALSE);
	}

	STARTUPINFO	siStartupInfo;
	PROCESS_INFORMATION	piProcInfo;
	memset(&siStartupInfo, 0, sizeof(siStartupInfo));
	siStartupInfo.cb = sizeof(siStartupInfo);
	CreateProcess(NULL, lpCmdLine, NULL, NULL, FALSE, NORMAL_PRIORITY_CLASS, NULL, ".", &siStartupInfo, &piProcInfo);

	return 0;
}
