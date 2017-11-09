<?php
require_once("proprietario.php");

class ProprietarioDao {
    /** @param Proprietario $proprietario */
    public static function selectByIdForm($proprietario) {
        $ret = array();
        try {
            $mysql = new GDbMysql();
            $mysql->execute("SELECT prp_int_codigo, prp_var_nome, prp_var_email, prp_var_tel FROM proprietario WHERE prp_int_codigo = ? ", array("i", $proprietario->getPrp_int_codigo()), true, MYSQL_ASSOC);
            if ($mysql->fetch()) {
                $ret = $mysql->res;
            }
            $mysql->close();
        } catch (GDbException $e) {
            echo $e->getError();
        }
        return $ret;
    }

    /** @param Proprietario $proprietario */
    public static function insert($proprietario) {

        $return = array();
        $param = array("sss",
            $proprietario->getPrp_var_nome(),
            $proprietario->getPrp_var_email(),
            $proprietario->getPrp_var_tel());
        try{
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_proprietario_ins(?,?,?, @p_status, @p_msg, @p_insert_id);", $param, false);
            $mysql->execute("SELECT @p_status, @p_msg, @p_insert_id");
            $mysql->fetch();
            $return["status"] = ($mysql->res[0]) ? true : false;
            $return["msg"] = $mysql->res[1];
            $return["insertId"] = $mysql->res[2];
            $mysql->close();
        } catch (GDbException $e) {
            $return["status"] = false; 
            $return["msg"] = $e->getError();
        }
        return $return;
    }

    /** @param Proprietario $proprietario */
    public static function update($proprietario) {

        $return = array();
        $param = array("isss",
            $proprietario->getPrp_int_codigo(),
            $proprietario->getPrp_var_nome(),
            $proprietario->getPrp_var_email(),
            $proprietario->getPrp_var_tel());
        try{
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_proprietario_upd(?,?,?,?, @p_status, @p_msg);", $param, false);
            $mysql->execute("SELECT @p_status, @p_msg");
            $mysql->fetch();
            $return["status"] = ($mysql->res[0]) ? true : false;
            $return["msg"] = $mysql->res[1];
            $mysql->close();
        } catch (GDbException $e) {
            $return["status"] = false;
            $return["msg"] = $e->getError();
        }
        return $return;
    }

    /** @param Proprietario $proprietario */
    public static function delete($proprietario) {

        $return = array();
        $param = array("i",$proprietario->getPrp_int_codigo());
        try {
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_proprietario_del(?, @p_status, @p_msg);", $param, false);
            $mysql->execute("SELECT @p_status, @p_msg");
            $mysql->fetch();
            $return["status"] = ($mysql->res[0]) ? true : false;
            $return["msg"] = $mysql->res[1];
            $mysql->close();
        } catch (GDbException $e) {
            $return["status"] = false;
            $return["msg"] = $e->getError();
        }
        return $return;
    }
}
