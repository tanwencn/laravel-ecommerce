@extends('admin::layouts.app')

@section('title', trans('admin.'.($model->id?'edit_product_attribute':'add_product_attribute')))

@section('breadcrumbs') <li><a href="{{ Admin::action('index') }}"> {{ trans_choice('admin.product_attribute', 1) }}</a></li> @endsection

@section('content')
<form data-pjax="true"
      action="{{ isset($model->id)?Admin::action('update', $model->id):Admin::action('store') }}"
      method="POST">
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

                    <div class="row">
                        <div class="col-sm-2"><h4 class="pull-right">{{ trans('admin.attribute_values') }}</h4>
                        </div>
                        <div class="col-sm-8"></div>
                    </div>

                    <hr style="margin-top: 0px;">

                    <div class="has-many-paintings-forms">
                        @foreach($model->children as $key => $child)
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label class="control-label col-md-2">{{ trans('admin.name') }}：</label>
                                    <div class="col-md-8">
                                        <input type="text" name="children[{{ $key }}][title]" class="form-control"
                                               value="{{ $child->title }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"></label>
                                    <div class="col-sm-8">
                                        <div class="remove btn btn-warning btn-sm pull-right">
                                            <i class="fa fa-trash">&nbsp;</i>{{ trans('admin.delete') }}</div>
                                    </div>
                                </div>
                                <hr>
                            </div>
                        @endforeach
                    </div>

                    <template class="paintings-tpl">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="control-label col-md-2">{{ trans('admin.name') }}：</label>
                                <div class="col-md-8">
                                    <input type="text" name="children[__KEY__][title]" class="form-control"
                                           value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"></label>
                                <div class="col-sm-8">
                                    <div class="remove btn btn-warning btn-sm pull-right">
                                        <i class="fa fa-trash">&nbsp;</i>{{ trans('admin.delete') }}</div>
                                </div>
                            </div>
                            <hr>
                        </div>
                    </template>


                    <div class="form-horizontal">
                        <div class="form-group">
                            <label class="col-sm-2 control-label"></label>
                            <div class="col-sm-8">
                                <div class="add btn btn-success btn-sm">
                                    <i class="fa fa-plus"></i>&nbsp;{{ trans('admin.add') }}</div>
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
    var index = 0;
    $(function () {
        $('.select2').select2();

        $('.add').click(function () {

            var tpl = $('template.paintings-tpl');

            var template = tpl.html().replace(/__KEY__/g, index);
            $('.has-many-paintings-forms').append(template);

            index++;
        });

        $('.has-many-paintings-forms').on('click', '.remove', function () {
            $(this).closest('.form-horizontal').remove();
        });
    });
</script>

@endsection

