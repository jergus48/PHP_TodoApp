<?php
session_start();
include("connection.php");
include("functions.php");

$user_data = check_login($con);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/styles.css">
    <title>Home | TodoApp</title>
    <link rel="icon" type="image/png" href="assets/icon.png">
    <link rel="shortcut icon" href="assets/icon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
    crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js"
    integrity="sha384-Rx+T1VzGupg4BHQYs2gCW9It+akI2MM/mndMCy36UVfodzcJcF0GGLxZIzObiEfa"
    crossorigin="anonymous"></script>
</head>
<body>


<section class="vh-100" style="background-color: #3da2c3;">

<div class="container py-5 h-100">

    <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col col-lg-8 col-xl-6">
            <div class="card rounded-3">
                <div class="card-body p-4">

                    <h1 style="text-align: center; "><a href="/lists.php/" class="home-a">ToDoList</a> / <a
                            class="home-a" href="/daily.php/">Daily
                            Tasks</a>
                            /
                            <a href="/logout.php/" class="home-a" style="color:red;">Logout</a>
                        </h1>

                </div>
            </div>
        </div>
    </div>
</div>
</section>
</body>
</html>