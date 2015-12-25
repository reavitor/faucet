<?php
class settings extends controller{   

    public $models = ['settings'];
    
    public function login(){
        if($post = $this->fw->get('POST')){
            //YISg5Hhe50vHwkX
            if($this->site_settings['password'] === crypt($post['data']['pass_word'] , $this->site_settings['password']) ){
                $this->fw->set('SESSION.admin' , 'active');
                $this->fw->set('SESSION.flash' , ['type' => 'success' , 'message' => 'You are logged in...']);
                $this->fw->reroute("/admin");
            }
            else {
                $message = "Login failed...<br />If you lost your password, you can reset it - <a href='./lost_pass'>Reset Password</a>";
                $this->fw->set('SESSION.flash' , ['type' => 'warning' , 'message' => $message]);
                $this->fw->reroute("/login");
            }
        }//end POST - logging in
        $this->fw->set('title' , "Login : {$this->site_settings['site_name']}");
        $this->fw->set('content' , "settings/login.ctp");   //[routes] don't set $action correctly
    }//end function
    
    
    public function logout() {
        $this->fw->set('SESSION.admin' , []);
        $this->fw->set('SESSION.flash' , ['type' => 'success' , 'message' => 'You are logged out...']);
        $this->fw->reroute("/");
    }//end function
    
    public function admin_index(){        
        //check last balance
        if($this->site_settings['last_balance_check'] < (time() + 60*10)){
            $_SERVER['HTTP_HOST'] = 'faucet.is-lost.org';   //over-ride for testing on localhost
            $fb = new FaucetBOX($this->site_settings['api_key']);
            $ret = $fb->getBalance();
            //pr($ret);
            $last_balance_check = $this->settings->load(['param = ?' , 'last_balance_check']);       
            $last_balance_check->val = time();
            $last_balance_check->update();
            $balance = $this->settings->load(['param = ?' , 'balance']);
            $balance->val = $ret['balance_bitcoin'];
            $balance->update();
        }
        $settings = $this->settings->find();    //load all available settings                
        $this->fw->set('settings' , $settings);
        $this->fw->set('scriptBottom' , "/js/settings_admin.js");
    }//end function
    

    public function admin_save() {
        $this->autoRender = false;  //ajax - not returning a view 
        if($data  = $this->fw->get('POST.data')){
            $this->settings->load(['param=?' , $data['param']]);    //init mapper object
            $this->settings->copyFrom($data);   //update with new data
            $this->settings->save();
        }//end - save setting
    }//end function
}