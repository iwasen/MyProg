VERSION 4.00
Begin VB.Form frmPreset 
   Caption         =   "Form2"
   ClientHeight    =   4620
   ClientLeft      =   2490
   ClientTop       =   1920
   ClientWidth     =   7350
   Height          =   5025
   Left            =   2430
   LinkTopic       =   "Form2"
   ScaleHeight     =   4620
   ScaleWidth      =   7350
   Top             =   1575
   Width           =   7470
   Begin VB.OptionButton optPreset1 
      Caption         =   "8"
      Height          =   270
      Index           =   7
      Left            =   240
      TabIndex        =   45
      Top             =   4080
      Width           =   495
   End
   Begin VB.OptionButton optPreset1 
      Caption         =   "7"
      Height          =   270
      Index           =   6
      Left            =   240
      TabIndex        =   44
      Top             =   3600
      Width           =   495
   End
   Begin VB.OptionButton optPreset1 
      Caption         =   "6"
      Height          =   270
      Index           =   5
      Left            =   240
      TabIndex        =   43
      Top             =   3120
      Width           =   495
   End
   Begin VB.OptionButton optPreset1 
      Caption         =   "5"
      Height          =   270
      Index           =   4
      Left            =   240
      TabIndex        =   42
      Top             =   2640
      Width           =   495
   End
   Begin VB.OptionButton optPreset1 
      Caption         =   "4"
      Height          =   270
      Index           =   3
      Left            =   240
      TabIndex        =   41
      Top             =   2160
      Width           =   495
   End
   Begin VB.OptionButton optPreset1 
      Caption         =   "3"
      Height          =   270
      Index           =   2
      Left            =   240
      TabIndex        =   40
      Top             =   1680
      Width           =   495
   End
   Begin VB.OptionButton optPreset1 
      Caption         =   "2"
      Height          =   270
      Index           =   1
      Left            =   240
      TabIndex        =   39
      Top             =   1200
      Width           =   495
   End
   Begin VB.OptionButton optPreset1 
      Caption         =   "1"
      Height          =   270
      Index           =   0
      Left            =   240
      TabIndex        =   38
      Top             =   720
      Width           =   495
   End
   Begin VB.CommandButton cmdCancel 
      Caption         =   "Cancel"
      Height          =   495
      Left            =   6000
      TabIndex        =   37
      Top             =   1200
      Width           =   1095
   End
   Begin VB.CommandButton cmdOK 
      Caption         =   "OK"
      Height          =   495
      Left            =   6000
      TabIndex        =   36
      Top             =   600
      Width           =   1095
   End
   Begin VB.TextBox txtComment 
      Height          =   405
      Index           =   7
      Left            =   3000
      TabIndex        =   35
      Top             =   3960
      Width           =   2775
   End
   Begin VB.TextBox txtZoom 
      Height          =   405
      Index           =   7
      Left            =   2280
      TabIndex        =   34
      Top             =   3960
      Width           =   615
   End
   Begin VB.TextBox txtTilt 
      Height          =   405
      Index           =   7
      Left            =   1560
      TabIndex        =   33
      Top             =   3960
      Width           =   615
   End
   Begin VB.TextBox txtPan 
      Height          =   405
      Index           =   7
      Left            =   840
      TabIndex        =   32
      Top             =   3960
      Width           =   615
   End
   Begin VB.TextBox txtComment 
      Height          =   405
      Index           =   6
      Left            =   3000
      TabIndex        =   31
      Top             =   3480
      Width           =   2775
   End
   Begin VB.TextBox txtZoom 
      Height          =   405
      Index           =   6
      Left            =   2280
      TabIndex        =   30
      Top             =   3480
      Width           =   615
   End
   Begin VB.TextBox txtTilt 
      Height          =   405
      Index           =   6
      Left            =   1560
      TabIndex        =   29
      Top             =   3480
      Width           =   615
   End
   Begin VB.TextBox txtPan 
      Height          =   405
      Index           =   6
      Left            =   840
      TabIndex        =   28
      Top             =   3480
      Width           =   615
   End
   Begin VB.TextBox txtComment 
      Height          =   405
      Index           =   5
      Left            =   3000
      TabIndex        =   27
      Top             =   3000
      Width           =   2775
   End
   Begin VB.TextBox txtZoom 
      Height          =   405
      Index           =   5
      Left            =   2280
      TabIndex        =   26
      Top             =   3000
      Width           =   615
   End
   Begin VB.TextBox txtTilt 
      Height          =   405
      Index           =   5
      Left            =   1560
      TabIndex        =   25
      Top             =   3000
      Width           =   615
   End
   Begin VB.TextBox txtPan 
      Height          =   405
      Index           =   5
      Left            =   840
      TabIndex        =   24
      Top             =   3000
      Width           =   615
   End
   Begin VB.TextBox txtComment 
      Height          =   405
      Index           =   4
      Left            =   3000
      TabIndex        =   23
      Top             =   2520
      Width           =   2775
   End
   Begin VB.TextBox txtZoom 
      Height          =   405
      Index           =   4
      Left            =   2280
      TabIndex        =   22
      Top             =   2520
      Width           =   615
   End
   Begin VB.TextBox txtTilt 
      Height          =   405
      Index           =   4
      Left            =   1560
      TabIndex        =   21
      Top             =   2520
      Width           =   615
   End
   Begin VB.TextBox txtPan 
      Height          =   405
      Index           =   4
      Left            =   840
      TabIndex        =   20
      Top             =   2520
      Width           =   615
   End
   Begin VB.TextBox txtComment 
      Height          =   405
      Index           =   3
      Left            =   3000
      TabIndex        =   19
      Top             =   2040
      Width           =   2775
   End
   Begin VB.TextBox txtZoom 
      Height          =   405
      Index           =   3
      Left            =   2280
      TabIndex        =   18
      Top             =   2040
      Width           =   615
   End
   Begin VB.TextBox txtTilt 
      Height          =   405
      Index           =   3
      Left            =   1560
      TabIndex        =   17
      Top             =   2040
      Width           =   615
   End
   Begin VB.TextBox txtPan 
      Height          =   405
      Index           =   3
      Left            =   840
      TabIndex        =   16
      Top             =   2040
      Width           =   615
   End
   Begin VB.TextBox txtComment 
      Height          =   405
      Index           =   2
      Left            =   3000
      TabIndex        =   15
      Top             =   1560
      Width           =   2775
   End
   Begin VB.TextBox txtZoom 
      Height          =   405
      Index           =   2
      Left            =   2280
      TabIndex        =   14
      Top             =   1560
      Width           =   615
   End
   Begin VB.TextBox txtTilt 
      Height          =   405
      Index           =   2
      Left            =   1560
      TabIndex        =   13
      Top             =   1560
      Width           =   615
   End
   Begin VB.TextBox txtPan 
      Height          =   405
      Index           =   2
      Left            =   840
      TabIndex        =   12
      Top             =   1560
      Width           =   615
   End
   Begin VB.TextBox txtComment 
      Height          =   405
      Index           =   1
      Left            =   3000
      TabIndex        =   11
      Top             =   1080
      Width           =   2775
   End
   Begin VB.TextBox txtZoom 
      Height          =   405
      Index           =   1
      Left            =   2280
      TabIndex        =   10
      Top             =   1080
      Width           =   615
   End
   Begin VB.TextBox txtTilt 
      Height          =   405
      Index           =   1
      Left            =   1560
      TabIndex        =   9
      Top             =   1080
      Width           =   615
   End
   Begin VB.TextBox txtPan 
      Height          =   405
      Index           =   1
      Left            =   840
      TabIndex        =   8
      Top             =   1080
      Width           =   615
   End
   Begin VB.TextBox txtComment 
      Height          =   405
      Index           =   0
      Left            =   3000
      TabIndex        =   7
      Top             =   600
      Width           =   2775
   End
   Begin VB.TextBox txtZoom 
      Height          =   405
      Index           =   0
      Left            =   2280
      TabIndex        =   6
      Top             =   600
      Width           =   615
   End
   Begin VB.TextBox txtTilt 
      Height          =   405
      Index           =   0
      Left            =   1560
      TabIndex        =   5
      Top             =   600
      Width           =   615
   End
   Begin VB.TextBox txtPan 
      Height          =   405
      Index           =   0
      Left            =   840
      TabIndex        =   4
      Top             =   600
      Width           =   615
   End
   Begin VB.Label Label4 
      AutoSize        =   -1  'True
      Caption         =   "Comment"
      Height          =   270
      Left            =   3120
      TabIndex        =   3
      Top             =   240
      Width           =   960
   End
   Begin VB.Label Label3 
      AutoSize        =   -1  'True
      Caption         =   "Zoom"
      Height          =   270
      Left            =   2280
      TabIndex        =   2
      Top             =   240
      Width           =   555
   End
   Begin VB.Label Label2 
      AutoSize        =   -1  'True
      Caption         =   "Tilt"
      Height          =   270
      Left            =   1680
      TabIndex        =   1
      Top             =   240
      Width           =   270
   End
   Begin VB.Label Label1 
      AutoSize        =   -1  'True
      Caption         =   "Pan"
      Height          =   270
      Left            =   960
      TabIndex        =   0
      Top             =   240
      Width           =   345
   End
