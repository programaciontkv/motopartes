<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class pdf_saldo_clientes_model extends CI_Model {

	public function lista_documentos_ctas($nm,$emp){
		$query ="select cli_ced_ruc as fac_identificacion, cli_raz_social as fac_nombre, c.pln_id from erp_factura f, erp_ctasxcobrar c, erp_i_cliente cl where c.com_id=f.fac_id and f.cli_id=cl.cli_id  $nm and f.emp_id=$emp group by cli_raz_social,cli_ced_ruc,c.pln_id
                     union
                     select cli_ced_ruc as fac_identificacion, cli_raz_social as fac_nombre,'0' as pln_id from erp_factura f, erp_i_cliente cl where  f.cli_id=cl.cli_id and not exists(select * from erp_ctasxcobrar c where c.com_id=f.fac_id) $nm and f.emp_id=$emp group by cli_raz_social,cli_ced_ruc order by fac_nombre, fac_identificacion,pln_id desc";
        $resultado=$this->db->query($query);
		return $resultado->result();
			
	}

	public function suma_documentos_cliente($id,$fec,$emp){
		$query ="select (select sum(p.fac_total_valor) from pagosxfactura p, erp_factura f, erp_i_cliente cl where f.cli_id=cl.cli_id and f.fac_id=p.fac_id and cli_ced_ruc ='$id' and (f.fac_estado=6 or fac_estado=4) and f.emp_id=$emp and f.fac_fecha_emision<='$fec') as fac_total_valor,
						(select sum(cta_monto) from erp_factura f, erp_ctasxcobrar c, erp_i_cliente cl where f.cli_id=cl.cli_id and cli_ced_ruc ='$id' and f.fac_id=c.com_id and (fac_estado=6 or fac_estado=4) and cta_estado=1 and f.emp_id=$emp and fac_fecha_emision<='$fec' and cta_fecha_pago<='$fec') as credito";
        $resultado=$this->db->query($query);
		return $resultado->row();
			
	}


	
}

?>