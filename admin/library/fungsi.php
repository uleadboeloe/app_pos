<?php
include_once "connection.php";

function create_url($string, $ext='.html'){
	$replace = '-';
	$string = strtolower($string);
	$string = preg_replace("/[\/\.]/", " ", $string);
	$string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
	$string = preg_replace("/[\s-]+/", " ", $string);
	$string = preg_replace("/[\s_]/", $replace, $string);
	$string = substr($string, 0, 100);
	return ($ext) ? $string.$ext : $string;
}

function sanitizeString($str) {
    $sanitizedStr = strip_tags($str);
    $sanitizedStr = str_replace('"', '""', $sanitizedStr);
    $sanitizedStr = filter_var($sanitizedStr, FILTER_SANITIZE_STRING);
    $sanitizedStr = html_entity_decode($sanitizedStr);
    return $sanitizedStr;
}

function normalize_line_endings($string){ 
	return preg_replace("/(?<=[^\r]|^)\n/", "\r\n", $string); 
}

function cleanWeirdCharacters($string) {
    // 1. Ubah encoding agar karakter rusak bisa dideteksi dan dibuang
    $string = mb_convert_encoding($string, 'UTF-8', 'UTF-8');

    // 2. Ganti karakter umum yang salah encoding (Windows-1252)
    $replace_map = [
        "\xC2\xA0" => ' ',  // non-breaking space
        "\xE2\x80\x93" => '-', // en dash
        "\xE2\x80\x94" => '-', // em dash
        "\xE2\x80\x98" => "'", // left single quote
        "\xE2\x80\x99" => "'", // right single quote
        "\xE2\x80\x9C" => '"', // left double quote
        "\xE2\x80\x9D" => '"', // right double quote
        "\xE2\x80\xa6" => '...', // ellipsis
        "\xE2\x82\xAC" => 'EUR', // euro
        "\xEF\xBF\xBD" => '',    // replacement char (�)
    ];
    $string = strtr($string, $replace_map);

    // 3. Hapus karakter non-ASCII yang aneh
    $string = preg_replace('/[^\x20-\x7E]/', '', $string);

    // 4. Bersihkan spasi ganda
    $string = preg_replace('/\s+/', ' ', $string);

    return trim($string);
}

function replacenumber($IDX) {
	$str = str_replace(".",",",$IDX);
	return $str;
}

function replacenumbers($IDX) {
	$str = (string)$IDX;
	$str = str_replace(",", "#", $str);
	$str = str_replace(".", "", $str);
	$str = str_replace("#", ".", $str);
	return $str;
}

function replace_phoneno($IDX) {
	$str = str_replace("-","",$IDX);
	$str = str_replace("+","",$str);
	return $str;
}

function compressImage($source, $destination, $quality) {
	$imgInfo = getimagesize($source);
	$mime = $imgInfo['mime'];

	switch($mime){
		case 'image/jpeg':
			$image = imagecreatefromjpeg($source);
			break;
		case 'image/png':
			$image = imagecreatefrompng($source);
			break;
		case 'image/gif':
			$image = imagecreatefromgif($source);
			break;
		default:
			$image = imagecreatefromjpeg($source);
	}
	imagejpeg($image, $destination, $quality);
	return $destination;
}

function getStoreName($IDX) {
	$strSQL="SELECT nama_store FROM `dbo_store` where kode_store = '" . $IDX . "'";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['nama_store'];
		}
	}else{
		$varResult = "";
	}
	return $varResult;
}
function getHeaderStruk($IDX) {
	$strSQL="SELECT header_struk FROM `dbo_store` where kode_store = '" . $IDX . "'";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['header_struk'];
		}
	}
	return $varResult;
}
function getFooterStruk($IDX) {
	$strSQL="SELECT footer_struk FROM `dbo_store` where kode_store = '" . $IDX . "'";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['footer_struk'];
		}
	}
	return $varResult;
}
function getHakAkses($IDX) {
	$strSQL="SELECT noid FROM `dbo_jobtitle` where job_title = '" . $IDX . "'";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['noid'];
		}
	}
	return $varResult;
}
function getPromoName($IDX) {
	$strSQL="SELECT label_name FROM `dbo_global` where noid = '" . $IDX . "'";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['label_name'];
		}
	}
	return $varResult;
}
function getPromoParameter($IDX) {
	$strSQL="SELECT parameter FROM `dbo_global` where noid = '" . $IDX . "'";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['parameter'];
		}
	}
	return $varResult;
}

