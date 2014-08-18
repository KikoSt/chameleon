<?php
/**
 * Created by IntelliJ IDEA.
 * User: thomas
 * Date: 05.08.14
 * Time: 15:23
 */

class SvgFileHandler
{
    private $filename;
    private $svgContent;
    private $type;

    public function save()
    {
        // write the temporary file
        if(is_dir(SVG_DIR))
        {
            $fh = fopen(SVG_DIR . $this->getFilename(), 'w');
            if(!$fh)
            {
                throw new Exception('Could not write to file ' . SVG_DIR . $this->getFilename());
            }
            fwrite($fh, $this->getSvgContent());
            fclose($fh);
        }
        else
        {
            throw new Exception(SVG_DIR . ' not found !');
        }
    }

    public function delete()
    {
        if(!unlink($this->getFilename()))
        {
            throw new FileNotFoundException();
        }
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getSvgContent()
    {
        return $this->svgContent;
    }

    /**
     * @param mixed $svgContent
     */
    public function setSvgContent($svgContent)
    {
        $this->svgContent = $svgContent;
    }

    /**
     * @return mixed
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param mixed $filename
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }


}
