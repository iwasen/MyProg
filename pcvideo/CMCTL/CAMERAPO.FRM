VERSION 4.00
Begin VB.Form frmCameraPos 
   Caption         =   "Camera Position"
   ClientHeight    =   1980
   ClientLeft      =   2805
   ClientTop       =   1770
   ClientWidth     =   3705
   Height          =   2385
   Left            =   2745
   LinkTopic       =   "Form2"
   ScaleHeight     =   1980
   ScaleWidth      =   3705
   Top             =   1425
   Width           =   3825
   Begin VB.CommandButton cmdCancel 
      Cancel          =   -1  'True
      Caption         =   "Cancel"
      Height          =   495
      Left            =   2520
      TabIndex        =   7
      Top             =   840
      Width           =   975
   End
   Begin VB.CommandButton cmdOK 
      Caption         =   "OK"
      Default         =   -1  'True
      Height          =   495
      Left            =   2520
      TabIndex        =   6
      Top             =   240
      Width           =   975
   End
   Begin VB.TextBox txtZoom 
      Height          =   405
      Left            =   1080
      TabIndex        =   5
      Top             =   1200
      Width           =   975
   End
   Begin VB.TextBox txtTilt 
      Height          =   405
      Left            =   1080
      TabIndex        =   3
      Top             =   720
      Width           =   975
   End
   Begin VB.TextBox txtPan 
      Height          =   405
      Left            =   1080
      TabIndex        =   1
      Top             =   240
      Width           =   975
   End
   Begin VB.Label Label3 
      AutoSize        =   -1  'True
      Caption         =   "Zoom"
      Height          =   270
      Left            =   360
      TabIndex        =   4
      Top             =   1320
      Width           =   555
   End
   Begin VB.Label Label2 
      AutoSize        =   -1  'True
      Caption         =   "Tilt"
      Height          =   270
      Left            =   360
      TabIndex        =   2
      Top             =   840
      Width           =   270
   End
   Begin VB.Label Label1 
      AutoSize        =   -1  'True
      Caption         =   "Pan"
      Height          =   270
      Left            =   360
      TabIndex        =   0
      Top             =   360
      Width           =   345
   End
End
Attribute VB_Name = "frmCameraPos"
Attribute VB_Creatable = False
Attribute VB_Exposed = False
Option Explicit

Private Sub cmdCancel_Click()
    Unload frmCameraPos
End Sub


Private Sub cmdOK_Click()
    CameraPosBuf.Pan = txtPan
    CameraPosBuf.Tilt = txtTilt
    CameraPosBuf.Zoom = txtZoom
    
    Unload frmCameraPos
    OKFlag = 1
End Sub


Private Sub Form_Load()
    txtPan = CameraPosBuf.Pan
    txtTilt = CameraPosBuf.Tilt
    txtZoom = CameraPosBuf.Zoom
    
    OKFlag = 0
End Sub


