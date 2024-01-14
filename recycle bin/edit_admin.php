
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container my-5">
        <h2>Edit Admin</h2>
<?php
$p_id = $_GET['id'];
require_once('db.php');
$q="SELECT * FROM admins where id=$p_id";
$result = $mysqli->query($q);
echo"<form action='admin.php' method='post'>";
while($row=$result->fetch_array()){
echo "ID: <input type=text name=id 
value=".$row['id']." Disabled><br>";
echo "<input type=hidden name=id 
value='".$row['id']."'>";
echo "Username: <input type=text name=username 
value=".$row['username']."><br>";
echo "Tel: <input type=text name=tel 
value=".$row['tel']."><br>";
echo "Email: <input type=text name=email 
value=".$row['email']."><br>";
echo"<input type=submit value=submit>";
}
$mysqli->close();
?>


</div>
</body>
</html>
<?php
require_once('db.php');
$p_id = $_POST['id'];
$p_username = $_POST['username'];
$p_tel = $_POST['tel'];
$p_email = $_POST['email'];

$q="UPDATE admins SET username='$p_username', tel='$tel', email='$p_email' where id=$p_id";
if(!$mysqli->query($q)){
    echo "UPDATE failed. Error: ".$mysqli->error;
}
$mysqli->close();
header("Location: admin.php");
?>