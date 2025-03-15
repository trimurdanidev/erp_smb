<script language="javascript" type="text/javascript">
    (function () {
        $('form').ajaxForm({
            beforeSubmit: function () {
            },
            complete: function (xhr) {
                Swal.fire($.trim(xhr.responseText));
                showMenu('content', 'index.php?model=report_query&action=showAllJQuery&skip=<?php echo $skip ?>&search=<?php echo $search ?>');
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


<form name="frmreport_query" id="frmreport_query" method="post"
    action="index.php?model=report_query&action=saveFormJQuery">
    <table>
        <tr>
            <td class="textBold">Id</td>
            <td><input type="text" class="form form-control" style="text-align: right;background-color:silver;"
                    onkeypress="validate(event);" name="id" id="id" value="<?php echo $report_query_->getId(); ?>"
                    size="11" ReadOnly placeholder="autonumber"></td>
        </tr>

        <tr>
            <td class="textBold">Report Name</td>
            <td><input type="text" class="form form-control" name="reportname" id="reportname"
                    value="<?php echo $report_query_->getReportname(); ?>" size="40"></td>
        </tr>

        <tr>
            <td class="textBold">Header</td>
            <td><input type="text" class="form form-control" name="header" id="header"
                    value="<?php echo $report_query_->getHeader(); ?>" size="40"></td>
        </tr>

        <tr>
            <td class="textBold">Query</td>
            <td><textarea class="form form-control" name="query" id="query" cols="50"
                    rows="7"><?php echo $report_query_->getQuery(); ?></textarea></td>
        </tr>

        <tr>
            <td class="textBold">is Crosstab</td>
            <td>
                <!-- <input type="text" style="text-align: right;" onkeypress="validate(event);"  name="crosstab" id="crosstab" value="<?php //echo $report_query_->getCrosstab(); ?>" size="11"   > -->
                <select name="crosstab" id="crosstab" class="form form-control">
                    <option value="">Pilih Set Crosstab</option>
                    <option value="0" <?php echo $report_query_->getCrosstab() == 0 ? "selected" : "" ?>>Tidak</option>
                    <option value="1" <?php echo $report_query_->getCrosstab() == 1 ? "selected" : "" ?>>Ya</option>
                </select>
            </td>
        </tr>

        <tr>
            <td class="textBold">is Total</td>
            <td>
                <!-- <input type="text" style="text-align: right;" onkeypress="validate(event);"  name="total" id="total" value="<?php //echo $report_query_->getTotal(); ?>" size="11"   > -->
                <select name="total" id="total" class="form form-control">
                    <option value="">Pilih Set Total</option>
                    <option value="0" <?php echo $report_query_->getTotal() == 0 ? "selected" : "" ?>>Tidak</option>
                    <option value="1" <?php echo $report_query_->getTotal() == 1 ? "selected" : "" ?>>Ya</option>
                </select>
            </td>
        </tr>

        <tr>
            <td class="textBold">is Subtotal</td>
            <td>
                <!-- <input type="text" style="text-align: right;" onkeypress="validate(event);"  name="subtotal" id="subtotal" value="<?php //echo $report_query_->getSubtotal(); ?>" size="11"   > -->
                <select name="subtotal" id="subtotal" class="form form-control">
                    <option value="">Pilih Set Subtotal</option>
                    <option value="0" <?php echo $report_query_->getSubtotal() == 0 ? "selected" : "" ?>>Tidak</option>
                    <option value="1" <?php echo $report_query_->getSubtotal() == 1 ? "selected" : "" ?>>Ya</option>
                </select>
            </td>
        </tr>

        <tr>
            <td class="textBold">Header Table Show</td>
            <td>
                <select name="headertableshow" id="headertableshow" class="form form-control">
                    <option value="">Pilih Show Header Table</option>
                    <option value="0" <?php echo $report_query_->getHeadertableshow() == 0 ? "selected" : "" ?>>Tidak
                    </option>
                    <option value="1" <?php echo $report_query_->getHeadertableshow() == 1 ? "selected" : "" ?>>Ya
                    </option>
                </select>
            </td>
        </tr>

        <tr>
            <td class="textBold">Footer Table Show</td>
            <td>
                <select name="footertableshow" id="footertableshow" class="form form-control">
                    <option value="">Pilih Show Footer Table</option>
                    <option value="0" <?php echo $report_query_->getFootertableshow() == 0 ? "selected" : "" ?>>Tidak
                    </option>
                    <option value="1" <?php echo $report_query_->getFootertableshow() == 1 ? "selected" : "" ?>>Ya
                    </option>
                </select>
            </td>
        </tr>

        <tr>
            <td class="textBold">Total Query Id</td>
            <td>
                <input type="text" class="form form-control" style="text-align: right;" onkeypress="validate(event);"
                    name="totalqueryid" id="totalqueryid" value="<?php echo $report_query_->getTotalqueryid(); ?>"
                    size="11">
                <!-- <select name="subtotal" id="subtotal" class="form form-control">
                    <option value="">Pilih Set Subtotal</option>
                    <option value="0" <?php //echo $report_query_->getSubtotal()==0?"selected":"" ?> >Tidak</option>
                    <option value="1" <?php //echo $report_query_->getSubtotal()==1?"selected":"" ?>>Ya</option>
                </select> -->
            </td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" name="submit" value="Submit" class="btn btn-danger btn-sm"></td>
        </tr>
    </table>
</form>

<br>
<br>