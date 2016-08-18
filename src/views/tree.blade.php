<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Каталог | Категории</title>

    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/liteweb-catalog/css/main.css') }}">
    <link rel="stylesheet" href="{{ elixir("css/app.css") }}">
    <script type="text/javascript" src="{{ elixir("js/app.js") }}"></script>
    <script type="text/javascript" src="{{ asset("vendor/liteweb-catalog/js/parseParams.js") }}"></script>
</head>
<body>
    <div class="box-container container-fluid col-sm-24">
        <div class="leftbar col-sm-6">
            <h3>Все категории</h3>
            <div class="">
                <button class="btn btn-success btn-sm addcategory" style="margin-bottom:5px; margin-left:40px;">+ Добавить</button>
                @if (isset($categories) && count($categories) > 0)
                    <ul data-parent="0" data-id="0">
                        @each('liteweb-catalog::tree-element', $categories, 'category', 'liteweb::none')
                    </ul>
                @else
                    <hr>
                    @include('liteweb::none')
                @endif
            </div>
        </div>
        <div class="content col-sm-offset-1 col-sm-4">
            <h3>Тип сделки</h3>
            <button class="btn btn-success btn-sm adddeal_type" style="margin-bottom:5px;" disabled="true">+ Добавить</button>
            <div id="deal_type" class="btn-group-vertical" role="group"></div>
        </div>
        <div class="content col-sm-6">
            <h3>Параметры</h3>
            <button class="btn btn-success btn-sm addgroup" style="margin-bottom:5px;" disabled="true">+ Добавить группу</button>
            <div id="params"></div>
        </div>
    </div>
    <div id="helpdata"></div>

    <!-- Modal -->
    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel"></h4>
          </div>
          <div class="modal-body">
          </div>
          <div class="modal-footer">
          </div>
        </div>
      </div>
    </div>

    <script type="text/javascript">
        console.log('load script');

        function reverseObj(obj) {
            var arr = [];
            var newObj = [];
            var i = 0;
            for( var name in obj ) {
                arr[name] = obj[name];
            }
            var len = arr.length;
            while( len-- ) {
                if( arr[len] !== undefined ) {
                    newObj[i] = arr[len];
                    i++;
                }
            }
            return newObj;
        }

        function getTreeElement(action, element) {
            //нахожу родительские группы, и переварачиваю
            var groups = reverseObj(element.parents('div.group'));
            console.log('groups');
            console.log(groups);
            var data = $('#helpdata').data('data');
            console.log(data);
            //параметры выбранной категории
            if (groups.length > 0) {
                var currentIndex = data['deal_types'][$('.getparams.active')[0].dataset['deal_type']];
            } else {
                return data['deal_types'][$(element)[0].dataset['key']];
            }

            $.each(groups, function(index, group) {
                currentIndex = currentIndex[$(group)[0].dataset['group']];
            });

            if (action === 'delete') {
                delete currentIndex[element[0].dataset['key']];
                return data;
            }

            return currentIndex[element[0].dataset['key']];
        }

        function editElement(element, catalog, type, key) {
            var form = $('#form-param').serializeArray();
            
            console.log("=========== form ===========");
            console.log(form);
            console.log(element);
            console.log("=========== form ===========");
            
            switch (type) {
                case 'catalog':
                    var data = form[0].value;
                    break;
                case 'deal_type':
                    var data = $('#helpdata').data('data');
                    //параметры выбранной категории
                    var currentIndex = data['deal_types'][$(element)[0].dataset['key']];

                    $.each(groups, function(index, group) {
                        currentIndex = currentIndex[$(group)[0].dataset['group']];
                    });

                    $.each(form, function(index, elem) {
                        currentIndex[elem.name] = elem.value;
                    });
                    break;
                case 'param':
                    var groups = reverseObj(element.parents('div.group'));
            
                    var data = $('#helpdata').data('data');
                    //параметры выбранной категории
                    var currentIndex = data['deal_types'][$('.getparams.active')[0].dataset['deal_type']];

                    $.each(groups, function(index, group) {
                        currentIndex = currentIndex[$(group)[0].dataset['group']];
                    });
                    currentIndex = currentIndex[key];
                    $.each(form, function(index, elem) {
                        currentIndex[elem.name] = elem.value;
                    });
                    break;
                default:
                    break;
            }

            
            console.log("=========== currentIndex ===========");
            console.log(currentIndex);
            console.log("=========== currentIndex ===========");

            

            console.log("=========== currentIndex ===========");
            console.log(currentIndex);
            console.log("=========== currentIndex ===========");

            console.log(data);

            $.post({
                type: "POST",
                url: "/catalog/edit/" + catalog + "/" + type,
                dataType: "JSON",
                data: {
                    "data": data
                }
            })
            .done(function(res) {
                console.log('done');
                console.log(res);
                console.log(element);
                console.log(element.parent('div').parent('div').children('span'));
                
                if (type === 'catalog') {
                    element.parent('div').siblings('a').html(data);
                } else if (type === 'deal_type') {
                    element.parent('div').parent('button').children('span').html(currentIndex['title']);
                } else {
                    element.parent('div').parent('div').children('span').html(currentIndex['label']);
                }
                $('#modal').modal('hide');
            })
            .fail(function(res) {
                console.log('fail');
                console.log(res);
            });

        }

        function deleteElement(element, catalog, type, key) {
            console.log('====== delete =======');
            console.log(element);
            console.log(catalog);
            console.log(type);
            console.log(key);
            console.log('====== delete =======');

            var fields = {};
            var deleteTag = 'li';
            switch (type) {
                case 'deal_type':
                    data = $('#helpdata').data('data');
                    delete data['deal_types'][key];
                    fields = data;
                    deleteTag = 'button';
                    break;
                case 'param':
                    fields = getTreeElement('delete', element);
                    deleteTag = 'div';
                    break;
                default:
                    break;
            }
            console.log(fields);
            // $.post({
            //     type: "POST",
            //     url: "/catalog/delete/" + catalog + "/" + type,
            //     dataType: "JSON",
            //     data: {
            //         "data": fields
            //     }
            // })
            // .done(function(res) {
            //     console.log('done');
            //     console.log(res);
            //     element.parent('div').parent(deleteTag).remove();
            //     if (type === 'catalog') {
            //         $('ul[data-parent="' + catalog + '"]').remove();
            //     }
            //     $('#modal').modal('hide');
            // })
            // .fail(function(res) {
            //     console.log('fail');
            //     console.log(res);
            // });

        }



        function templateParametr(obj) {
            console.log(obj);
            var response = '<form id="form-param" class="form-group">';
            for (key in obj) {
                if (typeof obj[key] !== 'object') {
                    response += key + ': <input name="' + key + '" value="' + obj[key] + '" class="form-control"><br>';
                }
            }
            response += '</form>';
            return response;
        }

        function renderEditButtons(type, element, key) {
            var editLink = '<a class="editbutton" href="/catalog/edit/' + type + '/' + key + '" data-type="' + type + '" data-key="' + key + '"><span class="glyphicon glyphicon-pencil" style="margin-left:10px;" aria-hidden="true"></span></a>';
            var deleteLink = '<a class="deletebutton" href="/catalog/delete/' + type + '/' + key + '"data-type="' + type + '"data-key="' + key + '"><span class="glyphicon glyphicon-remove text-danger" style="margin-left:10px;" aria-hidden="true"></span></a>';
            return '<div class="pull-right">' + editLink + deleteLink + '</div>';;
        }

        function renderSelect(data, element, key) {
            return '<div><span>' + data.label + '</span>' + renderEditButtons('param', data.name, key) + '</div>';
        }
        function renderInput(data, element, key) {
            return '<div><span>' + data.label + '</span>' + renderEditButtons('param', data.name, key) + '</div>';
        }
        function renderTextarea(data, element, key) {
            return '<div><span>' + data.label + '</span>' + renderEditButtons('param', data.name, key) + '</div>';
        }
        function renderGroup(data, element, key) {
            var title = '<h4>' + data.title + renderEditButtons('param', data.title, key) + '</h4><hr>';
            var addParam = '<div><button href="/catalog/create" class="btn btn-success btn-sm" style="margin-bottom:5px;margin-left:20px;">+ Добавить</button></div>';
            return title + addParam;
        }
        function renderDeal_types(data, element, key) {
            var dealTypeButton = $('<button class="btn btn-default getparams" data-deal_type="' + data.name + '" />').append('<span class="pull-left">' + data.title + '</span>');
            $(element).append(dealTypeButton.append(renderEditButtons('deal_type', data.name, key)));
        }


        function renderParams (obj, element, allowParamType) {
            resp = '<div class="maintree">';
            $.makeArray(obj);
            function parseParams (obj, element, allowParamType) {
                for (var key in obj) {
                    if (typeof obj [key] === 'object') {
                        if (obj[key].type) {
                            if (!allowParamType || allowParamType.indexOf(obj[key].type) > -1 || allowParamType === obj[key].type) {
                                switch (obj[key].type) {
                                    case 'select':
                                        resp += renderSelect(obj[key], element, key);
                                        parseParams(obj[key], element, allowParamType);
                                        break;
                                    case 'input':
                                        resp += renderInput(obj[key], element, key);
                                        parseParams(obj[key], element, allowParamType);
                                        break;
                                    case 'textarea':
                                        resp += renderTextarea(obj[key], element, key);
                                        parseParams(obj[key], element, allowParamType);
                                        break;
                                    case 'group':
                                        resp += renderGroup(obj[key], element, key);
                                        resp += '<div class="group" style="margin-left:20px;" data-group="' + key + '">';
                                            parseParams(obj[key], element, allowParamType);
                                        resp += '</div>';
                                        break;
                                    case 'deal_type':
                                        resp += renderDeal_types(obj[key], element, key);
                                        parseParams(obj[key], element, allowParamType, key);
                                        break;

                                    default:
                                        parseParams(obj[key], element, allowParamType);
                                        break;
                                }
                            } else {
                                parseParams(obj[key], element, allowParamType);
                            }
                        }
                    }
                }
            }
            parseParams(obj, element, allowParamType);
            resp += '</div>';
            return resp;
        }

        $(document).ready(function () {
            $.ajaxSetup({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
            });
            /*
                запрос параметров категории и отрисовка кнопок "тип сделки"
             */
            $('.box-container').on('click', '.getdeal_types', function (event) {
                event.preventDefault();
                $('#deal_type, #params').empty(); // очищаю кнопки и параметры
                var link = $(this);
                $('.leftbar li').removeClass('active'); // убираю ксласс active у категории в каталоге
                link.parent('li').addClass('active'); // добавлюя класс active выбранной категории
                $.ajax({
                    method: "GET",
                    url: link[0].href
                })
                .done(function(res) {
                    console.log(res);
                    $('#helpdata').data('data', res.category.fields); // записываю json от сервера в невидимый див для дальнейшей работы с датой
                    $('.addgroup').attr({disabled: true});
                    $('.adddeal_type').attr({disabled: false});
                    renderParams(res.category.fields.deal_types, '#deal_type', 'deal_type'); // отрисовываю кнопки "тип сделки"
                })
                .fail(function(res) {
                    console.log('fail');
                    console.log(res);
                });
            });
            /*
                парсинг параметров выбранного типа сделки
             */
            $('.box-container').on('click', '.getparams', function (event) {
                event.preventDefault();
                $('#params').empty(); // очищаю параметры
                $('.addgroup').attr({disabled: false});
                var deal_type = $(this);
                $('#deal_type button').removeClass('active'); // убираю ксласс active у кнопок "тип сделки"
                deal_type.addClass('active'); // добавлюя класс active выбранной кнопке
                var deal_typeValue = deal_type[0].dataset['deal_type']; // определяю выбранною кнопку, чтобы достать из даты (#helpdata), соответствующие параметры
                var data = $('#helpdata').data('data');
                $('#params').append(renderParams(data['deal_types'][deal_typeValue], '#params', false)); // отрисовываю параметры, false - все параметры
            });

            /*
                обработка кнопки удаления
             */

             $('.box-container').on('click', 'a.deletebutton', function(event) {
                event.preventDefault();

                var currentElement = $(this);
                var data = [];

                data['currentElement'] = currentElement;
                $('#modal').data('data', data);
                $('.modal-title').html('Удаление');
                $('.modal-body').html('Вы действительно хотите удалить элемент?');
                $('.modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button><button id="btn-delete" type="button" class="btn btn-primary">Удалить</button>');
                $('#modal').modal();
            });

            $('.modal-dialog').on('click', '#btn-delete', function(event) {
                event.preventDefault();

                var currentElement = $('#modal').data('data')['currentElement'];
                console.log(currentElement);
                var typeCurrentElement = currentElement[0].dataset['type'];
                var keyCurrentElement = currentElement[0].dataset['key'];
                var activeCatalog = (typeCurrentElement === 'catalog') ? keyCurrentElement : $('.catalog-list.active > a.getdeal_types')[0].dataset['key'];

                deleteElement(currentElement, activeCatalog, typeCurrentElement, keyCurrentElement);

            });

            /*
                обработка кнопки правки
             */

             $('.box-container').on('click', 'a.editbutton', function(event) {
                event.preventDefault();

                var currentElement = $(this);
                var data = [];

                data['currentElement'] = currentElement;
                console.log(currentElement);
                $('#modal').data('data', data);

                typeCurrentElement = currentElement[0].dataset['type'];
                switch (typeCurrentElement) {
                    case 'catalog':
                            var modalTitle = $(this).parent('div').siblings('a')[0].innerText;
                            var modalBody = '<form id="form-param" class="form-group">name: <input name="name" value="' + modalTitle + '" class="form-control"></form>';
                        break;
                    case 'deal_type':
                            var modalBody = 'type deal';
                            var modalBody = templateParametr(getTreeElement('edit', $(this)));
                        break;
                    case 'param':
                        var modalTitle = $(this).parent('div').parent('div')[0].innerText;
                        var modalBody = templateParametr(getTreeElement('edit', $(this)));
                        break;
                    default:
                        break;

                }

                $('.modal-title').html(modalTitle);
                $('.modal-body').html(modalBody);
                $('.modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button><button id="btn-save" type="button" class="btn btn-primary">Сохранить</button>');
                $('#modal').modal();
            });

             $('.modal-dialog').on('click', '#btn-save', function(event) {
                event.preventDefault();
                var currentElement = $('#modal').data('data')['currentElement'];
                console.log("=========== btn-save ===========");
                console.log(currentElement);
                console.log("=========== btn-save ===========");
                console.log(currentElement);
                var typeCurrentElement = currentElement[0].dataset['type'];
                var keyCurrentElement = currentElement[0].dataset['key'];
                var activeCatalog = (typeCurrentElement === 'catalog') ? keyCurrentElement : $('.catalog-list.active > a.getdeal_types')[0].dataset['key'];

                editElement(currentElement, activeCatalog, typeCurrentElement, keyCurrentElement);

            });

             /*
                добавление категории
             */

             $('.box-container').on('click', '.addcategory', function(event) {
                event.preventDefault();

                getCategoriesSelect().then(function(res) {
                    $('.modal-title').html('Добавить категорию');
                    $('.modal-body').html(addCategoryForm(res));
                    $('.modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button><button id="btn-add-category" type="button" class="btn btn-primary">Добавить</button>');
                    $('#modal').modal();
                });
            });
            $('.modal-dialog').on('click', '#btn-add-category', function(event) {
                addCategory();
            });

             /*
                добавление группы
             */

             $('.box-container').on('click', '.addgroup', function(event) {
                event.preventDefault();
                var groups = renderSelectGroups();
                $('.modal-title').html('Добавить группу');
                $('.modal-body').html(addGroupForm(groups));
                $('.modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button><button id="btn-add-group" type="button" class="btn btn-primary">Добавить</button>');
                $('#modal').modal();
            });
             $('.modal-dialog').on('click', '#btn-add-group', function(event) {
                addGroup();
            });
        });

        function renderSelectGroups() {
            var groups = $('div.group');
            var select = '<select name="group-param" class="form-control">';
            select += '<option value="0">Нет родителя</option>';
            $.each(groups, function(index, group) {
                console.log($(group));
                select += '<option value="' + $(group)[0].dataset['group'] + '">' + $(group)[0].dataset['group'] + '</option>';
            })
            select += '</select>';
            return select;
        }

        function addGroup() {
            var form = $('#add-category').serializeArray();
            var data = $('#helpdata').data('data');
            var activeDealType = $('.getparams.active')[0].dataset['deal_type'];
            var currentIndex = data['deal_types'][activeDealType];
            var newGroup = {
                "type": "group",
                "title": form[1].value,
            }
            if (form[0].value === '0') {
                currentIndex[form[2].value] = newGroup;
            } else {
                currentIndex[form[0].value][form[2].value] = newGroup;
            }
            console.log(data);
            var catalog = $('.catalog-list.active')[0].dataset['id'];
            $.post({
                type: "POST",
                url: "/catalog/edit/" + catalog + "/param",
                dataType: "JSON",
                data: {
                    "data": data
                }
            })
            .done(function(res) {
                console.log('done');
                console.log(res);
                $('#params').html(renderParams(data['deal_types'][activeDealType], '#params', false)); // отрисовываю параметры, false - все параметры
                $('#modal').hide();
                $('#modal').modal('hide');
            })
            .fail(function(res) {
                console.log('fail');
                console.log(res);
            });
        }

        function renderAddedCategory(form, res) {
            var ul = $('ul[data-id="' + form[0].value + '"]');
            var id = res.category.id;
            var buttons = renderEditButtons('catalog', 'element', id);
            if (ul.length > 0) {
                var newA = $('<a class="getdeal_tpyes" href="/catalog/category/getparams/' + id + '" data-type="catalog" data-key="' + id + '" />').html(form[1].value)
                var newLi = $('<li class="catalog-list" data-id="' + form[0].value + '" />').html(newA);
                ul.append(newLi.append(buttons));
            } else {
                var li = $('li[data-id="' + form[0].value + '"]');
                var newUl = $('<ul data-parent="' + form[0].value + '" data-id="' + form[0].value + '" />');
                var newA = $('<a class="getdeal_tpyes" href="/catalog/category/getparams/' + id + '" data-type="catalog" data-key="' + id + '" />').html(form[1].value)
                var newLi = $('<li class="catalog-list" data-id="' + id + '" />').html(newA);
                li.after(newUl.append(newLi.append(buttons)));
            }
        }

        function addCategory() {
            var form = $('#add-category').serializeArray();
            console.log(form);
            $.ajax({
                type: "POST",
                url: "/catalog/addcategory",
                dataType: "JSON",
                data: form
            })
            .done(function (res) {
                renderAddedCategory(form, res);
                $('#modal').modal('hide');
            })
            .fail(function (res) {
                console.log('fail...');
            });
        }

        function getCategoriesSelect() {
            return $.ajax({
                type: "GET",
                url: "/catalog/getcategoriesselect/",
                dataType: "JSON"
            });
        }

        function addCategoryForm(data) {
            var response = '<div class="row">';
            response += '<form id="add-category" class="form-group col-md-16">';
            response += '<div class="form-group"><label for="pid">Выберите родителя </label>';
            response += data;
            response += '</div>';
            response += '<div class="form-group"><label for="pid">Название </label>';
            response += '<input name="name" value="" class="form-control">';
            response += '</div>';
            response += '</form>';
            response += '</div>';
            return response;
        }
        function addGroupForm(data) {
            var response = '<div class="row">';
            response += '<form id="add-category" class="form-group col-md-16">';
            response += '<div class="form-group"><label for="pid">Выберите родителя </label>';
            response += data;
            response += '</div>';
            response += '<div class="form-group"><label for="pid">Название </label>';
            response += '<input name="name" value="" class="form-control">';
            response += '</div>';
            response += '<div class="form-group"><label for="pid">Идентификатор (на англ.) </label>';
            response += '<input name="key" value="" class="form-control">';
            response += '</div>';
            response += '</form>';
            response += '</div>';
            return response;
        }

    </script>
</body>