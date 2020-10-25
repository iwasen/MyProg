Public Class frmJuchuInput
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
    Friend WithEvents Label1 As System.Windows.Forms.Label
    Friend WithEvents Label3 As System.Windows.Forms.Label
    Friend WithEvents DataGridTableStyle1 As System.Windows.Forms.DataGridTableStyle
    Friend WithEvents btnOK As System.Windows.Forms.Button
    Friend WithEvents lblTitle As System.Windows.Forms.Label
    Friend WithEvents btnClose As System.Windows.Forms.Button
    Friend WithEvents cmbKokyaku As System.Windows.Forms.ComboBox
    Friend WithEvents dtgJuchu As System.Windows.Forms.DataGrid
    Friend WithEvents GroupBox1 As System.Windows.Forms.GroupBox
    Friend WithEvents dtpJuchuuBi As System.Windows.Forms.DateTimePicker
    Friend WithEvents dtbNouki As System.Windows.Forms.DateTimePicker
    Friend WithEvents Label2 As System.Windows.Forms.Label
    Friend WithEvents OleDbDataAdapter1 As System.Data.OleDb.OleDbDataAdapter
    Friend WithEvents OleDbInsertCommand1 As System.Data.OleDb.OleDbCommand
    Friend WithEvents 受注番号 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents 商品名称 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents 受注数量 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents DsWork1 As zaiko.dsWork
    <System.Diagnostics.DebuggerStepThrough()> Private Sub InitializeComponent()
        Me.lblTitle = New System.Windows.Forms.Label()
        Me.Label1 = New System.Windows.Forms.Label()
        Me.Label3 = New System.Windows.Forms.Label()
        Me.cmbKokyaku = New System.Windows.Forms.ComboBox()
        Me.dtgJuchu = New System.Windows.Forms.DataGrid()
        Me.DataGridTableStyle1 = New System.Windows.Forms.DataGridTableStyle()
        Me.受注番号 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.商品名称 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.受注数量 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.btnOK = New System.Windows.Forms.Button()
        Me.btnClose = New System.Windows.Forms.Button()
        Me.GroupBox1 = New System.Windows.Forms.GroupBox()
        Me.dtpJuchuuBi = New System.Windows.Forms.DateTimePicker()
        Me.dtbNouki = New System.Windows.Forms.DateTimePicker()
        Me.Label2 = New System.Windows.Forms.Label()
        Me.OleDbDataAdapter1 = New System.Data.OleDb.OleDbDataAdapter()
        Me.OleDbInsertCommand1 = New System.Data.OleDb.OleDbCommand()
        Me.DsWork1 = New zaiko.dsWork()
        CType(Me.dtgJuchu, System.ComponentModel.ISupportInitialize).BeginInit()
        CType(Me.DsWork1, System.ComponentModel.ISupportInitialize).BeginInit()
        Me.SuspendLayout()
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
        Me.lblTitle.Text = "受注データ入力"
        Me.lblTitle.TextAlign = System.Drawing.ContentAlignment.MiddleCenter
        '
        'Label1
        '
        Me.Label1.AutoSize = True
        Me.Label1.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.Label1.Location = New System.Drawing.Point(20, 52)
        Me.Label1.Name = "Label1"
        Me.Label1.Size = New System.Drawing.Size(52, 15)
        Me.Label1.TabIndex = 2
        Me.Label1.Text = "受注日"
        '
        'Label3
        '
        Me.Label3.AutoSize = True
        Me.Label3.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.Label3.Location = New System.Drawing.Point(348, 52)
        Me.Label3.Name = "Label3"
        Me.Label3.Size = New System.Drawing.Size(36, 15)
        Me.Label3.TabIndex = 6
        Me.Label3.Text = "顧客"
        '
        'cmbKokyaku
        '
        Me.cmbKokyaku.DropDownStyle = System.Windows.Forms.ComboBoxStyle.DropDownList
        Me.cmbKokyaku.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.cmbKokyaku.Location = New System.Drawing.Point(384, 48)
        Me.cmbKokyaku.Name = "cmbKokyaku"
        Me.cmbKokyaku.Size = New System.Drawing.Size(124, 23)
        Me.cmbKokyaku.TabIndex = 7
        '
        'dtgJuchu
        '
        Me.dtgJuchu.Anchor = (((System.Windows.Forms.AnchorStyles.Top Or System.Windows.Forms.AnchorStyles.Bottom) _
                    Or System.Windows.Forms.AnchorStyles.Left) _
                    Or System.Windows.Forms.AnchorStyles.Right)
        Me.dtgJuchu.CaptionVisible = False
        Me.dtgJuchu.DataMember = ""
        Me.dtgJuchu.DataSource = Me.DsWork1.受注明細
        Me.dtgJuchu.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.dtgJuchu.HeaderForeColor = System.Drawing.SystemColors.ControlText
        Me.dtgJuchu.ImeMode = System.Windows.Forms.ImeMode.Disable
        Me.dtgJuchu.Location = New System.Drawing.Point(24, 86)
        Me.dtgJuchu.Name = "dtgJuchu"
        Me.dtgJuchu.Size = New System.Drawing.Size(484, 238)
        Me.dtgJuchu.TabIndex = 0
        Me.dtgJuchu.TableStyles.AddRange(New System.Windows.Forms.DataGridTableStyle() {Me.DataGridTableStyle1})
        '
        'DataGridTableStyle1
        '
        Me.DataGridTableStyle1.AllowSorting = False
        Me.DataGridTableStyle1.AlternatingBackColor = System.Drawing.Color.FromArgb(CType(224, Byte), CType(255, Byte), CType(224, Byte))
        Me.DataGridTableStyle1.DataGrid = Me.dtgJuchu
        Me.DataGridTableStyle1.GridColumnStyles.AddRange(New System.Windows.Forms.DataGridColumnStyle() {Me.受注番号, Me.商品名称, Me.受注数量})
        Me.DataGridTableStyle1.HeaderForeColor = System.Drawing.SystemColors.ControlText
        Me.DataGridTableStyle1.MappingName = "受注明細"
        '
        '受注番号
        '
        Me.受注番号.Format = ""
        Me.受注番号.FormatInfo = Nothing
        Me.受注番号.HeaderText = "受注番号"
        Me.受注番号.MappingName = "受注番号"
        Me.受注番号.NullText = ""
        Me.受注番号.Width = 150
        '
        '商品名称
        '
        Me.商品名称.Format = ""
        Me.商品名称.FormatInfo = Nothing
        Me.商品名称.HeaderText = "商品名称"
        Me.商品名称.MappingName = "商品名称"
        Me.商品名称.NullText = ""
        Me.商品名称.Width = 200
        '
        '受注数量
        '
        Me.受注数量.Alignment = System.Windows.Forms.HorizontalAlignment.Right
        Me.受注数量.Format = ""
        Me.受注数量.FormatInfo = Nothing
        Me.受注数量.HeaderText = "受注数量"
        Me.受注数量.MappingName = "受注数量"
        Me.受注数量.NullText = ""
        Me.受注数量.Width = 75
        '
        'btnOK
        '
        Me.btnOK.Anchor = System.Windows.Forms.AnchorStyles.Bottom
        Me.btnOK.Location = New System.Drawing.Point(165, 352)
        Me.btnOK.Name = "btnOK"
        Me.btnOK.Size = New System.Drawing.Size(92, 24)
        Me.btnOK.TabIndex = 9
        Me.btnOK.Text = "OK"
        '
        'btnClose
        '
        Me.btnClose.Anchor = System.Windows.Forms.AnchorStyles.Bottom
        Me.btnClose.Location = New System.Drawing.Point(273, 352)
        Me.btnClose.Name = "btnClose"
        Me.btnClose.Size = New System.Drawing.Size(92, 24)
        Me.btnClose.TabIndex = 10
        Me.btnClose.Text = "閉じる(&C)"
        '
        'GroupBox1
        '
        Me.GroupBox1.Anchor = ((System.Windows.Forms.AnchorStyles.Bottom Or System.Windows.Forms.AnchorStyles.Left) _
                    Or System.Windows.Forms.AnchorStyles.Right)
        Me.GroupBox1.Location = New System.Drawing.Point(8, 336)
        Me.GroupBox1.Name = "GroupBox1"
        Me.GroupBox1.Size = New System.Drawing.Size(512, 4)
        Me.GroupBox1.TabIndex = 8
        Me.GroupBox1.TabStop = False
        '
        'dtpJuchuuBi
        '
        Me.dtpJuchuuBi.CalendarFont = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.dtpJuchuuBi.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.dtpJuchuuBi.Format = System.Windows.Forms.DateTimePickerFormat.Short
        Me.dtpJuchuuBi.Location = New System.Drawing.Point(72, 48)
        Me.dtpJuchuuBi.Name = "dtpJuchuuBi"
        Me.dtpJuchuuBi.Size = New System.Drawing.Size(108, 22)
        Me.dtpJuchuuBi.TabIndex = 3
        '
        'dtbNouki
        '
        Me.dtbNouki.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.dtbNouki.Format = System.Windows.Forms.DateTimePickerFormat.Short
        Me.dtbNouki.Location = New System.Drawing.Point(228, 48)
        Me.dtbNouki.Name = "dtbNouki"
        Me.dtbNouki.Size = New System.Drawing.Size(108, 22)
        Me.dtbNouki.TabIndex = 5
        '
        'Label2
        '
        Me.Label2.AutoSize = True
        Me.Label2.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.Label2.Location = New System.Drawing.Point(192, 52)
        Me.Label2.Name = "Label2"
        Me.Label2.Size = New System.Drawing.Size(36, 15)
        Me.Label2.TabIndex = 4
        Me.Label2.Text = "納期"
        '
        'OleDbDataAdapter1
        '
        Me.OleDbDataAdapter1.InsertCommand = Me.OleDbInsertCommand1
        Me.OleDbDataAdapter1.TableMappings.AddRange(New System.Data.Common.DataTableMapping() {New System.Data.Common.DataTableMapping("Table", "受注明細", New System.Data.Common.DataColumnMapping() {New System.Data.Common.DataColumnMapping("顧客コード", "顧客コード"), New System.Data.Common.DataColumnMapping("受注番号", "受注番号"), New System.Data.Common.DataColumnMapping("受注日", "受注日"), New System.Data.Common.DataColumnMapping("納期", "納期"), New System.Data.Common.DataColumnMapping("商品名称", "商品名称"), New System.Data.Common.DataColumnMapping("受注数量", "受注数量")})})
        '
        'OleDbInsertCommand1
        '
        Me.OleDbInsertCommand1.CommandText = "INSERT INTO [受注データ] ([顧客コード], 受注番号, 受注日, 納期, 商品名称, 受注数量, 標準商品名称) VALUES (?, ?, ?," & _
        " ?, ?, ?, ?)"
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("顧客コード", System.Data.OleDb.OleDbType.VarWChar, 20, "顧客コード"))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("受注番号", System.Data.OleDb.OleDbType.VarWChar, 15, "受注番号"))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("受注日", System.Data.OleDb.OleDbType.DBDate, 0, "受注日"))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("納期", System.Data.OleDb.OleDbType.DBDate, 0, "納期"))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("商品名称", System.Data.OleDb.OleDbType.VarWChar, 12, "商品名称"))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("受注数量", System.Data.OleDb.OleDbType.Integer, 0, System.Data.ParameterDirection.Input, False, CType(10, Byte), CType(0, Byte), "受注数量", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("標準商品名称", System.Data.OleDb.OleDbType.VarWChar, 12, "標準商品名称"))
        '
        'DsWork1
        '
        Me.DsWork1.DataSetName = "dsWork"
        Me.DsWork1.Locale = New System.Globalization.CultureInfo("en-US")
        Me.DsWork1.Namespace = "http://tempuri.org/Work.xsd"
        '
        'frmJuchuInput
        '
        Me.AutoScaleBaseSize = New System.Drawing.Size(5, 12)
        Me.ClientSize = New System.Drawing.Size(530, 385)
        Me.Controls.AddRange(New System.Windows.Forms.Control() {Me.dtbNouki, Me.Label2, Me.Label3, Me.Label1, Me.dtpJuchuuBi, Me.GroupBox1, Me.btnOK, Me.dtgJuchu, Me.cmbKokyaku, Me.lblTitle, Me.btnClose})
        Me.DockPadding.Top = 2
        Me.MinimizeBox = False
        Me.Name = "frmJuchuInput"
        Me.ShowInTaskbar = False
        Me.StartPosition = System.Windows.Forms.FormStartPosition.CenterScreen
        Me.Text = "JuchuInput"
        CType(Me.dtgJuchu, System.ComponentModel.ISupportInitialize).EndInit()
        CType(Me.DsWork1, System.ComponentModel.ISupportInitialize).EndInit()
        Me.ResumeLayout(False)

    End Sub

