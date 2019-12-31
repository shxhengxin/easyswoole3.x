<?php


namespace App\Server;


use App\Model\Admin\VideoModel;
use EasySwoole\Http\Message\Status;
use Response\ApiResponse;

class IndexService
{

    public function getVideo()
    {
        $page = 1;
        $limit = 2;
        $model = VideoModel::create()->limit($limit*($page-1),$limit)->withTotalCount();
        $list = $model->all();
        $total = $model->lastQueryResult()->getTotalCount();
        $arr = ['total' => $total, 'list' => $list];
        return ApiResponse::returnResults(Status::CODE_OK,"成功",$arr);
    }
}