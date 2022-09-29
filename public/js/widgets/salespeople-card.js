let BAB_frameUrl = null;
let BAB_modalBodyDiv=null;
let BAB_picUrl = null;
let font_family_3col = 'Mulish';
let color_code_3col = '#2765B7';
let font_family_srpbutton = 'Mulish';
let color_code_srpbutton = '#2765B7';
let vin_3col = null;
let BAB_processing = false;


(function(){

    let dealerId=null;
    let accessToken = null;
    let jsonData = {};
    let cardSectionDiv=null;
    let bab_div_ct = 0;
    let bab_div_col3_ct = 0;
    let bab_div_srpbutton_ct = 0;
    let globalCssSet = false;
    let navigation_3col = {rows:0,current:0};


    //post message to close iframe from inside frame
    window.addEventListener("message", (event) => {

        if(event.data == 'close'){
            closeFrame();
        }

    }, false);


    //Check and start dealer salescard display
    let interval = setInterval(function(){
        bab_div_ct +=1;
        if (document.getElementById('bab_div')){
            document.getElementById('bab_div').style.display = 'none';
            stopInterval(interval);
            GetBabData('dealer_salescard');
        }

        if(bab_div_ct >= 50){
            stopInterval(interval);
        }
    }, 100);

    //Check and start dealer salescard three column display
    let interval3col = setInterval(function(){
        bab_div_col3_ct +=1;
        if (document.getElementById('bab_div_3col')){
            document.getElementById('bab_div_3col').style.display = 'none';
            stopInterval(interval3col);
            GetBabData('dealer_salescard_3col');

        }

        if(bab_div_col3_ct >= 50){
            stopInterval(interval3col);
        }
    }, 100);

    //Check and start srp button
    let interval_srpbutton = setInterval(function(){
        bab_div_srpbutton_ct +=1;
        if (document.getElementsByClassName('bab_div_srpbutton')[0]){
            stopInterval(interval_srpbutton);
            displayType('dealer_srpbutton');

        }

        if(bab_div_srpbutton_ct >= 50){
            stopInterval(interval_srpbutton);
        }
    }, 100);

    function stopInterval(current_interval){
        clearInterval(current_interval);
    }

    function displayType(type){
        if(type == "dealer_salescard"){
            appendCss('dealer_salescard');

            document.querySelectorAll("[id='bab_div']").forEach(function (element) {
                buildDiv_dealersalescard(element);
            });

        }

        if(type == "dealer_salescard_3col"){
            appendCss('dealer_salescard_3col');
            document.querySelectorAll("[id='bab_div_3col']").forEach(function (element) {
                buildDiv_dealersalescard_3col(element);
            });
        }

        if(type == "dealer_srpbutton"){
            appendCss('dealer_srpbutton');
            buildDiv_dealersalescard_srpbutton();
        }
    }


    //Start when document is present
    function GetBabData(type) {


        if(type == "dealer_salescard"){
            //get credentials from div placed in code
            accessToken = document.getElementById('bab_div').getAttribute('token');
            dealerId = document.getElementById('bab_div').getAttribute('dealer_id');
        }else if(type == "dealer_salescard_3col"){
            //get credentials from div placed in code
            accessToken = document.getElementById('bab_div_3col').getAttribute('token');
            dealerId = document.getElementById('bab_div_3col').getAttribute('dealer_id');

            if(document.getElementById('bab_div_3col').hasAttribute('font_family') == true){
                font_family_3col = document.getElementById('bab_div_3col').getAttribute('font_family');
            }
            if(document.getElementById('bab_div_3col').hasAttribute('color_code') == true){
                color_code_3col = document.getElementById('bab_div_3col').getAttribute('color_code');
            }

            if(document.getElementById('bab_div_3col').hasAttribute('vin') == true){
                vin_3col = document.getElementById('bab_div_3col').getAttribute('vin');
            }


        }

        if(jsonData.length > 0 && BAB_processing == false){
            displayType(type);
            return;
        }

        BAB_processing = true;


        if(window.location.hostname == 'bab-api-jasonrwhite1983105091.codeanyapp.com'){
            var url = 'https://port-6000-bab-api-jasonrwhite1983105091.codeanyapp.com/getSalesPeople?accessToken='+accessToken+'&d_id='+dealerId;
            BAB_frameUrl = 'https://bab-api-jasonrwhite1983105091.codeanyapp.com';
            BAB_picUrl = 'https://beta-toolkit-api.azurewebsites.net/uploads/media/profile_images/';
        }else{
            var url = 'https://api.buildabrand.com/getSalesPeople?accessToken='+accessToken+'&d_id='+dealerId;
            BAB_frameUrl = 'https://expressbuying.com';
            BAB_picUrl = 'https://prod-toolkit-api.azurewebsites.net/uploads/media/profile_images/';
        }
        //Get salespeople of dealer
        //

        fetch(url)
            .then(response => {
                var info = response.json();
                if(response.status == 200){
                    if (jsonData.length > 0){
                        displayType(type);
                        throw new Error('Info Data source already set');
                    }else{
                        return info;
                    }

                }else{
                    throw new Error('Error getting salespeople');
                }


            })
            .then(data => {
                if (jsonData.length > 0){
                    displayType(type);
                    throw new Error('Info Data source already set');
                }else{
                    jsonData = data.salespeople.salespeople;
                    if(jsonData.length > 0){
                        //build salespeople card
                        displayType(type);
                    }else{
                        throw new Error('No salespeople returned');
                    }
                }


            })
            .catch((error) =>{
                console.log(error);
            })
    }

    function appendCss(type){
        let head = document.head || document.getElementsByTagName('head')[0];
        if(!globalCssSet){
            const style = document.createElement('style');
            style.innerHTML = css;
            head.append(style);
            globalCssSet = true;
        }
        if(type == "dealer_salescard"){

            const style = document.createElement('style');
            style.innerHTML = bab_css;
            head.append(style);
        }

        if(type == "dealer_salescard_3col"){
            const style = document.createElement('style');
            style.innerHTML = get3colCss(font_family_3col, color_code_3col);
            head.append(style);
        }

        if(type == "dealer_srpbutton"){
            const style = document.createElement('style');
            style.innerHTML = getSrpButtonCss(font_family_srpbutton, color_code_srpbutton);
            head.append(style);
        }

    }

    function closeFrame(){
        document.getElementsByTagName("body")[0].style.removeProperty('overflow-y');// enable vertical scroll

        document.querySelectorAll(".bab-modal").forEach(function(item){
            if(!!( item.offsetWidth || item.offsetHeight || item.getClientRects().length )){
                item.style.transform = 'scale(0)'; //shows iframe block

            }
        });


        document.querySelectorAll(".bab-modal-body").forEach(function(item){
            if(!!( item.offsetWidth || item.offsetHeight || item.getClientRects().length )){
                item.innerHTML = '';

            }
        });

        //Change z-index of header to not block modal
        if(dealerId == 449){
            document.querySelectorAll(".headerWrapper").forEach(function(item){
                console.log(item);
                item.style.zIndex = "1030";
            });

            document.querySelectorAll(".navbar-header").forEach(function(item){
                console.log(item);
                item.style.display = "block";
            });

            document.querySelectorAll(".static-to-nav .btn-group").forEach(function(item){
                console.log(item);
                item.style.display = "block";
            });

        }


        //document.getElementsByClassName("bab-modal")[0].style = 'transform: scale(0)'; //closes iframe block
        //document.getElementsByClassName("bab-modal-body")[0].innerHTML = '';
    }

    function launchBab(access_token, d_id, s_id, type){
        console.log('here');
        //create iframe, get outer div to append frame inside
        var url = BAB_frameUrl+'/welcome?accessToken='+access_token+'&d_id='+d_id+'&s_id='+s_id;

        if(type == '3col'){
            url = url+'&source=vdp';
        }else{
            url = url+'&source=dealer';
        }
        if(vin_3col != null){
            url = url+'&vin='+vin_3col;
        }

        buildIframe(url, type);
    }

    function buildIframe(url, type){
        var frame = document.createElement('iframe');
        console.log(url);
        console.log(type);
        frame.src = url;
        frame.allowtransparency = "true";
        if(type == '3col'){
            frame.style = "margin-top:10px;width:95%;height:95%;border-radius:.5em;border: 0px solid black;";
        }else{
            frame.style = "margin-top:10px;width:95%;height:95%;border-radius:.5em;border: 0px solid black;";
        }

        //Change z-index of header to not block modal
        if(dealerId == 449){
            document.querySelectorAll(".headerWrapper").forEach(function(item){
                console.log(item);
                item.style.zIndex = "0";
            });

            document.querySelectorAll(".navbar-header").forEach(function(item){
                console.log(item);
                item.style.display = "none";
            });

            document.querySelectorAll(".static-to-nav .btn-group").forEach(function(item){
                console.log(item);
                item.style.display = "none";
            });



        }


        document.querySelectorAll(".bab-modal-body").forEach(function(item){
            console.log(item.style.visibility);
            if(!!( item.offsetWidth || item.offsetHeight || item.getClientRects().length )){
                item.append(frame);
                item.style.transform = 'scale(1)'; //shows iframe block
                item.style = 'overflow-y:hidden';//disable vertical scroll

            }
        });


        document.querySelectorAll(".bab-modal").forEach(function(item){
            if(!!( item.offsetWidth || item.offsetHeight || item.getClientRects().length )){
                item.style.transform = 'scale(1)'; //shows iframe block

            }
        });


        //document.getElementsByClassName("bab-modal-body")[0].append(frame);
        //document.getElementsByClassName("bab-modal")[0].style.transform = 'scale(1)'; //shows iframe block
        document.getElementsByTagName("body")[0].style = 'overflow-y:hidden';//disable vertical scroll
    }

    //All functionality for dealer salescard srp button
    async function buildDiv_dealersalescard_srpbutton(){

        document.querySelectorAll(".bab_div_srpbutton").forEach(function(item){
            var temp_accessToken = item.getAttribute('token');
            var temp_dealerId = item.getAttribute('dealer_id');
            var temp_font_family_srpbutton = 'Mulish';
            var temp_color_code_srpbutton = '#2765B7';
            var temp_vin_srpbutton = null;

            if(item.hasAttribute('font_family') == true){
                temp_font_family_srpbutton = item.getAttribute('font_family');
            }
            if(item.hasAttribute('color_code') == true){
                temp_color_code_srpbutton = item.getAttribute('color_code');
            }

            if(item.hasAttribute('vin') == true){
                temp_vin_srpbutton = item.getAttribute('vin');
            }

            var div = document.createElement('div');
            div.className = "bab_srpbutton";
            div.innerHTML = 'Express Buying';
            item.append(div);

            var url = BAB_frameUrl+'/salesperson-detail?accessToken='+temp_accessToken+'&d_id='+temp_dealerId+'&source=dealer';

            if(temp_vin_srpbutton != null){
                url = url+'&vin='+temp_vin_srpbutton;
            }

            item.addEventListener('click', event => {
                buildIframe(url, 'button');
            });
        });

    }

    //All functionality for dealer salescard 3 column
    async function buildDiv_dealersalescard_3col(element){
        if(dealerId == 449){
            element.innerHTML = bab_modal_3col_449;
        }else{
            element.innerHTML = bab_modal_3col;
        }
        element.getElementsByClassName("bab-cards-section-3col")[0].innerHTML = await populateDiv();
        await addClickEvent(element);
        var col_row = element.getElementsByClassName('bab_col3_row');
        if(col_row.length > 0){
            navigation_3col.rows = (col_row.length-1);
            element.getElementsByClassName('bab_col3_row')[0].style.display = 'grid';
            await addNavigationEvent(element);
            element.getElementsByClassName('bab_3col_dot')[0].style.opacity = 1;
            //navigate3col('null');
        }

        function navigate3col(index, element){

            element.querySelectorAll(".bab_col3_row").forEach(function(item){
                item.style.display="none";
            });
            element.getElementsByClassName('bab_col3_row')[index].style.display = 'grid';

            element.querySelectorAll('.bab_3col_dot').forEach(item => {
                item.style.opacity = .3;
            });
            element.getElementsByClassName('bab_3col_dot')[index].style.opacity = 1;

        }

        function populateDiv(){
            var html = '<div class="bab_col3_row">';
            var html2 = '';
            var salesPeople = jsonData;
            var ct = 1;

            for(var person in salesPeople) {
                if(salesPeople[person].IsShowOnMeetTheTeam == 0){
                    continue;
                }


                html = html +
                    '<div style = "">'+
                    '<img  alt=""  class="img-responsive bab-card-image-3col" src="' + BAB_picUrl + salesPeople[person].photo + '" />'+
                    '<div class = "bab-card-names-3col" >' + salesPeople[person].first + ' ' + salesPeople[person].last +'</div>'+
                    '<div class="bab-card-reviews-3col">Reviews: &nbsp; '+salesPeople[person].review_count+'</div>'+
                    '<div style ="width:100%;text-align:center;padding-top: 5px;padding-bottom:5px;">'+
                    '<button  token="'+accessToken+'" d_id="'+dealerId+'" s_id="'+salesPeople[person].s_id+'"  class="bab-connect-3col"  >Start Here</button>'+
                    '</div>'+
                    '</div>';

                if(ct % 3 == 0 ){
                    html = html + '</div><div class="bab_col3_row">';
                }
                ct = ct + 1;

            }

            html = html + '</div>';
            return html+html2;
        }

        function addNavigationEvent(element){
            var ele = element.querySelector(".bab_3col_nav");
            for(i=0; i <= navigation_3col.rows; i++){
                var span = document.createElement('span');
                span.setAttribute('data-index', i);
                span.className = "bab_3col_dot";
                ele.append(span);
            }

            element.querySelectorAll('.bab_3col_dot').forEach(item => {
                var index = item.getAttribute('data-index');
                item.addEventListener('click', event => {
                    navigate3col(index,element);
                });

            });

        }

        function addClickEvent(element){
            //Add onclick to launch Iframe, pull attributes for iframe url
            element.querySelectorAll('.bab-connect-3col').forEach(item => {

                var token = accessToken;
                var s_id = item.getAttribute('s_id');
                var d_id = dealerId;
                item.addEventListener('click', event => {
                    launchBab(token, d_id, s_id, '3col');
                });

            });

        }

        document.querySelectorAll("[id='bab_div_3col']").forEach(function (element) {
            element.style.display = 'block';
        });

        //document.getElementById('bab_div_3col').style.display = 'block';
    }



    //All functionality for dealer salescard
    async function buildDiv_dealersalescard(element){
        if(dealerId == 449){
            element.innerHTML = bab_modal_449;
        }else{
            element.innerHTML = bab_modal;
        }
        element.getElementsByClassName("bab-cards-section")[0].innerHTML = await populateDiv();
        await addClickEvent(element);
        element.getElementsByClassName("bab-modal-body")[0]; //Get outer div once and use later
        element.getElementsByClassName("bab-cards-section")[0];
        setTimeout(() => {
            element.style.display = 'block';
        },2500);




        //shiftCarousel('none');



        // document.querySelectorAll('.bab_shift_item').forEach(item => {
        //     var direction = item.getAttribute('direction');
        //     item.addEventListener('click', event => {
        //         shiftCarousel(direction);
        //     });
        // });

        function addClickEvent(element){
            //Add onclick to launch Iframe, pull attributes for iframe url
            element.querySelectorAll('.bab-connect').forEach(item => {

                var token = accessToken;
                var s_id = item.getAttribute('s_id');
                var d_id = dealerId;
                item.addEventListener('click', event => {
                    launchBab(token, d_id, s_id, 'salescard');
                });

            });

        }


        function populateDiv(){
            var html = '';
            var html2 = '';
            var salesPeople = jsonData;

            for(var person in salesPeople) {
                if(salesPeople[person].IsShowOnMeetTheTeam == 0){
                    continue;
                }

                //css image fix for dealer id 19
                let css_update = '';
                //if(dealerId == 19){
                css_update = 'style = "height:initial!important"';
                //}



                html = html +
                    '<div class="bab-card"  >'+
                    '<div style = "height: 74%;position: relative;">'+
                    '<img  alt="" '+css_update+' class="img-responsive bab-card-image" src="' + BAB_picUrl + salesPeople[person].photo + '" />'+
                    '<div class = "bab-card-names" >' + salesPeople[person].first + ' ' + salesPeople[person].last + '<br><span class="bab-card-title" >' + salesPeople[person].title + '</span></div>'+
                    '</div>'+
                    '<div style ="width:100%;text-align:center;padding-top: 5px;padding-bottom:5px;">'+
                    '<button  token="'+accessToken+'" d_id="'+dealerId+'" s_id="'+salesPeople[person].s_id+'"  class="bab-connect"  >Choose Me</button>'+
                    '</div>';
                if(dealerId == 449){
                    html = html + '<div style = "display: grid;grid-template-columns: 100%;">'+
                        '<div style="font-family: Mulish;font-size:12px;text-align: center;color: #00000075;font-weight: 600;">Total Reviews<div style="font-weight: bold;font-size: 12px;color: black;margin-top: -2px;">'+salesPeople[person].review_count+'</div></div>'+
                        '<div style="font-family: Mulish;font-size:12px;text-align: center;color: #00000075;font-weight: 600;"><div style="font-weight: bold;font-size: 12px;color: black;margin-top: -2px;"></div></div>';
                }else{
                    html = html + '<div style = "display: grid;grid-template-columns: 50% 50%;">'+
                        '<div style="font-family: Mulish;font-size:12px;text-align: center;color: #00000075;font-weight: 600;">Total Reviews<div style="font-weight: bold;font-size: 12px;color: black;margin-top: -2px;">'+salesPeople[person].review_count+'</div></div>'+
                        '<div style="font-family: Mulish;font-size:12px;text-align: center;color: #00000075;font-weight: 600;">Last 30 Days<div style="font-weight: bold;font-size: 12px;color: black;margin-top: -2px;">'+salesPeople[person].reviewCount30days+'</div></div>';

                }

                html = html + '</div>'+
                    '</div>';





            }
            return html+html2;
        }

        function shiftCarousel(direction){
            var client_width = cardSectionDiv.clientWidth;
            var scroll_width = cardSectionDiv.scrollWidth;
            var scroll = cardSectionDiv.scrollLeft;
            var left_shift = document.getElementsByClassName('bab_shift_item left')[0];
            var right_shift = document.getElementsByClassName('bab_shift_item right')[0];
            var shift_amount = parseInt(client_width/200) * 200;

            var end = scroll + client_width;
            var begin = scroll - client_width;

            if(scroll == 0){
                left_shift.style.display = 'none';
            }

            if(end >= scroll_width){
                right_shift.style.display = 'none';
            }else{
                left_shift.style.display = 'block';
            }

            if(begin <= 0){
                left_shift.style.display = 'none';
            }else{
                right_shift.style.display = 'block';
            }

            if(direction == "right"){
                if(end <= scroll_width){
                    cardSectionDiv.scrollLeft += shift_amount;
                    left_shift.style.display = 'block';
                }
                if((scroll_width - client_width) >= scroll){
                    cardSectionDiv.scrollLeft += shift_amount;
                    right_shift.style.display = 'none';
                }
            }
            if(direction == "left"){
                if(scroll > 0){
                    cardSectionDiv.scrollLeft -= shift_amount;
                    right_shift.style.display = 'block';
                }
            }

            if(cardSectionDiv.scrollLeft > 0){
                left_shift.style.display = 'block';
            }

            if((cardSectionDiv.scrollLeft + client_width) < scroll_width){
                right_shift.style.display = 'block';
            }

        }
    }
    //End dealer salescard functionality






    //Dealer salescard template
    let bab_modal = `<div class="bab-modal"><div class="bab-modal-body"></div></div><div class = "bab-cards-header">Start Here and Choose Your Advisor</div><div class = "bab-cards-section"></div>`;

    let bab_modal_449 = `<div class="bab-modal"><div class="bab-modal-body"></div></div><div class = "bab-cards-header">Start Here and Choose Your Product Specialist</div><div class = "bab-cards-section"></div>`;

    //Dealer salescard 3 col template
    let bab_modal_3col = `<div class="bab-modal"><div class="bab-modal-body"></div></div>
        <div class="col3-header-text">Let's Get Started</div>
        <div class="col3-text">Choose your advisor and they will help you before coming to the dealership</div>
          <div class = "bab-cards-section-3col"></div><div class = "bab_3col_nav"></div>`;

    let bab_modal_3col_449 = `<div class="bab-modal"><div class="bab-modal-body"></div></div>
        <div class="col3-header-text">Let's Get Started</div>
        <div class="col3-text">Choose your product specialist and they will help you before coming to the dealership</div>
          <div class = "bab-cards-section-3col"></div><div class = "bab_3col_nav"></div>`;

    //Global css
    let css = `@import url('https://fonts.googleapis.com/css?family=Mulish');.bab-modal {height: 100%;top:0;left:0;width: 100%;position: fixed;z-index: 9999999999;background-color: rgb(0,0,0);background-color: rgba(0,0,0, 0.9);overflow-x: hidden;transform: scale(0);transition: transform 300ms ease;background-color: rgb(0 0 0);}.bab-modal-header {background-color: rgb(0 0 0);justify-content: center;text-align: center;}.bab-modal-body {background-color: rgb(0 0 0);justify-content: center;text-align: center;height: 100%;}`;

    //Dealer salescard css
    let bab_css = ` #bab_div{}.bab-cards-section{display: grid;grid-auto-flow: column;grid-auto-columns: 200px;overflow-x: auto;overscroll-behavior-x: contain;scroll-snap-type: x proximity;grid-gap: 1rem;padding:10px;justify-items: center;scroll-behavior: smooth;padding: 30px;}.bab-cards-header{text-align: center;font-size: 28px;padding-top: 15px;font-family: Mulish;font-size: 35px;}.bab-connect:hover{background-color:#2765b73b!important;}.bab-connect{font-family: Mulish;width: 70%;background-color: #ffff;border: 2px solid #2765B7;color: #2765B7;padding: 8px 2px;text-align: center;text-decoration: none;display: inline-block;margin: 4px 2px;cursor: pointer;border-radius: 30px;font-size: 16px;font-weight: bolder;line-height: 10px;}.bab_shift{padding: 10px 40px 50px 40px;margin: 5px;margin-bottom: 50px;display: flex;justify-content: center;}.bab_shift_item{display: none;margin: -11px 5px 17px;font-size: 35px;background-color: #0080c0;color: #ffff;padding: 8px;font-weight: bold;text-align: center;box-shadow: rgb(0 0 0 / 38%) 5px 5px 10px 1px;}.bab_shift_item .left{float: left;}.bab_shift_item .right{float: right;}.bab_shift_item:hover{opacity: .7;cursor:pointer;}.bab-card{box-shadow: 0px 2px 10px #888888;height: 300px;width: 200px;margin: 10px;padding: 9px;}.bab-card-image{display: block;max-width: 100%;object-fit: cover;height: -webkit-fill-available!important;margin: auto;width: 100%;border-bottom-left-radius: 5px;border-bottom-right-radius: 5px;box-shadow: 0px 4px 5px #3333335e;}.bab-card-names{overflow: hidden;font-family: Mulish;width: 100%;font-size: 18px;font-weight: 600;position: relative;top: -50px;text-align: center;background-color: #272525c7;color: white;border-bottom-left-radius: 5px;border-bottom-right-radius: 5px;}.bab-card-title{font-weight: 100;white-space: nowrap;font-family: Mulish;font-size: 12px;padding: 5px;}`;

    function get3colCss(font_family_3col, color_code_3col) {
        return `
        .bab_col3_row {
            display: none;
            grid-template-columns: 1fr 1fr 1fr;
            grid-gap: 5px;
            padding: 5px;
            justify-items: center;		
	    margin-top: 30px;	
        }
        .bab-card-image-3col{object-fit: cover;width: 100%;max-width:100px;max-height:100px;height:100px;box-shadow: 0px 4px 5px #3333335e;}
        
            
       .bab-card-names-3col{
            margin-top: 5px;
            font-family: ${font_family_3col};
            max-width: 120px;
            padding: 2px;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            text-align: center;
        }
        
        .bab-card-reviews-3col{
            width: 100%;
            font-family: ${font_family_3col};
            font-size: 10px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            text-align: center;
        }
        
        .bab-connect-3col{
            font-family: ${font_family_3col};
            background-color: #ffff;
            border: 2px solid ${color_code_3col};
            color: ${color_code_3col};
            text-align: center;
            padding: 4 8 4;
            cursor: pointer;
            border-radius: 50px;
            font-size: 12px;
            font-weight: bolder;
            line-height: 15px;
        }
        .bab-connect-3col:hover{background-color:${color_code_3col}!important;color:white;}
        
        .col3-header-text{
            font-size: 20px;
            font-weight: 700;
            padding: 5px;
            font-family: ${font_family_3col};
        }
        .col3-text{
            padding: 5px;
            font-size: 15px;
            font-family: ${font_family_3col};
        }
        .bab_div_3col_main{
          padding: 5px;
          box-shadow: 1px 1px 7px 3px #3333335e;
          margin: 30px 0px 10px 0px;
        }
        
        
       .bab_3col_nav{
            text-align: center;
            margin: 10px;
       }
        
        .bab_3col_dot {
          height: 15px;
          width: 15px;
          background-color: ${color_code_3col};
          border-radius: 50%;
          display: inline-block;
          margin-right:20px;
          opacity: .3;
          cursor:pointer;
        }
        
        .bab_3col_dot_active{
            opacity: 1;
        }
                
        .3col_hide{
            transform: scaleY(0);
            transition: transform 400ms ease 0ms;
        }
        
        .3col_show{
            transform: scaleY(1);
            transition: transform 400ms ease 0ms;
        
    `;
    }

    function getSrpButtonCss(font_family_srpbutton, color_code_srpbutton){
        return `
            .bab_div_srpbutton{
                text-align:center;
                cursor:pointer;
                width: 100%;
            
            }
            
            .bab_srpbutton{
                background-color: ${color_code_srpbutton};
                font-family: ${font_family_srpbutton};
                color: white;
            }
        
        `;
    }


})();