<?php

namespace Jambo\Seat\QQ\Http\Resources;

use Jambo\Seat\QQ\Models\QQInfo;
use Seat\Api\Http\Controllers\Api\v2\ApiController;
use Seat\Web\Models\User;

/**
 * Class QQApiController.
 * @package Jambo\Seat\QQ\Http\Controllers
 */

class QQApiController extends ApiController
{

    /**
     * @OA\Get(
     *      path="/v2/qq/bind-qq/{qq}",
     *      tags={"QQ绑定"},
     *      summary="获取QQ绑定信息",
     *      description="获取QQ绑定信息，返回Json格式",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
     *      @OA\Parameter(
     *          name="qq",
     *          description="查询QQ号",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *          in="path"
     *      ),
     *      @OA\Response(response=200, description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  type="array",
     *                  property="qq",
     *                  description="Date in YYYY-MM-DD format, always reverting to the first day of the month",
     *                  @OA\Items(
     *                      type="integer"
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=401, description="UnJamboized"),
     *     )
     *
     * @param int $limit
     */
    public function getQQInfo($qq = null)
    {
        // return 404 if status is not recognized
        if(! $qq){
            return response([], 404);
        }

        $qqinfo = QQinfo::where('qq', '=', $qq)->first()['char_id'];

        return response()->json([
            $qq => $qqinfo
        ]);;
    }
}