#End Region

    '*** フォームロードイベント
    Private Sub JuchuInput_Load(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MyBase.Load
        'タイトル設定
        Me.Text = Common.TITLE

        '顧客選択リスト設定
        KokyakuSelect(cmbKokyaku)

        'DBコネクション設定
        SetDbConnection(OleDbDataAdapter1)

        'テーブル用イベントハンドラ設定
        AddHandler DsWork1.受注明細.ColumnChanged, AddressOf DsWork1_Column_Changed

        With dtgJuchu.TableStyles(0)
            '最大入力文字数設定
            CType(.GridColumnStyles("受注番号"), DataGridTextBoxColumn).TextBox.MaxLength = 15
            CType(.GridColumnStyles("商品名称"), DataGridTextBoxColumn).TextBox.MaxLength = 12
            CType(.GridColumnStyles("受注数量"), DataGridTextBoxColumn).TextBox.MaxLength = 5

            '大文字のみ入力させる
            CType(.GridColumnStyles("商品名称"), DataGridTextBoxColumn).TextBox.CharacterCasing = CharacterCasing.Upper

            'キー入力制限用イベントハンドラ設定
            AddHandler CType(.GridColumnStyles("受注番号"), DataGridTextBoxColumn).TextBox.KeyPress, AddressOf KeyPress_NumOnly
            AddHandler CType(.GridColumnStyles("商品名称"), DataGridTextBoxColumn).TextBox.KeyPress, AddressOf KeyPress_ShouhinMeishou
            AddHandler CType(.GridColumnStyles("受注数量"), DataGridTextBoxColumn).TextBox.KeyPress, AddressOf KeyPress_NumOnly
        End With
    End Sub

    '*** [OK]ボタンクリックイベント
    Private Sub btnOK_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnOK.Click
        '顧客選択有無チェック
        If Me.cmbKokyaku.SelectedIndex = 0 Then
            MsgBox("顧客を選択してください。", MsgBoxStyle.OKOnly Or MsgBoxStyle.Information, APPNAME)
            Me.cmbKokyaku.Focus()
            Return
        End If

        '受注データ入力有無チェック
        If DsWork1.受注明細.Count = 0 Then
            MsgBox("受注データを入力してください。", MsgBoxStyle.OKOnly Or MsgBoxStyle.Information, APPNAME)
            Me.dtgJuchu.Focus()
            Return
        End If

        '受注番号チェック用SQL
        Dim dbcJuchuSelect As New OleDb.OleDbCommand()
        dbcJuchuSelect.Connection = Common.dbConnection
        dbcJuchuSelect.CommandType = CommandType.Text
        dbcJuchuSelect.CommandText = "SELECT COUNT(*) FROM 受注データ WHERE 顧客コード=? AND 受注番号=?"
        dbcJuchuSelect.Parameters.Add("顧客コード", Data.OleDb.OleDbType.VarWChar)
        dbcJuchuSelect.Parameters.Add("受注番号", Data.OleDb.OleDbType.VarWChar)

        '受注入力データチェック
        Dim errFlag As Boolean
        Dim errExist As Boolean = False
        Dim errList As String
        Dim row, row2 As dsWork.受注明細Row
        Dim shouhin As String
        For Each row In DsWork1.受注明細.Rows
            errFlag = False

            '受注番号入力チェック
            If row.Is受注番号Null Then
                row.SetColumnError(0, "受注番号が入力されていません。")
                errFlag = True
            Else
                '受注番号重複チェック（入力）
                For Each row2 In DsWork1.受注明細.Rows
                    If row Is row2 Then
                        Exit For
                    End If
                    If row("受注番号") = row2("受注番号") Then
                        row.SetColumnError(0, "この受注番号はすでに入力されています。")
                        errFlag = True
                    End If
                Next

                '受注番号重複チェック（ＤＢ）
                dbcJuchuSelect.Parameters("顧客コード").Value = Me.cmbKokyaku.SelectedValue
                dbcJuchuSelect.Parameters("受注番号").Value = row.受注番号
                If CType(dbcJuchuSelect.ExecuteScalar(), Integer) <> 0 Then
                    row.SetColumnError(0, "この受注番号はすでに登録されています。")
                    errFlag = True
                End If
            End If

            '商品名称入力チェック
            If row.Is商品名称Null Then
                row.SetColumnError(1, "商品名称が入力されていません。")
                errFlag = True
            Else
                '商品名称存在チェック
                shouhin = ShouhinCheck(row.商品名称)
                If shouhin Is Nothing Then
                    row.SetColumnError(1, "この商品名称は登録されていません。")
                    errFlag = True
                Else
                    row.標準商品名称 = shouhin
                End If
            End If

            '受注数量チェック
            If row.Is受注数量Null OrElse CType(row.受注数量, Integer) <= 0 Then
                row.SetColumnError(2, "受注数量が入力されていません。")
                errFlag = True
            End If

            'エラーメッセージ
            If errFlag Then
                errList &= "受注番号：" & row("受注番号") & vbCrLf
                errList &= "商品名称：" & row("商品名称") & vbCrLf & vbCrLf
                errExist = True
            End If
        Next

        'エラーがあったらリターン
        If errExist Then
            MsgBox("受注データにエラーがありました。" & vbCrLf & vbCrLf & _
                errList _
                , MsgBoxStyle.OKOnly Or MsgBoxStyle.Information, APPNAME)
            Return
        End If

        '登録確認
        If MsgBox("受注データを登録します。よろしいですか？", MsgBoxStyle.OKCancel Or MsgBoxStyle.Question, APPNAME) = MsgBoxResult.Cancel Then
            Return
        End If

        '受注日、納期、顧客コードをセット
        For Each row In DsWork1.受注明細.Rows
            row.受注日 = Me.dtpJuchuuBi.Value
            row.納期 = Me.dtbNouki.Value
            row.顧客コード = Me.cmbKokyaku.SelectedValue
        Next

        '受注データテーブルに追加
        Try
            OleDbDataAdapter1.Update(DsWork1)
        Catch ex As Exception
            MsgBox("受注入力の登録でエラーが発生しました。" & vbCrLf & vbCrLf & ex.Message, MsgBoxStyle.OKOnly Or MsgBoxStyle.Exclamation, APPNAME)
            Return
        End Try

        '完了確認メッセージ
        MsgBox("受注データの登録が完了しました。", MsgBoxStyle.OKOnly Or MsgBoxStyle.Information, APPNAME)

        '続けて入力する場合に備えてデータクリア
        DsWork1.Clear()
    End Sub

    '*** [閉じる]ボタンクリックイベント
    Private Sub btnClose_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnClose.Click
        '受注データ入力有無チェック
        If DsWork1.受注明細.Count > 0 Then
            If MsgBox("受注データが入力されています。登録しないで閉じてよろしいですか？", MsgBoxStyle.OKCancel Or MsgBoxStyle.Question, APPNAME) = MsgBoxResult.Cancel Then
                Return
            End If
        End If
        Me.DialogResult = DialogResult.Cancel
    End Sub

    '*** データグリッドカラム変更イベント
    Private Sub DsWork1_Column_Changed(ByVal sender As Object, ByVal e As DataColumnChangeEventArgs)
        'エラー状態をクリア
        e.Row.SetColumnError(e.Column, Nothing)
    End Sub
End Class
