<section class="content">
    <div class="box box-solid">
        <div class="box box-body">
            <div class="row">
                <table width="100%">

                    <tr>
                        <td colspan="2" width="100%">
                            <table class="encabezado5" width="100%">
                                <tr>
                                    <td style="font-size:12px"><?php echo $empresa->emp_nombre; ?></td>
                                    <td></td>
                                    <td> </td>
                                    <td></td>
                                    <td></td>
                                    <td rowspan="4" width="20%">
                                        <img src="<?php echo base_url().'imagenes/'.$empresa->emp_logo?>" width="120px"
                                            height="70px">
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-size:12px"><?php echo $empresa->emp_identificacion; ?> </td>
                                </tr>
                                <tr>
                                    <td style="font-size:12px">
                                        <?php echo $empresa->emi_ciudad."-".$empresa->emi_pais; ?> </td>
                                </tr>
                                <tr>
                                    <td style="font-size:12px"><?php echo "TELEFONO: " . $empresa->emi_telefono ?> </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

            </div>
            <br>

            <div class="row">
                <div class="col-12" id="detalle">
                    <?php echo $detalle?>
                </div>
            </div>
        </div>
    </div>


</section>

<style>
.subtotal {
    /*background: #A2CADF;*/
    background: #4682b4;
    color: #FFFFFF;
    font-weight: bolder;
    font-size: 14px;
}

.total {
    background: #3e5f8a;
    color: #FFFFFF;
    font-weight: bolder;
    font-size: 14px;
}

.local {
    font-weight: bolder;
}

.number {
    text-align: right;
}
</style>
<!-- jQuery 3 -->
<script src="<?php echo base_url(); ?>/assets/bower_components/jquery/dist/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="<?php echo base_url(); ?>/assets/bower_components/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script src="<?php echo base_url(); ?>/assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>/assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js">
</script>
<script src="<?php echo base_url(); ?>/assets/bower_components/accounting/accounting.js"></script>