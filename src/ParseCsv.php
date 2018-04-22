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
 * @license http://opensource.org/licenses/Apache-2.0 Apache 2.0 License Frank Mckechnie
 */
 
namespace Tranquilo;

use Tranquilo\Exceptions\CsvException;

class ParseCsv
{
    /**
     * String delimiter which is used to format the csv
     * 
     * @var string
     */
    public $delimiter;

    /**
     * Set of encoding types to check against
     * 
     * @var array
     */
    public $encodingTypes = array(
        "UTF-8",
        "UTF-32",
        "UTF-32BE",
        "UTF-32LE",
        "UTF-16",
        "UTF-16BE",
        "UTF-16LE"
    );

    /**
     * binded to a file pointer resource
     * 
     * @var resource
     */
    protected $file;

    /**
     * The location of the file
     * 
     * @var string
     */
    protected $fileName;

    /**
     * 
     * @var null
     */
    protected $header = null;

    /**
     * [$data description]
     * @var array
     */
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
        $this->closeFile();
    }

    public function fileReadsAndExists()
    {
        if (!isset($this->fileName)) {
            throw new CsvException("File name not set!");
            return false;
        }

        if (!file_exists($this->fileName) || !is_readable($this->fileName)) {
            throw new CsvException("File does not exist or is not readable!");
            return false;
        }
        
        $this->fileExists = true;

        return true;
    }

    public function convertEncoding(string $type = 'UTF-8')
    {
        if (!$this->fileExists) {
            throw new CsvException("File does not exist or is not readable!");
            return false;
        }

        if (isset($this->file)) {
            throw new CsvException("Must not parse then convert!");
            return false;
        }

        $this->encoding = $type;

        $data = file_get_contents($this->fileName);

        $encodingType = mb_detect_encoding($data, $this->encodingTypes, true);

        if ($encodingType !== $this->encoding) {
            $data = mb_convert_encoding($data, $this->encoding, $encodingType);
        }

        $handle = fopen("php://memory", "rw");

        fwrite($handle, $data);

        fseek($handle, 0);

        $this->file = $handle;

        return true;
    }

    public function get(int $max_lines = 0, int $offset = 0) 
    {
        return $this->parse($max_lines, $offset);
    }

    public function getWithOffset(int $offset  = 0) 
    {
        return $this->parse(0, $offset);
    }

    public function lastResults()
    {
        return $this->data;
    }

    public function getRowCount()
    {
        return $this->$row_count;
    }

    public function closeFile()
    {
        return isset($this->file) ? fclose($this->file) : false;
    }

    public function getFile()
    {
        return $this->file;
    }

    protected function parse(int $max_lines = 0, int $offset = 0)
    {
        if (!$this->fileExists) {
            return false;
        }

        $this->reset();

        $this->file = (isset($this->file)) ? $this->file : fopen($this->fileName, 'r');

        if ($max_lines > 0) {
            $line_count = 0;
            $max_lines = $max_lines + 1 + $offset;
        } else {
            $line_count = -1;
        }

        while ($line_count < $max_lines && !feof($this->file)) {
            
            $row = fgetcsv($this->file, 0, $this->delimiter);

            if (!$this->header) {
                $this->header = $row;
            } else {

                if ($offset > 0) {
                    $row = null;
                    $offset--;
                }

                if ($row == [null] || $row === false) {
                    continue;
                }

                $this->data[] = array_combine($this->header, $row);
                $this->row_count ++;
            }

            if ($max_lines > 0) {
                $line_count++;
            }

        }

        return $this->data;
    }   

    private function reset()
    {
        $this->data = [];
        $this->row_count = 0;
    }
}
