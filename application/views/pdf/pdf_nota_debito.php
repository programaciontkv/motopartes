<section class="content">
  <table width="100%">
    <tr>
        <td>
            <table id="login">
                <tr>
                    <td><img src="<?php echo base_url().'imagenes/'.$nota->emp_logo?>" width="250px" height="100px"></td>
                </tr>    
            </table>
        </td>
        <td rowspan="2" width="52%">
            <table id="encabezado2" width="100%">
                
                <tr>
                    <th class="titulo" style=" border-collapse: separate;" colspan="2">NOTA DE DEBITO</th>
                </tr>    
                <tr>
                    <th colspan="2" style="font-size:20px">No. <?php echo $nota->ndb_numero?></th>
                </tr>  
                <tr>
                    <td><strong> <?php echo utf8_encode('Fecha de Emisión:') ?> </strong><?php echo $nota->ndb_fecha_emision?></td>
                </tr>    
                <tr>
                    <th colspan="2"><?php echo utf8_encode('Número de Autorización:') ?> </th>
                </tr>    
                <tr>
                    <th colspan="2"><?php echo $nota->ndb_autorizacion?></th>
                </tr>    
                <tr>
                    <th> <?php echo utf8_encode('Fecha y hora de autorización:') ?></th>
                </tr>    
                <tr>    
                    <td><?php echo $nota->ndb_fec_hora_aut?></td>
                </tr>    
                <tr>
                    <?php 
                    switch ($ambiente->con_valor) {
                      case 0:
                        $amb='';
                        break;
                      case 1:
                        $amb='Pruebas';
                        break;
                      case 2:
                        $amb='Produccion';
                        break;  
                    }
                    ?>
                    <td><strong>Ambiente: </strong><?php echo $amb?></td>
                </tr>    
                <tr>
                    <td><strong> <?php echo utf8_encode('Emisión:') ?> </strong>Normal</td>
                </tr>    
                <tr>
                    <th>Clave de acceso:</th>
                    
                </tr>
                <tr> 
                    <td colspan="2">
                        <img src="<?php echo base_url();?>barcodes/<?php echo $nota->ndb_clave_acceso?>.png" alt="" width="350px" height="70px">
                    </td>
                </tr> 
                <tr>
                    <th colspan="2" style="font-size: 11px; text-align: center"><?php echo $nota->ndb_clave_acceso?></th>
                 </tr>    
            </table>
        </td>
    </tr>    
    <tr>    
        <td width="48%" valign="bottom">
            <table id="encabezado1" width="100%">
                <tr>
                    <th class="titulo" colspan="2"><?php echo $nota->emp_nombre?></th>
                </tr>
                <tr>
                    <td class="titulo" colspan="2"><?php echo ucwords(strtolower($nota->emi_nombre))?></td>
                </tr>
                <tr>
                    <th class="titulo"><?php echo $nota->emp_identificacion?></th>
                </tr>    
                <tr >
                  
                   <td colspan="2"><?php echo trim(ucwords(strtolower($nota->emp_direccion)))?></td>
                   
                </tr>
                <tr>
                    <td colspan="2"><strong><?php echo utf8_encode('Teléfono:') ?> </strong> <?php echo ucwords(strtolower($nota->emp_telefono))?></td>
                    <th></th>
                </tr> 
                <tr>
                    <td colspan="2"><strong>Email: </strong> <?php echo strtolower($nota->emp_email)?></td>
                    <th></th>
                </tr> 
                <?php 
                if(!empty($nota->emp_contribuyente_especial)){
                ?>
                <tr>
                    <th colspan="2">Cotribuyente Especial Nro:</th>
                    <td><?php echo $nota->emp_contribuyente_especial?></td>
                </tr>
                <?php 
                }
                ?>  

                <tr>
                    <td colspan="2"><strong>Obligado a llevar contabilidad: </strong> <?php echo ucwords(strtolower($nota->emp_obligado_llevar_contabilidad))?></td>
                    <th></th>
                </tr>  
                <tr>
                    <th colspan="2"><?php echo trim($nota->emp_leyenda_sri)?></th>
                </tr>    
                  
            </table>
        </td>
        <tr>
            <td><br></td>
        </tr>

    </tr>
    <tr>
        <td colspan="2">
            <table id="encabezado3" width="100%">
                <tr>
                    <td><strong><?php echo utf8_encode('Razón Social: ')?></strong>  <?php echo ucwords(strtolower($nota->ndb_nombre))?></td>
                </tr>    
                <tr>
                    <td><strong>Email: </strong><?php echo strtolower($nota->ndb_email)?></td>
                    <td><strong> <?php echo utf8_encode('Cédula/RUC:') ?> </strong><?php echo $nota->ndb_identificacion?></td>
                </tr>    
                <tr>
                    <td><strong><?php echo utf8_encode('Dirección:')?> </strong><?php echo ucwords(strtolower($nota->ndb_direccion))?></td>
                    <td><strong><?php echo utf8_encode('Teléfono:')?> </strong><?php echo $nota->cli_telefono?></td>
                </tr>
                <tr>
                    <th colspan="2" style="text-align:center">_____________________________________________________________________________________________</th>
                </tr>   
                <tr>
                    <td><strong>Comprobante que se modifica:</strong> <?php echo $nota->ndb_num_comp_modifica?></td>
                </tr>   
                <tr>    
                    <td><strong>Fecha <?php echo utf8_encode('emisión')?> (Comprobante a modificar):</strong> <?php echo $nota->ndb_fecha_emi_comp?></td>
                </tr>    
                
            </table>
        </td>
    </tr>      
    <tr>
        <td colspan="2">
            <table id="detalle" width="100%">
                <thead>
                    <tr>
                        <th><?php echo utf8_encode('Razón de la modicación')?></th>
                        <th style="width:70px">Valor</th>
                    </tr> 
                </thead> 
                <tbody>
                    <?php
                    $dec=$dec->con_valor;
                    $dcc=$dcc->con_valor;
                    foreach ($cns_det as $det) {
                    ?>
                    <tr>
                        <td><?php echo ucwords(strtolower($det->dnd_descripcion))?></td>
                        <td width="70px" class="numerico"><?php echo number_format($det->dnd_precio_total,$dec)?></td>
                    </tr>
                    <?php
                     } 
                    ?> 
                </tbody>
                <tbody>
                    <tr>
                        
                        <td class="label"><strong>Subtotal 12%</strong></td>
                        <td class="numerico"><?php echo number_format($nota->ndb_subtotal12,$dec)?></td>
                    </tr>
                    <tr>
                        <td class="label"><strong>Subtotal 0%</strong></td>
                        <td class="numerico"><?php echo number_format($nota->ndb_subtotal0,$dec)?></td>
                    </tr>
                    <tr>
                        <td class="label"><strong>Subtotal no objeto de IVA</strong></td>
                        <td class="numerico"><?php echo number_format($nota->ndb_subtotal_no_iva,$dec)?></td>
                    </tr>
                    <tr>
                        <td class="label"><strong>Subtotal excento IVA</strong></td>
                        <td class="numerico"><?php echo number_format($nota->ndb_subtotal_ex_iva,$dec)?></td>
                    </tr>
                    
                    <tr>
                        <td class="label"><strong>Subtotal sin impuestos</strong></td>
                        <td class="numerico"><?php echo number_format($nota->ndb_subtotal,$dec)?></td>
                    </tr>
                    <tr>
                        <td class="label"><strong>IVA 12%</strong></td>
                        <td class="numerico"><?php echo number_format($nota->ndb_total_iva,$dec)?></td>
                    </tr>
                    <tr>
                        <td class="label"><strong>VALOR TOTAL</strong></td>
                        <td class="numerico"><?php echo number_format($nota->ndb_total_valor,$dec)?></td>
                    </tr>
                    
                    
                </tbody>   
            </table>
        </td>
    </tr>    
