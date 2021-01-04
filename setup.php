<?php
if(file_exists("config.php")){
    die("Hello Hacker-F**k You!!!");
}
if(isset($_POST['server'], $_POST['username'], $_POST['password'], $_POST['dbname'])){
    $myfile = fopen("config.php", "w") or die("Unable to open file!");
    $txt = "
    <?php
    \$server = '".$_POST['server']."';
    \$db_username = '".$_POST['username']."';
    \$db_password = '".$_POST['password']."';
    \$db_name = '".$_POST['dbname']."';

    \$ViewPath = 'view/';
    \$SharedFolderPath = 'view/shared/';
?>  
    ";
    fwrite($myfile, $txt);

    fclose($myfile);
    header('Location: /IASBS-Chat-Room');
    exit();
}else{


?>

<!doctype html>
<html>
<head>

</head>
<body>
<h4>Enter database information: </h4>
<form action="setup.php" method="post" >


    <input placeholder="DB server" type="text" name="server"><br>
    <input placeholder="DB Username" type="text" name="username"><br>
    <input placeholder="DB Password" type="text" name="password"><br>
    <input placeholder="DB Name" type="text" name="dbname"><br>
    <input type="submit" value="Send">


</form>
</body>
</html>
<?php
}