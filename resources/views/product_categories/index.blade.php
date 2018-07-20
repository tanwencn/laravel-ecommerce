@extends('admin::layouts.app')

@section('title', trans_choice('admin.product_attribute', 1))

@section('content')
<div class="callout" style="background-color: #ddd !important; border-left-color: #aaa !important;">
    <h4>{{ trans('admin.tip') }}!</h4>

    <p>{{ trans('admin.adjust_sort_tip') }}</p>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="box box-default">
            <div class="box-header">
                <div class="btn-group">
                    <button class="btn btn-default btn-sm tools" data-action="expand">
                        <i class="fa fa-plus-square-o"></i>&nbsp;{{ trans('admin.expand_all') }}
                    </button>
                    <button class="btn btn-default btn-sm tools" data-action="collapse">
                        <i class="fa fa-minus-square-o"></i>&nbsp;{{ trans('admin.collapse_all') }}
                    </button>
                </div>
                @can('admin.add_product_category')
                <div class="btn-group">
                    <a class="btn btn-sm btn-success pull-right" href="{{ request()->getPathInfo().'/create' }}"><i class="fa fa-plus f-s-12"></i> {{ trans('admin.add_product_category') }}</a>
                </div>
                @endcan

                @can('admin.edit_product_category')
                <div class="btn-group pull-right">
                    <button class="btn btn-primary btn-sm save" style="width: 120px">{{ trans('admin.save') }}</button>
                </div>
                @endcan

            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
                <div class="dd">
                    <ol class="dd-list">
                        @recursive($data)
                        <li class="dd-item" data-id="{{ $val->id }}">
                            <div class="dd-handle">
                                <span class="handle ui-sortable-handle">
                                    <i class="fa fa-ellipsis-v"></i>
                                    <i class="fa fa-ellipsis-v"></i>
                                </span>
                                <strong>{{ $val->title }}</strong>
                                <span class="pull-right dd-nodrag">
                                    @can('admin.edit_product_category')
                                    <a href="{{ request()->getPathInfo().'/' . $val->id .'/edit' }}"><i class="fa fa-edit"></i></a>
                                    @endcan
                                    @can('admin.delete_product_category')
                                    <a href="javascript:;" class="trash" data-id="{{ $val->id }}"><i class="fa fa-trash"></i></a>
                                    @endcan
                                </span>
                            </div>
                            @nextrecursive(<ol class="dd-list">, </ol>)
                        </li>
                        @endrecursive
                    </ol>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix no-border">
                @can('admin.edit_product_category')
                <div class="btn-group pull-right">
                    <button class="btn btn-primary btn-sm save" style="width: 120px">{{ trans('admin.save') }}</button>
                </div>
                @endcan
            </div>
        </div>
    </div>
</div>

<script>

    $(function () {
        $('.dd').nestable();
        $('button.tools').on('click', function(e)
        {
            var target = $(e.target),
                action = target.data('action');
            if (action === 'expand') {
                $('.dd').nestable('expandAll');
            }
            if (action === 'collapse') {
                $('.dd').nestable('collapseAll');
            }
        });

        $('.dd-nodrag a').click(function(){
            if($(this).hasClass('trash')){
                var obj = $(this);
                var pk = obj.data('id');
                $.confirm({
                    title: '{{ trans('admin.delete_confirm') }}',
                    content: '{{ trans('admin.delete_message') }}',
                    autoClose: 'cancelAction|5000',
                    buttons: {
                        deleteAction: {
                            text: "{{ trans('admin.delete') }}",
                            action: function () {
                                $.ajax({
                                    method: 'post',
                                    url: '{{ request()->getPathInfo() }}/' + pk,
                                    data: {
                                        _method: 'DELETE',
                                        _token: "{{ csrf_token() }}"
                                    },
                                    success: function (data) {
                                        toastr.success(data.message);
                                        obj.closest('li').remove();
                                    }
                                });
                            }
                        },
                        cancelAction: {
                            text: '{{ trans('admin.cancel') }}'
                        }
                    }
                });
            }
        });

        $('.save').click(function(){
            var btn = $('.save');
            var nestable = JSON.stringify($('.dd').nestable('serialize'));
            btn.attr("disabled", true);
            $.ajax({
                method: 'post',
                url: '{{ Admin::action('order') }}',
                data: {
                    nestable:nestable,
                    _nestable: true,
                    _token: "{{ csrf_token() }}"
                },
                complete:function(){
                    btn.attr("disabled", false);
                },
                success: function (data) {
                    toastr.success(data.message);
                }
            });
        });
    });
</script>

@endsection