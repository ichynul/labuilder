<?php

namespace Ichynul\Labuilder\Logic;
use Ichynul\Labuilder\Models\Attachment;

class Upload
{
    //文件上传保存路径
    protected $path = '';
    //允许文件上传的后缀
    protected $allowSuffix = [
        //
        'jpg', 'jpeg', 'gif', 'wbmp', 'webpg', 'png', 'bmp', 'ico',
        //
        "flv", "swf", "mkv", "avi", "rm", "rmvb", "mpeg", "mpg", "ogv", "mov", "wmv", "mp4", "webm",
        //
        "ogg", "mp3", "wav", "mid",
        //
        "rar", "zip", "tar", "gz", "7z", "bz2", "cab", "iso",
        //
        "doc", "docx", "xls", "xlsx", "ppt", "pptx", "pdf", "txt", "md"];

    //允许文件上传的 Mime 类型
    protected $allowMime = ['image/jpeg', 'image/gif', 'image/wbmp', 'image/wbmp', 'image/png',
        'application/x-zip-compressed'];

    //允许文件上传的文件最大大小
    protected $maxSize = 20 * 1024 * 1024;
    //是否启用随机名
    protected $isRandName = true;

    protected $fileByDate = 0;
    //加上文件前缀
    protected $prefix = 'file';

    //自定义的错误号码和错误信息
    protected $errorNumber;
    protected $errorInfo;

    //文件的信息
    protected $oldName; //文件名
    protected $suffix; //文件后缀
    protected $size; //文件大小
    protected $mime; //文件 mime
    protected $tmpName; //文件临时路径
    protected $newName; //文件新名字
    protected $dirName;

    public function __construct($arr = [])
    {
        foreach ($arr as $key => $value) {
            $this->setOption($key, $value);
        }
    }

    public function __get($name)
    {
        if ($name == 'errorNumber') {
            return $this->errorNumber;
        } else if ($name == 'errorInfo') {
            return $this->getErrorInfo();
        }
    }

    /**
     * 判断这个$key 是不是我的成员属性，如果是，则设置
     *
     * @param [type] $key
     * @param [type] $value
     * @return void
     */
    protected function setOption($key, $value)
    {
        //得到所有的成员属性
        $keys = array_keys(get_class_vars(__CLASS__));
        if (in_array($key, $keys)) {
            $this->$key = $value;
        }
    }

    /**
     * 文件上传函数
     * $key 就是你 input 框中的 name 属性值
     *
     * @param [type] $key
     * @return boolean
     */
    public function uploadFile($key)
    {
        //判断$_FILES 里面的 error 信息是否为 0，如果为 0，说明文件信息在服务器端可以直接获取，提取信息保存到成员属性中
        $error = $_FILES[$key]['error'];
        if ($error) {
            $this->setOption('errorNumber', $error);
            return false;
        } else {
            //提取文件相关信息并且保存到成员属性中
            $this->getFileInfo($key);
        }
        //判断文件的大小、mime、后缀是否符合
        if (!$this->checkSize() /*|| !$this->checkMime() */ || !$this->checkSuffix()) {
            return false;
        }

        if (!$this->dirName) {

            $this->dirName = 'images';
            if (!in_array($this->suffix, ['jpg', 'jpeg', 'gif', 'wbmp', 'webpg', 'png', 'bmp'])) {
                $this->dirName = 'files';
            }
        }

        $scriptName = $_SERVER['SCRIPT_FILENAME'];

        $date = '';

        if ($this->fileByDate == 2) {
            $date = date('Ymd');
        } else if ($this->fileByDate == 3) {
            $date = date('Y/m');
        } else if ($this->fileByDate == 4) {
            $date = date('Y/md');
        } else if ($this->fileByDate == 5) {
            $date = date('Ym/d');
        } else if ($this->fileByDate == 6) {
            $date = date('Y/m/d');
        } else {
            $date = date('Ym');
        }

        $this->path = realpath(dirname($scriptName)) . "/uploads/{$this->dirName}/" . $date . '/';

        //判断该路径是否存在，是否可写
        if (!$this->check()) {
            $this->setOption('errorNumber', -2);
            return false;
        }

        //得到新的文件名字
        $this->newName = $this->createNewName();
        //判断是否是上传文件，并且移动上传文件
        if (is_uploaded_file($this->tmpName)) {
            if (move_uploaded_file($this->tmpName, $this->path . $this->newName)) {
                $url = "/uploads/{$this->dirName}/" . $date . '/' . $this->newName;
                $name = str_replace(['.' . $this->suffix], '', $this->oldName);
                $data = [
                    'name' => mb_substr($name, 0, 55),
                    'admin_id' => session('admin_id', 0),
                    'user_id' => session('user_id', 0),
                    'mime' => $this->mime,
                    'suffix' => $this->suffix,
                    'size' => $this->size / (1024 ** 2),
                    'sha1' => hash_file('sha1', $this->path . $this->newName),
                    'storage' => 'local',
                    'url' => $url,
                ];

                $dataModel = new Attachment;

                $res = $dataModel->fill($data)->save();

                if (!$res) {
                    return false;
                }

                return $url;
            } else {
                $this->setOption('errorNumber', -7);
                return false;
            }
        } else {
            $this->setOption('errorNumber', -6);
            return false;
        }
    }

