<style>
    th{white-space:nowrap}
</style>
    <!-- begin row -->
    <div class="row">
        <!-- begin col-12 -->
        <div class="col-md-12">
            <div class="panel panel-inverse" style="margin-bottom: 10px">
                <div class="panel-body">
                    <form id="search" data-pjax="true" action="{{ route('admin.products.index') }}">
                        <input type="hidden" name="trashed" value="{{ request('trashed', 0) }}">
                        <input type="hidden" name="release" value="{{ request('release') }}">
                        <div class="form-inline">
                            <div class="form-group">
                                <input type="input" name="search"
                                       value="{{ request('search', request('search')) }}" class="form-control"
                                       placeholder="{{ trans('TanwenCms::admin.search_related') }}...">
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-search f-s-14"></i> {{ trans('TanwenCms::admin.search') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- begin panel -->
            <div class="panel panel-inverse">
                <div class="panel-body">
                    <div class="btn-group">
                        <button type="button" class="btn btn-default btn-sm">{{ trans('TanwenCms::admin.batch') }}</button>
                        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="javascript:void(0)" class="grid-batch-release" data-value="1">{{ trans('Ecommerce::admin.shelves') }}</a></li>
                            <li><a href="javascript:void(0)" class="grid-batch-release" data-value="0">{{ trans('Ecommerce::admin.unshelves') }}</a></li>
                            <li role="separator" class="divider"></li>
                            @if(request('trashed'))
                                <li><a href="javascript:void(0)" class="grid-batch-restore">{{ trans('TanwenCms::admin.restore') }}</a></li>
                                <li><a href="javascript:void(0)" class="grid-batch-delete"
                                       data-message="{{ trans('TanwenCms::admin.delete_message') }}">{{ trans('TanwenCms::admin.delete_permanently') }}</a></li>
                            @else
                                <li><a href="javascript:void(0)" class="grid-batch-delete"
                                       data-message="{{ trans('TanwenCms::admin.trash_message') }}">{{ trans('TanwenCms::admin.move_trash') }}</a>
                                </li>
                            @endif
                        </ul>
                    </div>
                    <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-default btn-sm grid-trashed {{ request('trashed')?'active':'' }}">
                            <i class="fa f-s-12 fa-trash-o"></i> {{ trans('TanwenCms::admin.trash') }}{{ $statistics['delete'] }})
                        </label>
                    </div>

                    @if(empty(request('trashed')))
                        <div class="btn-group" data-toggle="buttons">
                            <label class="btn btn-default btn-sm grid-release {{ request('release')?:'active' }}"
                                   data-value="">{{ trans('TanwenCms::admin.all') }}{{ $statistics['total'] }})
                            </label>
                            <label class="btn btn-default btn-sm grid-release {{ request('release')=='up'?'active':'' }}"
                                   data-value="up">{{ trans('Ecommerce::admin.shelves') }}({{ $statistics['release'] }})
                            </label>
                            <label class="btn btn-default btn-sm grid-release {{ request('release')=='down'?'active':'' }}"
                                   data-value="down">{{ trans('Ecommerce::admin.unshelves') }}({{ $statistics['unrelease'] }})
                            </label>
                        </div>
                    @endif
                    <a class="btn btn-sm btn-success pull-right" href="{{ route('admin.products.create') }}">
                        <i class="fa fa-plus f-s-12"></i> {{ trans('TanwenCms::admin.add_product') }}
                    </a>

                    <table class="table table-hover" style="margin-top: 15px;">
                        <tbody>
                        <tr>
                            <th><input type="checkbox" class="grid-select-all" data-id="86"></th>
                            <th>ID{{--<a class="fa fa-fw fa-sort"
                                     href="http://laravel-admin.org/demo/users?gender=f&amp;_pjax=%23pjax-container&amp;_sort%5Bcolumn%5D=id&amp;_sort%5Btype%5D=desc"></a>--}}
                            </th>
                            <th>{{ trans('TanwenCms::admin.cover') }}</th>
                            <th>{{ trans('TanwenCms::admin.title') }}</th>
                            <th>SKU</th>
                            <th>{{ trans('Ecommerce::admin.stock') }}</th>
                            <th>{{ trans('Ecommerce::admin.price') }}({{ trans('Ecommerce::admin.currency') }})</th>
                            <th>{{ trans_choice('TanwenCms::admin.category', 0) }}</th>
                            <th>{{ trans_choice('TanwenCms::admin.tag', 0) }}</th>
                            <th>{{ trans('Ecommerce::admin.shelves') }}</th>
                            <th>{{ trans('TanwenCms::admin.updated_at') }}</th>
                            <th>{{ trans('TanwenCms::admin.operating') }}</th>
                        </tr>
                        @foreach($results as $product)
                            <tr>
                                <td>
                                    <input type="checkbox" class="grid-row-checkbox" data-id="{{ $product->id }}">
                                </td>
                                <td>
                                    {{ $product->id }}
                                </td>
                                <td>
                                    <img src="{{ $product->image }}" style="max-height: 40px">
                                </td>
                                <td>{{ $product->title }}</td>
                                <td>
                                    <a class="btn btn-xs btn-default grid-expand" data-inserted="0"
                                       data-key="{{ $product->id }}" data-toggle="collapse"
                                       data-target="#grid-collapse-{{ $product->id }}">
                                        <i class="fa fa-caret-right"></i> {{ trans('TanwenCms::admin.view') }}({{ $product->skus_count }})
                                    </a>
                                    <template class="grid-expand-{{ $product->id }}">
                                        <div id="grid-collapse-{{ $product->id }}" class="collapse">
                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <th>{{ trans('Ecommerce::admin.attributes') }}</th>
                                                    <th>{{ trans('Ecommerce::admin.price') }}</th>
                                                    <th>{{ trans('Ecommerce::admin.cost_price') }}</th>
                                                    <th>{{ trans('Ecommerce::admin.stock') }}</th>
                                                    <th>{{ trans('Ecommerce::admin.sales_volume') }}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($product->skus as $sku)
                                                    <tr>
                                                        <td>{{ $sku->sku_name }}</td>
                                                        <td>
                                                            <del>
                                                                <span><span>$</span>{{ $sku->market_price }}</span>
                                                            </del>
                                                            <ins>
                                                                <span><span>$</span>{{ $sku->price }}</span>
                                                            </ins>
                                                        </td>
                                                        <td>
                                                            <ins><span><span>$</span>{{ $sku->cost_price }}</span>
                                                            </ins>
                                                        </td>
                                                        <td>{{ $sku->stock }}</td>
                                                        <td>{{ $sku->sales }}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </template>
                                </td>
                                <td>{{ $product->skus->sum('stock') }}</td>
                                @php
                                $min_price = $product->skus->min('price');
                                $max_price = $product->skus->min('price');
                                @endphp
                                <td>@if($min_price == $max_price) {{ $min_price }} @else {{ $max_price }} ~ {{ $max_price }} @endif</td>
                                <td>{{ $product->categories->implode('title', ',') }}</td>
                                <td>{{ $product->tags->implode('title', ',') }}</td>
                                <td>
                                    <input type="checkbox" data-key="{{ $product->id }}" data-onname="{{ trans('Ecommerce::admin.shelves') }}" data-offname="{{ trans('Ecommerce::admin.unshelves') }}"
                                           class="grid-switch-released" {{ $product->is_release?'checked':'' }} />
                                </td>
                                <td>{{ $product->updated_at }}</td>
                                <td style="min-width: 60px">
                                    @if(request('trashed'))
                                        <a href="javascript:void(0);" data-id="{{ $product->id }}" class="grid-row-restore">{{ trans('TanwenCms::admin.restore') }}</a>&nbsp;&nbsp;&nbsp;
                                        <a href="javascript:void(0);" data-id="{{ $product->id }}" class="grid-row-delete" data-message="{{ trans('TanwenCms::admin.delete_confirm') }}"> {{ trans('TanwenCms::admin.delete_permanently') }}</a>
                                    @else
                                        <a href="{{ route('admin.products.edit', $product->id) }}">{{ trans('TanwenCms::admin.edit') }}</a>&nbsp;&nbsp;&nbsp;
                                        <a href="javascript:void(0);" data-id="{{ $product->id }}" class="grid-row-delete" data-message="After deletion, you can restore the data at the Recycle Bin.">{{ trans('TanwenCms::admin.delete') }}</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-sm-5">
                            <div class="pull-left pagination">
                                {{ trans('TanwenCms::pagination.range', [
                                'first' => $results->firstItem(),
                                'last' => $results->lastItem(),
                                'total' => $results->total(),
                                ]) }}
                            </div>
                        </div>
                        <div class="col-sm-7">
                            <div class="pull-right">
                                {{ $results->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- end panel -->

        <script>
            $(function () {
                $('.grid-expand').on('click', function () {
                    if ($(this).data('inserted') == '0') {
                        var key = $(this).data('key');
                        var row = $(this).closest('tr');
                        var html = $('template.grid-expand-' + key).html();

                        row.after("<tr><td colspan='" + row.find('td').length + "' style='padding:0 !important; border:0px;'>" + html + "</td></tr>");

                        $(this).data('inserted', 1);
                    }

                    $("i", this).toggleClass("fa-caret-right fa-caret-down");
                });


                $('.grid-select-all').iCheck({
                    checkboxClass: 'icheckbox_flat-blue',
                    increaseArea: '10%' // optional
                });

                $('.grid-row-checkbox').iCheck({
                    checkboxClass: 'icheckbox_flat-blue',
                    increaseArea: '10%' // optional
                }).on('ifChanged', function () {
                    if (this.checked) {
                        $(this).closest('tr').css('background-color', '#ffffd5');
                    } else {
                        $(this).closest('tr').css('background-color', '');
                    }
                });

                $('.grid-select-all').on('ifChanged', function (event) {
                    if (this.checked) {
                        $('.grid-row-checkbox').iCheck('check');
                    } else {
                        $('.grid-row-checkbox').iCheck('uncheck');
                    }
                });

                $('.grid-switch-released').bootstrapSwitch({
                    size: 'mini',
                    onText: '{{ trans('TanwenCms::admin.yes') }}',
                    offText: '{{ trans('TanwenCms::admin.no') }}',
                    onSwitchChange: function (event, state) {
                        var pk = $(this).data('key');
                        var value = state ? '1' : '0';
                        $.ajax({
                            method: 'post',
                            url: '{{ route('admin.products.index') }}/' + pk,
                            data: {
                                _method: 'PUT',
                                _token: "{{ csrf_token() }}",
                                is_release: value,
                                _only: "is_release"
                            },
                            success: function (data) {
                                toastr.success(data.message);
                            }
                        });
                    }
                });

                var selectedRows = function () {
                    var selected = [];
                    $('.grid-row-checkbox:checked').each(function () {
                        selected.push($(this).data('id'));
                    });
                    if (selected.length < 1) {
                        $.dialog({
                            title: 'Prompt',
                            content: 'Please select the data to be changed!',
                        });
                        return false;
                    }
                    return selected;
                };

                $('.grid-batch-release').on('click', function () {
                    var selected = selectedRows();
                    if (!selected) {
                        return false;
                    }
                    var id = selected.join();
                    var value = $(this).data('value');
                    $.ajax({
                        method: 'post',
                        url: '{{ route('admin.products.index') }}/' + id,
                        data: {
                            _method: 'PUT',
                            _token: "{{ csrf_token() }}",
                            is_release: value,
                            _only: "is_release"
                        },
                        success: function (data) {
                            $.pjax.reload('#pjax-container', {fragment: '#pjax-container'});
                            toastr.success(data.message);
                        }
                    });
                });

                $('.grid-batch-restore,.grid-row-restore').on('click', function () {
                    if($(this).hasClass('grid-row-restore')){
                        var id = $(this).data('id');
                        if(!id)
                            return false;
                    }else {
                        var selected = selectedRows();
                        if (!selected) {
                            return false;
                        }
                        var id = selected.join();
                    }

                    var value = $(this).data('value');
                    $.ajax({
                        method: 'post',
                        url: '{{ route('admin.products.index') }}/' + id,
                        data: {
                            _method: 'PUT',
                            _token: "{{ csrf_token() }}",
                            _only: "restore"
                        },
                        success: function (data) {
                            $.pjax.reload('#pjax-container', {fragment: '#pjax-container'});
                            toastr.success(data.message);
                        }
                    });
                });

                $('.grid-batch-delete,.grid-row-delete').on('click', function () {
                    if($(this).hasClass('grid-row-delete')){
                        var id = $(this).data('id');
                        if(!id)
                            return false;
                    }else {
                        var selected = selectedRows();
                        if (!selected) {
                            return false;
                        }
                        var id = selected.join();
                    }

                    var message = $(this).data('message');

                    $.confirm({
                        title: 'Are you sure to delete this item ?',
                        content: message,
                        autoClose: 'cancelAction|3000',
                        buttons: {
                            deleteAction: {
                                text: "delete",
                                action: function () {
                                    $.ajax({
                                        method: 'post',
                                        url: '{{ route('admin.products.index') }}/' + id,
                                        data: {
                                            _method: 'DELETE',
                                            _token: "{{ csrf_token() }}"
                                        },
                                        success: function (data) {
                                            $.pjax.reload('#pjax-container', {fragment: '#pjax-container'});
                                            toastr.success(data.message);
                                        }
                                    });
                                }
                            },
                            cancelAction: {
                                text: "cancel"
                            }
                        }
                    });
                });

                $('.grid-trashed').click(function () {
                    var trashed = $(this).hasClass('active') ? 0 : 1;
                    $('#search [name="trashed"]').val(trashed);
                    $('#search').submit();
                });
                $('.grid-release').click(function () {
                    var release = $(this).data('value');
                    $('#search [name="release"]').val(release);
                    $('#search').submit();
                });

                $('#search').submit(function () {
                    var url = $(this).attr('action') + '?' + $(this).serialize().replace('search_field=', '').replace('&search_value', '');
                    $.pjax({url: url, container: '#pjax-container'});
                    return false;
                });

            });
        </script>
    </div>