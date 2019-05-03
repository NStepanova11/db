<?php

include '../db/database.php';
//todo: вывод сообщений о неверных данных форм
function check_user_age($age){
    $correct_age = false;
    if($age>=15 && $age<=80){
        $correct_age = true;
    }
    return $correct_age;
}

function check_user_height($height){
    $correct_height = false;
    if($height>=150 && $height<=190){
        $correct_height = true;
    }
    return $correct_height;
}

function check_user_weight($weight){
    $correct_weight = false;
    if($weight>=50 && $weight<200){
        $correct_weight = true;
    }
    return $correct_weight;
}

function check_user_params($age, $height, $weight){   
    $params_checked=true;
    if(!check_user_age($age) || !check_user_height($height) || !check_user_weight($weight))
        $params_checked = false;
    return $params_checked;
}
?>