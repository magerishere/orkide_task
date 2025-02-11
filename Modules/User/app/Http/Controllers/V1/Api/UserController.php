<?php

namespace Modules\User\Http\Controllers\V1\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;
use Modules\Bank\Models\BankAccountCard;
use Modules\Bank\Repository\Contracts\BankAccountCardRepositoryInterface;
use Modules\Transaction\Repository\Contracts\TransactionRepositoryInterface;
use Modules\User\Http\Requests\V1\Api\UsersWhichMostTransactionsRequest;
use Modules\User\Repository\Contracts\UserRepositoryInterface;

class UserController extends Controller
{
    public function usersWhichMostTransactions(UsersWhichMostTransactionsRequest $request, UserRepositoryInterface $userRepository, TransactionRepositoryInterface $transactionRepository)
    {
        try {
            $topUsersIds = $this->topUsersIdsWhichMostTransactions(limit: 3);

            $users = $userRepository
                ->freshQuery()
                ->whereIn('id', $topUsersIds)
                ->all(relations: [
                    'bankAccountCards' => fn(HasManyThrough $builder) => $builder->whereHas('sentTransactions')->orWhereHas('receivedTransactions'),
                    'bankAccountCards.sentTransactions',
                    'bankAccountCards.receivedTransactions'
                ])
                ->getCollection(asResource: true, closure: function (Collection $collection) {
                    return $collection->map(function (User $user) {
                        $user->transactions = $user->bankAccountCards->map(fn(BankAccountCard $bankAccountCard) => $bankAccountCard->allTransactions)->collapse();

                        $user->makeHidden('bankAccountCards');
                        return $user;
                    });
                });

            return apiResponse([
                'users' => $users,
            ], __('base::v1.successful_loaded'));
        } catch (\Exception $exception) {
            report($exception);
            return apiResponse([], $exception);
        }
    }

    private function topUsersIdsWhichMostTransactions(int $limit = 3)
    {
        $userRepository = app(UserRepositoryInterface::class);
        $transactionTable = app(TransactionRepositoryInterface::class)->getModel()->getTable();
        $bankAccountCardsTable = app(BankAccountCardRepositoryInterface::class)->getModel()->getTable();
        return $userRepository->freshQuery()->getQuery()->select('id')->withCount([
            'bankAccountCards as total_transactions' => function (Builder $builder) use ($transactionTable, $bankAccountCardsTable) {
                $builder->select(DB::raw('COUNT(ref_number)'))->join($transactionTable, function (JoinClause $join) use ($transactionTable, $bankAccountCardsTable) {
                    $join->on("{$transactionTable}.from_bank_account_card_number", '=', "{$bankAccountCardsTable}.number")
                        ->orOn("{$transactionTable}.to_bank_account_card_number", '=', "{$bankAccountCardsTable}.number");
                });
            }
        ])->orderByDesc('total_transactions')->limit(value: $limit)->pluck('id');
    }

}
