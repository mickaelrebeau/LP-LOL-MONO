<?php 


namespace App\Utils;


class ErrorUtils {


    static function UserNotFound($id) {
        return sprintf('No user found for id : %d', $id);
    }

}