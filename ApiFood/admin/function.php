
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
function getUserList(){

global $conn;

$query = "SELECT * FROM `admin`";
$query_run = mysqli_query($conn,$query);

if($query_run)
{
    if(mysqli_num_rows($query_run)>0){
        
        $res = mysqli_fetch_all($query_run,MYSQLI_ASSOC);

        $data = [
            'status' => 200,
            'message' => 'Admin List Fetched Successfully',
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

function storeAdmin($AdminInput){
    global $conn;
    $username = mysqli_real_escape_string($conn,$AdminInput['username']);
    $email = mysqli_real_escape_string($conn,$AdminInput['email']);
    $password = mysqli_real_escape_string($conn,$AdminInput['password']);
    $date = mysqli_real_escape_string($conn,$AdminInput['date']);
    if(empty(trim($username)))
    {
        return error422('Enter your username');
    }elseif(empty(trim($email))){
        return error422('Enter your email');
    }elseif(empty(trim($password))){
        return error422('Enter your password');
    }elseif(empty(trim($date))){
        return error422('Enter your date');
    }else{
        $query = "INSERT INTO admin(username,password,email,date) VALUES('$username',md5('$password'),'$email','$date')";
        $result=mysqli_query($conn,$query);
        if($result)
        {
            $data = [
                'status' => 201,
                'message' => 'Admin Created successfully',
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
function updateAdmin($AdminInput,$AdminParams){
    global $conn;
    if(!isset($AdminParams['adm_id']))
    {
        return error422('Admin id not found in URL');
    }
    if($AdminParams['adm_id'] == null){
        return error422('Enter the admin id');
    }
    $adminId = mysqli_real_escape_string($conn,$AdminParams['adm_id']);

    $username = mysqli_real_escape_string($conn,$AdminInput['username']);
    $email = mysqli_real_escape_string($conn,$AdminInput['email']);
    $password = mysqli_real_escape_string($conn,$AdminInput['password']);
    $date = mysqli_real_escape_string($conn,$AdminInput['date']);
    if(empty(trim($username)))
    {
        return error422('Enter your username');
    }elseif(empty(trim($email))){
        return error422('Enter your email');
    }elseif(empty(trim($password))){
        return error422('Enter your password');
    }elseif(empty(trim($date))){
        return error422('Enter your date');
    }else{
        $query = "Update admin SET username = '$username',password =md5('$password') ,email ='$email',date ='$date' WHERE adm_id = '$adminId' LIMIT 1";
        $result=mysqli_query($conn,$query);
        if($result)
        {
            $data = [
                'status' => 201,
                'message' => 'Admin Update successfully',
            ];
        
            header("HTTP/1.0 201 Successfully");
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
function deleteAdmin($AdminParams){
    global $conn;

    if(!isset($AdminParams['adm_id']))
    {
        return error422('Admin id not found in URL');
    }
    if($AdminParams['adm_id'] == null){
        return error422('Enter the Admin id');
    }

    $adminId = mysqli_real_escape_string($conn,$AdminParams['adm_id']);

    $query = "DELETE FROM admin WHERE adm_id = '$adminId' LIMIT 1";

    $result = mysqli_query($conn,$query);

    if($result)
    {
        $data = [
            'status' => 200,
            'message' => 'Admin Deleted Successfully',
        ];
    
        header("HTTP/1.0 200 OK");
        return json_encode($data);
    }else{
        $data = [
            'status' => 404,
            'message' => 'Admin Not Found',
        ];
    
        header("HTTP/1.0 404 Not Found");
        return json_encode($data);
    }
}
?>