function getKodePromoByRandomCode($IDX) {
	$strSQL="SELECT kode_promo FROM `dbo_promo` where random_code = '" . $IDX . "'";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['kode_promo'];
		}
	}
	return $varResult;
}

function getParameterPromoByKodePromo($IDX) {
	$strSQL="SELECT promo_parameter FROM `dbo_promo` where kode_promo = '" . $IDX . "'";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['promo_parameter'];
		}
	}
	return $varResult;
}
function getPromoExist($IDX,$UOMX) {
	$strSQL="SELECT noid FROM `dbo_promo_detail` where sku_barang = '" . $IDX . "' and uom = '" . $UOMX . "'";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['noid'];
		}
	}else{
		$varResult = "";
	}
	return $varResult;
}
/*dbo_BARANG*/
function getKodeBarangBySkuBarang($IDX) {
	$strSQL="SELECT kode_barang FROM `dbo_barang` where sku_barang = '" . $IDX . "'";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['kode_barang'];
		}
	}
	return $varResult;
}

function getNamaBarangBySkuBarang($IDX) {
	$strSQL="SELECT nama_barang FROM `dbo_barang` where sku_barang = '" . $IDX . "'";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['nama_barang'];
		}
	}else{
		$varResult = "";
	}
	return $varResult;
}

function getRandomCodeBySkuBarang($IDX) {
	$strSQL="SELECT random_code FROM `dbo_barang` where sku_barang = '" . $IDX . "'";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['random_code'];
		}
	}else{
		$varResult = "";
	}
	return $varResult;
}
function getBankPenerbitMesinEdc($IDX) {
	$strSQL="SELECT bank_penerbit FROM `dbo_mesin_edc` where noid = '" . $IDX . "'";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['bank_penerbit'];
		}
	}else{
		$varResult = "";
	}
	return $varResult;
}


function getBarcodeBarangBySkuBarang($IDX) {
	$strSQL="SELECT barcode FROM `dbo_barang` where sku_barang = '" . $IDX . "'";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['barcode'];
		}
	}else{
		$varResult = "";
	}
	return $varResult;
}

function getTextAfterP($str) {
	$start = strpos($str, '(P)');
	if ($start === false) {
		return $str;
	}
	return substr($str, $start + 3);
}

function getNamaBarangByKodeBarang($IDX) {
	//$StrSearch = str_replace("(P)","",$IDX);
	$StrSearch = getTextAfterP($IDX);
	$strSQL="SELECT nama_barang FROM `dbo_barang` where kode_barang = '" . $StrSearch . "'";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['nama_barang'];
		}
	}else{
		$varResult = "";
	}
	return $varResult;
}

function getUomByKodeBarang($IDX) {
	//$StrSearch = str_replace("(P)","",$IDX);
	$StrSearch = getTextAfterP($IDX);
	$strSQL="SELECT uom FROM `dbo_barang` where kode_barang = '" . $StrSearch . "'";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['uom'];
		}
	}else{
		$varResult = "";
	}
	return $varResult;
}

function getNamaBarangByBarcode($IDX) {
	$strSQL="SELECT nama_barang FROM `dbo_barang` where barcode = '" . $IDX . "'";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['nama_barang'];
		}
	}else{
		$varResult = "";
	}
	return $varResult;
}

function getSkuBarangByBarcode($IDX) {
	$strSQL="SELECT sku_barang FROM `dbo_barang` where barcode = '" . $IDX . "'";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['sku_barang'];
		}
	}
	return $varResult;
}
function getKodeBarangByBarcode($IDX) {
	$strSQL="SELECT kode_barang FROM `dbo_barang` where barcode = '" . $IDX . "'";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['kode_barang'];
		}
	}
	return $varResult;
}
function getUomByBarcode($IDX) {
	$strSQL="SELECT uom FROM `dbo_barang` where barcode = '" . $IDX . "'";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['uom'];
		}
	}
	return $varResult;
}
/*dbo_BARANG*/

