<?php

namespace App\Db;

use \PDO;
use \PDOException;

class Database{
    /**
     * host de conexao com o banco de dados
     * @var string
     */
    const HOST = '127.0.0.1';

    /**
     * nome do banco de dados
     * @var string
     */

     const NAME = 'cn_vagas';

     /**
     * usuario do banco de dados
     * @var string
     */

    const USER = 'root';


    /**
     * nome do banco de dados
     * @var string
     */

    const PASS = '';

    /**
     * nome da tabela do banco de dados a ser manipulada
     * @var string
     */

       private  $table;

       /**
        * instancia da conexao com o banco de dados
        * @var PDO
        */

        private $connection;

        /**
         * define a tabela e intancia a canexao
         * @param string $table
         */

        public function __construct($table = null){
          $this->table = $table;
          $this->setConnection();
          }

         /**
          * metodo responsavel por criar uma conexao com o banco de dados

          *na linha abaixo  no trexo "..   self::USER          .." indica que 
          new PDO('mysql:host='.self::HOST.';dbname='.self::NAME,self::USER,self::PASS);

          */

          private function setConnection(){
              try{
                $this->connection = new PDO('mysql:host='.self::HOST.';dbname='.self::NAME, self::USER,self::PASS);
                 $this->connection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
              }catch(PDOException $e){
                  die('ERROR:'.$e->getMessage());
                  

              }
          }

                   /**
           * Método responsável por executar queries dentro do banco de dados
           * @param  string $query
           * @param  array  $params
           * @return PDOStatement
           */
          public function execute($query,$params = []){




            try{
              $statement = $this->connection->prepare($query);
              $statement->execute($params);
              return $statement;
            }catch(PDOException $e){
              die('ERROR: '.$e->getMessage());
            }
          }
            /**
               * Método responsável por inserir dados no banco
               * @param  array $values [ field => value ]
               * @return integer ID inserido
               */

          public function insert($values){
            //dados da query

            $fields = array_keys($values);
            $binds = array_pad([],count($fields), '?');

            //monta a query
             $query = 'INSERT INTO '.$this->table.' ('.implode(',',$fields).') VALUES ('.implode(',',$binds).')';

            //EXECUTA O INSERT
             $this->execute($query,array_values($values));


            //RETORNA O ID INSERIDO
            return $this->connection->lastInsertId();             


          }
         
          /**
           * Método responsável por executar uma consulta no banco
           * @param  string $where
           * @param  string $order
           * @param  string $limit
           * @param  string $fields
           * @return PDOStatement
           */
          public function select($where = null, $order = null, $limit = null, $fields = '*'){
               //DADOS DA QUERY
            $where = strlen($where) ? 'WHERE '.$where : '';
            $order = strlen($order) ? 'ORDER BY '.$order : '';
            $limit = strlen($limit) ? 'LIMIT '.$limit : '';

            //MONTA A QUERY
            $query = 'SELECT '.$fields.' FROM '.$this->table.' '.$where.' '.$order.' '.$limit;

            //EXECUTA A QUERY
            return $this->execute($query);

          }


          /**
             * Método responsável por executar atualizações no banco de dados
             * @param  string $where
             * @param  array $values [ field => value ]
             * @return boolean
             */
            public function update($where,$values){
              //DADOS DA QUERY
              $fields = array_keys($values);

              //MONTA A QUERY
              $query = 'UPDATE '.$this->table.' SET '.implode('=?,',$fields).'=? WHERE '.$where;

              //EXECUTAR A QUERY
              $this->execute($query,array_values($values));

              //RETORNA SUCESSO
              return true;
            }



            
          /**
           * Método responsável por excluir dados do banco
           * @param  string $where
           * @return boolean
           */
          public function delete($where){
            //MONTA A QUERY
            $query = 'DELETE FROM '.$this->table.' WHERE '.$where;

            //EXECUTA A QUERY
            $this->execute($query);

            //RETORNA SUCESSO
            return true;
          }























}