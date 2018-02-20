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

class ParseCsv
{

    public static $delimiter = ",";

    public $encodingTypes = array(
        "UTF-8",
        "UTF-32",
        "UTF-32BE",
        "UTF-32LE",
        "UTF-16",
        "UTF-16BE",
        "UTF-16LE"
    );

    private $fileName;
    private $header;
    private $data = [];
    private $row_count = 0;
    private $encoding = "UTF-8";

    public function __construct($fileName = '', $encoding = '')
    {
        if ($fileName != '') {
            $this->fileName = $fileName;
        }

    }

    public function file(string $fileName)
    {
        if (file_exists($fileName)) {
            echo "File does not exists";
            return false;
        } elseif (!is_readable($filename)) {
            echo "File is not readable";
            return false;
        }
    
        $this->fileName = $fileName;
        return true;
    }

    public function getEncoding()
    {
        $data = file_get_contents( $this->fileName );
        
        $encodingType = mb_detect_encoding( $data, $this->encodingTypes, TRUE );

        return $encodingType;
    }

    public function convertEncoding($fileName) 
    { 

        if( $encoding !== "UTF-8" ) {
            $data = mb_convert_encoding( $data, $this->encoding, $encodingType );
        }

        return $data;


        $input = mb_convert_encoding( $input, "UTF-8", $encoding );
        $fc = iconv('windows-1250', 'utf-8', file_get_contents($fileName)); 
        $handle = fopen("php://memory", "rw"); 
        fwrite($handle, $fc); 
        fseek($handle, 0);
        return $handle; 
    } 

    public function parse()
    {
        if (!isset($this->fileName)) {
            echo "File not set!";
            return false;
        }

        // clear results
        $this->reset();

        $file = fopen($this->fileName, 'r');
        while (!feof($file)) {
            $row = fgetcsv($file, 0, static::$delimiter);
            if ($row == [null] || $row === false) {
                continue;
            }
            if (!$this->header) {
                $this->header = $row;
            } else {
                $this->data[] = array_combine($this->header, $row);
                $this->row_count ++;
            }
        }
        fclose($file);
        return $this->data;
    }

    public function getRowCount()
    {
        return $this->$row_count;
    }


    private function reset()
    {
        $this->header = null;
        $this->data = [];
        $this->row_count = 0;
    }
}
