function openImage(){
var image = new Image();
image.src = "obr/svazana.html";
width = 1024;
height = 724;
window.open("" + image.src + "", "nove", "width=" + width + ",height=" + height + ",toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,left=300,top=100");
}