<?php

class AutoCompleteData extends CApplicationComponent {

    public $db;
    public $tables;

    public function init() {
        parent::init();
        $this->db = Yii::app()->db;
    }

    public function getData() {
        $data = array();
        foreach ($this->tables as $tableName => $columns) {
            $sql = "SELECT ::columns:: FROM {{" . $tableName . "}} ";
            foreach ($columns as $column) {
                /*
                 * prepare $sal to query
                 */
                $sql = str_replace("::columns::", $column . " , ::columns::", $sql);
            }
            $sql = str_replace(" , ::columns::", "", $sql);
//            var_dump($sql);
            $_table = Yii::app()->db->tablePrefix . $tableName;
            $data[$_table][] = Yii::app()->db->createCommand($sql)->queryAll();
        }

        $arr = array();
        foreach ($data as $table) {
            foreach ($table as $columns) {
                foreach ($columns as $column) {
                    foreach ($this->tables as $tableName=>$_table) {
                        foreach ($_table as $columnName) {
                        $arr[] = array(
                            'value' => $column[$columnName],
                            'label' => $column[$columnName]/*." $tableName"*/,
                        );
                        }
                    }
                }
            }
        }
//        var_dump($arr);
        return $arr;
    }

}

?>
