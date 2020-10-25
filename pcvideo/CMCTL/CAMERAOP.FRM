VERSION 4.00
Begin VB.Form frmCameraOpt 
   Caption         =   "Camera Option"
   ClientHeight    =   3750
   ClientLeft      =   4095
   ClientTop       =   1995
   ClientWidth     =   5280
   Height          =   4155
   Left            =   4035
   LinkTopic       =   "Form2"
   ScaleHeight     =   3750
   ScaleWidth      =   5280
   Top             =   1650
   Width           =   5400
   Begin VB.CommandButton cmdCancel 
      Cancel          =   -1  'True
      Caption         =   "Cancel"
      Height          =   495
      Left            =   3960
      TabIndex        =   15
      Top             =   840
      Width           =   1095
   End
   Begin VB.CommandButton cmdOK 
      Caption         =   "OK"
      Default         =   -1  'True
      Height          =   495
      Left            =   3960
      TabIndex        =   14
      Top             =   240
      Width           =   1095
   End
   Begin VB.TextBox txtWideConverter 
      Height          =   405
      Left            =   2280
      TabIndex        =   13
      Top             =   3120
      Width           =   1095
   End
   Begin VB.TextBox txtAutoWhiteBalance 
      Height          =   405
      Left            =   2280
      TabIndex        =   11
      Top             =   2640
      Width           =   1095
   End
   Begin VB.TextBox txtLightAdjust 
      Height          =   405
      Left            =   2280
      TabIndex        =   9
      Top             =   2160
      Width           =   1095
   End
   Begin VB.TextBox txtShutterSpeed 
      Height          =   405
      Left            =   2280
      TabIndex        =   7
      Top             =   1680
      Width           =   1095
   End
   Begin VB.TextBox txtZoomSpeed 
      Height          =   405
      Left            =   2280
      TabIndex        =   5
      Top             =   1200
      Width           =   1095
   End
   Begin VB.TextBox txtTiltSpeed 
      Height          =   405
      Left            =   2280
      TabIndex        =   3
      Top             =   720
      Width           =   1095
   End
   Begin VB.TextBox txtPanSpeed 
      Height          =   405
      Left            =   2280
      TabIndex        =   1
      Top             =   240
      Width           =   1095
   End
   Begin VB.Label Label8 
      AutoSize        =   -1  'True
      Caption         =   "Wide Converter"
      Height          =   270
      Left            =   240
      TabIndex        =   12
      Top             =   3240
      Width           =   1605
   End
   Begin VB.Label Label7 
      AutoSize        =   -1  'True
      Caption         =   "Auto white balance"
      Height          =   270
      Left            =   240
      TabIndex        =   10
      Top             =   2760
      Width           =   1965
   End
   Begin VB.Label Label6 
      AutoSize        =   -1  'True
      Caption         =   "Light adjust"
      Height          =   270
      Left            =   240
      TabIndex        =   8
      Top             =   2280
      Width           =   1155
   End
   Begin VB.Label Label4 
      AutoSize        =   -1  'True
      Caption         =   "Shutter speed"
      Height          =   270
      Left            =   240
      TabIndex        =   6
      Top             =   1800
      Width           =   1455
   End
   Begin VB.Label Label3 
      AutoSize        =   -1  'True
      Caption         =   "Zoom speed"
      Height          =   270
      Left            =   240
      TabIndex        =   4
      Top             =   1320
      Width           =   1260
   End
   Begin VB.Label Label2 
      AutoSize        =   -1  'True
      Caption         =   "Tilt speed"
      Height          =   270
      Left            =   240
      TabIndex        =   2
      Top             =   840
      Width           =   975
   End
   Begin VB.Label Label1 
      AutoSize        =   -1  'True
      Caption         =   "Pan speed"
      Height          =   270
      Left            =   240
      TabIndex        =   0
      Top             =   360
      Width           =   1050
   End
End
Attribute VB_Name = "frmCameraOpt"
Attribute VB_Creatable = False
Attribute VB_Exposed = False
Option Explicit

Private Sub Command1_Click()

End Sub


Private Sub cmdCancel_Click()
    Unload frmCameraOpt
End Sub

Private Sub cmdOK_Click()
    CameraOptBuf.PanSpeed = txtPanSpeed
    CameraOptBuf.TiltSpeed = txtTiltSpeed
    CameraOptBuf.ZoomSpeed = txtZoomSpeed
    CameraOptBuf.ShutterSpeed = txtShutterSpeed
    CameraOptBuf.LightAdjust = txtLightAdjust
    CameraOptBuf.AutoWhiteBalance = txtAutoWhiteBalance
    CameraOptBuf.WideConverter = txtWideConverter
    
    Unload frmCameraOpt
    OKFlag = 1
End Sub

Private Sub Form_Load()
    txtPanSpeed = CameraOptBuf.PanSpeed
    txtTiltSpeed = CameraOptBuf.TiltSpeed
    txtZoomSpeed = CameraOptBuf.ZoomSpeed
    txtShutterSpeed = CameraOptBuf.ShutterSpeed
    txtLightAdjust = CameraOptBuf.LightAdjust
    txtAutoWhiteBalance = CameraOptBuf.AutoWhiteBalance
    txtWideConverter = CameraOptBuf.WideConverter
    
    OKFlag = 0
End Sub

Private Sub txtSuterSpeed_Change()

End Sub


