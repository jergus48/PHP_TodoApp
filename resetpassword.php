<?php
include("connection.php");
include("functions.php");

require 'PHP/src/PHPMailer.php';
require 'PHP/src/SMTP.php';
require 'PHP/src/Exception.php';

require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

session_start();

    
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_name = $_POST['user_name'];
    $token = bin2hex(random_bytes(32));
    $token_expiry = date('Y-m-d H:i:s', time() + 3600); // Expiry 1 hour from now

    // Update the user's record in the database with the reset token and its expiry
    $update_query = "UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE user_name = ?";
    $stmt = mysqli_prepare($con, $update_query);
    mysqli_stmt_bind_param($stmt, "sss", $token, $token_expiry, $user_name);

    if (mysqli_stmt_execute($stmt)) {
        // Step 2: Send Reset Email
        $reset_link = "http://localhost:8000/newpassword.php?token=$token";
        $email_subject = "Password Reset";
        $email_body = "Click the link below to reset your password:\n\n$reset_link";

        // Initialize PHPMailer
        $mail = new PHPMailer();
        $mail->isSMTP();

        // Configure SMTP settings
        $mail->Host = 'smtp.gmail.com';;  // Update with your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'blessedstore.sk@gmail.com'; // Update with your email
        $mail->Password = 'nwcyhngarzrscbyq';   // Update with your email password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Set email content
        $mail->setFrom('blessedstore.sk@gmail.com', 'ToDoApp');
        $mail->addAddress($user_name);  // To address
        $mail->Subject = $email_subject;
        $mail->Body = $email_body;

        // Send the email
        if ($mail->send()) {
            echo 'Email sent successfully';
        } else {
            echo "Error sending email: " . $mail->ErrorInfo;
        }
    } else {
        echo "Error updating record: " . mysqli_error($con);
    }
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
                    <form method="post" class="card-body p-4" id="taskForm">

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="h2 mb-0">Reset Password</span>
                           
                            
                        </div>
                        <p class="text-muted pb-2">
                            <!-- <?php echo date('d/m/Y • H:i'); ?> -->
                        </p>

                        <div class="list-group rounded-0">
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Email address</label>
                                <input type="email" class="form-control" id="exampleInputEmail1"
                                    aria-describedby="emailHelp" name="user_name" placeholder="Your mail" required>
                                <div id="emailHelp" class="form-text">Reset link will be send to this mail
                                </div>
                            </div>
                            

                            <button type="submit" class="btn btn-sm btn-primary" name="reset"
                                value="reset">Submit</button>
                        </div>


                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
</html>