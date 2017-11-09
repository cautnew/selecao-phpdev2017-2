<?php
$form = new GForm();
$conn = new GDbMysql();

//Função que percorre todo o resultado de uma query e o retorna em uma array unidimensional
function prepArray( $res )
{
    $arr = array();
    while( $res->fetch() )
        $arr[ $res->res[ 0 ] ] = $res->res[ 1 ];

    return $arr;
}

//<editor-fold desc="Header">
$title = '<span class="acaoTitulo"></span>';
$tools = '<a id="f__btn_voltar"><i class="fa fa-arrow-left font-blue-steel"></i> <span class="hidden-phone font-blue-steel bold uppercase">Voltar</span></a>';
$htmlForm .= getWidgetHeader($title, $tools);
//</editor-fold>
//<editor-fold desc="Formulário">
$htmlForm .= $form->open('form', 'form-vertical form');
$htmlForm .= $form->addInput('hidden', 'acao', false, array('value' => 'ins', 'class' => 'acao'), false, false, false);
$htmlForm .= $form->addInput('hidden', 'anv_int_codigo', false, array('value' => ''), false, false, false);

$conn->execute( "SELECT ani_int_codigo, ani_var_nome FROM animal;" );

$htmlForm .= $form->addSelect('ani_int_codigo', prepArray( $conn ), '', 'Animal*', array('validate' => 'required'), false, false, true, '', 'Selecione...');

$conn->execute( "SELECT vac_int_codigo, vac_var_nome FROM vacina;" );

$htmlForm .= $form->addSelect('vac_int_codigo', prepArray( $conn ), '', 'Vacina*', array('validate' => 'required'), false, false, true, '', 'Selecione...');

$htmlForm .= $form->addDateField('anv_dat_programacao', 'Data de agendamento*');
$htmlForm .= $form->addDateField('anv_dti_aplicacao', 'Data vacinação');

$conn->execute( "SELECT usu_int_codigo, usu_var_nome FROM usuario;" );

$htmlForm .= $form->addSelect('usu_int_codigo', prepArray( $conn ), '', 'Usuário', null, false, false, true, '', 'Selecione...');

$htmlForm .= '<div class="form-actions">';
$htmlForm .= getBotoesAcao(true);
$htmlForm .= '</div>';
$htmlForm .= $form->close();
//</editor-fold>
$htmlForm .= getWidgetFooter();

echo $htmlForm;
?>
<script>
    $(function() {
        $('#form').submit(function() {
            var anv_int_codigo = $('#anv_int_codigo').val();
            $('#p__selecionado').val();
            if ($('#form').gValidate()) {
                var method = ($('#acao').val() == 'ins') ? 'POST' : 'PUT';
                var endpoint = ($('#acao').val() == 'ins') ? URL_API + 'animalVacina' : URL_API + 'animalVacina/' + anv_int_codigo;
                $.gAjax.exec(method, endpoint, $('#form').serializeArray(), false, function(json) {
                    if (json.status) {
                        showList(true);
                    }
                });
            }
            return false;
        });

        $('#f__btn_cancelar, #f__btn_voltar').click(function() {
            showList();
            return false;
        });

        $('#f__btn_excluir').click(function() {
            var anv_int_codigo = $('#ani_int_codigo').val();

            $.gDisplay.showYN("Quer realmente deletar o item selecionado?", function() {
                $.gAjax.exec('DELETE', URL_API + 'animalVacina/' + anv_int_codigo, false, false, function(json) {
                    if (json.status) {
                        showList(true);
                    }
                });
            });
        });
    });
</script>