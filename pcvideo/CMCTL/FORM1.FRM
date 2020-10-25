VERSION 4.00
Begin VB.Form frmMainWindow 
   AutoRedraw      =   -1  'True
   Caption         =   "CMCTL TEST PROGRAM"
   ClientHeight    =   5265
   ClientLeft      =   2340
   ClientTop       =   3255
   ClientWidth     =   8220
   Height          =   5670
   Left            =   2280
   LinkTopic       =   "Form1"
   ScaleHeight     =   5265
   ScaleWidth      =   8220
   Top             =   2910
   Width           =   8340
   Begin VB.CommandButton cmdReset 
      Caption         =   "Reset"
      Height          =   390
      Left            =   5520
      TabIndex        =   20
      Top             =   2400
      Width           =   975
   End
   Begin VB.TextBox txtErrorCode 
      Enabled         =   0   'False
      Height          =   405
      Left            =   1440
      TabIndex        =   19
      Top             =   4440
      Width           =   615
   End
   Begin VB.CommandButton cmdPosition 
      Caption         =   "Position"
      Height          =   375
      Left            =   5520
      TabIndex        =   18
      Top             =   1320
      Width           =   975
   End
   Begin VB.CommandButton cmdPreset 
      Caption         =   "Preset"
      Height          =   375
      Left            =   5520
      TabIndex        =   17
      Top             =   1680
      Width           =   975
   End
   Begin VB.CommandButton cmdOption 
      Caption         =   "Option"
      Height          =   375
      Left            =   5520
      TabIndex        =   16
      Top             =   960
      Width           =   975
   End
   Begin VB.CommandButton cmdUnlock 
      Caption         =   "Unlock"
      Height          =   375
      Left            =   5520
      TabIndex        =   15
      Top             =   600
      Width           =   975
   End
   Begin VB.CommandButton cmdLock 
      Caption         =   "Lock"
      Height          =   375
      Left            =   5520
      TabIndex        =   14
      Top             =   240
      Width           =   975
   End
   Begin VB.CommandButton cmdStop 
      Caption         =   "Stop"
      Height          =   375
      Left            =   6720
      TabIndex        =   12
      Top             =   3840
      Width           =   975
   End
   Begin VB.CommandButton cmdRight 
      Caption         =   "Right"
      Height          =   375
      Left            =   6720
      TabIndex        =   11
      Top             =   3480
      Width           =   975
   End
   Begin VB.CommandButton cmdLeft 
      Caption         =   "Left"
      Height          =   375
      Left            =   6720
      TabIndex        =   10
      Top             =   3120
      Width           =   975
   End
   Begin VB.CommandButton cmdDown 
      Caption         =   "Down"
      Height          =   375
      Left            =   6720
      TabIndex        =   9
      Top             =   2760
      Width           =   975
   End
   Begin VB.CommandButton cmdUp 
      Caption         =   "Up"
      Height          =   375
      Left            =   6720
      TabIndex        =   8
      Top             =   2400
      Width           =   975
   End
   Begin VB.CommandButton cmdHome 
      Caption         =   "Home"
      Height          =   375
      Left            =   6720
      TabIndex        =   7
      Top             =   2040
      Width           =   975
   End
   Begin VB.CommandButton Command5 
      Caption         =   "Exit"
      Height          =   375
      Left            =   6720
      TabIndex        =   6
      Top             =   4560
      Width           =   975
   End
   Begin VB.CommandButton cmdFrame 
      Caption         =   "Frame"
      Height          =   375
      Left            =   6720
      TabIndex        =   5
      Top             =   1320
      Width           =   975
   End
   Begin VB.CommandButton cmdAuto 
      Caption         =   "Auto"
      Height          =   375
      Left            =   6720
      TabIndex        =   4
      Top             =   960
      Width           =   975
   End
   Begin VB.CommandButton cmdSmall 
      Caption         =   "Small"
      Height          =   375
      Left            =   6720
      TabIndex        =   3
      Top             =   600
      Width           =   975
   End
   Begin VB.CommandButton cmdLarge 
      Caption         =   "Large"
      Height          =   375
      Left            =   6720
      TabIndex        =   2
      Top             =   240
      Width           =   975
   End
   Begin VB.Timer Timer1 
      Interval        =   10
      Left            =   5760
      Top             =   4560
   End
   Begin VB.PictureBox Picture1 
      AutoRedraw      =   -1  'True
      FillColor       =   &H00FF80FF&
      Height          =   1335
      Left            =   360
      ScaleHeight     =   85
      ScaleMode       =   3  'Ëß¸¾Ù
      ScaleWidth      =   117
      TabIndex        =   0
      Top             =   360
      Width           =   1815
   End
   Begin VB.Label lblErrorCode 
      Height          =   270
      Left            =   1560
      TabIndex        =   13
      Top             =   3960
      Width           =   645
   End
   Begin VB.Label Label1 
      AutoSize        =   -1  'True
      Caption         =   "ErrorCode"
      Height          =   270
      Left            =   240
      TabIndex        =   1
      Top             =   3960
      Width           =   1065
   End
End
Attribute VB_Name = "frmMainWindow"
Attribute VB_Creatable = False
Attribute VB_Exposed = False
Option Explicit

Dim buf() As RGBBUF24

Private Sub cmdDown_Click()
    txtErrorCode = CMControl(handle, CM_TILT_DOWN)
End Sub


