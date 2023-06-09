<?php

session_start();
include '../components/connect.php';

if(isset($_COOKIE['admin_id'])){
   $admin_id = $_COOKIE['admin_id'];
}else{
   $admin_id = '';
}

$message = [];

if(isset($_POST['submit'])){

   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   $select_admins = $conn->prepare("SELECT * FROM `admin` WHERE email = ? AND password = ? LIMIT 1");
   $select_admins->execute([$email, $pass]);
   $row = $select_admins->fetch(PDO::FETCH_ASSOC);
   
   if($select_admins->rowCount() > 0){
     $_SESSION['admin_id'] = $row['id']; // set the session variable
     setcookie('admin_id', $row['id'], time() + 60*60*24*30, '/');
     header('location:dashboard.php');
     exit;
   }else{
      $message[] = 'incorrect email or password!';
   }

}

?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login</title>
   <link rel="shortcut icon" href="../images/silogoo.png" type="image/png">

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body style="padding-left: 0;">

<?php
if (isset($messages)) {
   foreach ($messages as $msg) {
      echo '
      <div class="message form">
         <span>'.$msg.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<!-- register section starts  -->

<section class="form-container">

   <form action="" method="post" enctype="multipart/form-data" class="login">
      <h3>Welcome Back!</h3>
      <p>Email <span>*</span></p>
      <input type="email" name="email" placeholder="enter your email" maxlength="20" required class="box">
      <p>Password <span>*</span></p>
      <input type="password" name="pass" placeholder="enter your password" maxlength="20" required class="box">
      <p class="link">Don't Have an Account? <a href="register.php">Register</a></p>
      <input type="submit" name="submit" value="login now" class="btn">
   </form>

</section>

<!-- registe section ends -->














<script>

let darkMode = localStorage.getItem('dark-mode');
let body = document.body;

const enabelDarkMode = () =>{
   body.classList.add('dark');
   localStorage.setItem('dark-mode', 'enabled');
}

const disableDarkMode = () =>{
   body.classList.remove('dark');
   localStorage.setItem('dark-mode', 'disabled');
}

if(darkMode === 'enabled'){
   enabelDarkMode();
}else{
   disableDarkMode();
}

</script>
   
</body>
</html>