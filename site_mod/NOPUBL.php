<?php /* ������ ����������

{_NOPUBL:_} �����
*/

function NOPUBL($e) {
    if(!isset($GLOBALS['PUBL']) || isset($GLOBALS['rssmode'])) return '';
    idie('Publication restricted');
}
?>