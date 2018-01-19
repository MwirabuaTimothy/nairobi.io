<?php

/**
 * Return a success response
 *
 * @return Response
 */

function configs($message)
{
    $res = [
        'message' => $message,
        'requested_at'=>date('Y-m-d H:i:s'),
    ];
    if(session('api_token')){
        $res['api_token'] = session('api_token');
    }
    if(request()->api_token){
        $res['api_token'] = request()->api_token;
    }
    if(request()->user()){
        // $res['user'] = request()->user;
        $res['user'] = request()->user()->makeHidden(['lists', 'clips', 'teams', 'templates', 'messages'])->toArray();
    }
    return $res;
}

/**
 * Check if request is from api
 *
 * @return Boolean
 */
function is_api()
{
    return in_array('api', request()->segments()) || request()->ajax();
}

/**
 * Return a success response
 *
 * @return Response
 */
function success($message, $redirectUrl=null, $data=null)
{
    if (is_api()) {
        return array_merge(['success' => true], configs($message), ['data' => $data]);
    }
    if($redirectUrl){
        return redirect()->to($redirectUrl)->withSuccess($message)->withData($data);
    }
    return back()->withSuccess($message);
}

/**
 * Return an error response
 *
 * @return Response
 */

function error($message, $redirectUrl=null, $key=null)
{
    if (is_api()) {
        return array_merge(['success' => false], configs($message), ['key' => $key]);
    }
    if($redirectUrl){
        return redirect()->to($redirectUrl)->withInput()->withDanger($message);
    }
    return back()->withInput()->withDanger($message);
}


/**
 * Return an error response and stop execution
 *
 * @return Response
 */

function dieError($message, $redirectUrl=null)
{
    if (is_api()) {
        header(sprintf("Content-Type: application/json"));
        return exit(json_encode(array_merge(['success' => false], configs($message))));
    }
    
    session()->flash('danger', $message);
    return exit(View::make('error.500')->withDanger($message));
}

/**
 * Return an warning response
 *
 * @return Response
 */

function warning($message, $redirectUrl=null, $key=null)
{
    if (is_api()) {
        return array_merge(['success' => false], configs($message), ['key' => $key]);
    }
    if($redirectUrl){
        return redirect()->to($redirectUrl)->withInput()->withWarning($message);
    }
    return back()->withInput()->withWarning($message);
}

/**
 * Return validation errors
 *
 * @return Response
 */
function validater($validator, $redirectUrl=null)
{
    if (is_api()) {
        $array = $validator->messages()->toArray();
        // return $array;

        // new version:
        $key = array_keys($array)[0];
        $message = array_values($array)[0][0];
        return  error($message, $redirectUrl, null, $key);

        // old version:
        $output = [];
        foreach ($array as $key=>$message) {
            $output[$key]= $message[0];
        };
        return error($output);
    }
    if (!$redirectUrl) { // setting the $redirectURL
        $redirectUrl = url()->previous(); // falls back to home if referer is missing
    }
    return redirect($redirectUrl)->withInput()->withErrors($validator); // special cases where you want to redirect elsewhere
}

/**
 * Stop execution if user has no authority for the record
 *
 * @return Response
 */

function filterAccess($auth, $record, $field)
{
    if(!session('Master')){ // master admins get a free pass

        if($auth->id != $record->$field){

            $model = get_class($record); 
            $message = "You do not own the $model!";
            
            if (in_array('api', request()->segments())) {
                header(sprintf("Content-Type: application/json"));
                return exit(json_encode(error($message)));
            }
        
            session()->flash('danger', $message);
            return exit(View::make('error.403'));
        }

    }

}


function cached($record, $method, $param=null)
{
    $key = get_class($record)."-$record->id-$method";
    // if(Cache::has($key)){
    //     return Cache::get($key);
    // }
    $value = $record->$method($param);

    Cache::forever($key, $value);
    return $value;
}


function recache($record, $method, $param=null)
{
    $key = get_class($record)."-$record->id-$method";
    $value = $record->$method($param);
    Cache::forever($key, $value);
}

