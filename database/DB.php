<?php

namespace database;

use PDO;

class DB
{
    public PDO $connection;

    private bool $distinct = false;
    private string $table;
    private array $columns;
    private array $join;
    private array $where;
    private array $orderBy;
    private array $groupBy;
    private int $limit;
    private int $offset;
    private array $values;
    private string $statement;

    public function __construct($table)
    {
        $this->table = $table;
        $this->connection = DBConnection::connect();
    }

    public static function table($table)
    {
        return new self($table);
    }

    public function select($columns)
    {
        $this->columns = is_array($columns) ? $columns : func_get_args();
        return $this;
    }

    public function distinct()
    {
        $this->distinct = true;
        return $this;
    }

    public function join($table, $first, $operator, $second, $type = 'INNER')
    {
        $this->join[] = [$table, $first, $operator, $second, $type];
        return $this;
    }

    public function leftJoin($table, $first, $operator, $second)
    {
        return $this->join($table, $first, $operator, $second, 'LEFT');
    }

    public function rightJoin($table, $first, $operator, $second)
    {
        return $this->join($table, $first, $operator, $second, 'RIGHT');
    }

    public function where($column, $operator, $value, $boolean = "AND")
    {
        $this->where[] = [$column, $operator, $value, $boolean];
        return $this;
    }

    public function orWhere($column, $operator, $value)
    {
        return $this->where($column, $operator, $value, "OR");
    }

    public function groupBy($columns)
    {
        $this->groupBy = is_array($columns) ? $columns : func_get_args();
        return $this;
    }

    public function orderBy($columns, $sort = 'ASC')
    {
        $this->orderBy[] = [$columns, $sort];
        return $this;
    }

    public function limit($number)
    {
        $this->limit = $number;
        return $this;
    }

    public function offset($number)
    {
        $this->offset = $number;
        return $this;
    }

    public function stringOrNumber($value)
    {
        return is_string($value) ? "'$value'" : $value;
    }

    public function execute()
    {
        if (!isset($this->table) || empty($this->table))
        {
            return false;
        }

        $SQL = $this->distinct ? "SELECT DISTINCT " : "SELECT ";

        if (isset($this->columns) && is_array($this->columns))
        {
            $SQL .= implode(', ', $this->columns);
        }
        else
        {
            $SQL = " *";
        }

        $SQL .= " FROM $this->table";

        if (isset($this->join) && is_array($this->join))
        {
            foreach ($this->join as $item)
            {
                switch (strtolower($item[4]))
                {
                    case 'inner':
                        $SQL .= " INNER JOIN";
                        break;
                    case 'left':
                        $SQL .= " LEFT JOIN";
                        break;
                    case 'right':
                        $SQL .= " RIGHT JOIN";
                        break;
                    default:
                        $SQL .= " INNER JOIN";
                        break;
                }
                $SQL .= " $item[0] ON $item[1] $item[2] $item[3]";
            }
        }

        if (isset($this->where) && is_array($this->where))
        {
            $SQL .= " WHERE";
            foreach ($this->where as $key => $item)
            {
                if ($key > 0)
                {
                    $SQL .= " $item[3]";
                }
                $SQL .= " $item[0] $item[1] " . $this->stringOrNumber($item[2]);
            }
        }

        if (isset($this->groupBy) && is_array($this->groupBy))
        {
            $SQL .= " GROUP BY " . implode(', ', $this->groupBy);
        }

        if (isset($this->orderBy) && is_array($this->orderBy))
        {
            $SQL .= " ORDER BY ";
            foreach ($this->orderBy as $key => $item)
            {
                $SQL .= "$item[0] $item[1]";
                if ($key < (count($this->orderBy) - 1))
                {
                    $SQL .= ", ";
                }
            }
        }

        if (isset($this->limit))
        {
            $SQL .= " LIMIT $this->limit";
        }

        if (isset($this->offset))
        {
            $SQL .= " OFFSET $this->offset";
        }

        $this->statement = $SQL;
        return $this;
    }

    public function get()
    {
        $SQL = $this->statement;
        $query = $this->connection->prepare($SQL);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function first()
    {
        $SQL = $this->statement;
        $query = $this->connection->prepare($SQL);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function insert($values)
    {
        $this->values = is_array($values) ? $values : func_get_args();
        if (!isset($this->table) || empty($this->table))
        {
            return false;
        }
        $SQL = "INSERT INTO $this->table";
        if (isset($this->values) && is_array($this->values))
        {
            $column_name = [];
            $column_value = [];
            foreach ($this->values as $col_key => $value)
            {
                array_push($column_name, $col_key);
                array_push($column_value, $this->stringOrNumber($value));
            }
            $SQL .= " (". implode(', ', $column_name) .") VALUES (". implode(", ", $column_value) .")";
        }
        $this->statement = $SQL;
        return $this;
    }

    public function update($values)
    {
        $this->values = is_array($values) ? $values : func_get_args();
        if (!isset($this->table) || empty($this->table))
        {
            return false;
        }
        $SQL = "UPDATE $this->table";
        if (isset($this->values) && is_array($this->values))
        {
            $SQL .= " SET";
            foreach ($this->values as $col_key => $value)
            {
                $SQL .= " ".$col_key . " = " . $this->stringOrNumber($value) .",";
            }
            $SQL = rtrim($SQL, ",");
        }
        $this->statement = $SQL;
        return $this;
    }

    public function save()
    {
        $SQL = $this->statement;
        if (isset($this->where) && is_array($this->where))
        {
            $SQL .= " WHERE";
            foreach ($this->where as $key => $item)
            {
                if ($key > 0)
                {
                    $SQL .= " $item[3]";
                }
                $SQL .= " $item[0] $item[1] " . $this->stringOrNumber($item[2]);
            }
            $query = $this->connection->prepare($SQL);
            return $query->execute();
        }
        else
        {
            $query = $this->connection->prepare($SQL);
            $query->execute();
            return $this->connection->lastInsertId();
        }
    }

    public function increment($column)
    {
        if (!isset($this->table) || empty($this->table))
        {
            return false;
        }
        $SQL = "UPDATE $this->table SET $column = $column + 1";
        if (isset($this->where) && is_array($this->where))
        {
            $SQL .= " WHERE";
            foreach ($this->where as $key => $item)
            {
                $SQL .= " $item[0] $item[1] " . $this->stringOrNumber($item[2]);
                if ($key < (count($this->where) - 1))
                {
                    $SQL .= " $item[3]";
                }
            }
        }
        $query = $this->connection->prepare($SQL);
        return $query->execute();
    }

    public function delete($select, $value)
    {
        if (!isset($this->table) || empty($this->table))
        {
            return false;
        }
        $SQL = "DELETE FROM $this->table WHERE $select = " . $this->stringOrNumber($value);
        $query = $this->connection->prepare($SQL);
        return $query->execute();
    }
}