/*dbo_PRICE*/
function getPriceByKodeBarcode($IDX) {
	$strSQL="SELECT harga_jual FROM `dbo_price` where barcode = '" . $IDX . "'";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['harga_jual'];
		}
	}else{
		$varResult = "0";
	}
	return $varResult;
}
/*dbo_PRICE*/

function getDeptNameByCode($IDX) {
	$strSQL="SELECT nama_departemen FROM `dbo_departemen` where kode_departemen = '" . $IDX . "'";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['nama_departemen'];
		}
	}else{
		$varResult = "0";
	}
	return $varResult;
}
function getKategoriNameByCode($IDX) {
	$strSQL="SELECT nama_kategori FROM `dbo_kategori` where kode_kategori = '" . $IDX . "'";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['nama_kategori'];
		}
	}else{
		$varResult = "0";
	}
	return $varResult;
}
function getSubKategoriNameByCode($IDX) {
	$strSQL="SELECT nama_subkategori FROM `dbo_subkategori` where kode_subkategori = '" . $IDX . "'";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['nama_subkategori'];
		}
	}else{
		$varResult = "0";
	}
	return $varResult;
}

function getNamaMesinNoid($IDX) {
	$strSQL="SELECT nama_mesin FROM `dbo_mesin_edc` where noid = '" . $IDX . "'";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['nama_mesin'];
		}
	}else{
		$varResult = "#";
	}
	return $varResult;
}
function getBankPenerbitNoid($IDX) {
	$strSQL="SELECT bank_penerbit FROM `dbo_mesin_edc` where noid = '" . $IDX . "'";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['bank_penerbit'];
		}
	}else{
		$varResult = "#";
	}
	return $varResult;
}
function getTotalPoinMember($IDX) {
	$strSQL="SELECT IFNULL(SUM(nilai_poin), 0) as Total FROM `dbo_poin_member` WHERE member_id = '" . $IDX . "'";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['Total'];
		}
	}else{
		$varResult = 0;
	}
	return $varResult;
}

function getNamaUser($IDX) {
	$strSQL="SELECT nama_user FROM `dbo_user` where kode_kasir = '" . $IDX . "'";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['nama_user'];
		}
	}
	return $varResult;
}

function getNamaStore($IDX) {
	$strSQL="SELECT nama_store FROM `dbo_store` where kode_store = '" . $IDX . "'";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['nama_store'];
		}
	}
	return $varResult;
}

function getTotalItemPerStruk($IDX) {
	$strSQL="SELECT sum(qty_sales) as total FROM `dbo_detail` where no_struk = '" . $IDX . "'";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['total'];
		}
	}
	return $varResult;
}

/*DASHBOARD PER STORE*/
function getCountSalesHarianStore($TGL,$IDX) {
	$strSQL="SELECT count(no_struk) as total FROM `dbo_header` where tanggal = '" . $TGL . "' and kode_store = '" . $IDX . "' and is_voided in('0','2')";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['total'];
		}
	}else{
		$varResult = 0;
	}

	if ($varResult === null) {
		$varResult = 0;
	}	
	return $varResult;
}

function getTotalGrossHarianStore($TGL,$IDX) {
	$strSQL="SELECT sum(gross_sales) as total FROM `dbo_detail` where no_struk in (SELECT no_struk FROM `dbo_header` where tanggal = '" . $TGL . "' and kode_store = '" . $IDX . "' and is_voided in('0','2'))";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['total'];
		}
	}else{
		$varResult = 0;
	}
	if ($varResult === null) {
		$varResult = 0;
	}
	return $varResult;
}

function getTotalNettoHarianStore($TGL,$IDX) {
	$strSQL="SELECT sum(netto_sales) as total FROM `dbo_detail` where no_struk in (SELECT no_struk FROM `dbo_header` where tanggal = '" . $TGL . "' and kode_store = '" . $IDX . "' and is_voided in('0','2'))";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['total'];
		}
	}else{
		$varResult = 0;
	}
	if ($varResult === null) {
		$varResult = 0;
	}
	return $varResult;
}

