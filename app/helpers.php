<?php

use App\Validations\Validation;
use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;
use \Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

/**
 * @param string $date
 * @return \Carbon\Carbon|null
 */
function parse_user_date(string $date): ?\Carbon\Carbon
{
    return parse_date($date, config('app.date_format', 'Y-m-d'));
}

/**
 * @param string $date
 * @param null|string $format
 * @return \Carbon\Carbon|null
 */
function parse_date(string $date, ?string $format = null): ?\Carbon\Carbon
{
    if (!$format) {
        $format = 'Y-m-d';
    }
    try {
        return \Carbon\Carbon::createFromFormat($format, $date, config('app.timezone'));
    } catch (\Exception $e) {
        return null;
    }
}

/**
 * @param string $time
 * @return \Carbon\Carbon|null
 */
function parse_user_time(string $time): ?\Carbon\Carbon
{
    return parse_time($time, config('app.time_format', 'H:i'));
}

/**
 * @param string $time
 * @return \Carbon\Carbon|null
 */
function parse_user_date_time(string $time): ?\Carbon\Carbon
{
    return parse_time($time, config('app.date_time_format', 'Y/m/d H:i'));
}

/**
 * @param string $time
 * @param null|string $format
 * @return \Carbon\Carbon|null
 */
function parse_time(string $time, ?string $format = null): ?\Carbon\Carbon
{
    if (!$format) {
        $format = 'H:i';
    }

    try {
        return \Carbon\Carbon::createFromFormat($format, $time, config('app.timezone'));
    } catch (\Exception $e) {
        return null;
    }
}


/**
 * @param $date
 * @return \Carbon\Carbon|null
 */
function format_api_date($date): ?\Carbon\Carbon
{
    return parse_date($date, config('app.api_date_format', 'j/m/Y'));
}

/**
 * @param \Carbon\Carbon $date
 * @param null $format
 * @return string
 */
function format_date(\Carbon\Carbon $date, $format = null)
{
    if (!$format) {
        $format = config('app.date_formt', 'Y/m/d');
    }

    return $date->format($format);
}

/**
 * @param \Carbon\Carbon $date
 * @param $format
 * @return string
 */
function format_time(\Carbon\Carbon $date, $format = null)
{
    if (!$format) {
        $format = config('app.time_format', 'H:i');
    }

    return $date->format($format);
}

/**
 * @param \Carbon\Carbon $date
 * @param $format
 * @return string
 */
function format_date_time(\Carbon\Carbon $date, $format = null)
{
    if (!$format) {
        $format = config('app.date_formt', 'Y/m/d') . ' ' . config('app.time_format', 'H:i');
    }

    return $date->format($format);
}

/**
 * @param string $function
 * @param null|string ...$params
 * @return string
 */
function db_raw_function(string $function, ?string ...$params): string
{
    $function = \Illuminate\Support\Str::lower($function);
    switch ($function) {
        case 'addtime':
            list($column, $value) = $params;
            return \Illuminate\Support\Facades\DB::raw("ADDTIME($column, SEC_TO_TIME({$value}))");
        case 'subtime':
            list($column, $value) = $params;
            return \Illuminate\Support\Facades\DB::raw("SUBTIME($column, SEC_TO_TIME({$value}))");
        default:
            list($column) = $params;
            return \Illuminate\Support\Facades\DB::raw("$function($column))");
            break;
    }
}

/**
 * @param string $module
 * @param array|null $default
 * @return array|null
 */
function get_last_user_search(string $module, ?array $default = null): ?array
{
    return session("SEARCH_{$module}", $default);
}

/**
 * @param string $module
 * @param array|null $value
 */
function set_last_user_search(string $module, ?array $value = null): void
{
    session(["SEARCH_{$module}" => $value]);
}

/**
 * @param string $module
 * @param int|null $default
 * @return int
 */
function get_module_per_page(string $module, int $default = 20): int
{
    return session("SEARCH_{$module}_PER_PAGE", $default);
}

/**
 * @param string $module
 * @param int $value
 */
function set_module_per_page(string $module, int $value = 20): void
{
    session(["SEARCH_{$module}_PER_PAGE" => $value]);
}

/**
 * @param string $module
 * @param int|null $default
 * @return int
 */
function module_per_page(string $module, ?int $default = 20): int
{
    $request = request('per_page');

    if ($request) {
        set_module_per_page($module, $request);
        return $request;
    }

    return get_module_per_page($module, $default);
}

/**
 * @return bool
 */
function auth_admin(): bool
{
    if (app()->runningInConsole()) {
        auth()->login(User::where('username', 'admin')->firstOrFail());
        return true;
    }

    return false;
}

