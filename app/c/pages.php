<?php
class pages extends controller{
    /*
    public $models = [
        'pages'     //for keeping track of active/inactive pages and menu weight
        ];
    */
    
    public function display(){
        $this->autoRender = false;  //static pages work different than others
        $parts = explode("/" , trim($this->fw->get('PATH') , "/"));   //remove leading / trailing slashes        
        //default entry page is home.ctp
        array_shift($parts);
        $path = sizeof($parts)==0 ? "home" : join("/", $parts);
        $this->fw->set('title' , "{$this->site_settings['site_name']} : " . ucwords($path));
        $this->fw->set('content' , "pages/{$path}.ctp");
        echo $this->view->render($this->layout);
    }//end function       
    
    
    public function admin_index(){
        $files = [];
        $dir = dir("{$this->fw->UI}pages");
        while ($entry = $dir->read()){
            if($entry !== '.' && $entry !== '..' && strpos($entry , '.ctp')!== false && strpos($entry , 'admin_')===false){ //only edit.ctp or .ini files - do not allow editing of admin pages
                $files[] = $entry;
            }
        }
        $this->fw->set('files' , $files);
        //not using pages in DB yet
        //$pages = $this->pages->find();
        //$this->fw->set('pages' , $pages);
    }//end function
    
    public function admin_edit(){
        $this->fw->push('scripts' , "//cdn.ckeditor.com/4.5.5/full/ckeditor.js");   //add ck editor
        
        if($this->fw->QUERY){
            $file = @file_get_contents("{$this->fw->UI}/pages/{$this->fw->QUERY}");
            $this->fw->mset([
                'file_name' => $this->fw->QUERY,
                'file_content' => $file]);
        }//end - open file
        if($post = $this->fw->POST){
            $result = file_put_contents("{$this->fw->UI}pages/{$post['data']['file_name']}" , $post['data']['file_content']);
            if($result){
            $this->fw->mset([
                'file_name' => $post['data']['file_name'],
                'file_content' => $post['data']['file_content']]);
                $this->fw->set('SESSION.flash' , ['type' => 'success' , 'message' => "File {$post['data']['file_name']} saved..."]);
            }
        }//end - save file
        
        $this->fw->set('scriptBottom' , "/js/pages_admin_edit.js");
    }//end function
    
    public function admin_menu() {
        
        if($this->fw->QUERY){
            $file = @file_get_contents("{$this->fw->UI}/elements/{$this->fw->QUERY}");
            $this->fw->mset([
                'file_name' => $this->fw->QUERY,
                'file_content' => $file]);
        }//end - open requested file
        if($post = $this->fw->POST){
            $result = file_put_contents("{$this->fw->UI}elements/{$post['data']['file_name']}" , $post['data']['file_content']);
            if($result){
            $this->fw->mset([
                'file_name' => $post['data']['file_name'],
                'file_content' => $post['data']['file_content']]);
                $this->fw->set('SESSION.flash' , ['type' => 'success' , 'message' => "File {$post['data']['file_name']} saved..."]);
            }
        }//end - save file
        
        //if not editing - show list of files
        if(!$this->fw->QUERY && !$this->fw->POST){
            $files = [];
            $dir = dir("{$this->fw->UI}elements");
            while ($entry = $dir->read()){
                if($entry !== '.' && $entry !== '..' && strpos($entry , '.ini')!== false && strpos($entry , 'admin_')===false){ //only edit.ctp or .ini files - do not allow editing of admin pages
                    $files[] = $entry;
                }
            }
            $this->fw->set('files' , $files);
        }//end  - show all files
    }//end function
    
    /*
     * ajax function
     */
    public function admin_ad(){
        $this->autoRender = false;  //this ajax doesnt use a view
        if($this->fw->AJAX){
            echo $this->site_settings[$this->fw->QUERY];
        }
    }//end function
    
    public function admin_ads(){        
        if($this->fw->AJAX && $data = $this->fw->get('POST.data')){
            $this->autoRender = false;  //ajax not return a view
            $ad = $this->_load_model('settings');
            $ad->load(["param = ?" , $data['page_ads']]);
            $ad->val = $data['page_ad'];
            $ad->save();
            echo $ad->val;  //return the html for preview
            return; //done with ajax - bail
        }//end - save data        
        
        $this->fw->set('scriptBottom' , "/js/pages_admin_ads.js");
    }//end function
}