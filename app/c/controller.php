<?php
function pr($var){
    echo "<pre>" . print_r($var , true) . "</pre>\n";
}//end function

/*
 * load html webpages - ram through dom object to get rid of errors
 * load from url(default) or string
 */
function load_simplexml_page($page , $type='url'){
    ini_set('user_agent', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.9) Gecko/20071025 Firefox/2.0.0.9');
    $dom = new DOMDocument('1.0', 'UTF-8');
    $type === 'url'
        ? @$dom->loadHTMLFile(trim($page))
        : @$dom->loadHTML($page);
    return simplexml_import_dom($dom);
}//end function

class controller{
    public
        $fw,    //framework
        $db,    //database class
        $layout = 'layouts/default_flex.ctp',
        $autoRender = true ,
        $viewClass = 'Template',    //view class
        $view ,                     //view object
        $scripts = [
            "//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js",
            "//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js",
            //"https://adbit.co/js/show_ads.js"
        ],  //array of js urls to load
        $site_settings ,    //main settings available to all controllers/pages
        $models = null;     //access to db mappers(models)
        
    public function __construct(){        
        $this->fw = Base::instance();
        session_name($this->fw->SITE_NAME);    //set session/coookie to site name
        session_start();
        /*
         * set up dummy section to test DB for install
         * need to check for this the old fashion way
         */
            $mysqli = new mysqli($this->fw->DB_HOST , $this->fw->DB_USER , $this->fw->DB_PASS );
            if($mysqli->connect_error){
                $this->fw->set('SESSION.flash' , ['type' => 'danger' , 'message' => 'NO database server connection, please check config.ini...']);
                $this->fw->reroute("/install");
            }
            $db = $mysqli->select_db($this->fw->DB_NAME);
            if($db===false){
                $this->fw->set('SESSION.flash' , ['type' => 'warning' , 'message' => 'Good database connection, now lets install it...']);
                $this->fw->reroute("/install");
            }
        /*
         * end - dummy DB test
         */
        //we made it past dummy check - now we can use framework
        $this->db = new DB\SQL($this->fw->get('DSN') , $this->fw->get('DB_USER') , $this->fw->get('DB_PASS'));        
        //all controllers/pages/views have acces to settings
        $all_settings = [];
        $settings = $this->_load_model('settings');        
        //convert to easy access array
        foreach ($settings->find() as $setting){
            $all_settings[$setting->param] = $setting->val;
        }//end - convert to easy array
        $this->site_settings = $all_settings;
        
        $this->view = new $this->{viewClass}(); //init view/template class
        //pr($this);    //debugging
    }//end constructor
    
    /*
     * load $this->models (for reloading / late binding)
     */
    protected function _load_models( $model_array = null){
        //create db mappers
        if(is_array($model_array)){
            foreach($model_array as $model){
                if(!$this->{$model})
                    //$this->{$model} = new DB\SQL\Mapper($this->db , $model);    //init model
                    $this->{$model} = $this->_load_model($model);
            }//end models
        }//end - controller has models        
    }//end function
    
    /*
     * load db mapper - auto add DB_PREFIX for devs
     */
    protected function _load_model($table = null){
        return $table ? new DB\SQL\Mapper($this->db , "{$this->fw->DB_PREFIX}_{$table}") : null;
    }//end function
    
    
    //make nav available to all controllers/views
    public function nav($menu = 'menu.ini'){
        $items = parse_ini_file("{$this->fw->UI}elements/{$menu}");
        $this->fw->set('menu' , $items);
        //return $this->view->render("elements/pages_nav.ctp");
    }//end function    

    protected function is_ssl() {
        if(isset($_SERVER['HTTPS'])){
            if('on' == strtolower($_SERVER['HTTPS']))
                return true;
            if('1' == $_SERVER['HTTPS'])
                return true;
            if(true == $_SERVER['HTTPS'])
                return true;
        }elseif(isset($_SERVER['SERVER_PORT']) && ('443' == $_SERVER['SERVER_PORT'])){
            return true;
        }
        if(isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) == 'https') {
            return true;
        }
        return false;        
    }//end function
    
    //chek for admin/auth sections
    public function beforeRoute(){
        //create db mappers(orm)
        $this->_load_models( $this->models );

        if(stripos($this->fw->get('PATH'), "/admin") !== false){
            //make sure admin is logged in
            if($this->fw->get('SESSION.admin')==='active'){ 
                $action = $this->fw->get('PARAMS.action')
                        ? "admin_" . $this->fw->get('PARAMS.action') 
                        : "admin_index";            
                $this->fw->set('PARAMS.action' , $action);
                $this->fw->set('title' , 'ADMIN :: ' . get_class($this));
                $this->nav('admin.ini');
            } else {
                $this->fw->set('SESSION.flash' , ['type' => 'warning' , 'message' => 'You must login...']);
                $this->fw->reroute("/login");
            }
        }//end - check admin - auth
                
        if(!$this->fw->get('menu')){
            $this->nav();
        }
        $this->fw->set('site_settings' , $this->site_settings);  //make global settings available to all controllers/views                
        $this->fw->set('nav' , 'pages_nav.ctp');        //make nav available to all controllers/views
        $this->fw->set('scripts' , $this->scripts);     //make scripts available to all controllers/views
    }//end function
    
    //set up the view - clear the session flash
    public function afterRoute() {
        if($this->autoRender){
            //pr($this);
            $action = $this->fw->get('PARAMS.action') ? : "index";            
            $title = $this->fw->get('title') ? $this->fw->get('title') : $this->site_settings['site_name'] . " : " . get_class($this);

            $this->fw->mset([
                'title' => $title,
                'content' => $this->fw->get('content') ? : get_class($this) . "/{$action}.ctp"
            ]);
            echo $this->view->render($this->layout);            
        }
        $this->fw->set('SESSION.flash', []);    //clear session flash messages
    }//end function    
}