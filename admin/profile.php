<?php

include '../components/connect.php';

session_start();

if(isset($_SESSION['admin_id'])){
   $admin_id = $_SESSION['admin_id'];
}else{
   $admin_id = '';
   header('location:login.php');
}

   $select_playlists = $conn->prepare("SELECT * FROM `playlist` WHERE admin_id = ?");
   $select_playlists->execute([$admin_id]);
   $total_playlists = $select_playlists->rowCount();

   $select_contents = $conn->prepare("SELECT * FROM `content` WHERE admin_id = ?");
   $select_contents->execute([$admin_id]);
   $total_contents = $select_contents->rowCount();

   $select_likes = $conn->prepare("SELECT * FROM `likes` WHERE admin_id = ?");
   $select_likes->execute([$admin_id]);
   $total_likes = $select_likes->rowCount();

   $select_comments = $conn->prepare("SELECT * FROM `comments` WHERE admin_id = ?");
   $select_comments->execute([$admin_id]);
   $total_comments = $select_comments->rowCount();

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Profile</title>
   <link rel="shortcut icon" href="../images/silogoo.png" type="image/png">

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>
   
<section class="tutor-profile" style="min-height: calc(100vh - 19rem);"> 

   <h1 class="heading">profile details</h1>

   <div class="details">
      <div class="tutor">
         <img src="../uploaded_files/<?= $fetch_profile['image']; ?>" alt="">
         <h3><?= $fetch_profile['name']; ?></h3>
         <a href="update.php" class="inline-btn">update profile</a>
      </div>
      <div class="flex">
         <div class="box">
            <span><?= $total_playlists; ?></span>
            <p>total playlists</p>
            <a href="playlists.php" class="btn">view playlists</a>
         </div>
         <div class="box">
            <span><?= $total_contents; ?></span>
            <p>total videos</p>
            <a href="contents.php" class="btn">view contents</a>
         </div>
         <div class="box">
            <span><?= $total_likes; ?></span>
            <p>total likes</p>
            <a href="contents.php" class="btn">view contents</a>
         </div>
         <div class="box">
            <span><?= $total_comments; ?></span>
            <p>total comments</p>
            <a href="comments.php" class="btn">view comments</a>
         </div>
      </div>
   </div>

</section>















<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

</body>
</html>