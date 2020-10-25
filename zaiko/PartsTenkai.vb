Public Class frmPartsTenkai
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
    Friend WithEvents Label2 As System.Windows.Forms.Label
    Friend WithEvents Label3 As System.Windows.Forms.Label
    Friend WithEvents btnOK As System.Windows.Forms.Button
    Friend WithEvents btnClose As System.Windows.Forms.Button
    Friend WithEvents GroupBox1 As System.Windows.Forms.GroupBox
    Friend WithEvents cmbKokyaku As System.Windows.Forms.ComboBox
    Friend WithEvents Label4 As System.Windows.Forms.Label
    Friend WithEvents txtMishori As System.Windows.Forms.TextBox
    Friend WithEvents txtShoriNo As System.Windows.Forms.TextBox
    Friend WithEvents OleDbDataAdapter1 As System.Data.OleDb.OleDbDataAdapter
    Friend WithEvents OleDbSelectCommand1 As System.Data.OleDb.OleDbCommand
    Friend WithEvents DsWork1 As zaiko.dsWork
    <System.Diagnostics.DebuggerStepThrough()> Private Sub InitializeComponent()
        Me.lblTitle = New System.Windows.Forms.Label()
        Me.Label1 = New System.Windows.Forms.Label()
        Me.txtMishori = New System.Windows.Forms.TextBox()
        Me.btnOK = New System.Windows.Forms.Button()
        Me.Label2 = New System.Windows.Forms.Label()
        Me.txtShoriNo = New System.Windows.Forms.TextBox()
        Me.Label3 = New System.Windows.Forms.Label()
        Me.btnClose = New System.Windows.Forms.Button()
        Me.GroupBox1 = New System.Windows.Forms.GroupBox()
        Me.cmbKokyaku = New System.Windows.Forms.ComboBox()
        Me.Label4 = New System.Windows.Forms.Label()
        Me.OleDbDataAdapter1 = New System.Data.OleDb.OleDbDataAdapter()
        Me.OleDbSelectCommand1 = New System.Data.OleDb.OleDbCommand()
        Me.DsWork1 = New zaiko.dsWork()
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
        Me.lblTitle.Text = "パーツ展開・在庫引当"
        Me.lblTitle.TextAlign = System.Drawing.ContentAlignment.MiddleCenter
        '
        'Label1
        '
        Me.Label1.AutoSize = True
        Me.Label1.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.Label1.Location = New System.Drawing.Point(32, 96)
        Me.Label1.Name = "Label1"
        Me.Label1.Size = New System.Drawing.Size(103, 15)
        Me.Label1.TabIndex = 2
        Me.Label1.Text = "未処理データ数"
        '
        'txtMishori
        '
        Me.txtMishori.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.txtMishori.Location = New System.Drawing.Point(140, 92)
        Me.txtMishori.Name = "txtMishori"
        Me.txtMishori.ReadOnly = True
        Me.txtMishori.Size = New System.Drawing.Size(72, 22)
        Me.txtMishori.TabIndex = 3
        Me.txtMishori.TabStop = False
        Me.txtMishori.Text = ""
        Me.txtMishori.TextAlign = System.Windows.Forms.HorizontalAlignment.Right
        '
        'btnOK
        '
        Me.btnOK.Anchor = System.Windows.Forms.AnchorStyles.Bottom
        Me.btnOK.Location = New System.Drawing.Point(165, 160)
        Me.btnOK.Name = "btnOK"
        Me.btnOK.Size = New System.Drawing.Size(92, 24)
        Me.btnOK.TabIndex = 7
        Me.btnOK.Text = "実行(&E)"
        '
        'Label2
        '
        Me.Label2.AutoSize = True
        Me.Label2.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.Label2.Location = New System.Drawing.Point(264, 96)
        Me.Label2.Name = "Label2"
        Me.Label2.Size = New System.Drawing.Size(129, 15)
        Me.Label2.TabIndex = 5
        Me.Label2.Text = "在庫引当処理番号"
        '
        'txtShoriNo
        '
        Me.txtShoriNo.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.txtShoriNo.Location = New System.Drawing.Point(396, 92)
        Me.txtShoriNo.Name = "txtShoriNo"
        Me.txtShoriNo.ReadOnly = True
        Me.txtShoriNo.Size = New System.Drawing.Size(52, 22)
        Me.txtShoriNo.TabIndex = 6
        Me.txtShoriNo.TabStop = False
        Me.txtShoriNo.Text = ""
        Me.txtShoriNo.TextAlign = System.Windows.Forms.HorizontalAlignment.Right
        '
        'Label3
        '
        Me.Label3.AutoSize = True
        Me.Label3.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.Label3.Location = New System.Drawing.Point(220, 96)
        Me.Label3.Name = "Label3"
        Me.Label3.Size = New System.Drawing.Size(21, 15)
        Me.Label3.TabIndex = 4
        Me.Label3.Text = "件"
        '
        'btnClose
        '
        Me.btnClose.Anchor = System.Windows.Forms.AnchorStyles.Bottom
        Me.btnClose.DialogResult = System.Windows.Forms.DialogResult.Cancel
        Me.btnClose.Location = New System.Drawing.Point(274, 160)
        Me.btnClose.Name = "btnClose"
        Me.btnClose.Size = New System.Drawing.Size(92, 24)
        Me.btnClose.TabIndex = 8
        Me.btnClose.Text = "閉じる(&C)"
        '
        'GroupBox1
        '
        Me.GroupBox1.Anchor = ((System.Windows.Forms.AnchorStyles.Bottom Or System.Windows.Forms.AnchorStyles.Left) _
                    Or System.Windows.Forms.AnchorStyles.Right)
        Me.GroupBox1.Location = New System.Drawing.Point(7, 144)
        Me.GroupBox1.Name = "GroupBox1"
        Me.GroupBox1.Size = New System.Drawing.Size(516, 4)
        Me.GroupBox1.TabIndex = 11
        Me.GroupBox1.TabStop = False
        '
        'cmbKokyaku
        '
        Me.cmbKokyaku.DropDownStyle = System.Windows.Forms.ComboBoxStyle.DropDownList
        Me.cmbKokyaku.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.cmbKokyaku.Location = New System.Drawing.Point(72, 48)
        Me.cmbKokyaku.Name = "cmbKokyaku"
        Me.cmbKokyaku.Size = New System.Drawing.Size(124, 23)
        Me.cmbKokyaku.TabIndex = 13
        '
        'Label4
        '
        Me.Label4.AutoSize = True
        Me.Label4.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.Label4.Location = New System.Drawing.Point(32, 52)
        Me.Label4.Name = "Label4"
        Me.Label4.Size = New System.Drawing.Size(36, 15)
        Me.Label4.TabIndex = 12
        Me.Label4.Text = "顧客"
        '
        'OleDbDataAdapter1
        '
        Me.OleDbDataAdapter1.SelectCommand = Me.OleDbSelectCommand1
        Me.OleDbDataAdapter1.TableMappings.AddRange(New System.Data.Common.DataTableMapping() {New System.Data.Common.DataTableMapping("Table", "パーツ展開", New System.Data.Common.DataColumnMapping() {New System.Data.Common.DataColumnMapping("顧客コード", "顧客コード"), New System.Data.Common.DataColumnMapping("受注番号", "受注番号"), New System.Data.Common.DataColumnMapping("商品名称", "商品名称"), New System.Data.Common.DataColumnMapping("受注数量", "受注数量"), New System.Data.Common.DataColumnMapping("在庫引落日時", "在庫引落日時"), New System.Data.Common.DataColumnMapping("在庫引当処理番号", "在庫引当処理番号"), New System.Data.Common.DataColumnMapping("パーツ1", "パーツ1"), New System.Data.Common.DataColumnMapping("数量1", "数量1"), New System.Data.Common.DataColumnMapping("パーツ2", "パーツ2"), New System.Data.Common.DataColumnMapping("数量2", "数量2"), New System.Data.Common.DataColumnMapping("パーツ3", "パーツ3"), New System.Data.Common.DataColumnMapping("数量3", "数量3"), New System.Data.Common.DataColumnMapping("パーツ4", "パーツ4"), New System.Data.Common.DataColumnMapping("数量4", "数量4"), New System.Data.Common.DataColumnMapping("パーツ5", "パーツ5"), New System.Data.Common.DataColumnMapping("数量5", "数量5"), New System.Data.Common.DataColumnMapping("パーツ6", "パーツ6"), New System.Data.Common.DataColumnMapping("数量6", "数量6"), New System.Data.Common.DataColumnMapping("パーツ7", "パーツ7"), New System.Data.Common.DataColumnMapping("数量7", "数量7"), New System.Data.Common.DataColumnMapping("パーツ8", "パーツ8"), New System.Data.Common.DataColumnMapping("数量8", "数量8"), New System.Data.Common.DataColumnMapping("パーツ9", "パーツ9"), New System.Data.Common.DataColumnMapping("数量9", "数量9"), New System.Data.Common.DataColumnMapping("パーツ10", "パーツ10"), New System.Data.Common.DataColumnMapping("数量10", "数量10"), New System.Data.Common.DataColumnMapping("パーツ11", "パーツ11"), New System.Data.Common.DataColumnMapping("数量11", "数量11"), New System.Data.Common.DataColumnMapping("パーツ12", "パーツ12"), New System.Data.Common.DataColumnMapping("数量12", "数量12"), New System.Data.Common.DataColumnMapping("パーツ13", "パーツ13"), New System.Data.Common.DataColumnMapping("数量13", "数量13"), New System.Data.Common.DataColumnMapping("パーツ14", "パーツ14"), New System.Data.Common.DataColumnMapping("数量14", "数量14"), New System.Data.Common.DataColumnMapping("パーツ15", "パーツ15"), New System.Data.Common.DataColumnMapping("数量15", "数量15")})})
        '
        'OleDbSelectCommand1
        '
        Me.OleDbSelectCommand1.CommandText = "SELECT 受注データ.受注番号, 受注データ.商品名称, 受注データ.受注数量, パーツ展開マスタ.パーツ1, パーツ展開マスタ.数量1, パーツ展開マスタ." & _
        "パーツ2, パーツ展開マスタ.数量2, パーツ展開マスタ.パーツ3, パーツ展開マスタ.数量3, パーツ展開マスタ.パーツ4, パーツ展開マスタ.数量4, パー" & _
        "ツ展開マスタ.パーツ5, パーツ展開マスタ.数量5, パーツ展開マスタ.パーツ6, パーツ展開マスタ.数量6, パーツ展開マスタ.パーツ7, パーツ展開マスタ." & _
        "数量7, パーツ展開マスタ.パーツ8, パーツ展開マスタ.数量8, パーツ展開マスタ.パーツ9, パーツ展開マスタ.数量9, パーツ展開マスタ.パーツ10, パ" & _
        "ーツ展開マスタ.数量10, パーツ展開マスタ.パーツ11, パーツ展開マスタ.数量11, パーツ展開マスタ.パーツ12, パーツ展開マスタ.数量12, パーツ展" & _
        "開マスタ.パーツ13, パーツ展開マスタ.数量13, パーツ展開マスタ.パーツ14, パーツ展開マスタ.数量14, パーツ展開マスタ.パーツ15, パーツ展開マ" & _
        "スタ.数量15 FROM 受注データ INNER JOIN パーツ展開マスタ ON 受注データ.標準商品名称 = パーツ展開マスタ.商品名称 WHERE (受注" & _
        "データ.顧客コード = ?) AND (受注データ.在庫引当処理番号 = ?)"
        Me.OleDbSelectCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("顧客コード", System.Data.OleDb.OleDbType.VarWChar, 20, "顧客コード"))
        Me.OleDbSelectCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("在庫引当処理番号", System.Data.OleDb.OleDbType.Integer, 0, System.Data.ParameterDirection.Input, False, CType(10, Byte), CType(0, Byte), "在庫引当処理番号", System.Data.DataRowVersion.Current, Nothing))
        '
        'DsWork1
        '
        Me.DsWork1.DataSetName = "dsWork"
        Me.DsWork1.Locale = New System.Globalization.CultureInfo("en-US")
        Me.DsWork1.Namespace = "http://tempuri.org/Work.xsd"
        '
        'frmPartsTenkai
        '
        Me.AutoScaleBaseSize = New System.Drawing.Size(5, 12)
        Me.ClientSize = New System.Drawing.Size(530, 195)
        Me.Controls.AddRange(New System.Windows.Forms.Control() {Me.cmbKokyaku, Me.Label4, Me.Label1, Me.Label2, Me.Label3, Me.GroupBox1, Me.btnOK, Me.txtMishori, Me.lblTitle, Me.txtShoriNo, Me.btnClose})
        Me.DockPadding.Top = 2
        Me.FormBorderStyle = System.Windows.Forms.FormBorderStyle.FixedSingle
        Me.MaximizeBox = False
        Me.MinimizeBox = False
        Me.Name = "frmPartsTenkai"
        Me.ShowInTaskbar = False
        Me.StartPosition = System.Windows.Forms.FormStartPosition.CenterScreen
        Me.Text = "PartsTenkai"
        CType(Me.DsWork1, System.ComponentModel.ISupportInitialize).EndInit()
        Me.ResumeLayout(False)

    End Sub

