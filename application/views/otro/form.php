
<section class="content-header">
      <h1>
        Otros
      </h1>
</section>
<section class="content">
      <div class="row">
        <div class="col-md-6">
          <?php 
          $dec = $dec->con_valor;
          $dcc = $dcc->con_valor;
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
            <form role="form" action="<?php echo $action?>" method="post" autocomplete="off" enctype="multipart/form-data">
              <div class="box-body" >
                 <table class="table col-sm-12" border="0">
                    <tr>
                      <td class="col-sm-5">
                        <div class="box-body">
                        <div class="panel panel-default col-sm-12">
                        
                        <table class="table">
                            <tr>
                              <td><label>Familia:</label></td>
                              <td colspan="2">
                                <div class="form-group <?php if(form_error('mp_a')!=''){ echo 'has-error';}?>">
                                    <select name="mp_a" id="mp_a" class="form-control" onchange="load_tipo()">
                                      <option value="">SELECCIONE</option>
                                      <?php
                                          if(validation_errors()==''){
                                                $fam=$producto->mp_a;
                                              }else{
                                                $fam=set_value('mp_a');
                                          }
                                          if(!empty($familias)){
                                            foreach ($familias as $familia) {
                                      ?>
                                          <option value='<?php echo $familia->tps_id?>'><?php echo $familia->tps_nombre?></option>
                                      <?php        
                                            }
                                          }
                                      ?>
                                    </select>
                                    <?php echo form_error("mp_a","<span class='help-block'>","</span>");?>
                                  </div>
                                  <script type="text/javascript">
                                      var fam='<?php echo $fam?>';
                                      mp_a.value=fam;
                                  </script>
                                </td>
                            </tr>
                            <tr>
                                <td><label>Tipo</label></td>
                                <td colspan="2">
                                  <div class="form-group <?php if(form_error('mp_b')!=''){ echo 'has-error';}?> ">
                                    <select name="mp_b"  id="mp_b" class="form-control">
                                      <option value="">SELECCIONE</option>
                                      <?php
                                          if(!empty($tipos)){
                                            foreach ($tipos as $tipo) {
                                      ?>
                                          <option value='<?php echo $tipo->tps_id?>'><?php echo $tipo->tps_nombre?></option>
                                      <?php        
                                            }
                                          }
                                      ?>
                                    </select>
                                    <?php 
                                      if(validation_errors()==''){
                                        $tip=$producto->mp_b;
                                      }else{
                                        $tip=set_value('mp_b');
                                      }
                                    ?>
                                    <script type="text/javascript">
                                        var tip='<?php echo $tip?>';
                                        mp_b.value=tip;
                                    </script>
                                    <?php echo form_error("mp_b","<span class='help-block'>","</span>");?>
                                  </div>
                                </td>
                            </tr>
                            <tr>
                                <td><label>Codigo:</label></td>
                                <td>
                                  <div class="form-group <?php if(form_error('mp_c')!=''){ echo 'has-error';}?> ">
                                    <input type="text" class="form-control" name="mp_c" id="mp_c" value="<?php if(validation_errors()!=''){ echo set_value('mp_c');}else{ echo $producto->mp_c;}?>" maxlength='25'>
                                    <?php echo form_error("mp_c","<span class='help-block'>","</span>");?>
                                  </div>
                                </td>
                            </tr>
                            <tr>
                                <td><label>Codigo Aux:</label></td>
                                <td>
                                  <div class="form-group <?php if(form_error('mp_n')!=''){ echo 'has-error';}?> ">
                                    <input type="text" class="form-control" name="mp_n" id="mp_c" value="<?php if(validation_errors()!=''){ echo set_value('mp_n');}else{ echo $producto->mp_n;}?>" maxlength='25'>
                                    <?php echo form_error("mp_n","<span class='help-block'>","</span>");?>
                                  </div>
                                </td>
                            </tr>
                            <tr>
                                <td><label>Descripcion:</label></td>
                                <td colspan="2">
                                  <div class="form-group <?php if(form_error('mp_d')!=''){ echo 'has-error';}?> ">
                                    <input type="text" class="form-control" name="mp_d" id="mp_d" value="<?php if(validation_errors()!=''){ echo set_value('mp_d');}else{ echo $producto->mp_d;}?>" maxlength='50'>
                                    <?php echo form_error("mp_d","<span class='help-block'>","</span>");?>
                                  </div>
                                </td>
                            </tr>
                            <tr>
                                <td><label>Unidad:</label></td>
                                <td>
                                  <div class="form-group">
                                    <select id="mp_q" name="mp_q" class="form-control" onchange="cambio_unidad(),load_bobinados();">
                                            <option value=''>Seleccione</option>
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
                                    <?php 
                                      if(validation_errors()==''){
                                                $uni=$producto->mp_q;
                                              }else{
                                                $uni=set_value('mp_q');
                                      }
                                    ?>
                                    <script>
                                            var puni = '<?php echo $uni?>';
                                            mp_q.value=puni;
                                    </script>
                                    
                                  </div>
                                </td>
                                
                            </tr>
                            <tr>
                              <td><label>Precio1:</label></td>
                              <td colspan="2">
                                  <div class="form-group <?php if(form_error('mp_e')!=''){ echo 'has-error';}?> ">
                                    <input type="text" class="form-control decimal" name="mp_e" id="mp_e" value="<?php if(validation_errors()!=''){ echo set_value('mp_e');}else{ echo $producto->mp_e;}?>" onchange="validar_decimal(this)">
                                    <?php echo form_error("mp_e","<span class='help-block'>","</span>");?>
                                  </div>
                                </td>
                            </tr>
                            <tr>
                              <td><label>Precio2:</label></td>
                              <td colspan="2">
                                  <div class="form-group <?php if(form_error('mp_f')!=''){ echo 'has-error';}?> ">
                                    <input type="text" class="form-control decimal" name="mp_f" id="mp_f" value="<?php if(validation_errors()!=''){ echo set_value('mp_f');}else{ echo $producto->mp_f;}?>" onchange="validar_decimal(this)">
                                    <?php echo form_error("mp_f","<span class='help-block'>","</span>");?>
                                  </div>
                                </td>
                            </tr>
                            <tr>
                              <td><label>Descuento:</label></td>
                              <td colspan="2">
                                  <div class="form-group <?php if(form_error('mp_g')!=''){ echo 'has-error';}?> ">
                                    <input type="text" class="form-control decimal" name="mp_g" id="mp_g" value="<?php if(validation_errors()!=''){ echo set_value('mp_g');}else{ echo $producto->mp_g;}?>" onchange="validar_decimal(this)">
                                    <?php echo form_error("mp_g","<span class='help-block'>","</span>");?>
                                  </div>
                                </td>
                            </tr>
                            <tr>
                              <td><label>Iva:</label></td>
                              <td colspan="2">
                                  <div class="form-group <?php if(form_error('mp_h')!=''){ echo 'has-error';}?> ">
                                          <select name="mp_h" id="mp_h" class="form-control">
                                            <option value="12">12</option>
                                            <option value="0">0</option>
                                            <option value="NO">NO OBJETO</option>
                                            <option value="EX">EXCENTO</option>
                                          </select>
                                   <?php 
                                      if(validation_errors()==''){
                                                $iva=$producto->mp_h;
                                              }else{
                                                $iva=set_value('mp_h');
                                      }
                                    ?>
                                    <script>
                                            var iva = '<?php echo $iva?>';
                                            mp_h.value=iva;
                                    </script>
                                  </div>
                                </td>
                            </tr>
                            <tr>
                              <td><label>Propiedad2:</label></td>
                              <td colspan="2">
                                  <div class="form-group <?php if(form_error('mp_o')!=''){ echo 'has-error';}?> ">
                                    <input type="text" class="form-control" name="mp_o" id="mp_o" value="<?php if(validation_errors()!=''){ echo set_value('mp_o');}else{ echo $producto->mp_o;}?>" maxlength='50'>
                                    <?php echo form_error("mp_o","<span class='help-block'>","</span>");?>
                                  </div>
                                </td>
                            </tr>
                            <tr>
                              <td><label>Estado:</label></td>
                              <td colspan="2">
                                <div class="form-group ">
                                  <select name="mp_i"  id="mp_i" class="form-control">
                                     <?php
                                    if(!empty($estados)){
                                      foreach ($estados as $estado) {
                                    ?>
                                    <option value="<?php echo $estado->est_id?>"><?php echo $estado->est_descripcion?></option>
                                    <?php
                                      }
                                    }
                                  ?>
                                  </select>
                                  <?php 
                                    if(validation_errors()==''){
                                      $est=$producto->mp_i;
                                    }else{
                                      $est=set_value('mp_i');
                                    }
                                  ?>
                                  <script type="text/javascript">
                                    var est='<?php echo $est;?>';
                                    mp_i.value=est;
                                  </script>
                                </div>
                              </td>
                            </tr>
                          </table>
                          </div>
                          </div>
                          </td> 
                          
                    </tr>
                  </table>
              </div>
                                
                <input type="hidden" class="form-control" name="id" value="<?php echo $producto->id?>">
                <input type="hidden" class="form-control" name="ids" value="<?php echo $producto->ids?>">
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="<?php echo base_url().'otro/'.$opc_id?>" class="btn btn-default">Cancelar</a>
              </div>

            </form>
          </div>
         
      </div>
      <!-- /.row -->
    </section>
    
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
        margin-bottom: 5px !important;
        margin-top: 5px !important;
        padding-bottom: 5px !important;
        padding-top: 5px !important;
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
      var dec='<?php echo $dec; ?>';
      var dcc='<?php echo $dcc; ?>';

      function uploadAjax() {
                var nom = 'direccion';
                var inputFileImage = document.getElementById(nom);
                var file = inputFileImage.files[0];
                var data = new FormData();
                propiedad='mp_aa';
                data.append(propiedad, file);
                $.ajax({
                    beforeSend: function () {
                      if ($('#mp_c').val().length == 0) {
                            alert('Ingrese un codigo');
                            return false;
                        }
                        if ($('#direccion').val().length == 0) {
                            alert('Ingrese una imagen');
                            return false;
                        }
                    },
                    url: base_url+"upload/subir_imagen/"+propiedad+"/"+mp_c.value,
                    type: 'POST',
                    contentType: false,
                    data: data,
                    processData: false,
                    cache: false,
                    success: function (dt) {
                        dat = dt.split('&&');
                        if (dat[0] == 0) {
                            $('#mp_aa').val(dat[1]);
                            $('#fotografia').prop('src', base_url+'imagenes/'+dat[1]);
                        } else {
                            $('#mp_aa').val('');
                            $('#direccion').val('');
                            $('#fotografia').prop('src','');
                        }
                    }
                })
            }

            
            function load_tipo(){
            
            uri=base_url+'otro/traer_tipos/'+$('#mp_a').val();
            $.ajax({
                    url: uri,
                    type: 'POST',
                    success: function(dt){
                       $('#mp_b').html(dt);
                    } 
              });
           }

          
            function load_imagen(){
                $.ajax({
                    url: base_url+"otro/load_imagen/"+$("#pro_propiedad1").val(),
                    type: 'POST',
                    success: function(dt){
                    dat = dt.split("&&");
                    if (dat[0].length != 0) {
                        $('#fotografia1').prop('src', base_url+'imagenes/'+dat[0]);
                        $('#pro_mp18').val(dat[1]);
                    } else {
                        $('#fotografia1').prop('src', dt);
                        $('#pro_mp18').val('0');
                    }
                    calculos();
                }
              });
            }

            function validar_decimal(obj){
              val=$(obj).val();
              ob=val.split('.');
              if(ob[1]!=null){
                if(ob[0].length>10){
                  swal("Error!", "El valor entero debe ser maximo de 10 digitos!", "error");
                  $(obj).val("");
                }
                if(ob[1].length>dec){
                   swal("Error!","El valor decimal debe ser maximo de " +dec+ " digitos!", "error");
                  $(obj).val("");
                }
              }
            }
    </script>

