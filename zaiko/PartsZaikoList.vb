Public Class frmPartsZaikoList
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
    Friend WithEvents Label1 As System.Windows.Forms.Label
    Friend WithEvents btnPrint As System.Windows.Forms.Button
    Friend WithEvents cmbPartsShubetsu As System.Windows.Forms.ComboBox
    Friend WithEvents btnClose As System.Windows.Forms.Button
    Friend WithEvents GroupBox1 As System.Windows.Forms.GroupBox
    <System.Diagnostics.DebuggerStepThrough()> Private Sub InitializeComponent()
        Me.lblTitle = New System.Windows.Forms.Label()
        Me.cmbPartsShubetsu = New System.Windows.Forms.ComboBox()
        Me.Label1 = New System.Windows.Forms.Label()
        Me.btnPrint = New System.Windows.Forms.Button()
        Me.btnClose = New System.Windows.Forms.Button()
        Me.GroupBox1 = New System.Windows.Forms.GroupBox()
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
        Me.lblTitle.Text = "パーツ在庫リスト印刷"
        Me.lblTitle.TextAlign = System.Drawing.ContentAlignment.MiddleCenter
        '
        'cmbPartsShubetsu
        '
        Me.cmbPartsShubetsu.DisplayMember = "パーツ種別名"
        Me.cmbPartsShubetsu.DropDownStyle = System.Windows.Forms.ComboBoxStyle.DropDownList
        Me.cmbPartsShubetsu.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.cmbPartsShubetsu.Location = New System.Drawing.Point(184, 68)
        Me.cmbPartsShubetsu.Name = "cmbPartsShubetsu"
        Me.cmbPartsShubetsu.Size = New System.Drawing.Size(176, 23)
        Me.cmbPartsShubetsu.TabIndex = 3
        Me.cmbPartsShubetsu.ValueMember = "パーツ種別コード"
        '
        'Label1
        '
        Me.Label1.AutoSize = True
        Me.Label1.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.Label1.Location = New System.Drawing.Point(104, 72)
        Me.Label1.Name = "Label1"
        Me.Label1.Size = New System.Drawing.Size(73, 15)
        Me.Label1.TabIndex = 2
        Me.Label1.Text = "パーツ種別"
        '
        'btnPrint
        '
        Me.btnPrint.Anchor = System.Windows.Forms.AnchorStyles.Bottom
        Me.btnPrint.Location = New System.Drawing.Point(166, 140)
        Me.btnPrint.Name = "btnPrint"
        Me.btnPrint.Size = New System.Drawing.Size(92, 24)
        Me.btnPrint.TabIndex = 4
        Me.btnPrint.Text = "印刷(&P)..."
        '
        'btnClose
        '
        Me.btnClose.Anchor = System.Windows.Forms.AnchorStyles.Bottom
        Me.btnClose.DialogResult = System.Windows.Forms.DialogResult.Cancel
        Me.btnClose.Location = New System.Drawing.Point(273, 140)
        Me.btnClose.Name = "btnClose"
        Me.btnClose.Size = New System.Drawing.Size(92, 24)
        Me.btnClose.TabIndex = 5
        Me.btnClose.Text = "閉じる(&C)"
        '
        'GroupBox1
        '
        Me.GroupBox1.Anchor = ((System.Windows.Forms.AnchorStyles.Bottom Or System.Windows.Forms.AnchorStyles.Left) _
                    Or System.Windows.Forms.AnchorStyles.Right)
        Me.GroupBox1.Location = New System.Drawing.Point(7, 124)
        Me.GroupBox1.Name = "GroupBox1"
        Me.GroupBox1.Size = New System.Drawing.Size(516, 4)
        Me.GroupBox1.TabIndex = 11
        Me.GroupBox1.TabStop = False
        '
        'frmPartsZaikoList
        '
        Me.AutoScaleBaseSize = New System.Drawing.Size(5, 12)
        Me.ClientSize = New System.Drawing.Size(530, 175)
        Me.Controls.AddRange(New System.Windows.Forms.Control() {Me.GroupBox1, Me.btnPrint, Me.cmbPartsShubetsu, Me.Label1, Me.lblTitle, Me.btnClose})
        Me.DockPadding.Top = 2
        Me.FormBorderStyle = System.Windows.Forms.FormBorderStyle.FixedSingle
        Me.MaximizeBox = False
        Me.MinimizeBox = False
        Me.Name = "frmPartsZaikoList"
        Me.ShowInTaskbar = False
        Me.StartPosition = System.Windows.Forms.FormStartPosition.CenterScreen
        Me.Text = "PartsZaikoList"
        Me.ResumeLayout(False)

    End Sub

#End Region

    '*** フォームロードイベント
    Private Sub PartsZaikoList_Load(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MyBase.Load
        Me.Text = Common.TITLE

        'パーツ種別選択ドロップダウン設定
        PartsShubetsuSelect(cmbPartsShubetsu)
    End Sub

    '*** [印刷]ボタンクリックイベント
    Private Sub btnPrint_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnPrint.Click
        'パーツ種別選択チェック
        If cmbPartsShubetsu.SelectedIndex = 0 Then
            MsgBox("パーツ種別を選択してください。", MsgBoxStyle.OKOnly Or MsgBoxStyle.Information, APPNAME)
            cmbPartsShubetsu.Focus()
            Return
        End If

        Dim dataAdapter As New OleDb.OleDbDataAdapter( _
            "SELECT '" & cmbPartsShubetsu.Text & "' AS パーツ種別名, パーツ名称, 現在庫数, 備考" & _
            " FROM パーツ在庫マスタ" & _
            " WHERE パーツ種別='" & Me.cmbPartsShubetsu.SelectedValue & "'" & _
            " ORDER BY パーツ名称", _
            dbConnection)
        Dim dsZaiko As New dsWork()

        dataAdapter.Fill(dsZaiko, "パーツ在庫リスト")

        If dsZaiko.Tables("パーツ在庫リスト").Rows.Count = 0 Then
            MsgBox("現在、印刷するデータはありません。", MsgBoxStyle.OKOnly Or MsgBoxStyle.Information)
            Return
        End If

        '印刷データ取得
        Dim rcPartsZaikoList As ReportClass = New PartsZaikoList()
        ShowPrintForm(lblTitle.Text, rcPartsZaikoList, dsZaiko)
    End Sub
End Class
