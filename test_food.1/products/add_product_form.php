<?php
    include 'search_funct.php';
    session_start();
    $add_message = "";
    $id_eating = $_SESSION['id_eating'];
    $id_product = $_SESSION['id_product'];
    $product_info=array();

    $result = select_product_info($id_product);
    $product_info = get_product_info_from_select($result);
    if (isset($_POST['add'])){
        if ($_POST['width']>0 && $_POST['width']<=1000){
            add_product_to_eating($id_product, $id_eating, $_POST['width']);
        }
        else $add_message="Ведите корректное значение";
    }
?>

<html>
    <head>
        <title>Добавить продукт</title>
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
                <h1>Добавить продукт</h1>
            </div>

                <form method="POST" action = "">
                    <div class="col-6">
                        <div class="form-group row">
                            <div class="col-2">
                            <img  width=200 src = '<?=$product_info['pict_addr']?>'/>
                            </div>
                        </div>

                        <div class="form-group row">
                            <input type='hidden' name='id_product' value="<?=$product_info['id_product']?>" class='form-control'>
                        </div>
                        
                        <div class="form-group row">
                            <div class="col-2">Название:</div>
                            <div class="col-auto">
                            <input type="text" name="product_name" id="product_name" class='form-control' placeholder=<?=$product_info['prod_name']?> disabled="disabled">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-2">Белки:</div>
                            <div class="col-auto">
                            <input type="number" name="proteins" required id="proteins" class='form-control' placeholder=<?=$product_info['proteins'] ?>  disabled="disabled">
                            </div>
                        </div>    

                        <div class="form-group row">
                            <div class="col-2">Жиры:</div>
                            <div class="col-auto">
                            <input type="number" name="fats"  id="fats" class='form-control' placeholder=<?=$product_info['fats']?> disabled="disabled">
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <div class="col-2">Углеводы:</div>
                            <div class="col-auto">
                            <input type="number" name="carbos"  id="carbos" class='form-control' placeholder=<?=$product_info['carbohydrates']?> disabled="disabled">
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <div class="col-2">Калории:</div>
                            <div class="col-auto">
                            <input type="number" name="ccals"  id="ccals" class='form-control' placeholder=<?=$product_info['ccal']?> disabled="disabled">
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <div class="col-2">ГИ:</div>
                            <div class="col-auto">
                            <input type="number" name="gi"  id="gi" class='form-control' placeholder=<?=$product_info['gi']?> disabled="disabled">
                            </div>
                        </div>

                         <div class="form-group row">
                            <div class="col-2">Вес:</div>
                            <div class="col-auto">
                            <input type="number" name="width" required id="width" class='form-control'>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-auto">
                                <input type="submit" name="add" value="Добавить" class='btn btn-primary'>                        
                            </div>
                            <div class="col-auto">
                                <a href="search_product_form.php">Назад</a>                        
                            </div>
                        </div>
                    </div>
                </form>
                <p class = "text-danger"><?=$add_message?></p>
        </div>
    </body>
</html>