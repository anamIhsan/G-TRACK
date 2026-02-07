<?php

namespace App\Helpers;

use Exception;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Utils
{
    static function publicPath()
    {
        if (env('TYPE_SERVER') === "CPANEL" && env('APP_ENV') === "production") {
            return env('DOCUMENT_STORAGE');
        } else {
            return public_path();
        }
    }

    static function phoneFormat($phone)
    {
        $number = preg_replace('/\D/', '', $phone);

        if (strpos($number, '0') === 0) {
            $number = '62' . substr($number, 1);
            return $number;
        } else if (strpos($number, '62') === 0) {
            return $number;
        }

        return null;
    }

    static function generateQr($user)
    {
        $uniqueText = $user->nfc_id;

        $path = self::publicPath() . '/qrcodes';
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
        $filename = time() . '_' . uniqid() . '.png';

        QrCode::format('png')->size(400)->generate($uniqueText, $path . '/' . $filename, 'gd');

        return $filename;
    }

    static function generateTwibbon($user, $twibbonPhoto)
    {
        // try {
        $manager = new ImageManager(new GdDriver());
        $generateQr = self::generateQr($user);


        $pathProfile = $user->gambar && file_exists(self::publicPath() . $user->gambar) ? self::publicPath() . $user->gambar : self::publicPath() . "/defaultProfile.png";
        $pathTwibbon = self::publicPath() . $twibbonPhoto;
        $pathQr = self::publicPath() . '/qrcodes/' . $generateQr;

        $img = $manager->read($pathProfile);
        $img = $img->cover(345, 345, position: 'top-center');

        $twibbonFrameFullPath = $manager->read($pathTwibbon);
        $twibbonFrameFullPath = $twibbonFrameFullPath->cover(638, 1008);

        $qrRead = $manager->read($pathQr);
        $gambarQr = $qrRead->cover(230, 230);

        $finalImage = $manager->read(self::publicPath() . '/defaultTwibbon.png');
        $finalImage = $finalImage->cover(638, 1008);

        $finalImage->place($img, 'top', 0, 150);
        $finalImage->place($twibbonFrameFullPath, 'top', 0, 0);
        $finalImage->place($gambarQr, 'top', 0, 700);

        $nama_user = $user->nama;
        $nama_daerah = $user->zone->nama ?? "";
        $nfc = $user->nfc_id ?? "";
        $x_center = 319;
        $margin_top_nama = 565;
        $font_size_nama = 55;
        $line_height = 2;
        $margin_top_daerah = 625;
        $font_size_daerah = 45;
        $margin_top_nfc = 955;
        $font_size_nfc = 20;

        $nama_panjang = $nama_user;
        $batas_lebar = 20;

        $nama_pecah = wordwrap($nama_panjang, $batas_lebar, "\n", true);
        if (str_contains($nama_pecah, "\n")) {
            $margin_top_daerah += 40;
            $font_size_nama = 47;
        }

        $daerah_pecah = wordwrap($nama_daerah, $batas_lebar, "\n", true);
        if (str_contains($daerah_pecah, "\n")) {
            $margin_top_daerah += 40;
            $font_size_daerah = 37;
        }

        $lines = explode("\n", $nama_pecah);
        $y = $margin_top_nama;

        foreach ($lines as $index => $line) {
            $finalImage->text($line, $x_center, $y, function ($font) use ($font_size_nama) {
                $font->file(public_path('/fonts/Lato/Lato-Bold.ttf'));
                $font->size($font_size_nama);
                $font->color('#000000');
                $font->align('center');
                $font->valign('bottom');
            });

            $y += $font_size_nama + $line_height;
        }

        // $finalImage->text($nama_pecah, $x_center, $margin_top_nama, function ($font) use ($font_size_nama) {
        //     $font->file(public_path('/fonts/Lato/Lato-Bold.ttf'));
        //     $font->size($font_size_nama);
        //     $font->color('#000000');
        //     $font->align('center');
        //     $font->valign('bottom');
        // });

        $finalImage->text($nama_daerah, $x_center, $margin_top_daerah, function ($font) use ($font_size_daerah) {
            $font->file(public_path('/fonts/Lato/Lato-Regular.ttf'));
            $font->size($font_size_daerah);
            $font->color('#000000');
            $font->align('center');
            $font->valign('bottom');
        });

        $finalImage->text($nfc, $x_center, $margin_top_nfc, function ($font) use ($font_size_nfc) {
            $font->file(public_path('/fonts/Lato/Lato-Italic.ttf'));
            $font->size($font_size_nfc);
            $font->color('#000000');
            $font->align('center');
            $font->valign('bottom');
        });

        $filename = time() . '_' . uniqid() . '.png';
        $savePath = self::publicPath() . '/twibbons/' . $filename;

        if (!file_exists(self::publicPath() . '/twibbons')) {
            mkdir(self::publicPath() . '/twibbons', 0777, true);
        }

        $finalImage->save($savePath);

        $saveFile = '/twibbons/' . $filename;

        if (file_exists($pathQr)) {
            unlink($pathQr);
        }

        return $saveFile;
        // } catch (\Throwable $th) {
        //     Log::error("Twibbon Generation Error: " . $th->getMessage());
        //     throw new Exception("Twibbon Generation Error: " . $th->getMessage());
        // }
    }
}
