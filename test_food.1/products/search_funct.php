<?php
    include '../db/database.php'; 

    function search_products($filters){
        $result="";
        if (!empty($filters['search_field']) ||  !empty($filters['prod_type']) || !empty($filters['gi_type'])){
            $product = $filters['search_field'];
            $prod_type = $filters['prod_type'];
            $gi_type = $filters['gi_type'];

            $sql = "SELECT id_product, prod_name, proteins, fats, carbohydrates, ccal, id_gi_type FROM product_list WHERE";
            $p1="";
            $p2="";
            if (!empty($product)){
                $p1 = " prod_name LIKE '%$product%'";
                if (!empty($prod_type) && !empty($gi_type)){
                    $p2 = " AND id_group=$prod_type AND id_gi_type=$gi_type";
                }
                else if (!empty($prod_type) && empty($gi_type)){
                    $p2 = " AND id_group=$prod_type";
                }
                else if (empty($prod_type) && !empty($gi_type)){
                    $p2 = " AND id_gi_type=$gi_type";
                }
            }
            if (empty($product)){
                if (!empty($prod_type) && !empty($gi_type)){
                    $p2 = " id_group=$prod_type AND id_gi_type=$gi_type";
                }
                else if (!empty($prod_type) && empty($gi_type)){
                    $p2 = " id_group=$prod_type";
                }
                else if (empty($prod_type) && !empty($gi_type)){
                    $p2 = " id_gi_type=$gi_type";
                }
            }
            $sql=$sql.$p1;
            $sql=$sql.$p2;

            $mysqli = create_db_connection();
            $result = $mysqli->query($sql) or die("Ошибка " . mysqli_error($mysqli)); 
            $mysqli->close();
        }
        return $result;
    }

    function get_products_from_select($result){
        $product_arr = array();

        while($row = mysqli_fetch_row($result)){
            $id_product = (int)$row[0];
            $gi_type = (int)$row[6];

            $class_middle = "text-warning";
            $class_hight = "text-danger";
            $class_low = "text-dark";
            $gi_color = $class_low;

            if ($gi_type==1)
                $gi_color=$class_hight;
            else if ($gi_type==2){
                $gi_color=$class_middle;
            }

            $product = array(
                'id_product'=>$row[0],
                'prod_name'=>$row[1],
                'proteins'=>(int)$row[2],
                'fats'=>(int)$row[3],
                'carbohydrates'=>(int)$row[4],
                'ccal'=>(int)$row[5],
                'gi_type'=>$gi_type,
                'gi_color'=> $gi_color
            );
            $product_arr[$id_product]=$product;
        }
        return $product_arr;
    }


    function get_product_info_from_select($result){
        $row = mysqli_fetch_row($result);
        $product = array(
            'id_product'=>$row[0],
            'prod_name'=>$row[1],
            'proteins'=>(int)$row[2],
            'fats'=>(int)$row[3],
            'carbohydrates'=>(int)$row[4],
            'ccal'=>(int)$row[5],
            'gi'=>(int)$row[6],
            'pict_addr'=>$row[7],
            'pict_id'=>$row[8],
            'prod_type'=>$row[9]
        );    
        if ($product['pict_id'] == NULL){
            $product['pict_addr']="../pict/dafault.png";
        }
        return $product;
    }

    function select_product_info($id){
        $mysqli = create_db_connection();
        $sql = "SELECT 
                product_list.id_product, 
                product_list.prod_name, 
                product_list.proteins, 
                product_list.fats, 
                product_list.carbohydrates, 
                product_list.ccal, 
                product_list.gi,
                food_picture.address,
                product_list.id_picture,
                product_list.id_group
                FROM product_list
                LEFT JOIN food_picture
                ON product_list.id_picture = food_picture.id_picture
                WHERE id_product=$id";
        $result = $mysqli->query($sql) or die("Ошибка " . mysqli_error($mysqli)); 
        $mysqli->close();
        return $result;
    }

    function add_product_to_eating($id_product, $id_eating, $width){
        $mysqli = create_db_connection();
        $sql = "INSERT INTO food_in_eating (id_eating, id_product, amount) VALUES ($id_eating, $id_product, $width)";
        $mysqli->query($sql);
        $mysqli->close();
    }

    function delete_product_from_product_list($id_product){
        $deleted=false;
        $mysqli = create_db_connection();
        $sql = "DELETE FROM product_list WHERE id_product = $id_product";
        $result = $mysqli->query($sql);// or die("Ошибка " . mysqli_error($mysqli)); 
        if ($result){
            $deleted=true;
        }
        $mysqli->close();
        return $deleted;
    }
    
    function show_search_result($result){
        echo"<div class='container'>";
        echo "<table class='table table-hover table-responsive table-bordered'>";
        while($row = mysqli_fetch_row($result)){
            echo "<tr>";
            echo "<form action='' method='post'>";
            echo "<input type='hidden' name='id' value=$row[0] class='form-control'>"; //id==id_product
            echo "<td>$row[1]</td>";
            echo "<td>б: $row[2]</td>";
            echo "<td>ж: $row[3]</td>";
            echo "<td>у: $row[4]</td>";
            echo "<td>к: $row[5]</td>";
           //echo "<td><input type='number' name='width' required  placeholder ='Вес продукта' title='Вес продукта'></td>";
            echo "<td><input type='submit' name='add' value='add' class='btn btn-primary'>";
            echo "<input type='submit' name='update' value='update' class='btn btn-primary'>";
            echo "<input type='submit' name='delete' value='Удалить' class='btn btn-primary'></td>";

            echo "</form>";
            echo "</tr>";
        }
        echo "</table>";
        echo "</div>";
    }

    function load_product_types(){
        $mysqli = create_db_connection();
        $sql = "SELECT id_product_type, name FROM product_type";
        $result = $mysqli->query($sql) or die("Ошибка " . mysqli_error($mysqli)); 
        $mysqli->close();
        return $result;
    }

    function show_product_types(){
        $prod_types = load_product_types();
        $prod_funct = array();

        if($prod_types)
        {
            $rows = mysqli_num_rows($prod_types);
        }

        for ($i = 0 ; $i < $rows ; ++$i)
        {
            $row = mysqli_fetch_row($prod_types);
            $id = $row[0];
            $name = $row[1];
            $prod_funct[$id]=$name;
        }
        return $prod_funct;
    }

    function show_gi_types(){
        $gi_types = load_gi_types();
        $gi_funct = array();

        if($gi_types)
        {
            $rows = mysqli_num_rows($gi_types);
        }

        for ($i = 0 ; $i < $rows ; ++$i)
        {
            $row = mysqli_fetch_row($gi_types);
            $id = $row[0];
            $name = $row[1];
            $gi_funct[$id]=$name;
        }
        return $gi_funct;
    }

    function load_gi_types(){
        $mysqli = create_db_connection();
        $sql = "SELECT id_gi_type, name FROM glycemic_index";
        $result = $mysqli->query($sql) or die("Ошибка " . mysqli_error($mysqli)); 
        $mysqli->close();
        return $result;
    }

    function select_gi_type($gi){
        $mysqli = create_db_connection();
        $sql = "SELECT id_gi_type FROM glycemic_index WHERE $gi BETWEEN min_gi AND max_gi";
        $result = $mysqli->query($sql) or die("Ошибка " . mysqli_error($mysqli));
        $row = mysqli_fetch_row($result); 
        $mysqli->close(); 
        return $row[0];
    }

    function save_new_product($product, $image){

        $id_picture = save_uploaded_picture($image);
        $product_name = $product['product_name'];
        $proteins = $product['proteins'];
        $fats = $product['fats'];
        $carbos = $product['carbos'];
        $ccals = $product['ccals'];
        $id_group = (int)($product['prod_type']);
        $gi = $product['gi'];

        $gi_type = select_gi_type($gi);

        $null_pict_sql = "INSERT INTO product_list (prod_name, id_group, proteins, fats, carbohydrates, ccal, gi, id_gi_type, id_picture)
        VALUES('$product_name', $id_group, $proteins, $fats, $carbos, $ccals, $gi, $gi_type, NULL)";

        $sql = "INSERT INTO product_list (prod_name, id_group, proteins, fats, carbohydrates, ccal, gi, id_gi_type, id_picture)
        VALUES('$product_name', $id_group, $proteins, $fats, $carbos, $ccals, $gi, $gi_type, $id_picture)";

        $mysqli = create_db_connection();
        if ($id_picture==null)
            $sql = $null_pict_sql;
        $mysqli->query($sql) or die("Ошибка " . mysqli_error($mysqli)); 
        $mysqli->close();        
    }

    function save_uploaded_picture($image){
        $id_pict = null;

        if(isset($image) && $image['inputfile']['error'] == 0){ // Проверяем, загрузил ли пользователь файл
            $destiation_dir = '../pict'.'/'.$image['inputfile']['name']; // Директория для размещения файла
            move_uploaded_file($image['inputfile']['tmp_name'], $destiation_dir ); // Перемещаем файл в желаемую директорию
            $mysqli = create_db_connection();

            $sql = "SELECT id_picture FROM food_picture WHERE address='$destiation_dir'";
            $result = $mysqli->query($sql) or die("Ошибка " . mysqli_error($mysqli));
            $row_id_picture = mysqli_fetch_row($result);
            
            if ($row_id_picture==null){
                $sql = "INSERT INTO food_picture (address) VALUES('$destiation_dir')";
                $result = $mysqli->query($sql) or die("Ошибка " . mysqli_error($mysqli));
            
                $sql = "SELECT id_picture FROM food_picture WHERE address = '$destiation_dir'";
                $result = $mysqli->query($sql) or die("Ошибка " . mysqli_error($mysqli));
                $row_id_picture = mysqli_fetch_row($result);
            }
            $mysqli->close();

            $id_pict = $row_id_picture[0];
        }
        return $id_pict;
    }

    function delete_picture($product_info){
        $prod_id = $product_info['id_product'];
        $pict_id = $product_info['pict_id'];
        $mysqli = create_db_connection();
        $sql_update_prod_list = "UPDATE product_list SET id_picture = null WHERE id_product = $prod_id";
        $result = $mysqli->query($sql_update_prod_list) or die("Ошибка " . mysqli_error($mysqli));

        $sql_selest_del_pict = "SELECT address from food_picture WHERE id_picture = $pict_id";
        $result = $mysqli->query($sql_selest_del_pict) or die("Ошибка " . mysqli_error($mysqli));
        $row = mysqli_fetch_row($result);
        $file_name = $row[0];

        $sql_del_from_pict_table = "DELETE FROM food_picture WHERE id_picture = $pict_id";
        $result = $mysqli->query($sql_del_from_pict_table) or die("Ошибка " . mysqli_error($mysqli));
        $mysqli->close();

        delete_pict_file_from_directory($file_name);
    }

    function delete_pict_file_from_directory($file_name){
        $directory = "../pict";
        $dir = opendir($directory); 
        while(($file = readdir($dir))){
            if((is_file("$file_name")) && ("$file_name" == "$directory/$file"))
            { 
              unlink("$file_name"); 
                             
              if(!file_exists($file_name)) return $s = TRUE; 
            } 
        } 
        closedir($dir); 
    }
?>


