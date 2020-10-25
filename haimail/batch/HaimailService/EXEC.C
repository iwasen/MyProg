// THIS CODE AND INFORMATION IS PROVIDED "AS IS" WITHOUT WARRANTY OF
// ANY KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED TO
// THE IMPLIED WARRANTIES OF MERCHANTABILITY AND/OR FITNESS FOR A
// PARTICULAR PURPOSE.
//
// Copyright (C) 1993-1997  Microsoft Corporation.  All Rights Reserved.
//
//  MODULE:   simple.c
//
//  PURPOSE:  Implements the body of the service.
//            The default behavior is to open a
//            named pipe, \\.\pipe\simple, and read
//            from it.  It the modifies the data and
//            writes it back to the pipe.
//
//  FUNCTIONS:
//            ServiceStart(DWORD dwArgc, LPTSTR *lpszArgv);
//            ServiceStop( );
//
//  COMMENTS: The functions implemented in simple.c are
//            prototyped in service.h
//              
//
//  AUTHOR: Craig Link - Microsoft Developer Support
//


#include <windows.h>
#include <stdio.h>
#include <stdlib.h>
#include <process.h>
#include <tchar.h>
#include "service.h"

// this event is signalled when the
// service should end
//
HANDLE  hServerStopEvent = NULL;

extern char	currentDir[];


//
//  FUNCTION: ServiceStart
//
//  PURPOSE: Actual code of the service
//           that does the work.
//
//  PARAMETERS:
//    dwArgc   - number of command line arguments
//    lpszArgv - array of command line arguments
//
//  RETURN VALUE:
//    none
//
//  COMMENTS:
//    The default behavior is to open a
//    named pipe, \\.\pipe\simple, and read
//    from it.  It the modifies the data and
//    writes it back to the pipe.  The service
//    stops when hServerStopEvent is signalled
//
VOID ServiceStart (DWORD dwArgc, LPTSTR *lpszArgv)
{
	char iniFile[256];
	char exeFile1[256];
	char exeFile2[256];
	char exeFile3[256];
	int nInterval;
	int nCount;
	STARTUPINFO si;
	PROCESS_INFORMATION pi;

    ///////////////////////////////////////////////////
    //
    // Service initialization
    //

    // report the status to the service control manager.
    //
    if (!ReportStatusToSCMgr(
        SERVICE_START_PENDING, // service state
        NO_ERROR,              // exit code
        3000))                 // wait hint
        goto cleanup;

    // create the event object. The control handler function signals
    // this event when it receives the "stop" control code.
    //
    hServerStopEvent = CreateEvent(
        NULL,    // no security attributes
        TRUE,    // manual reset event
        FALSE,   // not-signalled
        NULL);   // no name

    if ( hServerStopEvent == NULL)
        goto cleanup;

    // report the status to the service control manager.
    //
    if (!ReportStatusToSCMgr(
        SERVICE_RUNNING,       // service state
        NO_ERROR,              // exit code
        0))                    // wait hint
        goto cleanup;

    //
    // End of initialization
    //
    ////////////////////////////////////////////////////////

    ////////////////////////////////////////////////////////
    // 
    // Service is now running, perform work until shutdown
    //

	sprintf(iniFile, "%s\\%s", currentDir, "HaimailService.ini");
	nInterval = GetPrivateProfileInt("Settings", "Interval", 60, iniFile);

	sprintf(exeFile1, "%s\\%s", currentDir, "HaimailReg.exe");
	sprintf(exeFile2, "%s\\%s", currentDir, "HaimailRecv.exe");
	sprintf(exeFile3, "%s\\%s", currentDir, "HaimailSend.exe");

	nCount = 0;
	while (WaitForSingleObject(hServerStopEvent, nInterval * 1000 / 3) == WAIT_TIMEOUT) {
		memset(&si, 0, sizeof(si));
		si.cb = sizeof(si);

		switch (nCount++) {
		case 0:
			si.dwFlags = STARTF_USESHOWWINDOW;
			si.wShowWindow = SW_HIDE;
			if (CreateProcess(exeFile1, NULL, NULL, NULL, FALSE, IDLE_PRIORITY_CLASS, NULL, currentDir, &si, &pi)) {
				CloseHandle(pi.hProcess);
				CloseHandle(pi.hThread);
			}
			break;
		case 1:
			si.dwFlags = STARTF_USESHOWWINDOW;
			si.wShowWindow = SW_HIDE;
			if (CreateProcess(exeFile2, NULL, NULL, NULL, FALSE, IDLE_PRIORITY_CLASS, NULL, currentDir, &si, &pi)) {
				CloseHandle(pi.hProcess);
				CloseHandle(pi.hThread);
			}
			break;
		case 2:
			si.dwFlags = STARTF_USESHOWWINDOW;
			si.wShowWindow = SW_HIDE;
			if (CreateProcess(exeFile3, NULL, NULL, NULL, FALSE, IDLE_PRIORITY_CLASS, NULL, currentDir, &si, &pi)) {
				CloseHandle(pi.hProcess);
				CloseHandle(pi.hThread);
			}
			nCount = 0;
			break;
		}
//		nInterval = GetPrivateProfileInt("Settings", "Interval", 60, iniFile);
nInterval = 60;
	}

  cleanup:

    if (hServerStopEvent)
        CloseHandle(hServerStopEvent);
}


//
//  FUNCTION: ServiceStop
//
//  PURPOSE: Stops the service
//
//  PARAMETERS:
//    none
//
//  RETURN VALUE:
//    none
//
//  COMMENTS:
//    If a ServiceStop procedure is going to
//    take longer than 3 seconds to execute,
//    it should spawn a thread to execute the
//    stop code, and return.  Otherwise, the
//    ServiceContrxxxanager will believe that
//    the service has stopped responding.
//    
VOID ServiceStop()
{
    if ( hServerStopEvent )
        SetEvent(hServerStopEvent);
}
