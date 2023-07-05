<?php

include('db.php');
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
    if ($_GET["a"] == "lista_niv") {

        $pesquisa = $_POST['pesq'];
        $where = "";

        if ($pesquisa != "") {
            $where .= "WHERE niv_desc LIKE '%{$pesquisa}%' ";
        }

        $res = $db->select("SELECT * FROM tb_niveis
                                {$where} ORDER BY niv_desc");

        if (count($res) > 0) {
            echo '<table class="table align-items-center mb-0">';
            echo '  <thead>';
            echo '      <tr>';
            echo '          <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-left">Nível</th>';
            echo '          <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 text-left">Editar</th>';
            echo '          <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 text-left">Deletar</th>';
            echo '      </tr>';
            echo '  </thead>';
            echo '  <tbody>';
            foreach($res as $r){
                echo '<tr>';
                echo '  <td class="align-middle text-left">';
                echo '      <span class="text-secondary text-xs font-weight-bold" style="padding-left:15px;">'.$r["niv_desc"].'</span>';
                echo '  </td>';
                echo '  <td class="align-middle">';
                echo '      <i title="Editar" onclick="get_item(\'' . $r["niv_id"] . '\')" class="fa fa-edit" style="cursor: pointer"></i>';
                echo '  </td>';
                echo '  <td class="align-middle">';
                echo '      <i title="Deletar" onclick="del_item(\'' . $r["niv_id"] . '\')" class="fa fa-trash" style="cursor: pointer"></i>';
                echo '  </td>';
                echo '</tr>';
            }
            echo '  </tbody>';
            echo '</table>';
        } else{
            echo '<div class="alert alert-warning" role="alert">';
            echo 'Nenhum registro localizado!';
            echo '</div>';
        }
    }

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
    * Inclusão de usuários:
    * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    if ($_GET["a"] == "inclui_niv") {

        $desc = $_POST["desc"];
        
        $res = $db->_exec("INSERT INTO tb_niveis (niv_desc) VALUES ('{$desc}')");

        echo $res;
    }

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
    * Edição de usuários:
    * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    if ($_GET["a"] == "edita_niv") {

        $id = $_POST["id"];
        $desc = $_POST["desc"];

        $res = $db->_exec("UPDATE tb_niveis SET niv_desc = '{$desc}' WHERE niv_id = $id");

        echo $res;
    }


    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
    * Deleta o usuário:
    * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    if ($_GET["a"] == "del_niv") {

        $id = $_POST["id"];

        $del = $db->_exec("DELETE FROM tb_niveis WHERE niv_id = {$id}");

        echo $del;
    }

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
    * Busca conteúdo para exibir na div de edição do usuário:
    * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    if ($_GET["a"] == "get_niv") {


        $id = $_POST["id"];

        $res = $db->select("SELECT * FROM tb_niveis 
                            WHERE niv_id = {$id}");

        echo json_encode($res);
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
                            <h6 class="text-white text-capitalize ps-3">Níveis</h6>
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
                            <h5 id="tit_frm_formul" class="modal-title">Incluir Nível</h5>
                        </div>
                    </div>
                    <button type="button" style="cursor: pointer; border: 1px solid #ccc; border-radius: 10px" aria-label="Fechar" onclick="$('#mod_formul').modal('hide');">X</button>
                </div>
                <div class="modal-body modal-dialog-scrollable">
                    <form id="frm_general" name="frm_general" class="col">
                        <div class="row mb-3">
                            <div class="col-4">
                                <label for="frm_nivel" class="form-label">Nível:</label>
                                <div class="input-group input-group-outline">
                                    <input type="text" class="form-control" id="frm_nivel" placeholder="Ex: Pós Graduação">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="$('#mod_formul').modal('hide');">Cancelar</button>
                    <button type="button" class="btn btn-primary" style="background:#DA522B;" id="OK" onclick="incluiUsu();"><img id="img_btn_ok" style="width: 15px; display: none; margin-right: 10px">OK</button>
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
                            <h5 id="div_edit_title">Editar Nível</h5>
                        </div>
                    </div>
                    <button type="button" style="cursor: pointer; border: 1px solid #ccc; border-radius: 10px" aria-label="Fechar" onclick="location.reload();">X</button>
                </div>
                <div class="modal-body modal-dialog-scrollable">
                    <form id="frm_general" name="frm_general" class="col">
                        <div class="row mb-3">
                            <div class="col-4">
                                <label for="frm_nivel_edit" class="form-label">Nível:</label>
                                <div class="input-group input-group-outline">
                                    <input id="frm_id_edit" hidden>
                                    <input type="text" class="form-control" id="frm_nivel_edit">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="location.reload();">Cancelar</button>
                    <button type="button" class="btn btn-primary" style="background:#DA522B;" id="frm_OK" onclick="editUsu();"><img id="img_btn_ok" style="width: 15px; display: none; margin-right: 10px">OK</button>
                </div>
            </div>
        </div>
    </div>

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
            url: '?a=lista_niv',
            type: 'post',
            data: {
                pesq: $('#input_pesquisa').val()
            },
            beforeSend: function() {
                $('#div_conteudo').html('<div class="spinner-grow m-3 text-primary" role="status"><span class="visually-hidden">Aguarde...</span></div>');
            },
            success: function retorno_ajax(retorno) {
                //console.log(retorno);
                $('#div_conteudo').html(retorno);
            }
        });
    }

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
     * Inclusão:
     * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    var ajax_div = $.ajax(null);
    const incluiUsu = () => {
        if (ajax_div) {
            ajax_div.abort();
        }
        ajax_div = $.ajax({
            cache: false,
            async: true,
            url: '?a=inclui_niv',
            type: 'post',
            data: {
                desc: $("#frm_nivel").val(),
            },
            success: function retorno_ajax(retorno) {
                //console.log(retorno)
                if (!retorno) {
                    alert("ERRO AO INCLUIR NÍVEL!");
                }else{
                    $("#mod_formul").modal('hide');
                    lista_itens();
                }
            }
        });
    }

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
     * Edição do usuário:
     * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    var ajax_div = $.ajax(null);
    const editUsu = (countarray, iditens, idPed) => {

        if (confirm("Confirma a edição do Nível?")) {
            if (ajax_div) {
                ajax_div.abort();
            }

            ajax_div = $.ajax({
                cache: false,
                async: true,
                url: '?uid=<?php echo $_COOKIE['idUsuario']; ?>&a=edita_niv',
                type: 'post',
                data: {
                    desc: $('#frm_nivel_edit').val(),
                    id: $('#frm_id_edit').val(),
                },
                beforeSend: function() {
                    $('#mod_formul_edit').modal("show");
                },
                success: function retorno_ajax(retorno) {
                    if (!retorno) {
                        alert("ERRO AO EDITAR O NÍVEL!");
                    }else{
                        $('#mod_formul_edit').modal("hide");
                        lista_itens();
                    }
                }
            });
        }
    }

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
     * Pesquisar itens do campo de edição:
     * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    var ajax_div = $.ajax(null);
    const get_item = (id) => {
        if (ajax_div) {
            ajax_div.abort();
        }
        ajax_div = $.ajax({
            cache: false,
            async: true,
            url: '?uid=<?php echo $_COOKIE['idUsuario']; ?>&a=get_niv',
            type: 'post',
            data: {
                id: id,
            },
            beforeSend: function() {
                $('#mod_formul_edit').modal("show");
            },
            success: function retorno_ajax(retorno) {
                var obj = JSON.parse(retorno);
                
                console.log(id);

                $("#frm_id_edit").val(id);

                $("#frm_nivel_edit").val(obj[0].niv_desc);
            }
        });
    }

     /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
     * Excluir usuário:
     * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    var ajax_div = $.ajax(null);
    function del_item(id) {
        if (confirm("Deseja excluir o nível?")) {
            if (ajax_div) {
                ajax_div.abort();
            }
            ajax_div = $.ajax({
                cache: false,
                async: true,
                url: '?uid=<?php echo $_COOKIE['idUsuario']; ?>&a=del_niv',
                type: 'post',
                data: {
                    id: id,
                },
                success: function retorno_ajax(retorno) {

                    if (retorno == 1) {
                        location.reload();
                        lista_itens();
                    }else {
                        alert("ERRO AO DELETAR ITENS! " + retorno);
                    }
                }
            });
        } else {
            lista_itens();
        }
    }
    
    // Evento inicial:
    $(document).ready(function() {
        lista_itens();
    });

</script>

<?php
    include("bottom.php");
?>   
