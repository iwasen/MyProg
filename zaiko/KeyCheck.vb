Module KeyCheck

    '*** �������ړ��͐����p�C�x���g�n���h��
    Public Sub KeyPress_NumOnly(ByVal sender As Object, ByVal e As System.Windows.Forms.KeyPressEventArgs)
        If Not (e.KeyChar >= "0" And e.KeyChar <= "9" _
                Or Char.IsControl(e.KeyChar)) Then
            Beep()
            e.Handled = True
        End If
    End Sub

    '*** �p�������ړ��͐����p�C�x���g�n���h��
    Public Sub KeyPress_AlphaNum(ByVal sender As Object, ByVal e As System.Windows.Forms.KeyPressEventArgs)
        If Not (e.KeyChar >= "0" And e.KeyChar <= "9" _
                Or e.KeyChar >= "A" And e.KeyChar <= "Z" _
                Or e.KeyChar >= "a" And e.KeyChar <= "z" _
                Or Char.IsControl(e.KeyChar)) Then
            Beep()
            e.Handled = True
        End If
    End Sub

    '*** ���i���̓��͐����p�C�x���g�n���h��
    Public Sub KeyPress_ShouhinMeishou(ByVal sender As Object, ByVal e As System.Windows.Forms.KeyPressEventArgs)
        If Not (e.KeyChar >= "0" And e.KeyChar <= "9" _
                Or e.KeyChar >= "A" And e.KeyChar <= "Z" _
                Or e.KeyChar >= "a" And e.KeyChar <= "z" _
                Or e.KeyChar = "-" _
                Or Char.IsControl(e.KeyChar)) Then
            Beep()
            e.Handled = True
        End If
    End Sub

    '*** �p�[�c���̓��͐����p�C�x���g�n���h��
    Public Sub KeyPress_PartsMeishou(ByVal sender As Object, ByVal e As System.Windows.Forms.KeyPressEventArgs)
        If Not (e.KeyChar >= "0" And e.KeyChar <= "9" _
                Or e.KeyChar >= "A" And e.KeyChar <= "Z" _
                Or e.KeyChar >= "a" And e.KeyChar <= "z" _
                Or e.KeyChar = "-" _
                Or Char.IsControl(e.KeyChar)) Then
            Beep()
            e.Handled = True
        End If
    End Sub

    '*** �ڋq�R�[�h���͐����p�C�x���g�n���h��
    Public Sub KeyPress_KokyakuCode(ByVal sender As Object, ByVal e As System.Windows.Forms.KeyPressEventArgs)
        If Not (e.KeyChar >= "0" And e.KeyChar <= "9" _
                Or e.KeyChar >= "A" And e.KeyChar <= "Z" _
                Or e.KeyChar >= "a" And e.KeyChar <= "z" _
                Or Char.IsControl(e.KeyChar)) Then
            Beep()
            e.Handled = True
        End If
    End Sub

    '*** ���t���͐����p�C�x���g�n���h��
    Public Sub KeyPress_Date(ByVal sender As Object, ByVal e As System.Windows.Forms.KeyPressEventArgs)
        If Not (e.KeyChar >= "0" And e.KeyChar <= "9" _
                Or e.KeyChar = "/" _
                Or Char.IsControl(e.KeyChar)) Then
            Beep()
            e.Handled = True
        End If
    End Sub

End Module