/**
 * @param \Illuminate\Pagination\LengthAwarePaginator $originalPaginator
 * @return \Illuminate\Support\Collection
 */
function get_per_page_links(\Illuminate\Pagination\LengthAwarePaginator $originalPaginator): \Illuminate\Support\Collection
{
    $paginator = clone $originalPaginator;
    return collect([20, 50, 100, 160, 200, 500, 1000])->filter(function ($p) use ($paginator) {
        static $break = false;
        if (!$break) {
            if ($p >= $paginator->total()) {
                $break = true;
                return true;
            }
            return $p < $paginator->total();
        }

        return false;
    })->map(function ($p) use ($paginator) {
        if ($p != $paginator->perPage()) {
            return '<a class="page-link btn-link" href="' . $paginator->appends(['per_page' => $p])->url(1) . '">' . $p . '</a>';
        }
        return "<strong>{$p}</strong>";
    });
}

/**
 * @param $modules
 * @param $permissions
 */
function abort_if_no_permission($modules, $permissions)
{
    if (!Validation::permissionsUser($modules, $permissions)) {
        abort(403, \Illuminate\Support\Facades\Lang::get('base_lang.no_permission'));
    }
}

function pagination($data, $perPorPage)
{
    $register = Collection::make($data);
    $pageName = 'page';

    $page = Paginator::resolveCurrentPage($pageName);

    return new LengthAwarePaginator($register->forPage($page, $perPorPage), $register->count(), $perPorPage, $page, [
        'path' => Paginator::resolveCurrentPath(),
        'pageName' => $pageName,
    ]);
}

/**
 * @param array $addresses
 * @param string $separator
 * @return string
 */
function map_email_addresses(array $addresses, $separator = ';'): string
{
    return collect($addresses)->map(function ($address) {
        if ($address['name'] ?? null) {
            return $address['name'] . '<' . $address['address'] . '>';
        }
        return $address['address'];
    })->implode($separator);
}

/**
 * @return array
 */
function all_permissions(): array
{
    $modules = config('modules.modules', []);
    $permissions = config('modules.base_permissions', []);

    $perms = [];
    foreach ($modules as $mod => $m) {
        foreach ($permissions as $perm => $p) {
            $perms[] = $perm . '_' . $mod;
        }
    }

    return $perms;
}

/**
 * @param array $permissions
 * @param User|null $user
 * @return bool
 */
function validatePermission(array $permissions, ?User $user = null): bool
{
    if (!$user) {
        $user = Auth::user();
    }

    try {
        return $user->isAdmin() || $user->hasAnyPermission($permissions);
    } catch (Exception $e) {
        \Illuminate\Support\Facades\Log::error('validatePermission: ' . $e->getMessage());
        return false;
    }
}

function remove_symbols($string)
{
    return trim($string);
}

function remove_punctuation($number)
{
    return preg_replace('/[^A-Za-z0-9]/', '', $number);
}

/**
 * @param $string
 * @return mixed
 */
function cleanAccents($string)
{
    $accents = array("á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú", "ñ", "À", "Ã", "Ì", "Ò", "Ù", "Ã™", "Ã ", "Ã¨", "Ã¬", "Ã²", "Ã¹", "ç", "Ç", "Ã¢", "ê", "Ã®", "Ã´", "Ã»", "Ã‚", "ÃŠ", "ÃŽ", "Ã”", "Ã›", "ü", "Ã¶", "Ã–", "Ã¯", "Ã¤", "«", "Ò", "Ã", "Ã„", "Ã‹");
    $clean = array("a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "n", "N", "A", "E", "I", "O", "U", "a", "e", "i", "o", "u", "c", "C", "a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "u", "o", "O", "i", "a", "e", "U", "I", "A", "E");
    return str_replace($accents, $clean, $string);
}

function permissionServer(Schedule $schedule)
{
    $date = Carbon::today()->toDateString();
    $schedule->exec("touch storage/logs/laravel-$date.log")->daily();
    $schedule->exec("sudo chown -R ec2-user:apache .;sudo chmod -R 755 .;sudo chmod -R 775 storage/");
}

function getDecimalFormat($value = false, $decimals = 3)
{
    $decimalSeparator = config('app.number_dec_sep', '.');
    $groupSeparator = config('app.number_grp', ',');

    if ($value && is_numeric($value)) {
        return rtrim(rtrim(number_format($value, $decimals, $decimalSeparator, $groupSeparator), '0'), $decimalSeparator);
    }

    return null;
}

function formatFloatValue($value, $decimals = 2)
{
    if (filled($value)) {
        $numDecimales = strlen(substr(strrchr($value, "."), 1));
        $numDecimales = max($numDecimales, $decimals);

        return number_format($value, $numDecimales, '.', '');
    }

    return $value;
}

