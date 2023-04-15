<nav class="bg-gray-700">
        <div class="grid grid-cols-3 md:flex">
            <div class="pt-3 pb-2.5 ml-5 w-20">
                <a href="index.php"><img src="images/newLogo.svg" alt="logo" class="w-50 h-7"></a>
            </div>

            <div class="flex mt-[2px] md:flex-1 justify-center">
                <form class="flex h-9">
                    <div class="">
                        <input type="text" placeholder="Search.." class="text-base mt-2 p-1.5 rounded-l h-7 bg-white w-30">
                    </div>
                    <button type="submit" class="text-sm cursor-pointer rounded-r mt-2 px-2 bg-blue-600">
                        <i class="fa fa-search relative top-[0.75px]"></i>
                    </button>
                </form>
            </div>

            <div class="mt-1 flex flex-row-reverse">
                <div class="mt-2 mr-5 ml-2 relative bottom-[2px]">
                    <!-- If the user is logged in, show the logout button, else show the login button -->
                    <?php if (isset($_SESSION['loggedin'])) : ?>
                        <a href="logout.php" class="text-sm underline text-white">Logout</a>
                    <?php else : ?>
                        <a href="login.php" class="text-sm underline text-white">Login</a>
                    <?php endif; ?>
                </div>
                <a href="profile.php"><img src="images/signup-image.jpg" alt="profile picture" class="w-9 h-9 rounded-full mt-[2px] mr-[10px]"></a>
            </div>
        </div>
    </nav>