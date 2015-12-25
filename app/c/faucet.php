<?php
class faucet extends controller{    

    protected $resp;    //store captcha response (for debugging)
    protected $fb_resp; //store faucetbox response (for debugging)


    public function index(){
        $this->_load_models(['ips' , 'addresses' , 'refs']);    //late binding - we only need these models for this function
        //$_SESSION = $_COOKIE = [];    //empty the session and cookie  - for testing
        //pr([$_SESSION , $_COOKIE]);
        //check for referal - check the cookie then the request
        $referral = $this->fw->get('COOKIE.r')?:$this->fw->get('REQUEST.r');
        if($referral){
            $this->fw->set('COOKIE.r' , $referral);
            $this->refs->load(["address = ?" , $referral]);
            //make sure referral is in db
            if($this->refs->dry()){ //referral not in db yet
                $this->refs->address = $referral;
                $this->refs->save();
            }
        }//end - set up referral cookie/session/db
                
        //set rewards for page
        $rewards = $this->rewards();    
        $this->fw->set('rewards' , $rewards);   

        //set captcha html for page
        $captcha = $this->{"_{$this->site_settings['default_captcha']}"}();
        $this->fw->set('captcha' , $captcha);
        
        //check for form post - give reward (if timer/ip elegible) - timer/ip eligible should be set on page(cookie) - we just double check here for scammers
        if($data = $this->fw->get('POST.faucet') ){

            if($data['address']){
                $this->fw->set('SESSION.address' , $data['address']);   //keep the address in SESSION
                //check the ip and the address for eligible before processing captcha (save bandwidth - catch scammers early)
                $this->ips->recent = "TIMESTAMPDIFF(MINUTE, last_used, CURRENT_TIMESTAMP())";   //create virtual field
                $this->ips->load(["ip = ?" , $this->fw->get('IP')]);
                $this->addresses->recent = "TIMESTAMPDIFF(MINUTE, last_used, CURRENT_TIMESTAMP())";   //create virtual field
                $this->addresses->load(["address = ?" , $data['address']]);
                //pr([$this->ips , $this->addresses]);
                
                if($this->ips->recent < $this->site_settings['timer'] || $this->addresses->recent < $this->site_settings['timer']){
                    $time_left = $this->site_settings['timer'] - $this->ips->recent;
                    $this->fw->set('SESSION.flash' , ['type' => 'warning' , 'message' => "It appears your IP or address tried too soon.. please wait {$time_left} minutes..."]);
                    $this->fw->reroute("/faucet");   //redirect and bail
                }//end - server timer not ready yet - re-directed user
                      
                $captcha_valid = $this->{"_{$this->site_settings['default_captcha']}"}(true);   //process protected captcha function
                if($captcha_valid){                    
                    $last_used = $this->fw->TIME;
                    
                    //generate reward
                    $total = 0;
                    $roll = number_format(mt_rand() /  mt_getrandmax() * 100 , 2); //get percentage roll (2 decimal places)
                    foreach($rewards as $chance){
                        $total += $chance['chance'];
                        if($roll <= $total){
                            $reward = $chance['satoshi'];
                            break;
                        }
                    }//end - get random reward
                    
                    //handle payments - user/ref payments                    
                    $fb = new FaucetBOX($this->site_settings['api_key'] , $this->site_settings['currency']);
                    
                    //now pay the faucet user
                    $this->fb_resp = $fb->send($data['address'] , $reward);
                    $msg_type = $this->fb_resp['success'] !== false
                            ? 'success'
                            : 'danger';
                    $message = $this->fb_resp['success']
                            ? "Congrats - you rolled {$roll} and won {$reward} Satoshi...<br /><a href='/faucet/more'>Get more Satoshi's here...</a>"
                            : "OOPS - " . json_encode($this->fb_resp);                            
                            
                    //now pay the referral - only if it is not 'self' fererral      
                    if(!$this->refs->dry() && $this->refs->address !== $data['address']){
                        $ref_pay = round (($this->site_settings['referral'] * $reward) / 100);

                        //only send payment if we have it
                        $balance = $this->site_settings['balance'] * 100000000; //convert BTC balance to satoshi
                        if($balance > ($this->refs->balance + $ref_pay + $reward)){                            
                            $this->fb_resp = $fb->sendReferralEarnings($this->refs->address , $ref_pay);
                            $message .= $this->fb_resp['success']
                                    ? "and we sent {$ref_pay} to your referrer too..."
                                    : "OOPS - " . json_encode($this->fb_resp);                            
                            $this->refs->balance > 0 ? $this->refs->balance -= $ref_pay : 0;
                            $this->refs->save();
                        } else {
                            $this->refs->balance += $ref_pay;
                            $this->refs->save();
                        }
                        $this->addresses->ref_id = $this->refs->id; //make sure referrer is attahced to this addy
                    }//end - pay referral                    
                                                            
                    
                    //update refs / ips / addresses with new timestamps                    
                    $this->ips->ip = $this->fw->get('IP');
                    $this->ips->last_used = date('Y-m-d H:i:s' , $last_used);   //convert unix time to timestamp/datetime val
                    $this->ips->save();
                    
                    $this->addresses->address = $data['address'];
                    $this->addresses->last_used = date('Y-m-d H:i:s' , $last_used); //convert unix time to timestamp/datetime val
                    $this->addresses->save();
                    //pr([$this->ips , $this->addresses]);
                    //set cookie timers - end_user time and server time for double check
                    $this->fw->mset([
                        'SESSION.u' => $data['time'] + $this->site_settings['timer']*60*1000 ,   //users time + minutes * seconds * milliseconds
                        'SESSION.s' => $last_used + $this->site_settings['timer']*60*1000 ,      //server time + minutes * seconds * milliseconds                                           
                        'SESSION.flash' => ['type' => $msg_type , 'message' => $message]
                        ]);
                }//end - good captcha
                else{
                    //put bad captcha/error message here
                    $this->fw->set('SESSION.flash' , ['type' => 'warning' , 'message' => print_r($this->resp , true)]);
                }
            }//end - good address
            else{
                $this->fw->set('SESSION.flash' , ['type' => 'warning' , 'message' => 'You must enter a valid BTC address...']);
            }
        }//end - faucet post
        
        $this->fw->set('scriptBottom' , "/js/faucet.js");
    }//end function
    
    
    /*
     * show users more faucets
     * use referal or site owner addy
     */
    public function more(){
        $url = "https://faucetbox.com/en/list/{$this->site_settings['currency']}";        
        //set good browser agent
        ini_set('user_agent', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.9) Gecko/20071025 Firefox/2.0.0.9');
        //load external faucet page - convert links and add referral addy
        $page = \Web::instance()->request( $url );
        $xml = load_simplexml_page($page['body']  , 'html');
        if($xml){
            $faucets = [];
            foreach($xml->xpath("//a[contains(@href , '?url=')]") as $faucet){
                parse_str(parse_url($faucet['href'] , PHP_URL_QUERY) , $parts);
                $faucets[] = [
                    trim((string)$faucet) , //link text
                    "{$parts['url']}?r={$this->fw->get('COOKIE.r')}"
                ];
            }//end - get faucet links
            $this->fw->set('faucets' , $faucets);
        }else{
            $this->fw->set('SESSION.flash' , ['type' => 'warning' , 'message' => 'Sorry...Unable to load faucets']);
        }
    }//end function


