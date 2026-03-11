<?php
session_start();
$conn = new mysqli("localhost", "root", "", "cash_for_trash");

$error = "";

if(isset($_POST['login'])){
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if($result->num_rows > 0){
        $_SESSION['user'] = $username;
        header("Location: index.php");
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Trash for Cash Login</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI', sans-serif;
}

body{
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background: linear-gradient(135deg,#0f3d2e,#14532d,#064e3b);
    overflow:hidden;
}

/* FLOATING BACKGROUND GLOW */
body::before, body::after{
    content:"";
    position:absolute;
    width:350px;
    height:350px;
    border-radius:50%;
    filter:blur(130px);
    opacity:0.6;
}

body::before{
    background:#22c55e;
    top:-100px;
    left:-100px;
}

body::after{
    background:#facc15;
    bottom:-120px;
    right:-120px;
}

/* LOGIN CARD */
.login-box{
    position:relative;
    background:rgba(255,255,255,0.12);
    backdrop-filter:blur(25px);
    padding:45px 35px;
    width:360px;
    border-radius:20px;
    box-shadow:0 25px 60px rgba(0,0,0,0.5);
    border:1px solid rgba(255,255,255,0.2);
    text-align:center;
    color:white;
    animation:fadeIn 0.8s ease-in-out;
}
.home-link{
    display:block;
    margin-top:18px;
    padding:12px;
    border-radius:30px;
    text-decoration:none;
    font-size:14px;
    font-weight:bold;
    background:rgba(255,255,255,0.15);
    color:white;
    border:1px solid rgba(255,255,255,0.2);
    transition:0.3s;
}

.home-link:hover{
    background:#facc15;
    color:#064e3b;
    transform:scale(1.05);
}
@keyframes fadeIn{
    from{opacity:0; transform:translateY(20px);}
    to{opacity:1; transform:translateY(0);}
}

.login-box h2{
    font-size:26px;
    margin-bottom:5px;
    background: linear-gradient(90deg,#22c55e,#facc15);
    -webkit-background-clip:text;
    -webkit-text-fill-color:transparent;
}

.login-box p{
    font-size:14px;
    opacity:0.8;
    margin-bottom:25px;
}

/* INPUT FIELDS */
.login-box input{
    width:100%;
    padding:14px;
    margin:10px 0;
    border-radius:30px;
    border:none;
    outline:none;
    font-size:14px;
    padding-left:20px;
    background:rgba(255,255,255,0.9);
    transition:0.3s;
}

.login-box input:focus{
    transform:scale(1.05);
    box-shadow:0 0 15px rgba(250,204,21,0.6);
}

/* BUTTON */
.login-box button{
    width:100%;
    padding:14px;
    margin-top:15px;
    border:none;
    border-radius:30px;
    font-size:16px;
    font-weight:bold;
    cursor:pointer;
    background:linear-gradient(90deg,#22c55e,#16a34a);
    color:white;
    transition:0.3s;
}

.login-box button:hover{
    background:linear-gradient(90deg,#facc15,#eab308);
    color:#064e3b;
    transform:scale(1.07);
}

/* ERROR MESSAGE */
.error{
    margin-top:15px;
    background:rgba(255,0,0,0.2);
    padding:10px;
    border-radius:10px;
    font-size:14px;
    color:#ffdddd;
    border:1px solid rgba(255,0,0,0.4);
}
</style>
</head>

<body>

<div class="login-box">
    <h2>♻ Trash for Cash</h2>
    <p>Admin Dashboard Login</p>

    <form method="POST">
        <input type="text" name="username" placeholder="Enter Username" required>
        <input type="password" name="password" placeholder="Enter Password" required>
        <button name="login">Login</button>
    </form>

    <a href="homepage.html" class="home-link">
        🏠 Back to Homepage
    </a>

    <?php if($error): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>
</div>

</body>
</html>