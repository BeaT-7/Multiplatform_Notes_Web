<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Notes4G
        </title>
        <script type="text/javascript" src="//js.nicedit.com/nicEdit-latest.js"></script> 
        <script type="text/javascript">
            bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });
        </script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
        <link rel="stylesheet" type="text/css" href="style.css">
        
    </head>
    
    <style>
       <?php include "style.css"; ?> 
    </style>
    <?php
    session_start();
    include "connectionToDB.php";
    // $groups structure = Groups(Group(ID, owner, group_name, notes(note(id, group, note_name, text), note(id, group, note_name, text))), Group(...))
    $groups = array();
    // gets all groups and notes on page load
    getAllGroups($connection, $_SESSION["user_id"]);
    getAllNotes($connection);
    
    
    // log out button
    if (isset($_POST["logout"])){
        session_unset();
        header("Location: index.php");
    }

    // gets all groups and adds them to array
    function getAllGroups(PDO $connection, int $id) {
        global $groups;
        $sql = "SELECT * FROM sql11496494.groups WHERE (owner = '$id')";
        try{
            $res = $connection->prepare($sql);
            $res->execute();
        }catch(PDOException $e){
            echo "Querry error!";
            die();
        }
        $rows = $res->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            $array = array($row['ID'], $row['owner'], $row['group_name'], array());
            array_push($groups, $array);
        }
    }

    // gets all notes and inserts them in their groups
    function getAllNotes(PDO $connection){
        global $groups;
        for ($i = 0; $i < count($groups); $i++){
            $id = $groups[$i][0];
            $sql = "SELECT * FROM sql11496494.notes WHERE notes.group = $id";
            try{
                $res = $connection->prepare($sql);
                $res->execute();
            }catch(PDOException $e){
                echo "Querry error!";
                die();
            }
            $rows = $res->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $row){
                $array = array($row["ID"], $row["group"], $row["note_name"], $row["text"]);
                array_push($groups[$i][3], $array);
            }
        }
    }

    // creates new group
    if (isset($_POST["newGroup"])){
        $groupName = $_POST["groupName"];
        $id = $_SESSION["user_id"];
        if (strlen($groupName) < 30 && strlen($groupName) > 2){
            $sql = "INSERT INTO sql11496494.groups(owner, group_name) VALUES ('$id', '$groupName')";
            try{
                $connection->exec($sql);
            }catch(PDOException $e){
                echo "Querry error!";
                die();
            }
            header("Location: main.php");
        }
    }

    // creates new note
    if (isset($_POST["newNote"])){
        $noteName = $_POST["noteName"];
        $groupID = $_POST["groupSelect"];
        if (strlen($noteName) < 30 && strlen($noteName) > 2){
            $sql = "INSERT INTO sql11496494.notes(notes.group, note_name, text) VALUES ('$groupID', '$noteName', '')";
            try{
                $connection->exec($sql);
            }catch(PDOException $e){
                echo "Querry error!";
                die();
            }
            header("Location: main.php");
        }
    }


    // !!pagaidu!! - dabū piezīmes ID, kad uz tās uzspiež
    if (isset($_POST["Note"])){
        $_SESSION["current_note"] = $_POST["id_note"];
        $_SESSION["current_group"] = $_POST["id_group"];
        $_SESSION["groups"] = $groups;
        header("Location: editor.php");
    }
    ?>

<body class="bodyEditMain">
    <!-- navigation bar -->
    <nav class="navbar navbar-dark bg-dark mb-2">
        <div class="d-flex container-fluid">
            <button class="btn btn-dark border border-light text-center mt-2 mx-2" onclick="openForm()">New Group</button>
            <button class="btn btn-dark border border-light text-center mt-2 mx-2" onclick="openFormNote()">New Note</button>
            <form action="" method="post" class="ms-auto">
                <button class="btn btn-dark border border-light text-center mt-2"  name = "logout" type="submit">Log out!</button>
            </form>
        </div>
    </nav>
        <div>
            <?php
            // Group and note displaying
            for ($i = 0; $i < count($groups); $i++){
                ?>
                <!-- creates labels for each group -->
                <div >
                    <form method="post" class="groupLabel d-flex">
                        <h4 class="gLabelText"><?php echo $groups[$i][2] ?></h4>
                    </form>
                </div>

                <div class="d-flex container-fluid"><?php
                    // creates buttons for each note in it's group
                    for ($j = 0; $j < count($groups[$i][3]); $j++){
                        ?>
                        <form method="post">
                            <input type="hidden" name="id_note" value="<?php echo $j ?>" />
                            <input type="hidden" name="id_group" value="<?php echo $i ?>" />
                            <button class="btn btn-dark border border-light text-center noteBtn" name = "Note"><?php echo $groups[$i][3][$j][2] ?></button>
                        </form>
                        <?php
                    }
                ?></div><?php
            }
            
            ?>
            <!-- new group pop up window -->
            <div class="form-popup" id="myForm">
                <form action="" class="form-container" method="post">
                    <label for="groupName" class="popupLabel"><b>New Group Name:</b></label>
                    <input type="text" placeholder="Enter New Group Name" name="groupName" required>
    
                    <button type="submit" name="newGroup" class="btn">Create</button>
                </form>
            </div>
            <!-- new note pop up window -->
            <div class="form-popup-note" id="newNote">
                <form action="" class="form-container" method="post">
                    <label for="noteName" class="popupLabel"><b>New Note Name:</b></label>
                    <input type="text" placeholder="Enter New Note Name" name="noteName" required>
                    <label for="group" class="popupLabel"><b>Add To Group:</b></label>
                    <select name="groupSelect" class="mb-4">
                        <?php
                        echo $groups[0][3][0][3];
                        foreach ($groups as $group){
                            ?><option value="<?php echo $group[0] ?>"><?php echo $group[2] ?></option><?php
                        }
                        ?>
                    </select>
                    <button type="submit" name="newNote" class="btn">Create</button>
                </form>
            </div>

            <script>
            // pop up window functions for hiding or showing them
            function openForm() { // new group pop up
                if (document.getElementById("myForm").style.display == "block") {
                    document.getElementById("myForm").style.display = "none";
                } else {
                    document.getElementById("newNote").style.display = "none";
                    document.getElementById("myForm").style.display = "block";
                }   
            }
            function openFormNote() { // new note pop up
                if (document.getElementById("newNote").style.display == "block") {
                    document.getElementById("newNote").style.display = "none";
                } else {
                    document.getElementById("myForm").style.display = "none";
                    document.getElementById("newNote").style.display = "block";
                }   
            }
            </script>

        </div>

    </body>

</html>