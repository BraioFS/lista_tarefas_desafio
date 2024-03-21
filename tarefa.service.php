<?php

class TarefaService
{
    private $conexao;
    private $tarefa;

    public function __construct(Conexao $conexao, Tarefa $tarefa)
    {
        $this->conexao = $conexao->conectar();
        $this->tarefa = $tarefa;
    }

    public function inserir()
    {
        $query = 'INSERT INTO tb_tarefas(tarefa, data_vencimento, id_categoria) VALUES (:tarefa, :data_vencimento, :id_categoria)';
        $stmt = $this->conexao->prepare($query);
        $stmt->bindValue(':tarefa', $this->tarefa->__get('tarefa'));
        $stmt->bindValue(':data_vencimento', $this->tarefa->__get('data_vencimento'));
        $stmt->bindValue(':id_categoria', $this->tarefa->__get('id_categoria'));
        $stmt->execute();
    }


    public function recuperar()
    {
        //R - read
        $query = '
            select
                t.id, s.status, t.tarefa
            from
                tb_tarefas as t
                left join tb_status as s on (t.id_status = s.id) 
        ';

        $stmt = $this->conexao->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function atualizar()
    {
        // U - update
        $query = "
    UPDATE 
        tb_tarefas 
    SET 
        tarefa = :tarefa,
        data_vencimento = :data_vencimento
    WHERE 
        id = :id";
        $stmt = $this->conexao->prepare($query);
        $stmt->bindValue(':tarefa', $this->tarefa->__get('tarefa'));
        $stmt->bindValue(':data_vencimento', $this->tarefa->__get('data_vencimento')); // Vincular a data de vencimento
        $stmt->bindValue(':id', $this->tarefa->__get('id'));
        return $stmt->execute();
    }


    public function remover()
    {
        //D - delete
        $query = '
        delete from 
            tb_tarefas
        where
            id= :id 
        ';
        $stmt = $this->conexao->prepare($query);
        $stmt->bindValue(':id', $this->tarefa->__get('id'));
        $stmt->execute();
    }

    public function marcarRealizada()
    {
        $query = "
        update 
            tb_tarefas 
        set 
            id_status = ? 
        where 
            id = ?";
        $stmt = $this->conexao->prepare($query);
        $stmt->bindValue(1, $this->tarefa->__get('id_status'));
        $stmt->bindValue(2, $this->tarefa->__get('id'));
        return $stmt->execute();
    }

    public function recuperarTarefasPendentes()
    {
        $query = '
        select
            t.id,
            s.status,
            t.tarefa
        from
            tb_tarefas as t
            left join tb_status as s on(t.id_status = s.id)
        where
            t.id_status = :id_status
        ';
        $stmt = $this->conexao->prepare($query);
        $stmt->bindValue('id_status', $this->tarefa->__get('id_status'));
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function ordenarPor($orderBy)
    {
        $query = "SELECT t.id, s.status, t.tarefa FROM tb_tarefas as t LEFT JOIN tb_status as s ON (t.id_status = s.id) ORDER BY $orderBy";
        $stmt = $this->conexao->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function verificarTarefa()
    {
        $hoje = date('Y-m-d'); // Obtém a data atual no formato MySQL

        $query = "SELECT * FROM tb_tarefas WHERE data_vencimento <= :hoje";
        $stmt = $this->conexao->prepare($query);
        $stmt->bindParam(':hoje', $hoje);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function arquivarTarefa()
    {
        $query = "
        UPDATE 
            tb_tarefas 
        SET 
            id_status = 3
        WHERE 
            id = :id
    ";
        $stmt = $this->conexao->prepare($query);
        $stmt->bindValue(':id', $this->tarefa->__get('id'));
        return $stmt->execute();
    }




}
