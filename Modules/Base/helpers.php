<?php

if (!function_exists('isApiRequest')) {
    function isApiRequest(\Illuminate\Http\Request $request): bool
    {
        return $request->is('api/*');
    }
}

if (!function_exists('apiResponse')) {
    function apiResponse(array $data = [], string|Exception $message = '', ?int $status = null): \Illuminate\Http\JsonResponse
    {
        if ($message instanceof Exception) {
            if (is_null($status)) {
                $status = $message->getCode();
            }
            $message = $message->getMessage();
        }

        $status = $status ?: \Symfony\Component\HttpFoundation\Response::HTTP_OK;

        $responseData = [
            'result' => $data,
            'message' => $message,
        ];
        return response()->json(data: $responseData, status: $status);
    }
}


if (!function_exists('faToEn')) {
    function faToEn($string): string
    {
        $newNumbers = range(0, 9);
        // 1. Persian HTML decimal
        $persianDecimal = array('&#1776;', '&#1777;', '&#1778;', '&#1779;', '&#1780;', '&#1781;', '&#1782;', '&#1783;', '&#1784;', '&#1785;');
        // 2. Arabic HTML decimal
        $arabicDecimal = array('&#1632;', '&#1633;', '&#1634;', '&#1635;', '&#1636;', '&#1637;', '&#1638;', '&#1639;', '&#1640;', '&#1641;');
        // 3. Arabic Numeric
        $arabic = array('٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩');
        // 4. Persian Numeric
        $persian = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');

        $string = str_replace($persianDecimal, $newNumbers, $string);
        $string = str_replace($arabicDecimal, $newNumbers, $string);
        $string = str_replace($arabic, $newNumbers, $string);
        return str_replace($persian, $newNumbers, $string);
    }
}

if (!function_exists('randomDigits')) {
    function randomDigits(int $length): string
    {
        $result = '';

        for ($i = 0; $i < $length; $i++) {
            $result .= random_int(0, 9);
        }

        return $result;
    }
}
