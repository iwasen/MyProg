Public Class PartsZaiko
    Inherits System.Windows.Forms.Form

#Region " Windows フォーム デザイナで生成されたコード "

    Public Sub New()
        MyBase.New()

        ' この呼び出しは Windows フォーム デザイナで必要です。
        InitializeComponent()

        ' InitializeComponent() 呼び出しの後に初期化を追加します。

        'アダプタにDBコネクション設定
        SetDbConnection(OleDbDataAdapter1)
        SetDbConnection(OleDbDataAdapter2)

        製造区分.AddList("", "")
        製造区分.AddList("製造分", "1")
        製造区分.AddList("発注分", "2")

        OleDbDataAdapter2.Fill(DsTable1)
        パーツ種別.ColumnComboBox.DataSource = DsTable1.パーツ種別マスタ.DefaultView
        パーツ種別.ColumnComboBox.DisplayMember = "パーツ種別名"
        パーツ種別.ColumnComboBox.ValueMember = "パーツ種別コード"
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
    Friend WithEvents btnOK As System.Windows.Forms.Button
    Friend WithEvents btnCancel As System.Windows.Forms.Button
    Friend WithEvents dtgMaster As System.Windows.Forms.DataGrid
    Friend WithEvents GroupBox1 As System.Windows.Forms.GroupBox
    Friend WithEvents DsTable1 As zaiko.dsTable
    Friend WithEvents DataGridTableStyle1 As System.Windows.Forms.DataGridTableStyle
    Friend WithEvents OleDbDataAdapter2 As System.Data.OleDb.OleDbDataAdapter
    Friend WithEvents OleDbSelectCommand2 As System.Data.OleDb.OleDbCommand
    Friend WithEvents パーツ名称 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents 製造区分 As zaiko.DataGridTextBoxCombo.DataGridComboBoxColumn
    Friend WithEvents パーツ種別 As zaiko.DataGridTextBoxCombo.DataGridComboBoxColumn
    Friend WithEvents 現在庫数 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents 発注点数量 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents 備考 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents 欠品になった日 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents 棚卸した日 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents 発注点警告日 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents OleDbDataAdapter1 As System.Data.OleDb.OleDbDataAdapter
    Friend WithEvents OleDbSelectCommand1 As System.Data.OleDb.OleDbCommand
    Friend WithEvents OleDbInsertCommand1 As System.Data.OleDb.OleDbCommand
    Friend WithEvents OleDbUpdateCommand1 As System.Data.OleDb.OleDbCommand
    Friend WithEvents OleDbDeleteCommand1 As System.Data.OleDb.OleDbCommand
    <System.Diagnostics.DebuggerStepThrough()> Private Sub InitializeComponent()
        Me.lblTitle = New System.Windows.Forms.Label()
        Me.dtgMaster = New System.Windows.Forms.DataGrid()
        Me.DsTable1 = New zaiko.dsTable()
        Me.DataGridTableStyle1 = New System.Windows.Forms.DataGridTableStyle()
        Me.パーツ名称 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.製造区分 = New zaiko.DataGridTextBoxCombo.DataGridComboBoxColumn()
        Me.パーツ種別 = New zaiko.DataGridTextBoxCombo.DataGridComboBoxColumn()
        Me.現在庫数 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.発注点数量 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.備考 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.発注点警告日 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.欠品になった日 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.棚卸した日 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.btnOK = New System.Windows.Forms.Button()
        Me.btnCancel = New System.Windows.Forms.Button()
        Me.GroupBox1 = New System.Windows.Forms.GroupBox()
        Me.OleDbDataAdapter2 = New System.Data.OleDb.OleDbDataAdapter()
        Me.OleDbSelectCommand2 = New System.Data.OleDb.OleDbCommand()
        Me.OleDbDataAdapter1 = New System.Data.OleDb.OleDbDataAdapter()
        Me.OleDbDeleteCommand1 = New System.Data.OleDb.OleDbCommand()
        Me.OleDbInsertCommand1 = New System.Data.OleDb.OleDbCommand()
        Me.OleDbSelectCommand1 = New System.Data.OleDb.OleDbCommand()
        Me.OleDbUpdateCommand1 = New System.Data.OleDb.OleDbCommand()
        CType(Me.dtgMaster, System.ComponentModel.ISupportInitialize).BeginInit()
        CType(Me.DsTable1, System.ComponentModel.ISupportInitialize).BeginInit()
        Me.SuspendLayout()
        '
        'lblTitle
        '
        Me.lblTitle.BackColor = System.Drawing.Color.LightSteelBlue
        Me.lblTitle.BorderStyle = System.Windows.Forms.BorderStyle.Fixed3D
        Me.lblTitle.Dock = System.Windows.Forms.DockStyle.Top
        Me.lblTitle.Font = New System.Drawing.Font("MS UI Gothic", 16.0!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.lblTitle.ForeColor = System.Drawing.Color.Maroon
        Me.lblTitle.Location = New System.Drawing.Point(0, 2)
        Me.lblTitle.Name = "lblTitle"
        Me.lblTitle.Size = New System.Drawing.Size(852, 28)
        Me.lblTitle.TabIndex = 1
        Me.lblTitle.Text = "マスターメンテナンス"
        Me.lblTitle.TextAlign = System.Drawing.ContentAlignment.MiddleCenter
        '
        'dtgMaster
        '
        Me.dtgMaster.Anchor = (((System.Windows.Forms.AnchorStyles.Top Or System.Windows.Forms.AnchorStyles.Bottom) _
                    Or System.Windows.Forms.AnchorStyles.Left) _
                    Or System.Windows.Forms.AnchorStyles.Right)
        Me.dtgMaster.CaptionText = "パーツ在庫マスター"
        Me.dtgMaster.DataMember = ""
        Me.dtgMaster.DataSource = Me.DsTable1.パーツ在庫マスタ
        Me.dtgMaster.HeaderForeColor = System.Drawing.SystemColors.ControlText
        Me.dtgMaster.ImeMode = System.Windows.Forms.ImeMode.Disable
        Me.dtgMaster.Location = New System.Drawing.Point(0, 38)
        Me.dtgMaster.Name = "dtgMaster"
        Me.dtgMaster.Size = New System.Drawing.Size(854, 386)
        Me.dtgMaster.TabIndex = 2
        Me.dtgMaster.TableStyles.AddRange(New System.Windows.Forms.DataGridTableStyle() {Me.DataGridTableStyle1})
        '
        'DsTable1
        '
        Me.DsTable1.DataSetName = "dsTable"
        Me.DsTable1.Locale = New System.Globalization.CultureInfo("ja-JP")
        Me.DsTable1.Namespace = "http://www.tempuri.org/dsZaiko.xsd"
        '
        'DataGridTableStyle1
        '
        Me.DataGridTableStyle1.AlternatingBackColor = System.Drawing.Color.FromArgb(CType(224, Byte), CType(255, Byte), CType(224, Byte))
        Me.DataGridTableStyle1.DataGrid = Me.dtgMaster
        Me.DataGridTableStyle1.GridColumnStyles.AddRange(New System.Windows.Forms.DataGridColumnStyle() {Me.パーツ名称, Me.製造区分, Me.パーツ種別, Me.現在庫数, Me.発注点数量, Me.備考, Me.発注点警告日, Me.欠品になった日, Me.棚卸した日})
        Me.DataGridTableStyle1.HeaderForeColor = System.Drawing.SystemColors.ControlText
        Me.DataGridTableStyle1.MappingName = "パーツ在庫マスタ"
        Me.DataGridTableStyle1.PreferredRowHeight = 18
        '
        'パーツ名称
        '
        Me.パーツ名称.Format = ""
        Me.パーツ名称.FormatInfo = Nothing
        Me.パーツ名称.HeaderText = "パーツ名称"
        Me.パーツ名称.MappingName = "パーツ名称"
        Me.パーツ名称.NullText = ""
        Me.パーツ名称.Width = 85
        '
        '製造区分
        '
        Me.製造区分.Format = ""
        Me.製造区分.FormatInfo = Nothing
        Me.製造区分.HeaderText = "製造区分"
        Me.製造区分.MappingName = "製造区分"
        Me.製造区分.NullText = ""
        Me.製造区分.Width = 70
        '
        'パーツ種別
        '
        Me.パーツ種別.Format = ""
        Me.パーツ種別.FormatInfo = Nothing
        Me.パーツ種別.HeaderText = "パーツ種別"
        Me.パーツ種別.MappingName = "パーツ種別"
        Me.パーツ種別.NullText = ""
        Me.パーツ種別.Width = 120
        '
        '現在庫数
        '
        Me.現在庫数.Format = ""
        Me.現在庫数.FormatInfo = Nothing
        Me.現在庫数.HeaderText = "現在庫数"
        Me.現在庫数.MappingName = "現在庫数"
        Me.現在庫数.NullText = ""
        Me.現在庫数.ReadOnly = True
        Me.現在庫数.Width = 80
        '
        '発注点数量
        '
        Me.発注点数量.Format = ""
        Me.発注点数量.FormatInfo = Nothing
        Me.発注点数量.HeaderText = "発注点数量"
        Me.発注点数量.MappingName = "発注点数量"
        Me.発注点数量.NullText = ""
        Me.発注点数量.Width = 80
        '
        '備考
        '
        Me.備考.Format = ""
        Me.備考.FormatInfo = Nothing
        Me.備考.HeaderText = "備考（メモ）"
        Me.備考.MappingName = "備考"
        Me.備考.NullText = ""
        Me.備考.ReadOnly = True
        Me.備考.Width = 120
        '
        '発注点警告日
        '
        Me.発注点警告日.Format = ""
        Me.発注点警告日.FormatInfo = Nothing
        Me.発注点警告日.HeaderText = "発注点警告日"
        Me.発注点警告日.MappingName = "発注点警告日"
        Me.発注点警告日.NullText = ""
        Me.発注点警告日.ReadOnly = True
        Me.発注点警告日.Width = 80
        '
        '欠品になった日
        '
        Me.欠品になった日.Format = ""
        Me.欠品になった日.FormatInfo = Nothing
        Me.欠品になった日.HeaderText = "欠品になった日"
        Me.欠品になった日.MappingName = "欠品になった日"
        Me.欠品になった日.NullText = ""
        Me.欠品になった日.ReadOnly = True
        Me.欠品になった日.Width = 80
        '
        '棚卸した日
        '
        Me.棚卸した日.Format = ""
        Me.棚卸した日.FormatInfo = Nothing
        Me.棚卸した日.HeaderText = "棚卸した日"
        Me.棚卸した日.MappingName = "棚卸した日"
        Me.棚卸した日.NullText = ""
        Me.棚卸した日.ReadOnly = True
        Me.棚卸した日.Width = 80
        '
        'btnOK
        '
        Me.btnOK.Anchor = System.Windows.Forms.AnchorStyles.Bottom
        Me.btnOK.Location = New System.Drawing.Point(327, 442)
        Me.btnOK.Name = "btnOK"
        Me.btnOK.Size = New System.Drawing.Size(92, 24)
        Me.btnOK.TabIndex = 4
        Me.btnOK.Text = "OK"
        '
        'btnCancel
        '
        Me.btnCancel.Anchor = System.Windows.Forms.AnchorStyles.Bottom
        Me.btnCancel.DialogResult = System.Windows.Forms.DialogResult.Cancel
        Me.btnCancel.Location = New System.Drawing.Point(434, 442)
        Me.btnCancel.Name = "btnCancel"
        Me.btnCancel.Size = New System.Drawing.Size(92, 24)
        Me.btnCancel.TabIndex = 5
        Me.btnCancel.Text = "キャンセル"
        '
        'GroupBox1
        '
        Me.GroupBox1.Anchor = ((System.Windows.Forms.AnchorStyles.Bottom Or System.Windows.Forms.AnchorStyles.Left) _
                    Or System.Windows.Forms.AnchorStyles.Right)
        Me.GroupBox1.Location = New System.Drawing.Point(7, 430)
        Me.GroupBox1.Name = "GroupBox1"
        Me.GroupBox1.Size = New System.Drawing.Size(838, 4)
        Me.GroupBox1.TabIndex = 3
        Me.GroupBox1.TabStop = False
        '
        'OleDbDataAdapter2
        '
        Me.OleDbDataAdapter2.SelectCommand = Me.OleDbSelectCommand2
        Me.OleDbDataAdapter2.TableMappings.AddRange(New System.Data.Common.DataTableMapping() {New System.Data.Common.DataTableMapping("Table", "パーツ種別マスタ", New System.Data.Common.DataColumnMapping() {New System.Data.Common.DataColumnMapping("パーツ種別コード", "パーツ種別コード"), New System.Data.Common.DataColumnMapping("パーツ種別名", "パーツ種別名")})})
        '
        'OleDbSelectCommand2
        '
        Me.OleDbSelectCommand2.CommandText = "SELECT [パーツ種別コード], [パーツ種別名] FROM [パーツ種別マスタ] ORDER BY [パーツ種別コード]"
        '
        'OleDbDataAdapter1
        '
        Me.OleDbDataAdapter1.DeleteCommand = Me.OleDbDeleteCommand1
        Me.OleDbDataAdapter1.InsertCommand = Me.OleDbInsertCommand1
        Me.OleDbDataAdapter1.SelectCommand = Me.OleDbSelectCommand1
        Me.OleDbDataAdapter1.TableMappings.AddRange(New System.Data.Common.DataTableMapping() {New System.Data.Common.DataTableMapping("Table", "パーツ在庫マスタ", New System.Data.Common.DataColumnMapping() {New System.Data.Common.DataColumnMapping("パーツ名称", "パーツ名称"), New System.Data.Common.DataColumnMapping("製造区分", "製造区分"), New System.Data.Common.DataColumnMapping("パーツ種別", "パーツ種別"), New System.Data.Common.DataColumnMapping("現在庫数", "現在庫数"), New System.Data.Common.DataColumnMapping("発注点警告日", "発注点警告日"), New System.Data.Common.DataColumnMapping("発注点数量", "発注点数量"), New System.Data.Common.DataColumnMapping("備考", "備考"), New System.Data.Common.DataColumnMapping("欠品になった日", "欠品になった日"), New System.Data.Common.DataColumnMapping("棚卸した日", "棚卸した日")})})
        Me.OleDbDataAdapter1.UpdateCommand = Me.OleDbUpdateCommand1
        '
        'OleDbDeleteCommand1
        '
        Me.OleDbDeleteCommand1.CommandText = "DELETE FROM [パーツ在庫マスタ] WHERE ([パーツ名称] = ?) AND ([パーツ種別] = ?) AND (備考 = ? OR ? IS " & _
        "NULL AND 備考 IS NULL) AND (棚卸した日 = ? OR ? IS NULL AND 棚卸した日 IS NULL) AND (欠品になった日" & _
        " = ? OR ? IS NULL AND 欠品になった日 IS NULL) AND (発注点警告日 = ? OR ? IS NULL AND 発注点警告日 I" & _
        "S NULL) AND (製造区分 = ?)"
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ名称", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ名称", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ種別", System.Data.OleDb.OleDbType.VarWChar, 3, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ種別", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_備考", System.Data.OleDb.OleDbType.VarWChar, 50, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "備考", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_備考1", System.Data.OleDb.OleDbType.VarWChar, 50, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "備考", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_棚卸した日", System.Data.OleDb.OleDbType.DBDate, 0, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "棚卸した日", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_棚卸した日1", System.Data.OleDb.OleDbType.DBDate, 0, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "棚卸した日", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_欠品になった日", System.Data.OleDb.OleDbType.DBDate, 0, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "欠品になった日", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_欠品になった日1", System.Data.OleDb.OleDbType.DBDate, 0, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "欠品になった日", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_発注点警告日", System.Data.OleDb.OleDbType.DBDate, 0, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "発注点警告日", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_発注点警告日1", System.Data.OleDb.OleDbType.DBDate, 0, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "発注点警告日", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_製造区分", System.Data.OleDb.OleDbType.VarWChar, 1, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "製造区分", System.Data.DataRowVersion.Original, Nothing))
        '
        'OleDbInsertCommand1
        '
        Me.OleDbInsertCommand1.CommandText = "INSERT INTO [パーツ在庫マスタ] ([パーツ名称], 製造区分, [パーツ種別], 現在庫数, 発注点警告日, 発注点数量, 備考, 欠品になった日," & _
        " 棚卸した日) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("パーツ名称", System.Data.OleDb.OleDbType.VarWChar, 9, "パーツ名称"))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("製造区分", System.Data.OleDb.OleDbType.VarWChar, 1, "製造区分"))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("パーツ種別", System.Data.OleDb.OleDbType.VarWChar, 3, "パーツ種別"))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("現在庫数", System.Data.OleDb.OleDbType.Integer, 0, System.Data.ParameterDirection.Input, False, CType(10, Byte), CType(0, Byte), "現在庫数", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("発注点警告日", System.Data.OleDb.OleDbType.DBDate, 0, "発注点警告日"))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("発注点数量", System.Data.OleDb.OleDbType.Integer, 0, System.Data.ParameterDirection.Input, False, CType(10, Byte), CType(0, Byte), "発注点数量", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("備考", System.Data.OleDb.OleDbType.VarWChar, 50, "備考"))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("欠品になった日", System.Data.OleDb.OleDbType.DBDate, 0, "欠品になった日"))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("棚卸した日", System.Data.OleDb.OleDbType.DBDate, 0, "棚卸した日"))
        '
        'OleDbSelectCommand1
        '
        Me.OleDbSelectCommand1.CommandText = "SELECT [パーツ名称], 製造区分, [パーツ種別], 現在庫数, 発注点警告日, 発注点数量, 備考, 欠品になった日, 棚卸した日 FROM [パーツ在" & _
        "庫マスタ] ORDER BY [パーツ名称]"
        '
        'OleDbUpdateCommand1
        '
        Me.OleDbUpdateCommand1.CommandText = "UPDATE [パーツ在庫マスタ] SET [パーツ名称] = ?, 製造区分 = ?, [パーツ種別] = ?, 現在庫数 = ?, 発注点警告日 = ?, 発" & _
        "注点数量 = ?, 備考 = ?, 欠品になった日 = ?, 棚卸した日 = ? WHERE ([パーツ名称] = ?) AND ([パーツ種別] = ?) A" & _
        "ND (備考 = ? OR ? IS NULL AND 備考 IS NULL) AND (棚卸した日 = ? OR ? IS NULL AND 棚卸した日 IS" & _
        " NULL) AND (欠品になった日 = ? OR ? IS NULL AND 欠品になった日 IS NULL) AND (発注点警告日 = ? OR ? I" & _
        "S NULL AND 発注点警告日 IS NULL) AND (製造区分 = ?)"
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("パーツ名称", System.Data.OleDb.OleDbType.VarWChar, 9, "パーツ名称"))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("製造区分", System.Data.OleDb.OleDbType.VarWChar, 1, "製造区分"))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("パーツ種別", System.Data.OleDb.OleDbType.VarWChar, 3, "パーツ種別"))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("現在庫数", System.Data.OleDb.OleDbType.Integer, 0, System.Data.ParameterDirection.Input, False, CType(10, Byte), CType(0, Byte), "現在庫数", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("発注点警告日", System.Data.OleDb.OleDbType.DBDate, 0, "発注点警告日"))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("発注点数量", System.Data.OleDb.OleDbType.Integer, 0, System.Data.ParameterDirection.Input, False, CType(10, Byte), CType(0, Byte), "発注点数量", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("備考", System.Data.OleDb.OleDbType.VarWChar, 50, "備考"))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("欠品になった日", System.Data.OleDb.OleDbType.DBDate, 0, "欠品になった日"))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("棚卸した日", System.Data.OleDb.OleDbType.DBDate, 0, "棚卸した日"))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ名称", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ名称", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ種別", System.Data.OleDb.OleDbType.VarWChar, 3, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ種別", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_備考", System.Data.OleDb.OleDbType.VarWChar, 50, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "備考", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_備考1", System.Data.OleDb.OleDbType.VarWChar, 50, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "備考", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_棚卸した日", System.Data.OleDb.OleDbType.DBDate, 0, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "棚卸した日", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_棚卸した日1", System.Data.OleDb.OleDbType.DBDate, 0, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "棚卸した日", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_欠品になった日", System.Data.OleDb.OleDbType.DBDate, 0, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "欠品になった日", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_欠品になった日1", System.Data.OleDb.OleDbType.DBDate, 0, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "欠品になった日", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_発注点警告日", System.Data.OleDb.OleDbType.DBDate, 0, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "発注点警告日", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_発注点警告日1", System.Data.OleDb.OleDbType.DBDate, 0, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "発注点警告日", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_製造区分", System.Data.OleDb.OleDbType.VarWChar, 1, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "製造区分", System.Data.DataRowVersion.Original, Nothing))
        '
        'PartsZaiko
        '
        Me.AutoScaleBaseSize = New System.Drawing.Size(5, 12)
        Me.ClientSize = New System.Drawing.Size(852, 473)
        Me.Controls.AddRange(New System.Windows.Forms.Control() {Me.GroupBox1, Me.btnOK, Me.btnCancel, Me.dtgMaster, Me.lblTitle})
        Me.DockPadding.Top = 2
        Me.MinimizeBox = False
        Me.Name = "PartsZaiko"
        Me.StartPosition = System.Windows.Forms.FormStartPosition.CenterScreen
        Me.Text = "PartsZaiko"
        CType(Me.dtgMaster, System.ComponentModel.ISupportInitialize).EndInit()
        CType(Me.DsTable1, System.ComponentModel.ISupportInitialize).EndInit()
        Me.ResumeLayout(False)

    End Sub

