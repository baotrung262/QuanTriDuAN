<?php

require '../inc/dbcon.php';
function error422($message)
{
    $data = [
        'status' => 422,
        'message' => $message,
    ];

    header("HTTP/1.0 422 Unprocessable Entity");
    echo json_encode($data);
    exit();
}
function storeUser($UserInput){
    global $conn;
    $username = mysqli_real_escape_string($conn,$UserInput['username']);
    $f_name = mysqli_real_escape_string($conn,$UserInput['f_name']);
    $l_name = mysqli_real_escape_string($conn,$UserInput['l_name']);
    $email = mysqli_real_escape_string($conn,$UserInput['email']);
    $phone = mysqli_real_escape_string($conn,$UserInput['phone']);
    $password = mysqli_real_escape_string($conn,$UserInput['password']);
    $address = mysqli_real_escape_string($conn,$UserInput['address']);
    $date = mysqli_real_escape_string($conn,$UserInput['date']);
    if(empty(trim($username)))
    {
        return error422('Enter your username');
    }elseif(empty(trim($f_name))){
        return error422('Enter your first name');
    }elseif(empty(trim($l_name))){
        return error422('Enter your last name');
    }elseif(empty(trim($email))){
        return error422('Enter your email');
    }elseif(empty(trim($phone))){
        return error422('Enter your phone');


    }elseif(empty(trim($password))){
        return error422('Enter your password');
    }elseif(empty(trim($address))){
        return error422('Enter your address');

    }elseif(empty(trim($date))){
        return error422('Enter your date');
    }else{
        $query = "INSERT INTO users(username,f_name,l_name,email,phone,password,address,date) VALUES('$username','$f_name','$l_name','$email','$phone',md5('$password'),'$address','$date')";
        $result=mysqli_query($conn,$query);
        if($result)
        {
            $data = [
                'status' => 201,
                'message' => 'Users Created successfully',
            ];
        
            header("HTTP/1.0 201 Created");
            return json_encode($data);
        }else{
            $data = [
                'status' => 500,
                'message' => 'Method Not Allowed',
            ];
        
            header("HTTP/1.0 500 Internal Server Error");
            return json_encode($data);
        }
    }
}
function getUserList(){

    global $conn;

    $query = "SELECT * FROM users";
    $query_run = mysqli_query($conn,$query);

    if($query_run)
    {
        if(mysqli_num_rows($query_run)>0){
            
            $res = mysqli_fetch_all($query_run,MYSQLI_ASSOC);

            $data = [
                'status' => 200,
                'message' => 'User List Fetched Successfully',
                'data' =>$res,
            ];
        
            header("HTTP/1.0 200 Success");
            return json_encode($data);
        }else{
            $data = [
                'status' => 404,
                'message' => 'Method Not Allowed',
            ];
        
            header("HTTP/1.0 404 Internal Server Error");
            return json_encode($data);
        }
    }else{
        $data = [
            'status' => 500,
            'message' => 'Method Not Allowed',
        ];
    
        header("HTTP/1.0 500 Internal Server Error");
        return json_encode($data);
    }

}


function getUser($userParams){
    global $conn;
    if($userParams['username']==null)
    {
        return error422('Enter your username');
    }

    $username = mysqli_real_escape_string($conn,$userParams['username']);

    $query = "SELECT * FROM users WHERE username = '$username' LIMIT 1";
    $result = mysqli_query($conn,$query);
    if($result)
    {
        if(mysqli_num_rows($result)==1)
        {
            $res = mysqli_fetch_assoc($result);

            $data = [
                'status' => 200,
                'message' => 'Username Fectched Successfully',
                'data' => $res
            ];
        
            header("HTTP/1.0 200 OK");
            return json_encode($data);
        }else
        {
            $data = [
                'status' => 404,
                'message' => 'Not Username Found',
            ];
        
            header("HTTP/1.0 404 Not Found");
            return json_encode($data);
        }
    }else{
        $data = [
            'status' => 500,
            'message' => 'Method Not Allowed',
        ];
    
        header("HTTP/1.0 500 Internal Server Error");
        return json_encode($data);
    }
}
function updateUser($UserInput,$userParams){

    global $conn;

    if(!isset($userParams['u_id']))
    {
        return error422('User id not found in URL');
    }
    if($userParams['u_id'] == null){
        return error422('Enter the user id');
    }

    $userId = mysqli_real_escape_string($conn,$userParams['u_id']);

    $username = mysqli_real_escape_string($conn,$UserInput['username']);
    $f_name = mysqli_real_escape_string($conn,$UserInput['f_name']);
    $l_name = mysqli_real_escape_string($conn,$UserInput['l_name']);
    $email = mysqli_real_escape_string($conn,$UserInput['email']);
    $phone = mysqli_real_escape_string($conn,$UserInput['phone']);
    $password = mysqli_real_escape_string($conn,$UserInput['password']);
    $address = mysqli_real_escape_string($conn,$UserInput['address']);
    $date = mysqli_real_escape_string($conn,$UserInput['date']);
    if(empty(trim($username)))
    {
        return error422('Enter your username');
    }elseif(empty(trim($f_name))){
        return error422('Enter your first name');
    }elseif(empty(trim($l_name))){
        return error422('Enter your last name');
    }elseif(empty(trim($email))){
        return error422('Enter your email');
    }elseif(empty(trim($phone))){
        return error422('Enter your phone');
    }elseif(empty(trim($password))){
        return error422('Enter your password');
    }elseif(empty(trim($address))){
        return error422('Enter your address');

    }elseif(empty(trim($date))){
        return error422('Enter your date');
    }else{
        $query = "UPDATE `users` SET `username` = '$username', `f_name` ='$f_name', `l_name` ='$l_name', `email` ='$email', `phone` ='$phone', `password` =md5('$password'), `address`='$address', `date` = '$date' WHERE `u_id` = '$userId'";
    
        $result=mysqli_query($conn,$query);
        if($result)
        {
            $data = [
                'status' => 200,
                'message' => 'Users Updated successfully',
            ];
        
            header("HTTP/1.0 200 Success $userId ");
            return json_encode($data);
        }else{
            $data = [
                'status' => 500,
                'message' => 'Method Not Allowed',
            ];
        
            header("HTTP/1.0 500 Internal Server Error");
            return json_encode($data);
        }
    }
}
function deleteUser($userParams){
    global $conn;

    if(!isset($userParams['u_id']))
    {
        return error422('User id not found in URL');
    }
    if($userParams['u_id'] == null){
        return error422('Enter the user id');
    }

    $userId = mysqli_real_escape_string($conn,$userParams['u_id']);

    $query = "DELETE FROM users = WHERE u_id = '$userId' LIMIT 1";

    $result = mysqli_query($conn,$query);

    if($result)
    {
        $data = [
            'status' => 200,
            'message' => 'User Deleted Successfully',
        ];
    
        header("HTTP/1.0 200 OK");
        return json_encode($data);
    }else{
        $data = [
            'status' => 404,
            'message' => 'User Not Found',
        ];
    
        header("HTTP/1.0 404 Not Found");
        return json_encode($data);
    }
}

?>