
               <div class="box box-default">
                   <div class="box-header with-border">
                       <h3 class="box-title">@lang('task.Step') 1</h3>
                   </div>
                   <div class="box-body">
                       <form method="POST" action="{{ route('import.sku2jd') }}" accept-charset="UTF-8" onsubmit="javascript:$('#btnsubmit').attr('disabled','disabled')">
                           <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                           <div class="form-group col-sm-4">
                               <label>订单名称</label>
                               <input class="form-control" required="required" name="project_name" value="{{ isset($input['project_name']) ? $input['project_name'] : '' }}" />
                           </div>
                           <div class="form-group col-sm-2">
                               <label>订单金额</label>
                               <input class="form-control" required="required" name="jd_price" value="{{ isset($input['jd_price']) ? $input['jd_price'] : '' }}" />
                           </div>
                           <div class="form-group col-sm-1">
                               <label>订单ID</label>
                               <input class="form-control" required="required" name="jd_id" value="{{ isset($input['jd_id']) ? $input['jd_id'] : '' }}" />
                           </div>
                           <div class="form-group col-sm-1">
                               <label>截止日期</label>
                               <input class="form-control" required="required" name="jd_end_at" value="{{ isset($input['jd_end_at']) ? $input['jd_end_at'] : '' }}" />
                           </div>
                           <div class="form-group col-sm-2">
                               <label>公司名称</label>
                               <input class="form-control" required="required" name="company_name" value="{{ isset($input['company_name']) ? $input['company_name'] : '' }}" />
                           </div>
                           <div class="form-group col-sm-2">
                               <label>公司电话</label>
                               <input class="form-control" required="required" name="company_tel" value="{{ isset($input['company_tel']) ? $input['company_tel'] : '' }}" />
                           </div>
                           <div class="form-group col-sm-12">
                               <label for="SKU">SKU, 项目渠道, 素材提供类型, 产品外观尺寸, 期望上线日期, 项目交付日期, 价格, 工期, 备注</label>
                               <textarea class="form-control" style="height:350px" required="required" name="data" cols="50" rows="10" id="SKU">{{ isset($input['data']) ? $input['data'] : '' }}</textarea>
                           </div>

                           <!-- Submit Field -->
                           <div class="form-group col-sm-12">
                               <input class="btn btn-primary" id="btnsubmit" type="submit" value="@lang('task.Import')">
                               <a href="{!! route('tasks.index') !!}" class="btn btn-default"> @lang('task.Cancel')</a>
                           </div>
                       </form>
                   </div>
               </div>
           @if(isset($jdSKU))
               <div class="box box-success">
                   <div class="box-header with-border">
                       <h3 class="box-title">@lang('task.Step') 2</h3>
                   </div>
                   <div class="box-body">
                       <form method="POST" action="{{ route('import.jd2task') }}" accept-charset="UTF-8" onsubmit="javascript:$('#btn_save').attr('disabled','disabled')">
                           <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                           <input type="hidden" name="project_name" value="{{ $input['project_name'] }}"/>
                           <input type="hidden" name="jd_price" value="{{ $input['jd_price'] }}"/>
                           <input type="hidden" name="jd_id" value="{{ $input['jd_id'] }}"/>
                           <input type="hidden" name="jd_id" value="{{ $input['jd_end_at'] }}"/>
                           <input type="hidden" name="company_name" value="{{ $input['company_name'] }}"/>
                           <input type="hidden" name="company_tel" value="{{ $input['company_tel'] }}"/>
                       <div class="form-group table-responsive col-sm-12">
                           <table class="table">
                               <thead>
                               <tr>
                                   <th>@lang('task.SKU')</th>
                                   <th>@lang('task.Name')</th>
                                   <th>@lang('task.Brand')</th>
                                   <th>@lang('task.Image')</th>
                                   <th>项目渠道</th>
                                   <th>素材提供类型</th>
                                   <th>产品外观尺寸</th>
                                   <th>期望上线日期</th>
                                   <th>项目交付日期</th>
                                   <th>价格</th>
                                   <th>工期</th>
                                   <th>备注</th>
                                   {{--<th colspan="2"></th>--}}
                               </tr>
                               </thead>
                               <tbody>
                               @foreach($jdSKU as $prd)
                                   <tr id="prd{{ $prd['sku'] }}">
                                       <td>{{ $prd['sku'] }}<br/>
                                           <input type="checkbox" name="jd[{{ $prd['sku'] }}][ready]" checked />@lang('task.Confirmed')
                                           <input type="hidden" name="jd[{{ $prd['sku'] }}][sku]" value="{{ $prd['sku'] }}"/></td>
                                       <td title="{{ $prd['name'] }}">{{ str_limit($prd['name'],60) }}
                                           <input type="hidden" name="jd[{{ $prd['sku'] }}][name]" value="{{ $prd['name'] }}"/></td>
                                       <td>{{ $prd['brand'] }}
                                           <input type="hidden" name="jd[{{ $prd['sku'] }}][brand]" value="{{ $prd['brand'] }}"/></td>
                                       <td><img src="{{ $prd['image'] }}" style="width: 150px;" />
                                           <input type="hidden" name="jd[{{ $prd['sku'] }}][image]" value="{{ $prd['image'] }}"/></td>
                                       <td><input type="text" name="jd[{{ $prd['sku'] }}][attr538]" value="{{ $prd['attr538'] }}"/></td>
                                       <td><input type="text" name="jd[{{ $prd['sku'] }}][attr539]" value="{{ $prd['attr539'] }}"/></td>
                                       <td><input type="text" name="jd[{{ $prd['sku'] }}][attr566]" value="{{ $prd['attr566'] }}"/></td>
                                       <td><input type="text" name="jd[{{ $prd['sku'] }}][attr540]" value="{{ $prd['attr540'] }}"/></td>
                                       <td><input type="text" name="jd[{{ $prd['sku'] }}][attr567]" value="{{ $prd['attr567'] }}"/></td>
                                       <td><input type="text" name="jd[{{ $prd['sku'] }}][price]" value="{{ $prd['price'] }}"/></td>
                                       <td><input type="text" name="jd[{{ $prd['sku'] }}][time_limit]" value="{{ $prd['time_limit'] }}"/></td>
                                       <td><input type="text" name="jd[{{ $prd['sku'] }}][attr568]" value="{{ $prd['attr568'] }}"/></td>
                                   {{--<td width="80"><a class="btn btn-primary" href="{{ URL::route('users.edit', $user->id) }}">@lang('task.edit')</a></td>--}}
                                   {{--<td width="80">{!! Form::open(['route' => ['users.update', $user->id], 'method' => 'DELETE']) !!}--}}
                                   {{--{!! Form::submit(trans('task.Delete'), ['class' => 'btn btn-danger', 'onclick' => 'return confirm("Are you sure?");']) !!}--}}
                                   {{--{!!  Form::close() !!}</td>--}}
                                   </tr>
                               @endforeach
                               </tbody>
                           </table>
                       </div>
                       <!-- Submit Field -->
                       <div class="form-group col-sm-12">
                           <input class="btn btn-primary" id="btn_save" type="submit" value="@lang('task.Save')">
                           <a href="{!! route('tasks.index') !!}" class="btn btn-default"> @lang('task.Cancel')</a>
                       </div>
                       </form>
                   </div>
               </div>
           @endif