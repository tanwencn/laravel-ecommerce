<!-- ================== BEGIN PAGE LEVEL JS ================== -->
{{--<script data-action="" src="{{ asset('vendor/laravel-cms/layer/layer.js') }}"></script>
<script src="{{ asset('vendor/laravel-cms/sortable/Sortable.min.js') }}"></script>
<script src="{{ asset('vendor/laravel-cms/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('vendor/laravel-ecommerce/js/admin/products.select.attributes.js') }}"></script>
<script src="{{ asset('vendor/laravel-cms/js/jquery.pin.js') }}"></script>--}}
<!-- ================== END PAGE LEVEL JS ================== -->
<style>

    .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {
        vertical-align: middle;
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

    .input-group-addon{
        padding: 6px 6px;
    }
</style>
<script>
    var money_identifier = '￥';
</script>

@if (count($errors) > 0)
    @foreach ($errors->all() as $error)
        <div class="alert alert-danger fade in" style="margin-bottom: 10px;">
            {{ $error }}.
            <span class="close" data-dismiss="alert">×</span>
        </div>
    @endforeach
@endif

<form action="{{ isset($product->id)?route('Ecommerce.admin.products.update', $product->id):route('Ecommerce.admin.products.store') }}"
      method="POST">
{{ csrf_field() }}
@if(isset($product->id))
    {{ method_field("PUT") }}
@endif
<!-- begin row -->
    <div class="row">
        <!-- begin col-12 -->
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">基本信息</h3>
                </div>
                <div class="panel-body">
                    <div class="row form-horizontal">
                        <div class="form-group">
                            <label class="control-label col-md-2">名称：</label>
                            <div class="col-md-8">
                                <input type="text" name="title" class="form-control"
                                       placeholder="请输入商品名称" value="{{ old('title', $product->title)}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">促销语：</label>
                            <div class="col-md-8">
                                    <textarea type="text" class="form-control" placeholder="请输入商品促销语"
                                              name="summary">{{ old('summary', $product->summary)}}</textarea>
                            </div>
                        </div>
                        {{--<div class="form-group">
                            <label class="control-label col-md-2">库存预警：</label>
                            <div class="col-md-1">
                                <input type="number" id="stock_warning" class="form-control"
                                       placeholder="请输入库存预警"
                                       data-parsley-group="wizard-step-2" value="0" max="255"
                                       data-parsley-type="number" required>
                            </div>
                            <div class="col-md-7">
                                设置最低库存预警值。当库存低于预警值时商家中心商品列表页库存列红字提醒。
                                <br>请填写0~255的数字，0为不预警
                            </div>
                        </div>--}}
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">库存</h3>
                </div>
                <div class="panel-body">
                    <div class="form-horizontal">
                        <div class="col-md-2 text-right"><label class="control-label">属性：</label></div>
                        <div class="form-group col-md-10">
                            <select class="select2 select-attributes form-control" name="terms[]"
                                    multiple="multiple" data-placeholder="选择销售属性">
                                <option value=""></option>
                                @foreach ($attriibutes as $val)
                                    <option {{ in_array($val->id, $terms->all()) ? 'selected="selected" ' : '' }} value="{{ $val->id }}">{{ $val->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-inline select-attr-values"></div>

                    <div class="col-md-12 table-stock" style="margin-top: 5px;padding: 0;"></div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-body" style="padding: 5px;">
                    <div class="form-group">
                            <textarea class="description" name="metas[description]">{{ old('metas.description', $product->description) }}</textarea>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="right-panel">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">设置</h3>
                    </div>
                    <div class="panel-body">

                        <div class="form-group">
                            <label class="control-label">分类：</label>
                            <select class="select2 select-category form-control" multiple="multiple" name="terms[]"
                                    data-placeholder="选择分类">
                                <option value=""></option>
                                @foreach ($categories as $id => $title)
                                    <option {{ in_array($id, $terms->all()) ? 'selected="selected" ' : '' }} value="{{ $id }}">{{ $title }}</option>
                                @endforeach;
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="control-label">标签：</label>
                            <select class="select2 select-tags form-control" multiple="multiple" name="terms[]"
                                    data-placeholder="选择标签">
                                <option value=""></option>
                                @foreach ($tags as $id => $title)
                                    <option {{ in_array($id, $terms->all()) ? 'selected="selected" ' : '' }} value="{{ $id }}">{{ $title }}</option>
                                @endforeach;
                            </select>
                        </div>

                        {{--<div class="form-group">
                            <label for="url-slug-input">Url Slug</label>
                            <input type="text" class="form-control" id="url-slug-input" placeholder="Slug">
                        </div>--}}

                        <div class="form-group">
                            <label for="url-slug-input">发布</label>
                            <select name="is_release" class="form-control">
                                <option value="0" {{ old('is_release', $product->is_release)<1?'selected':''}}>下架</option>
                                <option value="1" {{ old('is_release', $product->is_release)==1?'selected':''}}>上架</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">相册</h3>
                    </div>

                    <div class="panel-body" style="padding: 10px">
                        <p>
                            <a href="javascript:showImageSelector('gallery');">
                                添加图片
                            </a>
                        </p>
                        <div id="gallery">
                            @foreach (old('metas.gallery', $product->gallery) as $url)
                                <div class="col-md-4"><img class="img-rounded img-responsive" src="{{ $url }}"><input name="metas[gallery][]" value="{{ $url }}" type="hidden"><button type="button" class="btn btn-box-tool delete" data-original-title="Remove"><i class="fa fa-times"></i></button></div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-lg btn-block">保存</button>
            </div>
        </div>
        <!-- end panel -->
        <!-- end col-12 -->
    </div>
</form>
<!-- end row -->

<!-- end #content -->


<script>
    // function to update the file selected by elfinder
    var processSelectedFileId = '';

    function processSelectedFile(file, requestingField) {
        if (requestingField == 'tinymce4') {
            $('#' + processSelectedFileId).val(file.url);
        } else {
            $.each(file, function (index, val) {
                $('#' + requestingField).append('<div class="col-md-4"><img class="img-rounded img-responsive" src="' + val.url + '"><input name="metas[gallery][]" value="' + val.url + '" type="hidden"><button type="button" class="btn btn-box-tool delete" data-original-title="Remove"><i class="fa fa-times"><\/i><\/button><\/div>');
            });
            $(".right-panel").pin({
                containerSelector: ".row"
            });
        }
    }

    function showImageSelector(url) {
        layer.open({
            type: 2,
            title: '选择图片',
            shadeClose: true,
            shade: false,
            maxmin: true, //开启最大化最小化按钮
            area: ['80%', '70%'],
            content: '/{{ config('admin.route.prefix') }}/elfinder/popup/' + url
        });
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
            language: "{{ str_replace("-",  "_", config('app.locale')) }}",
            skin: 'voyager',
            height: 300,
            menubar: true,
            convert_urls: false,
            file_picker_types: 'image',
            file_browser_callback: function (field_name, url, type, win) {
                processSelectedFileId = field_name;
                showImageSelector("tinymce4?multiple=false&id='+field_name");
                return false;
            },
            plugins: [
                'advlist autolink lists link image code charmap print preview anchor',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime media table contextmenu paste code'
            ],
            toolbar: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | code',
            content_css: [
                '{{ asset('vendor/laravel-cms/tinymce/skins/lightgray/content.min.css') }}',
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
            selected: JSON.parse('{!! $terms->toJson() !!}'),
            call: {
                init: function () {
                    function initSelectAttributes() {
                        var parent_id = $('.select-attributes').val();
                        $.getJSON("{{ route('Ecommerce.admin.products.findAttrValues') }}", {parent_id: parent_id}, function (data) {
                            AttributeSelect.run(data);
                        });
                    }

                    initSelectAttributes();
                    $('.select-attributes').change(function () {
                        initSelectAttributes();
                    });
                },
                defaultByCode: function (code) {
                    var skus = JSON.parse('{!! $skus !!}');
                    if (skus[code]) {
                        return skus[code];
                    } else {
                        return {'price': 0, 'market_price': 0, 'cost_price': 0, 'stock': 0};
                    }
                }
            }
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