    /*
     * return array of available rewards
     */
    protected function rewards(){
        $return = [];
        //$rewards = $this->settings->load(['param = ?' , 'rewards']);
        $rewards = $this->site_settings['rewards'];
        foreach(explode("," , $rewards) as $reward){
            list($percent , $val) = explode("*" , $reward);
            $return[] = ['satoshi' => $val , 'chance' => $percent];
        }
        return $return;
    }//end function
    
    
    /*
     * _captchafunction
     * @param $get_api
     *  default false = just get the captcha html for the page
     *  true = form posted - check the captcha
     * 
     * all the captcha api functions return default html or an api result
     * all api functions store the response in $this->resp (for debugging)
     */
    protected function _solvemedia($get_api = false){        
        require_once $this->fw->get('ROOT') . '/captcha_lib/solvemedialib.php';
        if(!$get_api){
            $captcha = solvemedia_get_html($this->site_settings['faucet_solvemedia_challenge_key'] , null, $ssl = $this->is_ssl());
            return $captcha;    //bail early
        }
        $this->resp = solvemedia_check_answer(
            $this->site_settings['faucet_solvemedia_verification_key'],
            $this->fw->get('IP'),
            $this->fw->get('POST.adcopy_challenge'),
            $this->fw->get('POST.adcopy_response'),
            $this->site_settings['faucet_solvemedia_auth_key']
        );
        return $this->resp->is_valid;
    }//end function
    
    protected function _recaptcha($get_api = false){
        if(!$get_api){
            $captcha = $this->view->render("elements/recaptcha.ctp");
            return $captcha;    //bail early
        }
        $vars = [
            'secret' => $this->site_settings['faucet_recaptcha_private_key'],
            'response' => $this->fw->get('POST.g-recaptcha-response'),
            'remoteip' => $this->fw->get('IP')
        ];
        $url = "https://www.google.com/recaptcha/api/siteverify?" . http_build_query($vars);
        $this->resp = json_decode(file_get_contents($url) , true);
        return $this->resp['success'];
    }//end function
    
    protected function _ayah($get_api = false){
        require_once $this->fw->get('ROOT') . '/captcha_lib/ayahlib.php';
        if(!$get_api){            
            $this->ayah = new AYAH([
                'publisher_key' => $this->site_settings['faucet_ayah_publisher_key'],
                'scoring_key' => $this->site_settings['faucet_ayah_scoring_key'],
                'web_service_host' => 'ws.areyouahuman.com',
                'debug_mode' => false,
                'use_curl' => true
            ]);
            $captcha = $this->ayah->getPublisherHTML();       
            return $captcha;    //bail early
        }
        $this->resp = $this->ayah->scoreResult();
        return $this->resp;
    }//end function