function getTotalPembulatanHarianStore($TGL,$IDX) {
	$strSQL="SELECT sum(var_pembulatan) as total FROM `dbo_header` where tanggal = '" . $TGL . "' and kode_store = '" . $IDX . "' and is_voided in('0','2')";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['total'];
		}
	}else{
		$varResult = 0;
	}
	if ($varResult === null) {
		$varResult = 0;
	}
	return $varResult;
}

function getTotalKembalianHarianStore($TGL,$IDX) {
	$strSQL="SELECT sum(kembalian) as total FROM `dbo_header` where tanggal = '" . $TGL . "' and kode_store = '" . $IDX . "' and is_voided in('0','2')";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['total'];
		}
	}else{
		$varResult = 0;
	}
	if ($varResult === null) {
		$varResult = 0;
	}
	return $varResult;
}

function getTotalDiskonHarianStore($TGL,$IDX) {
	$strSQL="SELECT sum(var_diskon) as total FROM `dbo_detail` where no_struk in (SELECT no_struk FROM `dbo_header` where tanggal = '" . $TGL . "' and kode_store = '" . $IDX . "' and is_voided in('0','2'))";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['total'];
		}
	}else{
		$varResult = 0;
	}
	if ($varResult === null) {
		$varResult = 0;
	}
	return $varResult;
}

function getTotalCashHarianStore($TGL,$IDX) {
	$strSQL="SELECT sum(var_cash) as total FROM `dbo_header` where tanggal = '" . $TGL . "' and kode_store = '" . $IDX . "' and is_voided in('0','2')";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['total'];
		}
	}else{
		$varResult = 0;
	}
	if ($varResult === null) {
		$varResult = 0;
	}
	return $varResult;
}

function getTotalNonCashHarianStore($TGL,$IDX) {
	$strSQL="SELECT sum(var_noncash) as total FROM `dbo_header` where tanggal = '" . $TGL . "' and kode_store = '" . $IDX . "' and is_voided in('0','2')";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['total'];
		}
	}else{
		$varResult = 0;
	}
	if ($varResult === null) {
		$varResult = 0;
	}
	return $varResult;
}

function getTotalVoucherHarianStore($TGL,$IDX) {
	$strSQL="SELECT sum(var_voucher) as total FROM `dbo_header` where tanggal = '" . $TGL . "' and kode_store = '" . $IDX . "' and is_voided in('0','2')";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['total'];
		}
	}else{
		$varResult = 0;
	}
	if ($varResult === null) {
		$varResult = 0;
	}
	return $varResult;
}

function getTotalPointHarianStore($TGL,$IDX) {
	$strSQL="SELECT sum(var_poin) as total FROM `dbo_header` where tanggal = '" . $TGL . "' and kode_store = '" . $IDX . "' and is_voided in('0','2')";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['total'];
		}
	}else{
		$varResult = 0;
	}
	if ($varResult === null) {
		$varResult = 0;
	}
	return $varResult;
}
/*DASHBOARD PER STORE*/

/*DASHBOARD PER REGISTER*/
function getCountSalesHarianRegister($TGL,$IDX) {
	$strSQL="SELECT sum(var_poin) as total FROM `dbo_header` where tanggal = '" . $TGL . "' and kode_register = '" . $IDX . "' and is_voided in('0','2')";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $JumBar;
		}
	}else{
		$varResult = 0;
	}

	if ($varResult === null) {
		$varResult = 0;
	}	
	return $varResult;
}

function getTotalGrossHarianRegister($TGL,$IDX) {
	$strSQL="SELECT sum(gross_sales) as total FROM `dbo_detail` where no_struk in (SELECT no_struk FROM `dbo_header` where tanggal = '" . $TGL . "' and kode_register = '" . $IDX . "' and is_voided in('0','2'))";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['total'];
		}
	}else{
		$varResult = 0;
	}
	if ($varResult === null) {
		$varResult = 0;
	}
	return $varResult;
}

