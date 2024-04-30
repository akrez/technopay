<?php

namespace App\Enums\Order;

enum OrderStatusEnum: string
{
    case ACTIVE = 'active';
    case DEACTIVE = 'deactive';
}
