<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Nota_debito extends CI_Controller {

	private $permisos;

	function __construct(){
		parent:: __construct();
		if(!$this->session->userdata('s_login')){
			redirect(base_url());
		}
		$this->load->library('backend_lib');
		$this->load->model('backend_model');
		$this->permisos=$this->backend_lib->control();
		$this->load->library('form_validation');
		$this->load->model('empresa_model');
		$this->load->model('emisor_model');
		$this->load->model('factura_model');
		$this->load->model('nota_debito_model');
		$this->load->model('cliente_model');
		$this->load->model('vendedor_model');
		$this->load->model('producto_comercial_model');
		$this->load->model('forma_pago_model');
		$this->load->model('bancos_tarjetas_model');
		$this->load->model('auditoria_model');
		$this->load->model('menu_model');
		$this->load->model('estado_model');
		$this->load->model('configuracion_model');
		$this->load->model('forma_pago_model');
		$this->load->model('caja_model');
		$this->load->model('opcion_model');
		$this->load->model('cheque_model');
		$this->load->model('configuracion_cuentas_model');
		$this->load->model('plan_cuentas_model');
		$this->load->model('asiento_model');
		$this->load->library('html2pdf');
		$this->load->library('Zend');
		$this->load->library('export_excel');
		$this->load->library("nusoap_lib");
		$this->load->library('email');
	}

	public function _remap($method, $params = array()){
    
	    if(!method_exists($this, $method))
	      {
	       $this->index($method, $params);
	    }else{
	      return call_user_func_array(array($this, $method), $params);
	    }
  	}


	public function menus()
	{
		$menu=array(
					'menus' =>  $this->menu_model->lista_opciones_principal('1',$this->session->userdata('s_idusuario')),
					'sbmopciones' =>  $this->menu_model->lista_opciones_submenu('1',$this->session->userdata('s_idusuario'),$this->permisos->sbm_id),
					'actual'=>$this->permisos->men_id,
					'actual_sbm'=>$this->permisos->sbm_id,
					'actual_opc'=>$this->permisos->opc_id 
				);
		return $menu;
	}
	

	public function index($opc_id){

		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		///buscador 
		if($_POST){
			$text= trim($this->input->post('txt'));
			$ids= $this->input->post('tipo');
			$f1= $this->input->post('fec1');
			$f2= $this->input->post('fec2');	
			$cns_notas=$this->nota_debito_model->lista_nota_buscador($text,$f1,$f2,$rst_cja->emp_id,$rst_cja->emi_id);
		}else{
			$text= '';
			$f1= date('Y-m-d');
			$f2= date('Y-m-d');
			$cns_notas=$this->nota_debito_model->lista_nota_buscador($text,$f1,$f2,$rst_cja->emp_id,$rst_cja->emi_id);
		}

		$data=array(
					'permisos'=>$this->permisos,
					'notas'=>$cns_notas,
					'titulo'=>ucfirst(strtolower($rst_cja->emi_nombre)).' '.ucfirst(strtolower($rst_cja->cja_nombre)),
					'opc_id'=>$rst_opc->opc_id,
					'buscar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'txt'=>$text,
					'fec1'=>$f1,
					'fec2'=>$f2,
				);
		$this->load->view('layout/header',$this->menus());
		$this->load->view('layout/menu',$this->menus());
		$this->load->view('nota_debito/lista',$data);
		$modulo=array('modulo'=>'nota_debito');
		$this->load->view('layout/footer',$modulo);
	}


	public function nuevo($opc_id){
		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		if($permisos->rop_insertar){
			//valida cuentas asientos completos
			$conf_as=$this->configuracion_model->lista_una_configuracion('4');
			$valida_asiento=0;
			if($conf_as->con_valor==0){
				$cuentas=$this->configuracion_cuentas_model->lista_configuracion_cuenta_completa($rst_cja->emi_id);
				if(!empty($cuentas)){
					$valida_asiento=1;
				}
			}
			$usu_id=$this->session->userdata('s_idusuario');
			$rst_vnd=$this->vendedor_model->lista_un_vendedor($usu_id);
			
			if(empty($rst_vnd)){
				$vnd='';
			}else{
				$vnd=$rst_vnd->vnd_id;
				if ($vnd==1) {
					$vnd="";
				}
			}
			
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$mensaje='Para una mejor experiencia gire la pantalla de su celular';
			$data=array(
						'ctrl_inv'=>$this->configuracion_model->lista_una_configuracion('6'),
						'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
						'dcc'=>$this->configuracion_model->lista_una_configuracion('1'),
						'inven'=>$this->configuracion_model->lista_una_configuracion('3'),
						'cprec'=>$this->configuracion_model->lista_una_configuracion('20'),
						'cdesc'=>$this->configuracion_model->lista_una_configuracion('21'),
						'm_pag'=>$this->configuracion_model->lista_una_configuracion('22'),
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'vendedores'=>$this->vendedor_model->lista_vendedores_estado('1'),
						'cns_clientes'=>$this->cliente_model->lista_clientes_estado('1'),
						'titulo'=>ucfirst(strtolower($rst_cja->emi_nombre)).' '.ucfirst(strtolower($rst_cja->cja_nombre)),
						'cancelar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'mensaje'=> $mensaje,
						'nota'=> (object) array(
											'ndb_fecha_emision'=>date('Y-m-d'),
											'ndb_numero'=>'',
											'ndb_num_comp_modifica'=>'',
											'ndb_fecha_emi_comp'=>'',
											'fac_id'=>'0',
					                        'cli_id'=>'',
					                        'vnd_id'=>$vnd,
					                        'ndb_identificacion'=>'',
					                        'ndb_nombre'=>'',
					                        'ndb_direccion'=>'',
					                        'ndb_telefono'=>'',
					                        'ndb_email'=>'',
					                        'ndb_subtotal12'=>'0',
					                        'ndb_subtotal0'=>'0',
					                        'ndb_subtotal12'=>'0',
					                        'ndb_subtotal0'=>'0',
					                        'ndb_subtotal12'=>'0',
					                        'ndb_subtotal0'=>'0',
					                        'ndb_subtotal_ex_iva'=>'0',
					                        'ndb_subtotal_no_iva'=>'0',
					                        'ndb_subtotal'=>'0',
					                        'ndb_total_iva'=>'0',
					                        'ndb_total_valor'=>'0',
					                        'emp_id'=>$rst_cja->emp_id,
					                        'emi_id'=>$rst_cja->emi_id,
					                        'cja_id'=>$rst_cja->cja_id,
					                        'ndb_id'=>'',
										),
						'cns_det'=>'',
						'action'=>base_url().'nota_debito/guardar/'.$opc_id,
						'valida_asiento'=>$valida_asiento,
						);
			
			$this->load->view('nota_debito/form',$data);
			$modulo=array('modulo'=>'nota_debito');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}
	}

	public function guardar($opc_id){
		$conf_as=$this->configuracion_model->lista_una_configuracion('4');

		$ndb_fecha_emision = $this->input->post('ndb_fecha_emision');
		$vnd_id= $this->input->post('vnd_id');
		$fac_id= $this->input->post('fac_id');
		$ndb_num_comp_modifica= $this->input->post('ndb_num_comp_modifica');
		$ndb_fecha_emi_comp= $this->input->post('ndb_fecha_emi_comp');
		$identificacion = $this->input->post('identificacion');
		$nombre = $this->input->post('nombre');
		$cli_id = $this->input->post('cli_id');
		$direccion_cliente = $this->input->post('direccion_cliente');
		$telefono_cliente = $this->input->post('telefono_cliente');
		$email_cliente = $this->input->post('email_cliente');
		$subtotal12 = $this->input->post('subtotal12');
		$subtotal0 = $this->input->post('subtotal0');
		$subtotalex = $this->input->post('subtotalex');
		$subtotalno = $this->input->post('subtotalno');
		$subtotal = $this->input->post('subtotal');
		$total_iva = $this->input->post('total_iva');
		$total_valor = $this->input->post('total_valor');
		$emp_id = $this->input->post('emp_id');
		$emi_id = $this->input->post('emi_id');
		$cja_id = $this->input->post('cja_id');
		$count_det=$this->input->post('count_detalle');
		
		$this->form_validation->set_rules('ndb_fecha_emision','Fecha de Emision','required');
		$this->form_validation->set_rules('ndb_num_comp_modifica','Factura No','required');
		$this->form_validation->set_rules('ndb_fecha_emi_comp','Fecha Factura','required');
		$this->form_validation->set_rules('vnd_id','Vendedor','required');
		$this->form_validation->set_rules('identificacion','Identificacion','required');
		$this->form_validation->set_rules('nombre','Nombre','required');
		$this->form_validation->set_rules('direccion_cliente','Direccion','required');
		$this->form_validation->set_rules('telefono_cliente','Telefono','required');
		$this->form_validation->set_rules('email_cliente','Email','required');
		$this->form_validation->set_rules('total_valor','Total Valor','required');
		if($this->form_validation->run()){
			
			///secuencial de Nota Debito
			$rst_pto = $this->emisor_model->lista_un_emisor($emi_id);
			if ($rst_pto->emi_cod_punto_emision > 99) {
			    $ems = $rst_pto->emi_cod_punto_emision;
			} else if ($rst_pto->emi_cod_punto_emision < 100 && $rst_pto->emi_cod_punto_emision > 9) {
			    $ems = '0' .$rst_pto->emi_cod_punto_emision;
			} else {
			    $ems = '00' . $rst_pto->emi_cod_punto_emision;
			}

			$rst_cja = $this->caja_model->lista_una_caja($cja_id);
			if ($rst_cja->cja_codigo > 99) {
			    $caja = $rst_cja->cja_codigo;
			} else if ($rst_cja->cja_codigo < 100 && $rst_cja->cja_codigo > 9) {
			    $caja = '0' .$rst_cja->cja_codigo;
			} else {
			    $caja = '00' . $rst_cja->cja_codigo;
			}

			
			$rst_sec = $this->nota_debito_model->lista_secuencial_documento($emi_id,$cja_id);
		    if (empty($rst_sec)) {
		        $sec = $rst_cja->cja_sec_nota_debito;
		    } else {
		    	$sc=explode('-',$rst_sec->ndb_numero);
		        $sec = ($sc[2] + 1);
		    }
		    if ($sec >= 0 && $sec < 10) {
		        $tx = '00000000';
		    } else if ($sec >= 10 && $sec < 100) {
		        $tx = '0000000';
		    } else if ($sec >= 100 && $sec < 1000) {
		        $tx = '000000';
		    } else if ($sec >= 1000 && $sec < 10000) {
		        $tx = '00000';
		    } else if ($sec >= 10000 && $sec < 100000) {
		        $tx = '0000';
		    } else if ($sec >= 100000 && $sec < 1000000) {
		        $tx = '000';
		    } else if ($sec >= 1000000 && $sec < 10000000) {
		        $tx = '00';
		    } else if ($sec >= 10000000 && $sec < 100000000) {
		        $tx = '0';
		    } else if ($sec >= 100000000 && $sec < 1000000000) {
		        $tx = '';
		    }
		    $ndb_numero = $ems . '-'.$caja.'-' . $tx . $sec;

		    $clave_acceso=$this->clave_acceso($cja_id,$ndb_numero,$ndb_fecha_emision);

		    $data=array(	
		    				'emp_id'=>$emp_id,
		    				'emi_id'=>$emi_id,
		    				'cja_id'=>$cja_id,
							'cli_id'=>$cli_id, 
							'vnd_id'=>$vnd_id, 
							'fac_id'=>$fac_id,
							'ndb_denominacion_comprobante'=>'1',
							'ndb_fecha_emision'=>$ndb_fecha_emision,
							'ndb_numero'=>$ndb_numero, 
							'ndb_nombre'=>$nombre, 
							'ndb_identificacion'=>$identificacion, 
							'ndb_email'=>$email_cliente, 
							'ndb_direccion'=>$direccion_cliente, 
							'ndb_num_comp_modifica'=>$ndb_num_comp_modifica, 
							'ndb_fecha_emi_comp'=>$ndb_fecha_emi_comp, 
							'ndb_subtotal12'=>$subtotal12, 
							'ndb_subtotal0'=>$subtotal0, 
							'ndb_subtotal_ex_iva'=>$subtotalex, 
							'ndb_subtotal_no_iva'=>$subtotalno, 
							'ndb_total_iva'=>$total_iva, 
							'ndb_telefono'=>$telefono_cliente,
							'ndb_total_valor'=>$total_valor,
							'ndb_subtotal'=>$subtotal,
							'ndb_clave_acceso'=>$clave_acceso,
							'ndb_estado'=>'4'
		    );


		    $ndb_id=$this->nota_debito_model->insert($data);
		    if(!empty($ndb_id)){
		    	$n=0;
		    	while($n<$count_det){
		    		$n++;
		    		if($this->input->post("descripcion$n")!='' && $this->input->post("cantidad$n")>0){
		    			$descripcion = $this->input->post("descripcion$n");
		    			$cantidad = $this->input->post("cantidad$n");
		    			$dt_det=array(	
		    							'ndb_id'=>$ndb_id,
		    							'pro_id'=>0,
	                                    'dnd_descripcion'=>$descripcion,
	                                    'dnd_precio_total'=>$cantidad,
		    						);
		    			$this->nota_debito_model->insert_detalle($dt_det);
		    		}
		    	}
		    	
		    	
		    	///CHEQUES
		    	$cuenta="1.02.07.01.002";
		    	if($conf_as->con_valor==0){
		    		$cta=$this->configuracion_cuentas_model->lista_una_configuracion_cuenta('17',$emi_id);
		    		$cuenta=$cta->pln_codigo;
		    		
		    	}

				$rst_sec=$this->cheque_model->lista_secuencial();
				if (empty($rst_sec)) {
				$sec = 1;
				} else {
				$sec = $rst_sec->chq_secuencial + 1;
				}
				if ($sec >= 0 && $sec < 10) {
				$tx = '0000000';
				} else if ($sec >= 10 && $sec < 100) {
				$tx = '000000';
				} else if ($sec >= 100 && $sec < 1000) {
				$tx = '00000';
				} else if ($sec >= 1000 && $sec < 10000) {
				$tx = '0000';
				} else if ($sec >= 10000 && $sec < 100000) {
				$tx = '000';
				} else if ($sec >= 100000 && $sec < 1000000) {
				$tx = '00';
				} else if ($sec >= 1000000 && $sec < 10000000) {
				$tx = '0';
				} else if ($sec >= 10000000 && $sec < 100000000) {
				$tx = '';
				}
				$chq_secuencial = $tx . $sec;

		    	$dt_cheque=array(	
		    				'emp_id'=>$emp_id,
		    				'cli_id'=>$cli_id,
		    				'chq_recepcion'=>$ndb_fecha_emision,
							'chq_fecha'=>$ndb_fecha_emision,
							'chq_tipo_doc'=>'11', 
							'chq_nombre'=>'NOTA DE DEBITO', 
							'chq_concepto'=>'NOTA DE DEBITO',
							'chq_banco'=>'',
							'chq_numero'=>$ndb_numero,
							'chq_monto'=>$total_valor,
							'chq_estado'=>'9',
							'chq_estado_cheque'=>'11',
							'doc_id'=>$ndb_id,
							'chq_cuenta'=>$cuenta,
							'chq_secuencial'=>$chq_secuencial,
		    		);
		    	$chq_id=$this->cheque_model->insert($dt_cheque);

		    	//pagos_factura
		    	$dt_det=array(
		    							'com_id'=>$fac_id,
                                        'pag_fecha_v'=>$ndb_fecha_emision,
                                        'pag_forma'=>9,
                                        'pag_cant'=>$total_valor,
                                        'pag_banco'=>'0',
                                        'pag_tarjeta'=>'0',
                                        'pag_contado'=>'0',
                                        'chq_numero'=>$ndb_numero,
                                        'pag_id_chq'=>$chq_id,
                                        'pag_estado'=>'1',
                                        'pag_nd'=>'1',
		    						);
		    			
		    	$pag_id=$this->factura_model->insert_pagos($dt_det);

		    	$this->generar_xml($ndb_id,0);
		    	
		    	//genera asientos
		    	
		        if($conf_as->con_valor==0){
		        	$this->asientos($ndb_id);
		        }

				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'NOTA DE DEBITO',
								'adt_accion'=>'INSERTAR',
								'adt_campo'=>json_encode($this->input->post()),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$ndb_numero,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
			
				$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
				// redirect(base_url().strtolower($rst_opc->opc_direccion).$opc_id);
				redirect(base_url().'nota_debito/show_frame/'. $ndb_id.'/'.$opc_id);
			
			}else{
				$this->session->set_flashdata('error','No se pudo guardar');
				redirect(base_url().'nota_debito/nuevo/'.$opc_id);
			}
		}else{
			$this->nuevo($opc_id);
		}	

	}

	
	public function visualizar($id){
		if($this->permisos->rop_reporte){
			$data=array(
						'producto'=>$this->factura_model->lista_un_producto($id)
						);
			$this->load->view('factura/visualizar',$data);
		}else{
			redirect(base_url().'inicio');
		}	
	}


	public function anular($id,$num,$opc_id){
		if($this->permisos->rop_eliminar){
			$conf_as=$this->configuracion_model->lista_una_configuracion('4');
			$cnf_as=$conf_as->con_valor;
			$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);

			$rst_chq=$this->nota_debito_model->lista_cheque_nota($id);
				
			if(!empty($rst_chq)){
				//anulacion pagos
				$up_pag=array('pag_estado'=>3);
				$this->nota_debito_model->update_pagos($rst_chq->chq_id,$up_pag);

				$rst_ncr=$this->nota_debito_model->lista_una_nota($id);
			    $up_dtf=array('ndb_estado'=>3);
				if($this->nota_debito_model->update($id,$up_dtf)){
					$up_chq=array('chq_estado_cheque'=>3,'chq_estado'=>3);
					$this->cheque_model->update_chq_nota($id,$up_chq);
					//asiento anulacion nota
					if($cnf_as==0){
						$this->asiento_anulacion($id,'3');
					}

					$data_aud=array(
									'usu_id'=>$this->session->userdata('s_idusuario'),
									'adt_date'=>date('Y-m-d'),
									'adt_hour'=>date('H:i'),
									'adt_modulo'=>'NOTA DE DEBITO',
									'adt_accion'=>'ANULAR',
									'adt_ip'=>$_SERVER['REMOTE_ADDR'],
									'adt_documento'=>$num,
									'usu_login'=>$this->session->userdata('s_usuario'),
									);
					$this->auditoria_model->insert($data_aud);
					$data=array(
									'estado'=>0,
									'url'=>strtolower($rst_opc->opc_direccion).$opc_id,
								);

				}else{
					$data=array(
							'estado'=>1,
							'sms'=>'No se anulo la Nota de debito',
							'url'=>strtolower($rst_opc->opc_direccion).$opc_id,
					);
				}
			}
			echo json_encode($data);
		}else{
			redirect(base_url().'inicio');
		}	
	}

    	
	function clave_acceso($cja,$doc_numero,$doc_fecha){
		$cod_doc='04';
		$rst=$this->caja_model->lista_una_caja($cja);
		$rst_am=$this->configuracion_model->lista_una_configuracion('5');
		$ambiente = $rst_am->con_valor; //Pruebas 1    Produccion 2
		$codigo = "12345678"; //Del ejemplo del SRI
		$tp_emison = "1"; //Emision Normal

	    $ndoc = explode('-', $doc_numero);
	    $nfact = str_replace('-', '', $doc_numero);
	    $ems = $ndoc[0];
	    $emisor = intval($ndoc[0]);
	    $pt_ems = $ndoc[1];
	    $secuencial = $ndoc[2];
	    
	    $f=explode('-', $doc_fecha);
	    $fecha = "$f[2]/$f[1]/$f[0]";
	    $f2 = str_replace('/', '',$fecha);
	    
	    $clave1 = trim($f2 . $cod_doc . $rst->emp_identificacion . $ambiente . $ems . $pt_ems . $secuencial . $codigo . $tp_emison);
	    $cla = strrev($clave1);
	    $n = 0;
	    $p = 1;
	    $i = strlen($clave1);
	    $m = 0;
	    $s = 0;
	    $j = 2;
	    while ($n < $i) {
	        $d = substr($cla, $n, 1);
	        $m = $d * $j;
	        $s = $s + $m;
	        $j++;
	        if ($j == 8) {
	            $j = 2;
	        }
	        $n++;
	    }
	    $div = $s % 11;
	    $digito = 11 - $div;
	    if ($digito < 10) {
	        $digito = $digito;
	    } else if ($digito == 10) {
	        $digito = 1;
	    } else if ($digito == 11) {
	        $digito = 0;
	    }


	    $clave = trim($f2 . $cod_doc . $rst->emp_identificacion . $ambiente . $ems . $pt_ems . $secuencial . $codigo . $tp_emison . $digito);
	    return $clave;
	}
	
	public function show_frame($id,$opc_id){
		if($_POST){
			$text= trim($this->input->post('txt'));
			$fec1= $this->input->post('fec1');
			$fec2= $this->input->post('fec2');
		}else{
			$fec1=date('Y-m-d');
			$fec2=date('Y-m-d');
			$text='';
		}
		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		$etiqueta='Nota_debito.pdf';
    	if($permisos->rop_reporte){
    		$data=array(
					'titulo'=>'Nota de debito '.ucfirst(strtolower($rst_cja->emi_nombre)).' '.ucfirst(strtolower($rst_cja->cja_nombre)),
					'regresar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'direccion'=>"nota_debito/show_pdf/$id/$opc_id/0/$etiqueta",
					'fec1'=>$fec1,
					'fec2'=>$fec2,
					'txt'=>$text,
					'estado'=>'',
					'tipo'=>'',
					'vencer'=>'',
					'vencido'=>'',
					'pagado'=>'',
					'familia'=>'',
					'tip'=>'',
					'detalle'=>'',
				);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('pdf/frame_fecha',$data);
			$modulo=array('modulo'=>'nota_debito');
			$this->load->view('layout/footer',$modulo);
		}
    	
    }

    
    public function show_pdf($id,$opc_id,$correo,$etiqueta){
    		$rst=$this->nota_debito_model->lista_una_nota($id);
    		$imagen=$this->set_barcode($rst->ndb_clave_acceso); 
    		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
			$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
			$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);

			///recupera detalle
			$cns_dt=$this->nota_debito_model->lista_detalle_nota($id);
			$cns_det=array();
			foreach ($cns_dt as $rst_dt) {
	        
			$dt_det=(object) array(
						'dnd_descripcion'=>$rst_dt->dnd_descripcion,
						'dnd_precio_total'=>$rst_dt->dnd_precio_total,
						);	
				
				array_push($cns_det, $dt_det);
			}

			$data=array(
						'ambiente'=>$this->configuracion_model->lista_una_configuracion('5'),
						'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
						'dcc'=>$this->configuracion_model->lista_una_configuracion('1'),
						'titulo'=>ucfirst(strtolower($rst_cja->emi_nombre)).' '.ucfirst(strtolower($rst_cja->cja_nombre)),
						'cancelar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'nota'=>$this->nota_debito_model->lista_una_nota($id),
						'cns_det'=>$cns_det,
						);
			$this->html2pdf->filename('nota_debito.pdf');
			$this->html2pdf->paper('a4', 'portrait');
    		$this->html2pdf->html(utf8_decode($this->load->view('pdf/pdf_nota_debito', $data, true)));
    		$this->html2pdf->folder('./pdfs/');
            $this->html2pdf->filename($rst->ndb_clave_acceso.'.pdf');
            $this->html2pdf->create('save');
            
			if($correo==1){
				$datos_mail=(object) array('tipo' =>'NOTA DE DEBITO' ,
                              'cliente'=>$rst->ndb_nombre,
                              'emisor'=>$rst->emp_nombre,
                              'numero'=>$rst->ndb_numero,
                              'fecha'=>$rst->ndb_fecha_emision,
                              'total'=>$rst->ndb_total_valor,
                              'correo'=>$rst->ndb_email,
                              'ndb_id'=>$rst->ndb_id,
                              'logo'=>$rst->emp_logo,
                              'clave'=>$rst->ndb_clave_acceso,
                                 );
				$this->envio_mail($datos_mail);
			}else{
				$this->html2pdf->output(array("Attachment" => 0));	
			}
		
    }

    public function set_barcode($code)
	{
	
        $this->load->library('zend');
		$this->zend->load('Zend/Barcode');
		$imageResource = Zend_Barcode::factory('code39', 'image', array('text' => "$code", 'barHeight'=> 50,'factor'=> 1, 'drawText'=>false), array())->draw();
		$path="./barcodes/$code.png";
		imagepng($imageResource, $path);
	} 

	public function traer_facturas($num,$emi){
		$rst=$this->factura_model->lista_factura_numero($num,$emi);
		echo json_encode($rst);
	}

	public function load_factura($id,$inven,$ctrl_inv,$dec,$dcc){
		$rst=$this->factura_model->lista_una_factura($id);
		$n=0;
		
			$data= array(
						'fac_id'=>$rst->fac_id,
						'cli_id'=>$rst->cli_id,
						'cli_raz_social'=>$rst->cli_raz_social,
						'cli_ced_ruc'=>$rst->cli_ced_ruc,
						'cli_calle_prin'=>$rst->cli_calle_prin,
						'cli_telefono'=>$rst->cli_telefono,
						'cli_email'=>$rst->cli_email,
						'fac_fecha_emision'=>$rst->fac_fecha_emision,
						'fac_numero'=>$rst->fac_numero,
						);	

		echo json_encode($data);
	} 

	public function excel($opc_id,$fec1,$fec2){
    	$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);

    	$titulo='Nota de debito '.ucfirst(strtolower($rst_cja->emi_nombre)).' '.ucfirst(strtolower($rst_cja->cja_nombre));
    	$file="nota_debito".date('Ymd');
    	$data=$_POST['datatodisplay'];
    	$this->export_excel->to_excel($data,$file,$titulo,$fec1,$fec2);
    }

    public function consulta_sri($id,$opc_id,$env){
    	$amb=$this->configuracion_model->lista_una_configuracion('5');
    	$ambiente=$amb->con_valor;
    	if($ambiente!=0){
	    	$nota=$this->nota_debito_model->lista_una_nota($id);
	        set_time_limit(0);
	        if ($ambiente == 2) { //Produccion
            $client = new nusoap_client('https://cel.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl', 'wsdl');
	        } else {      //Pruebas
	            $client = new nusoap_client('https://celcer.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl', 'wsdl');
	        }
	        $client->soap_defencoding = 'UTF-8';
	        $client->decode_utf8 = FALSE;

	        // Calls
	        $result = $client->call('autorizacionComprobante', ["claveAccesoComprobante" => $nota->ndb_clave_acceso]);
	        if (empty($result['RespuestaAutorizacionComprobante']['autorizaciones'])) {
	           $this->generar_xml($nota->ndb_id,$env); 
	        } else {
	        	$res = $result['RespuestaAutorizacionComprobante']['autorizaciones']['autorizacion'];

	        	if($res['estado']=='AUTORIZADO'){
	        		$data = array(
	            					'ndb_autorizacion'=>$res['numeroAutorizacion'], 
	            					'ndb_fec_hora_aut'=>$res['fechaAutorizacion'], 
	            					'ndb_xml_doc'=>$res['comprobante'], 
	            					'ndb_estado'=>'6'
	            				);
	            	$this->nota_debito_model->update($nota->ndb_id,$data);

	        		$data_xml = (object)  array(
                					'estado'=>$res['estado'], 
                                    'autorizacion'=>$res['numeroAutorizacion'], 
                					'fecha'=>$res['fechaAutorizacion'], 
                					'comprobante'=>$res['comprobante'], 
                                    'ambiente'=>$res['ambiente'], 
                                    'clave'=>$nota->ndb_clave_acceso,
                                    'descarga'=>$env,
                				);
	        		$this->generar_xml_autorizado($data_xml,$nota->ndb_id,$opc_id); 
	        	}else{
	        		$this->generar_xml($nota->ndb_id,$env); 
	        	}

	        }
	    }    

    }
    

    function generar_xml($id,$d){
    	$amb=$this->configuracion_model->lista_una_configuracion('5');
	    $ambiente=$amb->con_valor;
    	if($ambiente!=0){
    	$xml="";    
    	$progr=$this->configuracion_model->lista_una_configuracion('15');
    	$programa=$progr->con_valor2;
    	$credencial=$this->configuracion_model->lista_una_configuracion('13');
    	$cred=explode('&',$credencial->con_valor2);
    	$firma=$cred[2];
		$pass=$cred[1];
    	$nota=$this->nota_debito_model->lista_una_nota($id);
    	$detalle=$this->nota_debito_model->lista_detalle_nota($nota->ndb_id);
        $dec = $this->configuracion_model->lista_una_configuracion('2');
        $round=$dec->con_valor;
        $codigo='12345678';  
        $tp_emison='1';  
        $empresa=$this->empresa_model->lista_una_empresa($nota->emp_id);    
        $emisor=$this->emisor_model->lista_un_emisor($nota->emi_id);    
        $ndoc = explode('-', $nota->ndb_numero);
        $nfact = str_replace('-', '', $nota->ndb_numero);
        $ems = $ndoc[0];
        $emi = intval($ndoc[0]);
        $pt_ems = $ndoc[1];
        $secuencial = $ndoc[2];
        $cod_doc = '05'; //01= factura, 02=nota de debito tabla 4
        $fecha = date_format(date_create($nota->ndb_fecha_emision), 'd/m/Y');
        $f2 = date_format(date_create($nota->ndb_fecha_emision), 'dmY');
        $dir_cliente = $nota->ndb_direccion;
        $telf_cliente = $nota->ndb_telefono;
        $email_cliente = $nota->ndb_email;
        $contabilidad = $empresa->emp_obligado_llevar_contabilidad;
        $razon_soc_comprador = $nota->ndb_nombre;
        $id_comprador = $nota->ndb_identificacion;;
        if (strlen($id_comprador) == 13 && $id_comprador != '9999999999999' && substr($id_comprador, -3) == '001') {
            $tipo_id_comprador = "04"; //RUC 04 
        } else if (strlen($id_comprador) == 10) {
            $tipo_id_comprador = "05"; //CEDULA 05 
        } else if ($id_comprador == '9999999999999') {
            $tipo_id_comprador = "07"; //VENTA A CONSUMIDOR FINAL
        } else {
            $tipo_id_comprador = "06"; // PASAPORTE 06 O IDENTIFICACION DELEXTERIOR* 08 PLACA 09            
        }
        

        $clave = $nota->ndb_clave_acceso;

        $xml.="<?xml version='1.0' encoding='UTF-8'?>" . chr(13);
	    $xml.="<notaDebito version='1.0.0' id='comprobante'>" . chr(13);
	    $xml.="<infoTributaria>" . chr(13);
	    $xml.="<ambiente>" . $ambiente . "</ambiente>" . chr(13);
	    $xml.="<tipoEmision>" . $tp_emison . "</tipoEmision>" . chr(13);
	    $xml.="<razonSocial>" . $empresa->emp_nombre . "</razonSocial>" . chr(13);
	    $xml.="<nombreComercial>" . $emisor->emi_nombre . "</nombreComercial>" . chr(13);
	    $xml.="<ruc>" . $empresa->emp_identificacion . "</ruc>" . chr(13);
	    $xml.="<claveAcceso>" . $clave . "</claveAcceso>" . chr(13);
	    $xml.="<codDoc>" . $cod_doc . "</codDoc>" . chr(13);
	    $xml.="<estab>" . $ems . "</estab>" . chr(13);
	    $xml.="<ptoEmi>" . $pt_ems . "</ptoEmi>" . chr(13);
	    $xml.="<secuencial>" . $secuencial . "</secuencial>" . chr(13);
	    $xml.="<dirMatriz>" . $empresa->emp_direccion . "</dirMatriz>" . chr(13);
	    $xml.="</infoTributaria>" . chr(13);

	//ENCABEZADO
	    $xml.="<infoNotaDebito>" . chr(13);
	    $xml.="<fechaEmision>" . $fecha . "</fechaEmision>" . chr(13);
	    $xml.="<dirEstablecimiento>" . $emisor->emi_dir_establecimiento_emisor . "</dirEstablecimiento>" . chr(13);
	    $xml.="<tipoIdentificacionComprador>" . $tipo_id_comprador . "</tipoIdentificacionComprador>" . chr(13);
	    $xml.="<razonSocialComprador>" . $razon_soc_comprador . "</razonSocialComprador>" . chr(13);
	    $xml.="<identificacionComprador>" . $id_comprador . "</identificacionComprador>" . chr(13);
	    if(!empty($empresa->emp_contribuyente_especial)){
        	$xml.="<contribuyenteEspecial>$empresa->emp_contribuyente_especial</contribuyenteEspecial>" . chr(13);
    	}
	    $xml.="<obligadoContabilidad>" . $contabilidad . "</obligadoContabilidad>" . chr(13);
	    $xml.="<codDocModificado>0" . $nota->ndb_denominacion_comprobante . "</codDocModificado>" . chr(13);
	    $xml.="<numDocModificado>" . $nota->ndb_num_comp_modifica . "</numDocModificado>" . chr(13);
	    $xml.="<fechaEmisionDocSustento>" . date_format(date_create($nota->ndb_fecha_emi_comp), 'd/m/Y') . "</fechaEmisionDocSustento>" . chr(13);
	    $xml.="<totalSinImpuestos>" . round($nota->ndb_subtotal, $round) . "</totalSinImpuestos>" . chr(13);
	    $xml.="<impuestos>" . chr(13);
	    $base = 0;
	    if ($nota->ndb_subtotal12 != 0) {
	        $codPorc = 2;
	        $base = round($nota->ndb_subtotal12, $round);
	        $valo_iva = round(($base * 12) / 100, $round);
	        $tarifa = '12.00';
	    } else if ($nota->ndb_subtotal0 != 0) {
	        $codPorc = 0;
	        $base = round($nota->ndb_subtotal0, $round);
	        $valo_iva = '0.00';
	        $tarifa = '0.00';
	    } else if ($nota->ndb_subtotal_ex_iva != 0) {
	        $codPorc = 7;
	        $base = round($nota->ndb_subtotal_ex_iva, $round);
	        $valo_iva = '0.00';
	        $tarifa = '0.00';
	    } else if ($nota->ndb_subtotal_no_iva != 0) {
	        $codPorc = 6;
	        $base = round($nota->ndb_subtotal_no_iva, $round);
	        $valo_iva = '0.00';
	        $tarifa = '0.00';
	    }

	    $xml.="<impuesto>" . chr(13);
	    $xml.="<codigo>2</codigo>" . chr(13); //Tipo de Impuesto
	    $xml.="<codigoPorcentaje>" . $codPorc . "</codigoPorcentaje>" . chr(13); 
	    $xml.="<tarifa>" . $tarifa . "</tarifa>" . chr(13);
	    $xml.="<baseImponible>" . round($base, $round) . "</baseImponible>" . chr(13);
	    $xml.="<valor>" . $valo_iva . "</valor>" . chr(13);
	    $xml.="</impuesto>" . chr(13);

	    $xml.="</impuestos>" . chr(13);

	    $xml.="<valorTotal>" . round($nota->ndb_total_valor, $round) . "</valorTotal>" . chr(13);
    	$xml.="</infoNotaDebito>" . chr(13);
    	$xml.="<motivos>" . chr(13);
	    foreach ($detalle as $det) {
	        $xml.="<motivo>" . chr(13);
	        $xml.="<razon>" . trim(substr($det->dnd_descripcion,0,25)) . "</razon >" . chr(13);
	        $xml.="<valor>" . round($det->dnd_precio_total, $round) . "</valor>" . chr(13);
	        $xml.="</motivo>" . chr(13);
	        
	    }
	    $xml.="</motivos>" . chr(13);
	    $xml.="<infoAdicional>" . chr(13);
	    $xml.="<campoAdicional nombre='Direccion'>" . $dir_cliente . "</campoAdicional>" . chr(13);
	    $xml.="<campoAdicional nombre='Telefono'>" . $telf_cliente . "</campoAdicional>" . chr(13);
	    $xml.="<campoAdicional nombre='Email'>" . strtolower(utf8_decode($email_cliente)) . "</campoAdicional>" . chr(13);
	    if(!empty($nota->emp_leyenda_sri)){
        	$xml.="<campoAdicional nombre='Observaciones'> " .$nota->emp_leyenda_sri. "</campoAdicional>" . chr(13);
        }
	    $xml.="</infoAdicional>" . chr(13);
	    $xml.="</notaDebito>" . chr(13);
	    
        $fch = fopen("./xml_docs/" . $clave . ".xml", "w+o");
        
		fwrite($fch, $xml);
		fclose($fch);

		if($d==1){
			$file = "./xml_docs/$clave.xml";
		        header("Content-type:xml");
		        header("Content-length:" . filesize($file));
		        header("Content-Disposition: attachment; filename= $clave.xml");
		        ob_clean();
  				flush();
		        readfile($file);
		}
		// header("Location: http://localhost:8080/central_xml_local/envio_sri/firmar.php?clave=$clave&programa=$programa&firma=$firma&password=$pass&ambiente=$ambiente");
		}
    }


    public function generar_xml_autorizado($dt,$id,$opc_id){
    	if (!empty($dt)) {
            $xml = "<?xml version='1.0' encoding='UTF-8' standalone='yes'?>
                    <autorizacion>
		              <estado>" . $dt->estado . "</estado>
		              <numeroAutorizacion>" . $dt->autorizacion . "</numeroAutorizacion>
		              <fechaAutorizacion>" . $dt->fecha . "</fechaAutorizacion>
		              <ambiente>" . $dt->ambiente . "</ambiente>
		              <comprobante><![CDATA[" . $dt->comprobante . "]]></comprobante>
                    	<mensajes/>
                    </autorizacion>";
               	$fch = fopen("./xml_docs/$dt->clave.xml", "w+o");
        
				fwrite($fch, $xml);
				fclose($fch);
				if($dt->descarga==1){
	                $file = "./xml_docs/$dt->clave.xml";
			        header("Content-type:xml");
			        header("Content-length:" . filesize($file));
			        header("Content-Disposition: attachment; filename= $dt->clave.xml");
			        readfile($file);
		    	}else if($dt->descarga==0){
			        $etiqueta='Nota_debito.pdf';
	            	$this->show_pdf($id,$opc_id,1,$etiqueta); 
	            }
        }

    }

    function envio_mail($datos){
        $credencial=$this->configuracion_model->lista_una_configuracion('8');
        $cred=explode('&',$credencial->con_valor2);
        $config['smtp_port'] = $cred[1];//'587';
        $config['smtp_host'] = $cred[2];//'mail.tivkas.com';
        $config['smtp_user'] = $cred[3];//'info@tivkas.com';
        $config['smtp_pass'] = $cred[4];//'tvk*36146';
        $config['protocol'] = 'smtp';
        $config['charset'] = 'utf-8';
        $config['mailtype'] = 'html';
        $config['smtp_crypto'] = 'ssl';

        $this->email->initialize($config);

        $this->email->from($cred[3], $cred[5]);
        $correos = str_replace(';',',', strtolower($datos->correo));
        
        $this->email->to($correos);
        $this->email->cc($cred[3]);

        $this->email->attach("./pdfs/$datos->clave.pdf");
        $this->email->attach("./xml_docs/$datos->clave.xml");

        if($datos->cliente=='CONSUMIDOR FINAL'){
          $ncliente="";
        }else{
          $ncliente=$datos->cliente;
        }
        
        $this->email->subject("DOCUMENTOS ELECTRONICOS $datos->tipo No: $datos->numero Cliente : $ncliente ");
        $img_logo=base_url().'imagenes/'.$datos->logo;
        $img_mail=base_url().'imagenes/mail2.png';
        $img_whatsapp=base_url().'imagenes/whatsapp2.png';
        $img_telefono=base_url().'imagenes/telefono2.png';
        $datos_sms = "<html>
              <head>
                <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
                 <style>
                      td {
                          color: #828282;
                          font-family: Arial, Helvetica, sans-serif;
                          font-size: 14px;
                          text-align: center;
                          font-weight: bolder;
                      }

                      
                  </style>
             </head>
             <body>
               <table width='100%'>
                
                  <tr><td><img  height='150px' width='300px' src='$img_logo'/></td></tr>
                  <tr><td></br></br> </td></tr>
                  <tr><td>Hola $datos->cliente, </td></tr>
                  <tr><td>Has recibido un nuevo documento electronico.</td></tr>
                  <tr><td></br></br> </td></tr>
                  <tr><td></br></br> </td></tr>
                  <tr><td>Fecha: $datos->fecha </td></tr>
                  <tr><td>Emisor: $datos->emisor </td></tr>
                  <tr><td>Tipo de Documento: $datos->tipo </td></tr>
                  <tr><td>Numero de Documento: $datos->numero </td></tr>
                  <tr><td>Total $: ". number_format($datos->total,2)."</td></tr>
                  <tr><td></br></br> </td></tr>
                  <tr><td></br></br> </td></tr>
                 <tr><td>Adjuntamos el comprobante en formato PDF y XML </td></tr>
                   <tr><td></br></br> </td></tr>
                    <tr><td></br></br> </td></tr>
                    <tr><td></br></br> </td></tr>
                    <tr><td></br></br> </td></tr>
                    <tr><td></br></br> </td></tr>
                    <tr><td></br></br> </td></tr>
                    <tr><td></br></br> </td></tr>
                    <tr><td></br></br> </td></tr>
                    <tr><td></br></br> </td></tr>
                    <tr><td></br></br> </td></tr>
                    <tr><td></br></br> </td></tr>
                 <tr>
                      <td style='font-size:16px'>FACTURACION ELECTRONICA POR TIKVASYST S.A.S</td>
                  </tr>
                  <tr><td></br></br> </td></tr>
                   
                  <tr><td></br></br> </td></tr>
                  <tr>
                      <td style='font-size:12px'></td>
                  </tr>
                  <tr>   
                       <td style='font-size:12px'>
                      
                        <img src='$img_mail' width='20px'><a href='https://www.tivkas.com'>www.tivkas.com</a> 
                        <img src='$img_whatsapp' width='20px'> +593 999404989 / +593 991815559
                      </td>
                  </tr>
                  <tr><td style='font-size:10px'>Copyright &copy; 2022 Todos los derechos reservados <a href='https://www.tivkas.com'>TIKVASYST S.A.S</a></td></tr>
               </table>
             </body>
           </html>";
        $this->email->message(utf8_decode($datos_sms));

        if($this->email->send()){
            $data= array('ndb_estado_correo' =>'ENVIADO');
            if($this->nota_debito_model->update($datos->ndb_id,$data)){
            	echo "Nota debito Enviada Correctamente";
            }
            
        }else{
            echo "no enviado";
        }

    }

    public function asientos($id){
        $conf=$this->configuracion_model->lista_una_configuracion('2');
		$dec=$conf->con_valor;

        $rst=$this->nota_debito_model->lista_una_nota($id);
        $cli=$this->configuracion_cuentas_model->lista_una_configuracion_cuenta('11',$rst->emi_id);
        $cex=$this->configuracion_cuentas_model->lista_una_configuracion_cuenta('12',$rst->emi_id);
        $vta=$this->configuracion_cuentas_model->lista_una_configuracion_cuenta('13',$rst->emi_id);
        $iva=$this->configuracion_cuentas_model->lista_una_configuracion_cuenta('14',$rst->emi_id);
        
        $asiento =$this->asiento_model->siguiente_asiento();
        
      
        $dat0 = array();
        $dat1 = array();
        $dat2 = array();
        $dat3 = array();

        $sub=round($rst->ndb_subtotal, $dec);

        if($rst->cli_tipo_cliente==0){
        	$ccli=$cli;
        }else{
        	$ccli=$cex;
        }
        $dat0 = Array(
                    'con_asiento'=>$asiento,
                    'con_concepto'=>'NOTA DE DEBITO',
                    'con_documento'=>$rst->ndb_numero,
                    'con_fecha_emision'=>$rst->ndb_fecha_emision,
                    'con_concepto_debe'=>$ccli->pln_id,
                    'con_concepto_haber'=>$vta->pln_id,
                    'con_valor_debe'=>round($rst->ndb_total_valor, $dec),
                    'con_valor_haber'=>round($sub, $dec),
                    'mod_id'=>'3',
                    'doc_id'=>$rst->ndb_id,
                    'cli_id'=>$rst->cli_id,
                    'con_estado'=>'1',
                    'emp_id'=>$rst->emp_id,
                );

        if ($rst->ndb_subtotal12 != 0) {
            $dat1 = Array(
                        'con_asiento'=>$asiento,
                        'con_concepto'=>'NOTA DE DEBITO',
                        'con_documento'=>$rst->ndb_numero,
                        'con_fecha_emision'=>$rst->ndb_fecha_emision,
                        'con_concepto_debe'=>'0',
                        'con_concepto_haber'=>$iva->pln_id,
                        'con_valor_debe'=>'0.00',
                        'con_valor_haber'=>round($rst->ndb_total_iva, $dec),
                        'mod_id'=>'3',
                        'doc_id'=>$rst->ndb_id,
                        'cli_id'=>$rst->cli_id,
                        'con_estado'=>'1',
                        'emp_id'=>$rst->emp_id,
            );
        }

        

        $array = array($dat0, $dat1, $dat2, $dat3);
        $j = 0;
        while ($j <= count($array)) {
            if (!empty($array[$j])) {
                $this->asiento_model->insert($array[$j]);
            }
            $j++;
        }
    }

    public function asiento_anulacion($id,$mod){
    	$conf=$this->configuracion_model->lista_una_configuracion('2');
		$dec=$conf->con_valor;
        
        $cns=$this->asiento_model->lista_asientos_modulo($id,$mod);
        $asiento = $asiento =$this->asiento_model->siguiente_asiento();

        foreach ($cns as $rst) {
            
            $data = Array(
                        'con_asiento'=>$asiento,
                        'con_concepto'=>'ANULACION '.$rst->con_concepto,
                        'con_documento'=>$rst->con_documento,
                        'con_fecha_emision'=>date('Y-m-d'),
                        'con_concepto_debe'=>$rst->con_concepto_haber,
                        'con_concepto_haber'=>$rst->con_concepto_debe,
                        'con_valor_debe'=>round($rst->con_valor_haber, $dec),
                        'con_valor_haber'=>round($rst->con_valor_debe, $dec),
                        'mod_id'=>$rst->mod_id,
                        'doc_id'=>$rst->doc_id,
                        'cli_id'=>$rst->cli_id,
                        'con_estado'=>'1',
                        'emp_id'=>$rst->emp_id,
                    );

            $this->asiento_model->insert($data);
                   
        }

    }
	
}
