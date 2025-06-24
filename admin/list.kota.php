<?php
include_once "library/connection.php";
include_once "library/parameter.php";

$variable = $_POST['noid'];
//echo $variable;
?>
<span class="text-purple-500 font-bold">Kota <div class="badge rounded-full bg-primary/10 text-primary dark:bg-accent-light/15 dark:text-accent-light">Wajib</div></span>
<span class="relative mt-1.5 flex">
	<select id="txtKota" name="txtKota" class="form-select mt-1 h-12 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs+ hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent" required>
	<?php
	$strSQL = "SELECT * FROM dbo_kota where provinsi_id = $variable";
	$CallstrSQL=mysqli_query($koneksidb, $strSQL);
		while($result=mysqli_fetch_array($CallstrSQL))
		{
		$varKode = $result['no_id'];
		$varNamaKategori = $result['nama_kota'];
		?>
		<option value="<?php	echo $varKode;	?>"><?php	echo $varNamaKategori;	?></option>
		<?php
		}
	?>
	</select>
</span>