<?php

namespace App\Enums\Order;

use App\Enums\Enum;

enum OrderStatusEnum: string
{
    use Enum;

    case ACTIVE = 'active';
    case DEACTIVE = 'deactive';
}
