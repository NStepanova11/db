<?php
include 'check_user_params.php';
include 'user_param_calculates.php';
include 'profile_const.php';

    $profile_error_message="";
    $weight_status="";
    $normal=array(
        'id_normal'=>0,
        'min_weight'=>0,
        'max_weight'=>0

    );//норм границы веса
    $age = 0;
    $height = 0;
    $weight = 0;

    $ccal=0;
    $water=0;
    $proteins=0;
    $fats=0;
    $carbos=0;

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
        $ccal=$params['ccal'];
        $water=$params['water'];
        $proteins=$params['p_gr'];
        $fats=$params['f_gr'];
        $carbos=$params['c_gr'];
        $normal = load_user_normal_for_height($height, $gender);
        $text_info_style = get_text_style($weight, $normal['min_weight'], $normal['max_weight']);
        $weight_status = get_weight_status($weight, $normal['min_weight'], $normal['max_weight']);

    }  
    
    if (isset($_POST['calculate_normal'])){
        $props_correct=false;

        $age = $_POST['age'];
        $height = $_POST['height'];
        $weight =  $_POST['weight'];
        if (isset($_POST['age']) && isset($_POST['height']) && isset($_POST['weight'])
        && isset($_POST['gender']) && isset($_POST['lifestyle']) && isset($_POST['purpose'])){
            $gender = $_POST['gender'];
            $lifestyle_code = $_POST['lifestyle'];
            $purpose = $_POST['purpose'];
            $props_correct=true;
        }
        else{
            $profile_error_message="Заполнены не все поля";
        }

        if ($props_correct){
            $user_id = 1;

            if (check_user_params($age, $height, $weight) && isset($gender) && isset($purpose)){
                $norm = calculate_user_params($age, $height, $weight, $gender, $lifestyle_code, $purpose);
                $normal = load_user_normal_for_height($height, $gender);
                update_user_table($age, $normal['id_normal'], $user_id, $height, $gender, $purpose, $lifestyle_code);
                add_new_result($user_id, $weight, $norm['ccal'], $norm['water']);
                header("Location: profile.php");
            }
            else {
                $profile_error_message = "Проверьте правильность введенных данных";
            }
        }
    }
?>

<html>
    <head>
        <title>Profile</title>
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
                    <a class="nav-link active" href="../profile/profile.php">Профиль</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../products/search_product_form.php">Продукты</a>
                </li>
            </ul>

            <div class="page-header">
                <h1>Профиль</h1>
            </div>

            <div class = "row">
                <div class="col-6">
                    <form method="post" action = "">
                        <div class="form-group col"> 
                            <label for="age">Возраст</label>
                            <input type="number" name="age" id="age" class='form-control' value="<?=$age?>" title="Ваш возраст от 15 до 80">
                        </div>
                        <div class="form-group col">
                            <label for="height">Рост</label>
                            <input type="number" name="height" id="height" class='form-control' value="<?=$height?>" title="Ваш рост, от 150 до 190 см.">
                        </div>
                        <div class="form-group col">
                            <label for="weight">Вес</label>
                            <input type="number" name="weight" id="weight" class='form-control' value="<?=$weight?>" title="Ваш вес, от 50 до 200 кг.">
                        </div>

                        <div class="form-group col">
                            <label>Пол</label><br />
                            <label>
                                <input type="radio" name="gender" value = "m">
                                М
                            </label>
                            <label>
                                <input type="radio" name="gender" value = "f">
                                Ж
                            </label>
                        </div>

                        <div class="form-group col">
                            <p><select size="1" name="lifestyle" class='form-control'>
                                <option selected value="1">Низкая или отсутствует</option>
                                <option value="2">Невысокая активность (1–3 тренировки в неделю)</option>
                                <option value="3">Умеренная активность (3–5 тренировок в неделю)</option>
                                <option value="4">Высокая активность (6–7 тренировок в неделю)</option>
                                <option value="5">Экстремально высокая активность (2 и более тренировок в день)</option>
                            </select></p>
                        </div>

                        <div class="form-group col">
                            <label>Цель</label><br />
                            <label>
                                <input type="radio" name="purpose" value="1"> <!--support-->
                                Поддерживать вес
                            </label><br />
                            <label>
                                <input type="radio" name="purpose" value="2"> <!--up-->
                                Набрать вес
                            </label><br />
                            <label>
                                <input type="radio" name="purpose" value="3"> <!--down-->
                                Сбросить вес
                            </label><br />
                        </div>

                        <div class="form-group col">
                            <input type="submit" name = "calculate_normal" value = "Рассчитать" id="calculate_normal" class='btn btn-primary'>
                        </div>
                    </form>
                </div>
                <div class="col-6">
                    <h2>Норма</h2>
                    <table class = "table table-bordered">
                        <tr>
                            <td style="width: 50%">Калории: </td>
                            <td><?=(int)$ccal?></td>
                        </tr>
                        <tr>
                            <td style="width: 50%">Белки: </td>
                            <td><?=(int)$proteins?> гр.</td>
                        </tr>
                        <tr>
                            <td style="width: 50%">Жиры: </td>
                            <td><?=(int)$fats?>  гр.</td>
                        </tr>
                        <tr>
                            <td style="width: 50%">Углеводы: </td>
                            <td><?=(int)$carbos?> гр.</td>
                        </tr>
                        <tr>
                            <td style="width: 50%">Вода:</td>
                            <td><?=$water?> мл.</td>
                        </tr>
                    </table>

                    <h2>Вес</h2>
                    <p class = "<?=$text_info_style?>"><?=$weight_status?></p>
                    <table class = "table table-bordered">
                        <tr>
                            <td style="width: 50%">
                            <p class = "<?=$text_info_style?>">Ваш вес:</p>
                            </td>
                            <td><p class = "<?=$text_info_style?>"><?=$weight?> </p></td>
                        </tr>
                        <tr>
                            <td><p>Мин. нормальный вес: </p></td>
                            <td><?=$normal['min_weight']?></td>
                        </tr>
                        <tr>
                            <td><p>Макс. нормальный вес: </p></td>
                            <td><?=$normal['max_weight']?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <p class = "text-danger"><?=$profile_error_message?></p>
        </div>
    </body>
</html>

<?php

   
?>