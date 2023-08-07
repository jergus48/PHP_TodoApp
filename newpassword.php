<?php
include("connection.php");
include("functions.php");


session_start();

if (isset($_GET['token'])) {
    $reset_token = $_GET['token']; 
    $run = FALSE;


    $query = "SELECT reset_token_expiry FROM users WHERE reset_token = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "s", $reset_token);

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_store_result($stmt);
        
        if (mysqli_stmt_num_rows($stmt) === 1) {
            mysqli_stmt_bind_result($stmt, $tokenExpiry);
            mysqli_stmt_fetch($stmt);
            
            
            $currentTime = time();
            $expiryTime = strtotime($tokenExpiry);
            
           
            $timeDifference = $expiryTime - $currentTime;
            
            if ($timeDifference <= 3600) { 
                
                
                $run = TRUE;
            } else {
                echo "Token has expired. Go here: <a href='/resetpassword.php/'>Here</a>";
                
            }
        } else {
            echo "Token not found. Go here: <a href='/resetpassword.php/'>Here</a>";
        }
    } 




if ($_SERVER['REQUEST_METHOD'] == 'POST' && $run == TRUE) {
    $newPassword =  $_POST['password1'];
    $query = "SELECT id FROM users WHERE reset_token = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "s", $reset_token);

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_store_result($stmt);
        
        if (mysqli_stmt_num_rows($stmt) === 1) {
            mysqli_stmt_bind_result($stmt, $userId);
            mysqli_stmt_fetch($stmt);
            
            // Update the user's password
            $query = "UPDATE users SET password = ? WHERE id = ?";
            $stmt = mysqli_prepare($con, $query);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "si", $newPassword, $userId);

                if (mysqli_stmt_execute($stmt)) {
                    echo "Password updated successfully. Go here: <a href='/'>Here</a>";
                } else {
                    echo "Error updating password: " . mysqli_stmt_error($stmt);
                }

               
            } else {
                echo "Error preparing statement: " . mysqli_error($con);}

        } 
    }
} 

        // Close the prepared statements
mysqli_stmt_close($stmt);
       

}

    


    
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
       Reset Password | TodoApp
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
                    <form method="post" class="card-body p-4" id="resetForm">

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="h2 mb-0">Reset Password</span>
                           
                            
                        </div>
                        <p class="text-muted pb-2">
                            <!-- <?php echo date('d/m/Y • H:i'); ?> -->
                        </p>

                        <div class="list-group rounded-0">
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">New Password</label>
                                <input type="password" class="form-control" 
                                    name="password1" placeholder="New Password" id="password1" required>
                                <input type="password" class="form-control" id="password2"
                                     name="password2" placeholder="Confirm Password" required>
                                <div id="emailHelp" class="form-text">
                                </div>
                            </div>
                            

                            <button type="button" class="btn btn-sm btn-primary" name="reset"
                                value="reset"  id="submitButton">Submit</button>
                        </div>


                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const password1Input = document.getElementById("password1");
    const password2Input = document.getElementById("password2");
    const submitButton = document.getElementById("submitButton");
    const form = document.getElementById("resetForm");
    
    
    submitButton.addEventListener("click", function() {
        const password1Value = password1Input.value;
        const password2Value = password2Input.value;
        
        if (password1Value === password2Value) {
            form.submit()
        } else {
            alert("Passwords do not match. Please try again.");
        }
    });
});
</script>
</html>