<?php
    include "../profile/check_user_params.php";
    include "../profile/user_param_calculates.php";
    include "../profile/profile_const.php";

    function create_day_page($date){
        if (empty($date)){
            $date =date('y-m-d');
        }
        $mysqli = create_db_connection();
        $sql = "INSERT INTO eating (id_user, date_time) VALUES (1, '$date')";
        $mysqli->query($sql);
        $mysqli->close();
    }

    function calculate_user_normal(){
        $result = load_user_params_from_db();
        if($result->num_rows!=0){
            $row=mysqli_fetch_row($result);
            $age=$row[0]; 
            $height=$row[1];
            $weight=$row[2];
            $gender=$row[6];
            $lifestyle_code = $row[7];
            $purpose =$row[8];
           
            $params = calculate_user_params($age, $height, $weight, $gender, $lifestyle_code, $purpose);
            return $params;
        }
    }

    function calculate_for_one_product($product){
        $amount = (int)$product[7];
        $product_arr = array(
        'name' => $product[0],
        'amount' => $amount,
        'protein' => $product[1]/100*$amount,
        'fat' => $product[2]/100*$amount,
        'carbo' => $product[3]/100*$amount,
        'ccal' => $product[4]/100*$amount,
        'id_product'=> (int)$product[5],
        'id_eating'=> (int)$product[6],
        'pict_addr'=>$product[8],
        'id_code'=>$product[9]
        );
        if ($product_arr['pict_addr']==NULL){
            $product_arr['pict_addr'] = "../pict/dafault.png";
        }
        return $product_arr;
    }
  
    function calculate_products($date, $eatings_id){
        $all_prod_of_dinner = array();
        $eatings_list = select_eatings($date); //id приемов пищи за день
       foreach ($eatings_id as $eating){
            $product_list = select_products_from_eating($eating); //список продуктов из приема пищи (строки)
            $eating_arr = array('protein'=>0,'fat'=>0,'carbo'=>0,'ccal'=>0);
            while($product = mysqli_fetch_row($product_list)){ 
                $product_arr = array('name'=>"", 'protein'=>0,'fat'=>0,'carbo'=>0,'ccal'=>0, 'id_product'=>0, 'id_eating'=>0, 'amount'=>0, 'pict_addr'=>NULL, 'id_code'=>0);
                $product_arr = calculate_for_one_product($product);
                array_push($all_prod_of_dinner, $product_arr);
            }
        } 
        return $all_prod_of_dinner;
    }

