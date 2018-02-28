<?php
/**
 * Tranquilo/parsecsv
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 *
 * @license http://opensource.org/licenses/Apache-2.0 Apache 2.0 License (Frank Mckechnie)
 */
 
namespace Tranquilo;

use Tranquilo\Exceptions\CsvException;

class ParseCsv
{
    public $delimiter;

    public $encodingTypes = array(
        "UTF-8",
        "UTF-32",
        "UTF-32BE",
        "UTF-32LE",
        "UTF-16",
        "UTF-16BE",
        "UTF-16LE"
    );

    protected $file;
    protected $fileName;
    protected $header;
    protected $data = array();
    protected $rowCount = 0;
    protected $encoding = "UTF-8";
    protected $fileExists = false; 
     

    public function __construct(string $fileName = '', bool $header = false, string $delimiter = ',')
    {
        if (trim($fileName) != '') {
            $this->fileName = $fileName;
            $this->delimiter = $delimiter;
            $this->header = $header;
            $this->fileReadsAndExists();
        }

    }

    public function __destruct() 
    { 
        if ($this->file) 
        {  
            fclose($this->file); 
        } 
    } 

    public function fileReadsAndExists()
    {
        if (!isset($this->fileName)) {
            throw new CsvException("File name not set!");
            return false;
        }

        if(!file_exists($this->fileName) || !is_readable($this->fileName)){
            throw new CsvException("File does not exist or is not readable!");
            return false;
        }
        
        $this->fileExists = true;

        return true;
    }

    public function parse(int $max_lines = 0, int $offset = 0)
    {
        if(!$this->fileExists){
            return false;
        }

        $this->reset();


        $this->file = (isset($this->file)) ? $this->file : fopen($this->fileName, 'r');

        if ($max_lines > 0) {
            $line_count = 0; 
        }else {
            $line_count = -1; 
        }

        while ($line_count < $max_lines && !feof($this->file)) {
            
            $row = fgetcsv($this->file, 0, $this->delimiter);
            
            if ($row == [null] || $row === false) {
                continue;
            }

            if (!$this->header) {
                $this->header = $row;
            } else {
                $this->data[] = array_combine($this->header, $row);
                $this->row_count ++;
            }

            if ($max_lines > 0){ 
                $line_count++; 
            }

        }

        //fclose($file);
        
        return $this->data;
    }

    public function convertEncoding(string $type = 'UTF-8') 
    { 
        if(!$this->fileExists){
            throw new CsvException("File does not exist or is not readable!");
            return false;
        }

        if(isset($this->file)){
            throw new CsvException("Must not parse then convert!");
            return false;
        }

        $this->encoding = $type;

        $data = file_get_contents( $this->fileName );
        
        $encodingType = mb_detect_encoding( $data, $this->encodingTypes, TRUE );

        if( $encodingType !== "UTF-8" ) {
            $data = mb_convert_encoding( $data, $this->encoding, $encodingType );
        }

        $handle = fopen("php://memory", "rw"); 

        fwrite($handle, $fc); 

        fseek($handle, 0);

        $this->file = $handle;

        return true; 
    } 

    public function lastResults(){
        return $this->data; 
    }

    public function getRowCount()
    {
        return $this->$row_count;
    }

    public function getFile()
    {
        return $this->file;
    }

    private function reset()
    {
        $this->header = null;
        $this->data = [];
        $this->row_count = 0;
    }
}
