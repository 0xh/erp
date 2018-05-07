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
            $skus = str_replace("\r\n",",",trim($data));
            $skuArray=array_unique(explode(',', $skus));
            $jdSKU=[];
            $errorSKU='';
            foreach($skuArray as $sku){
                $hasSKU=$this->value->where('task_value',$sku)->where('attribute_id',534)->get();
                if(!$hasSKU->count()){
                    $jdSKU[]=$this->getJdPrdFromSKU($sku);
                }else{
                    $errorSKU .= $sku.'  ';
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
            foreach($input['jd'] as $prd){
                if(isset($prd['ready'])){
                    $taskData=[
                        'title' => 'JD:'.$prd['sku'].' - '.$prd["name"],
                        'status_id' => 1,
                        'type_id' => 2,
                        'hours' => 1,
                        'end_at' => Carbon::now()->toDateTimeString(),
                        'user_id' => $authUser->id,];
                    $task=$this->task->create($taskData);
                    $attributes = [534=> $prd["sku"], 536=> $prd["name"], 535=> $prd["brand"], 537=> $prd["image"]];
                    foreach ($attributes as $attKey=>$attValue) {
                        $this->value->create(['task_id'=>$task->id,'attribute_id'=>$attKey,'task_value'=>$attValue]);
                    }
                }
            }
            admin_toastr('SKU'.trans('task.Import').trans('task.Success'));
        }
        return redirect(admin_base_path('tasks?type=2'));
    }

    public function getJdPrdFromSKU($sku)
    {
        $jsonData=[];
        $data=QueryList::get('https://item.jd.com/'.$sku.'.html');
        $jsonData['sku']=$sku;
        $jsonData['name']=$data->find('.sku-name')->text();
        $jsonData['brand']=$data->find('#parameter-brand li a')->text();
        $images=$data->find('#spec-img')->attrs('data-origin')->first();
        $jsonData['image']=$images;//?str_replace('n5/s54x54_jfs','n1/s450x450_jfs',$images[1]):'';
//        echo '<h1>SKU: '.$jsonData['name'].' <br>品牌: '.$jsonData['brand'].'<br></h1>'.'<img src="'.str_replace('n5/s54x54_jfs','n1/s450x450_jfs',$jsonData['image']).'" />';
        return ($jsonData);
    }
}
