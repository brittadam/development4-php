<?php
include_once(__DIR__ . "/bootstrap.php");

if (!empty($_POST)) {
    try {
        $user = new User();
        $user->setUsername($_POST['username']);
        $user->setEmail($_POST['email']);
        $user->setPassword($_POST['password']);
        $user->setToken(bin2hex(openssl_random_pseudo_bytes(32)));
        $user->save();
        $user->sendEmail("tibomertens25@gmail.com", "Hello World");
    } catch (Throwable $e) {
        $error = $e->getMessage();	
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up</title>
</head>

<body>
    <div>
        <form action="" method="post">
            <h2>Sign up</h2>
            <div>
                <label for="Email">Email</label>
                <input type="text" name="email" placeholder="Email" style="height:17px">
            </div>
            <div>
                <label for="Username">Username</label>
                <input type="text" name="username" placeholder="Username" style="height:17px">
            </div>
            <div style="display:flex">
                <label for="Password">Password</label>
                <input type="password" name="password" placeholder="Password" style="height:17px">
                <p style="position: relative; bottom:17px">*a minimum of 10 characters</p>
            </div>
            <?php if (isset($error)) : ?>
                <div>
                    <p style="color:red"><?php echo $error ?></p>
                </div>
            <?php endif ?>
            <div><button type="submit" name="submit">Sign up</button></div>
        </form>
    </div>
</body>

</html>