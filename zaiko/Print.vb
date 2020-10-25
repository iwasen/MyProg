Public Class frmPrint
    Inherits System.Windows.Forms.Form

#Region " Windows フォーム デザイナで生成されたコード "

    Public Sub New()
        MyBase.New()

        ' この呼び出しは Windows フォーム デザイナで必要です。
        InitializeComponent()

        ' InitializeComponent() 呼び出しの後に初期化を追加します。

    End Sub

    ' Form は dispose をオーバーライドしてコンポーネント一覧を消去します。
    Protected Overloads Overrides Sub Dispose(ByVal disposing As Boolean)
        If disposing Then
            If Not (components Is Nothing) Then
                components.Dispose()
            End If
        End If
        MyBase.Dispose(disposing)
    End Sub

    ' Windows フォーム デザイナで必要です。
    Private components As System.ComponentModel.IContainer

    ' メモ : 以下のプロシージャは、Windows フォーム デザイナで必要です。
    ' Windows フォーム デザイナを使って変更してください。  
    ' コード エディタは使用しないでください。
    Friend WithEvents crvPrint As CrystalDecisions.Windows.Forms.CrystalReportViewer
    <System.Diagnostics.DebuggerStepThrough()> Private Sub InitializeComponent()
        Me.crvPrint = New CrystalDecisions.Windows.Forms.CrystalReportViewer()
        Me.SuspendLayout()
        '
        'crvPrint
        '
        Me.crvPrint.ActiveViewIndex = -1
        Me.crvPrint.Dock = System.Windows.Forms.DockStyle.Fill
        Me.crvPrint.Name = "crvPrint"
        Me.crvPrint.ReportSource = Nothing
        Me.crvPrint.ShowCloseButton = False
        Me.crvPrint.ShowGroupTreeButton = False
        Me.crvPrint.Size = New System.Drawing.Size(380, 329)
        Me.crvPrint.TabIndex = 0
        '
        'frmPrint
        '
        Me.AutoScaleBaseSize = New System.Drawing.Size(5, 12)
        Me.ClientSize = New System.Drawing.Size(380, 329)
        Me.Controls.AddRange(New System.Windows.Forms.Control() {Me.crvPrint})
        Me.Name = "frmPrint"
        Me.StartPosition = System.Windows.Forms.FormStartPosition.WindowsDefaultBounds
        Me.Text = "Print"
        Me.ResumeLayout(False)

    End Sub

#End Region

End Class
