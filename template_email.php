<h1> Tarefa: <?php echo $tarefa['nome'];?></h1>

<p><strong>Concluida: </strong><?php echo converte_concluida($tarefa['concluida']);?> </p>

<p><strong>Descrição: </strong><?php echo nl2br($tarefa['descricao']); ?></p>

<p>><strong>Prazo: </strong><?php echo converte_prioridade($tarefa)['prioridade']; ?></p>

<p><strong>Prioridade: </strong> <?php echo converte_prioridade($tarefa['prioridade']); ?></p>

<?php if (count($anexos) > 0) : ?>
    <p><strong>Atenção!</strong>Esta terefa contém anexos!</p>
<?php endif; ?> 