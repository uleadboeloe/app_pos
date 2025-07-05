<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include_once "library/connection.php";
include_once "library/parameter.php";
include_once "library/fungsi.php";
include_once "../lib_dbo/user_functions.php";
$hash16 = CreateUniqueHash16();
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
            <div class="flex items-center justify-between py-2 px-4">
                <h2 class="font-bold text-xl uppercase tracking-wide text-slate-700 dark:text-navy-100">Setup Toko</h2>
            </div>

            <form name="formProses" name="frmMasterProduk" id="frmMasterProduk" method="post" action="proses-toko">
                <div class="grid grid-cols-2 my-2 gap-4 sm:gap-5 lg:gap-6">
                    <div class="col-span-12 sm:col-span-12">
                        <div class="card p-4 sm:p-5">
                            <div class="space-y-4">
                                <input type="hidden" id="txtRandomCode" name="txtRandomCode" value="<?php echo $hash16;   ?>" readonly>
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-5">
                                    <label class="block">
                                        <span class="text-purple-500 font-bold">Kode Store <div class="badge rounded-full bg-primary/10 text-primary dark:bg-accent-light/15 dark:text-accent-light">Maksimal 5 Karakter</div></span>
                                        <span class="relative mt-1.5 flex">						
                                            <input placeholder="Masukan Kode Kasir" type="text" id="txtKodeStore" name="txtKodeStore" maxlength="5" required
                                            class="form-input peer h-12 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"/>
                                            <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                                                <i class="fa-regular fa-building text-base"></i>
                                            </span>
                                        </span>
                                    </label>
                                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-1">
                                        <label class="block">
                                            <span class="text-purple-500 font-bold">Nama Store <div class="badge rounded-full bg-primary/10 text-primary dark:bg-accent-light/15 dark:text-accent-light">Wajib</div></span>
                                            <span class="relative mt-1.5 flex">						
                                                <input placeholder="Masukan Nama Store" type="text" id="txtNamaStore" name="txtNamaStore" required
                                                class="form-input peer h-12 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"/>
                                                <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                                                    <i class="fa-regular fa-building text-base"></i>
                                                </span>
                                            </span>
                                        </label>    
                                    </div>  
                                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-1 sm:col-span-3">
                                        <label class="block">
                                            <span class="text-purple-500 font-bold">Alamat Store <div class="badge rounded-full bg-primary/10 text-primary dark:bg-accent-light/15 dark:text-accent-light">Wajib</div></span>
                                            <span class="relative mt-1.5 flex">						
                                                <input placeholder="Masukan Nomor Kontak" type="text" id="txtAlamatStore" name="txtAlamatStore" required
                                                class="form-input peer h-12 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"/>
                                                <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                                                    <i class="fa-regular fa-building text-base"></i>
                                                </span>
                                                <input type="hidden" id="Lat" name="Lat" readonly>
                                                <input type="hidden" id="Long" name="Long" readonly>
                                            </span>
                                        </label>    
                                    </div>                                                                                              
                                </div>
                                
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-5">
                                    <label class="block">
                                        <span class="text-purple-500 font-bold">Provinsi <div class="badge rounded-full bg-primary/10 text-primary dark:bg-accent-light/15 dark:text-accent-light">Wajib</div></span>
                                        <span class="relative mt-1.5 flex">
                                            <select id="txtProvinsi" name="txtProvinsi" required
                                            class="form-select mt-1 h-12 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs+ hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
                                                <option value="">Pilih Provinsi</option>
                                                <?php
                                                $strSQL="SELECT no_id,nama_provinsi FROM `dbo_provinsi`";
                                                $CallstrSQL=mysqli_query($koneksidb, $strSQL);
                                                while($rec=mysqli_fetch_array($CallstrSQL)){
                                                ?>
                                                <option value="<?php    echo $rec['no_id']; ?>"><?php    echo $rec['nama_provinsi']; ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </span>
                                    </label>  
                                    <div id="DivKota">
                                        <label class="block">
                                            <span class="text-purple-500 font-bold">Kota <div class="badge rounded-full bg-primary/10 text-primary dark:bg-accent-light/15 dark:text-accent-light">Wajib</div></span>
                                            <span class="relative mt-1.5 flex">
                                                <select id="txtKota" name="txtKota" required
                                                class="form-select mt-1 h-12 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs+ hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
                                                    <option value="">Pilih Kota</option>
                                                </select>
                                            </span>
                                        </label>
                                    </div>
                                    <div id="DivKecamatan">
                                        <label class="block">
                                            <span class="text-purple-500 font-bold">Kecamatan <div class="badge rounded-full bg-primary/10 text-primary dark:bg-accent-light/15 dark:text-accent-light">Wajib</div></span>
                                            <span class="relative mt-1.5 flex">
                                                <select id="txtKecamatan" name="txtKecamatan" required
                                                class="form-select mt-1 h-12 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs+ hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
                                                    <option value="">Pilih Kecamatan</option>
                                                </select>
                                            </span>
                                        </label>                                             
                                    </div>     
                                    <label class="block">
                                        <span class="text-purple-500 font-bold">Kode Pos <div class="badge rounded-full bg-warning/10 text-warning dark:bg-accent-light/15 dark:text-accent-light">Opsional</div></span>
                                        <span class="relative mt-1.5 flex">						
                                            <input placeholder="Masukan Kode Pos" type="text" id="txtKodePos" name="txtKodePos"
                                            class="form-input peer h-12 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"/>
                                            <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                                                <i class="fa-regular fa-building text-base"></i>
                                            </span>
                                        </span> 
                                    </label> 
                                    <label class="block">
                                        <span class="text-purple-500 font-bold">Kontak Store <div class="badge rounded-full bg-primary/10 text-primary dark:bg-accent-light/15 dark:text-accent-light">Wajib</div></span>
                                        <span class="relative mt-1.5 flex">						
                                            <input placeholder="Masukan Kontak Store" type="text" id="txtNoKontak" name="txtNoKontak" required
                                            class="form-input peer h-12 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"/>
                                            <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                                                <i class="fa-regular fa-building text-base"></i>
                                            </span>
                                        </span> 
                                    </label>
                                </div>

                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    <label class="block">
                                        <span class="text-purple-500 font-bold">Header Struk <div class="badge rounded-full bg-warning/10 text-warning dark:bg-accent-light/15 dark:text-accent-light">Opsional</div></span>
                                        <textarea rows="4" placeholder="Header Struk" id="txtHeaderStruk" name="txtHeaderStruk"
                                        class="form-textarea mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent p-2.5 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"></textarea>
                                    </label>	
                                    <label class="block">
                                        <span class="text-purple-500 font-bold">Footer Struk <div class="badge rounded-full bg-warning/10 text-warning dark:bg-accent-light/15 dark:text-accent-light">Opsional</div></span>
                                        <textarea rows="4" placeholder="Footer Struk" id="txtFooterStruk" name="txtFooterStruk"
                                        class="form-textarea mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent p-2.5 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"></textarea>
                                    </label>	
                                </div>

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
                <h2 class="font-bold text-xl uppercase tracking-wide text-slate-700 dark:text-navy-100">List Toko</h2>
            </div>
            <div class="card p-5 mt-3">
                <table id="table1" class="is-hoverable w-full" width="100%">     
                    <thead>
                    <tr>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Kode Store</th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Nama Store</th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Alamat Store</th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Nomor Kontak</th>
                        <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    /*==========================*/
                    $StrViewQuery="SELECT * from dbo_store where fl_active = 1";   
                    $callStrViewQuery=mysqli_query($koneksidb, $StrViewQuery);
                    while($recView=mysqli_fetch_array($callStrViewQuery))
                    {
                        $RandomCode = $recView['random_code'];
                        $KodeStore = $recView['kode_store'];
                        $NamaStore = $recView['nama_store'];
                        $AlamatStore = $recView['alamat_store'];
                        $Provinsi = $recView['provinsi'];
                        $Kota = $recView['kota'];
                        $Kecamatan = $recView['kecamatan'];
                        $KodePos = $recView['kode_pos'];
                        $NoKontak = $recView['no_kontak'];
                        $Status = $recView['fl_active'];
                        ?>
                        <tr class="border-y border-transparent border-b-slate-200 dark:border-b-navy-500">
                            <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $KodeStore; ?></td>     
                            <td class="whitespace-nowrap px-4 py-3 sm:px-5"><a href="detail-toko@<?php   echo $RandomCode; ?>" class="font-bold text-primary hover:text-orange-500"><?php   echo $NamaStore; ?></a></td>     
                            <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $AlamatStore; ?></td>         
                            <td class="whitespace-nowrap px-4 py-3 sm:px-5"><?php   echo $NoKontak; ?></td>     
                            <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                                <?php
                                if($Status == 1){
                                    ?>
                                    <div class="btn h-8 rounded bg-warning px-3 text-xs font-medium text-white hover:bg-warning-focus focus:bg-warning-focus active:bg-warning-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">Nonaktif</div>
                                    <?php
                                } else {
                                    ?>
                                    <div class="btn h-8 rounded bg-primary px-3 text-xs font-medium text-white hover:bg-primary focus:bg-primary active:bg-primary/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">Aktifkan</div>
                                    <?php
                                } 
                                ?>
                                <div class="btn h-8 rounded bg-error px-3 text-xs font-medium text-white hover:bg-error-focus focus:bg-error-focus active:bg-error-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">Hapus</div>
                            </td>
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
    $("#txtProvinsi").change(function (e) {
        var ProvID = $(this).val();
        $.ajax({
        type: "POST",
        url: "list.kota.php",
        data: "noid=" + ProvID,
        success: function (data) {
            $("#DivKota").html(data);
        },
        });
    });

    $("#DivKota").change(function () {
        var DeptId = $("#txtKota").val();
        $.ajax({
            type: "POST",
            url: "list.kecamatan.php",
            data: "noid=" + DeptId,
            success: function (data) {
            $("#DivKecamatan").html(data);
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

function initPlace() {
    var input = document.getElementById('txtAlamatStore');
    var autocomplete = new google.maps.places.Autocomplete(input);

    autocomplete.addListener('place_changed', function() {
        var place = autocomplete.getPlace();
		$('#Lat').val(place.geometry.location.lat());
		$('#Long').val(place.geometry.location.lng());
    });
}
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDhZga4rJVijJoJpSSuWvLvo-Zdgd4sLH4&libraries=places&callback=initPlace" async defer></script>