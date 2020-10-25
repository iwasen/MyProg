Public Class PartsTenkai
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
    Friend WithEvents btnOK As System.Windows.Forms.Button
    Friend WithEvents btnCancel As System.Windows.Forms.Button
    Friend WithEvents dtgMaster As System.Windows.Forms.DataGrid
    Friend WithEvents lblTitle As System.Windows.Forms.Label
    Friend WithEvents GroupBox1 As System.Windows.Forms.GroupBox
    Friend WithEvents OleDbDataAdapter1 As System.Data.OleDb.OleDbDataAdapter
    Friend WithEvents OleDbSelectCommand1 As System.Data.OleDb.OleDbCommand
    Friend WithEvents OleDbInsertCommand1 As System.Data.OleDb.OleDbCommand
    Friend WithEvents OleDbUpdateCommand1 As System.Data.OleDb.OleDbCommand
    Friend WithEvents OleDbDeleteCommand1 As System.Data.OleDb.OleDbCommand
    Friend WithEvents DsTable1 As zaiko.dsTable
    Friend WithEvents DataGridTableStyle1 As System.Windows.Forms.DataGridTableStyle
    Friend WithEvents 商品名称 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents パーツ1 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents 数量1 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents パーツ2 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents 数量2 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents パーツ3 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents 数量3 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents パーツ4 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents 数量4 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents パーツ5 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents 数量5 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents パーツ6 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents 数量6 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents パーツ7 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents 数量7 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents パーツ8 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents 数量8 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents パーツ9 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents 数量9 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents パーツ10 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents 数量10 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents パーツ11 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents 数量11 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents パーツ12 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents 数量12 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents パーツ13 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents 数量13 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents パーツ14 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents 数量14 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents パーツ15 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents 数量15 As System.Windows.Forms.DataGridTextBoxColumn
    <System.Diagnostics.DebuggerStepThrough()> Private Sub InitializeComponent()
        Me.btnOK = New System.Windows.Forms.Button()
        Me.btnCancel = New System.Windows.Forms.Button()
        Me.dtgMaster = New System.Windows.Forms.DataGrid()
        Me.DsTable1 = New zaiko.dsTable()
        Me.DataGridTableStyle1 = New System.Windows.Forms.DataGridTableStyle()
        Me.商品名称 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.パーツ1 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.数量1 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.パーツ2 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.数量2 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.パーツ3 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.数量3 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.パーツ4 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.数量4 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.パーツ5 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.数量5 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.パーツ6 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.数量6 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.パーツ7 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.数量7 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.パーツ8 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.数量8 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.パーツ9 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.数量9 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.パーツ10 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.数量10 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.パーツ11 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.数量11 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.パーツ12 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.数量12 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.パーツ13 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.数量13 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.パーツ14 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.数量14 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.パーツ15 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.数量15 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.lblTitle = New System.Windows.Forms.Label()
        Me.GroupBox1 = New System.Windows.Forms.GroupBox()
        Me.OleDbDataAdapter1 = New System.Data.OleDb.OleDbDataAdapter()
        Me.OleDbDeleteCommand1 = New System.Data.OleDb.OleDbCommand()
        Me.OleDbInsertCommand1 = New System.Data.OleDb.OleDbCommand()
        Me.OleDbSelectCommand1 = New System.Data.OleDb.OleDbCommand()
        Me.OleDbUpdateCommand1 = New System.Data.OleDb.OleDbCommand()
        CType(Me.dtgMaster, System.ComponentModel.ISupportInitialize).BeginInit()
        CType(Me.DsTable1, System.ComponentModel.ISupportInitialize).BeginInit()
        Me.SuspendLayout()
        '
        'btnOK
        '
        Me.btnOK.Anchor = System.Windows.Forms.AnchorStyles.Bottom
        Me.btnOK.Location = New System.Drawing.Point(326, 442)
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
        'dtgMaster
        '
        Me.dtgMaster.Anchor = (((System.Windows.Forms.AnchorStyles.Top Or System.Windows.Forms.AnchorStyles.Bottom) _
                    Or System.Windows.Forms.AnchorStyles.Left) _
                    Or System.Windows.Forms.AnchorStyles.Right)
        Me.dtgMaster.CaptionText = "パーツ展開マスター"
        Me.dtgMaster.DataMember = ""
        Me.dtgMaster.DataSource = Me.DsTable1.パーツ展開マスタ
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
        Me.DataGridTableStyle1.GridColumnStyles.AddRange(New System.Windows.Forms.DataGridColumnStyle() {Me.商品名称, Me.パーツ1, Me.数量1, Me.パーツ2, Me.数量2, Me.パーツ3, Me.数量3, Me.パーツ4, Me.数量4, Me.パーツ5, Me.数量5, Me.パーツ6, Me.数量6, Me.パーツ7, Me.数量7, Me.パーツ8, Me.数量8, Me.パーツ9, Me.数量9, Me.パーツ10, Me.数量10, Me.パーツ11, Me.数量11, Me.パーツ12, Me.数量12, Me.パーツ13, Me.数量13, Me.パーツ14, Me.数量14, Me.パーツ15, Me.数量15})
        Me.DataGridTableStyle1.HeaderForeColor = System.Drawing.SystemColors.ControlText
        Me.DataGridTableStyle1.MappingName = "パーツ展開マスタ"
        Me.DataGridTableStyle1.PreferredRowHeight = 18
        '
        '商品名称
        '
        Me.商品名称.Format = ""
        Me.商品名称.FormatInfo = Nothing
        Me.商品名称.HeaderText = "商品名称"
        Me.商品名称.MappingName = "商品名称"
        Me.商品名称.NullText = ""
        Me.商品名称.Width = 95
        '
        'パーツ1
        '
        Me.パーツ1.Format = ""
        Me.パーツ1.FormatInfo = Nothing
        Me.パーツ1.HeaderText = "パーツ1"
        Me.パーツ1.MappingName = "パーツ1"
        Me.パーツ1.NullText = ""
        Me.パーツ1.Width = 95
        '
        '数量1
        '
        Me.数量1.Format = ""
        Me.数量1.FormatInfo = Nothing
        Me.数量1.HeaderText = "数量1"
        Me.数量1.MappingName = "数量1"
        Me.数量1.NullText = ""
        Me.数量1.Width = 50
        '
        'パーツ2
        '
        Me.パーツ2.Format = ""
        Me.パーツ2.FormatInfo = Nothing
        Me.パーツ2.HeaderText = "パーツ2"
        Me.パーツ2.MappingName = "パーツ2"
        Me.パーツ2.NullText = ""
        Me.パーツ2.Width = 95
        '
        '数量2
        '
        Me.数量2.Format = ""
        Me.数量2.FormatInfo = Nothing
        Me.数量2.HeaderText = "数量2"
        Me.数量2.MappingName = "数量2"
        Me.数量2.NullText = ""
        Me.数量2.Width = 50
        '
        'パーツ3
        '
        Me.パーツ3.Format = ""
        Me.パーツ3.FormatInfo = Nothing
        Me.パーツ3.HeaderText = "パーツ3"
        Me.パーツ3.MappingName = "パーツ3"
        Me.パーツ3.NullText = ""
        Me.パーツ3.Width = 95
        '
        '数量3
        '
        Me.数量3.Format = ""
        Me.数量3.FormatInfo = Nothing
        Me.数量3.HeaderText = "数量3"
        Me.数量3.MappingName = "数量3"
        Me.数量3.NullText = ""
        Me.数量3.Width = 50
        '
        'パーツ4
        '
        Me.パーツ4.Format = ""
        Me.パーツ4.FormatInfo = Nothing
        Me.パーツ4.HeaderText = "パーツ4"
        Me.パーツ4.MappingName = "パーツ4"
        Me.パーツ4.NullText = ""
        Me.パーツ4.Width = 95
        '
        '数量4
        '
        Me.数量4.Format = ""
        Me.数量4.FormatInfo = Nothing
        Me.数量4.HeaderText = "数量4"
        Me.数量4.MappingName = "数量4"
        Me.数量4.NullText = ""
        Me.数量4.Width = 50
        '
        'パーツ5
        '
        Me.パーツ5.Format = ""
        Me.パーツ5.FormatInfo = Nothing
        Me.パーツ5.HeaderText = "パーツ5"
        Me.パーツ5.MappingName = "パーツ5"
        Me.パーツ5.NullText = ""
        Me.パーツ5.Width = 95
        '
        '数量5
        '
        Me.数量5.Format = ""
        Me.数量5.FormatInfo = Nothing
        Me.数量5.HeaderText = "数量5"
        Me.数量5.MappingName = "数量5"
        Me.数量5.NullText = ""
        Me.数量5.Width = 50
        '
        'パーツ6
        '
        Me.パーツ6.Format = ""
        Me.パーツ6.FormatInfo = Nothing
        Me.パーツ6.HeaderText = "パーツ6"
        Me.パーツ6.MappingName = "パーツ6"
        Me.パーツ6.NullText = ""
        Me.パーツ6.Width = 95
        '
        '数量6
        '
        Me.数量6.Format = ""
        Me.数量6.FormatInfo = Nothing
        Me.数量6.HeaderText = "数量6"
        Me.数量6.MappingName = "数量6"
        Me.数量6.NullText = ""
        Me.数量6.Width = 50
        '
        'パーツ7
        '
        Me.パーツ7.Format = ""
        Me.パーツ7.FormatInfo = Nothing
        Me.パーツ7.HeaderText = "パーツ7"
        Me.パーツ7.MappingName = "パーツ7"
        Me.パーツ7.NullText = ""
        Me.パーツ7.Width = 95
        '
        '数量7
        '
        Me.数量7.Format = ""
        Me.数量7.FormatInfo = Nothing
        Me.数量7.HeaderText = "数量7"
        Me.数量7.MappingName = "数量7"
        Me.数量7.NullText = ""
        Me.数量7.Width = 50
        '
        'パーツ8
        '
        Me.パーツ8.Format = ""
        Me.パーツ8.FormatInfo = Nothing
        Me.パーツ8.HeaderText = "パーツ8"
        Me.パーツ8.MappingName = "パーツ8"
        Me.パーツ8.NullText = ""
        Me.パーツ8.Width = 95
        '
        '数量8
        '
        Me.数量8.Format = ""
        Me.数量8.FormatInfo = Nothing
        Me.数量8.HeaderText = "数量8"
        Me.数量8.MappingName = "数量8"
        Me.数量8.NullText = ""
        Me.数量8.Width = 50
        '
        'パーツ9
        '
        Me.パーツ9.Format = ""
        Me.パーツ9.FormatInfo = Nothing
        Me.パーツ9.HeaderText = "パーツ9"
        Me.パーツ9.MappingName = "パーツ9"
        Me.パーツ9.NullText = ""
        Me.パーツ9.Width = 95
        '
        '数量9
        '
        Me.数量9.Format = ""
        Me.数量9.FormatInfo = Nothing
        Me.数量9.HeaderText = "数量9"
        Me.数量9.MappingName = "数量9"
        Me.数量9.NullText = ""
        Me.数量9.Width = 50
        '
        'パーツ10
        '
        Me.パーツ10.Format = ""
        Me.パーツ10.FormatInfo = Nothing
        Me.パーツ10.HeaderText = "パーツ10"
        Me.パーツ10.MappingName = "パーツ10"
        Me.パーツ10.NullText = ""
        Me.パーツ10.Width = 95
        '
        '数量10
        '
        Me.数量10.Format = ""
        Me.数量10.FormatInfo = Nothing
        Me.数量10.HeaderText = "数量10"
        Me.数量10.MappingName = "数量10"
        Me.数量10.NullText = ""
        Me.数量10.Width = 50
        '
        'パーツ11
        '
        Me.パーツ11.Format = ""
        Me.パーツ11.FormatInfo = Nothing
        Me.パーツ11.HeaderText = "パーツ11"
        Me.パーツ11.MappingName = "パーツ11"
        Me.パーツ11.NullText = ""
        Me.パーツ11.Width = 95
        '
        '数量11
        '
        Me.数量11.Format = ""
        Me.数量11.FormatInfo = Nothing
        Me.数量11.HeaderText = "数量11"
        Me.数量11.MappingName = "数量11"
        Me.数量11.NullText = ""
        Me.数量11.Width = 50
        '
        'パーツ12
        '
        Me.パーツ12.Format = ""
        Me.パーツ12.FormatInfo = Nothing
        Me.パーツ12.HeaderText = "パーツ12"
        Me.パーツ12.MappingName = "パーツ12"
        Me.パーツ12.NullText = ""
        Me.パーツ12.Width = 95
        '
        '数量12
        '
        Me.数量12.Format = ""
        Me.数量12.FormatInfo = Nothing
        Me.数量12.HeaderText = "数量12"
        Me.数量12.MappingName = "数量12"
        Me.数量12.NullText = ""
        Me.数量12.Width = 50
        '
        'パーツ13
        '
        Me.パーツ13.Format = ""
        Me.パーツ13.FormatInfo = Nothing
        Me.パーツ13.HeaderText = "パーツ13"
        Me.パーツ13.MappingName = "パーツ13"
        Me.パーツ13.NullText = ""
        Me.パーツ13.Width = 95
        '
        '数量13
        '
        Me.数量13.Format = ""
        Me.数量13.FormatInfo = Nothing
        Me.数量13.HeaderText = "数量13"
        Me.数量13.MappingName = "数量13"
        Me.数量13.NullText = ""
        Me.数量13.Width = 50
        '
        'パーツ14
        '
        Me.パーツ14.Format = ""
        Me.パーツ14.FormatInfo = Nothing
        Me.パーツ14.HeaderText = "パーツ14"
        Me.パーツ14.MappingName = "パーツ14"
        Me.パーツ14.NullText = ""
        Me.パーツ14.Width = 95
        '
        '数量14
        '
        Me.数量14.Format = ""
        Me.数量14.FormatInfo = Nothing
        Me.数量14.HeaderText = "数量14"
        Me.数量14.MappingName = "数量14"
        Me.数量14.NullText = ""
        Me.数量14.Width = 50
        '
        'パーツ15
        '
        Me.パーツ15.Format = ""
        Me.パーツ15.FormatInfo = Nothing
        Me.パーツ15.HeaderText = "パーツ15"
        Me.パーツ15.MappingName = "パーツ15"
        Me.パーツ15.NullText = ""
        Me.パーツ15.Width = 95
        '
        '数量15
        '
        Me.数量15.Format = ""
        Me.数量15.FormatInfo = Nothing
        Me.数量15.HeaderText = "数量15"
        Me.数量15.MappingName = "数量15"
        Me.数量15.NullText = ""
        Me.数量15.Width = 50
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
        'GroupBox1
        '
        Me.GroupBox1.Anchor = ((System.Windows.Forms.AnchorStyles.Bottom Or System.Windows.Forms.AnchorStyles.Left) _
                    Or System.Windows.Forms.AnchorStyles.Right)
        Me.GroupBox1.Location = New System.Drawing.Point(8, 430)
        Me.GroupBox1.Name = "GroupBox1"
        Me.GroupBox1.Size = New System.Drawing.Size(838, 4)
        Me.GroupBox1.TabIndex = 3
        Me.GroupBox1.TabStop = False
        '
        'OleDbDataAdapter1
        '
        Me.OleDbDataAdapter1.DeleteCommand = Me.OleDbDeleteCommand1
        Me.OleDbDataAdapter1.InsertCommand = Me.OleDbInsertCommand1
        Me.OleDbDataAdapter1.SelectCommand = Me.OleDbSelectCommand1
        Me.OleDbDataAdapter1.TableMappings.AddRange(New System.Data.Common.DataTableMapping() {New System.Data.Common.DataTableMapping("Table", "パーツ展開マスタ", New System.Data.Common.DataColumnMapping() {New System.Data.Common.DataColumnMapping("商品名称", "商品名称"), New System.Data.Common.DataColumnMapping("パーツ1", "パーツ1"), New System.Data.Common.DataColumnMapping("数量1", "数量1"), New System.Data.Common.DataColumnMapping("パーツ2", "パーツ2"), New System.Data.Common.DataColumnMapping("数量2", "数量2"), New System.Data.Common.DataColumnMapping("パーツ3", "パーツ3"), New System.Data.Common.DataColumnMapping("数量3", "数量3"), New System.Data.Common.DataColumnMapping("パーツ4", "パーツ4"), New System.Data.Common.DataColumnMapping("数量4", "数量4"), New System.Data.Common.DataColumnMapping("パーツ5", "パーツ5"), New System.Data.Common.DataColumnMapping("数量5", "数量5"), New System.Data.Common.DataColumnMapping("パーツ6", "パーツ6"), New System.Data.Common.DataColumnMapping("数量6", "数量6"), New System.Data.Common.DataColumnMapping("パーツ7", "パーツ7"), New System.Data.Common.DataColumnMapping("数量7", "数量7"), New System.Data.Common.DataColumnMapping("パーツ8", "パーツ8"), New System.Data.Common.DataColumnMapping("数量8", "数量8"), New System.Data.Common.DataColumnMapping("パーツ9", "パーツ9"), New System.Data.Common.DataColumnMapping("数量9", "数量9"), New System.Data.Common.DataColumnMapping("パーツ10", "パーツ10"), New System.Data.Common.DataColumnMapping("数量10", "数量10"), New System.Data.Common.DataColumnMapping("パーツ11", "パーツ11"), New System.Data.Common.DataColumnMapping("数量11", "数量11"), New System.Data.Common.DataColumnMapping("パーツ12", "パーツ12"), New System.Data.Common.DataColumnMapping("数量12", "数量12"), New System.Data.Common.DataColumnMapping("パーツ13", "パーツ13"), New System.Data.Common.DataColumnMapping("数量13", "数量13"), New System.Data.Common.DataColumnMapping("パーツ14", "パーツ14"), New System.Data.Common.DataColumnMapping("数量14", "数量14"), New System.Data.Common.DataColumnMapping("パーツ15", "パーツ15"), New System.Data.Common.DataColumnMapping("数量15", "数量15")})})
        Me.OleDbDataAdapter1.UpdateCommand = Me.OleDbUpdateCommand1
        '
        'OleDbDeleteCommand1
        '
        Me.OleDbDeleteCommand1.CommandText = "DELETE FROM パーツ展開マスタ WHERE (商品名称 = ?) AND (パーツ1 = ? OR ? IS NULL AND パーツ1 IS NULL" & _
        ") AND (パーツ10 = ? OR ? IS NULL AND パーツ10 IS NULL) AND (パーツ11 = ? OR ? IS NULL AND" & _
        " パーツ11 IS NULL) AND (パーツ12 = ? OR ? IS NULL AND パーツ12 IS NULL) AND (パーツ13 = ? OR" & _
        " ? IS NULL AND パーツ13 IS NULL) AND (パーツ14 = ? OR ? IS NULL AND パーツ14 IS NULL) AND" & _
        " (パーツ15 = ? OR ? IS NULL AND パーツ15 IS NULL) AND (パーツ2 = ? OR ? IS NULL AND パーツ2 " & _
        "IS NULL) AND (パーツ3 = ? OR ? IS NULL AND パーツ3 IS NULL) AND (パーツ4 = ? OR ? IS NULL" & _
        " AND パーツ4 IS NULL) AND (パーツ5 = ? OR ? IS NULL AND パーツ5 IS NULL) AND (パーツ6 = ? OR" & _
        " ? IS NULL AND パーツ6 IS NULL) AND (パーツ7 = ? OR ? IS NULL AND パーツ7 IS NULL) AND (パ" & _
        "ーツ8 = ? OR ? IS NULL AND パーツ8 IS NULL) AND (パーツ9 = ? OR ? IS NULL AND パーツ9 IS NU" & _
        "LL) AND (数量1 = ? OR ? IS NULL AND 数量1 IS NULL) AND (数量10 = ? OR ? IS NULL AND 数量" & _
        "10 IS NULL) AND (数量2 = ? OR ? IS NULL AND 数量2 IS NULL) AND (数量3 = ? OR ? IS NULL" & _
        " AND 数量3 IS NULL) AND (数量4 = ? OR ? IS NULL AND 数量4 IS NULL) AND (数量5 = ? OR ? I" & _
        "S NULL AND 数量5 IS NULL) AND (数量6 = ? OR ? IS NULL AND 数量6 IS NULL) AND (数量7 = ? " & _
        "OR ? IS NULL AND 数量7 IS NULL) AND (数量8 = ? OR ? IS NULL AND 数量8 IS NULL) AND (数量" & _
        "9 = ? OR ? IS NULL AND 数量9 IS NULL)"
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_商品名称", System.Data.OleDb.OleDbType.VarWChar, 12, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "商品名称", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ1", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ1", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ11", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ1", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ10", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ10", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ101", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ10", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ111", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ11", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ112", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ11", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ12", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ12", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ121", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ12", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ13", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ13", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ131", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ13", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ14", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ14", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ141", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ14", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ15", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ15", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ151", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ15", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ2", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ2", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ21", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ2", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ3", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ3", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ31", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ3", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ4", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ4", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ41", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ4", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ5", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ5", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ51", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ5", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ6", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ6", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ61", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ6", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ7", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ7", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ71", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ7", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ8", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ8", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ81", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ8", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ9", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ9", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ91", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ9", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_数量1", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "数量1", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_数量11", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "数量1", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_数量10", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "数量10", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_数量101", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "数量10", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_数量2", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "数量2", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_数量21", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "数量2", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_数量3", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "数量3", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_数量31", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "数量3", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_数量4", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "数量4", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_数量41", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "数量4", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_数量5", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "数量5", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_数量51", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "数量5", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_数量6", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "数量6", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_数量61", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "数量6", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_数量7", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "数量7", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_数量71", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "数量7", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_数量8", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "数量8", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_数量81", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "数量8", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_数量9", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "数量9", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_数量91", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "数量9", System.Data.DataRowVersion.Original, Nothing))
        '
        'OleDbInsertCommand1
        '
        Me.OleDbInsertCommand1.CommandText = "INSERT INTO パーツ展開マスタ(パーツ1, パーツ10, パーツ11, パーツ12, パーツ13, パーツ14, パーツ15, パーツ2, パーツ3, " & _
        "パーツ4, パーツ5, パーツ6, パーツ7, パーツ8, パーツ9, 商品名称, 数量1, 数量10, 数量11, 数量12, 数量13, 数量14, 数量1" & _
        "5, 数量2, 数量3, 数量4, 数量5, 数量6, 数量7, 数量8, 数量9) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?," & _
        " ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("パーツ1", System.Data.OleDb.OleDbType.VarWChar, 9, "パーツ1"))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("パーツ10", System.Data.OleDb.OleDbType.VarWChar, 9, "パーツ10"))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("パーツ11", System.Data.OleDb.OleDbType.VarWChar, 9, "パーツ11"))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("パーツ12", System.Data.OleDb.OleDbType.VarWChar, 9, "パーツ12"))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("パーツ13", System.Data.OleDb.OleDbType.VarWChar, 9, "パーツ13"))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("パーツ14", System.Data.OleDb.OleDbType.VarWChar, 9, "パーツ14"))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("パーツ15", System.Data.OleDb.OleDbType.VarWChar, 9, "パーツ15"))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("パーツ2", System.Data.OleDb.OleDbType.VarWChar, 9, "パーツ2"))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("パーツ3", System.Data.OleDb.OleDbType.VarWChar, 9, "パーツ3"))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("パーツ4", System.Data.OleDb.OleDbType.VarWChar, 9, "パーツ4"))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("パーツ5", System.Data.OleDb.OleDbType.VarWChar, 9, "パーツ5"))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("パーツ6", System.Data.OleDb.OleDbType.VarWChar, 9, "パーツ6"))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("パーツ7", System.Data.OleDb.OleDbType.VarWChar, 9, "パーツ7"))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("パーツ8", System.Data.OleDb.OleDbType.VarWChar, 9, "パーツ8"))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("パーツ9", System.Data.OleDb.OleDbType.VarWChar, 9, "パーツ9"))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("商品名称", System.Data.OleDb.OleDbType.VarWChar, 12, "商品名称"))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("数量1", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "数量1", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("数量10", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "数量10", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("数量11", System.Data.OleDb.OleDbType.Integer, 0, System.Data.ParameterDirection.Input, False, CType(10, Byte), CType(0, Byte), "数量11", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("数量12", System.Data.OleDb.OleDbType.Integer, 0, System.Data.ParameterDirection.Input, False, CType(10, Byte), CType(0, Byte), "数量12", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("数量13", System.Data.OleDb.OleDbType.Integer, 0, System.Data.ParameterDirection.Input, False, CType(10, Byte), CType(0, Byte), "数量13", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("数量14", System.Data.OleDb.OleDbType.Integer, 0, System.Data.ParameterDirection.Input, False, CType(10, Byte), CType(0, Byte), "数量14", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("数量15", System.Data.OleDb.OleDbType.Integer, 0, System.Data.ParameterDirection.Input, False, CType(10, Byte), CType(0, Byte), "数量15", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("数量2", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "数量2", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("数量3", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "数量3", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("数量4", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "数量4", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("数量5", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "数量5", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("数量6", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "数量6", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("数量7", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "数量7", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("数量8", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "数量8", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("数量9", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "数量9", System.Data.DataRowVersion.Current, Nothing))
        '
        'OleDbSelectCommand1
        '
        Me.OleDbSelectCommand1.CommandText = "SELECT パーツ1, パーツ10, パーツ11, パーツ12, パーツ13, パーツ14, パーツ15, パーツ2, パーツ3, パーツ4, パーツ5, パー" & _
        "ツ6, パーツ7, パーツ8, パーツ9, 商品名称, 数量1, 数量10, 数量11, 数量12, 数量13, 数量14, 数量15, 数量2, 数量3, 数" & _
        "量4, 数量5, 数量6, 数量7, 数量8, 数量9 FROM パーツ展開マスタ ORDER BY 商品名称"
        '
        'OleDbUpdateCommand1
        '
        Me.OleDbUpdateCommand1.CommandText = "UPDATE パーツ展開マスタ SET パーツ1 = ?, パーツ10 = ?, パーツ11 = ?, パーツ12 = ?, パーツ13 = ?, パーツ14 =" & _
        " ?, パーツ15 = ?, パーツ2 = ?, パーツ3 = ?, パーツ4 = ?, パーツ5 = ?, パーツ6 = ?, パーツ7 = ?, パーツ8 " & _
        "= ?, パーツ9 = ?, 商品名称 = ?, 数量1 = ?, 数量10 = ?, 数量11 = ?, 数量12 = ?, 数量13 = ?, 数量14 =" & _
        " ?, 数量15 = ?, 数量2 = ?, 数量3 = ?, 数量4 = ?, 数量5 = ?, 数量6 = ?, 数量7 = ?, 数量8 = ?, 数量9" & _
        " = ? WHERE (商品名称 = ?) AND (パーツ1 = ? OR ? IS NULL AND パーツ1 IS NULL) AND (パーツ10 = " & _
        "? OR ? IS NULL AND パーツ10 IS NULL) AND (パーツ11 = ? OR ? IS NULL AND パーツ11 IS NULL)" & _
        " AND (パーツ12 = ? OR ? IS NULL AND パーツ12 IS NULL) AND (パーツ13 = ? OR ? IS NULL AND " & _
        "パーツ13 IS NULL) AND (パーツ14 = ? OR ? IS NULL AND パーツ14 IS NULL) AND (パーツ15 = ? OR " & _
        "? IS NULL AND パーツ15 IS NULL) AND (パーツ2 = ? OR ? IS NULL AND パーツ2 IS NULL) AND (パ" & _
        "ーツ3 = ? OR ? IS NULL AND パーツ3 IS NULL) AND (パーツ4 = ? OR ? IS NULL AND パーツ4 IS NU" & _
        "LL) AND (パーツ5 = ? OR ? IS NULL AND パーツ5 IS NULL) AND (パーツ6 = ? OR ? IS NULL AND " & _
        "パーツ6 IS NULL) AND (パーツ7 = ? OR ? IS NULL AND パーツ7 IS NULL) AND (パーツ8 = ? OR ? IS" & _
        " NULL AND パーツ8 IS NULL) AND (パーツ9 = ? OR ? IS NULL AND パーツ9 IS NULL) AND (数量1 = " & _
        "? OR ? IS NULL AND 数量1 IS NULL) AND (数量10 = ? OR ? IS NULL AND 数量10 IS NULL) AND" & _
        " (数量2 = ? OR ? IS NULL AND 数量2 IS NULL) AND (数量3 = ? OR ? IS NULL AND 数量3 IS NUL" & _
        "L) AND (数量4 = ? OR ? IS NULL AND 数量4 IS NULL) AND (数量5 = ? OR ? IS NULL AND 数量5 " & _
        "IS NULL) AND (数量6 = ? OR ? IS NULL AND 数量6 IS NULL) AND (数量7 = ? OR ? IS NULL AN" & _
        "D 数量7 IS NULL) AND (数量8 = ? OR ? IS NULL AND 数量8 IS NULL) AND (数量9 = ? OR ? IS N" & _
        "ULL AND 数量9 IS NULL)"
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("パーツ1", System.Data.OleDb.OleDbType.VarWChar, 9, "パーツ1"))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("パーツ10", System.Data.OleDb.OleDbType.VarWChar, 9, "パーツ10"))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("パーツ11", System.Data.OleDb.OleDbType.VarWChar, 9, "パーツ11"))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("パーツ12", System.Data.OleDb.OleDbType.VarWChar, 9, "パーツ12"))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("パーツ13", System.Data.OleDb.OleDbType.VarWChar, 9, "パーツ13"))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("パーツ14", System.Data.OleDb.OleDbType.VarWChar, 9, "パーツ14"))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("パーツ15", System.Data.OleDb.OleDbType.VarWChar, 9, "パーツ15"))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("パーツ2", System.Data.OleDb.OleDbType.VarWChar, 9, "パーツ2"))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("パーツ3", System.Data.OleDb.OleDbType.VarWChar, 9, "パーツ3"))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("パーツ4", System.Data.OleDb.OleDbType.VarWChar, 9, "パーツ4"))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("パーツ5", System.Data.OleDb.OleDbType.VarWChar, 9, "パーツ5"))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("パーツ6", System.Data.OleDb.OleDbType.VarWChar, 9, "パーツ6"))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("パーツ7", System.Data.OleDb.OleDbType.VarWChar, 9, "パーツ7"))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("パーツ8", System.Data.OleDb.OleDbType.VarWChar, 9, "パーツ8"))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("パーツ9", System.Data.OleDb.OleDbType.VarWChar, 9, "パーツ9"))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("商品名称", System.Data.OleDb.OleDbType.VarWChar, 12, "商品名称"))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("数量1", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "数量1", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("数量10", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "数量10", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("数量11", System.Data.OleDb.OleDbType.Integer, 0, System.Data.ParameterDirection.Input, False, CType(10, Byte), CType(0, Byte), "数量11", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("数量12", System.Data.OleDb.OleDbType.Integer, 0, System.Data.ParameterDirection.Input, False, CType(10, Byte), CType(0, Byte), "数量12", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("数量13", System.Data.OleDb.OleDbType.Integer, 0, System.Data.ParameterDirection.Input, False, CType(10, Byte), CType(0, Byte), "数量13", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("数量14", System.Data.OleDb.OleDbType.Integer, 0, System.Data.ParameterDirection.Input, False, CType(10, Byte), CType(0, Byte), "数量14", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("数量15", System.Data.OleDb.OleDbType.Integer, 0, System.Data.ParameterDirection.Input, False, CType(10, Byte), CType(0, Byte), "数量15", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("数量2", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "数量2", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("数量3", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "数量3", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("数量4", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "数量4", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("数量5", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "数量5", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("数量6", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "数量6", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("数量7", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "数量7", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("数量8", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "数量8", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("数量9", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "数量9", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_商品名称", System.Data.OleDb.OleDbType.VarWChar, 12, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "商品名称", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ1", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ1", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ11", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ1", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ10", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ10", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ101", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ10", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ111", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ11", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ112", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ11", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ12", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ12", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ121", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ12", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ13", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ13", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ131", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ13", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ14", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ14", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ141", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ14", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ15", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ15", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ151", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ15", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ2", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ2", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ21", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ2", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ3", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ3", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ31", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ3", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ4", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ4", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ41", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ4", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ5", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ5", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ51", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ5", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ6", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ6", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ61", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ6", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ7", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ7", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ71", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ7", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ8", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ8", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ81", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ8", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ9", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ9", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_パーツ91", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "パーツ9", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_数量1", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "数量1", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_数量11", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "数量1", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_数量10", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "数量10", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_数量101", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "数量10", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_数量2", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "数量2", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_数量21", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "数量2", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_数量3", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "数量3", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_数量31", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "数量3", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_数量4", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "数量4", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_数量41", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "数量4", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_数量5", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "数量5", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_数量51", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "数量5", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_数量6", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "数量6", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_数量61", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "数量6", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_数量7", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "数量7", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_数量71", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "数量7", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_数量8", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "数量8", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_数量81", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "数量8", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_数量9", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "数量9", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_数量91", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "数量9", System.Data.DataRowVersion.Original, Nothing))
        '
        'PartsTenkai
        '
        Me.AutoScaleBaseSize = New System.Drawing.Size(5, 12)
        Me.ClientSize = New System.Drawing.Size(852, 473)
        Me.Controls.AddRange(New System.Windows.Forms.Control() {Me.GroupBox1, Me.btnOK, Me.btnCancel, Me.dtgMaster, Me.lblTitle})
        Me.DockPadding.Top = 2
        Me.MinimizeBox = False
        Me.Name = "PartsTenkai"
        Me.StartPosition = System.Windows.Forms.FormStartPosition.CenterScreen
        Me.Text = "PartsTenkai"
        CType(Me.dtgMaster, System.ComponentModel.ISupportInitialize).EndInit()
        CType(Me.DsTable1, System.ComponentModel.ISupportInitialize).EndInit()
        Me.ResumeLayout(False)

    End Sub

#End Region

    Private dtcPartsCheck As OleDb.OleDbCommand

    '*** フォームロードイベント
    Private Sub PartsTenkai_Load(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MyBase.Load
        'タイトル設定
        Me.Text = Common.TITLE

        'マスタからデータセットに読み込み
        SetDbConnection(OleDbDataAdapter1)
        OleDbDataAdapter1.Fill(DsTable1)

        With dtgMaster.TableStyles(0)
            Dim i As Integer

            '最大入力文字数設定
            CType(.GridColumnStyles("商品名称"), DataGridTextBoxColumn).TextBox.MaxLength = 12
            For i = 1 To 15
                CType(.GridColumnStyles("パーツ" & i), DataGridTextBoxColumn).TextBox.MaxLength = 12
                CType(.GridColumnStyles("数量" & i), DataGridTextBoxColumn).TextBox.MaxLength = 5
            Next

            '大文字のみ入力させる
            CType(.GridColumnStyles("商品名称"), DataGridTextBoxColumn).TextBox.CharacterCasing = CharacterCasing.Upper
            For i = 1 To 15
                CType(.GridColumnStyles("パーツ" & i), DataGridTextBoxColumn).TextBox.CharacterCasing = CharacterCasing.Upper
            Next

            'キー入力制限用イベントハンドラ設定
            AddHandler CType(.GridColumnStyles("商品名称"), DataGridTextBoxColumn).TextBox.KeyPress, AddressOf KeyPress_ShouhinMeishou
            For i = 1 To 15
                AddHandler CType(.GridColumnStyles("パーツ" & i), DataGridTextBoxColumn).TextBox.KeyPress, AddressOf KeyPress_PartsMeishou
                AddHandler CType(.GridColumnStyles("数量" & i), DataGridTextBoxColumn).TextBox.KeyPress, AddressOf KeyPress_NumOnly
            Next

        End With

        'パーツ登録チェック用SQL
        dtcPartsCheck = New OleDb.OleDbCommand("SELECT COUNT(*) FROM パーツ在庫マスタ WHERE パーツ名称=?", dbConnection)
        dtcPartsCheck.Parameters.Add("パーツ名称", OleDb.OleDbType.VarWChar)

        'パーツ登録チェック用イベントハンドラ設定
        AddHandler DsTable1.パーツ展開マスタ.ColumnChanging, New DataColumnChangeEventHandler(AddressOf DsTable1_Column_Changing)
    End Sub

    '*** [OK]ボタンクリックイベント
    Private Sub btnOK_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnOK.Click
        '更新確認
        If MsgBox("パーツ展開マスターを更新します。よろしいですか？", MsgBoxStyle.OKCancel Or MsgBoxStyle.Question, APPNAME) = MsgBoxResult.Cancel Then
            Return
        End If

        'DB更新処理
        Try
            OleDbDataAdapter1.Update(DsTable1)
        Catch ex As Exception
            'DBエラー
            MsgBox("パーツ展開マスターの更新でエラーが発生しました。" & vbCrLf & vbCrLf & ex.Message, MsgBoxStyle.OKOnly Or MsgBoxStyle.Exclamation, APPNAME)
            Return
        End Try

        '完了確認
        MsgBox("パーツ展開マスターの更新が完了しました。", MsgBoxStyle.OKOnly Or MsgBoxStyle.Information, APPNAME)
        Me.DialogResult = DialogResult.OK
    End Sub

    Private Sub DsTable1_Column_Changing(ByVal sender As Object, ByVal e As DataColumnChangeEventArgs)
        If e.Column.ColumnName.Substring(0, 3) = "パーツ" Then
            dtcPartsCheck.Parameters("パーツ名称").Value = e.ProposedValue
            If dtcPartsCheck.ExecuteScalar() = 0 Then
                Beep()
                e.Row.SetColumnError(e.Column.ColumnName, "入力したパーツはパーツ在庫マスタに登録されていません。")
            Else
                e.Row.SetColumnError(e.Column.ColumnName, Nothing)
            End If
        End If
    End Sub
End Class
