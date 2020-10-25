Module JuchuFile

    '*** 受注データファイル読み取り
    Function ReadJuchuFile(ByRef juchuFile As String, ByVal kokyakuCode As String, ByRef errList As String) As Boolean

        Dim ok As Boolean = True

        Dim oCon As New OleDb.OleDbConnection( _
            "Provider=Microsoft.Jet.OLEDB.4.0;" & _
            "Data Source=" & IO.Path.GetDirectoryName(juchuFile) & ";" & _
            "Extended Properties=""text;HDR=No;FMT=Delimited"";")
        Dim oCmd As System.Data.OleDb.OleDbDataAdapter = _
            New System.Data.OleDb.OleDbDataAdapter("select * from " & IO.Path.GetFileName(juchuFile), oCon)

        Dim dsJuchu As dsWork = New dsWork()
        Try
            oCmd.Fill(dsJuchu, "受注ファイル")
        Catch ex As Exception
            errList = "受注ファイルのフォーマットが正しくないため、受注データの取り込みができませんでした。"
            Return False
        End Try
        oCon.Close()

        '受注番号チェック用SQL
        Dim dbcJuchuSelect As New OleDb.OleDbCommand()
        dbcJuchuSelect.Connection = Common.dbConnection
        dbcJuchuSelect.CommandType = CommandType.Text
        dbcJuchuSelect.CommandText = "SELECT COUNT(*) FROM 受注データ WHERE 顧客コード=? AND 受注番号=?"
        dbcJuchuSelect.Parameters.Add("顧客コード", Data.OleDb.OleDbType.VarWChar)
        dbcJuchuSelect.Parameters.Add("受注番号", Data.OleDb.OleDbType.VarWChar)

        '受注データ追加SQL
        Dim dbcJuchuInsert As New OleDb.OleDbCommand("INSERT INTO 受注データ (顧客コード, 受注番号, 受注日, 納期, 商品名称, 受注数量, 標準商品名称) VALUES(?, ?, ?, ?, ?, ?, ?)", dbConnection)
        dbcJuchuInsert.Parameters.Add("顧客コード", Data.OleDb.OleDbType.VarWChar)
        dbcJuchuInsert.Parameters.Add("受注番号", Data.OleDb.OleDbType.VarWChar)
        dbcJuchuInsert.Parameters.Add("受注日", Data.OleDb.OleDbType.DBDate)
        dbcJuchuInsert.Parameters.Add("納期", Data.OleDb.OleDbType.DBDate)
        dbcJuchuInsert.Parameters.Add("商品名称", Data.OleDb.OleDbType.VarWChar)
        dbcJuchuInsert.Parameters.Add("受注数量", Data.OleDb.OleDbType.Integer)
        dbcJuchuInsert.Parameters.Add("標準商品名称", Data.OleDb.OleDbType.VarWChar)

        Dim shouhin As String
        Dim errFlag As Boolean
        Dim errMsg As String
        Dim lineNo As Integer = 0
        Dim row As dsWork.受注ファイルRow
        For Each row In dsJuchu.受注ファイル.Rows
            errFlag = False
            errMsg = ""
            lineNo += 1

            '受注日チェック
            If Not row("F1") Is DBNull.Value Then
                '受注日チェック
                If Not IsDate(row("F1")) Then
                    errMsg = "受注日が正しくありません。"
                    errFlag = True
                End If

                '受注番号チェック
                If Not errFlag Then
                    If row("F2") Is DBNull.Value OrElse Not IsNumeric(row("F2")) OrElse CType(row("F2"), String).Length > 15 Then
                        errMsg = "受注番号が正しくありません。"
                        errFlag = True
                    Else
                        dbcJuchuSelect.Parameters("顧客コード").Value = kokyakuCode
                        dbcJuchuSelect.Parameters("受注番号").Value = row("F2")
                        If CType(dbcJuchuSelect.ExecuteScalar(), Integer) <> 0 Then
                            errMsg = "受注番号はすでに登録されています。"
                            errFlag = True
                        End If
                    End If
                End If


                '商品名称チェック
                If Not errFlag Then
                    If row("F3") Is DBNull.Value Then
                        errMsg = "商品名称が正しくありません。"
                        errFlag = True
                    Else
                        shouhin = ShouhinCheck(row("F3"))
                        If shouhin Is Nothing Then
                            errMsg = "商品名称が登録されていません。"
                            errFlag = True
                        End If
                    End If
                End If

                '受注数量チェック
                If Not errFlag Then
                    If row("F4") Is DBNull.Value OrElse Not IsNumeric(row("F4")) Then
                        errMsg = "受注数量が正しくありません。"
                        errFlag = True
                    End If
                End If

                '納期チェック
                If Not errFlag Then
                    If Not IsDate(row("F5")) Then
                        errMsg = "納期が正しくありません。"
                        errFlag = True
                    End If
                End If

                '受注テーブルに保存
                If Not errFlag Then
                    Try
                        dbcJuchuInsert.Parameters("顧客コード").Value = kokyakuCode
                        dbcJuchuInsert.Parameters("受注番号").Value = row("F2")
                        dbcJuchuInsert.Parameters("受注日").Value = row("F1")
                        dbcJuchuInsert.Parameters("納期").Value = row("F5")
                        dbcJuchuInsert.Parameters("商品名称").Value = row("F3")
                        dbcJuchuInsert.Parameters("受注数量").Value = row("F4")
                        dbcJuchuInsert.Parameters("標準商品名称").Value = shouhin
                        dbcJuchuInsert.ExecuteNonQuery()
                    Catch ex As Exception
                        errMsg = "データベースの書き込みに失敗しました。"
                        errFlag = True
                    End Try
                End If

                'エラーメッセージ
                If errFlag Then
                    errList &= "行番号：" & lineNo & vbCrLf
                    errList &= "受注番号：" & row("F2") & vbCrLf
                    errList &= "商品名称：" & row("F3") & vbCrLf
                    errList &= "エラー内容：" & errMsg & vbCrLf & vbCrLf
                    ok = False
                End If

            End If
        Next

        Return ok

    End Function

End Module
