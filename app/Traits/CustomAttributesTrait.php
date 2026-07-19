<?php

namespace App\Traits;

use App\Validations\Validation;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;

trait CustomAttributesTrait
{
    /**
     * @param string $name
     * @param mixed $value
     * @return mixed
     */
    public function setAttribute($name, $value)
    {

        if ($value) {
            if ($this->isTimeAttr($name)) {
                return $this->setTimeAttr($name, $value);
            }
            if ($this->isDateAttr($name)) {
                return $this->setDateAttr($name, $value);
            }
            if ($this->isNumberAttr($name)) {
                return $this->setNumberAttr($name, $value);
            }
            if ($this->isDateTimeAttr($name)) {
                return $this->setDateTimeAttr($name, $value);
            }
            if ($this->isFileAttr($name)) {
                return $this->setFileAttr($name, $value);
            }
            if ($this->isUpperAttr($name) || $this->isDoubleSpace($name)) {
                if ($this->isUpperAttr($name)) {
                    $value = $this->replaceUpper($value);
                }

                if ($this->isDoubleSpace($name)) {
                    $value = $this->replaceDoubleSpaces($value);
                }

                return $this->setUpperDoubleAttr($name, $value);
            }
        }

        return parent::setAttribute($name, $value);
    }

    /**
     * @param string $name
     * @return bool
     */
    protected function isTimeAttr($name)
    {
        return is_array($this->time_fields ?? null) &&
            in_array($name, $this->time_fields);
    }

    /**
     * @param $name
     * @return bool
     */
    protected function isDateAttr($name)
    {

        return is_array($this->dates ?? null) &&
            in_array($name, $this->dates);
    }

    /**
     * @param $name
     * @return bool
     */
    protected function isDateTimeAttr($name)
    {
        return is_array($this->date_time_fields ?? null) &&
            in_array($name, $this->date_time_fields);
    }

    /**
     * @param $name
     * @return bool
     */
    protected function isNumberAttr($name)
    {
        return is_array($this->number_format_fields ?? null) &&
            in_array($name, $this->number_format_fields);
    }

    /**
     * @param $name
     * @return bool
     */
    protected function isUpperAttr($name)
    {
        return is_array($this->upper_fields ?? null) &&
            in_array($name, $this->upper_fields);
    }

    /**
     * @param $name
     * @return bool
     */
    protected function isFileAttr($name)
    {
        return is_array($this->file_fields ?? null) &&
            in_array($name, $this->file_fields, true);
    }