    protected function _captchme($get_api = false){
        require_once $this->fw->get('ROOT') . '/captcha_lib/captchme-lib.php';
        if(!$get_api){            
            $captcha = captchme_generate_html($this->site_settings['faucet_captchme_public_key'], null, $ssl = $this->is_ssl());            
            return $captcha;    //bail early
        }
        $this->resp = captchme_verify(
            $this->site_settings['faucet_captchme_private_key'],
            $this->fw->get('POST.captchme_challenge_field'),
            $this->fw->get('POST.captchme_response_field'),
            $this->fw->get('IP'),
            $this->site_settings['faucet_captchme_authentication_key']
        );
        return $this->resp->is_valid;
    }//end function
    
    protected function _funcaptcha($get_api = false){
        require_once $this->fw->get('ROOT') . '/captcha_lib/funcaptcha.php';
        if(!$get_api){            
            $this->funcaptcha = new FUNCAPTCHA();
            $captcha = $this->funcaptcha->getFunCaptcha($this->site_settings['faucet_funcaptcha_public_key']);            
            return $captcha;    //bail early
        }
        $this->resp =  $funcaptcha->checkResult($this->site_settings['faucet_funcaptcha_private_key']);
        return $this->resp;
    }//end function

    protected function _reklamper($get_api = false){
        if(!$get_api){
            $captcha = '<script src="http://api.reklamper.com/start.js"></script>';
            return $captcha;    //bail early
        }
        $url = "http://api.reklamper.com/validate";
        $reklamper_data = [
            'captcha[value]' => $this->fw->get("POST.captcha_name"),
            'captcha[key]'   => $this->fw->get("COOKIE._cpathca")
        ];
        $options = [
            'method' => 'POST',
            'content' => http_build_query($reklamper_data)
        ];
        $this->resp = \Web::instance()->request($url , $options);        
        return $this->resp['body']==1;
    }//end function

    
    public function admin_index(){
        $settings = $this->_load_model('settings');
        if($data = $this->fw->get('POST.data')){
            
            $api_key = $settings->load(['param=?' , 'api_key']);
            $api_key->val = $data['api_key'];
            $api_key->save();
            
            $currency = $settings->load(['param=?' , 'currency']);
            $currency->val = $data['currency'];
            $currency->save();
                    
            //update captcha settings
            $updates = $settings->find(["param='default_captcha' or param like '%{$data['default_captcha']}%'"]);
            foreach($updates as $setting){
                if(array_key_exists($setting->param , $data)){
                    $setting->val = $data[$setting->param];
                }
                $setting->save();
            }//end - save captcha settings
            
            $this->fw->set('SESSION.flash' , ['type' => 'success' , 'message' => "Faucet Settings Updated..."]);
            $this->fw->reroute("/admin/faucet");    //reroute so new settings get loaded - hack for beforeroute
        }//end - update faucet data

        //currencies - only used if fb api doesnt have api_key yet
        $fb_currencies = ['BTC', 'LTC', 'DOGE', 'PPC', 'XPM', 'DASH'];        
        $faucets = [
            'Solve Media' => 'solvemedia',
            'ReCaptcha' => 'recaptcha',
            'Are You Human' => 'ayah',
            'Captch Me' => 'captchme',
            'Fun Captcha' => 'funcaptcha',
            'ReKlamper' => 'reklamper'
            ];
        $this->fw->set('faucets' , $faucets);
        
        //check apikey - current balance - and faucet currency
        $fb = new FaucetBOX($this->site_settings['api_key'] , $this->site_settings['currency']);
        $currencies = $fb->getCurrencies()?:$fb_currencies;        
        $this->fw->set('currencies' , $currencies);
        //pr($fb);
        if($fb->last_status !== 200){
            $this->fw->set('SESSION.flash' , ['type' => 'danger' , 'message' => "Please check your faucetbox api key..."]);
        } else {
            //good fb connection = check balance (every 10 mins)
            if($this->site_settings['last_balance_check'] < (time() + 60*10)){
                $fb_balance = $fb->getBalance();
                $last_balance_check = $this->fw->get('TIME');
                
                $settings_balance = $settings->load(['param=?' , 'balance']);
                $settings_balance->val = $fb_balance['balance_bitcoin'];
                $settings_balance->save();
                //make sure to update site settings var
                $this->site_settings['balance'] = $settings_balance->val;
                
                $settings_balance_check = $settings->load(['param=?' , 'last_balance_check']);
                $settings_balance_check->val = $last_balance_check;
                $settings_balance_check->save();
                //make sure to update site settings var
                $this->site_settings['last_balance_check'] = $settings_balance_check->val;
            }
        }//end - update balance
                
        $this->fw->set('scriptBottom' , "/js/faucet_admin.js");
    }//end function
}