var fields = '<div class="block-catalog-current-fields"><hr>';
var groupCount = 0;
function bustProps (obj) {
    $.makeArray(obj);
    // console.log();
    for (var key in obj) {
        if (typeof obj [key] == 'object') {
            // console.log(key);
            if (obj[key].type) {
                var currentObj = obj[key];
                switch (obj[key].type) {
                    case 'select':
                        var mes = currentObj.mes != undefined ? currentObj.mes : '';
                        var required = currentObj.required === true ? 'required' : '';
                        var validate = currentObj.validate != undefined ? currentObj.validate : '';
                        var style = currentObj.style != undefined ? (currentObj.style.match(/col-md/i) != null ? currentObj.style : currentObj.style + ' col-md-3') : 'col-md-3';
                        // var style = currentObj.style != undefined ? currentObj.style : '';
                        fields += '<div class="' + style + ' form-group form-field-required">';
                        fields += '<label for="' + currentObj.label + '" class="text-muted label-nowrap">' + currentObj.label + '</label>';
                        fields += '<div>';
                        fields += '<select name="' + key + '" class="form-control required ' + validate + '" required>';
                            for (var i = 0; i < obj[key].values.length; i++) {
                                fields += '<option value="' + currentObj.values[i] + '">' + currentObj.values[i] + '</option>';
                            };
                        fields += '<span class="input-group-addon mes">' + mes + '</span></select>';
                        fields += '</div>';
                        fields += '<div class="form-field-error"></div>';
                        fields += '</div>';
                        break
                    case 'input':
                        var mes = currentObj.mes != undefined ? currentObj.mes : '';
                        var required = currentObj.required === true ? 'required' : '';
                        var validate = currentObj.validate != undefined ? currentObj.validate : '';
                        var style = currentObj.style != undefined ? currentObj.style : '';
                        fields += '<div class="form-group form-field-required col-md-3 ' + style + '">';
                        fields += '<label for="' + currentObj.label + '" class="text-muted label-nowrap">' + currentObj.label + '</label>';
                        fields += '<div class="input-group"><input type="text" name="' + key + '" class="form-control ' + validate + ' ' + style + '"' + required + '><span class="input-group-addon mes">' + mes + '</span>';
                        fields += '</div>';
                        fields += '<div class="form-field-error"></div>';
                        fields += '</div>';
                        bustProps (obj[key]);
                        break
                    case 'textarea':
                        var mes = currentObj.mes != undefined ? currentObj.mes : '';
                        var required = currentObj.required === true ? 'required' : '';
                        var validate = currentObj.validate != undefined ? currentObj.validate : '';
                        var style = currentObj.style != undefined ? currentObj.style : '';
                        fields += '<div class="form-group form-field-required col-md-12 ' + currentObj.style + '">';
                        fields += '<label for="' + currentObj.label + '" class="text-muted label-nowrap">' + currentObj.label + '</label>';
                        fields += '<div class="input-group col-md-6"><textarea rows="5" name="' + key + '" class="form-control ' + style + '"' + required + '></textarea>';
                        fields += '</div>';
                        fields += '<div class="form-field-error"></div>';
                        fields += '</div>';
                        bustProps (obj[key]);
                        break
                    case 'group':
                        if (groupCount === 0) {
                            fields += '<h4>' + currentObj.title + '</h4>';
                            fields += '<div class="row">';
                        } else {
                            fields += '</div>';
                            fields += '<h4>' + currentObj.title + '</h4>';
                            fields += '<div class="row">';
                        }
                        groupCount++;
                        bustProps (obj[key]);
                        break
                    case 'images':
                        var mes = currentObj.mes != undefined ? currentObj.mes : '';
                        var required = currentObj.required === true ? 'required' : '';
                        var validate = currentObj.validate != undefined ? currentObj.validate : '';
                        var style = currentObj.style != undefined ? currentObj.style : '';
                        fields += '<div class="form-group form-field-required col-md-12 ' + currentObj.style + '">';
                        fields += '<label for="' + currentObj.label + '" class="text-muted label-nowrap">' + currentObj.label + '</label>';
                        fields += '<div class="input-group col-md-12">';
                        fields += '<input id="fileupload" type="file" name="files[]" data-url="additem" multiple>'
                        fields += '<div id="preview"></div>'
                        fields += '</div>';
                        // fields += '<button type="button" id="save-logo" class="btn btn-primary">Сохранить</button>';
                        fields += '<div class="form-field-error"></div>';
                        fields += '</div>';
                        break
                    case 'str_to_map':
                        var mes = currentObj.mes != undefined ? currentObj.mes : '';
                        var required = currentObj.required === true ? 'required' : '';
                        var validate = currentObj.validate != undefined ? currentObj.validate : '';
                        var style = currentObj.style != undefined ? currentObj.style : '';
                        fields += '<div class="form-group form-field-required col-md-3 ' + style + '">';
                        fields += '<label for="' + currentObj.label + '" class="text-muted label-nowrap">' + currentObj.label + '</label>';
                        fields += '<div class="input-group">' +
                                '<input type="text" id="crazy_address" name="' + key + '" class="form-control ' + validate + ' ' + style + '"' + required + '>' +
                                '<input type="hidden" id="coordinates" value="">' +
                                '<div id="myMap" style="width: 500px; height: 300px;"></div>' +
                                '<span class="input-group-addon mes">' + mes + '</span>';
                        fields += '</div>';
                        fields += '<div class="form-field-error"></div>';
                        fields += '</div>';
                        bustProps (obj[key]);
                        break
                    default:
                        bustProps (obj[key]);
                        break
                }
            }
        } else {
            // console.log(obj[key] + ' = ' + obj[key].values);
        }
   }
}
bustProps(data);
$('#submit-form1').prop('disabled', false);
$('#fields').append(fields);