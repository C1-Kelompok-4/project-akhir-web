<?php

session_start();
include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

$message = [];

if(isset($_POST['submit'])){
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE email = ? AND password = ? LIMIT 1");
   $select_tutor->execute([$email, $pass]);
   $row = $select_tutor->fetch(PDO::FETCH_ASSOC);
   
   if($select_tutor->rowCount() > 0){
     $_SESSION['tutor_id'] = $row['id'];
     setcookie('tutor_id', $row['id'], time() + 60*60*24*30, '/');
     header('location:tutor/dashboard.php');
     exit;
   }else{
      $message[] = 'Incorrect email or password!';
   }

   $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE email = ? AND password = ? LIMIT 1");
   $select_admin->execute([$email, $pass]);
   $row = $select_admin->fetch(PDO::FETCH_ASSOC);
   
   if($select_admin->rowCount() > 0){
     $_SESSION['admin_id'] = $row['id'];
     setcookie('admin_id', $row['id'], time() + 60*60*24*30, '/');
     header('location:admin/dashboard.php');
     exit;
   }else{
      $message[] = 'Incorrect email or password!';
   }

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ? LIMIT 1");
   $select_user->execute([$email, $pass]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);
   
   if($select_user->rowCount() > 0){
     $_SESSION['user_id'] = $row['id'];
     setcookie('user_id', $row['id'], time() + 60*60*24*30, '/');
     header('location:home.php');
     exit;
   }else{
      $message[] = 'Incorrect email or password!';
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
   <link rel="shortcut icon" href="images/silogoo.png" type="image/png">

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body style="padding-left: 0;">


<section class="form-container">

   <form action="" method="post" enctype="multipart/form-data" class="login">
      <h3>Welcome!</h3>
      <p>Email <span>*</span></p>
      <input type="email" name="email" placeholder="enter your email" maxlength="50" required class="box">
      <p>Password <span>*</span></p>
      <input type="password" name="pass" placeholder="enter your password" maxlength="20" required class="box">
      <p class="link">Don't have an account? <a href="register.php">Register</a></p>
      <input type="submit" name="submit" value="login now" class="btn">
   </form>

</section>
<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>