#End Region

    '*** フォームロードイベント
    Private Sub PartsZaiko_Load(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MyBase.Load
        'タイトル設定
        Me.Text = Common.TITLE

        'マスタからデータセットに読み込み
        OleDbDataAdapter1.Fill(DsTable1)

        With dtgMaster.TableStyles(0)
            '最大入力文字数設定
            CType(.GridColumnStyles("パーツ名称"), DataGridTextBoxColumn).TextBox.MaxLength = 12
            CType(.GridColumnStyles("発注点数量"), DataGridTextBoxColumn).TextBox.MaxLength = 5

            '大文字のみ入力させる
            CType(.GridColumnStyles("パーツ名称"), DataGridTextBoxColumn).TextBox.CharacterCasing = CharacterCasing.Upper

            'キー入力制限用イベントハンドラ設定
            AddHandler CType(.GridColumnStyles("パーツ名称"), DataGridTextBoxColumn).TextBox.KeyPress, AddressOf KeyPress_PartsMeishou
            AddHandler CType(.GridColumnStyles("現在庫数"), DataGridTextBoxColumn).TextBox.KeyPress, AddressOf KeyPress_NumOnly
            AddHandler CType(.GridColumnStyles("発注点数量"), DataGridTextBoxColumn).TextBox.KeyPress, AddressOf KeyPress_NumOnly
            AddHandler CType(.GridColumnStyles("発注点警告日"), DataGridTextBoxColumn).TextBox.KeyPress, AddressOf KeyPress_Date
            AddHandler CType(.GridColumnStyles("欠品になった日"), DataGridTextBoxColumn).TextBox.KeyPress, AddressOf KeyPress_Date
            AddHandler CType(.GridColumnStyles("棚卸した日"), DataGridTextBoxColumn).TextBox.KeyPress, AddressOf KeyPress_Date
        End With
    End Sub

    '*** [OK]ボタンクリックイベント
    Private Sub btnOK_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnOK.Click
        '更新確認
        If MsgBox("パーツ在庫マスターを更新します。よろしいですか？", MsgBoxStyle.OKCancel Or MsgBoxStyle.Question, APPNAME) = MsgBoxResult.Cancel Then
            Return
        End If

        'DB更新処理
        Try
            OleDbDataAdapter1.Update(DsTable1)
        Catch ex As Exception
            'DBエラー
            MsgBox("パーツ在庫マスターの更新でエラーが発生しました。" & vbCrLf & vbCrLf & ex.Message, MsgBoxStyle.OKOnly Or MsgBoxStyle.Exclamation, APPNAME)
            Return
        End Try

        '完了確認
        MsgBox("パーツ在庫マスターの更新が完了しました。", MsgBoxStyle.OKOnly Or MsgBoxStyle.Information, APPNAME)
        Me.DialogResult = DialogResult.OK
    End Sub
End Class
