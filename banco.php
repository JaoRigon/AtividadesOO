<?php

require_once "config.php";
$conexao = mysqli_connect(BD_SERVIDOR, BD_USUARIO, BD_SENHA, BD_BANCO);

if (mysqli_connect_errno()) {
    echo "Problemas para conectar no banco. Verifique os dados!";
    die();
}

function buscar_tarefas($conexao){
    $sqlBusca = 'SELECT * FROM tarefas';
    $resultado = mysqli_query($conexao, $sqlBusca);

    $tarefas = array();

    while ($tarefa = mysqli_fetch_assoc($resultado)) {
        $tarefas[] = $tarefa;
    }

    return $tarefas;
}

function buscar_tarefa($conexao, $id){
    $sqlBusca = 'SELECT * FROM tarefas WHERE id = ' . $id;
    $resultado = mysqli_query($conexao, $sqlBusca);
    return mysqli_fetch_assoc($resultado);
}

function gravar_tarefa($conexao, $tarefa){
    $sqlGravar = "
        INSERT INTO tarefas
        (nome, descricao, prioridade, prazo, concluida)
        VALUES
        (?, ?, ?, ?, ?)
    ";
    $stmt = mysqli_prepare($conexao, $sqlGravar);
    mysqli_stmt_bind_param($stmt, 'ssisi', $tarefa['nome'], $tarefa['descricao'], $tarefa['prioridade'], $tarefa['prazo'], $tarefa['concluida']);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_errno($stmt)) {
        die('Erro na inserção: ' . mysqli_stmt_error($stmt));
    }
}

function buscar_anexos($conexao, $tarefa_id)
{
    $sqlBusca = "SELECT * FROM anexos WHERE tarefas_id = {$tarefa_id}";
    $resultado = mysqli_query($conexao, $sqlBusca);

    $anexos = array();

    while ($anexo = mysqli_fetch_assoc($resultado)){
        $anexos[] = $anexo;
    }
    return $anexos;
}

function editar_tarefa($conexao, $tarefa){
    $sqlEditar = "
        UPDATE tarefas SET
            nome        = '{$tarefa['nome']}',
            descricao   = '{$tarefa['descricao']}',
            prioridade  =  {$tarefa['prioridade']},
            prazo       = '{$tarefa['prazo']}',
            concluida   =  {$tarefa['concluida']}
        WHERE id = {$tarefa['id']}
    ";

    mysqli_query($conexao, $sqlEditar);
}

function remover_tarefa($conexao, $id){
    $sqlRemover = "DELETE FROM tarefas WHERE id = {$id}";

    mysqli_query($conexao, $sqlRemover);
}


/*
C = reate -> gravar
R = ead -> buscar
U = PDATE -> editar
D = ELETE -> remover
*/