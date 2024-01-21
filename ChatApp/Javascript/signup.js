const form = document.querySelector(".signup form"),
continueBtn = form.querySelector(".button input");
errorText = form.querySelector(".error-text");

form.onsubmit = (e)=>{
    e.preventDefault();//preventing form from submitting
}

continueBtn.onclick = ()=>{
    console.log('btn press')
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "php/signup.php", true);
    //xhr.onload is an event handler that gets executed when the HTTP request (AJAX request) completes successfully. It is part of the XMLHttpRequest event model.
    xhr.onload = ()=>{
      if(xhr.readyState === XMLHttpRequest.DONE){
          if(xhr.status === 200){
              let data = xhr.response;
              console.log(data);
              if(data === "success"){
                location.href="users.php";
              }else{
                errorText.textContent = data;
                errorText.style.display = "block";
              }
          }
      }
    }
    let formData = new FormData(form);
    xhr.send(formData);
}