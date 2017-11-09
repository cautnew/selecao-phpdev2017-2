<?php 
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '_class/proprietarioDao.php';

$app->get('/proprietarios/{prp_int_codigo}', function (Request $request, Response $response) {
    $prp_int_codigo = $request->getAttribute('prp_int_codigo');
    
    $proprietario = new Proprietario();
    $proprietario->setPrp_int_codigo($prp_int_codigo);

    $data = ProprietarioDao::selectByIdForm($proprietario);
    $code = count($data) > 0 ? 200 : 404;

	return $response->withJson($data, $code);
});

$app->post('/proprietarios', function (Request $request, Response $response) {
    $body = $request->getParsedBody();

    $proprietario = new Proprietario();
    $proprietario->setPrp_var_nome($body['prp_var_nome']);
    $proprietario->setPrp_var_email($body['prp_var_email']);
    $proprietario->setPrp_var_tel($body['prp_var_tel']);

    $data = ProprietarioDao::insert($proprietario);
    $code = ($data['status']) ? 201 : 500;

	return $response->withJson($data, $code);
});

$app->put('/proprietarios/{prp_int_codigo}', function (Request $request, Response $response) {
    $body = $request->getParsedBody();
	$prp_int_codigo = $request->getAttribute('prp_int_codigo');
    
    $proprietario = new Proprietario();
    $proprietario->setPrp_int_codigo($prp_int_codigo);
    $proprietario->setPrp_var_nome($body['prp_var_nome']);
    $proprietario->setPrp_var_email($body['prp_var_email']);
    $proprietario->setPrp_var_tel($body['prp_var_tel']);

    $data = ProprietarioDao::update($proprietario);
    $code = ($data['status']) ? 200 : 500;

	return $response->withJson($data, $code);
});

$app->delete('/proprietarios/{prp_int_codigo}', function (Request $request, Response $response) {
	$prp_int_codigo = $request->getAttribute('prp_int_codigo');
    
    $proprietario = new Proprietario();
    $proprietario->setPrp_int_codigo($prp_int_codigo);

    $data = ProprietarioDao::delete($proprietario);
    $code = ($data['status']) ? 200 : 500;

	return $response->withJson($data, $code);
});
