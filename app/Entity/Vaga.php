<?php

namespace App\entity;
use \App\Db\Database;
use \PDO;

class Vaga{

   /**
   * Identificador único da vaga
   * @var integer
   */
  public $id;

   /**
   * titulo da vaga
   * @var string
   */
  public $titulo;

   /**
   * descrisao  da vaga(aceita html)
   * @var string
   */
  public $descricao;


   /**
   * descrisao  da vaga(aceita html)
   * @var string(s/n)
   */
  public $ativo;

    
   /**
   * data da publicacao
   * @var string
   */
  public $data;


  /**
   * metodo responsavel por cadastrar uma nova vaga no banco
   * @return boolean
   */
    public function cadastrar(){
        //definir a data
        $this->data = date('Y-m-d H:i:s');

        //inserir a vaga no banco

        $obDatabase = new Database('vagas');


        //atribuir o id da vaga na instancia e chamar o metodo insert de Database passando os dados da instancia

        $this->id = $obDatabase->insert([
                                      'titulo'    => $this->titulo,
                                      'descricao' => $this->descricao,
                                      'ativo'     => $this->ativo,
                                      'data'      => $this->data
                                    ]);
         
    //RETORNAR SUCESSO

          return true;  
       
        
    }

     /**
   * Método responsável por atualizar a vaga no banco
   * @return boolean
   */
    public function atualizar(){
      return (new Database('vagas'))->update('id = '.$this->id,[
                                                                  'titulo'    => $this->titulo,
                                                                  'descricao' => $this->descricao,
                                                                  'ativo'     => $this->ativo,
                                                                  'data'      => $this->data
                                                                ]);
    }

    /**
   * Método responsável por excluir a vaga do banco
   * @return boolean
   */
    public function excluir(){
    return (new Database('vagas'))->delete('id = '.$this->id);
  }




    /**
     * metodo que recupera as vagas do banco de dados
     * @param  string $where
     * @param  string $order
     * @param string $limit
     * @return array
     */
    public static function getVagas($where = null, $order = null, $limit = null){
        return (new Database('vagas'))->select($where,$order,$limit)->fetchAll(PDO::FETCH_CLASS,self::class);

    }


       /**
     * Método responsável por buscar uma vaga com base em seu ID
     * @param  integer $id
     * @return Vaga
     */
    public static function getVaga($id){
      return (new Database('vagas'))->select('id = '.$id)
                                    ->fetchObject(self::class);
    }



}