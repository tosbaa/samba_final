<?php
    function setUserAcl() {
        $command = "sudo setfacl -m u:" . trim(request("user_name")) . ":" . (request("r") ? "r" : "-") 
        . (request("w") ? "w" : "-") . (request("x") ? "x" : "-") . " " . trim(request("file_path"));
        $output = runCommand($command);
        if($output != ""){
            return respond($output,201);
        }else{
            return respond("OK",200);
        }
    }

    function setGroupAcl() {
        $command = "sudo setfacl -m g:" . trim(request("group_name")) . ":" . (request("r") ? "r" : "-") 
        . (request("w") ? "w" : "-") . (request("x") ? "x" : "-") . " " . trim(request("file_path"));
        $output = runCommand($command);
        if($output != ""){
            return respond($output,201);
        }else{
            return respond("OK",200);
        }
    }

?>