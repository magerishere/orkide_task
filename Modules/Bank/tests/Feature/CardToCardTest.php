<?php

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Bank\Enums\BankAccountCardStatus;
use Modules\Bank\Enums\BankAccountStatus;
use Modules\Bank\Repository\Contracts\BankAccountCardRepositoryInterface;
use Modules\User\Repository\Contracts\UserRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;

uses(RefreshDatabase::class);

test('route card to card exists', function () {
    $userRepository = app(UserRepositoryInterface::class);
    $user = $userRepository->first()->getModel();
    $response = $this->post(route('api.v1.banks.card-to-card', $user->mobile));

    $this->assertNotEquals(Response::HTTP_NOT_FOUND, $response->status());
});

test('can not use invalid origin ( from ) card number', function () {
    $userRepository = app(UserRepositoryInterface::class);
    $user = $userRepository->first()->getModel();

    $response = $this->post(route('api.v1.banks.card-to-card', $user->mobile), [
        'from_card_number' => '123456',
    ]);

    $response->assertInvalid([
        'from_card_number'
    ]);
});

test('can not use invalid destination ( to ) card number', function () {
    $userRepository = app(UserRepositoryInterface::class);
    $user = $userRepository->first()->getModel();

    $response = $this->post(route('api.v1.banks.card-to-card', $user->mobile), [
        'to_card_number' => '123456',
    ]);

    $response->assertInvalid([
        'to_card_number'
    ]);
});


test('can not use amount less than 1000', function () {
    $userRepository = app(UserRepositoryInterface::class);
    $user = $userRepository->first()->getModel();

    $response = $this->post(route('api.v1.banks.card-to-card', $user->mobile), [
        'amount' => '999',
    ]);

    $response->assertInvalid([
        'amount'
    ]);
});

test('can not use amount greater than 50000000', function () {
    $userRepository = app(UserRepositoryInterface::class);
    $user = $userRepository->first()->getModel();

    $response = $this->post(route('api.v1.banks.card-to-card', $user->mobile), [
        'amount' => '50000001',
    ]);

    $response->assertInvalid([
        'amount'
    ]);
});


test('bank account balance decrement after transaction completed.', function () {
    $userRepository = app(UserRepositoryInterface::class);
    $user = $userRepository->whereHas('bankAccounts', fn($query) => $query->where('status', BankAccountStatus::ACTIVE->value)
        ->whereRelation('cards', 'status', BankAccountCardStatus::ACTIVE->value))
        ->first()
        ->getModel();

    $bankAccount = $user->bankAccounts->where('status', BankAccountStatus::ACTIVE->value)->first();
    $oldBalance = $bankAccount->balance;
    $fromBankAccountCard = $bankAccount->cards->where('status', BankAccountCardStatus::ACTIVE->value)->first();

    $bankAccountCardRepository = app(BankAccountCardRepositoryInterface::class);
    $toBankAccountCard = $bankAccountCardRepository->freshQuery()->where('status', BankAccountCardStatus::ACTIVE)->randomly()->first()->getModel();

    while (strval($toBankAccountCard->number) === strval($fromBankAccountCard->number)) {
        $toBankAccountCard = $bankAccountCardRepository->freshQuery()->randomly()->first()->getModel();
    }

    $amount = 300000;
    $response = $this->post(route('api.v1.banks.card-to-card', $user->mobile), [
        'from_card_number' => $fromBankAccountCard->number,
        'to_card_number' => $toBankAccountCard->number,
        'amount' => $amount,
    ]);

    $response->assertOk();

    $bankAccount = $bankAccount->fresh();

    expect(intval($oldBalance - $amount))->toBe(intval($bankAccount->balance));
});
