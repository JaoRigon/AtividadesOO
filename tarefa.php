<?php

include "config.php";
include "banco.php";
include "auxiliares.php";
include "classes/Tarefa.php";
include "classes/Anexo.php";
include "classes/RepositorioTarefas.php";

$repositorio_tarefas = new RepositorioTarefas($conexao);

$tarefa = $repositorio_tarefas->buscar($_GET['id']);

$tem_erros = false;
$erros_validacao = array();

if (tem_post()) {
    $tarefa_id = $_POST['tarefa_id'];

    if (! array_key_exists('anexo', $_FILES)) {
        $tem_erros = true;
        $erros_validacao['anexo'] = 'Você deve selecionar um arquivo para anexar';
    } else {
        $dados_anexo = $_FILES['anexo'];
        if (tratar_anexo($dados_anexo)) {
            $anexo = new Anexo();
            $anexo->setTarefaId($tarefa_id);
            $anexo->setNome($dados_anexo['name']);
            $anexo->setArquivo($dados_anexo['name']);
        } else {
            $tem_erros = true;
            $erros_validacao['anexo'] = 'Envie apenas anexos nos formatos zip ou pdf';
        }
    }

    if (! $tem_erros) {
        $repositorio_tarefas->salvar_anexo($anexo);
    }
}

include "template_tarefa.php";