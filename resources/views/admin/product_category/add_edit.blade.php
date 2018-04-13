<form  action="{{ $action }}" method="POST">
{{ csrf_field() }}
@if(isset($model->id))
    {{ method_field("PUT") }}
@endif
<!-- begin row -->
    <div class="row">
        <!-- begin col-12 -->
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-header">
                </div>
                <div class="box-body">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label class="control-label col-md-2">{{ trans('admin.parent') }}：</label>
                            <div class="col-md-8">
                                <select name="parent_id" class="form-control select2" style="width: 100%">
                                    <option value="0">{{ trans('admin.none') }}</option>
                                    @recursive($data)
                                    @continue(isset($args[1]) && $args[1] == $val->id)
                                    <option {{ $val->id==old('parent_id', $args[0])?'selected':'' }} value="{{ $val->id }}">{{ str_repeat('&nbsp;', $depth*3) }}{{ $val->title }}</option>
                                    @nextrecursive
                                    </li>
                                    @endrecursive($model->parent_id, $model->id)
                                </select>
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('title')?"has-error":"" }}">
                            <label class="control-label col-md-2">{{ trans('admin.title') }}：</label>
                            <div class="col-md-8">
                                @if($errors->has('title'))
                                    <label class="control-label">
                                        <i class="fa fa-times-circle-o"></i>{{$errors->first('title')}}
                                    </label>
                                @endif
                                <input type="text" name="title" class="form-control"
                                       value="{{ old('title', $model->title)}}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer clearfix">
                    <button type="submit" class="pull-right btn btn-primary"
                            style="width: 120px">{{ trans('admin.save') }}</button>
                </div>
            </div>
        </div>
        <!-- end panel -->
        <!-- end col-12 -->
    </div>
</form>
<!-- end row -->

<!-- end #content -->

<script>
    $(function () {
        $('.select2').select2();
    });
</script>

