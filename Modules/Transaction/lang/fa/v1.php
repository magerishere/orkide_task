<?php

return [
    'mail' => [
        'with_draw_card_to_card' => [
            'subject' => 'برداشت وجه  ( :bank_name )',
            'title' => 'بانک: :name',
            'body' => 'مبلغ :amount تومان از حساب بانکی شما برداشت شد. :card_number',
            'button_text' => 'جزییات',
        ],
        "deposit_card_to_card" => [
            'subject' => 'واریز وجه  ( :bank_name )',
            'title' => 'بانک: :name',
            'body' => 'مبلغ :amount تومان به حساب بانکی شما واریز شد. :card_number',
            'button_text' => 'جزئیات',
        ],
    ],
];
