<?php

session_start();
include '../components/connect.php';

if(isset($_SESSION['admin_id'])){
   $admin_id = $_SESSION['admin_id'];
}else{
   $adminr_id = '';
   header('location:login.php');
}

if(isset($_POST['submit'])){

   $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE id = ? LIMIT 1");
   $select_admin->execute([$admin_id]);
   $fetch_admin = $select_admin->fetch(PDO::FETCH_ASSOC);

   $prev_pass = $fetch_admin['password'];
   $prev_image = $fetch_admin['image'];

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);

   if(!empty($name)){
      $update_name = $conn->prepare("UPDATE `admin` SET name = ? WHERE id = ?");
      $update_name->execute([$name, $admin_id]);
      $message[] = 'username updated successfully!';
   }

   if(!empty($email)){
      $select_email = $conn->prepare("SELECT email FROM `admin` WHERE id = ? AND email = ?");
      $select_email->execute([$admin_id, $email]);
      if($select_email->rowCount() > 0){
         $message[] = 'email already taken!';
      }else{
         $update_email = $conn->prepare("UPDATE `admin` SET email = ? WHERE id = ?");
         $update_email->execute([$email, $admin_id]);
         $message[] = 'email updated successfully!';
      }
   }

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $ext = pathinfo($image, PATHINFO_EXTENSION);
   $rename = unique_id().'.'.$ext;
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../uploaded_files/'.$rename;

   if(!empty($image)){
      if($image_size > 2000000){
         $message[] = 'image size too large!';
      }else{
         $update_image = $conn->prepare("UPDATE `admin` SET `image` = ? WHERE id = ?");
         $update_image->execute([$rename, $admin_id]);
         move_uploaded_file($image_tmp_name, $image_folder);
         if($prev_image != '' AND $prev_image != $rename){
            unlink('../uploaded_files/'.$prev_image);
         }
         $message[] = 'image updated successfully!';
      }
   }

   $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
   $old_pass = sha1($_POST['old_pass']);
   $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);
   $new_pass = sha1($_POST['new_pass']);
   $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   if($old_pass != $empty_pass){
      if($old_pass != $prev_pass){
         $message[] = 'old password not matched!';
      }elseif($new_pass != $cpass){
         $message[] = 'confirm password not matched!';
      }else{
         if($new_pass != $empty_pass){
            $update_pass = $conn->prepare("UPDATE `admin` SET password = ? WHERE id = ?");
            $update_pass->execute([$cpass, $admin_id]);
            $message[] = 'password updated successfully!';
         }else{
            $message[] = 'please enter a new password!';
         }
      }
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update Profile</title>
   <link rel="shortcut icon" href="../images/silogoo.png" type="image/png">

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<!-- register section starts  -->

<section class="form-container" style="min-height: calc(100vh - 19rem);">

   <form class="register" action="" method="post" enctype="multipart/form-data">
      <h3>Update Profile</h3>
      <div class="flex">
         <div class="col">
            <p>Name</p>
            <input type="text" name="name" placeholder="<?= $fetch_profile['name']; ?>" maxlength="50"  class="box">
            <p>Email </p>
            <input type="email" name="email" placeholder="<?= $fetch_profile['email']; ?>" maxlength="20"  class="box">
         </div>
         <div class="col">
            <p>Old Password :</p>
            <input type="password" name="old_pass" placeholder="enter your old password" maxlength="20"  class="box">
            <p>New Password :</p>
            <input type="password" name="new_pass" placeholder="enter your new password" maxlength="20"  class="box">
            <p>Confirm Password :</p>
            <input type="password" name="cpass" placeholder="confirm your new password" maxlength="20"  class="box">
         </div>
      </div>
      <p>Update Pic :</p>
      <input type="file" name="image" accept="image/*"  class="box">
      <input type="submit" name="submit" value="update now" class="btn">
   </form>

</section>

<!-- registe section ends -->










<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>
   
</body>
</html>