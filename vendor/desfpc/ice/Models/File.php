<?php
/**
 * iceCMS v0.1
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 *
 * File Class
 *
 */

namespace ice\Models;

use ice\DB\DB;

/**
 * Class File
 * @package ice\Models
 */
class File extends Obj
{
    /**
     * File constructor.
     *
     * @param DB $DB
     * @param null $id
     * @param null $settings
     */
    public function __construct(DB $DB, $id = null, $settings = null)
    {
        $this->doConstruct($DB, 'files', $id, $settings);
    }

    /**
     * Формирование фавиконки для файла
     *
     * @param $DB
     * @param $file
     * @param false $link
     * @param false $ifArray
     * @return array|string
     */
    public static function formatIcon($DB, $file, $link = false, $ifArray = false)
    {

        $fileObj = new File($DB, $file['id']);
        $fileObj->getRecord();
        $file = $fileObj->params;

        if ($file['filetype'] == 'image') {
            //visualijoper\visualijoper::visualijop($file, $fileObj);
            $icon = '<img src="' . $fileObj->getFileCacheUrl(48, 48) . '" />';
        } else {
            $icon = '<i class="material-icons md-48 md-dark">insert_drive_file</i>';
        }


        //вешаем на иконку ссылку
        if ($link) {
            if ($file['filetype'] == 'image') {
                $icon = '<a href="' . $fileObj->getFileCacheUrl(800, 0) . '" data-toggle="lightbox">' . $icon . '</a>';
            } else {
                $icon = '<a href="' . $fileObj->getFileUrl() . '">' . $icon . '</a>';
            }
        }

        if ($ifArray) {
            return ['icon' => $icon, 'link' => $fileObj->getFileCacheUrl(800, 0)];
        }

        return $icon;

    }

    /**
     * формирование строки URL файла кэша по размерам (ширина, высота)
     *
     * @param $x
     * @param $y
     * @return string
     */
    public function getFileCacheUrl($x, $y): string
    {
        $folder = $x . 'x' . $y;
        $dirpatch = $this->params['url'] . $folder . '/';
        if ($this->params['extension'] != '') {
            return $dirpatch . '/' . $this->id . '.' . $this->params['extension'];
        }
        return $dirpatch . '/' . $this->id;
    }

    /**
     * формирование строки URL оригинала файла
     *
     * @return string
     */
    public function getFileUrl(): string
    {
        $dirpatch = $this->params['url'];
        if ($this->params['extension'] != '') {
            return $dirpatch . '/' . $this->id . '.' . $this->params['extension'];
        }
        return $dirpatch . '/' . $this->id;
    }

    /**
     * форматированная строка размера файла
     *
     * @param int $size
     * @return string
     */
    public static function formateSize($size): string
    {

        $size = (int)$size;

        if ($size < 1024) {
            $size .= 'б';
        } elseif ($size < (1024 * 1024)) {
            $size = '<strong>' . round($size / 1024, 1) . '</strong>Кб';
        } elseif ($size < (1024 * 1024 * 1024)) {
            $size = '<strong>' . round($size / (1024 * 1024), 1) . '</strong>Мб';
        } else {
            $size = '<strong>' . round($size / (1024 * 1024 * 1024), 1) . '</strong>Гб';
        }

        return $size;

    }

