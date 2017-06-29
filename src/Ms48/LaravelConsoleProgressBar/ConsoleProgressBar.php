<?php

namespace Ms48\LaravelConsoleProgressBar;

/**
 * Show progress bar in the console
 *
 * @author Shanuka Dilshan <https://github.com/ms48>
 * @license MIT License
 */
class ConsoleProgressBar
{

    /**
     * Total completed count.
     *
     * @var int
     */
    protected $currentCount;
    
    /**
     * Max length of the line.
     *
     * @var int
     */
    protected $maxLength;
    
    /**
     * Progress start time.
     *
     * @var \Datetime
     */
    protected $startTime;

    /**
     * Create a new console Progress Bar.
     *     
     * @return void
     */
    public function __construct()
    {
        $this->resetValues();
    }
    
    /**
     * Reset the values to default;
     */
    protected function resetValues()
    {
        $this->currentCount=0;
        $this->maxLength=0;
        $this->startTime=0;
    }
    
    /**
     * Convert seconds to readable format   
     *
     * @param   string  $seconds   how many seconds you want to convert
     * @return  string
     */
    protected function convertSecondsToHMS($seconds) 
    {
         // extract hours
        $hours = (floor($seconds / (60 * 60))>0)?floor($seconds / (60 * 60))."h ":"";

        // extract minutes
        $divisor_for_minutes = $seconds % (60 * 60);
        $minutes = (floor($divisor_for_minutes / 60)>0)?floor($divisor_for_minutes / 60)."min ":"";

        // extract the remaining seconds
        $divisor_for_seconds = $divisor_for_minutes % 60;
        $sec = ceil($divisor_for_seconds)."sec";

        //create string
        return $hours.$minutes.$sec;
    }
    
    /**
     * When current line length is lesser than to the previous one, 
     * we should fill the remaining characters with spaces.
     *
     * @param   string text  Status bar text
     * @return  string     
     */
    protected function maintainLength($text)
    {
        $strLength = strlen($text);
        if($this->maxLength > $strLength){
            //fill spaces to extra length
            $text .=  str_repeat(" ", $this->maxLength - $strLength);
        }else{
            $this->maxLength = $strLength;
        }
        return $text;
    }
    
    /**
     * Generate progress bar   
     *
     * @param   int     $total   how many items are to be done total
     * @param   int     $size  size of the status bar
     * @param   int     $elapsed   elapsed time in sec
     * @return  string     
     */
    protected function showCompletedBar($total, $size, $elapsed)
    {
        $statusBar = "\r[".str_repeat("=", $size+1) 
                     . "] 100% $total/$total remaining: 0 sec. "
                     . "elapsed: " . $this->convertSecondsToHMS($elapsed) . "\n";
        
        return $this->maintainLength($statusBar);
    }        
    
    /**
     * Generate progress bar   
     *
     * @param   int     $done   how many items are completed
     * @param   int     $total  how many items are to be done total
     * @param   int     $size   optional size of the status bar
     * @return  void     
     */
    protected function generateProgress($done, $total, $size = 30){
        //if empty start time, get currunt time as start time
        if (empty($this->startTime)){
            $this->startTime = time();
        }    
        
        $now = time();
        $elapsed = $now - $this->startTime;

        // if we go over our bound or finished the process, display 100% and just ignore it
        if ($done >= $total){
            echo $this->showCompletedBar($total, $size, $elapsed);
            $this->resetValues();
            return;
        }  
        
        //calculate the precentage
        $perc = (double) ($done / $total);
        $bar = floor($perc * $size);
        
        $disp = number_format($perc * 100, 0);

        //make the status bar
        $statusBar = "\r[";
        $statusBar .= str_repeat("=", $bar);
        if ($bar < $size) {
            $statusBar .= ">";
            $statusBar .= str_repeat(" ", $size - $bar);
        } else {
            $statusBar .= "=";
        }

        $statusBar .= "] $disp%  $done/$total";

        //calculate the ETA
        $rate = ($now - $this->startTime) / $done;
        $left = $total - $done;
        $eta = round($rate * $left);

        $statusBar .= " remaining: " . $this->convertSecondsToHMS($eta) . " elapsed: " . $this->convertSecondsToHMS($elapsed);
        //print the status bar
        echo $this->maintainLength($statusBar);
    }
    
    /**
     * Generate a current progress output. We should take care of the looping
     * trough the collection.
     *
     * @param   int     $limit  data block size
     * @param   int     $total  how many items are to be done total
     * @param   int     $size   optional size of the status bar
     * @return  void
     */
    public function showProgress($limit, $total, $size = 30){
        $this->currentCount += $limit;
        $this->generateProgress($this->currentCount, $total, $size);
    }
}