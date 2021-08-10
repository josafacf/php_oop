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
    Transaction::setLogger(new LoggerTXT('class/query_obj_composite_pattern_repository/tmp/log_collection_update.txt'));
    //Transaction::setLogger(new LoggerXML('class/query_obj_composite_pattern_repository/tmp/log.xml'));
    
    // Define o criterio de selecao
    $criteria = new Criteria;
    $criteria->add(new Filter('preco_venda', '<=', 35));
    $criteria->add(new Filter('origem', '=', 'N'));

    // Cria o repositorio
    $repository = new Repository('Produto');
    // Atualiza os objetos, conforme o criterio
    $produtos = $repository->load($criteria);

    if ($produtos)
    {
        echo "Produtos <br>\n";
        // Percorre todos objetos
        foreach ($produtos as $produto) 
        {
            $produto->preco_venda *= 1.3;
            $produto->store();
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


