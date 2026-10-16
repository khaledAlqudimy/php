<?php

namespace App\Exceptions;

use RuntimeException;

/**
 * خطأ متوقع أثناء إتمام الطلب — رسالته تُعرض للعميل كما هي
 */
class CheckoutException extends RuntimeException
{
}
