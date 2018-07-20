@extends('admin::layouts.app')

@section('title', trans('admin.'.($model->id?'edit_product':'add_product')))

@section('breadcrumbs') <li><a href="{{ Admin::action('index') }}"> {{ trans_choice('admin.all_product', 1) }}</a></li> @endsection

@section('content')
<style>
    #gallery div.sortable-chosen{
        opacity:1;
    }
    #gallery div.sortable-ghost *{
        opacity:0.1;
    }

    #gallery div {
        margin: 6px 0;
        padding: 0 3px;
        cursor: move;
    }

    #gallery div img {
        width: 100% !important;
    }

    #gallery div .delete {
        padding: 0 3px;
        margin: 0;
        position: absolute;
        top: -3px;
        right: 0px;
        color: #000000;
        background: #fff;
        display: none;
    }
</style>

<form action="{{ isset($model->id)?Admin::action('update', $model->id):Admin::action('store') }}" method="POST">
{{ csrf_field() }}
@if(isset($model->id))
    {{ method_field("PUT") }}
@endif
<!-- begin row -->
    <div class="posts">
        <!-- begin col-12 -->
        <div class="posts-left">
            <div class="box box-solid">
                <div class="box-header">
                </div>
                <!-- /.box-header -->
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
                                <input type="text" name="title" class="form-control" value="{{ old('title', $model->title)}}">
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('excerpt')?"has-error":"" }}">
                            <label class="control-label col-md-2">{{ trans('admin.product_short_description') }}：</label>
                            <div class="col-md-8">
                                @if($errors->has('excerpt'))
                                    <label class="control-label">
                                        <i class="fa fa-times-circle-o"></i>{{$errors->first('excerpt')}}
                                    </label>
                                @endif
                                <textarea type="text" class="form-control"
                                          name="excerpt">{{ old('excerpt', $model->excerpt)}}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>

            <div class="box box-solid">
                <!-- /.box-header -->
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('admin.sales_options') }}</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="form-horizontal">
                        <div class="col-md-3 text-right"><label class="control-label">{{ trans_choice('admin.attribute', 0) }}：</label></div>
                        <div class="form-group col-md-9">
                            <select class="select2 select-attributes form-control" name="attributes[]"
                                    multiple="multiple" data-placeholder="{{ trans('admin.select_sales_attribute') }}">
                                <option value=""></option>
                                @foreach ($attributes->where('parent_id', 0) as $val)
                                    <option {{ in_array($val->id, $model->attributes->toArray()) ? 'selected="selected" ' : '' }} value="{{ $val->id }}" data-items="{{ $val }}">{{ $val->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-inline select-attr-values"></div>

                    <div class="col-md-12 table-stock" style="margin-top: 5px;padding: 0;"></div>
                </div>
                <!-- /.box-body -->
            </div>

            <div class="box box-solid">
                <!-- /.box-header -->
                <div class="box-body no-padding">
                    <div class="form-group">
                        <textarea class="description"
                                  name="metas[description]">{{ old('metas.description', $model->description) }}</textarea>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
        <div class="posts-right">
            <div class="right-panel">
                <!-- settting -->
                <div class="box box-solid">
                    <!-- /.box-header -->
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ trans_choice('admin.setting', 0) }}</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                        class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label>URL {{ trans('admin.slug') }}</label>
                            <input type="text" name="slug" class="form-control" value="{{ old('slug', $model->slug)}}">
                        </div>

                        <div class="form-group">
                            <label class="control-label">{{ trans_choice('admin.tag', 0) }}：</label>
                            <select class="select2 select-tags form-control" multiple="multiple" name="tags[]">
                                <option value=""></option>
                                @foreach ($tags as $item)
                                    <option {{ in_array($item->id, $model->tags) ? 'selected="selected" ' : '' }} value="{{ $item->id }}">{{ $item->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="url-slug-input">{{ trans('admin.shelves') }}</label>
                            <select name="is_release" class="form-control">
                                <option value="0" {{ old('is_release', $model->is_release)==0?'selected':''}}>{{ trans('admin.unshelves') }}</option>
                                <option value="1" {{ old('is_release', $model->is_release)!=0?'selected':''}}>{{ trans('admin.shelves') }}</option>
                            </select>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- settting -->

                <!-- category -->
                <div class="box box-solid">
                    <!-- /.box-header -->
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ trans_choice('admin.product_category', 1) }}</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                        class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="tab-content select-box">
                            <div role="tabpanel" class="tab-pane active" id="select_categories" style="border-top: 1px solid #ddd;">
                                <ul>
                                    @recursive($categories)
                                    <li>
                                        <label>
                                            <input name="categories[]" {{ in_array($val->id, $args[0])?"checked":'' }} value="{{ $val->id }}" data-image="{{ $val->image }}"
                                                   data-linkable_name="{{ trans_choice('admin.'.snake_case(class_basename($val)), 0) }}"
                                                   data-title="{{ $val->title }}" data-linkable_id="{{ $val->id }}"
                                                   data-linkable_type="{{ get_class($val) }}"
                                                   data-title="{{ $val->title }}" type="checkbox">
                                            <font>{{ $val->title }}</font>
                                        </label>
                                        @if(!empty($val->children))
                                            @nextrecursive(
                                            <ul class="children">,</ul>)
                                        @endif
                                    </li>
                                    @endrecursive($model->categories)
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- category -->

                <!-- cover -->
                <div class="box box-solid">
                    <!-- /.box-header -->
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ trans('admin.product_gallery') }}</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div style="width: 100%;float: left">
                        <a class="pull-right"
                           href="javascript:showImageSelector('gallery')"
                           style=" color: #337ab7 !important">{{ trans('admin.select_image') }}</a>
                        </div>
                        <div id="gallery">
                            @foreach (old('metas.gallery', $model->gallery) as $url)
                                <div class="col-md-4"><img class="img-rounded img-responsive" src="{{ $url }}"><input name="metas[gallery][]" value="{{ $url }}" type="hidden"><button type="button" class="btn btn-box-tool delete" data-original-title="Remove"><i class="fa fa-times"></i></button></div>
                            @endforeach
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- cover -->

                <button type="submit" class="btn btn-primary btn-lg btn-block">{{ trans('admin.save') }}</button>
            </div>
        </div>
        <!-- end panel -->
        <!-- end col-12 -->
    </div>
