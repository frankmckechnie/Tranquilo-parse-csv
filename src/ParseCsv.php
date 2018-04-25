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
     * tells parse if the file has been converted
     * 
     * @var boolean
     */
    protected $converted = false;

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
     * Used to set the first row of the csv
     *
     * @var null
     */
    protected $header = null;

    /**
     * array format of the csv
     *
     * @var array
     */
    protected $data = array();

    /**
     * count of all rows within the csv
     *
     * @var array
     */
    protected $rowCount = 0;

    /**
     * Type of encoding the file is to be set to
     *
     * @var string
     */
    protected $encoding = "UTF-8";

    /**
     * settable var to be set wehn the file exisits
     *
     * @var boolean
     */
    protected $fileExists = false;
    
    /**
     * create new hook to file
     *
     * @param string    $filename The path to the file
     * @param string    $delimiter The delimiter for parsing the csv
     */
    public function __construct(string $fileName = null, string $delimiter = ',')
    {
        $this->fileName = $fileName;
        $this->delimiter = $delimiter;
        $this->fileReadsAndExists();
    }

    /**
     * checks to see if the file reads and exists
     *
     * @return bool
     */
    public function fileReadsAndExists()
    {
        if (!isset($this->fileName)) {
            throw new CsvException("File name not set!" . " filepath:" . $this->fileName);
            return false;
        }

        if (!file_exists($this->fileName) || !is_readable($this->fileName)) {
            throw new CsvException("File does not exist or is not readable!"." filepath:" . $this->fileName);
            return false;
        }
        
        $this->fileExists = true;

        return true;
    }

    /**
     * converts the current encoding
     *
     * @param  string $type sets the type of encoding
     *
     * @return bool
     */
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
        $this->converted = true;

        return true;
    }

    /**
     * calls the parse fucntion with two params
     *
     * @param  int|integer $max_lines sets the max amount of rows returned
     * @param  int|integer $offset sets the starting point of looping
     *
     * @return array
     */
    public function get(int $max_lines = 0, int $offset = 0)
    {
        return $this->parse($max_lines, $offset);
    }

    /**
     * gets all rows with just an offset
     *
     * @param  int|integer $offset sets the starting point of looping
     *
     * @return array
     */
    public function getWithOffset(int $offset = 0)
    {
        return $this->parse(0, $offset);
    }

    /**
     * retuns the last results
     *
     * @return array
     */
    public function lastResults()
    {
        return $this->data;
    }

    /**
     * returns the current row count
     *
     * @return integer
     */
    public function getRowCount()
    {
        return $this->rowCount;
    }

    /**
     * Parses the csv
     *
     * @param  int|integer $max_lines sets the max amount of rows returned
     * @param  int|integer $offset sets the starting point of looping
     *
     * @return [type]
     */
    protected function parse(int $max_lines = 0, int $offset = 0)
    {
        if (!$this->fileExists) {
            return false;
        }

        $this->reset();

        $this->file = ($this->converted) ? $this->file : fopen($this->fileName, 'r');

        if ($max_lines > 0) {
            $line_count = 0;
            $max_lines = $max_lines + $offset;
        } else {
            $line_count = -1;
        }

        while ($line_count < $max_lines && !feof($this->file)) {

            $row = fgetcsv($this->file, 0, $this->delimiter);

            if (!$this->header) {
                $this->header = $row;
            } else {

                if ($offset > 0) {
                    $row = false;
                    $offset--;
                }

                if ($row != [null] && $row != false) {
                    $this->data[] = array_combine($this->header, $row);
                    $this->rowCount ++;
                }

                if ($max_lines > 0) {
                    $line_count++;
                }
            }
        }

        fclose($this->file);

        return $this->data;
    }

    /**
     * resets the values when $this->parse is called
     *
     * @return void
     */
    private function reset()
    {
        $this->data = [];
        $this->rowCount = 0;
        $this->header = null;
    }
}
