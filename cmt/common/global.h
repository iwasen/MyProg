extern CString GetIniData(LPCTSTR pSection, LPCTSTR pEntry, LPCTSTR pDefault);
extern int GetIniData(LPCTSTR pSection, LPCTSTR pEntry, int nDefault);
extern CString CreatePathName(LPCTSTR pDirName, LPCTSTR pFileName);
extern BOOL DeleteDirectory(LPCTSTR pDirName, BOOL bDeleteTop);
extern BOOL CopyDirectory(LPCTSTR pSrcDir, LPCTSTR pDstDir, CWnd *pWnd);
extern void NetSendMessage(LPCTSTR pComputerName, LPCTSTR pMessage);
extern DWORD ProcessWait(HANDLE hProcess);
extern int DispMessage(UINT nMsgID, int nBottumIcon, BOOL bSendMsg, ...);
