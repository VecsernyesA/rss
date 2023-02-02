<?php
    session_start();
    $db = new mysqli("localhost", "root", "", "Rss");
    
    if(isset($_POST["valasz"])){
        echo $POST["valasz"];
        $valasz = $db -> query("SELECT valasz FROM kerdesek WHERE valasz LIKE '".$POST["valasz"]."'");
        if($valasz -> num_rows > 1){
            $_SESSION["helyes"] = $valasz;
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kviz és Rss</title>
</head>
<body>
    <form action="" method="post">
    <table>
        <?php
            $leker = $db -> query("SELECT * FROM kerdesek");
            while($adatok = $leker->fetch_assoc()){
                echo "
                <tr>
                    <td>".$adatok['kerdes']."</td>
                </tr>
                <tr>
                    <td>
                        <select name=''>
                                <option value='".$adatok["id"]."'>".$adatok["valasz"]."</option>
                                <option value=''>Hunyadi Mátyás</option>
                                <option value=''>Mercedes</option>
                                <option value=''>Audi</option>
                        </select>
                    </td>
                </tr>
                ";           
            }
        ?>
        <tr>
            <td><button type="submit">Leadás</button></td>
        </tr>
        </table>
    </form>
    <?php
        
    ?>
</body>
</html>