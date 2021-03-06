<!DOCTYPE html>
    <html>
    <head>
        <title>Digitalko Feltöltött Termékek</title>
        <meta charset="utf-8">
        <!---LINK--->
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/pagin.css">
        <link rel="stylesheet" type="text/css" href="dashboard/vendor/font-awesome/css/font-awesome.min.css">
        <!---LINK--->
        <!---SCRIPT--->
        <script src="js/bootstrap.min.js"></script>
        <script src="js/jquery-1.11.3.min.js"></script>
        <!---SCRIPT--->
     
        <!---Checkbox--->
        <script type="text/javascript">
        $(document).ready(function(){
            $('#checkBoxAll').click(function(){
                if ($(this).is(":checked"))
                    $('.chkCheckBoxId').prop('checked', true);
                else
                    $('.chkCheckBoxId').prop('checked', false);
            });
        });
       </script>
    </head> 
    <body>
        <?php
        define("ROW_PER_PAGE",3);
        require_once('include/db.php');
        ?>
        <?php   
    $search_keyword = '';
    if(isset($_POST['search']['keyword'])) {
        $search_keyword = $_POST['search']['keyword'];
    }
    //keresés\\
  $sql3 ="SELECT * FROM asd
    WHERE statusz='0' and (
   cikkszam LIKE :keyword 
   OR cikknev LIKE :keyword 
   OR garancia LIKE :keyword
   OR ar LIKE :keyword
   OR gyarto LIKE :keyword
    )";
    //keresés\\

    //<---Lapozó--->
    $per_page_html = '';
    $page = 1;
    $start=0;
     if(!empty($_POST["page"])) {
        $page = $_POST["page"];
        $start=($page-1) * ROW_PER_PAGE;
    }
    $limit=" limit " . $start . "," . ROW_PER_PAGE;
    $pagination_statement = $pdo_conn->prepare($sql3);
    $pagination_statement->bindValue(':keyword', '%' . $search_keyword . '%', PDO::PARAM_STR);
    $pagination_statement->execute();

    $row_count = $pagination_statement->rowCount();
    if(!empty($row_count)){
        $per_page_html .= "<div style='text-align:center;margin:20px 0px;'>";
        $page_count=ceil($row_count/ROW_PER_PAGE);
        if($page_count>1) {
            for($i=1;$i<=$page_count;$i++){
                if($i==$page){
                    $per_page_html .= '<input type="submit" name="page" value="' . $i . '" class="btn-page current" />';
                } else {
                    $per_page_html .= '<input type="submit" name="page" value="' . $i . '" class="btn-page" />';
                }
            }
        }
        $per_page_html .= "</div>";
    }
    $query = $sql3.$limit;
    $pdo_statement = $pdo_conn->prepare($query);
    $pdo_statement->bindValue(':keyword', '%' . $search_keyword . '%', PDO::PARAM_STR);
    $pdo_statement->execute();
    $result = $pdo_statement->fetchAll();