function getTotalNettoHarianRegister($TGL,$IDX) {
	$strSQL="SELECT sum(netto_sales) as total FROM `dbo_detail` where no_struk in (SELECT no_struk FROM `dbo_header` where tanggal = '" . $TGL . "' and kode_register = '" . $IDX . "' and is_voided in('0','2'))";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['total'];
		}
	}else{
		$varResult = 0;
	}
	if ($varResult === null) {
		$varResult = 0;
	}
	return $varResult;
}

function getTotalPembulatanHarianRegister($TGL,$IDX) {
	$strSQL="SELECT sum(var_pembulatan) as total FROM `dbo_header` where tanggal = '" . $TGL . "' and kode_register = '" . $IDX . "' and is_voided in('0','2')";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['total'];
		}
	}else{
		$varResult = 0;
	}
	if ($varResult === null) {
		$varResult = 0;
	}
	return $varResult;
}

function getTotalKembalianHarianRegister($TGL,$IDX) {
	$strSQL="SELECT sum(kembalian) as total FROM `dbo_header` where tanggal = '" . $TGL . "' and kode_register = '" . $IDX . "' and is_voided in('0','2')";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['total'];
		}
	}else{
		$varResult = 0;
	}
	if ($varResult === null) {
		$varResult = 0;
	}
	return $varResult;
}


function getTotalDiskonHarianRegister($TGL,$IDX) {
	$strSQL="SELECT sum(var_diskon) as total FROM `dbo_detail` where no_struk in (SELECT no_struk FROM `dbo_header` where tanggal = '" . $TGL . "' and kode_register = '" . $IDX . "' and is_voided in('0','2'))";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['total'];
		}
	}else{
		$varResult = 0;
	}
	if ($varResult === null) {
		$varResult = 0;
	}
	return $varResult;
}

function getTotalCashHarianRegister($TGL,$IDX) {
	$strSQL="SELECT sum(var_cash) as total FROM `dbo_header` where tanggal = '" . $TGL . "' and kode_register = '" . $IDX . "' and is_voided in('0','2')";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['total'];
		}
	}else{
		$varResult = 0;
	}
	if ($varResult === null) {
		$varResult = 0;
	}
	return $varResult;
}

function getTotalNonCashHarianRegister($TGL,$IDX) {
	$strSQL="SELECT sum(var_noncash) as total FROM `dbo_header` where tanggal = '" . $TGL . "' and kode_register = '" . $IDX . "' and is_voided in('0','2')";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['total'];
		}
	}else{
		$varResult = 0;
	}
	if ($varResult === null) {
		$varResult = 0;
	}
	return $varResult;
}

function getTotalVoucherHarianRegister($TGL,$IDX) {
	$strSQL="SELECT sum(var_voucher) as total FROM `dbo_header` where tanggal = '" . $TGL . "' and kode_register = '" . $IDX . "' and is_voided in('0','2')";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['total'];
		}
	}else{
		$varResult = 0;
	}
	if ($varResult === null) {
		$varResult = 0;
	}
	return $varResult;
}

function getTotalPointHarianRegister($TGL,$IDX) {
	$strSQL="SELECT sum(var_poin) as total FROM `dbo_header` where tanggal = '" . $TGL . "' and kode_register = '" . $IDX . "' and is_voided in('0','2')";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['total'];
		}
	}else{
		$varResult = 0;
	}
	if ($varResult === null) {
		$varResult = 0;
	}
	return $varResult;
}
/*DASHBOARD PER REGISTER*/
function getRegisterByNoStruk($IDX) {
	$strSQL="SELECT kode_register FROM `dbo_header` where no_struk = '" . $IDX . "'";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['kode_register'];
		}
	}else{
		$varResult = "-";
	}
	return $varResult;
}
function getKodeKasirByInvoiceNo($IDX) {
	$strSQL="SELECT kode_kasir FROM `dbo_header` where no_struk = '" . $IDX . "'";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['kode_kasir'];
		}
	}

	return $varResult;
}