    /**
     * 得到文件的新名字
     *
     * @return string
     */
    protected function createNewName()
    {
        //判断是否使用随机名
        if ($this->isRandName) {
            $name = $this->prefix . md5(microtime(true)) . mt_rand(1000, 9999) . '.' . $this->suffix;
        } else {
            $name = str_replace(['\\', '/', '.' . $this->suffix, '..', ' ', '.'], '', $this->oldName) . '.' . $this->suffix;
        }

        return $name;
    }

    /**
     * 判断该路径是否存在，是否可写
     *
     * @return boolean
     */
    protected function check()
    {
        //文件夹不存在或者不是目录。创建文件夹
        if (!file_exists($this->path) || !is_dir($this->path)) {
            return mkdir($this->path, 0755, true);
        }
        //判断文件是否可写
        if (!is_writeable($this->path)) {
            return chmod($this->path, 0755);
        }
        return true;
    }

    /**
     * 提取文件相关信息并且保存到成员属性中
     *
     * @param [type] $key
     * @return void
     */
    protected function getFileInfo($key)
    {
        // 得到文件名字
        $this->oldName = $_FILES[$key]['name'];
        //得到文件的 mime 类型
        $this->mime = $_FILES[$key]['type'];
        //得到文件临时路径
        $this->tmpName = $_FILES[$key]['tmp_name'];
        //得到文件大小
        $this->size = $_FILES[$key]['size'];
        //得到文件后缀
        $this->suffix = pathinfo($this->oldName)['extension'];
    }

    /**
     * 判断文件大小
     *
     * @return boolean
     */
    protected function checkSize()
    {
        if ($this->size > $this->maxSize) {
            $this->setOption('errorNumber', -3);
            return false;
        }
        return true;
    }

    /**
     * 判断文件的 mime 是否符合
     *
     * @return boolean
     */
    protected function checkMime()
    {
        if (!in_array($this->mime, $this->allowMime)) {
            $this->setOption('errorNumber', -4);
            return false;
        }
        return true;
    }

    /**
     * 判断文件的后缀是否符合
     *
     * @return boolean
     */
    protected function checkSuffix()
    {
        if (in_array(strtolower($this->suffix), ['php', 'phps', 'php5', 'php7', 'htaccess', 'cgi', 'config', 'conf', 'js', 'css', 'html', 'htm', 'exe', 'asp', 'dll', 'aspx', 'asa', 'asax', 'ascx', 'asmx', 'ashx', 'axd', 'jsp', 'jspx', 'cer', 'cdx'])) {
            $this->setOption('errorNumber', -5);
            return false;
        }
        if (!in_array($this->suffix, $this->allowSuffix)) {
            $this->setOption('errorNumber', -5);
            return false;
        }
        /* 对图像文件进行严格检测 */
        if (in_array(strtolower($this->suffix), ['gif', 'jpg', 'jpeg', 'bmp', 'png', 'swf']) && !in_array($this->getImageType($this->tmpName), [1, 2, 3, 4, 6, 13])) {
            $this->setOption('errorNumber', -5);
            return false;
        }

        return true;
    }

    // 判断图像类型
    protected function getImageType($image)
    {
        if (function_exists('exif_imagetype')) {
            return exif_imagetype($image);
        }

        try {
            $info = getimagesize($image);
            return $info ? $info[2] : false;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * 返回错误信息
     *
     * @return string
     */
    protected function getErrorInfo()
    {
        switch ($this->errorNumber) {
            case -1:
                $str = '文件路径没有设置';
                break;
            case -2:
                $str = '文件路径不是目录或者没有权限';
                break;
            case -3:
                $str = '文件大小超过指定范围';
                break;
            case -4:
                $str = '文件 mime 类型不符合';
                break;
            case -5:
                $str = '文件后缀不符合';
                break;
            case -6:
                $str = '不是上传文件';
                break;
            case -7:
                $str = '文件上传失败';
                break;
            case 1:
                $str = '文件超出 php.ini 设置大小';
                break;
            case 2:
                $str = '文件超出 html 设置大小';
                break;
            case 3:
                $str = '文件部分上传';
                break;
            case 4:
                $str = '没有文件上传';
                break;
            case 6:
                $str = '找不到临时文件';
                break;
            case 7:
                $str = '文件写入失败';
                break;
        }
        return $str;
    }
}
