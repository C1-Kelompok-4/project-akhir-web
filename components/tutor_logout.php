<?php

   include 'connect.php';

   setcookie('tutor_id', '', time() - 1, '/');

   // header('location:../tutor/login.php');
   header('location:../login.php');

?>