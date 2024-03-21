<?php

class tarefa
{
    private $id;
    private $id_status;
    private $tarefa;
    private $data_cadastro;
    //Inserido a data de vencimento da tarefa apenas
    private $data_vencimento;

    public function __get($atributo)
    {
        return $this->$atributo;
    }

    public function __set($atributo, $valor)
    {
        $this->$atributo = $valor;
        return $this;
    }
}


?>