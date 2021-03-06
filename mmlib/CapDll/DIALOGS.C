/****************************************************************************
 *
 *   dialogs.c: Code for dialog procs of CapTest Sample Program
 * 
 *   Microsoft Video for Windows Capture Class Test Program
 *
 ***************************************************************************/
/**************************************************************************
 *
 *  THIS CODE AND INFORMATION IS PROVIDED "AS IS" WITHOUT WARRANTY OF ANY
 *  KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
 *  IMPLIED WARRANTIES OF MERCHANTABILITY AND/OR FITNESS FOR A PARTICULAR
 *  PURPOSE.
 *
 *  Copyright (c) 1992, 1993  Microsoft Corporation.  All Rights Reserved.
 * 
 **************************************************************************/

#pragma warning(disable: 4819)

#include <windows.h>
#include <commdlg.h>
#include <mmsystem.h>
#include <vfw.h>
//#include <msvideo.h>
#include <mmreg.h>
#include <io.h>
#include <fcntl.h>
#include <stdio.h>
#include <stdlib.h>
#include <memory.h>
#include <dos.h>

//#include <avicap.h>
#include "cap.h"

static long GetFreeDiskSpace(WORD) ;
static int  CountMCIDevices(WORD) ;

//
// AboutProc: About Dialog Box Procedure
//
int APIENTRY AboutProc(HWND hDlg, UINT Message, UINT wParam, LONG lParam)
{
    switch (Message) {
        case WM_INITDIALOG :
             return TRUE ;

        case WM_COMMAND :
            switch (wParam) {
                case IDOK :
                    EndDialog(hDlg, TRUE) ;
                    return TRUE ;

                case IDCANCEL :
                    EndDialog(hDlg, FALSE) ;
                    return TRUE ;
            }
            break ;
    }

    return FALSE ;
}


//
// GetFreeDiskSpace: Function to Measure Available Disk Space
//
static long GetFreeDiskSpace(WORD wDrive) 
{
//	static struct _diskfree_t DiskInfo ;
	char szRootPathName[4];			// root path
	DWORD SectorsPerCluster;		// sectors per cluster
	DWORD BytesPerSector;			// bytes per sector
	DWORD NumberOfFreeClusters;		// number of free clusters
	DWORD TotalNumberOfClusters;	// total number of clusters

    // Use C function to get the disk info
	sprintf(szRootPathName, "%c:\\", wDrive + 0x40);
	if (!GetDiskFreeSpace(szRootPathName,
				&SectorsPerCluster,
				&BytesPerSector,
				&NumberOfFreeClusters,
				&TotalNumberOfClusters)) {
//	if (_dos_getdiskfree(wDrive, &DiskInfo)) {
        MessageBox(NULL, "Can't measure free disk space.", "Error",
#ifdef BIDI
               MB_RTL_READING |
#endif
               MB_OK | MB_ICONINFORMATION) ;
        return -1L ;
    }
    return ( (long) NumberOfFreeClusters * 
             (long) SectorsPerCluster *
             (long) BytesPerSector ) ;
}


//
// AllocCapFileProc: Capture file Space Allocation Dialog Box Procedure
//
int APIENTRY AllocCapFileProc(HWND hDlg, UINT Message, UINT wParam, LONG lParam)
{
    static WORD      wFreeMBs = 0 ;

    switch (Message) {
        case WM_INITDIALOG :
        {
            int              fh ;
            long             lFileSize = 0 ;
            long             lFreeSpace ;
            static char      achCapFile[100] ;

            // Get current capture file name and measure its size
            capFileGetCaptureFile(ghWndCap, achCapFile, sizeof(achCapFile)) ;
            if ((fh = _open(achCapFile, _O_RDONLY)) != -1) {
                if ((lFileSize = _lseek(fh, 0L, SEEK_END)) == -1L) {
                    MessageBox(NULL, "Couldn't find size of current capture file",
                                "Error",
#ifdef BIDI
                MB_RTL_READING |
#endif

                    MB_OK | MB_ICONEXCLAMATION) ;
                    lFileSize = 0 ;
                }
                _close(fh) ;
            }

            // Get free disk space and add current capture file size to that.
            // Convert the available space to MBs.
            if ((lFreeSpace = GetFreeDiskSpace(0)) != -1L) {
                lFreeSpace += lFileSize ;
                wFreeMBs = (WORD)(lFreeSpace / ONEMEG) ;
                SetDlgItemInt(hDlg, IDD_SetCapFileFree, wFreeMBs, TRUE) ;
            }
            else {
                MessageBox(NULL, "Couldn't find out the free disk space",
                           "Error",
#ifdef BIDI
                MB_RTL_READING |
#endif

                MB_OK | MB_ICONEXCLAMATION) ;
                // shouldn't we disable the "free space" dlg item ?
            }

            SetDlgItemInt(hDlg, IDD_SetCapFileSize, gwCapFileSize, TRUE) ;
            return TRUE ;
        }

        case WM_COMMAND :
            switch (wParam) {
                case IDOK :
                {
                    int         iCapFileSize ;

                    iCapFileSize = (int) GetDlgItemInt(hDlg, IDD_SetCapFileSize, NULL, TRUE) ;
                    if (iCapFileSize <= 0 || (WORD)iCapFileSize > wFreeMBs) {
                        // You are asking for more than we have !! Sorry, ...
                        SetDlgItemInt(hDlg, IDD_SetCapFileSize, iCapFileSize, TRUE) ;
                        SetFocus(GetDlgItem(hDlg, IDD_SetCapFileSize)) ;
                        MessageBeep(MB_ICONEXCLAMATION) ;
                        return FALSE ;
                    }
                    gwCapFileSize = iCapFileSize ;

                    EndDialog(hDlg, TRUE) ;
                    return TRUE ;
                }

                case IDCANCEL :
                    EndDialog(hDlg, FALSE) ;
                    return TRUE ;
            }
            break ;
    }

    return FALSE ;

}


