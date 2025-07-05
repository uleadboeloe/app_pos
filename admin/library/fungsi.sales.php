<?php
include_once "connection.php";

/*==============GET TOTAL SALES,GROSS,DISKON,QTY PER ITEM PER TANGGAL=======================*/
function getTotalSalesPerItemPerTanggal($IDX,$TGLX) {
	$strSQL="SELECT sum(total_sales) as total FROM `dbo_detail` where no_struk in (select no_struk from dbo_header where tanggal = '" . $TGLX . "') and kode_barang = '" . $IDX . "'";
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
function getTotalGrossPerItemPerTanggal($IDX,$TGLX) {
	$strSQL="SELECT sum(gross_sales) as total FROM `dbo_detail` where no_struk in (select no_struk from dbo_header where tanggal = '" . $TGLX . "') and kode_barang = '" . $IDX . "'";
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
function getTotalDiskonPerItemPerTanggal($IDX,$TGLX) {
	$strSQL="SELECT sum(var_diskon) as total FROM `dbo_detail` where no_struk in (select no_struk from dbo_header where tanggal = '" . $TGLX . "') and kode_barang = '" . $IDX . "'";
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
function getTotalNettoPerItemPerTanggal($IDX,$TGLX) {
	$strSQL="SELECT sum(netto_sales) as total FROM `dbo_detail` where no_struk in (select no_struk from dbo_header where tanggal = '" . $TGLX . "') and kode_barang = '" . $IDX . "'";
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
/*==============GET TOTAL SALES,GROSS,DISKON,QTY PER ITEM PER TANGGAL=======================*/

/*==============GET TOTAL SALES,GROSS,DISKON,QTY PER ITEM PER PERIODE=======================*/
function getTotalNettoPerItemPerPeriode($IDX,$TGLX,$TGLXX) {
	$strSQL="SELECT sum(netto_sales) as total FROM `dbo_detail` where no_struk in (select no_struk from dbo_header where tanggal between '" . $TGLX . "' and '" . $TGLXX . "') and kode_barang = '" . $IDX . "'";
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
function getTotalDiskonPerItemPerPeriode($IDX,$TGLX,$TGLXX) {
	$strSQL="SELECT sum(var_diskon) as total FROM `dbo_detail` where no_struk in (select no_struk from dbo_header where tanggal between '" . $TGLX . "' and '" . $TGLXX . "') and kode_barang = '" . $IDX . "'";
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
function getTotalGrossPerItemPerPeriode($IDX,$TGLX,$TGLXX) {
	$strSQL="SELECT sum(gross_sales) as total FROM `dbo_detail` where no_struk in (select no_struk from dbo_header where tanggal between '" . $TGLX . "' and '" . $TGLXX . "') and kode_barang = '" . $IDX . "'";
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
function getTotalSalesPerItemPerPeriode($IDX,$TGLX,$TGLXX) {
	$strSQL="SELECT sum(total_sales) as total FROM `dbo_detail` where no_struk in (select no_struk from dbo_header where tanggal between '" . $TGLX . "' and '" . $TGLXX . "') and kode_barang = '" . $IDX . "'";
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
/*==============GET TOTAL SALES,GROSS,DISKON,QTY PER ITEM PER PERIODE=======================*/

/*==============GET TOTAL SALES,GROSS,DISKON,QTY PER SUBKATEGORI=======================*/
function getTotalSalesPerSubKategoriPerTanggal($IDX,$TGLX) {
	$strSQL="SELECT sum(total_sales) as total FROM `dbo_detail` where no_struk in (select no_struk from dbo_header where tanggal = '" . $TGLX . "') and kode_barang in(select kode_barang from dbo_barang where sub_dept = '" . $IDX . "')";
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
function getTotalGrossPerSubKategoriPerTanggal($IDX,$TGLX) {
	$strSQL="SELECT sum(gross_sales) as total FROM `dbo_detail` where no_struk in (select no_struk from dbo_header where tanggal = '" . $TGLX . "') and kode_barang in(select kode_barang from dbo_barang where sub_dept = '" . $IDX . "')";
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
function getTotalNettoPerSubKategoriPerTanggal($IDX,$TGLX) {
	$strSQL="SELECT sum(netto_sales) as total FROM `dbo_detail` where no_struk in (select no_struk from dbo_header where tanggal = '" . $TGLX . "') and kode_barang in(select kode_barang from dbo_barang where sub_dept = '" . $IDX . "')";
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
function getTotalDiskonPerSubKategoriPerTanggal($IDX,$TGLX) {
	$strSQL="SELECT sum(var_diskon) as total FROM `dbo_detail` where no_struk in (select no_struk from dbo_header where tanggal = '" . $TGLX . "') and kode_barang in(select kode_barang from dbo_barang where sub_dept = '" . $IDX . "')";
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
function getTotalQtyPerSubKategoriPerTanggal($IDX,$TGLX) {
	$strSQL="SELECT sum(qty_sales) as total FROM `dbo_detail` where no_struk in (select no_struk from dbo_header where tanggal = '" . $TGLX . "') and kode_barang in(select kode_barang from dbo_barang where sub_dept = '" . $IDX . "')";
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
/*==============GET TOTAL SALES,GROSS,DISKON,QTY PER SUBKATEGORI=======================*/
function getTotalSalesPerSubKategoriPerPeriode($IDX,$TGLX,$TGLXX) {
	$strSQL="SELECT sum(total_sales) as total FROM `dbo_detail` where no_struk in (select no_struk from dbo_header where tanggal between '" . $TGLX . "' and '" . $TGLXX . "') and kode_barang in(select kode_barang from dbo_barang where sub_dept = '" . $IDX . "')";
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
function getTotalGrossPerSubKategoriPerPeriode($IDX,$TGLX,$TGLXX) {
	$strSQL="SELECT sum(gross_sales) as total FROM `dbo_detail` where no_struk in (select no_struk from dbo_header where tanggal between '" . $TGLX . "' and '" . $TGLXX . "') and kode_barang in(select kode_barang from dbo_barang where sub_dept = '" . $IDX . "')";
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
function getTotalNettoPerSubKategoriPerPeriode($IDX,$TGLX,$TGLXX) {
	$strSQL="SELECT sum(netto_sales) as total FROM `dbo_detail` where no_struk in (select no_struk from dbo_header where tanggal between '" . $TGLX . "' and '" . $TGLXX . "') and kode_barang in(select kode_barang from dbo_barang where sub_dept = '" . $IDX . "')";
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
function getTotalDiskonPerSubKategoriPerPeriode($IDX,$TGLX,$TGLXX) {
	$strSQL="SELECT sum(var_diskon) as total FROM `dbo_detail` where no_struk in (select no_struk from dbo_header where tanggal between '" . $TGLX . "' and '" . $TGLXX . "') and kode_barang in(select kode_barang from dbo_barang where sub_dept = '" . $IDX . "')";
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
function getTotalQtyPerSubKategoriPerPeriode($IDX,$TGLX,$TGLXX) {
	$strSQL="SELECT sum(qty_sales) as total FROM `dbo_detail` where no_struk in (select no_struk from dbo_header where tanggal between '" . $TGLX . "' and '" . $TGLXX . "') and kode_barang in(select kode_barang from dbo_barang where sub_dept = '" . $IDX . "')";
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
?>