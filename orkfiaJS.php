<?php
header("content-type: application/x-javascript");
?>

sfHover = function() {
    var sfEls = document.getElementById("nav").getElementsByTagName("LI");
    for (var i=0; i<sfEls.length; i++) {
        sfEls[i].onmouseover=function() {
            this.className+=" sfhover";
        }
        sfEls[i].onmouseout=function() {
            this.className=this.className.replace(new RegExp(" sfhover\\b"), "");
        }
    }
}
if (window.attachEvent) window.attachEvent("onload", sfHover);


function twoDigits(number){
    return (number.toString().length==1)? "0"+number : number
}

function displayTime(){
    serverDate.setSeconds(serverDate.getSeconds()+1)
    var timeString=twoDigits(serverDate.getHours())+":"+twoDigits(serverDate.getMinutes())+":"+twoDigits(serverDate.getSeconds())
    clockWriteToDiv("ClockTime", timeString);
}

function simpleFindObj(name, inLayer) {
    return document[name] || (document.all && document.all[name])
        || (document.getElementById && document.getElementById(name))
        || (document.layers && inLayer && document.layers[inLayer].document[name]);
}

function clockWriteToDiv(divName, newValue){
    var divObject = simpleFindObj(divName);
    newValue = newValue;
    if (divObject && divObject.innerHTML) {
        divObject.innerHTML = newValue;
    }
    else if (divObject && divObject.document) {
        divObject.document.writeln(newValue);
        divObject.document.close();
    }
}

function clockStart(){
    setInterval("displayTime()", 1000)
}

var finished = false;
var i = 0;

function ike_slideshow(slideshow_id, jump) {
 slideshow=document.getElementById(slideshow_id);
 images=slideshow.getElementsByTagName("li");

 finished=false;
 for(i=0;i<images.length;i++){
  if(finished) break;
  if(images[i].style.display=="block") {
   if((i==0&&jump==-1)||(i+1==images.length&&jump==1)) break;
   images[i+jump].style.display="block";
   images[i].style.display="none";
   finished=true;
  }
 }
}

function ike_slideshow_init() {
 divs=document.getElementsByTagName("div");
 for(i=0;i<divs.length;i++){
  if(divs[i].className=="ike-slideshow"){
   lis=divs[i].getElementsByTagName("li");
   lis[0].style.display="block";
  }else if(divs[i].className=="ike-slideshow-loading"){
   divs[i].style.display="none";
  }
 }
}

if(window.addEventListener)
 window.addEventListener("load", ike_slideshow_init, false);
else if (window.attachEvent)
 window.attachEvent("onload", ike_slideshow_init);
else
 window.onload=ike_slideshow_init;