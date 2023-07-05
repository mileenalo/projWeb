<?

if (!defined("IS_INCLUDE"))
	die();
    
class Functions extends DB {
	/**
	 * Variáveis para inclusão de templates
	 */
	var $parse;
	var $template;
	var $fileHandle;
	var $data;
	
	var $file;
	var $openMode;
	var $fileInfo;
	var $fileData;
	var $handler;
	
    function dtoc($data){
        if(trim($data) <> ''){
            $datafmt = substr($data,6,2) . '/' . substr($data,4,2) . '/' . substr($data,0,4);
        }else{
            $datafmt = '';
        }
        return $datafmt;
    }
    
	function dtmysql($data){
        if(trim($data) <> ''){
            $datafmt = substr($data,0,4) . '-' . substr($data,4,2) . '-' . substr($data,6,2);
        }else{
            $datafmt = '';
        }
        return $datafmt;
    }
	
	//Api da Keep treinamento das lojas
	function token_keep(){
		$curl = curl_init();
		curl_setopt_array($curl, array(
			//CURLOPT_URL => 'https://myaccount-api-stage.keepsdev.com/auth',
			CURLOPT_URL => 'https://myaccount-api.keepsdev.com/auth',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS =>'{
				"username": "user_integration@buddemeyer.com.br",
				"password": "123456"
			}',
			CURLOPT_HTTPHEADER => array(
				'Content-Type: application/json'
			),
		));
		$response = curl_exec($curl);
		curl_close($curl);
		$data = json_decode($response);
		$token = $data->access_token;
		return $token;
	}


    function dtos($data){
        $datafmt = substr($data, 6,4) . substr($data, 3,2) . substr($data, 0,2);
        return $datafmt;
    }
    
    function utf8_converter($array){
        array_walk_recursive($array, function(&$item, $key){
            if(!mb_detect_encoding($item, 'utf-8', true)){
                    $item = utf8_encode($item);
            }
        });
     
        return $array;
    }
	
	function retiraAcento($string){
		return preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"),$string);
	}
    
    function difminutos($data){
    	$hora_data_atual = date("Y-m-d H:i:s");
    	$data = strtotime($data);
    	$hora_data_atual = strtotime($hora_data_atual);
    	$diferenca = $hora_data_atual - $data;
    	$dias = floor($diferenca / 86400);
    	$horas = floor($diferenca / 3600);
    	$minutos = floor(($diferenca / 60));
    	$segundos = floor($diferenca % 60);
    	$resultado = "{$minutos}";
    	return $resultado;
    }
    
    /**
    * Formata a data no padrão dd/mm/yyyy - hh:mm:ss
    * @param date $st_data
    * @return date
    */
	function utf8_string($string){
		$string = mb_convert_encoding($string, "UTF-8");
		return preg_replace(
				array(
						'/\x00/', '/\x01/', '/\x02/', '/\x03/', '/\x04/',
						'/\x05/', '/\x06/', '/\x07/', '/\x08/', '/\x09/', '/\x0A/',
						'/\x0B/','/\x0C/','/\x0D/', '/\x0E/', '/\x0F/', '/\x10/', '/\x11/',
						'/\x12/','/\x13/','/\x14/','/\x15/', '/\x16/', '/\x17/', '/\x18/',
						'/\x19/','/\x1A/','/\x1B/','/\x1C/','/\x1D/', '/\x1E/', '/\x1F/'
				),
				array(
						"\u0000", "\u0001", "\u0002", "\u0003", "\u0004",
						"\u0005", "\u0006", "\u0007", "\u0008", "\u0009", "\u000A",
						"\u000B", "\u000C", "\u000D", "\u000E", "\u000F", "\u0010", "\u0011",
						"\u0012", "\u0013", "\u0014", "\u0015", "\u0016", "\u0017", "\u0018",
						"\u0019", "\u001A", "\u001B", "\u001C", "\u001D", "\u001E", "\u001F"
				),
				$string
				);
	}
	
    function datahora( $st_data )
	{
        if(isset($st_data) && $st_data != ''){
		  $st_data = date("d/m/Y - H:i:s", strtotime($st_data));
        }
		
		return $st_data;
	}
	
	function simpnac($valor){
		if ($valor == '1'){
			$valor = '1 - Sim';
		}elseif($valor == '2'){
			$valor = '2 - Não';
		}
		return $valor;
	}
	
	function trocarepedito($valor){
		$valor = str_replace(',,', ',', $valor);
		return $valor;
	}
	
	function limpar($valor){
		$valor = trim(str_replace("&", " ",str_replace("'", " ", $valor)));
		return $valor;
	}
	
	function netrin_ast($valor){
		if ($valor == '********'){
			$valor = '';
		}
		return $valor;
	}
	
	function retirapontos($valor){
		$valor = str_replace(".", "", $valor);
		$valor = str_replace('-', "", $valor);
		return $valor;
	}

	function formataCEP($campo, $formatado = true){
		if(trim($campo) == ""){
			return false;
		}
		if($formatado){
			$mascara = '#####-###'	;
			$indice = -1;
			for ($i=0; $i < strlen($mascara); $i++) {
				if ($mascara[$i]=='#') $mascara[$i] = $campo[++$indice];
			}
			$campo = $mascara;
		}else{
			$campo = str_replace("-", "", $campo);
			$campo = preg_replace("[' '-./ t]",'',$campo);
		}
		return $campo;
	}

	function formatarCPF_CNPJ($campo, $formatado = true){
		$codigoLimpo = preg_replace("[' '-./ t]",'',$campo);
		$tamanho = (strlen($codigoLimpo) -2);
		if ($tamanho != 9 && $tamanho != 12){
			return $campo; 
		}
		if ($formatado){ 
			$mascara = ($tamanho == 9) ? '###.###.###-##' : '##.###.###/####-##'; 
			$indice = -1;
			for ($i=0; $i < strlen($mascara); $i++) {
				if ($mascara[$i]=='#') $mascara[$i] = $codigoLimpo[++$indice];
			}
			$retorno = $mascara;
		}else{
			$retorno = $codigoLimpo;
		}
		return $retorno;
	}

	function ennumeros($string) {
		return strpbrk($string, '0123456789') !== false;
	}
	/**
	 *Função para mostra a data
	 */
	function MostraData($date)
	{
		$data = explode('-',$date);
		$dia = $data[3];
		switch ($data[1])
		{
			case '01':
				$mes = 'Janeiro';
				break;
			
			case '02':
				$mes = 'Fevereiro';
				break;
			
			case '03':
				$mes = 'Março';
				break;

			case '04':
				$mes = 'Abril';
				break;			
			
			case '05':
				$mes = 'Maio';
				break;
				
			case '06':
				$mes = 'Junho';
				break;

			case '07':
				$mes = 'Julho';
				break;

			case '08':
				$mes = 'Agosto';
				break;

			case '09':
				$mes = 'Setembro';
				break;

			case '10':
				$mes = 'Outubro';
				break;

			case '11':
				$mes = 'Novembro';
				break;

			case '12':
				$mes = 'Dezembro';
				break;			
		}
		
		switch ($dia)
		{
			case '0':
				$dia = "Domingo";
				break;
				
			case '1':
				$dia = "Segunda";
				break;			

			case '2':
				$dia = "Terça";
				break;

			case '3':
				$dia = "Quarta";
				break;

			case '4':
				$dia = "Quinta";
				break;

			case '5':
				$dia = "Sexta";
				break;

			case '6':
				$dia = "Sábado";
				break;			
				
		}
		return $dia.' '.$data[2].' de '.$mes.' de '.$data[0];
	}
	
    function mes($mes){
		switch ($mes)
		{
			case '01':
				$mes = 'Janeiro';
				break;
			
			case '02':
				$mes = 'Fevereiro';
				break;
			
			case '03':
				$mes = 'Março';
				break;

			case '04':
				$mes = 'Abril';
				break;			
			
			case '05':
				$mes = 'Maio';
				break;
				
			case '06':
				$mes = 'Junho';
				break;

			case '07':
				$mes = 'Julho';
				break;

			case '08':
				$mes = 'Agosto';
				break;

			case '09':
				$mes = 'Setembro';
				break;

			case '10':
				$mes = 'Outubro';
				break;

			case '11':
				$mes = 'Novembro';
				break;

			case '12':
				$mes = 'Dezembro';
				break;			
		}
        return $mes;
    }
    
	//função para redirecionar
	public function Location($url)
	{
		echo "<script language=\"javascript\"> location.href='$url';</script>";
	}
	
	/**
	 * box para mostrar erros
	 */
	private function mostraErro($erro=""){
		$html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
					<html xmlns="http://www.w3.org/1999/xhtml">
					<head>				
						<style type="text/css">
							* {font: 11px Verdana, Arial, Helvetica, sans-serif;color: #222;}
							.erro {background: none repeat scroll 0 0 #F8F8D8;border: 1px solid #666666;margin: 10px auto 0;padding: 15px;text-align: center;width: 413px;}								  
							.voltar {text-decoration:underline;color:#000;}
						</style>
						<title>Erro</title>
					</head>
					<body>
						<div class="erro">
							'.$erro.'
							<br />
							<a href="'.$_SERVER['HTTP_REFERER'].'" class="voltar">Voltar</a>
						</div>
					</body>
				</html>';
		
		// Alterado por Thiago dia 22/02/2019 para trazer o erro em forma de texto:
		echo $erro;
		
		exit();
	}
    
	/**
	 * Método para retornar um erro e voltar à página anterior
	 *
	 * @param String $msg -> Mensagem de erro
	 */
	public function retornaErro($msg=''){
		echo '<script type="text/javascript">
					alert("'.$msg.'");
					history.back();
				</script>';		
	}
	
    public function moeda($valor)
    {
        if($valor == ""){ $valor = 0; }
		$valor = str_replace(",", "", $valor);
        $valor = number_format($valor, 2, ',', '.');
        return $valor;
    }
    
	public function moeda0($valor)
    {
        if($valor == ""){ $valor = 0; }
		$valor = str_replace(",", "", $valor);
        $valor = number_format($valor, 0, ',', '.');
        return $valor;
    }
	
	public function moeda4($valor)
    {
        $valor = str_replace(",", "", $valor);
        $valor = number_format($valor, 4, ',', '.');
        return $valor;
    }
	
	public function moeda5($valor)
    {
        $valor = str_replace(",", "", $valor);
        $valor = number_format($valor, 5, ',', '.');
        return $valor;
    }
	
    /**
     * Função para montar array e enviar para o protheus.
     * No protheus, usar a função U_ArrayPHP() 
     **/
     
     public function ArrayProtheus($array){
        $string = "";
        $i = 0;
        $j = 0;
        if(count($array) > 0){
            foreach($array as $a){
                foreach($a as $key => $value){
                    if(is_numeric($key)){
                        $string .= $value;   
                        if(count($a) - 1 > $j){
                            $string .= "|";    
                        }    
                    }
                    $j++;
                }   
                if(count($array) - 1 > $i){
                    $string .= ";;";    
                } 
                $i++;
                $j = 0;
            }   
            return $string;    
        } else {
            return "ERRO";
        }
	 }
	 
	 public function formataCPFouCNPJ($cpf)
	 {
		 if ($cpf) {
			 $cpf = trim($cpf);
			 $cpf = str_replace(".", "", $cpf);
			 $cpf = str_replace("-", "", $cpf);
			 $cpf = str_replace("/", "", $cpf);
	 
			 if (strlen($cpf) == 11) {
				 return substr($cpf, 0, 3) . "." . substr($cpf, 3, 3) . "." . substr($cpf, 6, 3) . "-" . substr($cpf, 9, 2);
			 } else {
				 return substr($cpf, 0, 2) . "." . substr($cpf, 2, 3) . "." . substr($cpf, 5, 3) . "/" . substr($cpf, 8, 4) . "-" . substr($cpf, 12, 2);
			 }
		 } else {
			 return '';
		 }
	 }
	 
	 public function validaCPFouCNPJ($cpf)
	 {
		 if ($cpf) {
			 $cpf = trim($cpf);
			 $cpf = str_replace(".", "", $cpf);
			 $cpf = str_replace("-", "", $cpf);
			 $cpf = str_replace("/", "", $cpf);
	 
			 //se é CPF
			 if (strlen($cpf) == 11) {
				 //primeiro dígito
				 $mult = 10;
				 $soma = 0;
	 
				 for ($i = 0; $i < 9; $i++) {
					 $var = intval(substr($cpf, $i, 1));
					 $soma += $var * $mult;
					 $mult--;
				 }
	 
				 $primeiro_digito = $soma % 11;
	 
				 if ($primeiro_digito < 2) {
					 $primeiro_digito = 0;
				 } else {
					 $primeiro_digito = 11 - $primeiro_digito;
				 }
	 
				 //segundo dígito
				 $mult = 11;
				 $soma = 0;
	 
				 for ($i = 0; $i < 10; $i++) {
					 $var = intval(substr($cpf, $i, 1));
					 $soma += $var * $mult;
					 $mult--;
				 }
	 
				 $segundo_digito = $soma % 11;
	 
				 if ($segundo_digito < 2) {
					 $segundo_digito = 0;
				 } else {
					 $segundo_digito = 11 - $segundo_digito;
				 }
	 
				 $digitos = $primeiro_digito . $segundo_digito;
	 
				 return $digitos == substr($cpf, 9, 2);
			 } else {
				 //primeiro dígito
				 $mult = 5;
				 $soma = 0;
	 
				 for ($i = 0; $i < 12; $i++) {
					 $var = intval(substr($cpf, $i, 1));
					 $soma += $var * $mult;
					 $mult--;
	 
					 if($mult < 2) $mult = 9;
				 }
	 
				 $primeiro_digito = $soma % 11;
	 
				 if ($primeiro_digito < 2) {
					 $primeiro_digito = 0;
				 } else {
					 $primeiro_digito = 11 - $primeiro_digito;
				 }
	 
				   //segundo dígito
				   $mult = 6;
				   $soma = 0;
	   
				   for ($i = 0; $i < 13; $i++) {
					   $var = intval(substr($cpf, $i, 1));
					   $soma += $var * $mult;
					   $mult--;
	 
					   if($mult < 2) $mult = 9;
				   }
	   
				   $segundo_digito = $soma % 11;
	   
				   if ($segundo_digito < 2) {
					   $segundo_digito = 0;
				   } else {
					   $segundo_digito = 11 - $segundo_digito;
				   }
	   
				   $digitos = $primeiro_digito . $segundo_digito;
	   
				   return $digitos == substr($cpf, 12, 2);
			 }
		 } else {
			 return false;
		 }
	 }
}
?>