End
Attribute VB_Name = "frmPreset"
Attribute VB_Creatable = False
Attribute VB_Exposed = False
Option Explicit

Private Sub cmdCancel_Click()
    Unload frmPreset
End Sub


Private Sub cmdOK_Click()
    Dim i As Integer
    
    For i = 0 To 7
        PresetBuf(i).Pos.Pan = Trim$(txtPan(i)) + Chr$(0)
        PresetBuf(i).Pos.Tilt = Trim$(txtTilt(i)) + Chr$(0)
        PresetBuf(i).Pos.Zoom = Trim$(txtZoom(i)) + Chr$(0)
        PresetBuf(i).Comment = Trim$(txtComment(i)) + Chr$(0)
    Next
    
    Unload frmPreset
    OKFlag = 1
End Sub


Private Sub Command1_Click()
    Unload frmPreset
End Sub

Private Sub Form_Load()
    Dim i As Integer
    
    For i = 0 To 7
        txtPan(i) = PresetBuf(i).Pos.Pan
        txtTilt(i) = PresetBuf(i).Pos.Tilt
        txtZoom(i) = PresetBuf(i).Pos.Zoom
        txtComment(i) = PresetBuf(i).Comment
    Next

    OKFlag = 0
End Sub


Private Sub option1_Click()

End Sub


Private Sub optPreset1_Click(Index As Integer)
    frmMainWindow.txtErrorCode = CMMovePreset(handle, Index + 1)
End Sub


