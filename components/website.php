<?php
/* 
 * Copyright (C) 2017 themhz
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

class Website{

    public function start(){
        session_start();
        
        include "config.php";

        include __DIR__.'/requesthandler.php';
        include __DIR__.'/responsehandler.php';

        //$requesthandler = new requesthandler();

        //Set the default system timezone according to user settings
        date_default_timezone_set('UTC');
        //
        //Load the dbhandler
        include __DIR__.'/../libs/dbhandler.php';

        //Load the Global functions
        include __DIR__.'/../libs/functions.php';
        

        //Load the authenticationhandler
        //include __DIR__.'/authenticationhandler.php';

        //include __DIR__.'/accounthandler.php';        

        //Load page
        include __DIR__.'/pages.php';
        $pageloader =  new pageloader();
        
        //Load controler
        include __DIR__.'/controller.php';       
                 
        $raw = isset($_REQUEST['format']) ? $_REQUEST['format'] : '';        
        if($raw != 'raw'){
            //Load template            
            include __DIR__.'/../template/index.php';
        }
    }
}