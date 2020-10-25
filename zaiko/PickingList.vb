Public Class frmPickingList
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
    Friend WithEvents GroupBox1 As System.Windows.Forms.GroupBox
    Friend WithEvents btnPrint As System.Windows.Forms.Button
    Friend WithEvents btnClose As System.Windows.Forms.Button
    Friend WithEvents lblTitle As System.Windows.Forms.Label
    Friend WithEvents PrintDocument1 As System.Drawing.Printing.PrintDocument
    Friend WithEvents PrintDialog1 As System.Windows.Forms.PrintDialog
    Friend WithEvents cmbKokyaku As System.Windows.Forms.ComboBox
    Friend WithEvents Label4 As System.Windows.Forms.Label
    Friend WithEvents Label1 As System.Windows.Forms.Label
    Friend WithEvents dtpNouki As System.Windows.Forms.DateTimePicker
    Friend WithEvents Label2 As System.Windows.Forms.Label
    <System.Diagnostics.DebuggerStepThrough()> Private Sub InitializeComponent()
        Me.GroupBox1 = New System.Windows.Forms.GroupBox()
        Me.btnPrint = New System.Windows.Forms.Button()
        Me.btnClose = New System.Windows.Forms.Button()
        Me.lblTitle = New System.Windows.Forms.Label()
        Me.PrintDocument1 = New System.Drawing.Printing.PrintDocument()
        Me.PrintDialog1 = New System.Windows.Forms.PrintDialog()
        Me.cmbKokyaku = New System.Windows.Forms.ComboBox()
        Me.Label4 = New System.Windows.Forms.Label()
        Me.Label1 = New System.Windows.Forms.Label()
        Me.dtpNouki = New System.Windows.Forms.DateTimePicker()
        Me.Label2 = New System.Windows.Forms.Label()
        Me.SuspendLayout()
        '
        'GroupBox1
        '
        Me.GroupBox1.Anchor = ((System.Windows.Forms.AnchorStyles.Bottom Or System.Windows.Forms.AnchorStyles.Left) _
                    Or System.Windows.Forms.AnchorStyles.Right)
        Me.GroupBox1.Location = New System.Drawing.Point(7, 136)
        Me.GroupBox1.Name = "GroupBox1"
        Me.GroupBox1.Size = New System.Drawing.Size(516, 4)
        Me.GroupBox1.TabIndex = 6
        Me.GroupBox1.TabStop = False
        '
        'btnPrint
        '
        Me.btnPrint.Anchor = System.Windows.Forms.AnchorStyles.Bottom
        Me.btnPrint.Location = New System.Drawing.Point(166, 152)
        Me.btnPrint.Name = "btnPrint"
        Me.btnPrint.Size = New System.Drawing.Size(92, 24)
        Me.btnPrint.TabIndex = 7
        Me.btnPrint.Text = "印刷(&P)..."
        '
        'btnClose
        '
        Me.btnClose.Anchor = System.Windows.Forms.AnchorStyles.Bottom
        Me.btnClose.DialogResult = System.Windows.Forms.DialogResult.Cancel
        Me.btnClose.Location = New System.Drawing.Point(273, 152)
        Me.btnClose.Name = "btnClose"
        Me.btnClose.Size = New System.Drawing.Size(92, 24)
        Me.btnClose.TabIndex = 8
        Me.btnClose.Text = "閉じる(&C)"
        '
        'lblTitle
        '
        Me.lblTitle.BackColor = System.Drawing.Color.LightSteelBlue
        Me.lblTitle.BorderStyle = System.Windows.Forms.BorderStyle.Fixed3D
        Me.lblTitle.Dock = System.Windows.Forms.DockStyle.Top
        Me.lblTitle.Font = New System.Drawing.Font("MS UI Gothic", 16.0!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.lblTitle.ForeColor = System.Drawing.Color.Navy
        Me.lblTitle.Location = New System.Drawing.Point(0, 2)
        Me.lblTitle.Name = "lblTitle"
        Me.lblTitle.Size = New System.Drawing.Size(530, 28)
        Me.lblTitle.TabIndex = 1
        Me.lblTitle.Text = "ピッキングリスト印刷"
        Me.lblTitle.TextAlign = System.Drawing.ContentAlignment.MiddleCenter
        '
        'PrintDialog1
        '
        Me.PrintDialog1.Document = Me.PrintDocument1
        '
        'cmbKokyaku
        '
        Me.cmbKokyaku.DropDownStyle = System.Windows.Forms.ComboBoxStyle.DropDownList
        Me.cmbKokyaku.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.cmbKokyaku.Location = New System.Drawing.Point(96, 56)
        Me.cmbKokyaku.Name = "cmbKokyaku"
        Me.cmbKokyaku.Size = New System.Drawing.Size(124, 23)
        Me.cmbKokyaku.TabIndex = 3
        '
        'Label4
        '
        Me.Label4.AutoSize = True
        Me.Label4.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.Label4.Location = New System.Drawing.Point(52, 60)
        Me.Label4.Name = "Label4"
        Me.Label4.Size = New System.Drawing.Size(36, 15)
        Me.Label4.TabIndex = 2
        Me.Label4.Text = "顧客"
        '
        'Label1
        '
        Me.Label1.AutoSize = True
        Me.Label1.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.Label1.Location = New System.Drawing.Point(52, 96)
        Me.Label1.Name = "Label1"
        Me.Label1.Size = New System.Drawing.Size(36, 15)
        Me.Label1.TabIndex = 4
        Me.Label1.Text = "納期"
        '
        'dtpNouki
        '
        Me.dtpNouki.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.dtpNouki.Format = System.Windows.Forms.DateTimePickerFormat.Short
        Me.dtpNouki.Location = New System.Drawing.Point(96, 92)
        Me.dtpNouki.Name = "dtpNouki"
        Me.dtpNouki.Size = New System.Drawing.Size(124, 22)
        Me.dtpNouki.TabIndex = 5
        '
        'Label2
        '
        Me.Label2.AutoSize = True
        Me.Label2.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.Label2.Location = New System.Drawing.Point(228, 96)
        Me.Label2.Name = "Label2"
        Me.Label2.Size = New System.Drawing.Size(58, 15)
        Me.Label2.TabIndex = 9
        Me.Label2.Text = "までの分"
        '
        'frmPickingList
        '
        Me.AutoScaleBaseSize = New System.Drawing.Size(5, 12)
        Me.ClientSize = New System.Drawing.Size(530, 187)
        Me.Controls.AddRange(New System.Windows.Forms.Control() {Me.Label2, Me.dtpNouki, Me.Label1, Me.cmbKokyaku, Me.Label4, Me.lblTitle, Me.GroupBox1, Me.btnPrint, Me.btnClose})
        Me.DockPadding.Top = 2
        Me.FormBorderStyle = System.Windows.Forms.FormBorderStyle.FixedSingle
        Me.MaximizeBox = False
        Me.MinimizeBox = False
        Me.Name = "frmPickingList"
        Me.ShowInTaskbar = False
        Me.StartPosition = System.Windows.Forms.FormStartPosition.CenterScreen
        Me.Text = "PickingList"
        Me.ResumeLayout(False)

    End Sub

#End Region

    '*** フォームロードイベント
    Private Sub frmPickingList_Load(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MyBase.Load
        Me.Text = Common.TITLE
        KokyakuSelect(cmbKokyaku)
    End Sub

    '*** [印刷]ボタンクリックイベント
    Private Sub btnPrint_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnPrint.Click
        Dim i As Integer

        '顧客選択チェック
        If Me.cmbKokyaku.SelectedIndex = 0 Then
            MsgBox("顧客を選択してください。", MsgBoxStyle.OKOnly Or MsgBoxStyle.Information, APPNAME)
            Me.cmbKokyaku.Focus()
            Return
        End If

        '該当納期取得
        Dim dtaNouki As New OleDb.OleDbDataAdapter( _
            "SELECT DISTINCT 納期 FROM 受注データ WHERE 受注データ.顧客コード=? AND 納期<=? AND ピッキングリスト出力=False", _
            dbConnection)
        dtaNouki.SelectCommand.Parameters.Add("顧客コード", Data.OleDb.OleDbType.VarWChar)
        dtaNouki.SelectCommand.Parameters.Add("納期", Data.OleDb.OleDbType.DBDate)
        dtaNouki.SelectCommand.Parameters("顧客コード").Value = cmbKokyaku.SelectedValue
        dtaNouki.SelectCommand.Parameters("納期").Value = dtpNouki.Text
        Dim dsNouki As New DataSet()
        dtaNouki.Fill(dsNouki)

        '受注データのパーツ展開データ取得
        Dim dtaParts As New OleDb.OleDbDataAdapter( _
            "SELECT 受注番号, 受注データ.商品名称, 受注数量, " & _
            " パーツ1, 数量1, パーツ2, 数量2, パーツ3, 数量3, パーツ4, 数量4, パーツ5, 数量5, " & _
            " パーツ6, 数量6, パーツ7, 数量7, パーツ8, 数量8, パーツ9, 数量9, パーツ10, 数量10," & _
            " パーツ11, 数量11, パーツ12, 数量12, パーツ13, 数量13, パーツ14, 数量14, パーツ15, 数量15" & _
            " FROM 受注データ" & _
            " INNER JOIN パーツ展開マスタ ON 受注データ.標準商品名称=パーツ展開マスタ.商品名称" & _
            " WHERE 受注データ.顧客コード=? AND 納期=? AND ピッキングリスト出力=False", _
            dbConnection)
        dtaParts.SelectCommand.Parameters.Add("顧客コード", Data.OleDb.OleDbType.VarWChar)
        dtaParts.SelectCommand.Parameters.Add("納期", Data.OleDb.OleDbType.DBDate)

        Dim aryJuchuNo As New ArrayList()
        Dim rowNouki As DataRow
        Dim dsWork1 As New dsWork()
        Dim rowPartsTenkai As dsWork.パーツ展開Row
        Dim dtPartsHikiete As dsWork.パーツ在庫引当DataTable
        Dim rowPartsHikiete As dsWork.パーツ在庫引当Row
        Dim dtPickingList As New dsWork.ピッキングリストDataTable()
        For Each rowNouki In dsNouki.Tables(0).Rows
            '受注データとパーツを読み込み
            dtaParts.SelectCommand.Parameters("顧客コード").Value = cmbKokyaku.SelectedValue
            dtaParts.SelectCommand.Parameters("納期").Value = rowNouki(0)
            dsWork1.パーツ展開.Clear()
            dtaParts.Fill(dsWork1, "パーツ展開")

            'パーツ展開処理
            dtPartsHikiete = PartsZaikoHikiate(dsWork1.パーツ展開)
            Dim dtvPartsHikiete As DataView = dtPartsHikiete.DefaultView
            dtvPartsHikiete.Sort = "パーツ名称"
            Dim count = dtPartsHikiete.DefaultView.Count - 1
            For i = 0 To count
                dsWork1.ピッキングリスト.AddピッキングリストRow(cmbKokyaku.Text, rowNouki(0), dtvPartsHikiete(i)("パーツ名称"), dtvPartsHikiete(i)("引当数"), Nothing)
            Next

            '後で印刷済みにするため受注番号を保存
            For Each rowPartsTenkai In dsWork1.パーツ展開.Rows
                aryJuchuNo.Add(rowPartsTenkai.受注番号)
            Next
        Next

        '備考取得
        Dim dbCmd As New OleDb.OleDbCommand( _
            "SELECT 備考 FROM パーツ在庫マスタ WHERE パーツ名称=?", _
            dbConnection)
        dbCmd.Parameters.Add("パーツ名称", Data.OleDb.OleDbType.VarWChar)
        Dim rowPickingList As dsWork.ピッキングリストRow
        Dim bikou As Object
        For Each rowPickingList In dsWork1.ピッキングリスト
            dbCmd.Parameters("パーツ名称").Value = rowPickingList.パーツ名称
            bikou = dbCmd.ExecuteScalar()
            If (Not bikou Is DBNull.Value) Then
                rowPickingList.備考 = bikou
            End If
        Next

        If dsWork1.Tables("ピッキングリスト").Rows.Count = 0 Then
            MsgBox("現在、印刷するデータはありません。", MsgBoxStyle.OKOnly Or MsgBoxStyle.Information)
            Return
        End If

        'プレビュー表示
        Dim rcPickingList As ReportClass = New PickingList()
        ShowPrintForm(lblTitle.Text, rcPickingList, dsWork1)

        '印刷済みにセット
        If MsgBox("ピッキングリストを印刷済みにしますか？", MsgBoxStyle.YesNo Or MsgBoxStyle.Question) = MsgBoxResult.Yes Then
            Dim dbcJuchuUpdate As New OleDb.OleDbCommand( _
                "UPDATE 受注データ SET ピッキングリスト出力=True WHERE 顧客コード=? AND 受注番号=?", _
                dbConnection)
            dbcJuchuUpdate.Parameters.Add("顧客コード", Data.OleDb.OleDbType.VarWChar)
            dbcJuchuUpdate.Parameters.Add("受注番号", Data.OleDb.OleDbType.VarWChar)

            dbcJuchuUpdate.Parameters("顧客コード").Value = cmbKokyaku.SelectedValue
            Dim juchuNo As String
            For Each juchuNo In aryJuchuNo
                dbcJuchuUpdate.Parameters("受注番号").Value = juchuNo
                dbcJuchuUpdate.ExecuteNonQuery()
            Next
        End If
    End Sub
End Class
