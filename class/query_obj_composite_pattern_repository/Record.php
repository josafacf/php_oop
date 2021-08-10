<?php
abstract class Record
{
	protected $data; // Array contendo os dados do objeto

	public function __construct($id = NULL)
	{
		if ($id) // Se o ID for informado
		{
			// Carrega o objeto correspondente
			$objetc = $this->load($id);
			if ($objetc)
			{
				$this->fromArray($objetc->toArray());
			}
		}
	}


	public function __clone()
	{
		unset($this->data['id']);
	}

	public function __set($prop, $value)
	{
		if (method_exists($this, 'set_'.$prop))
		{
			// Executa o método set_<propiedade>
			call_user_func(array($this, 'set_'.$prop), $value);
		}
		else
		{
			if ($value === NULL)
			{
				unset($this->data[$prop]);
			}
			else
			{
				$this->data[$prop] = $value; // atribui o valor da propiedade	
			}
		}
	}

	public function __get($prop)
	{
		if (method_exists($this, 'get_'.$prop))
		{
			// Executa o método get_<propiedade>
			return call_user_func(array($this, 'get_'.$prop));
		}
		else
		{
			if (isset($this->data[$prop]))
			{
				return $this->data[$prop];
			}
		}
	}

	public function __isset($prop)
	{
		return isset($this->data[$prop]);
	}


	public function getEntity()
	{
		$class = get_class($this); // Obtém o nome da classe
		return constant("{$class}::TABLENAME"); // Retorno a constante de classe TABLENAME
	}

	public function fromArray($data)
	{
		$this->data = $data;
	}

	public function toArray()
	{
		return $this->data;
	}


	public function store()
    {
        $prepared = $this->prepare($this->data);
        
        // verifica se tem ID ou se existe na base de dados
        if (empty($this->data['id']) or (!$this->load($this->id)))
        {
            // incrementa o ID
            if (empty($this->data['id']))
            {
                $this->id = $this->getLast() +1;
                $prepared['id'] = $this->id;
            }
            
            // cria uma instrução de insert
            $sql = "INSERT INTO {$this->getEntity()} " . 
                   '('. implode(', ', array_keys($prepared))   . ' )'.
                   ' values ' .
                   '('. implode(', ', array_values($prepared)) . ' )';
        }
        else
        {
            // monta a string de UPDATE
            $sql = "UPDATE {$this->getEntity()}";
            // monta os pares: coluna=valor,...
            if ($prepared) 
            {
                foreach ($prepared as $column => $value) 
                {
                    if ($column !== 'id') 
                    {
                        $set[] = "{$column} = {$value}";
                    }
                }
            }
            $sql .= ' SET ' . implode(', ', $set);
            $sql .= ' WHERE id=' . (int) $this->data['id'];
        }
        
        // obtém transação ativa
        if ($conn = Transaction::get())
        {
            // faz o log e executa o SQL
            Transaction::log($sql);
            $result = $conn->exec($sql);
            // retorna o resultado
            return $result;
        }
        else
        {
            // se não tiver transação, retorna uma exceção
            throw new Exception('Não há transação ativa!!');
        }
    }

    public function load($id)
    {
        // instancia instrução de SELECT
        $sql = "SELECT * FROM {$this->getEntity()}";
        $sql .= ' WHERE id=' . (int) $id;
        
        // obtém transação ativa
        if ($conn = Transaction::get())
        {
            // cria mensagem de log e executa a consulta
            Transaction::log($sql);
            $result= $conn->query($sql);
            
            // se retornou algum dado
            if ($result)
            {
                // retorna os dados em forma de objeto
                $object = $result->fetchObject(get_class($this));
            }
            return $object;
        }
        else
        {
            // se não tiver transação, retorna uma exceção
            throw new Exception('Não há transação ativa!!');
        }
    }

    public function delete($id = NULL)
    {
        // o ID é o parâmetro ou a propriedade ID
        $id = $id ? $id : $this->id;
        
        // monsta a string de UPDATE
        $sql  = "DELETE FROM {$this->getEntity()}";
        $sql .= ' WHERE id=' . (int) $this->data['id'];
        
        // obtém transação ativa
        if ($conn = Transaction::get())
        {
            // faz o log e executa o SQL
            Transaction::log($sql);
            $result = $conn->exec($sql);
            // retorna o resultado
            return $result;
        }
        else
        {
            // se não tiver transação, retorna uma exceção
            throw new Exception('Não há transação ativa!!');
        }
    }

    /**
     * Busca um objeto pelo id
     */
    public static function find($id)
    {
        $classname = get_called_class();
        $ar = new $classname;
        return $ar->load($id);
    }

    private function getLast()
    {
        // inicia transação
        if ($conn = Transaction::get())
        {
            // instancia instrução de SELECT
            $sql  = "SELECT max(id) FROM {$this->getEntity()}";
            
            // cria log e executa instrução SQL
            Transaction::log($sql);
            $result= $conn->query($sql);
            
            // retorna os dados do banco
            $row = $result->fetch();
            return $row[0];
        }
        else
        {
            // se não tiver transação, retorna uma exceção
            throw new Exception('Não há transação ativa!!');
        }
    }

    public function prepare($data)
    {
        $prepared = array();
        foreach ($data as $key => $value)
        {
            if (is_scalar($value))
            {
                $prepared[$key] = $this->escape($value);
            }
        }
        return $prepared;
    }

    public function escape($value)
    {
        // verifica se é um dado escalar (string, inteiro, ...)
        if (is_scalar($value))
        {
            if (is_string($value) and (!empty($value)))
            {
                // adiciona \ em aspas
                $value = addslashes($value);
                // caso seja uma string
                return "'$value'";
            }
            else if (is_bool($value))
            {
                // caso seja um boolean
                return $value ? 'TRUE': 'FALSE';
            }
            else if ($value!=='')
            {
                // caso seja outro tipo de dado
                return $value;
            }
            else
            {
                // caso seja NULL
                return "NULL";
            }
        }
    }


}