    /**
     * Загрузка файла на сервер, возвращает false либо id файла
     *
     * @param $paramName
     * @param string $type
     * @param false $private
     * @param null $userId
     * @param false $materialConnect
     * @return bool|int
     */
    public function upload($paramName, $type = 'file', $private = false, $userId = null, $materialConnect = false)
    {

        if ($paramName != '') {
            if (isset($_FILES[$paramName])) {

                $tmp_name = $_FILES[$paramName]["tmp_name"];
                $name = $_FILES[$paramName]['name'];
                $extension = File::getFileExtension($name);
                $size = $_FILES[$paramName]['size'];
                list($width, $height, $imgtype, $attr) = getimagesize($tmp_name);

                if (is_null($imgtype)) {
                    $imgtype = false;
                }

                if ($type == 'image' && !$imgtype) {
                    $this->errors[] = 'Переданный файл не является изображением, либо его формат не поддерживается';
                    return false;
                }

                if ($type == 'auto' && $imgtype) {
                    $type = 'image';
                } elseif ($type == 'auto') {
                    $type = 'file';
                }

                //если тип - image, проверяем расширение
                if ($type == 'image') {

                    switch ($imgtype) {
                        case '2':
                            $extension = 'jpg';
                            break;
                        case '3':
                            $extension = 'png';
                            break;
                        case '1':
                            $extension = 'gif';
                            break;
                        default:
                            $this->errors[] = 'Переданный файл не является изображением, либо его формат не поддерживается';
                            return false;
                            break;
                    }

                }

                $url = '/files/';
                if ($private) {
                    $url .= 'private/';
                }

                $url .= date('Ym') . '/';
                $dirpatch = $this->settings->path . '/web' . $url;
                //visualijoper\visualijoper::visualijop($dirpatch);


                if (!is_dir($dirpatch)) {
                    mkdir($dirpatch, 0750);
                }

                //TODO изменение параметров из POST запроса - в разработке
                //$this->params = array_merge($this->params,$this->paramsFromPost());

                if ($private) {
                    $privateInt = 1;
                } else {
                    $privateInt = 2;
                }

                //создаем запись в бд
                $this->params = [
                    'id' => null,
                    //'name' => '',
                    'filename' => $name,
                    //'anons' => '',
                    //'date_add' => '',
                    //'date_edit' => '',
                    'user_id' => $userId,
                    'status_id' => 2,
                    'filetype' => $type,
                    'extension' => $extension,
                    'url' => $url,
                    'size' => $size,
                    'image_width' => $width,
                    'image_height' => $height,
                    'private' => $privateInt
                ];

                if (!isset($this->params['name'])) {
                    $this->params['name'] = $name;
                }
                if (!isset($this->params['anons'])) {
                    $this->params['anons'] = '';
                }

                //пробуем сделать запись в БД
                if ($id = $this->createRecord()) {

                    //физически копируем файл
                    $filename = $dirpatch . $id;
                    if ($extension != '') {
                        $filename .= '.' . $extension;
                    }

                    if (move_uploaded_file($tmp_name, $filename)) {

                        //создаём кэши изображений
                        $this->createImageCaches();

                        //если все хорошо - связываем файл с материалом
                        if ($materialConnect) {
                            $query = 'INSERT INTO material_files(file_id, material_id, ordernum) VALUES(' . $id . ', ' . $materialConnect . ', NULL)';
                            $res = $this->DB->query($query);
                        }

                        return $id;
                    }

                } else {
                    $this->errors[] = 'Ошибка сохранения записи о файле';
                    return false;
                }

                //visualijoper\visualijoper::visualijop($tmp_name, $extension, $name, $size, $width, $height, $imgtype, $attr);
                //visualijoper\visualijoper::visualijop($this->settings);


            } else {
                $this->errors[] = 'Файл не передан';
                return false;
            }
        }
        $this->errors[] = 'Нет файловой переменной';
        return false;

    }

    /**
     * получение расширения файла
     *
     * @param $filename
     * @return mixed|string
     */
    public static function getFileExtension($filename)
    {
        $path_info = pathinfo($filename);
        if (!isset($path_info['extension'])) {
            return ('');
        }
        return $path_info['extension'];
    }

    /**
     * созджание кэшей файла-изображения по настройкам (ширина, высота ...)
     *
     * @return bool|int
     */
    public function createImageCaches()
    {

        if (isset($this->params['filetype']) && $this->params['filetype'] == 'image') {

            //список кэшей для изображения
            $imageCaches = new ImageCacheList($this->DB, null, null, 1, 1000);
            $imageCaches = $imageCaches->getRecords();

            if (is_array($imageCaches) && count($imageCaches) > 0) {

                foreach ($imageCaches as $cache) {
                    $this->createImageCache($cache);
                }

            }

        }

        return false;
    }