function sendReport($tracker, $exception=null, $subject='PressDesk Exceptions')
{

    // save message
    // @todo

    // fetch team
    $recipients = Config::get('app.devs');
    $dev = $recipients[0];

    // whatsapp/slack team 
    try {
    } catch (Exception $exception) {
    }

    // sms message
    try {
        // (new SMS)->send($dev['phone'], 'team.report', [substr($tracker, 0, 160)]);
        $message = 'Successfully sent sms!';
    } catch (Exception $exception) {
        $message = 'Failed to send sms!'.$tracker;
    }
    
    // email team
    try {

        $data = [
            'type' => 'emails.simple',
            'subject' => $subject,
            'email' => $dev['email'],
            'name' => $dev['name'],
            // Data to be used on the email view
            'body' => $tracker.'<br/>'.$exception->getMessage().'<br/>'.$exception,
        ];
        queue('Services\Mailer', $data);

        $message .= '<br/> Successfully sent mail!';
        return $message;
    } catch (Exception $exception) {
        $message .= '<br/> Failed to send mail!'.$tracker;
        return $message;
    }
}


function imager($str)
{
    if ($str && filter_var($str, FILTER_VALIDATE_URL) === false) { // string is not empty and is a not a url
       return Config::get('app.url').$str;
    }
    return $str;
}

function hhmmss($time = null)
{
    if($time){
        $time_object = new DateTime($time);
        return $time_object->format('His');
    }
    else{
        return date('His'); // or current time
    } 
}
function timestamp()
{
    // get current time
    // $now = time(); // seconds since the Unix Epoch (January 1 1970 00:00:00 GMT)
    // $now = date('U');  // same
    // $now = microtime(); // ... in precise microseconds
    $now = microtime(true); // ... in floating point format

    // in 6 decimal places
    $time = sprintf('%.6f', $now);
    
    // remove the decimals
    $time = $time*1000000;
    
    // remove the exponential notation
    $time = sprintf('%.0f', $time);
    
    // get a substring
    $time = substr($time, 0, 15);

    return $time; // 16 digits
}
function startDate($year, $month=null, $date=null)
{
    if(!isset($month)){
        $month = 1;
    }
    if(!isset($date)){
        $mmDDyy = "$year-$month-1";
    }
    else{
        $mmDDyy = "$year-$month-$date";
    }
    return Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $mmDDyy . ' 00:00:00');
}
function endDate($year, $month=null, $date=null)
{
    if(!isset($month)){
        $month = 12;
    }
    if(!isset($date)){
        $datestring = "$year/$month/1";
        $timestamp = strtotime($datestring);
        $mmDDyy = date('Y-m-t', $timestamp);
    }
    else{
        $mmDDyy = "$year-$month-$date";
    }
    return Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $mmDDyy . ' 23:59:59');
}

function uploadBase64($field, $path) 
{
    $data = explode(',', request($field));
    $decoded = base64_decode($data[1]); 
    $f = finfo_open();
    $mime = finfo_buffer($f, $decoded, FILEINFO_MIME_TYPE);
    $extensions = [
        'image/jpeg' => 'jpeg',
        'image/png' => 'png',
        'image/jpg' => 'jpg',
        'image/gif' => 'gif',
        'application/x-shockwave-flash' => 'swf',
        'image/psd' => 'psd',
        'image/bmp' => 'bmp',
        'image/tiff' => 'tiff',
        'image/tiff' => 'tiff',
        'application/octet-stream' => 'jpc',
        'image/jp2' => 'jp2',
        'application/octet-stream' => 'jpf',
        'application/octet-stream' => 'jb2',
        'application/x-shockwave-flash' => 'swc',
        'image/iff' => 'aiff',
        'image/vnd.wap.wbmp' => 'wbmp',
        'image/xbm' => 'xbm',
        'text/xml' => 'xml',
    ];

    $filename = timestamp() . '.'. $extensions[$mime];
    $file_path = $path.'/'.$filename;
    \Illuminate\Support\Facades\Storage::put($file_path, $decoded);
    return '/storage'.$file_path;
}

function uploadFile($field, $path)
{
    $object = request()->file($field);
    $filename = timestamp() . '-' . $object->getClientOriginalName();
    $file_path = $path.'/'.$filename;
    \Illuminate\Support\Facades\Storage::putFileAs($path, $object, $filename);
    return '/storage'.$file_path;
}

