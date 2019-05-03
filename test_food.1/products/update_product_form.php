<?php
    include 'search_funct.php';
    
    session_start();
    $id_product = $_SESSION['id_product'];
    $result = select_product_info($id_product);
    $product_info = get_product_info_from_select($result);
    $prod_name=$product_info['prod_name'];
    $prod_type=$product_info['prod_type'];
    $protein = $product_info['proteins'];
    $fat = $product_info['fats'];
    $carbo=$product_info['carbohydrates'];
    $ccal=$product_info['ccal'];
    $gi=$product_info['gi'];
    $error_message="";

    if(isset($_POST['update_info'])){
        $prod_name=$_POST["product_name"];
        $prod_type=$_POST['prod_type'];
        $protein=$_POST['proteins'];
        $fat=$_POST['fats'];
        $carbo=$_POST['carbos'];
        $ccal=$_POST['ccals'];
        $gi=$_POST['gi'];

        $ccal_min = ($protein*4)+($fat*9)+($carbo*4)-10;
        $ccal_max = ($protein*4)+($fat*9)+($carbo*4)+10;
        $correct_form=false;

        if (($protein+$fat+$carbo<=100) && ($protein>=0 && $fat>=0 && $carbo>=0)){
            if ($ccal>=$ccal_min && $ccal<=$ccal_max){
                if ($gi>=0 && $gi<=100){
                    update_product($_POST, $_FILES);
                    $correct_form=true;
                    header('Location: update_product_form.php');
                }
            }
        }
       
        if ($correct_form==false){
            $error_message="Проверьте правильность введенных данных";
        }

        if (isset($_POST['delete_pict'])){
            delete_picture($_POST);
            header('Location: update_product_form.php');
        }
    }   
?>

<html>
    <head>
        <title>Обновить продукт</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" />
    </head>
    <body>
        <div class="container">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link" href="../eating_form/eating_form.php">Главная</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../profile/profile.php">Профиль</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../products/search_product_form.php">Продукты</a>
                </li>
            </ul>

            <div class="page-header">
                <h1>Обновить продукт</h1>
            </div>

                <form method="POST" action = "" enctype="multipart/form-data">
                    <div class="col-6">
                        <div class="form-group row">
                        <div class="col">
                            <img width=300 src = '<?=$product_info['pict_addr']?>'/>
                        </div>
                        </div>

                        <div class="form-group row">
                            <input type='hidden' name='id_product' value="<?=$product_info['id_product']?>" class='form-control'>
                        </div>
                        
                        <div class="form-group row">
                            <div class="col-2">Фото:</div>
                            <div class="col-auto">
                                <input type="file" class="form-control-file" id="inputfile" name="inputfile">
                                <input type='hidden' name='pict_id' value = "<?=$product_info['pict_id']?>" class='form-control'>
                                <?php if ($product_info['pict_id']!=null){?>
                                <input type="checkbox" name="delete_pict" value="delete" class='btn btn-outline-danger'>Удалить изображение
                                <?php } ?>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-2">Название:</div>
                            <div class="col-auto">
                                <input type="text" name="product_name" id="product_name" class='form-control' value="<?=$prod_name?>" >
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-2">Тип:</div>
                            <div class="col-auto">
                                <p><select size="1" name="prod_type" required class='form-control'>
                                    <?php
                                        $types = show_product_types();
                                        foreach($types as $key=>$value){
                                            if ($key!=$prod_type){
                                            echo "<option value=$key>$key $value</option>";
                                            }
                                            else{
                                                echo "<option selected='selected' value=$key>$key $value</option>";
                                            }
                                        }
                                    ?>
                                </select>
                                </p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-2">Белки:</div>
                            <div class="col-auto">
                                <input type="number" name="proteins" required id="proteins" class='form-control' value=<?=$protein ?>>
                            </div>
                        </div>    

                        <div class="form-group row">
                            <div class="col-2">Жиры:</div>
                            <div class="col-auto">
                                <input type="number" name="fats"  id="fats" class='form-control' value=<?=$fat?> >
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <div class="col-2">Углеводы:</div>
                            <div class="col-auto">
                                <input type="number" name="carbos"  id="carbos" class='form-control' value=<?=$carbo?> >
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <div class="col-2">Калории:</div>
                            <div class="col-auto">
                                <input type="number" name="ccals"  id="ccals" class='form-control' value=<?=$ccal?> >
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <div class="col-2">ГИ:</div>
                            <div class="col-auto">
                                <input type="number" name="gi"  id="gi" class='form-control' value=<?=$product_info['gi']?>>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-auto">
                                <input type="submit" name="update_info" value="Обновить" class='btn btn-primary'>                        
                            </div>
                        </div>
                    </div>
                </form>
                <p class="text-left text-danger"><?=$error_message;?></p>

        </div>
    </body>
</html>

<?php
    function update_product($product, $picture){

        if(isset($picture) && $picture['inputfile']['error'] == 0){
            $id_picture = save_uploaded_picture($picture);
        }
        else{
            $id_picture = $product['pict_id'];
        }

        $id_product = $product['id_product'];
        $product_name = $product['product_name'];
        $proteins = $product['proteins'];
        $fats = $product['fats'];
        $carbos = $product['carbos'];
        $ccals = $product['ccals'];
        $id_group = (int)($product['prod_type']);
        $gi = $product['gi'];

        $gi_type = (int)select_gi_type($gi);

        $mysqli = create_db_connection();

        $sql = "UPDATE product_list
                SET prod_name = '$product_name', 
                    id_group = $id_group, 
                    proteins = $proteins, 
                    fats = $fats, 
                    carbohydrates = $carbos, 
                    ccal = $ccals,
                    gi = $gi, 
                    id_gi_type = $gi_type, 
                    id_picture = $id_picture
        WHERE id_product = $id_product";

        if ($id_picture==NULL){
            $sql = "UPDATE product_list
                    SET prod_name = '$product_name', 
                        id_group = $id_group, 
                        proteins = $proteins, 
                        fats = $fats, 
                        carbohydrates = $carbos, 
                        ccal = $ccals,
                        gi = $gi, 
                        id_gi_type = $gi_type, 
                        id_picture = NULL
        WHERE id_product = $id_product";
        }

        $mysqli->query($sql) or die("Ошибка " . mysqli_error($mysqli)); 
        $mysqli->close();
    }
?>