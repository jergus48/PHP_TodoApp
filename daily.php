<?php
session_start();
include("connection.php");
include("functions.php");

$user_data = check_login($con);

$user_id = $user_data['id'];


if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $title = $_POST['title'];
    $time = $_POST['time'];
    $finished = date("Y-m-d");
   
if (isset($_POST['create']) && !empty($title)) {
    
    $query = "INSERT INTO daily (user_id, title,time,finished) VALUES ('$user_id', '$title','$time','$finished')";
    mysqli_query($con,$query);
    
}
else if (isset($_POST['delete']) ){
   
    $list_id = $_POST['delete'];


    $query = "DELETE FROM daily WHERE id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "i", $list_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

}
else {

    if (isset($_POST['changedCheckbox'])) {
        $task_id = $_POST['changedCheckbox'];

        $query = "UPDATE daily SET completed = CASE WHEN completed = 0 THEN 1 ELSE 0 END, finished = CURDATE() WHERE id = ?";
    
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "i", $task_id);
        
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

}
};

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily | TodoApp</title>
    <link rel="icon" type="image/png" href="assets/icon.png">
    <link rel="shortcut icon" href="assets/icon.png">
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
<body>
<section class="vh-100" style="background-color: #3da2c3;">
    <a href="/">
        <button class="buttonwithicon" style="position: absolute;"></button>
    </a>
    <div class="container py-5 h-100">

        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col col-lg-8 col-xl-6">
                <div class="card rounded-3">
                    <form class="card-body p-4" method="post" id="taskForm">

                        <p class="mb-2"><span class="h2 me-2">Daily Tasks</span> </p>
                        <p class="text-muted pb-2">
                            <?php echo date('d/m/Y • H:i'); ?>
                        </p>

                        <div class="list-group rounded-0">
                            
                                <?php
                                loop_daily($user_id,$con);
                                ?>
                            
                        </div>

                        <div class="divider d-flex align-items-center my-4">
                            <p class="text-center mx-3 mb-0" style="color: #a2aab7;">Add Daily Task</p>
                        </div>

                        <div class="input-group mb-3" style="    width: 80%;
                            margin-left: auto;
                            margin-right: auto;">
                            <input type="text" class="form-control" placeholder="Task" aria-label="Recipient's username"
                                name="title">
                            <input type="time" class="input-group-append" aria-describedby="basic-addon2" name="time">
                            <input type="hidden" id="changedCheckbox" name="changedCheckbox" value="">
                            <div class="input-group-append">
                                <button class="input-group-text" id="basic-addon2" type="submit" name="create"
                                    value="create">Add</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
</body>


<script>
document.querySelector('form').addEventListener('keydown', function (event) {
    if (event.key === 'Enter' && event.target.tagName !== 'input') {
        event.preventDefault();
    }
});
function changeNameAndSubmit(checkbox) {
    var match = checkbox.name.match(/\d+/); // Extract number from name
    if (match) {
        var number = match[0];
        document.getElementById("changedCheckbox").value = number;
    }
    
    
    // Submit the form
    document.getElementById("taskForm").submit();
}

</script>
</html>