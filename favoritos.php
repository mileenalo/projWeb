<?php

include('db.php');
//include('functions.php');
//include('header.php');

$db = new Database();

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Verificação de ações requisitadas via AJAX:
* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (isset($_GET["a"])) {

    function remove_acento($string)
    {
        $caracteres_sem_acento = array(
            'Š' => 'S', 'š' => 's', 'Ð' => 'Dj', 'Â' => 'Z', 'Â' => 'z', 'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A',
            'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I',
            'Ï' => 'I', 'Ñ' => 'N', 'Å' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U',
            'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a',
            'å' => 'a', 'æ' => 'a', 'ç' => 'c', 'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i',
            'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'Å' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o', 'ù' => 'u',
            'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ý' => 'y', 'ý' => 'y', 'þ' => 'b', 'ÿ' => 'y', 'ƒ' => 'f',
            'Ä' => 'a', 'î' => 'i', 'â' => 'a', 'È' => 's', 'È' => 't', 'Ä' => 'A', 'Î' => 'I', 'Â' => 'A', 'È' => 'S', 'È' => 'T',
        );
        $nova_string = strtr($string, $caracteres_sem_acento);
        return ($nova_string);
    }

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
    * Buscar conteúdo na div conteudo:
    * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    if ($_GET["a"] == "lista_docs") {

        $pesquisa = $_POST['pesq'];
        $categ = $_POST['categ'];
        $nivel = $_POST['nivel'];

        $where = "";
        $whereCat = "";
        $whereNiv = "";

        if ($pesquisa != "") {
            $where .= " WHERE doc_desc LIKE '%{$pesquisa}%'";
        }

        if ($categ != "") {
            $whereCat .= " AND  cat_id = {$categ}";
        }

        if ($nivel != "") {
            $whereNiv .= " AND niv_id = {$nivel}";
        }

        $usuario = $_COOKIE["idUsuario"];

        $res = $db->select("SELECT * FROM tb_documentos
                            INNER JOIN tb_categorias ON doc_categoria = cat_id {$whereCat}
                            INNER JOIN tb_niveis ON doc_nivel = niv_id {$whereNiv}
                            INNER JOIN tb_favoritos ON doc_id = fav_documento AND fav_usu = {$usuario}
                                {$where} ORDER BY doc_datacad");
        
        if (count($res) > 0) {
            echo '<table class="table align-items-center mb-0">';
            echo '<div class="album py-5 bg-body-tertiary">';
            echo    '<div class="container">';
            echo        '<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">';
            foreach($res as $r){
                if($r["fav_id"] == null){
                    $style = '';
                    $val = $r["doc_id"];
                    $type = 2;
                }else{
                    $style = 'style="background:#DE5B98; color:#ffffff; "';
                    $val = $r["fav_id"];
                    $type = 1;
                    
                }

                echo            '<div class="col">';
                echo                '<div class="card shadow-sm">';
                echo                    '<svg class="bd-placeholder-img card-img-top" width="100%" xmlns="./assets/img/down-arrow-dark.svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false"><img src="./assets/img/unnamed.png" style="opacity: 0.5;" class="navbar-brand-img h-50" alt="main_logo"></svg>';
                echo                    '<div class="card-body">';
                echo                        '<p class="card-text"><b>'.$r["doc_desc"].'</b></p>';
                echo                        '<div class="d-flex justify-content-between align-items-center">';
                echo                            '<div class="btn-group">';
                echo                                '<button type="button" class="btn btn-sm btn-outline-secondary" onclick="viewDoc(\''.$r["doc_url"].'\');">View</button>';
                echo                                '<button type="button" '.$style.' class="btn btn-sm btn-outline-secondary" onclick="favDoc(\''.$val.'\', \''.$type.'\');">Favoritos</button>';
                echo                            '</div>';
                echo                            '<small class="text-body-secondary" style="padding-left: 15px;">Última Alteração: '.substr($r["doc_datacad"], 6, 2).'/'.substr($r["doc_datacad"], 4, 2).'/'.substr($r["doc_datacad"], 0, 4).'</small>';
                echo                        '</div>';
                echo                    '</div>';
                echo                '</div>';
                echo            '</div>';
            }
            echo        '</div>';
            echo    '</div>';
            echo '</div>';
            echo '</table>';
        } else{
            echo '<div class="alert alert-warning" role="alert" style="margin-left: 15px;margin-right:25px;">';
            echo 'Nenhum registro localizado!';
            echo '</div>';
        }
    }

    die();
}

// Includes para o script:
include('header.php');
include('aside.php');

?>
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3" style="background:#DA522B;">
                            <h6 class="text-white text-capitalize ps-3">Favoritos</h6>
                        </div>
                    </div>
                    
                    <div class="card-body px-0 pb-2">
                        <div class="form-group row" style="padding-left:15px;">
                            <div class="col-6">
                                <label>Pesquisar</label>
                                <div class="input-group input-group-outline">
                                    <input type="text" class="form-control" onkeyup="lista_itens()" id="input_pesquisa" placeholder="Pesquisar">
                                </div>
                            </div>
                            <div class="col-2">
                                <label>Categorias</label>
                                <div class="input-group input-group-outline">
                                    <select id="fil_categ" class="form-control form-control-lg" style="width:100%" name="fil_categ" type="text">
								        <option value="" selected></option>
								        <?php
                                            $desc = $db->select("SELECT cat_id, cat_desc FROM tb_categorias ORDER BY cat_desc");
                                            foreach($desc as $s){
                                                echo  '<option value="'.$s["cat_id"].'">'.$s["cat_desc"].'</option>';
                                            }
								        ?>
							        </select>
                                </div>
                            </div>
                            <div class="col-2">
                                <label>Nível</label>
                                <div class="input-group input-group-outline">
                                    <select id="fil_nivel" class="form-control form-control-lg" style="width:100%" name="fil_nivel" type="text">
								        <option value="" selected></option>
								        <?php
                                            $desc = $db->select("SELECT niv_id, niv_desc FROM tb_niveis ORDER BY niv_desc");
                                            foreach($desc as $s){
                                                echo  '<option value="'.$s["niv_id"].'">'.$s["niv_desc"].'</option>';
                                            }
								        ?>
							        </select>
                                </div>
                            </div>
                            <div class="col-2">
                                <label> </label>
                                <div class="input-group" style="padding-top: 8px;">
                                    <button type="button" onclick="lista_itens();" class="btn bg-gradient-primary" style="height: 39px; background:#DA522B;"><i class="mdi mdi-library-plus" style="margin-right: 5px"></i>Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive p-0" id="div_conteudo"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </main>

<script type="text/javascript">
   
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
     * Listar itens:
     * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    var ajax_div = $.ajax(null);
    const lista_itens = () => {
        if (ajax_div) {
            ajax_div.abort();
        }
        ajax_div = $.ajax({
            cache: false,
            async: true,
            url: '?a=lista_docs',
            type: 'post',
            data: {
                pesq: $('#input_pesquisa').val(), 
                categ: $("#fil_categ").val(),
                nivel: $("#fil_nivel").val(),
            },
            beforeSend: function() {
                $('#div_conteudo').html('<div class="spinner-grow m-3 text-primary" role="status"><span class="visually-hidden">Aguarde...</span></div>');
            },
            success: function retorno_ajax(retorno) {
                $('#div_conteudo').html(retorno);
            }
        });
    }
    
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
     * Favoritar itens:
     * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    var ajax_div = $.ajax(null);
    const favDoc = (doc, type) => {
        if (ajax_div) {
            ajax_div.abort();
        }
        ajax_div = $.ajax({
            cache: false,
            async: true,
            url: 'home.php?a=fav_docs',
            type: 'post',
            data: {
                doc: doc,
                type: type,
            },
            success: function retorno_ajax(retorno) {
                lista_itens();
            }
        });
    }

    // Evento inicial:
    $(document).ready(function() {
        lista_itens();
    });

</script>

<?php
    include("bottom.php");
?>   
