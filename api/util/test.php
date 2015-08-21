<?php
function getMonthMsg($date){
    $date_array = explode('-',$date);
    $first_day = mktime(0,0,0,$date_array[1],1,$date_array[0]);
    $last_day = strtotime('+1 month',$first_day);
    for($i=$first_day;$i<$last_day;$i=$i+24*60*60){
        echo date('Y-m-d',$i)."<br>";
    }
}

getMonthMsg('2015-07-31');

?>