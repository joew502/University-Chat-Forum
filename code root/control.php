<?php
session_start();

#parse URL for relevant components
require_once "./utils/url_parsing.php";
$MC_path_components = get_path_comps();
$MC_control_path = get_control_path();
$MC_abspath_cr = get_abspth_cr();

#Get login status util
require "./utils/session_login.php";

switch($MC_path_components[0]) {
    case "":
        header("Location: $MC_control_path/home");
        break;
    case "home":
        require "./controllers/homepage_controller.php";
        break;
    case "halls":
        switch (count($MC_path_components)) {
            case 1:
                header("Location: $MC_control_path/home");
                break;
            case 2:
                $BIL_hall = ucwords($MC_path_components[1]);
                require "./controllers/block_list_controller.php";
                break;
            case 3:
                $RIL_hall = ucwords($MC_path_components[1]);
                $RIL_block = urldecode($MC_path_components[2]);
                require "./controllers/room_list_controller.php";
                break;
            case 4:
                $IR_hall = ucwords($MC_path_components[1]);
                $IR_block = $MC_path_components[2];
                $IR_room = $MC_path_components[3];
                require "./controllers/inner_room_controller.php";
                break;
            default:
                include "./views/error.php";
                http_response_code(404);
                exit();
        }
        break;

    case "login":
        switch (count($MC_path_components)) {
            case 1:
                require "./controllers/login_controller.php";
                break;
            case 2:
                if($MC_path_components[1] == "attempt"){
                    require "./utils/login_attempt.php";
                    $INT_res = LOGIN_pwdlogin();
                    if($INT_res === False){
                        $ERROR_title = "Incorrect username or password";
                        $ERROR_desc = "";
                        $ERROR_code = "---";
                        include "./views/error.php";
                    } elseif($INT_res !== True){
                        $ERROR_title = $INT_res;
                        $ERROR_desc = "Login failure";
                        $ERROR_code = "---";
                        include "./views/error.php";
                    } else{
                        header("Location: $MC_control_path");
                    }
                    break;
                }
                include "./views/error.php";
                http_response_code(404);
                exit();
            default:
                include "./views/error.php";
                http_response_code(404);
                exit();
        }
        break;

    case "register":
        switch (count($MC_path_components)) {
            case 1:
                require "./controllers/register_controller.php";
                break;
            case 2:
                if($MC_path_components[1] == "attempt"){
                    require "./utils/registration_attempt.php";
                    $INT_res = REG_register();
                    if($INT_res !== True){
                        $ERROR_title = $INT_res;
                        $ERROR_desc = "Registration error";
                        $ERROR_code = "---";
                        include "./views/error.php";
                    } else{
                        header("Location: $MC_control_path");
                    }
                    break;
                }
                include "./views/error.php";
                http_response_code(404);
                exit();
            default:
                include "./views/error.php";
                http_response_code(404);
                exit();
        }
        break;
    case "createRoom":
        require "./utils/create_room_attempt.php";
        $INT_res = RL_CRA_create_room();
        if($INT_res !== True){
            $ERROR_title = $INT_res;
            $ERROR_desc = "room creation error";
            $ERROR_code = "---";
            include "./views/error.php";
        } else{
            header("Location: ".($RL_CRA_ret_loc ?? $MC_control_path));
        }
        break;
    case "editroom":
        require "./utils/edit_room_attempt.php";
        $INT_res = RV_ERA_edit_room();
        if($INT_res !== True){
            $ERROR_title = $INT_res;
            $ERROR_desc = "room edit error";
            $ERROR_code = "---";
            include "./views/error.php";
        } else{
            header("Location: ".($RV_ERA_ret_loc ?? $MC_control_path));
        }
        break;
    case "createcomment":
        require "./utils/create_comment_attempt.php";
        $INT_res = RV_CCA_create_comment();
        if($INT_res !== True){
            $ERROR_title = $INT_res;
            $ERROR_desc = "comment creation error";
            $ERROR_code = "---";
            include "./views/error.php";
        } else{
            header("Location: ".($RV_CCA_ret_loc ?? $MC_control_path));
        }
        break;
    case "createblock":
        require "./utils/create_block_attempt.php";
        $INT_res = BV_CBA_create_block();
        if($INT_res !== True){
            $ERROR_title = $INT_res;
            $ERROR_desc = "Block creation error";
            $ERROR_code = "---";
            include "./views/error.php";
        } else{
            header("Location: ".($BV_CBA_ret_loc ?? $MC_control_path));
        }
        break;

    case "editblock":
        require "./utils/edit_block_attempt.php";
        $INT_res = BV_EBA_edit_block();
        if($INT_res !== True){
            $ERROR_title = $INT_res;
            $ERROR_desc = "Block edit error";
            $ERROR_code = "---";
            include "./views/error.php";
        } else{
            header("Location: ".($BV_EBA_ret_loc ?? $MC_control_path));
        }
        break;

    case "userpage":
        if(count($MC_path_components) == 1){
            require "./controllers/userpage_controller.php";
        } else if(count($MC_path_components) == 2){
            if($MC_path_components[1] === "sendxp"){
                require "./utils/com_points_transfer.php";
                $INT_res = CPT_com_points_trans();
                if($INT_res !== True){
                    $ERROR_title = $INT_res;
                    $ERROR_desc = "Point transfer error";
                    $ERROR_code = "---";
                    include "./views/error.php";
                } else{
                    header("Location: ".$MC_control_path);
                }
                break;
            } else {
                include "./views/error.php";
                http_response_code(404);
            }
        } else {
            include "./views/error.php";
            http_response_code(404);
        }
        break;
    
    case "changePassword":
        switch (count($MC_path_components)) {
            case 1:
                require "./controllers/changepassword_controller.php";
                break;
            case 2:
                if($MC_path_components[1] == "attempt"){
                    require "./utils/changepassword_attempt.php";
                    $INT_res = CPA_change();
                    if($INT_res !== True){
                        $ERROR_title = $INT_res;
                        $ERROR_desc = "Change password error";
                        $ERROR_code = "---";
                        include "./views/error.php";
                    } else{
                        header("Location: $MC_control_path");
                    }
                    break;
                }
                include "./views/error.php";
                http_response_code(404);
                exit();
            default:
                include "./views/error.php";
                http_response_code(404);
                exit();
        }
        break;
    case "promotemember";
        require "./utils/make_admin_attempt.php";
        $INT_res = BV_MA_make_admin();
        if($INT_res !== True){
            $ERROR_title = $INT_res;
            $ERROR_desc = "Block edit error";
            $ERROR_code = "---";
            include "./views/error.php";
        } else{
            header("Location: ".($BV_MA_ret_loc ?? $MC_control_path));
        }
        break;
    case "tos":
        require "./views/tos.php";
        break;
    case "privacy":
        require "./views/privacypolicy.php";
        break;
    case "logout":
    case "signout":
        require "./controllers/logout_controller.php";
        break;//should be unreachable
    default:
        #NOTE: include used to prevent erroring during error management
        include "./views/error.php";
        http_response_code(404);
        exit();
}



