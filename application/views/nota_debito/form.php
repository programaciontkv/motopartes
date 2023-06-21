<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<section class="content-header">
      <!-- <h1>
        Nota de Debito <?php echo $titulo?>
      </h1> -->
</section>
<section class="content">
      <div class="row">
        <div class="col-md-12">
          <?php 
          $dec=$dec->con_valor;
          $dcc=$dcc->con_valor;
          $ctrl_inv=$ctrl_inv->con_valor;
          $inven=$inven->con_valor;
          $cprec=$cprec->con_valor;
          $cdesc=$cdesc->con_valor;
          
          if($inven==0){
            $hid_inv='';
            $col_obs='8';
          }else{
            $hid_inv='hidden';
            $col_obs='8';
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
                 <table class="table col-sm-12" border="0">
                    <tr>
                      <td class="col-sm-12">
                        <div class="box-body">
                        <div class="panel panel-default col-sm-12">
                        <div class="panel panel-heading"><label>Datos Generales</label></div>
                        <table class="table">
                          <tr>
                               <td><label>Fecha Emision:</label></td>
                              <td>
                              <div class="form-group <?php if(form_error('ndb_fecha_emision')!=''){ echo 'has-error';}?> ">
                                <input type="date" class="form-control" name="ndb_fecha_emision" id="ndb_fecha_emision" value="<?php if(validation_errors()!=''){ echo set_value('ndb_fecha_emision');}else{ echo  $nota->ndb_fecha_emision;}?>">
                                  <?php echo form_error("ndb_fecha_emision","<span class='help-block'>","</span>");?>
                                </div>
                                <input type="hidden" class="form-control" name="emp_id" id="emp_id" value="<?php if(validation_errors()!=''){ echo set_value('emp_id');}else{ echo  $nota->emp_id;}?>">
                                <input type="hidden" class="form-control" name="emi_id" id="emi_id" value="<?php if(validation_errors()!=''){ echo set_value('emi_id');}else{ echo   $nota->emi_id;}?>">
                                <input type="hidden" class="form-control" name="cja_id" id="cja_id" value="<?php if(validation_errors()!=''){ echo set_value('cja_id');}else{ echo  $nota->cja_id;}?>">
                                <input type="hidden" class="form-control" name="fac_id" id="fac_id" value="<?php if(validation_errors()!=''){ echo set_value('fac_id');}else{ echo  $nota->fac_id;}?>">
                                </div>
                              </td>
                                  <td><label>Vendedor</label></td>
                              <td>
                                <div class="form-group ">
                                  <select name="vnd_id"  id="vnd_id" class="form-control">
                                    <option value="">SELECCIONE</option>
                                     <?php
                                    if(!empty($vendedores)){
                                      foreach ($vendedores as $vendedor) {
                                    ?>
                                    <option value="<?php echo $vendedor->vnd_id?>"><?php echo $vendedor->vnd_nombre?></option>
                                    <?php
                                      }
                                    }
                                  ?>
                                  </select>
                                  <script type="text/javascript">
                                    var vnd='<?php echo $nota->vnd_id;?>';
                                    vnd_id.value=vnd;
                                  </script>
                                </div>
                              </td>    
                          </tr>
                          <tr>
                              <td><label>Factura No:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('ndb_num_comp_modifica')!=''){ echo 'has-error';}?> ">
                                  <input type="text" class="form-control" name="ndb_num_comp_modifica" id="ndb_num_comp_modifica" value="<?php if(validation_errors()!=''){ echo set_value('ndb_num_comp_modifica') ;}else{ echo  $nota->ndb_num_comp_modifica;}?>" onchange="num_factura(this)" maxlength="17">
                                  <?php echo form_error("ndb_num_comp_modifica","<span class='help-block'>","</span>");?>
                                </div>
                              </td>
                              <td><label>Fecha Factura:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('ndb_fecha_emi_comp')!=''){ echo 'has-error';}?> ">
                                  <input type="date" class="form-control" name="ndb_fecha_emi_comp" id="ndb_fecha_emi_comp" value="<?php if(validation_errors()!=''){ echo set_value('ndb_fecha_emi_comp');}else{ echo   $nota->ndb_fecha_emi_comp;}?>" readonly>
                                  <?php echo form_error("ndb_fecha_emi_comp","<span class='help-block'>","</span>");?>
                                </div>
                              </td>
                          </tr>
                          <tr>    
                              <td><label>RUC/CI:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('identificacion')!=''){ echo 'has-error';}?> ">
                                  <input type="text" class="form-control" name="identificacion" id="identificacion" value="<?php if(validation_errors()!=''){ echo set_value('identificacion');}else{ echo $nota->ndb_identificacion;}?>" list="list_clientes" onchange="traer_cliente(this)" readonly>
                                  <?php echo form_error("identificacion","<span class='help-block'>","</span>");?>
                                </div>
                              </td>
                            <td><label>Nombre:</label></td>
                            <td >
                              <div class="form-group <?php if(form_error('nombre')!=''){ echo 'has-error';}?> ">
                                <input type="text" class="form-control" name="nombre" id="nombre" value="<?php if(validation_errors()!=''){ echo set_value('nombre');}else{ echo $nota->ndb_nombre;}?>" readonly>
                                    <?php echo form_error("nombre","<span class='help-block'>","</span>");?>
                                
                                </div>
                                <input type="hidden" class="form-control" name="cli_id" id="cli_id" value="<?php if(validation_errors()!=''){ echo set_value('cli_id');}else{ echo $nota->cli_id;}?>" >
                              </td>
                          </tr>
                          <tr>
                            <td><label>Direccion:</label></td>
                            <td >
                              <div class="form-group <?php if(form_error('direccion_cliente')!=''){ echo 'has-error';}?> ">
                                <input type="text" class="form-control" name="direccion_cliente" id="direccion_cliente" value="<?php if(validation_errors()!=''){ echo set_value('direccion_cliente');}else{ echo $nota->ndb_direccion;}?>" readonly>
                                <?php echo form_error("direccion_cliente","<span class='help-block'>","</span>");?>
                                </div>
                              </td>
                              <td><label>Telefono:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('telefono_cliente')!=''){ echo 'has-error';}?> ">
                                  <input type="text" class="form-control" name="telefono_cliente" id="telefono_cliente" value="<?php if(form_error('telefono_cliente')){ echo set_value('telefono_cliente');}else{ echo $nota->ndb_telefono;}?>" readonly>
                                      <?php echo form_error("telefono_cliente","<span class='help-block'>","</span>");?>
                                  
                                  </div>
                              </td>
                            </tr>
                            <tr>
                              <td><label>Email:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('email_cliente')!=''){ echo 'has-error';}?> ">
                                  <input type="email" class="form-control" name="email_cliente" id="email_cliente" value="<?php if(validation_errors()!=''){ echo set_value('email_cliente');}else{ echo $nota->ndb_email;}?>" readonly>
                                  <?php echo form_error("email_cliente","<span class='help-block'>","</span>");?>
                                  </div>
                              </td> 
                            </tr>
                           
                          </table>
                          </div>
                          </div>
                        </td>
                    </tr>
                    <tr>
                       <td class="col-sm-12" colspan="2">
                          <div class="box-body">
                          <div class="panel panel-default col-sm-8">
                          
                          <table class="table table-bordered table-striped" id="tbl_detalle">
                            <thead>
                              <tr>
                                <th>Item</th>
                                <th>Razon de la Modificacion</th>
                                <th>Valor Modificacion</th>
                                <th></th>
                              </tr>
                            </thead>

                            <tbody id="lista_encabezado">
                            
                              <?php
                            
                                $cnt_detalle=0;
                                    
                                    
                                  ?>
                                    <tr>
                                        <td colspan="2" class="td1">
                                            <input style="text-align:left " type="text" style="width:  150px;" class="form-control" id="descripcion" name="descripcion"  value="" lang="1" />
                                        </td>
                                        
                                        <td style="text-align:center; width:  100px;">
                                          <input type ="text" size="7" style="text-align:right; width:  100px;" id="cantidad" name="cantidad" value="0" lang="1" class="form-control decimal"/>
                                        </td>

                                        <td style="width:  100px; text-align: center" ><input  type="button" name="add1" id="add1" class="btn btn-primary fa fa-plus" onclick="validar('#tbl_detalle','0')" lang="1" value='+'/> </td>
                                        
                                    </tr>
                              <?php 
                              ?>    
                                </tbody>        
                                <tbody id="lista"></tbody>
                            <tfoot>
                                <tr>

                                    <td colspan="2" align="right">Subtotal 12%:</td>
                                    <td>
                                        <input style="text-align:right; width:  100px;" type="text" class="form-control" id="subtotal12" name="subtotal12" value="<?php echo str_replace(',', '', number_format($nota->ndb_subtotal12, $dec)) ?>" readonly/>
                                    </td>
                                    <td><input type="radio" id="st1" name="st" onclick="calculo()" checked/></td></td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right">Subtotal 0%:</td>
                                    <td>
                                        <input style="text-align:right; width:  100px;" type="text" class="form-control" id="subtotal0" name="subtotal0" value="<?php echo str_replace(',', '', number_format($nota->ndb_subtotal0, $dec)) ?>" readonly/>
                                    </td>
                                    <td><input type="radio" id="st2" name="st" onclick="calculo()"/></td></td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right">Subtotal Excento de Iva:</td>
                                    <td><input style="text-align:right; width:  100px;" type="text" class="form-control" id="subtotalex" name="subtotalex" value="<?php echo str_replace(',', '', number_format($nota->ndb_subtotal_ex_iva, $dec)) ?>" readonly/>
                                    </td>
                                    <td><input type="radio" id="st3" name="st" onclick="calculo()"/></td></td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right">Subtotal no objeto de Iva:</td>
                                    <td><input style="text-align:right; width:  100px;" type="text" class="form-control" id="subtotalno" name="subtotalno" value="<?php echo str_replace(',', '', number_format($nota->ndb_subtotal_no_iva, $dec)) ?>" readonly/>
                                    </td>
                                    <td><input type="radio" id="st4" name="st" onclick="calculo()"/></td></td>
                                </tr>


                                <tr>
                                    <td colspan="2" align="right">Subtotal sin Impuestos:</td>
                                    <td><input style="text-align:right; width:  100px;" type="text" class="form-control" id="subtotal" name="subtotal" value="<?php echo str_replace(',', '', number_format($nota->ndb_subtotal, $dec)) ?>" readonly/>
                                    </td>
                                </tr>
                               
                                <tr>
                                    <td colspan="2" align="right">Total IVA:</td>
                                    <td><input style="text-align:right; width:  100px;" type="text" class="form-control" id="total_iva" name="total_iva" value="<?php echo str_replace(',', '', number_format($nota->ndb_total_iva, $dec)) ?>" readonly />
                                    </td>
                                </tr> 
                                <tr>
                                    <td colspan="2" align="right">Total Valor:</td>
                                    <td><input style="text-align:right; width:  100px;;font-size:15px;color:red  " type="text" class="form-control" id="total_valor" name="total_valor" value="<?php echo str_replace(',', '', number_format($nota->ndb_total_valor, $dec)) ?>" readonly />
                                        
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
                                
                <input type="hidden" class="form-control" name="ndb_id" value="<?php echo $nota->ndb_id?>">
                <input type="hidden" class="form-control" id="count_detalle" name="count_detalle" value="<?php echo $cnt_detalle?>">
                <input type="hidden" class="form-control" id="saldo" name="saldo" value="0">
              <div class="box-footer">
                <?php
                if($valida_asiento==0){ 
                ?>
                <button type="button" class="btn btn-primary" onclick="save()">Guardar</button>
                <?php
                }
                ?>
                <a href="<?php echo $cancelar;?>" class="btn btn-default">Cancelar</a>
              </div>

            </form>
          </div>
         </div>
      <!-- /.row -->
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
    
    <!-- ////modal -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Facturas</h4>
        </div>
        <div class="modal-body">
          <table class="table table-striped">
              <thead>
                  <th>Seleccione</th>
                  <th>Fecha</th>
                  <th>Tipo</th>
                  <th>Numero</th>
                  <th>CI/RUC</th>
                  <th>Cliente</th>
              </thead>
              <tbody id="det_ventas"></tbody>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
      
    </div>
  </div>
  


    <style type="text/css">
      .panel{
        margin-bottom: 0px !important;
        margin-top: 0px !important;
        padding-bottom: 0px !important;
        padding-top: 0px !important;
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
      }
    </style>
    

