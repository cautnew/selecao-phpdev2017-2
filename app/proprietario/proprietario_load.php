<?php

require_once("../_inc/global.php");

$html = '';
$mysql = new GDbMysql();
$form = new GForm();
//------------------------------ Parâmetros ----------------------------------//
$type = $_POST['type'];
$page = $_POST['page'];
$count = $_POST['count'];
$rp = (int) $_POST['rp'];
$start = (($page - 1) * $rp);
//------------------------------ Parâmetros ----------------------------------//
//-------------------------------- Filtros -----------------------------------//
$filter = new GFilter();

$prp_var_nome = $_POST['p__prp_var_nome'];

if (!empty($prp_var_nome)) {
    $filter->addFilter('AND', 'prp_var_nome', 'LIKE', 's', '%' . str_replace(' ', '%', $prp_var_nome) . '%');
}

//-------------------------------- Filtros -----------------------------------//

try {
    if ($type == 'C') {
        $query = "SELECT count(1) FROM proprietario " . $filter->getWhere();
        $param = $filter->getParam();
        $mysql->execute($query, $param);
        if ($mysql->fetch()) {
            $count = ceil($mysql->res[0] / $rp);
        }
        $count = $count == 0 ? 1 : $count;
        echo json_encode(array('count' => $count));
    } else if ($type == 'R') {
        $filter->setOrder(array('prp_var_nome' => 'ASC'));
        $filter->setLimit($start, $rp);

        $query = "SELECT prp_int_codigo, prp_var_nome, prp_var_email, prp_var_tel FROM proprietario " . $filter->getWhere();

        $param = $filter->getParam();
        $mysql->execute($query, $param);

        if ($mysql->numRows() > 0) {
            $html .= '<table class="table table-striped table-hover">';
            $html .= '<thead>';
            $html .= '<tr>';
            $html .= '<th>Nome</th>';
            $html .= '<th>Email</th>';
            $html .= '<th>Telefone</th>';
            $html .= '<th class="__acenter hidden-phone" width="100px">Actions</th>';
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';
            while ($mysql->fetch()) {
                $class = ($_POST['p__selecionado'] == $mysql->res['prp_int_codigo']) ? 'success' : '';
                $html .= '<tr id="' . $mysql->res['prp_int_codigo'] . '" class="linhaRegistro ' . $class . '">';
                $html .= '<td>' . $mysql->res['prp_var_nome'] . '</td>';
                $html .= '<td>' . $mysql->res['prp_var_email'] . '</td>';
                $html .= '<td>' . $mysql->res['prp_var_tel'] . '</td>';
                //<editor-fold desc="Actions">
                    $html .= '<td class="__acenter hidden-phone acoes">';
                    $html .= $form->addButton('l__btn_editar', '<i class="fa fa-pencil"></i>', array('class' => 'btn btn-small btn-icon-only l__btn_editar', 'title' => 'Edit'));
                    $html .= $form->addButton('l__btn_excluir', '<i class="fa fa-trash"></i>', array('class' => 'btn btn-small btn-icon-only l__btn_excluir', 'title' => 'Remove'));
                    $html .= '</td>';
                //</editor-fold>
                $html .= '</tr>';
            }
            $html .= '</tbody>';
            $html .= '</table>';
        } else {
            $html .= '<div class="nenhumResultado">Nenhum resultado encontrado.</div>';
        }

        echo $html;
    }
} catch (GDbException $exc) {
    echo $exc->getError();
}
?>