function getCsrfName(){
    if(document.querySelector('input[name="csrf-name"]')){
        return document.querySelector('input[name="csrf-name"]').value;
    }
}
function getCsrfParam(){
    if(document.querySelector('input[name="csrf-param"]')){
        return document.querySelector('input[name="csrf-param"]').value;
    }
}