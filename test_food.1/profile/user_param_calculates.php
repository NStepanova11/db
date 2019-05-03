<?php

    function calculate_base_metabolism($age, $height, $weight, $gender, $lifestyle_code){
        $bmr=0;

        if ($gender == 'm'){
            $bmr = 88.362+(13.397*$weight)+(4.799*$height)-(5.677*$age);   
        }
        else if ($gender == 'f'){
            $bmr = 447.593+(9.247*$weight)+(3.098*$height)-(4.330*$age);   
        }
        $bmr*=lifestyle_arr[$lifestyle_code];
        return $bmr;
    }

    function calculate_norm_for_purpose($purpose, $bmr){
        $total_ccal = 0;
        $per_from_bmr = $bmr/100*15;
        $proteins_gramm=0;
        $fat_gramm=0;
        $carbo_gramm=0;
        
        if(purpose_code[$purpose] == 'support'){
            $total_ccal = $bmr;
            $proteins_gramm = ($total_ccal/100*support_persent['p'])/proteins_ccal_to_gr;
            $fat_gramm = ($total_ccal/100*support_persent['f'])/fats_ccal_to_gr;
            $carbo_gramm = ($total_ccal/100*support_persent['c'])/carbo_ccal_to_gr;
        }
        else if (purpose_code[$purpose] == 'up'){
            $total_ccal = $bmr+$per_from_bmr;
            $proteins_gramm = ($total_ccal/100*up_persent['p'])/proteins_ccal_to_gr;
            $fat_gramm = ($total_ccal/100*up_persent['f'])/fats_ccal_to_gr;
            $carbo_gramm = ($total_ccal/100*up_persent['c'])/carbo_ccal_to_gr;
        }
        else if (purpose_code[$purpose] == 'down'){
            $total_ccal = $bmr-$per_from_bmr;
            $proteins_gramm = ($total_ccal/100*down_persent['p'])/proteins_ccal_to_gr;
            $fat_gramm = ($total_ccal/100*down_persent['f'])/fats_ccal_to_gr;
            $carbo_gramm = ($total_ccal/100*down_persent['c'])/carbo_ccal_to_gr;
        }

        $user_normal = array(
            'ccal'=>$total_ccal,
            'p_gr'=>$proteins_gramm,
            'f_gr'=>$fat_gramm,
            "c_gr"=>$carbo_gramm
        );
        return($user_normal);
    }

    function calculate_water($weight){
        return $weight*35;
    }

    function calculate_user_params($age, $height, $weight, $gender, $lifestyle_code, $purpose){
        $bmr = calculate_base_metabolism($age, $height, $weight, $gender, $lifestyle_code);
        $water = calculate_water($weight);
        $param_arr = calculate_norm_for_purpose($purpose, $bmr);
        $param_arr+=['water'=>$water];
        return $param_arr;
    }

    function load_user_params_from_db(){
        $mysqli = create_db_connection();
        $sql = "SELECT 
                        user.birth, 
                        user.height, 
                        result.current_weight, 
                        result.normal_ccal, 
                        result.normal_water, 
                        user.id_user, 
                        user.gender, 
                        user.lifestyle_code, 
                        user.purpose
                FROM user 
                LEFT JOIN result ON user.id_user=result.id_user 
                WHERE result.id_result=(SELECT MAX(result.id_result) from result)";
        $result = $mysqli->query($sql) or die("Ошибка " . mysqli_error($mysqli));
        $mysqli->close();
        return $result;
    }
    
    function load_user_normal_for_height($height, $gender){
        $mysqli = create_db_connection();
        $sql = "SELECT id_normal, min_weight, max_weight FROM normal WHERE gender='$gender' AND height=$height";
        $result = $mysqli->query($sql) or die("Ошибка " . mysqli_error($mysqli));
        $row=mysqli_fetch_row($result);
        $normal = array(
            'id_normal'=>$row[0],
            'min_weight'=>$row[1],
            'max_weight'=>$row[2]
        );
    
        $mysqli->close();
        return $normal;
    }
    
    function update_user_table($age, $id_normal, $id_user, $height, $gender, $purpose, $lifestyle){
        $mysqli = create_db_connection();
        $sql = "SELECT * FROM user";
        $result = $mysqli->query($sql) or die("Ошибка " . mysqli_error($mysqli));
        if ($result->num_rows!=0){
            $sql = "UPDATE user SET birth=$age, id_normal=$id_normal, lifestyle_code=$lifestyle, purpose=$purpose, gender='$gender' WHERE id_user = $id_user";
            $result = $mysqli->query($sql) or die("Ошибка " . mysqli_error($mysqli));
        }
        else{
            $sql = "INSERT INTO user(id_user, birth, gender, id_normal, height, lifestyle_code, purpose) VALUES ($id_user, $age, '$gender', $id_normal, $height, $lifestyle, $purpose)";
            $result = $mysqli->query($sql) or die("Ошибка " . mysqli_error($mysqli)); 
        }
        $mysqli->close();
    }
    
    function add_new_result($user_id, $weight, $ccal_norm, $water_norm){
        $mysqli = create_db_connection();
        $sql = "INSERT INTO result(id_user, date, current_weight, normal_ccal, normal_water) 
        VALUES ($user_id, NOW(), $weight, $ccal_norm , $water_norm)";
        $result = $mysqli->query($sql) or die("Ошибка " . mysqli_error($mysqli));
        $mysqli->close();
    }

    function get_text_style($weight, $min_weight, $max_weight){
        $text_style ="text-success";
        if ($weight<$min_weight || $weight>$max_weight)
            $text_style="text-danger";
        return $text_style;
    }

    function get_weight_status($weight, $min_weight, $max_weight){
        $status="Вес соответстует норме";
        if ($weight<$min_weight)
            $status="Вес ниже нормы";
        else if ($weight>$max_weight)
            $status="Вес выше нормы";
        return $status;
    }
?>