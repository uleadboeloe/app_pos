<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include "library/parameter.php";
include "library/connection.php";
include "library/fungsi.php";
include "library/qrcode/qrlib.php";

ini_set('max_execution_time', 36000); // 5 minutes

$Timestamp = str_replace(":","",$datedb);
$Timestamp = str_replace(" ","-",$Timestamp);
$Timestampx = str_replace("-","",$Timestamp);

$Userid = $_SESSION['SESS_user_id'];
$Username = $_SESSION['SESS_user_name'];

if(isset($_SESSION['SESS_user_id'])){
    $txtSourcex = (trim($_GET['source']));

    switch ($txtSourcex){
        case "store":
            $txtKodeStorex = (trim($_POST['txtKodeStore']));
            $txtRandomCodex = (trim($_POST['txtRandomCode']));
            $txtNamaStorex = (trim($_POST['txtNamaStore']));
            $txtAlamatStorex = (trim($_POST['txtAlamatStore']));
            $txtProvinsix = (trim($_POST['txtProvinsi']));
            $txtKotax = (trim($_POST['txtKota']));
            $txtKecamatanx = (trim($_POST['txtKecamatan']));
            $txtKodePosx = (trim($_POST['txtKodePos']));
            $txtNoKontakx = (trim($_POST['txtNoKontak']));
            $txtHeaderStrukx = nl2br((trim($_POST['txtHeaderStruk'])));
            $txtFooterStrukx = nl2br((trim($_POST['txtFooterStruk'])));
            $txtLongx = (trim($_POST['Long']));
            $txtLatx = (trim($_POST['Lat']));

            $strQuery="SELECT * FROM dbo_store WHERE kode_store = '" . $txtKodeStorex . "' and nama_store = '" . $txtNamaStorex . "'";
            $callstrQuery=mysqli_query($koneksidb, $strQuery);
            $Jumbar=mysqli_num_rows($callstrQuery);
            if($Jumbar == 0){
                $strInsert="INSERT INTO dbo_store(`random_code`,`kode_store`,`nama_store`,`alamat_store`,`provinsi`,`kota`,`kecamatan`,`kode_pos`,`no_kontak`,`coord_long`,`coord_lat`,`posting_date`,`posting_user`,`header_struk`,`footer_struk`) VALUES ('$txtRandomCodex','$txtKodeStorex','$txtNamaStorex','$txtAlamatStorex','$txtProvinsix','$txtKotax','$txtKecamatanx','$txtKodePosx','$txtNoKontakx','$txtLongx','$txtLatx','$datedb','$Userid','$txtHeaderStrukx','$txtFooterStrukx')";
                $executeSQL=mysqli_query($koneksidb, $strInsert); 

                if($executeSQL === false){
                    header("Location: setup-toko-msg!save-failed");
                }else{
                    header("Location: setup-toko-msg!save-success");
                }
            }else{
                while($recResult=mysqli_fetch_array($callStrQuery))
                {
                    $varNoFaktur = $recResult['kode_store'];
                    $varKodeCustomer = $recResult['nama_store'];     
                }
            }
        break;
        case "user":
            $txtKodeKasirx = (trim($_POST['txtKodeKasir']));
            $txtRandomCodex = (trim($_POST['txtRandomCode']));
            $txtNamaKasirx = (trim($_POST['txtNamaKasir']));
            $txtKodeStorex = (trim($_POST['txtKodeStore']));
            $txtNoKontakx = (trim($_POST['txtNoKontak']));
            $txtPasswordx = (trim($_POST['txtPassword']));
            $txtJabatanx = (trim($_POST['txtJabatan']));
            $HakAkses = getHakAkses($txtJabatanx);
            $Passwords = crypt($txtPasswordx, $Salt);
            $txtApproveCode = $txtKodeKasirx . "-" . $txtRandomCodex;
            $FileBarcode = "";
            $KodePrefix = $txtKodeStorex . "-";
            $txtApproveCode = str_replace(" ","-",$txtApproveCode);
            
            $strQuery="SELECT * FROM dbo_user WHERE kode_kasir = '" . $txtKodeStorex . "' and userid = '" . $txtNamaKasirx . "'";
            $callstrQuery=mysqli_query($koneksidb, $strQuery);
            $Jumbar=mysqli_num_rows($callstrQuery);
            if($Jumbar == 0){
                if($HakAkses > 1){
                    /*GENERATE QRCODE LOGO*/
                    $tempdir="qrcode_user/";
                    $file_name = $txtApproveCode.".png";
                    $record_value = $txtApproveCode;
                    $file_path = $tempdir.$file_name;
                    $forecolor = "0,0,0";
                    $backcolor = "255,255,255";
                    $logo = "assets/images/barcodelogo.png";
                    /* param (1)qrcontent,(2)filename,(3)errorcorrectionlevel,(4)pixelwidth,(5)margin,(6)saveandprint,(7)forecolor,(8)backcolor */
                    QRcode::png($record_value, $file_path, "H", 6, 1, 0, $forecolor, $backcolor, $logo);
                    $FileBarcode = $tempdir.$file_name;
                    /*GENERATE QRCODE LOGO*/

                    /*CREATEBARCODE*/
                    // Deteksi apakah protokolnya http atau https
                    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
                    // Buat URL ke barcode generator
                    $file_gambar = $protocol . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/barcode.php?text=" . urlencode($txtApproveCode) . "&print=true&size=100&orientation=horizontal&code_type=code128";
                    $folderPath = __DIR__ . '/qrcode_user/barcodes/';
                    if (!file_exists($folderPath)) {
                        mkdir($folderPath, 0777, true); // buat folder jika belum ada
                    }
                    $filename = $folderPath . $txtApproveCode . '.png';
                    // Ambil data gambar dari URL
                    $barcode_data = file_get_contents($file_gambar);
                    if ($barcode_data !== false) {
                        file_put_contents($filename, $barcode_data);
                    }
                    /*CREATEBARCODE*/
                }

                $strInsert="INSERT INTO dbo_user(
                `random_code`,`userid`,`userpass`,`kode_kasir`,`nama_user`,
                `nomor_kontak`,`job_title`,`hak_akses`,
                `posting_date`,`posting_user`,`kode_store`,`approval_code`,`qrcode_user`) VALUES (
                '$txtRandomCodex','$txtKodeKasirx','$Passwords','$txtKodeKasirx','$txtNamaKasirx',
                '$txtNoKontakx','$txtJabatanx','$HakAkses',
                '$datedb','$Userid','$txtKodeStorex','$txtApproveCode','$FileBarcode')";
                $executeSQL=mysqli_query($koneksidb, $strInsert); 

                $strxQuery="SELECT * FROM dbo_noseries WHERE kode_store = '" . $txtKodeStorex . "' and kode_kasir = '" . $txtKodeKasirx . "'";
                $callstrxQuery=mysqli_query($koneksidb, $strxQuery);
                $Jumbarx=mysqli_num_rows($callstrxQuery);
                if($Jumbarx == 0){
                    $strInsertx="INSERT INTO dbo_noseries(`kode_store`,`kode_kasir`,`kode_noseries`,`kode_prefix`,`nomor_akhir`,`modul_pakai`,`posting_date`,`posting_user`) VALUES ('$txtKodeStorex','$txtKodeKasirx','ORDER','$KodePrefix','1','SALES','$datedb','$Userid')";
                    $executeSQLx=mysqli_query($koneksidb, $strInsertx); 
                }

                if($executeSQL === false){
                    header("Location: akses-user-msg!save-failed");
                }else{
                    header("Location: akses-user-msg!save-success");
                }
            }else{
                while($recResult=mysqli_fetch_array($callStrQuery))
                {
                    $varUserID = $recResult['userid'];
                    $varUserPass = $recResult['userpass'];     
                }
            }              
        break;
        case "change-passworduser":
            $txtKodeKasirx = (trim($_POST['txtKodeKasir']));
            $txtRandomCodex = (trim($_POST['txtRandomCode']));
            $txtPasswordx = (trim($_POST['txtPassword']));
            $Passwords = crypt($txtPasswordx, $Salt);
            
            $strQuery="SELECT * FROM dbo_user WHERE kode_kasir = '" . $txtKodeKasirx . "'";
            $callstrQuery=mysqli_query($koneksidb, $strQuery);
            $Jumbar=mysqli_num_rows($callstrQuery);
            if($Jumbar == 1){
                $strInsert="UPDATE dbo_user set userpass = '$Passwords' where kode_kasir = '" . $txtKodeKasirx . "'";
                $executeSQL=mysqli_query($koneksidb, $strInsert); 

                if($executeSQL === false){
                    header("Location: profile-showmsg!save-failed");
                }else{
                    header("Location: profile-showmsg!save-success");
                }
            }            
        break;    
        case "daftarkartu":
            $txtJenisKartux = (trim($_POST['txtJenisKartu']));
            $txtNamaKartux = (trim($_POST['txtNamaKartu']));
            $txtNamaBankPenerbitx = (trim($_POST['txtNamaBankPenerbit']));
            
            $strQuery="SELECT * FROM dbo_kartu WHERE nama_kartu = '" . $txtNamaKartux . "'";
            $callstrQuery=mysqli_query($koneksidb, $strQuery);
            $Jumbar=mysqli_num_rows($callstrQuery);
            if($Jumbar == 0){
                $strInsertx="INSERT INTO dbo_kartu(`debet_kredit`,`nama_kartu`,`init_bank`,`posting_date`,`posting_user`) VALUES ('$txtJenisKartux','$txtNamaKartux','$txtNamaBankPenerbitx','$datedb','$Userid')";
                $executeSQLx=mysqli_query($koneksidb, $strInsertx); 

                if($executeSQL === false){
                    header("Location: daftar-kartu-showmsg!save-failed");
                }else{
                    header("Location: daftar-kartu-showmsg!save-success");
                }
            }            
        break;        
        case "promo":
            $txtRandomCodex = (trim($_POST['txtRandomCode']));
            $txtKodePromox = (trim($_POST['txtKodePromo']));
            $txtNamaPromox = (trim($_POST['txtNamaPromo']));
            $txtDeskripsiPromox = (trim($_POST['txtDeskripsiPromo']));
            $txtKodeStorex = (trim($_POST['txtKodeStore']));
            $txtPeriodePromox = (trim($_POST['txtPeriodePromo']));
            $txtPeriodePromox = explode(" to ", $txtPeriodePromox);
			$StartDate = $txtPeriodePromox[0];
			$EndDate = $txtPeriodePromox[1];
            $txtKriteriaPromox = (trim($_POST['txtKriteriaPromo']));            
            $PromoType = getPromoName($txtKriteriaPromox);
            $PromoParameter = getPromoParameter($txtKriteriaPromox);
            
            $txtPromoKriteriax = (trim($_POST['txtPromoKriteria']));
            $txtPromoValuex = (trim($_POST['txtPromoValue']));
            $txtVariabelPromox = (trim($_POST['txtVariabelPromo']));
            $txtFreeItemValuex = (trim($_POST['txtFreeItemValue']));
            if($txtFreeItemValuex == ""){
                $txtFreeItemValuex = "0";
            }else{
                if($txtFreeItemValuex != "FREETHIS"){
                    $txtFreeItemValuex = getSkuBarangByBarcode($txtFreeItemValuex);
                }
            }


            $txtQtyPromox = (trim($_POST['txtQtyPromo']));
            
            $UrlName = $txtRandomCodex . " " . $txtNamaPromox;
            $PromoUrl = create_url($UrlName);
            /***************************/
            $uploadPath = "fileupload/images/";
            if (!file_exists($uploadPath)){
                mkdir($uploadPath);
            }
            $status = $statusMsg = '';
            $status = 'error';
            /***************************/
            if(!empty($_FILES["picture"]["name"])) {
                $fileName1 = basename($_FILES["picture"]["name"]);
                $imageUploadPat1 = $uploadPath . $fileName1;
                $fileType1 = pathinfo($imageUploadPat1, PATHINFO_EXTENSION);
                $allowTypes1 = array('jpg','png','jpeg','gif');
                if(in_array($fileType1, $allowTypes1)){
                    $imageTemp1 = $_FILES["picture"]["tmp_name"];
                    $imageSize1 = $_FILES["picture"]["size"];
                    $compressedImage1 = compressImage($imageTemp1, $imageUploadPat1, 80);

                    if($compressedImage1){
                        $compressedImageSize1 = filesize($compressedImage1);
                        $status = 'success';
                        $statusMsg = "Image compressed successfully." . $compressedImageSize1;
                    }else{
                        $statusMsg = "Image compress failed!";
                    }

                    if(!empty($compressedImage1)){
                        $source1 = imagecreatefromjpeg($compressedImage1);
                        /*convert images jadi webp*/
                        $webpImage1 = imagecreatetruecolor(imagesx($source1), imagesy($source1));
                        $FilenameImages1 = "IMG0" . $Timestampx;
                        $compressedImages1 = $uploadPath.$FilenameImages1.".webp";

                        //Mengaktifkan transparansi untuk gambar PNG
                        //imagealphablending($webpImage, false);
                        //imagesavealpha($webpImage, true);

                        // Mengkonversi gambar PNG atau JPG menjadi WebP
                        imagewebp($source1, $compressedImages1);

                        //rotateimages
                        //$compressedImages = imagerotate($compressedImages, $degrees, 45);

                        // Menghapus gambar dari memori
                        imagedestroy($source1);
                        imagedestroy($webpImage1);

                        //unlink($FileSave);
                        unlink($compressedImage1);
                        /*convert images jadi webp*/
                    }
                }else{
                    $statusMsg = 'Sorry, only JPG, JPEG, PNG, & GIF files are allowed to upload.';
                }
            }else{
                $compressedImages1 = "";
                $statusMsg = 'Please select an image file to upload.';
            }
            /***************************/            
            if(!empty($_FILES["pictures"]["name"])) {
                $fileName2 = basename($_FILES["pictures"]["name"]);
                $imageUploadPat2 = $uploadPath . $fileName2;
                $fileType2 = pathinfo($imageUploadPat2, PATHINFO_EXTENSION);
                $allowTypes2 = array('jpg','png','jpeg','gif');
                if(in_array($fileType2, $allowTypes2)){
                    $imageTemp2 = $_FILES["pictures"]["tmp_name"];
                    $imageSize2 = $_FILES["pictures"]["size"];
                    $compressedImage2 = compressImage($imageTemp2, $imageUploadPat2, 80);

                    if($compressedImage2){
                        $compressedImageSize2 = filesize($compressedImage2);
                        $status = 'success';
                        $statusMsg = "Image compressed successfully." . $compressedImageSize2;
                    }else{
                        $statusMsg = "Image compress failed!";
                    }

                    if(!empty($compressedImage2)){
                        $source2 = imagecreatefromjpeg($compressedImage2);
                        /*convert images jadi webp*/
                        $webpImage2 = imagecreatetruecolor(imagesx($source2), imagesy($source2));
                        $FilenameImages2 = "IMG1" . $Timestampx;
                        $compressedImages2 = $uploadPath.$FilenameImages2.".webp";

                        //Mengaktifkan transparansi untuk gambar PNG
                        //imagealphablending($webpImage, false);
                        //imagesavealpha($webpImage, true);

                        // Mengkonversi gambar PNG atau JPG menjadi WebP
                        imagewebp($source2, $compressedImages2);

                        //rotateimages
                        //$compressedImages = imagerotate($compressedImages, $degrees, 45);

                        // Menghapus gambar dari memori
                        imagedestroy($source2);
                        imagedestroy($webpImage2);

                        //unlink($FileSave);
                        unlink($compressedImage2);
                        /*convert images jadi webp*/
                    }
                }else{
                    $statusMsg = 'Sorry, only JPG, JPEG, PNG, & GIF files are allowed to upload.';
                }
            }else{
                $compressedImages2 = "";
                $statusMsg = 'Please select an image file to upload.';
            }
            /***************************/          
            $PromoDay = "";
            $FlPromoDay = "0";
            if(!empty($_POST['checkbox'])) {
                foreach($_POST['checkbox'] as $selectedx) {
                    $PromoDay.=$selectedx . ",";
                }
                $FlPromoDay = "1";
            }
            /***************************/
            switch ($PromoParameter){
                case "A":
                    $BarcodeInduk = "-";
                    $FlFreeItem = "0";
                break;
                case "B":
                    $BarcodeInduk = "-";
                    $FlFreeItem = "0";
                break;
                case "C":
                    $BarcodeInduk = "-";
                    $FlFreeItem = "1";
                break;
                case "D":
                    $BarcodeInduk = "-";
                    $FlFreeItem = "1";
                break;
                case "F":
                    $BarcodeInduk = "-";
                    $FlFreeItem = "1";
                break;
            } 

            $strQuery="SELECT * FROM dbo_promo WHERE kode_promo = '" . $txtKodePromox . "' and promo_name = '" . $txtNamaPromox . "'";
            $callstrQuery=mysqli_query($koneksidb, $strQuery);
            $Jumbar=mysqli_num_rows($callstrQuery);
            if($Jumbar == 0){
                $strInsert="INSERT INTO dbo_promo(
                `random_code`,`kode_promo`,`promo_name`,`promo_desc`,`promo_start_date`,`promo_end_date`,`fl_promo_day`,`promo_day`,`promo_type`,
                `promo_images`,`url_promo`,`banner_header`,`fl_free_item`,`promo_parameter`,`kriteria_promo`,
                `kriteria_value`,`value_promo`,`free_item`,`qty_free_item`,`posting_date`,
                `posting_user`,`kode_store`,`fl_active`,`kode_barcode_induk`) VALUES (
                '$txtRandomCodex','$txtKodePromox','$txtNamaPromox','$txtDeskripsiPromox','$StartDate','$EndDate','$FlPromoDay','$PromoDay','$PromoType',
                '$compressedImages1','$PromoUrl','$compressedImages2','$FlFreeItem','$PromoParameter','$txtVariabelPromox',
                '$txtPromoKriteriax','$txtPromoValuex','$txtFreeItemValuex','$txtQtyPromox','$datedb','$Userid','$txtKodeStorex','0','$BarcodeInduk')";
                $executeSQL=mysqli_query($koneksidb, $strInsert); 
                //echo $strInsert;

                switch ($PromoParameter){
                    case "A":
                    break;
                    case "B":
                    break;
                    case "C":
                    break;
                    case "D":
                    break;
                    case "F":
                    break;
                } 

                if($executeSQL === false){
                    header("Location: setup-promosi-msg!save-failed");
                }else{
                    header("Location: detail-promosi-showmsg!save-success-detail-promo@$txtRandomCodex");
                }
                /**/
            }else{
                while($recResult=mysqli_fetch_array($callStrQuery))
                {
                    $varNoFaktur = $recResult['kode_promo'];
                    $varKodeCustomer = $recResult['promo_name'];     
                }
            }                   
        break;
        case "promo-detail":
            $txtRandomCodex = (trim($_POST['txtRandomCodes']));
            $txtKodePromox = getKodePromoByRandomCode($txtRandomCodex);
            $Status = 0;
            if(!empty($_POST['checkboxx'])) {
                $checked_count = count($_POST['checkboxx']);
                //echo $checked_count . "<br>";

                foreach($_POST['checkboxx'] as $selectedx) {
                    $txtBarcodex = (trim($_POST['txtBarcode'.$selectedx]));
                    $txtUomx = (trim($_POST['txtUom'.$selectedx]));
                    $txtNoidx = (trim($_POST['txtNoid'.$selectedx]));
                    $txtItemDetailx = (trim($_POST['txtSkuBarang'.$selectedx]));
                    $txtKdBarangx = getKodeBarangBySkuBarang($txtItemDetailx);

                    //echo $selectedx . "#" . $txtBarcodex . "#" . $txtUomx . "#" . $txtNoidx . "<br>";
                    
                    $strQuery="SELECT * FROM dbo_promo_detail WHERE random_code = '" . $txtRandomCodex . "' and barcode = '" . $txtBarcodex . "'";
                    //echo $strQuery . "<br>";
                    $callstrQuery=mysqli_query($koneksidb, $strQuery);
                    $Jumbar=mysqli_num_rows($callstrQuery);
                    if($Jumbar == 0){
                        $strInsert="INSERT INTO dbo_promo_detail(`random_code`,`barcode`,`kode_promo`,`sku_barang`,`kode_barang`,`uom`,`posting_date`,`posting_user`) VALUES ('$txtRandomCodex','$txtBarcodex','$txtKodePromox','$txtItemDetailx','$txtKdBarangx','$txtUomx','$datedb','$Userid')";
                        $executeSQL=mysqli_query($koneksidb, $strInsert); 
                        echo $strInsert . "<br>";
                        if($executeSQL == true){
                            $Status=$Status+1;
                        }
                    }
                }
            } 
            
            //exit();

            if($Status === $checked_count){
                header("Location: detail-promosi-showmsg!save-success@$txtRandomCodex");
            }else{
                header("Location: detail-promosi-showmsg!check-detail-promo@$txtRandomCodex");
            }
        break;
        case "promo-pricelevel":
            $txtRandomCodex = (trim($_POST['txtRandomCodes']));
            $txtKodePromox = (trim($_POST['txtKodePromo']));
            
            $txtBarcodeIndukx = (trim($_POST['txtBarcodeInduk']));
            $txtPromoKriteriax = (trim($_POST['txtPromoKriteria']));
            $txtPromoValuex = (trim($_POST['txtPromoValue']));
            $txtNormalPricex = (trim($_POST['txtNormalPrice']));

            $SkuBarangx = getSkuBarangByBarcode($txtBarcodeIndukx);
            $KodeBarangx = getKodeBarangByBarcode($txtBarcodeIndukx);
            $UomBarangx = getUomByBarcode($txtBarcodeIndukx);
            /*txtPromoValue - harga jual
            txtNormalPrice - harga normal satuan
            txtSkuPromoValue - barcode produk*/                       
            $strQueryLine="SELECT * FROM dbo_promo_detail WHERE barcode = '" . $txtBarcodeIndukx . "' and qty_jual = '" . $txtPromoKriteriax . "'";
            $callstrQueryLine=mysqli_query($koneksidb, $strQueryLine);
            $JumbarLine=mysqli_num_rows($callstrQueryLine);
            if($JumbarLine == 0){
                $strInsert="INSERT INTO dbo_promo_detail(`random_code`,`barcode`,`kode_promo`,`sku_barang`,`kode_barang`,`uom`,`posting_date`,`qty_jual`,`harga_jual`) VALUES 
                ('$txtRandomCodex','$txtBarcodeIndukx','$txtKodePromox','$SkuBarangx','$KodeBarangx','$UomBarangx','$datedb','$txtPromoKriteriax','$txtPromoValuex')";
                $executeSQL=mysqli_query($koneksidb, $strInsert); 
                echo $strInsert . "<br>";
            }else{
                echo "ITEM SUDAH TERDAFTAR";
            }

            if($executeSQL === false){
                header("Location: detail.product.php?rcode=$txtRandomCodex");
            }else{
                header("Location: detail.product.php?rcode=$txtRandomCodex");
            }   
            //INSERT LINE
        break;
        case "setup-detail":
            $btnSubmitx = (trim($_POST['btnSubmit']));
            $txtRandomCodex = (trim($_POST['txtRandomCodes']));
            
            switch ($btnSubmitx){
                case "Hapus Semua":
                    $strQuery="SELECT * FROM dbo_promo_detail WHERE random_code = '" . $txtRandomCodex . "'";
                    $callstrQuery=mysqli_query($koneksidb, $strQuery);
                    $Jumbar=mysqli_num_rows($callstrQuery);
                    if($Jumbar > 0){
                        $strDelete="DELETE FROM dbo_promo_detail WHERE random_code = '" . $txtRandomCodex . "'";
                        $executeSQL=mysqli_query($koneksidb, $strDelete); 
                    }

                    header("Location: detail-promosi-showmsg!delete-promo-detail-success@$txtRandomCodex");
                break;
                case "Non Aktifkan Promo":
                    $strQuery="SELECT * FROM dbo_promo WHERE random_code = '" . $txtRandomCodex . "'";
                    $callstrQuery=mysqli_query($koneksidb, $strQuery);
                    $Jumbar=mysqli_num_rows($callstrQuery);
                    if($Jumbar > 0){
                        $strUpdate="UPDATE dbo_promo set fl_active = 0 WHERE random_code = '" . $txtRandomCodex . "'";
                        $executeSQL=mysqli_query($koneksidb, $strUpdate); 
                    }

                    header("Location: detail-promosi-showmsg!promo-disable-success@$txtRandomCodex");
                break;
                case "Aktifkan Promo":
                    $strQuery="SELECT * FROM dbo_promo WHERE random_code = '" . $txtRandomCodex . "'";
                    $callstrQuery=mysqli_query($koneksidb, $strQuery);
                    $Jumbar=mysqli_num_rows($callstrQuery);
                    if($Jumbar > 0){
                        $strUpdate="UPDATE dbo_promo set fl_active = 1 WHERE random_code = '" . $txtRandomCodex . "'";
                        $executeSQL=mysqli_query($koneksidb, $strUpdate); 
                    }

                    header("Location: detail-promosi-showmsg!promo-enable-success@$txtRandomCodex");                    
                break;                                    
            }
            echo "<h1>" . $btnSubmitx . "</h1>";
        break;
        case "voucher":
            $txtRandomCodex = (trim($_POST['txtRandomCode']));
            $txtKodeStorex = (trim($_POST['txtKodeStore']));
            $txtJumlahVoucherx = (trim($_POST['txtJumlahVoucher']));
            $txtKodeVoucherx = (trim($_POST['txtKodeVoucher']));
            $txtNominalVoucherx = (trim($_POST['txtNominalVoucher']));

            for ($x=1; $x<=$txtJumlahVoucherx; $x++) {
                $format = '%1$04d';
                $NoVoucher = sprintf($format, $x);
                $KodeVoucher = $txtRandomCodex . "-" .$NoVoucher;
                $Md5KodeVoucher = md5($KodeVoucher);
                echo "<h1>" . $Md5KodeVoucher . "</h1>";

                /*GENERATE QRCODE LOGO*/
                $tempdir="qrcode_voucher/";
                $file_name = $Md5KodeVoucher.".png";
                $record_value = $Md5KodeVoucher;
                $file_path = $tempdir.$file_name;
                $forecolor = "0,0,0";
                $backcolor = "255,255,255";
                $logo = "assets/images/barcodelogo.png";
                /* param (1)qrcontent,(2)filename,(3)errorcorrectionlevel,(4)pixelwidth,(5)margin,(6)saveandprint,(7)forecolor,(8)backcolor */
                QRcode::png($record_value, $file_path, "H", 6, 1, 0, $forecolor, $backcolor, $logo);
                $FileBarcode = $tempdir.$file_name;
                /*GENERATE QRCODE LOGO*/

                $strQuery="SELECT * FROM dbo_voucher WHERE kode_voucher = '" . $KodeVoucher . "'";
                $callstrQuery=mysqli_query($koneksidb, $strQuery);
                $Jumbar=mysqli_num_rows($callstrQuery);
                if($Jumbar == 0){
                    $strInsert="INSERT INTO dbo_voucher(`random_code`,`keterangan`,`kode_voucher`,`nomor_voucher`,`kode_store`,`posting_date`,`posting_user`,`nominal_voucher`,`file_barcode`) VALUES ('$txtRandomCodex','$txtKodeVoucherx','$Md5KodeVoucher','$KodeVoucher','$txtKodeStorex','$datedb','$Userid','$txtNominalVoucherx','$FileBarcode')";
                    $executeSQL=mysqli_query($koneksidb, $strInsert); 
                }
            }
            
            if($executeSQL === false){
                header("Location: setup-voucher-showmsg!save-failed");
            }else{
                header("Location: setup-voucher-showmsg!save-success");
            }   

        break;   
        case "updateproduk":
            $txtRandomCodex = (trim($_POST['txtRandomCode']));
            $txtKeterangan1x = nl2br((trim($_POST['txtKeterangan1'])));
            $txtKeterangan2x = nl2br((trim($_POST['txtKeterangan2'])));
            $btnSubmitx = (trim($_POST['btnSubmit']));

            switch ($btnSubmitx){
                case "Update Produk":
                    $strQueryLine="SELECT * FROM dbo_barang WHERE random_code = '" . $txtRandomCodex . "'";
                    $callstrQueryLine=mysqli_query($koneksidb, $strQueryLine);
                    $JumbarLine=mysqli_num_rows($callstrQueryLine);
                    if($JumbarLine > 0){                                        
                        /***************************/
                        $uploadPath = "fileupload/product/";
                        if (!file_exists($uploadPath)){
                            mkdir($uploadPath);
                        }
                        $status = $statusMsg = '';
                        $status = 'error';
                        /***************************/
                        if(!empty($_FILES["picture"]["name"])) {
                            $fileName1 = basename($_FILES["picture"]["name"]);
                            $imageUploadPat1 = $uploadPath . $fileName1;
                            $fileType1 = pathinfo($imageUploadPat1, PATHINFO_EXTENSION);
                            $allowTypes1 = array('jpg','png','jpeg','gif');
                            if(in_array($fileType1, $allowTypes1)){
                                $imageTemp1 = $_FILES["picture"]["tmp_name"];
                                $imageSize1 = $_FILES["picture"]["size"];
                                $compressedImage1 = compressImage($imageTemp1, $imageUploadPat1, 80);

                                if($compressedImage1){
                                    $compressedImageSize1 = filesize($compressedImage1);
                                    $status = 'success';
                                    $statusMsg = "Image compressed successfully." . $compressedImageSize1;
                                }else{
                                    $statusMsg = "Image compress failed!";
                                }

                                if(!empty($compressedImage1)){
                                    $source1 = imagecreatefromjpeg($compressedImage1);
                                    /*convert images jadi webp*/
                                    $webpImage1 = imagecreatetruecolor(imagesx($source1), imagesy($source1));
                                    $FilenameImages1 = "IMG0" . $Timestampx;
                                    $compressedImages1 = $uploadPath.$FilenameImages1.".webp";

                                    //Mengaktifkan transparansi untuk gambar PNG
                                    //imagealphablending($webpImage, false);
                                    //imagesavealpha($webpImage, true);

                                    // Mengkonversi gambar PNG atau JPG menjadi WebP
                                    imagewebp($source1, $compressedImages1);

                                    //rotateimages
                                    //$compressedImages = imagerotate($compressedImages, $degrees, 45);

                                    // Menghapus gambar dari memori
                                    imagedestroy($source1);
                                    imagedestroy($webpImage1);

                                    //unlink($FileSave);
                                    unlink($compressedImage1);
                                    /*convert images jadi webp*/
                                }
                            }else{
                                $statusMsg = 'Sorry, only JPG, JPEG, PNG, & GIF files are allowed to upload.';
                            }
                        }else{
                            $compressedImages1 = "";
                            $statusMsg = 'Please select an image file to upload.';
                        }
                        /***************************/ 

                        $strInsert="UPDATE dbo_barang set keterangan_1 = '$txtKeterangan1x', keterangan_2 = '$txtKeterangan2x'";
                        if($compressedImages1 != ""){
                            $strInsert=$strInsert . ",images_produk = '$compressedImages1'";
                        }
                        $strInsert=$strInsert . " WHERE random_code = '" . $txtRandomCodex . "'";
                        $executeSQL=mysqli_query($koneksidb, $strInsert); 
                        //echo $strInsert . "<br>";
                        if($executeSQL === false){
                            header("Location: detail-product-showmsg!save-failed@$txtRandomCodex");
                        }else{
                            header("Location: detail-product-showmsg!save-success@$txtRandomCodex");
                        }   
                    }
                    //INSERT LINE
                break;
                case "Create Price Tag":
                    echo "Create Price Tag";
                break;
                case "Create Barcode":
                    echo "Create Barcode";
                break;                        
            }
        break;  
        case "edc":
            $txtRandomCodex = (trim($_POST['txtRandomCode']));
            $txtKodeStorex = (trim($_POST['txtKodeStore']));
            $txtNamaMesinx = (trim($_POST['txtNamaMesin']));
            $txtNamaBankPenerbitx = (trim($_POST['txtNamaBankPenerbit']));       

            $strQuery="SELECT * FROM dbo_mesin_edc WHERE kode_store = '" . $txtKodeStorex . "' and nama_mesin = '" . $txtNamaMesinx . "'";
            $callstrQuery=mysqli_query($koneksidb, $strQuery);
            $Jumbar=mysqli_num_rows($callstrQuery);
            if($Jumbar == 0){
                $strInsert="INSERT INTO dbo_mesin_edc(`random_code`,`kode_store`,`nama_mesin`,`bank_penerbit`,`posting_date`,`posting_user`) VALUES ('$txtRandomCodex','$txtKodeStorex','$txtNamaMesinx','$txtNamaBankPenerbitx','$datedb','$Userid')";
                $executeSQL=mysqli_query($koneksidb, $strInsert); 
            }

            if($executeSQL === false){
                header("Location: setup-edc-showmsg!save-failed");
            }else{
                header("Location: setup-edc-showmsg!save-success");
            }  
        break;  
        case "member":
            $txtRandomCodex = (trim($_POST['txtRandomCode']));
            $txtKodeStorex = (trim($_POST['txtKodeStore']));
            $txtNamaMemberx = (trim($_POST['txtNamaMember']));
            $txtNoWhatsappx = (trim($_POST['txtNoWhatsapp']));  
            $txtEmailMemberx = (trim($_POST['txtEmailMember']));   
            $KodeMemberx = $txtKodeStorex . "-" . $txtRandomCodex;

            $strQuery="SELECT * FROM dbo_member WHERE nomor_kontak = '" . $txtNoWhatsappx . "'";
            $callstrQuery=mysqli_query($koneksidb, $strQuery);
            $Jumbar=mysqli_num_rows($callstrQuery);
            if($Jumbar == 0){
                $StatusNomor = "";
                $strxQuery="SELECT * FROM dbo_member WHERE email_member = '" . $txtEmailMemberx . "'";
                $callstrxQuery=mysqli_query($koneksidb, $strxQuery);
                $Jumbarx=mysqli_num_rows($callstrxQuery);
                if($Jumbarx == 0){
                    $StatusEmail = "";
                }else{
                    $StatusEmail = "EXIST";
                }
            }else{
                $StatusNomor = "EXIST";
            }

            if($StatusNomor == "" && $StatusEmail == ""){
                /*GENERATE QRCODE LOGO*/
                $tempdir="qrcode_member/";
                $file_name = $KodeMemberx.".png";
                //$record_value = $Timestampx . "#" . $labelKegiatanx . "#" . $txtTglAwalx . "#^" . $txtTglAkhirx;
                $record_value = $KodeMemberx;
                $file_path = $tempdir.$file_name;
                $forecolor = "0,0,0";
                $backcolor = "255,255,255";
                $logo = "assets/images/barcodelogo.png";
                /* param (1)qrcontent,(2)filename,(3)errorcorrectionlevel,(4)pixelwidth,(5)margin,(6)saveandprint,(7)forecolor,(8)backcolor */
                QRcode::png($record_value, $file_path, "H", 6, 1, 0, $forecolor, $backcolor, $logo);
                $FileBarcode = $tempdir.$file_name;

                //echo $fileqrcode . "<br>";
                $strInsert="INSERT INTO dbo_member(`kode_store`,`random_code`,`kode_member`,`nama_member`,`nomor_kontak`,`email_member`,`qrcode_member`,`posting_user`,`posting_date`) VALUES ('$txtKodeStorex','$txtRandomCodex','$KodeMemberx','$txtNamaMemberx','$txtNoWhatsappx','$txtEmailMemberx','$FileBarcode','$Userid','$datedb')";
                $executeSQL=mysqli_query($koneksidb, $strInsert); 
                $CreateMember = true;
            }else{
                $CreateMember = false;
            }

            if($CreateMember === false){
                header("Location: member-toko-showmsg!save-failed");
            }else{
                header("Location: member-toko-showmsg!save-success");
            }  
        break;  
        case "konfirm-register":
            $txtNoRegisterx = (trim($_POST['txtNoRegister']));
            $txtTerimaSetoranx = (trim($_POST['txtTerimaSetoran']));
            $txtTerimaSetoranx = replacenumbers($txtTerimaSetoranx);
            
            $txtKomentarAdminx = nl2br(trim($_POST['txtKomentarAdmin']));

            $strQuery="SELECT * FROM dbo_register WHERE kode_register = '" . $txtNoRegisterx . "'";
            $callstrQueryLine=mysqli_query($koneksidb, $strQuery);
            $JumbarLine=mysqli_num_rows($callstrQueryLine);
            if($JumbarLine > 0){   

                $strInsert="UPDATE dbo_register set respon_skasir = '$txtKomentarAdminx',setoran_diterima = '$txtTerimaSetoranx', respon_user = '" . $_SESSION['SESS_kode_kasir'] . "', respon_date = '$datedb', status_respon = '1' ";
                $strInsert=$strInsert . " WHERE kode_register = '" . $txtNoRegisterx . "'";
                $executeSQL=mysqli_query($koneksidb, $strInsert); 
                //echo $strInsert;
                
            }

            if($CreateMember === false){
                header("Location: register-showmsg!save-failed@$txtNoRegisterx");
            }else{
                header("Location: register-showmsg!save-success@$txtNoRegisterx");
            }  
        break;   
        case "proses-laporan":
            $txtRandomCodex = (trim($_POST['txtRandomCode']));
            $txtKodeKasirx = (trim($_POST['txtKodeKasir']));
            $txtPeriodeLaporanx = (trim($_POST['txtPeriodeLaporan']));
            $txtPeriodeLaporanx = explode(" to ", $txtPeriodeLaporanx);
			$StartDate = $txtPeriodeLaporanx[0];
			$EndDate = $txtPeriodeLaporanx[1];

            $strQuery="SELECT * FROM dbo_user WHERE kode_kasir = '" . $txtKodeKasirx . "'";
            $callstrQuery=mysqli_query($koneksidb, $strQuery);
            $Jumbar=mysqli_num_rows($callstrQuery);
            if($Jumbar == 1){
                $strInsert="UPDATE dbo_user set `start_date` = '$StartDate', `end_date` = '$EndDate' where kode_kasir = '" . $txtKodeKasirx . "'";
                $executeSQL=mysqli_query($koneksidb, $strInsert); 

                if($executeSQL === false){
                    header("Location: laporan-showmsg!save-failed");
                }else{
                    header("Location: laporan-showmsg!save-success");
                }
            }   
        break;          
        case "HIDDEN":
            case "E":                             
                $txtNormalPricex = (trim($_POST['txtNormalPrice']));
                $txtSkuPromoValuex = (trim($_POST['txtSkuPromoValue']));
                $SkuBarangx = getSkuBarangByBarcode($txtSkuPromoValuex);
                $KodeBarangx = getKodeBarangByBarcode($txtSkuPromoValuex);
                $UomBarangx = getUomByBarcode($txtSkuPromoValuex);
                $BarcodeInduk = $txtSkuPromoValuex;
                $FlFreeItem = "0";

                /*txtPromoValue - harga jual
                txtNormalPrice - harga normal satuan
                txtSkuPromoValue - barcode produk*/                       
                $strQueryLine="SELECT * FROM dbo_promo_detail WHERE random_code = '" . $txtRandomCodex . "' and barcode = '" . $txtSkuPromoValuex . "' and qty_jual = '" . $txtPromoKriteriax . "'";
                $callstrQueryLine=mysqli_query($koneksidb, $strQueryLine);
                $JumbarLine=mysqli_num_rows($callstrQueryLine);
                if($JumbarLine == 0){
                    $strInsert="INSERT INTO dbo_promo_detail(`random_code`,`barcode`,`kode_promo`,`sku_barang`,`kode_barang`,`uom`,`posting_date`,`posting_user`,`qty_jual`,`harga_jual`) VALUES 
                    ('$txtRandomCodex','$txtSkuPromoValuex','$txtKodePromox','$SkuBarangx','$KodeBarangx','$UomBarangx','$datedb','1','$txtNormalPricex')";
                    $executeSQL=mysqli_query($koneksidb, $strInsert); 
                    echo $strInsert . "<br>";

                    $strInsert="INSERT INTO dbo_promo_detail(`random_code`,`barcode`,`kode_promo`,`sku_barang`,`kode_barang`,`uom`,`posting_date`,`posting_user`,`qty_jual`,`harga_jual`) VALUES 
                    ('$txtRandomCodex','$txtSkuPromoValuex','$txtKodePromox','$SkuBarangx','$KodeBarangx','$UomBarangx','$datedb','$Userid','$txtPromoKriteriax','$txtPromoValuex')";
                    $executeSQL=mysqli_query($koneksidb, $strInsert); 
                    echo $strInsert . "<br>";
                }else{
                    echo "ITEM SUDAH TERDAFTAR";
                }
                //INSERT LINE
            break;
        break;      
    }
}else{
    echo "Belum Login.";
}