Public Class frmTanaoroshi
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
    Friend WithEvents btnDisplay As System.Windows.Forms.Button
    Friend WithEvents DataGridTableStyle1 As System.Windows.Forms.DataGridTableStyle
    Friend WithEvents cmbPartsShubetsu As System.Windows.Forms.ComboBox
    Friend WithEvents btnOK As System.Windows.Forms.Button
    Friend WithEvents btnClose As System.Windows.Forms.Button
    Friend WithEvents dtgTanaoroshi As MyDataGrid
    Friend WithEvents GroupBox1 As System.Windows.Forms.GroupBox
    Friend WithEvents OleDbDataAdapter1 As System.Data.OleDb.OleDbDataAdapter
    Friend WithEvents OleDbSelectCommand1 As System.Data.OleDb.OleDbCommand
    Friend WithEvents DsWork1 As zaiko.dsWork
    Friend WithEvents パーツ名称 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents 現在庫数 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents 実棚卸数 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents 差異 As System.Windows.Forms.DataGridTextBoxColumn
    <System.Diagnostics.DebuggerStepThrough()> Private Sub InitializeComponent()
        Me.lblTitle = New System.Windows.Forms.Label()
        Me.Label1 = New System.Windows.Forms.Label()
        Me.cmbPartsShubetsu = New System.Windows.Forms.ComboBox()
        Me.btnDisplay = New System.Windows.Forms.Button()
        Me.dtgTanaoroshi = New zaiko.MyDataGrid()
        Me.DsWork1 = New zaiko.dsWork()
        Me.DataGridTableStyle1 = New System.Windows.Forms.DataGridTableStyle()
        Me.パーツ名称 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.現在庫数 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.実棚卸数 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.差異 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.btnOK = New System.Windows.Forms.Button()
        Me.btnClose = New System.Windows.Forms.Button()
        Me.GroupBox1 = New System.Windows.Forms.GroupBox()
        Me.OleDbDataAdapter1 = New System.Data.OleDb.OleDbDataAdapter()
        Me.OleDbSelectCommand1 = New System.Data.OleDb.OleDbCommand()
        CType(Me.dtgTanaoroshi, System.ComponentModel.ISupportInitialize).BeginInit()
        CType(Me.DsWork1, System.ComponentModel.ISupportInitialize).BeginInit()
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
        Me.lblTitle.Text = "棚卸入力"
        Me.lblTitle.TextAlign = System.Drawing.ContentAlignment.MiddleCenter
        '
        'Label1
        '
        Me.Label1.AutoSize = True
        Me.Label1.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.Label1.Location = New System.Drawing.Point(28, 52)
        Me.Label1.Name = "Label1"
        Me.Label1.Size = New System.Drawing.Size(73, 15)
        Me.Label1.TabIndex = 2
        Me.Label1.Text = "パーツ種別"
        '
        'cmbPartsShubetsu
        '
        Me.cmbPartsShubetsu.DisplayMember = "パーツ種別コード"
        Me.cmbPartsShubetsu.DropDownStyle = System.Windows.Forms.ComboBoxStyle.DropDownList
        Me.cmbPartsShubetsu.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.cmbPartsShubetsu.Location = New System.Drawing.Point(108, 48)
        Me.cmbPartsShubetsu.Name = "cmbPartsShubetsu"
        Me.cmbPartsShubetsu.Size = New System.Drawing.Size(176, 23)
        Me.cmbPartsShubetsu.TabIndex = 3
        '
        'btnDisplay
        '
        Me.btnDisplay.Enabled = False
        Me.btnDisplay.Location = New System.Drawing.Point(300, 48)
        Me.btnDisplay.Name = "btnDisplay"
        Me.btnDisplay.Size = New System.Drawing.Size(88, 24)
        Me.btnDisplay.TabIndex = 4
        Me.btnDisplay.Text = "表示(&D)"
        '
        'dtgTanaoroshi
        '
        Me.dtgTanaoroshi.Anchor = (((System.Windows.Forms.AnchorStyles.Top Or System.Windows.Forms.AnchorStyles.Bottom) _
                    Or System.Windows.Forms.AnchorStyles.Left) _
                    Or System.Windows.Forms.AnchorStyles.Right)
        Me.dtgTanaoroshi.CaptionVisible = False
        Me.dtgTanaoroshi.DataMember = ""
        Me.dtgTanaoroshi.DataSource = Me.DsWork1.棚卸入力
        Me.dtgTanaoroshi.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.dtgTanaoroshi.HeaderForeColor = System.Drawing.SystemColors.ControlText
        Me.dtgTanaoroshi.ImeMode = System.Windows.Forms.ImeMode.Disable
        Me.dtgTanaoroshi.Location = New System.Drawing.Point(24, 86)
        Me.dtgTanaoroshi.Name = "dtgTanaoroshi"
        Me.dtgTanaoroshi.Size = New System.Drawing.Size(484, 364)
        Me.dtgTanaoroshi.TabIndex = 5
        Me.dtgTanaoroshi.TableStyles.AddRange(New System.Windows.Forms.DataGridTableStyle() {Me.DataGridTableStyle1})
        '
        'DsWork1
        '
        Me.DsWork1.DataSetName = "dsWork"
        Me.DsWork1.Locale = New System.Globalization.CultureInfo("ja-JP")
        Me.DsWork1.Namespace = "http://www.tempuri.org/dsZaiko.xsd"
        '
        'DataGridTableStyle1
        '
        Me.DataGridTableStyle1.AlternatingBackColor = System.Drawing.Color.FromArgb(CType(224, Byte), CType(255, Byte), CType(224, Byte))
        Me.DataGridTableStyle1.DataGrid = Me.dtgTanaoroshi
        Me.DataGridTableStyle1.GridColumnStyles.AddRange(New System.Windows.Forms.DataGridColumnStyle() {Me.パーツ名称, Me.現在庫数, Me.実棚卸数, Me.差異})
        Me.DataGridTableStyle1.HeaderForeColor = System.Drawing.SystemColors.ControlText
        Me.DataGridTableStyle1.MappingName = "棚卸入力"
        '
        'パーツ名称
        '
        Me.パーツ名称.Format = ""
        Me.パーツ名称.FormatInfo = Nothing
        Me.パーツ名称.HeaderText = "パーツ名称"
        Me.パーツ名称.MappingName = "パーツ名称"
        Me.パーツ名称.NullText = ""
        Me.パーツ名称.ReadOnly = True
        Me.パーツ名称.Width = 185
        '
        '現在庫数
        '
        Me.現在庫数.Alignment = System.Windows.Forms.HorizontalAlignment.Right
        Me.現在庫数.Format = ""
        Me.現在庫数.FormatInfo = Nothing
        Me.現在庫数.HeaderText = "現在庫数"
        Me.現在庫数.MappingName = "現在庫数"
        Me.現在庫数.NullText = ""
        Me.現在庫数.ReadOnly = True
        Me.現在庫数.Width = 80
        '
        '実棚卸数
        '
        Me.実棚卸数.Alignment = System.Windows.Forms.HorizontalAlignment.Right
        Me.実棚卸数.Format = ""
        Me.実棚卸数.FormatInfo = Nothing
        Me.実棚卸数.HeaderText = "実棚卸数"
        Me.実棚卸数.MappingName = "実棚卸数"
        Me.実棚卸数.NullText = ""
        Me.実棚卸数.Width = 80
        '
        '差異
        '
        Me.差異.Alignment = System.Windows.Forms.HorizontalAlignment.Right
        Me.差異.Format = "+0;-0;0"
        Me.差異.FormatInfo = Nothing
        Me.差異.HeaderText = "差異"
        Me.差異.MappingName = "差異"
        Me.差異.NullText = ""
        Me.差異.ReadOnly = True
        Me.差異.Width = 80
        '
        'btnOK
        '
        Me.btnOK.Anchor = System.Windows.Forms.AnchorStyles.Bottom
        Me.btnOK.Location = New System.Drawing.Point(165, 472)
        Me.btnOK.Name = "btnOK"
        Me.btnOK.Size = New System.Drawing.Size(92, 24)
        Me.btnOK.TabIndex = 6
        Me.btnOK.Text = "OK"
        '
        'btnClose
        '
        Me.btnClose.Anchor = System.Windows.Forms.AnchorStyles.Bottom
        Me.btnClose.Location = New System.Drawing.Point(273, 472)
        Me.btnClose.Name = "btnClose"
        Me.btnClose.Size = New System.Drawing.Size(92, 24)
        Me.btnClose.TabIndex = 7
        Me.btnClose.Text = "閉じる(&C)"
        '
        'GroupBox1
        '
        Me.GroupBox1.Anchor = ((System.Windows.Forms.AnchorStyles.Bottom Or System.Windows.Forms.AnchorStyles.Left) _
                    Or System.Windows.Forms.AnchorStyles.Right)
        Me.GroupBox1.Location = New System.Drawing.Point(7, 458)
        Me.GroupBox1.Name = "GroupBox1"
        Me.GroupBox1.Size = New System.Drawing.Size(516, 4)
        Me.GroupBox1.TabIndex = 11
        Me.GroupBox1.TabStop = False
        '
        'OleDbDataAdapter1
        '
        Me.OleDbDataAdapter1.SelectCommand = Me.OleDbSelectCommand1
        Me.OleDbDataAdapter1.TableMappings.AddRange(New System.Data.Common.DataTableMapping() {New System.Data.Common.DataTableMapping("Table", "棚卸入力", New System.Data.Common.DataColumnMapping() {New System.Data.Common.DataColumnMapping("パーツ名称", "パーツ名称"), New System.Data.Common.DataColumnMapping("現在庫数", "実棚卸数"), New System.Data.Common.DataColumnMapping("棚卸した日", "棚卸した日")})})
        '
        'OleDbSelectCommand1
        '
        Me.OleDbSelectCommand1.CommandText = "SELECT パーツ種別, パーツ名称, 欠品になった日, 現在庫数, 製造区分, 棚卸した日, 発注点数量 FROM パーツ在庫マスタ WHERE (パーツ種別" & _
        " = ?) ORDER BY パーツ名称"
        Me.OleDbSelectCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("パーツ種別", System.Data.OleDb.OleDbType.VarWChar, 3, "パーツ種別"))
        '
        'frmTanaoroshi
        '
        Me.AutoScaleBaseSize = New System.Drawing.Size(5, 12)
        Me.ClientSize = New System.Drawing.Size(530, 505)
        Me.Controls.AddRange(New System.Windows.Forms.Control() {Me.GroupBox1, Me.btnOK, Me.btnClose, Me.dtgTanaoroshi, Me.btnDisplay, Me.cmbPartsShubetsu, Me.Label1, Me.lblTitle})
        Me.DockPadding.Top = 2
        Me.MinimizeBox = False
        Me.Name = "frmTanaoroshi"
        Me.ShowInTaskbar = False
        Me.StartPosition = System.Windows.Forms.FormStartPosition.CenterScreen
        Me.Text = "Tanaoroshi"
        CType(Me.dtgTanaoroshi, System.ComponentModel.ISupportInitialize).EndInit()
        CType(Me.DsWork1, System.ComponentModel.ISupportInitialize).EndInit()
        Me.ResumeLayout(False)

    End Sub

