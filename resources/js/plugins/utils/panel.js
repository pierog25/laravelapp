import { TweenMax, Power2, TimelineLite } from "gsap/TweenMax";
export default class Panel{

    static panelWidth(){

    }
    static tweenPanel(item,num,animation = 0){
        let ani = (animation == 0)? Back.easeOut : Back.easeIn;
        TweenLite.to($(item), 0, {width: num});
    }
    static widthPanel(detalle){
        if ($(window).width() <= 900) {
            Panel.tweenPanel('#origin',"0%",0);
            Panel.tweenPanel('#detail',"100%",0);

        } else {
            if(!detalle){
                Panel.opemPanel();
            }
        }
    }

    static widthPanelFull(detalle){
        if ($(window).width() <= 900){
            if(detalle){
                Panel.tweenPanel('#origin',"0%",0);
                Panel.tweenPanel('#detail',"100%",0);
            }else{

                Panel.tweenPanel('#origin',"100%",0);
                Panel.tweenPanel('#detail',"0%",0);
            }
        } else {
            if(detalle){
                Panel.tweenPanel('#origin',"0%",0);
                Panel.tweenPanel('#detail',"100%",0);
            }else{

                Panel.tweenPanel('#origin',"40%",0);
                Panel.tweenPanel('#detail',"60%",0);
            }
        }
    }

    static opemPanelDetail(){
        Panel.tweenPanel('#origin',"0%",0);
        Panel.tweenPanel('#detail',"100%",0);
    }
    static closePanelDetail(){
        // Panel.tweenPanel('#origin',"40%",0);
        // Panel.tweenPanel('#detail',"60%",0);

        Panel.tweenPanel('#origin',"50%",0);
        Panel.tweenPanel('#detail',"50%",0);
    }

    static opemPanelDetail50(){
        Panel.tweenPanel('#origin',"50%",0);
        Panel.tweenPanel('#detail',"50%",0);
    }

    static opemPanelEdit(){
        if( $(document).width() <= 992  ) {

            Panel.tweenPanel('#origin',"100%",0);
            Panel.tweenPanel('#detail',"0%",0);

        } else {
            Panel.navBars()
        }
        
    }

    static closePanelEdit(){
        if( $(document).width() <= 992  ) {

            Panel.tweenPanel('#origin',"0%",0);
            Panel.tweenPanel('#detail',"100%",0);

        }
        
    }

    static navBarsId(){
        if(  ( $("#origin").width() / $("#origin").parent().width() * 100 ) <= 50  ) {

            $("#res_dropdown-two").addClass('res-t__m-l-n-80-i');
            $("#res_dropdown-two").addClass('res-4__m-90-b-i');

            $("#res_dropdown-three").addClass('res-t__m-l-n-180-i');
            $("#res_dropdown-three").addClass('res-4__m-200-b-i');

        } else {
            
            $("#res_dropdown-two").removeClass('res-t__m-l-n-80-i');
            $("#res_dropdown-two").removeClass('res-4__m-90-b-i');

            $("#res_dropdown-three").removeClass('res-t__m-l-n-180-i');
            $("#res_dropdown-three").removeClass('res-4__m-200-b-i');
        }
    }

    static navBars(){
        if(  ( $("#origin").width() / $("#origin").parent().width() * 100 ) <= 50  ) {

            $(".res_dropdown-two").addClass('res-t__m-l-n-80-i');
            $(".res_dropdown-two").addClass('res-4__m-90-b-i');

            $(".res_dropdown-three").addClass('res-t__m-l-n-180-i');
            $(".res_dropdown-three").addClass('res-4__m-200-b-i');

        } else {
            $(".res_dropdown-two").removeClass('res-t__m-l-n-80-i');
            $(".res_dropdown-two").removeClass('res-4__m-90-b-i');

            $(".res_dropdown-three").removeClass('res-t__m-l-n-180-i');
            $(".res_dropdown-three").removeClass('res-4__m-200-b-i');
        }
    }

    static opemPanel(){

        if($(window).width() <= 992){
            Panel.tweenPanel('#origin',"0%",0);
            Panel.tweenPanel('#detail',"100%",0);
            $("#logo-shalom-responsive img").addClass('d-none');

            $(".res__card-remitente").removeClass('col-lg-4');
            $(".res__card-remitente").addClass('col-lg-12');

        }else{
            Panel.tweenPanel('#origin',"50%",0);
            Panel.tweenPanel('#detail',"50%",0); 
            $("#logo-shalom-responsive img").addClass('d-none');
            $(".res__card-pagar").removeClass('col-lg-4');
            $(".res__card-pagar").addClass('col-lg-12');

            $(".res__card-remision").removeClass('col-lg-4');
            $(".res__card-remision").addClass('col-lg-12');

            $(".res__card-remitente").removeClass('col-lg-4');
            $(".res__card-remitente").addClass('col-lg-12');
            
        }
        
        this.navBars()
        
        
        // if(  $(window).width() > 992 && $(window).width() <= 1230 ) {
        //     if( window.location.href == "https://pro.shalom.pe/#/envios/prelist" ) {
        //         $('.res__div-user').hide()
        //     } else {
        //         $('.res__div-user').show()
        //     }
        // }

    }
    
    static opemPanelTwo(){
        Panel.tweenPanel('#origin',"100%",0);
        Panel.tweenPanel('#detail',"0%",0);
    }

    static resetPanel(){
        Panel.tweenPanel('#origin',"100%",1);
        Panel.tweenPanel('#detail',"0%",1);
        $('.logo-shalom-responsive').show()
        $("#logo-shalom-responsive img").removeClass('d-none');
        $(".res__card-pagar").removeClass('col-lg-12');
        $(".res__card-pagar").addClass('col-lg-4');
        
        $(".res__card-remision").removeClass('col-lg-12');
        $(".res__card-remision").addClass('col-lg-4');

        $(".res__card-remitente").removeClass('col-lg-12');
        $(".res__card-remitente").addClass('col-lg-4');
    }
}