function cekPromoBarang($IDX,$UOMX){
	$strSQL="SELECT b.kode_barang, b.barcode, b.nama_barang, b.sku_barang, p.uom, p.harga_jual, c.`kode_promo`, d.`kriteria_promo`, d.`value_promo`
        FROM dbo_barang b
        JOIN dbo_price p ON b.sku_barang = p.sku_barang
        LEFT JOIN dbo_promo_detail c ON b.sku_barang = c.`sku_barang` AND p.uom = c.`uom`
        LEFT JOIN dbo_promo d ON c.`kode_promo` = d.`kode_promo`
        WHERE b.kode_barang LIKE '%" . $IDX . "%' AND p.uom = '" . $UOMX . "'";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['kriteria_promo'];
		}
	}
	if ($varResult === null) {
		$varResult = "";
	}

		return $varResult;		
}
function cekParameterPromoBarang($IDX,$UOMX){
	$strSQL="SELECT b.kode_barang, b.barcode, b.nama_barang, b.sku_barang, p.uom, p.harga_jual, c.`kode_promo`, d.`kriteria_promo`, d.`promo_parameter`, d.`value_promo`
        FROM dbo_barang b
        JOIN dbo_price p ON b.sku_barang = p.sku_barang
        LEFT JOIN dbo_promo_detail c ON b.sku_barang = c.`sku_barang` AND p.uom = c.`uom`
        LEFT JOIN dbo_promo d ON c.`kode_promo` = d.`kode_promo`
        WHERE b.kode_barang LIKE '%" . $IDX . "%' AND p.uom = '" . $UOMX . "'";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['promo_parameter'];
		}
	}
	if ($varResult === null) {
		$varResult = "";
	}

	return $varResult;		
}
function cekKriteriaPromoBarang($IDX,$UOMX){
	$strSQL="SELECT b.kode_barang, b.barcode, b.nama_barang, b.sku_barang, p.uom, p.harga_jual, c.`kode_promo`, d.`kriteria_promo`, d.`promo_parameter`, d.`kriteria_value`, d.`value_promo`
        FROM dbo_barang b
        JOIN dbo_price p ON b.sku_barang = p.sku_barang
        LEFT JOIN dbo_promo_detail c ON b.sku_barang = c.`sku_barang` AND p.uom = c.`uom`
        LEFT JOIN dbo_promo d ON c.`kode_promo` = d.`kode_promo`
        WHERE b.kode_barang LIKE '%" . $IDX . "%' AND p.uom = '" . $UOMX . "'";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['kriteria_value'];
		}
	}
	if ($varResult === null) {
		$varResult = "";
	}

	return $varResult;		
}

function cekTypePromoBarang($IDX,$UOMX){
	$strSQL="SELECT b.kode_barang, b.barcode, b.nama_barang, b.sku_barang, p.uom, p.harga_jual, c.`kode_promo`, d.`kriteria_promo`, d.`promo_parameter`, d.`promo_type`, d.`value_promo`
        FROM dbo_barang b
        JOIN dbo_price p ON b.sku_barang = p.sku_barang
        LEFT JOIN dbo_promo_detail c ON b.sku_barang = c.`sku_barang` AND p.uom = c.`uom`
        LEFT JOIN dbo_promo d ON c.`kode_promo` = d.`kode_promo`
        WHERE b.kode_barang LIKE '%" . $IDX . "%' AND p.uom = '" . $UOMX . "'";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['promo_type'];
		}
	}
	if ($varResult === null) {
		$varResult = "";
	}

	return $varResult;		
}

function cekValuePromoBarang($IDX,$UOMX){
	$strSQL="SELECT b.kode_barang, b.barcode, b.nama_barang, b.sku_barang, p.uom, p.harga_jual, c.`kode_promo`, d.`kriteria_promo`, d.`promo_parameter`, d.`promo_type`, d.`value_promo`
        FROM dbo_barang b
        JOIN dbo_price p ON b.sku_barang = p.sku_barang
        LEFT JOIN dbo_promo_detail c ON b.sku_barang = c.`sku_barang` AND p.uom = c.`uom`
        LEFT JOIN dbo_promo d ON c.`kode_promo` = d.`kode_promo`
        WHERE b.kode_barang LIKE '%" . $IDX . "%' AND p.uom = '" . $UOMX . "'";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['value_promo'];
		}
	}
	if ($varResult === null) {
		$varResult = "";
	}

	return $varResult;		
}

