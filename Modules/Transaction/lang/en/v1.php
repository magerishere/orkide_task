<?php

return [
    'mail' => [
        'with_draw_card_to_card' => [
            'subject' => 'Withdraw funds ( :bank_name )',
            'title' => 'Bank: :name',
            'body' => 'The amount of 1000 Tomans was withdrawn from your bank account. :card_number',
            'button_text' => 'Details',
        ],
        "deposit_card_to_card" => [
            'subject' => 'Deposit funds ( :bank_name )',
            'title' => 'Bank: :name',
            'body' => 'The amount of 1000 Tomans has been deposited into your bank account. :card_number',
            'button_text' => 'Details',
        ],
    ],
];