#End Region

    '*** フォームロードイベント
    Private Sub PartsTenkai_Load(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MyBase.Load
        Me.Text = Common.TITLE
        KokyakuSelect(cmbKokyaku)
        Me.txtShoriNo.Text = GetNextShoriNo()

        SetDbConnection(OleDbDataAdapter1)
    End Sub

    '*** [実行]ボタンクリックイベント
    Private Sub btnOK_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnOK.Click
        '顧客選択チェック
        If Me.cmbKokyaku.SelectedIndex = 0 Then
            MsgBox("顧客を選択してください。", MsgBoxStyle.OKOnly Or MsgBoxStyle.Information, APPNAME)
            Me.cmbKokyaku.Focus()
            Return
        End If

        '未処理データ数チェック
        If Me.txtMishori.Text = 0 Then
            MsgBox("処理するデータがありません。", MsgBoxStyle.OKOnly Or MsgBoxStyle.Information, APPNAME)
            Return
        End If

        '受注データとパーツ展開マスタ読み取り
        OleDbDataAdapter1.SelectCommand.Parameters("顧客コード").Value = Me.cmbKokyaku.SelectedValue
        OleDbDataAdapter1.SelectCommand.Parameters("在庫引当処理番号").Value = 0
        OleDbDataAdapter1.Fill(DsWork1)

        'パーツ在庫引当処理
        Dim dtPartsHikiete As dsWork.パーツ在庫引当DataTable
        dtPartsHikiete = PartsZaikoHikiate(DsWork1.パーツ展開)

        'トランザクション作成
        Dim trans As OleDb.OleDbTransaction
        trans = dbConnection.BeginTransaction()

        '受注データ更新SQL
        Dim dbcJuchuUpdate As New OleDb.OleDbCommand("UPDATE 受注データ SET 在庫引落日時=?, 在庫引当処理番号=? WHERE 顧客コード=? AND 受注番号=?", dbConnection, trans)
        dbcJuchuUpdate.Parameters.Add("在庫引落日時", Data.OleDb.OleDbType.DBDate)
        dbcJuchuUpdate.Parameters.Add("在庫引当処理番号", Data.OleDb.OleDbType.Integer)
        dbcJuchuUpdate.Parameters.Add("顧客コード", Data.OleDb.OleDbType.VarWChar)
        dbcJuchuUpdate.Parameters.Add("受注番号", Data.OleDb.OleDbType.VarWChar)

        'パーツ在庫マスタ選択SQL
        Dim dbcZaikoSelect As New OleDb.OleDbCommand("SELECT 現在庫数, 発注点数量, 発注点警告日, 欠品になった日 FROM パーツ在庫マスタ WHERE パーツ名称=?", dbConnection, trans)
        dbcZaikoSelect.Parameters.Add("パーツ名称", Data.OleDb.OleDbType.VarWChar)

        'パーツ在庫マスタ更新SQL
        Dim dbcZaikoUpdate As New OleDb.OleDbCommand("UPDATE パーツ在庫マスタ SET 現在庫数=?, 発注点警告日 = ?, 欠品になった日=? WHERE パーツ名称=?", dbConnection, trans)
        dbcZaikoUpdate.Parameters.Add("現在庫数", Data.OleDb.OleDbType.Integer)
        dbcZaikoUpdate.Parameters.Add("発注点警告日", Data.OleDb.OleDbType.DBDate)
        dbcZaikoUpdate.Parameters.Add("欠品になった日", Data.OleDb.OleDbType.DBDate)
        dbcZaikoUpdate.Parameters.Add("パーツ名称", Data.OleDb.OleDbType.VarWChar)

        '在庫変更履歴追加SQL
        Dim dbcRirekiInsert As New OleDb.OleDbCommand("INSERT INTO 在庫変更履歴 (パーツ名称, 処理日時, 入出庫区分, 数量) VALUES(?, ?, ?, ?)", dbConnection, trans)
        dbcRirekiInsert.Parameters.Add("パーツ名称", Data.OleDb.OleDbType.VarWChar)
        dbcRirekiInsert.Parameters.Add("処理日時", Data.OleDb.OleDbType.DBDate)
        dbcRirekiInsert.Parameters.Add("入出庫区分", Data.OleDb.OleDbType.VarWChar)
        dbcRirekiInsert.Parameters.Add("数量", Data.OleDb.OleDbType.Integer)

        'DB更新処理
        Dim dtNow As DateTime = Now
        Try
            '受注データ更新
            Dim rowTenkai As dsWork.パーツ展開Row
            For Each rowTenkai In DsWork1.パーツ展開.Rows
                dbcJuchuUpdate.Parameters("顧客コード").Value = cmbKokyaku.SelectedValue
                dbcJuchuUpdate.Parameters("受注番号").Value = rowTenkai.受注番号
                dbcJuchuUpdate.Parameters("在庫引落日時").Value = dtNow
                dbcJuchuUpdate.Parameters("在庫引当処理番号").Value = Me.txtShoriNo.Text
                dbcJuchuUpdate.ExecuteNonQuery()
            Next

            'パーツ在庫マスタ更新、在庫変更履歴追加
            Dim zaikoOld, zaikoNew, hacchuuten As Integer
            Dim dtrZaiko As OleDb.OleDbDataReader
            Dim rowHikiate As dsWork.パーツ在庫引当Row
            For Each rowHikiate In dtPartsHikiete.Rows
                '在庫数取得
                dbcZaikoSelect.Parameters("パーツ名称").Value = rowHikiate.パーツ名称
                dtrZaiko = dbcZaikoSelect.ExecuteReader()
                dtrZaiko.Read()
                zaikoOld = dtrZaiko("現在庫数")
                zaikoNew = zaikoOld - rowHikiate.引当数

                '発注点警告チェック
                If Not dtrZaiko("発注点数量") Is DBNull.Value Then
                    hacchuuten = dtrZaiko("発注点数量")
                    If zaikoNew >= hacchuuten Then
                        dbcZaikoUpdate.Parameters("発注点警告日").Value = DBNull.Value
                    ElseIf dtrZaiko("発注点警告日") Is DBNull.Value Or (zaikoOld >= hacchuuten And zaikoNew < hacchuuten) Then
                        dbcZaikoUpdate.Parameters("発注点警告日").Value = dtNow
                    Else
                        dbcZaikoUpdate.Parameters("発注点警告日").Value = dtrZaiko("発注点警告日")
                    End If
                End If

                '欠品チェック
                If zaikoNew >= 0 Then
                    dbcZaikoUpdate.Parameters("欠品になった日").Value = DBNull.Value
                ElseIf dtrZaiko("欠品になった日") Is DBNull.Value Or (zaikoOld >= 0 And zaikoNew < 0) Then
                    dbcZaikoUpdate.Parameters("欠品になった日").Value = dtNow
                Else
                    dbcZaikoUpdate.Parameters("欠品になった日").Value = dtrZaiko("欠品になった日")
                End If

                dtrZaiko.Close()

                'パーツ在庫マスタ更新
                dbcZaikoUpdate.Parameters("パーツ名称").Value = rowHikiate.パーツ名称
                dbcZaikoUpdate.Parameters("現在庫数").Value = zaikoNew
                dbcZaikoUpdate.ExecuteNonQuery()

                '在庫変更履歴追加
                dbcRirekiInsert.Parameters("パーツ名称").Value = rowHikiate.パーツ名称
                dbcRirekiInsert.Parameters("処理日時").Value = dtNow
                dbcRirekiInsert.Parameters("入出庫区分").Value = "02"
                dbcRirekiInsert.Parameters("数量").Value = -rowHikiate.引当数
                dbcRirekiInsert.ExecuteNonQuery()
            Next

            trans.Commit()
        Catch ex As Exception
            'DBエラー
            trans.Rollback()
            MsgBox("パーツ展開・在庫引当でエラーが発生しました。" & vbCrLf & vbCrLf & ex.Message, MsgBoxStyle.OKOnly Or MsgBoxStyle.Exclamation, APPNAME)
            Return
        End Try

        '完了メッセージ
        MsgBox("パーツ展開・在庫引当を実行しました。", MsgBoxStyle.OKOnly Or MsgBoxStyle.Information, APPNAME)
        Me.DialogResult = DialogResult.OK
    End Sub

    '*** 次の在庫引当処理番号取得処理
    Private Function GetNextShoriNo() As Integer
        Dim dbCmd As New OleDb.OleDbCommand()

        dbCmd.Connection = Common.dbConnection
        dbCmd.CommandType = CommandType.Text
        dbCmd.CommandText = "SELECT MAX(在庫引当処理番号) FROM 受注データ"

        Dim shoriNo As Object = dbCmd.ExecuteScalar()
        If shoriNo Is DBNull.Value Then
            Return 1
        Else
            Return shoriNo + 1
        End If
    End Function

    '*** 顧客ドロップダウンリスト選択イベント
    Private Sub cmbKokyaku_SelectedIndexChanged(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles cmbKokyaku.SelectedIndexChanged
        If cmbKokyaku.SelectedIndex <> 0 Then
            '未処理データ数取得SQL
            Dim dbCmd As New OleDb.OleDbCommand()
            dbCmd.Connection = Common.dbConnection
            dbCmd.CommandType = CommandType.Text
            dbCmd.CommandText = "SELECT COUNT(*) FROM 受注データ WHERE 顧客コード=? AND 在庫引当処理番号=0"
            dbCmd.Parameters.Add("顧客コード", Me.cmbKokyaku.SelectedValue)

            '未処理データ数表示
            Me.txtMishori.Text = dbCmd.ExecuteScalar()
        Else
            Me.txtMishori.Text = ""
        End If
    End Sub
End Class
