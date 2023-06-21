<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Nota_debito_model extends CI_Model {


	public function lista_notas_debito(){
		$this->db->from('erp_nota_debito n');
		$this->db->join('erp_vendedor v','n.vnd_id=v.vnd_id');
		$this->db->join('erp_estados e','f.ndb_estado=e.est_id');
		$this->db->order_by('ndb_numero');
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	public function lista_notas_empresa_emisor($emp_id,$emi_id){
		$query="SELECT ndb_id, ndb_fecha_emision,ndb_numero,v.vnd_nombre as usuario,ndb_identificacion,ndb_nombre,ndb_total_valor,est_descripcion, v2.vnd_nombre as vendedor, f.fac_total_valor, ndb_num_comp_modifica, ndb_estado, ndb_clave_acceso
			FROM erp_nota_debito n, erp_vendedor v, erp_estados e, erp_factura f,  erp_vendedor v2  
			WHERE n.vnd_id=v.vnd_id AND n.ndb_estado=e.est_id AND n.fac_id=f.fac_id AND f.vnd_id=v2.vnd_id  AND n.emp_id= $emp_id AND n.emi_id=$emi_id 
			
			ORDER BY ndb_numero";
		$resultado=$this->db->query($query);
		return $resultado->result();
			
	}

	public function lista_nota_buscador($text,$f1,$f2,$emp_id,$emi_id){
		$query="SELECT ndb_id, ndb_fecha_emision,ndb_numero,v.vnd_nombre as usuario,ndb_identificacion,ndb_nombre,ndb_total_valor,est_descripcion, v2.vnd_nombre as vendedor, f.fac_total_valor, ndb_num_comp_modifica, ndb_estado, ndb_clave_acceso
			FROM erp_nota_debito n, erp_vendedor v, erp_estados e, erp_factura f,  erp_vendedor v2  
			WHERE n.vnd_id=v.vnd_id AND n.ndb_estado=e.est_id AND n.fac_id=f.fac_id AND f.vnd_id=v2.vnd_id  AND n.emp_id= $emp_id AND n.emi_id=$emi_id and (ndb_numero like '%$text%' or ndb_nombre like '%$text%' or ndb_identificacion like '%$text%') and ndb_fecha_emision between '$f1' and '$f2'
			ORDER BY ndb_numero desc";
		$resultado=$this->db->query($query);
		return $resultado->result();
	}

	public function lista_secuencial_documento($emi,$cja){
		$this->db->select('ndb_numero');
		$this->db->from('erp_nota_debito');
		$this->db->where('emi_id',$emi);
		$this->db->where('cja_id',$cja);
		$this->db->order_by('ndb_numero','desc');
		$resultado=$this->db->get();
		return $resultado->row(); 
			
	}


	public function lista_una_nota($id){
		$this->db->from('erp_nota_debito n');
		$this->db->join('erp_vendedor v','n.vnd_id=v.vnd_id');
		$this->db->join('erp_i_cliente c','c.cli_id=n.cli_id');
		$this->db->join('erp_emisor m','m.emi_id=n.emi_id');
		$this->db->join('erp_empresas em','em.emp_id=n.emp_id');
		$this->db->join('erp_estados e','n.ndb_estado=e.est_id');
		$this->db->where('ndb_id',$id);
		$resultado=$this->db->get();
		return $resultado->row();
			
	}


	public function lista_detalle_nota($id){
		$this->db->from('erp_det_nota_debito d');
		$this->db->where('ndb_id',$id);
		$resultado=$this->db->get();
		return $resultado->result();
			
	}

	

	public function insert($data){
		$this->db->insert("erp_nota_debito",$data);
		return $this->db->insert_id();
	}

	public function insert_detalle($data){
		return $this->db->insert("erp_det_nota_debito",$data);
	}

	
	public function update($id,$data){
		$this->db->where('ndb_id',$id);
		return $this->db->update("erp_nota_debito",$data);
			
	}

	public function delete($id){
		$this->db->where('id',$id);
		return $this->db->delete("erp_nota_debito");
			
	}


	
   
	public function delete_detalle($id){
		$this->db->where('ndb_id',$id);
		return $this->db->delete("erp_det_nota_debito");
			
	}


	

	public function lista_nota_sin_autorizar(){
		$this->db->from('erp_nota_debito n');
		$this->db->join('erp_vendedor v','n.vnd_id=v.vnd_id');
		$this->db->join('erp_i_cliente c','c.cli_id=n.cli_id');
		$this->db->join('erp_emisor m','m.emi_id=n.emi_id');
		$this->db->join('erp_empresas em','em.emp_id=n.emp_id');
		$this->db->join('erp_estados e','n.ndb_estado=e.est_id');
		$this->db->where('ndb_estado', '4');
		$this->db->order_by('ndb_id','desc');
		$resultado=$this->db->get();
		return $resultado->row();
			
	}

	public function lista_suma_notas_factura($id){
		$query="SELECT sum(ndb_total_valor) as ndb_total_valor from erp_nota_debito where (ndb_estado=4 or ndb_estado=6) and fac_id=$id";
		$resultado=$this->db->query($query);
		return $resultado->row();
			
	}

	public function lista_cheque_nota($id){
		$this->db->from('erp_cheques ch');
		$this->db->where('doc_id',$id);
		$this->db->where('chq_tipo_doc','11');
		$resultado=$this->db->get();
		return $resultado->row();
			
	}

	public function update_pagos($id,$data){
		$this->db->where('pag_id_chq',$id);
		$this->db->where('pag_forma','9');
		return $this->db->update("erp_pagos_factura",$data);
			
	}
    
}

?>