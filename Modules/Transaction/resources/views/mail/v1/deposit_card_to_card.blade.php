<x-mail::message>
# {{__('transaction::v1.mail.deposit_card_to_card.title', ['name' => $bankName])}}

{{__('transaction::v1.mail.deposit_card_to_card.body', ['amount' => $amount, 'card_number' => $cardNumber]) }}

<x-mail::button :url="''">
    {{__('transaction::v1.mail.deposit_card_to_card.button_text')}}
</x-mail::button>

<br>
{{ config('app.name') }}
</x-mail::message>
