<style>
    .panel-group .panel-heading{
        cursor: pointer;
    }

    .tab-pane {
        min-height: 42px;
        max-height: 200px;
        overflow: auto;
        padding: 12px;
        border: 1px solid #ddd;
        background-color: #fdfdfd;
    }

    .tab-pane ul {
        padding: 0;
        list-style: none;
    }

    .tab-pane ul li {
        margin: 0;
        padding: 0 0 0 21px;
        line-height: 18px;
        display: list-item;
        list-style: none;
        position: relative;
    }

    .tab-pane ul li label {
        margin-bottom: 8px;
        font-weight: normal !important;
        cursor: pointer;
    }

    .tab-pane ul li div {
        position: absolute;
        top: 1px;
        left: 0;
    }

    [aria-expanded="false"] .fa-angle-up {
        display: none;
    }

    [aria-expanded="true"] .fa-angle-down {
        display: none;
    }

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

    .posts{
        margin-right: 300px;
    }
    .posts-left{
        float: left;
        width: 100%;
    }
    .posts-right{
        float: right;
        width: 280px;
        margin-right: -300px;
    }
</style>
<script>
    var money_identifier = "{{ trans('Ecommerce::admin.currency') }}";
</script>

@if (count($errors) > 0)
    @foreach ($errors->all() as $error)
        <div class="alert alert-danger fade in" style="margin-bottom: 10px;">
            {{ $error }}.
            <span class="close" data-dismiss="alert">×</span>
        </div>
    @endforeach
@endif

<form data-pjax="true" action="{{ isset($product->id)?route('admin.products.update', $product->id):route('admin.products.store') }}"
      method="POST">
{{ csrf_field() }}
@if(isset($product->id))
    {{ method_field("PUT") }}
@endif
<!-- begin row -->
    <div class="posts">
        <!-- begin col-12 -->
        <div class="posts-left">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row form-horizontal">
                        <div class="form-group">
                            <label class="control-label col-md-2">{{ trans('TanwenCms::admin.title') }}：</label>
                            <div class="col-md-8">
                                <input type="text" name="title" class="form-control" value="{{ old('title', $product->title)}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">{{ trans('Ecommerce::admin.product_short_description') }}：</label>
                            <div class="col-md-8">
                                <textarea type="text" class="form-control" name="summary">{{ old('summary', $product->summary)}}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ trans('Ecommerce::admin.sales_options') }}</h3>
                </div>
                <div class="panel-body">
                    <div class="form-horizontal">
                        <div class="col-md-3 text-right"><label class="control-label">{{ trans('Ecommerce::admin.attributes') }}：</label></div>
                        <div class="form-group col-md-9">
                            <select class="select2 select-attributes form-control" name="terms[]"
                                    multiple="multiple" data-placeholder="{{ trans('Ecommerce::admin.select_sales_attribute') }}">
                                <option value=""></option>
                                @foreach ($attriibutes as $val)
                                    <option {{ in_array($val->id, $terms->all()) ? 'selected="selected" ' : '' }} value="{{ $val->id }}" data-tw="tanwencms" data-items="{{ $val }}">{{ $val->title }}</option>
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
        <div class="posts-right">
            <div class="right-panel">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">{{ trans_choice('TanwenCms::admin.setting', 0) }}</h3>
                    </div>
                    <div class="panel-body">

                        <div class="form-group">
                            <label class="control-label">{{ trans_choice('TanwenCms::admin.tag', 0) }}：</label>
                            <select class="select2 select-tags form-control" multiple="multiple" name="terms[]" >
                                <option value=""></option>
                                @foreach ($tags as $id => $title)
                                    <option {{ in_array($id, $terms->all()) ? 'selected="selected" ' : '' }} value="{{ $id }}">{{ $title }}</option>
                                @endforeach;
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="url-slug-input">{{ trans('Ecommerce::admin.shelves') }}</label>
                            <select name="is_release" class="form-control">
                                <option value="0" {{ old('is_release', $product->is_release)==0?'selected':''}}>{{ trans('Ecommerce::admin.unshelves') }}</option>
                                <option value="1" {{ old('is_release', $product->is_release)!=0?'selected':''}}>{{ trans('Ecommerce::admin.shelves') }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading collapsed" role="tab" data-toggle="collapse" data-parent="#accordion"
                         href="#select_categories" aria-expanded="true" aria-controls="collapseTwo">
                        <h4 class="panel-title">
                            {{ trans_choice('TanwenCms::admin.product_category', 1) }}
                            <span class="pull-right" role="button">
                            <i class="fa fa-angle-down"></i>
                            <i class="fa fa-angle-up"></i>
                        </span>
                        </h4>
                    </div>
                    <div id="select_categories" class="panel-collapse collapse in" role="tabpanel">
                        <div class="panel-body">
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="select_categories_all">
                                    <ul>
                                        @each('TanwenCms::components.tree.multiple_choice_items', $categories, 'model')
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">{{ trans('Ecommerce::admin.product_gallery') }}</h3>
                    </div>

                    <div class="panel-body" style="padding: 10px">
                        <p>
                            <a href="javascript:showImageSelector('gallery');">
                                {{ trans('TanwenCms::admin.select_image') }}
                            </a>
                        </p>
                        <div id="gallery">
                            @foreach (old('metas.gallery', $product->gallery) as $url)
                                <div class="col-md-4"><img class="img-rounded img-responsive" src="{{ $url }}"><input name="metas[gallery][]" value="{{ $url }}" type="hidden"><button type="button" class="btn btn-box-tool delete" data-original-title="Remove"><i class="fa fa-times"></i></button></div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-lg btn-block">{{ trans('TanwenCms::admin.save') }}</button>
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


    var terms = JSON.parse('{!! $terms->toJson() !!}');
    $(function () {

        $('#select_categories :input[type="checkbox"]').attr('name', 'terms[]');

        $('#select_categories :input[type="checkbox"]').on('ifCreated', function(event){
            if($.inArray(parseInt($(this).val()), terms) >= 0 || $.inArray($(this).val().toString(), terms) >= 0) {
                $(this).prop('checked', true);
            }
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
            language: "{{ str_replace("-",  "_", config('app.locale')) }}",
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
            default: JSON.parse('{!! $skus !!}'),
            language:{
                'price': "{{ trans('Ecommerce::admin.price') }}",
                'market_price': "{{ trans('Ecommerce::admin.market_price') }}",
                'cost_price': "{{ trans('Ecommerce::admin.cost_price') }}",
                'stock': "{{ trans('Ecommerce::admin.stock') }}",
                'batch': "{{ trans('TanwenCms::admin.batch') }}",
            },
            init: function () {
                function initSelectAttributes() {
                    var parent_id = $('.select-attributes').val();

                    var items = [];
                    if($('.select-attributes').val() != null) {
                        $.each($('.select-attributes').val(), function (index, value) {
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

