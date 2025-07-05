<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once "admin/library/connection.php";
include_once "admin/library/parameter.php";
include_once "admin/library/fungsi.php";
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Meta tags  -->
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"/>

    <title><?php    echo $TitleApps;    ?></title>
    <link rel="icon" type="image/png" href="images/favicon.png" />
    <!-- CSS Assets -->
    <link rel="stylesheet" href="admin/assets/lineone/css/app.css" />
    <link rel="stylesheet" href="admin/assets/css/custom.css" />
    <!-- Javascript Assets -->
    <script src="admin/assets/lineone/js/app.js" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"rel="stylesheet"/>

	<!-- Include Owl Carousel CSS and JS -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css"/>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css"/>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

  </head>

  <body x-data class="is-header-blur" x-bind="$store.global.documentBody">
    <!-- App preloader-->
    <div class="app-preloader fixed z-50 grid h-full w-full place-content-center bg-orange-50 dark:bg-navy-900 bg-[url(assets/images/please-wait.avif)] bg-no-repeat bg-center">
      <div class="app-preloader-inner relative inline-block h-48 w-48"></div>
    </div>

    <!-- Page Wrapper -->
    <div id="root" class="bg-slate-50 dark:bg-navy-900" x-cloak>
      <!-- Main Content Wrapper -->
      <main class="w-full">
		<div class="w-full flex sm:px-5">
			<div class="w-1/2">
				<video class="video" width="100%" controls autoplay loop>
					<source src="admin\assets\promo.mp4" type="video/mp4" />
				</video>
			</div>
			<div class="w-1/2">
				<div id="display_1"></div>
			</div>	
		</div>
      </main>
    </div>
    <!-- 
        This is a place for Alpine.js Teleport feature 
        @see https://alpinejs.dev/directives/teleport
      -->
    <div id="x-teleport-target"></div>
    <script>
      window.addEventListener("DOMContentLoaded", () => Alpine.start());
    </script>
  </body>
</html>
<script type="text/javascript" src="assets/js/autocomplete/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="assets/js/autocomplete/jquery-ui.min.js"></script>
<script type="text/javascript" src="assets/js/autocomplete/jquery.select-to-autocomplete.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
setInterval(function(){
	$('#display_1').load("list.display.user.php").fadeIn("slow");
},1000);

$(document).ready(function(){
  $('.owl-carousel').owlCarousel({
      loop:true,
      margin:10,
      nav:false,
      items:1,
      autoplay:true,
      autoplayTimeout:5000,
      autoplayHoverPause:true
  });
});
</script>