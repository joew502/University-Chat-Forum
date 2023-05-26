<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HallAmi Home Page</title>
    <link rel="stylesheet" type="text/css" href="../css/homepage.css?v=<?php echo time();?>">
    <link rel="icon" href="<?php echo $MC_abspath_cr;?>/assets/icon/HallAmiLogo.png" />
</head>
<body>
<!-- Top nav bar-->
<?php require './controllers/navbar_controller.php'; ?>
<!-- Sidebar and block links -->

<?php
require './controllers/sidebar_controller.php';
?>

<!-- Main content-->
<div id="HP-main-container" class="HP-main-container">
        <button onclick="document.location= './halls/academic'" id="HP-academic-hall" class="HP-academic-hall-link">Academic Halls</button>
        <button onclick="document.location= './halls/society'" id="HP-society-hall" class="HP-society-hall-link">Society Halls</button>
        <button onclick="document.location= './halls/community'" class="HP-community-hall-link" id="HP-community-hall" >Community Halls</button>
        <!--Display the halls and have them link to their pages-->
</div>
</body>
</html>
