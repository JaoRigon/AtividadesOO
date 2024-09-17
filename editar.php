<?php

session_start();

require "config.php";
require "banco.php";
require "auxiliares.php";
require "classes/Tarefa.php";
require "classes/anexo.php";
require "classes/repositorioTarefas.php";

$repositorio_tarefas = new RepositorioTarefas($conexao);

$tarefa = $repositorio_tarefas->buscar($_GET['id']);

if ($tarefa === null) {
    die('Tarefa não encontrada.'); // Exibe uma mensagem ou redireciona
}

// Continua a lógica se a tarefa foi encontrada


$exibir_tabela = false;
$tem_erros = false;
$erros_validacao = array();

if (tem_post()) {

    if (isset($_POST['nome']) && strlen($_POST['nome']) > 0) 
        {
      $tarefa->setNome($_POST['nome']);
    } else {
        $tem_erros = true;
        $erros_validacao['nome'] = 'O nome da tarefa é obrigatório!!';
    }

    if (isset($_POST['descricao'])) {
        $tarefa->setDescricao($_POST['descricao']);
    } else {
        $tarefa->setDescricao('');
    }

    if (isset($_POST['prazo']) && strlen($_POST['prazo']) > 0) {
        if (validar_data($_POST['prazo'])) {
        $tarefa-> setPrazo (converte_data_br_para_objetos($_POST['prazo']));
        } else {
            $tem_erros = true;
            $erros_validacao['prazo'] = 'O prazo não é uma data válida!';
        }
    } else {
        $tarefa->setPrazo(null);
    }

    $tarefa->setPrioridade ($_POST['prioridade']);

    if (isset($_POST['concluida'])) {
        $tarefa->setConcluida(true);
    } else {
        $tarefa->setConcluida(false);
    }

    if (! $tem_erros) {
        $repositorio_tarefas->atualizar($tarefa);

        if (isset($_POST['lembrete']) && $_POST['lembrete'] == '1') {
            enviar_email($tarefa);
        }

        header('Location: tarefas.php');
        die();
    }
}

include "template.php";