<script >

      var base_url='<?php echo base_url();?>';
      var inven='<?php echo $inven;?>';
      var ctr_inv='<?php echo $ctrl_inv;?>';
      var dec='<?php echo $dec;?>';
      var dcc='<?php echo $dcc;?>';
      var valida_asiento='<?php echo $valida_asiento;?>';
      window.onload = function () {
        if(valida_asiento==1){
          swal("", "No se puede crear Documento \nRevise Configuracion de cuentas", "info");          
        }
      }

      function validar_decimal(obj){
        obj.value = (obj.value + '').replace(/[^0-9.]/g, '');
      }
      
      function num_factura(obj) {
        nfac = obj.value;
        dt = nfac.split('-');
        if (nfac.length != 17 || dt[0].length != 3 || dt[1].length != 3 || dt[2].length != 9) {
          $(obj).val('');
          $('fac_id').val('0');
          $(obj).focus();
          $(obj).css({borderColor: "red"});
          swal("Error!", "No cumple con la estructura ejem: 000-000-000000000.!", "error");
          limpiar_nota();                    
        } else {
          traer_facturas(obj);
        }
      }

      function traer_facturas(obj) {
        $.ajax({
                  beforeSend: function () {
                      if ($('#ndb_num_comp_modifica').val().length == 0) {
                             swal("Error!", "Ingrese una factura.!", "error");
                            return false;
                      }
                    },
                  url: base_url+"nota_debito/traer_facturas/"+$('#ndb_num_comp_modifica').val()+"/"+emi_id.value,
                  type: 'JSON',
                  dataType: 'JSON',
                  success: function (dt) { 
                    i=dt.length;
                    if(i>0){
                        n=0;
                         load_factura(dt[n]['fac_id']);
                    }else{
                        swal("Error!", "No existe Factura \nSe creara Nota de debito sin Factura.!", "error");
                        limpiar_nota();
                    }
                  }
          })
        }

        function load_factura(vl) {
              
              $.ajax({
                  beforeSend: function () {
                      
                    },
                    url: base_url+"nota_debito/load_factura/"+vl+"/"+inven+"/"+ctr_inv+"/"+dec+"/"+dcc,
                    type: 'JSON',
                    dataType: 'JSON',
                    success: function (dt) {
                            if (dt.length != '0') {

                                $('#fac_id').val(dt.fac_id);
                                $('#ndb_fecha_emi_comp').val(dt.fac_fecha_emision);
                                $('#identificacion').val(dt.cli_ced_ruc);
                                $('#nombre').val(dt.cli_raz_social);
                                $('#direccion_cliente').val(dt.cli_calle_prin);
                                $('#telefono_cliente').val(dt.cli_telefono);
                                $('#email_cliente').val(dt.cli_email);
                                $('#cli_id').val(dt.cli_id);
                                $('#identificacion').attr('readonly', true);
                                $('#nombre').attr('readonly', true);
                                $('#direccion_cliente').attr('readonly', true);
                                $('#telefono_cliente').attr('readonly', true);
                                $('#email_cliente').attr('readonly', true);
                                
                                calculo();
                            } else {
                                limpiar_nota();
                            }
                    }
                })
        }

        function validar(table, opc){
              var tr1 = $(table).find("tbody tr:last");
              var a1 = tr1.find("input").attr("lang");
              
                if($('#cantidad').val().length!=0 &&  parseFloat($('#cantidad').val())>0  && $('#descripcion').val().length!=0){
                  clona_detalle(table);
                }
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
               
                    i = j + 1;
                    var fila = "<tr>"+
                                        "<td id='item"+i+"' lang='"+i+"' align='center'>"+
                                          i+
                                        "</td>"+
                                        "<td>"+
                                          "<input type ='text' class='form-control' size='10' id='pro_descripcion"+i+"' name='descripcion"+i+"' lang='"+i+"' value='"+descripcion.value +"' readonly/>"+"</td>"+
                                        "<td>"+
                                          "<input type ='text' size='7' style='text-align:right' id='cantidad"+i+"' name='cantidad"+i+"' onchange='calculo()' value='"+cantidad.value+"' lang='"+i+"' class='form-control decimal' onkeyup='validar_decimal(this)' />"+
                                        "</td>"+
                                        "<td onclick='elimina_fila_det(this)' align='center' >"+"<span class='btn btn-danger fa fa-trash'>"+"</span>"+"</td>"+
                                    "</tr>";
                         
                $('#lista').append(fila);
                $('#count_detalle').val(i);
                descripcion.value = '';
                cantidad.value = '';
                $('#cantidad').css({borderColor: ""});
                $('#descripcion').focus();
                calculo();
                
        } 

        function elimina_fila_det(obj) {
            var parent = $(obj).parents();
            $(parent[0]).remove();
            calculo();
        }
 
        function round(value, decimals) {
            return Number(Math.round(value+'e'+decimals)+'e-'+decimals);
        }

        function calculo() {
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

                if($('#st1').prop('checked')==true){
                  ob='12';
                }else if($('#st2').prop('checked')==true){
                  ob='0';
                }else if($('#st3').prop('checked')==true){
                  ob='EX';
                }else if($('#st4').prop('checked')==true){
                  ob='NO';
                }

                while (n < i) {
                    n++;
                    if ($('#item' + n).val() == null) {
                        vt = 0;
                    } else {
                        vt = $('#cantidad' + n).val().replace(',', '');
                        if(vt==''){
                          vt=0;
                        }
                        
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
                sub1 = round(t0,dec) + round(tex,dec) + round(tno,dec);
                gtot = round(sub,dec) * 1 + round(tiva,dec) * 1;
                 
                $('#subtotal12').val(t12.toFixed(dec));
                $('#subtotal0').val(t0.toFixed(dec));
                $('#subtotalex').val(tex.toFixed(dec));
                $('#subtotalno').val(tno.toFixed(dec));
                $('#subtotal').val(sub.toFixed(dec));
                $('#total_iva').val(tiva.toFixed(dec));
                $('#total_valor').val(gtot.toFixed(dec));
            } 
      function save() {
                        if (ndb_num_comp_modifica.value.length == 0) {
                            $("#ndb_num_comp_modifica").css({borderColor: "red"});
                            $("#ndb_num_comp_modifica").focus();
                            return false;
                        } else if (ndb_fecha_emi_comp.value.length == 0) {
                            $("#ndb_fecha_emi_comp").css({borderColor: "red"});
                            $("#ndb_fecha_emi_comp").focus();
                            return false;
                        } else if (identificacion.value.length == 0) {
                            $("#identificacion").css({borderColor: "red"});
                            $("#identificacion").focus();
                            return false;
                        } 

                        var tr = $('#lista').find("tr:last");
                        a = tr.find("input").attr("lang");
                        i = parseInt(a);
                        n = 0;
                        j = 0;
                        k = 0;
                        if(a==null){
                          swal("Error!", "Ingrese Detalle.!", "error");
                          return false;
                        }
                        if (i != 0) {
                            while (n < i) {
                                n++;

                                if ($('#descripcion' + n).val() != null && parseFloat($('#cantidad' + n).val())>0) {
                                    k++;
                                    if ($('#descripcion' + n).val().length == 0) {
                                        $('#descripcion' + n).css({borderColor: "red"});
                                        $('#descripcion' + n).focus();
                                        return false;
                                    } else if ($('#cantidad' + n).val().length == 0) {
                                        $('#cantidad' + n).css({borderColor: "red"});
                                        $('#cantidad' + n).focus();
                                        return false;
                                    } 

                                }
                            }
                        }

                        if(k!=0){
                           swal("Error!", "No se puede Guardar Nota de debito con cantidades en 0.!", "error");
                          return false;
                        }
                        
                        if ($('#vnd_id').val() == 0 || $('#vnd_id').val() == '') {
                            $('#vnd_id').css({borderColor: "red"});
                            $('#vnd_id').focus();
                            return false;
                        }

                       
                        
                     $('#frm_save').submit();   
               }   

      
</script>