<?php

session_start();

include '../components/connect.php';

if(isset($_SESSION['tutor_id'])){
    $tutor_id = $_SESSION['tutor_id'];
 }else{
    $tutor_id = '';
    header('location:login.php');
 }

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Mentee</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/style.css">

</head>
<body>

<?php include '../components/tutor_header.php'; ?>

<section class="teachers">

   <h1 class="heading">Mentee</h1>

   <form action="" method="post" class="search-tutor">
      <input type="text" name="search_user" maxlength="100" placeholder="search mentee..." required>
      <button type="submit" name="search_user_btn" class="fas fa-search"></button>
   </form>

   <div class="box-container">

      <?php
         if(isset($_POST['search_user']) or isset($_POST['search_user_btn'])){
            $search_user = $_POST['search_user'];
            $select_users = $conn->prepare("SELECT * FROM `users` WHERE name LIKE '%{$search_user}%'");
            $select_users->execute();
            if($select_users->rowCount() > 0){
               while($fetch_user = $select_users->fetch(PDO::FETCH_ASSOC)){

                  $user_id = $fetch_user['id'];

                  $count_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ?");
                  $count_likes->execute([$user_id]);
                  $total_likes = $count_likes->rowCount();

                  $count_comments = $conn->prepare("SELECT * FROM `comments` WHERE user_id = ?");
                  $count_comments->execute([$user_id]);
                  $total_comments = $count_comments->rowCount();
      ?>
      <div class="box">
         <div class="tutor">
            <img src="../uploaded_files/<?= $fetch_user['image']; ?>" alt="">
            <div>
               <h3><?= $fetch_user['name']; ?></h3>
            </div>
         </div>
         <p>total likes : <span><?= $total_likes ?></span></p>
         <p>total comments : <span><?= $total_comments ?></span></p>
         <form action="tutor_profile.php" method="post">
            <input type="hidden" name="tutor_email" value="<?= $fetch_tutor['email']; ?>">
            <input type="submit" value="view profile" name="tutor_fetch" class="inline-btn">
         </form>
      </div>
      <?php
               }
            }else{
               echo '<p class="empty">no results found!</p>';
            }
         }else{
            echo '<p class="empty">please search something!</p>';
         }
      ?>

   </div>

</section>

<!-- teachers section ends -->










<?php include '../components/footer.php'; ?>

<!-- custom js file link  -->
<script src="../js/script.js"></script>
   
</body>
</html>