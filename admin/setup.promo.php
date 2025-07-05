<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include_once "library/connection.php";
include_once "library/parameter.php";
include_once "library/fungsi.php";
include_once "../lib_dbo/user_functions.php";
$hash16 = CreateUniqueHash16();
$PromoEndDate = date("Y-m-d", strtotime( "$currdatedb +3 Day" ));
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

<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/colreorder/1.7.0/css/colReorder.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/rowreorder/1.4.1/css/rowReorder.dataTables.min.css" rel="stylesheet">


<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/colreorder/1.7.0/js/dataTables.colReorder.min.js"></script>
<script src="https://cdn.datatables.net/rowreorder/1.4.1/js/dataTables.rowReorder.min.js"></script>

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
    <?php   include "sidebar.php";  ?>
    <!-- Main Content Wrapper -->
    <main class="main-content w-full px-[var(--margin-x)] pb-8 bg-green-100">
        <div class="col-span-12 p-2 lg:col-span-12">
            <div class="flex items-center justify-between py-3 px-4">
                <h2 class="font-bold text-xl uppercase tracking-wide text-slate-700 dark:text-navy-100">SETUP PROMO</h2>
            </div>

            <form name="formProses" name="frmMasterPromo" id="frmMasterPromo" method="post" action="proses-promo" enctype="multipart/form-data">
                <div class="grid grid-cols-2 my-2 gap-4 sm:gap-5 lg:gap-6">
                    <div class="col-span-12 sm:col-span-12">
                        <div class="card p-4 sm:p-5">
                            <div class="space-y-4">
                                <input type="hidden" id="txtRandomCode" name="txtRandomCode" value="<?php echo $hash16;   ?>" readonly>
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-5">
                                    <label class="block">
                                        <span class="text-purple-500 font-bold">Kode Promo <div class="badge rounded-full bg-primary/10 text-primary dark:bg-accent-light/15 dark:text-accent-light">Wajib, Maksimal 5 Karakter</div></span>
                                        <span class="relative mt-1.5 flex">						
                                            <input placeholder="Masukan Kode Promo" type="text" id="txtKodePromo" name="txtKodePromo" maxlength="5" required
                                            class="form-input peer h-12 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"/>
                                            <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                                                <i class="fa-regular fa-building text-base"></i>
                                            </span>
                                        </span>
                                    </label>
                                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-1 sm:col-span-4">
                                        <label class="block">
                                            <span class="text-purple-500 font-bold">Nama Promosi <div class="badge rounded-full bg-primary/10 text-primary dark:bg-accent-light/15 dark:text-accent-light">Wajib</div></span>
                                            <span class="relative mt-1.5 flex">						
                                                <input placeholder="Masukan Nama Promosi" type="text" id="txtNamaPromo" name="txtNamaPromo" required
                                                class="form-input peer h-12 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"/>
                                                <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                                                    <i class="fa-regular fa-building text-base"></i>
                                                </span>
                                            </span>
                                        </label>    
                                    </div>                                                             
                                </div>
                                <label class="block">
                                    <span class="text-purple-500 font-bold">Deskripsi Promo <div class="badge rounded-full bg-primary/10 text-primary dark:bg-accent-light/15 dark:text-accent-light">Wajib</div></span>
                                    <textarea rows="4" placeholder="Deskripsi Promo" id="txtDeskripsiPromo" name="txtDeskripsiPromo"
                                    class="form-textarea mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent p-2.5 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"></textarea>
                                </label>

                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                                    <label class="block">
                                        <span class="text-purple-500 font-bold">Kode Store <div class="badge rounded-full bg-warning/10 text-warning dark:bg-accent-light/15 dark:text-accent-light">Opsional / kosongkan bila berlaku semua</div></span>
                                        <span class="relative mt-1.5 flex">
                                            <select id="txtKodeStore" name="txtKodeStore"
                                            class="form-select h-12 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs+ hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
                                                <option value="">Pilih Store</option>
                                                <?php
                                                $strSQL="SELECT kode_store,nama_store FROM `dbo_store` where fl_active = 1";
                                                $CallstrSQL=mysqli_query($koneksidb, $strSQL);
                                                while($rec=mysqli_fetch_array($CallstrSQL)){
                                                ?>
                                                <option value="<?php    echo $rec['kode_store']; ?>"><?php    echo $rec['kode_store']; ?> - <?php    echo $rec['nama_store']; ?></option>
                                                <?php
                                                }
                                                ?>                                                 
                                            </select>
                                        </span>
                                    </label>  

                                    <label class="block">
                                        <span class="text-purple-500 font-bold">Periode Promo <div class="badge rounded-full bg-primary/10 text-primary dark:bg-accent-light/15 dark:text-accent-light">Wajib</div></span>                                    
                                        <label class="relative mt-1.5 flex">
                                            <input type="text" id="txtPeriodePromo" name="txtPeriodePromo" x-flatpickr="{mode: 'range',dateFormat: 'Y-m-d',defaultDate: ['<?php echo    $currdatedb;    ?>', '<?php echo    $PromoEndDate;   ?>'] }"
                                            class="form-input peer h-12 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
                                            placeholder="Choose date..." required/>
                                            <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                                                <i class="fa-regular fa-building text-base"></i>
                                            </span>
                                        </label>
                                    </label>
                                    
                                    <label class="block">
                                        <span class="text-purple-500 font-bold">Kriteria Promosi <div class="badge rounded-full bg-primary/10 text-primary dark:bg-accent-light/15 dark:text-accent-light">Wajib</div></span>
                                        <span class="relative mt-1.5 flex">
                                            <select id="txtKriteriaPromo" name="txtKriteriaPromo" required
                                            class="form-select h-12 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs+ hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
                                                <option value="">Pilih Kriteria Promosi</option>
                                                <?php
                                                $strSQL="SELECT noid,label_name FROM `dbo_global` where fl_active = 1 and kategori_global = 'JENISPROMO'";
                                                $CallstrSQL=mysqli_query($koneksidb, $strSQL);
                                                while($rec=mysqli_fetch_array($CallstrSQL)){
                                                ?>
                                                <option value="<?php    echo $rec['noid']; ?>"><?php    echo $rec['label_name']; ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </span>
                                    </label> 
                                </div>  
                                
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-5">
                                <div id="DivKriteriaPromo" class="grid grid-cols-1 gap-4 sm:grid-cols-2 sm:col-span-2"></div>
                                <div id="DivSatuanFree" class="grid grid-cols-1 gap-4 sm:grid-cols-3 sm:col-span-3"></div>
                                </div>
                                
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-5">
                                    <label class="block">
                                        <span class="text-purple-500 font-bold">Promo Images <div class="badge rounded-full bg-warning/10 text-warning dark:bg-accent-light/15 dark:text-accent-light">Opsional</div></span>
                                        <span class="relative mt-1.5 flex">
                                            <input tabindex="-1" type="file" name="picture" id="picture" onchange="previewFile(this);" style="display:none;" class="pointer-events-none"/>
                                            <div class="flex items-center space-x-2 h-12 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs+ hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M21 17H7V3h14m0-2H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2M3 5H1v16a2 2 0 0 0 2 2h16v-2H3m12.96-10.71l-2.75 3.54l-1.96-2.36L8.5 15h11z"/></svg>
                                                <span>Choose File</span>
                                            </div>
                                        </span>
                                    </label>  
                                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-1 sm:col-span-4">
                                        <label class="block">
                                            <span class="text-purple-500 font-bold">Banner Promo <div class="badge rounded-full bg-warning/10 text-warning dark:bg-accent-light/15 dark:text-accent-light">Opsional</div></span>
                                            <span class="relative mt-1.5 flex">
                                                <input tabindex="-1" type="file" name="pictures" id="pictures" onchange="previewFiles(this);" style="display:none;" class="pointer-events-none"/>
                                                <div class="flex items-center space-x-2 h-12 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs+ hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M21 17H7V3h14m0-2H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2M3 5H1v16a2 2 0 0 0 2 2h16v-2H3m12.96-10.71l-2.75 3.54l-1.96-2.36L8.5 15h11z"/></svg>
                                                    <span>Choose File</span>
                                                </div>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-5">
                                    <label class="block">
                                        <img id="previewImg" style="display:none">
                                    </label>
                                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-1 sm:col-span-4">
                                        <label class="block">
                                            <img id="previewImgs" style="display:none">
                                        </label>
                                    </div>
                                </div>

                                <label class="block">
                                    <span class="text-purple-500 font-bold">Pilih Hari Promosi<div class="badge rounded-full bg-primary/10 text-primary dark:bg-accent-light/15 dark:text-accent-light">Wajib</div></span>
                                    <span class="relative mt-1.5 flex">	
                                    <?php
                                    $strSQL="SELECT noid,label_name FROM `dbo_global` where fl_active = 1 and kategori_global = 'LABELHARI'";
                                    $CallstrSQL=mysqli_query($koneksidb, $strSQL);
                                    while($rec=mysqli_fetch_array($CallstrSQL)){
                                    ?>
                                    <div class="badge rounded-lg bg-primary/10 text-primary dark:bg-accent-light/15 dark:text-accent-light mr-2">
                                        <input type="checkbox" checked class="row-check form-check-input w-5 h-5 bg-primary mr-2" name="checkbox[]" id="checkbox<?php echo $rec['noid'];    ?>" value="<?php echo $rec['label_name'];    ?>"> <?php echo $rec['label_name'];    ?>
                                    </div>
                                    <?php
                                    }
                                    ?>     
                                    <div class="badge rounded-lg bg-warning/10 text-warning dark:bg-accent-light/15 dark:text-accent-light mr-2">
                                        <input type="checkbox" checked class="form-check-input w-5 h-5 bg-primary mr-2" name="checkbox0" id="checkbox0" value="0" onclick="toggleCheckAll(this)"> SEMUA HARI
                                    </div>                               
                                    </span>
                                </label>
                                
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    <label class="block">
                                        <span class="relative mt-1.5 flex">						
                                            <input type="submit" name="btnSubmit" id="btnSubmit" value="Proses Simpan"
                                            class="btn space-x-2 bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
                                        </span>
                                    </label>
                                </div>  
                            </div>
                        </div>
                    </div>
                </div>  
            </form>
        </div>

        <div class="col-span-12 p-2 lg:col-span-12">
            <div class="flex items-center justify-between py-2 px-4">
                <h2 class="font-bold text-xl uppercase tracking-wide text-slate-700 dark:text-navy-100">List Promo</h2>
            </div>
            <div class="card p-5 mt-3">
                <table id="table1" class="is-hoverable w-full" width="100%">     
                    <thead>
                    <tr>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Kode Promo</th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Label Promo</th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Periode Promo</th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Kode Store</th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Jenis Promo</th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    /*==========================*/
                    $StrViewQuery="SELECT * from dbo_promo where fl_active in('1','0') and promo_parameter <> 'E' and kode_store in('" . $_SESSION['SESS_kode_store'] . "','') order by promo_start_date desc";
                    //echo $StrSalesDetails . "<hr>";     
                    $callStrViewQuery=mysqli_query($koneksidb, $StrViewQuery);
                    while($recView=mysqli_fetch_array($callStrViewQuery))
                    {
                        $KodeStore = $recView['kode_store'];
                        $NamaStore = getStoreName($KodeStore);

                        $RandomCodePromo = $recView['random_code'];
                        $KodePromo = $recView['kode_promo'];
                        $LabelPromo = $recView['promo_name'];
                        $PromoDescription = $recView['promo_desc'];
                        $PromoStartDate = $recView['promo_start_date'];
                        $DisplayPromoStartDate = date("d M Y", strtotime($PromoStartDate));
                        $PromoEndDate = $recView['promo_end_date'];
                        $DisplayPromoEndDate = date("d M Y", strtotime($PromoEndDate));
                        $FlPromoDay = $recView['fl_promo_day'];
                        $PromoDay = $recView['promo_day'];
                        $PromoType = $recView['promo_type'];
                        $PromoParameter = $recView['promo_parameter'];
                        $PromoImages = $recView['promo_images'];
                        $PromoBanner = $recView['banner_header'];
                        $flStatus = $recView['fl_active'];
                        if($flStatus == '1'){
                            $flStatus = '<span class="badge rounded-full bg-success/10 text-success dark:bg-accent-light/15 dark:text-accent-light">Aktif</span>';
                        }else{
                            $flStatus = '<span class="badge rounded-full bg-warning/10 text-warning dark:bg-accent-light/15 dark:text-accent-light">Tidak Aktif</span>';
                        }
                        ?>
                        <tr class="border-y border-transparent border-b-slate-200 dark:border-b-navy-500">
                        <td class="whitespace-nowrap px-4 py-3 sm:px-5"><a href="detail-promosi@<?php   echo $RandomCodePromo; ?>"><?php   echo $KodePromo; ?></a></td>   
                        <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $LabelPromo; ?></td>    
                        <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $DisplayPromoStartDate; ?> <?php   echo $DisplayPromoEndDate; ?></td>     
                        <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $PromoParameter; ?></td>   
                        <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $PromoType; ?></td>      
                        <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $flStatus; ?></td>      
                        </tr>
                        <?php
                    }
                    ?>
                  </tbody>
                </table>
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
$(document).ready(function (){
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

    $("#txtKriteriaPromo").change(function (e) {
        var txtKriteriaPromo = $(this).val();

        $.ajax({
            type: "POST",
            url: "list.promo.php",
            data: "noid=" + txtKriteriaPromo,
            success: function (data) {
                $("#DivKriteriaPromo").html(data);
            },
        });
    });

    $("#DivKriteriaPromo").change(function (e) {
        var txtSkuPromoValue = $("#txtSkuPromoValue").val();
        var txtKriteriaPromo = $("#txtKriteriaPromo").val();
        $.ajax({
            type: "POST",
            url: "list.promo.uom.php",
            data: "noid=" + txtSkuPromoValue + "&promoid=" + txtKriteriaPromo,
            success: function (data) {
                if((txtKriteriaPromo == 3)||(txtKriteriaPromo == 4)||(txtKriteriaPromo == 5)){
                    $("#DivSatuanFree").html(data);
                }
            },
        });
    });

    let tableTransaksi = new DataTable('#table1', {
        colReorder: false,
        rowReorder: false,
        paging: true,
        responsive: true,
        searching: true,
        info: true,
        sort: true,
        zeroRecords: "",
    });
});    


function previewFile(input){
	var file = $("#picture").get(0).files[0];
	if(file){
		var reader = new FileReader();
		reader.onload = function(){
			$("#previewImg").attr("src", reader.result);
			$("#previewImg").css("display", "block");
		}
		reader.readAsDataURL(file);
	}
}

function previewFiles(input){
	var file = $("#pictures").get(0).files[0];
	if(file){
		var reader = new FileReader();
		reader.onload = function(){
			$("#previewImgs").attr("src", reader.result);
			$("#previewImgs").css("display", "block");
		}
		reader.readAsDataURL(file);
	}
}

function toggleCheckAll(source) {
  const checkboxes = document.querySelectorAll(".row-check");
  checkboxes.forEach((checkbox) => {
    checkbox.checked = source.checked;
  });
}

</script>