function getUpload($record, $field, $placeholder=null)
{
    $table = $record->getTable();
    $id = $record->id;
    $name = $record->$field;

    if ($name):
        return $name;
    endif;
    if ($placeholder):
        return $placeholder;
    endif;
    
    // return $name; // allowing blank images

    $name = "no_$field.png";
    return tempImage($table, $field);
}

function tempImage($table, $field)
{
    return asset("/assets/img/$table/$field/no_$field.png");
}


function getPlatform()
{
    if(isset($_SERVER['HTTP_USER_AGENT'])){
        $ua = $_SERVER['HTTP_USER_AGENT'];

        if (strpos($ua, 'MSIE') !== false) {
            $name = 'Internet explorer';
        } elseif (strpos($ua, 'Trident') !== false) { //For Supporting IE 11
        $name = 'Internet explorer';
        } elseif (strpos($ua, 'Firefox') !== false) {
            $name = 'Mozilla Firefox';
        } elseif (strpos($ua, 'Chrome') !== false) {
            $name = 'Google Chrome';
        } elseif (strpos($ua, 'Opera Mini') !== false) {
            $name = "Opera Mini";
        } elseif (strpos($ua, 'Opera') !== false) {
            $name = "Opera";
        } elseif (strpos($ua, 'Safari') !== false) {
            $name = "Safari";
        } else {
            $name = 'Unknown browser';
        }

        
        $ua = strtolower($ua);

        // What version? 
        if (preg_match('/.+(?:rv|it|ra|ie)[\/: ]([\d.]+)/', $ua, $matches)) {
            $version = $matches[1];
        } else {
            $version = 'unknown';
        }

        // Running on what platform? 
        if (preg_match('/linux/', $ua)) {
            $platform = 'linux';
        } elseif (preg_match('/macintosh|mac os x/', $ua)) {
            $platform = 'osx';
        } elseif (preg_match('/windows|win32/', $ua)) {
            $platform = 'windows';
        } else {
            $platform = 'unrecognized';
        }

        return [
            'browser'   => $name,
            'version'   => $version,
            'platform'  => $platform,
            'user_agent' => $ua
        ];
    }
    else{
        return [
            'browser'   => 'unknown',
            'version'   => 'unknown',
            'platform'  => 'unknown',
            'user_agent' => 'unknown'
        ];

    }
}

// polyfill for getallheaders() in case it doesnt exist on server
if (!function_exists('getallheaders')) {
    function getallheaders()
    {
        $headers = '';
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                // $headers[substr($name, 5)] = $value;
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }
}
function getHeaders($key, $default=false)
{
    if(request()->get($key)){
        $value = request()->get($key);
        return $value;
    }
    if(request()->get(ucfirst($key))){
        $value = request()->get(ucfirst($key));
        return $value;
    }
    if(request()->get(ucwords(str_replace('_', '-', $key)))){
        $value = request()->get(ucwords(str_replace('_', '-', $key)));
        return $value;
    }
    if(isset($_SERVER['HTTP_'.strtoupper(str_replace('-', '_', $key))])){
        $value = $_SERVER['HTTP_'.strtoupper(str_replace('-', '_', $key))];
        return $value;
    }
    if(isset($_SERVER[strtoupper(str_replace('-', '_', $key))])){
        $value = $_SERVER[strtoupper(str_replace('-', '_', $key))];
        return $value;
    }
    return $default;

}

// get the value of a variable if set or use a defalt

function get(&$var, $default = false) {
    return isset($var) ? $var : $default;
}

if ( ! function_exists('queue'))
{
    function queue($class, $data) {
        // $date = Carbon\Carbon::now()->addSeconds(10);
        // Queue::later($date, $class, $data);
        // Queue::later(10, $class, $data); // ten seconds later
        Queue::push($class, $data); // immediately
        // return exec('php artisan queue:work --tries=2 --delay=10');
        // nohup php artisan queue:listen --tries:2 --delay=10 > /dev/null 2>&1 &
        // art queue:listen --delay=10 --tries=3
    }
}


if ( ! function_exists('strhas'))
{
    function strhas($haystack, $needle) {
        if (strpos($haystack, $needle) !== false) {
            return true;
        }
        return false;
    }
}

function demo_id() {
    return 2;
}
function rand_string($length=10) {
    $ref = str_repeat('ABCDEFGHJKLMNPQRSTUVWXYZ23456789', 5);
    return substr(str_shuffle($ref), 0, $length);
}

