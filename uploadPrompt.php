<?php
require_once 'vendor/autoload.php';
include_once("bootstrap.php");

use Promptopolis\Framework\Upload;
use Cloudinary\Configuration\Configuration;

$config = parse_ini_file(__DIR__ . "\src\Promptopolis\Framework\config\config.ini");

$name = $config['CLOUDINARY_NAME'];
$apiKey = $config['CLOUDINARY_API_KEY'];
$apiSecret = $config['CLOUDINARY_API_SECRET'];

$config = Configuration::instance();
$config->cloud->cloudName = $name;
$config->cloud->apiKey = $apiKey;
$config->cloud->apiSecret = $apiSecret;
$config->url->secure = true;

if (isset($_SESSION["loggedin"])) {
    $user = new \Promptopolis\Framework\User();
    $prompt = new \Promptopolis\Framework\Prompt();
    $id = $_SESSION["id"];
    $userDetails = $user->getUserDetails($id);
    $profilePicture = $userDetails['profile_picture_url'];
    $isVerified = $userDetails['is_verified'];
    $target_dir = "uploads/";

    if (!empty($_POST["submit"])) {
        try {
            $upload = new Promptopolis\Framework\Upload();
            $prompt->setUser_id($_SESSION['id']);

            $images = ["mainImage"];

            if (isset($_FILES["overviewImage"]["tmp_name"])) {
                $images[] = "overviewImage";
            }

            if (isset($_FILES["image3"]["tmp_name"])) {
                $images[] = "image3";
            }

            if (isset($_FILES["image4"]["tmp_name"])) {
                $images[] = "image4";
            }

            foreach ($images as $image) {
                try {
                    if (!empty($_FILES[$image]["tmp_name"])) {
                        $target_file = $target_dir . basename($_FILES[$image]["name"]);
                        $uploadOk = 1;
                        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                    } else {
                        throw new Exception("Please upload an image");
                    }

                    if (!isset($imageFileType)) {
                        throw new Exception("Please upload an image");
                    } else {
                        switch ($image) {
                            case "mainImage":
                                $url = Upload::uploadImage($image, $imageFileType);
                                $prompt->setMainImage($url);
                                $exceptionCaught = false;
                                break;
                            case "overviewImage":
                                $url = Upload::uploadImage($image, $imageFileType);
                                $prompt->setOverviewImage($url);
                                $exceptionCaught = false;
                                break;
                            case "image3":
                                $url = Upload::uploadImage($image, $imageFileType);
                                $prompt->setImage3($url);
                                $exceptionCaught = false;
                                break;
                            case "image4":
                                $url = Upload::uploadImage($image, $imageFileType);
                                $prompt->setImage4($url);
                                $exceptionCaught = false;
                                break;
                        }
                    }
                } catch (Exception $e) {
                    switch ($image) {
                        case "mainImage":
                            $mainImageError = $e->getMessage();
                            $exceptionCaught = true;
                            break;
                    }
                }
            }

            try {
                $prompt->setTitle($_POST["title"]);
            } catch (Exception $e) {
                $exceptionCaught = true;
                $titleError = $e->getMessage();
            }

            try {
                $prompt->setDescription($_POST["description"]);
            } catch (Exception $e) {
                $exceptionCaught = true;
                $descriptionError = $e->getMessage();
            }

            try {
                $prompt->setPrice($_POST["price"]);
            } catch (Exception $e) {
                $exceptionCaught = true;
                $priceError = $e->getMessage();
            }

            try {
                $prompt->setModel($_POST["model"]);
            } catch (Exception $e) {
                $exceptionCaught = true;
                $modelError = $e->getMessage();
            }

            $tags = array();
            if (!empty($_POST['tag1'])) {
                $tags[] = $_POST['tag1'];
            }
            if (!empty($_POST['tag2'])) {
                $tags[] = $_POST['tag2'];
            }
            if (!empty($_POST['tag3'])) {
                $tags[] = $_POST['tag3'];
            }
            try {
                $prompt->setTags($tags);
            } catch (Exception $e) {
                $exceptionCaught = true;
                $tagsError = $e->getMessage();
            }

            $prompt->setCategory($_POST["category"]);

            if (!$exceptionCaught) {
                if ($isVerified == 1) {
                    $prompt->setIs_approved(1);
                } else {
                    $prompt->setIs_approved(0);
                }
                $prompt->savePrompt();
                header("Location: profile.php");
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
} else {
    header("Location: login.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="js/tagInput.js" defer></script>
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/c2626c7e45.js" crossorigin="anonymous"></script>
    <title>Sell prompt</title>
</head>

<body class="bg-[#121212] ">
    <?php include_once("inc/nav.inc.php") ?>
    <div class="flex justify-center items-center pt-10 mb-5">
        <div class="bg-[#2A2A2A] rounded-lg p-8 max-w-md">
            <div class="text-white">
                <a href="index.php"><i class="fa-solid fa-arrow-left"></i></a>
            </div>
            <h1 class="text-2xl font-bold mb-4 text-white">Create your prompt!</h1>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="mb-4 ">
                    <label class="block font-bold mb-0.5 text-white" for="title">Title</label>
                    <input class="w-full px-3 py-2 border-[3px] rounded hover:border-[#A25AFB] active:border-[#A25AFB]" style="height: 35px; font-size:1rem;" type="text" name="title" id="title">
                    <?php if (isset($titleError)) : ?>
                        <p class="text-red-500 text-xs italic"><?php echo htmlspecialchars($titleError); ?></p>
                    <?php endif; ?>
                </div>
                <div class="mb-4">
                    <label class="block font-bold mb-0.5 text-white" for="description">Description</label>
                    <textarea class="w-full px-3 py-2 border-[3px] rounded hover:border-[#A25AFB] active:border-[#A25AFB]" name="description" id="description" rows="4"></textarea>
                    <?php if (isset($descriptionError)) : ?>
                        <p class="text-red-500 text-xs italic"><?php echo htmlspecialchars($descriptionError); ?></p>
                    <?php endif; ?>
                </div>
                <div class="mb-4">
                    <label class="block font-bold mb-0.5 text-white" for="price">Price<span class="font-light text-[12px]">(value like 1,2,3 etc.)</span></label>
                    <input class="w-full px-3 py-2 border-[3px] rounded hover:border-[#A25AFB] active:border-[#A25AFB]" style="height: 35px; font-size:1rem;" type="number" name="price" id="price">
                    <?php if (isset($priceError)) : ?>
                        <p class="text-red-500 text-xs italic"><?php echo htmlspecialchars($priceError); ?></p>
                    <?php endif; ?>
                </div>
                <div class="mb-4">
                    <label class="block font-bold mb-0.5 text-white" for="model">Model</label>
                    <select class="w-full px-3 py-2 border-[3px] rounded hover:border-[#A25AFB] active:border-[#A25AFB]" name="model" class="rounded-md">
                        <option value="Midjourney">Midjourney</option>
                        <option value="Dall-E">Dall-E</option>
                        <option value="Stable_diffusion">Stable Diffusion</option>
                    </select>
                    <?php if (isset($modelError)) : ?>
                        <p class="text-red-500 text-xs italic"><?php echo htmlspecialchars($modelError); ?></p>
                    <?php endif; ?>
                </div>
                <div class="mb-4">
                    <label class="block font-bold mb-0.5 text-white" for="model">Category</label>
                    <select class="w-full px-3 py-2 border-[3px] rounded hover:border-[#A25AFB] active:border-[#A25AFB]" name="category" class="rounded-md">
                        <option value="None">None</option>
                        <option value="Nature">Nature</option>
                        <option value="Logo">Logo</option>
                        <option value="Civilisation">Civilisation</option>
                        <option value="Line_art">Line art</option>
                        <option value="Abstract">Abstract</option>
                        <option value="Architecture">Architecture</option>
                        <option value="Space">Space</option>
                        <option value="Fantasy">Fantasy</option>
                        <option value="Sci-fi">Sci-fi</option>
                        <option value="Cyberpunk">Cyberpunk</option>
                        <option value="Surreal">Surreal</option>
                        <option value="Futuristic">Futuristic</option>
                        <option value="Other">Other</option>
                    </select>
                    <?php if (isset($modelError)) : ?>
                        <p class="text-red-500 text-xs italic"><?php echo htmlspecialchars($modelError); ?></p>
                    <?php endif; ?>
                </div>
                <div class="mb-4">
                    <label class="block font-bold mb-0.5 text-white" for="tag1">Tag 1</label>
                    <input class="w-full px-3 py-2 border-[3px] rounded hover:border-[#A25AFB] active:border-[#A25AFB]" type="text" name="tag1" id="tag1">
                    <div id="tag-container"></div>
                    <button class="bg-[#BB86FC] hover:bg-[#A25AFB] text-white px-4 py-2 rounded mt-3" name="tag" id="add-tag-btn">Add another Tag</button>
                    <?php if (isset($tagsError)) : ?>
                        <p class="text-red-500 text-xs italic"><?php echo htmlspecialchars($tagsError); ?></p>
                    <?php endif; ?>
                </div>
                <div class="mb-4">
                    <label class="block font-bold mb-0.5 text-white" for="mainImage">Main image</label>

                    <div class="mb-8 mt-5 "><img class="w-[100px] h-[100px]" id="preview" src="https://placehold.co/100?text=example&font=roboto" alt="cover image"></div>

                    <input class="block w-full text-white
                        file:py-2 file:px-3
                        file:rounded file:border-[3px]
                        file:bg-[#BB86FC] file:text-white
                        hover:file:bg-[#A25AFB]
                        file:border-[#A25AFB]
                        file:active:border-[#A25AFB]" type="file" name="mainImage" id="mainImage" onchange="previewFile()">
                    <?php if (isset($mainImageError)) : ?>
                        <p class="text-red-500 text-xs italic"><?php echo htmlspecialchars($mainImageError); ?></p>
                    <?php endif; ?>
                </div>
                <div class="mb-4">
                    <label class="block font-bold mb-0.5 text-white" for="overviewImage">Upload 3 images<span class="font-light text-[12px]">(optional)</span></label>
                    <div class="mb-8 mt-5 "><img class="w-[100px] h-[100px] " src="https://placehold.co/100?text=example&font=roboto" alt="overview image" id="previewOverview"></div>
                    <input class="block w-full text-white
                        file:py-2 file:px-3
                        file:rounded file:border-[3px]
                        file:bg-[#BB86FC] file:text-white
                        hover:file:bg-[#A25AFB]
                        file:border-[#A25AFB]
                        file:active:border-[#A25AFB]" type="file" name="overviewImage" id="overviewImage" onchange="previewFileOverview()">
                    <?php if (isset($overviewImageError)) : ?>
                        <p class="text-red-500 text-xs italic"><?php echo htmlspecialchars($overviewImageError); ?></p>
                    <?php endif; ?>
                </div>
                <div class="mb-4">
                    <div class="mb-8 mt-5 "><img class="w-[100px] h-[100px] " src="https://placehold.co/100?text=example&font=roboto" alt="overview image" id="preview3"></div>
                    <input class="block w-full text-white
                        file:py-2 file:px-3
                        file:rounded file:border-[3px]
                        file:bg-[#BB86FC] file:text-white
                        hover:file:bg-[#A25AFB]
                        file:border-[#A25AFB]
                        file:active:border-[#A25AFB]" type="file" name="image3" id="image3" onchange="previewFile3()">
                    <?php if (isset($image3Error)) : ?>
                        <p class="text-red-500 text-xs italic"><?php echo htmlspecialchars($image3Error); ?></p>
                    <?php endif; ?>
                </div>
                <div class="mb-4">
                    <div class="mb-8 mt-5 "><img class="w-[100px] h-[100px] " src="https://placehold.co/100?text=example&font=roboto" alt="overview image" id="preview4"></div>
                    <input class="block w-full text-white
                        file:py-2 file:px-3
                        file:rounded file:border-[3px]
                        file:bg-[#BB86FC] file:text-white
                        hover:file:bg-[#A25AFB]
                        file:border-[#A25AFB]
                        file:active:border-[#A25AFB]" type="file" name="image4" id="image4" onchange="previewFile4()">
                    <?php if (isset($image4Error)) : ?>
                        <p class="text-red-500 text-xs italic"><?php echo htmlspecialchars($image4Error); ?></p>
                    <?php endif; ?>
                </div>
                <div class="flex justify-center">
                    <input name="submit" type="submit" value="Sell prompt" class="bg-[#BB86FC] hover:bg-[#A25AFB] text-white px-4 py-2 rounded" style="padding-left: 7rem; padding-right: 7rem;">
                </div>
            </form>
        </div>
    </div>
</body>

</html>