<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use QL\QueryList;
use Carbon\Carbon;
use Encore\Admin\Models\Task\Task;
use Encore\Admin\Models\Task\Value;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Encore\Admin\Exception\Handler;

class WebcrawlerController extends Controller
{
    private $task;
    private $value;

    public function __construct(Task $task, Value $value){
        $this->task = $task;
        $this->value = $value;
    }
    //
    public function jdSkuImport()
    {
        return Admin::content(function (Content $content) {
            $content->header(trans('task.Import').'SKU');
            $content->description('...');
            $content->body(view('import.sku')->render());
        });
    }

    public function sku2JD()
    {
        return Admin::content(function (Content $content) {
            $content->header(trans('task.Import').'SKU');
            $content->description('...');
            $input = Input::all();
            $data = isset($input['data']) ? $input['data'] : '';
            $dataString = str_replace("\r\n",",",trim($data));
            $dataArray=array_unique(explode(',', $dataString));
            foreach ($dataArray as $key=>$value){
                $dataArray[$key] = explode("\t", $value);
            }
//            dd($dataArray);
            $jdSKU=[];
            $errorSKU='';
            foreach($dataArray as $value){
                $hasSKU=$this->value->where('task_value',$value[0])->where('attribute_id',534)->get();
                if(!$hasSKU->count()){
                    $jdSKU[]=$this->getJdPrdFromSKU($value);
                }else{
                    $errorSKU .= $value[0].'  ';
                }
            }
            if($errorSKU){
                Handler::error(trans('task.Error'),'SKU: '.$errorSKU.trans('task.Duplicate'));
//                admin_toastr('SKU: '.$errorSKU.trans('task.Duplicate'),'error');
            } else {
                admin_toastr(trans('task.Import').trans('task.Check').trans('task.Content'));
            }
            $content->body(view('import.sku',compact('skuArray','jdSKU','data'))->render());
        });
    }

    public function jdSku2Task(Request $request)
    {
        $input = $request->all();
        $authUser=Admin::user();
        if(isset($input['jd'])){
            $taskData=[
                'title' => 'JD:'.$prd['sku'].' - '.$prd["name"],
                'status_id' => 1,
                'type_id' => 2,
                'price' => $data[6],
                'time_limit' => $data[7],
                'hours' => 1,
                'end_at' => Carbon::now()->toDateTimeString(),
                'user_id' => $authUser->id,];
            $attributes = [569=>$input[''], 573=>$input[''], 574=>$input[''], 575=>$input[''],];
            $this->saveTask($taskData,$attributes);
            foreach($input['jd'] as $prd){
                if(isset($prd['ready'])){
                    //SKU, 项目渠道, 素材提供类型, 产品外观尺寸, 期望上线日期, 项目交付日期, 价格, 工期, 备注
                    $taskData=[
                        'title' => 'JD:'.$prd['sku'].' - '.$prd["name"],
                        'status_id' => 1,
                        'type_id' => 2,
                        'price' => $data[6],
                        'time_limit' => $data[7],
                        'hours' => 1,
                        'end_at' => Carbon::now()->toDateTimeString(),
                        'user_id' => $authUser->id,];
                    $attributes = [534=> $prd["sku"], 536=> $prd["name"], 535=> $prd["brand"], 537=> $prd["image"],
                        538=> $prd["attr538"], 539=> $prd["attr539"], 566=> $prd["attr566"], 540=> $prd["attr540"],
                        567=> $prd["attr567"], 568=> $prd["attr568"]];
                    $this->saveTask($taskData,$attributes);
                }
            }
            admin_toastr('SKU'.trans('task.Import').trans('task.Success'));
        }
        return redirect(admin_base_path('tasks?type=2'));
    }

    public function saveTask($taskData,$attributes)
    {
        $task=$this->task->create($taskData);
        foreach ($attributes as $attKey=>$attValue) {
            $this->value->create(['task_id'=>$task->id,'attribute_id'=>$attKey,'task_value'=>$attValue]);
        }
    }

    public function getJdPrdFromSKU($data)
    {
        $jsonData=[];
        $ql=QueryList::get('https://item.jd.com/'.$data[0].'.html');
        $jsonData['sku']=$data[0];
        $jsonData['name']=$ql->find('.sku-name')->text();
        $jsonData['brand']=$ql->find('#parameter-brand li a')->text();
        $images=$ql->find('#spec-img')->attrs('data-origin')->first();
        $jsonData['image']=$images;//?str_replace('n5/s54x54_jfs','n1/s450x450_jfs',$images[1]):'';
        //SKU, 项目渠道, 素材提供类型, 产品外观尺寸, 期望上线日期, 项目交付日期, 价格, 工期, 备注
        $jsonData['attr538']=$data[1];
        $jsonData['attr539']=$data[2];
        $jsonData['attr566']=$data[3];
        $jsonData['attr540']=date('Y-m-d H:i:s', strtotime($data[4]));
        $jsonData['attr567']=date('Y-m-d H:i:s', strtotime($data[5]));
        $jsonData['price']=$data[6];
        $jsonData['time_limit']=$data[7];
        $jsonData['attr568']=isset($data[8])?$data[8]:'';
//        echo '<h1>SKU: '.$jsonData['name'].' <br>品牌: '.$jsonData['brand'].'<br></h1>'.'<img src="'.str_replace('n5/s54x54_jfs','n1/s450x450_jfs',$jsonData['image']).'" />';
        return ($jsonData);
    }
}
