/**
 * Created by tanwen-d on 2017/7/22.
 */
var AttributeSelect = function () {
    "use strict";
    return {
        selected: [],
        language: {
            'price': "Price",
            'market_price': 'Market price',
            'cost_price': 'Cost price',
            'stock': 'Stock',
            'batch': "Batch"
        },
        default: {},
        data: {},
        parser: {
            combine: function (arr) {
                arr.reverse();
                var r = [];
                (function f(t, a, n) {
                    if (n == 0) {
                        return r.push(t);
                    }
                    for (var i = 0; i < a[n - 1].length; i++) {
                        f(t.concat(a[n - 1][i]), a, n - 1);
                    }
                })([], arr, arr.length);


                return r;
            },
            clearNoNum: function (obj) {
                obj.value = obj.value.replace(/[^\d.]/g, "");  //清除“数字”和“.”以外的字符
                obj.value = obj.value.replace(/\.{2,}/g, "."); //只保留第一个. 清除多余的
                obj.value = obj.value.replace(".", "$#$").replace(/\./g, "").replace("$#$", ".");
                obj.value = obj.value.replace(/^(\-)*(\d+)\.(\d\d).*$/, '$1$2.$3');//只能输入两个小数
                if (obj.value.indexOf(".") < 0 && obj.value != "") {//以上已经过滤，此处控制的是如果没有小数点，首位不能为类似于 01、02的金额
                    obj.value = parseFloat(obj.value);
                }
            }
        },
        init: function (config) {
            var _this = this;
            if ($(config['default']).length > 0) {
                _this.default = config['default'];
            }

            if (config['language']) {
                _this.language = $.extend(_this.language, config['language']);
            }
            if (config['selected']) {
                _this.selected = config['selected'];
            }

            $('.select-attr-values').on('change', 'input[type="text"]', function () {
                var value_id = $(this).data('value');
                $('.table-stock .attr-value-text[data-id="' + value_id + '"]').text(this.value);
            });

            //初始化iCheck

            //iCheck选中事件
            $('.select-attr-values').on('ifChanged', 'input[type="checkbox"]', function () {
                var parent = $(this).parents('.attr-value');
                if ($(this).is(':checked')) {
                    parent.find('input[type="text"]').attr('disabled', false);
                } else {
                    parent.find('input[type="text"]').attr('disabled', true);
                }

                _this.initTable();
            });

            if (config.init) {
                config.init();
            }

            /*$.when(_this.data).then(function (data) {

                _this.initSelect();

                _this.initTable();
            });*/
        },
        run: function (data) {
            var results = [];
            $.each(data, function () {
                var values = [];
                $.each(this.children, function () {
                    values.push({id: this.id, name: this.title});
                });
                values.sort(function (a, b) {
                    return a.id > b.id;
                });
                results.push({id: this.id, name: this.title, children: values});
            });
            results.sort(function (a, b) {
                return a.id > b.id;
            });
            this.data = results;
            this.initSelect();

            $('.select-attr-values :input[type="checkbox"]').iCheck({
                checkboxClass: 'icheckbox_flat-red',
                increaseArea: '10%' // optional
            });

            $('.select-attr-values :input[data-selected="true"]').iCheck('check');

            this.initTable();
        },
        initSelect: function () {
            var _this = this;
            var html = '';
            $.each(_this.data, function (index, attribute) {
                html += '<div class="col-md-2 text-right" style="margin-top: 15px;"><label class="control-label">' + attribute.name + '：</label></div>';
                html += '<div class="col-md-10" >';
                html += '<div class="value-checkbox" data-name="' + attribute.name + '">';
                $.each(attribute.children, function (i, value) {
                    var selected = $.inArray(value.id.toString(), _this.selected) > -1;

                    if (selected == false) {
                        selected = $.inArray(value.id, _this.selected) > -1;
                    }
                    html += '<div class="form-group" style="margin: 5px">';
                    html += '<div class="input-group attr-value">';
                    html += '<span class="input-group-addon"><input name="attributes[]" data-selected="' + selected + '" type="checkbox" data-name="' + value.name + '" value="' + value.id + '"></span>';
                    html += '<span class="input-group-addon p-l-0">' + value.name + '</span>';
                    html += '</div>';
                    html += '</div>';
                });
                html += '</div>';
                html += '</div>';
            });
            $('.select-attr-values').html(html);

        },
        initTable: function () {
            var _this = this;

            //获取checked数组，并生成th动态部分
            var beforTh = '';
            var checkedObj = [];

            $('.select-attr-values .value-checkbox').each(function (index) {
                var checkedElement = $(this).find('input[type="checkbox"]:checked');
                if (checkedElement.length > 0) {
                    beforTh += '<th class="col-md-1 text-center">' + $(this).data('name') + '</th>';
                }

                var arr = [];
                checkedElement.each(function () {
                    var value_id = this.value;
                    var value_name = $(this).data('name');
                    arr.push({value_id: value_id, value_name: value_name});
                });
                if (arr.length > 0) {
                    checkedObj.push(arr);
                }
            });

            var checkedCombine = _this.parser.combine(checkedObj);

            //合并单元格
            var row = [];
            var rowspan = checkedCombine.length;
            for (var n = checkedObj.length - 1; n >= 0; n--) {
                row[n] = rowspan / checkedObj[n].length;
                rowspan = row[n];
            }
            row.reverse();

            var td = '';

            $.each(checkedCombine, function (combineIndex, combine) {
                var str = '';
                var skuname = [];
                var skucode = [];
                $.each(combine, function (index, value) {
                    //skuarr.push(value.attribute_id + ':' + value.value_id);
                    skucode.push(value.value_id);
                    skuname.push(value.value_name);

                    if (combineIndex % row[index] == 0 && row[index] > 1) {
                        str += '<td class="text-center attr-value-text" data-id="' + value.value_id + '" rowspan="' + row[index] + '">' + value.value_name + '</td>';
                    } else if (row[index] == 1) {
                        str += '<td class="text-center attr-value-text" data-id="' + value.value_id + '" rowspan="' + row[index] + '">' + value.value_name + '</td>';
                    }
                });

                skucode = skucode.join(':');
                skuname = skuname.join(' ');
                if (skucode == '') {
                    skucode = 'item';
                }

                if (_this.default[skucode] == undefined) {
                    var val = {'price': 0, 'market_price': 0, 'cost_price': 0, 'stock': 0};
                } else {
                    var val = _this.default[skucode];
                }

                td += "<tr>" + str + '<td>' +
                    '<div class="input-group">' +
                    '<span class="input-group-addon p-5">' + money_identifier + '</span>' +
                    '<input type="text" name="skus[' + skucode + '][price]" maxlength="10" data-type="price" class="form-control" data-field="price" value="' + val.price + '">' +
                    '<input type="hidden" name="skus[' + skucode + '][sku_name]" value="' + skuname + '">' +
                    '<input type="hidden" name="skus[' + skucode + '][sku_code]" value="' + skucode + '">' +
                    '</div>' +
                    '</td><td>' +
                    '<div class="input-group">' +
                    '<span class="input-group-addon p-5">' + money_identifier + '</span>' +
                    '<input type="text" name="skus[' + skucode + '][market_price]" maxlength="10" data-type="price" class="form-control" data-field="market_price" value="' + val.market_price + '">' +
                    '</div>' +
                    '</td>' +
                    '<td>' +
                    '<div class="input-group">' +
                    '<span class="input-group-addon p-5">' + money_identifier + '</span>' +
                    '<input type="text" name="skus[' + skucode + '][cost_price]" data-field="cost_price" maxlength="10"  data-type="price" class="form-control" value="' + val.cost_price + '">' +
                    '</div>' +
                    '</td><td>' +
                    '<input type="text" name="skus[' + skucode + '][stock]" data-field="stock" maxlength="6"  data-type="number" class="form-control" value="' + val.stock + '"/>' +
                    '</td></tr>';
            });

            $('.table-stock').html('<table class="table table-bordered"><thead>' + beforTh + '<th class="col-md-2 text-center">' + _this.language["price"] + '</th><th class="col-md-2 text-center">' + _this.language["market_price"] + '</th><th class="col-md-2 text-center">' + _this.language["cost_price"] + '</th><th class="col-md-2 text-center">' + _this.language["stock"] + '</th>' + td + '<tfoot><tr><td colspan="9" style="text-align:left;"><div class="batch-opts">' + _this.language["batch"] + '：<span><a class="batch" data-field="price" href="javascript:;">' + _this.language["price"] + '</a>&nbsp;&nbsp;<a class="batch" data-field="market_price" href="javascript:;">' + _this.language["market_price"] + '</a>&nbsp;&nbsp;	<a class="batch" data-field="cost_price" href="javascript:;">' + _this.language["cost_price"] + '</a>&nbsp;&nbsp;	<a class="batch" data-field="stock" data-type="number" href="javascript:;">' + _this.language["stock"] + '</a>	</span></div></td></tr></tfoot></table>');

            $('input[data-type="price"]').keyup(function () {
                var reg = $(this).val().match(/\d+\.?\d{0,2}/);
                var txt = '';
                if (reg != null) {
                    txt = reg[0];
                }
                $(this).val(txt);
            }).change(function () {
                $(this).keypress();
                var v = $(this).val();
                if (/\.$/.test(v)) {
                    $(this).val(v.substr(0, v.length - 1));
                }
            });
            $('input[data-type="number"]').keyup(function () {
                var reg = $(this).val().match(/\d+/);
                var txt = '';
                if (reg != null) {
                    txt = reg[0];
                }
                $(this).val(txt);
            }).change(function () {
                $(this).keypress();
                var v = $(this).val();
                if (/\.$/.test(v)) {
                    $(this).val(v.substr(0, v.length - 1));
                }
            });

            $('.batch').each(function (index, val) {
                $(val).editable({
                    type: 'text',
                    value: '',
                    title: '',
                    display: false,
                    success: function (response, newValue) {
                        $('.table-stock [data-field="' + $(val).data('field') + '"]').val(newValue);
                    },
                    setValue: function (value, convertStr) {
                    },
                    validate: function (value) {
                        var reg;

                        reg = new RegExp(/(^[1-9]([0-9]+)?(\.[0-9]{1,2})?$)|(^(0){1}$)|(^[0-9]\.[0-9]([0-9])?$)/);
                        if (!reg.test(value)) {
                            return "Please enter the number";
                        }
                    }
                });
            });
        }
    }
}();
