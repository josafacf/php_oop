<?php 
abstract class Expression 
{
	//Operadores lógicos
	const AND_OPERATOR = 'AND ';
	const OR_OPERATOR = 'OR ';

	abstract public function dump();
}