Private Sub cmdOption_Click()
    Dim ErrorCode As Integer

    ErrorCode = CMGetCameraOpt(handle, CameraOptBuf)
    txtErrorCode = ErrorCode
    If ErrorCode = 0 Then
        frmCameraOpt.Show 1
        If OKFlag = 1 Then
            txtErrorCode = CMSetCameraOpt(handle, CameraOptBuf)
        End If
    End If
End Sub

Private Sub cmdPosition_Click()
    Dim ErrorCode As Integer

    ErrorCode = CMGetCameraPos(handle, CameraPosBuf)
    txtErrorCode = ErrorCode
    If ErrorCode = 0 Then
        frmCameraPos.Show 1
        If OKFlag = 1 Then
            txtErrorCode = CMSetCameraPos(handle, CameraPosBuf)
        End If
    End If
End Sub


Private Sub cmdPreset_Click()
    Dim ErrorCode As Integer
    Dim i As Integer

    ErrorCode = CMGetPreset(handle, PresetBuf(0))
    txtErrorCode = ErrorCode
    If ErrorCode = 0 Then
        frmPreset.Show 1
        If OKFlag = 1 Then
            For i = 0 To 7
                ErrorCode = CMSetPreset(handle, PresetBuf(i), i + 1)
            Next
        End If
    End If
End Sub

Private Sub cmdHome_Click()
    txtErrorCode = CMControl(handle, CM_HOME_POSITION)
End Sub


Private Sub cmdLarge_Click()
    If mode.size < 16 Then
        mode.size = mode.size + 1
        Call SetVideoSize
    Else
        Beep
    End If
End Sub


Private Sub cmdLeft_Click()
    txtErrorCode = CMControl(handle, CM_PAN_LEFT)
End Sub

Private Sub cmdLock_Click()
    txtErrorCode = CMLock(handle, 1)
End Sub

Private Sub cmdReset_Click()
    txtErrorCode = CMControl(handle, CM_RESET)
End Sub

Private Sub cmdRight_Click()
    txtErrorCode = CMControl(handle, CM_PAN_RIGHT)
End Sub



Private Sub cmdSmall_Click()
    If mode.size <> 0 Then
        mode.size = mode.size - 1
        Call SetVideoSize
   Else
        Beep
    End If
End Sub

Private Sub cmdAuto_Click()
    Timer1.Enabled = True
End Sub

Private Sub cmdFrame_Click()
    Dim ErrCode As Integer

    Timer1.Enabled = False
        ErrCode = CMCaptureVideo(handle, mode, buf(0))
        If ErrCode = 0 Then
            ErrCode = CMDraw(Picture1.hdc, mode, buf(0))
            Picture1.Refresh
        End If
        lblErrorCode = ErrCode
End Sub

Private Sub cmdStop_Click()
    txtErrorCode = CMControl(handle, CM_STOP)
End Sub

Private Sub cmdUnlock_Click()
    txtErrorCode = CMLock(handle, 0)
End Sub

Private Sub cmdUp_Click()
    txtErrorCode = CMControl(handle, CM_TILT_UP)
End Sub

Private Sub Command5_Click()
    Unload frmMainWindow
End Sub

Private Sub Form_Load()
    Dim ErrCode As Integer

    VideoSize(0).width = 40
    VideoSize(0).height = 30
    VideoSize(1).width = 80
    VideoSize(1).height = 60
    VideoSize(2).width = 120
    VideoSize(2).height = 90
    VideoSize(3).width = 160
    VideoSize(3).height = 120
    VideoSize(4).width = 200
    VideoSize(4).height = 150
    VideoSize(5).width = 240
    VideoSize(5).height = 180
    VideoSize(6).width = 280
    VideoSize(6).height = 210
    VideoSize(7).width = 320
    VideoSize(7).height = 240
    VideoSize(8).width = 360
    VideoSize(8).height = 270
    VideoSize(9).width = 400
    VideoSize(9).height = 300
    VideoSize(10).width = 440
    VideoSize(10).height = 330
    VideoSize(11).width = 480
    VideoSize(11).height = 360
    VideoSize(12).width = 520
    VideoSize(12).height = 390
    VideoSize(13).width = 560
    VideoSize(13).height = 420
    VideoSize(14).width = 600
    VideoSize(14).height = 450
    VideoSize(15).width = 640
    VideoSize(15).height = 480

    mode.size = 3
    mode.color = 0

    Call SetVideoSize
        
    txtErrorCode = CMOpen("COM1", "test", 0, handle)
End Sub



Private Sub Form_Unload(Cancel As Integer)
    txtErrorCode = CMClose(handle)
End Sub

Private Sub Picture1_Paint()
    Dim ErrCode As Integer
    
'    ErrCode = CMPaint(hDC, Image, buf(0))

End Sub


Private Sub Timer1_Timer()
    Dim ErrCode As Integer
'    Dim i As Integer

'    If handle <> 0 Then
        ErrCode = CMCaptureVideo(handle, mode, buf(0))
        If ErrCode = 0 Then
'            For i = 0 To 1000
'                buf(i).green = 100
'            Next
            
            ErrCode = CMDraw(Picture1.hdc, mode, buf(0))
            Picture1.Refresh
'        Else
'            Beep
        End If
        lblErrorCode = ErrCode
'    End If
End Sub



Public Sub SetVideoSize()
    ReDim buf(CLng(VideoSize(mode.size).width) * VideoSize(mode.size).height) As RGBBUF24
    Picture1.width = Picture1.ScaleX(VideoSize(mode.size).width, 3, 1)
    Picture1.height = Picture1.ScaleY(VideoSize(mode.size).height, 3, 1)
End Sub
