<!DOCTYPE html>
<html lang="pt-BR">
	<head>
		<meta charset="UTF-8" />
		<!--Let browser know website is optimized for mobile-->
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		<!--Import Google Icon Font-->
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<!--Import jquery.js-->
		<script type="text/javascript" src="common/js/jquery.min.js"></script>
		<!--Import mask.js-->
		<script type="text/javascript" src="common/js/jquery.mask.min.js"></script>
		<!--Import redirect.js-->
		<script type="text/javascript" src="common/js/jquery.redirect.js"></script>		
		<!--Import jQuery RichText-->
		<link rel="stylesheet" href="common/css/richtext.min.css">
        <script type="text/javascript" src="common/js/jquery.richtext.js"></script>
		<!--Import materialize.css-->
		<link type="text/css" rel="stylesheet" href="common/css/materialize.min.css"  media="screen,projection"/>
		<!--Import clipe.css-->
		<link type="text/css" rel="stylesheet" href="common/css/main.css"  media="screen,projection"/>
		<!--Import main.js-->
		<script type="text/javascript" src="common/js/main.js"></script>

		<?php

			// Se houverem toasts de redirecionamento
			if(isset($_POST['redirect_toast'])) {

				echo '<script> $(document).ready(function(e){ M.toast({text: "'.str_protect($_POST['redirect_toast']).'", classes: "rounded success"}); }); </script>';
		
			}
		
		?>

	</head>

	<body>
		<header>

			<?php

			if(isset($_COOKIE['token'])) {

				// Inicia objetos do Materialize
				$logout = new buildMaterial;

				// Imprimir Formulário
				echo $logout->buildForm('POST', 'none');

			}						
			
			?>

			<nav>
				<div class="nav-wrapper">
					<a href="#" class="brand-logo"><img src="media/img/logos/<?=constant("EMPRESA_ID")?>.png" height="25px"></a>
					<ul id="nav-mobile" class="right hide-on-med-and-down">
						
						<?php

						if(isset($_COOKIE['token'])) {

							?>

							<li><a href="?page=configuracoes">Configurações</a></li>
							<li><a href="?page=setores">Setores</a></li>
							<li><a href="?page=colaboradores">Colaboradores</a></li>
							<li><a href="?page=processos">Processos</a></li>
							<li><a href="?page=dashboard">Dashboard</a></li>
							<li><a href="?page=notificacoes"><i class="material-icons">notifications_none</i></a></li>
							<li><a href="?page=perfil"><i class="material-icons">account_circle</i></a></li>

							<?php

							// Imprimir Botão de Envio
							echo "<li>".$logout->buildFormSubmit('a', '<i class="material-icons">logout</i>', 'api/logout.php')."</li>";

						} else {

							echo "<li><a href=\"?page=sobre\">Sobre o Clipe!</a></li>";
							
						}					
						
						?>

					</ul>
				</div>
			</nav>
		</header>
        <main>
        <div class="container">