<section class="content">
  <table width="100%">
    <tr>
        
        <td colspan="3" width="100%">
            <table id="encabezado1" width="100%">
                <tr>
                    <td rowspan="4" width="20%">
                       
                        <img src="<?php echo base_url().'imagenes/'.$orden->emp_logo?>"  width="250px" height="100px">
                    </td>
                  <!--   <th class="titulo" style="text-align:left;" width="80%"><?php echo $orden->emi_nombre?></th> -->
                </tr>
                <!-- <tr>
                    <th><?php echo $orden->emi_dir_establecimiento_emisor?></th>
                </tr>
                <tr>
                    <th><?php echo $orden->emi_ciudad .' - '. $orden->emi_pais ?></th>
                </tr>
                 <tr>
                    <th><?php echo $orden->emp_identificacion?></th>
                </tr>    
                <tr>
                    <td><br></td>
                </tr> -->    
                <tr>
                    <th></th>                    
                    <th class="titulo" style="text-align: right;">
                        <?php echo utf8_encode('Orden Compra N°: ')?>
                           <label style="color:red">
                             <?php echo $orden->orc_codigo?></th>
                        </label> 
                        </th>
                </tr>
                    
                <tr>
                    <td><br><br></td>
                </tr>  
            </table>
        </td>
    </tr> 
    <tr>
        <td colspan="3">
            <table id="encabezado1" width="100%">
                <tr>
                    <th class="titulo">Orden de Compra</th>
                </tr>  
                <tr>
                    <?php
                    $color='';
                    if($orden->orc_estado==22){
                        $color="color:red";
                    }
                    ?>
                    <th align="right"><label style="<?php echo $color?>">Estado: <?php echo $orden->est_descripcion?></label> </th>
                </tr>    
                <tr>
                    <td><br><br></td>
                </tr> 
            </table>
        </td>
    </tr>         
    
    <tr>
        <td colspan="3">
            <table id="encabezado2" width="100%">
                <tr>
                    <td><strong>Proveedor:</strong> <?php echo utf8_decode($orden->cli_raz_social)?></td>
                </tr>
                <tr>
                    <td><strong>Ruc:</strong> <?php echo utf8_decode($orden->cli_ced_ruc)?></td>
                </tr>
                <tr>
                    <td><strong>Telefono:</strong> <?php echo utf8_decode($orden->cli_telefono)?></td>
                </tr> 
                <tr>
                    <td><strong><?php echo utf8_encode('Dir.Entrega:') ?></strong> <?php echo $orden->orc_direccion_entrega?></td>
                </tr>    
                <tr>
                    <td><strong><?php echo utf8_encode('Conciones de pago:') ?></strong> <?php echo $orden->orc_condicion_pago?></td>
                </tr> 
                <tr>
                    <td><strong><?php echo utf8_encode('Fecha Solicituid:') ?></strong> <?php echo $orden->orc_fecha?></td>
                    <td><strong><?php echo utf8_encode('Fecha Entrega:') ?></strong> <?php echo $orden->orc_fecha_entrega?></td>
                </tr> 
                
            </table>
        </td>
    </tr>  
    <tr>
        <td><br></td>
    </tr>     
    <tr>
        <td colspan="3">
            <table id="detalle" width="100%">
                <thead>
                    <tr>
                        <th style="width:50px"> <?php echo utf8_encode('Código') ?></th>
                        <th><?php echo utf8_encode('Descripción') ?></th>
                        <th style="width:70px">Unidad</th>
                        <th style="width:70px">Cantidad</th>
                        <th style="width:70px">Val.Unit</th>
                        <th style="width:70px">Val.Total</th>
                    </tr> 
                </thead> 
                <tbody>
                    <?php
                    $dec=$dec->con_valor;
                    $dcc=$dcc->con_valor;
                    $total=0;
                    foreach ($cns_det as $det) {

                    ?>
                    <tr>
                        <td style="width:50px"><?php echo $det->mp_c?></td>
                        <td><?php echo substr(utf8_encode($det->mp_d),0,30)?></td>
                        <td><?php echo $det->mp_q?></td>
                        <td class="numerico"><?php echo number_format($det->orc_det_cant,$dcc)?></td>
                        <td class="numerico"><?php echo number_format($det->orc_det_vu,$dcc)?></td>
                        <td class="numerico"><?php echo number_format($det->orc_det_vt,$dcc)?></td>
                        
                    </tr>
                    
                    <?php
                    } 
                    ?> 
                    <tr>
                        <th colspan="3"></th>
                        <th colspan="2">Subtotal 12%</th>
                        <th  class="numerico"><?php echo number_format($orden->orc_sub12,$dec)?></th>
                    </tr>
                    <tr>
                        <th colspan="3"></th>
                        <th colspan="2">Subtotal 0%</th>
                        <th  class="numerico"><?php echo number_format($orden->orc_sub0,$dec)?></th>
                    </tr>
                    <tr>
                        <th colspan="3"></th>
                        <th colspan="2">Descuento <?php echo number_format($orden->orc_descuento,$dec)?>%</th>
                        <th  class="numerico"><?php echo number_format($orden->orc_descv,$dec)?></th>
                    </tr>
                    <tr>
                        <th colspan="3"></th>
                        <th colspan="2">Iva (12%)</th>
                        <th  class="numerico"><?php echo number_format($orden->orc_iva,$dec)?></th>
                    </tr>
                    <tr>
                        <th colspan="3"></th>
                        <th colspan="2">Total</th>
                        <th  class="numerico"><?php echo number_format($orden->orc_total,$dec)?></th>
                    </tr>
                </tbody> 
                
            </table>
        </td>
    </tr>    
</table>

<style type="text/css">
    *{
        font-size: 12px;
       /* font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;*/
       font-family: Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif; 
        text-align: left;
    }
    .numerico {
        text-align: right;
    }

    #encabezado1{
        border: none;
        text-align: center;
    }

    #encabezado2{
        border-top: 1px solid;
        border-bottom: 1px solid;
        text-align: left;
    }

    #detalle td, #detalle th{
        border-color: #ffffff;
        border-right: 1px solid #d7d7d7 !important;
        border-left: 1px solid #d7d7d7 !important;
    }

    #detalle tr:nth-child(2n-1) td {
      background: #DFDFDF !important;

    }

    .titulo,label{
        font-size: 15px;
        font-weight: bolder;
        text-align: center;
    }

</style>
