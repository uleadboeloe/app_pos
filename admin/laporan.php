<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include_once "library/connection.php";
include_once "library/parameter.php";
include_once "library/fungsi.php";
include_once "../lib_dbo/user_functions.php";
include_once "../lib/general_lib.php";

if(isset($_SESSION['SESS_user_id'])){
  $hash16 = CreateUniqueHash16();
  $ReportEndDate = date("Y-m-d", strtotime( "$currdatedb +15 Day" ));    
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
    <?php   include "sidebar.laporan.php";  ?>
      <!-- Main Content Wrapper -->
      <main class="main-content w-full px-[var(--margin-x)] pb-8">
        <form name="formProses" name="frmLaporan" id="frmLaporan" method="post" action="proses-laporan" enctype="multipart/form-data">
        <div class="grid grid-cols-2 my-2 gap-4 sm:gap-5 lg:gap-6">
            <div class="col-span-12 sm:col-span-12">
                <div class="card p-4 sm:p-5">
                    <div class="space-y-4">
                        <input type="hidden" id="txtRandomCode" name="txtRandomCode" value="<?php echo $hash16;   ?>" readonly>
                        <input type="hidden" id="txtKodeKasir" name="txtKodeKasir" value="<?php echo $_SESSION['SESS_kode_kasir'];   ?>" readonly>
                        <label class="block">
                            <span class="text-purple-500 font-bold">Periode Laporan <div class="badge rounded-full bg-primary/10 text-primary dark:bg-accent-light/15 dark:text-accent-light">Wajib</div></span>                                    
                            <label class="relative mt-1.5 flex">
                                <input type="text" id="txtPeriodeLaporan" name="txtPeriodeLaporan" x-flatpickr="{mode: 'range',dateFormat: 'Y-m-d',defaultDate: ['<?php echo    $currdatedb;    ?>', '<?php echo    $ReportEndDate;   ?>'] }"
                                class="form-input peer h-12 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
                                placeholder="Choose date..." required/>
                                <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                                    <i class="fa-regular fa-building text-base"></i>
                                </span>
                            </label>
                        </label>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <label class="block">
                                <span class="relative mt-1.5 flex">						
                                    <input type="submit" name="btnSubmit" id="btnSubmit" value="Proses Laporan"
                                    class="btn space-x-2 bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
                                </span>
                            </label>
                        </div>                          
                    </div>
                </div>
            </div>
        </div>
        </form>

        <form name="formProses" name="frmLaporan" id="frmLaporan" method="post" action="proses-tanggallaporan" enctype="multipart/form-data">
        <div class="grid grid-cols-2 my-2 gap-4 sm:gap-5 lg:gap-6">
            <div class="col-span-12 sm:col-span-12">
                <div class="card p-4 sm:p-5">
                    <div class="space-y-4">
                        <input type="hidden" id="txtRandomCode" name="txtRandomCode" value="<?php echo $hash16;   ?>" readonly>
                        <input type="hidden" id="txtKodeKasir" name="txtKodeKasir" value="<?php echo $_SESSION['SESS_kode_kasir'];   ?>" readonly>
                        <label class="block">
                            <span class="text-purple-500 font-bold">Tanggal Laporan <div class="badge rounded-full bg-primary/10 text-primary dark:bg-accent-light/15 dark:text-accent-light">Wajib</div></span>                                    
                            <label class="relative mt-1.5 flex">
                                <input type="text" id="txtStartDate" name="txtStartDate" x-flatpickr
                                class="form-input peer h-12 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
                                placeholder="Choose date..." required/>
                                <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                                    <i class="fa-regular fa-building text-base"></i>
                                </span>
                            </label>
                        </label>                        

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <label class="block">
                                <span class="relative mt-1.5 flex">						
                                    <input type="submit" name="btnSubmit" id="btnSubmit" value="Proses Laporan"
                                    class="btn space-x-2 bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
                                </span>
                            </label>
                        </div>                          
                    </div>
                </div>
            </div>
        </div>
        </form>        

      
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
(function($){
	$(function(){
	  $('select[id="txtSearch"]').selectToAutocomplete();
	});
})(jQuery);

$(document).ready(function(){
    var txtErrorType = $("#txtErrorType").val();
    var txtErrorDescription = $("#txtErrorDescription").val();

    if(txtErrorDescription != ""){
        Swal.fire({
            position: 'center',
            icon: txtErrorType,
            html: txtErrorDescription,
            confirmButtonColor: "#3085d6",
            confirmButtonText: "Tutup Pesan Ini",
        })
    }   
});
</script>
<?php
}else{
header("Location: home");
}
?>