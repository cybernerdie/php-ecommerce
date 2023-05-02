<?php

namespace Core\Migration;

class DatabaseTableMigration
{
    public function run()
    {
        (new UserTableMigration())->run();
        (new TokenTableMigration())->run();
        (new ProductTableMigration())->run();
        (new CartTableMigration())->run();
        (new OrderTableMigration())->run();
        (new OrderItemTableMigration())->run();
        (new OrderPaymentTableMigration())->run();
    }
}