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
    private $currentCount;
    
    /**
     * Max length of the line.
     *
     * @var int
     */
    private $maxLength;
    
    /**
     * Progress start time.
     *
     * @var \Datetime
     */
    private $startTime;

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
    private function resetValues()
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
    private function convertSecondsToHMS($seconds) 
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
     * When current line length lesser than the previous one, we should fill the
     * remaining characters with spaces.
     *
     * @param   string text  Status bar text
     * @return  string     
     */
    private function maintainLength($text)
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
     * Generate status bar   
     *
     * @param   int     $done   how many items are completed
     * @param   int     $total  how many items are to be done total
     * @param   int     $size   optional size of the status bar
     * @return  void     
     */
    private function showStatus($done, $total, $size = 30){
        //if empty start time, get currunt time as start time
        if (empty($this->startTime)){
            $this->startTime = time();
        }    
        
        $now = time();
        $elapsed = $now - $this->startTime;

        // if we go over our bound or finished the process, display 100% and just ignore it
        if ($done >= $total){
            $statusBar = "\r[".str_repeat("=", $size+1) ."] 100% $total/$total remaining: 0 sec. elapsed: " .
                                            $this->convertSecondsToHMS($elapsed) . "\n";
            echo $this->maintainLength($statusBar);
            $this->resetValues();
            return;
        }  

        $perc = (double) ($done / $total);
        $bar = floor($perc * $size);

        $statusBar = "\r[";
        $statusBar .= str_repeat("=", $bar);
        if ($bar < $size) {
            $statusBar .= ">";
            $statusBar .= str_repeat(" ", $size - $bar);
        } else {
            $statusBar .= "=";
        }

        $disp = number_format($perc * 100, 0);

        $statusBar .= "] $disp%  $done/$total";

        $rate = ($now - $this->startTime) / $done;
        $left = $total - $done;
        $eta = round($rate * $left);

        $statusBar .= " remaining: " . $this->convertSecondsToHMS($eta) . " elapsed: " . $this->convertSecondsToHMS($elapsed);
        //print the status bar
        echo $this->maintainLength($statusBar);
    }
    
    /**
     * Show a progress with limit in the console
     *
     * @param   int     $limit  data block size
     * @param   int     $total  how many items are to be done total
     * @param   int     $size   optional size of the status bar
     * @return  void
     */
    public function showProgress($limit, $total, $size = 30){
        $this->currentCount += $limit;
        $this->showStatus($this->currentCount, $total, $size);
    }
}