function cekQtyFree($IDX,$UOMX){
	$strSQL="SELECT b.kode_barang, b.barcode, b.nama_barang, b.sku_barang, p.uom, p.harga_jual, c.`kode_promo`, d.`kriteria_promo`, d.`promo_parameter`, d.`qty_free_item`, d.`value_promo`
        FROM dbo_barang b
        JOIN dbo_price p ON b.sku_barang = p.sku_barang
        LEFT JOIN dbo_promo_detail c ON b.sku_barang = c.`sku_barang` AND p.uom = c.`uom`
        LEFT JOIN dbo_promo d ON c.`kode_promo` = d.`kode_promo`
        WHERE b.kode_barang LIKE '%" . $IDX . "%' AND p.uom = '" . $UOMX . "'";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['qty_free_item'];
		}
	}
	if ($varResult === null) {
		$varResult = 0;
	}

	return $varResult;		
}

function cekTagFreeItem($IDX,$UOMX){
	$strSQL="SELECT b.kode_barang, b.barcode, b.nama_barang, b.sku_barang, p.uom, p.harga_jual, c.`kode_promo`, d.`kriteria_promo`, d.`promo_parameter`, d.`free_item`, d.`value_promo`
        FROM dbo_barang b
        JOIN dbo_price p ON b.sku_barang = p.sku_barang
        LEFT JOIN dbo_promo_detail c ON b.sku_barang = c.`sku_barang` AND p.uom = c.`uom`
        LEFT JOIN dbo_promo d ON c.`kode_promo` = d.`kode_promo`
        WHERE b.kode_barang LIKE '%" . $IDX . "%' AND p.uom = '" . $UOMX . "'";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['free_item'];
		}
	}
	if ($varResult === null) {
		$varResult = 0;
	}

	return $varResult;		
}

function cekKodeBarangPromoTempTransaksi($IDX,$UOMX,$ORDX){
	$strSQL="SELECT count(qty)as total from temp_transaksi where kode_barang like '%" . $IDX . "%' and  uom = '" . $UOMX . "' and  order_no = '" . $ORDX . "' and status = 'CURRENT'";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['total'];
		}
	}
	if ($varResult === null) {
		$varResult = 0;
	}

	return $varResult;		
}

function cekUomBarangPromoTempTransaksi($IDX,$UOMX,$ORDX){
	$strSQL="SELECT uom from temp_transaksi where kode_barang like '%" . $IDX . "%' and  uom = '" . $UOMX . "' and  order_no = '" . $ORDX . "' and status = 'CURRENT'";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['uom'];
		}
	}
	if ($varResult === null) {
		$varResult = 0;
	}

	return $varResult;		
}

function cekUomBarangFreeItem($IDX){
	$strSQL="SELECT a.noid,a.sku_barang,a.barcode,a.uom,a.harga_jual,b.nama_barang FROM `dbo_price` a join `dbo_barang` b on a.sku_barang = b.sku_barang where a.sku_barang = '" . $IDX . "' and a.fl_active = 1 order by a.barcode DESC limit 1";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['uom'];
		}
	}
	if ($varResult === null) {
		$varResult = 0;
	}

	return $varResult;		
}

function cekQtyTempTransaksi($IDX,$UOMX,$ORDX){
	$strSQL="SELECT sum(qty)as total from temp_transaksi where kode_barang = '" . $IDX . "' and  uom = '" . $UOMX . "' and  order_no = '" . $ORDX . "' and status = 'CURRENT'";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['total'];
		}
	}
	if ($varResult === null) {
		$varResult = 0;
	}

	return $varResult;		
}

function getFileBarcodeByKodeBarang($IDX) {
	//$StrSearch = str_replace("(P)","",$IDX);
	$StrSearch = getTextAfterP($IDX);
	$strSQL="SELECT file_barcode FROM `dbo_barang` where kode_barang = '" . $StrSearch . "'";
	$CallstrSQL=mysqli_query($GLOBALS["___mysqli_ston_"], $strSQL);
	$JumBar=mysqli_num_rows($CallstrSQL);
	if ($JumBar>0){
		while($rec=mysqli_fetch_array($CallstrSQL)){
			$varResult = $rec['file_barcode'];
		}
	}else{
		$varResult = "";
	}
	return $varResult;
}
?>

