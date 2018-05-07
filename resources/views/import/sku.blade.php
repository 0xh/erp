
    <section class="content-header">
        <h1>
            @lang('task.Create') SKU @lang('task.Import') - JD
            <a href="{!! route('tasks.index') !!}" class="btn btn-default">@lang('task.Back')</a>
        </h1>
   </section>
   <div class="content">
       <div class="row">
           <div class="col-md-4">
               <div class="box box-default">
                   <div class="box-header with-border">
                       <h3 class="box-title">@lang('task.Step') 1</h3>
                   </div>
                   <div class="box-body">
                       <form method="POST" action="{{ route('import.sku2jd') }}" accept-charset="UTF-8" onsubmit="javascript:$('#btnsubmit').attr('disabled','disabled')">
                           <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                           <div class="form-group col-sm-12">
                               <label for="SKU">SKU</label>
                               <textarea class="form-control" style="height:350px" required="required" name="data" cols="50" rows="10" id="SKU">{{ isset($data) ? $data : '' }}</textarea>
                           </div>

                           <!-- Submit Field -->
                           <div class="form-group col-sm-12">
                               <input class="btn btn-primary" id="btnsubmit" type="submit" value="@lang('task.Import')">
                               <a href="{!! route('tasks.index') !!}" class="btn btn-default"> @lang('task.Cancel')</a>
                           </div>
                       </form>
                   </div>
               </div>
           </div>
           @if(isset($jdSKU))
           <div class="col-md-8">
               <div class="box box-success">
                   <div class="box-header with-border">
                       <h3 class="box-title">@lang('task.Step') 2</h3>
                   </div>
                   <div class="box-body">
                       <form method="POST" action="{{ route('import.jd2task') }}" accept-charset="UTF-8" onsubmit="javascript:$('#btn_save').attr('disabled','disabled')">
                           <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                       <div class="form-group col-sm-12">
                           <table class="table">
                               <thead>
                               <tr>
                                   <th>@lang('task.SKU')</th>
                                   <th>@lang('task.Name')</th>
                                   <th>@lang('task.Brand')</th>
                                   <th>@lang('task.Image')</th>
                                   {{--<th colspan="2"></th>--}}
                               </tr>
                               </thead>
                               <tbody>
                               @foreach($jdSKU as $prd)
                                   <tr id="prd{{ $prd['sku'] }}">
                                       <td>{{ $prd['sku'] }}<br/>
                                           <input type="checkbox" name="jd[{{ $prd['sku'] }}][ready]" checked />@lang('task.Confirmed')
                                           <input type="hidden" name="jd[{{ $prd['sku'] }}][sku]" value="{{ $prd['sku'] }}"/></td>
                                       <td>{{ $prd['name'] }}
                                           <input type="hidden" name="jd[{{ $prd['sku'] }}][name]" value="{{ $prd['name'] }}"/></td>
                                       <td>{{ $prd['brand'] }}
                                           <input type="hidden" name="jd[{{ $prd['sku'] }}][brand]" value="{{ $prd['brand'] }}"/></td>
                                       <td><img src="{{ $prd['image'] }}" style="width: 150px;" />
                                           <input type="hidden" name="jd[{{ $prd['sku'] }}][image]" value="{{ $prd['image'] }}"/></td>
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
           </div>
           @endif
       </div>
   </div>