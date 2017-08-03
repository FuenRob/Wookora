<?php

class log {
    
    protected $reportErrorLog = '';
    protected $logPath = '/logs/';
    
    
    /*
     * Method logGenerate
     * 
     * @param $name     Log file name
     * @return $logName Return new file name
     */
    private function logGenerate($name = '') {
        $filterName = trim(str_replace(' ', '', $name));
        if(!empty($filterName)) $filterName = '.'.$filterName;
        $logName = date('Ymd').$filterName.'.log';
        
        return $logName;
    }
    
    
    /*
     * Method registerLogs
     * Register the users
     * 
     * @param $reg      Content to write in file log
     * @return TRUE / FALSE
     */
    public function registerLogs($reg = '') {
        $logPath = $_SERVER['DOCUMENT_ROOT'].$this->logPath;

        // Format content
        $content = PHP_EOL.date('Y/m/d H:i:s');
        if(!empty($_SERVER['REMOTE_ADDR'])) $content .= ' [REMOTE_ADDR] '.$_SERVER['REMOTE_ADDR'];
        if(!empty($_SERVER['REMOTE_HOST'])) $content .= ' [REMOTE_HOST] '.$_SERVER['REMOTE_HOST'];
        if(!empty($_SERVER['REMOTE_PORT'])) $content .= ' [REMOTE_PORT] '.$_SERVER['REMOTE_PORT'];
        if(!empty($_SERVER['REMOTE_USER'])) $content .= ' [REMOTE_USER] '.$_SERVER['REMOTE_USER'];
        if(!empty($_SERVER['HTTP_REFERER'])) $content .= ' [HTTP_REFERER] '.$_SERVER['HTTP_REFERER'];
        if(!empty($_SERVER['HTTP_USER_AGENT'])) $content .= ' [HTTP_USER_AGENT] '.$_SERVER['HTTP_USER_AGENT'];
        $content .= $reg;

        if($this->writeFile($logPath, $this->logGenerate('users'), $content))
            return true;
        
        return false;
    }
    
    
    /*
     * Method applicationLogs
     * 
     * @param $reg      Content to write in file log
     * @param $ref      Error reference
     * @return TRUE / FALSE
     */
    public function applicationLogs($reg = '', $ref = '') {
        if(!empty($reg)) {
            $logPath = $_SERVER['DOCUMENT_ROOT'].$this->logPath;

            // Format content
            $content = PHP_EOL.date('Y/m/d H:i:s');
            if(!empty($ref)) $content .= ' [ERROR REFERENCE] '.$ref;
            $serverScriptFilename = isset($_SERVER['SCRIPT_FILENAME']) ? $_SERVER['SCRIPT_FILENAME'] : '';
            if(!empty($serverScriptFilename)) $content .= ' [SCRIPT_FILENAME] '.$serverScriptFilename;
            if(is_array($reg))
                $content .= ' [] '.implode(' [] ', $reg);
            else
                $content .= ' [] '.$reg;
            
            if($this->writeFile($logPath, $this->logGenerate(), $content))
                return true;
            else
                $this->reportErrorLog = 'Failed method writeFile (#'.$logPath.'#, #'.$this->logGenerate().'#, #'.$content.'#)';
            
        } else
            $this->reportErrorLog = 'Reg is empty';
        
        $this->methodLogs('Method applicationLogs');
        return false;
    }
    
    
    /*
     * Method methodLogs
     * Only for class and methods error
     * 
     * @param $reg      Method that failed
     * @return TRUE / FALSE
     */
    protected function methodLogs($reg = '') {
        $logPath = $_SERVER['DOCUMENT_ROOT'].$this->logPath;

        // Format content
        $content = PHP_EOL.date('Y/m/d H:i:s');
        $serverScriptFilename = isset($_SERVER['SCRIPT_FILENAME']) ? $_SERVER['SCRIPT_FILENAME'] : '';
        if(!empty($serverScriptFilename)) $content .= ' [SCRIPT_FILENAME] '.$serverScriptFilename;
        $content .= ' [] '.$reg;
        $content .= ' -> '.$this->reportErrorLog;
        
        if($this->writeFile($logPath, $this->logGenerate('class'), $content))
            return true;
        
        return false;
    }
	
	
    /*
     * Method getPath
     * Gets if the received path is a valid directory
     * 
     * @param $path         Suggested directory
     * @param $create       TRUE if the directory not exist but you want create
     * @return TRUE / FALSE
     */
    public function getPath($path = '', $create = false) {
        if(!empty($path)) {
            if(!is_dir($path) && $create) {
                if(mkdir($path, 0777))
                    return true;
                else
                    $this->reportErrorLog = 'Path not id dir, failed mkdir 0777 #'.$path.'#';
                
            } elseif(is_dir($path))
                return true;
            else
                $this->reportErrorLog = 'Path not is a directory';
            
        } else
            $this->reportErrorLog = 'Path is empty';
        
        $this->methodLogs('Method getPath');
        return false;
    }
    
    
    /*
     * Method writeFile
     * Open and write in file on suggested directory. If the directory or the file not exist, create them
     * 
     * @param $path     Suggested directory
     * @param $file     File name
     * @param $content  Text to write in a file
     * @return TRUE / FALSE
     */
    public function writeFile($path = '', $file = '', $content = '') {
        if(!empty($path) && !empty($file) && !empty($content)) {
            
            // Check the directory
            if($this->getPath($path, true)) {
                $completeFile = $path.$file;
                
                // Check the file
                if(is_file($completeFile))
                    chmod($completeFile, 0777);
                
                // Open and write in file
                $pof = fopen($completeFile, 'a+');
                fwrite($pof, $content);
                fclose($pof);
                chmod($completeFile, 0644);

                return true;
            } else
                $this->reportErrorLog = 'Failed write #'.$completeFile.'#';
            
        } else
            $this->reportErrorLog = 'Path / file / content is empty';
        
        $this->methodLogs('Method writeFile');
        return false;
    }
	
	
}

?>