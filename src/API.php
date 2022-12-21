<?php

namespace ClassicO\NovaMediaLibrary;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use ClassicO\NovaMediaLibrary\Core\{
    Crop,
    Helper,
    Model,
    Upload
};

class API
{
    /**
     * Upload file by path\url
     *
     * @param string $path - path|url of file
     * @param string|null $folder - where store file if use `store` = 'folders'
     * @throws \Exception
     * @return true
     */
    public static function upload($path, $folder = null)
    {
        try {
            $base = basename(parse_url(($path), PHP_URL_PATH));
            $stream = fopen($path, 'r');
            Storage::disk('local')->writeStream('nml_temp/' . $base, $stream);
            fclose($stream);

            $file = new UploadedFile(storage_path('app/nml_temp/' . $base), $base);

            if (! $file) {
                throw new \Exception(__('The file was not downloaded for unknown reasons'), 0);
            }

            $upload = new Upload($file);

            if (! $upload->setType()) {
                throw new \Exception(__('Forbidden file format'), 1);
            }

            $upload->setWH();

            $upload->setFolder($folder);

            $upload->setPrivate();

            $upload->setFile();

            if (! $upload->checkSize()) {
                throw new \Exception(__('File size limit exceeded'), 2);
            }

            $item = $upload->save();

            if ($item) {
                Crop::createSizes($item);

                return $item;
            }

            throw new \Exception(__('The file was not downloaded for unknown reasons'), 0);
        } finally {
            Storage::disk('local')->delete('nml_temp/' . $base);
        }
    }

    /**
     * Returns files by ids
     *
     * @param int|array $ids - id or array of ids
     * @param string|null $imgSize - label from config `media-library.resize.sizes`
     * @param bool $object - returns full object of files data from DB (by default returns only urls)
     * @return mixed
     */
    public static function getFiles($ids, $imgSize = null, $object = false)
    {
        $items = Model::find(is_array($ids) ? $ids : [$ids]);

        if (! $items) {
            return is_array($ids) ? [] : null;
        }

        $array = $items->map(function ($item) use ($imgSize, $object) {
            $item = $item->toArray();

            if (! $item['url'] and ! $object) {
                return false;
            }

            if ($imgSize and in_array($imgSize, data_get($item, 'options.img_sizes', []))) {
                $item['url'] = self::getImageSize($item['url'], $imgSize);
            }

            return $object ? (object) $item : $item['url'];
        })->reject(function ($value) {
            return ! $value;
        });

        return is_array($ids) ? $array : ($array[0] ?? 1);
    }

    /**
     * Generate image url for needed size
     *
     * @param string $url - image url
     * @param string $size - image size from `media-library.resize.sizes`
     * @return string
     */
    public static function getImageSize($url, $size)
    {
        $name = explode('.', $url);
        array_pop($name);

        return implode('.', $name) . '-' . $size . '.' . pathinfo($url, PATHINFO_EXTENSION);
    }

    /**
     * Return file content
     * Must be used after checking user access in the controller
     *
     * @param string $path - data from DB ($item->path)
     * @param string|null $size - image size from `media-library.resize.sizes`
     * @return mixed
     */
    public static function getPrivateFile($path, $size = null)
    {
        try {
            if ($size) {
                $path = self::getImageSize($path, $size);
            }
            $file = Helper::storage()->get($path);
            $name = explode('/', $path);
            $bytes = Helper::storage()->size($path);
            $length = $bytes;
            $end = $bytes - 1;
            $start = 0;

            if (isset($_SERVER['HTTP_RANGE'])) {
                $temp = explode('bytes=', $_SERVER['HTTP_RANGE'], 2);
                $start = (float) (explode('-', $temp[1], 1))[0];
                $length = $bytes - $start;
            }

            return response($file)
                ->header('Content-Type', Helper::storage()->mimeType($path))
                ->header('Accept-Ranges', 'bytes')
                ->header('Content-Length', $length)
                ->header('Content-Range', "bytes {$start}-{$end}/{$bytes}")
                ->header('Content-Disposition', 'filename="' . array_pop($name) . '"');
        } catch (\Exception $e) {
            return response()->noContent(404);
        }
    }
}
