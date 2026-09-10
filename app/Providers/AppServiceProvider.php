<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Ensure DomPDF and QrCode PSR-4 mappings are registered
        spl_autoload_register(function ($class) {
            if ($class === 'Dompdf\\Cpdf') {
                $cpdf = base_path('vendor/dompdf/dompdf/lib/Cpdf.php');
                if (file_exists($cpdf)) {
                    require_once $cpdf;
                    return true;
                }
            }

            $prefixes = [
                'Barryvdh\\DomPDF\\' => base_path('vendor/barryvdh/laravel-dompdf/src/'),
                'Dompdf\\' => base_path('vendor/dompdf/dompdf/src/'),
                'SimpleSoftwareIO\\QrCode\\' => base_path('vendor/simplesoftwareio/simple-qrcode/src/'),
                'BaconQrCode\\' => base_path('vendor/bacon/bacon-qr-code/src/'),
                'DASPRiD\\Enum\\' => base_path('vendor/dasprid/enum/src/'),
                'Masterminds\\' => base_path('vendor/masterminds/html5/src/'),
                'Sabberworm\\CSS\\' => base_path('vendor/sabberworm/php-css-parser/src/'),
                'Svg\\' => base_path('vendor/dompdf/php-svg-lib/src/Svg/'),
                'FontLib\\' => base_path('vendor/dompdf/php-font-lib/src/FontLib/'),
            ];

            foreach ($prefixes as $prefix => $baseDir) {
                $len = strlen($prefix);
                if (strncmp($prefix, $class, $len) !== 0) {
                    continue;
                }
                $relativeClass = substr($class, $len);
                $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
                if (file_exists($file)) {
                    require_once $file;
                    return true;
                }
            }
            return false;
        }, true, true);

        if (class_exists(\Barryvdh\DomPDF\ServiceProvider::class)) {
            $this->app->register(\Barryvdh\DomPDF\ServiceProvider::class);
        }
        if (class_exists(\SimpleSoftwareIO\QrCode\QrCodeServiceProvider::class)) {
            $this->app->register(\SimpleSoftwareIO\QrCode\QrCodeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
