Module Common
    '定数定義
    Public Const APPNAME = "在庫管理システム"
    Public Const VERSION = "Ver0.70"
    Public Const TITLE = APPNAME & " " & VERSION
    Public Const COPYRIGHT = "Copyright (C) 2003 株式会社ケーアイ精機 All Rights Reserved."

    'DBコネクション
    Public dbConnection As OleDb.OleDbConnection

    'ユーザレベル
    Public userLevel As Integer

    'デバッグモード
    Public debugMode As Boolean

    '*** アプリケーション開始処理
    Sub main()
        Dim frm As Form
        Dim args As String
        Dim dbName As String = "Zaiko.mdb"

        'コマンドライン引数チェック
        For Each args In System.Environment.GetCommandLineArgs()
            If args.Substring(0, 3) = "db=" Then
                dbName = args.Substring(3)
            ElseIf args = "debug" Then
                debugMode = True
            End If
        Next

        'DBに接続
        Try
            dbConnection = New OleDb.OleDbConnection()
            dbConnection.ConnectionString = "Provider=Microsoft.Jet.OLEDB.4.0;Data Source=" & dbName
            dbConnection.Open()
        Catch ex As Exception
            MsgBox("データベースに接続できませんでした。" & vbCrLf & vbCrLf & ex.Message, MsgBoxStyle.OKOnly Or MsgBoxStyle.Critical, APPNAME)
            Return
        End Try

        'ログインダイアログを表示
        Try
            frm = New zaiko.frmLogin()
            If frm.ShowDialog() = DialogResult.OK Then
                'ログインOKならメニューを表示
                frm.Dispose()
                frm = New frmMenu()
                frm.ShowDialog()
            End If
        Catch ex As Exception
            MsgBox("システムエラーが発生しました。" & vbCrLf & vbCrLf & ex.Message, MsgBoxStyle.OKOnly Or MsgBoxStyle.Critical, APPNAME)
            Return
        End Try

    End Sub

    '*** データアダプタにDBコネクションを設定
    Sub SetDbConnection(ByRef dataAdapter As OleDb.OleDbDataAdapter)
        If Not dataAdapter.SelectCommand Is Nothing Then
            dataAdapter.SelectCommand.Connection = dbConnection
        End If

        If Not dataAdapter.InsertCommand Is Nothing Then
            dataAdapter.InsertCommand.Connection = dbConnection
        End If

        If Not dataAdapter.UpdateCommand Is Nothing Then
            dataAdapter.UpdateCommand.Connection = dbConnection
        End If

        If Not dataAdapter.DeleteCommand Is Nothing Then
            dataAdapter.DeleteCommand.Connection = dbConnection
        End If
    End Sub

    '*** パーツ種別選択ドロップダウンリスト
    Sub PartsShubetsuSelect(ByRef cmb As ComboBox)
        Dim dataAdapter As New OleDb.OleDbDataAdapter("SELECT * FROM パーツ種別マスタ ORDER BY パーツ種別コード", dbConnection)
        Dim dataSet As New dsTable()

        dataSet.パーツ種別マスタ.Addパーツ種別マスタRow("", "選択してください")
        dataAdapter.Fill(dataSet, "パーツ種別マスタ")
        cmb.DataSource = dataSet.パーツ種別マスタ
        cmb.DisplayMember = "パーツ種別名"
        cmb.ValueMember = "パーツ種別コード"
    End Sub

    '*** 顧客選択ドロップダウンリスト
    Sub KokyakuSelect(ByRef cmb As ComboBox)
        Dim dataAdapter As New OleDb.OleDbDataAdapter("SELECT * FROM 顧客マスタ ORDER BY 並び順", dbConnection)
        Dim dataSet As New dsTable()

        dataSet.顧客マスタ.Add顧客マスタRow(0, "", "選択してください")
        dataAdapter.Fill(dataSet, "顧客マスタ")
        cmb.DataSource = dataSet.顧客マスタ
        cmb.DisplayMember = "顧客名"
        cmb.ValueMember = "顧客コード"
    End Sub

    '*** 印刷プレビュー表示
    Sub ShowPrintForm(ByVal title As String, ByRef report As ReportClass, ByRef dataSet As DataSet)
        Dim frm As New zaiko.frmPrint()

        frm.Text = title
        report.SetDataSource(dataSet)
        frm.crvPrint.ReportSource = report
        frm.crvPrint.DisplayGroupTree = False
        frm.ShowDialog()
    End Sub

    '*** パーツ展開処理
    Function PartsZaikoHikiate(ByVal dtTenkai As dsWork.パーツ展開DataTable) As dsWork.パーツ在庫引当DataTable
        Dim dtHikiate As New dsWork.パーツ在庫引当DataTable()
        Dim rowTenkai As dsWork.パーツ展開Row
        Dim rowHikiate As dsWork.パーツ在庫引当Row
        Dim i As Integer
        Dim parts As String
        Dim suuryou As Integer

        For Each rowTenkai In dtTenkai
            For i = 1 To 15
                If Not rowTenkai.IsNull("パーツ" & i) AndAlso Not rowTenkai.IsNull("数量" & i) Then
                    parts = rowTenkai("パーツ" & i)
                    suuryou = rowTenkai("数量" & i) * rowTenkai.受注数量

                    rowHikiate = dtHikiate.Rows.Find(parts)
                    If rowHikiate Is Nothing Then
                        dtHikiate.Addパーツ在庫引当Row(parts, suuryou)
                    Else
                        rowHikiate.引当数 += suuryou
                    End If
                End If
            Next
        Next

        Return dtHikiate
    End Function

    '*** 商品名称存在チェック
    Function ShouhinCheck(ByVal shouhin As String) As string
        '商品名称チェック用SQL
        Dim dbCmd As New OleDb.OleDbCommand()
        dbCmd.Connection = dbConnection
        dbCmd.CommandType = CommandType.Text
        dbCmd.CommandText = "SELECT COUNT(*) FROM パーツ展開マスタ WHERE 商品名称=?"
        dbCmd.Parameters.Add("商品名称", Data.OleDb.OleDbType.WChar)

        dbCmd.Parameters("商品名称").Value = shouhin
        If CType(dbCmd.ExecuteScalar(), Integer) = 0 Then
            '無かった場合、最後のハイフン以降を削除して再度検索
            Dim n As Integer
            Dim s As String
            n = shouhin.LastIndexOf("-")
            If n > 0 Then
                shouhin = shouhin.Substring(0, n)
                dbCmd.Parameters("商品名称").Value = shouhin
                If CType(dbCmd.ExecuteScalar(), Integer) = 0 Then
                    shouhin = Nothing
                End If
            Else
                shouhin = Nothing
            End If
        End If

        Return shouhin
    End Function

    '*** テーブルに変更があったかどうかをチェック
    Function CheckTableUpdate(ByRef table As DataTable)
        Dim row As DataRow

        For Each row In table.Rows
            If row.RowState <> DataRowState.Unchanged Then
                Return True
            End If
        Next

        Return False
    End Function

    '*** リストボックス（コンボボックス）の選択データクラス
    Class ListSource
        Private display As String
        Private value As String

        Sub New(ByVal disp As String, ByVal val As String)
            Me.display = disp
            Me.value = val
        End Sub

        Public Property DisplayMember()
            Get
                Return Me.display
            End Get
            Set(ByVal Value)
                Me.display = Value
            End Set
        End Property

        Public Property ValueMember()
            Get
                Return Me.value
            End Get
            Set(ByVal Value)
                Me.value = Value
            End Set
        End Property
    End Class
End Module
