<?php
namespace Ms48\LaravelConsoleProgressBar;

/**
 * Show status bar in the console
 *
 * @author Shanuka Dilshan
 */
class ConsoleProgressBar {

    private $currentCount;
    
    public function __construct() {
        $this->currentCount=0;
    }
    
    /**
     * Generate status bar   
     *
     * @param   int     $done   how many items are completed
     * @param   int     $total  how many items are to be done total
     * @param   int     $size   optional size of the status bar
     * @return  void
     *
     */
    private function show_status($done, $total, $size = 30) 
    {
        static $start_time;
        
        //if empty start time, get currunt time as start time
        if (empty($start_time))
            $start_time = time();
        
        $now = time();
        $elapsed = $now - $start_time;

        // if we go over our bound or finished the process, display 100% and just ignore it
        if ($done >= $total){
            $status_bar = "\r[".str_repeat("=", $size+1) ."] 100%  $total/$total remaining: 0 sec.  elapsed: " . $this->convertSecondsToHMS($elapsed) . "\n";
            echo "$status_bar  ";
            $this->currentCount = 0;
            $start_time = 0;
            return;
        }  

        $perc = (double) ($done / $total);
        $bar = floor($perc * $size);

        $status_bar = "\r[";
        $status_bar .= str_repeat("=", $bar);
        if ($bar < $size) {
            $status_bar .= ">";
            $status_bar .= str_repeat(" ", $size - $bar);
        } else {
            $status_bar .= "=";
        }

        $disp = number_format($perc * 100, 0);

        $status_bar .= "] $disp%  $done/$total";

        $rate = ($now - $start_time) / $done;
        $left = $total - $done;
        $eta = round($rate * $left, 2);

        $status_bar .= " remaining: " . $this->convertSecondsToHMS($eta) . " elapsed: " . $this->convertSecondsToHMS($elapsed);
        
        //print the status bar
        echo $status_bar;
    }
    
    /**
     * Show a progress with limit in the console
     *
     * @param   int     $limit  data block size
     * @param   int     $total  how many items are to be done total
     * @param   int     $size   optional size of the status bar
     * @return  void
     *
     */
    public function showProgress($limit, $total, $size = 30){
        $this->currentCount=$this->currentCount+$limit;
        $this->show_status($this->currentCount, $total, $size);
    }
    
    /**
     * Convert seconds to readable format   
     *
     * @param   string  $seconds   how many seconds you want to convert
     * @return  string
     *
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
}