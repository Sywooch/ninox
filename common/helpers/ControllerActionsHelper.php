<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 23.11.15
 * Time: 17:59
 */

namespace common\helpers;


class ControllerActionsHelper {

    public static function getActions($controller){
        /**
         * Спижженая функция
         * нужно переделать
         * должно работать так: передаём контроллер - находит методы этого контроллера
         */
        $controllerlist = [];

        if ($handle = opendir('../controllers')) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != ".." && substr($file, strrpos($file, '.') - 10) == 'Controller.php') {
                    $controllerlist[] = $file;
                }
            }
            closedir($handle);
        }

        asort($controllerlist);
        $fulllist = [];

        foreach ($controllerlist as $controller) {
            $handle = fopen('../controllers/' . $controller, "r");
            if ($handle) {
                while (($line = fgets($handle)) !== false) {
                    if (preg_match('/public function action(.*?)\(/', $line, $display)){
                        if (strlen($display[1]) > 2){
                            $fulllist[substr($controller, 0, -4)][] = strtolower($display[1]);
                        }
                    }
                }
            }
            fclose($handle);
        }

        return $fulllist;
    }

}