<?php 
require_once 'class/query_obj_composite_pattern_repository/Transaction.php';
require_once 'class/query_obj_composite_pattern_repository/Connection.php';
require_once 'class/query_obj_composite_pattern_repository/Logger.php';
require_once 'class/query_obj_composite_pattern_repository/LoggerTXT.php';
//require_once 'class/query_obj_composite_pattern_repository/LoggerXML.php';
require_once 'class/query_obj_composite_pattern_repository/Record.php';
require_once 'class/query_obj_composite_pattern_repository/Produto.php';
require_once 'class/query_obj_composite_pattern_repository/Repository.php';
require_once 'class/query_obj_composite_pattern_repository/Criteria.php';
require_once 'class/query_obj_composite_pattern_repository/Expression.php';
require_once 'class/query_obj_composite_pattern_repository/Filter.php';

try 
{
    Transaction::open('estoque');
    Transaction::setLogger(new LoggerTXT('class/query_obj_composite_pattern_repository/tmp/log_collection_get.txt'));
    //Transaction::setLogger(new LoggerXML('class/query_obj_composite_pattern_repository/tmp/log.xml'));
    
    // Define o criterio de selecao
    $criteria = new Criteria;
    $criteria->add(new Filter('estoque', '>', 10));
    $criteria->add(new Filter('origem', '=', 'N'));

    // Cria o repositorio
    $repository = new Repository('Produto');
    // Carrega os objetos, conforme o criterio
    $produtos = $repository->load($criteria);

    if ($produtos)
    {
        echo "Produtos <br>\n";
        // Percorre todos objetos
        foreach ($produtos as $produto) 
        {
            echo ' ID ' . $produto->id;
            echo ' - Descricao: ' . $produto->descricao;
            echo ' - Estoque ' . $produto->estoque;
            echo "<br>\n";
        }
    } 

    print "Quantidade: " . $repository->count($criteria);
    Transaction::close(); // Fecha a transação
}
catch (Exception $e)
{
    Transaction::rollback();    
    print $e->getMessage();
}


