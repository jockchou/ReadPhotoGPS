<?php
namespace PhotoGps;

class PhotoGps
{
    private $filename;

    function __construct($filename)
    {
        $this->filename = $filename;
    }

    private function parseMeta($meta)
    {
        $GPSMeta = null;

        if (isset($meta['GPS'])) {
            $GPSMeta = $meta['GPS'];
            $lon = $this->gps($GPSMeta["GPSLongitude"], $GPSMeta['GPSLongitudeRef']);
            $lat = $this->gps($GPSMeta["GPSLatitude"], $GPSMeta['GPSLatitudeRef']);

            return array(
                'longitude' => $lon,
                'latitude' => $lat
            );
        }

        return false;
    }

    private function gps($coordinate, $hemisphere)
    {
        for ($i = 0; $i < 3; $i++) {
            $part = explode('/', $coordinate[$i]);
            if (count($part) == 1) {
                $coordinate[$i] = $part[0];
            } else {
                if (count($part) == 2) {
                    $coordinate[$i] = floatval($part[0]) / floatval($part[1]);
                } else {
                    $coordinate[$i] = 0;
                }
            }
        }
        list($degrees, $minutes, $seconds) = $coordinate;
        $sign = ($hemisphere == 'W' || $hemisphere == 'S') ? -1 : 1;

        return $sign * ($degrees + $minutes / 60 + $seconds / 3600);
    }

    public function coordinate()
    {
        if (function_exists('exif_read_data')) {
            $meta = exif_read_data($this->filename, false, true);

            return $this->parseMeta($meta);
        } else {
            throw new \ErrorException("need exif support!");
        }
    }

    /**
     * @param mixed $filename
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }
}