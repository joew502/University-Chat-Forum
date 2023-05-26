<?php

/**
 * @param string $target - The data to validate.
 * @param string $type - The type the data should conform to.
 * @return bool|string - True if conforms to requested type false otherwise.
 */
function SANE_sane(string $target, string $type){
    $target = trim($target);
    switch ($type){
        case "comment_text":
        case "room_text":
        case "block_desc":
            //DO NOT CHANGE THE FOLLOWING INDENTATION IT WILL BREAK!!!!!
            $regex_check = <<<'EOD'
    /^[a-zA-Z0-9 !"£$%^&*()_+\-=[\]{};'#:@~<>?,.\/¬|\n]{1,500}$/Du
    EOD;
            break;
        case "room_name":
        case "block_name":
            $regex_check = "/^[a-zA-Z0-9 !,.£$()@#~:;]{3,32}$/D";
            break;
        case "hall_name":
        case "hall":
            $regex_check = "/^(Academic|Community|Society)$/D";
            break;
        case "password":
            $regex_check = "/^[\S]{8,32}$/D";
            break;
        case "username":
            $regex_check = "/^[a-zA-Z\-_ 0-9]{4,30}$/D";
            break;
        case "exeter_email":
            $regex_check = "/^[a-zA-Z\.0-9]++@exeter\.ac\.uk$/D";
            break;
        default:
            trigger_error("The type name: $type is invalid",E_USER_ERROR);
            return false;
    }
    if (preg_match($regex_check,$target)){
        return true;
    }
    return false;
}
