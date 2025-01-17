<?php
require_once 'vendor/autoload.php';
include_once("bootstrap.php");

//check if user is logged in, else redirect to login page
if (isset($_SESSION['loggedin'])) {

    //Get id from logged in user
    $id = $_SESSION['id'];

    $user = new \Promptopolis\Framework\User();
    $userDetails = $user->getUserDetails($id);
    //get username form userdetails
    $username = $userDetails['username'];
    //get bio from userdetails
    $bio = $userDetails['bio'];
    //get profile picture from userdetails
    $profilePicture = $userDetails['profile_picture_url'];

    if (!empty($_POST)) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        if (!empty($_FILES["fileToUpload"]["name"])) {
            $profilePicture = $target_file;
        }

        // Check if image file is a actual image or fake image

        if (isset($_POST["submit"])) {
            try {
                if (!empty($_FILES["fileToUpload"]["name"])) {
                    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);

                    if ($check !== false) {
                        $uploadOk = 1;
                    } else {
                        throw new Exception("File is not an image.");
                        $uploadOk = 0;
                    }



                    // Check file size, if file is larger than 1MB give error
                    if ($_FILES["fileToUpload"]["size"] < 1000000) {

                        $uploadOk = 1;
                    } else {
                        throw new Exception("File is too large.");
                    }

                    // Allow certain file formats
                    if (
                        $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                        && $imageFileType != "gif"
                    ) {
                        throw new Exception("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
                        $uploadOk = 0;
                    }

                    // Check if $uploadOk is set to 0 by an error
                    if ($uploadOk == 0) {
                        throw new Exception("Sorry, your file was not uploaded.");
                        // if everything is ok, try to upload file
                    } else {
                        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {

                            //var_dump the file that was uploaded

                            $user->setProfile_picture_url($target_file);
                        } else {
                            throw new Exception("Sorry, there was an error uploading your file.");
                        }
                    }
                } else {
                    $user->setProfile_picture_url($profilePicture);
                }

                //get data from form
                $newUsername = $_POST['username'];
                $newBio = $_POST['bio'];

                //set data to user
                try {
                    $user->setUsername($newUsername);
                    $user->setBio($newBio);

                    //update user details
                    $users = $user->updateUserDetails();

                    //redirect to profile
                    header("Location: profile.php");
                } catch (Throwable $e) {
                    $usernameError = $e->getMessage();
                }
            } catch (Throwable $e) {
                $profilePictureError = $e->getMessage();
            }
        }
    }

    if (isset($_POST['delete'])) {
        try {

            // delete the user's account and redirect to the login page
            $user->deleteAccount();
            session_destroy();

            header('Location: login.php');
        } catch (Throwable $e) {
            $deleteError = $e->getMessage();
        }
    }
} else {
    header('Location: login.php');
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit profile</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/c2626c7e45.js" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/c2626c7e45.js" crossorigin="anonymous"></script>

</head>

<body class="bg-[#121212]">
    <?php include_once("inc/nav.inc.php") ?>
    <div class="flex justify-center items-center pt-10 mb-5">
        <div class="bg-[#2A2A2A] rounded-lg p-8 max-w-md">
            <div class="text-white">
                <a href="profile.php?id=<?php echo htmlspecialchars($id) ?>"><i class="fa-solid fa-arrow-left"></i></a>
            </div>
            <h1 class="text-2xl font-bold mb-4 text-white">Edit Your Profile</h1>
            <form action="" method="post" enctype="multipart/form-data">

                <div class="mb-4 text-white">


                    <div class="mb-8 mt-5 "><img class="w-[100px] h-[100px] rounded-full" src="<?php echo htmlspecialchars($profilePicture) ?>" alt="ProfilePicture"></div>
                    <p class="block font-bold mb-0.5 text-white">Selecteer foto om te uploaden:</p>
                    <input type="file" name="fileToUpload" id="fileToUpload">
                    <?php if (isset($profilePictureError)) : ?>
                        <p class="text-red-500 text-xs italic"><?php echo htmlspecialchars($profilePictureError) ?></p>
                    <?php endif; ?>
                </div>


                <div class="mb-4">
                    <label for="username" class="block font-bold mb-0.5 text-white">Username</label>
                    <input class="w-full px-3 py-2 border-[3px] rounded hover:border-[#A25AFB] active:border-[#A25AFB] <?php echo htmlspecialchars(isset($usernameError)) ? 'border-red-500' : ''; ?>" style="height: 35px; font-size:1rem;" type="text" name="username" value="<?php echo htmlspecialchars($username); ?>">
                    <?php if (isset($usernameError)) : ?>
                        <p class="text-red-500 text-xs italic"><?php echo htmlspecialchars($usernameError); ?></p>
                    <?php endif; ?>
                </div>
                <div class="mb-4">
                    <label for="bio" class="block font-bold mb-0.5 text-white">Bio</label>
                    <textarea class="w-full px-3 py-2 border-[3px] rounded hover:border-[#A25AFB] active:border-[#A25AFB]" rows="4" name="bio"><?php echo htmlspecialchars($bio); ?></textarea>
                </div>
                <div class="flex justify-center">
                    <button name="submit" type="submit" class="bg-[#BB86FC] hover:bg-[#A25AFB] text-white px-4 py-2 rounded" style="padding-left: 6rem; padding-right: 6rem;">Save</button>
                </div>
                <div class="flex justify-center">
                    <button name="delete" class="text-red-500 mt-5" href="#">Delete account</button>
                </div>
            </form>
        </div>
    </div>



</body>

</html>