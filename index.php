<?php
require_once 'config/database.php';

$db = new Database();
$conn = $db->conn;

// Fetch all campgrounds with their slug and first image
$stmt = $conn->prepare("
    SELECT c.id, c.name, c.location, c.slug, c.price,
           COALESCE(
               (SELECT ci.image_path FROM campground_images ci 
                WHERE ci.campground_id = c.id ORDER BY ci.id ASC LIMIT 1), 
               'assets/default-camp.jpg'
           ) AS first_image
    FROM campgrounds c
    WHERE c.status = 'approved'  -- ✅ Only fetch approved campgrounds
    ORDER BY c.created_at DESC
");

if (!$stmt) {
  die("Query preparation failed: " . $conn->error);
}

$stmt->execute();
$campgrounds_result = $stmt->get_result();

if (!$campgrounds_result) {
  die("Query execution failed: " . $stmt->error);
}

$campgrounds = $campgrounds_result->fetch_all(MYSQLI_ASSOC);

// Fetch latest 6 blogs
$query = "SELECT * FROM blogs ORDER BY created_at DESC LIMIT 6";
$blog_result = $conn->query($query);

// Fetch 10 random approved campgrounds
$random_sql = "
    SELECT c.id, c.name, c.location, c.description,c.slug,
           (SELECT image_path FROM campground_images ci WHERE ci.campground_id = c.id ORDER BY RAND() LIMIT 1) AS image
    FROM campgrounds c 
    WHERE c.status = 'approved' 
    ORDER BY RAND() 
    LIMIT 10
";

$random_campgrounds_result = $conn->query($random_sql);

if (!$random_campgrounds_result) {
  die("Query execution failed: " . $conn->error);
}

$random_campgrounds = $random_campgrounds_result->fetch_all(MYSQLI_ASSOC);

// Close the statement
$stmt->close();
?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta
    name="viewport"
    content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />
  <title>Campify</title>
  <style>
    /* Swiper Styles */
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

    /* Cursor circle */
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
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
    crossorigin="anonymous" />
  <link rel="stylesheet" href="style.css" />
  <script
    src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
    crossorigin="anonymous"></script>
</head>

<body>
  <div class="circle"></div>

  <?php
  include("components/header.php");
  ?>

  <section class="hero">
    <div class="video-container">
      <!-- <video
        onloadstart="this.playbackRate = 1;"
        src="assets/hero/3210473-uhd_3840_2160_25fps.mp4"
        autoplay
        loop
        muted></video> -->
      <video
        class="lazy-video"
        data-src="assets/hero/hero.mp4"
        autoplay loop muted
        poster="assets/hero/hero-2.jpg">
      </video>

      <!-- video-overlay to make video a little dark so that text is visible -->
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
        <?php if (!empty($random_campgrounds)) : ?>
          <?php foreach ($random_campgrounds as $camp) : ?>
            <!-- <?php
                  $imagePath = !empty($camp['image']) ? str_replace('../', '', $camp['image']) : 'assets/default.jpg';
                  ?>
            <div class="item" style="background-image: url('<?= htmlspecialchars($imagePath) ?>');"> -->
            <?php
            // Check if the image path is stored as a full path or just the image name
            if (!empty($camp['image'])) {
              // If the image path contains '../uploads/', assume it's a full path
              if (strpos($camp['image'], '../uploads/') !== false) {
                $imagePath = str_replace('../', '', $camp['image']);
              } else {
                // Otherwise, assume it's just the image name and prepend the correct path
                $imagePath = "uploads/" . $camp['slug'] . "/" . $camp['image'];
              }
            } else {
              $imagePath = "assets/default.jpg"; // Default image
            }
            ?>

            <div class="item" style="background-image: url('<?= htmlspecialchars($imagePath) ?>');">

              <div class="content">
                <div class="title"><?= htmlspecialchars(strtoupper($camp['name'])) ?></div>
                <div class="name"><?= htmlspecialchars($camp['location']) ?></div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else : ?>
          <!-- Hide the entire section if no campgrounds exist -->
          <p class="text-center text-muted">No campgrounds available at the moment.</p>
        <?php endif; ?>
      </div>

      <?php if (count($random_campgrounds) > 1) : ?>
        <!-- Show navigation buttons only if there are multiple camps -->
        <div class="arrows">
          <button class="prev">&lt;</button>
          <button class="next">&gt;</button>
        </div>
      <?php endif; ?>

      <div class="slide-number"></div>
    </div>
  </section>





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

      <swiper-container class="mySwiper" init="false" autoplay="true">
        <?php foreach ($campgrounds as $camp) : ?>
          <swiper-slide>
            <div class="card" style="width: 100%">
              <div style="height: 60%; overflow: hidden">
                <!-- image here -->
                <?php
                $baseURL = "http://localhost/MyProjects/campify/";

                // Ensure $camp contains 'slug'
                $campSlug = isset($camp['slug']) ? $camp['slug'] : null;
                $defaultImage = "assets/default-camp.jpg";

                // If slug and image exist, construct the path
                if (!empty($campSlug) && !empty($camp['first_image'])) {
                  $imagePath = "uploads/" . $campSlug . "/" . basename($camp['first_image']);
                } else {
                  $imagePath = $defaultImage;
                }

                // Construct the full image URL
                $fullImagePath = $baseURL . $imagePath;
                ?>

                <!-- Display the Image -->
                <img src="<?php echo htmlspecialchars($fullImagePath); ?>" loading="lazy" class="card-img-top" alt="<?php echo htmlspecialchars($camp['name']); ?>" />
              </div>
              <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($camp['name']); ?></h5>
                <h6 class="card-subtitle"><?php echo htmlspecialchars($camp['location']); ?></h6>
                <p class="card-text">Experience the beauty of nature...</p>
                <p><strong>Price per Night:</strong> ₹<?= number_format($camp['price'], 2) ?></p>

                <div class="d-flex justify-content-between align-items-center">
                  <a href="hostForm/campground_details.php?slug=<?php echo urlencode($camp['slug']); ?>" class="btn book-btn">Book Now</a>
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
        <?php while ($blog = mysqli_fetch_assoc($blog_result)): ?>
          <div class="col-lg-4">
            <!-- <a href="pages/blog.php?slug=<?php echo htmlspecialchars($blog['slug']); ?>" class="text-decoration-none">
              <div class="blog-item align-content-end p-4 shadow-sm text-white"
                style="background-image: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3)), 
                                 url('<?php echo htmlspecialchars($blog['image']); ?>');
                                background-size: cover;
                                background-position: center;                            
                                border-radius: 10px;">
                <p class="blog-date text-light">
                  <?php echo date("d F, Y", strtotime($blog['created_at'])); ?>
                </p>
                <h4 class="blog-heading">
                  <a href="blog.php?slug=<?php echo htmlspecialchars($blog['slug']); ?>"
                    class="text-decoration-none text-white">
                    <?php echo htmlspecialchars($blog['title']); ?>
                  </a>
                </h4>
                <p class="blog-content">
                  <?php echo htmlspecialchars(substr($blog['content'], 0, 100)); ?>...
                </p>
              </div>
            </a> -->

            <a href="pages/blog.php?slug=<?php echo htmlspecialchars($blog['slug']); ?>" class="text-decoration-none">
              <div class="blog-item align-content-end p-4 shadow-sm text-white"
                style="background-image: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3)), 
                             url('<?php echo htmlspecialchars($blog['image']); ?>');
                            background-size: cover;
                            background-position: center;
                            border-radius: 10px;">
                <p class="blog-date text-light">
                  <?php echo date("d F, Y", strtotime($blog['created_at'])); ?>
                </p>
                <h4 class="blog-heading">
                  <?php echo htmlspecialchars($blog['title']); ?>
                </h4>
                <!-- <p class="blog-content">
                  <?php echo substr(strip_tags(html_entity_decode($blog['content'], ENT_QUOTES | ENT_HTML5)), 0, 100) . '...'; ?>
                </p> -->

              </div>
            </a>

          </div>
        <?php endwhile; ?>
      </div>
    </div>
    <div class="mt-4 text-center">
      <a href="pages/blogs.php" class="btn all-blogs">View All Blogs</a>
    </div>
  </section>

  <section class="faqs" id="FAQ">
    <div class="faq-container">
      <h1>Frequently Asked Questions</h1>
      <!-- Start of the Accordion -->
      <div class="faq-item">
        <button class="faq-question">
          How do I list my campground on Campify?
          <span class="arrow">&#9660;</span>
        </button>
        <div class="faq-answer">
          <p>
            Once registered, log in to your account, go to the "Host a Campground" section, fill in the details, upload images, and submit your listing.
          </p>
        </div>
      </div>

      <div class="faq-item">
        <button class="faq-question">
          Can I book a campground through Campify?
          <span class="arrow">&#9660;</span>
        </button>
        <div class="faq-answer">
          <p>
            Currently, Campify allows users to browse and contact hosts for booking information. Online booking features may be introduced later.
          </p>
        </div>
      </div>

      <div class="faq-item">
        <button class="faq-question">
          Is my personal information safe on Campify?
          <span class="arrow">&#9660;</span>
        </button>
        <div class="faq-answer">
          <p>
            Yes, we prioritize user security and do not share your personal details with third parties.
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



  <script>
    document.addEventListener("DOMContentLoaded", function() {
      let videos = document.querySelectorAll(".lazy-video");

      let observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            let video = entry.target;
            video.src = video.dataset.src;
            observer.unobserve(video);
          }
        });
      });

      videos.forEach(video => {
        observer.observe(video);
      });
    });
  </script>
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