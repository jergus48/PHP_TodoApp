<?php


function check_login($con) {

    if(isset($_SESSION["user_id"])){
        $id = $_SESSION['user_id'];
        $query = "select * from users where user_id = $id limit 1"; 
        $result = mysqli_query($con,$query);
        if($result && mysqli_num_rows($result)>0){
            $user_data = mysqli_fetch_assoc($result);
            return $user_data;
        }
    }
    header("Location:/login.php/");
    die;
};

// signup.php
function random_num($length){
    $text="";
    if ($length < 5){
        $length = 5;
    }
    $len = rand(4,$length);
    for ($i = 0; $i < $len; $i++){
        $text .= rand(0,9);
    }
    return $text;

}

// lists.php
function loop_lists($user_id,$con) {
    $query = "SELECT * FROM todo WHERE user_id = ?  ORDER BY id DESC";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    

    while ($row = mysqli_fetch_assoc($result)) {
        $title =$row['title'];
      
        $id = $row['id'];
        echo "
        
        <li class='list-group-item border-0 d-flex align-items-center ps-0'>
            <a href='/tasks.php?id=$id' style='color: black;'>
                <b>$title</b></a>

            <button type='submit' class='btn-close' aria-label='Close' style='margin-left: auto;'
                name='delete' value='$id'></button>
        </li>
        ";
        
    }

    mysqli_stmt_close($stmt);
}
// tasks.php
function list_title($list_id, $con) {

    $query = "SELECT * FROM todo WHERE id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "i", $list_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $list = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    $title =$list['title'];
    
    if ($list) {
       
        echo "$title"; 
    } 
}
function loop_tasks($list_id,$con) {
    $query = "SELECT * FROM tasks WHERE list_id = ? ORDER BY id DESC";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "i", $list_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    

    while ($row = mysqli_fetch_assoc($result)) {
        $title =$row['title'];
        $completed =$row['completed'];
        $id = $row['id'];
        $created = $row['created'];
        $finished = $row['finished'];
        if($completed == 0){
            $check= "";
        }
        else if ($completed == 1){
            $check= "checked";
        }
        echo "
        <div
            class='list-group-item border-0 d-flex align-items-center justify-content-between ps-0'>
            <div class='d-flex align-items-center'>
                <input class='form-check-input me-3' type='checkbox' name='check-$id'
                    onchange='changeNameAndSubmit(this)' aria-label='...' $check />
                <div>";
                    if ($check == "checked") {

                    echo "<s>$title</s>";}

                    else {
                    echo "$title";
                    }
                    echo"
                </div>
            </div>
            <div class='d-flex align-items-center'>";
                if ($check == "checked") {
                echo "
                <div>

                    <span style='padding-right: 10px;'>" . date('d.m.Y', strtotime($created)) .

                        "<b>/</b>" . date('d.m.Y', strtotime($finished)) . "</span>


                </div>";}
                echo "
                <button type='submit' class='btn-close' aria-label='Close' name='delete'
                    value='$id'></button>
            </div>
        </div>";
        
        
    }

    mysqli_stmt_close($stmt);
}
// lists.php
function loop_daily($user_id,$con) {
    $query = "SELECT * FROM daily WHERE user_id = ? ORDER BY time ASC";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    

    while ($row = mysqli_fetch_assoc($result)) {
        $title =$row['title'];
        $completed =$row['completed'];
        $finished =$row['finished'];
        $id = $row['id'];
        $currentDate = date('Y-m-d');
        
        if ($finished !== $currentDate) {
            // Update the task's completed status and finished date
            $updateQuery = "UPDATE daily SET completed = 0, finished = ? WHERE id = ?";
            $stmt = mysqli_prepare($con, $updateQuery);
            mysqli_stmt_bind_param($stmt, "si", $currentDate, $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            
            // Update the $completed and $finished variables for display
            $completed = 0;
            
    }
        $time =  date('H:i', strtotime($row['time']));
        if($completed == 0){
            $check= "";
        }
        else if ($completed == 1){
            $check= "checked";
        }
        echo "
        <div class='list-group-item border-0 d-flex align-items-center justify-content-between ps-0'>
            <div class='d-flex align-items-center'>



                <input class='form-check-input me-3' type='checkbox' value='' aria-label='...'
                    $check name='check-$id' onchange='changeNameAndSubmit(this)' />

                <div>";
                    if ($check == "checked") {

                    echo "<s>$title</s>";}

                    else {
                    echo "$title";
                    }
                    echo "
                </div>


            </div>

            <div class='d-flex align-items-center'>

                <div class='input-group-append'>
                    <span class='input-group-text'>$time</span>
                </div>
                <button type='submit' name='delete' value='$id' class='btn-close'
                    aria-label='Close'></button>
            </div>
        </div>";
        
        
    }

  
}