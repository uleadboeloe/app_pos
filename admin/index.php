<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include_once "library/connection.php";
include_once "library/parameter.php";
include_once "library/fungsi.php";
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
    <link rel="stylesheet" href="assets/lineone/css/app.css" />
    <link rel="stylesheet" href="assets/css/custom.css" />
    <!-- Javascript Assets -->
    <script src="assets/lineone/js/app.js" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"rel="stylesheet"/>
  </head>

  <body x-data class="is-header-blur" x-bind="$store.global.documentBody">
    <!-- App preloader-->
    <div class="app-preloader fixed z-50 grid h-full w-full place-content-center bg-orange-50 dark:bg-navy-900 bg-[url(assets/images/please-wait.avif)] bg-no-repeat bg-center">
      <div class="app-preloader-inner relative inline-block h-48 w-48"></div>
    </div>

    <!-- Page Wrapper -->
    <div id="root" class="min-h-100vh flex grow bg-slate-50 dark:bg-navy-900" x-cloak>
      <!-- Main Content Wrapper -->
      <main class="grid w-full grow grid-cols-1 place-items-center">
        <form name="FrmLogin" id="FrmLogin" action="proses-login" method="post">
          <div class="w-full max-w-[26rem] p-4 sm:px-5">
            <div class="text-center">
              <img  class="mx-auto h-16 w-16" src="assets/images/logo.png" alt="logo"/>
              <div class="mt-4">
                <h2 class="text-2xl font-semibold text-green-600 dark:text-navy-100">
                  Amanmart - Halal Terjangkau
                </h2>
              </div>
            </div>
            <div class="card mt-5 rounded-lg p-5 lg:p-7">
              <label class="block">
                <span>Username:</span>
                <span class="relative mt-1.5 flex">
                  <input placeholder="Enter Username" name="txtUid" id="txtUid" autocomplete="off" value="" type="text" class="form-input h-12 peer w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:z-10 hover:border-slate-400 focus:z-10 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"/>
                  <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center font-bold text-green-600 peer-focus:text-green-600 dark:text-navy-300 dark:peer-focus:text-accent">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" fill-opacity="0" d="M12 13l-8 -5v10h16v-10l-8 5Z"><animate fill="freeze" attributeName="fill-opacity" begin="0.8s" dur="0.15s" values="0;0.3"/></path><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path stroke-dasharray="64" stroke-dashoffset="64" d="M4 5h16c0.55 0 1 0.45 1 1v12c0 0.55 -0.45 1 -1 1h-16c-0.55 0 -1 -0.45 -1 -1v-12c0 -0.55 0.45 -1 1 -1Z"><animate fill="freeze" attributeName="stroke-dashoffset" dur="0.6s" values="64;0"/></path><path stroke-dasharray="24" stroke-dashoffset="24" d="M3 6.5l9 5.5l9 -5.5"><animate fill="freeze" attributeName="stroke-dashoffset" begin="0.6s" dur="0.2s" values="24;0"/></path></g></svg>
                  </span>
                </span>
              </label>

              <label class="mt-4 block">
                <span>Password:</span>
                <span class="relative mt-1.5 flex">
                  <input type="password" placeholder="Enter Password" name="txtUserpass" id="txtUserpass" value="" class="form-input peer w-full h-12 rounded-lg border border-slate-300 bg-transparent px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:z-10 hover:border-slate-400 focus:z-10 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"/>
                  <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center font-bold text-green-600 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10s-4.477 10-10 10m-1-9.208V16h2v-3.208a2.5 2.5 0 1 0-2 0"/></svg>
                  </span>
                </span>
              </label>

              <button class="btn mt-5 w-full h-12 bg-green-600 font-medium text-white hover:bg-green-300 focus:bg-green-300 active:bg-green-300 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
                Sign In
              </button>
              <div class="mt-4 text-center text-xs+">
                <p class="line-clamp-1">
                  <span>Login Insanpos</span>
                  <a class="text-primary transition-colors hover:text-primary-focus dark:text-accent-light dark:hover:text-accent"href="../kasir.php">Click Here</a>
                </p>
              </div>

            </div>
          </div>
        </Form>
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
$(document).ready(function(){

});
</script>