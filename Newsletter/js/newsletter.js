if(document.querySelector("#form")){
    let form = document.querySelector("#form");
    form.addEventListener("submit", function(e){
        e.preventDefault();
        form_ajax(this);
    });
}

function form_ajax(form){ 
    let action = "email_integration",
    url = form.dataset.url + `?action=${action}`,
    email = form.email.value,
    response = document.querySelector("#response");
    xml = new XMLHttpRequest;
        
    xml.open("POST", url, true);
    xml.setRequestHeader("content-type", "application/x-www-form-urlencoded");
    
    xml.onprogress = function(){
        response.innerHTML ="...";
    }

    xml.onload = function(){
        if(this.status == 200){
            txt = this.responseText;
            if(txt.search("go-to:") != -1){
                link = txt.substr(6);
                location.href = link;
            }else{
                response.innerHTML = txt;
            }
            
        }else{
            response.innerHTML ="ERRROR: status "+ this.status +" - " + this.responseText;
        }
    }

    xml.onerror = function(){
        response.innerHTML ="Onerror ERRROR: " + this.responseText;
    }
    
    xml.send(`email=${email}`);

}

/*
================================
    Analytics Canvas
================================
*/
if(document.querySelector("canvas")){
    let canvas = document.querySelector('canvas'),
        ctx = canvas.getContext('2d'),
        cw = canvas.width,
        ch = canvas.height,
        line1 = [
            {x:100,y:300},
            {x:200,y:275},
            {x:300,y:265},
            {x:400,y:200},
            {x:500,y:125}
        ],
        line2 = [
            {x:150,y:325},
            {x:250,y:300},
            {x:350,y:300},
            {x:450,y:265},
            {x:550,y:200}
        ];
        draw_chart(ctx, cw, ch, line1, "red", "pink");
        draw_chart(ctx, cw, ch, line2, "blue", "lightblue");
        
    
    
}

function draw_chart(ctx, cw, ch, line, strokeColor, fillColor){
    ctx.beginPath();
    ctx.moveTo(0, ch);
    for(i=0; i<line.length; i++){
        pt = line[i];
        ctx.lineTo(pt.x, pt.y); 
    }
    ctx.strokeStyle = strokeColor;
    ctx.stroke();
    last = line[line.length -1].x;
    ctx.lineTo(last, cw);
    ctx.closePath();
    
    ctx.fillStyle = fillColor;
    ctx.fill();
}

/*
================================
    Mobile Menu
================================
*/

if(document.querySelector('.hamburger-menu')){
    let menu = document.querySelector('.hamburger-menu').parentElement,
        nav = document.querySelector('nav'),
        links = nav.querySelectorAll('a');

    menu.addEventListener('click', function(){
        nav.classList.toggle('active');
        console.log(links);
    });

    links.forEach(l => {
        l.addEventListener('click', function(){
            if(nav.classList.contains('active')){
                nav.classList.toggle('active');
            }
        });
    });

    
}