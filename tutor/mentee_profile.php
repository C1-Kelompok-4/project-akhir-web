<?php

session_start();

include '../components/connect.php';

if(isset($_SESSION['tutor_id'])){
    $tutor_id = $_SESSION['tutor_id'];
 }else{
    $tutor_id = '';
    header('location:login.php');
    exit;
 }

if(isset($_POST['user_fetch'])){

   $user_email = $_POST['user_email'];
   $user_email = filter_var($user_email, FILTER_SANITIZE_STRING);
   $select_user = $conn->prepare('SELECT * FROM `users` WHERE email = ?');
   $select_user->execute([$user_email]);

   $fetch_user = $select_user->fetch(PDO::FETCH_ASSOC);
   $user_id = $fetch_user['id'];

   $count_playlists = $conn->prepare("SELECT * FROM `playlist` WHERE user_id = ?");
   $count_playlists->execute([$user_id]);
   $total_playlists = $count_playlists->rowCount();

   $count_contents = $conn->prepare("SELECT * FROM `content` WHERE user_id = ?");
   $count_contents->execute([$user_id]);
   $total_contents = $count_contents->rowCount();

   $count_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ?");
   $count_likes->execute([$user_id]);
   $total_likes = $count_likes->rowCount();

   $count_comments = $conn->prepare("SELECT * FROM `comments` WHERE user_id = ?");
   $count_comments->execute([$user_id]);
   $total_comments = $count_comments->rowCount();

}else{
   header('location:comments.php');
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Mentee's profile</title>
   <link rel="shortcut icon" href="../images/silogoo.png" type="image/png">

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/style.css">

</head>
<body>

<?php include '../components/tutor_header.php'; ?>

<!-- teachers profile section starts  -->

<section class="tutor-profile">

   <h1 class="heading">profile details</h1>

   <div class="details">
      <div class="tutor">
         <img src="../uploaded_files/<?= $fetch_user['image']; ?>" alt="">
         <h3><?= $fetch_user['name']; ?></h3>
      </div>
      <div class="flex">
         <p>total playlists : <span><?= $total_playlists; ?></span></p>
         <p>total videos : <span><?= $total_contents; ?></span></p>
         <p>total likes : <span><?= $total_likes; ?></span></p>
         <p>total comments : <span><?= $total_comments; ?></span></p>
      </div>
   </div>

</section>

<!-- teachers profile section ends -->

<section class="courses">

   <h1 class="heading">latest courese</h1>

   <div class="box-container">

      <?php
         $select_courses = $conn->prepare("SELECT * FROM `playlist` WHERE user_id = ? AND status = ?");
         $select_courses->execute([$user_id, 'active']);
         if($select_courses->rowCount() > 0){
            while($fetch_course = $select_courses->fetch(PDO::FETCH_ASSOC)){
               $course_id = $fetch_course['id'];

               $select_user = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
               $select_user->execute([$fetch_course['user_id']]);
               $fetch_user = $select_user->fetch(PDO::FETCH_ASSOC);
      ?>
      <div class="box">
         <div class="tutor">
            <img src="../uploaded_files/<?= $fetch_user['image']; ?>" alt="">
            <div>
               <h3><?= $fetch_user['name']; ?></h3>
               <span><?= $fetch_user['date']; ?></span>
            </div>
         </div>
         <img src="../uploaded_files/<?= $fetch_course['thumb']; ?>" class="thumb" alt="">
         <h3 class="title"><?= $fetch_course['title']; ?></h3>
         <a href="playlist.php?get_id=<?= $course_id; ?>" class="inline-btn">view playlist</a>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">no courses added yet!</p>';
      }
      ?>

   </div>

</section>

<!-- courses section ends -->










<?php include '../components/footer.php'; ?>    

<!-- custom js file link  -->
<script src="../js/script.js"></script>
   
</body>
</html>