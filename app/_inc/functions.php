<?php

/**
 * Arquivo com as funções que serão utilizadas em todo o sistema.
 * Este arquivo já está inserido automaticamente em global.php
 */

/**
 * Retorna o html com os botões de filtro padrão
 *
 * @param bool $expandir default=false
 * @return string
 */
function getBotoesFiltro($expandir = false) {
    $form = new GForm();
    $retorno = '';
    $retorno .= $form->addInput('hidden', 'p__selecionado', false, false, false, false, false);

    $retorno .= $form->addButton('p__btn_filtrar', '<i class="fa fa-search"></i>', array('class' => 'btn  pull-left grey-cascade', 'data-title' => 'Search'), 'submit');
    $retorno .= $form->addButton('p__btn_limpar', '<i class="fa fa-reply"></i>', array('class' => 'btn pull-left sepV_a hidden-phone', 'data-title' => 'Clear filter'));
    if ($expandir) {
        $retorno .= $form->addButton('p__btn_expandir', '<i class="fa fa-filter"></i>', array('class' => 'btn pull-left sepV_a hidden-phone', 'data-title' => 'Advanced filter'));
        $retorno .= "<script>";
        $retorno .= "$('#p__btn_expandir').click(function() {";
        $retorno .= "var display = $('#divFiltrosAvancados').css('display');";
        $retorno .= "if (display === 'none') {";
        $retorno .= "$('#divFiltrosAvancados').show();";
        $retorno .= "} else {";
        $retorno .= "$('#divFiltrosAvancados').hide();";
        $retorno .= "}";
        $retorno .= "});";
        $retorno .= '$("#p__btn_expandir").tooltip({placement:"top", container:"body"}); ';
        $retorno .= '</script>';
    }
    $retorno .= '<script> $("#p__btn_limpar, #p__btn_filtrar").tooltip({placement:"top", container:"body"}); </script>';


    return $retorno;
}

/**
 *
 * @param bool $excluir defaul=false
 * @return type
 */
function getBotoesAcao($excluir = false, $cancelar = true) {
    $form = new GForm();
    $retorno = '';

    $retorno .= $form->addButton('f__btn_salvar', '<i class="fa fa-check"></i> Salvar', array('class' => 'btn blue pull-left sepV_b'), 'submit');
    if ($cancelar) {
        $retorno .= $form->addButton('f__btn_cancelar', '<i class="fa fa-ban"></i> Cancelar', array('class' => 'btn pull-left'));
        $retorno .= "<script> $('#f__btn_cancelar').hover(function() { $(this).addClass('yellow'); }, function() { $(this).removeClass('yellow'); }); </script>";
    }

    if ($excluir) {
        $retorno .= $form->addButton('f__btn_excluir', '<i class="fa fa-trash"></i> Remover', array('class' => 'btn pull-right'));
        $retorno .= "<script> $('#f__btn_excluir').hover(function() { $(this).addClass('red'); }, function() { $(this).removeClass('red'); }); </script>";
    }

    return $retorno;
}

function getBotaoAdicionar($id = 'p__btn_adicionar') {
    $form = new GForm();
    return $form->addButton($id, '<i class="fa fa-plus"></i> <span class="hidden-phone">Adicionar</span>', array('class' => 'btn sepH_a sepV_a blue-steel pull-left'));
}


function validaCPF($cpf) {
    // Verifica se o número digitado contém todos os digitos
    $cpf = str_pad(preg_replace('/[^0-9]/i', '', $cpf), 11, '0', STR_PAD_LEFT);

    // Verifica se nenhuma das sequências abaixo foi digitada, caso seja, retorna falso
    if (strlen($cpf) != 11 || $cpf == '00000000000' || $cpf == '11111111111' || $cpf == '22222222222' || $cpf == '33333333333' || $cpf == '44444444444' || $cpf == '55555555555' || $cpf == '66666666666' || $cpf == '77777777777' || $cpf == '88888888888' || $cpf == '99999999999') {
        return false;
    } else {   // Calcula os números para verificar se o CPF é verdadeiro
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf{$c} * (($t + 1) - $c);
            }

            $d = ((10 * $d) % 11) % 10;

            if ($cpf{$c} != $d) {
                return false;
            }
        }

        return true;
    }
}

function validaDominio($dominio) {
    if (!empty($dominio)) {
        if (strstr($dominio, '@'))
            list ($user, $dominio) = explode('@', $dominio);

        if (checkdnsrr($dominio, 'MX')) {
            return true;
        } else {
            return false;
        }
    } else {
        return true;
    }
}

function validaIp($ip) {
    return preg_match("/^(([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]).){3}([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/", $ip);
}

/**
 *
 * @param string $title
 * @param string $tools ''
 * @param string $cor 'blue'
 * @param string $span 'span12'
 * @return string
 */
function getWidgetHeader($title = '', $tools = '', $cor = 'blue-steel', $span = 'col-md-12') {
    $html = '';
    $html .= '<div class="' . $span . '">';
    $html .= '<div class="portlet light">';

    //<editor-fold desc="portlet-title">
    if(!empty($title)){
        $html .= '<div class="portlet-title">';
        $html .= '<div class="caption">';
        $html .= '<span class="caption-subject bold uppercase font-' . $cor . '">' . $title . '</span>';
        $html .= '</div>';

        if (!empty($tools)) {
            $html .= '<div class="tools ' . $cor . '">';
            $html .= $tools;
            $html .= '</div>';
        }
        $html .= '</div>';
    }
    //</editor-fold>

    $html .= '<div class="portlet-body form">';

    return $html;
}

function getWidgetFooter() {
    $html = '';
    $html .= '</div>'; //portlet-body
    $html .= '</div>'; //portlet
    $html .= '</div>'; //span6

    return $html;
}


/**
 *
 * @param string $titulo
 * @param string $span 'span12'
 * @return string
 */
function getWidgetViewHeader($titulo, $span = 'span12') {
    $html = '<div class="row-fluid">';
    $html .= '<div class="' . $span . '">';
    $html .= '<div class="widget-view">';
    $html .= '<div class="widget-view-header clearfix">';
    $html .= '<h5>' . $titulo . '</h5>';
    $html .= '</div>';
    $html .= '<div class="widget-view-container">';

    return $html;
}

function getWidgetViewFooter() {
    $html = '';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';

    return $html;
}

/**
 * Função para retornar um id aleatório
 *
 * @return string
 */

function geraPalavra($ini, $fim) {
    $CaracteresAceitos = '0123456789abcdefghijklmnopqrstuvxywz';
    $CaracteresAceitos = str_shuffle($CaracteresAceitos);
    $max = strlen($CaracteresAceitos) - 1;
    $palavra = NULL;
    for ($i = 0; $i < 4; $i++) {
        $palavra .= $CaracteresAceitos{mt_rand(0, $max)};
    }
    return str_shuffle(substr(uniqid($palavra, true), $ini, $fim));
}

function primeiroUltimoNome($texto) {
    $arrTexto = explode(' ',$texto);
    $primeiro = $arrTexto[0];
    $ultimo = (count($arrTexto) > 1) ? $arrTexto[count($arrTexto)-1] : '';
    $retorno = trim($primeiro . ' ' . $ultimo);
    return $retorno;
}
