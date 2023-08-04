<?php
include("connection.php");
include("functions.php");

session_start();
    

    if (isset($_SESSION['user_id'])) {
       
        header("Location: /index.php/");
        exit();}
    

    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $user_name = $_POST['user_name'];
        $password=  $_POST['password'];
    
    if (!empty($user_name) && !empty($password) && !is_numeric($user_name)){
        $query = "SELECT user_id FROM users WHERE user_name = ? AND password = ?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "ss", $user_name, $password);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        // Check if a matching user was found
        if (mysqli_stmt_num_rows($stmt) > 0) {
            // Fetch the user ID
            mysqli_stmt_bind_result($stmt, $user_id);
            mysqli_stmt_fetch($stmt);

            // Set the user_id in the session
            $_SESSION['user_id'] = $user_id;

            // Redirect to the home page or any other authenticated page
            header("Location: home.php");
            exit();
        } else {
            echo '<script>alert("Invalid mail or password.");</script>';
        }

       
        mysqli_stmt_close($stmt);
    }
    
    };
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="assets/icon.png">
    <link rel="shortcut icon" href="assets/icon.png">
    <title>
       Login | TodoApp
    </title>
    <link rel="stylesheet" type="text/css" href="/styles.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js"
        integrity="sha384-Rx+T1VzGupg4BHQYs2gCW9It+akI2MM/mndMCy36UVfodzcJcF0GGLxZIzObiEfa"
        crossorigin="anonymous"></script>
</head>
<section class="vh-100" style="background-color: #3da2c3;">

    <div class="container py-5 h-100">

        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col col-lg-8 col-xl-6">
                <div class="card rounded-3">
                    <form method="post" class="card-body p-4" id="taskForm">

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="h2 mb-0">Login</span>
                            <a href="/signup.php/">Sign Up</a>
                        </div>
                        <p class="text-muted pb-2">
                            <!-- <?php echo date('d/m/Y • H:i'); ?> -->
                        </p>

                        <div class="list-group rounded-0">
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Email address</label>
                                <input type="email" class="form-control" id="exampleInputEmail1"
                                    aria-describedby="emailHelp" name="user_name" placeholder="Your mail" required>
                                <div id="emailHelp" class="form-text">We'll never share your email with anyone else.
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputPassword1" class="form-label">Password</label>
                                <input type="password" class="form-control" id="exampleInputPassword1" name="password"
                                    placeholder="Password" required>
                            </div>

                            <button type="submit" class="btn btn-sm btn-primary" name="Login"
                                value="Login">Submit</button>
                        </div>


                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
</html>