function getImpersonateUser()
{
    $manager = app('impersonate');
    return $manager->getImpersonatorId() ?? Auth::id();
}

function months($month)
{
    switch ($month) {
        case '1':
            return 'Enero';
            break;
        case '2':
            return 'Febrero';
            break;
        case '3':
            return 'Marzo';
            break;
        case '4':
            return 'Abril';
            break;
        case '5':
            return 'Mayo';
            break;
        case '6':
            return 'Junio';
            break;
        case '7':
            return 'Julio';
            break;
        case '8':
            return 'Agosto';
            break;
        case '9':
            return 'Septiembre';
            break;
        case '10':
            return 'Octubre';
            break;
        case '11':
            return 'Noviembre';
            break;
        case '12':
            return 'Diciembre';
            break;
        default:
            return 'null';
            break;
    }
}

function getGoogleApiKey()
{
    $dayOfMonth = Carbon::now()->day;
    if ($dayOfMonth >= 1 && $dayOfMonth <= 10) {
        return config('geolocation.key');
    } elseif ($dayOfMonth >= 11 && $dayOfMonth <= 20) {
        return config('geolocation.key2');
    } else {
        return config('geolocation.key3');
    }
}

function getNameMunicipality($locate)
{
    if ($locate->getLocality() ?? false) {
        return $locate->getLocality()->getLongName();
    } elseif ($locate->getAdministrativeAreaLevel2() ?? false) {
        return $locate->getAdministrativeAreaLevel2()->getLongName();
    } elseif ($locate->getColloquialArea() ?? false) {
        return $locate->getColloquialArea()->getLongName();
    } else {
        return null;
    }
}

function getTimePerDateTime($datetime)
{
    $hour = format_time(Carbon::parse($datetime), 'h:i A');
    return $hour ?? null;
}

function value_or_null($value)
{
    return blank($value) ? null : $value;
}

function formatValue($value)
{
    $months = [
        'JANUARY' => 'ENERO',
        'FEBRUARY' => 'FEBRERO',
        'MARCH' => 'MARZO',
        'APRIL' => 'ABRIL',
        'MAY' => 'MAYO',
        'JUNE' => 'JUNIO',
        'JULY' => 'JULIO',
        'AUGUST' => 'AGOSTO',
        'SEPTEMBER' => 'SEPTIEMBRE',
        'OCTOBER' => 'OCTUBRE',
        'NOVEMBER' => 'NOVIEMBRE',
        'DECEMBER' => 'DICIEMBRE'
    ];

    $days = [
        'MONDAY' => 'LUNES',
        'TUESDAY' => 'MARTES',
        'WEDNESDAY' => 'MIÉRCOLES',
        'THURSDAY' => 'JUEVES',
        'FRIDAY' => 'VIERNES',
        'SATURDAY' => 'SÁBADO',
        'SUNDAY' => 'DOMINGO'
    ];

    if (is_null($value)) {
        return '';
    } elseif (isset($months[strtoupper($value)])) {
        return $months[strtoupper($value)];
    } elseif (isset($days[strtoupper($value)])) {
        return $days[strtoupper($value)];
    }

    return htmlspecialchars($value);
}

function caracterToText($char)
{
    switch ($char) {
        case '.':
            return 'punto';
        case ',':
            return 'coma';
        default:
            return '';
    }
}

function isRedisAvailable(): bool
{
    if (!config('database.redis.client') || !config('app.redis')) {
        return false;
    }

    try {
        return Redis::ping() == 'PONG';
    } catch (\Exception $e) {
        return false;
    }
}

function searchByField(string $model, string $field, string $term): array
{
    $startTime = microtime(true);

    $term = strtolower($term);
    $results = [];

    $prefix = strtolower($model);

    if (!isRedisAvailable()) {
        $modelClass = "App\\Models\\" . ucfirst($model);

        if (!class_exists($modelClass)) {
            return [];
        }

        $query = $modelClass::query()->status()
            ->where($field, 'LIKE', "%$term%")
            ->orderBy($field, 'asc');

        $data = $query->get();

        foreach ($data as $item) {
            if (isset($item->{$field}) && str_contains(strtolower($item->{$field}), $term)) {
                $results[] = $item->toArray();
            }
        }
    } else {
        $results = [];
        $foundIds = [];

        $words = preg_split('/\s+/', strtolower($term), -1, PREG_SPLIT_NO_EMPTY);

        foreach ($words as $word) {
            $prefixKey = "{$prefix}:{$field}_prefix:$word";

            try {
                $ids = Redis::smembers($prefixKey);
            } catch (\Exception $e) {
                $ids = [];
            }

            foreach ($ids as $id) {
                if (in_array($id, $foundIds)) {
                    continue;
                }

                $key = "{$prefix}:$id";

                try {
                    $data = Redis::hgetall($key);
                } catch (\Exception $e) {
                    continue;
                }

                if (!$data || !isset($data[$field])) {
                    continue;
                }

                $value = strtolower($data[$field]);

                $matchesAll = true;
                foreach ($words as $wordCheck) {
                    if (!str_contains($value, $wordCheck)) {
                        $matchesAll = false;
                        break;
                    }
                }

                if ($matchesAll) {
                    $results[] = $data;
                    $foundIds[] = $id;
                }

                usort($results, function ($a, $b) use ($field) {
                    return strcmp(strtolower($a[$field]), strtolower($b[$field]));
                });
            }
        }
    }

    return $results;
}