//
// MakePaletteProc: Palette Details Dialog Box Procedure
//
BOOL CALLBACK MakePaletteProc(HWND hDlg, UINT Message, UINT wParam, LONG lParam)
{
    switch (Message) {
        case WM_INITDIALOG :
            SetDlgItemInt(hDlg, IDD_MakePalColors, gwPalColors, FALSE) ;
            SetDlgItemInt(hDlg, IDD_MakePalFrames, gwPalFrames, FALSE) ;
            return TRUE ;

        case WM_COMMAND :
            switch (wParam) {
                case IDOK :
                {
                    int         iColors ;
                    int         iFrames ;

                    iColors = (int) GetDlgItemInt(hDlg, IDD_MakePalColors, NULL, TRUE) ;
                    if (! (iColors > 0 && iColors <= 236 || iColors == 256)) {
                        // invalid number of palette colors
                        SetDlgItemInt(hDlg, IDD_MakePalColors, iColors, TRUE) ;
                        SetFocus(GetDlgItem(hDlg, IDD_MakePalColors)) ;
                        MessageBeep(MB_ICONEXCLAMATION) ;
                        return FALSE ;
                    }
                    iFrames = (int) GetDlgItemInt(hDlg, IDD_MakePalFrames, NULL, TRUE) ;
                    if (iFrames <= 0 || iFrames > 10000) {
                        // no frame or way t-o-o many frames !!!
                        SetDlgItemInt(hDlg, IDD_MakePalFrames, iFrames, TRUE) ;
                        SetFocus(GetDlgItem(hDlg, IDD_MakePalFrames)) ;
                        MessageBeep(MB_ICONEXCLAMATION) ;
                        return FALSE ;
                    }
                    gwPalColors = iColors ;
                    gwPalFrames = iFrames ;

                    EndDialog(hDlg, TRUE) ;
                    return TRUE ;
                }

                case IDCANCEL :
                    EndDialog(hDlg, FALSE) ;
                    return TRUE ;
            }
            break ;
    }

    return FALSE ;

}


//
// CountMCIDevices: Function to Find the Number of MCI Devices of a Type
//
static int CountMCIDevices(WORD wType)
{
    int               nTotal = 0 ;
    DWORD             dwCount ;
    MCI_SYSINFO_PxxxS mciSIP ;

    mciSIP.dwCallback = 0;
    mciSIP.lpstrReturn = (LPSTR)(LPVOID) &dwCount ;
    mciSIP.dwRetSize = sizeof(DWORD) ;
    mciSIP.wDeviceType = wType ;
   
    // Use an MCI command to get the info
    if (! mciSendCommand(0, MCI_SYSINFO, MCI_SYSINFO_QUANTITY,
                         (DWORD_PTR)(LPVOID) &mciSIP))
        nTotal = (int) *((LPDWORD) mciSIP.lpstrReturn) ;

    return nTotal ;
}


