<?php
    include 'search_funct.php';
    $prod_name="";
    $prod_type=1;
    $protein = "";
    $fat = "";
    $carbo="";
    $ccal="";
    $gi="";
    $error_message="";
    if(isset($_POST['save'])){   
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
                    save_new_product($_POST, $_FILES);
                    $correct_form=true;
                    header('Location: create_product_form.php');
                }
            }
        }

        if ($correct_form==false){
            $error_message="Проверьте правильность введенных данных";
        }
    }
?>

<html>
    <head>
        <title>Search product</title>
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
                <h1>Создать продукт</h1>
            </div>

           
           <form method="POST" action = "" enctype="multipart/form-data">
            <div class="col-6">
                    <div class="form-group row">
                        <div class="col-2">Фото:</div>
                        <div class="col-auto">
                            <input type="file" class="form-control-file" id="inputfile" name="inputfile" >
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-2">Название:</div>
                        <div class="col-auto">
                            <input type="text" name="product_name" required autocomplete="off" id="product_name" class='form-control' value="<?=$prod_name?>">
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
                             <input type="number" name="proteins" required id="proteins" class='form-control' value="<?=$protein?>">
                        </div>
                    </div>    

                    <div class="form-group row">
                        <div class="col-2">Жиры:</div>
                        <div class="col-auto">
                            <input type="number" name="fats" required id="fats" class='form-control' value="<?=$fat?>">
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <div class="col-2">Углеводы:</div>
                        <div class="col-auto">
                            <input type="number" name="carbos" required id="carbos" class='form-control' value="<?=$carbo?>">
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <div class="col-2">Калории:</div>
                        <div class="col-auto">
                            <input type="number" name="ccals" required id="ccals" class='form-control' value="<?=$ccal?>">
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <div class="col-2">ГИ:</div>
                        <div class="col-auto">
                            <input type="number" name="gi" required id="gi" class='form-control' value="<?=$gi?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-auto">
                            <input type="submit" name="save" value="Сохранить" class='btn btn-primary'>
                        </div>
                    </div>
                </div>
            </form>
            <p class="text-left text-danger"><?=$error_message;?></p>

        </div>
    </body>
</html>