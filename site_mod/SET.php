<?php /* ������ ����������, ������� ����� ����� ����� ������������ ������� �� �����

{_SET:
imya=������� ������
var123=��������
_}

{_SET:imya3_}, {_SET:var123_}!

*/

function SET($e) { global $article;
    // ������ ����� ������������� ����������
    if(false===strpos($e,'=')) { $e=c($e); return (isset($article['VAR'])&&isset($article['VAR'][$e])?$article['VAR'][$e]:''); }
    // ��������� ����������
    $c=parse_e_conf($e); $article['VAR']=(isset($article['VAR'])?array_merge($article['VAR'],$c):$c); return '';
}
?>