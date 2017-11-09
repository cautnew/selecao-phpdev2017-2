<?php

class Proprietario{

	private $prp_int_codigo;
	private $prp_var_nome;
	private $prp_var_email;
	private $prp_var_tel;
	private $prp_dti_inclusao;

	public function getPrp_int_codigo() {
		return $this->prp_int_codigo;
	}

	public function setPrp_int_codigo($prp_int_codigo) {
		$this->prp_int_codigo = $prp_int_codigo;
	}

	public function getPrp_var_nome() {
		return $this->prp_var_nome;
	}

	public function setPrp_var_nome($prp_var_nome) {
		$this->prp_var_nome = $prp_var_nome;
	}

	public function getPrp_var_email() {
		return $this->prp_var_email;
	}

	public function setPrp_var_email($prp_var_email) {
		$this->prp_var_email = $prp_var_email;
	}

	public function getPrp_var_tel() {
		return $this->prp_var_tel;
	}

	public function setPrp_var_tel($prp_var_tel) {
		$this->prp_var_tel = $prp_var_tel;
	}

	public function getPrp_dti_inclusao() {
		return $this->prp_dti_inclusao;
	}

	public function setPrp_dti_inclusao($prp_dti_inclusao) {
		$this->prp_dti_inclusao = $prp_dti_inclusao;
	}

}
