/******* close the alert Box********* */
document.querySelectorAll(".close-alert").forEach(function(closeButton){
 
    closeButton.addEventListener('click', function(){
        closeButton.parentElement.style.display = "none";
    });
   
   });