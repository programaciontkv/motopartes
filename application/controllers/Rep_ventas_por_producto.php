<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rep_ventas_por_producto extends CI_Controller {

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
		$this->load->model('rep_ventas_por_producto_model');
		$this->load->model('auditoria_model');
		$this->load->model('menu_model');
		$this->load->model('estado_model');
		$this->load->model('configuracion_model');
		$this->load->model('opcion_model');
		$this->load->model('caja_model');
		$this->load->library('html2pdf');
		$this->load->library('Zend');
		$this->load->library('export_excel');
		$this->load->library('html5pdf');
		$this->load->library('html4pdf');

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
		
		$empresa='1';
		$f1= date('Y-m-d');
		$f2= date('Y-m-d');
		$ids='26';
		$txt="";
		$cns_productos=$this->rep_ventas_por_producto_model->lista_productos_buscador($f1,$f2,$empresa,$ids,$txt);
		
		
		$locales=$this->emisor_model->lista_emisores_empresa_2($empresa);
		$locales2=$this->emisor_model->lista_emisores_empresa($empresa);
		$detalle="<table id='tbl_list' class='table table-bordered table-list table-hover'>
						<thead>
							<tr>
								<th colspan='2'>Producto</th>";
								
								if(!empty($locales)){
									foreach($locales as $local){
									$detalle.="<th colspan='2' class='enc'>$local->emi_nombre</th>";
								
									}
								}
								
								$detalle.="<th colspan='2'>Total</th>
							</tr>	
							<tr>	
								<th>Codigo</th>
								<th>Descripcion</th>";
								
								if(!empty($locales2)){
									foreach($locales2 as $local2){
								
								$detalle.="<th>Cant.</th>
									<th>Valor</th>";
								 
									}
								}
								
							$detalle.="<th>Cant.</th>
								<th>Valor</th>
							</tr>	
						</thead>
						<tbody>";
						
		
		$dec=2;
		
		$gr_nm="";
		$th_cnt=0;
		$th_val=0;
		$s_sub12=0;
		$s_sub0=0;
		$s_sub=0;
		$s_ice=0;
		$s_iva=0;
		$s_total=0;
		$s_nc=0;
		
		if(!empty($cns_productos)){
			foreach ($cns_productos as $prod) {
				$n=0;;
				$detalle.="<tr>
								<td>$prod->mp_c</td>
								<td>$prod->mp_d</td>";
				$th_cnt=0;
				$th_val=0;				
				$locales3=$this->emisor_model->lista_emisores_empresa($empresa);
				if(!empty($locales3)){
					foreach ($locales3 as $loc3) {
						$n++;
						$rst_cnt=$this->rep_ventas_por_producto_model->lista_productos_local($loc3->emi_id,$prod->pro_id,$f1,$f2);
						$detalle.="<td class='cnt$n' >".number_format($rst_cnt->cantidad,$dec)."</td>
								<td class='val$n'>".number_format($rst_cnt->valor,$dec)."</td>";
						$th_cnt+=round($rst_cnt->cantidad,$dec);
						$th_val+=round($rst_cnt->valor,$dec);
					}
				}
				$detalle.="<td class='th_cnt'>".number_format($th_cnt,$dec)."</td>
							<td class='th_val'>".number_format($th_val,$dec)."</td>
							</tr>";

				
			}						
		}		
		$detalle.="<tr class='total'>
						<td colspan='2'>Total</td>";
					$n=0;	
					$locales4=$this->emisor_model->lista_emisores_empresa($empresa);
					if(!empty($locales4)){
						foreach ($locales4 as $loc3) {	
						$n++;		
						$detalle.="<td id='tv_cnt$n'></td>
								<td id='tv_val$n'></td>";
						}
					}			
		$detalle.="<td class='tv_cnt'></td>
					<td class='tv_val'></td>
				</tr>
				</tbody>
				</table>";
		


			$cns_empresas=$this->empresa_model->lista_empresas_estado('1');		
			
			$data=array(
						'permisos'=>$this->permisos,
						'empresas'=>$cns_empresas,
						'locales'=>$locales,
						'locales2'=>$locales2,
						'detalle'=>$detalle,
						'opc_id'=>$rst_opc->opc_id,
						'buscar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'empresa'=>$empresa,
						'fec1'=>$f1,
						'fec2'=>$f2,
						'ids'=>$ids,
						'txt'=>$txt,
			);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('reportes/rep_ventas_por_producto',$data);
			$modulo=array('modulo'=>'reportes');
			$this->load->view('layout/footer',$modulo);
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


	

    public function excel($opc_id,$fec1,$fec2){
    	$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);

    	$titulo='Ventas por Producto ';
    	$file="rep_ventas_por_producto".date('Ymd');
    	$data=$_POST['datatodisplay'];
    	$this->export_excel->to_excel($data,$file,$titulo,$fec1,$fec2);
    }


    public function buscar($empresa,$f1,$f2,$ids,$txt=""){
		
		$cns_productos=$this->rep_ventas_por_producto_model->lista_productos_buscador($f1,$f2,$empresa,$ids,$txt);
		$locales=$this->emisor_model->lista_emisores_empresa($empresa);
		$locales2=$this->emisor_model->lista_emisores_empresa($empresa);
		$detalle="<table id='tbl_list' class='table table-bordered table-list table-hover'>
						<thead>
							<tr>
								<th colspan='2'>Producto</th>";
								
								if(!empty($locales)){
									foreach($locales as $local){
									$detalle.="<th colspan='2' class='enc'>$local->emi_nombre</th>";
								
									}
								}
								
								$detalle.="<th colspan='2'>Total</th>
							</tr>	
							<tr>	
								<th>Codigo</th>
								<th>Descripcion</th>";
								
								if(!empty($locales2)){
									foreach($locales2 as $local2){
								
								$detalle.="<th>Cant.</th>
									<th>Valor</th>";
								 
									}
								}
								
							$detalle.="<th>Cant.</th>
								<th>Valor</th>
							</tr>	
						</thead>
						<tbody>";
						
		
		$dec=2;
		
		$gr_nm="";
		$th_cnt=0;
		$th_val=0;
		$s_sub12=0;
		$s_sub0=0;
		$s_sub=0;
		$s_ice=0;
		$s_iva=0;
		$s_total=0;
		$s_nc=0;
		
		if(!empty($cns_productos)){
			foreach ($cns_productos as $prod) {
				$n=0;;
				$detalle.="<tr>
								<td>$prod->mp_c</td>
								<td>$prod->mp_d</td>";
				$th_cnt=0;
				$th_val=0;				
				$locales3=$this->emisor_model->lista_emisores_empresa($empresa);
				if(!empty($locales3)){
					foreach ($locales3 as $loc3) {
						$n++;
						$rst_cnt=$this->rep_ventas_por_producto_model->lista_productos_local($loc3->emi_id,$prod->pro_id,$f1,$f2);
						$detalle.="<td class='cnt$n number' >".number_format($rst_cnt->cantidad,$dec)."</td>
								<td class='val$n number'>".number_format($rst_cnt->valor,$dec)."</td>";
						$th_cnt+=round($rst_cnt->cantidad,$dec);
						$th_val+=round($rst_cnt->valor,$dec);
					}
				}
				$detalle.="<td class='th_cnt number'>".number_format($th_cnt,$dec)."</td>
							<td class='th_val number'>".number_format($th_val,$dec)."</td>
							</tr>";

				
			}						
		}		
		$detalle.="</tbody>
		<tfoot>
		<tr class='total'>
						<td colspan='2'>Total</td>";
					$n=0;	
					$locales4=$this->emisor_model->lista_emisores_empresa($empresa);
					if(!empty($locales4)){
						foreach ($locales4 as $loc3) {	
						$n++;		
						$detalle.="<td id='tv_cnt$n' class='number'>></td>
								<td id='tv_val$n' class='number'>></td>";
						}
					}			
		$detalle.="<td id='tv_cnt' class='number'>></td>
					<td id='tv_val' class='number'>></td>
				</tr>
				</tfoot>
				</table>";

			
			$data=array(
						'detalle'=>$detalle,
			);
			
			echo json_encode($data);
	}

	public  function show_frame($opc_id,$empresa,$f1,$f2,$ids,$txt=""){

		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
    	$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);

		
		if($permisos->rop_reporte){
    		$data=array(
					'titulo'=>'rep_ventas_por_producto ',
					'regresar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'direccion'=>"rep_ventas_por_producto/buscar_2/$opc_id/$empresa/$f1/$f2/$ids/$txt",
					'fec1'=>$f1,
					'fec2'=>$f2,
					'txt'=>$txt,
					'estado'=>'',
					'tipo'=>$ids,
					'vencer'=>'',
					'vencido'=>'',
					'pagado'=>'',
					'familia'=>1,
					'tip'=>1,
					'detalle'=>'',
				);

			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('pdf/frame_fecha',$data);
			$modulo=array('modulo'=>'rep_ventas_por_producto');
			$this->load->view('layout/footer',$modulo);

		}
	}


	public function buscar_2($opc_id,$empresa,$f1,$f2,$ids,$txt=""){
		
		$cns_productos=$this->rep_ventas_por_producto_model->lista_productos_buscador($f1,$f2,$empresa,$ids,$txt);
		$locales=$this->emisor_model->lista_emisores_empresa_2($empresa);
		$locales2=$this->emisor_model->lista_emisores_empresa_2($empresa);
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		$emisor=$this->emisor_model->lista_un_emisor($rst_cja->emi_id);

		$detalle="<table id='tbl_list' width='100%' class='table table-bordered table-list table-hover'>
						<thead>
							<tr>
								<th colspan='2'>Producto</th>";
								
								if(!empty($locales)){
									foreach($locales as $local){
									$detalle.="<th colspan='2' class='enc'>$local->emi_nombre</th>";
								
									}
								}
								
								$detalle.="<th colspan='2'>Total</th>
							</tr>	
							<tr>	
								<th>".utf8_encode('Código')."</th>
								<th>".utf8_encode('Descripción') ."</th>";
								
								if(!empty($locales2)){
									foreach($locales2 as $local2){
								
								$detalle.="<th>Cant.</th>
									<th>Valor</th>";
								 
									}
								}
								
							$detalle.="<th>Cant.</th>
								<th>Valor</th>
							</tr>	
						</thead>
						<tbody>";
						
		
		$dec=2;
		
		$gr_nm="";
		$th_cnt=0;
		$th_val=0;
		$s_sub12=0;
		$s_sub0=0;
		$s_sub=0;
		$s_ice=0;
		$s_iva=0;
		$s_total=0;
		$s_nc=0;
		$t_c = 0;
		$t_val = 0;
		$can = 0;
		$val = 0;
		
		if(!empty($cns_productos)){
			foreach ($cns_productos as $prod) {
				$n=0;;
				$detalle.="<tr>
								<td>$prod->mp_c</td>
								<td>$prod->mp_d</td>";
				$th_cnt=0;
				$th_val=0;

				$locales3=$this->emisor_model->lista_emisores_empresa_2($empresa);
				if(!empty($locales3)){
					foreach ($locales3 as $loc3) {
						$n++;
						$rst_cnt=$this->rep_ventas_por_producto_model->lista_productos_local($loc3->emi_id,$prod->pro_id,$f1,$f2);
						$detalle.="<td class='cnt$n number' >".number_format($rst_cnt->cantidad,$dec)."</td>
								<td class='val$n number'>".number_format($rst_cnt->valor,$dec)."</td>";
						$th_cnt+=round($rst_cnt->cantidad,$dec);
						$th_val+=round($rst_cnt->valor,$dec);
					}
				}
				$detalle.="<td class='th_cnt number'>".number_format($th_cnt,$dec)."</td>
							<td class='th_val number'>".number_format($th_val,$dec)."</td>
							</tr>";
				$can += $rst_cnt->cantidad;
				$val += $rst_cnt->valor;
				$t_c+= $th_cnt;
				$t_val+=$th_val;


				
			}						
		}		
		$detalle.="</tbody>
		<tfoot>
		<tr class='total'>
						<td colspan='2'>Total</td>";
					$n=0;	
					$locales4=$this->emisor_model->lista_emisores_empresa_2($empresa);
					if(!empty($locales4)){
						foreach ($locales4 as $loc3) {	
						$n++;		
						$detalle.="<td id='tv_cnt$n' class='number'>".number_format($can,$dec)."</td>
								<td id='tv_val$n' class='number'>".number_format($val,$dec)."</td>";
						}
					}			
		$detalle.="<td id='tv_cnt' class='number'>" .number_format($t_c,$dec)."</td>
					<td id='tv_val' class='number'>".number_format($t_val,$dec)."</td>
				</tr>
				</tfoot>
				</table>";

			
			$data=array(
						'detalle'=>$detalle,
						'empresa'=>$emisor,
			);
		

			$this->html4pdf->filename('rep_ventas_por_producto.pdf');
			$this->html4pdf->paper('a4', 'landscape');
    		$this->html4pdf->html(utf8_decode($this->load->view('pdf/rep_ventas_por_producto', $data, true)));
			$this->html4pdf->output(array("Attachment" => 0));	
	}
     

}