?>
  <!---Lapozó--->
        <div class="form-group">
            <a href="inventory.php"><button class="btn btn-primary btn-lg">Vissza a fő oldalra</button></a>  
            <a href="inventoryup.php"><img src="img/sasa.jpg" width="205px" class="img-responsive center-block"></a>  
        </div>
        <div class="form-group">
          <table class="container-fluid" width="100%">
            <thead>
              <th class="text-center">Keresés</th>
              <th class="text-center">Feltöltés</th>
              <th class="text-center">Törlés</th>
              <th class="text-center">Kihagy</th>
              <th class="text-center">Ár-tól</th>
              <th class="text-center">Ár-ig</th>
              <th class="text-center">Raktár</th>
              <th class="text-center">Garancia</th>
              <th class="text-center">Szűrés</th>
              <th class="text-center">Kategória</th>
              <th class="text-center">Gyártó</th>
              <th class="text-center">Feltöltve</th>
              <th class="text-center">Kihagyva</th>
              <th class="text-center">Törölve</th>
            </thead>
            <tfoot>
              <th class="text-center">Keresés</th>
              <th class="text-center">Feltöltés</th>
              <th class="text-center">Törlés</th>
              <th class="text-center">Kihagy</th>
              <th class="text-center">Ár-tól</th>
              <th class="text-center">Ár-ig</th>
              <th class="text-center">Raktár</th>
              <th class="text-center">Garancia</th>
              <th class="text-center">Szűrés</th>
              <th class="text-center">Kategória</th>
              <th class="text-center">Gyártó</th>
              <th class="text-center">Feltöltve</th>
              <th class="text-center">Kihagyva</th>
              <th class="text-center">Törölve</th>
            </tfoot>
            <form name='frmSearch' action='' method='post'>
            <td><input type='text' name='search[keyword]' placeholder="keresés...." class="form-control" value="<?php echo $search_keyword; ?>" id='keyword'></td>
            </form>
          <form method="POST" class="form-group">
            <td><input type='button' class='btn btn-secondary btn-lg' name="feltolt" value="Feltölt"></td>
            <td><input type='submit' class='btn btn-secondary btn-lg' name="kihagy" value="Kihagy"></td>
             <td><input type='submit' class='btn btn-danger btn-lg' name="torol" value="Töröl"></td>
            <td><input type="text" class="form-control" name="min" placeholder="Ár-tól"></td>
            <td><input type="text" class="form-control name="max" placeholder="Ár-ig"></td>
            <td><input type="checkbox" class="form-control" name="rak"></td>
            <td><select class="form-control form-control-sm" name="gar">
            <option value="">Garancia</option>
                          <?php
                          while ($row = mysqli_fetch_array($resultc)) 
                          {
                          echo "<option>" . $row['garancia'] . "</option>";
                          }
                          ?>        
                          </select></td>
           <td><input type="submit"  class="btn btn-info btn-lg" name="szuk" value="Szűrés"></td>
                            <td><select class="form-control form-control-sm" id="select1">
                            <option>ÖSSZES KATEGÓRIA</option>
                            <?php
                            while ($row = mysqli_fetch_array($resulta)) 
                            {
                                 echo "<option value=" . $row['nev'] . ">" . $row['nev'] . "</option>";
                            }
                            ?>       
                            </select></td>
                            <td><select class="form-control form-control-sm" id="select2">
                                <option>ÖSSZES MÁRKA</option>
                            <?php

                            while ($row = mysqli_fetch_array($resultb)) 
                            {
                                 echo "<option value=" . $row['nev'] . ">" . $row['nev'] . "</option>";
                            }
                            ?>        
                            </select></td>
                            <td><input type="button" class='btn btn-success btn-lg' value="Feltöltve" onclick="window.location.href='inventoryup.php'" /></td>
                            <td><input type="button" class='btn btn-warning btn-lg' value="Kihagyva" onclick="window.location.href='inventorydown.php'" /></td>
                            <td><input type="button" class='btn btn-danger btn-lg' value="Törölve" onclick="window.location.href='inventorydel.php'" /></td>
           </table>
        </div>
        <div class="form-group">
        </div>
            <table id="example" class="table-bordered table-striped table-hover container-fluid text-center" width="100%">
                <thead>
                    <tr>
                        <th class="text-center"><input type='checkbox' id='checkBoxAll' /></th>
                        <th class='text-center'>Cikkszám</th>
                        <th class='text-center'>Gyártó</th>
                        <th class='text-center'>Cikknév</th>
                        <th class='text-center'>Cikkcsop</th>
                        <th class='text-center'>Cikkfajta</th>
                        <th class='text-center'>ÁR</th>
                        <th class='text-center'>Garancia</th>
                        <th class='text-center'>Raktár</th>
                        <th class='text-center'>Kép</th>
                        <th class='text-center'>Cikkszám</th>
                        <th class='text-center'>Cikknév</th>
                        <th class='text-center'>Garancia</th>
                        <th class='text-center'>Tömeg</th>
                        <th class='text-center'>X-mm</th>
                        <th class='text-center'>Y-mm</th>
                        <th class='text-center'>Z-mm</th>
                        <th class='text-center'>Megjegy</th>
                        <th class='text-center'>Kategória</th>
                        <th class='text-center'>Gyártó</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th></th>
                        <th class='text-center'>Cikkszám</th>
                        <th class='text-center'>Gyártó</th>
                        <th class='text-center'>Cikknév</th>
                        <th class='text-center'>Cikkcsop</th>
                        <th class='text-center'>Cikkfajta</th>
                        <th class='text-center'>ÁR</th>
                        <th class='text-center'>Garancia</th>
                        <th class='text-center'>Raktár</th>
                        <th class='text-center'>Kép</th>
                        <th class='text-center'>Cikkszám</th>
                        <th class='text-center'>Cikknév</th>
                        <th class='text-center'>Garancia</th>
                        <th class='text-center'>Tömeg</th>
                        <th class='text-center'>X-mm</th>
                        <th class='text-center'>Y-mm</th>
                        <th class='text-center'>Z-mm</th>
                        <th class='text-center'>Megjegy</th>
                        <th class='text-center'>Kategória</th>
                        <th class='text-center'>Gyártó</th>
                    </tr>
                 </tfoot>
                 <tbody>
                
                    <!---Megjelenítés--->
                <?php 

                        if(!empty($result)) 
                        { 
                            foreach($result as $row) 
                            {
                            $id = $row['id'];
                            $cikkszam = $row['cikkszam'];
                            $gyarto = $row['gyarto'];
                            $cikknev = $row['cikknev'];
                            $cikkfajta = $row['cikkfajta'];
                            $ar = $row['ar'];
                            $garancia = $row["garancia"];
                            $keszlet = $row['keszlet'];
                            $leiras = $row['leiras'];
                            $cikkcsopnev=$row['cikkcsopnev'];
                            $kepek=$row['kepek'];
                        ?>
                          <tr class='table-row'>
                          <td><input type="checkbox" class="chkCheckBoxId" value="<?php echo $row['id']; ?>" name="id"/></td>
                          <td name="cikkszam[]" value="cikkszam" id="cikkszam"> <?php echo $row['cikkszam']; ?> </td>
                          <td title="<?php echo $row['gyarto'];?>" id="rov"><?php echo $row['gyarto']; ?></td>
                          <td title="<?php echo $row['cikknev'];?>" id="rov"><?php echo $row['cikknev']; ?></td>
                          <td title="<?php echo $row['cikkcsopnev'];?>" id="rov"><?php echo $row['cikkcsopnev']; ?></td>
                          <td title="<?php echo $row['cikkfajta'];?>" id="rov"><?php echo $row['cikkfajta']; ?></td>
                          <td title="<?php echo $row['ar']; ?>"><?php echo $row['ar']; ?></td>
                          <td title="<?php echo $row['garancia'];?>" id="rov"><?php echo $row['garancia']; ?></td>
                          <td title="<?php echo $row['keszlet'];?>" id="rov"><?php echo $row['keszlet']; ?></td>
                          <td title="<?php echo $row['leiras'];?>" id="rov"> <a target="_blank" href="<?php echo $kepek;?>"><img src="<?php echo $kepek;?>" width="50px" height="50px" alt="X" class="img-responsive"></a></td>
                          <td id="rov"><textarea name="cikkszam" id="cikkszam" class="form-control"><?php echo $row['cikkszam'];?></textarea></td>
                          <td><textarea name="cikknev" id="cikknev" class="form-control"><?php echo $row['cikknev'];?></textarea></td>
                          <td id="rov"><textarea name="garancia" id="garancia" class="form-control"><?php echo $row['garancia'];?></textarea></td>
                          <td id="rov"><textarea name="tomeg" id="tomeg" class="form-control"></textarea></td>
                          <td id="rov"><textarea name="x" id="x" class="form-control"></textarea></td>
                          <td id="rov"><textarea name="y" id="y" class="form-control"></textarea></td>
                          <td id="rov"><textarea name="z" id="z" class="form-control"></textarea></td>
                          <td id="rov"><textarea name="megjegyzes" id="megjegyzes" class="form-control"></textarea></td>
                          <td id="nem"><textarea name="leiras" id="leiras"><?php echo $row['leiras'];?></textarea></td>
                          <td id="nem"><textarea name="kep" id="kep"><?php echo $row['kepek'];?></textarea></td>
                          <td id="nem"><textarea name="ar" id="ar"><?php echo $row['ar'];?></textarea></td>
                          <td id="rov">
                          <select name="kategoria" class="select">
                          <?php
                          require "include/db.php";
                          while ($row = mysqli_fetch_array($resulta)) 
                          {
                          echo "<option value=" . $row['nev'] . ">" . $row['nev'] . "</option>";
                          }
                          ?>       
                          <select></td>
                          <td id="rov">
                          <select name="gyarto" class="select2">
                          <?php
                          while ($row = mysqli_fetch_array($resultb)) 
                          {
                          echo "<option value=" . $row['nev'] . ">" . $row['nev'] . "</option>";
                          }
                          ?>        
                          </select></td>
                          </tr>
                     <?php
                    }
                    }
                    ?>
                    <?php
                    /*require "include/db.php";
                    if(isset($_POST['feltolt']))
                    {
                        $cikkszam = $_POST['cikkszam'];
                          $cikknev = $_POST["cikknev"];
                          $ar = $_POST["ar"];
                          $gyarto = $_POST["gyarto"];
                           $kategoria = $_POST["kategoria"];
                           $garancia = $_POST["garancia"];
                           $leiras = $_POST["leiras"];
                           $kep = $_POST["kep"];
                           $tomeg = $_POST["tomeg"];
                           $x = $_POST["x"];
                           $y = $_POST["y"];
                           $z = $_POST["z"];
                           $megjegyzes = $_POST["megjegyzes"];

                          $sql3 = "INSERT INTO test
                            (cikkszam,cikknev,ar,gyarto,kategoria,garancia,leiras,kep,tomeg,x,y,z,megjegyzes)
                            VALUES (
                            '$cikkszam',
                            '$cikknev',
                            '$ar',
                            '$gyarto',
                            '$kategoria',
                            '$garancia',
                            '$leiras',
                            '$kep',
                            '$tomeg',
                            '$x',
                            '$y',
                            '$z',
                            '$megjegyzes'
                            )";

                        if ($mysqli->query($sql3) === TRUE)
                         {
                        } 
                        else 
                        {
                            echo "<H1>TÖLTSD KI MINDET</H1>";
                        }
                    }
                    //Update Items
                        if(isset($_POST['feltolt'])){
                            $id = $_POST['id'];
                            $sql3 = "UPDATE asd SET 
                                statusz='0'
                                WHERE id='$id' ";
                            if ($mysqli->query($sql3) === TRUE) {
                                echo '<script>window.location.href="inventory.php"</script>';
                            } else {
                                echo "Error updating record: " . $mysqli->error;
                            }
                        }*/
                          if(isset($_POST['torol']))
                          {
                            // sql to delete a record
                             $id = $_POST['id'];
                            $sql3 = "UPDATE asd SET 
                                statusz='1'
                                WHERE id='$id' ";
                            if ($mysqli->query($sql3) === TRUE) {
                                echo '<script>window.location.href="inventoryup.php"</script>';
                            } else {
                                echo "Error updating record: " . $mysqli->error;
                            }
                    }/*
                    if(isset($_POST['kihagy'])){
                            // sql to delete a record
                            $id = $_POST['id'];
                            $sql3 = "UPDATE asd SET 
                                statusz='2'
                                WHERE id='$id' ";
                            if ($mysqli->query($sql3) === TRUE) {
                                echo '<script>window.location.href="inventoryup.php"</script>';
                            } else {
                                echo "Error updating record: " . $mysqli->error;
                            }
                    } */
                    ?>
                        
                        <script>
                            <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
                            <script type="text/javascript">
                        $(function() {
                          var setValue = "2";

                          /* Initialize the select's to base value */
                          $('select').each(function() {
                              $(this).val();
                          });

                          /* Change the values of select's when anyone of the select changes */
                          $('#select1').change(function() {
                            setValue = $(this).val();

                            $('.select').each(function() {
                                $(this).val(setValue);
                            });
                          });

                        });
                        </script>
                            <script type="text/javascript">
                        $(function() {
                          var setValue = "2";

                          /* Initialize the select's to base value */
                          $('select').each(function() {
                              $(this).val();
                          });

                          /* Change the values of select's when anyone of the select changes */
                          $('#select2').change(function() {
                            setValue = $(this).val();

                            $('.select2').each(function() {
                                $(this).val(setValue);
                            });
                          });

                        });
                        </script>
                       
                     <!---Megjelenítés--->
     
</tbody>
</table>
</form>     
<form method="get" action="">
<?php echo $per_page_html; ?>
</form>
</html>
</body>
