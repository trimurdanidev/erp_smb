<script language="javascript" type="text/javascript">
    function addProduk() {
        var idf = document.getElementById("idf").value;
        // var countidf = $('#freq option:selected').val();
        idf = parseInt(idf) + 1;
        var stre;
        // if (idf <= countidf) {
        stre = "<p id='srow" + idf + "'><input type='text' class='' id='kd_produk' name='kd_produk[]' placeholder='kode produk, nama produk'> | Stoc Tersedia = 10 Pcs | <input type='text' class='' id='qty' name='qty[]' placeholder='masukkan Quantity'> | <input type='text' class='' id='ket' name='ket[]' placeholder='masukkan Keterangan'> | <a href='#' style=\"color:#3399FD;\" onclick='hapusElemen(\"#srow" + idf + "\"); return false;'> Hapus</a></p>";
        $("#det_plan").append(stre);
        idf = (idf - 1) + 1;
        document.getElementById("idf").value = idf;
        // } else if (idf == 0) {
        //     Swal.fire('Pilih Frekuensi Plan Terlebih Dahulu !');
        //     return false;
        // } else {
        //     Swal.fire('Mencapai Batas Frekuensi Yang dipilih !!');
        //     return false;
        // }
    }

    function hapusElemen(idf) {
        $(idf).remove();
        document.getElementById("idf").value = idf.replace("#srow", '') - 1;
    }


    (function () {
        $('form').ajaxForm({
            beforeSubmit: function () {
            },
            complete: function (xhr) {
                Swal.fire($.trim(xhr.responseText));
                showMenu('content', 'index.php?model=transaction&action=showAllJQuery&skip=<?php echo $skip ?>&search=<?php echo $search ?>');
            }
        });
    })();
    function validate(evt) {
        var e = evt || window.event;
        var key = e.keyCode || e.which;
        if ((key < 48 || key > 57) && !(key == 8 || key == 9 || key == 13 || key == 37 || key == 39 || key == 46)) {
            e.returnValue = false;
            if (e.preventDefault) e.preventDefault();
        }
    }
</script>

<br>


<form name="frmtransaction" id="frmtransaction" method="post"
    action="index.php?model=transaction&action=saveFormJQuery">
    <table>
        <tr>
            <td class="textBold">ID</td>
            <td><input type="text" class="form form-control" style="text-align: right;" onkeypress="validate(event);"
                    name="id" id="id" value="<?php echo $transaction_->getId(); ?>" size="11" ReadOnly
                    placeholder="autonumber"></td>
        </tr>

        <tr>
            <td class="textBold">Tanggal</td>
            <td><input type="text" class="form form-control" name="tanggal" id="tanggal"
                    value="<?php echo $transaction_->getTanggal(); ?>" size="10">
                <script>
                    $(function () {
                        $('#tanggal').datepicker({
                            dateFormat: 'yy-mm-dd',
                            yearRange: '-100:+20',
                            changeYear: true,
                            changeMonth: true
                        });
                    });
                </script>
            </td>
        </tr>
        <tr>
            <td class="textBold">TRANSAKSI OUT ONLINE :</td>
            <td><button type="button" class="btn btn-green" onclick="addProduk(); return false;">TAMBAH PRODUK</button>
            </td>
        </tr>
        <tr>
            <td>
            <td><input id="idf" value="0" type="hidden" /></td>
            <input id="idfh" value="0" type="hidden" />

            <td>
                <div id="det_plan"></div>
            </td>
            </td>
        </tr>
        <!-- <tr> 
            <td class="textBold">No_trans</td> 
            <td><input type="text"  name="no_trans" id="no_trans" value="<?php echo $transaction_->getNo_trans(); ?>" size="40"   ></td>
        </tr> -->


        <!-- <tr> 
            <td class="textBold">Type_trans</td> 
            <td><input type="text" style="text-align: right;" onkeypress="validate(event);"  name="type_trans" id="type_trans" value="<?php echo $transaction_->getType_trans(); ?>" size="16"   ></td>
        </tr>

        <tr> 
            <td class="textBold">QtyTotal</td> 
            <td><input type="text" style="text-align: right;" onkeypress="validate(event);"  name="qtyTotal" id="qtyTotal" value="<?php echo $transaction_->getQtyTotal(); ?>" size="16"   ></td>
        </tr>

        <tr> 
            <td class="textBold">QtyRelease</td> 
            <td><input type="text" style="text-align: right;" onkeypress="validate(event);"  name="qtyRelease" id="qtyRelease" value="<?php echo $transaction_->getQtyRelease(); ?>" size="16"   ></td>
        </tr>

        <tr> 
            <td class="textBold">Created_by</td> 
            <td><input type="text"  name="created_by" id="created_by" value="<?php echo $transaction_->getCreated_by(); ?>" size="40"   ></td>
        </tr>

        <tr> 
            <td class="textBold">Created_at</td> 
            <td><input type="text"  name="created_at" id="created_at" value="<?php echo $transaction_->getCreated_at(); ?>" size="10"   ></td>
        </tr>

        <tr> 
            <td class="textBold">Updated_by</td> 
            <td><input type="text"  name="updated_by" id="updated_by" value="<?php echo $transaction_->getUpdated_by(); ?>" size="40"   ></td>
        </tr>

        <tr> 
            <td class="textBold">Updated_at</td> 
            <td><input type="text"  name="updated_at" id="updated_at" value="<?php echo $transaction_->getUpdated_at(); ?>" size="10"   ></td>
        </tr> -->


        <tr>
            <td></td>
            <td><input type="submit" name="submit" value="Submit" class="btn btn-facebook"></td>
        </tr>
    </table>
</form>

<br>
<br>