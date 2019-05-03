<?php
include 'total_table.php';
    $norma = calculate_user_normal(); //значения нормы бжук для пользователя
    session_start();
    session_unset();
    session_destroy();
    $empty_profile_message="";
    if (isset($_POST['create_eating'])){
        $sql = "SELECT id_user FROM user";
        $mysqli = create_db_connection();
        $result = $mysqli->query($sql) or die("Ошибка " . mysqli_error($mysqli));
        $mysqli->close();
        if($result->num_rows!=0){
            create_day_page($_POST['calendar']);
            header("Location: eating_form.php");
        }
        else{
            header("Location: ../profile/profile.php");
        }
    }

    if (isset($_POST['add_product'])){
        session_start();
        $_SESSION['id_eating'] = $_POST['id_eating'];
        header("Location: ../products/search_product_form.php");
    }
  
    if (isset($_POST['water'])){
        $sql = "SELECT id_user FROM user";
        $mysqli = create_db_connection();
        $result = $mysqli->query($sql) or die("Ошибка " . mysqli_error($mysqli));
        $mysqli->close();
        if($result->num_rows!=0){
        add_water($_POST['water'], $_POST['water_date']);
        }
        else{
            header("Location: ../profile/profile.php");
        }
    }

    if (isset($_POST['show_date'])){
        $date = $_POST['calendar'];
    }
    else{
        $date =date('Y-m-d');
    }
        $eatings_id = select_eatings($date); //список id приемов пищи за день
        $all_products_of_day = calculate_products($date, $eatings_id); //вычисления бжук для каждого продукта по весу
        $water = get_water_of_day($all_products_of_day);
        $total_of_eatings = calculate_for_dinner($all_products_of_day, $eatings_id); //вычисления общего бжук для приема пищи
        $total_arr = calculate_total($total_of_eatings);
    
    if (isset($_POST['update_weight']) && !empty($_POST['weight'])){
        update_product_weight_in_eating($_POST['weight'], $_POST['id_product'], $_POST['id_eating']);
        header("Location: eating_form.php");
    }

    if (isset($_POST['del_eating'])){
        delete_eating($_POST['id_eating']);
        header('Location: eating_form.php');
    }

    if (isset($_POST['delete_product'])){
        delete_product_from_eating($_POST['id_product'], $_POST['id_eating'], $_POST['id_code']);
        header('Location: eating_form.php');
    }
?>
<html>
    <head>
        <title>Питание</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" />
    </head>
    <body>
        <div class="container">

            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" href="../eating_form/eating_form.php">Главная</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../profile/profile.php">Профиль</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../products/search_product_form.php">Продукты</a>
                </li>
            </ul>

            <div class="page-header">
                <h1>Питание</h1>
            </div>
        
            <form method="post" action = "">
                <div class="form-row">
                        <div class="col-auto">
                            <input type="date" name="calendar" value="<?php echo $date;?>" class='form-control'>
                        </div>
                        <div class="col-7">
                            <input type="submit" name="show_date" value="Показать" class='btn btn-primary'><br/>
                        </div>
                        <div class="col-2">
                            <input type="submit" name="create_eating" value="Создать прием пищи" class='btn btn-success'>
                        </div>
                </div>         
            </form>

            <p class="text-danger"><?=$empty_profile_message?></p>

        <form method="post" action = "">
            <div class="form-group">
                <div class="row">
                    <div class="col-6">
                        <p>Белки: <?=(int)$total_arr['protein']?> / <b><?=(int)$norma['p_gr']?></b></p>
                    </div>
                    <div class="col-6">
                        <p>Жиры: <?=(int)$total_arr['fat']?> / <b><?=(int)$norma['f_gr']?></b></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <p>Углеводы: <?=(int)$total_arr['carbo']?> / <b><?=(int)$norma['c_gr']?></b></p>
                    </div>
                    <div class="col-6">
                        <p>Калории: <?=(int)$total_arr['ccal']?> / <b><?=(int)$norma['ccal']?></b></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <p>Вода: <?=(int)$water['amount']?> / <b><?=(int)$norma['water']?></b></p>
                    </div>  
                    <div class="col-6">
                        <input type='hidden' name='water_date' value="<?=$date?>" class='form-control'>
                        <input type="submit" name="water" value="+50" class='btn btn-outline-primary'>
                        <input type="submit" name="water" value="+100" class='btn btn-outline-primary'>
                        <input type="submit" name="water" value="+200" class='btn btn-outline-primary'>
                    </div> 
                </div>
            </div>
        </form>

<?php
            foreach($total_of_eatings as $id_eating=>$value):
                if ($id_eating!=$water['id_eating']){
                    ?>
                <form action="" method="post">
                    <table class = 'table table-hover table-bordered'>
                        <thead class="thead-dark">
                        <tr>
                                <input type='hidden' name='id_eating' value="<?=$id_eating?>" class='form-control'>
                                <th ></th>
                                <th style="width: 25%">Итог:</th>
                                <th style="width: 10%">Б: <?=$value['protein']?></th>
                                <th style="width: 10%">Ж: <?=$value['fat']?></th>
                                <th style="width: 10%">У: <?=$value['carbo']?></th>
                                <th style="width: 10%">К: <?=$value['ccal']?></th>
                                <th style="width: 30%">
                                    <input type="submit" name="add_product" value="Добавить продукт" class='btn btn-primary'>
                                    <input type="submit" name="del_eating" value="Удалить" class='btn btn-outline-danger'>
                                </th>
                            
                        </tr>
                        </thead>

                        <tbody>
                        <?php foreach($all_products_of_day as $product):
                        if ($id_eating == $product['id_eating']){?>
                            <tr>
                                <form action="" method="post" class="form-inline">
                                    <div class="form-group">
                                        <input type='hidden' name='id_product' value="<?=$product['id_product']?>" class='form-control'>
                                        <input type='hidden' name='id_eating' value="<?=$product['id_eating']?>" class='form-control'>
                                        <input type='hidden' name='id_code' value="<?=$product['id_code']?>" class='form-control'>

                                        <td ><img  width=50 src = '<?=$product['pict_addr']?>'></td>
                                        <td style="width: 25%"><?=$product['name']?></td>
                                        <td style="width: 10%"><?=$product['protein']?></td>
                                        <td style="width: 10%"><?=$product['fat']?></td>
                                        <td style="width: 10%"><?=$product['carbo']?></td>
                                        <td style="width: 10%"><?=$product['ccal']?></td>
                                        <td style="width: 30%">
                                                <div class="form-row">
                                                <div class="col"><input type="number" name="weight" required value =<?=$product['amount']?> title="Вес продукта"  class='form-control' ></div>
                                                <div class="col"><input type="submit" name="update_weight" value="Обновить" class='btn btn-outline-secondary'></div>
                                                <div class="col"><input type="submit" name="delete_product" value="-" class='btn btn-outline-danger'></div>
                                                </div>
                                        </td>
                                    </div>
                                </form>
                            </tr>
                        <?php 
                        }
                
                    endforeach;?>
                    </tbody>
                    </table>
                </form>
                <?php       }
            endforeach;?>
        </div>
    </body>
</html>