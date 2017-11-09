<?php
/*CHECK SE O CLIENTE ESTÁ LOGADO*/
session_start();

if( isset( $_SESSION[ 'LOGIN_REDE' ] ) )
{ header( "Location:app/index.php" ); }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <title>Login - SimplesVet</title>
  
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <style>
  	*
{
  font-family: Arial;
  font-size: 12px;
}

body
{ background-color: #bebebe; }

a
{
  color: #1188cc;
  text-decoration: none;
}

a:hover
{
  color: #1155dd;
  text-decoration: underline;
}

h1
{
  color: #696969;
  font-family: 'Kaushan Script';
  font-size: 55px;
  margin: 30px auto 10px auto;
}

h4
{
  font-size: 20px;
  color: #494949;
  margin: 5px auto 5px auto;
}

.clr
{ clear: both; }

.login
{
  border: 3px #aaa solid;
  border-radius: 10px;
  width: 400px;
  margin: 50px auto 20px auto;
  background-color: #ddd;
  padding: 10px;
}

.login input
{
  border-radius: 5px;
  border: 2px #ccc solid;
  display: block;
  font-weight: bold;
  font-size: 14px;
  width: 376px;
  padding: 10px;
  margin-bottom: 4px;
  text-align: center;
}

.login button
{
  background-color: #1188cc;
  border-radius: 5px;
  color: #fff;
  padding: 10px;
  font-weight: bold;
  border: none;
  width: 100%;
}

.panel
{
  border-radius: 5px;
  border: 2px #000 solid;
  width: 500px;
}

.panel .phead
{
  background-color: #000;
  color: #fff;
  padding: 5px;
}

footer
{
  width: 100%;
  text-align: center;
}

footer h4, footer h4 *
{ font-size: 15px; }
  </style>
  <link href='fonts/google-fonts-kaushan-script.css' rel='stylesheet'>

  <link rel="icon" href="img/logo/favicon_clarosqr.ico">
</head>

<body>

  <center>
  	<h1>Área Restrita</h1>
  	<h4>Faça seu login abaixo</h4>
  </center>  
  <div class="login">
    <form action="procLogin.php" method="post">
      <input type="text" name="us" placeholder="Usuário" maxlength="9" required autofocus> 
      <input type="password" name="pw" placeholder="Senha" maxlength="20" required>

      <button type="submit">Login</button>
    </form>
  </div>
  <footer>
  	<h4>SimplesVet</a></h4>
  </footer> 

</body>

</html>