    /**
     * создание конкретного кэша изображения
     *
     * @param $cache
     */
    public function createImageCache($cache)
    {

        $nw = $cache['width'];
        $nh = $cache['height'];
        $watermark = $cache['watermark'];
        $wx = $cache['w_x'];
        $wy = $cache['w_y'];

        $this->SaveImageSize($nw, $nh, $this->params['extension'], 1, $watermark, $wx, $wy);

    }

    /**
     * сохранение файла изображения с заданными размерами и форматом
     *
     * @param $newx
     * @param $newy
     * @param $extension
     * @param int $crop
     * @param int $watermark
     * @param int $wx
     * @param int $wy
     */
    public function SaveImageSize($newx, $newy, $extension, $crop = 0, $watermark = 0, $wx = 0, $wy = 0)
    {

        $from = $this->getFilePath(); // файл оригинал
        $to = $this->getFileCachePath($newx, $newy); // файл кэша

        //определение размеров
        $originalx = $this->params['image_width'];
        $originaly = $this->params['image_height'];
        if ($newx == 0) {
            $newx = round($originalx * $newy / $originaly);
        } elseif ($newy == 0) {
            $newy = round($originaly * $newx / $originalx);
        }


        //echo $from;
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                $im = imagecreatefromjpeg($from);
                $im1 = imagecreatetruecolor($newx, $newy);
                break;
            case 'png':
                $im = imagecreatefrompng($from);
                $im1 = imagecreatetruecolor($newx, $newy);
                imagealphablending($im1, false);
                imagesavealpha($im1, true);
                break;
            case 'gif':
                $im = imagecreatefromgif($from);
                $im1 = imagecreatetruecolor($newx, $newy);
                break;
        }

        if ($crop == 0) {
            imagecopyresampled($im1, $im, 0, 0, 0, 0, $newx, $newy, imagesx($im), imagesy($im));
        } else {

            //просчитываем с какой стороны обрезать (формируем переменные для обрезания)
            $sootn1 = $newx / $newy;
            $sootn2 = imagesx($im) / imagesy($im);

            //режим по x
            if ($sootn1 >= $sootn2) {
                $ix = imagesx($im);
                $iy = round($newy * imagesx($im) / $newx);
            } else {

                $iy = imagesy($im);
                $ix = round($newx * imagesy($im) / $newy);

            }

            //смещения
            $startx = (int)((imagesx($im) - $ix) / 2);
            //$starty=(int)((imagesy($im)-$iy)/2);
            $starty = 0;

            imagecopyresampled($im1, $im, 0, 0, $startx, $starty, $newx, $newy, $ix, $iy);

        }

        //наносим watermark
        if ($watermark > 0) {

            $wimg = new File($this->DB, $watermark);
            $stamp = imagecreatefrompng($wimg->getFilePath());

            $sx = imagesx($stamp);
            $sy = imagesy($stamp);

            imagecopy($im1, $stamp, imagesx($im1) - $sx - $wx, imagesy($im1) - $sy - $wy, 0, 0, imagesx($stamp), imagesy($stamp));

        }

        imagejpeg($im1, $to, 100);

    }

    /**
     * получение полного пути к файлу в ОС
     *
     * @return string
     */
    public function getFilePath(): string
    {

        if ($this->params['extension'] != '') {
            return $this->settings->path . '/web' . $this->params['url'] . $this->id . '.' . $this->params['extension'];
        }
        return $this->settings->path . '/web' . $this->params['url'] . $this->id;

    }

    /**
     * получение полного пути в ОС к файлу - кэшу изображения по его размерам
     *
     * @param $x
     * @param $y
     * @return string
     */
    public function getFileCachePath($x, $y): string
    {

        $folder = $x . 'x' . $y;

        $dirpatch = $this->settings->path . '/web' . $this->params['url'] . $folder . '/';
        //visualijoper\visualijoper::visualijop($dirpatch);

        if (!is_dir($dirpatch)) {
            mkdir($dirpatch, 0750);
        }

        if ($this->params['extension'] != '') {
            return $this->settings->path . '/web' . $this->params['url'] . $folder . '/' . $this->id . '.' . $this->params['extension'];
        }
        return $this->settings->path . '/web' . $this->params['url'] . $folder . '/' . $this->id;

    }

}