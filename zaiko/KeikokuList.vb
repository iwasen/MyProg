Public Class frmKeikokuList
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
    Friend WithEvents lblTitle As System.Windows.Forms.Label
    Friend WithEvents btnPrint As System.Windows.Forms.Button
    Friend WithEvents btnClose As System.Windows.Forms.Button
    Friend WithEvents GroupBox1 As System.Windows.Forms.GroupBox
    Friend WithEvents GroupBox2 As System.Windows.Forms.GroupBox
    Friend WithEvents btnList As System.Windows.Forms.Button
    Public WithEvents radHacchuu As System.Windows.Forms.RadioButton
    Public WithEvents radSeizou As System.Windows.Forms.RadioButton
    <System.Diagnostics.DebuggerStepThrough()> Private Sub InitializeComponent()
        Me.lblTitle = New System.Windows.Forms.Label()
        Me.btnPrint = New System.Windows.Forms.Button()
        Me.btnClose = New System.Windows.Forms.Button()
        Me.GroupBox1 = New System.Windows.Forms.GroupBox()
        Me.GroupBox2 = New System.Windows.Forms.GroupBox()
        Me.radHacchuu = New System.Windows.Forms.RadioButton()
        Me.radSeizou = New System.Windows.Forms.RadioButton()
        Me.btnList = New System.Windows.Forms.Button()
        Me.GroupBox2.SuspendLayout()
        Me.SuspendLayout()
        '
        'lblTitle
        '
        Me.lblTitle.BackColor = System.Drawing.Color.LightSteelBlue
        Me.lblTitle.BorderStyle = System.Windows.Forms.BorderStyle.Fixed3D
        Me.lblTitle.Dock = System.Windows.Forms.DockStyle.Top
        Me.lblTitle.Font = New System.Drawing.Font("MS UI Gothic", 16.0!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.lblTitle.ForeColor = System.Drawing.Color.Green
        Me.lblTitle.Location = New System.Drawing.Point(0, 2)
        Me.lblTitle.Name = "lblTitle"
        Me.lblTitle.Size = New System.Drawing.Size(530, 28)
        Me.lblTitle.TabIndex = 1
        Me.lblTitle.Text = "発注点警告リスト印刷"
        Me.lblTitle.TextAlign = System.Drawing.ContentAlignment.MiddleCenter
        '
        'btnPrint
        '
        Me.btnPrint.Anchor = System.Windows.Forms.AnchorStyles.Bottom
        Me.btnPrint.Location = New System.Drawing.Point(219, 180)
        Me.btnPrint.Name = "btnPrint"
        Me.btnPrint.Size = New System.Drawing.Size(92, 24)
        Me.btnPrint.TabIndex = 7
        Me.btnPrint.Text = "印刷(&P)..."
        '
        'btnClose
        '
        Me.btnClose.Anchor = System.Windows.Forms.AnchorStyles.Bottom
        Me.btnClose.DialogResult = System.Windows.Forms.DialogResult.Cancel
        Me.btnClose.Location = New System.Drawing.Point(326, 180)
        Me.btnClose.Name = "btnClose"
        Me.btnClose.Size = New System.Drawing.Size(92, 24)
        Me.btnClose.TabIndex = 8
        Me.btnClose.Text = "閉じる(&C)"
        '
        'GroupBox1
        '
        Me.GroupBox1.Anchor = ((System.Windows.Forms.AnchorStyles.Bottom Or System.Windows.Forms.AnchorStyles.Left) _
                    Or System.Windows.Forms.AnchorStyles.Right)
        Me.GroupBox1.Location = New System.Drawing.Point(7, 164)
        Me.GroupBox1.Name = "GroupBox1"
        Me.GroupBox1.Size = New System.Drawing.Size(516, 4)
        Me.GroupBox1.TabIndex = 5
        Me.GroupBox1.TabStop = False
        '
        'GroupBox2
        '
        Me.GroupBox2.Controls.AddRange(New System.Windows.Forms.Control() {Me.radHacchuu, Me.radSeizou})
        Me.GroupBox2.Location = New System.Drawing.Point(40, 48)
        Me.GroupBox2.Name = "GroupBox2"
        Me.GroupBox2.Size = New System.Drawing.Size(452, 100)
        Me.GroupBox2.TabIndex = 2
        Me.GroupBox2.TabStop = False
        Me.GroupBox2.Text = "製造区分"
        '
        'radHacchuu
        '
        Me.radHacchuu.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.radHacchuu.Location = New System.Drawing.Point(126, 60)
        Me.radHacchuu.Name = "radHacchuu"
        Me.radHacchuu.Size = New System.Drawing.Size(200, 24)
        Me.radHacchuu.TabIndex = 4
        Me.radHacchuu.Text = "発注分"
        '
        'radSeizou
        '
        Me.radSeizou.Checked = True
        Me.radSeizou.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.radSeizou.Location = New System.Drawing.Point(126, 28)
        Me.radSeizou.Name = "radSeizou"
        Me.radSeizou.Size = New System.Drawing.Size(200, 24)
        Me.radSeizou.TabIndex = 3
        Me.radSeizou.TabStop = True
        Me.radSeizou.Text = "製造分"
        '
        'btnList
        '
        Me.btnList.Anchor = System.Windows.Forms.AnchorStyles.Bottom
        Me.btnList.Location = New System.Drawing.Point(113, 180)
        Me.btnList.Name = "btnList"
        Me.btnList.Size = New System.Drawing.Size(92, 24)
        Me.btnList.TabIndex = 6
        Me.btnList.Text = "一覧表示(&L)..."
        '
        'frmKeikokuList
        '
        Me.AutoScaleBaseSize = New System.Drawing.Size(5, 12)
        Me.ClientSize = New System.Drawing.Size(530, 215)
        Me.Controls.AddRange(New System.Windows.Forms.Control() {Me.GroupBox2, Me.GroupBox1, Me.btnPrint, Me.btnClose, Me.lblTitle, Me.btnList})
        Me.DockPadding.Top = 2
        Me.FormBorderStyle = System.Windows.Forms.FormBorderStyle.FixedSingle
        Me.MaximizeBox = False
        Me.MinimizeBox = False
        Me.Name = "frmKeikokuList"
        Me.ShowInTaskbar = False
        Me.StartPosition = System.Windows.Forms.FormStartPosition.CenterScreen
        Me.Text = "KeikokuList"
        Me.GroupBox2.ResumeLayout(False)
        Me.ResumeLayout(False)

    End Sub

#End Region

    '*** フォームロードイベント
    Private Sub frmKeikokuList_Load(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MyBase.Load
        Me.Text = Common.TITLE
    End Sub

    '*** [印刷]ボタンクリックイベント
    Private Sub btnPrint_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnPrint.Click
        Dim seizouKubunCode As String
        Dim seizouKubunText As String

        If Me.radSeizou.Checked Then
            seizouKubunCode = 1
            seizouKubunText = Me.radSeizou.Text
        Else
            seizouKubunCode = 2
            seizouKubunText = Me.radHacchuu.Text
        End If

        Dim dataAdapter As New OleDb.OleDbDataAdapter( _
            "SELECT '" & seizouKubunText & "' AS 製造区分, 発注点警告日, パーツ名称, 発注点数量, round(現在庫数 / 発注点数量 * 100) AS 在庫比率, 現在庫数, 備考" & _
            " FROM パーツ在庫マスタ" & _
            " WHERE 製造区分='" & seizouKubunCode & "' AND 現在庫数 < 発注点数量" & _
            " ORDER BY 発注点警告日,パーツ名称", _
            dbConnection)
        Dim dsZaiko As New dsWork()

        dataAdapter.Fill(dsZaiko, "発注点警告リスト")

        If dsZaiko.Tables("発注点警告リスト").Rows.Count = 0 Then
            MsgBox("現在、印刷するデータはありません。", MsgBoxStyle.OKOnly Or MsgBoxStyle.Information)
            Return
        End If

        Dim rcKeikokuList As ReportClass = New KeikokuList()
        ShowPrintForm(lblTitle.Text, rcKeikokuList, dsZaiko)
    End Sub

    '*** [一覧表示]ボタンクリックイベント
    Private Sub btnList_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnList.Click
        Dim frm As New frmKeikokuList2()

        If Me.radSeizou.Checked Then
            frm.seizouKubunCode = 1
            frm.seizouKubunText = Me.radSeizou.Text
        Else
            frm.seizouKubunCode = 2
            frm.seizouKubunText = Me.radHacchuu.Text
        End If

        frm.ShowDialog()
    End Sub
End Class
