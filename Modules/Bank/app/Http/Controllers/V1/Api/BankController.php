<?php

namespace Modules\Bank\Http\Controllers\V1\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Modules\Bank\Enums\BankAccountCardStatus;
use Modules\Bank\Enums\BankAccountStatus;
use Modules\Bank\Exceptions\BankAccountBalanceNotEnoughException;
use Modules\Bank\Exceptions\BankAccountCardInActiveException;
use Modules\Bank\Exceptions\BankAccountInActiveException;
use Modules\Bank\Exceptions\OriginBankAccountCardDoesNotBelongsToUserException;
use Modules\Bank\Exceptions\UserHasNoBankAccountException;
use Modules\Bank\Http\Requests\V1\Api\BankCardToCardRequest;
use Modules\Bank\Models\BankAccount;
use Modules\Bank\Models\BankAccountCard;
use Modules\Bank\Repository\Contracts\BankAccountCardRepositoryInterface;
use Modules\Bank\Repository\Contracts\BankAccountRepositoryInterface;
use Modules\Transaction\Events\CardToCardTransaction;
use Modules\Transaction\Enums\TransactionStatus;
use Modules\Transaction\Models\Transaction;
use Modules\Transaction\Repository\Contracts\TransactionRepositoryInterface;

class BankController extends Controller
{
    public function cardToCard(
        BankCardToCardRequest $request,
        User $user,
        BankAccountCardRepositoryInterface $bankAccountCardRepository,
        BankAccountRepositoryInterface $bankAccountRepository,
        TransactionRepositoryInterface $transactionRepository,
    )
    {
        try {

            $transaction = null;
            DB::transaction(function () use ($request, $user, $bankAccountRepository, $bankAccountCardRepository, $transactionRepository, &$transaction) {
                $fromCardNumber = $request->get('from_card_number');
                $toCardNumber = $request->get('to_card_number');
                $amount = $request->get('amount');

                [$fromCard, $toCard] = $this->getCards(fromCardNumber: $fromCardNumber, toCardNumber: $toCardNumber);
                $this->checkCardOwner(bankAccountCard: $fromCard, user: $user);
                $this->checkBankAccountCardStatus(bankAccountCard: $fromCard);
                $this->checkBankAccountCardStatus(bankAccountCard: $toCard);

                $fromBankAccount = $fromCard->account;
                $this->checkBankAccountStatus(bankAccount: $fromBankAccount);
                $this->checkBankAccountBalance(bankAccount: $fromBankAccount, amount: $amount);

                $toBankAccount = $toCard->account;
                $this->checkBankAccountStatus(bankAccount: $toBankAccount);

                $transaction = $this->createTransactionsOfCardToCard(
                    fromCardNumber: $fromCardNumber,
                    toCardNumber: $toCardNumber,
                    amount: $amount
                );

                $bankAccountRepository->decrementBalance(amount: $request->get('amount'));
                /**
                 * @var Transaction $transaction
                 */
                $transaction = $transactionRepository->findById($transaction->ref_number)->update(
                    data: [
                        'status' => TransactionStatus::COMPLETED,
                    ]
                )->getModel();
            }, 3);

            if ($transaction) {
                $this->cardToCardEvents(transaction: $transaction);
            }

            return apiResponse([
                'transaction' => $transactionRepository->getModel(asResource: true),
            ], __('bank::v1.transaction_completed'));
        } catch (\Exception $exception) {
            report($exception);
            return apiResponse([],$exception);
        }
    }

    private function getCards(string $fromCardNumber, string $toCardNumber): array
    {
        $bankAccountCardRepository = app(BankAccountCardRepositoryInterface::class);
        return [
            $bankAccountCardRepository->freshQuery()->findById(id: $fromCardNumber)->getModel(),
            $bankAccountCardRepository->freshQuery()->findById(id: $toCardNumber)->getModel(),
        ];
    }

    private function checkCardOwner(BankAccountCard $bankAccountCard, User $user): void
    {
        if (strval($user->mobile) !== strval($bankAccountCard->user->mobile)) {
            throw new OriginBankAccountCardDoesNotBelongsToUserException();
        }
    }

    private function checkBankAccountStatus(?BankAccount $bankAccount): void
    {
        if (!$bankAccount) {
            throw new UserHasNoBankAccountException();
        }
        if ($bankAccount->status == BankAccountStatus::INACTIVE) {
            throw new BankAccountInActiveException(__('bank::v1.errors.bank_account_in_active', ['account_number' => $bankAccount->number]));
        }
    }

    private function checkBankAccountBalance(?BankAccount $bankAccount, int $amount): void
    {
        if (!$bankAccount->checkBalance(amount: $amount)) {
            throw new BankAccountBalanceNotEnoughException();
        }
    }

    public function checkBankAccountCardStatus(?BankAccountCard $bankAccountCard): void
    {
        if ($bankAccountCard->status == BankAccountCardStatus::INACTIVE) {
            throw new BankAccountCardInActiveException(__('bank::v1.errors.bank_account_card_in_active', [
                'card_number' => $bankAccountCard->number
            ]));
        }
    }

    private function createTransactionsOfCardToCard(string $fromCardNumber, string $toCardNumber, int $amount): Transaction
    {
        $transactionRepository = app(TransactionRepositoryInterface::class);
        return $transactionRepository->freshQuery()->create([
            'ref_number' => $transactionRepository->newRefNumber(),
            'from_bank_account_card_number' => $fromCardNumber,
            'to_bank_account_card_number' => $toCardNumber,
            'amount' => $amount,
        ])->getModel();
    }

    private function cardToCardEvents(Transaction $transaction): void
    {
        CardToCardTransaction::dispatch($transaction);
    }
}