//возвращает список массивов в котором общие бжук по приемам пищи
    function calculate_for_dinner($all_prod_of_dinner, $eatings_id){
        $eatings_total = array();
        foreach($eatings_id as $eating){
            $eating_total_arr = array('protein'=>0,'fat'=>0,'carbo'=>0,'ccal'=>0);
            foreach($all_prod_of_dinner as $dinner_list){
                if ($eating==$dinner_list['id_eating']){
                    $eating_total_arr['protein']+=$dinner_list['protein'];
                    $eating_total_arr['fat']+=$dinner_list['fat'];
                    $eating_total_arr['carbo']+=$dinner_list['carbo'];
                    $eating_total_arr['ccal']+=$dinner_list['ccal'];
                }    
            }
            //array_push($eatings_total, $eating_total_arr);
            $eatings_total[$eating] = $eating_total_arr;
        }
        return $eatings_total;
    }

    function calculate_total($total_of_eatings){
        $total = array('protein'=>0,'fat'=>0,'carbo'=>0,'ccal'=>0, 'water'=>0);
        foreach($total_of_eatings as $eating){
            $total['protein']+=$eating['protein'];
            $total['fat']+=$eating['fat'];
            $total['carbo']+=$eating['carbo'];
            $total['ccal']+=$eating['ccal'];
        }
        return $total;
    }

    function select_products_from_eating($eating){
        $mysqli = create_db_connection();
        $sql_product_list = "SELECT
            product_list.prod_name, 
            product_list.proteins, 
            product_list.fats, 
            product_list.carbohydrates, 
            product_list.ccal,
            product_list.id_product,
            food_in_eating.id_eating,
            food_in_eating.amount,
            food_picture.address,
            food_in_eating.id
            FROM product_list
            LEFT JOIN food_in_eating ON food_in_eating.id_product=product_list.id_product
            LEFT JOIN food_picture ON food_picture.id_picture = product_list.id_picture
            WHERE food_in_eating.id_eating= $eating";
            $product_list = $mysqli->query($sql_product_list) or die("Ошибка " . mysqli_error($mysqli));
        $mysqli->close();
        return $product_list;
    }

    //возвращает массив идентификаторов приемов пищи за выбранную лату
    function select_eatings($date){
        $mysqli = create_db_connection();
        $sql_eatings = "SELECT id_eating FROM eating WHERE date_time = '$date'";//CURRENT_DATE()";
        $eatings_list = $mysqli->query($sql_eatings) or die("Ошибка " . mysqli_error($mysqli));
        $eatings_id= array();
        while ($eating=mysqli_fetch_row($eatings_list)){
            array_push($eatings_id, (int)$eating[0]);
        }
        $mysqli->close();
        return $eatings_id;
    }

    function update_product_weight_in_eating($new_weight, $product_id, $eating_id){
        $weight_correct = check_new_weight($new_weight);
        if ($weight_correct){
            $mysqli = create_db_connection();
            $sql_update = "UPDATE food_in_eating SET amount=$new_weight WHERE id_eating=$eating_id AND id_product=$product_id";
            $mysqli->query($sql_update) or die("Ошибка " . mysqli_error($mysqli));
            $mysqli->close();
        }
        else echo "Неверно задано число";
    }

    function check_new_weight($new_weight){
        if ($new_weight>0 && $new_weight<=1000)
            return true;
        else
            return false;
    }

    function delete_eating($id_eating){
        $mysqli = create_db_connection();
        $sql_delete_eating = "DELETE FROM eating WHERE id_eating = $id_eating"; 
        $mysqli->query($sql_delete_eating) or die("Ошибка " . mysqli_error($mysqli));
        $mysqli->close(); 
    }

    function delete_product_from_eating($product_id, $eating_id, $id_code){
        $mysqli = create_db_connection();
        $sql_delete_eating = "DELETE FROM food_in_eating WHERE id_eating = $eating_id AND id_product = $product_id AND id=$id_code" ; 
        $mysqli->query($sql_delete_eating) or die("Ошибка " . mysqli_error($mysqli));

        $eating_empty=false;
        $sql= "SELECT * FROM food_in_eating WHERE id_eating = $eating_id";
        $result = $mysqli->query($sql) or die("Ошибка " . mysqli_error($mysqli));
        if ($result->num_rows==0){
            $eating_empty=true;
        }
        $mysqli->close(); 
        
        if ($eating_empty){
            delete_eating($eating_id);
        }
    }

    function add_water($water_count, $date){
        $id_water_eating=0;
        $id_water_product = select_water_id(); //получить id воды из списка продуктов

        //узнать добавлен ли за выбранный день прием пищи для воды
        $mysqli = create_db_connection();
        $sql = "SELECT food_in_eating.id, food_in_eating.id_eating 
        FROM food_in_eating 
        INNER JOIN product_list 
        ON food_in_eating.id_product=product_list.id_product 
        INNER JOIN eating 
        ON eating.id_eating=food_in_eating.id_eating 
        WHERE eating.date_time='$date' AND product_list.prod_name='вода'";

        $result = $mysqli->query($sql) or die("Ошибка " . mysqli_error($mysqli));
       
        if ($result->num_rows==0){
            $sql = "INSERT INTO eating (id_user, date_time) VALUES (1, '$date')";
            $mysqli->query($sql) or die("Ошибка " . mysqli_error($mysqli));
            $id_water_eating = get_last_water_eating_id();
            $sql = "INSERT INTO food_in_eating(id_eating, id_product, amount) VALUES($id_water_eating, $id_water_product, 0)";
            $mysqli->query($sql) or die("Ошибка " . mysqli_error($mysqli));
        }
        else{
            $row = mysqli_fetch_row($result);
            $id_water_eating = $row[1];
        }
        $mysqli->close();
        update_water_volume($id_water_eating, $water_count);
    
    }

    function select_water_id(){
        $mysqli = create_db_connection();
        $sql_water = "SELECT id_product FROM product_list WHERE prod_name = 'вода'";
        $res_water = $mysqli->query($sql_water) or die("Ошибка " . mysqli_error($mysqli));
        $row_water = mysqli_fetch_row($res_water);
        $id_water = $row_water[0];
        $mysqli->close();
        return $id_water;
    }

    function update_water_volume($id_water_eating, $water_count){
        $water_volume = 0;
        if ($water_count=="+50"){
            $water_volume=50;
        }
        else if ($water_count=="+100"){
            $water_volume=100;
        }
        else if ($water_count=="+200"){
            $water_volume=200;
        }
        $mysqli = create_db_connection();
        $sql_update = "UPDATE food_in_eating SET amount=amount+$water_volume WHERE id_eating = $id_water_eating";
        $mysqli->query($sql_update) or die("Ошибка " . mysqli_error($mysqli));
        $mysqli->close();
    }

    function get_last_water_eating_id(){
        $mysqli = create_db_connection();
        $sql = "SELECT MAX(id_eating) from eating";
        $result = $mysqli->query($sql) or die("Ошибка " . mysqli_error($mysqli));
        $id = mysqli_fetch_row($result);
        return $id[0];
    }

    function get_water_of_day($all_products_of_day){
        foreach($all_products_of_day as $product){
            if ($product['name']=="вода"){
                return $product;
            }
        }
        return null;
    }
?>