<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ctasxcobrar_pagos_model extends CI_Model { 


	public function lista_factura_buscador($text,$f1,$f2,$emp_id){
		$this->db->select("f.fac_id,f.fac_fecha_emision, pag_fecha_v, f.fac_numero, c.cli_raz_social as fac_nombre, cli_ced_ruc as fac_identificacion, p.fac_total_valor,(select sum(cta_monto) from erp_ctasxcobrar where com_id=f.fac_id and cta_fecha_pago<='$f2' and cta_estado=1) as pago,saldo,fac_estado");
		$this->db->from('erp_factura f');
		$this->db->join('pagosxfactura p','f.fac_id=p.fac_id');
		$this->db->join('erp_i_cliente c','f.cli_id=c.cli_id');
		$this->db->where("(f.fac_numero like '%$text%' or cli_raz_social like '%$text%' or cli_ced_ruc like '%$text%') and f.fac_fecha_emision between '$f1' and '$f2' and fac_estado!=3 and emp_id=$emp_id", null);
		$this->db->group_by('f.fac_id,f.fac_fecha_emision, pag_fecha_v, f.fac_numero, cli_raz_social, cli_ced_ruc,p.fac_total_valor,saldo,fac_estado');
		$this->db->order_by('cli_raz_social','asc');
		$this->db->order_by('cli_ced_ruc','asc');
		$this->db->order_by('fac_numero','asc');
		$resultado=$this->db->get();
		return $resultado->result();
	}

	public function lista_vencer_vencido($text,$f1,$f2,$emp_id){
		$query="select f.fac_id,f.fac_fecha_emision, pag_fecha_v, f.fac_numero, cli_raz_social as fac_nombre, cli_ced_ruc as fac_identificacion,p.fac_total_valor,fac_estado, (select sum(cta_monto) from erp_ctasxcobrar where com_id=f.fac_id and cta_fecha_pago<='$f2' and cta_estado=1) as pago 
from erp_factura f, pagosxfactura p, erp_i_cliente c
where f.fac_id=p.fac_id and f.cli_id=c.cli_id and (f.fac_numero like '%$text%' or cli_raz_social like '%$text%' or cli_ced_ruc like '%$text%') and f.fac_fecha_emision between '$f1' and '$f2' and fac_estado!=3 and emp_id=$emp_id and (p.fac_total_valor>(select sum(ct.cta_monto) from erp_ctasxcobrar ct where f.fac_id=ct.com_id and ct.cta_estado='1' and cta_fecha_pago between '$f1' and '$f2') or not exists(select * from erp_ctasxcobrar ct where f.fac_id=ct.com_id and ct.cta_estado='1' and cta_fecha_pago between '$f1' and '$f2')) and pag_fecha_v > '$f2'
group by f.fac_id,f.fac_fecha_emision, pag_fecha_v, f.fac_numero, cli_raz_social, cli_ced_ruc,p.fac_total_valor,fac_estado, saldo, pago
union 
select f.fac_id,f.fac_fecha_emision, pag_fecha_v, f.fac_numero, cli_raz_social as fac_nombre, cli_ced_ruc as fac_identificacion, p.fac_total_valor,fac_estado, (select sum(cta_monto) from erp_ctasxcobrar where com_id=f.fac_id and cta_fecha_pago<='$f2' and cta_estado=1) as pago
from erp_factura f, pagosxfactura p, erp_i_cliente c
where f.fac_id=p.fac_id and f.cli_id=c.cli_id and (f.fac_numero like '%$text%' or cli_raz_social like '%$text%' or cli_ced_ruc like '%$text%') and f.fac_fecha_emision between '$f1' and '$f2' and fac_estado!=3 and emp_id=$emp_id and pag_fecha_v <= '$f2' and (p.fac_total_valor>(select sum(ct.cta_monto) from erp_ctasxcobrar ct where f.fac_id=ct.com_id and ct.cta_estado='1' and cta_fecha_pago between '$f1' and '$f2') or not exists(select * from erp_ctasxcobrar ct where f.fac_id=ct.com_id and ct.cta_estado='1' and cta_fecha_pago between '$f1' and '$f2'))
group by f.fac_id,f.fac_fecha_emision, pag_fecha_v, f.fac_numero, cli_raz_social, cli_ced_ruc,p.fac_total_valor,fac_estado, saldo, pago
order by fac_nombre, fac_identificacion, fac_numero ";

		$resultado=$this->db->query($query);
		return $resultado->result();
	}

	public function lista_vencer_pagado($text,$f1,$f2,$emp_id){
		$query="select f.fac_id,f.fac_fecha_emision, pag_fecha_v, f.fac_numero, cli_raz_social as fac_nombre, cli_ced_ruc as fac_identificacion,p.fac_total_valor,fac_estado, (select sum(cta_monto) from erp_ctasxcobrar where com_id=f.fac_id and cta_fecha_pago<='$f2' and cta_estado=1) as pago 
from erp_factura f, pagosxfactura p, erp_i_cliente c
where f.fac_id=p.fac_id and f.cli_id=c.cli_id and (f.fac_numero like '%$text%' or cli_raz_social like '%$text%' or cli_ced_ruc like '%$text%') and f.fac_fecha_emision between '$f1' and '$f2' and fac_estado!=3 and emp_id=$emp_id and (p.fac_total_valor>(select sum(ct.cta_monto) from erp_ctasxcobrar ct where f.fac_id=ct.com_id and ct.cta_estado='1' and cta_fecha_pago between '$f1' and '$f2') or not exists(select * from erp_ctasxcobrar ct where f.fac_id=ct.com_id and ct.cta_estado='1' and cta_fecha_pago between '$f1' and '$f2')) and pag_fecha_v > '$f2'
group by f.fac_id,f.fac_fecha_emision, pag_fecha_v, f.fac_numero, cli_raz_social, cli_ced_ruc,p.fac_total_valor,fac_estado, saldo, pago
union 
select f.fac_id,f.fac_fecha_emision, pag_fecha_v, f.fac_numero, cli_raz_social as fac_nombre, cli_ced_ruc as fac_identificacion,p.fac_total_valor,fac_estado, (select sum(cta_monto) from erp_ctasxcobrar where com_id=f.fac_id and cta_fecha_pago<='$f2' and cta_estado=1) as pago
from erp_factura f, pagosxfactura p, erp_i_cliente c where f.fac_id=p.fac_id and f.cli_id=c.cli_id and (f.fac_numero like '%$text%' or cli_raz_social like '%$text%' or cli_ced_ruc like '%$text%') and f.fac_fecha_emision between '$f1' and '$f2' and fac_estado!=3 and emp_id=$emp_id and (select sum(cta_monto) from erp_ctasxcobrar where com_id=f.fac_id and cta_fecha_pago<='$f2' and cta_estado=1)=p.fac_total_valor 
group by f.fac_id,f.fac_fecha_emision, pag_fecha_v, f.fac_numero, cli_raz_social, cli_ced_ruc,p.fac_total_valor,fac_estado, saldo, pago
order by fac_nombre, fac_identificacion, fac_numero ";

		$resultado=$this->db->query($query);
		return $resultado->result();
	}

	public function lista_vencido_pagado($text,$f1,$f2,$emp_id){
		$query="select f.fac_id,f.fac_fecha_emision, pag_fecha_v, f.fac_numero, cli_raz_social as fac_nombre, cli_ced_ruc as fac_identificacion,p.fac_total_valor,fac_estado, (select sum(cta_monto) from erp_ctasxcobrar where com_id=f.fac_id and cta_fecha_pago<='$f2' and cta_estado=1) as pago
from erp_factura f, pagosxfactura p, erp_i_cliente c 
where f.fac_id=p.fac_id and f.cli_id=c.cli_id and (f.fac_numero like '%$text%' or cli_raz_social like '%$text%' or cli_ced_ruc like '%$text%') and f.fac_fecha_emision between '$f1' and '$f2' and fac_estado!=3 and emp_id=$emp_id and pag_fecha_v <= '$f2' and (p.fac_total_valor>(select sum(ct.cta_monto) from erp_ctasxcobrar ct where f.fac_id=ct.com_id and ct.cta_estado='1' and cta_fecha_pago between '$f1' and '$f2') or not exists(select * from erp_ctasxcobrar ct where f.fac_id=ct.com_id and ct.cta_estado='1' and cta_fecha_pago between '$f1' and '$f2'))
group by f.fac_id,f.fac_fecha_emision, pag_fecha_v, f.fac_numero, cli_raz_social, cli_ced_ruc,p.fac_total_valor,fac_estado, saldo, pago
union 
select f.fac_id,f.fac_fecha_emision, pag_fecha_v, f.fac_numero, cli_raz_social as fac_nombre, cli_ced_ruc as fac_identificacion,p.fac_total_valor,fac_estado, (select sum(cta_monto) from erp_ctasxcobrar where com_id=f.fac_id and cta_fecha_pago<='$f2' and cta_estado=1) as pago
from erp_factura f, pagosxfactura p, erp_i_cliente c  where f.fac_id=p.fac_id and f.cli_id=c.cli_id and (f.fac_numero like '%$text%' or cli_raz_social like '%$text%' or cli_ced_ruc like '%$text%') and f.fac_fecha_emision between '$f1' and '$f2' and fac_estado!=3 and emp_id=$emp_id and (select sum(cta_monto) from erp_ctasxcobrar where com_id=f.fac_id and cta_fecha_pago<='$f2' and cta_estado=1)=p.fac_total_valor 
group by f.fac_id,f.fac_fecha_emision, pag_fecha_v, f.fac_numero, cli_raz_social, cli_ced_ruc,p.fac_total_valor,fac_estado, saldo, pago
order by fac_nombre, fac_numero ";

		$resultado=$this->db->query($query);
		return $resultado->result();
	}

	public function lista_vencer($text,$f1,$f2,$emp_id){
		$this->db->select("f.fac_id,f.fac_fecha_emision, pag_fecha_v, f.fac_numero, cli_raz_social as fac_nombre, cli_ced_ruc as fac_identificacion, p.fac_total_valor,fac_estado, (select sum(cta_monto) from erp_ctasxcobrar where com_id=f.fac_id and cta_fecha_pago<='$f2' and cta_estado=1) as pago");
		$this->db->from('erp_factura f');
		$this->db->join('pagosxfactura p','f.fac_id=p.fac_id');
		$this->db->join('erp_i_cliente c','f.cli_id=c.cli_id');
		$this->db->where("(f.fac_numero like '%$text%' or cli_raz_social like '%$text%' or cli_ced_ruc like '%$text%') and f.fac_fecha_emision between '$f1' and '$f2' and fac_estado!=3 and emp_id=$emp_id and (p.fac_total_valor>(select sum(ct.cta_monto) from erp_ctasxcobrar ct where f.fac_id=ct.com_id and ct.cta_estado='1' and cta_fecha_pago between '$f1' and '$f2') or not exists(select * from erp_ctasxcobrar ct where f.fac_id=ct.com_id and ct.cta_estado='1' and cta_fecha_pago between '$f1' and '$f2')) and pag_fecha_v > '$f2'", null);
		$this->db->group_by('f.fac_id,f.fac_fecha_emision, pag_fecha_v, f.fac_numero, cli_raz_social, cli_ced_ruc,p.fac_total_valor,pago,saldo,fac_estado');
		$this->db->order_by('cli_raz_social','asc');
		$this->db->order_by('cli_ced_ruc','asc');
		$this->db->order_by('fac_numero','asc');
		$resultado=$this->db->get();
		return $resultado->result();
	}

	public function lista_vencido($text,$f1,$f2,$emp_id){
		$this->db->select("f.fac_id,f.fac_fecha_emision, pag_fecha_v, f.fac_numero, cli_raz_social as fac_nombre, cli_ced_ruc as fac_identificacion, p.fac_total_valor,saldo,fac_estado, (select sum(cta_monto) from erp_ctasxcobrar where com_id=f.fac_id and cta_fecha_pago<='$f2' and cta_estado=1) as pago");
		$this->db->from('erp_factura f');
		$this->db->join('pagosxfactura p','f.fac_id=p.fac_id');
		$this->db->join('erp_i_cliente c','f.cli_id=c.cli_id');
		$this->db->where("(f.fac_numero like '%$text%' or cli_raz_social like '%$text%' or cli_ced_ruc like '%$text%') and f.fac_fecha_emision between '$f1' and '$f2' and fac_estado!=3 and emp_id=$emp_id and pag_fecha_v <= '$f2' and (p.fac_total_valor>(select sum(ct.cta_monto) from erp_ctasxcobrar ct where f.fac_id=ct.com_id and ct.cta_estado='1' and cta_fecha_pago between '$f1' and '$f2') or not exists(select * from erp_ctasxcobrar ct where f.fac_id=ct.com_id and ct.cta_estado='1' and cta_fecha_pago between '$f1' and '$f2'))", null);
		$this->db->group_by('f.fac_id,f.fac_fecha_emision, pag_fecha_v, f.fac_numero, cli_raz_social, cli_ced_ruc,p.fac_total_valor,pago,saldo,fac_estado');
		$this->db->order_by('cli_raz_social','asc');
		$this->db->order_by('cli_ced_ruc','asc');
		$this->db->order_by('fac_numero','asc');
		$resultado=$this->db->get();
		return $resultado->result();
	}

	public function lista_pagado($text,$f1,$f2,$emp_id){
		$this->db->select("f.fac_id,f.fac_fecha_emision, pag_fecha_v, f.fac_numero, cli_raz_social as fac_nombre, cli_ced_ruc as fac_identificacion ,p.fac_total_valor,saldo,fac_estado, (select sum(cta_monto) from erp_ctasxcobrar where com_id=f.fac_id and cta_fecha_pago<='$f2' and cta_estado=1) as pago");
		$this->db->from('erp_factura f');
		$this->db->join('pagosxfactura p','f.fac_id=p.fac_id');
		$this->db->join('erp_i_cliente c','f.cli_id=c.cli_id');
		$this->db->where("(f.fac_numero like '%$text%' or cli_raz_social like '%$text%' or cli_ced_ruc like '%$text%') and f.fac_fecha_emision between '$f1' and '$f2' and fac_estado!=3 and emp_id=$emp_id and (select sum(cta_monto) from erp_ctasxcobrar where com_id=f.fac_id and cta_fecha_pago<='$f2' and cta_estado=1)=p.fac_total_valor", null);
		$this->db->group_by('f.fac_id,f.fac_fecha_emision, pag_fecha_v, f.fac_numero, cli_raz_social, cli_ced_ruc,p.fac_total_valor,pago,saldo,fac_estado');
		$this->db->order_by('cli_raz_social','asc');
		$this->db->order_by('cli_ced_ruc','asc');
		$this->db->order_by('fac_numero','asc');
		$resultado=$this->db->get();
		return $resultado->result();
	}

	
	
	public function lista_pagos_factura($id){
		$this->db->from('erp_pagos_factura p');
		$this->db->join('erp_factura c','cast(p.com_id as integer)=c.fac_id');
		$this->db->where('com_id',$id);
		$this->db->where('pag_estado','1');
		$resultado=$this->db->get();
		return $resultado->result();
			
	}


	

	public function lista_ctasxcobrar($id){
		$this->db->from('erp_ctasxcobrar c');
		$this->db->join('erp_factura f','c.com_id=f.fac_id');
		$this->db->order_by('cta_fecha_pago','asc');
		$this->db->where("fac_id", $id);
		$this->db->where('cta_estado','1');
		$resultado=$this->db->get();
		return $resultado->result();
	}
	public function lista_ctasxcobrar_fecha($id,$fecha){
		$this->db->from('erp_ctasxcobrar c');
		$this->db->join('erp_factura f','c.com_id=f.fac_id');
		$this->db->order_by('cta_fecha_pago','asc');
		$this->db->where("fac_id", $id);
		$this->db->where("cta_fecha <='$fecha'",null);
		$this->db->where('cta_estado','1');
		$resultado=$this->db->get();
		return $resultado->result();
	}


	public function lista_saldo_factura($id){
		$this->db->select('f.fac_id,f.fac_fecha_emision, pag_fecha_v, f.fac_numero, cli_raz_social as f.fac_nombre, cli_ced_ruc as fac_identificacion, p.fac_total_valor,pago,saldo,fac_estado');
		$this->db->from('erp_factura f');
		$this->db->join('pagosxfactura p','f.fac_id=p.fac_id');
		$this->db->join('erp_i_cliente c','f.cli_id=c.cli_id');
		$this->db->where('f.fac_id',$id);
		$this->db->group_by('f.fac_id,f.fac_fecha_emision, pag_fecha_v, f.fac_numero, cli_raz_social, cli_ced_ruc, p.fac_total_valor,pago,saldo,fac_estado');
		$resultado=$this->db->get();
		return $resultado->row();
	}

	public function insert($data){
		$this->db->insert("erp_ctasxcobrar",$data);
		return $this->db->insert_id();
	}


    public  function lista_notcre_cliente($id) {
		$this->db->from('erp_cheques');
		$this->db->where('cli_id',$id);
		$this->db->where('chq_tipo_doc','8');
		$this->db->where('chq_estado !=','3');
		$resultado=$this->db->get();
		return $resultado->result();
    }



	public function lista_nota_credito_factura($id){
		$this->db->from('erp_nota_credito');
		$this->db->where('fac_id',$id);
		$this->db->where('ncr_estado !=','3');
		$resultado=$this->db->get();
		return $resultado->row();
			
	}

	public function lista_guia_factura($id){
		$this->db->from('erp_guia_remision');
		$this->db->where('fac_id',$id);
		$this->db->where('gui_estado !=','3');
		$resultado=$this->db->get();
		return $resultado->row();
			
	}

	public function lista_retencion_factura($id){
		$this->db->from('erp_registro_retencion');
		$this->db->where('fac_id',$id);
		$this->db->where('rgr_estado !=','3');
		$resultado=$this->db->get();
		return $resultado->row();
			
	}

	public function lista_una_factura_cliente($num,$id,$emp){
		$this->db->from('erp_reg_documentos');
		$this->db->where('cli_id',$id);
		$this->db->where('emp_id',$emp);
		$this->db->where('reg_num_documento',$num);
		$this->db->where('reg_estado!=3',null);
		$resultado=$this->db->get();
		return $resultado->row();
			
	}

	public function update($id,$data){
		$this->db->where('com_id',$id);
		return $this->db->update("erp_ctasxcobrar",$data);
			
	}

	public function update_ctascob_cheque($id,$data){
		$this->db->where('chq_id',$id);
		return $this->db->update("erp_ctasxcobrar",$data);
			
	}

	public function delete_pagos_factura($id){
		$this->db->where('com_id',$id);
		return $this->db->delete("erp_ctasxcobrar");
			
	}

	public  function lista_ctasxcobrar_notcre($id) {
		$this->db->select("sum(cta_monto)");
		$this->db->from('erp_ctasxcobrar');
		$this->db->where('chq_id',$id);
		$this->db->where('cta_estado !=','3');
		$resultado=$this->db->get();
		return $resultado->row();
    }

    public function lista_ctasxcobrar_cheque($id){
		

		$query = "SELECT * FROM erp_ctasxcobrar c JOIN erp_asientos_contables a ON a.doc_id=c.pag_id JOIN erp_factura f ON c.com_id=f.fac_id JOIN erp_cheques ch ON ch.chq_id=c.chq_id WHERE c.chq_id = $id  and c.cta_monto=a.con_valor_haber AND cta_estado = '1' ORDER BY cta_fecha_pago ASC";
    	$resultado =$this->db->query($query);
    	return $resultado->result();
    	//echo $this->db->last_query();
	}
	public function lista_ctasxcobrar_cheque2($id){
		

		$query = "SELECT * FROM erp_ctasxcobrar c JOIN erp_asientos_contables a ON a.doc_id=c.pag_id JOIN erp_factura f ON c.com_id=f.fac_id JOIN erp_cheques ch ON ch.chq_id=c.chq_id WHERE c.chq_id = $id  and c.cta_monto=a.con_valor_haber  ORDER BY cta_fecha_pago ASC";
    	$resultado =$this->db->query($query);
    	return $resultado->result();
    	//echo $this->db->last_query();
	}

	 public function lista_ctasxcobrar_cheque_2($id){
		
		$query = "SELECT * FROM erp_ctasxcobrar c 
		JOIN erp_asientos_contables a ON a.doc_id=c.cta_id  
		JOIN erp_factura f ON c.com_id=f.fac_id
		JOIN erp_cheques ch ON ch.chq_id=c.chq_id WHERE c.chq_id = $id   AND cta_estado = '1' ORDER BY cta_fecha_pago ASC";
    	$resultado =$this->db->query($query);
    	return $resultado->result();
    	//echo $this->db->last_query();
	}
	public function ultimo_id(){
		$this->db->select("max(cta_id) as id");
		$this->db->from("erp_ctasxcobrar");
		$resultado=$this->db->get();
		return $resultado->row();
		//echo $this->db->last_query();
	}

    
}

?>