    /**
     * @param $name
     * @return bool
     */
    protected function isDoubleSpace($name)
    {
        return is_array($this->no_double_spaces ?? null) &&
            in_array($name, $this->no_double_spaces);
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return mixed
     */
    protected function setTimeAttr($name, $value)
    {
        if ($value instanceof Carbon || $value instanceof \DateTime) {
            $this->attributes[$name] = $value->format('H:i');
        } else if (($time = parse_user_time($value)) || ($time = parse_time($value))) {
            $this->attributes[$name] = $time->format('H:i');
        }

        return $this;
    }

    /**
     * @param string $name
     * @param $value
     * @return mixed
     */
    protected function setDateAttr($name, $value)
    {
        if ($value instanceof Carbon || $value instanceof \DateTime) {
            $this->attributes[$name] = $value->format($this->getDateFormat());
        } else if (
            ($date = parse_user_date($value)) ||
            ($date = parse_date($value)) ||
            ($date = parse_date($value, 'Y-m-d H:i:s'))
        ) {
            $this->attributes[$name] = $date->format($this->getDateFormat());
        }

        return $this;
    }

    /**
     * @param $name
     * @param $value
     * @return $this
     */
    protected function setDateTimeAttr($name, $value)
    {

        if ($value instanceof Carbon || $value instanceof \DateTime) {
            $this->attributes[$name] = $value->format($this->getDateFormat());
        } else if (($date = parse_date($value, config('app.date_time_format', 'Y/m/d H:i')))) {
            $this->attributes[$name] = $date->format($this->getDateFormat());
        }

        return $this;
    }

    /**
     * @param $name
     * @param $value
     * @return mixed
     */
    protected function setNumberAttr($name, $value)
    {
        $value = str_replace(config('app.number_grp', ','), '', $value);
        $value = str_replace(config('app.number_dec_sep', '.'), '.', $value);
        $this->attributes[$name] = (float)$value;

        return $this;
    }

    /**
     * @param $name
     * @param $value
     * @return CustomAttributesTrait
     */
    protected function setUpperDoubleAttr($name, $value)
    {
        $this->attributes[$name] = $value;
        return $this;
    }

    /**
     * @param $name
     * @param $file
     * @return $this
     */
    protected function setFileAttr($name, $file)
    {
        if ($file instanceof UploadedFile || (is_string($file) && !empty($file))) {
            if (isset($this->attributes[$name])) {
                Storage::delete($this->attributes[$name]);
            }

            $path = $this->compressAndStoreFile($file);

            if ($path) {
                $this->attributes[$name] = $path;
            }
        }

        return $this;
    }

    /**
     * @param UploadedFile|string $file
     * @return string|null
     */
    public function compressAndStoreFile($file)
    {
        $folder = $this->getTable();

        if ($file instanceof UploadedFile) {
            $mimeType = $file->getMimeType();
            $isImage = str_starts_with($mimeType, 'image/');

            if ($isImage) {
                return $this->compressAndSaveImage($file->getRealPath(), $folder, $file);
            }

            return $file->store($folder);
        }

        if (is_string($file) && !empty($file)) {
            $decodedFile = base64_decode($file);

            try {
                return $this->compressAndSaveImage($decodedFile, $folder);
            } catch (\Exception $e) {
                Log::error('Error compressing image: ' . $e->getMessage(), [
                    'exception' => $e,
                ]);

                $fileName = $folder . '/' . Str::random(40) . '.jpg';
                Storage::put($fileName, $decodedFile);
                return $fileName;
            }
        }

        return null;
    }

    /**
     * @param string $imageData
     * @param string $folder
     * @param UploadedFile|null $originalFile
     * @return string
     */
    private function compressAndSaveImage($imageData, $folder, $originalFile = null)
    {
        $manager = ImageManager::imagick();
        $image = $manager->read($imageData);

        $needsResize = $image->width() > 1920 || $image->height() > 1920;

        if (!$needsResize) {
            $originalSize = is_string($imageData) ? strlen($imageData) : filesize($imageData);

            $encoded = $image->toJpeg(80);
            $compressedSize = strlen((string) $encoded);

            $savings = (($originalSize - $compressedSize) / $originalSize) * 100;

            if ($savings < 5) {
                if ($originalFile instanceof UploadedFile) {
                    return $originalFile->store($folder);
                } else {
                    $fileName = $folder . '/' . Str::random(40) . '.jpg';
                    Storage::put($fileName, $imageData);
                    return $fileName;
                }
            }

            $fileName = $folder . '/' . Str::random(40) . '.jpg';
            Storage::put($fileName, (string) $encoded);
            return $fileName;
        }

        $image->scale(1920, 1920);
        $encoded = $image->toJpeg(80);
        $fileName = $folder . '/' . Str::random(40) . '.jpg';
        Storage::put($fileName, (string) $encoded);

        return $fileName;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getAttributeValue($name)
    {
        $value = parent::getAttributeValue($name);

        if ($this->isTimeAttr($name) && is_string($value)) {
            return Carbon::createFromTimeString($value, config('app.timezone'));
        }

        if ($this->isDateAttr($name) && is_string($value)) {
            if (strlen($value) === 10) {
                return Carbon::createFromFormat('Y-m-d', $value, config('app.timezone'))->startOfDay();
            }

            return Carbon::createFromFormat($this->getDateFormat(), $value, config('app.timezone'));
        }

        if ($this->isDateTimeAttr($name) && is_string($value)) {
            return Carbon::createFromFormat($this->getDateFormat(), $value, config('app.timezone'));
        }

        if (($this->isUpperAttr($name) || $this->isDoubleSpace($name)) && !empty($value)) {
            if ($this->isUpperAttr($name)) {
                $value = $value ? Str::upper($value) : $value;
            }

            if ($this->isDoubleSpace($name)) {
                $value = preg_replace('/[\x{00A0}\x{200B}\x{FEFF}\x{202F}\x{180E}\x{2060}\x{00AD}]/u', ' ', $value);
                $value = preg_replace('/\s+/u', ' ', $value);
                $value = trim($value);
            }

            return $value;
        }

        return $value;
    }

    /**
     * @param $name
     * @param $value
     */
    private function replaceUpper($value)
    {
        return $value ? Str::upper($value) : $value;
    }

    /**
     * @param $name
     * @param $value
     * @return CustomAttributesTrait
     */
    private function replaceDoubleSpaces($value)
    {
        $value = preg_replace('/[\x{00A0}\x{200B}\x{FEFF}\x{202F}\x{180E}\x{2060}\x{00AD}]/u', ' ', $value);
        $value = preg_replace('/\s+/u', ' ', $value);
        $value = trim($value);

        return $value;
    }

    public function getStatusTextAttribute()
    {
        return $this->status ? __('base_lang.active') : __('base_lang.inactive');
    }
}
