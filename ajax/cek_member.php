<?php
include("../lib/mysql_pdo.php");
include("../lib/mysql_connect.php");
include("../lib/general_lib.php");

$id = isset($_POST['memberid']) ? $_POST['memberid'] : '';
$source = isset($_POST['source']) ? $_POST['source'] : '';
//echo $id . "#" . $source;
switch ($source)
{
    case "memberid":
        $CheckMemberHNI = CheckMemberByID($id);
        $dataMemberid = json_decode($CheckMemberHNI, true);
        $codex = $dataMemberid['code'];
        $msgx = $dataMemberid['msg'] ?? "";
        $datax = $dataMemberid['data'];
        if($codex == "000"){
            $MemberID = $datax['customer_id'];
            $MemberName = $datax['customer_name'];
            $MemberName = str_replace("'","`",$MemberName);
            $PhoneByMemberid = $datax['customer_hp'];
            $varStatusMember = "MEMBER_HNI#" . $MemberName . "#" . $PhoneByMemberid;
        }else{
            // Query untuk menjumlahkan nilai voucher berdasarkan kode_voucher
            $stmt = $db->prepare("SELECT nama_member,nomor_kontak FROM dbo_member WHERE kode_member = :kode_member");
            //SELECT nominal FROM dbo_voucher WHERE kode_voucher = '170809469642a16e-0003'
            $stmt->bindParam(':kode_member', $id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $jumlahRecord = $stmt->rowCount();

            if($jumlahRecord == 0){
                $PhoneByMemberid = "";
                $varStatusMember = "";
                $MemberName = "";
                $MemberID = "";
            }else{
                $MemberID = $id;
                $MemberName = $result['nama_member'] ?? '';
                $PhoneByMemberid = $result['nomor_kontak'] ?? '';
                $varStatusMember = "MEMBER TOKO#" . $MemberName . "#" . $PhoneByMemberid;
            }
        }

        echo $varStatusMember;
    break;
    case "phoneno":
        $CheckMemberHNI = CheckMemberByPhone($id);
        $dataMemberid = json_decode($CheckMemberHNI, true);

        $codex = $dataMemberid['code'];
        $msgx = $dataMemberid['msg'] ?? "";
        $datax = $dataMemberid['data'];
        if($codex == "000"){
            $MemberID = $datax['customer_id'];
            $MemberName = $datax['customer_name'];
            $MemberName = str_replace("'","`",$MemberName);
            $PhoneByMemberid = $datax['customer_hp'];
            $varStatusMember = "MEMBER_HNI#" . $MemberName . "#" . $PhoneByMemberid;
        }else{
            // Query untuk menjumlahkan nilai voucher berdasarkan kode_voucher
            $stmt = $db->prepare("SELECT nama_member,nomor_kontak FROM dbo_member WHERE kode_member = :kode_member");
            //SELECT nominal FROM dbo_voucher WHERE kode_voucher = '170809469642a16e-0003'
            $stmt->bindParam(':kode_member', $id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $jumlahRecord = $stmt->rowCount();

            if($jumlahRecord == 0){
                $PhoneByMemberid = "";
                $varStatusMember = "";
                $MemberName = "";
                $MemberID = "";
            }else{
                $MemberID = $id;
                $MemberName = $result['nama_member'] ?? '';
                $PhoneByMemberid = $result['nomor_kontak'] ?? '';
                $varStatusMember = "MEMBER TOKO#" . $MemberName . "#" . $PhoneByMemberid;
            }
        }

        echo $varStatusMember;
    break; 
    case "verify":
        $CheckMemberHNI = CheckMemberByPhone($id);
        $dataMemberid = json_decode($CheckMemberHNI, true);

        $codex = $dataMemberid['code'];
        $msgx = $dataMemberid['msg'] ?? "";
        $datax = $dataMemberid['data'];
        if($codex === "000"){
            $MemberID = $datax['customer_id'];
            $MemberName = $datax['customer_name'];
            $MemberName = str_replace("'","`",$MemberName);
            $PhoneByMemberid = $datax['customer_hp'];
            $varStatusMember = "MEMBER_HNI#" . $MemberName . "#" . $PhoneByMemberid;
        }else{
            // Query untuk menjumlahkan nilai voucher berdasarkan kode_voucher
            $stmt = $db->prepare("SELECT nama_member,nomor_kontak FROM dbo_member WHERE kode_member = :kode_member");
            //SELECT nominal FROM dbo_voucher WHERE kode_voucher = '170809469642a16e-0003'
            $stmt->bindParam(':kode_member', $id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $jumlahRecord = $stmt->rowCount();

            if($jumlahRecord == 0){
                $PhoneByMemberid = "";
                $varStatusMember = "";
                $MemberName = "";
                $MemberID = "";
            }else{
                $MemberID = $id;
                $MemberName = $result['nama_member'] ?? '';
                $PhoneByMemberid = $result['nomor_kontak'] ?? '';
                $varStatusMember = "MEMBER TOKO#" . $MemberName . "#" . $PhoneByMemberid;
            }
        }
        echo $MemberID;
    break;     
}
?>