<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<section class="content-header">
      <h1>
        Registro de Facturas <?php echo $titulo?>
      </h1>
</section>
<section class="content">
  <div class="row">
    <div class="col-md-12">
          <?php 
          $dec=$dec->con_valor;
          $dcc=$dcc->con_valor;
          $ctrl_inv=$ctrl_inv->con_valor;
          if($ctaxpagar==0){
            $read_cxp='';
            $hid_cxp='';
            $sms_cxp='';
          }else{
            $read_cxp='readonly';
            $hid_cxp="style='display:none;'";
            $sms_cxp='*Factura ya tiene registrado pagos no se pueden modificar los valores monetarios';
          }

          if($retencion==0){
            $read_ret='';
            $hid_ret='';
            $sms_ret='';
          }else{
            $read_ret='readonly';
            $hid_ret="style='display:none;'";
            $sms_ret='*Apertura de Retencion no se pueden modificar los valores monetarios';
          }


          if($conf_as==1){
            $col_obs='7';
            $hidden_as='hidden';
          }else{
            $col_obs='8';
            $hidden_as='';
          }


          if($this->session->flashdata('error')){
            ?>
        <div class="alert alert-danger alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <p><i class="icon fa fa-ban"></i> <?php echo $this->session->flashdata('error')?></p>
        </div>
        <?php
          }
        ?>
        <div class="box box-primary">
          <form id="frm_save" role="form" action="<?php echo $action?>" method="post" autocomplete="off" enctype="multipart/form-data">
            <div class="box-body" >
            <table class="table col-sm-10" border="0">
              <tr>
                <td class="col-sm-5">
                  <div class="box-body">
                  <div class="panel panel-default col-sm-14">
                  <table class="table">
                    <tr>
                      <?php
                      $min = date('Y-m-d');
                      ?>
                       <td colspan="2"><label>Fecha Emision:</label></td>
                        <td>
                        <div class="form-group <?php if(form_error('reg_femision')!=''){ echo 'has-error';}?> ">
                                <input type="date" onchange="calculo_fecha(this)" max="<?php echo $min ?>" class="form-control" name="reg_femision" id="reg_femision" value="<?php if(validation_errors()!=''){ echo set_value('reg_femision');}else{ echo $factura->reg_femision;}?>" <?php echo $read_ret?> <?php echo $read_cxp?> onchange="calculo_fecha()">
                            <?php echo form_error("reg_femision","<span class='help-block'>","</span>");?>
                          </div>
                          <input type="hidden" class="form-control" name="emp_id" id="emp_id" value="<?php if(validation_errors()!=''){ echo set_value('emp_id');}else{ echo $factura->emp_id;}?>">
                           <input type="hidden" class="form-control" name="orden_compra" id="orden_compra" value="<?php echo $orden_compra;?>">
                           <input type="hidden" class="form-control" name="ingreso" id="ingreso" value="<?php echo $ingreso;?>">
                          </div>
                        </td> 
                    </tr>    
                    <tr>
                      <td colspan="2"><label>Tipo de Documento</label></td>
                      <td>
                        <div class="form-group ">
                          <select name="reg_tipo_documento"  id="reg_tipo_documento" class="form-control" onchange="doc_duplicado()">
                            <option value="0">SELECCIONE</option>
                              <?php
                            if(!empty($tipo_documentos)){
                              foreach ($tipo_documentos as $tp_dc) {
                            ?>
                            <option value="<?php echo $tp_dc->tdc_id?>"><?php echo $tp_dc->tdc_codigo .' - '. $tp_dc->tdc_descripcion?></option>
                            <?php
                              }
                            }
                            ?>
                          </select>
                          <script type="text/javascript">
                            var tipodoc='<?php echo $factura->reg_tipo_documento;?>';
                                    reg_tipo_documento.value=tipodoc;
                          </script>
                          <?php echo form_error("reg_tipo_documento","<span class='help-block'>","</span>");?>
                        </div>
                      </td>    
                    </tr>
                    <tr>
                      <td colspan="2"><label>Sustento</label></td>
                      <td>
                        <div class="form-group ">
                        <select name="reg_sustento"  id="reg_sustento" class="form-control">
                          <option value="0">SELECCIONE</option>
                            <?php
                            if(!empty($cns_sustento)){
                              foreach ($cns_sustento as $rst_sust) {
                            ?>
                              <option value="<?php echo $rst_sust->sus_id?>"><?php echo $rst_sust->sus_codigo .' - '. $rst_sust->sus_descripcion?></option>
                            <?php
                              }
                            }
                            ?>
                        </select>
                        <script type="text/javascript">
                          var sust='<?php echo $factura->reg_sustento;?>';
                                    reg_sustento.value=sust;
                        </script>
                        <?php echo form_error("reg_sustento","<span class='help-block'>","</span>");?>
                        </div>
                    </td>    
                  </tr>
                  <tr>
                    <td style="width: 150px;" colspan="2"><label>Numero de Documento:</label></td>
                    <td >
                      <div class="form-group <?php if(form_error('reg_num_documento')!=''){ echo 'has-error';}?>">
                                <input  type="hidden" class="form-control documento" name="reg_num_documento" id="reg_num_documento" value="<?php if(validation_errors()!=''){ echo set_value('reg_num_documento');}else{ echo $factura->reg_num_documento;}?>" onchange="num_factura(this)"  maxlength="17" <?php echo $read_ret ?> <?php echo $read_cxp?>>
                                    <?php echo form_error("reg_num_documento","<span class='help-block'>","</span>");?>
                      <?php
                      if ($factura->reg_num_documento!='' ) {
                         $dn=explode("-",$factura->reg_num_documento);
                      }else{
                        $dn[0]='';
                        $dn[1]='';
                        $dn[2]='';
                      }

                     
                      ?>  
                      
                        <div class="row">
                          
                             <div class="col-xs-3">
                          <input type="text" class="form-control" id="reg_num_documento0" size="3" maxlength="3" value="<?php echo $dn[0] ?>" onkeyup=" this.value = this.value.replace(/[^0-9]/, '')" onchange="completar_ceros(this, 0)" <?php echo $read_ret ?> <?php echo $read_cxp?>/>
                        </div>
                      <div class="col-xs-1" style="width: 0.5%;"> <p>-</p> </div> 
                          <div class="col-xs-3">
                            <input type="text"  class="form-control" id="reg_num_documento1"  maxlength="3" value="<?php echo $dn[1] ?>" onkeyup=" this.value = this.value.replace(/[^0-9]/, '')" onchange="completar_ceros(this, 0)" <?php echo $read_ret ?> <?php echo $read_cxp?>/>
                          </div>
                          <div class="col-xs-1" style="width: 1%;"> <p>-</p> </div> 
                        <div class="col-xs-4">
                          <input type="text"  class="form-control" id="reg_num_documento2"  maxlength="9" value="<?php echo $dn[2] ?>" onkeyup=" this.value = this.value.replace(/[^0-9]/, '')" onchange="completar_ceros(this, 1)" <?php echo $read_ret ?> <?php echo $read_cxp?>/>
                        </div>
                      </div>  
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2"><label>Nro. de Autorizacion:</label></td>
                    <td >
                      <div class="form-group <?php if(form_error('reg_num_autorizacion')!=''){ echo 'has-error';}?> ">
                      <input type="text" class="form-control numerico" name="reg_num_autorizacion" id="reg_num_autorizacion" value="<?php if(validation_errors()!=''){ echo set_value('reg_num_autorizacion');}else{ echo $factura->reg_num_autorizacion;}?>" onchange="validar_autorizacion()" maxlength="49">
                        <?php echo form_error("reg_num_autorizacion","<span class='help-block'>","</span>");?>
                      </div>
                    </td>
                  </tr>
                  <tr hidden>
                    <td colspan="2"><label>Tipo Proveedor</label></td>
                      <td>
                        <div class="form-group ">
                        <select name="reg_tpcliente"  id="reg_tpcliente" class="form-control">
                          <option value="0">SELECCIONE</option>
                          <option value="LOCAL">LOCAL</option>
                          <option value="EXTRANJERO">EXTRANJERO</option>
                        </select>
                        <script type="text/javascript">
                          var tprov='<?php echo $factura->reg_tpcliente;?>';
                                      reg_tpcliente.value=tprov;
                        </script>
                        <?php echo form_error("reg_tpcliente","<span class='help-block'>","</span>");?>
                        </div>
                      </td>    
                    </tr>
                    <tr>
                      <td colspan="2"><label>Proveedor RUC/CI:</label></td>
                      <td >
                        <div class="form-group <?php if(form_error('reg_ruc_cliente')!=''){ echo 'has-error';}?> ">
                          <input type="text" placeholder="Ingrese nombre o ruc de proveedor" class="form-control" name="reg_ruc_cliente" id="reg_ruc_cliente" value="<?php if(validation_errors()!=''){ echo set_value('reg_ruc_cliente');}else{ echo $factura->cli_ced_ruc;}?>" list="list_clientes" onchange="traer_cliente(this)" <?php echo $read_ret ?> <?php echo $read_cxp?>>
                                    <?php echo form_error("reg_ruc_cliente","<span class='help-block'>","</span>");?>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="2"><label>Proveedor Razon Social:</label></td>
                      <td >
                        <div class="form-group <?php if(form_error('nombre')!=''){ echo 'has-error';}?> ">
                        <input type="text" class="form-control" name="nombre" id="nombre" value="<?php if(validation_errors()!=''){ echo set_value('nombre');}else{ echo $factura->cli_raz_social;}?>" <?php echo $read_ret ?> <?php echo $read_cxp?>>
                        <?php echo form_error("nombre","<span class='help-block'>","</span>");?>
                                  
                        <input type="hidden" class="form-control" name="cli_id" id="cli_id" value="<?php if(validation_errors()!=''){ echo set_value('cli_id');}else{ echo $factura->cli_id;}?>" >
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2"><label>Direccion:</label></td>
                    <td >
                      <div class="form-group <?php if(form_error('direccion_cliente')!=''){ echo 'has-error';}?> ">
                        <input type="text" class="form-control" name="direccion_cliente" id="direccion_cliente" value="<?php if(validation_errors()!=''){ echo set_value('direccion_cliente');}else{ echo $factura->cli_calle_prin;}?>" <?php echo $read_ret ?> <?php echo $read_cxp?>>
                        <?php echo form_error("direccion_cliente","<span class='help-block'>","</span>");?>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="2"><label>Telefono:</label></td>
                      <td >
                        <div class="form-group <?php if(form_error('telefono_cliente')!=''){ echo 'has-error';}?> ">
                          <input type="text" class="form-control numerico" name="telefono_cliente" id="telefono_cliente" value="<?php if(form_error('telefono_cliente')){ echo set_value('telefono_cliente');}else{ echo $factura->cli_telefono;}?>" <?php echo $read_ret ?> <?php echo $read_cxp?>>
                              <?php echo form_error("telefono_cliente","<span class='help-block'>","</span>");?>
                                    
                          </div>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="2"><label>Email:</label></td>
                      <td >
                        <div class="form-group <?php if(form_error('email_cliente')!=''){ echo 'has-error';}?> ">
                          <input type="email" class="form-control" name="email_cliente" id="email_cliente" value="<?php if(validation_errors()!=''){ echo set_value('email_cliente');}else{ echo $factura->cli_email;}?>" <?php echo $read_ret ?> <?php echo $read_cxp?>>
                          <?php echo form_error("email_cliente","<span class='help-block'>","</span>");?>
                          </div>
                      </td> 
                    </tr>
                  </table>
                  </div>
                </div>
                </td>
                <td class="col-sm-2">
                  <div class="box-body">
                  <div class="panel panel-default col-sm-12">
                  <table class="table">
                    <tbody>
                      <tr>
                        <td ><label>Fecha Registro:</label></td>
                        <td>
                          <div class="form-group <?php if(form_error('reg_fregistro')!=''){ echo 'has-error';}?> ">
                          <input type="date" class="form-control" name="reg_fregistro" id="reg_fregistro" value="<?php if(validation_errors()!=''){ echo set_value('reg_fregistro');}else{ echo $factura->reg_fregistro;}?>">
                          <?php echo form_error("reg_fregistro","<span class='help-block'>","</span>");?>
                          </div>
                        </td>
                        
                      </tr> 
                      <tr>
                        <td><label>Concepto:</label></td>
                        <td >
                          <div class="form-group <?php if(form_error('reg_concepto')!=''){ echo 'has-error';}?> ">
                            <input type="text" class="form-control" name="reg_concepto" id="reg_concepto" value="<?php if(validation_errors()!=''){ echo set_value('reg_concepto');}else{ echo $factura->reg_concepto;}?>">
                            <?php echo form_error("reg_concepto","<span class='help-block'>","</span>");?>
                            </div>
                          </td>
                        </tr>
                        <tr hidden>
                          <td><label>#Importacion:</label></td>
                          <td>
                            <div class="form-group <?php if(form_error('reg_importe')!=''){ echo 'has-error';}?> ">
                            <input type="text" id="reg_importe" name="reg_importe" class="form-control itm" size="25px" value="<?php if(validation_errors()!=''){ echo set_value('reg_importe');}else{ echo $factura->reg_importe;}?>">
                            <?php echo form_error("reg_importe","<span class='help-block'>","</span>");?>
                            </div>
                          </td>
                        </tr>
                      <tr hidden>
                        <td><label>Fecha Autorizacion:</label></td>
                        <td>
                          <div class="form-group <?php if(form_error('reg_fautorizacion')!=''){ echo 'has-error';}?> ">
                          <input type="date" id="reg_fautorizacion" name="reg_fautorizacion" class="form-control itm" size="25px" value="<?php if(validation_errors()!=''){ echo set_value('reg_fautorizacion');}else{ echo $factura->reg_fautorizacion;}?>">
                          <?php echo form_error("reg_fautorizacion","<span class='help-block'>","</span>");?>
                          </div>
                        </td>
                      </tr>
                      <tr hidden>
                        <td><label>Fecha de Caducidad:</label></td>
                        <td>
                          <div class="form-group <?php if(form_error('reg_fcaducidad')!=''){ echo 'has-error';}?> ">
                                  <input type="date" id="reg_fcaducidad" name="reg_fcaducidad" class="form-control itm" size="25px" value="<?php if(validation_errors()!=''){ echo set_value('reg_fcaducidad');}else{ echo $factura->reg_fcaducidad;}?>">
                          <?php echo form_error("reg_fcaducidad","<span class='help-block'>","</span>");?>
                          </div>
                        </td>
                      </tr>
                      
                        <tr hidden>
                          <td><label>Pais Origen:</label></td>
                          <td>
                            <div class="form-group ">
                            <select name="reg_pais_importe"  id="reg_pais_importe" class="form-control">
                              <option value="0">SELECCIONE</option>
                                <?php
                                  if(!empty($paises)){
                                    foreach ($paises as $pais) {
                                ?>
                                  <option value="<?php echo $pais->pai_id?>"><?php echo $pais->pai_codigo .' - '. $pais->pai_descripcion?></option>
                                <?php
                                    }
                                  }
                                ?>
                            </select>
                            <script type="text/javascript">
                              var pais='<?php echo $factura->reg_pais_importe;?>';
                                reg_pais_importe.value=pais;
                            </script>
                            <?php echo form_error("reg_pais_importe","<span class='help-block'>","</span>");?>
                            </div>
                          </td>    
                          </tr>
                          <tr hidden>
                            <td><label>Tipo Pago:</label></td>
                            <td>
                              <div class="form-group ">
                              <select name="reg_tipo_pago"  id="reg_tipo_pago" class="form-control">
                                <option value="0">SELECCIONE</option>
                                <option value='01'>01 - PAGO A RESIDENTE</option>
                                <option value='02'>02 - PAGO A NO RESIDENTE</option>
                              </select>
                              <script type="text/javascript">
                                var tip_pago='<?php echo $factura->reg_tipo_pago;?>';
                                reg_tipo_pago.value=tip_pago;
                              </script>
                              <?php echo form_error("reg_tipo_pago","<span class='help-block'>","</span>");?>
                              </div>
                            </td>    
                          </tr>
                          <tr>
                            <td><label>Forma de Pago:</label></td>
                            <td>
                            <div class="form-group ">
                              <select name="reg_forma_pago"  id="reg_forma_pago" class="form-control">
                                <option value="0">SELECCIONE</option>
                                  <?php
                                if(!empty($formas_pago)){
                                  foreach ($formas_pago as $forma) {
                                ?>
                                <option value="<?php echo $forma->fpg_id?>"><?php echo $forma->fpg_codigo .' - '. $forma->fpg_descripcion?></option>
                                <?php
                                   }
                                }
                              ?>
                              </select>
                              <script type="text/javascript">
                                var forma='<?php echo $factura->reg_forma_pago;?>';
                                reg_forma_pago.value=forma;
                              </script>
                              <?php echo form_error("reg_forma_pago","<span class='help-block'>","</span>");?>
                            </div>
                            </td>    
                          </tr>
                          <tr>
                            <td><label>Documento Relacionado:</label></td>
                            <td>
                            <div class="form-group ">
                              <select name="reg_relacionado"  id="reg_relacionado" class="form-control">
                                <option value="">SELECCIONE</option>
                                <option value='SI'>SI</option>
                                <option value='NO'>NO</option>
                              </select>
                              <script type="text/javascript">
                                var relacionado='<?php echo $factura->reg_relacionado;?>';
                                    reg_relacionado.value=relacionado;
                              </script>
                              <?php echo form_error("reg_relacionado","<span class='help-block'>","</span>");?>
                            </div>
                            </td>    
                          </tr>
                          <tr>
                            <td class="col-xs-4" colspan="2">
                    <div class="box-body">
                    <div class="panel panel-default col-sm-12">
                    <div class="panel panel-heading "><label>Pagos</label></div>
                    <table class="table">
                      <thead>
                        <tr>
                          <th>%</th>
                          <th>Dias</th>
                          <th>Valor</th>
                          <th>Fecha</th>
                          <th></th>
                        </tr>  
                      </thead>
                      <tbody id="tb_pagos">
                      <?php
                        $m=1;
                        if(empty($cns_pagos)){
                      ?>  
                          <tr>
                            <td >
                              <div class="form-group itm1">
                                <input type="text" class="form-control  decimal" name="pag_porcentage1" id="pag_porcentage1" value="100" lang="1" onchange="calculo_total_pago()">
                              </div>
                            </td>
                            <td>
                              <div class="form-group">
                                <input type="text" id="pag_dias1" name="pag_dias1" class="form-control  numerico" size="25px" value="1" lang="1" onchange="calculo_fecha()">
                              </div>
                            </td>
                            <td>
                              <div class="form-group ">
                                <input type="text" id="pag_valor1" name="pag_valor1" class="form-control decimal" size="20px" value="0" lang="1" readonly>
                              </div>
                            </td> 
                            <td>
                              <div class="form-group">
                                <input type="date" id="pag_fecha_v1" name="pag_fecha_v1" class="form-control" size="25px" value="" lang="1" readonly>
                              </div>
                            </td>
                            <td onclick="elimina_fila(this)" align="center"><span class="btn btn-danger fa fa-trash"></span></td>  
                          </tr>
                        <?php
                        }else{
                          $m=1;
                          foreach ($cns_pagos as $pag) {
                            $m++;
                        ?>
                        <tr>
                            <td >
                              <div class="form-group itm1">
                                <input type="text" class="form-control  decimal" name="pag_porcentage<?php echo $m?>" id="pag_porcentage<?php echo $m?>" style="text-align: right;" value="<?php echo str_replace(',','', number_format($pag->pag_porcentage,$dec))?>" lang="<?php echo $m?>" onchange="calculo_total_pago()" <?php echo $read_ret?> <?php echo $read_cxp?>>
                              </div>
                            </td>
                            <td>
                              <div class="form-group">
                                <input type="text" id="pag_dias<?php echo $m?>" name="pag_dias<?php echo $m?>" class="form-control numerico" size="25px" style="text-align: right;" value="<?php echo $pag->pag_dias?>" lang="<?php echo $m?>" onchange="calculo_fecha()" <?php echo $read_ret?> <?php echo $read_cxp?>>
                              </div>
                            </td>
                            <td>
                              <div class="form-group ">
                                <input type="text" id="pag_valor<?php echo $m?>" name="pag_valor<?php echo $m?>" class="form-control decimal" size="20px" style="text-align: right;" value="<?php echo str_replace(',','', number_format($pag->pag_valor,$dec))?>" lang="<?php echo $m?>" readonly>
                              </div>
                            </td> 
                            <td>
                              <div class="form-group">
                                <input type="date" id="pag_fecha_v<?php echo $m?>" name="pag_fecha_v<?php echo $m?>" class="form-control" size="25px" value="<?php echo $pag->pag_fecha_v?>" lang="<?php echo $m?>" readonly>
                              </div>
                            </td>
                            <td onclick="elimina_fila(this)" align="center"><span class="btn btn-danger fa fa-trash" <?php echo $hid_ret?> <?php echo $hid_cxp?>></span></td>  
                          </tr>
                        <?php 
                          }
                        }
                        ?>   

                        </tbody>
                        <tfoot>
                          <tr>
                            <td id="pg_por" style="text-align: right;"></td>
                            <td style="text-align: right;">Faltante</td>
                            <td id="pg_total" style="text-align: right;"></td>
                            <td></td>
                            <td align="center" >
                              <input  type="button" name="addp1" id="addp1" class="btn btn-primary" onclick="validar(1)" lang="1" value='Agregar' <?php echo $hid_ret?> <?php echo $hid_cxp?>/> 
                            </td>  
                          </tr>
                        </tfoot>
                      </table>
                      </div>
                      </div>
                    </td>
                          </tr>
                        </tbody>
                      </table>
                      </div>
                    </div>
                  </td>
                  
                  </tr>
                  <tr>
                       <td class="col-sm-12" colspan="4">
                          <div class="box-body">
                          <div class="panel panel-default col-sm-12">
                          
                          <table class="table table-bordered table-striped" id="tbl_detalle">
                            <thead>
                              <tr>
                                <th>Item</th>
                                <th>Codigo</th>
                                <th>Descripcion</th>
                                <th <?php echo $hidden_as?>>Cta.Contable</th>
                                <th>Unidad</th>
                                <th>Cant.</th>
                                <th>Precio</th>
                                <th>Desc.%</th>
                                <th>Desc.$</th>
                                <th>IVA</th>
                                <th hidden>ICE%</th>
                                <th hidden>ICE $</th>
                                <th>Val.Total</th>
                                <th></th>
                                <th></th>
                              </tr>
                            </thead>

                            <tbody <?php echo $hid_ret?> <?php echo $hid_cxp?>>
                            
                                <?php
                                $cnt_detalle=0;
                                $verifica_cuenta=0;
                                   ?>
                                    <tr>
                                      <td width="60px" align="center" ><input style="color: #fff;background-color: #5cb85c;border-color: #4cae4c;"  type="button" name="nuevo" id="nuevo" class="btn " size="100" onclick="habilitar(0)" lang="1" value='NUEVO'/> </td>
                                       
                                        
                                        <td>
                                            <input style="text-align:left " type="text" size="40" class="form-control" id="pro_descripcion" name="pro_descripcion"  value="" lang="1"   maxlength="16" list="productos" onchange="load_producto(this.lang)"  />
                                        </td>
                                        <td>
                                            <input style="text-align:left " type ="text" size="40" class="refer form-control"  id="pro_referencia" name="pro_referencia"   value="" lang="1" readonly style="width:300px;" />
                                            <input type="hidden"  id="pro_aux" name="pro_aux" lang="1"/>
                                            <!-- <input type="hidden"  id="pro_ids" name="pro_ids" lang="1"/> -->
                                        </td>
                                        <td <?php echo $hidden_as?>>
                                            <input style="text-align:left " type="text" size="40" class="form-control" id="reg_codigo_cta" name="reg_codigo_cta"  value="" lang="1"   maxlength="14" list="list_cuentas" onchange="load_cuenta(this,0)"/>
                                            <input type="hidden" name="pln_id" id="pln_id" lang="1" value="0">
                                        </td>
                                        <td>
                                          <select id="unidad" name="unidad" class="form-control" disabled>
                                            <option value=''>SELECCIONE</option>
                                            <option value='KG'>kg</option>
                                            <option value='LB'>lb</option>
                                            <option value='GR'>gr</option>
                                            <option value='LITRO'>litro</option>
                                            <option value='GALON'>galon</option>
                                            <option value='M'>m</option>
                                            <option value='CM'>cm</option>
                                            <option value='FT'>ft</option>
                                            <option value='IN'>in</option>
                                            <option value='UNIDAD'>UNIDAD</option>
                                            <option value='MILLAR'>MILLAR</option>
                                            <option value='ROLLO'>rollo</option>
                                          </select>
                                          <!-- <input type ="text" size="7" id="unidad" name="unidad"  value="" lang="1" readonly class="form-control" /> -->
                                        </td>
                                        <td>
                                          <input type ="text" size="7"  style="text-align:right" id="cantidad" name="cantidad"  value="" lang="1" onchange="calculo_encabezado(this)" class="form-control decimal" />
                                        </td>
                                        <td>
                                          <input type ="text" size="7" style="text-align:right" id="pro_precio" name="pro_precio" onchange="calculo_encabezado(this)" value="" lang="1" class="form-control decimal"/>
                                        </td>
                                        <td>
                                          <input type ="text" size="7"  style="text-align:right" id="descuento" name="descuento"  value="" lang="1" onchange="calculo_encabezado(this)" class="form-control decimal"/>
                                        </td>
                                        <td>
                                          <input type ="text" size="7"  style="text-align:right" id="descuent" name="descuent"  value="" lang="1" readonly  class="form-control decimal" />
                                        </td>
                                        <td>
                                          <select name="iva" id="iva" class="form-control">
                                            <option value="12">12</option>
                                            <option value="0">0</option>
                                            <option value="NO">NO OBJETO</option>
                                            <option value="EX">EXCENTO</option>
                                          </select>
                                        </td>
                                        <td hidden><input type="text" name="ice_p" id="ice_p" size="5" value="0" readonly lang="1" /></td>
                                        <td hidden><input type="text" name="ice" id="ice" size="5" value="0" readonly lang="1"/>
                                            <input type=""  name="ice_cod" id="ice_cod" size="5" value="0" readonly lang="1"/>
                                        </td>
                                        <td>
                                            <input type ="text" size="9" style="text-align:right" id="valor_total" name="valor_total" value="" lang="1" readonly class="form-control decimal" />
                                            
                                        </td>
                                        <td align="center" ><input  type="button" name="add1" id="add1" class="btn btn-primary fa fa-plus" onclick="validar(0)" lang="1" value='+'/> </td>
                                        
                                    </tr>
                                </tbody>        
                                <tbody id="lista">
                                  <?php
                                  if(!empty($cns_det)){
                                  $cnt_detalle=0;
                                  $n=0;
                                    foreach($cns_det as $rst_det) {
                                        $n++;
                                        ?>
                                        <tr>
                                            <td id="item<?PHP echo $n ?>" name="item<?PHP echo $n ?>" lang="<?PHP echo $n ?>" align="center"><?PHP echo $n ?></td>
                                          
                                            
                                            <td id="pro_descripcion<?PHP echo $n ?>" name="pro_descripcion<?PHP echo $n ?>" lang="<?PHP echo $n ?>"><?php echo $rst_det->pro_codigo ?></td>
                                            <td id="pro_referencia<?PHP echo $n ?>" name="pro_referencia<?PHP echo $n ?>" lang="<?PHP echo $n ?>"><?php echo $rst_det->pro_descripcion ?>
                                                <input type="hidden" size="7" id="pro_aux<?PHP echo $n ?>" name="pro_aux<?PHP echo $n ?>" value="<?php echo $rst_det->pro_id ?>" lang="<?PHP echo $n ?>"/>
                                            </td>
                                            <td <?php echo $hidden_as?>>
                                              <input style="text-align:left " type="text" size="40" class="form-control" id="reg_codigo_cta<?PHP echo $n ?>" name="reg_codigo_cta<?PHP echo $n ?>"  value="<?PHP echo $rst_det->reg_codigo_cta ?>" lang="<?PHP echo $n ?>"   maxlength="14" list="list_cuentas" onchange="load_cuenta(this,1)"/>
                                              <input type="hidden" name="pln_id<?PHP echo $n ?>" id="pln_id<?PHP echo $n ?>" lang="<?PHP echo $n ?>" value="<?PHP echo $rst_det->pln_id ?>">
                                            </td>
                                            <td id="unidad<?PHP echo $n ?>" name="unidad<?PHP echo $n ?>" lang="<?PHP echo $n ?>"><?PHP echo $rst_det->pro_unidad ?></td>
                                            <td ><input type ="text" size="7"  style="text-align:right" class="form-control decimal" id="<?php echo 'cantidad' . $n ?>" name="<?php echo 'cantidad' . $n ?>" value="<?php echo str_replace(',', '', number_format($rst_det->cantidad, $dec)) ?>" lang="<?PHP echo $n ?>" onchange="calculo(this)" onkeyup="this.value = this.value.replace(/[^0-9.]/, '')" <?php echo $read_ret ?> <?php echo $read_cxp?>/></td>
                                            <td><input type ="text" size="7" style="text-align:right" class="form-control decimal" id="<?php echo 'pro_precio' . $n ?>" name="<?php echo 'pro_precio' . $n ?>" value="<?php echo str_replace(',', '', number_format($rst_det->pro_precio, $dec)) ?>" lang="<?PHP echo $n ?>" onchange="calculo(this)" <?php echo $read_ret ?> <?php echo $read_cxp?>/></td>
                                            <td>
                                                <input type ="text" size="7" style="text-align:right" class="form-control decimal" id="<?php echo 'descuento' . $n ?>" name="<?php echo 'descuento' . $n ?>"  value="<?php echo str_replace(',', '', number_format($rst_det->pro_descuento, $dec)) ?>" lang="<?PHP echo $n ?>" onchange="calculo(this)"  <?php echo $read_ret ?> <?php echo $read_cxp?>/>
                                            </td>
                                            <td>
                                                <input type ="text" size="7" style="text-align:right" class="form-control decimal" id="<?php echo 'descuent' . $n ?>" name="<?php echo 'descuent' . $n ?>"  value="<?php echo str_replace(',', '', number_format($rst_det->pro_descuent, $dec)) ?>" lang="<?PHP echo $n ?>"  readonly/>
                                            </td>
                                            
                                            <td>
                                              <?php 
                                              if($read_ret=='' && $read_cxp=='' ){
                                              ?>
                                              <select name="<?php echo 'iva' . $n ?>" id="<?php echo 'iva' . $n ?>" onchange='calculo()' class="form-control">
                                                <option value="12">12</option>
                                                <option value="0">0</option>
                                                <option value="NO">NO OBJETO</option>
                                                <option value="EX">EXCENTO</option>
                                              </select>
                                              <script type="text/javascript">
                                                var iv='<?php echo $rst_det->pro_iva;?>';
                                                <?php echo 'iva' . $n ?>.value=iv;
                                              </script>
                                              <?php
                                              }else{
                                              ?>  
                                                <input type ="text" size="7" style="text-align:right" class="form-control decimal" id="<?php echo 'iva' . $n ?>" name="<?php echo 'iva' . $n ?>"  value="<?php echo $rst_det->pro_iva ?>" lang="<?PHP echo $n ?>"  readonly/>
                                              <?php
                                              }
                                              ?>
                                            </td>
                                            <td hidden><input type="text" id="<?php echo 'ice_p' . $n ?>" name="<?php echo 'ice_p' . $n ?>" size="5" value="<?php echo str_replace(',', '', number_format($rst_det->ice_p, $dec)) ?>" lang="<?PHP echo $n ?>" readonly /></td>
                                            <td hidden><input type="text" id="<?php echo 'ice' . $n ?>" name="<?php echo 'ice' . $n ?>" size="5" class="form-control" value="<?php echo str_replace(',', '', number_format($rst_det->ice, $dec)) ?>" readonly lang="<?php echo $n ?>"/>
                                                <input type="hidden" id="<?php echo 'ice_cod' . $n ?>" name="<?php echo 'ice_cod' . $n ?>" size="5" class="form-control" value="<?php echo $rst_det->ice_cod ?>" lang="<?PHP echo $n ?>"readonly />
                                            </td>
                                            <td>
                                                <input type ="text" size="9" style="text-align:right" class="form-control" id="<?php echo 'valor_total' . $n ?>" name="<?php echo 'valor_total' . $n ?>"  value="<?php echo str_replace(',', '', number_format($rst_det->precio_tot, $dec)) ?>" readonly lang="<?PHP echo $n ?>"/>
                                                
                                            </td>
                                            <td onclick="elimina_fila_det(this)" align="center" <?php echo $hid_ret?> <?php echo $hid_cxp?>><span class="btn btn-danger fa fa-trash"></span></td>
                                        </tr>
                                        <?php
                                        $cnt_detalle++;
                                    }
                                  }
                                ?>
                                </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3"><label>Observaciones (250 Caracteres max.):</label></td>
                                </tr>
                                <tr>

                                    <td valign="top" rowspan="10" colspan="<?php echo $col_obs?>">
                                      <textarea style="height: 80px; width: 90%;" id="reg_observaciones" name="reg_observaciones"   onkeydown="return enter(event)" maxlength="250" ><?php echo $factura->reg_observaciones ?></textarea>
                                    </td>

                                    <td colspan="2" align="right">Subtotal 12%:</td>
                                    <td>
                                        <input style="text-align:right" type="text" class="form-control" id="subtotal12" name="subtotal12" value="<?php echo str_replace(',', '', number_format($factura->reg_sbt12, $dec)) ?>" readonly/>
                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right">Subtotal 0%:</td>
                                    <td>
                                        <input style="text-align:right" type="text" class="form-control" id="subtotal0" name="subtotal0" value="<?php echo str_replace(',', '', number_format($factura->reg_sbt0, $dec)) ?>" readonly/>
                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right">Subtotal Excento de Iva:</td>
                                    <td><input style="text-align:right" type="text" class="form-control" id="subtotalex" name="subtotalex" value="<?php echo str_replace(',', '', number_format($factura->reg_sbt_excento, $dec)) ?>" readonly/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right">Subtotal no objeto de Iva:</td>
                                    <td><input style="text-align:right" type="text" class="form-control" id="subtotalno" name="subtotalno" value="<?php echo str_replace(',', '', number_format($factura->reg_sbt_noiva, $dec)) ?>" readonly/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right">Subtotal sin Impuestos:</td>
                                    <td><input style="text-align:right" type="text" class="form-control" id="subtotal" name="subtotal" value="<?php echo str_replace(',', '', number_format($factura->reg_sbt, $dec)) ?>" readonly/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right">Total Descuento:</td>
                                    <td><input style="text-align:right" type="text" class="form-control" id="total_descuento" name="total_descuento" value="<?php echo str_replace(',', '', number_format($factura->reg_tdescuento, $dec)) ?>" readonly/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right">Total ICE:</td>
                                    <td><input style="text-align:right" type="text" class="form-control" id="total_ice" name="total_ice" value="<?php echo str_replace(',', '', number_format($factura->reg_ice, $dec)) ?>"  readonly/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right">Total IVA:</td>
                                    <td><input style="text-align:right" type="text" class="form-control" id="total_iva" name="total_iva" value="<?php echo str_replace(',', '', number_format($factura->reg_iva12, $dec)) ?>" readonly />
                                    </td>
                                </tr> 
                                <tr>
                                    <td colspan="2" align="right">Propina:</td>
                                    <td><input type="text" class="form-control" id="total_propina" name="total_propina" value="<?php echo str_replace(',', '', number_format($factura->reg_propina, $dec)) ?>"  style="text-align:right" onchange="calculo()" <?php echo $read_ret?> <?php echo $read_cxp?> />
                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right">Total Valor:</td>
                                    <td><input style="text-align:right;font-size:15px;color:red  " type="text" class="form-control" id="total_valor" name="total_valor" value="<?php echo str_replace(',', '', number_format($factura->reg_total, $dec)) ?>" readonly />
                                        
                                    </td>
                                </tr>
                                <tr>
                                  <td colspan="<?php echo $col_obs?>" style="color:red;">
                                  <?php echo $sms_ret?><br/> <?php echo $sms_cxp?> 
                                  </td>
                                </tr>
                              </tfoot>
                          </table>
                          </div>
                          </div>
                          </td>
                    </tr> 
                    
                    
                  </table>
              </div>
                                
                <input type="hidden" class="form-control" name="reg_id" value="<?php echo $factura->reg_id?>">
                <input type="hidden" class="form-control" id="count_detalle" name="count_detalle" value="<?php echo $cnt_detalle?>">
                <input type="hidden" class="form-control" id="count_pagos" name="count_pagos" value="<?php echo $m?>">
                <input type="hidden" class="form-control" id="verifica_cuenta" name="verifica_cuenta" value="<?php echo $verifica_cuenta?>">
                <input type="hidden" class="form-control" id="ctaxpagar" name="ctaxpagar" value="<?php echo $ctaxpagar?>">
                <input type="hidden" class="form-control" id="retencion" name="retencion" value="<?php echo $retencion?>">
              <div class="box-footer">
                <button type="button" class="btn btn-primary" onclick="save()">Guardar</button>
                <a href="<?php echo $cancelar;?>" class="btn btn-default">Cancelar</a>
              </div>

            </form>
          </div>
         
      </div>
      <div class="modal fade" id="modal_productos">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Crear producto</h4>
              </div>
              <div class="modal-body">
                <table class="table" width="50%">
                  <tr>
                    <td>Categoria</td>
                    <td >
                        <select name="pro_ids" id="pro_ids" class="form-control" onchange="load_familias()">
                          <option value="0">SELECCIONE</option>
                          <?php 
                          if(!empty($categorias)){
                            foreach ($categorias as $rst_ct) {
                          ?>
                           <option value="<?php echo $rst_ct->cat_id?>"><?php echo strtoupper($rst_ct->cat_descripcion)?></option>
                           <?php
                            }
                          }
                          ?>
                        </select>
                      </td> 
                  </tr>
                  <tr>  
                    <td>Familia</td>
                    <td>
                      <select name="mp_a" id="mp_a" class="form-control" onchange='load_tipos(),load_codigo()'>
                        <option value="0">SELECCIONE</option>
                        <?php 
                        if(!empty($tipos)){
                          foreach ($tipos as $rst_tp) {
                        ?>
                        <option value="<?php echo $rst_tp->tps_id?>"><?php echo $rst_tp->tps_nombre?></option>
                        <?php
                          }
                        }
                        ?>
                      </select>
                    </td>
                  </tr>
                  <tr>    
                    <td>Tipo</td>
                    <td>
                        <select name="mp_b" id="mp_b" onchange="load_codigo()" class="form-control">
                          <option value="0">SELECCIONE</option>
                          <?php 
                          if(!empty($familias)){
                            foreach ($familias as $rst_fm) {
                          ?>
                          <option value="<?php echo $rst_fm->tps_id?>"><?php echo $rst_fm->tps_nombre?></option>
                          <?php
                            }
                          }
                          ?>
                        </select>
                      </td>
                  </tr>
                  <tr>  
                    <td>Codigo</td>
                    <td><input type="text" id="codigo" name="codigo" class="form-control" size="15px" lang="1" readonly onchange="validar_codigo()">
                      </td> 
                  </tr> 
                  <tr>
                    <td>Descripcion</td>
                    <td><input type="text" id="descripcion" name="descripcion" class="form-control" size="15px" lang="1">
                    </td>
                  </tr>
                  <tr>
                    <td>Unidad</td>  
                    <td>
                        <select id="unidad_pr" name="unidad_pr" class="form-control">
                           <option value=''>SELECCIONE</option>
                          <option value='KG'>kg</option>
                          <option value='LB'>lb</option>
                          <option value='GR'>gr</option>
                          <option value='LITRO'>litro</option>
                          <option value='GALON'>galon</option>
                          <option value='M'>m</option>
                          <option value='CM'>cm</option>
                          <option value='FT'>ft</option>
                          <option value='IN'>in</option>
                          <option value='UNIDAD'>UNIDAD</option>
                          <option value='MILLAR'>MILLAR</option>
                          <option value='ROLLO'>rollo</option>
                        </select>
                    </td>
                  </tr>
                  <tr>  
                    <td>Precio</td>
                    <td><input type="text" id="precio" name="precio" class="form-control decimal" size="15px" lang="1" onkeyup="this.value = this.value.replace(/[^0-9.]/, '')">
                    </td>
                  </tr>
                  <tr>  
                    <td>Iva</td>
                    <td>
                      <select name="iva_pr" id="iva_pr" class="form-control">
                        <option value="12">12</option>
                        <option value="0">0</option>
                        <option value="NO">NO OBJETO</option>
                        <option value="EX">EXCENTO</option>
                      </select>
                    </td>
                  </tr>
                  
                </table>
              </div>
              <div class="modal-footer"  >
                <div style="float:right">
                  <button type="button" class="btn btn-success pull-left btn-md" onclick="nuevo_producto()">Agregar</button>
                </div>
              </div>
            </div>
          </div>
      </div>

    </section>
    <datalist id="list_clientes">
      <?php 
        if(!empty($cns_clientes)){
          foreach ($cns_clientes as $rst_cli) {
      ?>
        <option value="<?php echo $rst_cli->cli_id?>"><?php echo $rst_cli->cli_ced_ruc .' '.$rst_cli->cli_raz_social?></option>
      <?php 
          }
        }
      ?>
    </datalist>
    <datalist id="productos">
      <?php 
        if(!empty($cns_productos)){
          foreach ($cns_productos as $rst_pro) {
      ?>
        <option value="<?php echo $rst_pro->id?>"><?php echo $rst_pro->mp_c .' '.$rst_pro->mp_d?></option>
      <?php 
          }
        }
      ?>
  
    </datalist>

    <datalist id="list_cuentas">
      <?php 
        if(!empty($cns_cuentas)){
          foreach ($cns_cuentas as $rst_cta) {
      ?>
        <option  class="option"  value="<?php echo $rst_cta->pln_codigo?>"><?php echo $rst_cta->pln_codigo .' '.$rst_cta->pln_descripcion?></option>
      <?php 
          }
        }
      ?>
  
    </datalist>
    

    <style type="text/css">
     
      .panel{
        margin: 0.5px !important;
        /*margin-top: 0px !important;*/
        padding: 0.5px !important;
        /*padding-top: 0px !important;*/

      }

      div{
        margin-bottom: 0px !important;
        margin-top: 0px !important;
        padding-bottom: 0px !important;
        padding-top: 0px !important;
      }
      div .panel-heading{
        margin-bottom: 4px !important;
        margin-top: 4px !important;
        padding-bottom: 4px !important;
        padding-top: 4px !important;
      }
      
      .form-control{
        margin-bottom: 0px !important;
        margin-top: 0px !important;
        padding-bottom: 0px !important;
        padding-top: 0px !important;
        height:28px !important;
      }

      td{
        margin-bottom: 1px !important;
        margin-top: 1px !important;
        padding-bottom: 1px !important;
        padding-top: 1px !important;
        margin-right: 0px !important;
        margin-left:  0px !important;
        padding-right: 0px !important;
        padding-left:  0px !important;
      }
      
      .btn-success{
        width: 35px !important;
        font-size: 10px;
        padding-right: 0px !important;
        padding-left:  0px !important;
      }

      .fa-plus{
        width: 20px !important;
        padding-right: 0px !important;
        padding-left:  0px !important;
      }

      .sel{
        width: 100px !important;
        
      }

      .option {
      width: 150px !important;
      white-space:pre-wrap;
      word-wrap: break-word;
      }


    </style>
    <script >

      var base_url='<?php echo base_url();?>';
      var dec='<?php echo $dec;?>';
      var dcc='<?php echo $dcc;?>';
      var valida_asiento='<?php echo $valida_asiento;?>';
      var conf_as='<?php echo $conf_as;?>';
      var ret_url='<?php echo $ret_url;?>';
      var registro='<?php echo $registro;?>';
  
      if(registro!=0){
        generar_retencion(registro);
      }

      window.onload = function () {
        if(valida_asiento==1){
          swal("", "No se puede crear Documento \nRevise Configuracion de cuentas", "info");          
        }
      }

      function completar_ceros(obj, v) {
                o = obj.value;
                val = parseFloat(o);
                if (v == 0) {
                    if (val == 0) {
                        //alert("Numero incorrecto");
                        swal("", "Nùmero incorrecto", "info"); 
                        $(obj).val('');
                    } else if (val > 0 && val < 10) {
                        txt = '00';
                    } else if (val >= 10 && val < 100) {
                        txt = '0';
                    } else if (val >= 100 && val < 1000) {
                        txt = '';
                    }
                    $(obj).val(txt + val);
                } else {
                    if (val > 0 && val < 10) {
                        txt = '00000000';
                    } else if (val >= 10 && val < 100) {
                        txt = '0000000';
                    } else if (val >= 100 && val < 1000) {
                        txt = '000000';
                    } else if (val >= 1000 && val < 10000) {
                        txt = '00000';
                    } else if (val >= 10000 && val < 100000) {
                        txt = '0000';
                    } else if (val >= 100000 && val < 1000000) {
                        txt = '000';
                    } else if (val >= 1000000 && val < 10000000) {
                        txt = '00';
                    } else if (val >= 10000000 && val < 100000000) {
                        txt = '0';
                    } else if (val >= 100000000 && val < 1000000000) {
                        txt = '';
                    }
                    $(obj).val(txt + val);

                    if (val == 0 || o.length == 0) {
                        //alert("Numero incorrecto");
                        Swal.fire("", "Nùmero incorrecto", "info"); 
                        $(obj).val('');
                        return false;
                    }else{
                      //num_doc = $('#reg_num_documento0').val()+'-'+$('#reg_num_documento1').val()+'-'+$('#reg_num_documento2').val();
                      val_factura();
                     
                    }
                }
                doc_duplicado()
            }

      function validar_decimal(obj){
        obj.value = (obj.value + '').replace(/[^0-9.]/g, '');
      }
            function traer_cliente(){
              $.ajax({
                    beforeSend: function () {
                      if ($('#reg_ruc_cliente').val().length == 0) {
                            //alert('Ingrese dato');
                            swal("", "Ingrese dato", "info"); 
                            $('#nombre').focus();
                            $('#cli_id').val('0');
                            $('#nombre').val('');
                            $('#telefono_cliente').val('');
                            $('#direccion_cliente').val('');
                            $('#cli_ciudad').val('');
                            $('#email_cliente').val('');
                            $('#cli_pais').val('');
                            $('#cli_parroquia').val('');
                            return false;
                      }
                    },
                    url: base_url+"reg_factura/traer_cliente/"+reg_ruc_cliente.value,
                    type: 'JSON',
                    dataType: 'JSON',
                    success: function (dt) {
                        if(dt!=""){
                          $('#cli_id').val(dt.cli_id);
                          $('#nombre').val(dt.cli_raz_social);
                          $('#telefono_cliente').val(dt.cli_telefono);
                          $('#direccion_cliente').val(dt.cli_calle_prin);
                          $('#cli_ciudad').val(dt.cli_canton);
                          $('#email_cliente').val(dt.cli_email);
                          $('#reg_ruc_cliente').val(dt.cli_ced_ruc);
                          $('#cli_pais').val(dt.cli_pais);
                          $('#cli_parroquia').val(dt.cli_parroquia);
                          doc_duplicado();
                        }else{
                          //alert('Proveedor no existe');
                          swal("", "Proveedor no existe. Se creara un nuevo proveedor", "info"); 
                          $('#nombre').focus();
                          $('#cli_id').val('0');
                          $('#nombre').val('');
                          $('#telefono_cliente').val('');
                          $('#direccion_cliente').val('');
                          $('#cli_ciudad').val('');
                          $('#email_cliente').val('');
                          $('#cli_pais').val('');
                          $('#cli_parroquia').val('');
                        } 
                        
                    },
                    error : function(xhr, status) {
                          //alert('Proveedor no existe');
                         swal("", "Proveedor no existe. Se creara un nuevo proveedor ", "info"); 
                          $('#nombre').focus();
                          $('#cli_id').val('0');
                          $('#nombre').val('');
                          $('#telefono_cliente').val('');
                          $('#direccion_cliente').val('');
                          $('#cli_ciudad').val('');
                          $('#email_cliente').val('');
                          $('#cli_pais').val('');
                          $('#cli_parroquia').val('');
                    }
                    });    
            }

            function doc_duplicado(){
              num_doc = $('#reg_num_documento').val();
              tip_doc = $('#reg_tipo_documento').val();
              if (num_doc.length = 17 && cli_id.value.length > 0 && tip_doc != 0) {
                $.ajax({
                      beforeSend: function () {
                      },
                      url: base_url+"reg_factura/doc_duplicado/"+cli_id.value+"/"+num_doc+"/"+tip_doc,
                      type: 'JSON',
                      dataType: 'JSON',
                      success: function (dt) {
                          if(dt!=""){
                            //alert('EL numero de Documento y el RUC/CI del Proveedor \n Ya existen en el Registro de Facturas');  
                            swal("", "EL numero de Documento y el RUC/CI del Proveedor \n Ya existen en el Registro de Facturas", "info");  
                            $('#reg_num_documento').val('');
                            $('#reg_num_documento0').val('');
                            $('#reg_num_documento1').val('');
                            $('#reg_num_documento2').val('');
                          } 
                      }
                    });
              }          
            }

            function validar_autorizacion(){
              var aut = $('#reg_num_autorizacion').val();
              if(aut.length!=10 && aut.length!=37 && aut.length!=49 ){
                -//alert('El numero de autorizacion debe ser de 10, 37 o 49 digitos');
                Swal.fire("", "El numero de autorizacion debe ser de 10, 37 o 49 digitos", "info"); 
                $('#reg_num_autorizacion').val('');
              }

            }

            function validar(opc){
              if(opc==0){
                if($('#cantidad').val().length!=0   && parseFloat($('#cantidad').val())>0 && $('#pro_precio').val().length!=0 &&  parseFloat($('#pro_precio').val())>0 && $('#descuento').val().length!=0 && $('#pro_descripcion').val().length!=0){
                  clona_detalle();
                }else{
                  if($('#cantidad').val().length!=0){
                     $("#cantidad").css({borderColor: "red"});
                     $("#cantidad").focus();
                  }
                  

                  if(parseFloat($('#cantidad').val())>0){
                     $("#cantidad").css({borderColor: "red"});
                     $("#cantidad").focus();
                  }

                  if($('#pro_precio').val().length!=0){
                     $("#pro_precio").css({borderColor: "red"});
                     $("#pro_precio").focus();
                  }

                  if(parseFloat($('#pro_precio').val())>0){
                     $("#pro_precio").css({borderColor: "red"});
                     $("#pro_precio").focus();
                  }
                  if($('#descuento').val().length!=0){
                     $("#descuento").css({borderColor: "red"});
                     $("#descuento").focus();
                  }
                  if($('#pro_descripcion').val().length!=0){
                     $("#pro_descripcion").css({borderColor: "red"});
                     $("#pro_descripcion").focus();
                  }


                  Swal.fire("", "Revise que la información este completa", "info"); 
                }
              }else{
                var tr = $('#tb_pagos').find("tr:last");
                var a = tr.find("input").attr("lang");
                var i=parseInt(a);
                if($('#pag_porcentage'+i).val().length!=0 && parseFloat($('#pg_por').html())<100 && $('#pag_dias'+i).val().length!=0 && $('#pag_valor'+i).val().length!=0 && parseFloat($('#pag_valor'+i).val())>0 && $('#pag_fecha_v'+i).val().length!=0){
                  clona_fila();
                }
              }
            }
            function validarEmail(valor) {
                if (/^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i.test(valor)){
               return true;
            } else {
              return false;
            }
            }
            function clona_fila() {
                
                  var tr = $('#tb_pagos').find("tr:last").clone();
                  tr.find("input,select").attr("name", function () {
                      var parts = this.id.match(/(\D+)(\d+)$/);
                      return parts[1] + ++parts[2];
                  }).attr("id", function () {
                      var parts = this.id.match(/(\D+)(\d+)$/);
                      x = ++parts[2];
                      this.lang = x;
                      var parent = $(this).parents();
                      $(parent[1]).css('background-color', 'transparent');
                      if (parts[1] == 'item') {
                          this.value = x;
                      } else if (parts[1] == 'pag_porcentage') {
                          por=100-round($('#pg_por').html(),dec);
                          this.value = por;
                      } else if (parts[1] == 'pag_valor') {
                          this.value = $('#pg_total').html();
                      } else{
                          this.value ='';
                      }
                      return parts[1] + x;
                  });
                  $('#tb_pagos').find("tr:last").after(tr);
                  $('#count_pagos').val(x);
                  calculo_total_pago();
                
            }

            function clona_detalle(table,opc) {
                d = 0;
                n = 0;
                ap = '"';
                var tr = $('#lista').find("tr:last");
                var a = tr.find("input").attr("lang");
                if(a==null){
                    j=0;
                }else{
                    j=parseInt(a);
                }
                if (j > 0) {
                    while (n < j) {
                        n++;
                        if ($('#pro_aux' + n).val() == pro_aux.value) {
                            d = 1;
                            cant = round($('#cantidad' + n).val(),dcc) + round(cantidad.value,dcc);
                            $('#cantidad' + n).val(cant.toFixed(dcc));
                            $('#pro_precio' + n).val(pro_precio.value);
                            $('#descuento' + n).val(descuento.value);
                            $('#iva' + n).val(iva.value);
                            
                        }
                    }
                }
                                    
                if (d == 0) {
                    i = j + 1;
                    var fila = "<tr>"+
                                        "<td id='item"+i+"' lang='"+i+"' align='center'>"+
                                          i+

                                          "<input type ='hidden' name='pro_aux"+i+"' id='pro_aux"+i+"' lang='"+i+"' value='"+pro_aux.value+"'/>"+
                                        "</td>"+
                                        "<td id='pro_descripcion"+i+"' lang='"+i+"'>"+pro_descripcion.value+"</td>"+
                                        "<td id='pro_referencia"+i+"' lang='"+i+"'>"+pro_referencia.value+"</td>"+
                                        "<td <?php echo $hidden_as?>>"+
                                            "<input style='text-align:left ' type='text' size='40' class='form-control' id='reg_codigo_cta"+i+"' name='reg_codigo_cta"+i+"'  value='"+reg_codigo_cta.value+"' lang='"+i+"'  maxlength='14' list='list_cuentas' onchange='load_cuenta(this,1)'/>"+
                                            "<input type='hidden' name='pln_id"+i+"' id='pln_id"+i+"' lang='"+i+"' value='"+pln_id.value+"'>"+
                                        "</td>"+
                                        "<td id='unidad"+i+"' lang='"+i+"'>"+unidad.value+"</td>"+
                                        "<td>"+
                                          "<input type ='text' class='form-control decimal' size='7' style='text-align:right' id='cantidad"+i+"' name='cantidad"+i+"' lang='"+i+"'  value='"+cantidad.value +"' onchange='calculo()' onkeyup='validar_decimal(this)'/>"+
                                        "</td>"+
                                        "<td>"+
                                          "<input type ='text' size='7' style='text-align:right' id='pro_precio"+i+"' name='pro_precio"+i+"' onchange='calculo()' value='"+pro_precio.value+"' lang='"+i+"' class='form-control decimal' onkeyup='validar_decimal(this)' />"+
                                        "</td>"+
                                        "<td>"+
                                          "<input type ='text' size='7'  style='text-align:right' id='descuento"+i+"' name='descuento"+i+"'  lang='"+i+"' onchange='calculo()' class='form-control decimal' value='"+descuento.value+"' onkeyup='validar_decimal(this)'/>"+
                                        "</td>"+
                                        "<td>"+
                                          "<input type ='text' size='7'  style='text-align:right' id='descuent"+i+"' name='descuent"+i+"'  lang='"+i+"' onchange='calculo()' class='form-control decimal' readonly value='"+descuent.value+"' onkeyup='validar_decimal(this)'/>"+
                                        "</td>"+
                                        "<td>"+
                                          "<select name='iva"+i+"' id='iva"+i+"' onchange='calculo()' class='form-control'>"+
                                            "<option value='12'>12</option>"+
                                            "<option value='0'>0</option>"+
                                            "<option value='NO'>NO OBJETO</option>"+
                                            "<option value='EX'>EXCENTO</option>"+
                                          "</select>"+
                                        "</td>"+
                                        "<td hidden>"+
                                          "<input type ='text' size='7'  style='text-align:right' id='ice_p"+i+"' name='ice_p"+i+"'  lang='"+i+"' onchange='calculo()' class='form-control decimal' readonly value='"+ice_p.value+"' onkeyup='validar_decimal(this)'/>"+
                                          "<input type ='text' size='7'  style='text-align:right' id='ice_cod"+i+"' name='ice_cod"+i+"'  lang='"+i+"' onchange='calculo()' class='form-control decimal' readonly value='"+ice_cod.value+"' onkeyup='validar_decimal(this)'/>"+
                                        "</td>"+
                                        "<td hidden>"+
                                          "<input type ='text' size='7'  style='text-align:right' id='ice"+i+"' name='ice"+i+"'  lang='"+i+"' onchange='calculo()' class='form-control decimal' readonly value='"+ice.value+"' onkeyup='validar_decimal(this)'/>"+
                                        "</td>"+
                                        "<td >"+
                                          "<input type ='text' size='7'  style='text-align:right' id='valor_total"+i+"' name='valor_total"+i+"'  lang='"+i+"' onchange='calculo()' class='form-control decimal' readonly value='"+valor_total.value+"' onkeyup='validar_decimal(this)'/>"+
                                        "</td>"+
                                        "<td onclick='elimina_fila_det(this)' align='center' >"+"<span class='btn btn-danger fa fa-trash'>"+"</span>"+"</td>"+
                                    "</tr>";
                    $('#lista').append(fila);
                    $('#count_detalle').val(i);
                    $('#iva' + i).val(iva.value);
                }
         
                reg_codigo_cta.value='';
                pln_id.value='0';
                pro_referencia.value = '';
                pro_descripcion.value = '';
                pro_aux.value = '';
                unidad.value = '';
                cantidad.value = '';
                pro_precio.value = '';
                iva.value = '12';
                descuento.value = '';
                descuent.value = '';
                ice.value = '';
                ice_cod.value = '';
                ice_p.value = '';
                valor_total.value = '';
                $('#cantidad').css({borderColor: ""});
                $('#pro_descripcion').focus();
                calculo();
                
            }

            function elimina_fila(obj) {
                
                  itm = $('.itm1').length;
                  if (itm > 1) {
                      var parent = $(obj).parents();
                      $(parent[0]).remove();
                  } else {
                      // alert('No puede eliminar todas las filas');
                    Swal.fire("", "No puede eliminar todas las filas", "info"); 
                  }
                  calculo_total_pago();
            }

            function elimina_fila_det(obj) {
                  var parent = $(obj).parents();
                  $(parent[0]).remove();
                  calculo();
            }

            

            function load_producto(j) {
                vl = $('#pro_descripcion').val();
                $.ajax({
                  beforeSend: function () {
                      if ($('#pro_descripcion').val().length == 0) {
                            //alert('Ingrese un producto');
                            swal("", "Ingrese un producto", "info"); 
                            return false;
                      }
                    },
                    url: base_url+"reg_factura/load_producto/"+vl,
                    type: 'JSON',
                    dataType: 'JSON',
                    success: function (dt) {
                      console.log(dt);
                      if (dt!='') {
                        $('#pro_cat').val(dt.pro_cat);
                        $('#mp_a').val(dt.mp_a);
                        $('#mp_b').val(dt.mp_b);
                        $('#pro_descripcion').val(dt.pro_codigo);
                        $('#pro_referencia').val(dt.pro_descripcion);
                        if(dt.pro_iva==''){
                          $('#iva').val('12');
                        }else{
                          $('#iva').val(dt.pro_iva);
                        }
                        $('#pro_aux').val(dt.pro_id);
                        $('#pro_ids').val(dt.ids);
                        $('#cantidad').val('');
                        $('#unidad').val(dt.pro_unidad);
                       
                        if (dt.pro_precio== '') {
                            $('#pro_precio').val(0);
                            
                        } else {
                            $('#pro_precio').val(parseFloat(dt.pro_precio).toFixed(dec));
                        }

                        if (dt.pro_descuento == '') {
                            $('#descuento').val(0);
                        } else {
                            $('#descuento').val(parseFloat(dt.pro_descuento).toFixed(dec));
                        }

                        if (dt.ice_p== '') {
                            $('#ice').val('0');
                            $('#ice_p').val('0');
                        } else {
                            $('#ice').val('0');
                            $('#ice_p').val(parseFloat(dt.ice_p).toFixed(dec));

                        }

                        if (dt.ice_cod == '') {
                            $('#ice_cod').val('0');
                        } else {
                            $('#ice_cod').val(dt.ice_cod);
                        }
                        if(conf_as==1){
                          $('#cantidad').focus();
                        }else{
                          $("#pln_id").val(dt.pln_id);
                          $("#reg_codigo_cta").val(dt.reg_codigo_cta);
                          $('#reg_codigo_cta').focus();
                          // if (dt.reg_codigo_cta !='') {
                          //   val_cuenta(dt.reg_codigo_cta);
                          // }
                          

                        }
                      }else{
                        $('#pro_descripcion').val('');
                        $('#pro_referencia').val('');
                        $('#cantidad').val('');
                        $('#iva').val('0');
                        $('#pro_aux').val('');
                        $('#pro_ids').val('');
                        $('#pro_precio').val('0');
                        $('#descuento').val('0');
                        
                        $('#ice').val('0');
                        $('#ice_p').val('0');
                        $('#ice_cod').val('0');
                        $('#pro_descripcion').focus();
                      }
                      calculo('1');
                    }
                  });
                
              }

            function round(value, decimals) {
                  return Number(Math.round(value+'e'+decimals)+'e-'+decimals);
            }


            function calculo_encabezado() {
                
                n = 0;
                var t12 = 0;
                var t0 = 0;
                var tex = 0;
                var tno = 0;
                var tdsc = 0;
                var tiva = 0;
                var gtot = 0;
                var tice = 0;
                var tib = 0;
                var sub = 0;
                var prop=0;

                
                        cnt = $('#cantidad').val().replace(',', '');
                        if(cnt==''){
                          cnt=0;
                        }
                        pr = $('#pro_precio').val().replace(',', '');
                        d = $('#descuento').val().replace(',', '');
                        vtp = round(cnt,dcc) * round(pr,2); //Valor total parcial
                        vt = (vtp * 1) - (vtp * round(d,dec) / 100);
                        ic = $('#ice_p').val().replace(',', '');
                        pic = (round(vt,dec) * round(ic,dec)) / 100;
                        if(pic.toFixed(2)=='NaN'){
                          pic=0;
                        }
                        dsc= (round(vtp,dec) * round(d,dec)) / 100; 
                        if(dsc.toFixed(2)=='NaN'){
                          dsc=0;
                        }  
                        $('#descuent').val(dsc.toFixed(dec));
                        $('#valor_total').val(vt.toFixed(dec));
                        ob = $('#iva').val();
                        val = $('#valor_total').val().replace(',', '');
                        d = $('#descuent').val().replace(',', '');
                        $('#ice').val(pic.toFixed(dec));
            }     

            function calculo(obj) {

                var tr = $('#lista').find("tr:last");
                var a = tr.find("input").attr("lang");
                i = parseInt(a);

                n = 0;
                var t12 = 0;
                var t0 = 0;
                var tex = 0;
                var tno = 0;
                var tdsc = 0;
                var tiva = 0;
                var gtot = 0;
                var tice = 0;
                var tib = 0;
                var sub = 0;
                var prop=0;

                while (n < i) {
                    n++;
                    if ($('#item' + n).val() == null) {
                        ob = 0;
                        val = 0;
                        val2 = 0;
                        d = 0;
                        cnt = 0;
                        pr = 0;
                        d = 0;
                        vtp = 0;
                        vt = 0;
                        ic = 0;
                        ib = 0;
                        dsc= 0;
                        uni=0;
                    } else {
                        
                        cnt = $('#cantidad' + n).val().replace(',', '');
                        if(cnt==''){
                          cnt=0;
                        }
                        pr = $('#pro_precio' + n).val().replace(',', '');
                        d = $('#descuento' + n).val().replace(',', '');
                        vtp = round(cnt,dcc) * round(pr,2); //Valor total parcial
                        vt = (vtp * 1) - (vtp * round(d,dec) / 100);
                        ic = $('#ice_p' + n).val().replace(',', '');
                        pic = (round(vt,dec) * round(ic,dec)) / 100;
                        if(pic.toFixed(2)=='NaN'){
                          pic=0;
                        }
                        dsc= (round(vtp,dec) * round(d,dec)) / 100; 
                        if(dsc.toFixed(2)=='NaN'){
                          dsc=0;
                        }  
                        $('#descuent' + n).val(dsc.toFixed(dec));
                        $('#valor_total' + n).val(vt.toFixed(dec));
                        ob = $('#iva' + n).val();
                        val = $('#valor_total' + n).val().replace(',', '');
                        d = $('#descuent' + n).val().replace(',', '');
                        $('#ice' + n).val(pic.toFixed(dec));
                    }

                    tdsc = (round(tdsc,dec) * 1) + (round(d,dec) * 1);
                    tice = (round(tice,dec) * 1) + (round(pic,dec) * 1);

                    if (ob == '14') {
                        t12 = (round(t12,dec) * 1 + round(vt,dec) * 1);
                        tiva = ((round(tice,dec) + round(t12,dec)) * 14 / 100);
                    }

                    if (ob == '12') {
                        t12 = (round(t12,dec) * 1 + round(vt,dec) * 1);
                        tiva = ((round(tice,dec) + round(t12,dec)) * 12 / 100);
                    }
                    if (ob == '0') {
                        t0 = (round(t0,dec) * 1 + round(vt,dec) * 1);
                    }
                    if (ob == 'EX') {
                        tex = (round(tex,dec) * 1 + round(vt,dec) * 1);
                    }
                    if (ob == 'NO') {
                        tno = (round(tno,dec) * 1 + round(vt,dec) * 1);
                    }

                }

                sub = round(t12,dec) + round(t0,dec) + round(tex,dec) + round(tno,dec);
                prop = $('#total_propina').val().replace(',', '');
                gtot = (round(sub,dec) * 1 + round(tiva,dec) * 1 + round(tice,dec) * 1 + round(prop,dec) * 1);
                 
                $('#subtotal12').val(t12.toFixed(dec));
                $('#subtotal0').val(t0.toFixed(dec));
                $('#subtotalex').val(tex.toFixed(dec));
                $('#subtotalno').val(tno.toFixed(dec));
                $('#subtotal').val(sub.toFixed(dec));
                $('#total_descuento').val(tdsc.toFixed(dec));
                $('#total_iva').val(tiva.toFixed(dec));
                $('#total_ice').val(tice.toFixed(dec));
                $('#total_valor').val(gtot.toFixed(dec));
                $('#pag_valor1').val(gtot.toFixed(dec));
                calculo_total_pago();
            }     

            function save() {
                      var v=0;

                        if (reg_femision.value.length == 0) {
                            $("#reg_femision").css({borderColor: "red"});
                            $("#reg_femision").focus();
                            return false;
                        } else if ($("#reg_tipo_documento").val() == '0') {
                            $("#reg_tipo_documento").css({borderColor: "red"});
                            $("#reg_tipo_documento").focus();
                            return false;
                        } else if ($("#reg_sustento").val() == '0') {
                            $("#reg_sustento").css({borderColor: "red"});
                            $("#reg_sustento").focus();
                            return false;
                        } else if ($("#reg_sustento").val() == '0') {
                            $("#reg_sustento").css({borderColor: "red"});
                            $("#reg_sustento").focus();
                            return false;
                        } else if ($("#reg_tipo_pago").val() == '0') {
                            $("#reg_tipo_pago").css({borderColor: "red"});
                            $("#reg_tipo_pago").focus();
                            return false;
                        } else if (reg_forma_pago.value.length == 0) {
                            $("#reg_forma_pago").css({borderColor: "red"});
                            $("#reg_forma_pago").focus();
                            return false;
                        } else if (reg_num_documento.value.length == 0) {
                            $("#reg_num_documento0").css({borderColor: "red"});
                            $("#reg_num_documento1").css({borderColor: "red"});
                            $("#reg_num_documento2").css({borderColor: "red"});
                            $("#reg_num_documento").focus();
                            return false;
                        } else if (reg_num_autorizacion.value.length == 0) {
                            $("#reg_num_autorizacion").css({borderColor: "red"});
                            $("#reg_num_autorizacion").focus();
                            return false;
                        } else if ($("#reg_tpcliente").val() == '0') {
                            $("#reg_tpcliente").css({borderColor: "red"});
                            $("#reg_tpcliente").focus();
                            return false;
                        } else if (reg_ruc_cliente.value.length == 0) {
                            $("#reg_ruc_cliente").css({borderColor: "red"});
                            $("#reg_ruc_cliente").focus();
                            swal("", "Ingrese nombre o ruc de proveedor", "info"); 
                            return false;
                        } else if (nombre.value.length == 0) {
                            $("#nombre").css({borderColor: "red"});
                            $("#nombre").focus();
                            return false;
                        } else if (direccion_cliente.value.length == 0) {
                            $("#direccion_cliente").css({borderColor: "red"});
                            $("#direccion_cliente").focus();
                            return false;
                        } else if (telefono_cliente.value.length == 0) {
                            $("#telefono_cliente").css({borderColor: "red"});
                            $("#telefono_cliente").focus();
                            return false;
                        } else if (email_cliente.value.length == 0 || validarEmail(email_cliente.value) == false) {
                            $("#email_cliente").css({borderColor: "red"});
                            $("#email_cliente").focus();
                            return false;
                        } else if (reg_concepto.value.length == 0) {
                            $("#reg_concepto").css({borderColor: "red"});
                            $("#reg_concepto").focus();
                            return false;
                        } 
                        var ast=0;
                        var tr = $('#lista').find("tr:last");
                        a = tr.find("input").attr("lang");
                        i = parseInt(a);
                        n = 0;
                        j = 0;
                        if(a==null){
                          //alert("Ingrese Detalle");
                          swal("", "Ingrese Detalle", "info"); 
                          return false;
                        }
                        if (i != 0) {
                            while (n < i) {
                                n++;
                                if ($('#pro_descripcion' + n).html() != null) {
                                    if ($('#pro_descripcion' + n).html().length == 0) {
                                        $('#pro_descripcion' + n).css({borderColor: "red"});
                                        $('#pro_descripcion' + n).focus();
                                        return false;
                                    } else if ($('#cantidad' + n).val().length == 0 || parseFloat($('#cantidad' + n).val()) == 0) {
                                        $('#cantidad' + n).css({borderColor: "red"});
                                        $('#cantidad' + n).focus();
                                        return false;
                                    } else if ($('#descuento' + n).val().length == 0) {
                                        $('#descuento' + n).css({borderColor: "red"});
                                        $('#descuento' + n).focus();
                                        return false;
                                    } else if ($('#pro_precio' + n).val().length == 0 || parseFloat($('#pro_precio' + n).val()) == 0) {
                                        $('#pro_precio' + n).css({borderColor: "red"});
                                        $('#pro_precio' + n).focus();
                                        return false;
                                    }else if ($('#reg_codigo_cta' + n).val().length == 0 && conf_as==0) {
                                        $('#reg_codigo_cta' + n).css({borderColor: "red"});
                                        $('#reg_codigo_cta' + n).focus();
                                        ast++;
                                    }

                                }
                            }
                          $('#verifica_cuenta').val(ast);  
                        }

                        var tr = $('#tb_pagos').find("tr:last");
                        b = tr.find("input").attr("lang");
                        j = parseInt(a);
                        m = 0;
                        while(m<j){
                          m++;
                          if ($('#pag_porcentage' + m).val() != null) {
                              if ($('#pag_porcentage' + m).val().length == 0) {
                                $('#pag_porcentage' + m).css({borderColor: "red"});
                                $('#pag_porcentage' + m).focus();
                                return false;
                              }
                              if ($('#pag_dias' + m).val().length == 0) {
                                $('#pag_dias' + m).css({borderColor: "red"});
                                $('#pag_dias' + m).focus();
                                return false;
                              }
                              if ($('#pag_valor' + m).val().length == 0) {
                                $('#pag_valor' + m).css({borderColor: "red"});
                                $('#pag_valor' + m).focus();
                                return false;
                              }
                              if ($('#pag_fecha_v' + m).val().length == 0) {
                                $('#pag_fecha_v' + m).css({borderColor: "red"});
                                $('#pag_fecha_v' + m).focus();
                                return false;
                              }
                          }    
                        }

                        if(parseFloat($('#pg_total').html())!=0){
                          //alert('La suma de los pagos no completa el Total Valor');
                          swal("", "La suma de los pagos no completa el Total Valor", "info");
                          return false;
                        }

                        

                        if(parseFloat($('#orden_compra').val())!=0 ){
                          if(parseFloat($('#orden_compra').val())!=parseFloat($('#subtotal').val())){
                            alert('El detalle fue cambiado revise movimientos \nde la orden de compra en Kardex');
                            
                          }
                        }

                        if(parseFloat($('#ingreso').val())!=0 ){
                          if(parseFloat($('#ingreso').val())!=parseFloat($('#subtotal').val())){
                            alert('El detalle fue cambiado revise movimientos \ndel ingreso en Kardex');
                            
                          }
                        }


                        if(ast>0 && conf_as==0){
                          v=1;
                          Swal.fire({
                            title: 'El detalle de factura no esta completo \ny no se creará el Asiento Contable \n¿Esta Seguro de Guardar?',
                            showDenyButton: true,
                            showCancelButton: false,
                            confirmButtonText: 'Guardar',
                            denyButtonText: 'Cancelar',
                            }).then((resultado) => {

                              if(resultado.isConfirmed){
                                $('#frm_save').submit();
                              }
                              else if (resultado.isDenied) {
                                v=1;
                              } 
                            })

                        }



                        if(v==0){
                        
                          $('#frm_save').submit();
                        }

   
               } 

      function habilitar(op){
          $('#pro_ids').val('0');
          $('#mp_a').val('0');
          $('#mp_b').val('0');
          $('#codigo').val('');
          $('#descripcion').val('');
          $('#unidad_pr').val('');
          $('#precio').val('0');
          $('#iva_pr').val('12');
          $("#modal_productos").modal('show');
        
      }     

      function load_familias(){
            uri=base_url+'reg_factura/traer_familias/'+$('#pro_ids').val();
            $.ajax({
                    url: uri,
                    type: 'POST',
                    success: function(dt){
                      $('#mp_a').html(dt);
                      load_codigo();
                    } 
              });
      }

      function validar_codigo(){
        vl = $('#codigo').val();
                $.ajax({
                  beforeSend: function () {
                      if ($('#codigo').val().length == 0) {
                            ///alert('Ingrese un codigo');
                             swal("", "Ingrese un codigo", "info");
                            return false;
                      }
                    },
                    url: base_url+"reg_factura/load_producto/"+vl,
                    type: 'JSON',
                    dataType: 'JSON',
                    success: function (dt) {
                      if (dt!='') {
                        //alert('Ya existe codigo');
                         swal("", "Ya existe codigo", "info");
                        $('#pro_descripcion').val('');
                      }
                    }
                  });
      }

      function nuevo_producto(){
        
        var data={
          "ids":$('#pro_ids').val(),
          'mp_a':$('#mp_a').val(),
          "mp_b": $('#mp_b').val(),
          "mp_c": $('#codigo').val(),
          "mp_d": $('#descripcion').val(),
          "mp_q": $('#unidad_pr').val(),
          "mp_e": $('#precio').val(),
          "mp_h": $('#iva_pr').val()
          
        }
                $.ajax({
                  beforeSend: function () {
                      if ($("#pro_ids").val() == '0') {
                        $("#pro_ids").css({borderColor: "red"});
                        $("#pro_ids").focus();
                        return false;
                      } else if ($("#mp_a").val() == '0') {
                        $("#mp_a").css({borderColor: "red"});
                        $("#mp_a").focus();
                        return false;
                      } else if ($("#mp_b").val() == '0') {
                        $("#mp_b").css({borderColor: "red"});
                        $("#mp_b").focus();
                        return false;
                      } else if ($("#codigo").val().length == 0) {
                        $("#descripcion").css({borderColor: "red"});
                        $("#descripcion").focus();
                        return false;
                      } else if ($("#descripcion").val().length == 0) {
                        $("#descripcion").css({borderColor: "red"});
                        $("#descripcion").focus();
                        return false;
                      } else if ($("#unidad_pr").val().length == 0) {
                        $("#unidad_pr").css({borderColor: "red"});
                        $("#unidad_pr").focus();
                        return false;
                      } else if ($("#precio").val().length == 0 && parseFloat($('#precio').val())==0) {
                        $("#precio").css({borderColor: "red"});
                        $("#precio").focus();
                        return false;
                      }
                    },
                    url: base_url+"reg_factura/nuevo_producto/",
                    type: 'POST',
                    data: {data:data},
                    dataType: 'JSON',
                    success: function (dt) {
                      if (dt.id!='0') {
                        $('#pro_descripcion').val(dt.id);
                        $('#productos').html(dt.lista);
                        $("#modal_productos").modal('hide');
                        load_producto();
                      }else{
                        //alert('Error al guardar producto')
                         swal("", "Error al guardar producto", "info");
                      }
                    },
                    error : function(xhr, status, error) {
                      alert(error);
                    }

                  });
      }    

      function num_factura(obj) {
                
                nfac = obj.value;
                nfac = $(' reg_num_documento0').val()+'-'+$(' reg_num_documento1').val()+'-'+$(' reg_num_documento2').val();
                // dt = nfac.split('-');
                // dt = nfac.split('-');
                if (nfac.length != 17 || dt[0].length != 3 || dt[1].length != 3 || dt[2].length != 9) {
                    $(obj).val('');
                    $('reg_num_documento0').val('');
                    $('reg_num_documento1').val('');
                    $('reg_num_documento2').val('');
                    $('reg_id').val('0');
                    $(obj).focus();
                    $(obj).css({borderColor: "red"});
                    //alert('No cumple con la estructura ejem: 000-000-000000000');
                     Swal.fire("", "No cumple con la estructura ejem: 000-000-000000000", "info");
                } else{
                  $('#reg_num_documento').val(num_doc);
                  doc_duplicado();
                }
            } 
        function val_factura() {
                
                nfac = $('#reg_num_documento0').val()+'-'+$('#reg_num_documento1').val()+'-'+$('#reg_num_documento2').val();
                 dt = nfac.split('-');
                if (nfac.length != 17 || dt[0].length != 3 || dt[1].length != 3 || dt[2].length != 9) {
                    $(obj).val('');
                    $('#reg_num_documento0').val('');
                    $('#reg_num_documento1').val('');
                    $('#reg_num_documento2').val('');
                    $('#reg_id').val('0');
                    $(obj).focus();
                    $(obj).css({borderColor: "red"});
                    //alert('No cumple con la estructura ejem: 000-000-000000000');
                     Swal.fire("", "No cumple con la estructura ejem: 000-000-000000000", "info");
                } else{
                  $('#reg_num_documento').val(nfac);
                  doc_duplicado();
                }
            } 

      function load_cuenta(obj,opc) {
              var uri = base_url+'reg_factura/traer_cuenta/'+ $(obj).val();
              j=obj.lang;
              $.ajax({
                  url: uri, //this is your uri
                  type: 'GET', //this is your method
                  dataType: 'json',
                  success: function (response) {
                    console.log(response);
                   
                    if(opc==0){
                      $("#pln_id").val(response['pln_id']);  
                     
                    }else{
                      $("#pln_id"+j).val(response['pln_id']);
                      
                    }  
                      
                  },
                  error : function(xhr, status) {
                      //alert('No existe Cuenta');
                     Swal.fire("", "No existe cuenta", "info");
                      if(opc==0){
                        $("#pln_id").val('0');
                        $("#reg_codigo_cta").val('');
                      }else{
                        $("#pln_id"+j).val('0');
                        $("#reg_codigo_cta"+j).val('');
                      }
                        
                  }
              });
          }        

    function val_cuenta(obj) {
              var uri = base_url+'reg_factura/traer_cuenta/'+ $(obj).val();
              j=obj.lang;
              $.ajax({
                  url: uri, //this is your uri
                  type: 'GET', //this is your method
                  dataType: 'json',
                  success: function (response) {
                   
                    if(opc==0){
                      $("#pln_id").val(response['pln_id']);  
                     
                    }else{
                      $("#pln_id"+j).val(response['pln_id']);
                      
                    }  
                      
                  },
                  error : function(xhr, status) {
                      //alert('No existe Cuenta');
                     Swal.fire("", "No existe cuenta", "info");
                      $("#pln_id").val('0');
                        $("#reg_codigo_cta").val('');
                        
                  }
              });
          }
    function load_tipos(){
      uri=base_url+'reg_factura/traer_tipos/'+$('#pro_ids').val();
            $.ajax({
                    url: uri,
                    type: 'POST',
                    success: function(dt){
                       $('#mp_b').html(dt);
                       load_codigo();
                    } 
              });
       
    }

    function load_codigo(){
              var id1 =  $('#mp_a').val();
              var id2 =  $('#mp_b').val();

              if (id1 != 0 && id2 != 0) {
              $.ajax({
                    url: base_url+"reg_factura/traer_codigo/"+id1+"/"+id2,
                    type: 'POST',
                    success: function(dt){

                    dat = dt.split("&&");
                    if (dat[0] != 1) {
                        $('#codigo').val(dat[0]);
                        $('#codigo').attr('readonly', true);
                    } 

                }
              });

            }else{
              
                $('#codigo').val('');
            }
          }
    function calculo_fecha(){
      obj = $(".itm1");
      var tr = $('#tb_pagos').find("tr:last");
      var a = tr.find("input").attr("lang");
      var i=parseInt(a);
      n = 1;


      while (n <= i) {
        if ($('#pag_porcentage' + n).val() != null) {
          if ($('#pag_dias' + n).val().length != 0) {
              var sumarDias = parseInt($('#pag_dias' + n).val());
              var fecha = $('#reg_femision').val();
              fecha = fecha.replace("-", "/").replace("-", "/");
              fecha = new Date(fecha);
              fecha.setDate(fecha.getDate() + sumarDias);
              var anio = fecha.getFullYear();
              var mes = fecha.getMonth() + 1;
              var dia = fecha.getDate();
              if (mes.toString().length < 2) {
                  mes = "0".concat(mes);
              }
              if (dia.toString().length < 2) {
                  dia = "0".concat(dia);
              }
              $('#pag_fecha_v' + n).val(anio + "-" + mes + "-" + dia);
             
          }else{
            $('#pag_fecha_v' + n).val('');
          }
        }  
        
        n++;
      }

    } 

    function calculo_total_pago() {
                var t = 0;
                var tp = 0;
                obj = $(".itm1");
                total = $("#total_valor").val();
                var tr = $('#tb_pagos').find("tr:last");
                var a = tr.find("input").attr("lang");
                var i=parseInt(a);
                var n = 1;
                while (n <= i) {
                  if($("#pag_porcentage" + n).val()!=null){
                    por = $("#pag_porcentage" + n).val();
                    vpago = (round(por,dec) * round(total,dec) / 100);
                    $("#pag_valor" + n).val(parseFloat(vpago).toFixed(dec));
                    t+= round(($("#pag_valor" + n).val() * 1),dec);
                    tp += round(($("#pag_porcentage" + n).val() * 1),dec);
                    if(tp>100){
                      $("#pag_porcentage" + n).val('0');
                      $("#pag_valor" + n).val('0');
                      //alert('El Total del porcentaje no debe ser mayor al 100%');
                      swal("", "El Total del porcentaje no debe ser mayor al 100 %", "info");
                      return false;
                    }

                    ///ultimo pago suma 1 centavo
                    tg=round(total,dec)-round(t,dec);
                    if(round(tg,dec)==0.01){
                      pg_valor=$("#pag_valor" + n).val();
                      ult_pg=round(pg_valor,dec)+round(tg,dec);
                      $("#pag_valor" + n).val(ult_pg.toFixed(dec));
                      tg=0;
                    }

                  }
                    n++;
                }
                
                $("#pg_total").html(tg.toFixed(dec));
                $("#pg_por").html(tp.toFixed(dec));
            }  

      function generar_retencion(registro){
  
        Swal.fire({
          title: '¿Desea Generar Retencion?',
          showDenyButton: true,
          showCancelButton: false,
          confirmButtonText: 'Si',
          denyButtonText: 'No'
          }).then((result) => {
            if (result.isConfirmed) {
              url=ret_url+'/'+registro;
              window.location.assign(url);
            } 
            else if(result.isDenied) {
              url2='<?php echo $cancelar;?>';
              window.location.assign(url2);
            } 
        })  
      }    

      
    </script>

