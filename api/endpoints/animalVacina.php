<?php 
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '_class/animalVacinaDao.php';
require_once("_class/animal.php");
require_once("_class/vacina.php");
require_once("_class/usuario.php");

$app->get('/animalVacina/{anv_int_codigo}', function (Request $request, Response $response) {
    $anv_int_codigo = $request->getAttribute('anv_int_codigo');
    
    $animalVacina = new AnimalVacina();
    $animalVacina->setAnv_int_codigo($anv_int_codigo);

    $data = AnimalVacinaDao::selectByIdForm($animalVacina);
    $code = count($data) > 0 ? 200 : 404;

	return $response->withJson($data, $code);
});

$app->post('/animalVacina', function (Request $request, Response $response) {
    $body = $request->getParsedBody();

    $animalVacina = new AnimalVacina();
    $animal = new Animal();
    $vacina = new Vacina();
    $usuario = new usuario();

    $animal->setAni_int_codigo($body['ani_int_codigo']);
    $vacina->setVac_int_codigo($body['vac_int_codigo']);
    $usuario->setUsu_int_codigo($body['usu_int_codigo']);

    $animalVacina->setAnimal($animal);
    $animalVacina->setVacina($vacina);
    $animalVacina->setAnv_dat_programacao($body['anv_dat_programacao']);
    $animalVacina->setAnv_dti_aplicacao($body['anv_dti_aplicacao']);
    $animalVacina->setUsuario($usuario);

    $data = AnimalVacinaDao::insert($animalVacina);
    $code = ($data['status']) ? 201 : 500;

	return $response->withJson($data, $code);
});

$app->put('/animalVacina/{anv_int_codigo}', function (Request $request, Response $response) {
    $body = $request->getParsedBody();
	$anv_int_codigo = $request->getAttribute('anv_int_codigo');
    
    $animalVacina = new AnimalVacina();
    $animalVacina->setVac_int_codigo($body['anv_int_codigo']);
    $animalVacina->getAnimal()->setAni_int_codigo($body['ani_int_codigo']);
    $animalVacina->getVacina()->setVac_int_codigo($body['anv_int_codigo']);
    $animalVacina->setAnv_dat_programacao($body['anv_dat_programacao']);
    $animalVacina->setAnv_dti_aplicacao($body['anv_dti_aplicacao']);
    $animalVacina->getUsuario()->setUsu_int_codigo($body['usu_int_codigo']);

    $data = AnimalVacinaDao::update($animalVacina);
    $code = ($data['status']) ? 200 : 500;

	return $response->withJson($data, $code);
});

$app->delete('/animalVacina/{anv_int_codigo}', function (Request $request, Response $response) {
	$anv_int_codigo = $request->getAttribute('anv_int_codigo');
    
    $animalVacina = new AnimalVacina();
    $animalVacina->setVac_int_codigo($body['anv_int_codigo']);

    $data = AnimalVacinaDao::delete($animalVacina);
    $code = ($data['status']) ? 200 : 500;

	return $response->withJson($data, $code);
});
