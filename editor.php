<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notes4G</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="style.css">
    <script type="text/javascript" src="//js.nicedit.com/nicEdit-latest.js"></script> 
    <script type="text/javascript">
        bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });
    </script>
</head>
<body class="bg-light">
    <?php
    include "connectionToDB.php";
    session_start();
    $groups = $_SESSION["groups"];
    $curNote = $_SESSION["current_note"];
    $curGroup = $_SESSION["current_group"];

    if (isset($_POST["saveText"])){
        $noteID = $groups[$curGroup][3][$curNote][0];
        $text = htmlspecialchars($_POST["editor"], ENT_QUOTES);
        $sql = "UPDATE sql11496494.notes SET text = '$text' WHERE id = $noteID";
        try{
            $connection->exec($sql);
        }catch(PDOException $e){
            echo "Querry error!";
            die();
        }
        header("Location: main.php");
    }

    ?>
    <div class="editorDiv">
        <form action="" method="post">
            <div class="d-flex">
                <h2><?php echo $groups[$curGroup][3][$curNote][2];?>:</h2>
                <button type="submit" name="saveText" class="btn border-dark ms-auto saveBtn"><h2>X</h2></button>
            </div>
            <textarea name="editor" class="textEditor" value> <?php echo  htmlspecialchars_decode($groups[$curGroup][3][$curNote][3], ENT_QUOTES); ?> </textarea>
        </form>
    </div>
    


</body>
</html>