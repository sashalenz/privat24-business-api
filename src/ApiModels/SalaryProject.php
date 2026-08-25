<?php

namespace Sashalenz\Privat24BusinessApi\ApiModels;

use Illuminate\Support\Collection;
use Sashalenz\Privat24BusinessApi\Enums\SalaryGroupType;
use Sashalenz\Privat24BusinessApi\Exceptions\Privat24BusinessApiException;
use Sashalenz\Privat24BusinessApi\RequestData\Salary\ListReceiversRequest;
use Sashalenz\Privat24BusinessApi\RequestData\Salary\UpdateReceiverRequest;
use Sashalenz\Privat24BusinessApi\ResponseData\Salary\UpdateReceiverResponse;
use Sashalenz\Privat24BusinessApi\Types\SalaryGroup;
use Sashalenz\Privat24BusinessApi\Types\SalaryReceiver;

/**
 * pay/mp — the directory of people a mass-payment project may pay.
 *
 * 🔴 This is the half with no equivalent in single payments, and the half that
 * catches callers out. A single payment names an IBAN and the bank pays it. A
 * register names a `receiver` id out of THIS directory, so somebody with
 * perfect bank details on your side still cannot be paid until they are put
 * here — see {@see updateReceiver()}.
 *
 * Keep the returned ids: nothing else about a person will find theirs again
 * except a search through the whole list.
 */
class SalaryProject extends BaseModel
{
    protected string $method = 'pay/mp';

    /**
     * The company's mass-payment projects, with the bank's fee for each.
     *
     * @return Collection<int, SalaryGroup>
     *
     * @throws Privat24BusinessApiException
     */
    public function groups(): Collection
    {
        return $this
            ->setMethod('list-groups')
            ->request()
            ->map(fn (array $row) => SalaryGroup::from($row));
    }

    /**
     * People already in a project. `filter` searches name, tax id and employee
     * number, which is the cheap way to find one person rather than paging
     * through everybody.
     *
     * @return Collection<int, SalaryReceiver>
     *
     * @throws Privat24BusinessApiException
     */
    public function receivers(
        SalaryGroupType|string $group,
        ?int $page = null,
        ?int $pageSize = null,
        ?string $filter = null,
    ): Collection {
        return $this
            ->setMethod('list-receivers')
            ->setParams(new ListReceiversRequest(
                group: $group instanceof SalaryGroupType ? $group->value : $group,
                page: $page,
                pageSize: $pageSize,
                filter: $filter,
            ))
            ->request()
            ->map(fn (array $row) => SalaryReceiver::from($row));
    }

    /**
     * Put a person into the project, or correct somebody already in it.
     *
     * @throws Privat24BusinessApiException
     */
    public function updateReceiver(UpdateReceiverRequest $request): UpdateReceiverResponse
    {
        $this->isPost = true;

        return UpdateReceiverResponse::from(
            $this
                ->setMethod('update-receiver')
                ->setParams($request)
                ->validate([
                    'pan' => ['required', 'string'],
                    'group' => ['required', 'string'],
                    'fio' => ['required', 'array', 'min:1'],
                ])
                ->request()
        );
    }
}
