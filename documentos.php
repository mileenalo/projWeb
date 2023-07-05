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
        $where = "";

        if ($pesquisa != "") {
            $where .= " AND doc_categoria LIKE '%{$pesquisa}%' OR doc_nivel LIKE '%{$pesquisa}%' OR doc_desc LIKE '%{$pesquisa}%'";
        }

        $usuario = $_COOKIE["idUsuario"];
        
        $res = $db->select("SELECT * FROM tb_documentos
                            INNER JOIN tb_categorias ON doc_categoria = cat_id
                            INNER JOIN tb_niveis ON doc_nivel = niv_id
                                WHERE doc_usucad = {$usuario}
                                {$where} ORDER BY doc_datacad");

        if (count($res) > 0) {
            echo '<table class="table align-items-center mb-0">';
            echo '  <thead>';
            echo '      <tr>';
            echo '          <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-left">Documento</th>';
            echo '          <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 text-left">Data do Cadastro</th>';
            echo '          <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 text-left">Categoria</th>';
            echo '          <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 text-left">Nível</th>';
            echo '          <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 text-left">Abrir</th>';
            echo '          <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 text-left">Editar</th>';
            echo '          <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 text-left">Deletar</th>';
            echo '      </tr>';
            echo '  </thead>';
            echo '  <tbody>';
            foreach($res as $r){
                echo '<tr>';
                echo '  <td class="align-middle text-left">';
                echo '      <span class="text-secondary text-xs font-weight-bold" style="padding-left:15px;">'.$r["doc_desc"].'</span>';
                echo '  </td>';
                echo '  <td class="align-middle text-left">';
                echo '    <span class="text-secondary text-xs font-weight-bold">'.substr($r["doc_datacad"], 6, 2).'-'.substr($r["doc_datacad"], 4, 2).'-'.substr($r["doc_datacad"], 0, 4).'</span>';
                echo '  </td>';
                echo '  <td class="align-middle text-left">';
                echo '    <span class="text-secondary text-xs font-weight-bold">'.$r["cat_desc"].'</span>';
                echo '  </td>';
                echo '  <td class="align-middle text-left">';
                echo '    <span class="text-secondary text-xs font-weight-bold">'.$r["niv_desc"].'</span>';
                echo '  </td>';
                echo '  <td class="align-middle text-left">';
                echo '      <i title="Visuzalizar" onclick="viewDoc(\''.$r["doc_url"].'\');" class="fa fa-eye" style="cursor: pointer"></i>';
                echo '  </td>';
                echo '  <td class="align-middle">';
                echo '      <i title="Editar" onclick="get_item(\'' . $r["doc_id"] . '\')" class="fa fa-edit" style="cursor: pointer"></i>';
                echo '  </td>';
                echo '  <td class="align-middle">';
                echo '      <i title="Deletar" onclick="del_item(\'' . $r["doc_id"] . '\')" class="fa fa-trash" style="cursor: pointer"></i>';
                echo '  </td>';
                echo '</tr>';
            }
            echo '  </tbody>';
            echo '</table>';
        } else{
            echo '<div class="alert alert-warning" role="alert" style="margin-left: 15px;margin-right:25px;">';
            echo 'Nenhum registro localizado!';
            echo '</div>';
        }
    }

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
    * Inserir documento na tabela e no diretorio do projeto:
    * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    if ($_GET["a"] == "inclui_doc") {
       
        if ($_FILES['doc']['error'] === UPLOAD_ERR_OK) {
            $destination_path = getcwd().DIRECTORY_SEPARATOR;
            $nomeArquivo = $_FILES['doc']['name'];
            $caminhoTemporario = $_FILES['doc']['tmp_name'];

            $caminhoDestino = "./arquivos/". $nomeArquivo;

            if(@move_uploaded_file($caminhoTemporario, $caminhoDestino)) {
                echo 'Arquivo enviado e salvo com sucesso.';
            }else{
                echo 'Erro ao salvar o arquivo.';
            }
        }else{
            echo 'Ocorreu um erro durante o envio do arquivo.';
        }
        
        $usuario = $_COOKIE["idUsuario"];
        $desc = $_POST["name"];
        $categ = $_POST["categ"];
        $nivel = $_POST["nivel"];
        $datacad = date("Ymd");

        $res = $db->_exec("INSERT INTO tb_documentos (doc_url, doc_categoria, doc_nivel, doc_desc, doc_usucad, doc_datacad) 
                            VALUES ('{$caminhoDestino}', {$categ}, {$nivel}, '{$desc}', {$usuario}, '{$datacad}')");

        echo $res;
    }


    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
    * Deleta o documento:
    * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    if ($_GET["a"] == "del_doc") {

        $id = $_POST["id"];

        $del = $db->_exec("DELETE FROM tb_documentos WHERE doc_id = {$id}");

        echo $del;
    }

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
    * Busca conteúdo para exibir na div de edição do documento:
    * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    if ($_GET["a"] == "get_doc") {


        $id = $_POST["id"];

        $res = $db->select("SELECT doc_id, doc_categoria, doc_nivel, doc_desc, doc_usu, doc_datacad FROM tb_documentos 
                            WHERE doc_id = {$id}");

        echo json_encode($res);
    }

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
    * Edita informações do documento:
    * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    if($_GET["a"] == "edita_doc"){

        $id = $_POST["id"];
        $desc = $_POST["name"];
        $categ = $_POST["categ"];
        $nivel = $_POST["nivel"];
        $datacad = date("Ymd");

        $res = $db->_exec("UPDATE tb_documentos SET doc_categoria = {$categ}, doc_nivel = {$nivel}, 
                                doc_desc = '{$desc}', doc_datacad = '{$datacad}'
                            WHERE doc_id = {$id}");

        echo $res;

    }

    if($_GET["a"] == "download"){
        $arquivoPDF = 'caminho_do_arquivo.pdf'; // Caminho completo para o arquivo PDF

        // Define o tipo de conteúdo como PDF
        header('Content-Type: application/pdf');

        // Força o download do arquivo com um nome específico
        header('Content-Disposition: attachment; filename="nome_arquivo.pdf"');

        // Envia o arquivo PDF para o navegador
        readfile($arquivoPDF);
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
                            <h6 class="text-white text-capitalize ps-3">Cadastro de Documentos</h6>
                        </div>
                    </div>
                    
                    <div class="card-body px-0 pb-2">
                        <div class="form-group row" style="padding-left:15px;">
                            <div class="col-10">
                                <div class="input-group input-group-outline">
                                    <input type="text" class="form-control" onkeyup="lista_itens()" id="input_pesquisa" placeholder="Pesquisar">
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="input-group">
                                    <button type="button" onclick="$('#mod_formul').modal('show');" class="btn bg-gradient-primary" style="height: 38px; background:#DA522B;"><i class="mdi mdi-library-plus" style="margin-right: 5px"></i>Incluir</button>
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

    <!-- Modal formulário Inclusao -->
    <div class="modal" id="mod_formul">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-sm" style="max-width: 50%;">
            <div class="modal-content">
                <div class="modal-header" style="align-items: center">
                    <div style="display: flex; align-items: center">
                        <div style="margin-right: 5px">
                            <h2 style="margin: 0"><span class="badge bg-info text-white" style="padding: 8px" id="span_endereco_nome"></span></h2>
                        </div>
                        <div>
                            <h5 id="tit_frm_formul" class="modal-title">Incluir Documento</h5>
                        </div>
                    </div>
                    <button type="button" style="cursor: pointer; border: 1px solid #ccc; border-radius: 10px" aria-label="Fechar" onclick="$('#mod_formul').modal('hide');">X</button>
                </div>
                <div class="modal-body modal-dialog-scrollable">
                    <form id="frm_general" name="frm_general" class="col" enctype="multipart/form-data">
                        <div class="row mb-3">
                            <div class="col-6">
                                <label for="frm_val1_insert" class="form-label">Título:</label>
                                <div class="input-group input-group-outline">
                                    <input type="text" class="form-control" id="frm_nome" placeholder="Ex: Maria Luiza">
                                </div>
                            </div>
                            <div class="col-6">
                                <label for="frm_categ" class="form-label">Categoria:</label>
                                <div class="input-group input-group-outline">
                                    <select id="frm_categ" class="form-control form-control-lg" style="width:100%" name="frm_categ" type="text">
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
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <label for="frm_nivel" class="form-label">Nível:</label>
                                <div class="input-group input-group-outline">
                                    <select id="frm_nivel" class="form-control form-control-lg" style="width:100%" name="frm_nivel" type="text">
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
                            <div class="col-6">
                                <label for="frm_val2_insert" class="form-label">Upload:</label>
                                <div class="input-group input-group-outline">
                                    <input type="file" accept=".pdf" class="form-control" id="frm_doc"  placeholder="Ex: material.pdf">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="$('#mod_formul').modal('hide');">Cancelar</button>
                    <button type="button" class="btn btn-primary" style="background:#DA522B;" id="OK" onclick="incluiDoc();"><img id="img_btn_ok" style="width: 15px; display: none; margin-right: 10px">OK</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal formulário Edição-->

    <div class="modal" id="mod_formul_edit">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" style="max-width: 50%;">
            <div class="modal-content">
                <div class="modal-header" style="align-items: center">
                    <div style="display: flex; align-items: center">
                        <div style="margin-right: 5px">
                            <h2 style="margin: 0"><span class="badge bg-info text-white" style="padding: 8px" id="span_endereco_nome"></span></h2>
                        </div>
                        <div>
                            <h5 id="div_edit_title">Editar Documento</h5>
                        </div>
                    </div>
                    <button type="button" style="cursor: pointer; border: 1px solid #ccc; border-radius: 10px" aria-label="Fechar" onclick="location.reload();">X</button>
                </div>
                <div class="modal-body modal-dialog-scrollable">
                    <form id="frm_general_edit" name="frm_general"  class="col">
                        <div class="row mb-3">
                            <div class="col-6">
                                <label for="frm_val1_insert" class="form-label">Título:</label>
                                <div class="input-group input-group-outline">
                                    <input id="frm_id_edit" hidden>
                                    <input type="text" class="form-control" id="frm_nome_edit" placeholder="Ex: Maria Luiza">
                                </div>
                            </div>
                            <div class="col-6">
                                <label for="frm_categ" class="form-label">Categoria:</label>
                                <div class="input-group input-group-outline">
                                    <select id="frm_categ_edit" class="form-control form-control-lg" style="width:100%" name="frm_categ_edit" type="text">
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
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <label for="frm_nivel_edit" class="form-label">Nível:</label>
                                <div class="input-group input-group-outline">
                                    <select id="frm_nivel_edit" class="form-control form-control-lg" style="width:100%" name="frm_nivel_edit" type="text">
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
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="location.reload();">Cancelar</button>
                    <button type="button" class="btn btn-primary" style="background:#DA522B;" id="frm_OK" onclick="editDoc();"><img id="img_btn_ok" style="width: 15px; display: none; margin-right: 10px">OK</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="mod_vis">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" style="max-width: 50%;">
            <div class="modal-content">
                <div class="modal-header" style="align-items: center">
                    <div style="display: flex; align-items: center">
                        <div style="margin-right: 5px">
                            <h2 style="margin: 0"><span class="badge bg-info text-white" style="padding: 8px"></span></h2>
                        </div>
                        <div>
                            <h5 id="tit_frm_formul_" class="modal-title">Visualizador</h5>
                        </div>
                    </div>
                    <button type="button" style="cursor: pointer; border: 1px solid #ccc; border-radius: 10px" aria-label="Fechar" onclick="$('#mod_vis').modal('hide');">X</button>
                </div>
                <div class="modal-body">
                    <div id="documentViewer" class="flowpaper_viewer"></div>
                    <input type="text" id="frm_path_doc" hidden>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secundary" id="download" onclick="baixarArquivoPDF();"><img id="img_btn_download" style="width: 15px; display: none; margin-right: 10px">Baixar</button>
                    <button type="button" class="btn btn-primary" style="background:#DA522B;" id="OK" onclick="$('#mod_vis').modal('hide');"><img id="img_btn_close" style="width: 15px; display: none; margin-right: 10px">Fechar</button>
                </div>
            </div>
        </div>
    </div>

<link rel="stylesheet" type="text/css" href="./assets/flowPaper/css/flowpaper.css" />
<script type="text/javascript" src="./assets/flowPaper/js/jquery.min.js"></script>
<script type="text/javascript" src="./assets/flowPaper/js/jquery.extensions.min.js"></script>
<!--[if gte IE 10 | !IE ]><!-->
<script type="text/javascript" src="./assets/flowPaper/js/three.min.js"></script>
<!--<![endif]-->
<script type="text/javascript" src="./assets/flowPaper/js/flowpaper.js"></script>
<script type="text/javascript" src="./assets/flowPaper/js/flowpaper_handlers.js"></script>
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
                pesq: $('#input_pesquisa').val()
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
     * Incluisão de novos documentos do usuário:
     * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    var ajax_div = $.ajax(null);
    const incluiDoc = () => {
        if (ajax_div) {
            ajax_div.abort();
        }
       
        var doc = $('#frm_doc')[0].files[0];
    
        var formData = new FormData();
        
        formData.append('doc', doc,);
        formData.append('name', $("#frm_nome").val());
        formData.append('categ', $("#frm_categ").val());
        formData.append('nivel', $("#frm_nivel").val());

        ajax_div = $.ajax({
            cache: false,
            async: true,
            url: '?a=inclui_doc',
            type: 'post',
            data: formData,
            contentType: false, 
            processData: false,
            success: function retorno_ajax(retorno) {
                console.log(retorno)
                if (!retorno) {
                    alert("ERRO AO INCLUIR DOCUMENTO!");
                }else{
                    $("#mod_formul").modal('hide');
                    lista_itens();
                }
            }
        });
    }

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
     * Edição de descrições do documento cadastrado:
     * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    var ajax_div = $.ajax(null);
    const editDoc = () => {

        if (confirm("Confirma a edição dos dados do documento?")) {
            if (ajax_div) {
                ajax_div.abort();
            }

            ajax_div = $.ajax({
                cache: false,
                async: true,
                url: '?uid=<?php echo $_COOKIE['idUsuario']; ?>&a=edita_doc',
                type: 'post',
                data: {
                    name: $("#frm_nome_edit").val(),
                    categ: $("#frm_categ_edit").val(),
                    nivel: $("#frm_nivel_edit").val(),
                    id: $('#frm_id_edit').val(),
                },
                beforeSend: function() {
                    $('#mod_formul_edit').modal("show");
                },
                success: function retorno_ajax(retorno) {
                    if (!retorno) {
                        alert("ERRO AO EDITAR O DOCUMENTO!");
                    }else{
                        $('#mod_formul_edit').modal("hide");
                        lista_itens();
                    }
                }
            });
        }
    }

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
     * Busca itens para os campo de edição:
     * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    var ajax_div = $.ajax(null);
    const get_item = (id) => {
        if (ajax_div) {
            ajax_div.abort();
        }
        ajax_div = $.ajax({
            cache: false,
            async: true,
            url: '?uid=<?php echo $_COOKIE['idUsuario']; ?>&a=get_doc',
            type: 'post',
            data: {
                id: id,
            },
            beforeSend: function() {
                $('#mod_formul_edit').modal("show");
            },
            success: function retorno_ajax(retorno) {
                console.log(retorno);
                var obj = JSON.parse(retorno);
                
                console.log(id);

                $("#frm_id_edit").val(id);

                $("#frm_nome_edit").val(obj[0].doc_desc);
                $("#frm_categ_edit").val(obj[0].doc_categoria);
                $("#frm_nivel_edit").val(obj[0].doc_nivel);
                $("#frm_doc_edit").val("");

            }
        });
    }

     /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
     * Excluir pedido:
     * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    var ajax_div = $.ajax(null);
    function del_item(id) {
        if (confirm("Deseja excluir o documento?")) {
            if (ajax_div) {
                ajax_div.abort();
            }
            ajax_div = $.ajax({
                cache: false,
                async: true,
                url: '?uid=<?php echo $_COOKIE['idUsuario']; ?>&a=del_doc',
                type: 'post',
                data: {
                    id: id,
                },
                success: function retorno_ajax(retorno) {

                    if (retorno == 1) {
                        location.reload();
                        lista_itens();
                    }else {
                        alert("ERRO AO DELETAR DOCUMENTO! " + retorno);
                    }
                }
            });
        } else {
            lista_itens();
        }
    }
    
    function baixarArquivoPDF() {
        var doc = $("#frm_path_doc").val();

        var urlArquivoPDF = doc;
        var nomeArquivo = "file_.pdf"; // O nome que deseja dar ao arquivo baixado

        var link = document.createElement("a");
        link.href = urlArquivoPDF;
        link.download = nomeArquivo;

        link.dispatchEvent(new MouseEvent("click"));
    }

    function viewDoc(doc){
        $("#mod_vis").modal("show");
        $("#frm_path_doc").val(doc);
        $('#documentViewer').FlowPaperViewer({
            config: {
                PDFFile: doc,
                Scale: 1.0, // Escala inicial do documento (opcional)
                ZoomTransition: 'easeOut', // Transição de zoom (opcional)
                UIConfig    : './assets/flowPaper/UI_Zine.xml',
                ViewModeToolsVisible: false // Exibir as ferramentas de visualização (opcional)
                // Outras opções de configuração podem ser adicionadas conforme necessário
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