#End Region

    '*** フォームロードイベント
    Private Sub Tanaoroshi_Load(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MyBase.Load
        Me.Text = Common.TITLE
        PartsShubetsuSelect(cmbPartsShubetsu)

        SetDbConnection(OleDbDataAdapter1)

        DsWork1.棚卸入力.DefaultView.AllowNew = False
        DsWork1.棚卸入力.DefaultView.AllowDelete = False

        With dtgTanaoroshi.TableStyles(0)
            '最大入力文字数設定
            CType(.GridColumnStyles("実棚卸数"), DataGridTextBoxColumn).TextBox.MaxLength = 5

            'キー入力制限用イベントハンドラ設定
            AddHandler CType(.GridColumnStyles("実棚卸数"), DataGridTextBoxColumn).TextBox.KeyPress, AddressOf KeyPress_NumOnly
        End With
    End Sub

    '*** [閉じる]ボタンクリックイベント
    Private Sub btnClose_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnClose.Click
        'データ変更チェック
        If CheckTableUpdate(DsWork1.棚卸入力) Then
            If MsgBox("データが変更されています。登録しないで閉じてよろしいですか？", MsgBoxStyle.OKCancel Or MsgBoxStyle.Question, APPNAME) = MsgBoxResult.Cancel Then
                Return
            End If
        End If
        Me.DialogResult = DialogResult.Cancel
    End Sub

    '*** [表示]ボタンクリックイベント
    Private Sub btnDisplay_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnDisplay.Click
        Dim table As DataTable = DsWork1.棚卸入力

        'データ変更チェック
        If CheckTableUpdate(DsWork1.棚卸入力) Then
            If MsgBox("データが変更されています。登録しないでデータを表示してよろしいですか？", MsgBoxStyle.OKCancel Or MsgBoxStyle.Question, APPNAME) = MsgBoxResult.Cancel Then
                Return
            End If
        End If

        RemoveHandler table.ColumnChanging, AddressOf DsWork1_Column_Changed

        DsWork1.棚卸入力.Clear()
        OleDbDataAdapter1.SelectCommand.Parameters("パーツ種別").Value = Me.cmbPartsShubetsu.SelectedValue
        OleDbDataAdapter1.Fill(DsWork1)

        '実棚卸数と差異をセット
        Dim row As dsWork.棚卸入力Row
        For Each row In DsWork1.棚卸入力.Rows
            row.現在庫数 = row.実棚卸数
            row.差異 = 0
        Next
        table.AcceptChanges()

        AddHandler table.ColumnChanged, New DataColumnChangeEventHandler(AddressOf DsWork1_Column_Changed)
    End Sub

    '*** データグリッドフォーカス取得イベント
    Private Sub dtgTanaoroshi_Enter(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles dtgTanaoroshi.Enter
        dtgTanaoroshi.CurrentCell = New DataGridCell(0, 2)
    End Sub

    '*** パーツ種別選択イベント
    Private Sub cmbPartsShubetsu_SelectedIndexChanged(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles cmbPartsShubetsu.SelectedIndexChanged
        If cmbPartsShubetsu.SelectedValue = "" Then
            Me.btnDisplay.Enabled = False
        Else
            Me.btnDisplay.Enabled = True
        End If
    End Sub

    '*** [OK]ボタンクリックイベント
    Private Sub btnOK_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnOK.Click
        'パーツ種別選択チェック
        If cmbPartsShubetsu.SelectedIndex = 0 Then
            MsgBox("パーツ種別を選択してください。", MsgBoxStyle.OKOnly Or MsgBoxStyle.Information, APPNAME)
            cmbPartsShubetsu.Focus()
            Return
        End If

        '表示確認
        If DsWork1.棚卸入力.Rows.Count = 0 Then
            MsgBox("データが表示されていません。", MsgBoxStyle.OKOnly Or MsgBoxStyle.Information, APPNAME)
            btnDisplay.Focus()
            Return
        End If

        '登録確認
        If MsgBox("棚卸入力を登録します。よろしいですか？", MsgBoxStyle.OKCancel Or MsgBoxStyle.Question, APPNAME) = MsgBoxResult.Cancel Then
            Return
        End If

        'トランザクション作成
        Dim trans As OleDb.OleDbTransaction
        trans = dbConnection.BeginTransaction()

        'パーツ在庫マスタ更新SQL
        Dim dbcZaikoUpdate As New OleDb.OleDbCommand("UPDATE パーツ在庫マスタ SET 現在庫数=?, 棚卸した日=? WHERE パーツ名称=?", dbConnection, trans)
        dbcZaikoUpdate.Parameters.Add("現在庫数", Data.OleDb.OleDbType.Integer)
        dbcZaikoUpdate.Parameters.Add("棚卸した日", Data.OleDb.OleDbType.DBDate)
        dbcZaikoUpdate.Parameters.Add("パーツ名称", Data.OleDb.OleDbType.VarWChar)

        '発注点警告日設定SQL
        Dim dbcKeikokuUpdate As New OleDb.OleDbCommand("UPDATE パーツ在庫マスタ SET 発注点警告日=? WHERE パーツ名称=? AND 現在庫数 < 発注点数量 AND 発注点警告日 IS NULL", dbConnection, trans)
        dbcKeikokuUpdate.Parameters.Add("発注点警告日", Data.OleDb.OleDbType.DBDate)
        dbcKeikokuUpdate.Parameters.Add("パーツ名称", Data.OleDb.OleDbType.VarWChar)

        '在庫変更履歴追加SQL
        Dim dbcRirekiInert As New OleDb.OleDbCommand("INSERT INTO 在庫変更履歴 (パーツ名称, 処理日時, 入出庫区分, 数量) VALUES(?, ?, ?, ?)", dbConnection, trans)
        dbcRirekiInert.Parameters.Add("パーツ名称", Data.OleDb.OleDbType.VarWChar)
        dbcRirekiInert.Parameters.Add("処理日時", Data.OleDb.OleDbType.DBDate)
        dbcRirekiInert.Parameters.Add("入出庫区分", Data.OleDb.OleDbType.VarWChar)
        dbcRirekiInert.Parameters.Add("数量", Data.OleDb.OleDbType.Integer)

        'DB更新
        Try
            'パーツ在庫マスタ更新
            Dim row As dsWork.棚卸入力Row
            Dim dtNow As DateTime = Now
            For Each row In DsWork1.棚卸入力.Rows
                If Not row.Is実棚卸数Null Then
                    '現在庫数更新
                    dbcZaikoUpdate.Parameters("パーツ名称").Value = row.パーツ名称
                    dbcZaikoUpdate.Parameters("現在庫数").Value = row.実棚卸数
                    dbcZaikoUpdate.Parameters("棚卸した日").Value = dtNow
                    dbcZaikoUpdate.ExecuteNonQuery()

                    '発注点警告チェック
                    dbcKeikokuUpdate.Parameters("パーツ名称").Value = row.パーツ名称
                    dbcKeikokuUpdate.Parameters("発注点警告日").Value = dtNow
                    dbcKeikokuUpdate.ExecuteNonQuery()
                End If
            Next

            '在庫変更履歴更新
            Dim kubun As String
            For Each row In DsWork1.棚卸入力.Rows
                If row.差異 <> 0 Then
                    If row.差異 > 0 Then
                        kubun = "08"
                    Else
                        kubun = "09"
                    End If

                    dbcRirekiInert.Parameters("パーツ名称").Value = row.パーツ名称
                    dbcRirekiInert.Parameters("処理日時").Value = dtNow
                    dbcRirekiInert.Parameters("入出庫区分").Value = kubun
                    dbcRirekiInert.Parameters("数量").Value = row.差異
                    dbcRirekiInert.ExecuteNonQuery()
                End If
            Next

            trans.Commit()
        Catch ex As Exception
            trans.Rollback()
            MsgBox("棚卸入力の登録でエラーが発生しました。" & vbCrLf & vbCrLf & ex.Message, MsgBoxStyle.OKOnly Or MsgBoxStyle.Exclamation, APPNAME)
            Return
        End Try

        '完了メッセージ
        MsgBox("棚卸入力の登録が完了しました。", MsgBoxStyle.OKOnly Or MsgBoxStyle.Information, APPNAME)

        '続けて入力に備えてデータクリア
        Me.cmbPartsShubetsu.SelectedIndex = 0
        DsWork1.Clear()
    End Sub

    '*** データグリッドカラム変更イベント
    Private Sub DsWork1_Column_Changed(ByVal sender As Object, ByVal e As DataColumnChangeEventArgs)
        Dim table As dsWork.棚卸入力DataTable = sender
        Select Case e.Column.ColumnName
            Case "実棚卸数"
                If e.ProposedValue Is DBNull.Value Then
                    e.Row("差異") = 0
                Else
                    e.Row("差異") = e.ProposedValue - e.Row("現在庫数")
                End If
        End Select
    End Sub
End Class
