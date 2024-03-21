<?php

require "tarefa.model.php";
require "tarefa.service.php";
require "conexao.php";

$acao = isset ($_GET['acao']) ? $_GET['acao'] : $acao;
if ($acao == 'inserir') {
    $tarefa = new Tarefa();
    $tarefa->__set('tarefa', $_POST['tarefa']);
    $tarefa->__set('data_vencimento', $_POST['data_vencimento']);
    $tarefa->__set('id_categoria', $_POST['id_categoria']);

    $conexao = new Conexao();
    $tarefaService = new TarefaService($conexao, $tarefa);
    $tarefaService->inserir();

    header('Location: todas_tarefas.php');
} else if ($acao == 'recuperar') {
    $tarefa = new Tarefa();
    $conexao = new Conexao();

    $tarefaService = new TarefaService($conexao, $tarefa);
    $tarefas = $tarefaService->recuperar();
} else if ($acao == 'atualizar') {
    $tarefa = new Tarefa();
    $tarefa->__set('id', $_POST['id']);
    $tarefa->__set('tarefa', $_POST['tarefa']);
    $tarefa->__set('data_vencimento', $_POST['data_vencimento']);

    $conexao = new Conexao();
    $tarefaService = new TarefaService($conexao, $tarefa);
    $tarefaService->atualizar();
    header('Location: index.php');
} else if ($acao == 'remover') {
    $tarefa = new Tarefa();
    $tarefa->__set('id', $_GET['id']);
    $conexao = new Conexao();

    $tarefaService = new TarefaService($conexao, $tarefa);
    $tarefaService->remover();

    header('Location: todas_tarefas.php');
} else if ($acao == 'recuperarTarefasPendentes') {
    $tarefa = new Tarefa();
    $tarefa->__set('id_status', 1);

    $conexao = new Conexao();

    $tarefaService = new TarefaService($conexao, $tarefa);
    $tarefas = $tarefaService->recuperarTarefasPendentes();
} else if ($acao == 'marcarRealizadas') {
    $tarefa = new Tarefa();
    $tarefa->__set('id', $_GET['id']);
    $tarefa->__set('id_status', 2);

    $conexao = new Conexao();

    $tarefaService = new TarefaService($conexao, $tarefa);
    $tarefaService->marcarRealizada();

    header('Location: todas_tarefas.php');
} else if ($acao == 'filtrarStatus') {
    $tarefa = new Tarefa();
    $conexao = new Conexao();
    $tarefaService = new TarefaService($conexao, $tarefa);

    $tarefa->__set('id_status', $_GET['id']);
    $tarefas = $tarefaService->recuperarTarefasPendentes();
} else if ($acao == 'ordenarPor') {
    if (isset ($_GET['atributo'])) {
        $tarefa = new Tarefa();
        $conexao = new Conexao();
        $tarefaService = new TarefaService($conexao, $tarefa);

        $tarefas = $tarefaService->ordenarPor($_GET['atributo']);
    }
} else if ($acao == 'verificarTarefas') {
    $tarefa = new Tarefa();
    $conexao = new Conexao();
    $tarefaService = new TarefaService($conexao, $tarefa);

    // Não precisamos mais do loop, pois vamos apenas verificar se há tarefas vencidas
    $vencido = $tarefaService->verificarTarefa();

    if ($vencido) {
        echo "<script>alert('Você possui tarefas vencendo hoje ou que vencerão em breve. Verifique suas tarefas para não perder nenhum prazo!');</script>";
    }

} else if ($acao == 'arquivar') {
    $tarefa = new Tarefa();
    $tarefa->__set('id', $_GET['id']);
    $conexao = new Conexao();

    $tarefaService = new TarefaService($conexao, $tarefa);
    $tarefaService->arquivarTarefa();

    header('Location: todas_tarefas.php');
}