</form>
<!-- end row -->

<!-- end #content -->


<script>
    var money_identifier = "{{ trans('admin.currency') }}";

    // function to update the file selected by elfinder
    var processSelectedFileId = '';

    function processSelectedFile(file, requestingField) {
        if (requestingField == 'tinymce4') {
            processSelectedFileId(file.url);
        } else {
            $.each(file, function (index, val) {
                $('#' + requestingField).append('<div class="col-md-4"><img class="img-rounded img-responsive" src="' + val.url + '"><input name="metas[gallery][]" value="' + val.url + '" type="hidden"><button type="button" class="btn btn-box-tool delete" data-original-title="Remove"><i class="fa fa-times"><\/i><\/button><\/div>');
            });
            /*$(".right-panel").pin({
                containerSelector: ".row",
                bottom:100
            });*/
        }
    }

    $('#gallery').on('click', '.delete', function () {
        $(this).parent().remove();
    });

    $('#gallery').on('mouseover', 'div', function () {
        $(this).find('.delete').show();
    });
    $('#gallery').on('mouseout', 'div', function () {
        $(this).find('.delete').hide();
    });


    $(function () {

        $('[name="title"]').keyup(function(){
            $('[name="slug"]').val(slugify($(this).val()));
        });

        $('#select_categories :input[type="checkbox"]').iCheck({
            checkboxClass: 'icheckbox_minimal-red',
            increaseArea: '20%' // optional
        });

        Sortable.create(gallery, {
            animation: 150, // ms, animation speed moving items when sorting, `0` — without animation
            //handle: ".tile__title", // Restricts sort start click/touch to the specified element
            draggable: "div", // Specifies which items inside the element should be sortable
            onUpdate: function (evt/**Event*/) {
                var item = evt.item; // the current dragged HTMLElement
            }
        });

        tinymce.init({
            selector: '.description',
            skin: 'voyager',
            height: 300,
            menubar: true,
            convert_urls: false,
            file_picker_types: 'image',
            file_picker_callback: function(cb, value, meta) {
                processSelectedFileId = cb;
                showImageSelector("tinymce4?multiple=false&id='+field_name");
            },
            plugins: [
                'advlist autolink lists link image code charmap print preview anchor',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime media table contextmenu paste code'
            ],
            toolbar: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | code',
            content_css: [
                '{{ asset('vendor/laravel-blog/tinymce/skins/lightgray/content.min.css') }}',
            ]
        });

        $('.select2').select2({
            allowClear: true,
            placeholder: $(this).data('data-placeholder'),
            templateSelection: function (state) {
                return $.trim(state.text);
            }
        });


        AttributeSelect.init({
            selected: JSON.parse('{!! $model->attributes->toJson() !!}'),
            default: JSON.parse('{!! $skus !!}'),
            language:{
                'price': "{{ trans('admin.price') }}",
                'market_price': "{{ trans('admin.market_price') }}",
                'cost_price': "{{ trans('admin.cost_price') }}",
                'stock': "{{ trans('admin.stock') }}",
                'batch': "{{ trans('admin.batch') }}",
            },
            init: function () {
                function initSelectAttributes() {
                    var attributes = $('.select-attributes').val();

                    var items = [];
                    if(attributes != null) {
                        $.each(attributes, function (index, value) {
                            items.push($('.select-attributes').find('option[value="' + value + '"]').data('items'));
                        });
                    }
                    AttributeSelect.run(items);
                }

                initSelectAttributes();
                $('.select-attributes').change(function () {
                    initSelectAttributes();
                });
            },
        });

        $('.goods-select-attributes [type="text"]').not(':disabled').each(function (index, val) {
            data.attributes[$(val).data('value')] = $(val).val();
        });

        $('.goods-stock-table thead tr').each(function (index, val) {
            var code = '';
            var value = {};
            $(val).find('input').each(function (i, v) {
                code = $(v).data('code');
                value[$(v).data('field')] = $(v).val();
            });
            data.goods[code] = value;
        });
    });

</script>

@endsection