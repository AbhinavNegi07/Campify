 <link
   href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
   rel="stylesheet"
   integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
   crossorigin="anonymous" />
 <style>
   <?php
    // Define the primary and fallback image paths
    $primaryImage = "assets/campgrounds/pexels-photo-552784.jpeg";
    $fallbackImage = "../assets/campgrounds/pexels-photo-552784.jpeg"; // Provide a fallback image

    // Check if the primary image exists, otherwise use the fallback
    $backgroundImage = file_exists($primaryImage) ? $primaryImage : $fallbackImage;
    ?>.site-footer {
     background-image: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3)),
       url('<?php echo $backgroundImage; ?>');
     background-position: top;
     background-size: cover;
   }

   .site-footer {
     background-color: #26272b;
     padding: 45px 0 20px;
     font-size: 16px;
     line-height: 2;
     color: #fff;
     margin-top: 40px;
   }

   .site-footer hr {
     border-top-color: #bbb;
     opacity: 0.5;
   }

   .site-footer hr.small {
     margin: 20px 0;
   }

   .site-footer h6 {
     color: #f2681d;
     font-size: 16px;
     font-weight: 700;
     text-transform: uppercase;
     margin-top: 5px;
     letter-spacing: 2px;
   }

   .site-footer a {
     color: #fff;
   }

   .site-footer a:hover {
     color: #f2681d;
     text-decoration: none;
   }

   .footer-links {
     padding-left: 0;
     list-style: none;
   }

   .footer-links li {
     display: block;
   }

   .footer-links a {
     color: #fff;
     text-decoration: none;
   }

   .footer-links a:active,
   .footer-links a:focus,
   .footer-links a:hover {
     color: #f2681d;
     text-decoration: none;
   }

   .footer-links.inline li {
     display: inline-block;
   }

   .site-footer .social-icons {
     text-align: right;
   }

   .site-footer .social-icons a {
     width: 40px;
     height: 40px;
     line-height: 40px;
     margin-left: 6px;
     margin-right: 0;
     border-radius: 100%;
     background-color: #fff;
   }

   .copyright-text {
     margin: 0;
   }

   .about {
     padding: 0 40px 0 0;
   }

   @media (max-width: 991px) {
     .site-footer [class^="col-"] {
       margin-bottom: 30px;
     }
   }

   @media (max-width: 767px) {
     .site-footer {
       padding-bottom: 0;
     }

     .site-footer .copyright-text,
     .site-footer .social-icons {
       text-align: center;
     }
   }

   .social-icons {
     padding-left: 0;
     margin-bottom: 0;
     list-style: none;
   }

   .social-icons li {
     display: inline-block;
     margin-bottom: 4px;
   }

   .social-icons li.title {
     margin-right: 15px;
     text-transform: uppercase;
     color: #96a2b2;
     font-weight: 700;
     font-size: 13px;
   }

   .social-icons a {
     background-color: #eceeef;
     color: #818a91;
     font-size: 16px;
     display: inline-block;
     line-height: 44px;
     width: 44px;
     height: 44px;
     text-align: center;
     margin-right: 8px;
     border-radius: 100%;
     -webkit-transition: all 0.2s linear;
     -o-transition: all 0.2s linear;
     transition: all 0.2s linear;
   }

   .social-icons a:active,
   .social-icons a:focus,
   .social-icons a:hover {
     color: #fff;
     background-color: #29aafe;
   }

   .social-icons.size-sm a {
     line-height: 34px;
     height: 34px;
     width: 34px;
     font-size: 14px;
   }

   .social-icons img {
     width: 25px;
   }

   .social-icons a:hover {
     background-color: #f2681d;
   }

   @media (max-width: 767px) {
     .social-icons li.title {
       display: block;
       margin-right: 0;
       font-weight: 600;
     }
   }
 </style>

 <footer class="site-footer">
   <div class="container">
     <div class="row">
       <div class="col-sm-12 col-md-6 about">
         <h6>Campify</h6>

         <p class="text-justify">
           Lorem ipsum, dolor sit amet consectetur adipisicing elit.
           Officiis, expedita aliquam iure ea dicta illum iusto sunt earum
           sit magnam corporis doloribus quae consectetur laudantium id
           tempore nesciunt voluptate? Voluptate, reprehenderit commodi dolor
           voluptatum eaque sunt minus labore ex? Repellat.
         </p>
       </div>

       <div class="col-xs-6 col-md-3">
         <h6>Top Locations</h6>
         <ul class="footer-links">
           <li>
             <a href="#">Manali</a>
           </li>
           <li>
             <a href="#">Shimla</a>
           </li>
           <li>
             <a href="#">Dharamshala</a>
           </li>
           <li>
             <a href="#">Dalhousie</a>
           </li>
         </ul>
       </div>

       <div class="col-xs-6 col-md-3">
         <h6>Quick Links</h6>
         <ul class="footer-links">
           <li><a href="#">About Us</a></li>
           <li><a href="#">Contact Us</a></li>
           <li>
             <a href="#">Contribute</a>
           </li>
           <li>
             <a href="#">Privacy Policy</a>
           </li>
           <li><a href="#">Sitemap</a></li>
         </ul>
       </div>
     </div>
     <hr />
   </div>
   <div class="container">
     <div class="row">
       <div class="col-md-8 col-sm-6 col-xs-12">
         <p class="copyright-text">
           Copyright &copy; 2025 All Rights Reserved by
           <a href="#">Campify</a>.
         </p>
       </div>

       <div class="col-md-4 col-sm-6 col-xs-12">
         <ul class="social-icons">
           <li>
             <a class="facebook" href="#">

               <img src="<?php
                          $path1 = "../assets/footer/facebook-app-symbol (1).png";
                          $path2 = "assets/footer/facebook-app-symbol (1).png";
                          echo file_exists($path1) ? $path1 : $path2;
                          ?>" alt="Navbar Logo">
             </a>
           </li>
           <li>
             <a class="twitter" href="#">

               <img src="<?php
                          $path1 = "../assets/footer/twitter.png";
                          $path2 = "assets/footer/twitter.png";
                          echo file_exists($path1) ? $path1 : $path2;
                          ?>" alt="Navbar Logo">

             </a>
           </li>

           <li>
             <a class="instagram" href="#">
               <img src="<?php
                          $path1 = "../assets/footer/instagram.png";
                          $path2 = "assets/footer/instagram.png";
                          echo file_exists($path1) ? $path1 : $path2;
                          ?>" alt="Navbar Logo"></a>
           </li>
         </ul>
       </div>
     </div>
   </div>
 </footer>

 <script
   src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
   integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
   crossorigin="anonymous"></script>