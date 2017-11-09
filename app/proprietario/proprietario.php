<?php
require_once '../_inc/global.php';

$form = new GForm();

$header = new GHeader('Proprietários');
$header->addLib(array('paginate'));
$header->show(false, 'proprietario/proprietario.php');
// ---------------------------------- Header ---------------------------------//


$html .= '<div id="divTable" class="row">';
$html .= getWidgetHeader();
//<editor-fold desc="Formulário de Filtro">
$html .= $form->open('filter', 'form-inline filterForm');
$html .= $form->addInput('text', 'p__prp_var_nome', false, array('placeholder' => 'Nome', 'class' => 'sepV_b m-wrap small'), false, false, false);

$html .= getBotoesFiltro();
$html .= getBotaoAdicionar();
$html .= $form->close();
//</editor-fold>

$paginate = new GPaginate('proprietario', 'proprietario_load.php', SYS_PAGINACAO);
$html .= $paginate->get();
$html .= '</div>'; //divTable
$html .= getWidgetFooter();
echo $html;

echo '<div id="divForm" class="row divForm">';
include 'proprietario_form.php';
echo '</div>';

// ---------------------------------- Footer ---------------------------------//
$footer = new GFooter();
$footer->show();
?>
<script>
    var pagCrud = 'proprietario_crud.php';
    var pagView = 'proprietario_view.php';
    var pagLoad = 'proprietario_load.php';

    function filtrar(page) {
        proprietarioLoad('', '', '', $('#filter').serializeObject(), page);
        return false;
    }

    $(function() {
        filtrar(1);
        $('#filter select').change(function() {
            filtrar(1);
            return false;
        });
        $('#filter').submit(function() {
            filtrar(1);
            return false;
        });
        $('#p__btn_limpar').click(function() {
            clearForm('#filter');
            filtrar(1);
        });
        $(document).on('click', '#p__btn_adicionar', function() {
            scrollTop();
            unselectLines();

            showForm('divForm', 'ins', 'Adicionar');
        });
        $(document).on('click', '.l__btn_editar, tr.linhaRegistro td:not([class~="acoes"])', function() {
            var prp_int_codigo = $(this).parents('tr.linhaRegistro').attr('id');

            scrollTop();
            selectLine(prp_int_codigo);

            loadForm(URL_API + 'proprietarios/' + prp_int_codigo, function(json) {
                showForm('divForm', 'upd', 'Editar');
            });
        });
        $(document).on('click', '.l__btn_excluir', function() {
            var prp_int_codigo = $(this).parents('tr.linhaRegistro').attr('id');

            $.gDisplay.showYN("Quer realmente deletar o item selecionado?", function() {
                $.gAjax.exec('DELETE', URL_API + 'proprietarios/' + prp_int_codigo, false, false, function(json) {
                    if (json.status) {
                        filtrar();
                    }
                });
            });
        });
    });
</script>