<?php
    include 'search_funct.php';
    $id_eating=0;
    $message="";

    session_start();
    if (isset( $_SESSION['id_eating'])){
        $id_eating = $_SESSION['id_eating'];
    }
    $product_arr=array();
    if(isset($_POST['search'])){
        $result = search_products($_POST);
        if (!empty($result)){
            if ($result->num_rows==0)
                $message="Ничего не найдено";
            else
                $product_arr = get_products_from_select($result);
            
        }
        else{
            $message="Ничего не найдено";
        }
    }

    if (isset($_POST['create_product'])){
        header('Location: create_product_form.php');
    }
    if (isset($_POST['add'])){
        session_start();
        $_SESSION['id_product'] = $_POST['id_product'];
        $_SESSION['id_eating'] = $_POST['id_eating'];
        header('Location: add_product_form.php');
    }
    if (isset($_POST['delete'])){
        $deleted = delete_product_from_product_list($_POST['id_product']);
        if ($deleted==false)
            $message = "Нельзя удалить эту запись";
        //else
            //header('Location: search_product_form.php');
    }
    if (isset($_POST['update'])){
        session_start();
        $_SESSION['id_product'] = $_POST['id_product'];
        header('Location: update_product_form.php');
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
                    <a class="nav-link active" href="../products/search_product_form.php">Продукты</a>
                </li>
            </ul>

            <div class="h1">
                <h1>Поиск</h1>
            </div>
            
            <form method="POST" action = "search_product_form.php">
                <div class="form-row align-items-center">
                    <div class="col-auto">
                        <input type="search" name="search_field" id="search_field" class='form-control' autocomplete="off">
                    </div>

                    <div class="col-auto">
                        <select size="1" name="prod_type" id="prod_type" class='form-control' value="">
                            <?php
                                $types = show_product_types();
                                echo "<option value='' selected='selected'>Тип продукта: </option>";
                                foreach($types as $key=>$value){
                                    echo "<option value=$key>$key $value</option>";
                                }
                            ?>
                        </select>
                    </div>

                    <div class="col-auto">
                        <select size="1" name="gi_type" id="gi_type" class='form-control' value="">
                            <?php
                                $gi_types = show_gi_types();
                                echo "<option value='' selected='selected'>ГИ:</option>";
                                foreach($gi_types as $key=>$value){
                                    echo "<option value=$key>$key $value</option>";
                                }
                            ?>
                        </select>
                    </div>

                    <div class="col-auto">   
                        <input type="submit" name="search" value="Найти" class='btn btn-primary'>
                    </div>

                    <div class="col-auto">
                        <input type='submit' name='create_product' value='Создать' class='btn btn-primary'>
                    </div>
                </div> 
            </form>

           
                <p class="text-left"><?=$message;?></p>

                <table class='table table-hover table-bordered'>
                <?php
                    if (!empty($product_arr)){ 
                    foreach($product_arr as $product){
                        if($product['prod_name']!='вода'){
                        ?>
                            <tr class=<?=$product['gi_color']?>>
                            <form action='' method='post'>
                                <input type='hidden' name='id_eating' value=<?=$id_eating?> class='form-control'>
                                <input type='hidden' name='id_product' value=<?=$product['id_product']?> class='form-control'>
                                <td><?=$product['prod_name']?></b></td>
                                <td>Б: <b><?=$product['proteins']?></b></td>
                                <td>Ж: <b><?=$product['fats']?></b></td>
                                <td>У: <b><?=$product['carbohydrates']?></b></td>
                                <td>К: <b><?=$product['ccal']?></b></td>
                                <td>
                                    <?php if ($id_eating!=0){?>
                                    <input type='submit' name='add' value='Добавить' class='btn btn-outline-success'>
                                    <?php }?>
                                    <input type='submit' name='update' value='Обновить' class='btn btn-outline-secondary'>
                                    <input type='submit' name='delete' value='Удалить' class='btn btn-outline-danger'>
                                </td>
                            </form>
                            </tr>
                <?php }}
                    } ?>
                
                </table>
        </div>
    </body>
</html>

<?php
    
?>