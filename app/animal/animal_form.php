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
$htmlForm .= $form->open('form', 'form-vertical form', 'post', '_self', '', true);
$htmlForm .= $form->addInput('hidden', 'acao', false, array('value' => 'ins', 'class' => 'acao'), false, false, false);
$htmlForm .= $form->addInput('hidden', 'ani_int_codigo', false, array('value' => ''), false, false, false);
$htmlForm .= $form->addInput('text', 'ani_var_nome', 'Nome*', array('maxlength' => '50', 'validate' => 'required'));
$htmlForm .= $form->addSelect('ani_cha_vivo', array('S' => 'Sim', 'N' => 'Não'), '', 'Vivo*', array('validate' => 'required'), false, false, true, '', 'Selecione...');

$htmlForm .= $form->addInput('text', 'ani_dec_peso', 'Peso*', array('maxlength' => '100', 'validate' => 'required'));
//$htmlForm .= $form->addInput('text', 'rac_int_codigo', 'Raça*', array('maxlength' => '100', 'validate' => 'required'));

$conn->execute( "SELECT rac_int_codigo, rac_var_nome FROM raca;" );

$htmlForm .= $form->addSelect('rac_int_codigo', prepArray( $conn ), '', 'Raça*', array('validate' => 'required'), false, false, true, '', 'Selecione...');

$conn->execute( "SELECT prp_int_codigo, prp_var_nome FROM proprietario;" );

$htmlForm .= $form->addSelect('prp_int_codigo', prepArray( $conn ), '', 'Proprietário*', array('validate' => 'required'), false, false, true, '', 'Selecione...');

$htmlForm .= $form->addInput('file', 'ani_img_perfil', 'Imagem do animal', null);

$htmlForm .= "<p>Imagem atual: </p>";
$htmlForm .= "<img id=\"ani_img_foto\" width=\"150\">";

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
        $('#ani_dec_peso').maskMoney({thousands:'.', decimal:',', precision:3,  affixesStay: false});

        $('#form').submit(function() {
            var ani_int_codigo = $('#ani_int_codigo').val();
            $('#p__selecionado').val();
            if ($('#form').gValidate()) {
                var method = ($('#acao').val() == 'ins') ? 'POST' : 'PUT';
                var endpoint = ($('#acao').val() == 'ins') ? URL_API + 'animais' : URL_API + 'animais/' + ani_int_codigo;
                $.gAjax.exec(method, endpoint, $('#form').serializeArray(), false, function(json) {
                    if (json.status) {
                        showList(true);
                    }
                });
            }
            return false;
        });
        $('#ani_img_foto').attr( 'src', "../img/" + $('#ani_int_codigo').val() );

        $('#f__btn_cancelar, #f__btn_voltar').click(function() {
            showList();
            return false;
        });

        $('#f__btn_excluir').click(function() {
            var ani_int_codigo = $('#ani_int_codigo').val();

            $.gDisplay.showYN("Quer realmente deletar o item selecionado?", function() {
                $.gAjax.exec('DELETE', URL_API + 'usuarios/' + ani_int_codigo, false, false, function(json) {
                    if (json.status) {
                        showList(true);
                    }
                });
            });
        });
    });
</script>