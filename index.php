<?php
require_once 'config/database.php';

$db = new Database();
$conn = $db->conn;

// Fetch campgrounds from the database
$stmt = $conn->prepare("SELECT id, name, location, image FROM campgrounds");
$stmt->execute();
$result = $stmt->get_result();
$campgrounds = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta
    name="viewport"
    content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />
  <title>Campify</title>
  <script
    src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
    crossorigin="anonymous"></script>
  <style>
    html,
    body {
      position: relative;
      height: 100%;
    }

    body {
      background: #eee;
      font-family: Helvetica Neue, Helvetica, Arial, sans-serif;
      font-size: 14px;
      color: #000;
      margin: 0;
      padding: 0;
    }

    swiper-container {
      width: 100%;
      height: 100%;
    }

    swiper-slide {
      text-align: center;
      font-size: 18px;
      background: #fff;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    swiper-slide img {
      display: block;
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
  </style>
  <style>
    .circle {
      --circle-size: 40px;
      position: fixed;
      height: var(--circle-size);
      width: var(--circle-size);
      border: 2px solid #f2681d;
      border-radius: 100%;
      top: calc(var(--circle-size) / 2 * -1);
      left: calc(var(--circle-size) / 2 * -1);
      pointer-events: none;
      z-index: 100000;
    }
  </style>
  <link
    href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css"
    rel="stylesheet" />
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
    crossorigin="anonymous" />
  <link rel="stylesheet" href="style.css" />
</head>

<body>
  <div class="circle"></div>

  <?php
  include("components/header.php");
  ?>

  <section class="hero">
    <div class="video-container">
      <video
        onloadstart="this.playbackRate = 1;"
        src="assets/hero/3210473-uhd_3840_2160_25fps.mp4"
        autoplay
        loop
        muted></video>
      <div class="video-overlay"></div>
      <div class="row">
        <div class="col-lg-6">
          <div class="overlay-content hero-text">
            <h2 class="heading-title-new">
              Escape to Nature, Stay with Comfort.
            </h2>
            <p class="mb-0">
              Discover the perfect getaway where adventure and relaxation
              meet.
            </p>
            <p class="mb-0">
              Camp under the stars, explore scenic trails, and unwind by the
              campfire—all at Campify.
            </p>
            <button onclick="window.location.href='pages/camp.php'">
              Start Your Adventure
            </button>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="campgrounds">
    <h2>Top Campgrounds</h2>
    <div class="carousel container">
      <div class="list">
        <div
          class="item"
          style="
              background-image: url(assets/campgrounds/free-photo-of-barren-mountain-peaks.jpeg);
            ">
          <div class="content">
            <div class="title">SUNRISE ON PEAKS</div>
            <div class="name">Sunrise</div>
            <div class="des">
              Witness the serene beauty of the sunrise over majestic mountain
              peaks. A moment of pure tranquility.
            </div>
          </div>
        </div>

        <div
          class="item"
          style="
              background-image: url(assets/campgrounds/free-photo-of-barren-mountains-peaks-under-clouds.jpeg);
            ">
          <div class="content">
            <div class="title">RUGGED ROCKS</div>
            <div class="name">Rocky</div>
            <div class="des">
              Explore the rugged beauty of barren rocky mountains. A testament
              to nature's raw power.
            </div>
          </div>
        </div>

        <div
          class="item"
          style="
              background-image: url(assets/campgrounds/free-photo-of-barren-rocky-mountains.jpeg);
            ">
          <div class="content">
            <div class="title">FOREST PATHWAY</div>
            <div class="name">Forest</div>
            <div class="des">
              A peaceful trail through dense green forests. Perfect for
              reconnecting with nature.
            </div>
          </div>
        </div>

        <div
          class="item"
          style="
              background-image: url(assets/campgrounds/free-photo-of-mountain-peaks-over-clouds.jpeg);
            ">
          <div class="content">
            <div class="title">COLORFUL MEADOW</div>
            <div class="name">Meadow</div>
            <div class="des">
              A colorful meadow filled with butterflies and blooming flowers.
              Nature at its best.
            </div>
          </div>
        </div>

        <div
          class="item"
          style="
              background-image: url(assets/campgrounds/pexels-photo-2073873.jpeg);
            ">
          <div class="content">
            <div class="title">SERENE LAKE</div>
            <div class="name">Lake</div>
            <div class="des">
              A calm and serene lake surrounded by towering trees and
              mountains. A perfect escape.
            </div>
          </div>
        </div>

        <div
          class="item"
          style="
              background-image: url(assets/campgrounds/pexels-photo-2832061.jpeg);
            ">
          <div class="content">
            <div class="title">PEAKS IN THE CLOUDS</div>
            <div class="name">Clouds</div>
            <div class="des">
              Mountain peaks wrapped in clouds. A dreamy sight that inspires
              awe and wonder.
            </div>
          </div>
        </div>

        <div
          class="item"
          style="
              background-image: url(assets/campgrounds/pexels-photo-552784.jpeg);
            ">
          <div class="content">
            <div class="title">RIVERBANK PARADISE</div>
            <div class="name">Riverbank</div>
            <div class="des">
              A picturesque riverbank flowing through lush greenery and
              vibrant landscapes.
            </div>
          </div>
        </div>

        <div
          class="item"
          style="
              background-image: url(assets/campgrounds/pexels-photo-552785.jpeg);
            ">
          <div class="content">
            <div class="title">MYSTIC RIDGES</div>
            <div class="name">Ridges</div>
            <div class="des">
              Discover the mystic beauty of mountain ridges under a cloudy
              sky. Perfect for adventurers.
            </div>
          </div>
        </div>

        <div
          class="item"
          style="
              background-image: url(assets/campgrounds/pexels-photo-6439041.jpeg);
            ">
          <div class="content">
            <div class="title">GOLDEN CLIFFS</div>
            <div class="name">Cliffs</div>
            <div class="des">
              Golden cliffs basking in sunlight. A stunning view that captures
              the heart of nature.
            </div>
          </div>
        </div>

        <div
          class="item"
          style="
              background-image: url(assets/campgrounds/pexels-photo-7616134.jpeg);
            ">
          <div class="content">
            <div class="title">PEACEFUL VALLEY</div>
            <div class="name">Valley</div>
            <div class="des">
              A peaceful valley surrounded by towering mountains. A perfect
              destination for solitude.
            </div>
          </div>
        </div>
      </div>

      <!--next prev button-->
      <div class="arrows">
        <button class="prev">
          <
            </button>
            <button class="next">
              >
            </button>

            <div class="slide-number"></div>
      </div>

      <!-- time running -->
      <!-- <div class="timeRunning"></div> -->
    </div>
  </section>

  <!-- <section class="locations" id="campground">
    <div class="container">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="location-main">Explore Camps</h4>
        <div class="swiper-navigation d-flex justify-content-end">
          <button class="custom-prev">
            <img src="assets/locations/arrow-left-line.svg" alt="" />
          </button>
          <button class="custom-next">
            <img src="assets/locations/arrow-right-line.svg" alt="" />
          </button>
        </div>
      </div>
      <swiper-container class="mySwiper" init="false">
        <swiper-slide>
          <div class="card" style="width: 100%">
            <div style="height: 60%; overflow: hidden">
              <img
                src="assets/locations/location-1.jpg"
                class="card-img-top"
                alt="..." />
            </div>
            <div class="card-body">
              <h5 class="card-title">Him Camps</h5>
              <h6 class="card-subtitle">Manali, H.P</h6>
              <p class="card-text">Some quick example text to...</p>
              <div class="d-flex justify-content-between align-items-center">
                <p>₹1700 / night</p>
                <a href="#" class="btn">Book Now</a>
              </div>
            </div>
          </div>
        </swiper-slide>
        <swiper-slide>
          <div class="card" style="width: 100%">
            <div style="height: 60%; overflow: hidden">
              <img
                src="assets/locations/location-2.jpg"
                class="card-img-top"
                alt="..." />
            </div>
            <div class="card-body">
              <h5 class="card-title">Him Camps</h5>
              <h6 class="card-subtitle">Manali, H.P</h6>
              <p class="card-text">Some quick example text to...</p>
              <div class="d-flex justify-content-between align-items-center">
                <p>₹1700 / night</p>
                <a href="#" class="btn">Book Now</a>
              </div>
            </div>
          </div>
        </swiper-slide>
        <swiper-slide>
          <div class="card" style="width: 100%">
            <div style="height: 60%; overflow: hidden">
              <img
                src="assets/locations/location-3.jpg"
                class="card-img-top"
                alt="..." />
            </div>
            <div class="card-body">
              <h5 class="card-title">Him Camps</h5>
              <h6 class="card-subtitle">Manali, H.P</h6>
              <p class="card-text">Some quick example text to...</p>
              <div class="d-flex justify-content-between align-items-center">
                <p>₹1700 / night</p>
                <a href="#" class="btn">Book Now</a>
              </div>
            </div>
          </div>
        </swiper-slide>
        <swiper-slide>
          <div class="card" style="width: 100%">
            <div style="height: 60%; overflow: hidden">
              <img
                src="assets/locations/location-4.jpg"
                class="card-img-top"
                alt="..." />
            </div>
            <div class="card-body">
              <h5 class="card-title">Him Camps</h5>
              <h6 class="card-subtitle">Manali, H.P</h6>
              <p class="card-text">Some quick example text to...</p>
              <div class="d-flex justify-content-between align-items-center">
                <p>₹1700 / night</p>
                <a href="#" class="btn">Book Now</a>
              </div>
            </div>
          </div>
        </swiper-slide>
        <swiper-slide>
          <div class="card" style="width: 100%">
            <div style="height: 60%; overflow: hidden">
              <img
                src="assets/locations/location-5.jpg"
                class="card-img-top"
                alt="..." />
            </div>
            <div class="card-body">
              <h5 class="card-title">Him Camps</h5>
              <h6 class="card-subtitle">Manali, H.P</h6>
              <p class="card-text">Some quick example text to...</p>
              <div class="d-flex justify-content-between align-items-center">
                <p>₹1700 / night</p>
                <a href="#" class="btn">Book Now</a>
              </div>
            </div>
          </div>
        </swiper-slide>
        <swiper-slide>
          <div class="card" style="width: 100%">
            <div style="height: 60%; overflow: hidden">
              <img
                src="assets/locations/location-6.jpg"
                class="card-img-top"
                alt="..." />
            </div>
            <div class="card-body d-flex flex-column justify-content-center">
              <h5 class="card-title">Him Camps</h5>
              <h6 class="card-subtitle">Manali, H.P</h6>
              <p class="card-text">Some quick example text to...</p>
              <div class="d-flex justify-content-between align-items-center">
                <p>₹1700 / night</p>
                <a href="#" class="btn">Book Now</a>
              </div>
            </div>
          </div>
        </swiper-slide>
        <swiper-slide>
          <div class="card" style="width: 100%">
            <div style="height: 60%; overflow: hidden">
              <img
                src="assets/locations/location-7.jpg"
                class="card-img-top"
                alt="..." />
            </div>
            <div class="card-body d-flex flex-column justify-content-center">
              <h5 class="card-title">Him Camps</h5>
              <h6 class="card-subtitle">Manali, H.P</h6>
              <p class="card-text">Some quick example text to...</p>
              <div class="d-flex justify-content-between align-items-center">
                <p>₹1700 / night</p>
                <a href="#" class="btn">Book Now</a>
              </div>
            </div>
          </div>
        </swiper-slide>
        <swiper-slide>
          <div class="card" style="width: 100%">
            <div style="height: 60%; overflow: hidden">
              <img
                src="assets/locations/location-8.jpg"
                class="card-img-top"
                alt="..." />
            </div>
            <div class="card-body d-flex flex-column justify-content-center">
              <h5 class="card-title">Him Camps</h5>
              <h6 class="card-subtitle">Manali, H.P</h6>
              <p class="card-text">Some quick example text to...</p>
              <div class="d-flex justify-content-between align-items-center">
                <p>₹1700 / night</p>
                <a href="#" class="btn">Book Now</a>
              </div>
            </div>
          </div>
        </swiper-slide>
      </swiper-container>
    </div>

    <div class="mt-4 text-center all-locations">
      <a
        class=""
        href="hostForm/view_campgrounds.php"
        style="
            text-decoration: none;
            font-size: 20px;
            border: 1px solid #f2681d;
            color: #f2681d;
            padding: 10px 20px;
            transition: all 0.5s ease-in-out;
          ">View all camps</a>
    </div>
  </section> -->

  <section class="locations" id="campground">
    <div class="container">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="location-main">Explore Camps</h4>
        <div class="swiper-navigation d-flex justify-content-end">
          <button class="custom-prev">
            <img src="assets/locations/arrow-left-line.svg" alt="" />
          </button>
          <button class="custom-next">
            <img src="assets/locations/arrow-right-line.svg" alt="" />
          </button>
        </div>
      </div>

      <swiper-container class="mySwiper" init="false">
        <?php foreach ($campgrounds as $camp) : ?>
          <swiper-slide>
            <div class="card" style="width: 100%">
              <div style="height: 60%; overflow: hidden">
                <?php
                $imagePath = $camp['image'];

                // Remove "../" from the path
                $imagePath = str_replace("../", "", $imagePath);

                // Check if file exists, else use a default image
                if (!file_exists($imagePath) || empty($camp['image'])) {
                  $imagePath = "assets/default-camp.jpg";
                }
                ?>

                <img src="<?php echo htmlspecialchars($imagePath); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($camp['name']); ?>" />

              </div>
              <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($camp['name']); ?></h5>
                <h6 class="card-subtitle"><?php echo htmlspecialchars($camp['location']); ?></h6>
                <p class="card-text">Experience the beauty of nature...</p>
                <div class="d-flex justify-content-between align-items-center">
                  <!-- <p>₹<?php echo number_format($camp['price']); ?> / night</p> -->
                  <a href="hostForm/campground_details.php?id=<?php echo $camp['id']; ?>" class="btn">Book Now</a>
                </div>
              </div>
            </div>
          </swiper-slide>
        <?php endforeach; ?>
      </swiper-container>
    </div>

    <div class="mt-4 text-center all-locations">
      <a href="hostForm/view_campgrounds.php" class="view-all-camps">View all camps</a>
    </div>
  </section>

  <section class="blogs" id="blog">
    <h2 class="text-center mb-4">Latest Blogs</h2>
    <div class="container">
      <div class="row">
        <div class="col-lg-4">
          <div class="blog-item align-content-end p-4 blog-item-1">
            <p class="blog-date">10 March</p>
            <h4 class="blog-heading">Mountains</h4>
            <p class="blog-content">
              Lorem ipsum dolor sit amet consectetur adipisicing elit.
              Aperiam, dicta.
            </p>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="blog-item align-content-end p-4 blog-item-2">
            <p class="blog-date">10 March</p>
            <h4 class="blog-heading">Mountains</h4>
            <p class="blog-content">
              Lorem ipsum dolor sit amet consectetur adipisicing elit.
              Aperiam, dicta.
            </p>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="blog-item align-content-end p-4 blog-item-3">
            <p class="blog-date">10 March</p>
            <h4 class="blog-heading">Mountains</h4>
            <p class="blog-content">
              Lorem ipsum dolor sit amet consectetur adipisicing elit.
              Aperiam, dicta.
            </p>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="blog-item align-content-end p-4 blog-item-4">
            <p class="blog-date">10 March</p>
            <h4 class="blog-heading">Mountains</h4>
            <p class="blog-content">
              Lorem ipsum dolor sit amet consectetur adipisicing elit.
              Aperiam, dicta.
            </p>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="blog-item align-content-end p-4 blog-item-5">
            <p class="blog-date">10 March</p>
            <h4 class="blog-heading">Mountains</h4>
            <p class="blog-content">
              Lorem ipsum dolor sit amet consectetur adipisicing elit.
              Aperiam, dicta.
            </p>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="blog-item align-content-end p-4 blog-item-6">
            <p class="blog-date">10 March</p>
            <h4 class="blog-heading">Mountains</h4>
            <p class="blog-content">
              Lorem ipsum dolor sit amet consectetur adipisicing elit.
              Aperiam, dicta.
            </p>
          </div>
        </div>
      </div>
    </div>
    <div class="mt-4 text-center"><a href="">View all Blogs</a></div>
  </section>

  <section class="faqs" id="FAQ">
    <div class="faq-container">
      <h1>Frequently Asked Questions</h1>
      <!-- Start of the Accordion -->
      <div class="faq-item">
        <button class="faq-question">
          What is your return policy?
          <span class="arrow">&#9660;</span>
        </button>
        <div class="faq-answer">
          <p>
            You can return any item within 30 days of purchase for a full
            refund.
          </p>
        </div>
      </div>

      <div class="faq-item">
        <button class="faq-question">
          Do you offer international shipping?
          <span class="arrow">&#9660;</span>
        </button>
        <div class="faq-answer">
          <p>
            Yes, we ship to most countries worldwide. Shipping fees apply.
          </p>
        </div>
      </div>

      <div class="faq-item">
        <button class="faq-question">
          How can I track my order?
          <span class="arrow">&#9660;</span>
        </button>
        <div class="faq-answer">
          <p>
            Once your order is shipped, we will send you a tracking number via
            email.
          </p>
        </div>
      </div>
      <!-- End of the Accordion -->
    </div>
  </section>

  <!-- Site footer -->

  <?php
  include("components/footer.php");
  ?>


  <!--BACK TO TOP BUTTON-->
  <div class="m-backtotop" aria-hidden="true">
    <div class="arrow">
      <!-- <i class="fa fa-arrow-up"></i> -->
      <img src="assets/top.png" style="width: 50px" alt="" />
    </div>
    <div class="text">Back to top</div>
  </div>

  <script src="script.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-element-bundle.min.js"></script>
  <script>
    const swiperEl = document.querySelector("swiper-container");
    Object.assign(swiperEl, {
      slidesPerView: 1,
      spaceBetween: 10,

      navigation: {
        nextEl: ".custom-next",
        prevEl: ".custom-prev",
      },
      breakpoints: {
        640: {
          slidesPerView: 2,
          spaceBetween: 20,
        },
        768: {
          slidesPerView: 3,
          spaceBetween: 10,
        },
        1024: {
          slidesPerView: 4,
          spaceBetween: 20,
        },
      },
    });
    swiperEl.initialize();
  </script>
  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
  <script>
    // Select the circle element
    const circleElement = document.querySelector('.circle');

    // Create objects to track mouse position and custom cursor position
    const mouse = {
      x: 0,
      y: 0
    }; // Track current mouse position
    const previousMouse = {
      x: 0,
      y: 0
    } // Store the previous mouse position
    const circle = {
      x: 0,
      y: 0
    }; // Track the circle position

    // Initialize variables to track scaling and rotation
    let currentScale = 0; // Track current scale value
    let currentAngle = 0; // Track current angle value

    // Update mouse position on the 'mousemove' event
    window.addEventListener('mousemove', (e) => {
      mouse.x = e.x;
      mouse.y = e.y;
    });

    // Smoothing factor for cursor movement speed (0 = smoother, 1 = instant)
    const speed = 0.17;

    // Start animation
    const tick = () => {
      // MOVE
      // Calculate circle movement based on mouse position and smoothing
      circle.x += (mouse.x - circle.x) * speed;
      circle.y += (mouse.y - circle.y) * speed;
      // Create a transformation string for cursor translation
      const translateTransform = `translate(${circle.x}px, ${circle.y}px)`;

      // SQUEEZE
      // 1. Calculate the change in mouse position (deltaMouse)
      const deltaMouseX = mouse.x - previousMouse.x;
      const deltaMouseY = mouse.y - previousMouse.y;
      // Update previous mouse position for the next frame
      previousMouse.x = mouse.x;
      previousMouse.y = mouse.y;
      // 2. Calculate mouse velocity using Pythagorean theorem and adjust speed
      const mouseVelocity = Math.min(Math.sqrt(deltaMouseX ** 2 + deltaMouseY ** 2) * 4, 150);
      // 3. Convert mouse velocity to a value in the range [0, 0.5]
      const scaleValue = (mouseVelocity / 150) * 0.5;
      // 4. Smoothly update the current scale
      currentScale += (scaleValue - currentScale) * speed;
      // 5. Create a transformation string for scaling
      const scaleTransform = `scale(${1 + currentScale}, ${1 - currentScale})`;

      // ROTATE
      // 1. Calculate the angle using the atan2 function
      const angle = Math.atan2(deltaMouseY, deltaMouseX) * 180 / Math.PI;
      // 2. Check for a threshold to reduce shakiness at low mouse velocity
      if (mouseVelocity > 20) {
        currentAngle = angle;
      }
      // 3. Create a transformation string for rotation
      const rotateTransform = `rotate(${currentAngle}deg)`;

      // Apply all transformations to the circle element in a specific order: translate -> rotate -> scale
      circleElement.style.transform = `${translateTransform} ${rotateTransform} ${scaleTransform}`;

      // Request the next frame to continue the animation
      window.requestAnimationFrame(tick);
    }

    // Start the animation loop
    tick();
  </script>
</body>

</html>