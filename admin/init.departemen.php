<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include_once "library/connection.php";
include_once "library/parameter.php";
include_once "library/fungsi.php";

if(isset($_SESSION['SESS_user_id'])){
    $ftp_server = "ftp.amanahmart.id";
    $ftp_username = "IT";
    $ftp_password = "It@123";
    $ftp_port = "21";

    $ftp_file = "/MASTERBC/Departemen.txt";
    $local_file = "fileupload/Departemen.txt";

    /*===============Tarik Flatfile Daily BC===================================*/
    // Initialize cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "ftp://$ftp_server$ftp_file");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, "$ftp_username:$ftp_password");
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
    curl_setopt($ch, CURLOPT_PORT, $ftp_port);
    // Open file to write
    $fp = fopen($local_file, 'w');
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Error: ' . curl_error($ch) . "<br>";
        //insert log error here, datetime, error message
    } else {
        echo 'File downloaded successfully<br>';
        //insert log error here, datetime, error message
    }
    fclose($fp);
    curl_close($ch);

    /*
    ===============Tarik Flatfile Master Departemen Daily BC===================================
    */
    $Filesize = filesize($local_file);
    if($Filesize > 0){
        $myfiles = fopen($local_file, "r") or die("Unable to open file!");
        $KontenFiles = fread($myfiles,filesize($local_file));
        //echo "Data Item : " .$KontenFiles."<br>";

        $varArrays = explode("^",$KontenFiles);
        $CountArrays = count($varArrays);
        //echo "Count Array : " .$varArrays."<br>";
        
        $varDataItem = explode("^", $KontenFiles);
        for($x=0;$x<$CountArrays;$x++){
            $varStrings = $varDataItem[$x];
            $varDataItemLine = explode("|", $varStrings);
            $Kode = $varDataItemLine[0];
            $StrLenKode = strlen($Kode);
            $LabelName = $varDataItemLine[1];
            //echo $x . "Data Item 1: " .$Kode . "#Data Item 2: " .$Kode . "<br>";
            //$varItemNo = $varDataItemLine[2];
            //if($StrLenKode ==4){
            $strQuery="SELECT * FROM dbo_departemen WHERE kode_departemen = '" . $Kode . "' and nama_departemen = '" . $LabelName . "'";
            $callstrQuery=mysqli_query($koneksidb, $strQuery);
            $Jumbar=mysqli_num_rows($callstrQuery);
            if($Jumbar == 0){
                $strInsert="INSERT INTO dbo_departemen(`kode_departemen`,`nama_departemen`,`posting_date`,`posting_user`) VALUES ('$Kode','$LabelName','$datedb','" . $_SESSION['SESS_user_id'] . "')";
                $executeSQL=mysqli_query($koneksidb, $strInsert); 
                //echo $strInsert . "<br>";
            }
            //}
        }
        fclose($myfiles);
    }
}
?>
