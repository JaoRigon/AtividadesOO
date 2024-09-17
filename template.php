<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador De Tarefas</title>
    <link rel="stylesheet" href="pico-main/css/pico.amber.css">
</head>
    <body>
        <h1>Gerenciador de Tarefas</h1>

        <?php include('formulario.php'); ?>

        <?php if ($exibir_tabela) : ?>
            <?php include('tabela.php'); ?>
        <?php endif; ?>
    </body>
</html>
