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


    function setFileAcl() {
        $others_command = "sudo setfacl -m other::" . (request("r_others") ? "r" : "-") . (request("w_others") ? "w" : "-") 
        . (request("x_others") ? "x" : "-") . " " . trim(request("file_path"));
        
        $user_command = "sudo setfacl -m user::" . (request("r_user") ? "r" : "-") . (request("w_user") ? "w" : "-") 
        . (request("x_user") ? "x" : "-") . " " . trim(request("file_path"));

        $group_command = "sudo setfacl -m group::" . (request("r_group") ? "r" : "-") . (request("w_group") ? "w" : "-") 
        . (request("x_group") ? "x" : "-") . " " . trim(request("file_path"));

        $others_output = runCommand($others_command);
        $user_output = runCommand($user_command);
        $group_output = runCommand($group_command);

        if ($user_output . $others_output . $group_output != "") {
            return respond($user_output . $others_output . $group_output, 201);
        } else {
            return respond("OK",200);
        }

    }
?>