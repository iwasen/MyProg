Module JuchuFile

    '*** �󒍃f�[�^�t�@�C���ǂݎ��
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
            oCmd.Fill(dsJuchu, "�󒍃t�@�C��")
        Catch ex As Exception
            errList = "�󒍃t�@�C���̃t�H�[�}�b�g���������Ȃ����߁A�󒍃f�[�^�̎�荞�݂��ł��܂���ł����B"
            Return False
        End Try
        oCon.Close()

        '�󒍔ԍ��`�F�b�N�pSQL
        Dim dbcJuchuSelect As New OleDb.OleDbCommand()
        dbcJuchuSelect.Connection = Common.dbConnection
        dbcJuchuSelect.CommandType = CommandType.Text
        dbcJuchuSelect.CommandText = "SELECT COUNT(*) FROM �󒍃f�[�^ WHERE �ڋq�R�[�h=? AND �󒍔ԍ�=?"
        dbcJuchuSelect.Parameters.Add("�ڋq�R�[�h", Data.OleDb.OleDbType.VarWChar)
        dbcJuchuSelect.Parameters.Add("�󒍔ԍ�", Data.OleDb.OleDbType.VarWChar)

        '�󒍃f�[�^�ǉ�SQL
        Dim dbcJuchuInsert As New OleDb.OleDbCommand("INSERT INTO �󒍃f�[�^ (�ڋq�R�[�h, �󒍔ԍ�, �󒍓�, �[��, ���i����, �󒍐���, �W�����i����) VALUES(?, ?, ?, ?, ?, ?, ?)", dbConnection)
        dbcJuchuInsert.Parameters.Add("�ڋq�R�[�h", Data.OleDb.OleDbType.VarWChar)
        dbcJuchuInsert.Parameters.Add("�󒍔ԍ�", Data.OleDb.OleDbType.VarWChar)
        dbcJuchuInsert.Parameters.Add("�󒍓�", Data.OleDb.OleDbType.DBDate)
        dbcJuchuInsert.Parameters.Add("�[��", Data.OleDb.OleDbType.DBDate)
        dbcJuchuInsert.Parameters.Add("���i����", Data.OleDb.OleDbType.VarWChar)
        dbcJuchuInsert.Parameters.Add("�󒍐���", Data.OleDb.OleDbType.Integer)
        dbcJuchuInsert.Parameters.Add("�W�����i����", Data.OleDb.OleDbType.VarWChar)

        Dim shouhin As String
        Dim errFlag As Boolean
        Dim errMsg As String
        Dim lineNo As Integer = 0
        Dim row As dsWork.�󒍃t�@�C��Row
        For Each row In dsJuchu.�󒍃t�@�C��.Rows
            errFlag = False
            errMsg = ""
            lineNo += 1

            '�󒍓��`�F�b�N
            If Not row("F1") Is DBNull.Value Then
                '�󒍓��`�F�b�N
                If Not IsDate(row("F1")) Then
                    errMsg = "�󒍓�������������܂���B"
                    errFlag = True
                End If

                '�󒍔ԍ��`�F�b�N
                If Not errFlag Then
                    If row("F2") Is DBNull.Value OrElse Not IsNumeric(row("F2")) OrElse CType(row("F2"), String).Length > 15 Then
                        errMsg = "�󒍔ԍ�������������܂���B"
                        errFlag = True
                    Else
                        dbcJuchuSelect.Parameters("�ڋq�R�[�h").Value = kokyakuCode
                        dbcJuchuSelect.Parameters("�󒍔ԍ�").Value = row("F2")
                        If CType(dbcJuchuSelect.ExecuteScalar(), Integer) <> 0 Then
                            errMsg = "�󒍔ԍ��͂��łɓo�^����Ă��܂��B"
                            errFlag = True
                        End If
                    End If
                End If


                '���i���̃`�F�b�N
                If Not errFlag Then
                    If row("F3") Is DBNull.Value Then
                        errMsg = "���i���̂�����������܂���B"
                        errFlag = True
                    Else
                        shouhin = ShouhinCheck(row("F3"))
                        If shouhin Is Nothing Then
                            errMsg = "���i���̂��o�^����Ă��܂���B"
                            errFlag = True
                        End If
                    End If
                End If

                '�󒍐��ʃ`�F�b�N
                If Not errFlag Then
                    If row("F4") Is DBNull.Value OrElse Not IsNumeric(row("F4")) Then
                        errMsg = "�󒍐��ʂ�����������܂���B"
                        errFlag = True
                    End If
                End If

                '�[���`�F�b�N
                If Not errFlag Then
                    If Not IsDate(row("F5")) Then
                        errMsg = "�[��������������܂���B"
                        errFlag = True
                    End If
                End If

                '�󒍃e�[�u���ɕۑ�
                If Not errFlag Then
                    Try
                        dbcJuchuInsert.Parameters("�ڋq�R�[�h").Value = kokyakuCode
                        dbcJuchuInsert.Parameters("�󒍔ԍ�").Value = row("F2")
                        dbcJuchuInsert.Parameters("�󒍓�").Value = row("F1")
                        dbcJuchuInsert.Parameters("�[��").Value = row("F5")
                        dbcJuchuInsert.Parameters("���i����").Value = row("F3")
                        dbcJuchuInsert.Parameters("�󒍐���").Value = row("F4")
                        dbcJuchuInsert.Parameters("�W�����i����").Value = shouhin
                        dbcJuchuInsert.ExecuteNonQuery()
                    Catch ex As Exception
                        errMsg = "�f�[�^�x�[�X�̏������݂Ɏ��s���܂����B"
                        errFlag = True
                    End Try
                End If

                '�G���[���b�Z�[�W
                If errFlag Then
                    errList &= "�s�ԍ��F" & lineNo & vbCrLf
                    errList &= "�󒍔ԍ��F" & row("F2") & vbCrLf
                    errList &= "���i���́F" & row("F3") & vbCrLf
                    errList &= "�G���[���e�F" & errMsg & vbCrLf & vbCrLf
                    ok = False
                End If

            End If
        Next

        Return ok

    End Function

End Module
