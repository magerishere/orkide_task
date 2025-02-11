<?php

namespace Modules\User\Http\Controllers\V1\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

            $createdAtTransactionsDate = now()->subMinutes(value: 10);
            $users = $userRepository
                ->freshQuery()
                ->whereIn('id', $topUsersIds)
                ->all(relations: [
                    'bankAccountCards' => fn(HasManyThrough $builder) => $builder->whereHas('latestSentTransactions')->orWhereHas('latestReceivedTransactions'),
                ])
                ->getCollection(asResource: true, closure: function (Collection $collection) {
                    return $collection->map(function (User $user) {
                        $user->transactions = $user->bankAccountCards->map(fn(BankAccountCard $bankAccountCard) => $bankAccountCard->allLatestTransactions)->collapse()->take(10);

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

    private function topUsersIdsWhichMostTransactions(int $limit = 3, ?string $transactionCreatedAtDate = null)
    {
        $userRepository = app(UserRepositoryInterface::class);
        $transactionTable = app(TransactionRepositoryInterface::class)->getModel()->getTable();
        $bankAccountCardsTable = app(BankAccountCardRepositoryInterface::class)->getModel()->getTable();

        $transactionCreatedAtDate ??= now()->subMinutes(value: 10);
        return $userRepository->freshQuery()->getQuery()->select('id')->withCount([
            'bankAccountCards as total_transactions' => function (Builder $builder) use ($transactionTable, $bankAccountCardsTable, $transactionCreatedAtDate) {
                $builder->select(DB::raw('COUNT(ref_number)'))->join($transactionTable, function (JoinClause $join) use ($transactionTable, $bankAccountCardsTable, $transactionCreatedAtDate) {
                    $join->whereDate("{$transactionTable}.created_at", '>=', $transactionCreatedAtDate)->on("{$transactionTable}.from_bank_account_card_number", '=', "{$bankAccountCardsTable}.number")
                        ->orOn("{$transactionTable}.to_bank_account_card_number", '=', "{$bankAccountCardsTable}.number");
                });
            }
        ])->orderByDesc('total_transactions')->limit(value: $limit)->pluck('id');
    }

}
