let searchFilter = {};
let searchUI = {
    list_header : ".list_header",
    show_list_class : "current",
    makesListElement : ".search_nav_make_list",
    makesCheckboxGroup : "make_chkbox_grp",
    modelsCheckboxGroup : "model_chkbox_grp",
    enginesCheckboxGroup : "engine_chkbox_grp",
    transmissionsCheckboxGroup : "transmission_chkbox_grp",
    drivetrainsCheckboxGroup : "drivetrain_chkbox_grp",
    modelsListElement : ".search_nav_model_list",
    enginesListElement : ".search_nav_engine_list",
    transmissionsListElement : ".search_nav_transmission_list",
    drivetrainsListElement : ".search_nav_drivetrain_list",
    conditionsCheckboxGroup : "condition_chkbox_grp",
    breadCrumbBlock: ".breadcrumbs"

};

let searchDataModels = {};

var pagination_page =1;
(function($){
    "use strict";

    //Add click toggle show/hide to list at onready
    searchFilter.addListToggleEvent = () => {
        let elements = $(searchUI.list_header);
        let class_name = searchUI.show_list_class;
        elements.on('click', function(){
            if(!($(this).hasClass(class_name))){
                $(this).removeClass(class_name);
                $(this).next("ul").slideUp();
            }
            $(this).toggleClass(class_name);
            $(this).next("ul").slideToggle("fast");
        });
    }


    //Get selected options before submission
    searchFilter.getSelectedFilters = (checkboxGroup) => {
        let selected = $("input[name='"+checkboxGroup+"']:checked");
        let selectedIds = [];
        if(selected.length < 1) return null;

        $.each(selected, function(ct, obj){
            selectedIds.push(obj.value);
        });

        if(selectedIds.length == 0) return null;

        return selectedIds;
    }


    searchFilter.updateMakesFilter = (arr) => {
        let selectedIds = searchFilter.getSelectedFilters(searchUI.makesCheckboxGroup);
        if(selectedIds == null){
            selectedIds = [];
        }
        let show = false;

        let makesElementList = $(searchUI.makesListElement);
        makesElementList.hide();
        makesElementList.html('');


        let template = '';
        searchDataModels = {};
        $.each(arr, function(make, obj){
            let id = obj.id;
            let count = obj.count;
            let checked = searchFilter.inarray(id, selectedIds);
            if(checked != '') show = true;

            template += searchFilter.makesListTemplate(make, id, count, checked);
            searchDataModels[make] = {};
        });

        makesElementList.html(template);
        if(show){
            makesElementList.show();
        }

    };

    searchFilter.updateModelsFilter = (arr) => {
        let selectedIds = searchFilter.getSelectedFilters(searchUI.modelsCheckboxGroup);
        if(selectedIds == null){
            selectedIds = [];
        }
        let show = false;
        let makeIdsArray = [];

        let modelsElementList = $(searchUI.modelsListElement);
        modelsElementList.hide();
        modelsElementList.html('');


        let template = '';
        let ct = 0;
        $.each(arr, function(model, obj){
            let id = obj.id;
            let count = obj.count;
            let checked = searchFilter.inarray(id, selectedIds);
            let make = obj.make;
            if(checked != '') show = true;

            searchDataModels[make][ct] = {
                id : id,
                model : model,
                checked : checked,
                count : count
            };


            ct++;
        });

        $.each(searchDataModels, function(current_make, current_obj){

            if(searchFilter.inarray(current_make, makeIdsArray) == 'checked'){

            }else{
                makeIdsArray.push(current_make);
                if(Object.keys(current_obj).length !== 0 && current_obj.constructor === Object) {
                    template += `
                        <li style = "list-style: none;color: #0000007d;font-weight: 800;font-size: 12px;padding-top:5px">${current_make}</li>
                    `;
                }
            }


            $.each(current_obj, function(index, data_obj){
                template += searchFilter.modelsListTemplate(data_obj.model, data_obj.id, data_obj.count, data_obj.checked);
            });

        })

        modelsElementList.html(template);
        if(show){
            modelsElementList.show();
        }

    };

    searchFilter.updateEnginesFilter = (arr) => {
        let selectedIds = searchFilter.getSelectedFilters(searchUI.enginesCheckboxGroup);
        if(selectedIds == null){
            selectedIds = [];
        }
        let show = false;
        let enginesElementList = $(searchUI.enginesListElement);
        enginesElementList.hide();
        enginesElementList.html('');


        let template = '';
        searchDataModels = {};
        $.each(arr, function(engine, obj){
            let count = obj.count;
            let checked = searchFilter.inarray(engine, selectedIds);
            if(checked != '') show = true;

            template += searchFilter.enginesListTemplate(engine, count, checked);
            searchDataModels[engine] = {};
        });

        enginesElementList.html(template);
        if(show){
            enginesElementList.show();
        }

    };

    searchFilter.updateTransmissionsFilter = (arr) => {
        let selectedIds = searchFilter.getSelectedFilters(searchUI.transmissionsCheckboxGroup);
        if(selectedIds == null){
            selectedIds = [];
        }
        let show = false;
        let transmissionsElementList = $(searchUI.transmissionsListElement);
        transmissionsElementList.hide();
        transmissionsElementList.html('');


        let template = '';
        searchDataModels = {};
        $.each(arr, function(transmission, obj){
            let count = obj.count;
            let checked = searchFilter.inarray(transmission, selectedIds);
            if(checked != '') show = true;

            template += searchFilter.transmissionsListTemplate(transmission, count, checked);
            searchDataModels[transmission] = {};
        });

        transmissionsElementList.html(template);
        if(show){
            transmissionsElementList.show();
        }

    };


    searchFilter.updateDrivetrainsFilter = (arr) => {
        let selectedIds = searchFilter.getSelectedFilters(searchUI.drivetrainsCheckboxGroup);
        if(selectedIds == null){
            selectedIds = [];
        }
        let show = false;
        let drivetrainsElementList = $(searchUI.drivetrainsListElement);
        drivetrainsElementList.hide();
        drivetrainsElementList.html('');


        let template = '';
        searchDataModels = {};
        $.each(arr, function(drivetrain, obj){
            let count = obj.count;
            let checked = searchFilter.inarray(drivetrain, selectedIds);
            if(checked != '') show = true;

            template += searchFilter.drivetrainsListTemplate(drivetrain, count, checked);
            searchDataModels[drivetrain] = {};
        });

        drivetrainsElementList.html(template);
        if(show){
            drivetrainsElementList.show();
        }

    };





    searchFilter.modelsListTemplate = (model, id, count, checked) => {
        let template = ``;

        template += `
            <li style = "list-style: none;padding-left: 15px;">
                <label style = "cursor:pointer;margin-bottom: 0px">
                    <input onclick="submitFormEvent()" data-group="model_chkbox_grp" class="contact-box" ${checked} data-alias="Model" data-display="${model}" value="${id}" name="model_chkbox_grp" type="checkbox">
                    <span style = "font-size: 13px;font-weight: 600;">${model} (${count})</span>
                </label>
            </li>
        `;
        return template;
    };

    searchFilter.makesListTemplate = (make, id, count, checked) => {
        return `
            <li class = "" style = "list-style: none;">
                <label style = "cursor:pointer;margin-bottom: 0px">
                    <input ${checked} onclick="submitFormEvent()" data-group="make_chkbox_grp" class="contact-box"  data-alias="Make" data-display="${make}" value="${id}" name="make_chkbox_grp" type="checkbox">
                    <span style = "font-size: 13px;font-weight: 600;">${make} (${count})</span>
                </label>
            </li>
        `;
    }

    searchFilter.enginesListTemplate = (engine, count, checked) => {
        return `
            <li class = "" style = "list-style: none;">
                <label style = "cursor:pointer;margin-bottom: 0px">
                    <input ${checked} onclick="submitFormEvent()" data-group="engine_chkbox_grp" class="contact-box"  data-alias="Engine" data-display="${engine}" value="${engine}" name="engine_chkbox_grp" type="checkbox">
                    <span style = "font-size: 13px;font-weight: 600;">${engine} (${count})</span>
                </label>
            </li>
        `;
    }

    searchFilter.transmissionsListTemplate = (transmission, count, checked) => {
        return `
            <li class = "" style = "list-style: none;">
                <label style = "cursor:pointer;margin-bottom: 0px">
                    <input ${checked} onclick="submitFormEvent()" data-group="transmission_chkbox_grp" class="contact-box"  data-alias="Transmission" data-display="${transmission}" value="${transmission}" name="transmission_chkbox_grp" type="checkbox">
                    <span style = "font-size: 13px;font-weight: 600;">${transmission} (${count})</span>
                </label>
            </li>
        `;
    }

    searchFilter.drivetrainsListTemplate = (drivetrain, count, checked) => {
        return `
            <li class = "" style = "list-style: none;">
                <label style = "cursor:pointer;margin-bottom: 0px">
                    <input ${checked} onclick="submitFormEvent()" data-group="drivetrain_chkbox_grp" class="contact-box"  data-alias="Drivetrain" data-display="${drivetrain}" value="${drivetrain}" name="drivetrain_chkbox_grp" type="checkbox">
                    <span style = "font-size: 13px;font-weight: 600;">${drivetrain} (${count})</span>
                </label>
            </li>
        `;
    }

    searchFilter.breadCrumbsTitleTemplate = () => {
        return '';
        return `
           <div style = "margin-right: 5px;font-size: 12px;font-weight: 700;margin-top: 6px;">Filters:</div> 
        `;
    }

    searchFilter.breadCrumbsTemplate = (alias, display) => {
        return `
            <div id="${alias}${display}" style = "display:inline-block">
            <div  style = "margin-bottom:5px;margin-right:5px;max-height:25px;position:relative;border: 1px solid #039BE5;width: max-content;padding: 5px;font-size: 9px;font-weight: 500;border-radius: 20px;text-overflow: ellipsis; max-width:125px;overflow: hidden; white-space: nowrap;padding-right:18px">
                ${display.substring(0,30)} 
                 <i  data-alias="${alias}"  data-display="${display}" onclick="searchFilter.deleteBreadCrumb(this)"  style="font-size:12px;cursor: pointer;color: #039BE5;position: absolute;right: 4px;top: 5px;" class="fas fa-times-circle delete_breadcrumb"></i>

            </div>
            </div>
        `;
    }

    searchFilter.deleteBreadCrumb = (obj) => {
        var display = obj.getAttribute('data-display');
        var alias = obj.getAttribute('data-alias');
        if(alias == 'Price'){
            rsp.value([0, 0]);
        }else if(alias == 'Year'){
            rsy.value([0, 0]);
            console.log(rsy.value());
        }else if(alias == 'Mileage'){
            rsm.value([0, 0]);
        }else if(alias == 'Search'){
            $('#v_search').val('');
        }else{
            $("input[data-display='" + display +"']").prop('checked', false);
        }


         var id = alias+display;
         $(".breadcrumbs").find("#"+id).remove();
         submitFormEvent();
    }


    searchFilter.buildBreadCrumbs = () => {
        let breadCrumbBlock = $(searchUI.breadCrumbBlock);
        breadCrumbBlock.html('');
        breadCrumbBlock.append(searchFilter.breadCrumbsTitleTemplate());
        let conditionSelected = $("input[name='"+searchUI.conditionsCheckboxGroup+"']:checked");
        if(conditionSelected.length != {}){
            $.each(conditionSelected, function(i, obj){
                let alias = obj.getAttribute('data-alias');
                let display = obj.getAttribute('data-display');
                breadCrumbBlock.append(searchFilter.breadCrumbsTemplate(alias, display));
            });
        }

        let makeSelected = $("input[name='"+searchUI.makesCheckboxGroup+"']:checked");
        if(makeSelected.length != {}){
            $.each(makeSelected, function(i, obj){
                let alias = obj.getAttribute('data-alias');
                let display = obj.getAttribute('data-display');
                breadCrumbBlock.append(searchFilter.breadCrumbsTemplate(alias, display));
            });
        }

        let modelSelected = $("input[name='"+searchUI.modelsCheckboxGroup+"']:checked");
        if(modelSelected.length != {}){
            $.each(modelSelected, function(i, obj){
                let alias = obj.getAttribute('data-alias');
                let display = obj.getAttribute('data-display');
                breadCrumbBlock.append(searchFilter.breadCrumbsTemplate(alias, display));
            });
        }

        let engineSelected = $("input[name='"+searchUI.enginesCheckboxGroup+"']:checked");
        if(engineSelected.length != {}){
            $.each(engineSelected, function(i, obj){
                let alias = obj.getAttribute('data-alias');
                let display = obj.getAttribute('data-display');
                breadCrumbBlock.append(searchFilter.breadCrumbsTemplate(alias, display));
            });
        }

        let transmissionSelected = $("input[name='"+searchUI.transmissionsCheckboxGroup+"']:checked");
        if(transmissionSelected.length != {}){
            $.each(transmissionSelected, function(i, obj){
                let alias = obj.getAttribute('data-alias');
                let display = obj.getAttribute('data-display');
                breadCrumbBlock.append(searchFilter.breadCrumbsTemplate(alias, display));
            });
        }

        let drivetrainSelected = $("input[name='"+searchUI.drivetrainsCheckboxGroup+"']:checked");
        if(drivetrainSelected.length != {}){
            $.each(drivetrainSelected, function(i, obj){
                let alias = obj.getAttribute('data-alias');
                let display = obj.getAttribute('data-display');
                breadCrumbBlock.append(searchFilter.breadCrumbsTemplate(alias, display));
            });
        }



        let price_low = rsp.value()[0];
        let price_high = rsp.value()[1];
        breadCrumbBlock.append(searchFilter.breadCrumbsTemplate('Price', price_low+' - '+price_high));

        let year_low = rsy.value()[0];
        let year_high = rsy.value()[1];
        breadCrumbBlock.append(searchFilter.breadCrumbsTemplate('Year', year_low+' - '+year_high));

        let mileage_low = rsm.value()[0];
        let mileage_high = rsm.value()[1];
        breadCrumbBlock.append(searchFilter.breadCrumbsTemplate('Mileage', mileage_low+' - '+mileage_high));

        if($.trim($('#v_search').val())  != '' && $.trim($('#v_search').val()) != undefined ){
            breadCrumbBlock.append(searchFilter.breadCrumbsTemplate('Search', $('#v_search').val()));
        }




    }




    searchFilter.clearCheckboxGroup = (checkboxGroup) => {
        let selected = $("input[name='"+checkboxGroup+"']:checked");
        if(selected.length < 1) return null;

        $.each(selected, function(ct, obj){
            $("input[name='"+obj.name+"']").prop("checked", false);
        });

    }

    searchFilter.clearFilters = () =>{
        searchFilter.clearCheckboxGroup(searchUI.conditionsCheckboxGroup);
        searchFilter.clearCheckboxGroup(searchUI.makesCheckboxGroup);
        searchFilter.clearCheckboxGroup(searchUI.modelsCheckboxGroup);
        searchFilter.clearCheckboxGroup(searchUI.enginesCheckboxGroup);
        searchFilter.clearCheckboxGroup(searchUI.transmissionsCheckboxGroup);
        searchFilter.clearCheckboxGroup(searchUI.drivetrainsCheckboxGroup);

    }


    searchFilter.inarray = function(value, array){
        if(array.length == 0)return '';

        for(var i = 0; i < array.length; i++){
            if(array[i] == value){
                return 'checked';
            }
        }
        return '';
    }

    //Document ready functions
    $(document).ready(function () {
        searchFilter.addListToggleEvent();

    });

})(jQuery);