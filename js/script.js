Date.prototype.format = function(f) {
    if (!this.valueOf()) return " ";
 
    var weekName = ["일요일", "월요일", "화요일", "수요일", "목요일", "금요일", "토요일"];
    var d = this;
     
    return f.replace(/(Y|y|m|d|E|H|i|s|a\/p)/gi, function($1) {
        switch ($1) {
            case "Y": return d.getFullYear();
            case "y": return (d.getFullYear() % 1000).zf(2);
            case "m": return (d.getMonth() + 1).zf(2);
            case "d": return d.getDate().zf(2);
            case "E": return weekName[d.getDay()];
            case "H": return d.getHours().zf(2);
            case "h": return ((h = d.getHours() % 12) ? h : 12).zf(2);
            case "i": return d.getMinutes().zf(2);
            case "s": return d.getSeconds().zf(2);
            case "a/p": return d.getHours() < 12 ? "오전" : "오후";
            default: return $1;
        }
    });
};
 
String.prototype.string = function(len){var s = '', i = 0; while (i++ < len) { s += this; } return s;};
String.prototype.zf = function(len){return "0".string(len - this.length) + this;};
Number.prototype.zf = function(len){return this.toString().zf(len);};


function comma(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}
function round2(n){
    return Math.round(n*100)/100;
}
function get_profit(buy_price, sell_price){
    return (sell_price-buy_price)/buy_price*100;
}



function send_chatting(cont){
    ws.send(JSON.stringify({
        action:"send_chatting",
        chatting_data:{
            name:my_name,
            cont:cont,
        }
    }));
}

$(function(){
    $('[data-toggle="datepicker"]').datepicker({
        language: 'ko-KR'
    });

    $('#view_strategy_chat_input').keydown(function(e){
        if(e.which == 13){
            send_chatting($('#view_strategy_chat_input').val());
            $('#view_strategy_chat_input').val('');
        }
    })
});