</table>

<style type="text/css">
    *{
        font-size: 14px;
        /*font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;*/
       /* font-family: 'Source Sans Pro';*/
       font-family: Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif; 

    }
    
    .label {
        text-align: right;
    }

    .numerico {
        text-align: right;
    }

    #encabezado3 {
        border-top: 1px solid;
        border-bottom: 1px solid;
        text-align: left;
    }

    /*#detalle{
        border-collapse: collapse;
    }*/

    

    #encabezado1 td, #encabezado1 th, #encabezado2 td, #encabezado2 th, #encabezado3 td, #encabezado3 th{
        text-align: left;
    }
    #detalle td, #detalle th{
        /*border: 1px solid;
        border-color: #ffffff;
         background:#d7d7d7; */
        border-right: 2px solid #d7d7d7 !important;
        border-top: 2px solid #d7d7d7 !important;
        border-bottom: 2px solid #d7d7d7 !important;
        border-left: 2px solid #d7d7d7 !important;
        justify-content: right;
    }

    #detalle tr:nth-child(2n-1) td ,#detalle tr:nth-child(2n-1) th {
      background: #DFDFDF !important;

    }

    #info td, #info th, #info tr{
        border: none;
        border-right: 2px solid #ffffff !important;
        border-top: 2px solid #ffffff !important;
        border-bottom: 2px solid #ffffff !important;
        border-left: 2px solid #ffffff !important;

    }

    #info{
        background: white !important;
    }

    #pagos{
        border-top: 1px  solid;
    }

    .titulo{
        font-size: 15px;
    }



</style>


         

