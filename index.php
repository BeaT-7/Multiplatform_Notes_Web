<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Multiplatform Notes
        </title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    </head>

    <body>
        <div class="container-fluid vh-100" style="margin-top:300px">
            <div class="" style="margin-top:200px">
                <div class="rounded d-flex justify-content-center">
                    <div class="col-md-4 col-sm-12 shadow-lg p-5 bg-light">
                        <div class="text-center">
                            <h3 class="text-dark fw-bold">SIGN IN</h3>
                        </div>
                        <form action="" method = "POST">
                            <div class="p-4">
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-dark"><i
                                            class="bi bi-person-plus-fill text-white"></i></span>
                                    <input type="text" class="form-control" name="username" placeholder="username">
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-dark"><i class="bi bi-key-fill text-white"></i></span>
                                    <input type="password" class="form-control" name="password" placeholder="password">
                                </div>
                                <button class="btn btn-dark text-center mt-2"  name = "submit" type="submit">Login</button>
                                <button class="btn btn-dark text-center mt-2" name = "register" type="submit">Register</button>
                                
                </div>
            </div>
        </div>

        <?php
        include "connectionToDB.php";
        session_start();
        if (isset($_SESSION["username"])){
            $user = $_SESSION['username'];
            $querry = "SELECT * FROM users WHERE (username = '$user')";
            try{
                $res = $connection->prepare($querry);
                $res->execute();
            } catch(PDOException $e){
                echo "Querry error!";
                die();
            }
            $row = $res->fetch(PDO::FETCH_ASSOC);
            if(is_array($row)){
                if ($_SESSION["user_pass"] == $row['password']){
                    header("Location: main.php"); 
                    echo "user logged in!";
                } else {
                    session_unset();
                }
            }

        }
        if (isset($_POST['submit'])) {
            $login = FALSE; 
            $username = $_POST["username"];
            $password = $_POST["password"];
            $querry = "SELECT * FROM users WHERE (username = :username)";
            $values = [':username'=>$username];
            try{
                $res = $connection->prepare($querry);
                $res->execute($values);
            }
            catch(PDOException $e){
                echo "Querry error!";
                die();
            }
            $row = $res->fetch(PDO::FETCH_ASSOC);
            if(is_array($row)){
                if(password_verify($password, $row['password'])){ // password_verify, jo registracijas mirklī lietotājam paroles tiek hashotas 
                    $login = TRUE;
                    $_SESSION["username"] = $username;
                    $_SESSION["user_id"] = $row['id'];
                    $_SESSION["user_email"] = $row['email'];
                    $_SESSION["user_pass"] = $row['password'];
                    header("Location: main.php"); 
                    echo "user logged in!";
                }else{
                    echo $row['password'];
                    echo password_verify($password, $row['password']);
                }
            }

        }
        if (isset($_POST['register'])){
            header("Location: register.php");
        }


        ?>
    </body>

</html>