<?php

//inico função email
use PHPMailer\PHPMailer\PHPMailer;

// primeira funçao
function tem_post()
{
    if (count($_POST) > 0) {
        return true;
    }

    return false;
}
    
//segunda  função
function enviar_email($tarefa)
{   
    include "bibliotecas/PHPMailer-master/src/PHPMailer.php";

    $corpo = preparar_corpo_email($tarefa, $anexos);

    $email = new PHPMailer();

    $email->isSMTP();
    $email->Host = "smtp.gmail.com";
    $email->Port = 587;
    $email->SMTPSecure = 'tls';
    $email->SMTPAuth = true;
    $email->Username = "joao_rigon@estudante.sesisenai.org.br";
    $email->Password = "Joao#pereira1986";
    $email->setFrom("joao_rigon@estudante.sesisenai.org.br", "Avisador de Tarefas");
    $email->addAddress(EMAIL_NOTIFICACAO);
    $email->Subject = "Aviso de Tarefa: {$tarefa->getNome()}";
    $email->msgHTML($corpo);

    foreach ($tarefa->getAnexos() as $anexo) {
        $email->addAttachment("anexos/{$anexo->getAnexo}");
    }

    if (!$email->send()) {
        error_log('Mailer Error: ' . $email->ErrorInfo);
        return false;
    }

    return true;
}

// terceira função
function preparar_corpo_email($tarefa, $anexos)
{
    ob_start();
    include "template_email.php";

    $corpo = ob_get_contents();

    ob_end_clean();

    return $corpo; 
}

// quarta função
function montar_email($tarefa, $anexos) {
    $tem_anexos = '';

    if (count($anexos) > 0) {
        $tem_anexos = "<p><strong>Atenção!</strong> Esta tarefa contém anexos!</p>";
    }

    $corpo = "
        <html>
            <head>
                <meta charset=\"utf-8\" />
                <title>Gerenciador de Tarefas</title>
                <link rel=\"stylesheet\" href=\"tarefas.css\" type=\"text/css\" />
            </head>
            <body>
                <h1>Tarefa: {$tarefa['nome']}</h1>
                <p><strong>Concluída:</strong> " . converte_concluida($tarefa['concluida']) . "</p>
                <p><strong>Descrição:</strong> " . nl2br($tarefa['descricao']) . "</p>
                <p><strong>Prazo:</strong> " . converte_data_para_tela($tarefa['prazo']) . "</p>
                <p><strong>Prioridade:</strong> " . converte_prioridade($tarefa['prioridade']) . "</p>
                {$tem_anexos}
            </body>
        </html>
    ";

    return $corpo;
}

// quinta função
function tratar_anexo($anexo) {
    $padrao = '/^.+(\.pdf$|\.zip$)$/';
    $resultado = preg_match($padrao, $anexo['name']);

    if (!$resultado) {
        return false;
    }

    move_uploaded_file($anexo['tmp_name'], "anexos/{$anexo['name']}");
    
    return true;
}

// sexta função
function validar_data($data)
{
    $padrao = '/^[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4}$/';
    $resultado = preg_match($padrao, $data);

    if (!$resultado) {
        return false;
    }

    $dados = explode('/', $data);

    $dia = (int)$dados[0];
    $mes = (int)$dados[1];
    $ano = (int)$dados[2];

    $resultado = checkdate($mes, $dia, $ano);

    return $resultado;
}

//fim função email

// setima função
function converte_concluida($concluida)
{
    if ($concluida == 1) {
        return 'Sim';
    }

    return 'Não';
}

// oitava função
function converte_prioridade($codigo)
{
    $prioridade = '';
    switch ($codigo) {
        case 1:
            $prioridade = 'Baixa';
            break;
        case 2:
            $prioridade = 'Média';
            break;
        case 3:
            $prioridade = 'Alta';
            break;
    }

    return $prioridade;
}

// nona função
function converte_data_para_banco($data)
{
    if ($data == "") {
        return "";
    }

    $dados = explode("/", $data);

    if (count($dados) != 3) {
        return $data;
    }

    $data_mysql = "{$dados[2]}-{$dados[1]}-{$dados[0]}";

    return $data_mysql;
}

function converte_data_br_para_objetos($data)
{
    if($data == "") {
        return "";
    }

    $dados = explode("/", $data);

    if (count($dados) != 3) {
        return($data);
    }

    return DateTime::createFromFormat('d/m/Y', $data);
}

// decima função
function converte_data_para_tela($data)
{
    // Verifica se é um objeto DateTime tive que pedir pro chat gpt
    if ($data instanceof DateTime) {
        return $data->format('d/m/Y');
    }

    if ($data == "" || $data == "0000-00-00") {
        return "";
    }

    $dados = explode("-", $data);

    if (count($dados) != 3) {
        return $data;
    }

    $data_exibir = "{$dados[2]}/{$dados[1]}/{$dados[0]}";

    return $data_exibir;
}
