<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Reading rss feeds using PHP</title>
</head>

<body>
    <div class="content">
        <form method="POST">
            <select name="feedurl">
                <option value="https://www.hirstart.hu/site/publicrss.php?pos=balrovat&pid=1">Nyitóoldal</option>
                <option value="https://www.hirstart.hu/site/publicrss.php?pos=top&pid=8">Legfrisebb TOP 50 hír</option>
                <option value="https://www.hirstart.hu/site/publicrss.php?pos=vezeto&pid=74">Vezető hírek</option>
                <option value="https://www.hirstart.hu/site/publicrss.php?pos=balrovat&pid=48">Autó-Motor</option>
                <option value="https://www.hirstart.hu/site/publicrss.php?pos=balrovat&pid=63">Baleset-Bűnügy</option>
            </select>&nbsp;<input type="submit" value="Beküldés" name="submit">
        </form>
        <?php
        session_start();
        $url = "https://www.hirstart.hu/site/publicrss.php?pos=balrovat&pid=51";
        if (isset($_POST['submit'])) {
            if ($_POST['feedurl'] != '') {
                $url = $_POST['feedurl'];
            }
        }



        $invalidurl = false;
        if (@simplexml_load_file($url)) {
            $feeds = simplexml_load_file($url);
        } else {
            $invalidurl = true;
            echo "<h2>Invalid RSS feed URL.</h2>";
        }


        $i = 0;
        if (!empty($feeds)) {


            $site = $feeds->channel->title;
            $sitelink = $feeds->channel->link;

            echo "<h1>" . $site . "</h1>";
            foreach ($feeds->channel->item as $item) {

                $title = $item->title;
                $link = $item->link;
                $description = $item->description;
                $postDate = $item->pubDate;
                $pubDate = date('D, d M Y', strtotime($postDate));


                if ($i >= 50) break;
        ?>
                <div class="post">
                    <div class="post-head">
                        <h2><a class="feed_title" href="<?php echo $link; ?>"><?php echo $title; ?></a></h2>
                        <span><?php echo $pubDate; ?></span>
                    </div>
                    <div class="post-content">
                        <?php echo implode(' ', array_slice(explode(' ', $description), 0, 20)) . "..."; ?> <a href="<?php echo $link; ?>">Read more</a>
                    </div>
                </div>
        <?php
                $i++;
            }
        } else {
            if (!$invalidurl) {
                echo "<h2>No item found</h2>";
            }
        }
        $db = new mysqli("localhost", "root", "", "Rss");

        if (isset($_POST["valasz"])) {
            echo $POST["valasz"];
            $valasz = $db->query("SELECT valasz FROM kerdesek WHERE valasz LIKE '" . $POST["valasz"] . "'");
            if ($valasz->num_rows > 1) {
                $_SESSION["helyes"] = $valasz;
            }
        }
        ?>

    </div>

    <div>
        <form action="" method="post">
            <table>
                <?php
                $leker = $db->query("SELECT * FROM kerdesek");
                while ($adatok = $leker->fetch_assoc()) {
                    echo "
                <tr>
                    <td>" . $adatok['kerdes'] . "</td>
                </tr>
                <tr>
                    <td>
                        <select name=''>
                                <option value='" . $adatok["id"] . "'>" . $adatok["valasz"] . "</option>
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
    </div>
</body>

</html>