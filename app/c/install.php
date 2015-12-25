<?php
class install extends controller{
    
    /*
     *  over-ride the default constructor
     *  i.e., dont try to load anything from the framework db - it isn't there yet 
     * 
     */
    public function __construct(){        
        $this->fw = Base::instance();   //connect to the framework
        session_name($this->fw->SITE_NAME);    //set coookie to site name
        session_start();
        $this->db = new mysqli($this->fw->DB_HOST , $this->fw->DB_USER , $this->fw->DB_PASS );  //connect to database server - old fashioned way - framework not available yet
        $this->view = new $this->{viewClass}(); //init view/template class
    }//end - over-ride parent constructor
    
    
    public function index(){               
                            
        if($data = $this->fw->get('POST.install')){
            if($data['db_pass'] === $this->fw->DB_PASS){                
                $this->_install_db();   //install db first
                //now we can use framework
                $this->db = new DB\SQL($this->fw->get('DSN') , $this->fw->get('DB_USER') , $this->fw->get('DB_PASS'));        
                $password = $this->_setNewPass();   //you can't set pass until db is installed
                
                $message = "Your faucet is installed and ready to use - please save your password - {$password}";
                $this->fw->set('SESSION.flash' , ['type' => 'success' , 'message' => $message]);
            }//end - good db password
            else{
                $message = "Bad password - please check and try again...";
                $this->fw->set('SESSION.flash' , ['type' => 'warning' , 'message' => $message]);                
            }
        }//end - clean install                

    }//end function


    public function lost_pass() {
        $this->autoRender = false;  //over-ride default view
        $this->fw->set('lost_pass' , 'lost_pass');  //set toggle var 
        
        if($data = $this->fw->get('POST.install')){
            if($data['db_pass'] === $this->fw->DB_PASS){
                $password = $this->_setNewPass();   

                $message = "Your password has been reset - please save your new password - {$password}";
                $this->fw->set('SESSION.flash' , ['type' => 'success' , 'message' => $message]);            
            }//end - good db password
            else{
                $message = "Bad password - please check and try again...";
                $this->fw->set('SESSION.flash' , ['type' => 'warning' , 'message' => $message]);                
            }            
        }//end - post
        
        $this->fw->set('content' , "install/index.ctp");    //re-using index view
        echo $this->view->render($this->layout);        
    }//end function
    
    
    protected function _setNewPass() {
        //now we can use framework
        $this->db = new DB\SQL($this->fw->get('DSN') , $this->fw->get('DB_USER') , $this->fw->get('DB_PASS'));        
        $setting = $this->_load_model('settings')->load(["param = ?" , 'password']);    //object chain ;)
        
        $alphabet = str_split('qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM1234567890');
        $password = ''; 
        for($i = 0; $i < 15; $i++)
            $password .= $alphabet[array_rand($alphabet)];
        $hash = crypt($password);        
        $setting->val = $hash;
        $setting->save();
        
        return $password;
    }//end function

    
    protected function _install_db(){
        $mysql = $this->view->render("install/install.sql");    //load sql file w/vars - let the view create good queries
        $this->db->multi_query($mysql);
        $this->db->close();
        $this->db = null;
    }//end function    
}