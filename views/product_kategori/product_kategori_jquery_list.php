<script type="text/javascript"> 
function deletedata(id, skip, search){ 
    var ask = confirm("Do you want to delete ID " + id + " ?");
    if (ask == true) {
        site = "index.php?model=product_kategori&action=deleteFormJQuery&skip=" + skip + "&search=" + search + "&id=" + id;
        target = "content";
        showMenu(target, site);
    }
}
function searchData() {
     var searchdata = document.getElementById("search").value;
     site =  'index.php?model=product_kategori&action=showAllJQuery&search='+searchdata;
     target = "content";
     showMenu(target, site);
}
$(document).ready(function(){
    $('#search').keypress(function(e) {
            if(e.which == 13) {
                searchData();
            }
    });
});
</script>

<h1>KATEGORI PRODUK</h1>
<div id="header_list">
</div>
<table width="95%" >
    <tr>
        <td align="left">
                    <img alt="Move First"  src="./img/icon/icon_move_first.gif" onclick="showMenu('content', 'index.php?model=product_kategori&action=showAllJQuery&search=<?php echo $search ?>');" >
                    <img alt="Move Previous" src="./img/icon/icon_move_prev.gif" onclick="showMenu('content', 'index.php?model=product_kategori&action=showAllJQuery&skip=<?php echo $previous ?>&search=<?php echo $search ?>');">
                     Page <?php echo $pageactive ?> / <?php echo $pagecount ?> 
                    <img alt="Move Next" src="./img/icon/icon_move_next.gif" onclick="showMenu('content', 'index.php?model=product_kategori&action=showAllJQuery&skip=<?php echo $next ?>&search=<?php echo $search ?>');" >
                    <img alt="Move Last" src="./img/icon/icon_move_last.gif" onclick="showMenu('content', 'index.php?model=product_kategori&action=showAllJQuery&skip=<?php echo $last ?>&search=<?php echo $search ?>');">
                    <a href="index.php?model=product_kategori&action=export&search=<?php echo $search ?>">Export</a>
                    <a href="index.php?model=product_kategori&action=printdata&search=<?php echo $search ?>" target="_"><img src="./images/icon_print.png"/></a>
        </td>
        <td align="right">
       <input type="text" name="search" id="search" value="<?php echo $search ?>" >&nbsp;&nbsp;<input type="button" class="btn btn-info btn-sm" value="find" onclick="searchData()">
<?php if($isadmin || $ispublic || $isentry){ ?>
       <input type="button" class="btn btn-warning btn-sm" value="new" name="new" onclick="showMenu('header_list', 'index.php?model=product_kategori&action=showFormJQuery')"> 
<?php } ?>

        </td>
    </tr>
</table>
<table border="1"  cellpadding="2" style="border-collapse: collapse;" width="95%">
    <tr>
        <th class="textBold">id</th>
        <th class="textBold">Kategori</th>
        <th class="textBold">Kategory Logo</th>
        <th class="textBold">Created By</th>
        <th class="textBold">Updated By</th>
        <th class="textBold">Created At</th>
        <th class="textBold">Updated At</th>
<td>&nbsp;</td>
    </tr>
    <?php
    
    $no = 1;
    
    if ($product_kategori_list != "") { 
        foreach($product_kategori_list as $product_kategori){
            $pi = $no + 1;
            $bg = ($pi%2 != 0) ? "#E1EDF4" : "#F0F0F0";
    ?>
            <tr bgcolor="<?php echo $bg;?>">
                <td><a href='#' onclick="showMenu('header_list', 'index.php?model=product_kategori&action=showDetailJQuery&id=<?php echo $product_kategori->getId();?>')"><?php echo $product_kategori->getId();?></a> </td>
            <td><?php echo $product_kategori->getKategori_name();?></td>
            <td>
                <?php if ($product_kategori->getKategori_image()!="") {?>
            
                <img src="./images_medium/<?php echo $product_kategori->getKategori_image();?>" width="150px" height="160px">
                <?php } else {?>
                <img src="./images/no-image-available-icon.png" width="150px" height="160px">
                <?php }?>
            </td>
            <td><?php echo $product_kategori->getCreated_by();?></td>
            <td><?php echo $product_kategori->getUpdated_by();?></td>
            <td><?php echo $product_kategori->getCreated_at();?></td>
            <td><?php echo $product_kategori->getUpdated_at();?></td>

                <td align="center" class="combobox">
  <?php if($isadmin || $ispublic || $isupdate){ ?>
                    <a href='#' onclick="showMenu('header_list', 'index.php?model=product_kategori&action=showFormJQuery&id=<?php echo $product_kategori->getid();?>&skip=<?php echo $skip ?>&search=<?php echo $search ?>')">[Edit]</a> | 
<?php } ?>
<?php if($isadmin || $ispublic || $isdelete){ ?>
                    <a href='#' onclick="deletedata('<?php echo $product_kategori->getId()?>','<?php echo $skip ?>','<?php echo $search ?>')">[Delete]</a>
<?php } ?>

                </td>
            </tr>
    <?php
            $no++;
        }
    }
    ?>
</table>
<br>