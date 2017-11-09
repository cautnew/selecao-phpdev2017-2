<?php
require_once("usuario.php");

class UsuarioDao {
    /** @param Usuario $usuario */
    public static function selectByIdForm($usuario) {
        $ret = array();
        try {
            $mysql = new GDbMysql();
            $mysql->execute("SELECT usu_int_codigo,usu_var_nome,usu_var_email, usu_cha_status,usu_dti_inclusao FROM usuario WHERE usu_int_codigo = ? ", array("i", $usuario->getUsu_int_codigo()), true, MYSQL_ASSOC);
            if ($mysql->fetch()) {
                $ret = $mysql->res;
            }
            $mysql->close();
        } catch (GDbException $e) {
            echo $e->getError();
        }
        return $ret;
    }

    /** @param Usuario $usuario */
    public static function insert($usuario) {

        $return = array();
        $param = array("sss",
            $usuario->getUsu_var_nome(),
            $usuario->getUsu_var_email(),
            $usuario->getUsu_cha_status());
        try{
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_usuario_ins(?,?,?, @p_status, @p_msg, @p_insert_id);", $param, false);
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

    /** @param Usuario $usuario */
    public static function update($usuario) {

        $return = array();
        $param = array("isss",$usuario->getUsu_int_codigo(),$usuario->getUsu_var_nome(),$usuario->getUsu_var_email(),$usuario->getUsu_cha_status());
        try{
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_usuario_upd(?,?,?,?, @p_status, @p_msg);", $param, false);
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

    /** @param Usuario $usuario */
    public static function delete($usuario) {

        $return = array();
        $param = array("i",$usuario->getUsu_int_codigo());
        try {
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_usuario_del(?, @p_status, @p_msg);", $param, false);
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
