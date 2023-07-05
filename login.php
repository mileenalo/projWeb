<?php 

include("db.php");

$db = new Database();
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
* Verificação de ações requisitadas via AJAX:
* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (isset($_GET["a"])) {

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Edita conteúdo:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if ($_GET["a"] == "logar") {
		
		$email = $_POST["email"];
		$senha = md5($_POST["senha"]);
		
		if($email == "" || $senha == ""){
			echo '<div class="alert alert-warning" role="alert">';
				echo 'O campo email ou senha se encontra vazio!';
			echo '</div>';
		}else{

			$sel = $db->select("SELECT usu_id, usu_nome, usu_senha, usu_permissao FROM tb_usuarios WHERE usu_email = '$email'");

			if(!empty($sel)){		
				
				if ($sel[0]["usu_senha"] == $senha) {
					setcookie("codUsu", md5($sel[0]["usu_id"].date("Ymd")), 0);
					setcookie("idUsuario", $sel[0]["usu_id"], 0);
					setcookie("permissao", $sel[0]["usu_permissao"], 0);
					setcookie("nome", $email, 0);
					setcookie("senha", $senha, 0);

					$res["success"] = $_COOKIE["codUsu"];
					echo json_encode($res);

				}else{
					$res["error"] = 'Senha Incorreta!';
					echo json_encode($res);
				}
			}else{
				$res["error"] = 'Não há registro desse email de Usuario!';
				echo json_encode($res);
			}

		}
	}

	die();
}

include("header.php");

?>

<body class="bg-gray-200">
  	<main class="main-content  mt-0">
   		<div class="page-header align-items-start min-vh-100" style="background-image: url('https://images.unsplash.com/photo-1497294815431-9365093b7331?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1950&q=80');">
      		<span class="mask bg-gradient-dark opacity-6"></span>
      		<div class="container my-auto">
        		<div class="row">
          			<div class="col-lg-4 col-md-8 col-12 mx-auto">
            			<div class="card z-index-0 fadeIn3 fadeInBottom">
              				<div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                				<div class="bg-gradient-primary shadow-primary border-radius-lg py-3 pe-1" style="background:#DA522B;">
                  					<h4 class="text-white font-weight-bolder text-center mt-2 mb-0">Entrar</h4>
                				</div>
              				</div>
              			<div class="card-body">
                			<form role="form" class="text-start">
                  				<div class="input-group input-group-outline my-3">
                    				<label class="form-label">Email</label>
                    				<input type="email" class="form-control" id="frm_email">
                  				</div>
                  				<div class="input-group input-group-outline mb-3">
                    				<label class="form-label">Senha</label>
                    				<input type="password" class="form-control" id="frm_password">
                  				</div>
                  				<div class="text-center">
                    				<button type="button" class="btn bg-gradient-primary w-100 my-4 mb-2" style="background:#DA522B;" onclick="logar();">Entrar</button>
                  				</div>
                  				<p class="mt-4 text-sm text-center">
                    				Não tem cadastro?
                    				<a href="new_user.php" class="text-primary text-gradient font-weight-bold" style="color:#DA522B;">Cadastre agora!</a>
                  				</p>
                			</form>
              			</div>
						<div id=""></div>
           	 		</div>
          		</div>
        	</div>
      	</div>
  	</main>
</body>

<?php 
	include("bottom.php");
?>
<script>
	var win = navigator.platform.indexOf('Win') > -1;
	if (win && document.querySelector('#sidenav-scrollbar')) {
		var options = {
			damping: '0.5'
		}
		Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * Valida o login:
	 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	var ajax_div = $.ajax(null);
	const logar = () => {
		if (ajax_div) {
			ajax_div.abort();
		}
		ajax_div = $.ajax({
			cache: false,
			async: true,
			url: '?a=logar',
			type: 'post',
			data: {
				email: $("#frm_email").val(),
				senha: $("#frm_password").val(),
			},
			success: function retorno_ajax(retorno) {
				var obj = JSON.parse(retorno);
				if(obj.success != undefined){
					document.location.href="./home.php?uid="+obj.success;
				}else{
					alert("ERRO! " + obj.error);
				}
			}
		});
	}
</script>
<!-- Github buttons -->
<script async defer src="https://buttons.github.io/buttons.js"></script>
<!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
<script src="./assets/js/material-dashboard.min.js?v=3.0.4"></script>
