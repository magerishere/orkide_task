<?php

namespace Modules\Bank\Http\Controllers\V1\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Modules\Bank\Enums\BankAccountStatus;
use Modules\Bank\Exceptions\BankAccountBalanceNotEnoughException;
use Modules\Bank\Exceptions\BankAccountInActiveException;
use Modules\Bank\Exceptions\UserHasNoBankAccountException;
use Modules\Bank\Http\Requests\V1\Api\BankCardToCardRequest;
use Modules\Bank\Models\BankAccount;
use Modules\Bank\Repository\Contracts\BankAccountCardRepositoryInterface;
use Modules\Bank\Repository\Contracts\BankAccountRepositoryInterface;
use Modules\Transaction\app\Events\CardToCardTransaction;
use Modules\Transaction\app\Mail\DepositCardToCard;
use Modules\Transaction\app\Mail\TransactionOfCardToCard;
use Modules\Transaction\app\Mail\WithdrawCardToCard;
use Modules\Transaction\Enums\TransactionStatus;
use Modules\Transaction\Models\Transaction;
use Modules\Transaction\Repository\Contracts\TransactionRepositoryInterface;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('bank::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('bank::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('bank::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('bank::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }

    public function cardToCard(
        BankCardToCardRequest $request,
        User $user,
        BankAccountCardRepositoryInterface $bankAccountCardRepository,
        BankAccountRepositoryInterface $bankAccountRepository,
        TransactionRepositoryInterface $transactionRepository,
    )
    {
        try {
            $fromCardNumber = $request->get('from_card_number');
            $toCardNumber = $request->get('to_card_number');
            $amount = $request->get('amount');

            [$fromCard, $toCard] = $this->getCards(bankAccountCardRepository: $bankAccountCardRepository, fromCardNumber: $fromCardNumber, toCardNumber: $toCardNumber);

            $bankAccount = $fromCard->account;

            $this->checkBankAccountStatus(bankAccount: $bankAccount);

            $this->checkBankAccountBalance(bankAccount: $bankAccount, amount: $amount);

            $this->createTransactionsOfCardToCard(
                transactionRepository: $transactionRepository,
                fromCardNumber: $fromCardNumber,
                toCardNumber: $toCardNumber,
                amount: $amount
            );

            $bankAccountRepository->decrementBalance(amount: $request->get('amount'));
            /**
             * @var Transaction $transaction
             */
            $transaction = $transactionRepository->update(
                data: [
                    'status' => TransactionStatus::COMPLETED,
                ]
            )->getModel();


            CardToCardTransaction::dispatch($transaction);

            return apiResponse([
                'transaction' => $transactionRepository->getModel(asResource: true),
            ], __('bank::v1.transaction_completed'));
        } catch (\Exception $exception) {
            report($exception);
            return apiResponse([],$exception);
        }
    }

    private function getCards(BankAccountCardRepositoryInterface $bankAccountCardRepository, string $fromCardNumber, string $toCardNumber): array
    {
        return [
            $bankAccountCardRepository->freshQuery()->findById(id: $fromCardNumber)->getModel(),
            $bankAccountCardRepository->freshQuery()->findById(id: $toCardNumber)->getModel(),
        ];
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

    private function createTransactionsOfCardToCard(TransactionRepositoryInterface $transactionRepository, string $fromCardNumber, string $toCardNumber, int $amount): Transaction
    {
        return $transactionRepository->freshQuery()->create([
            'ref_number' => $transactionRepository->newRefNumber(),
            'from_bank_account_card_number' => $fromCardNumber,
            'to_bank_account_card_number' => $toCardNumber,
            'amount' => $amount,
        ])->getModel();
    }
}