function insertRedis($model, $prefix, array $indexFields)
{
    if (!isRedisAvailable()) {
        return;
    }

    $key = "{$prefix}:{$model->id}";
    $cleanedData = [];

    foreach ($model->toArray() as $fieldKey => $fieldValue) {
        if (is_string($fieldValue)) {
            $cleanedData[$fieldKey] = preg_replace('/\s+/', ' ', trim($fieldValue));
        } else {
            $cleanedData[$fieldKey] = $fieldValue;
        }
    }

    foreach ($indexFields as $field) {
        $oldValue = Redis::hget($key, $field);
        if ($oldValue) {
            $oldValue = cleanField($field, $oldValue);
            $words = explode(' ', $oldValue);
            foreach ($words as $word) {
                $word = trim($word);
                for ($i = 1; $i <= mb_strlen($word); $i++) {
                    $partial = mb_substr($word, 0, $i, 'UTF-8');
                    $indexKey = "{$prefix}:{$field}_prefix:{$partial}";
                    Redis::srem($indexKey, $model->id);
                }
            }
        }
    }

    Redis::hmset($key, $cleanedData);
    Redis::expire($key, 86400);

    Redis::sadd("{$prefix}s:ids", $model->id);
    Redis::expire("{$prefix}s:ids", 86400);

    foreach ($indexFields as $field) {
        $value = cleanField($field, $model->{$field});
        $words = explode(' ', $value);
        foreach ($words as $word) {
            $word = trim($word);
            for ($i = 1; $i <= mb_strlen($word); $i++) {
                $partial = mb_substr($word, 0, $i, 'UTF-8');
                $indexKey = "{$prefix}:{$field}_prefix:{$partial}";
                Redis::sadd($indexKey, $model->id);
                Redis::expire($indexKey, 86400);
            }
        }
    }
}


function cleanField($field, $value)
{
    $value = strtolower($value);
    $value = preg_replace('/[^a-z0-9áéíóúüñ\s]/iu', '', $value);
    return preg_replace('/\s+/', ' ', trim($value));
}

function generateFourDigitCode($digits = 6)
{
    $min = pow(10, $digits - 1);
    $max = pow(10, $digits) - 1;

    return rand($min, $max);
}

function decodePolyline(string $polyline): array
{
    $points = [];
    $index = $lat = $lng = 0;

    while ($index < strlen($polyline)) {
        $result = 1;
        $shift = 0;
        do {
            $b = ord($polyline[$index++]) - 63 - 1;
            $result += $b << $shift;
            $shift += 5;
        } while ($b >= 0x1f);
        $lat += ($result & 1) ? ~($result >> 1) : ($result >> 1);

        $result = 1;
        $shift = 0;
        do {
            $b = ord($polyline[$index++]) - 63 - 1;
            $result += $b << $shift;
            $shift += 5;
        } while ($b >= 0x1f);
        $lng += ($result & 1) ? ~($result >> 1) : ($result >> 1);

        $points[] = [
            'latitude' => $lat * 1e-5,
            'longitude' => $lng * 1e-5,
        ];
    }

    return $points;
}

function diff_for_humans_days_value(string $date, ?string $format = null): ?int
{
    $date = parse_date($date, $format);

    if (!$date) {
        return null;
    }

    $today = now()->startOfDay();
    $date = $date->startOfDay();

    return $today->diffInDays($date, false);
}

function diff_for_humans_days_text(string $date, ?string $format = null): ?string
{
    $date = parse_date($date, $format);

    if (!$date) {
        return null;
    }

    $today = now()->startOfDay();
    $date = $date->startOfDay();

    if ($date->equalTo($today)) {
        return 'Vence hoy';
    }

    $days = $today->diffInDays($date);
    $label = $days === 1 ? 'día' : 'días';

    return $date->isPast() ? 'Hace ' . $days . ' ' . $label : 'Faltan ' . $days . ' ' . $label;
}
