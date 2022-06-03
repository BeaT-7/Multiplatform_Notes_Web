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
                            <h3 class="text-dark fw-bold">REGISTER</h3>
                        </div>
                        <form action="" method = "POST">
                            <div class="p-4">
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-dark"><i
                                            class="bi bi-person-plus-fill text-white"></i></span>
                                    <input type="text" class="form-control" name="username" placeholder="username">
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-dark"><i
                                            class="bi bi-envelope-fill text-white"></i></span>
                                    <input type="text" class="form-control" name="email" placeholder="email@address.com">
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-dark"><i class="bi bi-key-fill text-white"></i></span>
                                    <input type="password" class="form-control" name="password" placeholder="password">
                                </div>
                                <button class="btn btn-dark text-center mt-2" name = "register" type="submit">Register</button>
                                
                </div>
            </div>
        </div>
        

        <?php
        include "connectionToDB.php";
        if (isset($_POST['register'])) {
            $username = $_POST["username"];
            $email = $_POST["email"]; 
            $password = $_POST["password"];
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $checkUsername = "SELECT id FROM users WHERE '$username' = username;";
            $checkEmail = "SELECT id FROM users WHERE '$email' = email;";
            $addNewUser = "INSERT INTO users(username, email, password) VALUES ('$username', '$email', '$hash');";

            $checUsernameRes = $connection->prepare($checkUsername);
            $checUsernameRes->execute();
            $checkUsernameRow = $checUsernameRes->fetch(PDO::FETCH_ASSOC);
            $checkEmailRes = $connection->prepare($checkEmail);
            $checkEmailRes->execute();
            $checkEmailRow = $checkEmailRes->fetch(PDO::FETCH_ASSOC);
            if(!$checkUsernameRow && !$checkEmailRow){ // katram no pārbaudēm jeb selectiem nav rezultāts, tapēc var reģistrēt lietotāju
                $addUser = $connection ->prepare($addNewUser);
                $addUser->execute(); 
                echo "user added!";
            }else{
                echo "kaut kas nesanāca!";
            }
        }


        ?>
    </body>

</html>