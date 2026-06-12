<?php

namespace Cloud\Engine\PHP\MySQL;

class CloudEngineMySQLQuery {
    
    public static function execute($connection, $SQLSentence, $parameters = NULL) {
        $query = $connection->getConnection()->prepare($SQLSentence);
        
        if ($parameters != NULL && count($parameters) > 0) {
            $dataTypes = "";
            $byRef = array();
            for ($i=0; $i<count($parameters); $i++) {
                $parameters[$i] = ($parameters[$i]);
                $dataTypes .= "s";
            }
            $byRef[] = $dataTypes;
            for ($i=0; $i<count($parameters); $i++) {
                $byRef[] = & $parameters[$i];
            }
            call_user_func_array(array($query, 'bind_param'), $byRef);
        }
        
        $query->execute();
        
        $result = $query->get_result();
        
        $connection->close();
        
        //print_r($result);
        
        return $result;
    }
    
}
