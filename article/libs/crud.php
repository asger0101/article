<?php

abstract class crud {

    protected $objCon;
    protected $tableName;
    protected $pk = "id";
    protected $fields = array();
    protected $values = array();
    protected $string;

    public function __construct($objCon, $id = null) {
        $this->objCon = $objCon;
        $this->tableName = DB_PREFIX . get_called_class();
        $this->values = array_fill_keys($this->fields, NULL);
        if ($id) {
            $this->findById($id);
        }
    }
    
    /**
     * @comment runs the script, and returns an object
     * @param type $id
     */
    public function Sql_By_Id($id){
        $fields = implode(",", $this->fields);
        $sql = "SELECT $fields FROM $this->tableName WHERE id = " . $id;

        $result = $this->objCon->query($sql);
        $row = $result->fetch_object();

        foreach ($this->fields as $fields) {
            $this->values[$fields] = $row->$fields;
        }
    }
    
    /**
     * 
     * @param type integer
     * @throws Exception when not an ID
     */
    protected function findById($id) {
        if(is_numeric($id)){
            $this->Sql_By_Id($id);
        }else{
            throw new Exception("$id Ugyldigt id, eller ikke et tal");
        }
    }

    /**
     * 
     * @param string $return  Default id, kunne evt være object. 
     * @param type $condition
     * @return array som er id'er, eller objecter
     */
    public function findAll($condition = "") {
        $class = get_called_class();
        $fields = implode(",", $this->fields); 
        $aa = [];
        $sql = "SELECT $fields FROM $this->tableName $condition";
        $result = $this->objCon->query($sql);
        //lav validate
        while ($row = $result->fetch_object()) {
            $aa[] = new $class($this->objCon,$row->id); 
        }
        return $aa;
    }

    /**
     * Husk at objektet skal vÃ¦re init med id
     */
    public function delete() {
        $sql = "DELETE FROM $this->tableName WHERE id = " . $this->values['id'];
        $this->objCon->query($sql);
    }

    /**
     * @comment inserts the values in the right table
     * @return type
     */
    protected function insert() {
        $fields = $this->fields;
        $values = $this->values;
        array_shift($values);
        array_shift($fields);
        
        $sql = "INSERT INTO $this->tableName (". implode(",", $fields). ") VALUES ('" . implode("','", $values) . "' ) ";
        $this->objCon->query($sql) or die($this->objCon->error);
        return $this->values['id'] = $this->objCon->insert_id;
    }

    /**
     * @comment updates the respective values in a specific table
     */
    protected function update() {
        foreach ($this->values as $key => $value) {
            $this->string .= "$key='$value',";
        }
        $this->string = rtrim($this->string, ",");
        $sql = "UPDATE $this->tableName SET $this->string WHERE id =" . $this->values['id'];
        $this->objCon->query($sql)or die($this->objCon->error);
    }

    /**
     * @comment uses html special chars and trim to secure string from injections
     */
    protected function escapeString() {
        foreach ($this->values as $field => $value) {
            $this->values[$field] = htmlspecialchars(trim($value));
        }
    }

    /**
     * @comment executes save() and either goes to insert() or update()
     * @return type
     */
    public function save() {
        $this->escapeString();
        if ($this->values['id']) {
            $this->update();
        } else {
            return $this->insert();
        }
    }

}
