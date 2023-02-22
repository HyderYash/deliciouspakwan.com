<?php
/*Check if function is available (php5.3<)*/
if (false === function_exists('http_response_code')) {
    /* Fallback */

    function http_response_code($code = null)
    {
        static $currentStatus;

        if ($code === null) {
            if ($currentStatus !== null) {
                return $currentStatus;
            }

            $currentStatus = 200;

            if (empty($_SERVER['PHP_SELF']) === false &&
                preg_match('#/RESERVED\.HTTP\-STATUS\-(\d{3})\.html$#', $_SERVER['PHP_SELF'], $match) > 0)
            {
                $currentStatus = (int) $match[1];
            }
        } elseif (is_int($code) && headers_sent() === false) {
            header('X-PHP-Response-Code: ' . $code, true, $code);
            $currentStatus = $code;
        }

        return $currentStatus;
    }
}
?>