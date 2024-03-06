<?php

namespace App\Http\Controllers\PriceList\BO;

use App\Http\Controllers\Controller;
use App\Http\Dto\File\UploadedFileDto;
use App\Models\PriceList;
use App\Models\User\User;
use App\Services\PriceList\BO\PriceListService;
use Illuminate\Http\JsonResponse;

class PriceListController extends Controller
{
    /**
     * PriceListController constructor.
     *
     * @param PriceListService $priceListService
     */
    public function __construct(private readonly PriceListService $priceListService)
    {
    }

    /**
     * Return all price lists.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->OK($this->priceListService->index());
    }

    /**
     * Store a new price list.
     *
     * @param UploadedFileDto $dto
     *
     * @return JsonResponse
     */
    public function store(UploadedFileDto $dto): JsonResponse
    {
        $this->priceListService->create($dto);

        return $this->OK();
    }

    /**
     * Attach user to price list.
     *
     * @param User $user
     * @param PriceList $priceList
     *
     * @return JsonResponse
     */
    public function attachUser(PriceList $priceList, User $user): JsonResponse
    {
        $this->priceListService->attachUser($user, $priceList);

        return $this->OK();
    }
}
