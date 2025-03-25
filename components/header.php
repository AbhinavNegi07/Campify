<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start(); // Start session only if none exists
}

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$username = $isLoggedIn && !empty($_SESSION['username']) ? $_SESSION['username'] : ""; // Ensure username is set
?>

<style>
  @import url("https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&display=swap");

  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: "Nunito Sans", sans-serif;
  }

  html,
  body {
    position: relative;
    width: 100%;
    height: 100%;
    scroll-behavior: smooth;
  }

  <?php
  // Check if the current page is index.php
  $isIndex = basename($_SERVER['PHP_SELF']) != "index.php";
  ?>

  /* Navbar */
  .navbar {
    position: <?php echo $isIndex ? "static" : "fixed !important"; ?>;
    width: 100%;
    padding: 20px 40px !important;
    display: flex;
    justify-content: space-between;
    align-items: center;
    z-index: 99999;
    background-color: <?php echo $isIndex ? "grey" : "none"; ?>;
  }

  .logo img {
    width: 150px;
  }

  .nav-links ul {
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0;
  }

  .nav-links ul li {
    list-style-type: none;
    padding: 10px 20px;
  }

  .nav-links ul li a {
    color: #fff;
    text-decoration: none;
    transition: all 0.5s ease-in-out;
  }

  .nav-links ul li:hover a {
    color: #f2681d;
  }

  .nav-buttons {
    display: flex;
    align-items: center;
    gap: 15px;
  }

  .nav-buttons a {
    text-decoration: none;
    color: #fff;
    padding: 5px 10px;
    background-color: #f2681d;
    border: 1px solid #f2681d;
    transition: all 1s ease-in-out;
  }

  .nav-buttons a:hover {
    border: 1px solid white;
  }

  .username {
    color: white;
    font-weight: bold;
  }
</style>

<header>
  <nav class="navbar" id="mynav">
    <div class="logo">
      <a href=" <?php
                $path1 = "../index.php";
                $path2 = "index.php";
                echo file_exists($path1) ? $path1 : $path2;
                ?>">
        <img src="<?php
                  $path1 = "../assets/navbar/DeWatermark.ai_1742196298745-removebg-preview.png";
                  $path2 = "assets/navbar/DeWatermark.ai_1742196298745-removebg-preview.png";
                  echo file_exists($path1) ? $path1 : $path2;
                  ?>" alt="Navbar Logo">
      </a>
    </div>

    <div class="nav-links">
      <ul>
        <li><a href="#FAQ">FAQs</a></li>
        <li><a href="#blog">Blogs</a></li>
        <li><a href="#campground">Campgrounds</a></li>
      </ul>
    </div>

    <div class="nav-buttons">
      <!-- "Become a Host" should always be visible -->
      <!-- <a href="hostForm/campground_form.php">Become a Host</a> -->
      <a href="<?php echo $isLoggedIn ? 'hostForm/campground_form.php' : 'authentication/login.php'; ?>">
        Become a Host
      </a>

      <?php if ($isLoggedIn): ?>
        <!-- Show username and logout button when logged in -->
        <span class="username" style="color: white;">Hello, <?php echo htmlspecialchars($username); ?></span>
        <a href="
        <?php
        $path1 = "authentication/logout.php";
        $path2 = "../authentication/logout.php";
        echo file_exists($path1) ? $path1 : $path2;
        ?>
        ">Logout</a>
      <?php else: ?>
        <!-- Show login button when not logged in -->
        <a href="
        <?php
        $path1 = "authentication/login.php";
        $path2 = "../authentication/login.php";
        echo file_exists($path1) ? $path1 : $path2;
        ?>
        ">Login</a>
      <?php endif; ?>
    </div>
  </nav>
</header>


<script>
  const nav = document.getElementById("mynav");
  window.onscroll = function() {
    if (
      document.body.scrollTop >= 400 ||
      document.documentElement.scrollTop >= 400
    ) {
      nav.classList.add("nav-colored");
      nav.classList.remove("nav-transparent");
    } else {
      nav.classList.add("nav-transparent");
      nav.classList.remove("nav-colored");
    }
  };
</script>