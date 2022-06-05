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
        <link rel = "stylesheet" href="style.css">
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
                            <div class="p-4 text-center">
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-dark"><i
                                            class="bi bi-person-plus-fill text-white"></i></span>
                                    <input type="text" class="form-control" name="username" placeholder="username" required>
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-dark"><i
                                            class="bi bi-envelope-fill text-white"></i></span>
                                    <input type="text" class="form-control" name="email" placeholder="email@address.com" required>
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-dark"><i class="bi bi-key-fill text-white"></i></span>
                                    <input type="password" class="form-control" name="password" placeholder="password" required>
                                    
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-dark"><i class="bi bi-key-fill text-white"></i></span>
                                    <input type="password" class="form-control" name="password_conf" placeholder="confirm password" required>
                                    
                                </div>
                                <button class="btn btn-dark mt-2" name = "register" type="submit">Register</button>
                                <?php echo "</br>" ?>
                                <?php echo "</br>" ?>
                                
        

        <?php
        include "connectionToDB.php";
        if (isset($_POST['register'])) {
            $username = $_POST["username"];
            $email = $_POST["email"]; 
            $password = $_POST["password"];
            $password_conf = $_POST["password_conf"];
            $inputsAreValid = checkIfInputsAreValid($password, $username, $email,$password_conf);
            if ($inputsAreValid == True){
                registerNewUser($username, $password, $email);
            }else{
                ?>
                <div class="alert alert-danger text-center" role="alert">
                    <h4 class="alert-heading">Something went wrong!</h4>
                    <p>Password should contain: </p>
                    <ul>
                        <li>atleast 8 symbols</li>
                        <li>atleast 1 digit</li>
                        <li>atleast 1 lowercase letter</li>
                        <li>atleast 1 uppercase letter</li>
                        <li>atleast 1 special character</li>
                    </ul>
                </div>
            <?php 
            }
        }
    function registerNewUser($username, $password, $email){
        include "connectionToDB.php";
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $checkUsername = "SELECT * FROM users WHERE username = '$username';";
        $checkEmail = "SELECT * FROM users WHERE email = '$email';";
        $addNewUser = "INSERT INTO users(username, email, password) VALUES ('$username', '$email', '$hash');";
        $getUserIdSQL = "SELECT id FROM users WHERE username = '$username';"; 
        $registerNewGroupSQL = "INSERT INTO sql11496494.groups(owner, group_name) VALUES (?, 'Default')";
            $checkUsernameRes = $connection->prepare($checkUsername);
            $checkUsernameRes->execute();
            $checkUsernameRow = $checkUsernameRes->fetch(PDO::FETCH_ASSOC);
            $checkEmailRes = $connection->prepare($checkEmail);
            $checkEmailRes->execute();
            $checkEmailRow = $checkEmailRes->fetch(PDO::FETCH_ASSOC); 
            if(!$checkUsernameRow && !$checkEmailRow){ // katram no pārbaudēm jeb selectiem nav rezultāts, tapēc var reģistrēt lietotāju
                $addUser = $connection ->prepare($addNewUser);
                $addUser->execute();
               
                $getId = $connection ->prepare($getUserIdSQL);
                $getId->execute(); 
                $id = $getId ->fetch(PDO::FETCH_ASSOC);

                $addGroupUser = $connection -> prepare($registerNewGroupSQL);
                $addGroupUser -> bindParam(1, $id['id'],PDO::PARAM_INT);
                $addGroupUser->execute(); 
                

                ?>
                <div class="alert alert-success text-center" role="alert">
                    <h4 class="alert-heading">Account registered!</h4>
                    <p>Your new account is registered! Now you can <a href="index.php" class="alert-link">login</a> to Your account. </p>
                </div>
            <?php
            }else if($checkUsernameRow){
                ?>
                <div class="alert alert-danger text-center" role="alert">
                    <h4 class="alert-heading">Username is taken!</h4>
                    <p>This username is already taken! Choose different username and try again!</p>
                </div>
            <?php
            }else if($checkEmailRow){
                ?>
                <div class="alert alert-danger text-center" role="alert">
                    <h4 class="alert-heading">Email is already registered!</h4>
                    <p>Account with this email is already registered! Try <a href="index.php" class="alert-link">login</a> to your account! </p>
                </div>
            <?php 
            }
    }
    function checkIfInputsAreValid($password, $username, $email,$password_conf ) {
            $password_length = 8;
            $username_max_lenght = 25; 
            $inputsAreValid = True;
        
            if ( strlen($password) < $password_length ) {
                $inputsAreValid = False;
            }
        
            if ( !preg_match("#[0-9]+#", $password) ) {
                $inputsAreValid = False;
            }
        
            if ( !preg_match("#[a-z]+#", $password) ) {
                $inputsAreValid = False;
            }
        
            if ( !preg_match("#[A-Z]+#", $password) ) {
                $inputsAreValid = False;
            }
        
            if ( !preg_match("/[\'^£$%&*()}{@#~?><>,|=_+!-]/", $password) ) {
                $inputsAreValid = False;
            }
            if( strlen($username) > $username_max_lenght){
                $inputsAreValid = False; 
            }
            if (!preg_match( "/^([a-zA-Z0-9\.]+@+[a-zA-Z]+(\.)+[a-zA-Z]{2,3})$/", $email)){
                $inputsAreValid = False; 
            }
            if( $password != $password_conf){
                $inputsAreValid = False; 
            }
            return $inputsAreValid;
        }
        ?>
              </div>
            </div>
        </div>
    </body>

</html>
