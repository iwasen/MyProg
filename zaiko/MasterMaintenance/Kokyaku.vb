Public Class Kokyaku
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
    Friend WithEvents btnOK As System.Windows.Forms.Button
    Friend WithEvents btnCancel As System.Windows.Forms.Button
    Friend WithEvents dtgMaster As System.Windows.Forms.DataGrid
    Friend WithEvents GroupBox1 As System.Windows.Forms.GroupBox
    Friend WithEvents OleDbDataAdapter1 As System.Data.OleDb.OleDbDataAdapter
    Friend WithEvents OleDbSelectCommand1 As System.Data.OleDb.OleDbCommand
    Friend WithEvents OleDbInsertCommand1 As System.Data.OleDb.OleDbCommand
    Friend WithEvents OleDbUpdateCommand1 As System.Data.OleDb.OleDbCommand
    Friend WithEvents OleDbDeleteCommand1 As System.Data.OleDb.OleDbCommand
    Friend WithEvents DsTable1 As zaiko.dsTable
    Friend WithEvents DataGridTableStyle1 As System.Windows.Forms.DataGridTableStyle
    Friend WithEvents 並び順 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents 顧客コード As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents 顧客名 As System.Windows.Forms.DataGridTextBoxColumn
    <System.Diagnostics.DebuggerStepThrough()> Private Sub InitializeComponent()
        Me.lblTitle = New System.Windows.Forms.Label()
        Me.btnOK = New System.Windows.Forms.Button()
        Me.btnCancel = New System.Windows.Forms.Button()
        Me.dtgMaster = New System.Windows.Forms.DataGrid()
        Me.DsTable1 = New zaiko.dsTable()
        Me.DataGridTableStyle1 = New System.Windows.Forms.DataGridTableStyle()
        Me.並び順 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.顧客コード = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.顧客名 = New System.Windows.Forms.DataGridTextBoxColumn()
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
        'lblTitle
        '
        Me.lblTitle.BackColor = System.Drawing.Color.LightSteelBlue
        Me.lblTitle.BorderStyle = System.Windows.Forms.BorderStyle.Fixed3D
        Me.lblTitle.Dock = System.Windows.Forms.DockStyle.Top
        Me.lblTitle.Font = New System.Drawing.Font("MS UI Gothic", 16.0!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.lblTitle.ForeColor = System.Drawing.Color.Maroon
        Me.lblTitle.Location = New System.Drawing.Point(0, 2)
        Me.lblTitle.Name = "lblTitle"
        Me.lblTitle.Size = New System.Drawing.Size(530, 28)
        Me.lblTitle.TabIndex = 1
        Me.lblTitle.Text = "マスターメンテナンス"
        Me.lblTitle.TextAlign = System.Drawing.ContentAlignment.MiddleCenter
        '
        'btnOK
        '
        Me.btnOK.Anchor = System.Windows.Forms.AnchorStyles.Bottom
        Me.btnOK.Location = New System.Drawing.Point(165, 444)
        Me.btnOK.Name = "btnOK"
        Me.btnOK.Size = New System.Drawing.Size(92, 24)
        Me.btnOK.TabIndex = 4
        Me.btnOK.Text = "OK"
        '
        'btnCancel
        '
        Me.btnCancel.Anchor = System.Windows.Forms.AnchorStyles.Bottom
        Me.btnCancel.DialogResult = System.Windows.Forms.DialogResult.Cancel
        Me.btnCancel.Location = New System.Drawing.Point(273, 444)
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
        Me.dtgMaster.CaptionText = "顧客マスター"
        Me.dtgMaster.DataMember = ""
        Me.dtgMaster.DataSource = Me.DsTable1.顧客マスタ
        Me.dtgMaster.HeaderForeColor = System.Drawing.SystemColors.ControlText
        Me.dtgMaster.Location = New System.Drawing.Point(0, 38)
        Me.dtgMaster.Name = "dtgMaster"
        Me.dtgMaster.Size = New System.Drawing.Size(532, 386)
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
        Me.DataGridTableStyle1.GridColumnStyles.AddRange(New System.Windows.Forms.DataGridColumnStyle() {Me.並び順, Me.顧客コード, Me.顧客名})
        Me.DataGridTableStyle1.HeaderForeColor = System.Drawing.SystemColors.ControlText
        Me.DataGridTableStyle1.MappingName = "顧客マスタ"
        Me.DataGridTableStyle1.PreferredRowHeight = 18
        '
        '並び順
        '
        Me.並び順.Format = ""
        Me.並び順.FormatInfo = Nothing
        Me.並び順.HeaderText = "並び順"
        Me.並び順.MappingName = "並び順"
        Me.並び順.NullText = ""
        Me.並び順.Width = 120
        '
        '顧客コード
        '
        Me.顧客コード.Format = ""
        Me.顧客コード.FormatInfo = Nothing
        Me.顧客コード.HeaderText = "顧客コード"
        Me.顧客コード.MappingName = "顧客コード"
        Me.顧客コード.NullText = ""
        Me.顧客コード.Width = 120
        '
        '顧客名
        '
        Me.顧客名.Format = ""
        Me.顧客名.FormatInfo = Nothing
        Me.顧客名.HeaderText = "顧客名"
        Me.顧客名.MappingName = "顧客名"
        Me.顧客名.NullText = ""
        Me.顧客名.Width = 200
        '
        'GroupBox1
        '
        Me.GroupBox1.Anchor = ((System.Windows.Forms.AnchorStyles.Bottom Or System.Windows.Forms.AnchorStyles.Left) _
                    Or System.Windows.Forms.AnchorStyles.Right)
        Me.GroupBox1.Location = New System.Drawing.Point(7, 432)
        Me.GroupBox1.Name = "GroupBox1"
        Me.GroupBox1.Size = New System.Drawing.Size(516, 4)
        Me.GroupBox1.TabIndex = 3
        Me.GroupBox1.TabStop = False
        '
        'OleDbDataAdapter1
        '
        Me.OleDbDataAdapter1.DeleteCommand = Me.OleDbDeleteCommand1
        Me.OleDbDataAdapter1.InsertCommand = Me.OleDbInsertCommand1
        Me.OleDbDataAdapter1.SelectCommand = Me.OleDbSelectCommand1
        Me.OleDbDataAdapter1.TableMappings.AddRange(New System.Data.Common.DataTableMapping() {New System.Data.Common.DataTableMapping("Table", "顧客マスタ", New System.Data.Common.DataColumnMapping() {New System.Data.Common.DataColumnMapping("並び順", "並び順"), New System.Data.Common.DataColumnMapping("顧客コード", "顧客コード"), New System.Data.Common.DataColumnMapping("顧客名", "顧客名")})})
        Me.OleDbDataAdapter1.UpdateCommand = Me.OleDbUpdateCommand1
        '
        'OleDbDeleteCommand1
        '
        Me.OleDbDeleteCommand1.CommandText = "DELETE FROM 顧客マスタ WHERE (顧客コード = ?) AND (顧客名 = ? OR ? IS NULL AND 顧客名 IS NULL)"
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_顧客コード", System.Data.OleDb.OleDbType.VarWChar, 20, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "顧客コード", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_顧客名", System.Data.OleDb.OleDbType.VarWChar, 30, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "顧客名", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_顧客名1", System.Data.OleDb.OleDbType.VarWChar, 30, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "顧客名", System.Data.DataRowVersion.Original, Nothing))
        '
        'OleDbInsertCommand1
        '
        Me.OleDbInsertCommand1.CommandText = "INSERT INTO 顧客マスタ(顧客コード, 顧客名, 並び順) VALUES (?, ?, ?)"
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("顧客コード", System.Data.OleDb.OleDbType.VarWChar, 20, "顧客コード"))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("顧客名", System.Data.OleDb.OleDbType.VarWChar, 30, "顧客名"))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("並び順", System.Data.OleDb.OleDbType.Integer, 0, System.Data.ParameterDirection.Input, False, CType(10, Byte), CType(0, Byte), "並び順", System.Data.DataRowVersion.Current, Nothing))
        '
        'OleDbSelectCommand1
        '
        Me.OleDbSelectCommand1.CommandText = "SELECT 顧客コード, 顧客名, 並び順 FROM 顧客マスタ ORDER BY 並び順"
        '
        'OleDbUpdateCommand1
        '
        Me.OleDbUpdateCommand1.CommandText = "UPDATE 顧客マスタ SET 顧客コード = ?, 顧客名 = ?, 並び順 = ? WHERE (顧客コード = ?) AND (顧客名 = ? OR ? " & _
        "IS NULL AND 顧客名 IS NULL)"
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("顧客コード", System.Data.OleDb.OleDbType.VarWChar, 20, "顧客コード"))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("顧客名", System.Data.OleDb.OleDbType.VarWChar, 30, "顧客名"))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("並び順", System.Data.OleDb.OleDbType.Integer, 0, System.Data.ParameterDirection.Input, False, CType(10, Byte), CType(0, Byte), "並び順", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_顧客コード", System.Data.OleDb.OleDbType.VarWChar, 20, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "顧客コード", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_顧客名", System.Data.OleDb.OleDbType.VarWChar, 30, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "顧客名", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_顧客名1", System.Data.OleDb.OleDbType.VarWChar, 30, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "顧客名", System.Data.DataRowVersion.Original, Nothing))
        '
        'Kokyaku
        '
        Me.AutoScaleBaseSize = New System.Drawing.Size(5, 12)
        Me.ClientSize = New System.Drawing.Size(530, 475)
        Me.Controls.AddRange(New System.Windows.Forms.Control() {Me.GroupBox1, Me.btnOK, Me.btnCancel, Me.dtgMaster, Me.lblTitle})
        Me.DockPadding.Top = 2
        Me.MinimizeBox = False
        Me.Name = "Kokyaku"
        Me.StartPosition = System.Windows.Forms.FormStartPosition.CenterScreen
        Me.Text = "Kokyaku"
        CType(Me.dtgMaster, System.ComponentModel.ISupportInitialize).EndInit()
        CType(Me.DsTable1, System.ComponentModel.ISupportInitialize).EndInit()
        Me.ResumeLayout(False)

    End Sub

#End Region

    '*** フォームロードイベント
    Private Sub Kokyaku_Load(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MyBase.Load
        'タイトル設定
        Me.Text = Common.TITLE

        'マスタからデータセットに読み込み
        SetDbConnection(OleDbDataAdapter1)
        OleDbDataAdapter1.Fill(DsTable1)

        With dtgMaster.TableStyles(0)
            '最大入力文字数設定
            CType(.GridColumnStyles("並び順"), DataGridTextBoxColumn).TextBox.MaxLength = 2
            CType(.GridColumnStyles("顧客コード"), DataGridTextBoxColumn).TextBox.MaxLength = 20
            CType(.GridColumnStyles("顧客名"), DataGridTextBoxColumn).TextBox.MaxLength = 30

            'キー入力制限用イベントハンドラ設定
            AddHandler CType(.GridColumnStyles("並び順"), DataGridTextBoxColumn).TextBox.KeyPress, AddressOf KeyPress_NumOnly
            AddHandler CType(.GridColumnStyles("顧客コード"), DataGridTextBoxColumn).TextBox.KeyPress, AddressOf KeyPress_KokyakuCode
        End With
    End Sub

    '*** [OK]ボタンクリックイベント
    Private Sub btnOK_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnOK.Click
        '更新確認
        If MsgBox("顧客マスターを更新します。よろしいですか？", MsgBoxStyle.OKCancel Or MsgBoxStyle.Question, APPNAME) = MsgBoxResult.Cancel Then
            Return
        End If

        'DB更新処理
        Try
            OleDbDataAdapter1.Update(DsTable1)
        Catch ex As Exception
            'DBエラー
            MsgBox("顧客マスターの更新でエラーが発生しました。" & vbCrLf & vbCrLf & ex.Message, MsgBoxStyle.OKOnly Or MsgBoxStyle.Exclamation, APPNAME)
            Return
        End Try

        '完了確認
        MsgBox("顧客マスターの更新が完了しました。", MsgBoxStyle.OKOnly Or MsgBoxStyle.Information, APPNAME)
        Me.DialogResult = DialogResult.OK
    End Sub
End Class
