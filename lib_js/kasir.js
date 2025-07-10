String.prototype.replaceAll = function (str1, str2, ignore) {
  return this.replace(
    new RegExp(
      str1.replace(/([\/\,\!\\\^\$\{\}\[\]\(\)\.\*\+\?\|\<\>\-\&])/g, "\\$&"),
      ignore ? "gi" : "g"
    ),
    typeof str2 == "string" ? str2.replace(/\$/g, "$$$$") : str2
  );
};

function onlyNumbers(input) {
  return input.toString().replace(/[^0-9.]/g, "");
}

function formatRibuan(angka) {
  if (angka == null) return "";
  return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

function KeypadBS() {
  var t = $("#EditBoxTendered").val();
  t = t.replaceAll(".", "");
  t = t.slice(0, -1);
  tf = formatRibuan(t);
  $("#EditBoxTendered").val(tf);

  // hitung kembalian
  var m = $("#hiddenTotal").val();
  m = m.replaceAll(".", "");

  if (Number(t) > Number(m)) {
    $("#pTendered").css("color", "black");
    var c = t - m;
    $("#TxtChanges").text("CHANGES: Rp." + formatRibuan(c));
  } else {
    $("#pTendered").css("color", "red");
    $("#TxtChanges").text("CHANGES: Rp.0");
  }
}

function KeypadCL() {
  $("#EditBoxTendered").val("");
  $("#pTendered").css("color", "red");
  $("#TxtChanges").text("CHANGES: Rp.0");
}

function KeypadNumber(n) {
  var t = $("#EditBoxTendered").val();

  if (t == "" && n.slice(-1) == "0") return;

  t = t.replaceAll(".", "");
  t = t + n.slice(-1);
  tf = formatRibuan(t);
  $("#EditBoxTendered").val(tf);

  // hitung kembalian
  var m = $("#hiddenTotal").val();
  m = m.replaceAll(".", "");

  if (Number(t) > Number(m)) {
    $("#pTendered").css("color", "black");
    var c = t - m;
    $("#TxtChanges").text("CHANGES: Rp." + formatRibuan(c));
  } else {
    $("#pTendered").css("color", "red");
    $("#TxtChanges").text("CHANGES: Rp.0");
  }
}

function pad(num, size) {
  num = num.toString();
  while (num.length < size) num = "0" + num;
  return num;
}

$(document).ready(function () {
  window.current_order_no = prefix_struk_no + pad(lastno_struk_no, 6);
  window.proses_order_no = prefix_struk_no + pad(lastno_struk_no, 6);
  window.current_lastno_struk_no = lastno_struk_no;
  window.current_user_id = user_id;
  window.current_user_name = user_name;
  window.current_kode_store = kode_store;
  window.current_kode_kasir = kode_kasir;

  window.spvpassword = ""; // password supervisor
  window.btnminusdelete; // button minus delete
  window.isvoid = false; // flag void
  window.editharga = false;
  window.isdeleteline = false;
  window.ispoinchecked = false;

  window.sessionStorage.setItem("modal_awal", "0");

  /* Member & Voucher */
  window.sessionStorage.setItem("member_id", "");
  window.sessionStorage.setItem("poin_member_id", "0");
  window.sessionStorage.setItem("kode_voucher", "");
  window.sessionStorage.setItem("nilai_voucher", "0");

  /* Event on scan item */
  $("#EditBoxScanItem").keypress(function (e) {
    var key = e.which;
    if (key == 13) {
      // cek voucher sdh di entry
      var nilai_voucher = window.sessionStorage.getItem("nilai_voucher") || "0";
      if (nilai_voucher != "0") {
        return;
      }

      // the enter key code
      let keyword = $("#EditBoxScanItem").val();
      if (keyword === "") {
        return;
      }

      $.ajax({
        url: "api/scan_barang.php",
        type: "GET",
        dataType: "json",
        data: { keyword: keyword },
        success: function (response) {
          console.log(response);
          if (!response.status) {
            Swal.fire({
              title: "Error",
              text: "Silahkan Scan Kode Barcode yang Sesuai",
              icon: "error",
            });
            $("#EditBoxScanItem").val("");
            return;
          }

          var kodeBarang = response.data.kode_barang;
          var gramBarang = response.data.gram_barang;
          var hargaJual = response.data.harga_jual;
          var namaBarang = response.data.nama_barang;
          var uom = response.data.uom;

          console.log(kodeBarang, gramBarang, hargaJual, namaBarang);

          // deafult qty = 1
          var qty = 1.0;
          if (gramBarang != "0") {
            qty = gramBarang / 1000;
            //qty = qty.toFixed(2);
          }

          var log_description =
            "Scan Barcode Barang: " +
            response.data.kode_barang +
            " Barcode: " +
            keyword;
          var log_tipe = "INFO";
          $.ajax({
            type: "POST",
            url: "lib_dbo/simpan_log_aktivitas.php",
            data: {
              proses_user: current_user_id,
              log_description: log_description,
              log_tipe: log_tipe,
              source: "kasir",
              no_struk: current_order_no,
              kode_barang: response.data.kode_barang,
              kode_barcode: keyword,
              qty_baru: 1,
            },
          });

          // AJAX tambah barang
          $.ajax({
            url: "ajax/temp_trx_addscan.php",
            type: "POST",
            data: {
              order_no: current_order_no,
              kode_barang: kodeBarang,
              nama_barang: namaBarang,
              harga_jual: hargaJual,
              uom: uom,
              qty: qty,
            },
            success: function (res) {
              // update the content of scan item table
              refreshTable();
            },
          });
        },
      });
    }
  });

  /* Update scanned items table  */
  function refreshTable() {
    var point_member = window.sessionStorage.getItem("poin_member_id") || "0";
    point_member = point_member.replaceAll(".", "");
    var nilai_voucher = window.sessionStorage.getItem("nilai_voucher") || "0";
    nilai_voucher = nilai_voucher.replaceAll(".", "");
    $.ajax({
      url: "lib_dbo/refresh_scanned_item_table.php",
      type: "GET",
      data: {
        orderno: current_order_no,
        pointmember: point_member,
        nilaivoucher: nilai_voucher,
      },
      success: function (response) {
        $("#ItemTable tbody").empty().html(response);

        let qty = $("#hiddenQty").val();
        let subtotal = $("#hiddenSubtotal").val();
        let ppnamt = $("#hiddenPpnAmt").val();
        let total = $("#hiddenTotal").val();

        // Update tampilan subtotal & total
        $("#TxtTotalItems").text(qty);
        $("#TxtSubTotal").text(subtotal);
        $("#TxtPPN").text(ppnamt);
        $("#TxtGrandTotal").text(total);

        $("#EditBoxTendered").val(total);
        $("#pTendered").css("color", "black");

        let option1 = $("#hiddenPecahan1").val();
        let option2 = $("#hiddenPecahan2").val();
        let option3 = $("#hiddenPecahan3").val();

        $("#BtnNominalOption1").text(formatRibuan(option1));
        $("#BtnNominalOption2").text(formatRibuan(option2));
        $("#BtnNominalOption3").text(formatRibuan(option3));

        $("#EditBoxScanItem").val("").focus();
      },
    });
  }

  // Setelah tabel selesai dimuat, ambil nilai subtotal dan total
  $(document).on("ajaxComplete", function () {});

  // Event on click Keypads / panel kanan
  $(function () {
    $("span.btn").click(function (e) {
      var id = $(this).attr("id");

      // check keypad
      if (id == "BtnKeypadBS") KeypadBS();
      else if (id == "BtnKeypadCL") KeypadCL();
      else if (id.substring(0, 9) == "BtnKeypad") KeypadNumber(id);
      else if (id.substring(0, 16) == "BtnNominalOption") {
        $("#pTendered").css("color", "black");
        // khusus tombol opsi tender
        var n = $("#" + id).text();
        $("#EditBoxTendered").val(n);
        n = n.replaceAll(".", "");
        var m = $("#hiddenTotal").val();
        m = m.replaceAll(".", "");

        //console.log("n: " + n + " m: " + m);

        var c = n - m;
        $("#TxtChanges").text("CHANGES Rp." + formatRibuan(c));
      }
    });
  });

  // Event on click cell harga (edit harga)
  $(document).on("click", ".harga-cell", function () {
    var hargaText = $(this).text().trim(); // Mengambil teks dalam <td>
    harga = hargaText.substring(3);
    harga = harga.replaceAll(".", "");

    var rowValues = [];
    $(this)
      .closest("tr")
      .find("td")
      .each(function () {
        rowValues.push($(this).text());
      });

    var kodebarang = rowValues[1];
    var qty = rowValues[5];
    qty = onlyNumbers(qty);
    var diskon = rowValues[6];
    var Uom = rowValues[4];
    //var IdTemp = rowValues[4];
    //console.log("Qty: " + qty);

    // cek kode barang, kalau barang promo gak bisa!
    $.ajax({
      url: "lib_dbo/chk_promo_kodebarang.php",
      type: "GET",
      dataType: "json",
      data: { kodebarang: kodebarang },
      success: function (response) {
        //if (!response.ada_promo) {
        window.editharga = true;
        window.kodeBarang = kodebarang;
        window.qty = qty;
        window.harga = harga;
        window.diskon = diskon;
        window.uom = Uom;
        window.promo = response.ada_promo;
        $("#DialogSpvApproval").dialog("open");
        //}
      },
    });
  });

  // Event on click button minus qty
  $(document).on("click", ".btn-minus", function () {
    // cek voucher sdh di entry
    var nilai_voucher = window.sessionStorage.getItem("nilai_voucher") || "0";
    if (nilai_voucher != "0") {
      return;
    }
    window.isdeleteline = false; // set flag delete
    btnminusdelete = $(this); // simpan button minus yang di klik

    // open dialog spv approval
    $("#DialogSpvApproval").dialog("open");
  });

  // Event on click button del qty
  $(document).on("click", ".btn-del", function () {
    // cek voucher sdh di entry
    var nilai_voucher = window.sessionStorage.getItem("nilai_voucher") || "0";
    if (nilai_voucher != "0") {
      return;
    }
    window.isdeleteline = true; // set flag delete
    btnminusdelete = $(this); // simpan button minus yang di klik

    // open dialog spv approval
    $("#DialogSpvApproval").dialog("open");
  });

  // Event on press F3 - SEARCH
  $(document).keydown(function (event) {
    if (event.key === "F3") {
      event.preventDefault(); // Mencegah fungsi default browser (misalnya pencarian halaman)
      $("#BtnSearch").click(); // Trigger klik tombol
    }
  });

  // Event on click button CASH
  $(document).on("click", ".btn-cash", function () {
    if ($("#TxtGrandTotal").text() == "") {
      Swal.fire({
        title: "Error",
        text: "Silahkan Input Transaksi Terlebih Dahulu",
        icon: "error",
      });
      return;
    } // jika tidak ada transaksi

    let isChecked = $("#chkboxUsePoint").is(":checked") ? 1 : 0;
    console.log("Checked Status : " + isChecked);
    if (isChecked == 0) window.ispoinchecked = 0; // jika tidak di centang, set pointmemberid ke 0

    var point_member = window.sessionStorage.getItem("poin_member_id") || "0";
    point_member = point_member.replaceAll(".", "");
    if (window.ispoinchecked == 0) point_member = 0;

    var nilai_voucher = window.sessionStorage.getItem("nilai_voucher") || "0";
    nilai_voucher = nilai_voucher.replaceAll(".", "");

    /*addtional log aktivitas*/
    var log_description = "TENDER CASH: " + current_order_no;
    var log_tipe = "SUCCESS";
    $.ajax({
      type: "POST",
      url: "lib_dbo/simpan_log_aktivitas.php",
      data: {
        proses_user: current_user_id,
        log_description: log_description,
        log_tipe: log_tipe,
        source: "kasir",
      },
    });
    /*addtional log aktivitas*/

    $.ajax({
      type: "POST",
      url: "ajax/temp_trx_total_amount.php",
      data: {
        order_no: current_order_no,
        pointmember: point_member,
        nilaivoucher: nilai_voucher,
      },
      success: function (response) {
        if (Number(response) > 0) {
          window.totaltrx = Number(response);
          $("#DialogPaymentCash").dialog("open");
        } else {
          Swal.fire({
            title: "Error",
            text: "Silahkan Input Transaksi Terlebih Dahulu",
            icon: "error",
          });
        }
      },
      error: function () {
        console.log("Error");
      },
    });
  });

  // start focus on scan item
  $("#EditBoxScanItem").val("").focus();

  // Event on click checkbox Use Point
  $("#chkboxUsePoint").on("click", function () {
    let isChecked = $(this).is(":checked") ? 1 : 0;
    var point_member = window.sessionStorage.getItem("poin_member_id") || "0";
    point_member = point_member.replaceAll(".", "");
    if (isChecked == 0) point_member = 0; // jika tidak di centang, set pointmemberid ke 0

    var nilai_voucher = window.sessionStorage.getItem("nilai_voucher") || "0";
    nilai_voucher = nilai_voucher.replaceAll(".", "");

    $.ajax({
      url: "lib_dbo/refresh_scanned_item_table.php",
      type: "GET",
      data: {
        orderno: current_order_no,
        pointmember: point_member,
        nilaivoucher: nilai_voucher,
      },
      success: function (response) {
        $("#ItemTable tbody").empty().html(response);

        let qty = $("#hiddenQty").val();
        let subtotal = $("#hiddenSubtotal").val();
        let ppnamt = $("#hiddenPpnAmt").val();
        let total = $("#hiddenTotal").val();

        // Update tampilan subtotal & total
        $("#TxtTotalItems").text(qty);
        $("#TxtSubTotal").text(subtotal);
        $("#TxtPPN").text(ppnamt);
        $("#TxtGrandTotal").text(total);

        $("#EditBoxTendered").val(total);
        $("#pTendered").css("color", "black");

        let option1 = $("#hiddenPecahan1").val();
        let option2 = $("#hiddenPecahan2").val();
        let option3 = $("#hiddenPecahan3").val();

        $("#BtnNominalOption1").text(formatRibuan(option1));
        $("#BtnNominalOption2").text(formatRibuan(option2));
        $("#BtnNominalOption3").text(formatRibuan(option3));

        $("#EditBoxScanItem").val("").focus();
      },
    });
  });

  // F5 - Refresh page kasir
  refreshTable();

  // CREDIT CARD - btnCreditCard event click dsb berasal dari dlg_payment_creditcard.js
}); // document ready