//
// CapSetUpProc: Capture SetUp Details Dialog Box Procedure
//
int APIENTRY CapSetUpProc(HWND hDlg, UINT Message, UINT wParam, LONG lParam)
{
    BOOL            fValue ;
    static char     achBuffer[21] ;

    switch (Message) {
        case WM_INITDIALOG :
        {
            WORD        wValue ;

            // Convert from MicroSecPerFrame to FPS -- that's easier !!
            wValue = (int) (1E+6 / gCapPxxxs.dwRequestMicroSecPerFrame + 0.5) ;
            SetDlgItemInt(hDlg, IDD_FrameRateData, wValue, FALSE) ;
            if (gCapPxxxs.fAbortLeftMouse)
                wValue = IDD_AbortLeftFlag ;
            else
                if (gCapPxxxs.fAbortRightMouse)
                    wValue = IDD_AbortRightFlag ;
                else
                    wValue = 0 ;  // none of the mouse-buttons !!!
            CheckRadioButton(hDlg, IDD_AbortLeftFlag, IDD_AbortRightFlag, wValue) ;

            if (gCapPxxxs.vKeyAbort == VK_ESCAPE)
                wValue = IDD_AbortEscFlag ;
            else
                if (gCapPxxxs.vKeyAbort == VK_SPACE)
                    wValue = IDD_AbortSpaceFlag ;
                else
                    if (gCapPxxxs.vKeyAbort == VK_F8)
                        wValue = IDD_AbortF8Flag ;
                    else
                        wValue = 0 ;  // none of the keys !!!
            CheckRadioButton(hDlg, IDD_AbortEscFlag, IDD_AbortF8Flag, wValue) ;

            // If time limit isn't anabled, disable the time data part
            CheckDlgButton(hDlg, IDD_TimeLimitFlag, (fValue = gCapPxxxs.fLimitEnabled)) ;
            EnableWindow(GetDlgItem(hDlg, IDD_SecondsText), fValue) ;
            EnableWindow(GetDlgItem(hDlg, IDD_SecondsData), fValue) ;
            if (fValue)
                SetDlgItemInt(hDlg, IDD_SecondsData, gCapPxxxs.wTimeLimit, FALSE) ;
            EnableWindow(GetDlgItem(hDlg, IDD_CapAudioFlag), gCapStatus.fAudioHardware) ;
            CheckDlgButton(hDlg, IDD_CapAudioFlag, gCapPxxxs.fCaptureAudio) ;
            CheckDlgButton(hDlg, IDD_UseDOSMemFlag, gCapPxxxs.fUsingDOSMemory) ;
            CheckDlgButton(hDlg, IDD_CaptureOKFlag, gCapPxxxs.fMakeUserHitOKToCapture) ;

            // Find out how many MCI devices can source video
            if (CountMCIDevices(MCI_DEVTYPE_VCR) + 
                CountMCIDevices(MCI_DEVTYPE_VIDEODISC) == 0)
                // if no VCRs or Videodiscs, disable the controls
                fValue = FALSE ;
            else
                fValue = TRUE ;

            // If no MCI device or MCI Control not enabled, disable MCI
            // device name, start and stop time, step capture etc
            EnableWindow(GetDlgItem(hDlg, IDD_MCIControlFlag), fValue) ;
            CheckDlgButton(hDlg, IDD_MCIControlFlag,
                           (fValue &= gCapPxxxs.fMCIControl)) ;
            EnableWindow(GetDlgItem(hDlg, IDD_MCIDeviceText), fValue) ;
            EnableWindow(GetDlgItem(hDlg, IDD_MCIDeviceData), fValue) ;
            SetDlgItemText(hDlg, IDD_MCIDeviceData, (LPSTR)gachMCIDeviceName) ;
            EnableWindow(GetDlgItem(hDlg, IDD_MCIStartText), fValue) ;
            EnableWindow(GetDlgItem(hDlg, IDD_MCIStartData), fValue) ;
            EnableWindow(GetDlgItem(hDlg, IDD_MCIStopText), fValue) ;
            EnableWindow(GetDlgItem(hDlg, IDD_MCIStopData), fValue) ;
            if (fValue) {
                wsprintf((LPSTR)achBuffer, "%lu", gCapPxxxs.dwMCIStartTime) ;
                SetDlgItemText(hDlg, IDD_MCIStartData, (LPSTR)achBuffer) ;
                wsprintf((LPSTR)achBuffer, "%lu", gCapPxxxs.dwMCIStopTime) ;
                SetDlgItemText(hDlg, IDD_MCIStopData, (LPSTR)achBuffer) ;
            }
            EnableWindow(GetDlgItem(hDlg, IDD_MCIStepCapFlag), fValue) ;
            CheckDlgButton(hDlg, IDD_MCIStepCapFlag, gCapPxxxs.fStepMCIDevice) ;
            SetDlgItemInt(hDlg, IDD_MaxDropData, gCapPxxxs.wPercentDropForError, FALSE) ;
            SetDlgItemInt(hDlg, IDD_VideoBuffData, gCapPxxxs.wNumVideoRequested, FALSE) ;

            return TRUE ;
        }

        case WM_COMMAND :
            switch (wParam) {
                case IDD_TimeLimitFlag :
                    // If this flag changes, en/dis-able time limit data part
                    fValue = IsDlgButtonChecked(hDlg, IDD_TimeLimitFlag) ;
                    EnableWindow(GetDlgItem(hDlg, IDD_SecondsText), fValue) ;
                    EnableWindow(GetDlgItem(hDlg, IDD_SecondsData), fValue) ;
                    return TRUE ;

                case IDD_MCIControlFlag :
                    // If this flag changes, en/dis-able MCI times data part
                    fValue = IsDlgButtonChecked(hDlg, IDD_MCIControlFlag) ;
                    EnableWindow(GetDlgItem(hDlg, IDD_MCIDeviceText), fValue) ;
                    EnableWindow(GetDlgItem(hDlg, IDD_MCIDeviceData), fValue) ;
                    EnableWindow(GetDlgItem(hDlg, IDD_MCIStartText), fValue) ;
                    EnableWindow(GetDlgItem(hDlg, IDD_MCIStartData), fValue) ;
                    EnableWindow(GetDlgItem(hDlg, IDD_MCIStopText), fValue) ;
                    EnableWindow(GetDlgItem(hDlg, IDD_MCIStopData), fValue) ;
                    EnableWindow(GetDlgItem(hDlg, IDD_MCIStepCapFlag), fValue) ;
                    CheckDlgButton(hDlg, IDD_MCIStepCapFlag,
                            gCapPxxxs.fStepMCIDevice |
                            IsDlgButtonChecked(hDlg, IDD_MCIStepCapFlag)) ;
                    return TRUE ;

                case IDOK :
                {
                    int         iFrameRate ;
                    int         iTimeLimit ;
                    int         iMaxDropRate ;
                    int         iVideoBuffers ;
                    long        lMCIStart ;
                    long        lMCIStop ;

                    if ((iFrameRate = (int) GetDlgItemInt(hDlg, IDD_FrameRateData, NULL,
                                      TRUE)) <= 0 || iFrameRate >= 100) {
                        // No frame at all or more than 100 FPS !!
                        SetDlgItemInt(hDlg, IDD_FrameRateData, iFrameRate, TRUE) ;
                        SetFocus(GetDlgItem(hDlg, IDD_FrameRateData)) ;
                        MessageBeep(MB_ICONQUESTION) ;
                        return FALSE ;
                    }
                    if (IsDlgButtonChecked(hDlg, IDD_TimeLimitFlag)) {
                        if ((iTimeLimit = (int) GetDlgItemInt(hDlg, IDD_SecondsData, NULL,
                                          TRUE)) <= 0 || iTimeLimit > 3600) {
                            // No capture or more than 1 hour !!
                            SetDlgItemInt(hDlg, IDD_SecondsData, iTimeLimit, TRUE) ;
                            SetFocus(GetDlgItem(hDlg, IDD_SecondsData)) ;
                            MessageBeep(MB_ICONQUESTION) ;
                            return FALSE ;
                        }
                    }
                    if (IsDlgButtonChecked(hDlg, IDD_MCIControlFlag)) {
                        GetDlgItemText(hDlg, IDD_MCIStartData, (LPSTR)achBuffer, 20) ;
                        lMCIStart = atol(achBuffer) ;
                        if (lMCIStart < 0 ||             // negative time !!!
                            lMCIStart >= 2000000000L) {  // or too high !!!
                            SetDlgItemText(hDlg, IDD_MCIStartData, achBuffer) ;
                            SetFocus(GetDlgItem(hDlg, IDD_MCIStartData)) ;
                            MessageBeep(MB_ICONQUESTION) ;
                            return FALSE ;
                        }
                        GetDlgItemText(hDlg, IDD_MCIStopData, (LPSTR)achBuffer, 20) ;
                        lMCIStop = atol(achBuffer) ;
                        if (lMCIStop < 0 ||            // negative time !!!
                            lMCIStop >= 2000000000L || // or too high !!!
                            lMCIStop < lMCIStart) {    // or Stop before Start !!!
                            SetDlgItemText(hDlg, IDD_MCIStopData, achBuffer) ;
                            SetFocus(GetDlgItem(hDlg, IDD_MCIStopData)) ;
                            MessageBeep(MB_ICONQUESTION) ;
                            return FALSE ;
                        }
                    }
                    if ((iMaxDropRate = (int) GetDlgItemInt(hDlg, IDD_MaxDropData, NULL,
                                            TRUE)) < 0 || iMaxDropRate >= 100) {
                        // Negative drop limit or more than 100% !!
                        SetDlgItemInt(hDlg, IDD_MaxDropData, iMaxDropRate, TRUE) ;
                        SetFocus(GetDlgItem(hDlg, IDD_MaxDropData)) ;
                        MessageBeep(0) ;
                        return FALSE ;
                    }
                    if ((iVideoBuffers = (int) GetDlgItemInt(hDlg, IDD_VideoBuffData, NULL,
                                             TRUE)) <= 0 || iVideoBuffers >= 32767) {
                        // Can't capture with too many or without video buffers
                        SetDlgItemInt(hDlg, IDD_VideoBuffData, iVideoBuffers, TRUE) ;
                        SetFocus(GetDlgItem(hDlg, IDD_VideoBuffData)) ;
                        MessageBeep(MB_ICONQUESTION) ;
                        return FALSE ;
                    }

                    // All the Cap params are correct. So set them now...
                    gCapPxxxs.dwRequestMicroSecPerFrame = (DWORD)(1E+6 / iFrameRate + 0.5) ;
                    gCapPxxxs.fLimitEnabled = IsDlgButtonChecked(hDlg, IDD_TimeLimitFlag) ;
                    gCapPxxxs.wTimeLimit = iTimeLimit ;
                    if (gCapPxxxs.fMCIControl =
                            IsDlgButtonChecked(hDlg, IDD_MCIControlFlag)) {
                        gCapPxxxs.dwMCIStartTime = lMCIStart ;
                        gCapPxxxs.dwMCIStopTime  = lMCIStop ;
                        GetDlgItemText(hDlg, IDD_MCIDeviceData, (LPSTR)achBuffer, 20) ;
                        lstrcpy(gachMCIDeviceName, achBuffer) ;
                        capSetMCIDeviceName(ghWndCap, gachMCIDeviceName) ;
                        gCapPxxxs.fStepMCIDevice = IsDlgButtonChecked(hDlg, IDD_MCIStepCapFlag) ;
                    }
                    else {
                        gCapPxxxs.dwMCIStartTime = gCapPxxxs.dwMCIStopTime = 0 ;
                        gCapPxxxs.fStepMCIDevice = FALSE ;
                    }
                    // Lets yield while doing a step capture
                    gCapPxxxs.fYield = gCapPxxxs.fStepMCIDevice;

                    gCapPxxxs.wPercentDropForError = iMaxDropRate ;
                    gCapPxxxs.wNumVideoRequested = iVideoBuffers ;
                    gCapPxxxs.fAbortLeftMouse = IsDlgButtonChecked(hDlg, IDD_AbortLeftFlag) ;
                    gCapPxxxs.fAbortRightMouse = IsDlgButtonChecked(hDlg, IDD_AbortRightFlag) ;
                    if (IsDlgButtonChecked(hDlg, IDD_AbortEscFlag))
                        gCapPxxxs.vKeyAbort = VK_ESCAPE ;
                    else
                        if (IsDlgButtonChecked(hDlg, IDD_AbortSpaceFlag))
                            gCapPxxxs.vKeyAbort = VK_SPACE ;
                        else
                            if (IsDlgButtonChecked(hDlg, IDD_AbortF8Flag))
                                gCapPxxxs.vKeyAbort = VK_F8 ;
                            else
                                gCapPxxxs.vKeyAbort = 0 ;
                    gCapPxxxs.fCaptureAudio = 
                                IsDlgButtonChecked(hDlg, IDD_CapAudioFlag) ;
                    gCapPxxxs.fUsingDOSMemory = 
                                IsDlgButtonChecked(hDlg, IDD_UseDOSMemFlag) ;
                    gCapPxxxs.fMakeUserHitOKToCapture = 
                                IsDlgButtonChecked(hDlg, IDD_CaptureOKFlag) ;

                    EndDialog(hDlg, TRUE) ;
                    return TRUE ;
                }

                case IDCANCEL :
                    EndDialog(hDlg, FALSE) ;
                    return TRUE ;
            }
            break ; 
    }

    return FALSE ;
}
