<?php
include_once "library/connection.php";
include_once "library/parameter.php";

$variable = $_POST['noid'];
//echo $variable;
?>
<span class="text-purple-500 font-bold">Kecamatan <div class="badge rounded-full bg-primary/10 text-primary dark:bg-accent-light/15 dark:text-accent-light">Wajib</div></span>
<span class="relative mt-1.5 flex">
	<select id="txtKecamatan" name="txtKecamatan" class="form-select mt-1 h-12 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs+ hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent" required>
	<?php
	$strSQLs = "SELECT * FROM dbo_kecamatan where id_kota = $variable";
	$CallstrSQLs=mysqli_query($koneksidb, $strSQLs);
		while($results=mysqli_fetch_array($CallstrSQLs))
		{
		$varKode = $results['no_id'];
		$varNamaKategori = $results['nama_kecamatan'];
		?>
		<option value="<?php	echo $varKode;	?>"><?php	echo $varNamaKategori;	?></option>
		<?php
		}
	?>
	</select>
</span>